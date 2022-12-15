<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Admin;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    private $ataCodeObj;
    private $menuItemObj;
    private $airCompPartObj;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Plane');
        $this->loadModel('MenuItems');
        $this->loadModel('UserMenuItems');
        $this->loadModel('UserAircrafts');
        
        $this->loadComponent('Auth', [
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email'],
                    'finder' => 'auth'
                ]
            ],
            'authError' => 'Your session has expired, please login again.',
            'loginRedirect' => [
                'controller' => 'Dashboard',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'authorize' => ['Controller']
        ]);
        
        $this->loadComponent('Report');
        $this->loadComponent('AtaCode');
        $this->loadComponent('Disposition');
        $this->loadComponent('AdsbStatus');
        $this->loadComponent('AirframeComponentPart');
        $this->menuItemObj = TableRegistry::get('MenuItems');
        $this->ataCodeObj = TableRegistry::get('AtaCodes');
        $this->airCompPartObj = TableRegistry::get('AirframeComponentParts');
        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event) {
        $this->viewBuilder()->setLayout('admin');
        $this->Auth->allow('login');
        if($this->request->is('ajax')){
            //return response to ajax call when session expired
            if (!$this->Auth->user()) { 
                //$url = Router::url(['controller' => 'Users', 'action' => 'login', 'prefix' => 'admin'],TRUE);
                echo json_encode(env('SESSION_EXPIRED_MSG'));
                die;
            }
        }
    }

    public function isAuthorized($user) {
        // Only admin can access every action
        if ($this->request->getParam('prefix') === 'admin') {
            $permissionRoles = PERMISSION_ROLE_ID;
            $permissionRoles = explode(',', $permissionRoles);
            if ($user['role'] === 'Admin' || in_array($user['role_id'], $permissionRoles)) {
                $this->set('userLoginEmail', $this->Auth->user('email'));
            	return true;
            } else {
                $this->request->session()->delete('Flash');
                $this->Flash->error(__(UNAUTHORIZED_ADMIN_USER));
            	return $this->redirect(['controller' => 'Users', 'action' => 'login', 'prefix' => 'admin']);
            }
        }
        // Default deny
        return false;
    }
    
    /**
     * setDeleteExceptionMessage method
     * To edit exception message
     * 
     * @param string|null $message.
     * @return string $finalMsg
     */
    public function setDeleteExceptionMessage($message = null) {
        $finalMsg = '';
        if (!empty($message)) {
            if (stripos($message, 'SQLSTATE[23000]: Integrity constraint violation: 1451') !== false) {
                $start = '`.`';
                $end = '`,';
                $table = $this->getStringBetween($message, $start, $end);
                $finalMsg = "Information can't be deleted as it has child record in following table: ".$table.". Delete child information before deleting this.";
            } else {
                $finalMsg = $message;
            }
        }
        return $finalMsg;
    }
        
    /**
     * getStringBetween method
     * To extract the string between two delimiters
     *
     * @param string|null $message.
     * @param string|null $start.
     * @param string|null $end.
     * @return string
     */
    function getStringBetween($string, $start, $end) {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public function sendEmail($data) {
        // Setting email config
        $email = new Email();
        $email->transport('smtp');
        //$email->template('forgotPassword');
        $email->emailFormat('html');
        // set senders information
        $email->from([$data['fromEmail'] => $data['compName']]);
        // set receiver information
        $email->to($data['toEmail']);
        $email->subject($data['subject']);
        $email->viewVars(array('msg' => $data['messageData']));//body($data['messageData']);
        try {
            if ($email->send($data['messageData'])) {
                $status = true;
            } else {
                $status = false;
            }
        } catch (\Exception $e) {
            $status = false;
        }
        return $status;
    }

    /**
     * checkMaxlength method
     * To count the exact length of description with enter key press or copy paste
     *
     * @param string|null $description.
     * @return string
     */
    public function checkMaxlength($description=null) {
        $description =  str_replace("\r\n", "\n", $description);
        // $description = preg_replace('/[^A-Za-z0-9\-]/', '', $description);
        return $description;
    }

    public function checkAction()
    {
        $actionItems=[];
        $userID = $this->Auth->user('id');
        $roleID = $this->Auth->user('role_id');
        $allMenu = $this->menuItemObj->find('all')
            ->select(['MenuItems.name','UserMenuItems.action_add', 'UserMenuItems.action_edit', 'UserMenuItems.action_view', 'UserMenuItems.action_delete'])
            ->join([
                'UserMenuItems' => [
                    'table' => 'user_menu_items',
                    'alias' => 'UserMenuItems',
                    'type' => 'LEFT',
                    'conditions' => [
                        'UserMenuItems.menu_item_id = MenuItems.id',
                    ]
                ]
            ])
            //->where(['UserMenuItems.role_id' => $roleID, 'MenuItems.deleted is null'])->toArray();
            ->where(['UserMenuItems.user_id' => $userID, 'MenuItems.deleted is null'])->toArray();

        foreach ($allMenu as $key => $value) {
            $actionItems[$value['name']] = array('action'=>$value['UserMenuItems']);
        }
        return $actionItems;
    }

    //Common function to get conditional records
    public function condRecords($reports, $params = null)
    {
        $results = array();
        if(!empty($reports)) {
            $j = 0;
            $k = 0;
            $t = 0;
            $p = 0;
            $alt = 0;
            $mResults = array();
            foreach ($reports as $row) {
                //Parent/child
                $subPArr = [];
                $subCArr = [];
                $parent = '';
                if(!empty($row['plane']['airframe_component_parts'])) {
                    foreach ($row['plane']['airframe_component_parts'] as $keyP => $valueP) {
                        //echo $keyP."<br>";
                        if(!empty($valueP['parent_child_relation']['parent_id']) && $row['id'] == $valueP['parent_child_relation']['airframe_component_part_id']) {
                            $subPArr[] = $valueP['parent_child_relation']['parent_id'];
                        }

                        if(!empty($valueP['parent_child_relation']['parent_id']) && $row['id'] == $valueP['parent_child_relation']['parent_id']) {
                            $subCArr[] = $valueP['parent_child_relation']['airframe_component_part_id'];
                        }
                    }
                    if(count($subPArr) >= 1) {
                        $parent = 'P';
                    }
                }
                $chkPExist = $this->getParentsDetail($subPArr);
                
                if(!empty($chkPExist) && !empty($row['parent_id']) && $row['parent_id']!=0) {
                    $mResults['partDet']['parentChild'] = 'C';
                    $mResults['partDet']['parentName']  = $chkPExist;
                } elseif(count($subCArr) >= 1 && empty($row['parent_id'])) {
                    $mResults['partDet']['parentChild'] = 'P';
                    $mResults['partDet']['parentName']  = '';
                } else {
                    $mResults['partDet']['parentChild'] = '';
                    $mResults['partDet']['parentName']  = '';
                }
                
                if(!empty($mResults['partDet']['parentChild']) && $mResults['partDet']['parentChild'] == 'C') {
                    $mResults['partDet']['pId'] = implode(',', $subPArr);
                } elseif (!empty($mResults['partDet']['parentChild']) && $mResults['partDet']['parentChild'] == 'P') {
                    $mResults['partDet']['pId'] = $row['id'];
                }
                //End Parent/child

                $date = !empty($row['airframe_component_last_cw'][0]['last_cw_date']) ? date('d M Y', strtotime($row['airframe_component_last_cw'][0]['last_cw_date'])) : '';

                //Plane details
                $mResults['plane']['plane_id'] = $row['plane']['id'];
                $mResults['plane']['plane_code'] = $row['plane']['plane_code'];              
                
                //ATA code
                $ataAD = '';
                $itemType = '';
                $itemTypePPT = '';
                if (!empty($row['ata_code']) || !empty($row['mfg_code'])) {
                    $ataAD = $this->AtaCode->ataCode($row['ata_code']).' '.$row['mfg_code'];
                } elseif (!empty($row['item_type']) || !empty($row['ad_sb_number']) || !empty($row['amendment'])) {                    
                    if(!empty($row['item_type'])) {
                        $ataAD = $row['item_type'];
                    } 

                    if(!empty($row['amendment']) && !empty($row['ad_sb_number'])) {
                        $ataAD .= ' '.$row['ad_sb_number'].' AMD '.$row['amendment'];
                    } elseif(!empty($row['ad_sb_number'])) {
                        $ataAD .= ' '.$row['ad_sb_number'];
                    } elseif(!empty($row['amendment'])) {
                        $ataAD .= ' '.$row['amendment'];
                    } else {
                        $ataAD = '';
                    }
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
                } else {
                    $itemType = 'INSPECTION';
                }

                $itemTypePPT = $itemType;

                if(!empty($itemType) && !empty($row['disposition'])) {
                    $itemType .= ' <span class="lblColor">DISP:</span>'.$this->Disposition->dispTitle($row['disposition']);
                    $itemTypePPT .= ' DISP:'.$this->Disposition->dispTitle($row['disposition']);
                }

                $mResults['ataCode']['ata_value'] = $this->AtaCode->ataCode($row['ata_code']);
                $mResults['ataCode']['ata_code']  = $row['ata_code'];
                $mResults['ataCode']['item_type'] = $itemType;
                $mResults['ataCode']['item_type_ppt'] = $itemTypePPT;
                $mResults['ataCode']['reference'] = $row['reference'];
                $mResults['ataCode']['ad_sb_number'] = $row['ad_sb_number'];
                $mResults['ataCode']['ad_sb_status'] = $this->AdsbStatus->adsbTitle($row['ad_sb_status']);
                $mResults['ataCode']['disposition'] = $this->Disposition->dispTitle($row['disposition']);

                if($row['airframe_component']['log_book'] == 'Airframe') {
                    $mResults['ataCode']['log_book'] = $row['airframe_component']['log_book'];
                } else {
                    $mResults['ataCode']['log_book'] = $row['airframe_component']['log_book'].' '.$row['airframe_component']['position'];
                }

                //Component Times
                $compHours  = !empty($row['airframe_component']['airframe_component_times'][0]['hours']) ? $row['airframe_component']['airframe_component_times'][0]['hours'] : 0;
                $compCycles = !empty($row['airframe_component']['airframe_component_times'][0]['cycles']) ? $row['airframe_component']['airframe_component_times'][0]['cycles'] : 0;
                                
                //Part description
                $mResults['partDet']['id']           = $row['id'];
                $mResults['partDet']['partDesc']     = !empty($row['description']) ? $row['description'] : '';
                $mResults['partDet']['partNum']      = !empty($row['part_number']) ? $row['part_number'] : '';
                $mResults['partDet']['partSerial']   = !empty($row['serial_number']) ? $row['serial_number'] : '';
                $mResults['partDet']['partHardware'] = !empty($row['hardware']) ? $row['hardware'] : '';
                $mResults['partDet']['partSoftware'] = !empty($row['software']) ? $row['software'] : '';
                $mResults['partDet']['partMMRef']    = !empty($row['mm_ref']) ? $row['mm_ref'] : '';
                $mResults['partDet']['partOpsNum']   = !empty($row['ops_numbers']) ? $row['ops_numbers'] : '';
                
                //Current hours cycles
                $mResults['currHC']['currHCHrs'] = $compHours;
                $mResults['currHC']['currHCAfl'] = $compCycles;
                                                
                //Required Freq or interval
                $mResults['reqFeq']['reqFreqHrs'] = !empty($row['airframe_component_last_cw'][0]['required_frequency_hrs']) ? $row['airframe_component_last_cw'][0]['required_frequency_hrs'] : '';
                $mResults['reqFeq']['reqFreqAfl'] = !empty($row['airframe_component_last_cw'][0]['required_frequency_afl']) ? $row['airframe_component_last_cw'][0]['required_frequency_afl'] : '';
                $mResults['reqFeq']['reqFreqMos'] = !empty($row['airframe_component_last_cw'][0]['required_frequency_mos']) ? $row['airframe_component_last_cw'][0]['required_frequency_mos'] : '';
                $mResults['reqFeq']['reqFreqDays'] = !empty($row['airframe_component_last_cw'][0]['required_frequency_days']) ? $row['airframe_component_last_cw'][0]['required_frequency_days'] : '';

                //lastcw
                $mResults['lastCW']['lastCwHrs'] = !empty($row['airframe_component_last_cw'][0]['last_cw_hrs']) ? $row['airframe_component_last_cw'][0]['last_cw_hrs'] : '';
                $mResults['lastCW']['lastCwAfl'] = !empty($row['airframe_component_last_cw'][0]['last_cw_afl']) ? $row['airframe_component_last_cw'][0]['last_cw_afl'] : '';
                $mResults['lastCW']['lastCwMos'] = !empty($row['airframe_component_last_cw'][0]['last_cw_date']) ? strtoupper(date('m/d/Y', strtotime($row['airframe_component_last_cw'][0]['last_cw_date']))) : '';
                $mResults['lastCW']['lastCwMsc'] = !empty($row['airframe_component_last_cw'][0]['last_cw_msc']) ? $row['airframe_component_last_cw'][0]['last_cw_msc'] : '';

                //Next due
                //Get data correction and nextdue calculation
                $mos = $hrs = $afl = $msc = '';
                if(!empty($row['airframe_component_last_cw'][0])) {
                    $getRes = $this->AirframeComponentPart->postDataProcess($row['airframe_component_last_cw'][0]);
                    $mos = $getRes['mos'];
                    $hrs = $getRes['hrs'];
                    $afl = $getRes['afl'];
                    $msc = $getRes['msc'];
                }
                                
                $mResults['nextDue']['mos'] = !empty($mos) ? date('d-M-Y', strtotime($mos)) : '';
                $mResults['nextDue']['hrs'] = $hrs;
                $mResults['nextDue']['afl'] = $afl;
                $mResults['nextDue']['msc'] = $msc;

                //Start Remaining
                $remData = $this->Report->getRemaining($mos);
                $maintRemMos  = $remData['maintRemMos'];
                $maintRemDays = $remData['maintRemDays'];
                $totalRemD    = $remData['totalRemD'];

                $remHrs = !empty($hrs) ? $hrs - $compHours : 0;
                $remAfl = !empty($afl) ? $afl - $compCycles : 0;

                $mResults['remaining']['remMos']    = $maintRemMos;
                $mResults['remaining']['remDays']   = $maintRemDays;
                $mResults['remaining']['remHrs']    = $remHrs;
                $mResults['remaining']['remAfl']    = $remAfl;
                $mResults['remaining']['totalRemD'] = $totalRemD;
                //End Remaining

                //Tolerance
                $tolrMos  = !empty($row['airframe_component_last_cw'][0]['tolerance_mos']) ? $row['airframe_component_last_cw'][0]['tolerance_mos'] : 0;
                $tolrDays = !empty($row['airframe_component_last_cw'][0]['tolerance_days']) ? $row['airframe_component_last_cw'][0]['tolerance_days'] : 0;
                $tolrHrs  = !empty($row['airframe_component_last_cw'][0]['tolerance_hrs']) ? $row['airframe_component_last_cw'][0]['tolerance_hrs'] : 0;
                $tolrAfl  = !empty($row['airframe_component_last_cw'][0]['tolerance_afl']) ? $row['airframe_component_last_cw'][0]['tolerance_afl'] : 0;

                $totalTlrD = $tolrMos * 30 + $tolrDays;

                $mResults['tolerance']['tolrMos']  = $tolrMos;               
                $mResults['tolerance']['tolrDays'] = $tolrDays;
                $mResults['tolerance']['tolrHrs']  = $tolrHrs;               
                $mResults['tolerance']['tolrAfl']  = $tolrAfl;
                $mResults['tolerance']['totalTlrD']= $totalTlrD;

                //TSN/TSO
                $mResults['TSNTSO']['tsnTso'] = '';
                if(!empty($row['item_type']) && in_array($row['item_type'], ['PART', 'INSPECTION'])) {
                    if(!empty($row['requirement_type']) && $row['requirement_type'] == 'Life Limited') {
                        if(!empty($row['part_installed_times'][0]['new_months']) || (isset($row['part_installed_times'][0]['new_months']) && $row['part_installed_times'][0]['new_months'] == 0)) {
                            $mResults['TSNTSO']['tsnTso'] .= 'Months: '.$row['part_installed_times'][0]['new_months'].' ';
                        }

                        if(!empty($row['part_installed_times'][0]['new_hours'])) {
                            $mResults['TSNTSO']['tsnTso'] .= 'Hours: '.$row['part_installed_times'][0]['new_hours'].'<br>';
                        } elseif (isset($row['part_installed_times'][0]['new_hours']) && $row['part_installed_times'][0]['new_hours'] == 0) {
                            $mResults['TSNTSO']['tsnTso'] .= 'Hours: '.$row['part_installed_times'][0]['new_hours'].'.00 ';
                        }

                        if(!empty($row['part_installed_times'][0]['new_landings']) || (isset($row['part_installed_times'][0]['new_landings']) && $row['part_installed_times'][0]['new_landings'] == 0)) {
                            $mResults['TSNTSO']['tsnTso'] .= 'Cycles: '.$row['part_installed_times'][0]['new_landings'].' ';
                        }
                    } elseif(!empty($row['requirement_type']) && $row['requirement_type'] == 'Overhaul') {
                        if(!empty($row['part_installed_times'][0]['overhaul_months']) || (isset($row['part_installed_times'][0]['overhaul_months']) && $row['part_installed_times'][0]['overhaul_months'] == 0)) {
                            $mResults['TSNTSO']['tsnTso'] .= 'Months: '.$row['part_installed_times'][0]['overhaul_months'].' ';
                        }

                        if(!empty($row['part_installed_times'][0]['overhaul_hours'])) {
                            $mResults['TSNTSO']['tsnTso'] .= 'Hours: '.$row['part_installed_times'][0]['overhaul_hours'].' ';
                        } elseif (isset($row['part_installed_times'][0]['overhaul_hours']) && $row['part_installed_times'][0]['overhaul_hours'] == 0) {
                            $mResults['TSNTSO']['tsnTso'] .= 'Hours: '.$row['part_installed_times'][0]['overhaul_hours'].'.00 ';
                        }

                        if(!empty($row['part_installed_times'][0]['overhaul_landings']) || (isset($row['part_installed_times'][0]['overhaul_landings']) && $row['part_installed_times'][0]['overhaul_landings'] == 0)) {
                            $mResults['TSNTSO']['tsnTso'] .= 'Cycles: '.$row['part_installed_times'][0]['overhaul_landings'].' ';
                        }
                    }
                }

                //Recurring
                $recMos  = !empty($row['airframe_component_last_cw'][0]['recurring_mos']) ? $row['airframe_component_last_cw'][0]['recurring_mos'] : 0;
                $recDays = !empty($row['airframe_component_last_cw'][0]['recurring_days']) ? $row['airframe_component_last_cw'][0]['recurring_days'] : 0;
                $recHrs  = !empty($row['airframe_component_last_cw'][0]['recurring_hrs']) ? $row['airframe_component_last_cw'][0]['recurring_hrs'] : 0;
                $recAfl  = !empty($row['airframe_component_last_cw'][0]['recurring_afl']) ? $row['airframe_component_last_cw'][0]['recurring_afl'] : 0;

                $mResults['recurring']['recMos']  = $recMos;               
                $mResults['recurring']['recDays'] = $recDays;
                $mResults['recurring']['recHrs']  = $recHrs;               
                $mResults['recurring']['recAfl']  = $recAfl;

                //Threshold
                $thresMos  = !empty($row['airframe_component_last_cw'][0]['threshold_mos']) ? $row['airframe_component_last_cw'][0]['threshold_mos'] : 0;
                $thresDays = !empty($row['airframe_component_last_cw'][0]['threshold_days']) ? $row['airframe_component_last_cw'][0]['threshold_days'] : 0;
                $thresHrs  = !empty($row['airframe_component_last_cw'][0]['threshold_hrs']) ? $row['airframe_component_last_cw'][0]['threshold_hrs'] : 0;
                $thresAfl  = !empty($row['airframe_component_last_cw'][0]['threshold_afl']) ? $row['airframe_component_last_cw'][0]['threshold_afl'] : 0;

                $mResults['threshold']['thresMos']  = $thresMos;               
                $mResults['threshold']['thresDays'] = $thresDays;
                $mResults['threshold']['thresHrs']  = $thresHrs;               
                $mResults['threshold']['thresAfl']  = $thresAfl;

                //Utilization
                $mResults['utilization']['hours'] = $utilHours = !empty($row['airframe_component']['utilizations'][0]['hours']) ? $row['airframe_component']['utilizations'][0]['hours'] : 1;
                $mResults['utilization']['cycles'] = $utilCycles = !empty($row['airframe_component']['utilizations'][0]['cycles']) ? $row['airframe_component']['utilizations'][0]['cycles'] : 1;

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
            
                /*$altDays = !empty($row['airframe_component_last_cw'][0]['alert_days']) ? $row['airframe_component_last_cw'][0]['alert_days'] : 30;
                $altHrs  = !empty($row['airframe_component_last_cw'][0]['alert_hrs']) ? $row['airframe_component_last_cw'][0]['alert_hrs'] : 50;
                $altAfl  = !empty($row['airframe_component_last_cw'][0]['alert_afl']) ? $row['airframe_component_last_cw'][0]['alert_afl'] : 25;*/

                $mResults['alert']['altDays'] = $altDays;
                $mResults['alert']['altHrs']  = $altHrs;               
                $mResults['alert']['altAfl']  = $altAfl;

                //Days color
                $mResults['alert']['altDVal'] = '';
                $mResults['alert']['altDColor'] = 'color:#036a03';
                if((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) < 0) {
                    $mResults['alert']['altDColor'] = 'color:#f40808';

                } elseif((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) > 0) { 
                    $mResults['alert']['altDColor'] = 'color:#d35400';
                    
                } elseif(!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays > $totalRemD) {
                    $mResults['alert']['altDColor'] = 'color:#f39c12';
                    $mResults['alert']['altDVal'] = 'alert';

                } elseif(!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays < $totalRemD) {
                   $mResults['alert']['altDColor'] = 'color:#036a03';
                }

                //Hours color
                $mResults['alert']['altHVal'] = '';
                $mResults['alert']['altHColor'] = 'color:#036a03';
                if(!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) < 0) {
                    $mResults['alert']['altHColor'] = 'color:#f40808';

                } elseif(!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) > 0) {
                    $mResults['alert']['altHColor'] = 'color:#d35400';

                } elseif(!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs > $remHrs) {
                    $mResults['alert']['altHColor'] = 'color:#f39c12';
                    $mResults['alert']['altHVal'] = 'alert';

                } elseif (!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs < $remHrs) {
                   $mResults['alert']['altHColor'] = 'color:#036a03';
                }

                //Cycle color
                $mResults['alert']['altAVal'] = '';
                $mResults['alert']['altAColor'] = 'color:#036a03';
                if(!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) < 0) {
                    $mResults['alert']['altAColor'] = 'color:#f40808';

                } elseif(!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) > 0) {
                    $mResults['alert']['altAColor'] = 'color:#d35400';

                } elseif(!empty($altAfl) && !empty($remAfl) && $remAfl > 0 && $altAfl > $remAfl) {
                    $mResults['alert']['altAColor'] = 'color:#f39c12';
                    $mResults['alert']['altAVal'] = 'alert';

                } elseif (!empty($altAfl) && !empty($remAfl) && $remAfl > 0 && $altAfl < $remAfl) {
                   $mResults['alert']['altAColor'] = 'color:#036a03';
                }
                //End Alert 

                //Status button color
                $mResults['alert']['btncolor'] = '';
                if($mResults['alert']['altDColor'] == 'color:#036a03' && $mResults['alert']['altHColor'] == 'color:#036a03' && $mResults['alert']['altAColor'] == 'color:#036a03') {
                    $mResults['alert']['btncolor'] = 'background-color:#036a03';
                }
                
                if((!empty($mos) || !empty($hrs) || !empty($afl)) && !empty($params['type'])) {
                    if(!empty($params['type']) && $params['type'] == 'maintenance') {
                        
                        $results[] = $mResults;

                    } elseif(!empty($params['type']) && ($params['type'] == 'maintenanceItems' || $params['type'] == 'adsbstatus')) {
                        $results[] = $mResults;

                    } elseif(!empty($params['type']) && $params['type'] == 'alldues') {
                        
                        if (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) < 0) 
                            || (!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) < 0) 
                            || (!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) < 0)) {
                            
                            $results[] = $mResults;
                        } 

                        if (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) > 0) 
                            || (!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) > 0) 
                            || (!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) > 0)) {
                            
                            $results[] = $mResults;
                        } 

                        if((!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays > $totalRemD) || (!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs > $remHrs) || (!empty($altAfl) && !empty($remAfl) && $altAfl > 0 && $altAfl > $remAfl)) {
                            //Alert values
                            $results[] = $mResults;
                        }

                    } elseif($params['type'] != 'maintenance' && $params['type'] != 'maintenanceItems' && $params['type'] != 'adsbstatus' && $params['type'] != 'alldues') {
                        if (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) < 0) 
                            || (!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) < 0) 
                            || (!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) < 0)) {
                            
                            $j++;
                            if(!empty($params['type']) && $params['type'] == 'past_due') {
                                $results[$j] = $mResults;
                            }

                        } elseif (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD < 0 && ($totalRemD+$totalTlrD) > 0) 
                            || (!empty($remHrs) && $remHrs < 0 && ($remHrs+$tolrHrs) > 0) 
                            || (!empty($remAfl) && $remAfl < 0 && ($remAfl+$tolrAfl) > 0)) {
                            
                            $t++;
                            if(!empty($params['type']) && $params['type'] == 'tolerance') {
                                $results[$t] = $mResults;
                            }

                        } elseif((!empty($altDays) && (!empty($maintRemMos) || !empty($maintRemDays)) && (!empty($totalRemD) && $totalRemD > 0) && $altDays > $totalRemD) || (!empty($altHrs) && !empty($remHrs) && $remHrs > 0 && $altHrs > $remHrs) || (!empty($altAfl) && !empty($remAfl) && $altAfl > 0 && $altAfl > $remAfl)) {
                            //Alert values
                            $alt++;
                            if(!empty($params['type']) && $params['type'] == 'alert_due') {
                                $results[$alt] = $mResults;
                            }
                        } elseif (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD > 10) 
                            || (!empty($remHrs) && !empty($utilHours) && $remHrs/$utilHours > 10) 
                            || (!empty($remAfl) && !empty($utilCycles) && $remAfl/$utilCycles > 10)) {
                            
                            $p++;
                            if(!empty($params['type']) && $params['type'] != 'coming_due') {
                                //$results[$p] = $mResults;
                            }
                        } 
                        elseif (((!empty($maintRemMos) || !empty($maintRemDays)) && $totalRemD >= 0 && $totalRemD <= 10) 
                            || (!empty($remHrs) && !empty($utilHours) && ($remHrs/$utilHours >= 0 && $remHrs/$utilHours <= 10)) 
                            || (!empty($remAfl) && !empty($utilCycles) && ($remAfl/$utilCycles >= 0 && $remAfl/$utilCycles <= 10))) {
                            $k++;
                            if(!empty($params['type']) && $params['type'] == 'coming_due') {
                                $results[$k] = $mResults;
                            }
                        }
                        elseif(empty($params['type'])) {
                            $results[] = $mResults;
                        }
                    }
                } else {
                    if(!empty($params['type']) && $params['type'] == 'alldues') {
                        //$results[] = $mResults;
                    } else {
                        $results[] = $mResults;
                    }
                }
            }
        }
        return $results;
    }

    //Get parent name
    public function getParentsDetail($pId)
    {
        $result = '';
        if(!empty($pId)) {
            $results = $this->airCompPartObj->find('all')->where(['id IN'=>$pId])->select(['id', 'description'])->enableHydration(false)->toArray();
            if(!empty($results)) {
                $comma = '';
                if(count($results)>1) {
                    $comma = ', ';
                }
                foreach ($results as $key => $value) {
                    $result .= $value['description'].$comma;
                }
            }
        }
        return $result;
    }

    public function checkmydate($date, $lastCwMos=null) {
        $maintRemMos  = '';
        $maintRemDays = '';
        $remainingArr = array();
        if(!empty($date)) {
            $date = $this->AirframeComponentPart->changeFormat($date);
            $tempDate = explode('/', $date);
            // checkdate(month, day, year)
            if(!empty($tempDate) && !empty($tempDate[0]) && !empty($tempDate[1]) && !empty($tempDate[2])) {
                if(checkdate($tempDate[0], $tempDate[1], $tempDate[2])) {
                    if(!empty($lastCwMos)) {
                        $lastCwMos = $this->AirframeComponentPart->changeFormat($lastCwMos);
        
                        $date1 = new \DateTime($date);
                        $date2 = new \DateTime($lastCwMos);
                        $interval = date_diff($date1, $date2);
                        $year = $interval->format('%y');
                        $maintRemMos = $interval->format('%m') + $year * 12;
                        $maintRemDays = $interval->format('%d');

                        if($date1 < $date2) {
                            $maintRemMos = !empty($maintRemMos) ? -$maintRemMos : 0;
                            $maintRemDays = !empty($maintRemDays) ? -$maintRemDays : 0;
                        } 
                        
                        $remainingArr['maintRemMos']  = $maintRemMos;
                        $remainingArr['maintRemDays'] = $maintRemDays;
                        return $remainingArr;
                    } else {
                        return $this->Report->getRemaining($date);
                    }                
                }
            }
        }
    }

    //Generate trip id
    public function generateTripId($length = 10) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    
}
