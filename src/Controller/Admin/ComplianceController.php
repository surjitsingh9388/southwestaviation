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
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\I18n\FrozenTime;

/**
 * Compliance Controller
 */
class ComplianceController extends AppController
{
    /*private $planeObj;
    private $airCompPartObj;
    private $airCompTimeObj;
    private $airCompLastCwObj;
    private $partInstTimeObj;
    private $extDetailObj;
    private $parentChildRelObj;*/

    protected \App\Model\Table\PlanesTable $planeObj;
    protected \App\Model\Table\AirframeComponentPartsTable $airCompPartObj;
    protected \App\Model\Table\AirframeComponentTimesTable $airCompTimeObj;
    protected \App\Model\Table\AirframeComponentLastCwTable $airCompLastCwObj;
    protected \App\Model\Table\PartInstalledTimesTable $partInstTimeObj;
    protected \App\Model\Table\ExtraDetailsTable $extDetailObj;
    protected \App\Model\Table\ParentChildRelationsTable $parentChildRelObj;

    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );
    
    public function initialize():void 
    {
        parent::initialize();

        $this->loadComponent('AirframeComponentPart');
        $this->loadComponent('Report');
        $this->loadComponent('AircraftHistory');

        $this->planeObj       = $this->fetchTable('Planes');
        $this->airCompPartObj = $this->fetchTable('AirframeComponentParts');
        $this->airCompTimeObj = $this->fetchTable('AirframeComponentTimes');
        $this->airCompLastCwObj = $this->fetchTable('AirframeComponentLastCw');
        $this->partInstTimeObj = $this->fetchTable('PartInstalledTimes');
        $this->extDetailObj = $this->fetchTable('ExtraDetails');
        $this->parentChildRelObj = $this->fetchTable('ParentChildRelations');
    }
    
    //Error correction
    public function errorCorrection($id = null)
    {
        if(empty($id)) {
            $params = $_GET;
            $id = $params['partId'];
        }
        
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Component Parts', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Parts'];
            }
        }
        
        $airCompParts = $this->airCompPartObj->get($params['partId'], 
                        [
                            'contain'=> [
                                'Planes'=>[
                                    'fields'=>[
                                        'Planes.id',
                                        'Planes.plane_code'
                                    ],
                                    'AirframeComponents'=>[
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
                                                'AirframeComponentTimes.plane_id',
                                                'AirframeComponentTimes.airframe_component_id',
                                                'AirframeComponentTimes.log_date',
                                                'AirframeComponentTimes.hours',
                                                'AirframeComponentTimes.cycles' 
                                            ],
                                            'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                        ]
                                    ]
                                ],
                                'AirframeComponentLastCw'=>[
                                    'sort' => ['AirframeComponentLastCw.id' => 'DESC']
                                ], 
                                'AirframeComponents'=>[
                                    'AirframeComponentTimes'=>[
                                        'fields'=>[
                                            'AirframeComponentTimes.airframe_component_id', 
                                            'AirframeComponentTimes.hours', 
                                            'AirframeComponentTimes.cycles'
                                        ], 
                                        'sort' => ['AirframeComponentTimes.id' => 'DESC']
                                    ]
                                ]
                            ]
                        ]);

        //Get all parent and child items
        /*$cond = [
                    'AirframeComponentParts.plane_id'=>$params['aircraftId'], 
                    'OR'=>[
                        'AirframeComponentParts.id'=>$params['partId'],
                        'AirframeComponentParts.parent_id'=>$params['partId']
                    ]
                ];*/
        $childs = $this->parentChildRelObj->find()
                                ->where([
                                    'ParentChildRelations.plane_id'=>$params['aircraftId'], 
                                    'ParentChildRelations.parent_id'=>$params['partId']
                                ])
                                ->select(['airframe_component_part_id'])
                                ->enableHydration(false)->toArray();
        $partIds = array($params['partId']);
        if(!empty($childs)) {
            foreach ($childs as $key => $value) {
                $partIds[] = $value['airframe_component_part_id'];
            }
        }
        
        $cond = [
                    'AirframeComponentParts.plane_id'=>$params['aircraftId'],
                    'AirframeComponentParts.id IN'=>$partIds
                ];
        $partRecords = $this->airCompPartObj->find()
                        ->where($cond)
                        ->contain([
                            'Planes'=>[
                                'fields'=>[
                                    'Planes.id',
                                    'Planes.plane_code'
                                ]
                            ],
                            'AirframeComponents'=>[
                                'fields'=>[
                                    'AirframeComponents.id',
                                    'AirframeComponents.log_book',
                                    'AirframeComponents.position'
                                ],
                                'AirframeComponentTimes'=>[
                                    'fields'=>[
                                        'AirframeComponentTimes.id',
                                        'AirframeComponentTimes.airframe_component_id',
                                        'AirframeComponentTimes.hours',
                                        'AirframeComponentTimes.cycles' 
                                    ],
                                    'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                ]
                            ],
                            'AirframeComponentLastCw'=>[
                                'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                            ],
                            'PartInstalledTimes'=>[
                                'sort'=>['PartInstalledTimes.id'=>'DESC']
                            ]
                        ])->order(['AirframeComponentParts.parent_id'=>'ASC'])->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();

            $airCompParts = $this->airCompPartObj->patchEntity($airCompParts, $postData);//echo "<pre>";print_r($airCompParts);exit;
            if ($this->airCompPartObj->save($airCompParts)) {
                
                //Post data process and calculate nextdue
                $postData = $this->AirframeComponentPart->postDataProcess($postData);

                //Save related data in airframe_component_last_cw table
                $postData['airframe_component_part_id'] = $airCompParts->id;
                $postData['last_revised_by'] = $authUserData['id'];
                $partsDetails = $this->airCompLastCwObj->newEmptyEntity();
                
                $partsDetails = $this->airCompLastCwObj->patchEntity($partsDetails, $postData);
                $this->airCompLastCwObj->save($partsDetails);
                //End to save related data in airframe_component_last_cw table

                //Part installed time
                $partInstalledTimes = $this->partInstTimeObj->newEmptyEntity();
                
                $partInstalledTimes = $this->partInstTimeObj->patchEntity($partInstalledTimes, $postData);
                $this->partInstTimeObj->save($partInstalledTimes);
                //End part installed time

                $this->Flash->success(__('The aircraft component part has been saved.'));
                //return $this->redirect(['action' => 'index']);
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The aircraft component part could not be saved. Please, try again.'));
        }

        $removalReason = $this->AirframeComponentPart->getRemovalReason();

        $airCPComp = $this->AirframeComponentPart;
        $this->set(compact('airCompParts', 'actionItems', 'removalReason', 'partRecords', 'params', 'airCPComp'));
    }

    //Apply changed time to selected item
    public function applyToSelect()
    {
        $params = $this->request->getData();
        $pdata = array();
        if(!empty($params)) {
            foreach ($params['id'] as $key => $value) {
                if(!empty($params['comp_id']) && $params['comp_id'] == $value) {
                    $pdata['log_book'] = !empty($params['log_book'][$key]) ? $params['log_book'][$key] : '';
                    $pdata['log_date'] = !empty($params['log_date'][$key]) ? $params['log_date'][$key] : '';
                    $pdata['hours']    = !empty($params['hours'][$key]) ? $params['hours'][$key] : 0;
                    $pdata['cycles']   = !empty($params['cycles'][$key]) ? $params['cycles'][$key] : 0;
                }
            }

            $report = $this->airCompPartObj->find()
                    ->where(['AirframeComponentParts.id'=>$params['part_id']])
                    ->contain([ 
                        'Planes'=>[
                            'fields'=>[
                                'Planes.id',
                                'Planes.plane_code'
                            ]
                        ],
                        'AirframeComponents'=>[
                            'fields'=>[
                                'AirframeComponents.id',
                                'AirframeComponents.log_book',
                                'AirframeComponents.position'
                            ],
                            'AirframeComponentTimes'=>[
                                /*'fields'=>[
                                    'AirframeComponentTimes.id',
                                    'AirframeComponentTimes.airframe_component_id',
                                    'AirframeComponentTimes.hours',
                                    'AirframeComponentTimes.cycles' 
                                ],
                                'sort'=>['AirframeComponentTimes.id'=>'DESC']*/
                                'strategy' => 'select',
                                'queryBuilder' => function ($q) {
                                    return $q->order(['AirframeComponentTimes.id'=>'DESC'])->limit(1);
                                }
                            ]
                        ],
                        'AirframeComponentLastCw'=>[
                            //'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                            'strategy' => 'select',
                            'queryBuilder' => function ($q) {
                                return $q->order(['AirframeComponentLastCw.id'=>'DESC'])->limit(1);
                            }
                        ],
                    ])
                    ->enableHydration(false)->first();

            //calculations
            $data = array();
            if(!empty($report)) {                      
                //Start Next due
                $mos = $hrs = $afl = '';
                $date = !empty($pdata['log_date']) ? $this->AirframeComponentPart->dateFormat($pdata['log_date']) : '';
                
                $hrs = !empty($report['airframe_component_last_cw'][0]['required_frequency_hrs']) ? ($report['airframe_component_last_cw'][0]['required_frequency_hrs'] + (!empty($pdata['hours'])?$pdata['hours']:0) + (!empty($report['airframe_component_last_cw'][0]['adjustment_hrs'])?$report['airframe_component_last_cw'][0]['adjustment_hrs']:0)) : '';
                
                $afl = !empty($report['airframe_component_last_cw'][0]['required_frequency_afl']) ? ($report['airframe_component_last_cw'][0]['required_frequency_afl'] + (!empty($pdata['cycles'])?$pdata['cycles']:0) + (!empty($report['airframe_component_last_cw'][0]['adjustment_afl'])?$report['airframe_component_last_cw'][0]['adjustment_afl']:0)) : '';

                //Get mos
                $mos = $this->AirframeComponentPart->getMos($report['airframe_component_last_cw'][0], $date);

                if(!empty($mos) && $report['airframe_component_last_cw'][0]['eom'] == 1) {
                    $mos = strtoupper(date('t-M-Y', strtotime($mos)));
                }
                //End Next due

                $compHours  = !empty($report['airframe_component']['airframe_component_times'][0]['hours']) ? $report['airframe_component']['airframe_component_times'][0]['hours'] : 0;
                $compCycles = !empty($report['airframe_component']['airframe_component_times'][0]['cycles']) ? $report['airframe_component']['airframe_component_times'][0]['cycles'] : 0;

                //Start Remaining           
                $remData = $this->Report->getRemaining($mos);
                $totalRemD = $remData['totalRemD'];
                $remMos  = $remData['maintRemMos'];
                $remDays = $remData['maintRemDays'];
                $remHrs  = !empty($hrs) ? $hrs - $compHours : '';
                $remAfl  = !empty($afl) ? $afl - $compCycles : '';
                //End Remaining

                $hrs = !empty($hrs) ? round($hrs,1) : 0;
                $remHrs = !empty($remHrs) ? round($remHrs,1) : 0;

                $data = array(
                        'lastCwMos' => $pdata['log_date'],
                        'lastCwHrs' => $pdata['hours'],
                        'lastCwAfl' => $pdata['cycles'],
                        'nextMos' => !empty($mos) ? date('m-d-Y', strtotime($mos)) : '',
                        'nextHrs' => $hrs,
                        'nextAfl' => $afl,
                        'remMos'  => $remMos,
                        'remDays' => $remDays,
                        'remHrs'  => $remHrs,
                        'remAfl'  => $remAfl,
                        'override'=> $report['airframe_component_last_cw'][0]['override']
                    );
                $result = array('status'=>'success', 'data'=>$data);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>$data);
                echo json_encode($result);die;
            }
        } else {
            $result = array('status'=>'failure', 'data'=>$pdata);
            echo json_encode($result);die;
        }
    }

    //Apply changed time to selected item
    public function applyToSelectCustom()
    {
        $params = $this->request->getData();
        $pdata = array();
        if(!empty($params)) {
            $pdata['log_date'] = !empty($params['log_date']) ? $params['log_date'] : '';
            $pdata['hours']    = !empty($params['hours']) ? $params['hours'] : 0;
            $pdata['cycles']   = !empty($params['cycles']) ? $params['cycles'] : 0;
           
            $report = $this->airCompPartObj->find()
                    ->where(['AirframeComponentParts.id'=>$params['partId']])
                    ->contain([ 
                        'Planes'=>[
                            'fields'=>[
                                'Planes.id',
                                'Planes.plane_code'
                            ]
                        ],
                        'AirframeComponents'=>[
                            'fields'=>[
                                'AirframeComponents.id',
                                'AirframeComponents.log_book',
                                'AirframeComponents.position'
                            ],
                            'AirframeComponentTimes'=>[
                                'strategy' => 'select',
                                'queryBuilder' => function ($q) {
                                    return $q->order(['AirframeComponentTimes.id'=>'DESC'])->limit(1);
                                }
                            ]
                        ],
                        'AirframeComponentLastCw'=>[
                            //'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                            'strategy' => 'select',
                            'queryBuilder' => function ($q) {
                                return $q->order(['AirframeComponentLastCw.id'=>'DESC'])->limit(1);
                            }
                        ],
                    ])
                    ->enableHydration(false)->first();

            //calculations
            $data = array();
            if(!empty($report)) {
                //Start Next due
                $mos = $hrs = $afl = '';
                $date = !empty($pdata['log_date']) ? $this->AirframeComponentPart->dateFormat($pdata['log_date']) : '';
                
                $hrs = !empty($report['airframe_component_last_cw'][0]['required_frequency_hrs']) ? ($report['airframe_component_last_cw'][0]['required_frequency_hrs'] + (!empty($pdata['hours'])?$pdata['hours']:0) + (!empty($report['airframe_component_last_cw'][0]['adjustment_hrs'])?$report['airframe_component_last_cw'][0]['adjustment_hrs']:0)) : '';
                
                $afl = !empty($report['airframe_component_last_cw'][0]['required_frequency_afl']) ? ($report['airframe_component_last_cw'][0]['required_frequency_afl'] + (!empty($pdata['cycles'])?$pdata['cycles']:0) + (!empty($report['airframe_component_last_cw'][0]['adjustment_afl'])?$report['airframe_component_last_cw'][0]['adjustment_afl']:0)) : '';

                //Get mos
                $mos = $this->AirframeComponentPart->getMos($report['airframe_component_last_cw'][0], $date);

                if(!empty($mos) && $report['airframe_component_last_cw'][0]['eom'] == 1) {
                    $mos = strtoupper(date('t-M-Y', strtotime($mos)));
                }
                //End Next due

                $compHours  = !empty($report['airframe_component']['airframe_component_times'][0]['hours']) ? $report['airframe_component']['airframe_component_times'][0]['hours'] : 0;
                $compCycles = !empty($report['airframe_component']['airframe_component_times'][0]['cycles']) ? $report['airframe_component']['airframe_component_times'][0]['cycles'] : 0;

                //Start Remaining           
                $remData = $this->Report->getRemaining($mos);
                $totalRemD = $remData['totalRemD'];
                $remMos  = $remData['maintRemMos'];
                $remDays = $remData['maintRemDays'];
                $remHrs  = !empty($hrs) ? $hrs - $compHours : '';
                $remAfl  = !empty($afl) ? $afl - $compCycles : '';
                //End Remaining

                $hrs  = !empty($hrs) ? round($hrs,1) : 0;
                $remHrs  = !empty($remHrs) ? round($remHrs,1) : 0;

                $data = array(
                        'lastCwMos' => $pdata['log_date'],
                        'lastCwHrs' => $pdata['hours'],
                        'lastCwAfl' => $pdata['cycles'],
                        'nextMos' => !empty($mos) ? date('m-d-Y', strtotime($mos)) : '',
                        'nextHrs' => $hrs,
                        'nextAfl' => $afl,
                        'remMos'  => $remMos,
                        'remDays' => $remDays,
                        'remHrs'  => $remHrs,
                        'remAfl'  => $remAfl,
                        'override'=> $report['airframe_component_last_cw'][0]['override']
                    );
                $result = array('status'=>'success', 'data'=>$data);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>$data);
                echo json_encode($result);die;
            }
        } else {
            $result = array('status'=>'failure', 'data'=>$pdata);
            echo json_encode($result);die;
        }
    }

    public function applyToAll()
    {
        $params = $this->request->getData();
        $data = array();
        if(!empty($params)) {
            $pdata = array();
            foreach ($params['id'] as $key => $value) {
                if(!empty($params['comp_id']) && $params['comp_id'] == $value) {
                    $pdata['log_book'] = !empty($params['log_book'][$key]) ? $params['log_book'][$key] : '';
                    $pdata['log_date'] = !empty($params['log_date'][$key]) ? $params['log_date'][$key] : '';
                    $pdata['hours']    = !empty($params['hours'][$key]) ? $params['hours'][$key] : 0;
                    $pdata['cycles']   = !empty($params['cycles'][$key]) ? $params['cycles'][$key] : 0;
                }
            }

            $data = array(
                    'planeId' => $params['plane_id'],
                    'compId' => $params['comp_id'],
                    'partId' => $params['partId'],
                    'lastCwMos' => $pdata['log_date'],
                    'lastCwHrs' => $pdata['hours'],
                    'lastCwAfl' => $pdata['cycles'],
                    'addHrVal' => $params['addHrVal'],
                    'addCyVal' => $params['addCyVal'],
                );
            $result = array('status'=>'success', 'data'=>$data);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$data);
            echo json_encode($result);die;
        }
    }

    public function applyToAllCustom()
    {
        $params = $this->request->getData();
        $data = array();
        $pdata = array();
        if(!empty($params)) {
            $pdata['log_date'] = !empty($params['log_date']) ? $params['log_date'] : '';
            $pdata['hours']    = !empty($params['hours']) ? $params['hours'] : 0;
            $pdata['cycles']   = !empty($params['cycles']) ? $params['cycles'] : 0;
            
            $data = array(
                    'planeId' => $params['plane_id'],
                    'lastCwMos' => $pdata['log_date'],
                    'lastCwHrs' => $pdata['hours'],
                    'lastCwAfl' => $pdata['cycles'],
                    //'addHrVal' => $params['addHrVal'],
                    //'addCyVal' => $params['addCyVal'],
                );
            $result = array('status'=>'success', 'data'=>$data);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$data);
            echo json_encode($result);die;
        }
    }

    //Change next due, remaining and interval values based on recurring or threshold selected
    public function errorCNextdueRem()
    {
        $params = $this->request->getData();
        $report = $this->airCompPartObj->find()
                    ->where(['AirframeComponentParts.id'=>$params['partId']])
                    ->contain([ 
                        'Planes'=>[
                            'fields'=>[
                                'Planes.id',
                                'Planes.plane_code'
                            ]
                        ],
                        'AirframeComponents'=>[
                            'fields'=>[
                                'AirframeComponents.id',
                                'AirframeComponents.log_book',
                                'AirframeComponents.position'
                            ],
                            'AirframeComponentTimes'=>[
                                'fields'=>[
                                    'AirframeComponentTimes.id',
                                    'AirframeComponentTimes.airframe_component_id',
                                    'AirframeComponentTimes.hours',
                                    'AirframeComponentTimes.cycles' 
                                ],
                                'sort'=>['AirframeComponentTimes.id'=>'DESC']
                            ]
                        ],
                        'AirframeComponentLastCw'=>[
                            'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                        ],
                    ])
                    ->enableHydration(false)->first();

        //calculations
        $data = array();
        if(!empty($report)) {
            //Check if part months value is date then convert it to months and days
            if(!empty($params['partMos']) && !is_numeric($params['partMos'])) {
                $remData = $this->checkmydate(trim($params['partMos']), trim($params['lastCwMos']));
                if(!empty($remData) && is_array($remData)) {
                    $params['intvAdjMos'] = $remData['maintRemMos'];
                    $params['intvAdjDay'] = $remData['maintRemDays'];
                } else {
                    $params['intvAdjMos'] = 0;
                    $params['intvAdjDay'] = 0;
                }
            } elseif(!empty($params['partMos']) && is_numeric(trim($params['partMos']))) {
                $params['intvAdjMos'] = -trim($params['partMos']);
                $params['intvAdjDay'] = 0;

            } elseif(!empty($params['overhaulType']) && empty($params['partMos'])) {
                $params['intvAdjMos'] = 0;
                $params['intvAdjDay'] = 0;
            }

            //Adjust overhaul and repair
            if(!empty($params['partHrs'])) {
                $params['intvAdjHrs'] = -trim($params['partHrs']);

            } elseif(!empty($params['overhaulType']) && empty($params['partHrs'])) {
                $params['intvAdjHrs'] = 0;
            }

            if(!empty($params['partAfl'])) {
                $params['intvAdjAfl'] = -trim($params['partAfl']);

            } elseif(!empty($params['overhaulType']) && empty($params['partAfl'])) {
                $params['intvAdjAfl'] = 0;
            }
            
            //Get data correction and nextdue calculation
            $mos = $hrs = $afl = $msc = '';
            $getRes = $this->AirframeComponentPart->getDataProcess($params, $report);
            if(!empty($getRes)) {
                $mos = $getRes['mos'];
                $hrs = $getRes['hrs'];
                $afl = $getRes['afl'];
            }

            //Component Times
            $compHours  = !empty($report['airframe_component']['airframe_component_times'][0]['hours']) ? $report['airframe_component']['airframe_component_times'][0]['hours'] : 0;
            $compCycles = !empty($report['airframe_component']['airframe_component_times'][0]['cycles']) ? $report['airframe_component']['airframe_component_times'][0]['cycles'] : 0;

            //Start Remaining           
            $remData = $this->Report->getRemaining($mos);
            $totalRemD = $remData['totalRemD'];
            $remMos  = $remData['maintRemMos'];
            $remDays = $remData['maintRemDays'];
            $remHrs  = !empty($hrs) ? $hrs - $compHours : '';
            $remAfl  = !empty($afl) ? $afl - $compCycles : '';
            //End Remaining

            $roundHrs = !empty($hrs) ? round($hrs,1) : '0';
            $roundremHrs = !empty($remHrs) ? round($remHrs,1) : '0';

            $data = array(
                    'nextMos' => !empty($mos) ? date('m-d-Y', strtotime($mos)) : '',
                    'nextHrs' => $roundHrs,
                    'nextAfl' => $afl,
                    'remMos'  => $remMos,
                    'remDays' => $remDays,
                    'remHrs'  => $roundremHrs,
                    'remAfl'  => $remAfl,
                    'override'=> $report['airframe_component_last_cw'][0]['override'],
                    'intvAdjMos' => !empty($params['intvAdjMos']) ? $params['intvAdjMos'] : 0,
                    'intvAdjDay' => !empty($params['intvAdjDay']) ? $params['intvAdjDay'] : 0,
                    'intvAdjHrs' => !empty($params['intvAdjHrs']) ? $params['intvAdjHrs'] : 0,
                    'intvAdjAfl' => !empty($params['intvAdjAfl']) ? $params['intvAdjAfl'] : 0
                );
            $result = array('status'=>'success', 'data'=>$data);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$data);
            echo json_encode($result);die;
        }
    }

    //Add Compliance
    public function addCompliance($id = null)
    {
        $ftype = '';
        if(empty($id)) {
            $params = $_GET;
            $id = $params['partId'];
            $ftype = !empty($params['ftype']) ? $params['ftype'] : '';
        }
        
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Component Parts', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Parts'];
            }
        }
        
        $airCompParts = $this->airCompPartObj->get($params['partId'], 
                        [
                            'contain'=> [
                                'Planes'=>[
                                    'fields'=>[
                                        'Planes.id',
                                        'Planes.plane_code'
                                    ],
                                    'AirframeComponents'=>[
                                        'fields'=>[
                                            'AirframeComponents.id',
                                            'AirframeComponents.plane_id',
                                            'AirframeComponents.log_book',
                                            'AirframeComponents.position',
                                            'AirframeComponents.description',
                                            'AirframeComponents.serial_no'
                                        ],
                                        'sort'=>['FIELD(AirframeComponents.log_book, "Aircraft", "Engine", "Propeller", "Air Conditioner", "Misc")', 'AirframeComponents.position'=>'ASC'],
                                        'AirframeComponentTimes'=>[
                                            'fields'=>[
                                                'AirframeComponentTimes.id',
                                                'AirframeComponentTimes.plane_id',
                                                'AirframeComponentTimes.airframe_component_id',
                                                'AirframeComponentTimes.log_date',
                                                'AirframeComponentTimes.hours',
                                                'AirframeComponentTimes.cycles' 
                                            ],
                                            'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                        ]
                                    ]
                                ],
                                'AirframeComponentLastCw'=>[
                                    'sort' => ['AirframeComponentLastCw.id' => 'DESC']
                                ], 
                                'AirframeComponents'=>[
                                    'AirframeComponentTimes'=>[
                                        'fields'=>[
                                            'AirframeComponentTimes.airframe_component_id', 
                                            'AirframeComponentTimes.hours', 
                                            'AirframeComponentTimes.cycles'
                                        ], 
                                        'sort' => ['AirframeComponentTimes.id' => 'DESC']
                                    ]
                                ]
                            ]
                        ]);

        //Get all parent and child items
        /*$cond = [
                    'AirframeComponentParts.plane_id'=>$params['aircraftId'], 
                    'OR'=>[
                        'AirframeComponentParts.id'=>$params['partId'],
                        'AirframeComponentParts.parent_id'=>$params['partId']
                    ]
                ];*/
        $childs = $this->parentChildRelObj->find()
                                ->where([
                                    'ParentChildRelations.plane_id'=>$params['aircraftId'], 
                                    'ParentChildRelations.parent_id'=>$params['partId']
                                ])
                                ->select(['airframe_component_part_id'])
                                ->enableHydration(false)->toArray();
        $partIds = array($params['partId']);
        if(!empty($childs)) {
            foreach ($childs as $key => $value) {
                $partIds[] = $value['airframe_component_part_id'];
            }
        }

        $cond = [
                    'AirframeComponentParts.plane_id'=>$params['aircraftId'],
                    'AirframeComponentParts.id IN'=>$partIds
                ];
        $partRecords = $this->airCompPartObj->find()
                        ->where($cond)
                        ->contain([ 
                            'Planes'=>[
                                'fields'=>[
                                    'Planes.id',
                                    'Planes.plane_code'
                                ]
                            ],
                            'AirframeComponents'=>[
                                'fields'=>[
                                    'AirframeComponents.id',
                                    'AirframeComponents.log_book',
                                    'AirframeComponents.position'
                                ],
                                'AirframeComponentTimes'=>[
                                    'fields'=>[
                                        'AirframeComponentTimes.id',
                                        'AirframeComponentTimes.airframe_component_id',
                                        'AirframeComponentTimes.hours',
                                        'AirframeComponentTimes.cycles' 
                                    ],
                                    'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                ]
                            ],
                            'AirframeComponentLastCw'=>[
                                'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                            ],
                            'PartInstalledTimes'=>[
                                'sort'=>['PartInstalledTimes.id'=>'DESC']
                            ]
                        ])->order(['AirframeComponentParts.parent_id'=>'ASC'])->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $airCompParts = $this->airCompPartObj->patchEntity($airCompParts, $postData);
            
            if ($this->airCompPartObj->save($airCompParts)) {
                
                //Post data process and calculate nextdue
                $postData = $this->AirframeComponentPart->postDataProcess($postData);
                
                //Save related data in airframe_component_last_cw table
                $postData['airframe_component_part_id'] = $airCompParts->id;
                $postData['last_revised_by'] = $authUserData['id'];
                $partsDetails = $this->airCompLastCwObj->newEmptyEntity();
                $partsDetails = $this->airCompLastCwObj->patchEntity($partsDetails, $postData);
                
                $this->airCompLastCwObj->save($partsDetails);
                //End to save related data in airframe_component_last_cw table

                //Part installed time
                $partInstalledTimes = $this->partInstTimeObj->newEmptyEntity();
                
                $partInstalledTimes = $this->partInstTimeObj->patchEntity($partInstalledTimes, $postData);
                $this->partInstTimeObj->save($partInstalledTimes);
                //End part installed time

                //save to aircraft history table
                $this->AircraftHistory->saveAirframeComponentPartHistory($partsDetails);

                $this->Flash->success(__('The aircraft component part has been saved.'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The aircraft component part could not be saved. Please, try again.'));
        }
                        
        $removalReason = $this->AirframeComponentPart->getRemovalReason();
        $airCPComp = $this->AirframeComponentPart;
        $this->set(compact('airCompParts', 'actionItems', 'removalReason', 'partRecords', 'params', 'ftype', 'airCPComp'));
    }

    //Add Custom Compliance
    public function addCustomCompliance()
    {
        $params = $_GET;
        $ftype = !empty($params['ftype']) ? $params['ftype'] : '';
               
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Airframe Component Parts', $actionStatus))
            {
                $actionItems = $actionStatus['Airframe Component Parts'];
            }
        }
        
        $airCompParts = $this->planeObj->get($params['aircraftId'], 
                        [
                            'fields'=>['id', 'plane_code'],
                            'contain'=> [
                                'AirframeComponents'=>[
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
                                            'AirframeComponentTimes.plane_id',
                                            'AirframeComponentTimes.airframe_component_id',
                                            'AirframeComponentTimes.log_date',
                                            'AirframeComponentTimes.hours',
                                            'AirframeComponentTimes.cycles' 
                                        ],
                                        'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                    ]
                                ]
                            ]
                        ]);
        $partIds = explode(',', $params['partids']);
        $cond = [
                    'AirframeComponentParts.plane_id'=>$params['aircraftId'],
                    'AirframeComponentParts.id IN'=>$partIds
                ];
        $partRecords = $this->airCompPartObj->find()
                        ->where($cond)
                        ->contain([ 
                            'Planes'=>[
                                'fields'=>[
                                    'Planes.id',
                                    'Planes.plane_code'
                                ]
                            ],
                            'AirframeComponents'=>[
                                'fields'=>[
                                    'AirframeComponents.id',
                                    'AirframeComponents.log_book',
                                    'AirframeComponents.position'
                                ],
                                'AirframeComponentTimes'=>[
                                    'fields'=>[
                                        'AirframeComponentTimes.id',
                                        'AirframeComponentTimes.airframe_component_id',
                                        'AirframeComponentTimes.hours',
                                        'AirframeComponentTimes.cycles' 
                                    ],
                                    'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                ]
                            ],
                            'AirframeComponentLastCw'=>[
                                'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                            ],
                            'PartInstalledTimes'=>[
                                'sort'=>['PartInstalledTimes.id'=>'DESC']
                            ]
                        ])->order(['AirframeComponentParts.parent_id'=>'ASC'])->toArray();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            $airCompParts = $this->airCompPartObj->get($postData['part_id'], 
                        [
                            'contain'=> [
                                'Planes'=>[
                                    'fields'=>[
                                        'Planes.id',
                                        'Planes.plane_code'
                                    ],
                                    'AirframeComponents'=>[
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
                                                'AirframeComponentTimes.plane_id',
                                                'AirframeComponentTimes.airframe_component_id',
                                                'AirframeComponentTimes.log_date',
                                                'AirframeComponentTimes.hours',
                                                'AirframeComponentTimes.cycles' 
                                            ],
                                            'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                        ]
                                    ]
                                ],
                                'AirframeComponentLastCw'=>[
                                    'sort' => ['AirframeComponentLastCw.id' => 'DESC']
                                ], 
                                'AirframeComponents'=>[
                                    'AirframeComponentTimes'=>[
                                        'fields'=>[
                                            'AirframeComponentTimes.airframe_component_id', 
                                            'AirframeComponentTimes.hours', 
                                            'AirframeComponentTimes.cycles'
                                        ], 
                                        'sort' => ['AirframeComponentTimes.id' => 'DESC']
                                    ]
                                ]
                            ]
                        ]);
            
            $airCompParts = $this->airCompPartObj->patchEntity($airCompParts, $postData);
            
            if ($this->airCompPartObj->save($airCompParts)) {
                //Post data process and calculate nextdue
                $postData = $this->AirframeComponentPart->postDataProcess($postData);

                //Save related data in airframe_component_last_cw table

                $postData['airframe_component_part_id'] = $airCompParts->id;
                $postData['last_revised_by'] = $authUserData['id'];
                $partsDetails = $this->airCompLastCwObj->newEmptyEntity();
                $partsDetails = $this->airCompLastCwObj->patchEntity($partsDetails, $postData);
                $this->airCompLastCwObj->save($partsDetails);
                //End to save related data in airframe_component_last_cw table

                //Part installed time
                $partInstalledTimes = $this->partInstTimeObj->newEmptyEntity();
                
                $partInstalledTimes = $this->partInstTimeObj->patchEntity($partInstalledTimes, $postData);
                $this->partInstTimeObj->save($partInstalledTimes);
                //End part installed time

                $this->Flash->success(__('The aircraft component part has been saved.'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('The aircraft component part could not be saved. Please, try again.'));
        }
                        
        $removalReason = $this->AirframeComponentPart->getRemovalReason();
        $airCPComp = $this->AirframeComponentPart;
        $this->set(compact('airCompParts', 'actionItems', 'removalReason', 'partRecords', 'params', 'ftype', 'airCPComp'));
    }

    //Save components time
    public function saveAirCompTimes()
    {
        $params = $this->request->getData();
        $type = '';
        if(!empty($params['type']) && $params['type'] == 'all') {
            $type = $params['type'];
        }
        $authUserData = $this->Authentication->getResult()->getData();
        $results = array();
        if(!empty($params['id']) && (!empty($params['tstatus']) && $params['tstatus'] == 'yes')) {
            $i=0;
            $historyId = [];
            $time_id = '';
            foreach ($params['id'] as $key => $value) {
                $results[$key]['plane_id'] = $params['plane_id'];
                $results[$key]['airframe_component_id'] = $value;
                $results[$key]['log_date'] = $params['log_date'][$key].' '.date('H:i:s');
                $results[$key]['hours'] = $params['hours'][$key];
                
                //Get user and accrued times
                $results[$key]['user_id'] = $authUserData['id'];
                $hours_accrued = $results[$key]['hours'] - $params['prvhours'][$key];
                $results[$key]['hours_accrued'] = !empty($hours_accrued) ? round($hours_accrued,1) : 0;
                if(!in_array($params['log_book'][$key], ['Propeller','Air Conditioner'])) {
                    $results[$key]['cycles'] = $params['cycles'][$key];

                    $results[$key]['cycles'] = !empty($results[$key]['cycles']) ? $results[$key]['cycles'] : 0;
                    $params['prvcycles'][$key] = !empty($params['prvcycles'][$key]) ? $params['prvcycles'][$key] : 0;
                    $results[$key]['cycles_accrued'] = $results[$key]['cycles'] - $params['prvcycles'][$key];
                }
                
                $compTimes = $this->airCompTimeObj->newEmptyEntity();
                $compTimes = $this->airCompTimeObj->patchEntity($compTimes, $results[$key]);
                if($this->airCompTimeObj->save($compTimes)) {
                    $historyId[$i] = $compTimes->id;
                    if($params['log_book'][$key] == 'Airframe') {
                        $time_id = $compTimes->id;
                    }
                }
                $i++;
            }

            //Save extra details
            $data = array();
            $extDetail = $this->extDetailObj->newEmptyEntity();
            $data['plane_id']  = $params['plane_id'];
            $data['airframe_component_time_id'] = $time_id;
            $data['history_ids'] = implode(",",$historyId);
            $data['reference'] = "";
            $data['notes']     = "";
            $data['date']      = new \Cake\I18n\FrozenTime('now');
            $extDetail = $this->extDetailObj->patchEntity($extDetail, $data);
            $this->extDetailObj->save($extDetail);

            $res = array('status'=>'success', 'message'=>'Report Time added successfully.', 'type'=>$type, 'tstatus'=>$params['tstatus']);
            echo json_encode($res);die;
        } else if(!empty($params['tstatus']) && $params['tstatus'] == 'no') {
            $res = array('status'=>'success', 'message'=>'Report Time will not update.', 'type'=>$type, 'tstatus'=>$params['tstatus']);
            echo json_encode($res);die;
        } else {
            $res = array('status'=>'failure', 'message'=>'Please enter details.', 'type'=>$type);
            echo json_encode($res);die;
        }
    }

    //Update all items
    public function updateAllItems()
    {
        $params = $this->request->getData();
        $pdata = array();
        foreach ($params['id'] as $key => $value) {
            if($params['comp_id'] == $value) {
                $pdata['partId']   = $params['partId'];
                $pdata['aircraftId'] = $params['plane_id'];
                $pdata['log_book'] = !empty($params['log_book'][$key]) ? $params['log_book'][$key] : '';
                $pdata['log_date'] = !empty($params['log_date'][$key]) ? $params['log_date'][$key] : '';
                $pdata['hours']    = !empty($params['hours'][$key]) ? $params['hours'][$key] : 0;
                $pdata['cycles']   = !empty($params['cycles'][$key]) ? $params['cycles'][$key] : 0;
            }
        }
        $authUserData = $this->Authentication->getResult()->getData();
        //Get all parent and child items
        $childs = $this->parentChildRelObj->find()
                                ->where([
                                    'ParentChildRelations.plane_id'=>$pdata['aircraftId'], 
                                    'ParentChildRelations.parent_id'=>$pdata['partId']
                                ])
                                ->select(['airframe_component_part_id'])
                                ->enableHydration(false)->toArray();
        $partIds = array($pdata['partId']);
        if(!empty($childs)) {
            foreach ($childs as $key => $value) {
                $partIds[] = $value['airframe_component_part_id'];
            }
        }
        
        $cond = [
                    'AirframeComponentParts.plane_id'=>$pdata['aircraftId'],
                    'AirframeComponentParts.id IN'=>$partIds
                ];
        $partRecords = $this->airCompPartObj->find()
                        ->where($cond)
                        ->contain([ 
                                'Planes'=>[
                                    'fields'=>[
                                        'Planes.id',
                                        'Planes.plane_code'
                                    ]
                                ],
                                'AirframeComponents'=>[
                                    'fields'=>[
                                        'AirframeComponents.id',
                                        'AirframeComponents.log_book',
                                        'AirframeComponents.position'
                                    ],
                                    'AirframeComponentTimes'=>[
                                        'fields'=>[
                                            'AirframeComponentTimes.id',
                                            'AirframeComponentTimes.airframe_component_id',
                                            'AirframeComponentTimes.hours',
                                            'AirframeComponentTimes.cycles' 
                                        ],
                                        'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                    ]
                                ],
                                'AirframeComponentLastCw'=>[
                                    'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                                ],
                                'PartInstalledTimes'=>[
                                    'sort'=>['PartInstalledTimes.id'=>'DESC']
                                ]
                            ])
                        ->order(['AirframeComponentParts.parent_id'=>'ASC'])->enableHydration(false)->toArray();

        if(!empty($partRecords)) {
            foreach ($partRecords as $key=>$compParts) {
                $airCompParts = $this->airCompPartObj->get($compParts['id'],
                            [
                                'contain'=> [
                                    'Planes'=>[
                                        'fields'=>[
                                            'Planes.id',
                                            'Planes.plane_code'
                                        ]
                                    ],
                                    'AirframeComponentLastCw'=>[
                                        'sort' => ['AirframeComponentLastCw.id' => 'DESC']
                                    ], 
                                    'AirframeComponents'=>[
                                        'AirframeComponentTimes'=>[
                                            'fields'=>[
                                                'AirframeComponentTimes.airframe_component_id', 
                                                'AirframeComponentTimes.hours', 
                                                'AirframeComponentTimes.cycles'
                                            ], 
                                            'sort' => ['AirframeComponentTimes.id' => 'DESC']
                                        ]
                                    ]
                                ]
                            ]);
                
                $airCompParts = $this->airCompPartObj->patchEntity($airCompParts, $compParts);
                if ($this->airCompPartObj->save($airCompParts)) {
                    $compParts['airframe_component_last_cw'][0]['last_cw_date'] = $pdata['log_date'];
                    $compParts['airframe_component_last_cw'][0]['last_cw_hrs'] = $pdata['hours'];
                    $compParts['airframe_component_last_cw'][0]['last_cw_afl'] = $pdata['cycles'];

                    //Post data process and calculate nextdue
                    if(!empty($compParts['airframe_component_last_cw'][0])) {
                        $compParts['airframe_component_last_cw'][0] = $this->AirframeComponentPart->postDataProcess($compParts['airframe_component_last_cw'][0]);
                    }
                    
                    $postData = $compParts['airframe_component_last_cw'][0];
                    //Save related data in airframe_component_last_cw table
                    $postData['airframe_component_part_id'] = $airCompParts->id;
                    $postData['last_revised_by'] = $authUserData['id'];                    
                    $partsDetails = $this->airCompLastCwObj->newEmptyEntity();
                    $partsDetails = $this->airCompLastCwObj->patchEntity($partsDetails, $postData);
                    $this->airCompLastCwObj->save($partsDetails);
                    //End to save related data in airframe_component_last_cw table
                }
            }
            $res = array('status'=>'success', 'message'=>'Items updated successfully.');
            echo json_encode($res);die;
        } else {
            $res = array('status'=>'failure', 'message'=>'Items not updated.');
            echo json_encode($res);die;
        }
    }

    //Update all items
    public function updateCustomItems()
    {
        $params = $this->request->getData();
        $pdata = array();
        $pdata['partids']   = $params['partids'];
        $pdata['aircraftId'] = $params['plane_id'];
        $pdata['log_date'] = !empty($params['hlog_date']) ? $params['hlog_date'] : '';
        $pdata['hours']    = !empty($params['hhours']) ? $params['hhours'] : 0;
        $pdata['cycles']   = !empty($params['hcycles']) ? $params['hcycles'] : 0;
        
        $authUserData = $this->Authentication->getResult()->getData();
        $partIds = explode(',', $params['partids']);
        $cond = [
                    'AirframeComponentParts.plane_id'=>$pdata['aircraftId'],
                    'AirframeComponentParts.id IN'=>$partIds
                ];
        $partRecords = $this->airCompPartObj->find()
                        ->where($cond)
                        ->contain([ 
                                'Planes'=>[
                                    'fields'=>[
                                        'Planes.id',
                                        'Planes.plane_code'
                                    ]
                                ],
                                'AirframeComponents'=>[
                                    'fields'=>[
                                        'AirframeComponents.id',
                                        'AirframeComponents.log_book',
                                        'AirframeComponents.position'
                                    ],
                                    'AirframeComponentTimes'=>[
                                        'fields'=>[
                                            'AirframeComponentTimes.id',
                                            'AirframeComponentTimes.airframe_component_id',
                                            'AirframeComponentTimes.hours',
                                            'AirframeComponentTimes.cycles' 
                                        ],
                                        'sort'=>['AirframeComponentTimes.id'=>'DESC']
                                    ]
                                ],
                                'AirframeComponentLastCw'=>[
                                    'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                                ],
                                'PartInstalledTimes'=>[
                                    'sort'=>['PartInstalledTimes.id'=>'DESC']
                                ]
                            ])
                        ->order(['AirframeComponentParts.parent_id'=>'ASC'])->enableHydration(false)->toArray();

        if(!empty($partRecords)) {
            foreach ($partRecords as $key=>$compParts) {
                $airCompParts = $this->airCompPartObj->get($compParts['id'], 
                            [
                                'contain'=> [
                                    'Planes'=>[
                                        'fields'=>[
                                            'Planes.id',
                                            'Planes.plane_code'
                                        ]
                                    ],
                                    'AirframeComponentLastCw'=>[
                                        'sort' => ['AirframeComponentLastCw.id' => 'DESC']
                                    ], 
                                    'AirframeComponents'=>[
                                        'AirframeComponentTimes'=>[
                                            'fields'=>[
                                                'AirframeComponentTimes.airframe_component_id', 
                                                'AirframeComponentTimes.hours', 
                                                'AirframeComponentTimes.cycles'
                                            ], 
                                            'sort' => ['AirframeComponentTimes.id' => 'DESC']
                                        ]
                                    ]
                                ]
                            ]);
                
                $airCompParts = $this->airCompPartObj->patchEntity($airCompParts, $compParts);
                if ($this->airCompPartObj->save($airCompParts)) {
                    $compParts['airframe_component_last_cw'][0]['last_cw_date'] = $pdata['log_date'];
                    $compParts['airframe_component_last_cw'][0]['last_cw_hrs'] = $pdata['hours'];
                    $compParts['airframe_component_last_cw'][0]['last_cw_afl'] = $pdata['cycles'];

                    //Post data process and calculate nextdue
                    if(!empty($compParts['airframe_component_last_cw'][0])) {
                        $compParts['airframe_component_last_cw'][0] = $this->AirframeComponentPart->postDataProcess($compParts['airframe_component_last_cw'][0]);
                    }
                    
                    $postData = $compParts['airframe_component_last_cw'][0];
                    //Save related data in airframe_component_last_cw table
                    $postData['airframe_component_part_id'] = $airCompParts->id;
                    $postData['last_revised_by'] = $authUserData['id'];                    
                    $partsDetails = $this->airCompLastCwObj->newEmptyEntity();
                    $partsDetails = $this->airCompLastCwObj->patchEntity($partsDetails, $postData);
                    $this->airCompLastCwObj->save($partsDetails);
                    //End to save related data in airframe_component_last_cw table
                }
            }
            $res = array('status'=>'success', 'message'=>'The aircraft component part has been saved.');
            echo json_encode($res);die;
        } else {
            $res = array('status'=>'failure', 'message'=>'The aircraft component part not saved.');
            echo json_encode($res);die;
        }
    }

}