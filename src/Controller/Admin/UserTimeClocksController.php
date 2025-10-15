<?php
    
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use Cake\View\View;
    use App\View\Helper\UserTimeClockHelper;
    use Cake\Datasource\FactoryLocator;
    use Cake\ORM\Locator\LocatorAwareTrait;
    use Mpdf\Mpdf;

    class UserTimeClocksController extends AppController
    {
        public function initialize():void {
            $this->loadComponent('UserTimeClock');
            $this->loadComponent('UserManagementHistory');

            parent::initialize();
        }

        protected \App\Model\Table\UserTimeClocksTable $userTimeClocks;

        public function index()
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('ActiveTimeClock', $actionStatus))
                {
                    $actionItems = $actionStatus['ActiveTimeClock'];
                }
                $this->set(compact('actionItems'));
            }
            
            $usertimeclocklist = $this->UserTimeClock->getAllTimeClockActiveUsers();
            $this->set(compact('usertimeclocklist'));
        }

        public function fetchUserTimeClockPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $section = $postData['section'];

                $fileName = '/element/UserTimeClock/';
                $sessionUser = $this->request->getSession()->read('Auth');
                $this->set(compact('sessionUser'));
                $userTimeClocks = $this->fetchTable('UserTimeClocks');

                if($section == 'time_clock'){
                    $fileName .= 'time_clock';
                }else if($section == 'check_time_clock_status'){
                    $fileName .= 'check_time_clock_status';
                }else if($section == 'time_clock_adjustment'){
                    $fileName .= 'time_clock_adjustment';

                    $userlist = $this->UserTimeClock->getUserListDropDown();
                    $this->set(compact('userlist'));
                }else if($section == 'add_new_time_clock_record'){
                    $fileName .= 'add_new_time_clock_record';

                    $userlist = $this->UserTimeClock->getUserListDropDown();
                    $this->set(compact('userlist'));
                }else if($section == 'add_hours_to_start_time'){
                    $fileName .= 'add_hours_to_start_time';
                }else if($section == 'time_clock_log'){
                    $fileName .= 'time_clock_log';
                    
                    $userlist = $this->UserTimeClock->getUserListDropDown($sessionUser['id']);
                    $this->set(compact('userlist'));
                }else if($section == 'edit_time_clock'){
                    $fileName .= 'edit_time_clock';
                    
                    $timeclocks = $this->fetchTable('UserTimeClocks')->get($postData['time_clock_id']);
                    $userlist = $this->UserTimeClock->getUserListDropDown();
                    $this->set(compact('userlist', 'timeclocks'));
                }

                $this->viewBuilder()->setLayout('ajax');
                $this->render($fileName);
            }
        }

        public function checkUserTimeClockStatus(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    
                    $is_valid_time_clock_code = $this->UserTimeClock->validateUserAccessCode($postData['user_time_clock_code']);
                    if(!empty($is_valid_time_clock_code)){
                        $usertimeclocks = $this->UserTimeClock->getUserTimeClockDetail();
                        $message = '';
                        if(!empty($usertimeclocks) && empty($usertimeclocks['out_time'])){
                            $message = 'User is currently logged IN.';
                        }else {
                            $message = 'User is currently logged OUT.';
                        }
                        $responsearr = ['status'=>'success', 'message'=>$message];
                    }else{
                        $responsearr = ['status'=>'failed', 'message'=>'An invalid code was entered.'];
                    }
                }else{
                    $responsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again'];
                }

                echo json_encode($responsearr);die;
            }
        }

        public function markUserTimeClock(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    
                    $is_valid_time_clock_code = $this->UserTimeClock->validateUserAccessCode($postData['user_time_clock_code']);
                    if(!empty($is_valid_time_clock_code)){
                        $usertimeclocks = $this->UserTimeClock->getUserTimeClockDetail();
                        
                        $timeclockdata = [];
                        $message = '';
                        $sessionUser = $this->request->getSession()->read('Auth');
                        $userTimeClocks = $this->fetchTable('UserTimeClocks');

                        $is_add = 0;

                        if(!empty($usertimeclocks) && empty($usertimeclocks['out_time'])){
                            $timeclockdata['out_time'] = new \Cake\I18n\FrozenTime('now');
                            $timeclockdata['updated_by'] = $sessionUser['id'];
                            $timeclockdata['updated_at'] = new \Cake\I18n\FrozenTime('now');

                            $usertimeclocks = $userTimeClocks->patchEntity($usertimeclocks, $timeclockdata);
                            $message = 'You are now logged OUT, '.$sessionUser['first_name'];
                        }else{
                            $usertimeclocks = $userTimeClocks->newEmptyEntity();

                            $usertimeclocks->user_id = $sessionUser['id'];
                            $usertimeclocks->in_time = new \Cake\I18n\FrozenTime('now');
                            $usertimeclocks->added_by = $sessionUser['id'];
                            $usertimeclocks->created_at = new \Cake\I18n\FrozenTime('now');

                            $message = 'You are now logged IN, '.$sessionUser['first_name'];

                            $is_add = 1;
                        }
                        
                        //print_r($usertimeclocks);exit;
                        if ($userTimeClocks->save($usertimeclocks)){
                            if(!empty($is_add)){
                                //save history data
                                $this->UserManagementHistory->saveUserTimeClockHistory($usertimeclocks);
                            }

                            $date_to = date("Y-m-d");
                            $date_from = date('Y-m-d', strtotime('-7 days'));
                            $user_id = $sessionUser['id'];

                            //$aircraftoptiondata = $this->CustomerOTC->getAircraftOptionDataWO();
                            $usertimeclocklist = $this->UserTimeClock->getTimeClockDetailByUser($date_from, $date_to, $user_id);

                            $view = new View();
                            $view->set(compact('usertimeclocklist'));
                            $elementContent = $view->element('UserTimeClock/time_clock_data');

                            // Build response array
                            $responsearr = [
                                'status' => 'success',
                                'message' => $message,
                                'time_clock_data' => $elementContent
                            ];
                        }else{
                            $responsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again'];
                        }
                    }else{
                        $responsearr = ['status'=>'failed', 'message'=>'Please enter a valid code and try again.'];
                    }
                }else{
                    $responsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again'];
                }

                echo json_encode($responsearr);die;
            }
        }

        public function loadTimeClockForDate(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();

                    $filterdate = date('Y-m-d', strtotime($postData['time_clock_date']));
                    $filter = ['user_id'=>$postData['user_id'], 'DATE(in_time)'=>$filterdate];
                    
                    $usertimeclocklist = $this->UserTimeClock->filterUserTimeClock($filter);
                    
                    $UserTimeClockHelper = new UserTimeClockHelper(new \Cake\View\View());
                    $timeclockhtml = $UserTimeClockHelper->loadTimeClockForDateHTML($usertimeclocklist);

                    $response = ['status'=>'success', 'message'=>'', 'timeclockhtml'=>$timeclockhtml];
                    echo json_encode($response);die;
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }
        
        public function saveUserTimeClock(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    $authUserData = $this->Authentication->getResult()->getData();
                    $userTimeClocks = $this->fetchTable('UserTimeClocks');

                    $is_add = 0;

                    if(!empty($postData['time_clock_id'])){
                        $usertimeclocks = $userTimeClocks->get($postData['time_clock_id']);
                        $postData['updated_by'] = $authUserData['id'];
                        $postData['updated_at'] = new \Cake\I18n\FrozenTime('now');

                        $timeclocks = $userTimeClocks->patchEntity($usertimeclocks, $postData);

                        $message = 'Time clock detail updated successfully.';
                    }else{
                        $timeclocks = $userTimeClocks->newEmptyEntity();

                        $timeclocks->in_time = $postData['in_time'];
                        $timeclocks->out_time = $postData['out_time'];
                        $timeclocks->user_id = $postData['user_id'];
                        $timeclocks->added_by = $authUserData['id'];
                        $timeclocks->created_at = new \Cake\I18n\FrozenTime('now');

                        $message = 'Time clock detail saved successfully.';

                        $is_add = 1;
                    }
                    
                    
                    //print_r($usertimeclocks);exit;
                    if ($userTimeClocks->save($timeclocks)){
                        if(!empty($is_add)){
                              //save history data
                              $this->UserManagementHistory->saveUserTimeClockHistory($usertimeclocks);
                          }

                        $responsearr = ['status'=>'success', 'message'=>$message];
                    }else{
                        $responsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again'];
                    }
                }else{
                    $responsearr = ['status'=>'failed', 'message'=>'Something went wrong, please try again'];
                }

                echo json_encode($responsearr);die;
            }
        }

        public function timeClockLogReportsPdf()
        {
            $postData = $this->request->getData();
            
            $mainHtml = $this->UserTimeClock->timeClockLogReports($postData);

            if(!empty($mainHtml)){
                $html ='<html lang="en">
                <head>
                  <meta charset="utf-8">
                  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                  <title></title>
                  <style>
                  body{
                    font-family: Arial, Helvetica, sans-serif;
                  }
                  .top-header {
                    font-size: 13px;
                    font-weight: 600;
                    line-height: 22px;
                    border-bottom: 2px solid #000;
                    padding-bottom: 6px;
                  }
                  .main-table {
                    margin-top: 50px;
                  }
                  table {
                    width: 100%;
                  }
                  .mid-header {
                    font-size: 21px;
                    text-align: center;
                    font-weight: 600;
                    padding-top: 8px;
                    line-height: 26px;
                  }
                  .main-td {
                    border-bottom: 4px solid #000;
                    padding-bottom: 15px;
                  }
                  .date-class {
                    font-size: 19px;
                    border-bottom: 3px solid #000;
                    font-weight: bold;
                    font-style: italic;
                    line-height: 1.5;
                  }
                  .time-table tr td { 
                    font-size: 17px;
                  }
                  .time-table .first-row td {
                    padding-bottom: 5px;
                    border-bottom: 2px solid #7f7f7f;
                  }
                  .time-table {
                    width: auto;
                    padding-bottom: 10px;
                    padding-top: 30px;
                  }
                  .hr-right {
                    padding-right: 59px;
                  }
                  .second-row td {
                    padding-top: 10px;
                  }
                  .time-table-td{
                    border-bottom: 4px solid #000;
                  }
                  .emp-hours-table thead td {
                    font-size: 16px;
                    padding: 4px 0px 4px 0px;
                    border-bottom: 4px solid #000;
                  }
                  .emp-hours-table tbody td {
                    padding: 7px 0px 5px 0px;
                    border-bottom: 2px solid #7f7f7f;
                  }
                  .rm-border{
                    border-bottom: 0px;
                    padding-bottom: 0px;
                  }
                  .date-class-bg {
                    font-size: 20px;
                    border-bottom: 3px solid #000;
                    font-weight: bold;
                    font-style: italic;
                    line-height: 1.5;
                  }
                  .table-time-work {
                    border: 1px solid #7f7f7f;
                    margin: 7px 0px 10px 0px;
                    font-size: 17px;
                  }
                  .table-time-work td {
                    font-size: 17px;
                  }
                  .summary-style {
                    font-size: 17px;
                  }
                  .summary-date {
                    font-size: 17px;
                    font-weight: bold;
                  }
                  .table-hrs {
                    font-size: 17px;
                    padding-bottom: 7px;
                  }
                  .tech-table {
                    font-size: 17px;
                    margin-top: 30px;
                  }
                  .tech-table thead td{
                    border-bottom: 1px solid #808080;
                    padding-bottom: 5px;
                  }
                  .tech-table tbody td{
                    border-bottom: 1px solid #808080;
                    padding: 10px 0px 5px 0px;
                  }
                  .td-space {
                    padding-left: 20px !important;
                  }
                  .rs-table {
                    font-size: 17px;
                    margin-top: 30px;
                    width: 97%;
                    margin: auto;
                  }
                  .tcs-table {
                    font-size: 17px;
                    margin-top: 30px;
                    width: 97%;
                    margin: auto;
                  }
                  .rs-table thead td{
                    border-bottom: 1px solid #808080;
                    padding-bottom: 5px;
                  }
                  .rs-table tbody td{
                    border-bottom: 1px solid #808080;
                    padding: 10px 0px 5px 0px;
                  }
                  .tsc_td{
                    border-bottom: 1px solid #808080;
                    padding: 2px 0px 5px 0px;
                  }
                  .date-group-table {
                    font-weight: bold;
                    font-size: 19px;
                    padding: 0px;
                    border-top: 3px solid #000;
                    margin: 10px 0px 5px 0px;
                  }
                  .date-group-table span {
                    border-bottom: 2px solid #000;
                    line-height: 1px;
                    font-style: italic;
                  }
                  .tcs_date_heading {
                    border-bottom: 2px solid #000;
                    line-height: 1px;
                    font-style: italic;
                  }
                  .emp-clock-table {
                    width: 97%;
                    font-size: 17px;
                    margin: auto;
                  }
                  .emp-title {
                    font-weight: bold;
                    font-style: italic;
                  }
                  .emp-title span {
                    text-decoration: underline;
                  }
                  .emp-block-table {
                    width: 70%;
                    padding: 10px 0px 0px 0px;
                  }
                  .emp-block-table td {
                    border-bottom: 2px solid #555555;
                    padding: 7px 0px 7px 0px;
                  }
                  .border-hide td {
                    border: 0px !important;
                  }
                  .emp-bottom-table {
                    width: 97%;
                    font-size: 17px;
                    margin: auto;
                  }
                  .emp-bottom-table td {
                    padding: 10px 0px 10px 0px;
                    border-top: 2px solid #2b2b2b;
                  }
                  .emp-bt-border td{
                    border-top: 3px solid #2b2b2b !important;
                  }
                  </style>
                </head>
                
                <body>'.$mainHtml.'</body></html>';
                //echo $html;exit;
                
                ini_set('pcre.backtrack_limit', '10000000');

                $mpdf = new Mpdf();
                $mpdf->SetDisplayMode('fullpage');
                $mpdf->AddPage('', // L - landscape, P - portrait 
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
                $fileName = "time_clock_log_reports_".date('YmdHis').".pdf";
                $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");

                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            }else{
                $result = array('status'=>'failure', 'message'=>'There were no records for the criteria specified.');
                echo json_encode($result);die;
            }
            
        }

        public function updateTimeClockUserLogoutTime(){
          
            $todaydate = date('Y-m-d');
            $todaydatetime = new \Cake\I18n\FrozenTime('now');

            $userTimeClocks = $this->fetchTable('UserTimeClocks');

            $dataarr = [];
            $usertimeclocks = $userTimeClocks->find('all')->where(['out_time is'=>NULL, 'DATE(in_time)'=>$todaydate])->select($userTimeClocks);
            if($usertimeclocks->count() > 0){
              $useridarr = [];
              foreach($usertimeclocks as $timeclock){
                $useridarr[] = $timeclock['user_id'];
              }
              $useridarr = array_unique($useridarr);

              $sessionUser = $this->request->getSession()->read('Auth');

              $dataarr['out_time'] = $todaydatetime;
              $dataarr['updated_by'] = $sessionUser['id'];
              $dataarr['updated_at'] = $todaydatetime;
              
              $res = $userTimeClocks->updateAll(
                $dataarr,
                array('user_id IN' => $useridarr, 'out_time is'=>NULL, 'DATE(in_time)'=>$todaydate)
              );
              if($res){
                echo "Out time updated";exit;
              }
            }else{
              echo "No out time for update";exit;
            }
          
        }

        public function deleteUserTimeClock(){
          if (!$this->request->is('ajax')) {
              return $this->redirect(['action' => 'index']);
          }else{
              if ($this->request->is('post') || $this->request->is('put')) {
                  $postData = $this->request->getData();
                  $userTimeClocks = $this->fetchTable('UserTimeClocks');

                  $entity = $userTimeClocks->get($postData['time_clock_id']);
                  if(!empty($entity)){
                    $result = $userTimeClocks->delete($entity);

                    $filterdate = date('Y-m-d', strtotime($postData['time_clock_date']));
                    $filter = ['user_id'=>$postData['user_id'], 'DATE(in_time)'=>$filterdate];
                    
                    $usertimeclocklist = $this->UserTimeClock->filterUserTimeClock($filter);
                    
                    $UserTimeClockHelper = new UserTimeClockHelper(new \Cake\View\View());
                    $timeclockhtml = $UserTimeClockHelper->loadTimeClockForDateHTML($usertimeclocklist);

                    $response = ['status'=>'success', 'message'=>'', 'timeclockhtml'=>$timeclockhtml];
                  }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                  }
                  echo json_encode($response);die;
              }else{
                  $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                  echo json_encode($response);die;
              }
          }
        }
    }

?>