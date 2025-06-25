<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * AircraftDiscrepancies Controller
 *
 * @property \App\Model\Table\AircraftDiscrepanciesTable $AircraftDiscrepancies
 *
 * @method \App\Model\Entity\AircraftDiscrepancies[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AircraftDiscrepanciesController extends AppController
{
    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    protected \App\Model\Table\AircraftDiscrepanciesTable $AircraftDiscrepancies;

    public function initialize():void {
        parent::initialize();
        $this->loadComponent('Plane');
        $this->loadComponent('Pilot');

        $this->AircraftDiscrepancies = $this->fetchTable('AircraftDiscrepancies');
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($id = null)
    {
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Aircraft Discrepancy', $actionStatus))
            {
                $actionItems = $actionStatus['Aircraft Discrepancy'];
            }
        }
        $conditions = ['discrepancy_corrected !=' => 1];

        if (!is_null($id)) {
            $conditions['plane_id'] = $id; // Only include when $id is NOT NULL
        } else {
            $conditions['plane_id IS'] = null; // Use IS NULL for NULL values
        }
        //Discrepancies
        $discrepResult = $this->AircraftDiscrepancies->find()
                            ->where($conditions)
                            ->enableHydration(false)->all()->toArray();
        $planeId = $id;
        
        //Display assigned aircraft only
        $planes = $this->Plane->getAllPlanes();

        $this->set(compact('actionItems', 'planes', 'planeId', 'discrepResult'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $params = $this->request->getData();
        if(!empty($params['signature_data'])) {
            $params['corrected_signature'] = $params['signature_data'];
        }
        $dth = '00';
        if(!empty($params['discrepancy_time_hour'])) {
            if($params['discrepancy_time_hour']<10) {
                $dth = $params['discrepancy_time_hour'];
            } else {
                $dth = $params['discrepancy_time_hour'];
            } 
        }

        $dtm = '00';
        if(!empty($params['discrepancy_time_minut'])) {
            if($params['discrepancy_time_minut']<10) {
                $dtm = $params['discrepancy_time_minut'];
            } else {
                $dtm = $params['discrepancy_time_minut'];
            }
        }

        $params['discrepancy_time'] = $dth.':'.$dtm;

        if(!empty($params['discrepancy_mel'])) {
            $params['discrepancy_mel'] = 1;
        } else {
            unset($params['mel_category']);
            unset($params['mel_number']);
            unset($params['mel_action']);
            unset($params['deferred_by']);
            unset($params['deferred_cert']);
            unset($params['mel_repair_by']);
        }

        if(!empty($params['discrepancy_corrected'])) {
            $params['discrepancy_corrected'] = 1;
        } else {
            unset($params['corrected_action']);
            unset($params['corrected_by']);
            unset($params['corrected_cert']);
            unset($params['corrected_signature']);
            unset($params['corrected_date']);
        }

        $tHtml = '';
        if(!empty($params['disp_id'])) {
            $airDisc = $this->AircraftDiscrepancies->get($params['disp_id']);
            $res = $this->AircraftDiscrepancies->saveData($airDisc, $params);
            if ($res) {
                $results = $this->AircraftDiscrepancies->find()
                            ->where(['AircraftDiscrepancies.plane_id'=>$params['plane_id'], 'AircraftDiscrepancies.discrepancy_corrected !='=>1])
                            ->enableHydration(false)->toArray();
                if(!empty($results)) {
                    $tHtml = $this->discrepancyListing($results, $params);
                }

                $res = array('status'=>'success', 'message'=>'The Aircraft Discrepancy has been saved.', 'data'=>$tHtml);
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'message'=>'The Aircraft Discrepancy could not be saved. Please, try again.', 'data'=>$tHtml);
                echo json_encode($res);die;
            }
        } else {
            $airDisc = $this->AircraftDiscrepancies->newEmptyEntity();
            $res = $this->AircraftDiscrepancies->saveData($airDisc, $params);
            if ($res) {
                
                $results = $this->AircraftDiscrepancies->find()
                            ->where(['AircraftDiscrepancies.plane_id'=>$params['plane_id'], 'AircraftDiscrepancies.discrepancy_corrected !='=>1])
                            ->enableHydration(false)->toArray();
                if(!empty($results)) {
                    $tHtml = $this->discrepancyListing($results, $params);
                }

                $res = array('status'=>'success', 'message'=>'The Aircraft Discrepancy has been saved.', 'data'=>$tHtml);
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'message'=>'The Aircraft Discrepancy could not be saved. Please, try again.', 'data'=>$tHtml);
                echo json_encode($res);die;
            }
        }
    }

    // change 'm/d/Y' to 'Y-m-d' 
    public function changeDispDate($date)
    {
        if(!empty($date)) {
            $tempDate = explode('/', $date);
            if(!empty($tempDate) && !empty($tempDate[0]) && !empty($tempDate[1]) && !empty($tempDate[2])) {
                $newDate = $tempDate[2].'/'.$tempDate[0].'/'.$tempDate[1];
                return $newDate;
            }
        }
    }

    //Add discrepancy form
    public function addDiscrepancy()
    {
        $params = $this->request->getData();
        $allPilots = $this->Pilot->getPilots();
        $tHtml = '';
        if(!empty($params['plane_id'])) {

            $tHtml .= '<div class="dispDataCls">
                <div class="form-group">
                    <div class="col-sm-6 col-xs-12 mb-10">
                        <label>Date of Discovery</label>
                        <input type="text" name="discrepancy_date" id="discrepancy-date" class="form-control col-md-12 col-xs-12">
                    </div>

                    <div class="col-sm-6 col-xs-12 mb-10">
                        <label>Time of Discovery (hrs / mins)</label>
                        <div class="row">
                            <div class="col-xs-6">
                                <select name="discrepancy_time_hour" class="form-control col-md-6 col-xs-12 selectpicker dispHourCls" data-show-subtext="true" data-live-search="false">';
                                    $hour = array();
                                    for ($i=0; $i < 24; $i++) { 
                                        if($i<10) {
                                            $hour = '0'.$i;
                                            $tHtml .= '<option value="'.$hour.'">'.$hour.'</option>';
                                        } else {
                                            $hour = $i;
                                            $tHtml .= '<option value="'.$hour.'">'.$hour.'</option>';
                                        }     
                                    }
                                $tHtml .= '</select>
                            </div>

                            <div class="col-xs-6">
                                <select name="discrepancy_time_minut" class="form-control col-md-6 col-xs-12 selectpicker dispMinutCls" data-show-subtext="true" data-live-search="false">';
                                    $time = array();
                                    for ($i=0; $i < 60; $i++) { 
                                        if($i<10) {
                                            $time = '0'.$i;
                                            $tHtml .= '<option value="'.$time.'">'.$time.'</option>';
                                        } else {
                                            $time = $i;
                                            $tHtml .= '<option value="'.$time.'">'.$time.'</option>';
                                        }     
                                    }
                                $tHtml .= '</select>
                            </div>
                        </div>
                    </div>                          
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <label>Discrepancy</label>
                        <textarea type="text" name="discrepancy" class="form-control col-md-7 col-xs-12" rows="2"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6 col-xs-12 mb-10">
                        <label>Discovered By</label>
                        <!--input type="text" name="discovered_by" class="form-control col-md-6 col-xs-12 crewListCls"-->
                        <select type="text" name="discovered_by" class="form-control col-md-6 col-xs-12 selectpicker crewListCls" data-show-subtext="true" data-live-search="true" style="display:block !important;">
                            <option value=" "> Select </option>';
                        foreach ($allPilots as $key => $value) {
                            $tHtml .= '<option value="'.$key.'">'.$value.'</option>';
                        }
                        $tHtml .= '</select>
                    </div>

                    <div class="col-sm-6 col-xs-12">
                        <label>Cert Number</label>
                        <input type="text" name="discovered_cert" class="form-control col-md-7 col-xs-12">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12 checkbox-align  btnWrap" style="gap:3px;">
                        <input type="checkbox" name="discrepancy_mel" id="discrepMelId"> Defer this discrepancy (MEL/NEF)
                    </div>
                </div>

                <div class="discrepancyMelUpCls" style="display:none;">
                    <div class="form-group">
                        <div class="col-sm-6 col-xs-12">
                            <label>MEL Category</label>
                            <select name="mel_category" class="form-control col-md-6 col-xs-12 selectpicker melCategory" data-show-subtext="true" data-live-search="false">
                                <option value=""></option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <label>MEL/NEF Item #</label>
                            <input type="text" name="mel_number" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>MEL O or M action</label>
                            <textarea type="text" name="mel_action" class="form-control col-md-7 col-xs-12" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6 col-xs-12">
                            <label>Deferred By</label>
                            <select type="text" name="deferred_by" class="form-control col-md-7 col-xs-12 selectpicker crewListCls" data-show-subtext="true" data-live-search="true" style="display:block !important;">
                                    <option value=" "> Select </option>';
                                foreach ($allPilots as $key => $value) {
                                    $tHtml .= '<option value="'.$key.'">'.$value.'</option>';
                                }
                                $tHtml .= '</select>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <label>Cert Number</label>
                            <input type="text" name="deferred_cert" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6 col-xs-12">
                            <label>Repair by 00:00 on:</label>
                            <input type="text" name="mel_repair_by" id="mel-repair-by" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12 checkbox-align btnWrap" style="gap:3px;">
                        <input type="checkbox" name="discrepancy_corrected" id="discrepCorrectId"> Add corrective action for this discrepancy 
                    </div>
                </div>

                <div class="discrepancyUpdateCls" style="display:none;">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label>Corrective Action</label>
                            <textarea type="text" name="corrected_action" class="form-control col-md-7 col-xs-12" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6 col-xs-12">
                            <label>Technician Name</label>
                            <select type="text" name="corrected_by" class="form-control col-md-7 col-xs-12 selectpicker crewListCls" data-show-subtext="true" data-live-search="true" style="display:block !important;">
                                    <option value=" "> Select </option>';
                                foreach ($allPilots as $key => $value) {
                                    $tHtml .= '<option value="'.$key.'">'.$value.'</option>';
                                }
                                $tHtml .= '</select>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <label>A&P Number</label>
                            <input type="text" name="corrected_cert" class="form-control col-md-7 col-xs-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-8">
                            <div id="signArea">
                                <div>
                                    <label>RTS Signature</label>
                                    <span class="sigNav" style="float:right;">
                                        <span class="clearButton"><a href="#clear">Clear</a></span>
                                    </span>
                                </div>
                                <div class="sig sigWrapper" style="height:auto;">
                                    <div class="typed"></div>
                                    <canvas class="sign-pad" id="sign-pad" width="100%" height="100"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <label>Date</label>
                            <input type="text" name="corrected_date" id="corrected-date" class="form-control col-md-7 col-xs-12">
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

    //Update discrepancy form
    public function updateDiscrepancy()
    {
        $params = $this->request->getData();
        $allPilots = $this->Pilot->getPilots();
        $result = $this->AircraftDiscrepancies->find()
                            ->where([
                                'AircraftDiscrepancies.id'=>$params['disp_id'], 
                                'AircraftDiscrepancies.plane_id'=>$params['plane_id']
                            ])
                            ->enableHydration(false)->first();
        $tHtml = '';
        $signature = '';
        if(!empty($result)) {
            if(!empty($result['corrected_signature'])) {
                $correctedSignature = stream_get_contents($result['corrected_signature']);
                $signature = '<div>
                                <div>
                                    <label>RTS Signature</label>
                                    <span class="sigNav" style="float:right;">
                                        <span class="clearButton"><a href="#clear">Clear</a></span>
                                    </span>
                                </div>
                                <div style="height:auto;">
                                    <img style="border:1px solid #c0c0c0;" src="data:image/png;base64,'.$correctedSignature.'" />
                                </div>
                            </div>';
            } else {
                $signature = '<div id="signArea">
                                <div>
                                    <label>RTS Signature</label>
                                    <span class="sigNav" style="float:right;">
                                        <span class="clearButton"><a href="#clear">Clear</a></span>
                                    </span>
                                </div>
                                <div class="sig sigWrapper" style="height:auto;">
                                    <div class="typed"></div>
                                    <canvas class="sign-pad" id="sign-pad" style="width: 100%;" height="100"></canvas>
                                </div>
                            </div>';
            }
                        
            $disHr = $disMi = '';
            if(!empty($result['discrepancy_time'])) {
                $disTime = explode(':', $result['discrepancy_time']);
                $disHr = $disTime[0];
                $disMi = $disTime[1];
            }

            $dispMel = '';
            $addStyle = 'style="display:none;"';
            if(!empty($result['discrepancy_mel'])) {
                $dispMel = 'checked disabled';
                $addStyle = 'style="display:block;"';
            }

            $dispCorr = 'checked';
            if(!empty($result['discrepancy_corrected'])) {
                $dispCorr = 'checked disabled';
            }

            $discrepancyDate = '';
            if(!empty($result['discrepancy_date'])) {
                $discrepancyDate = date('m-d-Y', strtotime($result['discrepancy_date']));
            }

            $melRepairBy = '';
            if(!empty($result['mel_repair_by'])) {
                $melRepairBy = date('m-d-Y', strtotime($result['mel_repair_by']));
            }

            $correctedDate = '';
            if(!empty($result['corrected_date'])) {
                $correctedDate = date('m-d-Y', strtotime($result['corrected_date']));
            }

            //Crew detail
            $discoveredBy = '';
            if(!empty($result['discovered_by'])) {
                $discoveredBy = $this->Pilot->getPilotName($result['discovered_by']);
            }

            $deferredBy = '';
            if(!empty($result['deferred_by'])) {
                $deferredBy = $this->Pilot->getPilotName($result['deferred_by']);
            }

            $correctedBy = '';
            if(!empty($result['corrected_by']) && is_numeric($result['corrected_by'])) {
                $correctedBy = $this->Pilot->getPilotName($result['corrected_by']);
            }else{
                $correctedBy = !empty($result['corrected_by']) ? $result['corrected_by'] : '';
            }

            $tHtml .= '<div class="dispDataCls">
                <div class="form-group">
                    <div class="col-md-6">
                        <label>Date of Discovery</label>
                        <input type="text" name="discrepancy_date" id="discrepancy-date" class="form-control col-md-12 col-xs-12" value="'.$discrepancyDate.'">
                    </div>

                    <div class="col-md-6">
                        <label>Time of Discovery (hrs / mins)</label>
                        <div class="row">
                            <div class="col-md-6">
                                <select name="discrepancy_time_hour" class="form-control col-md-6 col-xs-12 selectpicker dispHourCls" data-show-subtext="true" data-live-search="false">';
                                    $hour = array();
                                    for ($i=0; $i < 24; $i++) { 
                                        if($i<10) {
                                            $hour = '0'.$i;
                                            $tHtml .= '<option value="'.$hour.'">'.$hour.'</option>';
                                        } else {
                                            $hour = $i;
                                            $tHtml .= '<option value="'.$hour.'">'.$hour.'</option>';
                                        }     
                                    }
                                $tHtml .= '</select>
                            </div>

                            <div class="col-md-6">
                                <select name="discrepancy_time_minut" class="form-control col-md-6 col-xs-12 selectpicker dispMinutCls" data-show-subtext="true" data-live-search="false">';
                                    $time = array();
                                    for ($i=0; $i < 60; $i++) { 
                                        if($i<10) {
                                            $time = '0'.$i;
                                            $tHtml .= '<option value="'.$time.'">'.$time.'</option>';
                                        } else {
                                            $time = $i;
                                            $tHtml .= '<option value="'.$time.'">'.$time.'</option>';
                                        }     
                                    }
                                $tHtml .= '</select>
                            </div>
                        </div>
                    </div>                          
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <label>Discrepancy</label>
                        <textarea type="text" name="discrepancy" class="form-control col-md-7 col-xs-12" rows="2">'.$result['discrepancy'].'</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6">
                        <label>Discovered By</label>
                        <select type="text" name="discovered_by" class="form-control col-md-6 col-xs-12 selectpicker crewListCls" data-show-subtext="false" data-live-search="false" style="display:block !important;">';
                            foreach ($allPilots as $key => $value) {
                                if($result['discovered_by'] == $key) {
                                    $tHtml .= '<option value="'.$key.'" selected>'.$value.'</option>';
                                } else {
                                    $tHtml .= '<option value="'.$key.'">'.$value.'</option>';
                                }
                            }
                            $tHtml .= '</select>
                    </div>

                    <div class="col-md-6">
                        <label>Cert Number</label>
                        <input type="text" name="discovered_cert" class="form-control col-md-7 col-xs-12" value="'.$result['discovered_cert'].'">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <input type="checkbox" name="discrepancy_mel" id="discrepMelId" '.$dispMel.'> Defer this discrepancy (MEL/NEF)
                    </div>
                </div>

                <div class="discrepancyMelUpCls" '.$addStyle.'>
                    <div class="form-group">
                        <div class="col-md-6">
                            <label>MEL Category</label>
                            <select name="mel_category" class="form-control col-md-6 col-xs-12 selectpicker melCategory" data-show-subtext="true" data-live-search="false">
                                <option value=""></option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>MEL/NEF Item #</label>
                            <input type="text" name="mel_number" class="form-control col-md-7 col-xs-12" value="'.$result['mel_number'].'">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label>MEL O or M action</label>
                            <textarea type="text" name="mel_action" class="form-control col-md-7 col-xs-12" rows="2">'.$result['mel_action'].'</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Deferred By</label>
                            <input type="text" name="deferred_by" class="form-control col-md-7 col-xs-12" value="'.$deferredBy.'">
                        </div>

                        <div class="col-md-6">
                            <label>Cert Number</label>
                            <input type="text" name="deferred_cert" class="form-control col-md-7 col-xs-12" value="'.$result['deferred_cert'].'">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Repair by 00:00 on:</label>
                            <input type="text" name="mel_repair_by" id="mel-repair-by" class="form-control col-md-7 col-xs-12" value="'.$melRepairBy.'">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <input type="checkbox" name="discrepancy_corrected" id="discrepCorrectId" '.$dispCorr.'> Add corrective action for this discrepancy 
                    </div>
                </div>

                <div class="discrepancyUpdateCls">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Corrective Action</label>
                            <textarea type="text" name="corrected_action" class="form-control col-md-7 col-xs-12" rows="2">'.$result['corrected_action'].'</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <label>Technician Name</label>
                            <input type="text" name="corrected_by" class="form-control col-md-7 col-xs-12" value="'.$correctedBy.'">
                        </div>

                        <div class="col-md-6">
                            <label>A&P Number</label>
                            <input type="text" name="corrected_cert" class="form-control col-md-7 col-xs-12" value="'.$result['corrected_cert'].'">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8">'.$signature.'</div>

                        <div class="col-md-4">
                            <label>Date</label>
                            <input type="text" name="corrected_date" id="corrected-date" class="form-control col-md-7 col-xs-12" value="'.$correctedDate.'">
                        </div>
                    </div>
                </div>
            </div>';

            $res = array('status'=>'success', 'data'=>$tHtml, 'correction'=>$result['discrepancy_corrected'], 'category'=>$result['mel_category'], 'hour'=>$disHr, 'minut'=>$disMi);
            echo json_encode($res);die;
        } else {
            $res = array('status'=>'failure', 'data'=>$tHtml, 'correction'=>'');
            echo json_encode($res);die;
        }
    }

    //Display all discrepancy items of any aircraft
    public function allDiscrepancy()
    {
        $params = $this->request->getData();
        //Discrepancies
        $results = $this->AircraftDiscrepancies->find()
                            ->where(['AircraftDiscrepancies.plane_id'=>$params['plane_id']])
                            ->enableHydration(false)->toArray();
        $tHtml = '';
        if(!empty($results)) {
            $tHtml .= $this->discrepancyListing($results, $params);
            $res = array('status'=>'success', 'data'=>$tHtml);
            echo json_encode($res);die;
        } else {
            $tHtml .= '<tr><td colspan="7" style="text-align: center;"">No discrepancies found for the selected aircraft</td></tr>';
            $res = array('status'=>'failure', 'data'=>$tHtml);
            echo json_encode($res);die;
        }
    }

    //Display open discrepancies of any aircraft
    public function openDiscrepancy()
    {
        $params = $this->request->getData();
        //Discrepancies
        $results = $this->AircraftDiscrepancies->find()
                            ->where(['AircraftDiscrepancies.plane_id'=>$params['plane_id'], 'AircraftDiscrepancies.discrepancy_corrected !='=>1])
                            ->enableHydration(false)->toArray();
        $tHtml = '';
        if(!empty($results)) {
            $tHtml .= $this->discrepancyListing($results, $params); 
            $res = array('status'=>'success', 'data'=>$tHtml);
            echo json_encode($res);die;
        } else {
            $tHtml .= '<tr><td colspan="7" style="text-align: center;"">No discrepancies found for the selected aircraft</td></tr>';
            $res = array('status'=>'failure', 'data'=>$tHtml);
            echo json_encode($res);die;
        }
    }

    public function discrepancyListing($results, $params)
    {
        $tHtml = '';
        if(!empty($results)) {
            foreach ($results as $key => $value) {
                if(!empty($value["discrepancy_mel"])) { 
                    $dispMel = "yes";
                    $melRepairBy = date('m/d/Y', strtotime($value["mel_repair_by"]));
                } else {
                    $dispMel = "no";
                    $melRepairBy = '';
                }

                if(empty($value["discrepancy_corrected"])) { 
                    $dispCorr = '<button type="button" class="close deleteBtnCls" data-id="'.$value['id'].'" data-plane_id="'.$value['plane_id'].'">&times;</button>';
                } else {
                    $dispCorr = '';
                }

                $tHtml .= '<tr>
                                <td data-id="'.$value['id'].'" data-plane_id="'.$value['plane_id'].'" class="updateDiscrepancy" style="cursor: pointer; color: #5395cf;">'.date('m/d/Y', strtotime($value['discrepancy_date'])).'</td>
                                <td>'.$value["discrepancy"].'</td>
                                <td>'.date('H:i', strtotime($value["discrepancy_time"])).'</td>
                                <td>'.$value["discovered_by"].'</td>
                                <td>'.$dispMel.'</td>
                                <td>'.$melRepairBy.'</td>
                                <td>'.$dispCorr.'</td>
                            </tr>';
            }
        }
        return $tHtml;
    }

    //Delete discrepancy
    public function deleteDiscrepancy() 
    {
        $params = $this->request->getData();
        if(!empty($params['disp_id']) && !empty($params['plane_id'])) {
            $condition = array('AircraftDiscrepancies.id' => $params['disp_id'], 'AircraftDiscrepancies.plane_id' => $params['plane_id']);
            if($this->AircraftDiscrepancies->deleteAll($condition,false)) {
                $result = array('status'=>'success', 'message'=>'Deleted successfully.');
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'message'=>'Something Wrong. Please try again.');
                echo json_encode($result);die;
            }
        }
    }

    public function saveSignature($img)
    {
        $params = $this->request->getData();
        if(!empty($img)) {
            $result = array();
            $imagedata = base64_decode($img);
            $filename = md5(date("dmYhisA"));
            //Location to where you want to created sign image
            $file_name = WWW_ROOT.'signature/'.$filename.'.png';
            file_put_contents($file_name, $imagedata);
            
            $result['file_name'] = $file_name;
            echo json_encode($result);die;
        } else {
            $result['status'] = 'failure';
            $result['file_name'] = '';
            echo json_encode($result);die;
        }
    }

    public function getAirDueRecord()
    {
        $params = $this->request->getData();
    }
   
}
