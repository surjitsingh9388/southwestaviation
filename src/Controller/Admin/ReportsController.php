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
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\I18n\FrozenTime;

/**
 * Users Controller
 */
class ReportsController extends AppController
{
    /*private $planeObj;
    private $airCompObj;
    private $airCompTimeObj;
    private $airCompPartObj;
    private $airCompLastCwObj;
    private $extDetailObj;
    private $parentChildRelObj;
    private $groupObj;*/

    protected \App\Model\Table\PlanesTable $planeObj;
    protected \App\Model\Table\AirframeComponentsTable $airCompObj;
    protected \App\Model\Table\AirframeComponentTimesTable $airCompTimeObj;
    protected \App\Model\Table\AirframeComponentPartsTable $airCompPartObj;
    protected \App\Model\Table\AirframeComponentLastCwTable $airCompLastCwObj;
    protected \App\Model\Table\ExtraDetailsTable $extDetailObj;
    protected \App\Model\Table\ParentChildRelationsTable $parentChildRelObj;
    protected \App\Model\Table\GroupsTable $groupObj;

    protected array $ataCodeArr;
    protected array $adSbStatus;
    protected array $adSbDispArr;
    protected array $disListArr;

    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );
    
    public function initialize():void 
    {
        parent::initialize();
        $this->loadComponent('Plane');
        $this->loadComponent('AirframeComponent');
        $this->loadComponent('AirframeComponentPart');
        $this->loadComponent('AtaCode');
        $this->loadComponent('Disposition');
        $this->loadComponent('AdsbStatus');
        $this->loadComponent('Report');

        $this->planeObj          = $this->fetchTable('Planes');
        $this->airCompObj        = $this->fetchTable('AirframeComponents');
        $this->airCompTimeObj    = $this->fetchTable('AirframeComponentTimes');
        $this->airCompPartObj    = $this->fetchTable('AirframeComponentParts');
        $this->airCompLastCwObj  = $this->fetchTable('AirframeComponentLastCw');
        $this->extDetailObj      = $this->fetchTable('ExtraDetails');
        $this->parentChildRelObj = $this->fetchTable('ParentChildRelations');
        $this->groupObj          = $this->fetchTable('Groups');

        //To display Active and Historical records
        $this->ataCodeArr = [79,80];
        $this->adSbStatus = [1,2,6,7];
        $this->adSbDispArr = [17,21,63,64,65,66,67,68,82,89,105,107];
        $this->disListArr = [17,19,21,25,63,64,65,66,67,68];
    }
    
    public function index(){}

    /**
     * customReport method
     *
     * @return \Cake\Http\Response|void
     */
    public function customReport()
    {
        ini_set('memory_limit', '1024M');
        $actionItems='';
        $reportTime='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Availability', $actionStatus))
            {
                $actionItems = $actionStatus['Availability'];
            }

            if(array_key_exists('Airframe Component Times', $actionStatus))
            {
                $reportTime = $actionStatus['Airframe Component Times'];
            }
        }

        //Query
        $records = $this->Report->customReportQuery();
        //Custom page records and dues count
        $allResults = $this->Report->customReportWithCount($records, 'customPage');

        $results = $allResults['results'];
        $pastDue = $allResults['pastDue'];
        $comingDue = $allResults['comingDue'];
        $alertDue = $allResults['alertDue'];
        $toleranceDue = $allResults['toleranceDue'];

        $this->set(compact('actionItems', 'reportTime', 'results', 'pastDue', 'comingDue', 'toleranceDue', 'alertDue'));
    }

    //Get items count
    public function getItemCounts()
    {
        $params = $this->request->getData();
        $planeIds = array();
        if(!empty($params['values'])) {
            $planeIds = $params['values'];
        } else {
            $result = array('status'=>'failure', 'data'=>array());
            echo json_encode($result);die;
        }

        //Query
        $records = $this->Report->customReportQuery($planeIds);

        //Get conditional records
        if(!empty($records)) {
            //Custom page records and dues count
            $allResults = $this->Report->customReportWithCount($records, 'itemCount');
            $pastDue = $allResults['pastDue'];
            $comingDue = $allResults['comingDue'];
            $alertDue = $allResults['alertDue'];
            $toleranceDue = $allResults['toleranceDue'];

            $data = array(
                    'pastDue' => $pastDue,
                    'comingDue' => $comingDue,
                    'toleranceDue' => $toleranceDue,
                    'alertDue' => $alertDue
                );
            $result = array('status'=>'success', 'data'=>$data);
            echo json_encode($result);die;

        } else {

            $result = array('status'=>'failure', 'data'=>array());
            echo json_encode($result);die;
        }
    }
    
    //Multiple aircrafts maintenance records
    public function maintenance()
    {
        ini_set('memory_limit', '-1');
        //ini_set('memory_limit', '1024M');
        ob_start();
        $params      = $_GET;

        $aircraftIds = '';
        $cond        = '';
        $pids        = array();
        $results     = array();

        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        $type = !empty($params['type']) ? $params['type'] : '';

        if(!empty($params['AircraftIds'])) {
            if(is_array($params['AircraftIds'])) {
                $pids = $params['AircraftIds'];
                $aircraftIds = implode(',', $params['AircraftIds']);
            } else {
                $pids = explode(',', $params['AircraftIds']);
                $aircraftIds = $params['AircraftIds'];
            }
            $cond = ['AirframeComponentParts.plane_id IN'=>$pids];
        } else {
            if($type != 'maintenance' && $type != 'maintenanceItems' && $type != 'adsbstatus') {
                $ids = array_keys($planes);
                $aircraftIds = implode(',', $ids);
                $pids = $ids;
                $cond = ['AirframeComponentParts.plane_id IN'=>$pids];
            }
        }

        if(!empty($pids)) {
            $results = $this->getPartResults($params, $cond, $pids);
        }

        //All aircraft data to display in popup
        $allAircraft = $this->allAircraft();

        //Display Status
        $displayStatus = $this->displayStatus($type);
        $actionItems   = !empty($displayStatus['actionItems']) ? $displayStatus['actionItems'] : '';
        $reportAction  = !empty($displayStatus['reportAction']) ? $displayStatus['reportAction'] : '';
        $reportTime    = !empty($displayStatus['reportTime']) ? $displayStatus['reportTime'] : '';

        $this->set(compact('actionItems', 'reportAction', 'reportTime', 'results', 'aircraftIds', 'planes', 'params', 'type', 'allAircraft'));
        ob_end_flush();
    }

    public function displayStatus($type)
    {
        if($type == 'maintenanceItems' || $type == 'quickRef' || $type == 'adsbstatus') {
            $type = "Maintenance Items";
        } else {
            $type = "Due List";
        }

        $result = array();
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists($type, $actionStatus))
            {
                $result['actionItems'] = $actionStatus['Maintenance Items'];
            }

            if(array_key_exists('Generate Report', $actionStatus))
            {
                $result['reportAction'] = $actionStatus['Generate Report'];
            }

            if(array_key_exists('Airframe Component Times', $actionStatus))
            {
                $result['reportTime'] = $actionStatus['Airframe Component Times'];
            }
        }
        return $result;
    }

    public function getPartResults($params, $cond, $pids)
    { 
        //Sorting condition
        $sortOrder = $this->sortOrderResults($pids);

        //To check parent child relation
        /*$childQuery = function ($q) { 
                        return $q->where(['AirframeComponentParts.parent_id !='=>0, 'AirframeComponentParts.id !='=>'ParentChildRelations.parent_id'])
                        ->select(['AirframeComponentParts.id', 'AirframeComponentParts.plane_id'])
                        ->contain([
                            'ParentChildRelations'=>[
                                'fields'=>['ParentChildRelations.airframe_component_part_id', 'ParentChildRelations.parent_id']
                            ]
                        ]);
                    };*/

                    $childQuery = function ($q) { 
                        return $q->where([
                                'AirframeComponentParts.parent_id !=' => 0,
                                $q->newExpr()->add('AirframeComponentParts.id != ParentChildRelations.parent_id') // Column comparison
                            ])
                            ->select(['AirframeComponentParts.id', 'AirframeComponentParts.plane_id', 'AirframeComponentParts.position_id'])
                            ->contain([
                                'ParentChildRelations' => [
                                    'fields' => ['ParentChildRelations.airframe_component_part_id', 'ParentChildRelations.parent_id']
                                ]
                            ]);
                    };
                    

        //Components associate tables
        $compAssociates = $this->Report->associateQuery();
        $reports = $this->airCompPartObj->find()
                    ->where($cond)
                    ->order($sortOrder)
                    ->contain([
                        'Planes'=>[
                            'fields'=>[
                                'Planes.id',
                                'Planes.plane_code'
                            ],
                            'AirframeComponentParts'=>$childQuery
                        ],
                        'AirframeComponents'=>$compAssociates,
                        'AirframeComponentLastCw'=>[
                            'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                        ],
                    ])
                    ->enableHydration(false)->toArray();

        //Active and historical records

        $connection = ConnectionManager::get('default');

        $finalArr = array();
        $activeArr = array();
        $historicalArr = array();
        foreach ($reports as $keys=>$row) {
            $airframe_comp_parts_idarr = [];
            $attachment_count = 0;
            if(!empty($row['airframe_component_last_cw'])){
                foreach($row['airframe_component_last_cw'] as $compparts){
                    $airframe_comp_parts_idarr[] = $compparts['airframe_component_part_id'];
                }
                
                if(!empty($airframe_comp_parts_idarr)){
                    $query = "SELECT count(id) as total_files_count FROM `airframe_component_part_files` where airframe_component_part_id in(".implode(',', $airframe_comp_parts_idarr).")";
                    
                    $results = $connection->execute($query)->fetch('assoc');
                    $attachment_count = $results['total_files_count'];
                }
            }
            $row['attachment_count'] = $attachment_count;
            $reports[$keys]['attachment_count'] = $attachment_count;

            if(!empty($params['type']) && $params['type'] == 'maintenanceItems') {
                if(!empty($row['ata_code']) && !empty($row['disposition']) && !in_array($row['ata_code'], $this->ataCodeArr) && in_array($row['disposition'], $this->disListArr)) {
                    $historicalArr[] = $row;
                } elseif(!in_array($row['ata_code'], $this->ataCodeArr)) {
                    $activeArr[] = $row;
                }
            } elseif (!empty($params['type']) && $params['type'] == 'adsbstatus') {
                if(!empty($row['ata_code']) && !empty($row['disposition']) && in_array($row['ata_code'], $this->ataCodeArr) && in_array($row['disposition'], $this->adSbDispArr)) {
                    $historicalArr[] = $row;
                } elseif(!empty($row['ata_code']) && in_array($row['ata_code'], $this->ataCodeArr) && !in_array($row['ad_sb_status'], $this->adSbStatus)) {
                    $activeArr[] = $row;
                }
            }
        }
        
        if(!empty($params['action']) && $params['action'] == 'historical') {
            $finalArr = $historicalArr;
        } elseif(!empty($params['action']) && $params['action'] == 'active') {
            $finalArr = $activeArr;
        } else {
            $finalArr = $reports;
        }
        //End Active and historical records
        //Get conditional records
        $results = $this->condRecords($finalArr, $params);
        return $results;
    }

    public function allAircraft()
    {
        $whereCond = '';
        //Display assigned aircraft only
        $airIds = $this->Plane->getSelectedAircraft();
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            if(!empty($airIds['airIdsArr']) && is_array($airIds['airIdsArr'])) {
                $whereCond = ['Planes.id IN'=>$airIds['airIdsArr']];
            } else {
                $whereCond = ['Planes.id IN'=>''];
            }
        }

        $allAircraft = $this->planeObj->find()
                    ->where($whereCond)
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
                            ]
                        ]
                    ])
                    ->enableHydration(false)->toArray();

        return $allAircraft;
    }

    public function sortOrderResults($pids, $req=null)
    {
        //Sorting(NOTE: Need to make sorting dynamic)
        $flag = 0;
        $sortOrder = ['-ata_code'=> 'DESC'];
        if(!empty($pids) && count($pids) == 1 && $this->Plane->orderType($pids[0]) == 'no') {
            $flag = 1;
            $sortOrder = ['log_book'=>'ASC'];
        } elseif(!empty($pids) && count($pids) == 1 && $this->Plane->orderType($pids[0]) == 'yes') {
            $flag = 2;
            $sortOrder = ['-ata_code'=>'DESC', 'mfg_code'=>'ASC'];
        }

        if(!empty($req['titlen']) && !empty($req['datasort']) && $flag==1) {
            $sortOrder = [$req['titlen'] => $req['datasort']];            
            if($req['titlen'] == 'description') {
                $sortOrder = ['AirframeComponentParts.'.$req['titlen']=> $req['datasort']];
            }
        } elseif(!empty($req['titlen']) && !empty($req['datasort']) && $flag==2) {
            if($req['datasort'] == 'ASC' && $req['titlen'] != 'description') {
                $sortOrder = ['-mfg_code'=>'DESC', 'ata_code'=>'ASC', 'item_type' => 'ASC', 'ad_sb_number' => 'ASC', 'ad_sb_status'=>'ASC', 'amendment'=>'ASC', 'log_book'=>'ASC'];
            } elseif($req['datasort'] == 'DESC' && $req['titlen'] != 'description') {
                $sortOrder = ['-mfg_code'=>'ASC', 'ata_code'=>'DESC', 'item_type' => 'DESC', 'ad_sb_number' => 'DESC', 'ad_sb_status'=>'DESC', 'amendment'=>'DESC', 'log_book'=>'DESC']; 
            }

            if($req['titlen'] == 'description') {
                $sortOrder = ['AirframeComponentParts.'.$req['titlen']=> $req['datasort']];
            }
        }

        return $sortOrder;
    }

    /**
     * Generate PDF for multiple aircraft
     */
    public function generateMultiAircraftPdf()
    {
        ini_set('memory_limit', '1024M');
        $this->autoRender = false;
        $req = $this->request->getData();
        //Aircraft Ids
        $pids = explode(',', $req['pids']);

        //Sorting condition
        $sortOrder = $this->sortOrderResults($pids, $req);
        
        //Condition if parts selected
        $cond = '';
        $partids = '';
        if(!empty($req['partids'])) {
            if(is_array($req['partids'])) {
                $partids = $req['partids'];
            } else {
                $partids = explode(',', $req['partids']);
            }
            $cond = ['AirframeComponentParts.id IN'=>$partids];
        }

        //PDF Type
        $pdfType = 'Maintenance Due List';
        if(!empty($req['type']) && $req['type'] == 'maintenanceItems') {
            $pdfType = 'Maintenance Items';
            if(!empty($req['reporttype']) && $req['reporttype'] == 'projected') {
                $pdfType = 'Projected Report';
            }

        } elseif(!empty($req['type']) && $req['type'] == 'adsbstatus') {
            $pdfType = 'Airworthiness Directives & Service Bulletins';

        } elseif(!empty($req['type']) && $req['type'] == 'past_due') {
            $pdfType = 'Maintenance Overdue List';

        } elseif(!empty($req['type']) && $req['type'] == 'tolerance') {
            $pdfType = 'Maintenance Current Due List';

        } elseif(!empty($req['type']) && $req['type'] == 'alert_due') {
            $pdfType = 'Maintenance Projected Due List';

        } elseif(!empty($req['type']) && $req['type'] == 'quickRef') {
            if(!empty($cond)) {
                $cond = ['AirframeComponentParts.id IN'=>$partids, 'AirframeComponentParts.quick_ref'=>'yes'];
            } else {
                $cond = ['AirframeComponentParts.quick_ref'=>'yes'];
            }
        }

        /*********************************************/
        if(!empty($req['searchby'])) {
            if(!empty($req['searchval']) && !empty($cond)) {
                $cond = [
                            'AND'=>[
                                $cond,
                                'OR'=>[
                                    'AirframeComponentParts.reference LIKE' => '%'.trim($req['searchval']).'%',
                                    'AirframeComponentParts.item_type LIKE' => '%'.trim($req['searchval']).'%',
                                    'AirframeComponentParts.requirement_type LIKE' => '%'.trim($req['searchval']).'%',
                                    'AirframeComponentParts.ad_sb_status LIKE' => '%'.trim($req['searchval']).'%',
                                    'AirframeComponentParts.description LIKE' => '%'.trim($req['searchval']).'%',
                                    'AirframeComponents.log_book LIKE' => '%'.trim($req['searchval']).'%'
                                ]
                            ]
                        ];
            } elseif (!empty($req['searchval'])) {
                $cond = [
                            'OR'=>[
                                'AirframeComponentParts.reference LIKE' => '%'.trim($req['searchval']).'%',
                                'AirframeComponentParts.item_type LIKE' => '%'.trim($req['searchval']).'%',
                                'AirframeComponentParts.requirement_type LIKE' => '%'.trim($req['searchval']).'%',
                                'AirframeComponentParts.ad_sb_status LIKE' => '%'.trim($req['searchval']).'%',
                                'AirframeComponentParts.description LIKE' => '%'.trim($req['searchval']).'%',
                                'AirframeComponents.log_book LIKE' => '%'.trim($req['searchval']).'%'
                            ]
                        ];
            }
        }

        //Components associate tables
        $compAssociates = $this->Report->associateQuery();
        $partsQuery = function ($q) use ($partids, $cond, $sortOrder, $compAssociates) { 
                        return $q->where($cond)
                        ->order($sortOrder)
                        ->contain([
                            'AirframeComponents'=>$compAssociates,
                            'AirframeComponentLastCw'=>[
                                'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                            ],
                            'PartInstalledTimes'=>[
                                'sort'=>['PartInstalledTimes.id'=>'DESC']
                            ],
                            'AirframeCategories'=>[
                                'fields'=>[
                                    'AirframeCategories.id',
                                    'AirframeCategories.category_name'
                                ]
                            ]
                        ]);
                    };
        $reports = $this->planeObj->find()
                    ->where(['Planes.id IN'=>$pids])
                    ->contain([ 
                        'AirframeComponents'=>$compAssociates,
                        'AirframeComponentParts'=>$partsQuery,
                    ])
                    ->select(['Planes.id', 'Planes.plane_name', 'Planes.plane_type', 'Planes.plane_code', 'Planes.plane_serial_number', 'Planes.federal_aviation_regulation', 'Planes.airworthiness_date', 'Planes.hours', 'Planes.cycles'])
                    ->enableHydration(false)
                    ->toArray();

        //Seperate aircraft details
        $multiReports = array();
        $aircraftCode = '';
        $mResults = '';
        $tmp = '';
        $headerHTML = '';
        $footerHTML = '';
        $reportDate = date('m/d/Y');
        foreach ($reports as $key => $value) {
            //Reported date, hours and cycles
            $reportedDate = '&nbsp;';
            $planeHours   = !empty($value['hours']) ? $value['hours'] : '&nbsp;';
            $planeCycles  = !empty($value['cycles']) ? $value['cycles'] : '&nbsp;';
            if(!empty($req['reporttype']) && $req['reporttype'] == 'projected') {
                $reportedDate = !empty($req['log_date']) ? date('m/d/Y', strtotime($req['log_date'])) : date('m/d/Y');
                $planeHours = !empty($req['hours']) ? '+'. $req['hours'] : '&nbsp;';
                $planeCycles = !empty($req['cycles']) ? '+'. $req['cycles'] : '&nbsp;';
            } else {
                foreach ($value['airframe_components'] as $key2 => $value2) {
                    if($value2['log_book'] == 'Airframe') {
                        $reportedDate = !empty($value2['airframe_component_times'][0]['log_date']) ? date('m/d/Y', strtotime($value2['airframe_component_times'][0]['log_date'])) : '&nbsp;';
                        $planeHours = !empty($value2['airframe_component_times'][0]['hours']) ? $value2['airframe_component_times'][0]['hours'] : '&nbsp;';
                        $planeCycles = !empty($value2['airframe_component_times'][0]['cycles']) ? $value2['airframe_component_times'][0]['cycles'] : '&nbsp;';
                    }
                }
            }
            
            if($key > 0) {
                $mResults .= '<div style="page-break-after: always;"></div>
                    <header>
                        <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td style="width:6%; font-size:17px; font-weight: normal; text-align:left; padding:0 0 5px 0;"></td>
                                    <td style="width:88%; font-size:15px; padding:0 0 5px 0;"></td>
                                    <td style="width:6%; font-size: 10px; font-weight: normal; text-align:left; padding:0 0 10px 0;">'.$reportDate.'</td>
                                </tr>
                                <tr>
                                    <td style="width:6%; font-size:17px; font-weight: normal; text-align:left; padding:0 0 5px 0;">'.(!empty($req['action']) ? ucfirst($req['action']) : '').'</td>
                                    <td style="width:88%; font-size:17px; padding:0 0 5px 0;">'.$pdfType.'</td>
                                    <td style="width:6%; font-size: 17px; font-weight: normal; text-align:left; padding:0 0 5px 0;">'.$value['plane_code'].'</td>
                                </tr>
                            </tbody>
                        </table>
                    </header>';
                $aircraftCode = $value['plane_code'];
            } else {
                $mResults .= '<header>
                                <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr>
                                            <td style="width:6%; font-size:17px; font-weight: normal; text-align:left; padding:0 0 5px 0;"></td>
                                            <td style="width:88%; font-size:15px; padding:0 0 5px 0;"></td>
                                            <td style="width:6%; font-size: 10px; font-weight: normal; text-align:left; padding:0 0 10px 0;">'.$reportDate.'</td>
                                        </tr>
                                        <tr>
                                            <td style="width:6%; font-size:17px; font-weight: normal; text-align:left; padding:0 0 5px 0;">'.(!empty($req['action']) ? ucfirst($req['action']) : '').'</td>
                                            <td style="width:88%; font-size:17px; padding:0 0 5px 0;">'.$pdfType.'</td>
                                            <td style="width:6%; font-size: 17px; font-weight: normal; text-align:left; padding:0 0 5px 0;">'.$value['plane_code'].'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </header>';
                $aircraftCode = $value['plane_code'];
            }

            $mResults .='<table style="width:100%; margin:0 auto; padding:10px 0 5px 0; font-size:11px; border-top: 2px solid #c0c0c0;" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td style="width: 40%; padding: 10px 0 3px 0; margin: 0; color:#777777;">Make & Model (Serial)</td>
                                    <td style="width: 24%; padding: 10px 0 3px 0; margin: 0; color:#777777;">Operator</td>
                                    <td style="width: 12%; padding: 10px 0 3px 0; margin: 0; color:#777777;">Date</td>
                                    <td style="width: 12%; padding: 10px 0 3px 0; margin: 0; color:#777777;">Hours</td>
                                    <td style="width: 12%; padding: 10px 0 3px 0; margin: 0; color:#777777;">Landings/Cycles</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 3px 0 15px 0; margin: 0;">'.$value['plane_type'].' ('.$value['plane_serial_number'].')</td>
                                    <td style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 3px 0 15px 0; margin: 0;">'.$value['plane_name'].'</td>
                                    <td style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 3px 0 15px 0; margin: 0;">'.$reportedDate.'</td>
                                    <td style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 3px 0 15px 0; margin: 0;">'.$planeHours.'</td>
                                    <td style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 3px 0 15px 0; margin: 0;">'.$planeCycles.'</td>
                                </tr>
                            </tbody>
                        </table>';

            $mResults .= '<table style="width:100%; font-size:11px;">
                            <tr>
                                <td style="width: 10%; color:#777777;">
                                    Major Components
                                </td>
                                <td style="width: 90%;">
                                    <hr style="width: 100%; color:#c0c0c0;">
                                </td>
                            </tr>
                        </table>';

            $mResults .='<table style="width:95%; margin:0 auto; padding:5px 0 10px 0; font-size:11px;" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="padding: 0 0 15px 0;">
                                <table style="width:100%; margin:0 auto; padding:0; border-collapse: collapse;">
                                    <thead>
                                        <tr>
                                            <td style="width:30%; font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; color:#777777; margin: 0;">Component</td>
                                            <td style="width:34%; font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; color:#777777; margin: 0;">Model (Serial)</td>
                                            <td style="width:12%; font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; color:#777777; margin: 0;">Log Date</td>
                                            <td style="width:12%; font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; color:#777777; margin: 0;">Hours</td>
                                            <td style="width:12%; font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; color:#777777; margin: 0;">Cycles</td>
                                        </tr>';
                                        
                                        if(!empty($req['reporttype']) && $req['reporttype'] == 'projected') {
                                            foreach ($value['airframe_components'] as $key2 => $value2) {
                                                $newDate = !empty($value2['airframe_component_times'][0]['log_date']) ? date('m/d/Y', strtotime($value2['airframe_component_times'][0]['log_date'])) : '&nbsp;';
                                                
                                                if($value2['log_book'] == 'Airframe') {
                                                    $component = $value2['log_book'];
                                                } else {
                                                    $component = $value2['log_book'].' '.$value2['position'];
                                                }

                                                $serialNo = '';
                                                if(!empty($value2['serial_no'])) {
                                                    $serialNo = '('.$value2['serial_no'].')';
                                                }

                                                $compHours = !empty($value2['airframe_component_times'][0]['hours']) ? ($value2['airframe_component_times'][0]['hours'] + $req['hours']) : '&nbsp;';
                                                $compCycles = !empty($value2['airframe_component_times'][0]['cycles']) ? ($value2['airframe_component_times'][0]['cycles'] + $req['cycles']) : '&nbsp;';

                                                $mResults .='<tr>
                                                    <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;">'.$component.'</td>
                                                    <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;">'.$value2['description'].' '.$serialNo.'</td>
                                                    <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;">'.$newDate.'</td>
                                                    <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;">'.$compHours.'</td>
                                                    <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;">'.$compCycles.'</td>
                                                </tr>';
                                            }
                                        } else {
                                            foreach ($value['airframe_components'] as $key2 => $value2) {
                                                $newDate = !empty($value2['airframe_component_times'][0]['log_date']) ? date('m/d/Y', strtotime($value2['airframe_component_times'][0]['log_date'])) : '&nbsp;';
                                                
                                                if($value2['log_book'] == 'Airframe') {
                                                    $component = $value2['log_book'];
                                                } else {
                                                    $component = $value2['log_book'].' '.$value2['position'];
                                                }

                                                $serialNo = '';
                                                if(!empty($value2['serial_no'])) {
                                                    $serialNo = '('.$value2['serial_no'].')';
                                                }

                                                $compHours = !empty($value2['airframe_component_times'][0]['hours']) ? $value2['airframe_component_times'][0]['hours'] : '&nbsp;';
                                                $compCycles = !empty($value2['airframe_component_times'][0]['cycles']) ? $value2['airframe_component_times'][0]['cycles'] : '&nbsp;';

                                                $mResults .='<tr>
                                                    <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;">'.$component.'</td>
                                                    <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;">'.$value2['description'].' '.$serialNo.'</td>
                                                    <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;">'.$newDate.'</td>
                                                    <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;">'.$compHours.'</td>
                                                    <td style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;">'.$compCycles.'</td>
                                                </tr>';
                                            }
                                        }
                                        
                                    $mResults .='</thead>
                                </table>
                            </td>
                        </tr>
                    </table>';

            $mResults .= '<table class="table table-bordered" cellpadding="0" cellspacing="0" style="width:100%; margin:0 auto; padding:0; border-collapse: collapse;border: 1px solid #c0c0c0; text-align:center;">
                <thead>
                    <tr>
                        <th style="width:4%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0;  background:#c0c0c0;">ATA</th>';

                if (!empty($req['type']) && $req['type'] == 'adsbstatus') {
                    $mResults .= '<th style="width:8%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0;  background:#c0c0c0;">AD/SB Number</th>

                        <th style="width:8%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0;  background:#c0c0c0;">AD/SB Class</th>

                        <th style="width:8%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0;  background:#c0c0c0;">Disposition</th>';
                } else {
                    $mResults .= '<th style="width:24%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0;  background:#c0c0c0;">Reference & Component & Item Type</th>';
                }
                                        
                $mResults .= '<th style="width:18%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Description</th>
                        
                        <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Compliance</th>
                        
                        <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Interval</th>
                        
                        <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Tolerance</th>
                                                
                        <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Part Current Life</th>

                        <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Next Due</th>

                        <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Remaining</th>
                        
                    </tr>
                </thead>
                <tbody>';

            //Active and historical records
            $finalArr = array();
            $activeArr = array();
            $historicalArr = array();
            foreach ($value['airframe_component_parts'] as $row) {
                if(!empty($req['type']) && $req['type'] == 'maintenanceItems') {
                    if(!empty($row['ata_code']) && !empty($row['disposition']) && !in_array($row['ata_code'], $this->ataCodeArr) && in_array($row['disposition'], $this->disListArr)) {
                        $historicalArr[] = $row;
                    } elseif(!in_array($row['ata_code'], $this->ataCodeArr)) {
                        $activeArr[] = $row;
                    }
                } elseif (!empty($req['type']) && $req['type'] == 'adsbstatus') {
                    if(!empty($row['ata_code']) && !empty($row['disposition']) && in_array($row['ata_code'], $this->ataCodeArr) && in_array($row['disposition'], $this->adSbDispArr)) {
                        $historicalArr[] = $row;
                    } elseif(!empty($row['ata_code']) && in_array($row['ata_code'], $this->ataCodeArr) && !in_array($row['ad_sb_status'], $this->adSbStatus)) {
                        $activeArr[] = $row;
                    }
                }
            }

            if(!empty($req['action']) && $req['action'] == 'historical') {
                $finalArr = $historicalArr;
            } elseif(!empty($req['action']) && $req['action'] == 'active') {
                $finalArr = $activeArr;
            } else {
                $finalArr = $value['airframe_component_parts'];
            }
            //End Active and historical records

            //Conditions to display in groups
            $mResults .= $this->displayGroupData($finalArr, $req);
            $mResults .= '</tbody></table>';
        }

        $footerHTML = '<footer>
                            <table style="width:100%; margin:0 auto; padding:15px 0 15px 0;" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="font-size:13px; text-align:center;">'.$pdfType.'</td>
                                </tr>
                                <tr>
                                    <td style="font-size:13px; text-align:right; float:right;">
                                    Page {PAGENO} of {nbpg}
                                    </td>
                                </tr>
                            </table>
                        </footer>';

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
                    <main>'.$mResults.'</main>        
                </body>
                </html>';
        //pr($html);die;
       
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', '600');
        ini_set("pcre.backtrack_limit", "50000000");
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

        $mpdf->SetHTMLHeader($headerHTML);
        $mpdf->SetHTMLFooter($footerHTML);
        $chunks = explode("chunk", $html);
        foreach($chunks as $key => $val) {
            $mpdf->WriteHTML($val);
        }

        //save file on particular location
        //https://mpdf.github.io/reference/mpdf-functions/output.html
        $fileName = date('YmdHis').".pdf";
        $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
        if($mResults) {
            $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>'');
            echo json_encode($result);die;
        }
    }

    //Conditions to display data in groups for pdf generate
    public function displayGroupData($airPartsData, $req)
    {
        $results = '';
        $tmp = '';
        if(!empty($airPartsData) && !empty($req)) {
            foreach ($airPartsData as $key => $row) {
                $mResults = '';
                //Get data correction and nextdue calculation
                $mos = $hrs = $afl = $msc = '';
                if(!empty($row['airframe_component_last_cw'][0])) {
                    $getRes = $this->AirframeComponentPart->postDataProcess($row['airframe_component_last_cw'][0]);
                    $mos = $getRes['mos'];
                    $hrs = $getRes['hrs'];
                    $afl = $getRes['afl'];
                    $msc = $getRes['msc'];
                }

                //Components time
                $fdt = '';
                if(!empty($req['reporttype']) && $req['reporttype'] == 'projected') {
                    $fdt = !empty($req['log_date']) ? date('m/d/Y', strtotime($req['log_date'])) : date('m/d/Y');
                    $compHours = !empty($row['airframe_component']['airframe_component_times'][0]['hours']) ? $row['airframe_component']['airframe_component_times'][0]['hours'] + $req['hours'] : 0;
                    $compCycles = !empty($row['airframe_component']['airframe_component_times'][0]['cycles']) ? $row['airframe_component']['airframe_component_times'][0]['cycles'] + $req['cycles'] : 0;
                } else {
                    $compHours = !empty($row['airframe_component']['airframe_component_times'][0]['hours']) ? $row['airframe_component']['airframe_component_times'][0]['hours'] : 0;
                    $compCycles = !empty($row['airframe_component']['airframe_component_times'][0]['cycles']) ? $row['airframe_component']['airframe_component_times'][0]['cycles'] : 0;
                }
                
                //Get remaining
                $remData = $this->Report->getRemaining($mos, $fdt);
                $maintRemMos  = $remData['maintRemMos'];
                $maintRemDays = $remData['maintRemDays'];
                $totalRemD    = $remData['totalRemD'];
                $remMos       = $remData['maintRemMos'];
                $remDays      = $remData['maintRemDays'];

                $remHrs = !empty($hrs) ? $hrs - $compHours : 0;
                $remAfl = !empty($afl) ? $afl - $compCycles : 0;

                //Tolerance
                $tolrMos  = !empty($row['airframe_component_last_cw'][0]['tolerance_mos']) ? $row['airframe_component_last_cw'][0]['tolerance_mos'] : 0;
                $tolrDays = !empty($row['airframe_component_last_cw'][0]['tolerance_days']) ? $row['airframe_component_last_cw'][0]['tolerance_days'] : 0;
                $tolrHrs  = !empty($row['airframe_component_last_cw'][0]['tolerance_hrs']) ? $row['airframe_component_last_cw'][0]['tolerance_hrs'] : 0;
                $tolrAfl  = !empty($row['airframe_component_last_cw'][0]['tolerance_afl']) ? $row['airframe_component_last_cw'][0]['tolerance_afl'] : 0;
                $totalTlrD = $tolrMos * 30 + $tolrDays;

                //Alert
                $isResThres = !empty($row['airframe_component_last_cw'][0]['is_recThres']) ? $row['airframe_component_last_cw'][0]['is_recThres'] : '';

                //Change alert box value
                if(!empty($isResThres) && $isResThres == 'recurring') {
                    $altDays = ($row['airframe_component_last_cw'][0]['recurring_mos'] > 24) ? 60 : 30;
                    $altHrs = ($row['airframe_component_last_cw'][0]['recurring_hrs'] > 999) ? 200 : 50;
                    $altAfl = ($row['airframe_component_last_cw'][0]['recurring_afl'] > 999) ? 200 : 25;
                } elseif(!empty($isResThres) && $isResThres == 'threshold') {
                    $altDays = ($row['airframe_component_last_cw'][0]['threshold_mos'] > 24) ? 60 : 30;
                    $altHrs = ($row['airframe_component_last_cw'][0]['threshold_hrs'] > 999) ? 200 : 50;
                    $altAfl = ($row['airframe_component_last_cw'][0]['threshold_afl'] > 999) ? 200 : 25;
                }
                /*$altDays=!empty($row['airframe_component_last_cw'][0]['alert_days']) ? $row['airframe_component_last_cw'][0]['alert_days']:30;
                $altHrs=!empty($row['airframe_component_last_cw'][0]['alert_hrs']) ? $row['airframe_component_last_cw'][0]['alert_hrs']:50;
                $altAfl=!empty($row['airframe_component_last_cw'][0]['alert_afl']) ? $row['airframe_component_last_cw'][0]['alert_afl']:25;*/

                //Days color
                $altDVal = '';
                $altDColor = 'color:green';
                if((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) < 0) {
                    $altDColor = 'color:red';

                } elseif((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) > 0) { 
                    $altDColor = 'color:#d35400';
                    
                } elseif(!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays > $totalRemD) {
                    $altDColor = 'color:#f39c12';
                    $altDVal = 'alert';

                } elseif(!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays < $totalRemD) {
                   $altDColor = 'color:green';
                }

                //Hours color
                $altHVal = '';
                $altHColor = 'color:green';
                if(!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) < 0) {
                    $altHColor = 'color:red';

                } elseif(!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) > 0) {
                    $altHColor = 'color:#d35400';

                } elseif(!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs > $remHrs) {
                    $altHColor = 'color:#f39c12';
                    $altHVal = 'alert';

                } elseif (!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs < $remHrs) {
                   $altHColor = 'color:green';
                }

                //Cycle color
                $altAVal = '';
                $altAColor = 'color:green';
                if(!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) < 0) {
                    $altAColor = 'color:red';

                } elseif(!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) > 0) {
                    $altAColor = 'color:#d35400';

                } elseif(!empty($altAfl) && !empty($remAfl) && $remAfl > 0 && $altAfl > $remAfl) {
                    $altAColor = 'color:#f39c12';
                    $altAVal = 'alert';

                } elseif (!empty($altAfl) && !empty($remAfl) && $remAfl > 0 && $altAfl < $remAfl) {
                   $altAColor = 'color:green';
                }
                //End Alert

                //Display category row
                if(!empty($row['airframe_category']['id'])) {
                    if ($row['airframe_category']['id'] != $tmp) {
                        $mResults .= '<tr><td colspan="9" style="font-weight:bold;text-align:left;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0;">'.$row['airframe_category']['category_name'].'</td></tr>';
                    }
                }
                $tmp = !empty($row['airframe_category']['id']) ? $row['airframe_category']['id'] : '';
                $mResults  .= '<tr>';
            
                //ATA code
                $ataAD = '';
                $itemType = '';
                if (!empty($row['ata_code']) || !empty($row['mfg_code'])) {
                    $ataAD = $this->AtaCode->ataCode($row['ata_code']).' '.$row['mfg_code'];
                } elseif (!empty($row['item_type']) || !empty($row['ad_sb_number']) || !empty($row['amendment'])) {
                    $ataAD = $row['item_type'].' '.$row['ad_sb_number'].' '.$row['amendment'];
                }

                if(!empty($row['item_type']) && $row['item_type'] == 'INSPECTION') {
                    $itemType = $row['item_type'];
                } elseif (!empty($row['item_type']) && $row['item_type'] == 'PART') {
                    $itemType = $row['item_type'];
                    if(!empty($row['requirement_type'])) {
                        $itemType .= ' - '.$row['requirement_type'];
                    }
                } elseif (!empty($row['item_type']) && ($row['item_type'] == 'AD' || $row['item_type'] == 'SB')) {
                    $itemType = $row['item_type'];
                    if(!empty($row['ad_sb_status'])) {
                        $itemType .= ' - '.$row['ad_sb_status'];
                    }
                }

                if(!empty($itemType) && !empty($row['disposition'])) {
                    $itemType .= ' <span style="color:#7777777;">DISP:</span>'.$this->Disposition->dispTitle($row['disposition']);
                }
                if($row['airframe_component']['log_book'] == 'Airframe' && !empty($row['position_id'])) {
                    $itemType .= ' <span style="color:#7777777;">POS:</span>'.$this->Position->positionTitle($row['position_id']);
                }
                //ATA code

                if($row['airframe_component']['log_book'] == 'Airframe') {
                    $logBook = $row['airframe_component']['log_book'];
                } else {
                    $logBook = $row['airframe_component']['log_book'].' '.$row['airframe_component']['position'];
                }
        
                //Display ATA code
                $mResults .= '<td style="border: 1px solid #c0c0c0;"><table style="width:100%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;"><tr><td style="padding:1px;border:0; text-align:center; text-align:center;">'.$this->AtaCode->ataCode($row['ata_code']).'</td></tr></table></td>';

                //AD/SB or Refrence & component & Item Type
                $notesCS = 8;
                if (!empty($req['type']) && $req['type'] == 'adsbstatus') {
                    $notesCS = 10;
                    $mResults .= '<td style="border: 1px solid #c0c0c0;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 0; margin: 0;">'.$row['ad_sb_number'].'</td>';

                    $mResults .= '<td style="border: 1px solid #c0c0c0;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 0; margin: 0;">'.$this->AdsbStatus->adsbTitle($row['ad_sb_status']).'</td>';

                    $mResults .= '<td style="border: 1px solid #c0c0c0;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 0; margin: 0;">'.$this->Disposition->dispTitle($row['disposition']).'</td>';
                } else {
                    //Reference & Component & Item Type
                    $mResults .= '<td style="border: 1px solid #c0c0c0;"><table style="width:100%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;"><tr><td style="padding:1px;border:0; text-align:center; text-align:center;">'.$row['reference'].'</td></tr><tr><td style="padding:1px;border:0; text-align:center; text-align:center;">'.$logBook.'</td></tr><tr><td style="padding:1px;border:0; text-align:center; text-align:center;">'.$itemType.'</td></tr><tr><td style="padding:1px;border:0; text-align:center; text-align:center;">&nbsp;</td></tr></table></td>';
                }
                                
                //Part description
                $partDesc     = !empty($row['description']) ? $row['description'] : '&nbsp;';
                $partNum      = !empty($row['part_number']) ? '<span style="color:#777777;">Part Number <br></span>'.$row['part_number'] : '&nbsp;';
                $partSerial   = !empty($row['serial_number']) ? '<span style="color:#777777;">Serial Number <br></span>'.$row['serial_number'] : '&nbsp;';
                $partHardware = !empty($row['hardware']) ? $row['hardware'] : '&nbsp;';
                $partSoftware = !empty($row['software']) ? $row['software'] : '&nbsp;';
                $partMMRef    = !empty($row['mm_ref']) ? $row['mm_ref'] : '&nbsp;';
                $partOpsNum   = !empty($row['ops_numbers']) ? $row['ops_numbers'] : '&nbsp;';
                $mResults .= '<td style="text-align:center;border: 1px solid #c0c0c0;"><table style="width:100%;text-align:center;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px; margin: 0;"><tr><td colspan="2" style="text-align:left;padding:1px;border:0; text-align:center; text-align:center;">'.$partDesc.'</td></tr> <tr><td colspan="2"></td></tr> <tr><td style="padding:1px;border:0; text-align:center; text-align:center;">'.$partNum.'</td><td style="padding:1px;border:0; text-align:center; text-align:center;">'.$partSerial.'</td></tr> <tr><td style="padding:1px;border:0; text-align:center; text-align:center;">'.$partHardware.'</td><td style="padding:1px;border:0; text-align:center; text-align:center;">'.$partSoftware.'</td></tr><tr><td colspan="2" style="padding:1px;border:0; text-align:center; text-align:center;">'.$partMMRef.'</td></tr><tr><td colspan="2" style="padding:1px;border:0; text-align:center; text-align:center;">'.$partOpsNum.'</td></tr></table></td>';

                //lastcw or complience
                $lastCW = '';
                if(!empty($row['airframe_component_last_cw'][0]['last_cw_date'])) {
                    $lastCW .= date('m/d/Y', strtotime($row['airframe_component_last_cw'][0]['last_cw_date'])).'<br>';
                }
                
                if(!empty($row['airframe_component_last_cw'][0]['last_cw_hrs'])) {
                    $lastCW .= 'Hours: '.$row['airframe_component_last_cw'][0]['last_cw_hrs'].'<br>';
                }

                if(!empty($row['airframe_component_last_cw'][0]['last_cw_afl'])) {
                    $lastCW .= 'Cycles: '.$row['airframe_component_last_cw'][0]['last_cw_afl'].'<br>';
                }

                $mResults .= '<td style="border: 1px solid #c0c0c0;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 0; margin: 0;">'.$lastCW.'</td>';

                //Required Freq or Interval
                $reqFeq = '';
                if(!empty($row['airframe_component_last_cw'][0]['required_frequency_mos'])) {
                    $reqFeq .= 'Months: '.$row['airframe_component_last_cw'][0]['required_frequency_mos'].'<br>';
                }                       

                if(!empty($row['airframe_component_last_cw'][0]['required_frequency_days'])) {
                    $reqFeq .= 'Days: '.$row['airframe_component_last_cw'][0]['required_frequency_days'].'<br>';
                }

                if(!empty($row['airframe_component_last_cw'][0]['required_frequency_hrs'])) {
                    $reqFeq .= 'Hours: '.$row['airframe_component_last_cw'][0]['required_frequency_hrs'].'<br>';
                }

                if(!empty($row['airframe_component_last_cw'][0]['required_frequency_afl'])) {
                    $reqFeq .= 'Cycles: '.$row['airframe_component_last_cw'][0]['required_frequency_afl'].'<br>';
                }
                
                $mResults .= '<td style="border: 1px solid #c0c0c0;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 0; margin: 0;">'.$reqFeq.'</td>';

                //Tolerance
                $tolerance = '';
                if(!empty($row['airframe_component_last_cw'][0]['tolerance_mos'])) {
                    $tolerance .= 'Months: '.$row['airframe_component_last_cw'][0]['tolerance_mos'].'<br>';
                }

                if(!empty($row['airframe_component_last_cw'][0]['tolerance_days'])) {
                    $tolerance .= 'Days: '.$row['airframe_component_last_cw'][0]['tolerance_days'].'<br>';
                }

                if(!empty($row['airframe_component_last_cw'][0]['tolerance_hrs'])) {
                    $tolerance .= 'Hours: '.$row['airframe_component_last_cw'][0]['tolerance_hrs'].'<br>';
                }

                if(!empty($row['airframe_component_last_cw'][0]['tolerance_afl'])) {
                    $tolerance .= 'Cycles: '.$row['airframe_component_last_cw'][0]['tolerance_afl'].'<br>';
                }                    
                
                $mResults .= '<td style="border: 1px solid #c0c0c0;font-size: 11px;font-family: Arial, Helvetica, sans-serif;font-weight:normal;padding:0;margin:0;">'.$tolerance.'</td>';
                                                                
                //TSN/TSO
                /*$tsnTso = '';
                if(!empty($row['item_type']) && in_array($row['item_type'], ['PART', 'INSPECTION'])) {
                    if(!empty($row['requirement_type']) && $row['requirement_type'] == 'Life Limited') {
                        if(!empty($row['part_installed_times'][0]['new_months']) || (isset($airCompParts['part_installed_times'][0]['new_months']) && $row['part_installed_times'][0]['new_months'] == 0)) {
                            $tsnTso .= 'Months: '.$row['part_installed_times'][0]['new_months'].'<br>';
                        }

                        if(!empty($row['part_installed_times'][0]['new_hours'])) {
                            $tsnTso .= 'Hours: '.$row['part_installed_times'][0]['new_hours'].'<br>';
                        } elseif (isset($row['part_installed_times'][0]['new_hours']) && $row['part_installed_times'][0]['new_hours'] == 0) {
                            $tsnTso .= 'Hours: '.$row['part_installed_times'][0]['new_hours'].'.00<br>';
                        }

                        if(!empty($row['part_installed_times'][0]['new_landings']) || (isset($row['part_installed_times'][0]['new_landings']) && $row['part_installed_times'][0]['new_landings'] == 0)) {
                            $tsnTso .= 'Cycles: '.$row['part_installed_times'][0]['new_landings'].'<br>';
                        }
                    } elseif(!empty($row['requirement_type']) && $row['requirement_type'] == 'Overhaul') {
                        if(!empty($row['part_installed_times'][0]['overhaul_months']) || (isset($row['part_installed_times'][0]['overhaul_months']) && $row['part_installed_times'][0]['overhaul_months'] == 0)) {
                            $tsnTso .= 'Months: '.$row['part_installed_times'][0]['overhaul_months'].'<br>';
                        }

                        if(!empty($row['part_installed_times'][0]['overhaul_hours'])) {
                            $tsnTso .= 'Hours: '.$row['part_installed_times'][0]['overhaul_hours'].'<br>';
                        } elseif (isset($row['part_installed_times'][0]['overhaul_hours']) && $row['part_installed_times'][0]['overhaul_hours'] == 0) {
                            $tsnTso .= 'Hours: '.$row['part_installed_times'][0]['overhaul_hours'].'.00<br>';
                        }

                        if(!empty($row['part_installed_times'][0]['overhaul_landings']) || (isset($row['part_installed_times'][0]['overhaul_landings']) && $row['part_installed_times'][0]['overhaul_landings'] == 0)) {
                            $tsnTso .= 'Cycles: '.$row['part_installed_times'][0]['overhaul_landings'].'<br>';
                        }
                    }
                }*/

                $tsnTso = '';
                //if(!empty($row['item_type']) && in_array($row['item_type'], ['PART', 'INSPECTION'])) {
                    //if(!empty($row['requirement_type']) && $row['requirement_type'] == 'Life Limited') {
                        if(!empty($row['part_installed_times'][0]['new_hours']) || isset($row['part_installed_times'][0]['new_hours']) && $row['part_installed_times'][0]['new_hours'] == 0) {
                            $last_cw_hrs = !empty($row['airframe_component_last_cw'][0]['last_cw_hrs']) ? $row['airframe_component_last_cw'][0]['last_cw_hrs'] : 0;
                            $tsnTso .= 'TSN: '.round(($compHours - $last_cw_hrs + $row['part_installed_times'][0]['new_hours']), 1).'<br>';
                        }
                    //}
                    
                    //if(!empty($row['requirement_type']) && $row['requirement_type'] == 'Overhaul') {
                        if(!empty($row['part_installed_times'][0]['overhaul_hours']) || isset($row['part_installed_times'][0]['overhaul_hours']) && $row['part_installed_times'][0]['overhaul_hours'] == 0) {
                            $last_cw_hrs = !empty($row['airframe_component_last_cw'][0]['last_cw_hrs']) ? $row['airframe_component_last_cw'][0]['last_cw_hrs'] : 0;
                            $tsnTso .= 'TSO: '.round(($compHours - $last_cw_hrs + $row['part_installed_times'][0]['overhaul_hours']), 1).'<br>';
                        }
                   //}

                    //if(!empty($row['requirement_type']) && $row['requirement_type'] == 'Life Limited') {
                        if(!empty($row['part_installed_times'][0]['new_landings']) || (isset($row['part_installed_times'][0]['new_landings']) && $row['part_installed_times'][0]['new_landings'] == 0)) {
                            $last_cw_afl = !empty($row['airframe_component_last_cw'][0]['last_cw_afl']) ? $row['airframe_component_last_cw'][0]['last_cw_afl'] : 0;
                            $tsnTso .= 'CSN: '.round(($compCycles - $last_cw_afl + $row['part_installed_times'][0]['new_landings']), 1).'<br>';
                        }
                    //}
                    
                    //if(!empty($row['requirement_type']) && $row['requirement_type'] == 'Overhaul') {
                        if(!empty($row['part_installed_times'][0]['overhaul_landings']) || (isset($row['part_installed_times'][0]['overhaul_landings']) && $row['part_installed_times'][0]['overhaul_landings'] == 0)) {
                            $last_cw_afl = !empty($row['airframe_component_last_cw'][0]['last_cw_afl']) ? $row['airframe_component_last_cw'][0]['last_cw_afl'] : 0;
                            $tsnTso .= 'CSO: '.round(($compCycles - $last_cw_afl + $row['part_installed_times'][0]['overhaul_landings']), 1).'<br>';
                        }
                    //}

                    //if(!empty($row['requirement_type']) && $row['requirement_type'] == 'Life Limited') {
                        if(!empty($row['part_installed_times'][0]['new_months'])) {
                            $tsnTso .= 'DOM: '.$row['part_installed_times'][0]['new_months'].'<br>';
                        }
                    //}

                    //if(!empty($row['requirement_type']) && $row['requirement_type'] == 'Overhaul') {
                        if(!empty($row['part_installed_times'][0]['overhaul_months'])) {
                            $tsnTso .= 'DLO: '.$row['part_installed_times'][0]['overhaul_months'].'<br>';
                        }
                    //}
                //}

                $mResults .= '<td style="border: 1px solid #c0c0c0;font-size: 11px;font-family: Arial, Helvetica, sans-serif;font-weight:normal;padding:0;margin:0;">'.$tsnTso.'</td>';
                
                //Next due
                $nextDue = '';
                if(!empty($mos)) {
                    $mos = !empty($mos) ? date('d-M-Y', strtotime($mos)) : '&nbsp;';
                    $nextDue .= $mos.'<br>';
                }

                if(!empty($hrs)) {
                    $nextDue .= 'Hours: '.$hrs.'<br>';
                }

                if(!empty($afl)) {
                    $nextDue .= 'Cycles: '.$afl.'<br>';
                }

                $mResults .= '<td style="border: 1px solid #c0c0c0;font-size: 11px;font-family: Arial, Helvetica, sans-serif;font-weight:normal;padding:0;margin:0;">'.$nextDue.'</td>';

                //Remaining
                $remMaint = '';
                if(!empty($remMos)) {
                    $remMaint .= '<span style="'.$altDColor.'">Months: '.$remMos.'</span><br>';
                }

                if(!empty($remDays)) {
                    $remMaint .= '<span style="'.$altDColor.'">Days: '.$remDays.'</span><br>';
                }

                if(!empty($remHrs)) {
                    $remMaint .= '<span style="'.$altHColor.'">Hours: '.(!empty($remHrs) && is_numeric($remHrs) ? round($remHrs,1) : 0).'</span><br>';
                }

                if(!empty($remAfl)) {
                    $remMaint .= '<span style="'.$altAColor.'">Cycles: '.$remAfl.'</span><br>';
                }

                $mResults .= '<td style="border: 1px solid #c0c0c0;font-size: 11px;font-family: Arial, Helvetica, sans-serif;font-weight:normal;padding:0;margin:0;">'.$remMaint.'</td>';
                
                $mResults .= '</tr>';
                
                //Note
                if(!empty($row['notes'])) {
                    $mResults .= '<tr><td style="border: 1px solid #c0c0c0;">&nbsp;</td><td colspan="'.$notesCS.'" style="text-align:left;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0;"><span style="color:#777777;">Notes:</span> '.$row['notes'].'</td></tr>';
                }

                //Utilization
                $utilHours = !empty($row['airframe_component']['utilizations'][0]['hours']) ? $row['airframe_component']['utilizations'][0]['hours'] : 1;
                $utilCycles = !empty($row['airframe_component']['utilizations'][0]['cycles']) ? $row['airframe_component']['utilizations'][0]['cycles'] : 1;

                if((!empty($mos) || !empty($hrs) || !empty($afl)) && !empty($req['type']) && $req['type'] == 'maintenance') {
                    $results .= $mResults;
                } elseif(!empty($req['type']) && ($req['type'] == 'maintenanceItems' || $req['type'] == 'quickRef' || $req['type'] == 'adsbstatus')) {
                    $results .= $mResults;
                } elseif (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) < 0) 
                    || (!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) < 0) 
                    || (!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) < 0)) {
                    
                    if(!empty($req['type']) && $req['type'] == 'past_due') {
                        $results .= $mResults;
                    }
                } elseif (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) > 0) 
                    || (!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) > 0) 
                    || (!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) > 0)) {
                    
                    if(!empty($req['type']) && $req['type'] == 'tolerance') {
                        $results .= $mResults;
                    }
                } elseif((!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays > $totalRemD) || (!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs > $remHrs) || (!empty($altAfl) && !empty($remAfl) && $altAfl > 0 && $altAfl > $remAfl)) {
                    //Alert values
                    if(!empty($req['type']) && $req['type'] == 'alert_due') {
                        $results .= $mResults;
                    }
                } elseif (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD > 10 ) 
                    || (!empty($remHrs) && !empty($utilHours) && $remHrs/$utilHours > 10) 
                    || (!empty($remAfl) && !empty($utilCycles) && $remAfl/$utilCycles > 10)) {
                    
                    if(!empty($req['type']) && $req['type'] != 'coming_due') {
                        $mResults = ''; 
                        $results .= $mResults;
                    }
                } elseif (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD >= 0 && $totalRemD <= 10) 
                    || (!empty($remHrs) && !empty($utilHours) && ($remHrs/$utilHours >= 0 && $remHrs/$utilHours <= 10)) 
                    || (!empty($remAfl) && !empty($utilCycles) && ($remAfl/$utilCycles >= 0 && $remAfl/$utilCycles <= 10))) {
                    
                    if(!empty($req['type']) && $req['type'] == 'coming_due') {
                        $results .= $mResults;
                    }
                } else {
                    $mResults = ''; 
                    $results .= $mResults;
                }
            }
        }
        return $results;
    }

    //Get component time for update
    public function compNewTime()
    {
        $req = $this->request->getData();
        $reports = $this->planeObj->find()
                    ->where(['Planes.id'=>$req['plane_id']])
                    ->contain([
                        'AirframeComponents'=>[
                            'fields'=>[
                                'AirframeComponents.id',
                                'AirframeComponents.plane_id',
                                'AirframeComponents.log_book',
                                'AirframeComponents.position',
                                'AirframeComponents.description',
                                'AirframeComponents.serial_no'
                            ],
                            'sort'=>['AirframeComponents.log_book'=>'ASC', 'AirframeComponents.position'=>'ASC'],
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
                    ])
                    ->select(['Planes.id', 'Planes.plane_code'])
                    ->enableHydration(false)->first();

        //pr($reports);die;
        $tHtml = '';
        if(!empty($reports)) {
            $tHtml = '<div class="x_panel"><div class="x_content"><table class="table table-hover table-header-dark" id="upTCId" style="padding:0;margin:0;">';
            $date = date('m-d-Y');
            foreach ($reports['airframe_components'] as $key => $value) {

                $dataAtr = "";
                if(in_array($value['log_book'], ['Airframe'/*,'Air Conditioner'*/])) {
                    $readonly = '';
                    $logBook = $value['log_book'];
                    if($logBook == 'Airframe') {
                        $dataAtr = "data-comp='Airframe'";
                    }
                } else {
                    $readonly = 'readonly="readonly"';
                    $logBook = $value['log_book'].' '.$value['position'];
                }

                if(!empty($value['serial_no'])) {
                    $serialNo = ' ('.$value['serial_no'].')';
                } else {
                    $serialNo = '';
                }

                $tHtml .= '<thead><tr>
                                <th width="30%">'.$logBook.$serialNo.'</th>
                                <th width="20%">Reported</th>
                                <th width="20%">Accrued</th>
                                <th width="20%">New</th>
                                <th width="10%">Override</th>                              
                            </tr></thead>';

                if(!empty($value['airframe_component_times'])) {
                    $value2 = [];
                    foreach ($value['airframe_component_times'] as $key2 => $value2) {
                        if($key2 == 0) {
                            $date = '&nbsp;';
                            if(!empty($value2['log_date'])) {
                                $date = date('m-d-Y', strtotime($value2['log_date']));
                            }

                            //Components time
                            $compHours = !empty($value2['hours']) ? $value2['hours'] : '&nbsp;';
                            $compCycles = !empty($value2['cycles']) ? $value2['cycles'] : '&nbsp;';
                            
                            $tHtml .= '<input type="hidden" name="id['.$key.']" value="'.$value2['id'].'"><input type="hidden" name="plane_id['.$key.']" value="'.$value2['plane_id'].'"><input type="hidden" name="airframe_component_id['.$key.']" value="'.$value2['airframe_component_id'].'">';

                            $tHtml .= '<tr>
                                        <td class="mainRTD">
                                            <table class="table">
                                                <tr>
                                                    <td class="childRTD">Date</td>
                                                </tr>
                                                <tr>
                                                    <td class="childRTD">Hours</td>
                                                </tr>
                                                <tr>
                                                    <td class="childRTD">Cycles</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="mainRTD">
                                            <table class="table">
                                                <tr>
                                                    <td class="childRTD">'.$date.'</td>
                                                </tr>
                                                <tr>
                                                    <td class="childRTD">'.$compHours.'</td>
                                                </tr>
                                                <tr>
                                                    <td class="childRTD">'.$compCycles.'</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="mainRTD">
                                            <table class="table">
                                                <tr>
                                                    <td class="childRTD">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="childRInp"><input type="text" name="hours_accrued['.$key.']" class="getValid hrsUpCls form-control hours_'.$key.'" '.$dataAtr.' '.$readonly.'></td>
                                                </tr>
                                                <tr>
                                                    <td class="childRInp"><input type="number" name="cycles_accrued['.$key.']" class="cycleUpCls form-control cycles_'.$key.'" '.$dataAtr.' '.$readonly.'></td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="mainRTD">
                                            <table class="table">
                                                <tr>
                                                    <td class="childRInp">
                                                        <div class="date datePicker">
                                                            <input type="text" name="log_date['.$key.']" value="'.$date.'" class="form-control logDatepicker log_date_'.$key.'" '.$readonly.'>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="childRInp">
                                                        <input type="text" name="hours['.$key.']" value="'.$value2['hours'].'" id="hours_'.$key.'" class="form-control newHrsCls hours_'.$key.'" data-currenthr_'.$key.'="'.$value2['hours'].'" '.$readonly.'>
                                                        <input type="hidden" name="hhours['.$key.']" value="'.$value2['hours'].'">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="childRInp">
                                                        <input type="number" name="cycles['.$key.']" value="'.$value2['cycles'].'" id="cycles_'.$key.'" class="form-control newCycleCls cycles_'.$key.'" data-currentcy_'.$key.'="'.$value2['cycles'].'" '.$readonly.'>
                                                        <input type="hidden" name="hcycles['.$key.']" value="'.$value2['cycles'].'">
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td class="mainRTD">';
                                        if(!in_array($value['log_book'], ['Airframe'/*,'Air Conditioner'*/])) {
                                            $tHtml .= '<table class="table">
                                                <tr>
                                                    <td class="childChk">
                                                        <input type="checkbox" name="log_date_chk['.$key.']" class="chkClass" data-key="'.$key.'" data-val="log_date">
                                                        <input type="hidden" name="log_book['.$key.']" value="'.$value['log_book'].'">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="childChk">
                                                        <input type="checkbox" name="hours_chk['.$key.']" class="chkClass" data-key="'.$key.'" data-val="hours">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="childChk">
                                                        <input type="checkbox" name="cycles_chk['.$key.']" class="chkClass" data-key="'.$key.'" data-val="cycles">
                                                    </td>
                                                </tr>
                                            </table>';
                                        } else {
                                            $tHtml .= '<table class="table" style="display:none;">
                                                <tr>
                                                    <td class="childChk">
                                                        <input type="checkbox" name="log_date_chk['.$key.']" checked>
                                                        <input type="hidden" name="log_book['.$key.']" value="'.$value['log_book'].'">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="childChk">
                                                        <input type="checkbox" name="hours_chk['.$key.']" checked>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="childChk">
                                                        <input type="checkbox" name="cycles_chk['.$key.']" checked>
                                                    </td>
                                                </tr>
                                            </table>';
                                        }
                                        $tHtml .= '</td>
                                    </tr>';
                        }
                    }
                } else {
                    $tHtml .= '<input type="hidden" name="id['.$key.']" value=""><input type="hidden" name="plane_id['.$key.']" value="'.$value['plane_id'].'"><input type="hidden" name="airframe_component_id['.$key.']" value="'.$value['id'].'">';

                    $tHtml .= '<tr>
                                <td class="mainRTD">
                                    <table class="table">
                                        <tr>
                                            <td class="childRTD">Date</td>
                                        </tr>
                                        <tr>
                                            <td class="childRTD">Hours</td>
                                        </tr>
                                        <tr>
                                            <td class="childRTD">Cycles</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="mainRTD">
                                    <table class="table">
                                        <tr>
                                            <td class="childRTD">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="childRTD">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="childRTD">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="mainRTD">
                                    <table class="table">
                                        <tr>
                                            <td class="childRTD">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="childRInp"><input type="text" name="hours_accrued['.$key.']" class="getValid hrsUpCls form-control hours_'.$key.'" '.$dataAtr.' '.$readonly.'></td>
                                        </tr>
                                        <tr>
                                            <td class="childRInp"><input type="number" name="cycles_accrued['.$key.']" class="cycleUpCls form-control cycles_'.$key.'" '.$dataAtr.' '.$readonly.'></td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="mainRTD">
                                    <table class="table">
                                        <tr>
                                            <td class="childRInp">
                                                <div class="date datePicker">
                                                    <input type="text" name="log_date['.$key.']" value="'.$date.'" class="form-control logDatepicker log_date_'.$key.'" '.$readonly.'>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="childRInp">
                                                <input type="text" name="hours['.$key.']" value="" class="form-control newHrsCls hours_'.$key.'" data-currenthr_'.$key.'="" '.$readonly.'>
                                                <input type="hidden" name="hhours['.$key.']" value="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="childRInp">
                                                <input type="number" name="cycles['.$key.']" value="" class="form-control cycles_'.$key.'" '.$readonly.'>
                                                <input type="hidden" name="hcycles['.$key.']" value="">
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class="mainRTD">';
                                if(!in_array($value['log_book'], ['Airframe','Air Conditioner'])) {
                                    $tHtml .= '<table class="table">
                                        <tr>
                                            <td class="childChk">
                                                <input type="checkbox" name="log_date_chk['.$key.']" class="chkClass" data-key="'.$key.'" data-val="log_date">
                                                <input type="hidden" name="log_book['.$key.']" value="'.$value['log_book'].'">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="childChk">
                                                <input type="checkbox" name="hours_chk['.$key.']" class="chkClass" data-key="'.$key.'" data-val="hours">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="childChk">
                                                <input type="checkbox" name="cycles_chk['.$key.']" class="chkClass" data-key="'.$key.'" data-val="cycles">
                                            </td>
                                        </tr>
                                    </table>';
                                } else {
                                    $tHtml .= '<table class="table" style="display:none;">
                                        <tr>
                                            <td class="childChk">
                                                <input type="checkbox" name="log_date_chk['.$key.']" checked>
                                                <input type="hidden" name="log_book['.$key.']" value="'.$value['log_book'].'">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="childChk">
                                                <input type="checkbox" name="hours_chk['.$key.']" checked>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="childChk">
                                                <input type="checkbox" name="cycles_chk['.$key.']" checked>
                                            </td>
                                        </tr>
                                    </table>';
                                }
                                $tHtml .= '</td>
                            </tr>';
                }
            }
            $tHtml .= '</table>';
            $tHtml .= '<table class="table table-hover" style="padding:0;margin:0;">
                            <tr>
                                <td colspan="2" style="padding:8px 0 8px 10px; color:#fff; background-color:#2e3238;">Additional Information</td>
                            </tr>
                            <tr>
                                <td>Reference</td>
                                <td><input type="text" name="reference" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>Notes</td>
                                <td><textarea name="notes" class="form-control" rows="3"></textarea></td>
                            </tr>
                        </table>';
            $tHtml .= '</div></div>';

            $result = array('status'=>'success', 'planeCode'=>$reports['plane_code'], 'data'=>$tHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$tHtml);
            echo json_encode($result);die;
        }
    }

    //Get component time for update
    public function compProjectedTime()
    {
        $req = $this->request->getData();
        $planeid = !empty($req['plane_id']) ? '<input type="hidden" name="pids" value="'.$req['plane_id'].'">' : '';
        $partids = !empty($req['partids']) ? '<input type="hidden" name="partids" value="'.implode(",",$req['partids']).'">' : '';
        $type = !empty($req['type']) ? '<input type="hidden" name="type" value="'.$req['type'].'">' : '';
        $reporttype = !empty($req['reporttype']) ? '<input type="hidden" name="reporttype" value="'.$req['reporttype'].'">' : '';
        $searchval = !empty($req['searchval']) ? '<input type="hidden" name="searchval" value="'.$req['searchval'].'">' : '';
        $searchby = !empty($req['searchby']) ? '<input type="hidden" name="searchby" value="'.$req['searchby'].'">' : '';
        $titlen = !empty($req['titlen']) ? '<input type="hidden" name="titlen" value="'.$req['titlen'].'">' : '';
        $datasort = !empty($req['datasort']) ? '<input type="hidden" name="datasort" value="'.$req['datasort'].'">' : '';
        $action = !empty($req['action']) ? '<input type="hidden" name="action" value="'.$req['action'].'">' : '';

        $reports = $this->planeObj->find()
                    ->where(['Planes.id'=>$req['plane_id']])
                    ->contain([
                        'AirframeComponents'=>[
                            'fields'=>[
                                'AirframeComponents.id',
                                'AirframeComponents.plane_id',
                                'AirframeComponents.log_book',
                                'AirframeComponents.position',
                                'AirframeComponents.description',
                                'AirframeComponents.serial_no'
                            ],
                            'sort'=>['AirframeComponents.id'=>'ASC'],
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
                    ])
                    ->select(['Planes.id', 'Planes.plane_code'])
                    ->enableHydration(false)->first();

        $tHtml = '';
        if(!empty($reports)) {
            $tHtml = '<div class="x_panel">
                        <div class="x_content">
                            '.$planeid.'
                            '.$partids.'
                            '.$type.'
                            '.$reporttype.'
                            '.$searchval.'
                            '.$searchby.'
                            '.$titlen.'
                            '.$datasort.'
                            '.$action.'
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0 15px 25px 15px;">
                                    <label>Date</label>
                                    <input type="text" name="log_date" class="form-control projDatepicker">
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12"style="padding:0 15px 25px 15px;">
                                    <label>Hours</label>
                                    <input type="text" name="hours" class="form-control">
                                </div> 
                            </div>
                            <div class="row">
                                <div cclass="col-md-12 col-sm-12 col-xs-12" style="padding:0 15px 25px 15px;">
                                    <label>Landing/Cycles</label>
                                    <input type="number" name="cycles" class="form-control">
                                </div> 
                            </div>
                        </div>
                    </div>';

            $result = array('status'=>'success', 'planeCode'=>$reports['plane_code'], 'data'=>$tHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$tHtml);
            echo json_encode($result);die;
        }
    }

    /**
     * Add Report Time (When new component installed the new field values will update otherwise accure field values will update)
     */
    public function addTime() 
    {
        $params = $this->request->getData();
        //pr($params);die;
        $results = [];
        if(!empty($params['id'])) {
            $authUserData = $this->Authentication->getResult()->getData();

            $i=0;
            $time_id = '';
            $historyId = [];
            foreach ($params['id'] as $key => $value) {
                $results[$key]['id'] = $value;
                $results[$key]['plane_id'] = $params['plane_id'][$key];
                $results[$key]['airframe_component_id'] = $params['airframe_component_id'][$key];
                //Add current date
                //$results[$key]['log_date'] = date('m-d-Y');
                $results[$key]['log_date'] = $params['log_date'][$key].' '.date('H:i:s');
                
                if(!empty($params['hours_chk'][$key]) || !empty($params['hours_accrued'][$key])) {

                    if(!empty($params['hours'][$key]) && !empty($params['hhours'][$key]) && $params['hours'][$key] == $params['hhours'][$key]) {

                        if(!empty($params['hours_accrued'][$key])) {
                            $results[$key]['hours'] = $params['hours_accrued'][$key];
                        } else {
                            $results[$key]['hours'] = $params['hours'][$key];
                        }

                    } elseif(!empty($params['hours'][$key]) && !empty($params['hhours'][$key]) && $params['hours'][$key] > $params['hhours'][$key] && $params['log_book'][$key] == 'Airframe') {
                        
                        $results[$key]['hours'] = $params['hours'][$key];

                    } elseif (!empty($params['hours'][$key]) && !empty($params['hhours'][$key]) && ($params['hours'][$key] > $params['hhours'][$key] || $params['hours'][$key] < $params['hhours'][$key]) && $params['log_book'][$key] != 'Airframe') {
                        
                        $results[$key]['hours'] = $params['hours'][$key];

                    } else {

                        if(!empty($params['hours_accrued'][$key])) {
                            $results[$key]['hours'] = $params['hours_accrued'][$key];
                        } else {
                            $results[$key]['hours'] = $params['hours'][$key];
                        }
                    }
                } else {
                    $results[$key]['hours'] = $params['hours'][$key];
                }

                if(!empty($params['cycles_chk'][$key]) || !empty($params['cycles_accrued'][$key])) {

                    if(!empty($params['cycles'][$key]) && !empty($params['hcycles'][$key]) && $params['cycles'][$key] == $params['hcycles'][$key]) {

                        if(!empty($params['cycles_accrued'][$key])) {
                            $results[$key]['cycles'] = $params['cycles_accrued'][$key];
                        } else {
                            $results[$key]['cycles'] = $params['cycles'][$key];
                        }

                    } elseif(!empty($params['cycles'][$key]) && !empty($params['hcycles'][$key]) && $params['cycles'][$key] > $params['hcycles'][$key] && $params['log_book'][$key] == 'Airframe') {
                        
                        $results[$key]['cycles'] = $params['cycles'][$key];

                    } elseif (!empty($params['cycles'][$key]) && !empty($params['hcycles'][$key]) && ($params['cycles'][$key] > $params['hcycles'][$key] || $params['cycles'][$key] < $params['hcycles'][$key]) && $params['log_book'][$key] != 'Airframe') {
                        
                        $results[$key]['cycles'] = $params['cycles'][$key];

                    } else {

                        if(!empty($params['cycles_accrued'][$key])) {
                            $results[$key]['cycles'] = $params['cycles_accrued'][$key];
                        } else {
                            $results[$key]['cycles'] = $params['cycles'][$key];
                        }
                    }
                } else {
                    $results[$key]['cycles'] = $params['cycles'][$key];
                }

                //Get user and accrued times
                $results[$key]['user_id'] = $authUserData['id'];
                $hours_accrued = '';
                if(!empty($results[$key]['hours']) && !empty($params['hhours'][$key])) {
                    $hours_accrued = $results[$key]['hours'] - $params['hhours'][$key];
                    $results[$key]['hours_accrued'] = !empty($hours_accrued) && is_numeric($hours_accrued) ? round($hours_accrued,1) : 0;
                }
                
                $results[$key]['cycles'] = !empty($results[$key]['cycles']) ? $results[$key]['cycles'] : 0;
                $params['hcycles'][$key] = !empty($params['hcycles'][$key]) ? $params['hcycles'][$key] : 0;
                $results[$key]['cycles_accrued'] = $results[$key]['cycles'] - $params['hcycles'][$key];

                //If propeller and air conditioner then cycles is 0
                if($params['log_book'][$key] == 'Propeller' || $params['log_book'][$key] == 'Air Conditioner') {
                    $results[$key]['cycles'] = 0;
                    $results[$key]['cycles_accrued'] = 0;
                }

                //Update query
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
            $data['plane_id']  = $params['plane_id'][0];
            $data['airframe_component_time_id'] = $time_id;
            $data['history_ids'] = implode(",",$historyId);
            $data['reference'] = $params['reference'];
            $data['notes']     = $params['notes'];
            $data['date']      = new \Cake\I18n\FrozenTime('now');
            $extDetail = $this->extDetailObj->patchEntity($extDetail, $data);
            $this->extDetailObj->save($extDetail);

            $res = array('status'=>'success', 'message'=>'Report Time added successfully.');
            echo json_encode($res);die;
        } else {
            $res = array('status'=>'failure', 'message'=>'Please enter details.');
            echo json_encode($res);die;
        }
    }

    //Aircraft availability form with details
    public function aircraftAvailability()
    {
        $req = $this->request->getData();
        $reports = $this->planeObj->find()
                    ->where(['Planes.id'=>$req['plane_id']])
                    ->select(['Planes.id', 'Planes.plane_code', 'Planes.status'])
                    ->enableHydration(false)->first();

        $tHtml = '';
        if(!empty($reports)) {
            $result = array('status'=>'success', 'data'=>$reports);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$reports);
            echo json_encode($result);die;
        }
    }

    //Update aircraft availability
    public function updateAirStatus() 
    {
        $params = $this->request->getData();
        if(!empty($params)) {
            //Update query
            $airStatus = $this->planeObj->get($params['plane_id']);
            $airStatus['status'] = $params['status'];
            $airStatus = $this->planeObj->patchEntity($airStatus, $params);

            //status 
            $sts = explode("_", $params['status']);
            if(count($sts)>1) {
                $status = ucwords($sts[0].' '.$sts[1].' '.$sts[2]);
            } else {
                $status = ucwords($params['status']);
            }
            $params['status'] = $status;
                 
            if ($this->planeObj->save($airStatus)) {
                $res = array('status'=>'success', 'message'=>'Aircraft status updated successfully.', 'data'=>$params);
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'message'=>'Some error occured. Please try again!', 'data'=>$params);
                echo json_encode($res);die;
            }
        } else {
            $res = array('status'=>'failure', 'message'=>'Please enter details.');
            echo json_encode($res);die;
        }
    }

    //Aircraft historical time
    public function aircraftHistoricalTime()
    {
        $reportAction='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Generate Report', $actionStatus))
            {
                $reportAction = $actionStatus['Generate Report'];
            }
        }

        $params = $_GET;
        $cond = ['AirframeComponents.log_book'=>'Airframe'];
        $airCompQuery = function ($q) use ($cond) { 
                        return $q->where($cond)->contain([
                            'AirframeComponentTimes'=>[
                                'sort'=>['AirframeComponentTimes.id'=>'DESC'],
                                'ExtraDetails'=>[
                                    'sort'=>['ExtraDetails.id'=>'DESC']
                                ]
                            ],
                        ]);
                    };

        $results = $this->planeObj->find()
                    ->where(['Planes.id'=>$params['plane_id']])
                    ->contain([ 
                        'AirframeComponents'=>$airCompQuery,
                        /*'ExtraDetails'=>[
                            'sort'=>['ExtraDetails.id'=>'DESC']
                        ]*/
                    ])
                    ->enableHydration(false)->first();

        $this->set(compact('reportAction', 'results'));
    }

    //Equipment historical time
    public function historicalEquipmentTime()
    {
        $params = $this->request->getData();
        $historyIds = explode(",", $params['history_ids']);
        //Count component
        $compCount = $this->airCompObj->find()
                        ->select('id')
                        ->where(['AirframeComponents.plane_id' => $params['plane_id']])
                        ->enableHydration(false)->toArray();
        $compCount = count($compCount);

        $records = $this->airCompTimeObj->find()
                    ->where(['AirframeComponentTimes.id IN'=>$historyIds, 'AirframeComponentTimes.plane_id'=>$params['plane_id'], 'AirframeComponentTimes.log_date'=>$params['log_date']])
                    ->contain([ 
                        'AirframeComponents',
                        'ExtraDetails'
                    ])
                    ->enableHydration(false)->toArray();

        /*
        //Extra details
        $extDetails = $this->extDetailObj->find()
                    ->where(['ExtraDetails.plane_id'=>$params['plane_id'], 'ExtraDetails.date'=>$params['log_date']])
                    ->select(['reference','notes'])
                    ->enableHydration(false)->first();
        */

        $tHtml = '';
        if(!empty($records)) {
            $tHtml = '<div class="x_panel"><div class="x_content"><table class="table table-hover table-header-dark" style="padding:0;margin:0;">';
            
            $tHtml .= '<thead><tr>
                                <th width="30%">Display</th>
                                <th width="20%">Model</th>
                                <th width="20%">Serial</th>
                                <th width="20%">Report Date</th>
                                <th width="10%">Hours</th>
                                <th width="10%">Cycles</th>                              
                            </tr></thead>';
            $i = 1;
            $reference = '';
            $notes = '';
            foreach ($records as $key => $value) {
                if($i <= $compCount) {
                    if($value['airframe_component']['log_book'] == 'Airframe') {
                        $logBook = $value['airframe_component']['log_book'];
                        $reference = $value['extra_details'][0]['reference'];
                        $notes = $value['extra_details'][0]['notes'];
                    } else {
                        $logBook = $value['airframe_component']['log_book'].' '.$value['airframe_component']['position'];
                    }

                    $date = date('m-d-Y', strtotime($value['log_date']));

                    $tHtml .= '<tr>
                                <td>'.$logBook.'</td>
                                <td>'.$value['airframe_component']['description'].'</td>
                                <td>'.$value['airframe_component']['serial_no'].'</td>
                                <td>'.$date.'</td>
                                <td>'.$value['hours'].'</td>
                                <td>'.$value['cycles'].'</td>
                            </tr>';
                }
                $i++;
            }
            $tHtml .= '</table>';

            $tHtml .= '<table class="table table-hover" style="padding:0;margin:0;">
                            <tr>
                                <td colspan="2" style="padding:8px 0 8px 10px; color:#fff; background-color:#2e3238;">Additional Information</td>
                            </tr>
                            <tr>
                                <td colspan="2">Reference: '.$reference.'</td>
                            </tr>
                            <tr>
                                <td colspan="2">Notes: '.$notes.'</td>
                            </tr>
                        </table>';
            $tHtml .= '</div></div>';

            $result = array('status'=>'success', 'data'=>$tHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$tHtml);
            echo json_encode($result);die;
        }
    }

    /**
     * Generate Aircraft Times PDF
     */
    public function generateAircraftTimesPdf()
    {
        $req = $this->request->getData();  
        $reports = $this->planeObj->find()
                    ->where(['Planes.id'=>$req['pids']])
                    ->contain([ 
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
                                    'AirframeComponentTimes.airframe_component_id',
                                    'AirframeComponentTimes.log_date',
                                    'AirframeComponentTimes.hours',
                                    'AirframeComponentTimes.cycles' 
                                ],
                                'sort'=>['AirframeComponentTimes.id'=>'DESC']
                            ]
                        ]
                    ])
                    ->select(['Planes.id', 'Planes.plane_name', 'Planes.plane_type', 'Planes.plane_code', 'Planes.plane_serial_number'])
                    ->enableHydration(false)->first();

        $airTHtml = '';
        if(!empty($reports)) {
            $airTHtml .='<table style="width:95%; margin:0 auto; padding:5px 0 10px 0; font-size:11px;" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="font-size:20px; font-weight:bold; padding:8px 0 8px 0; text-align:right;">'.$reports['plane_name'].' - '.$reports['plane_code'].'</td>
                </tr>
            </table>';
            $airTHtml .='<table style="width:100%; margin:0 auto; padding:20px 0 10px 0; font-size:11px;" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="padding: 0 0 15px 0;">
                        <table class="table-bordered" style="width:98%; margin:0 auto; padding:0; border-collapse: collapse; text-align:center;">
                            <thead>
                                <tr>
                                    <td style="width:20%; font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 10px 2px 10px 2px; background-color:#c0c0c0; margin: 0;">Component</td>
                                    <td style="width:40%; font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 10px 2px 10px 2px; background-color:#c0c0c0; margin: 0;">Model (Serial)</td>
                                    <td style="width:14%; font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 10px 2px 10px 2px; background-color:#c0c0c0; margin: 0;">Date</td>
                                    <td style="width:13%; font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 10px 2px 10px 2px; background-color:#c0c0c0; margin: 0;">Hours</td>
                                    <td style="width:13%; font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 10px 2px 10px 2px; background-color:#c0c0c0; margin: 0;">Cycles</td>
                                </tr></thead><tbody>';
                                foreach ($reports['airframe_components'] as $key => $value) {
                                    if($value['log_book'] == 'Airframe') {
                                        $component = $value['log_book'];
                                    } else {
                                        $component = $value['log_book'].' '.$value['position'];
                                    }

                                    $modal = !empty($value['description']) ? $value['description'] : '';
                                    if(!empty($value['serial_no'])) {
                                        $modal .= ' ('.$value['serial_no'].')';
                                    }

                                    $newDate = '';
                                    if(!empty($value['airframe_component_times'][0]['log_date'])) {
                                        $newDate = date('d-M-Y', strtotime($value['airframe_component_times'][0]['log_date']));
                                    }

                                    //Components time
                                    $compHours = !empty($value['airframe_component_times'][0]['hours']) ? $value['airframe_component_times'][0]['hours'] : '';
                                    $compCycles = !empty($value['airframe_component_times'][0]['cycles']) ? $value['airframe_component_times'][0]['cycles'] : '';

                                    $airTHtml .='<tr>
                                        <td style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 10px 2px 10px 2px; margin: 0;">'.$component.'</td>
                                        <td style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 10px 2px 10px 2px; margin: 0;">'.$modal.'</td>
                                        <td style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 10px 2px 10px 2px; margin: 0;">'.$newDate.'</td>
                                        <td style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 10px 2px 10px 2px; margin: 0;">'.$compHours.'</td>
                                        <td style="font-size: 12px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 10px 2px 10px 2px; margin: 0;">'.$compCycles.'</td>
                                    </tr>';
                                }
                            $airTHtml .='</tbody>
                        </table>
                    </td>
                </tr>
            </table>';
        }
        
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
                            top: 1cm;
                            left: 0cm;
                            right: 0cm;
                            bottom: 0cm;
                            height: 1cm;
                        }

                        footer {
                            position: fixed; 
                            bottom: 0cm;
                            left: 0cm; 
                            right: 0cm;
                            height: 1.5cm;
                        }

                        .page_break { 
                            page-break-before: always; 
                        }

                        .table-bordered thead tr td {
                            border: 1px solid #c0c0c0;
                        }

                        .table-bordered tbody tr td {
                            border: 1px solid #c0c0c0;
                        }
                    </style>
                </head>
                <body>
                    <header>
                        <table id="header" style="width:98%; margin:0 auto; padding:0; text-align:center;" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td style="font-size:20px; font-weight:bold; padding:8px 0 8px 0; color:#fff; background-color:#2e3238;">AIRCRAFT TIMES</td>
                                </tr>
                            </tbody>
                        </table>
                    </header>
                    <footer>
                        <table style="width:100%; margin:0 auto; padding:0; font-size:11px;" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="font-size:11px; padding:12px; text-align:right; float:right;">
                                    <script type="text/php">
                                        if ( isset($pdf) ) {
                                            $font = $fontMetrics->getFont("Arial, Helvetica, sans-serif", "normal");
                                            $pdf->page_text(745, 582, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 7, array(0,0,0));
                                        }
                                    </script>
                                    </td>
                                </tr>
                        </table>
                    </footer>
                    <main>'.$airTHtml.'</main>        
                </body>
                </html>';

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
        $fileName = "Maintenance".date('YmdHis').".pdf";
        $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
        if($airTHtml) {
            $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>'');
            echo json_encode($result);die;
        }
    }

    //Update current time
    public function updateCurrentTime()
    {
        $params = $this->request->getData();
        if(!empty($params['plane_id']) && !empty($params['comp_id'])) {
            $result = $this->airCompTimeObj->find()
                            ->where([
                                'AirframeComponentTimes.plane_id'=>$params['plane_id'], 
                                'AirframeComponentTimes.airframe_component_id'=>$params['comp_id']
                            ])
                            ->select(['id', 'hours', 'cycles'])
                            ->order(['AirframeComponentTimes.id DESC'])
                            ->enableHydration(false)->first();

            if(!empty($result)) {
                $data = array(
                        'id' => $result['id'],
                        'hours' => $result['hours'],
                        'cycles' => $result['cycles']
                    );
                $result = array('status'=>'success', 'data'=>$data);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>array());
                echo json_encode($result);die;
            }
        }
    }

    public function loadMoreData()
    {
        $params = $this->request->getData();
        if($params['loader'] == 'yes') {
            $result = array('status'=>'success');
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure');
            echo json_encode($result);die;
        }
    }

    //Parent/Child listing
    public function parentChildInfo()
    {
        $this->viewBuilder()->setLayout('ajax');
        $params = $this->request->getData();
        $type = $params['type'];
        $pid = $params['pid'];
        $cid = $params['cid'];
        $typ = $params['typ'];
        $act = $params['act'];
        //Get sub items
        $subResults = array();
        //To check parent child relation
        /*$childQuery = function ($q) { 
                        return $q->where(['AirframeComponentParts.parent_id !='=>0, 'AirframeComponentParts.id !='=>'ParentChildRelations.parent_id'])
                        ->select(['AirframeComponentParts.id', 'AirframeComponentParts.plane_id'])
                        ->contain([
                            'ParentChildRelations'=>[
                                'fields'=>['ParentChildRelations.airframe_component_part_id', 'ParentChildRelations.parent_id']
                            ]
                        ]);
                    };
        
        //Components associate tables
        $compAssociates = $this->Report->associateQuery();

        $contain = [ 
                    'Planes'=>[
                        'fields'=>[
                            'Planes.id',
                            'Planes.plane_code'
                        ],
                        'AirframeComponentParts'=>$childQuery
                    ],
                    'AirframeComponents'=>$compAssociates,
                    'AirframeComponentLastCw'=>[
                        'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                    ],
                ];
        if(!empty($type) && $type == 'parent') {
            $partIds = [''];
            if(!empty($params['airid']) && !empty($params['pid'])) {
                $prChildRes = $this->parentChildRelObj->find()
                                ->where([
                                    'ParentChildRelations.plane_id'=>$params['airid'], 
                                    'ParentChildRelations.parent_id'=>$params['pid']
                                ])
                                ->select(['airframe_component_part_id'])
                                ->enableHydration(false)->toArray();
                                        
                if(!empty($prChildRes)) {
                    foreach ($prChildRes as $key => $value) {
                        $partIds[] = $value['airframe_component_part_id'];
                    }
                }
            }

            $partRecords = $this->airCompPartObj->find()
                                ->where(['AirframeComponentParts.plane_id'=>$params['airid'], 'AirframeComponentParts.id IN'=>$partIds])
                                ->contain($contain)
                                ->enableHydration(false)->toArray();
        } elseif(!empty($type) && $type == 'child') {
            $partRecords = $this->airCompPartObj->find()
                                ->where(['AirframeComponentParts.plane_id'=>$params['airid'], 'AirframeComponentParts.id IN'=>explode(",",$pid)])
                                ->contain($contain)
                                ->enableHydration(false)->toArray();
        }*/

        // Child query definition for nested containment
        $childQuery = function ($q) {
            return $q
                ->where([
                    'AirframeComponentParts.parent_id !=' => 0
                ])
                ->andWhere(function ($exp, $query) {
                    return $exp->notEq('AirframeComponentParts.id', $query->identifier('ParentChildRelations.parent_id'));
                })
                ->select(['AirframeComponentParts.id', 'AirframeComponentParts.plane_id'])
                ->contain([
                    'ParentChildRelations' => [
                        'fields' => [
                            'ParentChildRelations.airframe_component_part_id',
                            'ParentChildRelations.parent_id'
                        ]
                    ]
                ]);
        };

        // Load AirframeComponents association fields
        $compAssociates = $this->Report->associateQuery();

        // Common contain array
        $contain = [
            'Planes' => [
                'fields' => ['Planes.id', 'Planes.plane_code'],
                'AirframeComponentParts' => $childQuery
            ],
            'AirframeComponents' => $compAssociates,
            'AirframeComponentLastCw' => function ($q) {
                return $q->order(['AirframeComponentLastCw.id' => 'DESC']);
            }
        ];

        // Main logic based on type
        if (!empty($type) && $type === 'parent') {
            $partIds = [];

            if (!empty($params['airid']) && !empty($params['pid'])) {
                $prChildRes = $this->parentChildRelObj->find()
                    ->where([
                        'ParentChildRelations.plane_id' => $params['airid'],
                        'ParentChildRelations.parent_id' => $params['pid']
                    ])
                    ->select(['airframe_component_part_id'])
                    ->enableHydration(false)
                    ->toArray();

                foreach ($prChildRes as $value) {
                    $partIds[] = $value['airframe_component_part_id'];
                }
            }

            $partRecords = $this->airCompPartObj->find()
                ->where([
                    'AirframeComponentParts.plane_id' => $params['airid'],
                    'AirframeComponentParts.id IN' => $partIds ?: ['0'] // fallback to avoid SQL error
                ])
                ->contain($contain)
                ->enableHydration(false)
                ->toArray();

        } elseif (!empty($type) && $type === 'child') {
            $ids = !empty($pid) ? explode(',', $pid) : ['0'];

            $partRecords = $this->airCompPartObj->find()
                ->where([
                    'AirframeComponentParts.plane_id' => $params['airid'],
                    'AirframeComponentParts.id IN' => $ids
                ])
                ->contain($contain)
                ->enableHydration(false)
                ->toArray();
        }
                    
        //Get conditional records
        $subResults = $this->condRecords($partRecords, $params=array());

        $this->set(compact(['subResults', 'type', 'pid', 'cid', 'typ', 'act']));
    }

    //Quick reference
    public function quickReference()
    {
        $params = $_GET;
        $type = !empty($params['type']) ? $params['type'] : '';
        $action = !empty($params['action']) ? $params['action'] : '';
        $planeId = !empty($params['AircraftIds']) ? $params['AircraftIds'] : '';
        
        //Components associate tables
        $compAssociates = $this->Report->associateQuery();

        //Get parts which are in quick reference
        $partRecords = $this->airCompPartObj->find()
                        ->where(['AirframeComponentParts.plane_id'=>$planeId, 'AirframeComponentParts.quick_ref'=>'yes'])
                        ->contain([ 
                            'Planes'=>[
                                'fields'=>[
                                    'Planes.id',
                                    'Planes.plane_code'
                                ],
                            ],
                            'Groups'=>[
                                'fields'=>[
                                    'Groups.id',
                                    'Groups.airframe_component_part_id',
                                    'Groups.group_name'
                                ]
                            ],
                            'AirframeComponents'=>$compAssociates,
                            'AirframeComponentLastCw'=>[
                                'sort' => ['AirframeComponentLastCw.id' => 'DESC'],
                            ]
                        ])
                        ->enableHydration(false)->toArray();
            
        //Get conditional records
        $refResults = $this->condRecords($partRecords, $params=array());

        //Display Status
        $displayStatus = $this->displayStatus($type);
        $actionItems   = !empty($displayStatus['actionItems']) ? $displayStatus['actionItems'] : '';
        $reportAction  = !empty($displayStatus['reportAction']) ? $displayStatus['reportAction'] : '';
        $this->set(compact('actionItems', 'reportAction', 'planeId', 'refResults', 'action'));
    }

    //Add to quick reference
    public function addToQuickRef()
    {
        $params = $this->request->getData();
        if($this->request->is(['patch', 'post', 'put'])) 
        {
            $planeId = $params['planeId'];
            $partIds  = $params['partIds'];
            $this->airCompPartObj->updateAll(
                ['quick_ref' => 'yes'],
                ['id IN' => $partIds, 'plane_id' => $planeId]
            );

            $result = array('status'=>'success', 'message'=>'Updated successfully.');
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something Wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

    //Remove quick reference
    public function deleteQuickRef()
    {
        $params = $this->request->getData();
        if($this->request->is(['patch', 'post', 'put'])) 
        {
            $planeId = $params['planeId'];
            $partIds  = $params['partIds'];
            $this->airCompPartObj->updateAll(
                ['quick_ref' => 'no'],
                ['id IN' => $partIds, 'plane_id' => $planeId]
            );

            $result = array('status'=>'success', 'message'=>'Updated successfully.');
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something Wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

    //Part Details PDF
    public function printPartDetailPdf_original()
    {
        $params = $this->request->getData();
        $compPartDetail = $this->airCompPartObj->get($params['partid'], [
                            'contain' => [
                                'Groups'=>[
                                    'fields'=>[
                                        'Groups.id',
                                        'Groups.airframe_component_part_id',
                                        'Groups.group_name'
                                    ]
                                ],
                                'AirframeComponentLastCw'=>[
                                    'strategy' => 'select',
                                    'queryBuilder' => function ($q) {
                                        return $q->order(['AirframeComponentLastCw.id'=>'DESC'])->limit(1);
                                    }
                                ], 
                                'AirframeComponents'=>[
                                    'AirframeComponentTimes'=>[
                                        'strategy' => 'select',
                                        'queryBuilder' => function ($q) {
                                            return $q->order(['AirframeComponentTimes.id'=>'DESC'])->limit(1);
                                        }
                                    ]
                                ],
                                'PartInstalledTimes'=>[
                                    'sort'=>['PartInstalledTimes.id'=>'DESC']
                                ]
                            ]
                        ]);
        //pr($compPartDetail);die;

        $airTHtml = '';
        if(!empty($compPartDetail)) {
            $airCode = $this->Plane->getPlaneName($params['planeid']);
            $aircraft = $this->Plane->getPlanes(array($params['planeid']));
            $airComp = $this->AirframeComponent->getCompDetail($compPartDetail->airframe_component_id);
            
            $itemTypes = $this->AirframeComponentPart->getItemTypes();
            $itemType = !empty($compPartDetail['item_type']) ? $itemTypes[$compPartDetail['item_type']] : 'Select';
            
            $dispTitle = $this->Disposition->dispTitle($compPartDetail->disposition);
            $dispTitle = !empty($dispTitle) ? $dispTitle : 'Select';
            
            $ataCode = $this->AtaCode->ataTitle($compPartDetail->ata_code);
            $ataCode = !empty($ataCode) ? $ataCode : 'Select';

            $mfgCode = !empty($compPartDetail['mfg_code']) ? $compPartDetail['mfg_code'] : '&nbsp;';
            $adSbNumber = !empty($compPartDetail['ad_sb_number']) ? $compPartDetail['ad_sb_number'] : '&nbsp;';
            $adsbStatus = $this->AdsbStatus->adsbTitle($compPartDetail->ad_sb_status);
            $adsbStatus = !empty($adsbStatus) ? $adsbStatus : 'Select';
            
            $reference = !empty($compPartDetail['reference']) ? $compPartDetail['reference'] : '&nbsp;';
            $reqTypes = $this->AirframeComponentPart->getRequirementTypes();
            $reqType = !empty($compPartDetail['requirement_type']) ? $reqTypes[$compPartDetail['requirement_type']] : 'Select';
            $amendment = !empty($compPartDetail['amendment']) ? $compPartDetail['amendment'] : '&nbsp;';
            $authority = !empty($compPartDetail['authority']) ? $compPartDetail['authority'] : 'Select';
            
            $itemName = !empty($compPartDetail['description']) ? $compPartDetail['description'] : '&nbsp;';
            $notes = !empty($compPartDetail['notes']) ? $compPartDetail['notes'] : '&nbsp;';
            $workDesc = !empty($compPartDetail['work_description']) ? $compPartDetail['work_description'] : '&nbsp;';
            $avgManHrs = !empty($compPartDetail['avg_man_hrs']) ? $compPartDetail['avg_man_hrs'] : '&nbsp;';
            $tags = !empty($compPartDetail['tags']) ? $compPartDetail['tags'] : '&nbsp;';

            $isResThres = !empty($compPartDetail['airframe_component_last_cw'][0]['is_recThres']) ? $compPartDetail['airframe_component_last_cw'][0]['is_recThres'] : '';

            $recurring = (!empty($isResThres) && $isResThres == 'recurring') ? "checked" : "";
            $threshold = (!empty($isResThres) && $isResThres == 'threshold') ? "checked" : "";

            //Change alert box value
            if(!empty($isResThres) && $isResThres == 'recurring') {
                $alertDays = ($compPartDetail['airframe_component_last_cw'][0]['recurring_mos'] > 24) ? 60 : 30;
                $alertHrs = ($compPartDetail['airframe_component_last_cw'][0]['recurring_hrs'] > 999) ? 200 : 50;
                $alertAfl = ($compPartDetail['airframe_component_last_cw'][0]['recurring_afl'] > 999) ? 200 : 25;
            } elseif(!empty($isResThres) && $isResThres == 'threshold') {
                $alertDays = ($compPartDetail['airframe_component_last_cw'][0]['threshold_mos'] > 24) ? 60 : 30;
                $alertHrs = ($compPartDetail['airframe_component_last_cw'][0]['threshold_hrs'] > 999) ? 200 : 50;
                $alertAfl = ($compPartDetail['airframe_component_last_cw'][0]['threshold_afl'] > 999) ? 200 : 25;
            }

            //Get data correction and nextdue calculation
            $mos = $hrs = $afl = $msc = '';
            if(!empty($compPartDetail['airframe_component_last_cw'][0])) {
                $getRes = $this->AirframeComponentPart->postDataProcess($compPartDetail['airframe_component_last_cw'][0]);
                $mos = $getRes['mos'];
                $hrs = $getRes['hrs'];
                $afl = $getRes['afl'];
                $msc = $getRes['msc'];
            }

            //Start Remaining    
            $remMonths = '';
            $remDays = '';
            if(!empty($mos)) {
                $mos = $this->AirframeComponentPart->changeFormat($mos);
                $date1 = new \DateTime($mos);
                //Do not change date format
                $date2 = new \DateTime(date('d-m-Y'));
                $interval = date_diff($date1, $date2);
                $year = $interval->format('%y');
                $remMonths = $interval->format('%m') + $year * 12;
                $remDays = $interval->format('%d');
                
                if($date1 < $date2) {
                    $remMonths = !empty($remMonths) ? -$remMonths : 0;
                    $remDays = !empty($remDays) ? -$remDays : 0;
                }
            }

            //Component Times
            $compHours  = !empty($compPartDetail['airframe_component']['airframe_component_times'][0]['hours']) ? $compPartDetail['airframe_component']['airframe_component_times'][0]['hours'] : 0;
            $compCycles = !empty($compPartDetail['airframe_component']['airframe_component_times'][0]['cycles']) ? $compPartDetail['airframe_component']['airframe_component_times'][0]['cycles'] : 0;

            $remMonths = !empty($remMonths) ? $remMonths : '&nbsp;';
            $remDays = !empty($remDays) ? $remDays : '&nbsp;';
            $remHours  = !empty($hrs) ? $hrs - $compHours : '&nbsp;';
            $remCycles = !empty($afl) ? $afl - $compCycles : '&nbsp;';
            //End Remaining

            $override = !empty($compPartDetail['airframe_component_last_cw'][0]['override']) ? 'checked' : '';
            $eom = !empty($compPartDetail['airframe_component_last_cw'][0]['eom']) ? 'checked' : '';
            
            $lastCwDate = !empty($compPartDetail['airframe_component_last_cw'][0]['last_cw_date']) ? date('m-d-Y', strtotime($compPartDetail['airframe_component_last_cw'][0]['last_cw_date'])) : '&nbsp;';
            $lastCwHour = !empty($compPartDetail['airframe_component_last_cw'][0]['last_cw_hrs']) ? $compPartDetail['airframe_component_last_cw'][0]['last_cw_hrs'] : '&nbsp;';
            $lastCwCycle = !empty($compPartDetail['airframe_component_last_cw'][0]['last_cw_afl']) ? $compPartDetail['airframe_component_last_cw'][0]['last_cw_afl'] : '&nbsp;';

            $style = '';
            if ((!empty($mos) && strtotime($mos) <= strtotime(date('m-d-Y'))) || (empty($mos) && !empty($remHours) && $remHours < 0)) {
                $style = 'pastDue';
            }

            //Change next due date format
            $mos = !empty($mos) ? $this->AirframeComponentPart->dateFormat($mos) : '&nbsp;';
            $hrs = !empty($hrs) ? $hrs : '&nbsp;';
            $afl = !empty($afl) ? $afl : '&nbsp;';

            //Start Tolerance
            $tolrMon = !empty($compPartDetail['airframe_component_last_cw'][0]['tolerance_mos']) ? $compPartDetail['airframe_component_last_cw'][0]['tolerance_mos'] : '&nbsp;';
            $tolrDays = !empty($compPartDetail['airframe_component_last_cw'][0]['tolerance_days']) ? $compPartDetail['airframe_component_last_cw'][0]['tolerance_days'] : '&nbsp;';
            $tolrHrs = !empty($compPartDetail['airframe_component_last_cw'][0]['tolerance_hrs']) ? $compPartDetail['airframe_component_last_cw'][0]['tolerance_hrs'] : '&nbsp;';
            $tolrAfl = !empty($compPartDetail['airframe_component_last_cw'][0]['tolerance_afl']) ? $compPartDetail['airframe_component_last_cw'][0]['tolerance_afl'] : '&nbsp;';
            //End Tolerance

            //Start Recurring
            $recrMon = !empty($compPartDetail['airframe_component_last_cw'][0]['recurring_mos']) ? $compPartDetail['airframe_component_last_cw'][0]['recurring_mos'] : '&nbsp;';
            $recrDays = !empty($compPartDetail['airframe_component_last_cw'][0]['recurring_days']) ? $compPartDetail['airframe_component_last_cw'][0]['recurring_days'] : '&nbsp;';
            $recrHrs = !empty($compPartDetail['airframe_component_last_cw'][0]['recurring_hrs']) ? $compPartDetail['airframe_component_last_cw'][0]['recurring_hrs'] : '&nbsp;';
            $recrAfl = !empty($compPartDetail['airframe_component_last_cw'][0]['recurring_afl']) ? $compPartDetail['airframe_component_last_cw'][0]['recurring_afl'] : '&nbsp;';
            //End Recurring

            //Start Threshold
            $thresMon = !empty($compPartDetail['airframe_component_last_cw'][0]['threshold_mos']) ? $compPartDetail['airframe_component_last_cw'][0]['threshold_mos'] : '&nbsp;';
            $thresDays = !empty($compPartDetail['airframe_component_last_cw'][0]['threshold_days']) ? $compPartDetail['airframe_component_last_cw'][0]['threshold_days'] : '&nbsp;';
            $thresHrs = !empty($compPartDetail['airframe_component_last_cw'][0]['threshold_hrs']) ? $compPartDetail['airframe_component_last_cw'][0]['threshold_hrs'] : '&nbsp;';
            $thresAfl = !empty($compPartDetail['airframe_component_last_cw'][0]['threshold_afl']) ? $compPartDetail['airframe_component_last_cw'][0]['threshold_afl'] : '&nbsp;';
            //End Threshold

            //Start Interval
            $intrlMon = !empty($compPartDetail['airframe_component_last_cw'][0]['required_frequency_mon']) ? $compPartDetail['airframe_component_last_cw'][0]['required_frequency_mon'] : '&nbsp;';
            $intrlDays = !empty($compPartDetail['airframe_component_last_cw'][0]['required_frequency_days']) ? $compPartDetail['airframe_component_last_cw'][0]['required_frequency_days'] : '&nbsp;';
            $intrlHrs = !empty($compPartDetail['airframe_component_last_cw'][0]['required_frequency_hrs']) ? $compPartDetail['airframe_component_last_cw'][0]['required_frequency_hrs'] : '&nbsp;';
            $intrlAfl = !empty($compPartDetail['airframe_component_last_cw'][0]['required_frequency_afl']) ? $compPartDetail['airframe_component_last_cw'][0]['required_frequency_afl'] : '&nbsp;';
            //End Interval

            //Start Adjustment
            $adjsMon = !empty($compPartDetail['airframe_component_last_cw'][0]['adjustment_mos']) ? $compPartDetail['airframe_component_last_cw'][0]['adjustment_mos'] : '&nbsp;';
            $adjsDays = !empty($compPartDetail['airframe_component_last_cw'][0]['adjustment_days']) ? $compPartDetail['airframe_component_last_cw'][0]['adjustment_days'] : '&nbsp;';
            $adjsHrs = !empty($compPartDetail['airframe_component_last_cw'][0]['adjustment_hrs']) ? $compPartDetail['airframe_component_last_cw'][0]['adjustment_hrs'] : '&nbsp;';
            $adjsAfl = !empty($compPartDetail['airframe_component_last_cw'][0]['adjustment_afl']) ? $compPartDetail['airframe_component_last_cw'][0]['adjustment_afl'] : '&nbsp;';
            //End Adjustment

            //Part and Admin Information
            $partNum = !empty($compPartDetail["part_number"]) ? $compPartDetail["part_number"] : '';
            $serialNum = !empty($compPartDetail["serial_number"]) ? $compPartDetail["serial_number"] : '';

            $newMonths = (!empty($compPartDetail["part_installed_times"][0]["new_months"]) || (isset($compPartDetail["part_installed_times"][0]["new_months"]) && $compPartDetail["part_installed_times"][0]["new_months"] == 0)) ? $compPartDetail["part_installed_times"][0]["new_months"] : "";

            $overhaulMonths = (!empty($compPartDetail["part_installed_times"][0]["overhaul_months"]) || (isset($compPartDetail["part_installed_times"][0]["overhaul_months"]) && $compPartDetail["part_installed_times"][0]["overhaul_months"] == 0)) ? $compPartDetail["part_installed_times"][0]["overhaul_months"] : "";

            $repairMonths = (!empty($compPartDetail["part_installed_times"][0]["repair_months"]) || (isset($compPartDetail["part_installed_times"][0]["repair_months"]) && $compPartDetail["part_installed_times"][0]["repair_months"] == 0)) ? $compPartDetail["part_installed_times"][0]["repair_months"] : "";

            $newHours = (!empty($compPartDetail["part_installed_times"][0]["new_hours"]) || (isset($compPartDetail["part_installed_times"][0]["new_hours"]) && $compPartDetail["part_installed_times"][0]["new_hours"] == 0)) ? $compPartDetail["part_installed_times"][0]["new_hours"] : "";

            $overhaulHours = (!empty($compPartDetail["part_installed_times"][0]["overhaul_hours"]) || (isset($compPartDetail["part_installed_times"][0]["overhaul_hours"]) && $compPartDetail["part_installed_times"][0]["overhaul_hours"] == 0)) ? $compPartDetail["part_installed_times"][0]["overhaul_hours"] : "";

            $repairHours = (!empty($compPartDetail["part_installed_times"][0]["repair_hours"]) || (isset($compPartDetail["part_installed_times"][0]["repair_hours"]) && $compPartDetail["part_installed_times"][0]["repair_hours"] == 0)) ? $compPartDetail["part_installed_times"][0]["repair_hours"] : "";

            $newlandings = (!empty($compPartDetail["part_installed_times"][0]["new_landings"]) || (isset($compPartDetail["part_installed_times"][0]["new_landings"]) && $compPartDetail["part_installed_times"][0]["new_landings"] == 0)) ? $compPartDetail["part_installed_times"][0]["new_landings"] : "";
                                        
            $overhaulLandings = (!empty($compPartDetail["part_installed_times"][0]["overhaul_landings"]) || (isset($compPartDetail["part_installed_times"][0]["overhaul_landings"]) && $compPartDetail["part_installed_times"][0]["overhaul_landings"] == 0)) ? $compPartDetail["part_installed_times"][0]["overhaul_landings"] : "";
        
            $repairLandings = (!empty($compPartDetail["part_installed_times"][0]["repair_landings"]) || (isset($compPartDetail["part_installed_times"][0]["repair_landings"]) && $compPartDetail["part_installed_times"][0]["repair_landings"] == 0)) ? $compPartDetail["part_installed_times"][0]["repair_landings"] : "";

            $workCard = !empty($compPartDetail["work_card"]) ? trim($compPartDetail["work_card"]) : "&nbsp;";
            $position = !empty($compPartDetail["position"]) ? trim($compPartDetail["position"]) : "&nbsp;";
            $version = !empty($compPartDetail["airframe_component_last_cw"][0]["version"]) ? trim($compPartDetail["airframe_component_last_cw"][0]["version"]) : "&nbsp;";

            $lastRevisedBy = !empty($compPartDetail["airframe_component_last_cw"][0]["last_revised_by"]) ? trim($compPartDetail["airframe_component_last_cw"][0]["last_revised_by"]) : "&nbsp;";
            $revisedBy = $this->AirframeComponentPart->getUserName($lastRevisedBy);
            if(!empty($revisedBy)) {
                $lastRevisedBy = $revisedBy;
            }

            $adminNotes = !empty($compPartDetail["admin_notes"]) ? $compPartDetail["admin_notes"] : '&nbsp;';
            $airTHtml .='<div style="background: #fff;" class="formBGCls">
                <div style="border: 1px solid #c0c0c0;">
                    <div style="background: #99a1a6;font-weight: bold;padding: 2px;margin: 5px;height: 24px; text-align:center;">Part Details('.$airCode.' - '.$airComp.' - '.$params['partid'].')</div>
                    <br>
                    <div style="background: #99a1a6;font-weight: bold;padding: 2px;margin: 5px;height: 24px;">Basic Information</div>

                    <div style="width:100%;padding-top:10px;" class="row">
                        <div style="width:26%; float:left; padding-left:5px;">
                            <div style="width:24%; float:left;">Aircraft<span class="required">*</span></div>
                            <div style="width:73%;float:right;">
                                <select size="1.5" name="plane_id">';
                                    foreach ($aircraft as $key => $value) {
                                        $airTHtml .='<option value="'.$key.'">'.$value.'</option>';
                                    }
                                $airTHtml .='</select>
                            </div>
                        </div>

                        <div style="width:24%; float:left; padding-left:5px;">
                            <div style="width:35%; float:left;">Component<span class="required">*</span></div>
                            <div style="width:60%; float:right;">
                                <select size="1.5">
                                    <option value="'.$compPartDetail['airframe_component_id'].'">'.$airComp.'</option>
                                </select>
                            </div>
                        </div>

                        <div style="width:24%; float:left; padding-left:5px; ">
                            <div style="width:35%; float:left;">Item Type</div>
                            <div style="width:60%; float:right;">
                                <select size="1.5" name="item_type">
                                    <option value="'.$compPartDetail['item_type'].'">'.$itemTypes[$compPartDetail['item_type']].'</option>
                                </select>
                            </div>
                        </div>

                        <div style="width:22%; float:left; padding-left:5px; ">
                            <div style="width:35%; float:left;">Disposition</div>
                            <div style="width:60%; float:right;">
                                <select size="1.5">
                                    <option value="'.$compPartDetail['disposition'].'">'.$dispTitle.'</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="width:100%;padding-top:10px;" class="row">
                        <div style="width:26%; float:left; padding-left:5px; ">
                            <div style="width:24%; float:left;">ATA</div>
                            <div style="width:73%; float:right;">
                                <select size="1.5">
                                    <option value="'.$compPartDetail['ata_code'].'">'.$ataCode.'</option>
                                </select>
                            </div>
                        </div>

                        <div style="width:24%; float:left; padding-left:2px; ">
                            <div style="width:26%; float:left;padding:5px;">Mfg Code</div>
                            <div id="input" style="width:54%; float:right;">'.$mfgCode.'</div>
                        </div>

                        <div style="width:24%; float:left; padding-left:2px; ">
                            <div style="width:40%; float:left;padding:5px;">AD/SB Number</div>
                            <div id="input" style="width:48%; float:left;">'.$adSbNumber.'</div>
                        </div>

                        <div style="width:23%; float:left; padding-left:5px; ">
                            <div style="width:35%; float:left;">AD/SB Class</div>
                            <div style="width:60%; float:right;">
                                <select size="1.5">
                                    <option value="'.$compPartDetail['ad_sb_status'].'">'.$adsbStatus.'</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="width:100%;padding-top:10px;" class="row">
                        <div style="width:26%; float:left; padding-left:2px; ">
                            <div style="width:26%; float:left;padding:5px;">Reference</div>
                            <div id="input" style="width:55%; float:left;">'.$reference.'</div>
                        </div>

                        <div style="width:25%; float:left; padding-left:5px; ">
                            <div style="width:35%; float:left;">Requirement Type</div>
                            <div style="width:60%; float:right;">
                                <select size="1.5" name="plane_id" style="width:30px;float: right;">
                                    <option value="'.$compPartDetail['requirement_type'].'">'.$reqType.'</option>
                                </select>
                            </div>
                        </div>

                        <div style="width:23%; float:left; padding-left:2px;">
                            <div style="width:42%; float:left;padding:5px 0 0 0;">Amendment</div>
                            <div id="input" style="width:48%; float:left;">'.$amendment.'</div>
                        </div>

                        <div style="width:23%; float:left; padding-left:6px; ">
                            <div style="width:35%; float:left;">Authority</div>
                            <div style="width:60%; float:right;">
                                <select size="1.5" name="plane_id">
                                    <option value="'.$compPartDetail['authority'].'" >'.$authority.'</option>
                                </select>
                            </div>
                        </div>                            
                    </div>

                    

                    <div style="width:100%;padding:10px 0 0 2px;float:left;" class="row">
                        <div style="width:10%; float:left;padding:5px;">Notes</div>
                        <div id="textarea" style="width:62%; float:left;">'.$notes.'</div>
                        <div style="width:20%;">&nbsp;</div>
                    </div>

                    <div style="width:100%;padding-top:10px;" class="row">
                        <div style="width:76%; float:left; padding-left:2px;">
                            <div style="width:13%; float:left;padding:5px;">Work Description</div>
                            <div id="textarea" style="width:81%; float:left;">'.$workDesc.'</div>
                        </div>

                        <div style="width:21%; float:right; padding-left:5px;">
                            <div style="width:35%; float:left;padding:5px;">Man Hours</div>
                            <div id="input" style="width:50%; float:right;">'.$avgManHrs.'</div>
                        </div>
                    </div>

                    <div style="width:100%;padding:10px 0 0 2px;float:left;" class="row">
                        <div style="width:10%; float:left;padding:5px;">Tags</div>
                        <div id="textarea" style="width:62%; float:left;">'.$tags.'</div>
                        <div style="width:20%;">&nbsp;</div>
                    </div>
                </div>
                
                <div style="clear: both;"></div>

                <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                    <div class="container">
                        <div style="background: #99a1a6;font-weight: bold;padding: 2px;margin: 5px;height: 24px;">Item Information</div>
                        <div class="tab-content">
                            <div id="itemInfo" class="tab-pane fade in active">
                                <div>
                                    <div class="corrPageHeading" style="width:100%; text-align:left;">
                                        <div style="width:19%; float:left; background: #99a1a6; font-weight:bold; padding:2px 2px 2px 2px; margin-left:5px; height:24px;">Last Complied With</div>
                                        <div style="width:19%; float:left; background: #518aed; font-weight:bold; padding:2px 2px 2px 10px; height:24px;">Next Due</div>
                                        <div style="width:19%; float:left; background: #518aed; font-weight:bold; padding:2px 2px 2px 10px; height:24px;">Remaining</div>
                                        <div style="width:19%; float:left; background: #99a1a6; font-weight:bold; padding:2px 2px 2px 10px; height:24px;">Tolerance</div>
                                        <div style="width:18%; float:left; background: #99a1a6; font-weight:bold; padding:2px 3px 2px 10px; height:24px;">Alert</div>
                                    </div>
                                    <div class="itemInfoCls" style="width:100%;">
                                        <div class="itemCls" style="width:19%; float:left; padding:2px 2px 2px 8px;">
                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="float:left;padding:5px;">
                                                    <span style="cursor: pointer; color: #3c8dbc;">Use Current Times</span>
                                                </div>
                                            </div>

                                            <div class="form-group" style="padding-top:12px;"> 
                                                <div style="width:22%; float:left;padding:5px;">Date</div>
                                                <div id="input" style="width:50%; float:left;">'.$lastCwDate.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:22%; float:left;padding:5px;">Hours</div>
                                                <div id="input" style="width:50%; float:left;">'.$lastCwHour.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:22%; float:left;padding:5px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left;">'.$lastCwCycle.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>                               
                                        </div>

                                        <div class="itemCls" style="width:19%; float:left; padding:2px 2px 2px 8px;">
                                            <div class="form-group" style="padding-top:12px;">
                                                <div style="width:55%;float:left;">
                                                    <input type="checkbox" checked='.$override.'>
                                                    <span style="font-size: 10px;">Override Next Due</span>
                                                </div>

                                                <div style="width:40%;float:right;">
                                                    <input type="checkbox" checked='.$eom.'>
                                                    <span style="font-size: 10px;">EoM Adj</span>
                                                </div>
                                            </div>

                                            <div class="form-group" style="padding-top:18px;"> 
                                                <div style="width:25%; float:left;padding:5px;">Date</div>
                                                <div id="input" style="width:50%; float:left;">'.$mos.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Hours</div>
                                                <div id="input" style="width:50%; float:left;">'.$hrs.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left;">'.$afl.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>                            
                                        </div>

                                        <div class="itemCls" style="width:19%; float:left; padding:2px 2px 2px 8px;">
                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Months</div>
                                                <div id="input" style="width:50%; float:left;">'.$remMonths.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;"> 
                                                <div style="width:25%; float:left;padding:5px;">Days</div>
                                                <div id="input" style="width:50%; float:left;">'.$remDays.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Hours</div>
                                                <div id="input" style="width:50%; float:left;">'.(!empty($remHours) && is_numeric($remHours) ? round($remHours,1) : 0).'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left;">'.$remCycles.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>                           
                                        </div>

                                        <div class="itemCls" style="width:19%; float:left; padding:2px 2px 2px 8px;">
                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Months</div>
                                                <div id="input" style="width:50%; float:left;">'.$tolrMon.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;"> 
                                                <div style="width:25%; float:left;padding:5px;">Days</div>
                                                <div id="input" style="width:50%; float:left;">'.$tolrDays.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Hours</div>
                                                <div id="input" style="width:50%; float:left;">'.$tolrHrs.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left;">'.$tolrAfl.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>
                                        </div>

                                        <div class="itemCls" style="width:18%; float:left; padding:2px 2px 2px 8px;">
                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:26%; float:left;padding:5px;">&nbsp;</div>
                                                <div style="width:50%; float:left;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;"> 
                                                <div style="width:26%; float:left;padding:5px;">Days</div>
                                                <div id="input" style="width:50%; float:left;">'.$alertDays.'</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:26%; float:left;padding:5px;">Hours</div>
                                                <div id="input" style="width:50%; float:left;">'.$alertHrs.'</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:26%; float:left;padding:5px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left;">'.$alertAfl.'</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="addPageHeading" style="background:#99a1a6; font-weight:bold; padding:2px; margin:5px; height: 24px;">
                                        <div style="top:-5px;width:24%; float:left; background: #99a1a6; font-weight:bold; padding:2px 2px 2px 2px; margin-left:5px; height:24px;">
                                            Recurring <input type="radio" name="is_recThres" checked='.$recurring.'>
                                        </div>
                                        <div style="top:-5px;width:24%; float:left; background: #99a1a6; font-weight:bold; padding:2px 2px 2px 2px; margin-left:5px; height:24px;">
                                            Threshold <input type="radio" name="is_recThres" checked='.$threshold.'>
                                        </div>
                                        <div style="width:24%; float:left; background: #99a1a6; font-weight:bold; padding:2px 2px 2px 2px; margin-left:5px; height:24px;">Interval</div>
                                        <div style="width:24%; float:left; background: #99a1a6; font-weight:bold; padding:2px 2px 2px 2px; margin-left:5px; height:24px;">Adjustment</div>
                                    </div>
                                    
                                    <div style="clear: both;"></div>

                                    <div class="row" style="width:100%;padding-top:10px;">
                                        <div style="width:24%; float:left; padding:2px 2px 2px 8px;">
                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Months</div>
                                                <div id="input" style="width:50%; float:left;">'.$recrMon.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;"> 
                                                <div style="width:25%; float:left;padding:5px;">Days</div>
                                                <div id="input" style="width:50%; float:left;">'.$recrDays.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Hours</div>
                                                <div id="input" style="width:50%; float:left;">'.$recrHrs.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left;">'.$recrAfl.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>
                                        </div>

                                        <div style="width:24%; float:left; padding:2px 2px 2px 8px;">
                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Months</div>
                                                <div id="input" style="width:50%; float:left;">'.$thresMon.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;"> 
                                                <div style="width:25%; float:left;padding:5px;">Days</div>
                                                <div id="input" style="width:50%; float:left;">'.$thresDays.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Hours</div>
                                                <div id="input" style="width:50%; float:left;">'.$thresHrs.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left;">'.$thresAfl.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>
                                        </div>

                                        <div style="width:24%; float:left; padding:2px 2px 2px 8px;">
                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Months</div>
                                                <div id="input" style="width:50%; float:left;">'.$intrlMon.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;"> 
                                                <div style="width:25%; float:left;padding:5px;">Days</div>
                                                <div id="input" style="width:50%; float:left;">'.$intrlDays.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Hours</div>
                                                <div id="input" style="width:50%; float:left;">'.$intrlHrs.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left;">'.$intrlAfl.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>
                                        </div>

                                        <div style="width:24%; float:left; padding:2px 2px 2px 8px;">
                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Months</div>
                                                <div id="input" style="width:50%; float:left;">'.$adjsMon.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;"> 
                                                <div style="width:25%; float:left;padding:5px;">Days</div>
                                                <div id="input" style="width:50%; float:left;">'.$adjsDays.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Hours</div>
                                                <div id="input" style="width:50%; float:left;">'.$adjsHrs.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:10px;">
                                                <div style="width:25%; float:left;padding:5px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left;">'.$adjsAfl.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <div>
                                    <div class="addPageHeading" style="background:#99a1a6; font-weight:bold; padding:2px; margin:5px; height: 24px;">
                                        <div style="width:24%; float:left; background: #99a1a6; font-weight:bold; padding:2px 2px 2px 2px; margin-left:5px; height:24px;">Part Information</div>
                                        <div style="width:48%; float:left; background: #99a1a6; font-weight:bold; padding:2px 2px 2px 2px; margin-left:5px; height:24px;">Times Since (at Installed)</div>
                                        <div style="width:24%; float:left; background: #99a1a6; font-weight:bold; padding:2px 2px 2px 2px; margin-left:5px; height:24px;">Additional Information</div>
                                    </div>
                                    <div style="width:100%; padding-top:10px;">
                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">Part Number</div>
                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">'.$partNum.'</div>
                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">&nbsp;</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">New</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">Overhaul</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">Repair</div>

                                        <div style="width:8%; float:left; padding:2px 2px 2px 8px;">Work Card</div>

                                        <div style="width:12%; float:right; padding:2px 2px 2px 8px;">
                                            <div id="input">'.$workCard.'</div>
                                        </div>
                                    </div>

                                    <div style="width:100%; padding-top:10px;">
                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">Serial Number</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">'.$serialNum.'</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">Months</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">'.$newMonths.'</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">'.$overhaulMonths.'</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">'.$repairMonths.'</div>

                                        <div style="width:8%; float:left; padding:2px 2px 2px 8px;">Position</div>

                                        <div style="width:12%; float:right; padding:2px 2px 2px 8px;">
                                            <div id="input">'.$position.'</div>
                                        </div>
                                    </div>

                                    <div style="width:100%; padding-top:10px;">
                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">
                                            Hours
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">
                                            '.$newHours.'
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">
                                           '.$overhaulHours.'
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">
                                            '.$repairHours.'
                                        </div>

                                        <div style="width:8%; float:left; padding:2px 2px 2px 8px;">
                                            Version
                                        </div>

                                        <div style="width:12%; float:right; padding:2px 2px 2px 8px;">
                                            <div id="input">'.$version.'</div>
                                        </div>
                                    </div>

                                    <div style="width:100%; padding-top:10px;">
                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">
                                            Landings
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">
                                            '.$newlandings.'
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">
                                           '.$overhaulLandings.'
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 8px;">
                                            '.$repairLandings.'
                                        </div>

                                        <div style="width:8%; float:left; padding:2px 2px 2px 8px;">
                                            Last Revised By
                                        </div>

                                        <div style="width:12%; float:right; padding:2px 2px 2px 8px;">
                                            <div id="input">'.$lastRevisedBy.'</div>
                                        </div>
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <div>
                                    <div style="background:#99a1a6; font-weight:bold; padding:2px; margin:5px;">Admin Notes</div>
                                    <div style="width:100%; padding:10px;">
                                        <div style="width:10%; float:left;">Notes</div>
                                        <div id="textarea" style="width:85%;float:right;">'.$adminNotes.'</div>
                                    </div>
                                </div>

                                <div class="ln_solid"></div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
        
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
                            top: 1cm;
                            left: 0cm;
                            right: 0cm;
                            bottom: 0cm;
                            height: 1cm;
                        }

                        footer {
                            position: fixed; 
                            bottom: 0cm;
                            left: 0cm; 
                            right: 0cm;
                            height: 1.5cm;
                        }

                        .page_break { 
                            page-break-before: always; 
                        }

                        .table-bordered thead tr td {
                            border: 1px solid #c0c0c0;
                        }

                        .table-bordered tbody tr td {
                            border: 1px solid #c0c0c0;
                        }

                        #input {
                            border: 1px solid #000000;
                            padding: 5px;
                            width: 350px;
                        }

                        #textarea {
                            border: 1px solid #000000;
                            padding: 15px;
                            min-width: 500px;
                        }


                    </style>
                </head>
                <body>
                    <header>
                        <!--table id="header" style="width:98%; margin:0 auto; padding:0; text-align:center;" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td style="font-size:20px; font-weight:bold; padding:8px 0 8px 0; color:#fff; background-color:#2e3238;">Part Details</td>
                                </tr>
                            </tbody>
                        </table-->
                    </header>
                    <footer>
                        <table style="width:100%; margin:0 auto; padding:0; font-size:11px;" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="font-size:11px; padding:12px; text-align:right; float:right;">
                                    <script type="text/php">
                                        if ( isset($pdf) ) {
                                            $font = $fontMetrics->getFont("Arial, Helvetica, sans-serif", "normal");
                                            $pdf->page_text(745, 582, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 7, array(0,0,0));
                                        }
                                    </script>
                                    </td>
                                </tr>
                        </table>
                    </footer>
                    <main>'.$airTHtml.'</main>        
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
        $fileName = "part-".$airCode."-".$airComp."-".$params['partid'].".pdf";
        $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
        if($airTHtml) {
            $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>'');
            echo json_encode($result);die;
        }
    }

    public function printPartDetailPdf()
    {
        $params = $this->request->getData();
        $compPartDetail = $this->airCompPartObj->get($params['partid'], [
                            'contain' => [
                                'Groups'=>[
                                    'fields'=>[
                                        'Groups.id',
                                        'Groups.airframe_component_part_id',
                                        'Groups.group_name'
                                    ]
                                ],
                                'AirframeComponentLastCw'=>[
                                    'strategy' => 'select',
                                    'queryBuilder' => function ($q) {
                                        return $q->order(['AirframeComponentLastCw.id'=>'DESC'])->limit(1);
                                    }
                                ], 
                                'AirframeComponents'=>[
                                    'AirframeComponentTimes'=>[
                                        'strategy' => 'select',
                                        'queryBuilder' => function ($q) {
                                            return $q->order(['AirframeComponentTimes.id'=>'DESC'])->limit(1);
                                        }
                                    ]
                                ],
                                'PartInstalledTimes'=>[
                                    'sort'=>['PartInstalledTimes.id'=>'DESC']
                                ]
                            ]
                        ]);
        //pr($compPartDetail);die;

        $airTHtml = '';
        if(!empty($compPartDetail)) {
            $airCode = $this->Plane->getPlaneName($params['planeid']);
            $aircraft = $this->Plane->getPlanes(array($params['planeid']));
            $airComp = $this->AirframeComponent->getCompDetail($compPartDetail->airframe_component_id);
            
            $itemTypes = $this->AirframeComponentPart->getItemTypes();
            $itemType = !empty($compPartDetail['item_type']) ? $itemTypes[$compPartDetail['item_type']] : 'Select';
            
            $dispTitle = $this->Disposition->dispTitle($compPartDetail->disposition);
            $dispTitle = !empty($dispTitle) ? $dispTitle : 'Select';
            
            $ataCode = $this->AtaCode->ataTitle($compPartDetail->ata_code);
            $ataCode = !empty($ataCode) ? $ataCode : 'Select';

            $mfgCode = !empty($compPartDetail['mfg_code']) ? $compPartDetail['mfg_code'] : '&nbsp;';
            $adSbNumber = !empty($compPartDetail['ad_sb_number']) ? $compPartDetail['ad_sb_number'] : '&nbsp;';
            $adsbStatus = $this->AdsbStatus->adsbTitle($compPartDetail->ad_sb_status);
            $adsbStatus = !empty($adsbStatus) ? $adsbStatus : 'Select';
            $positionSel = $this->Position->positionTitle($compPartDetail->position_id);
            $positionSel = !empty($positionSel) ? $positionSel : 'Select';
            
            $reference = !empty($compPartDetail['reference']) ? $compPartDetail['reference'] : '&nbsp;';
            $reqTypes = $this->AirframeComponentPart->getRequirementTypes();
            $reqType = !empty($compPartDetail['requirement_type']) ? $reqTypes[$compPartDetail['requirement_type']] : 'Select';
            $amendment = !empty($compPartDetail['amendment']) ? $compPartDetail['amendment'] : '&nbsp;';
            $authority = !empty($compPartDetail['authority']) ? $compPartDetail['authority'] : 'Select';
            
            $itemName = !empty($compPartDetail['description']) ? $compPartDetail['description'] : '&nbsp;';
            $notes = !empty($compPartDetail['notes']) ? $compPartDetail['notes'] : '&nbsp;';
            $workDesc = !empty($compPartDetail['work_description']) ? $compPartDetail['work_description'] : '&nbsp;';
            $avgManHrs = !empty($compPartDetail['avg_man_hrs']) ? $compPartDetail['avg_man_hrs'] : '&nbsp;';
            $tags = !empty($compPartDetail['tags']) ? $compPartDetail['tags'] : '&nbsp;';

            $isResThres = !empty($compPartDetail['airframe_component_last_cw'][0]['is_recThres']) ? $compPartDetail['airframe_component_last_cw'][0]['is_recThres'] : '';

            $recurring = (!empty($isResThres) && $isResThres == 'recurring') ? "checked" : "";
            $threshold = (!empty($isResThres) && $isResThres == 'threshold') ? "checked" : "";

            //Change alert box value
            if(!empty($isResThres) && $isResThres == 'recurring') {
                $alertDays = ($compPartDetail['airframe_component_last_cw'][0]['recurring_mos'] > 24) ? 60 : 30;
                $alertHrs = ($compPartDetail['airframe_component_last_cw'][0]['recurring_hrs'] > 999) ? 200 : 50;
                $alertAfl = ($compPartDetail['airframe_component_last_cw'][0]['recurring_afl'] > 999) ? 200 : 25;
            } elseif(!empty($isResThres) && $isResThres == 'threshold') {
                $alertDays = ($compPartDetail['airframe_component_last_cw'][0]['threshold_mos'] > 24) ? 60 : 30;
                $alertHrs = ($compPartDetail['airframe_component_last_cw'][0]['threshold_hrs'] > 999) ? 200 : 50;
                $alertAfl = ($compPartDetail['airframe_component_last_cw'][0]['threshold_afl'] > 999) ? 200 : 25;
            }

            //Get data correction and nextdue calculation
            $mos = $hrs = $afl = $msc = '';
            if(!empty($compPartDetail['airframe_component_last_cw'][0])) {
                $getRes = $this->AirframeComponentPart->postDataProcess($compPartDetail['airframe_component_last_cw'][0]);
                $mos = $getRes['mos'];
                $hrs = $getRes['hrs'];
                $afl = $getRes['afl'];
                $msc = $getRes['msc'];
            }

            //Start Remaining    
            $remMonths = '';
            $remDays = '';
            if(!empty($mos)) {
                $mos = $this->AirframeComponentPart->changeFormat($mos);
                $date1 = new \DateTime($mos);
                //Do not change date format
                $date2 = new \DateTime(date('d-m-Y'));
                $interval = date_diff($date1, $date2);
                $year = $interval->format('%y');
                $remMonths = $interval->format('%m') + $year * 12;
                $remDays = $interval->format('%d');
                
                if($date1 < $date2) {
                    $remMonths = !empty($remMonths) ? -$remMonths : 0;
                    $remDays = !empty($remDays) ? -$remDays : 0;
                }
            }

            //Component Times
            $compHours  = !empty($compPartDetail['airframe_component']['airframe_component_times'][0]['hours']) ? $compPartDetail['airframe_component']['airframe_component_times'][0]['hours'] : 0;
            $compCycles = !empty($compPartDetail['airframe_component']['airframe_component_times'][0]['cycles']) ? $compPartDetail['airframe_component']['airframe_component_times'][0]['cycles'] : 0;

            $remMonths = !empty($remMonths) ? $remMonths : '&nbsp;';
            $remDays = !empty($remDays) ? $remDays : '&nbsp;';
            $remHours  = !empty($hrs) ? $hrs - $compHours : '&nbsp;';
            $remCycles = !empty($afl) ? $afl - $compCycles : '&nbsp;';
            //End Remaining

            $override = !empty($compPartDetail['airframe_component_last_cw'][0]['override']) ? 'checked' : '';
            $eom = !empty($compPartDetail['airframe_component_last_cw'][0]['eom']) ? 'checked' : '';
            
            $lastCwDate = !empty($compPartDetail['airframe_component_last_cw'][0]['last_cw_date']) ? date('m-d-Y', strtotime($compPartDetail['airframe_component_last_cw'][0]['last_cw_date'])) : '&nbsp;';
            $lastCwHour = !empty($compPartDetail['airframe_component_last_cw'][0]['last_cw_hrs']) ? $compPartDetail['airframe_component_last_cw'][0]['last_cw_hrs'] : '&nbsp;';
            $lastCwCycle = !empty($compPartDetail['airframe_component_last_cw'][0]['last_cw_afl']) ? $compPartDetail['airframe_component_last_cw'][0]['last_cw_afl'] : '&nbsp;';

            $style = '';
            if ((!empty($mos) && strtotime($mos) <= strtotime(date('m-d-Y'))) || (empty($mos) && !empty($remHours) && $remHours < 0)) {
                $style = 'pastDue';
            }

            //Change next due date format
            $mos = !empty($mos) ? $this->AirframeComponentPart->dateFormat($mos) : '&nbsp;';
            $hrs = !empty($hrs) ? $hrs : '&nbsp;';
            $afl = !empty($afl) ? $afl : '&nbsp;';

            //Start Tolerance
            $tolrMon = !empty($compPartDetail['airframe_component_last_cw'][0]['tolerance_mos']) ? $compPartDetail['airframe_component_last_cw'][0]['tolerance_mos'] : '&nbsp;';
            $tolrDays = !empty($compPartDetail['airframe_component_last_cw'][0]['tolerance_days']) ? $compPartDetail['airframe_component_last_cw'][0]['tolerance_days'] : '&nbsp;';
            $tolrHrs = !empty($compPartDetail['airframe_component_last_cw'][0]['tolerance_hrs']) ? $compPartDetail['airframe_component_last_cw'][0]['tolerance_hrs'] : '&nbsp;';
            $tolrAfl = !empty($compPartDetail['airframe_component_last_cw'][0]['tolerance_afl']) ? $compPartDetail['airframe_component_last_cw'][0]['tolerance_afl'] : '&nbsp;';
            //End Tolerance

            //Start Recurring
            $recrMon = !empty($compPartDetail['airframe_component_last_cw'][0]['recurring_mos']) ? $compPartDetail['airframe_component_last_cw'][0]['recurring_mos'] : '&nbsp;';
            $recrDays = !empty($compPartDetail['airframe_component_last_cw'][0]['recurring_days']) ? $compPartDetail['airframe_component_last_cw'][0]['recurring_days'] : '&nbsp;';
            $recrHrs = !empty($compPartDetail['airframe_component_last_cw'][0]['recurring_hrs']) ? $compPartDetail['airframe_component_last_cw'][0]['recurring_hrs'] : '&nbsp;';
            $recrAfl = !empty($compPartDetail['airframe_component_last_cw'][0]['recurring_afl']) ? $compPartDetail['airframe_component_last_cw'][0]['recurring_afl'] : '&nbsp;';
            //End Recurring

            //Start Threshold
            $thresMon = !empty($compPartDetail['airframe_component_last_cw'][0]['threshold_mos']) ? $compPartDetail['airframe_component_last_cw'][0]['threshold_mos'] : '&nbsp;';
            $thresDays = !empty($compPartDetail['airframe_component_last_cw'][0]['threshold_days']) ? $compPartDetail['airframe_component_last_cw'][0]['threshold_days'] : '&nbsp;';
            $thresHrs = !empty($compPartDetail['airframe_component_last_cw'][0]['threshold_hrs']) ? $compPartDetail['airframe_component_last_cw'][0]['threshold_hrs'] : '&nbsp;';
            $thresAfl = !empty($compPartDetail['airframe_component_last_cw'][0]['threshold_afl']) ? $compPartDetail['airframe_component_last_cw'][0]['threshold_afl'] : '&nbsp;';
            //End Threshold

            //Start Interval
            $intrlMon = !empty($compPartDetail['airframe_component_last_cw'][0]['required_frequency_mon']) ? $compPartDetail['airframe_component_last_cw'][0]['required_frequency_mon'] : '&nbsp;';
            $intrlDays = !empty($compPartDetail['airframe_component_last_cw'][0]['required_frequency_days']) ? $compPartDetail['airframe_component_last_cw'][0]['required_frequency_days'] : '&nbsp;';
            $intrlHrs = !empty($compPartDetail['airframe_component_last_cw'][0]['required_frequency_hrs']) ? $compPartDetail['airframe_component_last_cw'][0]['required_frequency_hrs'] : '&nbsp;';
            $intrlAfl = !empty($compPartDetail['airframe_component_last_cw'][0]['required_frequency_afl']) ? $compPartDetail['airframe_component_last_cw'][0]['required_frequency_afl'] : '&nbsp;';
            //End Interval

            //Start Adjustment
            $adjsMon = !empty($compPartDetail['airframe_component_last_cw'][0]['adjustment_mos']) ? $compPartDetail['airframe_component_last_cw'][0]['adjustment_mos'] : '&nbsp;';
            $adjsDays = !empty($compPartDetail['airframe_component_last_cw'][0]['adjustment_days']) ? $compPartDetail['airframe_component_last_cw'][0]['adjustment_days'] : '&nbsp;';
            $adjsHrs = !empty($compPartDetail['airframe_component_last_cw'][0]['adjustment_hrs']) ? $compPartDetail['airframe_component_last_cw'][0]['adjustment_hrs'] : '&nbsp;';
            $adjsAfl = !empty($compPartDetail['airframe_component_last_cw'][0]['adjustment_afl']) ? $compPartDetail['airframe_component_last_cw'][0]['adjustment_afl'] : '&nbsp;';
            //End Adjustment

            //Part and Admin Information
            $partNum = !empty($compPartDetail["part_number"]) ? $compPartDetail["part_number"] : '';
            $serialNum = !empty($compPartDetail["serial_number"]) ? $compPartDetail["serial_number"] : '';

            $newMonths = (!empty($compPartDetail["part_installed_times"][0]["new_months"]) || (isset($compPartDetail["part_installed_times"][0]["new_months"]) && $compPartDetail["part_installed_times"][0]["new_months"] == 0)) ? $compPartDetail["part_installed_times"][0]["new_months"] : "";

            $overhaulMonths = (!empty($compPartDetail["part_installed_times"][0]["overhaul_months"]) || (isset($compPartDetail["part_installed_times"][0]["overhaul_months"]) && $compPartDetail["part_installed_times"][0]["overhaul_months"] == 0)) ? $compPartDetail["part_installed_times"][0]["overhaul_months"] : "";

            $repairMonths = (!empty($compPartDetail["part_installed_times"][0]["repair_months"]) || (isset($compPartDetail["part_installed_times"][0]["repair_months"]) && $compPartDetail["part_installed_times"][0]["repair_months"] == 0)) ? $compPartDetail["part_installed_times"][0]["repair_months"] : "";

            $newHours = (!empty($compPartDetail["part_installed_times"][0]["new_hours"]) || (isset($compPartDetail["part_installed_times"][0]["new_hours"]) && $compPartDetail["part_installed_times"][0]["new_hours"] == 0)) ? $compPartDetail["part_installed_times"][0]["new_hours"] : "";

            $overhaulHours = (!empty($compPartDetail["part_installed_times"][0]["overhaul_hours"]) || (isset($compPartDetail["part_installed_times"][0]["overhaul_hours"]) && $compPartDetail["part_installed_times"][0]["overhaul_hours"] == 0)) ? $compPartDetail["part_installed_times"][0]["overhaul_hours"] : "";

            $repairHours = (!empty($compPartDetail["part_installed_times"][0]["repair_hours"]) || (isset($compPartDetail["part_installed_times"][0]["repair_hours"]) && $compPartDetail["part_installed_times"][0]["repair_hours"] == 0)) ? $compPartDetail["part_installed_times"][0]["repair_hours"] : "";

            $newlandings = (!empty($compPartDetail["part_installed_times"][0]["new_landings"]) || (isset($compPartDetail["part_installed_times"][0]["new_landings"]) && $compPartDetail["part_installed_times"][0]["new_landings"] == 0)) ? $compPartDetail["part_installed_times"][0]["new_landings"] : "";
                                        
            $overhaulLandings = (!empty($compPartDetail["part_installed_times"][0]["overhaul_landings"]) || (isset($compPartDetail["part_installed_times"][0]["overhaul_landings"]) && $compPartDetail["part_installed_times"][0]["overhaul_landings"] == 0)) ? $compPartDetail["part_installed_times"][0]["overhaul_landings"] : "";
        
            $repairLandings = (!empty($compPartDetail["part_installed_times"][0]["repair_landings"]) || (isset($compPartDetail["part_installed_times"][0]["repair_landings"]) && $compPartDetail["part_installed_times"][0]["repair_landings"] == 0)) ? $compPartDetail["part_installed_times"][0]["repair_landings"] : "";

            $workCard = !empty($compPartDetail["work_card"]) ? trim($compPartDetail["work_card"]) : "&nbsp;";
            $position = !empty($compPartDetail["position"]) ? trim($compPartDetail["position"]) : "&nbsp;";
            $version = !empty($compPartDetail["airframe_component_last_cw"][0]["version"]) ? trim($compPartDetail["airframe_component_last_cw"][0]["version"]) : "&nbsp;";

            $lastRevisedBy = !empty($compPartDetail["airframe_component_last_cw"][0]["last_revised_by"]) ? trim($compPartDetail["airframe_component_last_cw"][0]["last_revised_by"]) : "&nbsp;";
            if(is_numeric($lastRevisedBy)){
                $revisedBy = $this->AirframeComponentPart->getUserName($lastRevisedBy);
            }else{
                $revisedBy = $lastRevisedBy;
            }
            if(!empty($revisedBy)) {
                $lastRevisedBy = $revisedBy;
            }

            $adminNotes = !empty($compPartDetail["admin_notes"]) ? $compPartDetail["admin_notes"] : '&nbsp;';
            $airTHtml .='<div style="background: #fff;" class="formBGCls">
                <div>
                    <div style="background: #99a1a6;font-weight:bold; font-size:10px; padding: 2px;margin: 3px;height: 4px; text-align:center;">Part Details('.$airCode.' - '.$airComp.' - '.$params['partid'].')</div>
                    <div style="background: #99a1a6;font-weight:bold; font-size:10px; padding: 2px;margin: 3px;height: 4px;">Basic Information</div>

                    <div style="width:100%;padding-top:5px;" class="row">
                        <div style="width:26%; float:left; padding-left:5px;">
                            <div style="width:24%; float:left;font-size: 10px;">Aircraft<span class="required">*</span></div>
                            <div style="width:73%;float:right;">
                                <select size="1" style="font-size: 10px;">';
                                    foreach ($aircraft as $key => $value) {
                                        $airTHtml .='<option value="'.$key.'">'.$value.'</option>';
                                    }
                                $airTHtml .='</select>
                            </div>
                        </div>

                        <div style="width:24%; float:left; padding-left:5px;">
                            <div style="width:35%; float:left; font-size: 10px;">Component<span class="required">*</span></div>
                            <div style="width:60%; float:right;">
                                <select size="1" style="font-size: 10px;">
                                    <option value="'.$compPartDetail['airframe_component_id'].'">'.$airComp.'</option>
                                </select>
                            </div>
                        </div>

                        <div style="width:24%; float:left; padding-left:5px;">
                            <div style="width:35%; float:left; font-size: 10px;">Item Type</div>
                            <div style="width:60%; float:right;">
                                <select size="1" style="font-size: 10px;">
                                    <option value="'.$compPartDetail['item_type'].'">'.$itemType.'</option>
                                </select>
                            </div>
                        </div>

                        <div style="width:22%; float:left; padding-left:5px;">
                            <div style="width:32%; float:left; font-size: 10px;">Disposition</div>
                            <div style="width:60%; float:right;">
                                <select size="1" style="font-size: 10px;">
                                    <option value="'.$compPartDetail['disposition'].'">'.$dispTitle.'</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="width:100%;padding-top:5px;" class="row">
                        <div style="width:26%; float:left; padding-left:5px;">
                            <div style="width:24%; float:left; font-size: 10px;">ATA</div>
                            <div style="width:73%; float:right;">
                                <select size="1" style="font-size: 10px;">
                                    <option value="'.$compPartDetail['ata_code'].'">'.$ataCode.'</option>
                                </select>
                            </div>
                        </div>

                        <div style="width:24%; float:left; padding-left:2px;">
                            <div style="width:38%; float:left;padding:3px; font-size: 10px;">Mfg Code</div>
                            <div id="input" style="width:53%; float:left; font-size: 10px;">'.$mfgCode.'</div>
                        </div>

                        <div style="width:24%; float:left; padding-left:2px;">
                            <div style="width:40%; float:left;padding:3px; font-size: 10px;">AD/SB Number</div>
                            <div id="input" style="width:48%; float:left; font-size: 10px;">'.$adSbNumber.'</div>
                        </div>

                        <div style="width:23%; float:left; padding-left:5px;">
                            <div style="width:35%; float:left; font-size: 10px;">AD/SB Class</div>
                            <div style="width:60%; float:right;">
                                <select size="1" style="font-size: 10px;">
                                    <option value="'.$compPartDetail['ad_sb_status'].'">'.$adsbStatus.'</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="width:100%;padding-top:5px;" class="row">
                        <div style="width:26%; float:left; padding-left:2px;">
                            <div style="width:26%; float:left;padding:3px; font-size: 10px;">Reference</div>
                            <div id="input" style="width:55%; float:left; font-size: 10px;">'.$reference.'</div>
                        </div>

                        <div style="width:25%; float:left; padding-left:5px;">
                            <div style="width:35%; float:left; font-size: 10px;">Requirement Type</div>
                            <div style="width:60%; float:right;">
                                <select size="1" style="width:30px;float: right; font-size:10px;">
                                    <option value="'.$compPartDetail['requirement_type'].'">'.$reqType.'</option>
                                </select>
                            </div>
                        </div>

                        <div style="width:23%; float:left; padding-left:2px;">
                            <div style="width:42%; float:left;padding:3px 0 0 0; font-size: 10px;">Amendment</div>
                            <div id="input" style="width:48%; float:left; font-size: 10px;">'.$amendment.'</div>
                        </div>

                        <div style="width:23%; float:left; padding-left:6px;">
                            <div style="width:35%; float:left; font-size: 10px;">Authority</div>
                            <div style="width:60%; float:right;">
                                <select size="1" style="font-size: 10px;">
                                    <option value="'.$compPartDetail['authority'].'" >'.$authority.'</option>
                                </select>
                            </div>
                        </div>                            
                    </div>

                    <div style="width:100%;padding-top:5px;" class="row">
                        <div style="width:76%; float:left; padding-left:2px;">
                            <div style="width:14%; float:left;padding:3px; font-size: 10px;">Item Name</div>
                            <div id="textarea" style="width:81%; float:left; font-size: 10px;">'.$itemName.'</div>
                        </div>

                        <div style="width:21%; float:right; padding-left:5px;">
                            <div style="width:35%; float:left;padding:3px; font-size: 10px;">Position</div>
                            <div style="width:50%; float:right; font-size: 10px;">
                                <select size="1" style="font-size: 10px;">
                                    <option value="'.$compPartDetail['position_id'].'" >'.$positionSel.'</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div style="width:100%;padding:5px 0 0 2px;float:left;" class="row">
                        <div style="width:10%; float:left;padding:5px; font-size: 10px;">Notes</div>
                        <div id="textarea" style="width:62%; float:left; font-size: 10px;">'.$notes.'</div>
                        <div style="width:20%;">&nbsp;</div>
                    </div>

                    <div style="width:100%;padding-top:5px;" class="row">
                        <div style="width:76%; float:left; padding-left:2px;">
                            <div style="width:14%; float:left;padding:3px; font-size: 10px;">Work Description</div>
                            <div id="textarea" style="width:81%; float:left; font-size: 10px;">'.$workDesc.'</div>
                        </div>

                        <div style="width:21%; float:right; padding-left:5px;">
                            <div style="width:35%; float:left;padding:3px; font-size: 10px;">Man Hours</div>
                            <div id="input" style="width:50%; float:right; font-size: 10px;">'.$avgManHrs.'</div>
                        </div>
                    </div>

                    <div style="width:100%;padding:10px 0 0 2px;float:left;" class="row">
                        <div style="width:10%; float:left;padding:3px 3px; font-size: 10px;">Tags</div>
                        <div id="textarea" style="width:62%; float:left; font-size: 10px; margin-left:4px;">'.$tags.'</div>
                        <div style="width:20%;">&nbsp;</div>
                    </div>
                </div>
                
                <div style="clear: both;"></div>

                <div id="aircraftTabs" style="padding: 5px 0 15px 0;">
                    <div class="container">
                        <div style="background: #99a1a6;font-weight: bold; font-size:10px; padding: 2px;margin: 5px;height: 4px;">Item Information</div>
                        <div class="tab-content">
                            <div class="tab-pane fade in active">
                                <div>
                                    <div style="width:100%; text-align:left;">
                                        <div style="width:18%; float:left; background: #99a1a6; font-weight:bold; font-size:10px; padding:2px 3px; margin-left:5px; height:4px;">Last Complied With</div>
                                        <div style="width:18%; float:left; background: #518aed; font-weight:bold; font-size:10px; padding:2px 3px; height:4px;">Next Due</div>
                                        <div style="width:18%; float:left; background: #518aed; font-weight:bold; font-size:10px; padding:2px 3px; height:4px;">Remaining</div>
                                        <div style="width:20%; float:left; background: #99a1a6; font-weight:bold; font-size:10px; padding:2px 3px; height:4px;">Tolerance</div>
                                        <div style="width:20%; float:left; background: #99a1a6; font-weight:bold; font-size:10px; padding:2px 5px; height:4px;">Alert</div>
                                    </div>
                                    <div class="itemInfoCls" style="width:100%;">
                                        <div class="itemCls" style="width:19%; float:left; padding:2px 2px 2px 2px;">
                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="float:left;padding:5px;">
                                                    <span style="cursor: pointer; color: #3c8dbc; font-size: 10px;">Use Current Times</span>
                                                </div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;"> 
                                                <div style="width:22%; float:left;padding:3px; font-size: 10px;">Date</div>
    `1                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$lastCwDate.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:22%; float:left;padding:3px; font-size: 10px;">Hours</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$lastCwHour.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:22%; float:left;padding:3px; font-size: 10px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$lastCwCycle.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>                               
                                        </div>

                                        <div class="itemCls" style="width:19%; float:left; padding:2px 2px 2px 2px;">
                                            <div class="form-group" style="padding-top:12px;">
                                                <div style="width:60%;float:left; font-size: 8px;">
                                                    <input type="checkbox" checked='.$override.'>
                                                    <span style="font-size: 8px;">Override Next Due</span>
                                                </div>

                                                <div style="width:35%;float:right; font-size: 8px;">
                                                    <input type="checkbox" checked='.$eom.'>
                                                    <span style="font-size: 8px;">EoM Adj</span>
                                                </div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;"> 
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Date</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$mos.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Hours</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$hrs.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$afl.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>                            
                                        </div>

                                        <div class="itemCls" style="width:19%; float:left; padding:2px 2px 2px 2px;">
                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Months</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$remMonths.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;"> 
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Days</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$remDays.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Hours</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.(!empty($remHours) && is_numeric($remHours) ? round($remHours,1) : 0).'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$remCycles.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>                           
                                        </div>

                                        <div class="itemCls" style="width:19%; float:left; padding:2px 2px 2px 2px;">
                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Months</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$tolrMon.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;"> 
                                                <div style="width:25%; float:left; padding:3px; font-size: 10px;">Days</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$tolrDays.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Hours</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$tolrHrs.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$tolrAfl.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>
                                        </div>

                                        <div class="itemCls" style="width:18%; float:left; padding:2px 2px 2px 2px;">
                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:26%; float:left;padding:3px; font-size: 10px;">&nbsp;</div>
                                                <div style="width:50%; float:left; font-size: 10px;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;"> 
                                                <div style="width:26%; float:left;padding:3px; font-size: 10px;">Days</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$alertDays.'</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:26%; float:left;padding:3px; font-size: 10px;">Hours</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$alertHrs.'</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:26%; float:left;padding:3px; font-size: 10px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$alertAfl.'</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="addPageHeading" style="background:#99a1a6; font-weight:bold; padding:2px; margin:5px; height:4px;">
                                        <div style="top:-5px;width:22%; float:left; background: #99a1a6; font-weight:bold; font-size:10px; padding:2px 2px 2px 2px; margin-left:3px; height:4px;">
                                            Recurring <input type="radio" name="is_recThres" checked='.$recurring.'>
                                        </div>
                                        <div style="top:-5px;width:21%; float:left; background: #99a1a6; font-weight:bold; font-size:10px; padding:2px 2px 2px 2px; margin-left:33x; height:4px;">
                                            Threshold <input type="radio" name="is_recThres" checked='.$threshold.'>
                                        </div>
                                        <div style="width:24%; float:left; background: #99a1a6; font-weight:bold; font-size:10px; padding:2px 2px 2px 2px; margin-left:3px; height:4px;">Interval</div>
                                        <div style="width:24%; float:left; background: #99a1a6; font-weight:bold; font-size:10px; padding:2px 2px 2px 2px; margin-left:3px; height:4px;">Adjustment</div>
                                    </div>
                                    
                                    <div style="clear: both;"></div>

                                    <div class="row" style="width:100%;padding-top:5px;">
                                        <div style="width:24%; float:left; padding:2px 2px 2px 2px;">
                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Months</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$recrMon.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;"> 
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Days</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$recrDays.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Hours</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$recrHrs.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$recrAfl.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>
                                        </div>

                                        <div style="width:24%; float:left; padding:2px 2px 2px 2px;">
                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Months</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$thresMon.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;"> 
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Days</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$thresDays.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Hours</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$thresHrs.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$thresAfl.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>
                                        </div>

                                        <div style="width:24%; float:left; padding:2px 2px 2px 2px;">
                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Months</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$intrlMon.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;"> 
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Days</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$intrlDays.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Hours</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$intrlHrs.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$intrlAfl.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>
                                        </div>

                                        <div style="width:24%; float:left; padding:2px 2px 2px 2px;">
                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Months</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$adjsMon.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;"> 
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Days</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$adjsDays.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Hours</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$adjsHrs.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>

                                            <div class="form-group" style="padding-top:5px;">
                                                <div style="width:25%; float:left;padding:3px; font-size: 10px;">Cycles</div>
                                                <div id="input" style="width:50%; float:left; font-size: 10px;">'.$adjsAfl.'</div>
                                                <div style="width:10%;">&nbsp;</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <div>
                                    <div class="addPageHeading" style="background:#99a1a6; font-weight:bold; padding:2px; margin:5px; height:4px;">
                                        <div style="width:24%; float:left; background: #99a1a6; font-weight:bold; font-size:10px; padding:2px 2px 2px 2px; margin-left:5px; height:4px;">Part Information</div>
                                        <div style="width:48%; float:left; background: #99a1a6; font-weight:bold; font-size:10px; padding:2px 2px 2px 2px; margin-left:5px; height:4px;">Times Since (at Installed)</div>
                                        <div style="width:24%; float:left; background: #99a1a6; font-weight:bold; font-size:10px; padding:2px 2px 2px 2px; margin-left:5px; height:4px;">Additional Information</div>
                                    </div>
                                    <div style="width:100%; padding-top:5px;">
                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px;font-size:10px;">Part Number</div>
                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px;font-size:10px;">'.$partNum.'</div>
                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px;font-size:10px;">&nbsp;</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px;font-size:10px;">New</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px;font-size:10px;">Overhaul</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px;font-size:10px;">Repair</div>

                                        <div style="width:8%; float:left; padding:2px 2px 2px 2px;font-size:10px;">Work Card</div>

                                        <div style="width:12%; float:right; padding:2px 2px 2px 2px;font-size:10px;">
                                            <div id="input">'.$workCard.'</div>
                                        </div>
                                    </div>

                                    <div style="width:100%; padding-top:5px;">
                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">Serial Number</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">'.$serialNum.'</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">Months</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">'.$newMonths.'</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">'.$overhaulMonths.'</div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">'.$repairMonths.'</div>

                                        <div style="width:8%; float:left; padding:2px 2px 2px 2px; font-size:10px;">Position</div>

                                        <div style="width:12%; float:right; padding:2px 2px 2px 2px; font-size:10px;">
                                            <div id="input">'.$position.'</div>
                                        </div>
                                    </div>

                                    <div style="width:100%; padding-top:5px;">
                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                            Hours
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                            '.$newHours.'
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                           '.$overhaulHours.'
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                            '.$repairHours.'
                                        </div>

                                        <div style="width:8%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                            Version
                                        </div>

                                        <div style="width:12%; float:right; padding:2px 2px 2px 2px; font-size:10px;">
                                            <div id="input">'.$version.'</div>
                                        </div>
                                    </div>

                                    <div style="width:100%; padding-top:5px;">
                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                            Landings
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                            '.$newlandings.'
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                           '.$overhaulLandings.'
                                        </div>

                                        <div style="width:12%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                            '.$repairLandings.'
                                        </div>

                                        <div style="width:8%; float:left; padding:2px 2px 2px 2px; font-size:10px;">
                                            Last Revised By
                                        </div>

                                        <div style="width:12%; float:right; padding:2px 2px 2px 2px; font-size:10px;">
                                            <div id="input">'.$lastRevisedBy.'</div>
                                        </div>
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <div>
                                    <div style="background:#99a1a6; font-weight:bold; font-size:10px; padding:2px; margin:5px;">Admin Notes</div>
                                    <div style="width:100%; padding:5px;">
                                        <div style="width:10%; float:left; font-size:10px;">Notes</div>
                                        <div id="textarea" style="width:85%;float:right; font-size:10px;">'.$adminNotes.'</div>
                                    </div>
                                </div>

                                <div class="ln_solid"></div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
        
        $html ='<!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <style>
                        @page {
                            margin: 0cm 0cm;
                        }

                        body {
                            margin-top: 1cm;
                            margin-left: .5cm;
                            margin-right: .5cm;
                            margin-bottom: 1cm;
                        }

                        .page_break { 
                            page-break-before: always; 
                        }

                        .table-bordered thead tr td {
                            border: 1px solid #c0c0c0;
                        }

                        .table-bordered tbody tr td {
                            border: 1px solid #c0c0c0;
                        }

                        #input {
                            border: 1px solid #000000;
                            padding: 0px;
                            width: 350px;
                        }

                        #textarea {
                            border: 1px solid #000000;
                            padding: 3px;
                            min-width: 500px;
                        }


                    </style>
                </head>
                <body>
                    <main>'.$airTHtml.'</main>        
                </body>
                </html>';
        //pr($html);die;

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->AddPage('P', // L - landscape, P - portrait 
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
        $fileName = "part-".$airCode."-".$airComp."-".$params['partid'].".pdf";
        $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
        if($airTHtml) {
            $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>'');
            echo json_encode($result);die;
        }
    }

    /*********************TO ADD existing parent/child records to parentChildRelations *************************/
    public function updatepc($planeId=null)
    {
        $reports = $this->airCompPartObj->find()
                    ->where(['AirframeComponentParts.plane_id'=>$planeId, 'AirframeComponentParts.parent_id !='=>0])
                    ->select(['AirframeComponentParts.id', 'AirframeComponentParts.plane_id', 'AirframeComponentParts.parent_id'])
                    ->enableHydration(false)->toArray();
        if(!empty($reports)) {
            $relData = array();
            foreach ($reports as $key => $value) {
                $relData[$key]['plane_id'] = $value['plane_id'];
                $relData[$key]['group_id'] = $this->getGP($value['parent_id']);
                $relData[$key]['parent_id'] = $value['parent_id'];
                $relData[$key]['airframe_component_part_id'] = $value['id'];
            }

            //pr($relData);die;
            $parentChildRel = $this->parentChildRelObj->newEntities($relData);
            //pr($parentChildRel);die;
            if($this->parentChildRelObj->saveMany($parentChildRel)) {
                echo "Records add successfully.";die;
            } else {
                echo "Something wrong.";die;
            }
        } else {
            echo "No data exist.";die;
        }
    }

    public function getGP($partId)
    {
        $gp = $this->groupObj->find()
                    ->where(['Groups.airframe_component_part_id'=>$partId])
                    ->select(['Groups.id'])
                    ->enableHydration(false)->first();
        return $gp['id'];
    }
    /*********************TO ADD existing parent/child records to parentChildRelations *************************/

}
