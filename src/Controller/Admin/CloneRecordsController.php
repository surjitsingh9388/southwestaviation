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
 * CloneRecords Controller
 */
class CloneRecordsController extends AppController
{
    private $planeObj;
    private $airCompObj;
    private $airCompPartObj;
    private $airCompTimeObj;
    private $utilizationObj;
    private $lastCwObj;
    private $groupObj;

    public $paginate = array(
        'limit' => 10
    );
    
    public function initialize() 
    {
        parent::initialize();
        $this->loadComponent('Plane');
        $this->loadComponent('AirframeComponent');
        $this->loadComponent('AirframeCategory');
        $this->loadComponent('AtaCode');

        $this->planeObj = TableRegistry::get('Planes');
        $this->airCompObj = TableRegistry::get('AirframeComponents');
        $this->airCompPartObj = TableRegistry::get('AirframeComponentParts');
        $this->airCompTimeObj = TableRegistry::get('AirframeComponentTimes');
        $this->utilizationObj = TableRegistry::get('Utilizations');
        $this->lastCwObj = TableRegistry::get('AirframeComponentLastCw');
        $this->groupObj = TableRegistry::get('Groups');
    }
    
    public function index()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Clone Records', $actionStatus))
            {
                $actionItems = $actionStatus['Clone Records'];
            }
        }

        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        $this->set(compact('actionItems', 'planes'));
    }

    public function cloneAircraft()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Clone Records', $actionStatus))
            {
                $actionItems = $actionStatus['Clone Records'];
            }
        }

        $postData = $this->request->getData();           
        //Query to get all associated records of aircraft
        $records = $this->planeObj->find()
            ->where(['Planes.id' => $postData['plane_id']])
            ->contain([
                'AirframeComponents'=>[
                    'AirframeComponentTimes'=>[
                        'sort'=>['AirframeComponentTimes.id'=>'DESC']
                    ],
                    'Utilizations'=>[
                        'sort'=>['Utilizations.id'=>'DESC']
                    ],
                    'AirframeComponentParts'=>[
                        'sort'=>['AirframeComponentParts.id'=>'ASC'],
                        'AirframeComponentLastCw'=>[
                            'sort' => ['AirframeComponentLastCw.id' => 'DESC']
                        ],
                        'Groups'
                    ]
                ] 
            ])
            ->enableHydration(false)->first();

        $status = 'failure';
        if(!empty($records)) {
            unset($postData['plane_id']);
            if(!empty($postData['add_info']) && $postData['add_info'] == 1) {
                //Checkbox selected
                $planeData = $postData;
            } else {
                //Checkbox not selected
                $planeData['plane_code']                  = $postData['plane_code'];
                $planeData['plane_name']                  = $records['plane_name'];
                $planeData['plane_serial_number']         = $records['plane_serial_number'];
                $planeData['airworthiness_date']          = $records['airworthiness_date'];
                $planeData['plane_type']                  = $records['plane_type'];
                $planeData['federal_aviation_regulation'] = $records['federal_aviation_regulation'];
                $planeData['hours']                       = $records['hours'];
                $planeData['cycles']                      = $records['cycles'];
                $planeData['address']                     = $records['address'];
                $planeData['manufacturered_by']           = $records['manufacturered_by'];
                $planeData['manufacturered_on']           = $records['manufacturered_on'];
            }

            $aircraft = $this->planeObj->newEntity();
            $aircraft = $this->planeObj->patchEntity($aircraft, $planeData);
            if ($this->planeObj->save($aircraft)) {
                $planeId = $aircraft->id;
                foreach ($records['airframe_components'] as $key => $value) {
                    //Add component data
                    $compData['plane_id']    = $planeId;
                    $compData['log_book']    = $value['log_book'];
                    $compData['position']    = $value['position'];
                    $compData['description'] = $value['description'];
                    $compData['serial_no']   = $value['serial_no'];
                    $aircraftComponent = $this->airCompObj->newEntity();
                    $aircraftComponent = $this->airCompObj->patchEntity($aircraftComponent, $compData);
                    $this->airCompObj->save($aircraftComponent);

                    $compId = $aircraftComponent->id;

                    //Component times
                    if(!empty($value['airframe_component_times'])) {
                        $this->saveComponentTime($planeId, $compId, $value['airframe_component_times'][0]);
                    }

                    //Utilizations
                    if(!empty($value['utilizations'])) {
                        $this->saveUtilization($planeId, $compId, $value['utilizations'][0]);
                    }

                    //Add parts
                    if(!empty($value['airframe_component_parts'])) {
                        $status = $this->saveParts($planeId, $compId, $value['airframe_component_parts'], $postData['exclude_details']);
                    }
                }

                $result = array('status'=>$status, 'message'=>'The aircraft has been duplicated successfully.');
                echo json_encode($result);die;
            }

            $result = array('status'=>$status, 'message'=>'The aircraft data could not be duplicated. Please, try again.');
            echo json_encode($result);die;
        }

        $result = array('status'=>$status, 'message'=>'The aircraft data could not be duplicated. Please, try again.');
        echo json_encode($result);die;
    }

    //Save Component Times
    private function saveComponentTime($planeId, $compId, $compTimeData)
    {
        unset($compTimeData['id']);
        $compTimeData['plane_id']              = $planeId;
        $compTimeData['airframe_component_id'] = $compId;
        $airCompTimes = $this->airCompTimeObj->newEntity();
        $airCompTimes = $this->airCompTimeObj->patchEntity($airCompTimes, $compTimeData);
        $this->airCompTimeObj->save($airCompTimes);
    }

    //Save Utilization
    private function saveUtilization($planeId, $compId, $utilData)
    {
        unset($utilData['id']);
        $utilData['plane_id']              = $planeId;
        $utilData['airframe_component_id'] = $compId;
        $utilization = $this->utilizationObj->newEntity();
        $utilization = $this->utilizationObj->patchEntity($utilization, $utilData);
        $this->utilizationObj->save($utilization);
    }

    //Save parts
    private function saveParts($planeId, $compId, $aircompParts, $excludeDetails)
    {
        $totalParts = count($aircompParts);
        $i=0;
        foreach ($aircompParts as $key => $partData) {
            
            if(!empty($partData['parent_id'])) {
                $cloneId = $this->airCompPartObj->find()
                            ->select('id')
                            ->where([
                                'AirframeComponentParts.plane_id'=>$planeId,
                                'AirframeComponentParts.airframe_component_id'=>$compId,
                                'AirframeComponentParts.clone_id'=>$partData['parent_id']
                            ])
                            ->enableHydration(false)->first();
                if(!empty($cloneId['id'])) {
                    $partData['parent_id'] = $cloneId['id'];
                } else {
                    $partData['parent_id'] = 0;
                }
            } else {
                $partData['parent_id'] = 0;
            }

            if(!empty($excludeDetails) && $excludeDetails == 1) {
                unset($partData['serial_number']);
            }
            $partData['clone_id']              = $partData['id'];
            unset($partData['id']);
            $partData['plane_id']              = $planeId;
            $partData['airframe_component_id'] = $compId;
            
            $part = $this->airCompPartObj->newEntity();
            $part = $this->airCompPartObj->patchEntity($part, $partData);
            if($this->airCompPartObj->save($part)) {
                $partId = $part->id;

                //Save parts last complied with
                if(!empty($partData['airframe_component_last_cw'])) {
                    $this->saveLastCW($planeId, $compId, $partId, $partData['airframe_component_last_cw'][0], $excludeDetails);
                }

                //Save group values
                if(!empty($partData['group']) && $partData['parent_id'] == 0) {
                    $this->saveGroup($planeId, $compId, $partId, $partData['group']);
                }
                
                $i++;
                if($totalParts == $i) {
                    $result = 'success';
                } else {
                    $result = 'failure';
                }
            } else {
                $result = 'failure';
            }
        }
        return $result;
    }

    //Save last CW
    private function saveLastCW($planeId, $compId, $partId, $lastCwData, $excludeDetails)
    {
        if(!empty($excludeDetails) && $excludeDetails == 1) {
            unset($lastCwData['last_cw_date']);
            unset($lastCwData['last_cw_hrs']);
            unset($lastCwData['last_cw_afl']);
            unset($lastCwData['last_cw_msc']);
        }
        unset($lastCwData['id']);
        $lastCwData['plane_id']                   = $planeId;
        $lastCwData['airframe_component_id']      = $compId;
        $lastCwData['airframe_component_part_id'] = $partId;
        $lastCw = $this->lastCwObj->newEntity();
        $lastCw = $this->lastCwObj->patchEntity($lastCw, $lastCwData);
        $this->lastCwObj->save($lastCw);
    }

    //Save group
    private function saveGroup($planeId, $compId, $partId, $groupData)
    {
        unset($groupData['id']);
        $groupData['plane_id']                   = $planeId;
        $groupData['airframe_component_id']      = $compId;
        $groupData['airframe_component_part_id'] = $partId;
        $group = $this->groupObj->newEntity();
        $group = $this->groupObj->patchEntity($group, $groupData);
        $this->groupObj->save($group);
    }

}