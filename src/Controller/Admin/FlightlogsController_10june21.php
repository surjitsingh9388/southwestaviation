<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Database\Expression\QueryExpression;
use Cake\Core\Configure;
use Dompdf\Dompdf;

/**
 * Flightlogs Controller
 */
class FlightlogsController extends AppController
{
    private $planeObj;
    private $airCompTimeObj;
    private $crewObj;
    private $flDetailObj;
    private $templateObj;
    private $tripFileObj;
    private $manifestObj;
    private $pilotObj;
    private $discpObj;
    
    public $paginate = array(
        'limit' => 10
    );
    
    public function initialize() 
    {
        parent::initialize();
        $this->loadComponent('User');
        $this->loadComponent('Plane');
        $this->loadComponent('Pilot');
        $this->loadComponent('AirframeComponent');
        $this->loadComponent('Report');
        $this->loadComponent('Flightlog');
        $this->loadComponent('Timezone');

        $this->planeObj = TableRegistry::get('Planes');
        $this->airCompTimeObj = TableRegistry::get('AirframeComponentTimes');
        $this->crewObj = TableRegistry::get('Crews');
        $this->flDetailObj = TableRegistry::get('FlightlogDetails');
        $this->templateObj = TableRegistry::get('Templates');
        $this->tripFileObj = TableRegistry::get('TripFiles');
        $this->manifestObj = TableRegistry::get('Manifest');
        $this->pilotObj = TableRegistry::get('Pilots');
        $this->discpObj = TableRegistry::get('AircraftDiscrepancies');
    }
    
    //Flight center page
    public function index()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flight Center', $actionStatus))
            {
                $actionItems = $actionStatus['Flight Center'];
            }
        }

        $this->set(compact('actionItems'));
    }

    //Flight log report
    public function reports()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flightlog Report', $actionStatus))
            {
                $actionItems = $actionStatus['Flightlog Report'];
            }
        }

        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();
        $this->set(compact('actionItems', 'planes'));
    }

    //Flightlog common Query
    public function flightLogQuery($tripId)
    {
        $fls = $this->Flightlogs->find()
                    ->where(['Flightlogs.trip_id'=>$tripId])
                    ->contain([
                        'FlightlogDetails',
                        'Crews'=>[
                            'fields'=>['id', 'plane_id', 'flightlog_id', 'trip_id', 'crew_member', 'member_type', 'pilot_flying', 'duty_start_date', 'duty_on', 'duty_off', 'required_rest', 'legs_apply']
                        ],
                        'Manifest',
                        'Planes'=>[
                            'fields'=>['Planes.id', 'Planes.plane_code']
                        ]
                    ])
                    ->enableHydration(false)
                    ->toArray();
        return $fls;
    }

    /**
     * Create multiple leg for dispatch
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function dispatch($tripId=null, $legDate=null)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flight Center', $actionStatus))
            {
                $actionItems = $actionStatus['Flight Center'];
            }
        }
        
        $fls = '';
        $tfcount = 0;
        if(!empty($tripId)) {
            $fls = $this->flightLogQuery($tripId);

            //Get trip file count
            $resArray = $this->getTripFiles($tripId);
            $tfcount = $resArray['tfcount'];
        }
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        //Pilot Component
        $pilotComp = $this->Pilot;
        $reportComp = $this->Report;
        $this->set(compact('actionItems', 'fls', 'planes', 'pilotComp', 'reportComp', 'tripId', 'legDate', 'tfcount'));
    }

    /**
     * Create multiple leg for release
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function release($tripId=null)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flight Center', $actionStatus))
            {
                $actionItems = $actionStatus['Flight Center'];
            }
        }
        
        $fls = '';
        $tfcount = 0;
        if(!empty($tripId)) {
            $fls = $this->flightLogQuery($tripId);

            //Get trip file count
            $resArray = $this->getTripFiles($tripId);
            $tfcount = $resArray['tfcount'];
        }
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        //Pilot Component
        $pilotComp = $this->Pilot;
        $reportComp = $this->Report;
        $this->set(compact('actionItems', 'fls', 'planes', 'pilotComp', 'reportComp', 'tfcount'));
    }

    /**
     * Create multiple leg for preplanning
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function preplanning($tripId=null)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flight Center', $actionStatus))
            {
                $actionItems = $actionStatus['Flight Center'];
            }
        }
        
        $fls = '';
        $tfcount = 0;
        if(!empty($tripId)) {
            $fls = $this->flightLogQuery($tripId);

            //Get trip file count
            $resArray = $this->getTripFiles($tripId);
            $tfcount = $resArray['tfcount'];
        }
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        //Pilot Component
        $pilotComp = $this->Pilot;
        $reportComp = $this->Report;
        $this->set(compact('actionItems', 'fls', 'planes', 'pilotComp', 'reportComp', 'tfcount'));
    }

    /**
     * Flightlog details of initiated new flight
     *
     * @return \Cake\Http\Response|null Redirects on successful flightlog, renders view otherwise.
     */
    public function flightlog($tripId=null)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flight Center', $actionStatus))
            {
                $actionItems = $actionStatus['Flight Center'];
            }
        }
        
        $fls = '';
        $tfcount = 0;
        if(!empty($tripId)) {
            $fls = $this->flightLogQuery($tripId);

            //Do other fields calculations
            foreach ($fls as $key=>$value) {
                //Flight time
                $takeOff = date('H:i', strtotime($value['flightlog_detail']['taxi_off']));
                $landing = date('H:i', strtotime($value['flightlog_detail']['landing']));                
                $ftArr = array(
                            'leg_date'=>$value['leg_date'],
                            'startTime'=>$takeOff, 
                            'takeoff_timezone'=>$value['flightlog_detail']['takeoff_timezone'], 
                            'endTime'=>$landing, 
                            'landing_timezone'=>$value['flightlog_detail']['landing_timezone']
                        );
                $fltTime = $this->Flightlog->getFlightTime($ftArr);
                $fls[$key]['flightlog_detail']['flight_time'] = $fltTime;
                //Flight decimal time
                $fls[$key]['flightlog_detail']['flight_time_decimal'] = $this->Flightlog->convertTimeDecimal($fltTime);

                //Block time
                $taxiOut = date('H:i', strtotime($value['flightlog_detail']['taxi_out']));
                $taxiIn = date('H:i', strtotime($value['flightlog_detail']['taxi_in']));
                $btArr = array(
                            'leg_date'=>$value['leg_date'],
                            'startTime'=>$taxiOut, 
                            'takeoff_timezone'=>$value['flightlog_detail']['takeoff_timezone'], 
                            'endTime'=>$taxiIn, 
                            'landing_timezone'=>$value['flightlog_detail']['landing_timezone']
                        );
                $blkTime = $this->Flightlog->getFlightTime($btArr);
                $fls[$key]['flightlog_detail']['block_time'] = $blkTime;
                //Block decimal time
                $fls[$key]['flightlog_detail']['block_time_decimal'] = $this->Flightlog->convertTimeDecimal($blkTime);

                //Fuel burn
                $fls[$key]['flightlog_detail']['fuel_total'] = $startFuel = !empty($value['flightlog_detail']['fuel_on_board']) ? $value['flightlog_detail']['fuel_on_board'] : 0;
                $endFuel = !empty($value['flightlog_detail']['ending_fuel']) ? $value['flightlog_detail']['ending_fuel'] : 0;           
                $fls[$key]['flightlog_detail']['fuel_burn'] = $startFuel - $endFuel;
            }

            //Get trip file count
            $resArray = $this->getTripFiles($tripId);
            $tfcount = $resArray['tfcount'];
        }
        //pr($fls);die;
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        //Components
        $pilotComp = $this->Pilot;
        $timezoneComp = $this->Timezone;
        $reportComp = $this->Report;
        $flightComp = $this->Flightlog;
        $this->set(compact('actionItems', 'fls', 'planes', 'pilotComp', 'timezoneComp', 'reportComp', 'flightComp', 'tfcount'));
    }

    //Flightlog success
    public function flightlogSuccess($tripid)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flight Logs', $actionStatus))
            {
                $actionItems = $actionStatus['Flight Logs'];
            }
        }

        $this->set(compact('actionItems', 'tripid'));
    }

    //Save flight leg scheduled page data
    public function saveFLSData()
    {
        $params = $this->request->data;
        if(!empty($params)) {           
            //pr($params);die;
            $dataType = '';
            if(!empty($params['data_type'])){
                $dataType = $params['data_type'];
            }

            //If copy trip or load template then unset ids
            if(!empty($params['new_trip'])) {
                unset($params['id']);
                unset($params['fld_id']);
                unset($params['crew_id']);
                unset($params['mf_id']);
                $dataType = 'copytrip';
            }

            //Add logged in user
            $params['user_id'] = $this->Auth->user('id');

            //Change status
            if(!empty($params['form_type']) && in_array($params['form_type'], ['fldetail', 'flpreplanning'])) {
                //Update flstatus
                if($params['form_type'] == 'fldetail') {
                    $params['flstatus'] = 'yes';
                    //Update leg_start and leg_length of flightlog table
                    if(!empty($params['taxi_out'])) {
                        $params['leg_start'] = $params['taxi_out'];
                    }
                    
                    if(!empty($params['block_time'])) {
                        $params['leg_length'] = $params['block_time'];
                    }
                }

                if(!empty($params['close_trip']) && $params['close_trip'] == 'yes') {
                    $params['status'] = 'closed';
                } else {
                    $params['status'] = 'in_progress';
                }
            } elseif (!empty($params['form_type']) && $params['form_type'] == 'fllog') {
                $params['status'] = 'scheduled';
            } elseif (!empty($params['form_type']) && $params['form_type'] == 'flrelease') {
                $params['status'] = 'released';
            } else {
                $params['status'] = 'schedule_process';
            }

            //Check if "Save trip as a template" selected
            if(!empty($params['is_template'])) {
                //change template status to yes
                $params['is_template'] = 'yes';
                
                $tempData = [];
                $tempData['trip_id'] = $params['trip_id'];
                $tempData['template_name'] = $params['template_name'];
                $tempRes = $this->templateObj->newEntity($tempData);
                $this->templateObj->save($tempRes);
            }

            //Add/update Flightlogs table
            if(!empty($params['id'])) {
                $fls = $this->Flightlogs->get($params['id']);
            } else{
                $fls = $this->Flightlogs->newEntity();
            }
            
            $fls = $this->Flightlogs->patchEntity($fls, $params);
            if ($this->Flightlogs->save($fls)) {
                //Update component times
                if($params['status'] == 'closed'){
                    $this->updateCompTimes($fls->id, $params);
                }

                //Save flightlog details
                if(!empty($params['fld_id'])) {
                    $params['id'] = $params['fld_id'];
                    $flDetails = $this->flDetailObj->get($params['id']);
                } else {
                    $flDetails = $this->flDetailObj->newEntity();
                }

                $params['flightlog_id'] = $fls->id;
                $flDetails = $this->flDetailObj->patchEntity($flDetails, $params);
                $this->flDetailObj->save($flDetails);

                //Save related data in crew details table
                if(!empty($params['crew_member'])) {
                    $insertData = array();
                    foreach ($params['crew_member'] as $key => $value) {
                        if(!empty($params['crew_id'][$key])) {
                            $insertData[$key]['id']          = $params['crew_id'][$key];
                        }
                        $insertData[$key]['plane_id']        = $params['plane_id'];
                        $insertData[$key]['trip_id']         = $params['trip_id'];
                        $insertData[$key]['flightlog_id']    = $fls->id;
                        $insertData[$key]['crew_member']     = $params['crew_member'][$key];
                        $insertData[$key]['pilot_flying']    = $params['pilot_flying'][$key];
                        $insertData[$key]['member_type']     = $params['member_type'][$key];
                        $insertData[$key]['duty_start_date'] = $params['duty_start_date'][$key];
                        $insertData[$key]['duty_on']         = $params['duty_on'][$key];
                        $insertData[$key]['duty_off']        = $params['duty_off'][$key];
                        $insertData[$key]['required_rest']   = $params['required_rest'][$key];
                        $insertData[$key]['legs_apply']      = $params['legs_apply'][$key];
                    }
                    //pr($insertData);
                    $crewDetail = $this->crewObj->newEntities($insertData);
                    $this->crewObj->saveMany($crewDetail);
                }

                //Save manifest details
                if(!empty($params['mf_id'])) {
                    $params['id'] = $params['mf_id'];
                    $manifest = $this->manifestObj->get($params['id']);
                } else {
                    $manifest = $this->manifestObj->newEntity();
                }

                $manifest = $this->manifestObj->patchEntity($manifest, $params);
                $this->manifestObj->save($manifest);

                $result = array('status'=>'success', 'tripid'=>$params['trip_id'], 'formtype'=>$params['form_type'], 'datatype'=>$dataType, 'message'=>'Your flight has been successfully saved!');
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please, try again.');
                echo json_encode($result);die;
            }
        }
    }

    //Update component time
    public function updateCompTimes($flId, $data)
    {
        if(!empty($flId) && !empty($data['plane_id'])) {
            $compDetails = $this->planeObj->find()
                    ->where(['Planes.id'=>$data['plane_id']])
                    ->select(['Planes.id', 'Planes.plane_code'])
                    ->contain([
                        'AirframeComponents'=>[
                            'fields'=>[
                                'AirframeComponents.id',
                                'AirframeComponents.log_book',
                                'AirframeComponents.plane_id'
                            ],
                            'AirframeComponentTimes'=>[
                                'fields'=>[
                                    'AirframeComponentTimes.id',
                                    'AirframeComponentTimes.plane_id',
                                    'AirframeComponentTimes.airframe_component_id',
                                    'AirframeComponentTimes.hours',
                                    'AirframeComponentTimes.cycles' 
                                ],
                                'sort'=>['AirframeComponentTimes.id'=>'DESC']
                            ]
                        ],
                    ])
                    ->enableHydration(false)
                    ->first();
            
            //Calculate leg time
            $takeOff = !empty($data['taxi_off']) ? date('H:i', strtotime($data['taxi_off'])) : '00:00';
            $landing = !empty($data['landing']) ? date('H:i', strtotime($data['landing'])) : '00:00';
            $ftArr = array(
                        'leg_date'=>$data['leg_date'],
                        'startTime'=>$takeOff, 
                        'takeoff_timezone'=>$data['takeoff_timezone'], 
                        'endTime'=>$landing, 
                        'landing_timezone'=>$data['landing_timezone']
                    );
            $fltTime = $this->Flightlog->getFlightTime($ftArr);
            $timeDecimal = $this->Flightlog->convertTimeDecimal($fltTime);

            $results = [];
            if(!empty($compDetails['airframe_components'])) {
                foreach ($compDetails['airframe_components'] as $key => $value) {
                    $results[$key]['plane_id'] = $value['plane_id'];
                    $results[$key]['airframe_component_id'] = $value['id'];
                    $results[$key]['flightlog_id'] = $flId;
                    $results[$key]['log_date'] = date('m-d-Y');
                    $results[$key]['hours'] = $value['airframe_component_times'][0]['hours'] + $timeDecimal;
                    
                    $results[$key]['user_id'] = $this->Auth->user('id');
                    $results[$key]['hours_accrued'] = round($timeDecimal,1);
                    if(in_array($value['log_book'], ['Airframe', 'Engine'])) {
                        $results[$key]['cycles'] = $value['airframe_component_times'][0]['cycles'] + 1;
                        $results[$key]['cycles_accrued'] = 1;
                    }

                    $compTimes = $this->airCompTimeObj->newEntity();
                    $compTimes = $this->airCompTimeObj->patchEntity($compTimes, $results[$key]);
                    $this->airCompTimeObj->save($compTimes);
                }
            }
        }
    }

    //Flight log dispatch success
    public function dispatchSuccess($tripid)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flight Logs', $actionStatus))
            {
                $actionItems = $actionStatus['Flight Logs'];
            }
        }

        //Get trip file count
        $resArray = $this->getTripFiles($tripid);
        $tfcount = $resArray['tfcount'];
        $this->set(compact('actionItems', 'tripid', 'tfcount'));
    }

    //Flight log release success
    public function releaseSuccess($tripid)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flight Logs', $actionStatus))
            {
                $actionItems = $actionStatus['Flight Logs'];
            }
        }

        $this->set(compact('actionItems', 'tripid'));
    }

    //Flight log preplanning success
    public function preplanningSuccess($tripid)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flight Logs', $actionStatus))
            {
                $actionItems = $actionStatus['Flight Logs'];
            }
        }

        $this->set(compact('actionItems', 'tripid'));
    }

    //Get crew list
    public function getCrewList()
    {
        $allPilots = $this->Pilot->getPilots();
        $crewHtml = '';
        if(!empty($allPilots)) {
            $crewHtml .= '<select name="crew_member" class="form-control col-md-6 col-xs-12 selectpicker changePilot">';
            foreach ($allPilots as $key => $value) {
                $crewHtml .= '<option value="'.$key.'">'.$value.'</option>';
            }
            $crewHtml .= '</select>';
            
            $result = array('status'=>'success', 'data'=>$crewHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$crewHtml);
            echo json_encode($result);die;
        }
    }

    //Calculate block time and decimal value
    public function getBlockTime()
    {
        $params = $this->request->data;

        if(empty($params['taxiOut'])) {
            $params['taxiOut'] = "00:00";
        }

        if(empty($params['taxiIn'])) {
            $params['taxiIn'] = "24:00";
        }

        $finalTime = array();
        $btArr = array(
                    'leg_date'=>$params['leg_date'],
                    'startTime'=>$params['taxiOut'], 
                    'takeoff_timezone'=>$params['takeoff_timezone'], 
                    'endTime'=>$params['taxiIn'], 
                    'landing_timezone'=>$params['landing_timezone']
                );
        $blkTime = $this->Flightlog->getFlightTime($btArr);
        $finalTime['blockTime'] = $blkTime;

        //Decimal time
        $timeDecimal = $this->Flightlog->convertTimeDecimal($blkTime);
        $finalTime['decimalTime'] = $timeDecimal;

        if(!empty($finalTime)) {
            $result = array('status'=>'success', 'data'=>$finalTime);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$finalTime);
            echo json_encode($result);die;
        }
    }

    //Calculate flight time and decimal value
    public function getFlightTime()
    {
        $params = $this->request->data;

        if(empty($params['takeOff'])) {
            $params['takeOff'] = "00:00";
        }

        if(empty($params['landing'])) {
            $params['landing'] = "24:00";
        }

        $finalTime = array();
        $ftArr = array(
                        'leg_date'=>$params['leg_date'],
                        'startTime'=>$params['takeOff'], 
                        'takeoff_timezone'=>$params['takeoff_timezone'], 
                        'endTime'=>$params['landing'], 
                        'landing_timezone'=>$params['landing_timezone']
                    );
        $fltTime = $this->Flightlog->getFlightTime($ftArr);
        $finalTime['flightTime'] = $fltTime;
        //Decimal time
        $timeDecimal = $this->Flightlog->convertTimeDecimal($fltTime);
        $finalTime['ftDecimal'] = $timeDecimal;

        if(!empty($finalTime)) {
            $result = array('status'=>'success', 'data'=>$finalTime);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$finalTime);
            echo json_encode($result);die;
        }
    }

    //Fuel details
    public function getFuelDetails()
    {
        $params = $this->request->data;
        $data = array();
        if(!empty($params)) {
            $params['startFuel'] = !empty($params['startFuel']) ? $params['startFuel'] : 0;
            $params['endFuel'] = !empty($params['endFuel']) ? $params['endFuel'] : 0;

            $data['startingFuel'] = $params['startFuel'];
           
            $data['fuelBurn'] = $params['startFuel'] - $params['endFuel'];
            $result = array('status'=>'success', 'data'=>$data);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$data);
            echo json_encode($result);die;
        }
    }

    //Display crew data
    public function displayCrewData()
    {
        $params = $this->request->data;
        $crewHtml = '';
        $manifestHtml = '';
        if(!empty($params)) {
            $crewId = !empty($params['id']) ? $params['id'] : '';
            $crewRecId = !empty($params['id']) ? '<input type="hidden" name="crew_id[]" value="'.$params['id'].'"><input type="hidden" name="update_crew" value="update">' : '';
            $pilotFlying = !empty($params['pilot_flying']) ? '<i class="fa fa-check pilotFlying" style="color:#1ab394; display: inline;"></i>' : '--';
            $pilotFlyingVal = !empty($params['pilot_flying']) ? $params['pilot_flying'] : '';
            $totalTime = $this->Pilot->crewDutyTime($params['duty_on'], $params['duty_off']);
            $crewName = $this->Pilot->getPilotName($params['crew_member']);
            $legsApply = !empty($params['legs_apply']) ? $params['legs_apply'] : 'on';
            $legsInfo = !empty($params['leg_info']) ? $params['leg_info'] : 'TBD-TBD';
            $delBtn = '<td style="cursor:pointer;" class="crewDelCls" data-crew_member="'.$params['crew_member'].'">X</td>';
            $crewHtml .= '<tr class="crewRecord'.$params['crew_member'].'">
                            <td><input type="hidden" name="member_type[]" value="'.$params['member_type'].'">'.strtoupper($params['member_type']).'</td>
                            <td class="initCrewRecUpdate checkCrewCls" style="color:#428bca;cursor:pointer;" data-member_type="'.$params['member_type'].'" data-crew_member="'.$params['crew_member'].'" data-pilot_flying="'.$pilotFlyingVal.'" data-duty_start_date="'.$params['duty_start_date'].'" data-duty_on="'.$params['duty_on'].'" data-duty_off="'.$params['duty_off'].'" data-required_rest="'.$params['required_rest'].'" data-legs_apply="'.$legsApply.'" data-leg_info="'.$legsInfo.'">
                            <input type="hidden" name="crew_member[]" value="'.$params['crew_member'].'">'.$crewName.'</td>';
                            if($params['allcrew'] != 'dispatch') {
                                $crewHtml .= '<td><input type="hidden" name="pilot_flying[]" value="'.$pilotFlyingVal.'">'.$pilotFlying.'</td>';
                            }
                            $crewHtml .= '<td><input type="hidden" name="duty_start_date[]" value="'.$params['duty_start_date'].'">'.$params['duty_start_date'].'</td>
                            <td><input type="hidden" class="dutyOnCls" name="duty_on[]" value="'.$params['duty_on'].'">'.$params['duty_on'].'</td>
                            <td><input type="hidden" class="dutyOffCls" name="duty_off[]" value="'.$params['duty_off'].'">'.$params['duty_off'].'</td>
                            <td><input type="hidden" name="required_rest[]" value="'.$params['required_rest'].'"><input type="hidden" name="legs_apply[]" value="'.$legsApply.'">'.$totalTime.'</td>
                            '.$delBtn.'
                        </tr>';

            $manifestHtml .= '<div class="crewListHtml" style="display: none;">
                            <div class="col-md-3">
                                <label>Type</label>
                                <input type="text" class="form-control disabledBG manifestType" value="'.strtoupper($params['member_type']).'" disabled>
                            </div>
                            <div class="col-md-9">
                                <label>Crew Member</label>
                                <input type="text" class="form-control disabledBG manifestCrew" value="'.$crewName.'" disabled>
                            </div>
                        </div>';

            $result = array('status'=>'success', 'data'=>$crewHtml, 'crewmem'=>$params['crew_member'], 'manifest'=>$manifestHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$crewHtml, 'manifest'=>$manifestHtml);
            echo json_encode($result);die;
        }
    }

    //Get flight log data
    public function getFlightLogData()
    {
        $params = $this->request->data;
        $flHtml = '';
        if(!empty($params['planeId']) && !empty($params['startDt']) && !empty($params['endDt'])) {
            $startDt = date('Y-m-d', strtotime($params['startDt'])).' 00:00:00';
            $endDt = date('Y-m-d', strtotime($params['endDt'])).' 23:59:59';
           
            $whereCond = [
                        'AirframeComponentTimes.created >='=>$startDt,
                        'AirframeComponentTimes.created <='=>$endDt
                    ];

            $selRec = [
                        'AirframeComponentTimes.id',
                        'AirframeComponentTimes.plane_id',
                        'AirframeComponentTimes.flightlog_id',
                        'AirframeComponentTimes.airframe_component_id',
                        'AirframeComponentTimes.hours',
                        'AirframeComponentTimes.cycles',
                        'AirframeComponentTimes.created',
                        'AirframeComponentTimes.user_id',
                        'AirframeComponentTimes.hours_accrued',
                        'AirframeComponentTimes.cycles_accrued',
                    ];

            //Comp times
            $comptimes = $this->airCompTimeObj->find()
                        ->select($selRec)
                        ->where(['AirframeComponentTimes.plane_id'=>$params['planeId'], 'AirframeComponentTimes.created >='=>$startDt, 'AirframeComponentTimes.created <='=>$endDt])
                        ->contain([
                            'AirframeComponents'=>[
                                'fields'=>[
                                    'AirframeComponents.id',
                                    'AirframeComponents.plane_id',
                                    'AirframeComponents.log_book',
                                    'AirframeComponents.position'
                                ]
                            ]
                        ])
                        ->order(['AirframeComponentTimes.id'=>'DESC'])
                        //->group('AirframeComponentTimes.created')
                        ->enableHydration(false)
                        ->toArray();
            //pr($comptimes);die;
            $results = [];
            foreach ($comptimes as $key => $value) {
                $logDT = date("m-d-Y H:i", strtotime($value['created']));
                //$flid = !empty($value['flightlog_id']) ? [$value['flightlog_id']] : '';
                if(!empty($value['flightlog_id'])) {
                    $flres = $this->fldata($value['flightlog_id']);
                    $results[$logDT][$value['flightlog_id']]['id'] = $value['id'];
                    $results[$logDT][$value['flightlog_id']]['plane_id'] = $value['plane_id'];
                    $results[$logDT][$value['flightlog_id']]['flightlog_id'] = $value['flightlog_id'];
                    $results[$logDT][$value['flightlog_id']]['airframe_component_id'] = $value['airframe_component_id'];
                    
                    if(!empty($flres)) {
                        $results[$logDT][$value['flightlog_id']]['tripid'] = $flres['tripid'];
                        $results[$logDT][$value['flightlog_id']]['crewName'] = $flres['crewName'];
                        $results[$logDT][$value['flightlog_id']]['flight_from'] = $flres['flight_from'];
                        $results[$logDT][$value['flightlog_id']]['flight_to'] = $flres['flight_to'];
                        $results[$logDT][$value['flightlog_id']]['approaches'] = $flres['approaches'];
                        $results[$logDT][$value['flightlog_id']]['passengers'] = $flres['passengers'];
                        $results[$logDT][$value['flightlog_id']]['leg_type'] = $flres['leg_type'];
                        $results[$logDT][$value['flightlog_id']]['timeDecimal'] = $flres['timeDecimal'];
                        $results[$logDT][$value['flightlog_id']]['fuelBurn'] = $flres['fuelBurn'];
                    } else {
                        $results[$logDT][$value['flightlog_id']]['tripid'] = '';
                        $results[$logDT][$value['flightlog_id']]['crewName'] = '';
                        $results[$logDT][$value['flightlog_id']]['flight_from'] = '';
                        $results[$logDT][$value['flightlog_id']]['flight_to'] = '';
                        $results[$logDT][$value['flightlog_id']]['approaches'] = '';
                        $results[$logDT][$value['flightlog_id']]['passengers'] = '';
                        $results[$logDT][$value['flightlog_id']]['leg_type'] = '';
                        $results[$logDT][$value['flightlog_id']]['timeDecimal'] = '';
                        $results[$logDT][$value['flightlog_id']]['fuelBurn'] = '';
                    }
                    
                    if(!empty($value['airframe_component']['log_book']) && $value['airframe_component']['log_book'] == 'Airframe') {
                        $results[$logDT][$value['flightlog_id']]['date'] = $flres['legdate'];
                        $results[$logDT][$value['flightlog_id']]['airHr'] = $value['hours'];
                        $results[$logDT][$value['flightlog_id']]['airCy'] = $value['cycles'];
                        
                    } elseif(!empty($value['airframe_component']['log_book']) && !empty($value['airframe_component']['position']) && $value['airframe_component']['log_book'] == 'Engine' && $value['airframe_component']['position'] == 1) {
                        $results[$logDT][$value['flightlog_id']]['date'] = $flres['legdate'];
                        $results[$logDT][$value['flightlog_id']]['eng1Hr'] = $value['hours'];
                        $results[$logDT][$value['flightlog_id']]['eng1Cy'] = $value['cycles'];

                    } elseif(!empty($value['airframe_component']['log_book']) && !empty($value['airframe_component']['position']) && $value['airframe_component']['log_book'] == 'Engine' && $value['airframe_component']['position'] == 2) {
                        $results[$logDT][$value['flightlog_id']]['date'] = $flres['legdate'];
                        $results[$logDT][$value['flightlog_id']]['eng2Hr'] = $value['hours'];
                        $results[$logDT][$value['flightlog_id']]['eng2Cy'] = $value['cycles'];
                    } else {
                        $results[$logDT][$value['flightlog_id']]['date'] = '';
                        $results[$logDT][$value['flightlog_id']]['airHr'] = '';
                        $results[$logDT][$value['flightlog_id']]['airCy'] = '';
                        $results[$logDT][$value['flightlog_id']]['eng1Hr'] = '';
                        $results[$logDT][$value['flightlog_id']]['eng1Cy'] = '';
                        $results[$logDT][$value['flightlog_id']]['eng2Hr'] = '';
                        $results[$logDT][$value['flightlog_id']]['eng2Cy'] = '';
                    }

                } else {
                    $results[$logDT]['id'] = $value['id'];
                    $results[$logDT]['plane_id'] = $value['plane_id'];
                    $results[$logDT]['flightlog_id'] = $value['flightlog_id'];
                    $results[$logDT]['airframe_component_id'] = $value['airframe_component_id'];
                    $username = $this->User->getUserName($value['user_id']);
                    
                    if(!empty($value['airframe_component']['log_book']) && $value['airframe_component']['log_book'] == 'Airframe') {
                        $results[$logDT]['date'] = date('m-d-Y H:i', strtotime($value['created']));
                        $results[$logDT]['airHr'] = $value['hours'];
                        $results[$logDT]['airCy'] = $value['cycles'];

                        if(!empty($username)) {
                            $results[$logDT]['afEntry'] = 'Manual Entry: ('.$username.') Air Frame +'.$value['hours_accrued'].', L/G +'.$value['cycles_accrued'];
                        }

                    } elseif(!empty($value['airframe_component']['log_book']) && !empty($value['airframe_component']['position']) && $value['airframe_component']['log_book'] == 'Engine' && $value['airframe_component']['position'] == 1) {
                        $results[$logDT]['date'] = date('m-d-Y H:i', strtotime($value['created']));
                        $results[$logDT]['eng1Hr'] = $value['hours'];
                        $results[$logDT]['eng1Cy'] = $value['cycles'];
                        
                        if(!empty($username)) {
                            $results[$logDT]['eng1Entry'] = 'Manual Entry: ('.$username.') Eng 1 +'.$value['hours_accrued'].', Eng 1 Cyc +'.$value['cycles_accrued'];
                        }                        

                    } elseif(!empty($value['airframe_component']['log_book']) && !empty($value['airframe_component']['position']) && $value['airframe_component']['log_book'] == 'Engine' && $value['airframe_component']['position'] == 2) {
                        $results[$logDT]['date'] = date('m-d-Y H:i', strtotime($value['created']));
                        $results[$logDT]['eng2Hr'] = $value['hours'];
                        $results[$logDT]['eng2Cy'] = $value['cycles'];
                        
                        if(!empty($username)) {
                            $results[$logDT]['eng2Entry'] = 'Manual Entry: ('.$username.') Eng 2 +'.$value['hours_accrued'].', Eng 2 Cyc +'.$value['cycles_accrued'];
                        }
                    } else {
                        $results[$logDT]['date'] = '';
                        $results[$logDT]['airHr'] = '';
                        $results[$logDT]['airCy'] = '';
                        $results[$logDT]['eng1Hr'] = '';
                        $results[$logDT]['eng1Cy'] = '';
                        $results[$logDT]['eng2Hr'] = '';
                        $results[$logDT]['eng2Cy'] = '';
                        $results[$logDT]['afEntry'] = '';
                        $results[$logDT]['eng1Entry'] = '';
                        $results[$logDT]['eng2Entry'] = '';
                    }
                }
            }

            $flHtml = '';
            if(!empty($results)) {
                foreach ($results as $key => $value) {
                    if(!empty($value) && count($value) < 8) {
                        foreach ($value as $key2 => $value2) {
                            $flHtml .= '<tr class="flRow">
                                    <td>'.$value2['date'].'</td>
                                    <td><a class="openFLCls" style="cursor:pointer;color:#428bca;" href="flightlog/'.$value2['tripid'].'" target="_blank">'.strtoupper($value2['tripid']).'</a></td></td>
                                    <td>'.$value2['crewName'].'</td>
                                    <td>'.strtoupper($value2['flight_from']).'</td>
                                    <td>'.strtoupper($value2['flight_to']).'</td>
                                    <td>'.$value2['timeDecimal'].'</td>
                                    <td>'.$value2['fuelBurn'].'</td>
                                    <td>'.$value2['passengers'].'</td>
                                    <td>'.$value2['approaches'].'</td>
                                    <td>'.$value2['leg_type'].'</td>
                                    <td>'.$value2['airHr'].'</td>
                                    <td>'.$value2['airCy'].'</td>
                                    <td>'.$value2['eng1Hr'].'</td>
                                    <td>'.$value2['eng2Hr'].'</td>
                                    <td>'.$value2['eng1Cy'].'</td>
                                    <td>'.$value2['eng2Cy'].'</td>
                                </tr>';
                        }
                    } else {
                        
                        $flHtml .= '<tr class="flRow">
                                        <td>'.$value['date'].'</td>
                                        <td colspan="9">'.$value['eng2Entry'].'</td>
                                        <td>'.$value['airHr'].'</td>
                                        <td>'.$value['airCy'].'</td>
                                        <td>'.$value['eng1Hr'].'</td>
                                        <td>'.$value['eng2Hr'].'</td>
                                        <td>'.$value['eng1Cy'].'</td>
                                        <td>'.$value['eng2Cy'].'</td>
                                    </tr>
                                    <tr class="flRow">
                                        <td>'.$value['date'].'</td>
                                        <td colspan="9">'.$value['eng1Entry'].'</td>
                                        <td>'.$value['airHr'].'</td>
                                        <td>'.$value['airCy'].'</td>
                                        <td>'.$value['eng1Hr'].'</td>
                                        <td>'.$value['eng2Hr'].'</td>
                                        <td>'.$value['eng1Cy'].'</td>
                                        <td>'.$value['eng2Cy'].'</td>
                                    </tr>
                                    <tr class="flRow">
                                        <td>'.$value['date'].'</td>
                                        <td colspan="9">'.$value['afEntry'].'</td>
                                        <td>'.$value['airHr'].'</td>
                                        <td>'.$value['airCy'].'</td>
                                        <td>'.$value['eng1Hr'].'</td>
                                        <td>'.$value['eng2Hr'].'</td>
                                        <td>'.$value['eng1Cy'].'</td>
                                        <td>'.$value['eng2Cy'].'</td>
                                    </tr>
                                    ';
                    }
                }
                $result = array('status'=>'success', 'data'=>$flHtml);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>$flHtml);
                echo json_encode($result);die;  
            }          
        } else {
            $result = array('status'=>'failure', 'data'=>$flHtml);
            echo json_encode($result);die;
        }
    }

    public function fldata($flid)
    {
        $res = $this->Flightlogs->find() 
                        ->where([
                            'Flightlogs.id'=>$flid,
                            'Flightlogs.status'=>'closed'
                        ])
                        ->contain([
                            'FlightlogDetails',
                            'Crews'=>[
                                'fields'=>['id', 'flightlog_id', 'crew_member']
                            ]
                        ])
                        ->enableHydration(false)
                        ->first();
        $flres = [];
        if(!empty($res)) {
            $flres['legdate'] = date('m-d-Y', strtotime($res['leg_date'])).' '.date('H:i', strtotime($res['flightlog_detail']['taxi_out']));
            $flres['tripid'] = $res['trip_id'];
            $flres['crewName'] = !empty($res['crews']) ? $this->Pilot->getPilotName($res['crews'][0]['crew_member']) : '';
            $flres['flight_from'] = $res['flight_from'];
            $flres['flight_to'] = $res['flight_to'];
            $flres['approaches'] = !empty($res['flightlog_detail']['approaches']) ? $res['flightlog_detail']['approaches'] : 0;
            $flres['leg_type'] = $res['leg_type'];
            $flres['passengers'] = $res['passengers'];

            //Flight time
            $takeOff = date('H:i', strtotime($res['flightlog_detail']['taxi_off']));
            $landing = date('H:i', strtotime($res['flightlog_detail']['landing']));            
            $ftArr = array(
                        'leg_date'=>$res['leg_date'],
                        'startTime'=>$takeOff, 
                        'takeoff_timezone'=>$res['flightlog_detail']['takeoff_timezone'], 
                        'endTime'=>$landing, 
                        'landing_timezone'=>$res['flightlog_detail']['landing_timezone']
                    );
            $fltTime = $this->Flightlog->getFlightTime($ftArr);
            //Decimal time
            $flres['timeDecimal'] = $this->Flightlog->convertTimeDecimal($fltTime);

            //Fuel burn
            $startFuel = !empty($res['flightlog_detail']['fuel_on_board']) ? $res['flightlog_detail']['fuel_on_board']:0;
            $endFuel = !empty($res['flightlog_detail']['ending_fuel']) ? $res['flightlog_detail']['ending_fuel']:0;   
            $flres['fuelBurn'] = $startFuel - $endFuel;
        }
        return $flres;
    }

    public function getFlightLogData_old()
    {
        $params = $this->request->data;
        $flHtml = '';
        if(!empty($params['planeId']) && !empty($params['startDt']) && !empty($params['endDt'])) {
            $startDt = date('Y-m-d', strtotime($params['startDt']));
            $endDt = date('Y-m-d', strtotime($params['endDt']));
            $records = $this->Flightlogs->find() 
                        ->where([
                            'Flightlogs.plane_id'=>$params['planeId'], 
                            'Flightlogs.leg_date >='=>$startDt, 
                            'Flightlogs.leg_date <='=>$endDt,
                            'Flightlogs.status'=>'closed'
                        ])
                        ->contain([
                            'FlightlogDetails',
                            'Crews'=>[
                                'fields'=>['id', 'flightlog_id', 'crew_member']
                            ],
                            'Planes'=>[
                                'fields'=>['id', 'plane_code'],
                                'AirframeComponents'=>[
                                    'fields'=>[
                                        'AirframeComponents.id',
                                        'AirframeComponents.plane_id',
                                        'AirframeComponents.log_book',
                                        'AirframeComponents.position'
                                    ],
                                    'AirframeComponentTimes'=>[
                                        'fields'=>[
                                            'AirframeComponentTimes.id',
                                            'AirframeComponentTimes.plane_id',
                                            'AirframeComponentTimes.flightlog_id',
                                            'AirframeComponentTimes.airframe_component_id',
                                            'AirframeComponentTimes.hours',
                                            'AirframeComponentTimes.cycles' 
                                        ],
                                        'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                    ]
                                ]
                            ]
                        ])
                        ->order(['Flightlogs.id'=>'DESC'])
                        ->enableHydration(false)
                        ->toArray();
            //pr($records);die;

            if(!empty($records)) {
                $prevTimeDecimal = '';
                foreach ($records as $key => $value) {
                    $value['flightlog_detail']['approaches'] = !empty($value['flightlog_detail']['approaches']) ? $value['flightlog_detail']['approaches'] : 0;

                    //Crew name
                    $crewName = !empty($value['crews']) ? $this->Pilot->getPilotName($value['crews'][0]['crew_member']) : '';

                    //Flight time
                    $takeOff = date('H:i', strtotime($value['flightlog_detail']['taxi_off']));
                    $landing = date('H:i', strtotime($value['flightlog_detail']['landing']));            
                    $ftArr = array(
                                'leg_date'=>$value['leg_date'],
                                'startTime'=>$takeOff, 
                                'takeoff_timezone'=>$value['flightlog_detail']['takeoff_timezone'], 
                                'endTime'=>$landing, 
                                'landing_timezone'=>$value['flightlog_detail']['landing_timezone']
                            );
                    $fltTime = $this->Flightlog->getFlightTime($ftArr);
                    //Decimal time
                    $timeDecimal = $this->Flightlog->convertTimeDecimal($fltTime);
                    //Fuel burn
                    $startFuel = !empty($value['flightlog_detail']['fuel_on_board'])?$value['flightlog_detail']['fuel_on_board']:0;
                    $endFuel = !empty($value['flightlog_detail']['ending_fuel'])?$value['flightlog_detail']['ending_fuel']:0;   
                    $fuelBurn = $startFuel - $endFuel;

                    //Component details
                    $airHr = '';
                    $airCy = '';
                    $eng1Hr = '';
                    $eng1Cy = '';
                    $eng2Hr = '';
                    $eng2Cy = '';
                    if(!empty($value['plane']['airframe_components'])) {
                        foreach ($value['plane']['airframe_components'] as $key2 => $value2) {
                            foreach ($value2['airframe_component_times'] as $key3 => $value3) {
                                if($value2['log_book'] == 'Airframe' && $value['id'] == $value3['flightlog_id']) {
                                    $airHr = $value3['hours'];
                                    $airCy = $value3['cycles'];

                                } elseif($value2['log_book'] == 'Engine' && $value2['position'] == 1 && $value['id'] == $value3['flightlog_id']) {
                                    $eng1Hr = $value3['hours'];
                                    $eng1Cy = $value3['cycles'];

                                } elseif($value2['log_book'] == 'Engine' && $value2['position'] == 2 && $value['id'] == $value3['flightlog_id']) {
                                    $eng2Hr = $value3['hours'];
                                    $eng2Cy = $value3['cycles'];
                                }
                            }
                        }
                    }

                    $flHtml .= '<tr class="flRow">
                            <td>'.date('m-d-Y', strtotime($value['leg_date'])).' '.date('H:i', strtotime($value['flightlog_detail']['taxi_out'])).'</td>
                            <td><a class="openFLCls" style="cursor:pointer;color:#428bca;" href="flightlog/'.$value['trip_id'].'" target="_blank">'.strtoupper($value['trip_id']).'</a></td>
                            <td>'.$crewName.'</td>
                            <td>'.strtoupper($value['flight_from']).'</td>
                            <td>'.strtoupper($value['flight_to']).'</td>
                            <td>'.$timeDecimal.'</td>
                            <td>'.$fuelBurn.'</td>
                            <td>'.$value['passengers'].'</td>
                            <td>'.$value['flightlog_detail']['approaches'].'</td>
                            <td>'.$value['leg_type'].'</td>
                            <td>'.$airHr.'</td>
                            <td>'.$airCy.'</td>
                            <td>'.$eng1Hr.'</td>
                            <td>'.$eng2Hr.'</td>
                            <td>'.$eng1Cy.'</td>
                            <td>'.$eng2Cy.'</td>
                        </tr>';

                    $prevTimeDecimal = $timeDecimal;
                }
                $result = array('status'=>'success', 'data'=>$flHtml);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>$flHtml);
                echo json_encode($result);die;
            }
        } else {
            $result = array('status'=>'failure', 'data'=>$flHtml);
            echo json_encode($result);die;
        }
    }

    //Open crew update popup
    public function crewUpdatePopup()
    {
        $params = $this->request->data;
        $updHtml = '';
        if(!empty($params['id']) && !empty($params['airId']) && !empty($params['flId'])) {
            $record = $this->crewObj->find() 
                        ->where([
                            'Crews.id'=>$params['id'], 
                            'Crews.plane_id'=>$params['airId'], 
                            'Crews.flightlog_id'=>$params['flId']
                        ])
                        ->enableHydration(false)
                        ->first();

            if(!empty($record)) {
                $crewName = $this->Pilot->getPilotName($record['crew_member']);
                $dutyOn = date('H:i', strtotime($record['duty_on']));
                $dutyOff = date('H:i', strtotime($record['duty_off']));
                $dsDate = !empty($record['duty_start_date']) ? date('m/d/Y', strtotime($record['duty_start_date'])) : '';
                $updHtml .= '<div>
                        <div class="row">
                            <input type="hidden" name="id" value="'.$record['id'].'">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Crew Member</label>
                                <select name="crew_member" class="form-control col-md-6 col-xs-12">
                                    <option value="'.$record['crew_member'].'">'.$crewName.'</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Crew Member Type</label>
                                <select name="member_type" class="form-control col-md-6 col-xs-12 landingCls">';
                                $restArr = ['pic', 'sic'];
                                foreach ($restArr as $key => $value) {
                                    if($record['member_type'] == $value) {
                                        $updHtml .= '<option value="'.$value.'" selected>'.strtoupper($value).'</option>';
                                    } else {
                                        $updHtml .= '<option value="'.$value.'">'.strtoupper($value).'</option>';
                                    }
                                }
                                $updHtml .= '</select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">';
                                $chk = '';
                                if($record['pilot_flying']) {
                                    $chk = "checked='checked'";
                                }
                                $updHtml .= '<input type="checkbox" name="pilot_flying" '.$chk.'> Pilot Flying
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Duty Start Date</label>
                                <input type="text" name="duty_start_date" value="'.$dsDate.'" class="form-control datePicker dtStartD">
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Estimated Duty On</label>
                                <input type="text" name="duty_on" value="'.$dutyOn.'" class="form-control timePicker" placeholder="00.00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Estimated Duty Off</label>
                                <input type="text" name="duty_off" value="'.$dutyOff.'" class="form-control timePicker" placeholder="00.00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Required Rest</label>
                                <select name="required_rest" class="form-control col-md-6 col-xs-12 requiredRestCls">';
                                    $restArr = [0, 8, 9, 10, 11, 12, 16];
                                    foreach ($restArr as $key => $value) {
                                        if($record['required_rest'] == $value && $record['required_rest'] == 0) {
                                            $updHtml .= '<option value="'.$value.'" selected>00 Hours (Part 91)</option>';
                                        } elseif($record['required_rest'] != $value && $value == 0) {
                                            $updHtml .= '<option value="'.$value.'">00 Hours (Part 91)</option>';
                                        } elseif($record['required_rest'] == $value && $record['required_rest'] != 0) {
                                            $updHtml .= '<option value="'.$value.'" selected>'.$value.' Hours</option>';
                                        } else {
                                            $updHtml .= '<option value="'.$value.'">'.$value.' Hours</option>';
                                        }
                                    }
                                    $updHtml .= '</select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Which legs should this apply to</label>
                                <br>';
                                $chk = '';
                                if($record['legs_apply']) {
                                    $chk = "checked='checked'";
                                }
                                $updHtml .= '<input type="checkbox" name="legs_apply" '.$chk.'> <span>'.strtoupper($params['route']).'</span>
                            </div>
                        </div>
                    </div>';

                $result = array('status'=>'success', 'data'=>$updHtml);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>$updHtml);
                echo json_encode($result);die;
            }
        } else {
            $result = array('status'=>'failure', 'data'=>$updHtml);
            echo json_encode($result);die;
        }
    }

    //Open crew initial update popup
    public function crewInitialUpPopup()
    {
        $params = $this->request->data;
        $initHtml = '';
        if(!empty($params)) {
            $allPilots = $this->Pilot->getPilots();
            $crewName = $this->Pilot->getPilotName($params['crew_member']);
            $dutyOn = !empty($params['duty_on']) ? date('H:i', strtotime($params['duty_on'])) : '';
            $dutyOff = !empty($params['duty_off']) ? date('H:i', strtotime($params['duty_off'])) : '';
            $initHtml .= '<div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Crew Member</label>
                            <select name="crew_member" class="form-control col-md-6 col-xs-12 selectpicker changePilot">';
                                foreach ($allPilots as $key => $value) {
                                    if($params['crew_member'] == $key) {
                                        $initHtml .= '<option value="'.$key.'" selected>'.strtoupper($value).'</option>';
                                    } else {
                                        $initHtml .= '<option value="'.$key.'">'.strtoupper($value).'</option>';
                                    }
                                }
                            $initHtml .= '</select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Crew Member Type</label>
                            <select name="member_type" class="form-control col-md-6 col-xs-12 landingCls">';
                            $restArr = ['pic', 'sic'];
                            foreach ($restArr as $key => $value) {
                                if($params['member_type'] == $value) {
                                    $initHtml .= '<option value="'.$value.'" selected>'.strtoupper($value).'</option>';
                                } else {
                                    $initHtml .= '<option value="'.$value.'">'.strtoupper($value).'</option>';
                                }
                            }
                            $initHtml .= '</select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">';
                            $chk = '';
                            if(!empty($params['pilot_flying'])) {
                                $chk = "checked='checked'";
                            }
                            $initHtml .= '<input type="checkbox" name="pilot_flying" '.$chk.'> Pilot Flying
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Duty Start Date</label>
                            <input type="text" name="duty_start_date" value="'.$params['duty_start_date'].'" class="form-control datePicker dtStartD">
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Estimated Duty On</label>
                            <input type="text" name="duty_on" value="'.$dutyOn.'" class="form-control timePicker" placeholder="00.00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Estimated Duty Off</label>
                            <input type="text" name="duty_off" value="'.$dutyOff.'" class="form-control timePicker" placeholder="00.00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Required Rest</label>
                            <select name="required_rest" class="form-control col-md-6 col-xs-12 requiredRestCls">';
                                $restArr = [0, 8, 9, 10, 11, 12, 16];
                                foreach ($restArr as $key => $value) {
                                    if($params['required_rest'] == $value && $params['required_rest'] == 0) {
                                        $initHtml .= '<option value="'.$value.'" selected>00 Hours (Part 91)</option>';
                                    } elseif($params['required_rest'] != $value && $value == 0) {
                                        $initHtml .= '<option value="'.$value.'">00 Hours (Part 91)</option>';
                                    } elseif($params['required_rest'] == $value && $params['required_rest'] != 0) {
                                        $initHtml .= '<option value="'.$value.'" selected>'.$value.' Hours</option>';
                                    } else {
                                        $initHtml .= '<option value="'.$value.'">'.$value.' Hours</option>';
                                    }
                                }
                                $initHtml .= '</select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Which legs should this apply to</label>
                            <br>';
                            $chk = '';
                            if(!empty($params['legs_apply'])) {
                                $chk = "checked='checked'";
                            }
                            $initHtml .= '<input type="checkbox" name="legs_apply" '.$chk.' disabled> <span>'.$params['leg_info'].'</span>
                        </div>
                    </div>
                </div>';

            $result = array('status'=>'success', 'data'=>$initHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$initHtml);
            echo json_encode($result);die;
        }
    }

    //New tab form for flightlogDetail page
    public function newTabForm()
    {
        $tabHtml = $this->Flightlog->flightlogTabForm();
        $result = array('status'=>'success', 'data'=>$tabHtml);
        echo json_encode($result);die;
    }

    //New tab form for dispatch page
    public function newTab()
    {                  
        $tabHtml = $this->Flightlog->dispatchTabForm();
        $result = array('status'=>'success', 'data'=>$tabHtml);
        echo json_encode($result);die;
    }

    //Flight center data
    public function getFlightCenterData()
    {
        $params = $this->request->data;
        $status = ['Flightlogs.status NOT IN'=>['delete', 'closed']];
        if(!empty($params['tbname']) && in_array($params['tbname'], ['released', 'in_progress'])) {
            $statusArr = ['released', 'in_progress'];
            $status = ['Flightlogs.status IN'=>$statusArr, 'Flightlogs.status NOT IN'=>['delete', 'closed']];
        } elseif(!empty($params['tbname']) && $params['tbname'] == 'closed') {
            $status = ['Flightlogs.status'=>$params['tbname'], 'Flightlogs.status !='=>'delete'];
        } elseif (!empty($params['tbname'])) {
            $status = ['Flightlogs.status'=>$params['tbname'], 'Flightlogs.status NOT IN'=>['delete', 'closed']];
        }
        
        $records = $this->Flightlogs->find() 
                        ->where($status)
                        ->select(['id', 'plane_id', 'trip_id', 'flight_from', 'flight_to', 'leg_date', 'status'])
                        ->contain([
                            'Planes'=>[
                                'fields'=>['id', 'plane_code']
                            ]
                        ])
                        ->order(['Flightlogs.id'=>'ASC'])
                        ->enableHydration(false)
                        ->toArray();

        $routeHtml = '';
        if(!empty($records)) {
            $routeData = array();
            $route = '';
            $tmp = '';
            $i = 0;
            foreach ($records as $key => $value) {
                if($value['trip_id'] != $tmp) {
                    $route = strtoupper($value['flight_from']);                    
                    $i++;
                }
                $route = $route.'-'.strtoupper($value['flight_to']);
                $routeData[$i]['trip_id'] = strtoupper($value['trip_id']);
                $routeData[$i]['flight_date'] = date('m/d/Y', strtotime($value['leg_date']));
                $routeData[$i]['aircraft'] = $value['plane']['plane_code'];
                $routeData[$i]['route'] = $route;
                $routeData[$i]['status'] = $value['status'];

                $tmp = $value['trip_id'];
            }

            foreach ($routeData as $key2 => $value2) {
                //Status
                $sts = explode("_", $value2['status']);
                if(count($sts)>1) {
                    $status = ucwords($sts[0].' '.$sts[1]);
                } else {
                    $status = ucwords($value2['status']);
                }

                $routeHtml .= '<tr class="fcRow">
                            <td>'.$value2['trip_id'].'</td>
                            <td>'.$value2['flight_date'].'</td>
                            <td>'.$value2['aircraft'].'</td>
                            <td>'.$value2['route'].'</td>
                            <td>'.$status.'</td>
                            <td>
                                <div class="split-btn">
                                    <button class="btn-dropdown btn-default dueListCls">Action</button>
                                    <button class="icon-part dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-caret-down"></i>
                                    </button>                               
                                    <ul class="dropdown-content dropdown-menu">
                                        <li><a href="javascript:void(0);" data-fls="schedule" data-tripid="'.$value2['trip_id'].'" class="changeFLStatus">Schedule</a></li>';
                                        if($value2['status'] == 'schedule_process') {
                                            $routeHtml .= '';
                                        } elseif($value2['status'] == 'scheduled') {
                                            $routeHtml .= '<li><a href="javascript:void(0);" data-fls="release" data-tripid="'.$value2['trip_id'].'" class="changeFLStatus">Release</a></li>';
                                        } elseif($value2['status'] == 'released') {
                                            $routeHtml .= '<li><a href="javascript:void(0);" data-fls="release" data-tripid="'.$value2['trip_id'].'" class="changeFLStatus">Release</a></li>
                                            <li><a href="javascript:void(0);" data-fls="pre_plan" data-tripid="'.$value2['trip_id'].'" class="changeFLStatus">Pre-Plan</a></li>';
                                        } elseif($value2['status'] == 'in_progress' || $value2['status'] == 'closed') {
                                            $routeHtml .= '<li><a href="javascript:void(0);" data-fls="release" data-tripid="'.$value2['trip_id'].'" class="changeFLStatus">Release</a></li>
                                            <li><a href="javascript:void(0);" data-fls="pre_plan" data-tripid="'.$value2['trip_id'].'" class="changeFLStatus">Pre-Plan</a></li>
                                            <li><a href="javascript:void(0);" data-fls="flight_log" data-tripid="'.$value2['trip_id'].'" class="changeFLStatus">Flight Log</a></li>';
                                        }
                                        $routeHtml .= '<li><a href="javascript:void(0);" data-fls="delete" data-tripid="'.$value2['trip_id'].'" class="deleteFlightCls">Delete</a></li>
                                        <li><a href="javascript:void(0);" data-fls="copy" data-tripid="'.$value2['trip_id'].'" class="changeFLStatus">Copy</a></li>
                                        <li><a href="javascript:void(0);" data-fls="send_itinerary" data-tripid="'.$value2['trip_id'].'" class="changeFLStatus">Send Itinerary</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>';
            }

            $result = array('status'=>'success', 'data'=>$routeHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$routeHtml);
            echo json_encode($result);die;
        }
    }

    //Generate Flight Log pdf
    public function generateFLPdf()
    {
        $params = $this->request->data;
        if(!empty($params['planeId']) && !empty($params['startDt']) && !empty($params['endDt'])) {
            $aircraft = $this->planeObj->find()
                    ->where(['Planes.id'=>$params['planeId']])
                    ->select(['Planes.id', 'Planes.plane_type', 'Planes.plane_code'])
                    ->enableHydration(false)
                    ->first();
            //pr($aircraft);die;

            $startDt = date('Y-m-d', strtotime($params['startDt']));
            $endDt = date('Y-m-d', strtotime($params['endDt']));
            $records = $this->Flightlogs->find() 
                        ->where([
                            'Flightlogs.plane_id'=>$params['planeId'], 
                            'Flightlogs.leg_date >='=>$startDt, 
                            'Flightlogs.leg_date <='=>$endDt,
                            'Flightlogs.status'=>'closed'
                        ])
                        ->contain([
                            'FlightlogDetails',
                            'Crews'=>[
                                'fields'=>['id', 'flightlog_id', 'crew_member']
                            ],
                            'Planes'=>[
                                'fields'=>['id', 'plane_code', 'plane_type'],
                                'AirframeComponents'=>[
                                    'fields'=>[
                                        'AirframeComponents.id',
                                        'AirframeComponents.plane_id',
                                        'AirframeComponents.log_book',
                                        'AirframeComponents.position'
                                    ],
                                    'AirframeComponentTimes'=>[
                                        'fields'=>[
                                            'AirframeComponentTimes.id',
                                            'AirframeComponentTimes.plane_id',
                                            'AirframeComponentTimes.flightlog_id',
                                            'AirframeComponentTimes.airframe_component_id',
                                            'AirframeComponentTimes.hours',
                                            'AirframeComponentTimes.cycles' 
                                        ],
                                        'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                    ]
                                ]
                            ]
                        ])
                        ->order(['Flightlogs.id'=>'DESC'])
                        ->enableHydration(false)
                        ->toArray();
            //pr($records);die;

            $mainHtml = '';
            $flHtml = '';
            if(!empty($records)) {
                $prevTimeDecimal = '';
                foreach ($records as $key => $value) {
                    $value['flightlog_detail']['approaches'] = !empty($value['flightlog_detail']['approaches']) ? $value['flightlog_detail']['approaches'] : 0;

                    //Crew name
                    $crewName = !empty($value['crews']) ? $this->Pilot->getPilotName($value['crews'][0]['crew_member']) : '';

                    //Flight time
                    $finalTime = array();
                    $takeOff = date('H:i', strtotime($value['flightlog_detail']['taxi_off']));
                    $landing = date('H:i', strtotime($value['flightlog_detail']['landing']));

                    if(strtotime($landing) < strtotime($takeOff)) {
                        $landing = "24:00";
                    }
                    $ftArr = array(
                                'leg_date'=>$value['leg_date'],
                                'startTime'=>$takeOff, 
                                'takeoff_timezone'=>$value['flightlog_detail']['takeoff_timezone'], 
                                'endTime'=>$landing, 
                                'landing_timezone'=>$value['flightlog_detail']['landing_timezone']
                            );
                    $fltTime = $this->Flightlog->getFlightTime($ftArr);
                    //Decimal time
                    $timeDecimal = $this->Flightlog->convertTimeDecimal($fltTime);

                    //Fuel burn
                    $startFuel = !empty($value['flightlog_detail']['fuel_on_board'])?$value['flightlog_detail']['fuel_on_board']:0;
                    $endFuel = !empty($value['flightlog_detail']['ending_fuel'])?$value['flightlog_detail']['ending_fuel']:0;
                    $fuelBurn = $startFuel - $endFuel;

                    //Component details
                    $airHr = '';
                    $airCy = '';
                    $eng1Hr = '';
                    $eng1Cy = '';
                    $eng2Hr = '';
                    $eng2Cy = '';
                    if(!empty($value['plane']['airframe_components'])) {
                        foreach ($value['plane']['airframe_components'] as $key2 => $value2) {
                            foreach ($value2['airframe_component_times'] as $key3 => $value3) {
                                if($value2['log_book'] == 'Airframe' && $value['id'] == $value3['flightlog_id']) {
                                    $airHr = $value3['hours'];
                                    $airCy = $value3['cycles'];

                                } elseif($value2['log_book'] == 'Engine' && $value2['position'] == 1 && $value['id'] == $value3['flightlog_id']) {
                                    $eng1Hr = $value3['hours'];
                                    $eng1Cy = $value3['cycles'];

                                } elseif($value2['log_book'] == 'Engine' && $value2['position'] == 2 && $value['id'] == $value3['flightlog_id']) {
                                    $eng2Hr = $value3['hours'];
                                    $eng2Cy = $value3['cycles'];
                                }
                            }
                        }
                    }

                    $flHtml .= '<tr>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.date('m-d-Y', strtotime($value['leg_date'])).' '.date('H:i', strtotime($value['flightlog_detail']['taxi_out'])).'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.strtoupper($value['trip_id']).'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.$crewName.'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.strtoupper($value['flight_from']).'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.strtoupper($value['flight_to']).'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.$timeDecimal.'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.$fuelBurn.'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.$value['passengers'].'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.$value['flightlog_detail']['approaches'].'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.$value['leg_type'].'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.$airHr.'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.$airCy.'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.$eng1Hr.'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.$eng2Hr.'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.$eng1Cy.'</td>
                            <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 7px 5px; margin: 0;">'.$eng2Cy.'</td>
                        </tr>';

                    $prevTimeDecimal = $timeDecimal;
                }
            } else {
                $flHtml = '<tr><td colspan="16" style="text-align: center;">No Records Found for that Aircraft.</td></tr>';
            }

            $mainHtml .= '<header>
                            <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td style="width:70%; padding:0 0 15px 0;"></td>
                                        <td style="width:30%; font-size: 14px; font-weight: normal; text-align:right; padding:0 0 15px 0; color: #676a6c;"><span style="font-weight: bold;">Aircraft:</span> '.$aircraft['plane_code'].' ('.$aircraft['plane_type'].')'.'</td>
                                    </tr>
                                    <tr>
                                        <td style="width:70%; padding:0 0 15px 0;"></td>
                                        <td style="width:30%; font-size: 14px; font-weight: normal; text-align:right; padding:0 0 15px 0; color: #676a6c;"><span style="font-weight: bold;">Date Range:</span> '.$params['startDt'].' - '.$params['endDt'].'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </header>';

            $mainHtml .= '<table style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center; font-size:11px;" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Date/Time</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Trip ID</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Crew</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Depart</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Arrive</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Leg Time</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Fuel Burn</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;"># Pax</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Appr.</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Type</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Total Time</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">L/G Cyc</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Eng 1 Time</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Eng 2 Time</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Eng 1 Cyc</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;">Eng 2 Cyc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    '.$flHtml.'
                                </tbody>
                            </table>';

            //pr($mainHtml);die;
            $html ='<!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="utf-8">
                        <style>
                            @page {
                                margin: 0cm 0cm;
                            }

                            body {
                                margin-top: 2cm;
                                margin-left: .5cm;
                                margin-right: .5cm;
                                margin-bottom: 1cm;
                            }

                            header {
                                position: fixed;
                                top: .5cm;
                                left: 0cm;
                                right: 0cm;
                                bottom: 0cm;
                                height: 1cm;
                            }

                            footer {
                                position: fixed; 
                                bottom: .3cm;
                                left: 0cm; 
                                right: 0cm;
                                height: 1cm;
                            }

                            .page_break { 
                                page-break-before: always; 
                            }
                        </style>
                    </head>
                    <body>
                        <main>'.$mainHtml.'</main>        
                    </body>
                    </html>';
            //pr($html);die;
           
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->AddPage('L', // L - landscape, P - portrait 
            '', '', '', '',
            5, // margin_left
            5, // margin right
            15, // margin top
            10, // margin bottom
            0, // margin header
            0); // margin footer

            $mpdf->WriteHTML($html);
            
            //save the file on particular location
            //https://mpdf.github.io/reference/mpdf-functions/output.html
            $fileName = "FlightLog".date('YmdHis').".pdf";
            $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
            if($mainHtml) {
                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
        }
    }

    //Update flight log status
    public function changeFLStatus()
    {
        $params = $this->request->data;
        if(!empty($params['tripId']) && !empty($params['flStatus'])) {
            
            //Status update
            $res = $this->Flightlogs->updateAll(
                    ['user_id'=>$this->Auth->user('id'),'status'=>$params['flStatus']],
                    ['trip_id'=>$params['tripId']]
                );

            if($res) {
                $result = array('status'=>'success', 'tripid'=>$params['tripId'], 'message'=>'Your flight has been successfully released!');
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'message'=>'Somthing wrong. Please try again.');
                echo json_encode($result);die;
            }
        }
    }

    //Get stop time
    public function getStopTime()
    {
        $params = $this->request->data;
        $res = $this->Pilot->addTimesMulti(array($params['leg_start'], $params['leg_length']));
        $result = array('status'=>'success', 'data'=>$res);
        echo json_encode($result);die;
    }

    //Open schedule crew initial update popup
    public function flsCrewInitUpPopup()
    {
        $params = $this->request->data;
        $initHtml = '';
        if(!empty($params)) {
            $allPilots = $this->Pilot->getPilots();
            $crewName = $this->Pilot->getPilotName($params['crew_member']);
            $dutyOn = !empty($params['duty_on']) ? date('H:i', strtotime($params['duty_on'])) : '';
            $dutyOff = !empty($params['duty_off']) ? date('H:i', strtotime($params['duty_off'])) : '';
            $initHtml .= '<div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Crew Member</label>
                            <select name="crew_member" class="form-control col-md-6 col-xs-12 selectpicker changePilot">';
                                foreach ($allPilots as $key => $value) {
                                    if($params['crew_member'] == $key) {
                                        $initHtml .= '<option value="'.$key.'" selected>'.strtoupper($value).'</option>';
                                    } else {
                                        $initHtml .= '<option value="'.$key.'">'.strtoupper($value).'</option>';
                                    }
                                }
                            $initHtml .= '</select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Crew Member Type</label>
                            <select name="member_type" class="form-control col-md-6 col-xs-12 landingCls">';
                            $restArr = ['pic', 'sic'];
                            foreach ($restArr as $key => $value) {
                                if($params['member_type'] == $value) {
                                    $initHtml .= '<option value="'.$value.'" selected>'.strtoupper($value).'</option>';
                                } else {
                                    $initHtml .= '<option value="'.$value.'">'.strtoupper($value).'</option>';
                                }
                            }
                            $initHtml .= '</select>
                        </div>
                        <div class="col-md-6">
                            <label>Duty Start Date</label>
                            <input type="text" name="duty_start_date" value="'.$params['duty_start_date'].'" class="form-control datePicker dtStartD">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Estimated Duty On</label>
                            <input type="text" name="duty_on" value="'.$dutyOn.'" class="form-control timePicker" placeholder="00.00">
                        </div>
                        <div class="col-md-6">
                            <label>Estimated Duty Off</label>
                            <input type="text" name="duty_off" value="'.$dutyOff.'" class="form-control timePicker" placeholder="00.00">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Required Rest</label>
                            <select name="required_rest" class="form-control col-md-6 col-xs-12 requiredRestCls">';
                                $restArr = [0, 8, 9, 10, 11, 12, 16];
                                foreach ($restArr as $key => $value) {
                                    if($params['required_rest'] == $value && $params['required_rest'] == 0) {
                                        $initHtml .= '<option value="'.$value.'" selected>00 Hours (Part 91)</option>';
                                    } elseif($params['required_rest'] != $value && $value == 0) {
                                        $initHtml .= '<option value="'.$value.'">00 Hours (Part 91)</option>';
                                    } elseif($params['required_rest'] == $value && $params['required_rest'] != 0) {
                                        $initHtml .= '<option value="'.$value.'" selected>'.$value.' Hours</option>';
                                    } else {
                                        $initHtml .= '<option value="'.$value.'">'.$value.' Hours</option>';
                                    }
                                }
                                $initHtml .= '</select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Which legs should this apply to</label>
                            <br>';
                            $chk = '';
                            if(!empty($params['legs_apply'])) {
                                $chk = "checked='checked'";
                            }
                            $initHtml .= '<input type="checkbox" name="legs_apply" '.$chk.' disabled> <span>'.$params['leg_info'].'</span>
                        </div>
                    </div>
                </div>';

            $result = array('status'=>'success', 'data'=>$initHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$initHtml);
            echo json_encode($result);die;
        }
    }

    //Open schedule crew update popup
    public function flsCrewUpdatePopup()
    {
        $params = $this->request->data;
        $updHtml = '';
        if(!empty($params['id']) && !empty($params['airId']) && !empty($params['flId'])) {
            $record = $this->crewObj->find() 
                        ->where([
                            'Crews.id'=>$params['id'], 
                            'Crews.plane_id'=>$params['airId'], 
                            'Crews.flightlog_id'=>$params['flId']
                        ])
                        ->enableHydration(false)
                        ->first();

            if(!empty($record)) {
                $crewName = $this->Pilot->getPilotName($record['crew_member']);
                $dutyOn = date('H:i', strtotime($record['duty_on']));
                $dutyOff = date('H:i', strtotime($record['duty_off']));
                $updHtml .= '<div>
                        <input type="hidden" name="id" value="'.$record['id'].'">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Crew Member</label>
                                <select name="crew_member" class="form-control col-md-6 col-xs-12">
                                    <option value="'.$record['crew_member'].'">'.$crewName.'</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Crew Member Type</label>
                                <select name="member_type" class="form-control col-md-6 col-xs-12 landingCls">';
                                $restArr = ['pic', 'sic'];
                                foreach ($restArr as $key => $value) {
                                    if($record['member_type'] == $value) {
                                        $updHtml .= '<option value="'.$value.'" selected>'.strtoupper($value).'</option>';
                                    } else {
                                        $updHtml .= '<option value="'.$value.'">'.strtoupper($value).'</option>';
                                    }
                                }
                                $updHtml .= '</select>
                            </div>
                            <div class="col-md-6">
                                <label>Duty Start Date</label>
                                <input type="text" name="duty_start_date" value="'.date('m/d/Y', strtotime($record['duty_start_date'])).'" class="form-control datePicker dtStartD">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Estimated Duty On</label>
                                <input type="text" name="duty_on" value="'.$dutyOn.'" class="form-control timePicker" placeholder="00.00">
                            </div>
                            <div class="col-md-6">
                                <label>Estimated Duty Off</label>
                                <input type="text" name="duty_off" value="'.$dutyOff.'" class="form-control timePicker" placeholder="00.00">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Required Rest</label>
                                <select name="required_rest" class="form-control col-md-6 col-xs-12 requiredRestCls">';
                                    $restArr = [0, 8, 9, 10, 11, 12, 16];
                                    foreach ($restArr as $key => $value) {
                                        if($record['required_rest'] == $value && $record['required_rest'] == 0) {
                                            $updHtml .= '<option value="'.$value.'" selected>00 Hours (Part 91)</option>';
                                        } elseif($record['required_rest'] != $value && $value == 0) {
                                            $updHtml .= '<option value="'.$value.'">00 Hours (Part 91)</option>';
                                        } elseif($record['required_rest'] == $value && $record['required_rest'] != 0) {
                                            $updHtml .= '<option value="'.$value.'" selected>'.$value.' Hours</option>';
                                        } else {
                                            $updHtml .= '<option value="'.$value.'">'.$value.' Hours</option>';
                                        }
                                    }
                                    $updHtml .= '</select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Which legs should this apply to</label>
                                <br>';
                                $chk = '';
                                if($record['legs_apply']) {
                                    $chk = "checked='checked'";
                                }
                                $updHtml .= '<input type="checkbox" name="legs_apply" '.$chk.'> <span>'.strtoupper($params['route']).'</span>
                            </div>
                        </div>
                    </div>';

                $result = array('status'=>'success', 'data'=>$updHtml);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>$updHtml);
                echo json_encode($result);die;
            }
        } else {
            $result = array('status'=>'failure', 'data'=>$updHtml);
            echo json_encode($result);die;
        }
    }

    //Save trip files
    public function saveTripFiles()
    {
        $params = $this->request->data;
        if(!empty($params['file_name']) && isset($params['file_name']['tmp_name'])) 
        {
            $temp = $params['file_name']['tmp_name'];
            $name = $params['file_name']['name'];
            $ext = substr(strrchr($name , '.'), 1);
            $arr_ext = array('jpeg', 'jpg', 'png', 'pdf');
            $setNewFileName =  $params['title'].'.'.$ext;
            $params['filename'] =  $setNewFileName;
            
            if (in_array($ext, $arr_ext)) {
                if(move_uploaded_file($temp, WWW_ROOT . 'tripfiles/' . $setNewFileName)) {
                    $tripFiles = $this->tripFileObj->newEntity(); 
                    $tripFiles = $this->tripFileObj->patchEntity($tripFiles, $params);
                    if($this->tripFileObj->save($tripFiles)) {
                        //Get all files of this trip id
                        $resArray = $this->getTripFiles($params['trip_id']);
                        $result = array('status'=>'success', 'tfcount'=>$resArray['tfcount'], 'data'=>$resArray['tflist'], 'message'=>"Saved successfully.");
                    }                    
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failed', 'message'=>'Please upload correct format.');
            }
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            echo json_encode($result);die;
        }
    }

    //Display trip files listing
    public function tripFilesList()
    {
        $tripId = $this->request->data['tripid'];
        if(!empty($tripId)) {
            $resArray = $this->getTripFiles($tripId);
            $result = array('status'=>'success', 'tfcount'=>$resArray['tfcount'], 'data'=>$resArray['tflist'], 'message'=>"Trip files listing");
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'No record found');
            echo json_encode($result);die;
        }
    }

    //Get trip files
    public function getTripFiles($tripId)
    {
        $resArray = [];
        $tfcount = 0;
        $tflist = '';
        if(!empty($tripId)) {
            $results = $this->tripFileObj->find()
                    ->where(['trip_id'=>$tripId])
                    ->select(['id', 'trip_id', 'title', 'filename'])
                    ->enableHydration(false)
                    ->toArray();

            if(!empty($results)) {
                $tfcount = count($results);
                foreach ($results as $key => $value) {
                    $tflist .= '<tr>
                            <td>'.$value['title'].'</td>
                            <td><a href="'.ROOT_DIR . 'tripfiles/'.$value['filename'].'" class="btn btn-sm btn-primary" target="_blank"><i class="fa fa-download"></i> View</a></td>
                            <td><button type="button" class="btn btn-sm btn-info pull-right editTFCls" data-id="'.$value['id'].'" data-tripid="'.$value['trip_id'].'"><i class="fa fa-edit"></i> Edit</button></td>
                        </tr>';
                }
            }
        }
        $resArray['tfcount'] = $tfcount;
        $resArray['tflist'] = $tflist;
        return $resArray;
    }

    //Edit trip file popup
    public function editTFPopup()
    {
        $params = $this->request->data();
        $tfHtml = '';
        if(!empty($params['tfid']) && !empty($params['tripid'])) {
            $result = $this->tripFileObj->find()
                    ->where(['id'=>$params['tfid'], 'trip_id'=>$params['tripid']])
                    ->select(['id', 'trip_id', 'title', 'filename'])
                    ->enableHydration(false)
                    ->first();

            if(!empty($result)) {
                $tfHtml .= '<div class="row">
                        <div class="col-md-12">
                            <label>Choose a File</label>
                            <input type="file" name="file_name" class="form-control" />
                            <span>Max 50mb file upload.</span>
                        </div>
                    </div>
                    <div style="clear: both;padding:10px;"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Currently uploaded file</label>
                            <div class="form-control">'.$result['title'].'</div>
                            <span>Max 50mb file upload.</span>
                        </div>
                    </div>
                    <div style="clear: both;padding:10px;"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Type a name for the file</label>
                            <input type="text" name="title" class="form-control" maxlength="100" value="'.$result['title'].'">
                            <span>100 characters max.</span>
                        </div>
                    </div>';
            }

            $result = array('status'=>'success', 'tfid'=>$result['id'], 'tripid'=>$result['trip_id'], 'data'=>$tfHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'No record found');
            echo json_encode($result);die;
        }
    }

    //Delete trip file
    public function deleteTripFile()
    {
        $params = $this->request->data;
        if(!empty($params['tfid']) && !empty($params['tripid'])) {
            //Delete trip file
            $tripRes = $this->tripFileObj->get($params['tfid']);
            $this->tripFileObj->delete($tripRes);

            $resArray = $this->getTripFiles($params['tripid']);
            $result = array('status'=>'success', 'tfcount'=>$resArray['tfcount'], 'data'=>$resArray['tflist'], 'message'=>"Trip files deleted successfully!");
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

    //Delete flight, flight details, crew and templates
    public function deleteFlight()
    {
        $params = $this->request->data;
        //pr($params);die;
        if(!empty($params)) {
            //Delete trip template
            $templ = array('Templates.trip_id'=>$params['tripId']);
            $this->templateObj->deleteAll($templ,false);

            //Delete trip files
            $tripfile = array('TripFiles.trip_id'=>$params['tripId']);
            $this->tripFileObj->deleteAll($tripfile,false);

            //Delete crew details
            $crewDetail = array('Crews.trip_id'=>$params['tripId']);
            $this->crewObj->deleteAll($crewDetail,false);

            //Delete manifest details
            $manifest = array('Manifest.trip_id'=>$params['tripId']);
            $this->manifestObj->deleteAll($manifest,false);

            //Delete trip flight details
            $flDetails = array('FlightlogDetails.trip_id'=>$params['tripId']);
            $this->flDetailObj->deleteAll($flDetails,false);

            //Delete flightlog
            $fllog = array('Flightlogs.trip_id'=>$params['tripId']);
            $this->Flightlogs->deleteAll($fllog,false);
            
            $result = array('status'=>'success', 'message'=>'Flight deleted successfully.');
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

    //Add template name
    public function addTemplateName()
    {
        $params = $this->request->data;
        $tempHtml = '';
        if(!empty($params['template_name'])) {
            $tempHtml .= '<input type="hidden" name="template_name" value="'.$params['template_name'].'">';
            $result = array('status'=>'success', 'data'=>$tempHtml, 'message'=>"Template name added successfully!");
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

    //Get templates
    public function getTemplates()
    {
        $results = $this->templateObj->find()
                    ->select(['id', 'trip_id', 'template_name'])
                    ->enableHydration(false)
                    ->toArray();

        $tempHtml = '';
        if(!empty($results)) {
            foreach ($results as $key => $value) {
                $tempHtml .= '<tr>
                                <td>'.$value['template_name'].'</td>
                                <td><button type="button" class="btn btn-sm btn-success tempPopupCls" data-id="'.$value['id'].'" data-tripid="'.$value['trip_id'].'"><i class="fa fa-download"></i> Load</button></td>
                                <td><button type="button" class="btn btn-sm btn-warning pull-right removeTempCls" data-id="'.$value['id'].'" data-tripid="'.$value['trip_id'].'"><span aria-hidden="true">×</span> Remove</button></td>
                            </tr>';
            }
        } else {
            $tempHtml .= '<tr>
                            <td colspan="3">No Templates Saved Yet</td>
                        </tr>';
        }
        return $tempHtml;
    }

    //Display templates listing
    public function displayTemplates()
    {
        $tempHtml = $this->getTemplates();
        $result = array('status'=>'success', 'data'=>$tempHtml);
        echo json_encode($result);die;
    }

    //Delete template
    public function deleteTemplate()
    {
        $params = $this->request->data;
        if(!empty($params['tmpid']) && !empty($params['tripid'])) {
            //Delete template file
            $tempRes = $this->templateObj->get($params['tmpid']);
            $this->templateObj->delete($tempRes);

            $tempHtml = $this->getTemplates();
            $result = array('status'=>'success', 'data'=>$tempHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

    //Open template popup
    public function openTempPopup()
    {
        $params = $this->request->data;
        if(!empty($params['tmpid']) && !empty($params['tripid'])) {
            $tempRes = $this->templateObj->find()
                    ->where(['id'=>$params['tmpid'], 'trip_id'=>$params['tripid']])
                    ->select(['id', 'trip_id', 'template_name'])
                    ->enableHydration(false)
                    ->first();

            $tempHtml = '';
            $date = date('m/d/Y');        
            if(!empty($tempRes)) {
                $tempHtml .= '<div class="row">
                                <label>Pick a New Starting Date for the Trip</label>
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar col-md-1" aria-hidden="true"></i>
                                    </span>
                                    <input type="text" name="leg_date" class="form-control legDate datePicker valid" value="'.$date.'">
                                    <input type="hidden" class="tripid" name="trip_id" value="'.$tempRes['trip_id'].'">
                                </div>                                
                            </div>';
            }
            
            $result = array('status'=>'success', 'tempName'=>$tempRes['template_name'], 'data'=>$tempHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

    //Load template
    public function loadTemplate()
    {
        $params = $this->request->data;
        if(!empty($params['trip_id']) && !empty($params['leg_date'])) {
            $legdate = date('m-d-Y', strtotime($params['leg_date']));
            $result = array('status'=>'success', 'tripid'=>$params['trip_id'], 'legdate'=>$legdate);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

    //Itinerary passenger email form
    public function itineraryPassenger($tripId=null)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flight Center', $actionStatus))
            {
                $actionItems = $actionStatus['Flight Center'];
            }
        }
        
        $fls = '';
        if(!empty($tripId)) {
            $fls = $this->Flightlogs->find()
                    ->where(['Flightlogs.trip_id'=>$tripId])
                    ->contain([
                        'FlightlogDetails',
                        'Crews',
                        'Planes'=>[
                            'fields'=>['Planes.id', 'Planes.plane_code', 'Planes.plane_type']
                        ]
                    ])
                    ->enableHydration(false)
                    ->toArray();
        }
        //Pilot Component
        $pilotComp = $this->Pilot;

        $this->set(compact('actionItems', 'fls', 'pilotComp', 'tripId'));
    }

    public function sendItineraryEmail()
    {
        $params = $this->request->data;
        if(!empty($params)) {
            if($this->sendEmail($params)) {
                $result = array('status'=>'success', 'message'=>'Email send successfully.');
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
                echo json_encode($result);die;
            }
        } else {
            $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

    public function itineraryPassengerSuccess($tripId=null)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flight Logs', $actionStatus))
            {
                $actionItems = $actionStatus['Flight Logs'];
            }
        }

        $fls = '';
        if(!empty($tripId)) {
            $fls = $this->Flightlogs->find()
                    ->where(['Flightlogs.trip_id'=>$tripId])
                    ->contain([
                        'FlightlogDetails',
                        'Crews',
                        'Planes'=>[
                            'fields'=>['Planes.id', 'Planes.plane_code', 'Planes.plane_type']
                        ]
                    ])
                    ->enableHydration(false)
                    ->toArray();
        }
        //Pilot Component
        $pilotComp = $this->Pilot;

        $this->set(compact('actionItems', 'fls', 'pilotComp', 'tripId'));
    }

    //Add manifest in form
    public function displayManifest()
    {
        $params = $this->request->data;
        $manifHtml = '';
        if(!empty($params)) {
            $mfid = '';
            if(!empty($params['mf_id'])) {
                $mfid = $params['mf_id'];
            }
            $manifHtml = '<input type="hidden" class="mfId" name="mf_id" value="'.$mfid.'"><input type="hidden" class="mfMaxWeight" name="max_weight" value="'.$params['max_weight'].'"><input type="hidden" class="mfActualWeight" name="actual_weight" value="'.$params['actual_weight'].'"><input type="hidden" class="mfForwardCg" name="forward_cg" value="'.$params['forward_cg'].'"><input type="hidden" class="mfActualCg" name="actual_cg" value="'.$params['actual_cg'].'"><input type="hidden" class="mfAftCg" name="aft_cg" value="'.$params['aft_cg'].'">';

            $result = array('status'=>'success', 'data'=>$manifHtml, 'tabnum'=>$params['tabnum']);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$manifHtml);
            echo json_encode($result);die;
        }
    }

    //Open manifest in popup
    public function initManifest()
    {
        $params = $this->request->data;
        //pr($params);die;
        $airCode = $this->Plane->getPlaneName($params['plane_id']);
        
        $mf_id = '';
        if(!empty($params['mf_id'])) {
            $mf_id = $params['mf_id'];
        }

        $max_weight = '';
        if(!empty($params['max_weight'])) {
            $max_weight = $params['max_weight'];
        }

        $actual_weight = '';
        if(!empty($params['actual_weight'])) {
            $actual_weight = $params['actual_weight'];
        }

        $forward_cg = '';
        if(!empty($params['forward_cg'])) {
            $forward_cg = $params['forward_cg'];
        }

        $actual_cg = '';
        if(!empty($params['actual_cg'])) {
            $actual_cg = $params['actual_cg'];
        }

        $aft_cg = '';
        if(!empty($params['aft_cg'])) {
            $aft_cg = $params['aft_cg'];
        }

        $manifHtml = '';
        if(!empty($params)) {
            $manifHtml .= '<div class="row">
                    <div class="col-md-12">
                        <label>Flight Information:</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label>Date</label>
                        <input type="text" class="form-control disabledBG manifestDate" value="'.$params['leg_date'].'" disabled>
                    </div>

                    <div class="col-md-6">
                        <label>Aircraft</label>
                        <input type="text" class="form-control disabledBG manifestAircraft" value="'.$airCode.'" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label>Origin</label>
                        <input type="text" class="form-control disabledBG manifestFlightFrom" value="'.$params['flight_from'].'" style="text-transform:uppercase" disabled>
                    </div>

                    <div class="col-md-6">
                        <label>Destination</label>
                        <input type="text" class="form-control disabledBG manifestFlightTo" value="'.$params['flight_to'].'" style="text-transform:uppercase" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label># of Passengers</label>
                        <input type="text" class="form-control disabledBG manifestPassenger" value="'.$params['passengers'].'" disabled>
                    </div>

                    <div class="col-md-6">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="padding-top: 25px;">
                        <label>Crew Information:</label>
                    </div>
                </div>

                <div class="row manifestCrewList">
                    <div class="table-responsive">
                        <table width="100%">
                            <thead style="border-bottom: 1px solid #c0c0c0;padding: 2px;">
                                <tr>
                                    <th width="20%">Type</th>
                                    <th width="80%">Crew Member</th>
                                </tr>
                            </thead>
                            <tbody class="crewMemberList">';
                                if(!empty($params['crew_member'])) {
                                    foreach ($params['crew_member'] as $key => $value) {
                                        $crewName = $this->Pilot->getPilotName($params['crew_member'][$key]);
                                        $manifHtml .= '<tr>
                                                        <td style="padding:2px;"><input type="text" class="form-control disabledBG" value="'.strtoupper($params['member_type'][$key]).'" disabled></td>
                                                        <td style="padding:2px;"><input type="text" class="form-control disabledBG" value="'.$crewName.'" disabled></td>
                                                    </tr>';
                                    }
                                }
                            $manifHtml .= '</tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="padding-top: 10px;">
                        <label>Aircraft Weight:</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label>Max Allowable T/O Weight</label>
                        <input type="number" name="max_weight" value="'.$max_weight.'" class="form-control" placeholder="0" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0">
                        <input type="hidden" name="mf_id" value="'.$mf_id.'">
                    </div>

                    <div class="col-md-6">
                        <label>Actual T/O Weight</label>
                        <input type="number" name="actual_weight" value="'.$actual_weight.'" class="form-control" placeholder="0" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="padding-top: 10px;">
                        <label>CG Limits:</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label>Forward CG Limit</label>
                        <input type="number" name="forward_cg" value="'.$forward_cg.'" class="form-control" placeholder="0" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0">
                    </div>

                    <div class="col-md-6">
                        <label>Actual CG</label>
                        <input type="number" name="actual_cg" value="'.$actual_cg.'" class="form-control" placeholder="0" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="padding-top: 10px;">
                        <label>AFT CG Limit</label>
                        <input type="number" name="aft_cg" value="'.$aft_cg.'" class="form-control" placeholder="0" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0">
                    </div>
                </div>';

            $result = array('status'=>'success', 'data'=>$manifHtml, 'tabnum'=>$params['tabnum']);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$manifHtml);
            echo json_encode($result);die;
        }
    }

    //Check manifest
    public function getManifest()
    {
        $params = $this->request->data;
        if(!empty($params['tripId']) && !empty($params['mfId'])) {
            
            $mRes = $this->manifestObj->find()
                    ->select(['max_weight', 'actual_weight', 'forward_cg', 'actual_cg', 'aft_cg'])
                    ->where(['Manifest.trip_id'=>$params['tripId'], 'Manifest.id'=>$params['mfId']])
                    ->enableHydration(false)
                    ->first();
            if(!empty($mRes)) {
                $result = array('status'=>'success');
                echo json_encode($result);die;
            }
        } else {
            $result = array('status'=>'failure');
            echo json_encode($result);die;
        }
    }

    //Change trip status
    public function changeTripStatus()
    {
        $params = $this->request->data;
        if(!empty($params['tripId'])) {
            $res = $this->Flightlogs->updateAll(
                    ['user_id'=>$this->Auth->user('id'),'status'=>'closed'],
                    ['trip_id'=>$params['tripId']]
                );

            $result = array('status'=>'success');
            echo json_encode($result);die;
        }
    }

    public function activity()
    {
        ini_set('memory_limit', '1024M');

        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Dashboard', $actionStatus))
            {
                $actionItems = $actionStatus['Dashboard'];
            }
        }

        //Pilots listing
        $pilots = $this->Pilot->allPilots();
        $allPilots = [];
        $pids = [];
        if(!empty($pilots)) {
            foreach ($pilots as $key => $value) {
                $pids[] = $value['id'];
            }
            $allPilots = $this->pilotObj->find()
                            ->where(['Pilots.id IN'=>$pids])
                            ->contain([
                                'Users',
                                'DutyAssignments'=>[
                                    'sort'=>['DutyAssignments.id'=>'DESC']
                                ], 
                                'PilotCertificates'=>[
                                    'sort'=>['PilotCertificates.id'=>'DESC']
                                ], 
                                'PilotCheckings'=>[
                                    'sort'=>['PilotCheckings.id'=>'DESC']
                                ], 
                                'PilotTrainings'=>[
                                    'sort'=>['PilotTrainings.id'=>'DESC']
                                ]
                            ])
                            ->toArray();
        }

        //All aircraft records
        $records = $this->Report->customReportQuery();
        $allResults = $this->Report->customReportWithCount($records, 'flightLog');
        $aircrafts = $allResults['results'];

        //Discrepancies
        $discrepancies = $this->discpObj->find()
                            ->where(['AircraftDiscrepancies.discrepancy_corrected !='=>1])
                            ->select([
                                'AircraftDiscrepancies.id', 
                                'AircraftDiscrepancies.plane_id', 
                                'AircraftDiscrepancies.discrepancy', 
                                'AircraftDiscrepancies.mel_repair_by'
                            ])
                            ->contain([
                                'Planes'=>[
                                    'fields'=>[
                                        'Planes.id',
                                        'Planes.plane_code'
                                    ]
                                ]
                            ])
                            ->enableHydration(false)
                            ->toArray();

        //Notifications
        $flightRes = $this->Flightlogs->find()
                        ->where(['Flightlogs.status !='=>'closed'])
                        ->select(['id', 'trip_id', 'leg_date', 'flstatus', 'status'])
                        ->order(['Flightlogs.id'=>'ASC'])
                        ->enableHydration(false)
                        ->toArray();

        $notifications = array();
        if(!empty($flightRes)) {
            $tmp = '';
            $i = 0;
            foreach ($flightRes as $key => $value) {
                if($value['trip_id'] != $tmp) {                   
                    $i++;
                }
                $notifications[$i]['id'] = $value['id'];
                $notifications[$i]['trip_id'] = strtoupper($value['trip_id']);
                $notifications[$i]['leg_date'] = date('m/d/Y', strtotime($value['leg_date']));
                $notifications[$i]['flstatus'] = $value['flstatus'];
                $notifications[$i]['status'] = $value['status'];

                $tmp = $value['trip_id'];
            }
        }
        //pr($notifications);die;
                
        $pilotComp = $this->Pilot;
        $this->set(compact('actionItems', 'pilotComp', 'notifications', 'allPilots', 'aircrafts', 'discrepancies'));
    }

    public function calendar()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Flight Calendar', $actionStatus))
            {
                $actionItems = $actionStatus['Flight Calendar'];
            }
        }
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();
        $this->set(compact('actionItems', 'planes'));
    }

    public function getFlights()
    {
        $params = $this->request->data;
        $whereCond = ['Flightlogs.leg_start !='=>'', 'Flightlogs.leg_length !='=>'', 'Flightlogs.status !='=>'closed'];
        if(!empty($params['airIds'])) {
            $whereCond = ['Flightlogs.plane_id IN'=>$params['airIds'], 'Flightlogs.leg_start !='=>'', 'Flightlogs.leg_length !='=>'', 'Flightlogs.status !='=>'closed'];
        }

        $flightRes = $this->Flightlogs->find()
                        ->where($whereCond)
                        ->select(['id', 'trip_id', 'flight_from', 'flight_to', 'leg_date', 'leg_start', 'leg_length', 'flstatus', 'status'])
                        ->contain([
                            'FlightlogDetails'=>[
                                'fields'=>['id', 'flightlog_id', 'trip_id', 'takeoff_timezone', 'landing_timezone', 'taxi_out', 'taxi_in']
                            ],
                            'Crews'=>[
                                'fields'=>['id', 'flightlog_id', 'trip_id', 'crew_member']
                            ],
                            'Planes'=>[
                                'fields'=>['Planes.id', 'Planes.plane_code', 'Planes.plane_type']
                            ]
                        ])
                        ->order(['Flightlogs.id'=>'ASC'])
                        ->enableHydration(false)
                        ->toArray();
        
        $flRes = array();
        if(!empty($flightRes)) {
            $results = [];
            foreach($flightRes as $value) {
                $tripid = $value['trip_id'];
                if(!empty($value['flightlog_detail']['taxi_out']) && !empty($value['flightlog_detail']['taxi_in'])) {
                    $taxiOut = date('H:i', strtotime($value['flightlog_detail']['taxi_out']));
                    $taxiIn = date('H:i', strtotime($value['flightlog_detail']['taxi_in']));

                    $btArr = array(
                                'leg_date'=>$value['leg_date'],
                                'startTime'=>$taxiOut, 
                                'takeoff_timezone'=>$value['flightlog_detail']['takeoff_timezone'], 
                                'endTime'=>$taxiIn, 
                                'landing_timezone'=>$value['flightlog_detail']['landing_timezone']
                            );
                    $blkTime = $this->Flightlog->getFlightTime($btArr);
                } else {
                    $taxiOut = date('H:i', strtotime($value['leg_start']));
                    $blkTime = date('H:i', strtotime($value['leg_length']));
                }
                
                //Block decimal time
                $blockTimeDecimal = $this->Flightlog->convertTimeDecimal($blkTime);

                $results[$tripid][] = [
                    'trip_id'    => $value['trip_id'],
                    'route'      => $value['flight_from'].' - '.$value['flight_to'],
                    'crewMember' => $this->Pilot->getPilotName($value['crews'][0]['crew_member']),
                    'legStart'   => date('l, m/d/Y', strtotime($value['leg_date'])).' '.$taxiOut,
                    'legLength'  => $blockTimeDecimal,
                ];
            }
                        
            foreach ($flightRes as $key => $value) {
                //Create leg html
                $legHtml = '';
                if(!empty($results[$value['trip_id']])) {
                    foreach ($results[$value['trip_id']] as $key2 => $value2) {
                        $legHtml .= '<div class="legContainer marginTop20">
                                    <div>
                                        <span class="route font13 text-uppercase">'.$value2['route'].'</span>
                                        <i class="marginLeft10 fa fa-users font13 text-info"></i>
                                        <span class="crew italic text-info">'.$value2['crewMember'].'</span>
                                    </div>
                                    <div class="calendarTime marginTop5">
                                        <i class="fa fa-calendar font16 calendarIcon marginRight7"></i> 
                                        <span class="legTime font14">'.$value2['legStart'].'</span> 
                                        <span class="font14">-</span>
                                        <span class="font14 legLength">'.$value2['legLength'].'</span> 
                                        <span class="font14">Hour(s)</span>
                                    </div>
                                    <div style="clear:both"></div>                                
                                </div>';
                    }
                }
                
                //Status
                $sts = explode("_", $value['status']);
                if(count($sts)>1) {
                    $status = ucwords($sts[0].' '.$sts[1]);
                } else {
                    $status = ucwords($value['status']);
                }

                //Description Details
                $popHtml = '<div class="row">
                                <div class="col-sm-12">
                                    <div class="marginBottom20">
                                        <button class="btn btn-default btn-lg paddingRight15 pull-left" type="button"><i class="fa fa-plane font18"></i>
                                        </button> 
                                        <div class="pull-left marginLeft10">
                                            <span class="font14 aircraft">'.$value['plane']['plane_code'].'</span><br> 
                                            <span class="font12 makeModel">'.$value['plane']['plane_type'].'</span> <span class="font12"></span>
                                        </div>
                                        <div style="clear:both">
                                        </div>
                                    </div>
                                    '.$legHtml.'
                                </div>
                                <div class="marginBottom15" style="clear:both">
                                </div> 
                            </div>';

                $flRes[$key]['id'] = $value['id'];
                $flRes[$key]['title'] = $value['plane']['plane_code'].', '.strtoupper($value['flight_from']).'-'.strtoupper($value['flight_to']);
                $flRes[$key]['start'] = date('Y-m-d', strtotime($value['leg_date'])).'T'.date('H:i:s', strtotime($value['leg_start']));
                $flRes[$key]['end'] = date('Y-m-d', strtotime($value['leg_date'])).'T'.date('H:i:s', strtotime($this->Pilot->addTimesMulti(array(date('H:i', strtotime($value['leg_start'])), date('H:i', strtotime($value['leg_length']))))));
                $flRes[$key]['trip_id'] = $value['trip_id'];
                //$flRes[$key]['allDay'] = 'false';
                //$flRes[$key]['rendering'] = 'background';
                //$flRes[$key]['textColor'] = 'red';
                $flRes[$key]['color']     = 'transparent';
                $flRes[$key]['background'] = '#1ab394';

                $flRes[$key]['status'] = $status;
                $flRes[$key]['description'] = $popHtml;
            }

            $result = array('status'=>'success', 'data'=>$flRes);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$flRes);
            echo json_encode($result);die;
        }
    }

    //Find max past due result to dispay on dispatch and flightlog screen 
    public function aircraftDueRecord()
    {
        $params = $this->request->data;
        $extHtml = '';
        $airitem = '';
        $airtogo = '';
        if(!empty($params['airId'])) {
            
            $results = $this->Report->airDues($params['airId']);

            $result = array('status'=>'success', 'data'=>$results['extHtml'], 'airitem'=>$results['airitem'], 'airtogo'=>$results['airtogo']);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$extHtml, 'airitem'=>$airitem, 'airtogo'=>$airtogo);
            echo json_encode($result);die;
        }
    }

    
}