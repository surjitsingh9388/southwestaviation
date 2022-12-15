<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Database\Expression\QueryExpression;

class ReportComponent extends Component {
    
    //Call another component
    public $components = ['Auth', 'AirframeComponentPart', 'AtaCode', 'Plane'];

    public function customReportQuery($planeIds=null) 
    {
        $whereCond = '';
        //Display assigned aircraft only
        $airIds = $this->Plane->getSelectedAircraft();
        if($this->Auth->user('id') != 1) {
            if(!empty($airIds['airIdsArr']) && is_array($airIds['airIdsArr'])) {
                $whereCond = ['Planes.id IN'=>$airIds['airIdsArr']];
            } else {
                $whereCond = ['Planes.id IN'=>''];
            }
        }

        if(!empty($planeIds)) {
            $whereCond = ['Planes.id IN'=>$planeIds];
        }

        $planeModel = TableRegistry::get('Planes');
        $records = $planeModel->find()
                    ->where($whereCond)
                    ->select(['id', 'plane_code', 'airworthiness_date', 'hours', 'cycles', 'status'])
                    ->contain([ 
                        'AirframeComponents'=>[
                            'AirframeComponentTimes'=>[
                                'fields'=>[
                                    'AirframeComponentTimes.airframe_component_id', 
                                    'AirframeComponentTimes.log_date',
                                    'AirframeComponentTimes.hours', 
                                    'AirframeComponentTimes.cycles'
                                ], 
                                'sort' => [
                                    'AirframeComponentTimes.id' => 'DESC'
                                ]
                            ],
                            'AirframeComponentParts'=>[
                                'AirframeComponentLastCw'=>[
                                    'sort' => ['AirframeComponentLastCw.id' => 'DESC']
                                ]
                            ],
                            'Utilizations'=>[
                                'fields'=>[
                                    'Utilizations.id',
                                    'Utilizations.airframe_component_id',
                                    'Utilizations.hours',
                                    'Utilizations.cycles'
                                ]
                            ]
                        ]
                    ])
                    ->enableHydration(false)->toArray();
        return $records;
    }

    //Custom page records and dues count
    public function customReportWithCount($records, $type=null)
    {
        //Sort array in alphanumeric order
        usort($records, function($a, $b) {
            //return strcasecmp($a['plane_code'], $b['plane_code']);
            return strnatcasecmp($a['plane_code'], $b['plane_code']);
        });

        $allResults = array();
        $results = array();
        $j = 0;
        $k = 0;
        $t = 0;
        $p = 0;
        $alt = 0;
        $pastDue = 0;
        $comingDue = 0;
        $toleranceDue = 0;
        $due10Plus = 0;
        $alertDue = 0;
        $airPastDue = array();
        $airTolrDue = array();
        foreach ($records as $key => $value) {
            
            if(!empty($type) && ($type == 'customPage' || $type == 'flightLog')) {
                $results[$key]['plane']['plane_id'] = $value['id'];
                $results[$key]['plane']['plane_code'] = $value['plane_code'];
                $results[$key]['plane']['airworthiness_date'] = $value['airworthiness_date'];
                $results[$key]['plane']['plane_hours'] = $value['hours'];
                $results[$key]['plane']['plane_cycles'] = $value['cycles'];
                //status 
                $sts = explode("_", $value['status']);
                if(count($sts)>1) {
                    $status = $sts[0].' '.$sts[1].' '.$sts[2];
                } else {
                    $status = $value['status'];
                }
                $results[$key]['plane']['status'] = $status;
            }

            $aj = 0;
            $at = 0;
            $ac = 0;
            foreach ($value['airframe_components'] as $key2 => $value2) {
                //Utilization
                $utilHours = !empty($value2['utilizations'][0]['hours']) ? $value2['utilizations'][0]['hours'] : 1;
                $utilCycles = !empty($value2['utilizations'][0]['cycles']) ? $value2['utilizations'][0]['cycles'] : 1;

                //Components time
                $compHours = !empty($value2['airframe_component_times'][0]['hours']) ? $value2['airframe_component_times'][0]['hours'] : 0;
                $compCycles = !empty($value2['airframe_component_times'][0]['cycles']) ? $value2['airframe_component_times'][0]['cycles'] : 0;

                //Reported date, hours and cycles
                if($value2['log_book'] == 'Airframe' && !empty($type) && $type == 'customPage') {
                    $results[$key]['plane']['reported_date'] = !empty($value2['airframe_component_times'][0]['log_date']) ? $value2['airframe_component_times'][0]['log_date'] : '';
                    $results[$key]['plane']['hours'] = $compHours;
                    $results[$key]['plane']['cycles'] = $compCycles;
                }

                foreach ($value2['airframe_component_parts'] as $key3 => $value3) {
                    //Get data correction and nextdue calculation
                    $mos = $hrs = $afl = $msc = '';
                    if(!empty($value3['airframe_component_last_cw'][0])) {
                        $getRes = $this->AirframeComponentPart->postDataProcess($value3['airframe_component_last_cw'][0]);
                        $mos = $getRes['mos'];
                        $hrs = $getRes['hrs'];
                        $afl = $getRes['afl'];
                        $msc = $getRes['msc'];
                    }
                    
                    //Start Remaining
                    $remData = $this->getRemaining($mos);
                    $maintRemMos  = $remData['maintRemMos'];
                    $maintRemDays = $remData['maintRemDays'];
                    $totalRemD    = $remData['totalRemD'];
                    
                    $remHrs = !empty($hrs) ? $hrs - $compHours : 0;
                    $remAfl = !empty($afl) ? $afl - $compCycles : 0;
                    //End Remaining

                    //Tolerance
                    $tolrMos  = !empty($value3['airframe_component_last_cw'][0]['tolerance_mos']) ? $value3['airframe_component_last_cw'][0]['tolerance_mos'] : 0;
                    $tolrDays = !empty($value3['airframe_component_last_cw'][0]['tolerance_days']) ? $value3['airframe_component_last_cw'][0]['tolerance_days'] : 0;
                    $tolrHrs  = !empty($value3['airframe_component_last_cw'][0]['tolerance_hrs']) ? $value3['airframe_component_last_cw'][0]['tolerance_hrs'] : 0;
                    $tolrAfl  = !empty($value3['airframe_component_last_cw'][0]['tolerance_afl']) ? $value3['airframe_component_last_cw'][0]['tolerance_afl'] : 0;
                    $totalTlrD = $tolrMos * 30 + $tolrDays;

                    //Alert
                    $isResThres = !empty($value3['airframe_component_last_cw'][0]['is_recThres']) ? $value3['airframe_component_last_cw'][0]['is_recThres'] : '';

                    //Change alert box value
                    if(!empty($isResThres) && $isResThres == 'recurring') {
                        $altDays = ($value3['airframe_component_last_cw'][0]['recurring_mos'] > 24) ? 60 : 30;
                        $altHrs = ($value3['airframe_component_last_cw'][0]['recurring_hrs'] > 999) ? 200 : 50;
                        $altAfl = ($value3['airframe_component_last_cw'][0]['recurring_afl'] > 999) ? 200 : 25;
                    } elseif(!empty($isResThres) && $isResThres == 'threshold') {
                        $altDays = ($value3['airframe_component_last_cw'][0]['threshold_mos'] > 24) ? 60 : 30;
                        $altHrs = ($value3['airframe_component_last_cw'][0]['threshold_hrs'] > 999) ? 200 : 50;
                        $altAfl = ($value3['airframe_component_last_cw'][0]['threshold_afl'] > 999) ? 200 : 25;
                    }

                    //Check past dues
                    if((((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD + $totalTlrD) < 0) 
                        || (!empty($remHrs) && $remHrs < 0 && ($remHrs + $tolrHrs) < 0)
                        || (!empty($remAfl) && $remAfl < 0 && ($remAfl + $tolrAfl) < 0)) && !empty($type) && ($type=='customPage' || $type=='flightPage')) {

                        //Next due                        
                        $results[$key]['extDetails'][$key2][$key3]['hrs'] = $hrs;
                        $results[$key]['extDetails'][$key2][$key3]['afl'] = $afl;
                        $results[$key]['extDetails'][$key2][$key3]['mos'] = !empty($mos) ? strtoupper(date('m/d/Y', strtotime($mos))) : '';
                        $results[$key]['extDetails'][$key2][$key3]['msc'] = $msc;                    
                        //End next due

                        //Part details
                        $results[$key]['extDetails'][$key2][$key3]['id'] = $value3['id'];
                        $results[$key]['extDetails'][$key2][$key3]['ata_code'] = $this->AtaCode->ataCode($value3['ata_code']);
                        $results[$key]['extDetails'][$key2][$key3]['description'] = $value3['description'];
                        $results[$key]['extDetails'][$key2][$key3]['log_book'] = $value2['log_book'];

                        //Start lastcw
                        $results[$key]['extDetails'][$key2][$key3]['lastCwHrs'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_hrs']) ? $value3['airframe_component_last_cw'][0]['last_cw_hrs'] : '';
                        
                        $results[$key]['extDetails'][$key2][$key3]['lastCwAfl'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_afl']) ? $value3['airframe_component_last_cw'][0]['last_cw_afl'] : '';
                        
                        $results[$key]['extDetails'][$key2][$key3]['lastCwMos'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_date']) ? date('d M Y', strtotime($value3['airframe_component_last_cw'][0]['last_cw_date'])) : '';
                        
                        $results[$key]['extDetails'][$key2][$key3]['lastCwMsc'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_msc']) ? $value3['airframe_component_last_cw'][0]['last_cw_msc'] : '';
                        //End lastcw

                        //Start required Freq
                        $results[$key]['extDetails'][$key2][$key3]['reqFreqHrs'] = !empty($row['required_frequency_hrs']) ? $row['required_frequency_hrs'] : '&nbsp;';
                        
                        $results[$key]['extDetails'][$key2][$key3]['reqFreqAfl'] = !empty($row['required_frequency_afl']) ? $row['required_frequency_afl'] : '&nbsp;';
                        
                        $results[$key]['extDetails'][$key2][$key3]['reqFreqMos'] = !empty($row['required_frequency_mos']) ? $row['required_frequency_mos'] : '&nbsp;';
                        
                        $results[$key]['extDetails'][$key2][$key3]['reqFreqDays'] = !empty($row['required_frequency_days']) ? $row['required_frequency_days'] : '&nbsp;';
                        //End required freq
                        
                        //Start Remaining           
                        $results[$key]['extDetails'][$key2][$key3]['remMos']  = $maintRemMos;
                        $results[$key]['extDetails'][$key2][$key3]['remDays'] = $maintRemDays;
                        $results[$key]['extDetails'][$key2][$key3]['totalRemD'] = $totalRemD;
                        $results[$key]['extDetails'][$key2][$key3]['remHrs']  = $remHrs;
                        $results[$key]['extDetails'][$key2][$key3]['remAfl']  = $remAfl;
                        //End Remaining

                        /*************************** Color *************************************/
                        //Days color
                        $results[$key]['extDetails'][$key2][$key3]['altDColor'] = 'color:#036a03';
                        if((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) < 0) {
                            $results[$key]['extDetails'][$key2][$key3]['altDColor'] = 'color:#f40808';

                        } elseif((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) > 0) { 
                            $results[$key]['extDetails'][$key2][$key3]['altDColor'] = 'color:#d35400';
                            
                        } elseif(!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays > $totalRemD) {
                            $results[$key]['extDetails'][$key2][$key3]['altDColor'] = 'color:#f39c12';

                        } elseif(!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays < $totalRemD) {
                           $results[$key]['extDetails'][$key2][$key3]['altDColor'] = 'color:#036a03';
                        }

                        //Hours color
                        $results[$key]['extDetails'][$key2][$key3]['altHColor'] = 'color:#036a03';
                        if(!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) < 0) {
                            $results[$key]['extDetails'][$key2][$key3]['altHColor'] = 'color:#f40808';

                        } elseif(!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) > 0) {
                            $results[$key]['extDetails'][$key2][$key3]['altHColor'] = 'color:#d35400';

                        } elseif(!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs > $remHrs) {
                            $results[$key]['extDetails'][$key2][$key3]['altHColor'] = 'color:#f39c12';

                        } elseif (!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs < $remHrs) {
                           $results[$key]['extDetails'][$key2][$key3]['altHColor'] = 'color:#036a03';
                        }

                        //Cycle color
                        $results[$key]['extDetails'][$key2][$key3]['altAColor'] = 'color:#036a03';
                        if(!empty($altAfl) && $altAfl < 0 && ($altAfl+$tolrAfl) < 0) {
                            $results[$key]['extDetails'][$key2][$key3]['altAColor'] = 'color:#f40808';

                        } elseif(!empty($altAfl) && $altAfl < 0 && ($altAfl+$tolrAfl) > 0) {
                            $results[$key]['extDetails'][$key2][$key3]['altAColor'] = 'color:#d35400';

                        } elseif(!empty($altAfl) && !empty($remAfl) && $altAfl > 0 && $altAfl > $remAfl) {
                            $results[$key]['extDetails'][$key2][$key3]['altAColor'] = 'color:#f39c12';

                        } elseif (!empty($altAfl) && !empty($remAfl) && $altAfl > 0 && $altAfl < $remAfl) {
                           $results[$key]['extDetails'][$key2][$key3]['altAColor'] = 'color:#036a03';
                        }
                        //End Alert
                        /*************************** Color *************************************/
                            
                        //Main page display
                        if(!empty($mos)) {
                            $results[$key]['plane']['nextDue'] = strtoupper(date('m/d/Y', strtotime($mos)));
                        } elseif (!empty($hrs)) {
                            $results[$key]['plane']['nextDue'] = 'Hours: '.$hrs;
                        } elseif (!empty($afl)) {
                            $results[$key]['plane']['nextDue'] = 'Cycles: '.$afl;
                        }
                        
                        $results[$key]['extDetails'][$key2] = array_values($results[$key]['extDetails'][$key2]);

                    } elseif ((((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) > 0) 
                            || (!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) > 0) 
                            || (!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) > 0)) && !empty($type) && $type == 'flightPage') {
                        /** Tolerance Due **/
                        //Next due                        
                        $results[$key]['extTolDetails'][$key2][$key3]['hrs'] = $hrs;
                        $results[$key]['extTolDetails'][$key2][$key3]['afl'] = $afl;
                        $results[$key]['extTolDetails'][$key2][$key3]['mos'] = !empty($mos) ? strtoupper(date('m/d/Y', strtotime($mos))) : '';
                        $results[$key]['extTolDetails'][$key2][$key3]['msc'] = $msc;                    
                        //End next due

                        //Part details
                        $results[$key]['extTolDetails'][$key2][$key3]['id'] = $value3['id'];
                        $results[$key]['extTolDetails'][$key2][$key3]['ata_code'] = $this->AtaCode->ataCode($value3['ata_code']);
                        $results[$key]['extTolDetails'][$key2][$key3]['description'] = $value3['description'];
                        $results[$key]['extTolDetails'][$key2][$key3]['log_book'] = $value2['log_book'];

                        //Start lastcw
                        $results[$key]['extTolDetails'][$key2][$key3]['lastCwHrs'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_hrs']) ? $value3['airframe_component_last_cw'][0]['last_cw_hrs'] : '';
                        
                        $results[$key]['extTolDetails'][$key2][$key3]['lastCwAfl'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_afl']) ? $value3['airframe_component_last_cw'][0]['last_cw_afl'] : '';
                        
                        $results[$key]['extTolDetails'][$key2][$key3]['lastCwMos'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_date']) ? date('d M Y', strtotime($value3['airframe_component_last_cw'][0]['last_cw_date'])) : '';
                        
                        $results[$key]['extTolDetails'][$key2][$key3]['lastCwMsc'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_msc']) ? $value3['airframe_component_last_cw'][0]['last_cw_msc'] : '';
                        //End lastcw

                        //Start required Freq
                        $results[$key]['extTolDetails'][$key2][$key3]['reqFreqHrs'] = !empty($row['required_frequency_hrs']) ? $row['required_frequency_hrs'] : '&nbsp;';
                        
                        $results[$key]['extTolDetails'][$key2][$key3]['reqFreqAfl'] = !empty($row['required_frequency_afl']) ? $row['required_frequency_afl'] : '&nbsp;';
                        
                        $results[$key]['extTolDetails'][$key2][$key3]['reqFreqMos'] = !empty($row['required_frequency_mos']) ? $row['required_frequency_mos'] : '&nbsp;';
                        
                        $results[$key]['extTolDetails'][$key2][$key3]['reqFreqDays'] = !empty($row['required_frequency_days']) ? $row['required_frequency_days'] : '&nbsp;';
                        //End required freq
                        
                        //Start Remaining           
                        $results[$key]['extTolDetails'][$key2][$key3]['remMos']  = $maintRemMos;
                        $results[$key]['extTolDetails'][$key2][$key3]['remDays'] = $maintRemDays;
                        $results[$key]['extTolDetails'][$key2][$key3]['totalRemD'] = $totalRemD;
                        $results[$key]['extTolDetails'][$key2][$key3]['remHrs']  = $remHrs;
                        $results[$key]['extTolDetails'][$key2][$key3]['remAfl']  = $remAfl;
                        //End Remaining

                        /*************************** Color *************************************/
                        //Days color
                        $results[$key]['extTolDetails'][$key2][$key3]['altDColor'] = 'color:#036a03';
                        if((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) < 0) {
                            $results[$key]['extTolDetails'][$key2][$key3]['altDColor'] = 'color:#f40808';

                        } elseif((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) > 0) { 
                            $results[$key]['extTolDetails'][$key2][$key3]['altDColor'] = 'color:#d35400';
                            
                        } elseif(!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays > $totalRemD) {
                            $results[$key]['extTolDetails'][$key2][$key3]['altDColor'] = 'color:#f39c12';

                        } elseif(!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays < $totalRemD) {
                           $results[$key]['extTolDetails'][$key2][$key3]['altDColor'] = 'color:#036a03';
                        }

                        //Hours color
                        $results[$key]['extTolDetails'][$key2][$key3]['altHColor'] = 'color:#036a03';
                        if(!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) < 0) {
                            $results[$key]['extTolDetails'][$key2][$key3]['altHColor'] = 'color:#f40808';

                        } elseif(!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) > 0) {
                            $results[$key]['extTolDetails'][$key2][$key3]['altHColor'] = 'color:#d35400';

                        } elseif(!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs > $remHrs) {
                            $results[$key]['extTolDetails'][$key2][$key3]['altHColor'] = 'color:#f39c12';

                        } elseif (!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs < $remHrs) {
                           $results[$key]['extTolDetails'][$key2][$key3]['altHColor'] = 'color:#036a03';
                        }

                        //Cycle color
                        $results[$key]['extTolDetails'][$key2][$key3]['altAColor'] = 'color:#036a03';
                        if(!empty($altAfl) && $altAfl < 0 && ($altAfl+$tolrAfl) < 0) {
                            $results[$key]['extTolDetails'][$key2][$key3]['altAColor'] = 'color:#f40808';

                        } elseif(!empty($altAfl) && $altAfl < 0 && ($altAfl+$tolrAfl) > 0) {
                            $results[$key]['extTolDetails'][$key2][$key3]['altAColor'] = 'color:#d35400';

                        } elseif(!empty($altAfl) && !empty($remAfl) && $altAfl > 0 && $altAfl > $remAfl) {
                            $results[$key]['extTolDetails'][$key2][$key3]['altAColor'] = 'color:#f39c12';

                        } elseif (!empty($altAfl) && !empty($remAfl) && $altAfl > 0 && $altAfl < $remAfl) {
                           $results[$key]['extTolDetails'][$key2][$key3]['altAColor'] = 'color:#036a03';
                        }
                        //End Alert
                        /*************************** Color *************************************/
                            
                        //Main page display
                        if(!empty($mos)) {
                            $results[$key]['plane']['nextDue'] = strtoupper(date('m/d/Y', strtotime($mos)));
                        } elseif (!empty($hrs)) {
                            $results[$key]['plane']['nextDue'] = 'Hours: '.$hrs;
                        } elseif (!empty($afl)) {
                            $results[$key]['plane']['nextDue'] = 'Cycles: '.$afl;
                        }
                        $results[$key]['extTolDetails'][$key2] = array_values($results[$key]['extTolDetails'][$key2]);

                    } elseif ((((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD > 10) 
                        || (!empty($remHrs) && !empty($utilHours) && $remHrs/$utilHours > 10) 
                        || (!empty($remAfl) && !empty($utilCycles) && $remAfl/$utilCycles > 10)) && !empty($type) && $type == 'flightPage') {

                        /** Current Due **/
                        //Next due                        
                        $results[$key]['extCurrentDetails'][$key2][$key3]['hrs'] = $hrs;
                        $results[$key]['extCurrentDetails'][$key2][$key3]['afl'] = $afl;
                        $results[$key]['extCurrentDetails'][$key2][$key3]['mos'] = !empty($mos) ? strtoupper(date('m/d/Y', strtotime($mos))) : '';
                        $results[$key]['extCurrentDetails'][$key2][$key3]['msc'] = $msc;                    
                        //End next due

                        //Part details
                        $results[$key]['extCurrentDetails'][$key2][$key3]['id'] = $value3['id'];
                        $results[$key]['extCurrentDetails'][$key2][$key3]['ata_code'] = $this->AtaCode->ataCode($value3['ata_code']);
                        $results[$key]['extCurrentDetails'][$key2][$key3]['description'] = $value3['description'];
                        $results[$key]['extCurrentDetails'][$key2][$key3]['log_book'] = $value2['log_book'];

                        //Start lastcw
                        $results[$key]['extCurrentDetails'][$key2][$key3]['lastCwHrs'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_hrs']) ? $value3['airframe_component_last_cw'][0]['last_cw_hrs'] : '';
                        
                        $results[$key]['extCurrentDetails'][$key2][$key3]['lastCwAfl'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_afl']) ? $value3['airframe_component_last_cw'][0]['last_cw_afl'] : '';
                        
                        $results[$key]['extCurrentDetails'][$key2][$key3]['lastCwMos'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_date']) ? date('d M Y', strtotime($value3['airframe_component_last_cw'][0]['last_cw_date'])) : '';
                        
                        $results[$key]['extCurrentDetails'][$key2][$key3]['lastCwMsc'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_msc']) ? $value3['airframe_component_last_cw'][0]['last_cw_msc'] : '';
                        //End lastcw

                        //Start required Freq
                        $results[$key]['extCurrentDetails'][$key2][$key3]['reqFreqHrs'] = !empty($row['required_frequency_hrs']) ? $row['required_frequency_hrs'] : '&nbsp;';
                        
                        $results[$key]['extCurrentDetails'][$key2][$key3]['reqFreqAfl'] = !empty($row['required_frequency_afl']) ? $row['required_frequency_afl'] : '&nbsp;';
                        
                        $results[$key]['extCurrentDetails'][$key2][$key3]['reqFreqMos'] = !empty($row['required_frequency_mos']) ? $row['required_frequency_mos'] : '&nbsp;';
                        
                        $results[$key]['extCurrentDetails'][$key2][$key3]['reqFreqDays'] = !empty($row['required_frequency_days']) ? $row['required_frequency_days'] : '&nbsp;';
                        //End required freq
                        
                        //Start Remaining           
                        $results[$key]['extCurrentDetails'][$key2][$key3]['remMos']  = $maintRemMos;
                        $results[$key]['extCurrentDetails'][$key2][$key3]['remDays'] = $maintRemDays;
                        $results[$key]['extCurrentDetails'][$key2][$key3]['totalRemD'] = $totalRemD;
                        $results[$key]['extCurrentDetails'][$key2][$key3]['remHrs']  = $remHrs;
                        $results[$key]['extCurrentDetails'][$key2][$key3]['remAfl']  = $remAfl;
                        //End Remaining

                        /*************************** Color *************************************/
                        //Days color
                        $results[$key]['extCurrentDetails'][$key2][$key3]['altDVal'] = '';
                        $results[$key]['extCurrentDetails'][$key2][$key3]['altDColor'] = 'color:#036a03';
                        if((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) < 0) {
                            $results[$key]['extCurrentDetails'][$key2][$key3]['altDColor'] = 'color:#f40808';

                        } elseif((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) > 0) { 
                            $results[$key]['extCurrentDetails'][$key2][$key3]['altDColor'] = 'color:#d35400';
                            
                        } elseif(!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays > $totalRemD) {
                            $results[$key]['extCurrentDetails'][$key2][$key3]['altDColor'] = 'color:#f39c12';
                            $results[$key]['extCurrentDetails'][$key2][$key3]['altDVal'] = 'alert';

                        } elseif(!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays < $totalRemD) {
                           $results[$key]['extCurrentDetails'][$key2][$key3]['altDColor'] = 'color:#036a03';
                        }

                        //Hours color
                        $results[$key]['extCurrentDetails'][$key2][$key3]['altHVal'] = '';
                        $results[$key]['extCurrentDetails'][$key2][$key3]['altHColor'] = 'color:#036a03';
                        if(!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) < 0) {
                            $results[$key]['extCurrentDetails'][$key2][$key3]['altHColor'] = 'color:#f40808';

                        } elseif(!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) > 0) {
                            $results[$key]['extCurrentDetails'][$key2][$key3]['altHColor'] = 'color:#d35400';

                        } elseif(!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs > $remHrs) {
                            $results[$key]['extCurrentDetails'][$key2][$key3]['altHColor'] = 'color:#f39c12';
                            $results[$key]['extCurrentDetails'][$key2][$key3]['altHVal'] = 'alert';

                        } elseif (!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs < $remHrs) {
                           $results[$key]['extCurrentDetails'][$key2][$key3]['altHColor'] = 'color:#036a03';
                        }

                        //Cycle color
                        $results[$key]['extCurrentDetails'][$key2][$key3]['altAVal'] = '';
                        $results[$key]['extCurrentDetails'][$key2][$key3]['altAColor'] = 'color:#036a03';
                        if(!empty($altAfl) && $altAfl < 0 && ($altAfl+$tolrAfl) < 0) {
                            $results[$key]['extCurrentDetails'][$key2][$key3]['altAColor'] = 'color:#f40808';

                        } elseif(!empty($altAfl) && $altAfl < 0 && ($altAfl+$tolrAfl) > 0) {
                            $results[$key]['extCurrentDetails'][$key2][$key3]['altAColor'] = 'color:#d35400';

                        } elseif(!empty($altAfl) && !empty($remAfl) && $altAfl > 0 && $altAfl > $remAfl) {
                            $results[$key]['extCurrentDetails'][$key2][$key3]['altAColor'] = 'color:#f39c12';
                            $results[$key]['extCurrentDetails'][$key2][$key3]['altAVal'] = 'alert';

                        } elseif (!empty($altAfl) && !empty($remAfl) && $altAfl > 0 && $altAfl < $remAfl) {
                           $results[$key]['extCurrentDetails'][$key2][$key3]['altAColor'] = 'color:#036a03';
                        }
                        //End Alert
                        /*************************** Color *************************************/
                            
                        //Main page display
                        if(!empty($mos)) {
                            $results[$key]['plane']['nextDue'] = strtoupper(date('m/d/Y', strtotime($mos)));
                        } elseif (!empty($hrs)) {
                            $results[$key]['plane']['nextDue'] = 'Hours: '.$hrs;
                        } elseif (!empty($afl)) {
                            $results[$key]['plane']['nextDue'] = 'Cycles: '.$afl;
                        }
                        $results[$key]['extCurrentDetails'][$key2] = array_values($results[$key]['extCurrentDetails'][$key2]);
                    }

                    $results[$key]['extDetails'] = !empty($results[$key]['extDetails'])?array_values($results[$key]['extDetails']):[];
                    $results[$key]['extTolDetails'] = !empty($results[$key]['extTolDetails'])?array_values($results[$key]['extTolDetails']):[];
                    $results[$key]['extCurrentDetails'] = !empty($results[$key]['extCurrentDetails'])?array_values($results[$key]['extCurrentDetails']):[];

                    /*$altDays = !empty($value3['airframe_component_last_cw'][0]['alert_days']) ? $value3['airframe_component_last_cw'][0]['alert_days']:30;
                    $altHrs  = !empty($value3['airframe_component_last_cw'][0]['alert_hrs']) ? $value3['airframe_component_last_cw'][0]['alert_hrs']:50;
                    $altAfl  = !empty($value3['airframe_component_last_cw'][0]['alert_afl']) ? $value3['airframe_component_last_cw'][0]['alert_afl']:25;*/

                    if((!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays > $totalRemD) || (!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs > $remHrs) || (!empty($altAfl) && !empty($remAfl) && $altAfl > 0 && $altAfl > $remAfl)) {
                        $alt++;
                        $alertDue = $alt;

                        /** Alert Due Due **/
                        if(!empty($type) && $type == 'flightPage') {
                            //Next due                        
                            $results[$key]['extAlertDetails'][$key2][$key3]['hrs'] = $hrs;
                            $results[$key]['extAlertDetails'][$key2][$key3]['afl'] = $afl;
                            $results[$key]['extAlertDetails'][$key2][$key3]['mos'] = !empty($mos) ? strtoupper(date('m/d/Y', strtotime($mos))) : '';
                            $results[$key]['extAlertDetails'][$key2][$key3]['msc'] = $msc;                    
                            //End next due

                            //Part details
                            $results[$key]['extAlertDetails'][$key2][$key3]['id'] = $value3['id'];
                            $results[$key]['extAlertDetails'][$key2][$key3]['ata_code'] = $this->AtaCode->ataCode($value3['ata_code']);
                            $results[$key]['extAlertDetails'][$key2][$key3]['description'] = $value3['description'];
                            $results[$key]['extAlertDetails'][$key2][$key3]['log_book'] = $value2['log_book'];

                            //Start lastcw
                            $results[$key]['extAlertDetails'][$key2][$key3]['lastCwHrs'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_hrs']) ? $value3['airframe_component_last_cw'][0]['last_cw_hrs'] : '';
                            
                            $results[$key]['extAlertDetails'][$key2][$key3]['lastCwAfl'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_afl']) ? $value3['airframe_component_last_cw'][0]['last_cw_afl'] : '';
                            
                            $results[$key]['extAlertDetails'][$key2][$key3]['lastCwMos'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_date']) ? date('d M Y', strtotime($value3['airframe_component_last_cw'][0]['last_cw_date'])) : '';
                            
                            $results[$key]['extAlertDetails'][$key2][$key3]['lastCwMsc'] = !empty($value3['airframe_component_last_cw'][0]['last_cw_msc']) ? $value3['airframe_component_last_cw'][0]['last_cw_msc'] : '';
                            //End lastcw

                            //Start required Freq
                            $results[$key]['extAlertDetails'][$key2][$key3]['reqFreqHrs'] = !empty($row['required_frequency_hrs']) ? $row['required_frequency_hrs'] : '&nbsp;';
                            
                            $results[$key]['extAlertDetails'][$key2][$key3]['reqFreqAfl'] = !empty($row['required_frequency_afl']) ? $row['required_frequency_afl'] : '&nbsp;';
                            
                            $results[$key]['extAlertDetails'][$key2][$key3]['reqFreqMos'] = !empty($row['required_frequency_mos']) ? $row['required_frequency_mos'] : '&nbsp;';
                            
                            $results[$key]['extAlertDetails'][$key2][$key3]['reqFreqDays'] = !empty($row['required_frequency_days']) ? $row['required_frequency_days'] : '&nbsp;';
                            //End required freq
                            
                            //Start Remaining           
                            $results[$key]['extAlertDetails'][$key2][$key3]['remMos']  = $maintRemMos;
                            $results[$key]['extAlertDetails'][$key2][$key3]['remDays'] = $maintRemDays;
                            $results[$key]['extAlertDetails'][$key2][$key3]['totalRemD'] = $totalRemD;
                            $results[$key]['extAlertDetails'][$key2][$key3]['remHrs']  = $remHrs;
                            $results[$key]['extAlertDetails'][$key2][$key3]['remAfl']  = $remAfl;
                            //End Remaining

                            /*************************** Color *************************************/
                            //Days color
                            $results[$key]['extAlertDetails'][$key2][$key3]['altDColor'] = 'color:#036a03';
                            if((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) < 0) {
                                $results[$key]['extAlertDetails'][$key2][$key3]['altDColor'] = 'color:#f40808';

                            } elseif((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) > 0) { 
                                $results[$key]['extAlertDetails'][$key2][$key3]['altDColor'] = 'color:#d35400';
                                
                            } elseif(!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays > $totalRemD) {
                                $results[$key]['extAlertDetails'][$key2][$key3]['altDColor'] = 'color:#f39c12';

                            } elseif(!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays < $totalRemD) {
                               $results[$key]['extAlertDetails'][$key2][$key3]['altDColor'] = 'color:#036a03';
                            }

                            //Hours color
                            $results[$key]['extAlertDetails'][$key2][$key3]['altHColor'] = 'color:#036a03';
                            if(!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) < 0) {
                                $results[$key]['extAlertDetails'][$key2][$key3]['altHColor'] = 'color:#f40808';

                            } elseif(!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) > 0) {
                                $results[$key]['extAlertDetails'][$key2][$key3]['altHColor'] = 'color:#d35400';

                            } elseif(!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs > $remHrs) {
                                $results[$key]['extAlertDetails'][$key2][$key3]['altHColor'] = 'color:#f39c12';

                            } elseif (!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs < $remHrs) {
                               $results[$key]['extAlertDetails'][$key2][$key3]['altHColor'] = 'color:#036a03';
                            }

                            //Cycle color
                            $results[$key]['extAlertDetails'][$key2][$key3]['altAColor'] = 'color:#036a03';
                            if(!empty($altAfl) && $altAfl < 0 && ($altAfl+$tolrAfl) < 0) {
                                $results[$key]['extAlertDetails'][$key2][$key3]['altAColor'] = 'color:#f40808';

                            } elseif(!empty($altAfl) && $altAfl < 0 && ($altAfl+$tolrAfl) > 0) {
                                $results[$key]['extAlertDetails'][$key2][$key3]['altAColor'] = 'color:#d35400';

                            } elseif(!empty($altAfl) && !empty($remAfl) && $altAfl > 0 && $altAfl > $remAfl) {
                                $results[$key]['extAlertDetails'][$key2][$key3]['altAColor'] = 'color:#f39c12';

                            } elseif (!empty($altAfl) && !empty($remAfl) && $altAfl > 0 && $altAfl < $remAfl) {
                               $results[$key]['extAlertDetails'][$key2][$key3]['altAColor'] = 'color:#036a03';
                            }
                            //End Alert
                            /*************************** Color *************************************/
                                
                            //Main page display
                            if(!empty($mos)) {
                                $results[$key]['plane']['nextDue'] = strtoupper(date('m/d/Y', strtotime($mos)));
                            } elseif (!empty($hrs)) {
                                $results[$key]['plane']['nextDue'] = 'Hours: '.$hrs;
                            } elseif (!empty($afl)) {
                                $results[$key]['plane']['nextDue'] = 'Cycles: '.$afl;
                            }
                            
                            $results[$key]['extAlertDetails'][$key2] = array_values($results[$key]['extAlertDetails'][$key2]);
                        }
                    }
                    $results[$key]['extAlertDetails'] = !empty($results[$key]['extAlertDetails'])?array_values($results[$key]['extAlertDetails']):[];
                    //End Alert

                    //Get past dues
                    if (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) < 0) 
                        || (!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) < 0) 
                        || (!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) < 0)) {
                        $j++;
                        $aj++;
                        $pastDue = $j;
                        $results[$key]['airPastDue'] = $aj;
                    } elseif (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) > 0) 
                        || (!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) > 0) 
                        || (!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) > 0)) {
                        $t++;
                        $at++;
                        $toleranceDue = $t;
                        $results[$key]['airTolrDue'] = $at;
                    } elseif (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD > 10) 
                        || (!empty($remHrs) && !empty($utilHours) && $remHrs/$utilHours > 10) 
                        || (!empty($remAfl) && !empty($utilCycles) && $remAfl/$utilCycles > 10)) {
                        $p++;
                        $ac++;
                        $due10Plus = $p;
                        $results[$key]['airCurtDue'] = $ac;
                    } elseif (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD >= 0 && $totalRemD <= 10) 
                        || (!empty($remHrs) && !empty($utilHours) && ($remHrs/$utilHours >= 0 && $remHrs/$utilHours <= 10)) 
                        || (!empty($remAfl) && !empty($utilCycles) && ($remAfl/$utilCycles >= 0 && $remAfl/$utilCycles <= 10))) {
                        $k++;
                        $comingDue = $k;
                    } 
                }
            }
        }

        $allResults['results']      = $results;
        $allResults['pastDue']      = $pastDue;
        $allResults['comingDue']    = $comingDue;
        $allResults['alertDue']     = $alertDue;
        $allResults['toleranceDue'] = $toleranceDue;
        //$allResults['airPastDue']   = $airPastDue;
        //$allResults['airTolrDue']   = $airTolrDue;

        return $allResults;
    }

    //Get maintenance remaining months and days
    public function getRemaining($mos=null, $fdt=null) 
    {
        $maintRemMos  = '';
        $maintRemDays = '';
        $totalRemD    = '';
        $remainingArr = array();
        if(!empty($mos)) {
            $mos = date('m/d/Y', strtotime($mos));
            $date1 = new \DateTime($mos);
            $date2 = !empty($fdt) ? new \DateTime($fdt) : new \DateTime(date('m/d/Y'));
            $interval = date_diff($date1, $date2);
            $year = $interval->format('%y');
            $maintRemMos = $interval->format('%m') + $year * 12;
            $maintRemDays = $interval->format('%d');

            if($date1 < $date2) {
                $maintRemMos = !empty($maintRemMos) ? -$maintRemMos : 0;
                $maintRemDays = !empty($maintRemDays) ? -$maintRemDays : 0;
            }
            $totalRemD = $maintRemMos * 30 + $maintRemDays; 
        }

        $remainingArr['maintRemMos']  = $maintRemMos;
        $remainingArr['maintRemDays'] = $maintRemDays;
        $remainingArr['totalRemD']    = $totalRemD;

        return $remainingArr;
    }

    //Associate query condition
    public function associateQuery()
    {
        return $compAssociates = [
                        'fields'=>[
                            'AirframeComponents.id',
                            'AirframeComponents.plane_id',
                            'AirframeComponents.log_book',
                            'AirframeComponents.position',
                            'AirframeComponents.description',
                            'AirframeComponents.serial_no'
                        ],
                        'AirframeComponentTimes'=>[
                            'fields'=>[
                                'AirframeComponentTimes.id',
                                'AirframeComponentTimes.airframe_component_id',
                                'AirframeComponentTimes.log_date',
                                'AirframeComponentTimes.hours',
                                'AirframeComponentTimes.cycles' 
                            ],
                            'sort'=>['AirframeComponentTimes.id'=>'DESC']
                        ],
                        'Utilizations'=>[
                            'fields'=>[
                                'Utilizations.id',
                                'Utilizations.airframe_component_id',
                                'Utilizations.hours',
                                'Utilizations.cycles'
                            ]
                        ]
                    ];
    }

    public function airDues($airId)
    {
        $records = $this->customReportQuery($airId);
        $allResults = $this->customReportWithCount($records, 'flightPage');
        
        $results = array();
        $extHtml = '';
        $airitem = '';
        $airDueAt = '';
        $airtogo = '';
        foreach ($allResults['results'] as $key=>$row) {
            //Check if pastdue or toleranceDue or alertDue or comingDue
            $dueResults = '';
            if(!empty($row['extDetails'])) {
                $dueResults = $row['extDetails'];

            } elseif(!empty($row['extTolDetails'])) {
                $dueResults = $row['extTolDetails'];

            } elseif(!empty($row['extAlertDetails'])) {
                $dueResults = $row['extAlertDetails'];

            } elseif(!empty($row['extCurrentDetails'])) {
                $dueResults = $row['extCurrentDetails'];
            }

            if(!empty($dueResults)) {
                $i=0;
                $extHtml = '<tr>';
                foreach ($dueResults as $key2 => $value2) {
                    $dateCompare = function($a,$b)
                                    {
                                        if(!empty($a['mos']) && !empty($b['mos'])) {
                                            $t1 = strtotime($a['mos']);
                                            $t2 = strtotime($b['mos']);
                                            return $t1 - $t2;
                                        }
                                    };  
                    usort($value2, $dateCompare);
                    foreach ($value2 as $key3 => $value3) {
                        if($i == 0) {
                            $airitem = $value3['description'];

                            $extHtml .='<td>'.$value3['description'].'</td>';

                            //Nextdue
                            $nextdue = '';
                            if(!empty($value3['hrs'])) {
                                $nextdue .= round($value3['hrs'],1).' hrs, ';
                            } 

                            if(!empty($value3['afl'])) {
                                $nextdue .= $value3['afl'].' cyc, ';
                            }

                            if(!empty($value3['mos'])) {
                                $nextdue .= $value3['mos'];
                            }

                            $airDueAt = $nextdue;

                            //Remaining
                            $remaining = '';
                            if(!empty($value3['remHrs'])) {
                                $remaining .= round($value3['remHrs'],1).' hrs';

                                if(!empty($value3['altHColor'])) {
                                    $remaining = '<span style="'.$value3['altHColor'].'">'.$remaining.'</span>';
                                }
                            }

                            if(!empty($value3['remAfl'])) {
                                $comma = $remaining !='' ? ', ': '';
                                $remaining .= $comma.$value3['remAfl'].' cyc';

                                if(!empty($value3['altAColor'])) {
                                    $remaining = '<span style="'.$value3['altAColor'].'">'.$remaining.'</span>';
                                }
                            }

                            if(!empty($value3['totalRemD'])) {
                                $comma = $remaining !='' ? ', ': '';
                                $remaining .= $comma.$value3['totalRemD'].' days';

                                if(!empty($value3['altDColor'])) {
                                    $remaining = '<span style="'.$value3['altDColor'].'">'.$remaining.'</span>';
                                }
                            }
                            $extHtml .='<td>'.$remaining.'</td>';

                            $airtogo = $remaining;
                        }
                        $i++;
                    }
                }
                $extHtml .= '</tr>';
            }
        }

        $results['extHtml']  = $extHtml;
        $results['airitem']  = $airitem;
        $results['airDueAt'] = $airDueAt;
        $results['airtogo']  = $airtogo;
        return $results;
    }
    
}