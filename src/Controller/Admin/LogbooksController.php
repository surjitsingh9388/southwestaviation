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
 * Logbooks Controller
 */
class LogbooksController extends AppController
{
    private $planeObj;
    private $airCompObj;
    private $airCompPartObj;
    private $airCompLastCwObj;
    private $extDetailObj;

    public $paginate = array(
        'limit' => PAGINATION_LIMIT
    );
    
    public function initialize() 
    {
        parent::initialize();
        $this->loadComponent('Plane');
        $this->loadComponent('AirframeComponent');
        $this->loadComponent('AirframeComponentPart');
        $this->loadComponent('AtaCode');
        $this->loadComponent('Disposition');

        $this->planeObj   = TableRegistry::get('Planes');
        $this->airCompObj = TableRegistry::get('AirframeComponents');
        $this->subCompObj = TableRegistry::get('SubComponents');
    }
    
    /**
     * Get all aircrafts
     */
    public function index()
    {
        $actionItems='';
        $whereCond = '';
        //Display assigned aircraft only
        $airIds = $this->Plane->getSelectedAircraft();
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Aircraft', $actionStatus))
            {
                $actionItems = $actionStatus['Aircraft'];
            }

            if(!empty($airIds['airIdsArr']) && is_array($airIds['airIdsArr'])) {
                $whereCond = ['Planes.id IN'=>$airIds['airIdsArr']];
            } else {
                $whereCond = ['Planes.id IN'=>''];
            }
        }

        $aircrafts = $this->planeObj->find()
                    ->where($whereCond)
                    ->select(['id', 'plane_code', 'plane_serial_number'])
                    ->enableHydration(false)->toArray();

        $this->set(compact('actionItems', 'aircrafts'));
    }

    //Get all child records
    public function getChilds()
    {
        $params = $this->request->data;
        if(!empty($params['pid']) && $params['level'] == 0) {
            $records = $this->airCompObj->find()
                    ->where(['plane_id'=>$params['pid']])
                    ->enableHydration(false)->toArray();
            
            if(!empty($records)) {
                $result = array('status'=>'success', 'data'=>$records);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
        } elseif(!empty($params['pid']) && $params['level'] == 1) {
            $records = $this->subCompObj->find()
                    ->where(['airframe_component_id'=>$params['pid'], 'parent_id'=>0])
                    ->enableHydration(false)->toArray();
            
            if(!empty($records)) {
                $result = array('status'=>'success', 'data'=>$records);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
        } elseif(!empty($params['pid']) && $params['level'] == 2) {
            $records = $this->subCompObj->find()
                    ->where(['parent_id'=>$params['pid']])
                    ->enableHydration(false)->toArray();
            
            if(!empty($records)) {
                $result = array('status'=>'success', 'data'=>$records);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
        }
    }

    //Add items popup
    public function addItemPopup()
    {
        $params = $this->request->data;
        $tHtml = '';
        if(!empty($params['pid'])) {

            $planeId    = 0;
            $airCompId  = 0;
            $subCompId  = 0;
            $subComp2Id = 0;
            if(!empty($params['pid']) && $params['level'] == 0) {
                $planeId = $params['pid'];
                $logHistory = $this->Logbooks->find()
                        ->where(['plane_id'=>$planeId, 'airframe_component_id'=>$airCompId, 'sub_component_id'=>$subCompId, 'sub_component2_id'=>$subComp2Id])
                        ->enableHydration(false)->toArray();

            } elseif(!empty($params['pid']) && $params['level'] == 1) {
                $records = $this->airCompObj->find()
                        ->where(['id'=>$params['pid']])
                        ->enableHydration(false)->first();
                
                $planeId = $records['plane_id'];
                $airCompId = $records['id'];

                $logHistory = $this->Logbooks->find()
                        ->where(['plane_id'=>$planeId, 'airframe_component_id'=>$airCompId, 'sub_component_id'=>$subCompId, 'sub_component2_id'=>$subComp2Id])
                        ->enableHydration(false)->toArray();
                
            } elseif(!empty($params['pid']) && $params['level'] == 2) {
                $records = $this->subCompObj->find()
                        ->where(['id'=>$params['pid'], 'parent_id'=>0])
                        ->enableHydration(false)->first();
                
                $planeId = $records['plane_id'];
                $airCompId = $records['airframe_component_id'];
                $subCompId = $records['id'];

                $logHistory = $this->Logbooks->find()
                        ->where(['plane_id'=>$planeId, 'airframe_component_id'=>$airCompId, 'sub_component_id'=>$subCompId, 'sub_component2_id'=>$subComp2Id])
                        ->enableHydration(false)->toArray();
                
            } elseif(!empty($params['pid']) && $params['level'] == 3) {
                $records = $this->subCompObj->find()
                        ->where(['id'=>$params['pid']])
                        ->enableHydration(false)->first();
                
                $planeId = $records['plane_id'];
                $airCompId = $records['airframe_component_id'];
                $subCompId = $records['parent_id'];
                $subComp2Id = $records['id'];

                $logHistory = $this->Logbooks->find()
                        ->where(['plane_id'=>$planeId, 'airframe_component_id'=>$airCompId, 'sub_component_id'=>$subCompId, 'sub_component2_id'=>$subComp2Id])
                        ->enableHydration(false)->toArray();
            }
            
            $historyHtml = '<table class="table table-hover table-header-dark table-condensed" style="font-size:11px;">
                        <tr>
                            <th width="3%">#</th>
                            <th width="10%">Nomenclature</th>
                            <th width="8%">Part Number</th>
                            <th width="8%">Serial Number</th>
                            <th width="8%">Installed Date</th>
                            <th width="8%">Last Inspection</th>                              
                            <th width="7%">Time New</th>
                            <th width="7%">Time Overhaul</th>
                            <th width="7%">Cycle New</th>
                            <th width="8%">Cycle Overhaul</th>
                            <th width="10%">Interval</th>
                            <th width="10%">Next Due</th>
                            <th width="6%"></th>
                        </tr>
                    <tbody>';
                if(!empty($logHistory)) {
                    foreach ($logHistory as $key => $value) {
                        //Last inspection
                        $lastInspDate = !empty($value['last_inspection_date']) ? date('m/d/Y',strtotime($value['last_inspection_date']))."<br>" : '';
                        $lastInspHours = !empty($value['last_inspection_hours']) ? "Hours: ".$value['last_inspection_hours']."<br>" : '';
                        $lastInspCycles = !empty($value['last_inspection_cycles']) ? "Cycles: ".$value['last_inspection_cycles']."<br>" : '';

                        //Interval
                        $intvHours = !empty($value['interval_hours']) ? "Hours: ".$value['interval_hours']."<br>" : '';
                        $intvCycles = !empty($value['interval_cycles']) ? "Cycles: ".$value['interval_cycles']."<br>" : '';
                        $intvDays = !empty($value['interval_days']) ? "Cycles: ".$value['interval_days']."<br>" : '';

                        //Next due
                        $dueDate = !empty($value['due_date']) ? date('m/d/Y',strtotime($value['due_date']))."<br>" : '';
                        $dueHours = !empty($value['due_hours']) ? "Hours: ".$value['due_hours']."<br>" : '';
                        $dueCycles = !empty($value['due_cycles']) ? "Cycles: ".$value['due_cycles']."<br>" : '';

                        $historyHtml .= '<tr class="logItem_'.$value['id'].'">
                                    <td>'.$value['id'].'</td>
                                    <td>'.$value['nomenclature'].'</td>
                                    <td>'.$value['part_number'].'</td>
                                    <td>'.$value['serial_number'].'</td>
                                    <td>'.date('m/d/Y',strtotime($value['installed_date'])).'</td>
                                    <td>'.$lastInspDate.' '.$lastInspHours.' '.$lastInspCycles.'</td>
                                    <td>'.$value['time_since_new'].'</td>
                                    <td>'.$value['time_since_overhaul'].'</td>
                                    <td>'.$value['cycle_since_new'].'</td>
                                    <td>'.$value['cycle_since_overhaul'].'</td>
                                    <td>'.$intvHours.' '.$intvCycles.' '.$intvDays.'</td>
                                    <td>'.$dueDate.' '.$dueHours.' '.$dueCycles.'</td>
                                    <td><i class="fa fa-edit editItemCls" style="cursor:pointer;color:blue;font-size: 17px;" data-id='.$value['id'].' data-plane_id='.$value['plane_id'].' data-level='.$params['level'].'></i> &nbsp; <i class="fa fa-trash deleteItemCls" style="cursor:pointer;color:red;font-size: 17px;" data-id='.$value['id'].' data-plane_id='.$value['plane_id'].' data-level='.$params['level'].'></i></td>
                                </tr>';
                    }
                } else {
                    $historyHtml .= '<tr>
                            <td colspan="13" style="text-align:center;">No record found.</td>
                        </tr>';
                }
            $historyHtml .= '</tbody>
                    </table>';

            $tHtml .= '<div id="aircraftTabs">
                        <div class="container">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#itemInfo">Item</a></li>
                                <li><a data-toggle="tab" href="#historical">Historical</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="itemInfo" class="tab-pane fade in active">
                                    <form method="post" id="addItemFrm" class="form-horizontal">
                                        <input type="hidden" name="level" id="levelid" value="'.$params['level'].'">
                                        <input type="hidden" name="plane_id" id="plane_id" value="'.$planeId.'">
                                        <input type="hidden" name="airframe_component_id" id="airframe_component_id" value="'.$airCompId.'">
                                        <input type="hidden" name="sub_component_id" id="sub_component_id" value="'.$subCompId.'">
                                        <input type="hidden" name="sub_component2_id" id="sub_component2_id" value="'.$subComp2Id.'">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Nomenclature:</label>
                                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                                        <input type="text" name="nomenclature" class="form-control" maxlength="30">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-5 col-xs-12">Date Installed:</label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input type="text" name="installed_date" id="installed_date" class="form-control installedDate">
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Part Number:</label>
                                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                                        <input type="text" name="part_number" class="form-control" maxlength="30">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-5 col-xs-12">Date of Last Inspection:</label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input type="text" name="last_inspection_date" id="last_inspection_date" class="form-control lastInspDate">
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Serial Number:</label>
                                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                                        <input type="text" name="serial_number" class="form-control" maxlength="20">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-5 col-xs-12">Last Inspection Hours:</label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input type="text" name="last_inspection_hours" id="last_inspection_hours" class="form-control keypress" maxlength="15">
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-5 col-xs-12">Last Inspection Cycles:</label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input type="text" name="last_inspection_cycles" id="last_inspection_cycles" class="form-control keypress" maxlength="15">
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>

                                        <div class="row">
                                            <br>                          
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-5 col-xs-12">Time Since New:</label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input type="text" name="time_since_new" id="time_since_new" class="form-control keypress" maxlength="10">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-6">Inspection Type/Interval:</label>
                                                </div>
                                            </div>                          
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-5 col-xs-12">Time Since Overhaul:</label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input type="text" name="time_since_overhaul" id="time_since_overhaul" class="form-control timeCyCls keypress" maxlength="10" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Hours:</label>
                                                    <div class="col-md-1 col-sm-1 col-xs-12">
                                                        <input type="checkbox" name="hour_check" class="hourCheck">
                                                    </div>
                                                    <div class="col-md-1 col-sm-1 col-xs-12">
                                                        <strong> - </strong>
                                                    </div>
                                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                                        <input type="text" name="interval_hours" id="interval_hours" class="form-control hrROCls keypress" maxlength="15" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-5 col-xs-12">Cycles Since New:</label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input type="text" name="cycle_since_new" id="cycle_since_new" class="form-control keypress" maxlength="10">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Cycles:</label>
                                                    <div class="col-md-1 col-sm-1 col-xs-12">
                                                        <input type="checkbox" name="cycle_check" class="cyclesCheck">
                                                    </div>
                                                    <div class="col-md-1 col-sm-1 col-xs-12">
                                                        <strong> - </strong>
                                                    </div>
                                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                                        <input type="text" name="interval_cycles" id="interval_cycles" class="form-control cyROCls keypress" maxlength="15" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-5 col-xs-12">Cycles Since Overhaul:</label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input type="text" name="cycle_since_overhaul" id="cycle_since_overhaul" class="form-control timeCyCls keypress" maxlength="10" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-2 col-sm-2 col-xs-12">Days:</label>
                                                    <div class="col-md-1 col-sm-1 col-xs-12">
                                                        <input type="checkbox" name="days_check" class="daysCheck">
                                                    </div>
                                                    <div class="col-md-1 col-sm-1 col-xs-12">
                                                        <strong> - </strong>
                                                    </div>
                                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                                        <input type="text" name="interval_days" id="interval_days" class="form-control daROCls keypress" maxlength="15" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-5 col-xs-12">Overhaul Indicator:</label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input type="checkbox" name="overhaul_indicator" class="overhIndiCheck">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-6">Next Inspection Due:</label>
                                                </div>
                                            </div>                          
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label">&nbsp;</label>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Hours:</label>
                                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                                        <input type="text" name="due_hours" id="due_hours" class="form-control hrROCls" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-5 col-sm-5 col-xs-12">Next Higher Item:</label>
                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                        <input type="text" name="next_item" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Cycles:</label>
                                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                                        <input type="text" name="due_cycles" id="due_cycles" class="form-control cyROCls" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Date:</label>
                                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                                        <input type="text" name="due_date" id="due_date" class="form-control daROCls dateROCls" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>                          
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">Notes:</label>
                                                    <div>
                                                        <textarea type="text" name="notes" class="form-control" rows="2"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group" style="float:right;">
                                                    <span id="addItemMsg"></span>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    <input type="button" value="Save" class="btn btn-primary" id="addItemBtn">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="historical" class="tab-pane fade">
                                    '.$historyHtml.'
                                </div>
                            </div>
                        </div>
                    </div>';

            $res = array('status'=>'success', 'data'=>$tHtml);
            echo json_encode($res);die;
        } else {
            $res = array('status'=>'failure', 'data'=>$tHtml);
            echo json_encode($res);die;
        }
    }

    //get next due based on selected values
    public function addUpdateNextdues()
    {
        $params = $this->request->data;        
        //Check if part months value is date then convert it to months and days
        if(empty($params['partMos'])) {
            $params['intvAdjMos'] = 0;
            $params['intvAdjDay'] = 0;
        }

        //Adjust overhaul and repair
        if(!empty($params['partHrs'])) {
            $params['intvAdjHrs'] = -trim($params['partHrs']);
        } elseif(empty($params['partHrs'])) {
            $params['intvAdjHrs'] = 0;
        }

        if(!empty($params['partAfl'])) {
            $params['intvAdjAfl'] = -trim($params['partAfl']);
        } elseif(empty($params['partAfl'])) {
            $params['intvAdjAfl'] = 0;
        }

        //calculations
        $data = array();
        if(!empty($params)) {
            //Get data correction and nextdue calculation
            $mos = $hrs = $afl = '';
            $getRes = $this->AirframeComponentPart->getDataProcess($params);
            if(!empty($getRes)) {
                $mos = $getRes['mos'];
                $hrs = $getRes['hrs'];
                $afl = $getRes['afl'];
            }
            
            $data = array(
                    'nextMos' => !empty($mos) ? date('m/d/Y', strtotime($mos)) : '',
                    'nextHrs' => round($hrs,1),
                    'nextAfl' => $afl,                    
                );
            $result = array('status'=>'success', 'data'=>$data);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$data);
            echo json_encode($result);die;
        }
    }

    //Add item details
    public function addItemDetails()
    {
        $params = $this->request->data;
        if(!empty($params['id']) && !empty($params['plane_id']) && !empty($params['nomenclature'])) {
            $logbook = $this->Logbooks->get($params['id']);
            $logbook = $this->Logbooks->patchEntity($logbook, $params);
            if($this->Logbooks->save($logbook)) {
                $res = array('status'=>'success', 'message'=>'Item updated successfully.');
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
                echo json_encode($res);die;
            }
        } elseif(!empty($params['plane_id']) && !empty($params['nomenclature'])) {
            $logbook = $this->Logbooks->newEntity();
            $logbook = $this->Logbooks->patchEntity($logbook, $params);

            if($this->Logbooks->save($logbook)) {
                $res = array('status'=>'success', 'message'=>'Item added successfully.');
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'message'=>'Something went wrong. Please try again.');
                echo json_encode($res);die;
            }
        }
    }

    //Update items popup
    public function updateItemPopup()
    {
        $params = $this->request->data;
        $tHtml = '';
        if(!empty($params['pid'])) {

            $planeId    = 0;
            $airCompId  = 0;
            $subCompId  = 0;
            $subComp2Id = 0;
            if(!empty($params['pid']) && $params['level'] == 0) {
                $logHistory = $this->Logbooks->find()
                        ->where(['id'=>$params['pid']])
                        ->enableHydration(false)->first();

            } elseif(!empty($params['pid']) && $params['level'] == 1) {
                $logHistory = $this->Logbooks->find()
                        ->where(['id'=>$params['pid']])
                        ->enableHydration(false)->first();
                
            } elseif(!empty($params['pid']) && $params['level'] == 2) {
                $logHistory = $this->Logbooks->find()
                        ->where(['id'=>$params['pid']])
                        ->enableHydration(false)->first();
                
            } elseif(!empty($params['pid']) && $params['level'] == 3) {
                $logHistory = $this->Logbooks->find()
                        ->where(['id'=>$params['pid']])
                        ->enableHydration(false)->first();
            }
            
            $instDate = !empty($logHistory['installed_date']) ? date('m/d/Y', strtotime($logHistory['installed_date'])) : '';
            $lastInspDate = !empty($logHistory['last_inspection_date']) ? date('m/d/Y', strtotime($logHistory['last_inspection_date'])) : '';

            $readonly = 'readonly="readonly"';
            $hrChk = '';
            if(!empty($logHistory['hour_check'])) {
                $hrChk = 'checked';
                $readonly = '';
            }

            $cyChk = '';
            if(!empty($logHistory['cycle_check'])) {
                $cyChk = 'checked';
                $readonly = '';
            }

            $daysChk = '';
            if(!empty($logHistory['days_check'])) {
                $daysChk = 'checked';
                $readonly = '';
            }

            $indiChk = '';
            if(!empty($logHistory['overhaul_indicator'])) {
                $indiChk = 'checked';
                $readonly = '';
            }
            
            $tHtml .= '<div id="aircraftTabs">
                        <div class="container">
                            <form method="post" id="updateItemFrm" class="form-horizontal">
                                <input type="hidden" name="level" id="levelId" value="'.$params['level'].'">
                                <input type="hidden" name="id" value="'.$logHistory['id'].'">
                                <input type="hidden" name="plane_id" id="planeId" value="'.$logHistory['plane_id'].'">
                                <input type="hidden" name="airframe_component_id" id="airCompId" value="'.$logHistory['airframe_component_id'].'">
                                <input type="hidden" name="sub_component_id" id="subCompId" value="'.$logHistory['sub_component_id'].'">
                                <input type="hidden" name="sub_component2_id" id="subComp2Id" value="'.$logHistory['sub_component2_id'].'">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4 col-sm-4 col-xs-12">Nomenclature:</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="text" name="nomenclature" value="'.$logHistory['nomenclature'].'" class="form-control" maxlength="30">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-5 col-xs-12">Date Installed:</label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="text" name="installed_date" value="'.$instDate.'" id="installed_date" class="form-control installedDate">
                                            </div>
                                        </div>
                                    </div>                          
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4 col-sm-4 col-xs-12">Part Number:</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="text" name="part_number" value="'.$logHistory['part_number'].'" class="form-control" maxlength="30">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-5 col-xs-12">Date of Last Inspection:</label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="text" name="last_inspection_date" value="'.$lastInspDate.'" id="lastInspDate" class="form-control lastInspDate">
                                            </div>
                                        </div>
                                    </div>                          
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4 col-sm-4 col-xs-12">Serial Number:</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="text" name="serial_number" value="'.$logHistory['serial_number'].'" class="form-control" maxlength="20">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-5 col-xs-12">Last Inspection Hours:</label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="text" name="last_inspection_hours" id="lastInspHours" value="'.$logHistory['last_inspection_hours'].'" class="form-control upkeypress" maxlength="15">
                                            </div>
                                        </div>
                                    </div>                          
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-5 col-xs-12">Last Inspection Cycles:</label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="text" name="last_inspection_cycles" id="lastInspCycles" value="'.$logHistory['last_inspection_cycles'].'" class="form-control upkeypress" maxlength="15">
                                            </div>
                                        </div>
                                    </div>                          
                                </div>

                                <div class="row">
                                    <br>                          
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-5 col-xs-12">Time Since New:</label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="text" name="time_since_new" id="timeSinceNew" value="'.$logHistory['time_since_new'].'" class="form-control upkeypress" maxlength="10">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-6">Inspection Type/Interval:</label>
                                        </div>
                                    </div>                          
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-5 col-xs-12">Time Since Overhaul:</label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="text" name="time_since_overhaul" id="timeSinceOverhaul" value="'.$logHistory['time_since_overhaul'].'" class="form-control timeCyCls upkeypress" maxlength="10" '.$readonly.'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Hours:</label>
                                            <div class="col-md-1 col-sm-1 col-xs-12">
                                                <input type="checkbox" name="hour_check" value="'.$logHistory['hour_check'].'" class="hourCheck" '.$hrChk.'>
                                            </div>
                                            <div class="col-md-1 col-sm-1 col-xs-12">
                                                <strong> - </strong>
                                            </div>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="text" name="interval_hours" id="intvHours" value="'.$logHistory['interval_hours'].'" class="form-control hrROCls upkeypress" maxlength="15" '.$readonly.'>
                                            </div>
                                        </div>
                                    </div>                          
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-5 col-xs-12">Cycles Since New:</label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="text" name="cycle_since_new" id="cycleSinceNew" value="'.$logHistory['cycle_since_new'].'" class="form-control upkeypress" maxlength="10">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Cycles:</label>
                                            <div class="col-md-1 col-sm-1 col-xs-12">
                                                <input type="checkbox" name="cycle_check" value="'.$logHistory['cycle_check'].'" class="cyclesCheck" '.$cyChk.'>
                                            </div>
                                            <div class="col-md-1 col-sm-1 col-xs-12">
                                                <strong> - </strong>
                                            </div>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="text" name="interval_cycles" id="intvCycles" value="'.$logHistory['interval_cycles'].'" class="form-control cyROCls upkeypress" maxlength="15" '.$readonly.'>
                                            </div>
                                        </div>
                                    </div>                          
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-5 col-xs-12">Cycles Since Overhaul:</label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="text" name="cycle_since_overhaul" id="cycleSinceOverhaul" value="'.$logHistory['cycle_since_overhaul'].'" class="form-control timeCyCls upkeypress" maxlength="10" '.$readonly.'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Days:</label>
                                            <div class="col-md-1 col-sm-1 col-xs-12">
                                                <input type="checkbox" name="days_check" value="'.$logHistory['days_check'].'" class="daysCheck" '.$daysChk.'>
                                            </div>
                                            <div class="col-md-1 col-sm-1 col-xs-12">
                                                <strong> - </strong>
                                            </div>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="text" name="interval_days" id="intvDays" value="'.$logHistory['interval_days'].'" class="form-control upkeypress" maxlength="15" '.$readonly.'>
                                            </div>
                                        </div>
                                    </div>                          
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-5 col-xs-12">Overhaul Indicator:</label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="checkbox" name="overhaul_indicator" value="'.$logHistory['overhaul_indicator'].'" class="overhIndiCheck upOHIndiChk" '.$indiChk.'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-6">Next Inspection Due:</label>
                                        </div>
                                    </div>                          
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">&nbsp;</label>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Hours:</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input type="text" name="due_hours" id="dueHours" value="'.$logHistory['due_hours'].'" class="form-control hrROCls" '.$readonly.'>
                                            </div>
                                        </div>
                                    </div>                          
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-5 col-xs-12">Next Higher Item:</label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="text" name="next_item" value="'.$logHistory['next_item'].'" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Cycles:</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input type="text" name="due_cycles" id="dueCycles" value="'.$logHistory['due_cycles'].'" class="form-control cyROCls" '.$readonly.'>
                                            </div>
                                        </div>
                                    </div>                          
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">&nbsp;</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Date:</label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input type="text" name="due_date" id="dueDate" value="'.$logHistory['due_date'].'" class="form-control daROCls dateROCls" '.$readonly.'>
                                            </div>
                                        </div>
                                    </div>                          
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Notes:</label>
                                            <div>
                                                <textarea type="text" name="notes" class="form-control" rows="2">'.$logHistory['notes'].'</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" style="float:right;">
                                            <span id="updateItemMsg"></span>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <input type="button" value="Update" class="btn btn-primary" id="updateItemBtn">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>';

            $res = array('status'=>'success', 'data'=>$tHtml);
            echo json_encode($res);die;
        } else {
            $res = array('status'=>'failure', 'data'=>$tHtml);
            echo json_encode($res);die;
        }
    }

    //Delete item
    public function deleteItem()
    {
        $params = $this->request->data;
        if(!empty($params['pid'])) {
            $logbook = $this->Logbooks->get($params['pid']);

            if($this->Logbooks->delete($logbook)) {
                $result = array('status'=>'success', 'message'=>'Deleted successfully.');
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'message'=>'Something Wrong. Please try again.');
                echo json_encode($result);die;
            }
        } else {
            $result = array('status'=>'failure', 'message'=>'Something Wrong. Please try again.');
            echo json_encode($result);die;
        }
    }

}