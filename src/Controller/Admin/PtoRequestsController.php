<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use Cake\View\View;
    use Cake\Datasource\FactoryLocator;
    use Cake\ORM\Locator\LocatorAwareTrait;

    class PtoRequestsController extends AppController
    {
        public $helpers = array('Session');

        protected \App\Model\Table\UserPTORequestsTable $UserPTORequests;
        protected \App\Model\Table\UserPTORequestLogsTable $UserPTORequestLogs;
        protected \App\Model\Table\CustomerAircraftWOMessagesTable $CustomerAircraftWOMessages;
        protected \App\Model\Table\PTOAccrualRatesTable $PTOAccrualRates;

        public function initialize(): void
        {
            parent::initialize();

            $this->UserPTORequests = $this->fetchTable('UserPTORequests');
            $this->UserPTORequestLogs = $this->fetchTable('UserPTORequestLogs');
            $this->CustomerAircraftWOMessages = $this->fetchTable('CustomerAircraftWOMessages');
            $this->PTOAccrualRates = $this->fetchTable('PTOAccrualRates');

            $this->loadComponent('PTORequests');
        }

        public function beforeRender(\Cake\Event\EventInterface $event) {
            $authUserData = $this->Authentication->getResult()->getData();
            $this->set('userData', $authUserData);
        }

        public function index()
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('PTO Request', $actionStatus))
                {
                    $actionItems = $actionStatus['PTO Request'];
                }
                $this->set(compact('actionItems'));
            }
            
            $sessionUser = $this->request->getSession()->read('Auth');
            $ptorequestslist = $this->PTORequests->getPTOReqestList();
            $this->set(compact('ptorequestslist', 'sessionUser'));
        }

        public function search()
        {
            $query = array();
            
            $query['count']  = "SELECT count( UserPtoRequests.`id`) AS count  FROM `user_pto_requests` UserPtoRequests WHERE 1=1";

            $query['detail'] = "SELECT UserPtoRequests.`id`, UserPtoRequests.`previous_balance`, UserPtoRequests.`hours_used_gained`, UserPtoRequests.`new_balance`, UserPtoRequests.`pto_requests_status`, UserPtoRequests.is_first_paycheck, UserPtoRequests.is_pto_add, UserPtoRequests.created_at, Users.full_name FROM `user_pto_requests` UserPtoRequests JOIN `users` Users ON UserPtoRequests.`user_id` = Users.`id` WHERE 1=1";
            
            return $query;
        }

        public function ajaxPTORequestSearch() {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('PTO Request', $actionStatus))
                {
                    $actionItems = $actionStatus['PTO Request'];
                }
            }
            $this->autoRender = false;
            $this->viewBuilder()->setLayout('ajax');
            $requestData= $this->request->getData();

            $query = $this->search();

            $cond = "";
            if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ) {
                //$search = $requestData['search']['value'];
                //$cond.=" AND ( Users.full_name LIKE '%".$search."%' OR DATE(UserPtoRequests.created_at) LIKE '%".$search."%' OR Users.full_name LIKE '%".$search."%' OR Users.phone_ext LIKE '%".$search."%' OR Users.phone LIKE '%".$search."%' OR  Users.email LIKE '%".$search."%' OR Roles.role_name LIKE '%".$search."%' )";
            }
            
            $columns = array(
                0 => 'UserPtoRequests.id',
                1 => 'Users.full_name',
                2 => 'UserPtoRequests.created_at',
                3 => 'UserPtoRequests.previous_balance',
                4 => 'UserPtoRequests.hours_used_gained', 
                5 => 'UserPtoRequests.new_balance',
                6 => 'UserPtoRequests.pto_requests_status'
            );

            $count = $query['count'].$cond;
            $detail = $query['detail'].$cond;
            $totalCount = $query['count'];

            $conn = ConnectionManager::get('default');
            $results = $conn->execute($count)->fetchAll('assoc');
            $totalData = isset($results[0]['count']) ? $results[0]['count'] : 0;

            $totalFiltered = $totalData;
            $results = $conn->execute( $totalCount )->fetchAll('assoc');
            $totalRecords = isset($results[0]['count']) ? $results[0]['count'] : 0;

            $sidx = $columns[$requestData['order'][0]['column']];
            $sord = $requestData['order'][0]['dir'];

            $start = $requestData['start'];
            $length = PAGINATION_LIMIT;

            $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
            $results = $conn->execute( $SQL )->fetchAll('assoc');

            $i = 0;
            $j=1;
            
            $sessionUser = $this->request->getSession()->read('Auth');

            $data = array();
            $view = $edit = $delete = '';
            $dataval = [];
            foreach ($results as $row) {
                $nestedData= [];
                $nestedData[] = $j++;
                $nestedData[] = $row['full_name'];
                $nestedData[] = date('m/d/Y', strtotime($row['created_at']));
                $nestedData[] = $row['previous_balance'];
                $nestedData[] = $row["hours_used_gained"];
                $nestedData[] = $row["new_balance"];
                $requetstatus = 'Pending';
                if($row['pto_requests_status'] == '2'){
                    $requetstatus = 'Approved';
                }else if($row['pto_requests_status'] == '3'){
                    $requetstatus = 'Denied';
                }
                $nestedData[] = $requetstatus;
                
                $approvedenybtn = '';
                if($row['pto_requests_status'] == '1' && ($sessionUser['role_id'] == '1' || $row['manager_id'] == $authUserData['id'])){
                    $approvedenybtn .= '<a href="javascript:void(0);" class="btn btn-primary btn-xs pto-request-approve-deny-btn" pto-request-id="'.$row['id'].'" data-val="2" source=\'pto\'> Approve</a>';
                    $approvedenybtn .= '<a href="javascript:void(0);" class="btn btn-primary btn-xs pto-request-approve-deny-btn" pto-request-id="'.$row['id'].'" data-val="3" source=\'pto\'> Deny</a>';
                }

                if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $authUserData['id'] == 1){
                    $view = '<a href="javascript:void(0);" class="btn btn-primary btn-xs view_pto_request_det" pto-request-id="'.$row['id'].'"><i class="fa fa-folder"></i> View</a>';
                }
                if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                    $edit = ' <a href="javascript:void(0);" data-val="'.$row['id'].'" class="btn btn-info btn-xs pto_request_create_btn"><i class="fa fa-pencil"></i> Edit</a>';
                }
                if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                    $delete = '<a href="javascript:void(0);" data-val="'.$row['id'].'" data-url="pto_requests/delete" class="btn btn-danger btn-xs pto_request_delete_btn"><i class="fa fa-trash-o"></i> Delete</a>';
                }
                $nestedData[] = $approvedenybtn.$view.$edit.$delete;
                $data[] = $nestedData;
                $i++;
            }

            $airComp = array(
                "draw"            => intval( $requestData['draw'] ),
                "recordsTotal"    => intval( $totalRecords ),
                "recordsFiltered" => intval( $totalFiltered ),
                "data"            => $data
            );
        
            echo json_encode($airComp);die;
        }

        public function savePTORequestAjax(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('PTO Request', $actionStatus))
                {
                    $actionItems = $actionStatus['PTO Request'];
                }
            }
            
            if ($this->request->is('post') || $this->request->is('put')) {
                $postData = $this->request->getData();
                if(!empty($postData['day_of_week'])){
                    for($i=0; $i<count($postData['day_of_week']); $i++){
                        
                        if(empty($postData['day_of_week'][$i])){
                            $resp = ['status'=>'failed', 'message'=>'Day of the week is a required field.'];
                            echo json_encode($resp);die;
                        }else if(empty($postData['date_of_day'][$i])){
                            $resp = ['status'=>'failed', 'message'=>'Date is a required field.'];
                            echo json_encode($resp);die;
                        }else if(empty($postData['time_from'][$i])){
                            $resp = ['status'=>'failed', 'message'=>'Time From is a required field.'];
                            echo json_encode($resp);die;
                        }else if(empty($postData['time_to'][$i])){
                            $resp = ['status'=>'failed', 'message'=>'Time To is a required field.'];
                            echo json_encode($resp);die;
                        }else if(empty($postData['pto_to_use'][$i])){
                            $resp = ['status'=>'failed', 'message'=>'PTO to Use is a required field.'];
                            echo json_encode($resp);die;
                        }
                    }
                    $response = $this->savePTORequests($postData);
                }else{
                    $resp = ['status'=>'failed', 'message'=>'Please fill PTO details.'];
                    echo json_encode($resp);die;
                }
                echo json_encode($response);die;
            }
        }

        public function savePTORequests($postData){
            $currentdatetime = new \Cake\I18n\FrozenTime('now');
            $todaydate = date('Y-m-d');
            $authUserData = $this->Authentication->getResult()->getData();

            $userptorequests = $this->fetchTable('UserPTORequests');
            $userptorequestlogs = $this->fetchTable('UserPTORequestLogs');

            if(!empty($postData['pto_request_id'])){
                $userptorequestlogs->deleteAll(['pto_request_id'=>$postData['pto_request_id']]);
                
                $ptorequests = $userptorequests->get($postData['pto_request_id']);

                $lastptorecord = $ptorequests;

                $ptorequestpost = [];
                $ptorequestpost['updated_by'] = $authUserData['id'];
                $ptorequestpost['updated_at'] = $currentdatetime;

                $ptorequests = $userptorequests->patchEntity($ptorequests, $ptorequestpost);
            }else{
                $ptorequests = $userptorequests->newEmptyEntity();

                $lastptorecord = $userptorequests->find('all', ['order'=>'id desc'])->where(['user_id'=>$authUserData['id'], 'pto_requests_status !='=>'3'])->select(['previous_balance', 'new_balance'])->first();

                /*$ptorequestpost = [];
                $ptorequestpost['user_id'] = $authUserData['id'];
                $ptorequestpost['manager_id'] = $authUserData['direct_manager_id'];
                $ptorequestpost['added_by'] = $authUserData['id'];
                $ptorequestpost['created_at'] = $currentdatetime;*/

                $ptorequests->user_id = $authUserData['id'];
                $ptorequests->manager_id = $authUserData['direct_manager_id'];
                $ptorequests->added_by = $authUserData['id'];
                $ptorequests->created_at = $currentdatetime;
            }

            if($userptorequests->save($ptorequests)){
                $pto_request_id = $ptorequests->id;

                $totaltime = 0;
                $totalptouse = 0;
                
                for($i=0; $i<count($postData['day_of_week']); $i++){
                    if(!empty($postData['day_of_week'][$i])){
                        $ptorequestlogs = $userptorequestlogs->newEmptyEntity();

                        $totalptouse += $postData['pto_to_use'][$i];
                        $milliseconds = strtotime($postData['time_to'][$i]) - strtotime($postData['time_from'][$i]);
                        $calulatedtime = $milliseconds / 3600;
                        $calulatedtime = round($calulatedtime, 2);
                        $totaltime += $calulatedtime;

                        $ptorequestlogs->pto_request_id = $pto_request_id;
                        $ptorequestlogs->day_of_week = $postData['day_of_week'][$i];
                        $ptorequestlogs->date_of_day = $postData['date_of_day'][$i];
                        $ptorequestlogs->time_from = $postData['time_from'][$i];
                        $ptorequestlogs->time_to = $postData['time_to'][$i];
                        $ptorequestlogs->pto_to_use = $postData['pto_to_use'][$i];
                        $ptorequestlogs->added_by = $authUserData['id'];
                        $ptorequestlogs->created_at = $currentdatetime;

                        $userptorequestlogs->save($ptorequestlogs);
                    }
                }

                $ptorequests = $userptorequests->get($pto_request_id);
                $ptorequestpost = [];
                $previous_balance = 0;
                $new_balance = 0;
                if(!empty($postData['pto_request_id'])){
                    $previous_balance = $lastptorecord->previous_balance;
                    $new_balance = $previous_balance-$totalptouse;
                }else{
                    if(!empty($lastptorecord)){
                        $previous_balance = $lastptorecord->new_balance;
                        $new_balance = $previous_balance-$totalptouse;
                        //$new_balance += $totaltime;
                    }else{
                        $new_balance = $totalptouse;
                    }
                }
                
                $ptorequestpost['previous_balance'] = $previous_balance;
                $ptorequestpost['hours_used_gained'] = $totalptouse;
                $ptorequestpost['new_balance'] = $new_balance;
                $ptorequests = $userptorequests->patchEntity($ptorequests, $ptorequestpost);

                $userptorequests->save($ptorequests);

                if(!empty($postData['pto_request_id'])){
                    $user_id = $ptorequests->user_id;

                    $this->updatePTORequestDetail($user_id);
                }

                if(!empty($authUserData['direct_manager_id']) && empty($postData['pto_request_id'])){
                    $sessionUser = $this->request->getSession()->read('Auth');
                    $customerAircraftWOMessages = $this->fetchTable('CustomerAircraftWOMessages');

                    $wooptionsendmsg = $customerAircraftWOMessages->newEmptyEntity();
                    $messagepost = [];

                    $messagepost['pto_request_id'] = $pto_request_id;
                    $messagepost['message_to'] = $sessionUser['direct_manager_id'];
                    $messagepost['message_subject'] = 'PTO Request';
                    $messagepost['message'] = $sessionUser['full_name'].' is requesting PTO for '.$todaydate.' for '.$totaltime.' hours of work. Please select APPROVE or DENY.';
                    $messagepost['added_by'] = $authUserData['id'];
                    $messagepost['created_at'] = new \Cake\I18n\FrozenTime('now');
                    
                    $wooptionsendmsg = $customerAircraftWOMessages->patchEntity($wooptionsendmsg, $messagepost);
                    $customerAircraftWOMessages->save($wooptionsendmsg);
                }
                
                $resp = ['status'=>'success', 'message'=>'PTO Requests saved successfully'];
                return $resp;
            }else{
                $resp = ['status'=>'failed', 'message'=>'The PTO Request could not be saved. Please try again.'];
                return $resp;
            }
        }

        public function updatePTORequestDetail($user_id){
            $userPTORequests = $this->fetchTable('UserPTORequests');
            $ptorequestlist = $userPTORequests->find('all')->where(['user_id'=>$user_id, 'pto_requests_status != '=>'3'])->toArray();
            foreach($ptorequestlist as $key=>$ptorequest){
                if($key == '0'){
                    $lastptorecord = $ptorequest;
                    continue;
                }else{
                    $lastptorecord = $ptorequestlist[$key-1];
                }
                $ptorequestpost = [];
                
                $totalptouse = $ptorequest->hours_used_gained;
                $previous_balance = $lastptorecord->new_balance;
                if(!empty($ptorequest['is_pto_add'])){
                    $new_balance = $previous_balance+$totalptouse;
                }else{
                    $new_balance = $previous_balance-$totalptouse;
                }
                
                $ptorequestpost['previous_balance'] = $previous_balance;
                $ptorequestpost['hours_used_gained'] = $totalptouse;
                $ptorequestpost['new_balance'] = $new_balance;
                $userptorequests = $userPTORequests->patchEntity($ptorequest, $ptorequestpost);

                $userPTORequests->save($userptorequests);
            }
        }

        public function approveDenyPTORequests(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $authUserData = $this->Authentication->getResult()->getData();
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    $userPTORequests = $this->fetchTable('UserPTORequests');

                    $pto_request_id = $postData['pto_request_id'];
                    $pto_requests_status = $postData['pto_requests_status'];
                    if(!empty($pto_request_id) && !empty($pto_requests_status)){
                        $res = $userPTORequests->updateAll(
                            array('updated_by'=>$authUserData['id'], 'updated_at'=>new \Cake\I18n\FrozenTime('now'), 'pto_requests_status' => $pto_requests_status),
                            array('id' => $pto_request_id)
                        );
                        
                        if ($res) {
                            //update pto request calculations
                            $ptorequests = $userPTORequests->get($pto_request_id);
                            $user_id = $ptorequests->user_id;
                            
                            $this->updatePTORequestDetail($user_id);
                            
                            $pto_requests_status = $pto_requests_status == '2' ? 'Approve' : 'Deny';

                            $response = ['status'=>'success', 'message'=>'PTO Requests has been '.$pto_requests_status];
                            echo json_encode($response);die;
                        }else{
                            $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                            echo json_encode($response);die;
                        }
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getPTORequestDet(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;

                    $pto_request_id = $postData['pto_request_id'];
                    if(!empty($pto_request_id)){
                        $ptorequestlist = $this->PTORequests->getPTOReqestsDet($pto_request_id);
                        
                        $this->set(compact('ptorequestlist'));
                        $this->viewBuilder()->setLayout('ajax');
                        $this->render('/element/PTORequests/view_pto_requests');
                    }else{
                        echo 'Failed';die;
                    }
                }else{
                    echo 'Failed';die;
                }
            }
        }

        public function createPTORequestPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                if ($this->request->is('post') || $this->request->is('put')) {
                    $sessionUser = $this->request->getSession()->read('Auth');
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    $pto_request_id = !empty($postData['pto_request_id']) ? $postData['pto_request_id'] : '';
                    $ptorequestlogs = [];
                    if(!empty($pto_request_id)){
                        $userptorequests = $this->fetchTable('UserPTORequests')->get($pto_request_id);
                        $ptorequestlogs = $this->fetchTable('UserPTORequestLogs')->find('all')->where(['pto_request_id'=>$pto_request_id]);
                    }else{
                        $userptorequests = $this->fetchTable('UserPTORequests')->newEmptyEntity();
                    }
                    
                    $this->set(compact('sessionUser', 'userptorequests', 'ptorequestlogs', 'pto_request_id'));
                    $this->viewBuilder()->setLayout('ajax');
                    $this->render('/element/PTORequests/create_pto_requests_popup');
                }
            }
        }

        public function pto_accrual_rate(){
            $actionItems = '';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('PTO Accrual Rate', $actionStatus))
                {
                    $actionItems = $actionStatus['PTO Accrual Rate'];
                }
            }
            $ptoaccrualrates = $this->fetchTable('PTOAccrualRates')->newEmptyEntity();
            $ptoaccrualratelist = $this->getPTOAccrualRates();
            $this->set(compact('ptoaccrualratelist', 'ptoaccrualrates'));
        }

        public function delete($id = null)
        {
            $postData = $this->request->getData();//print_r($postData);exit;
            $id = $postData['id'];
            $this->request->allowMethod(['post', 'delete']);
            $userPTORequests = $this->fetchTable('UserPTORequests');
            $ptoRequests = $userPTORequests->get($id);
            $user_id = $ptoRequests->user_id;
            try {
                if ($userPTORequests->delete($ptoRequests)) {
                    //Delete PTO Request Logs related data
                    $userPTORequestLogs = $this->fetchTable('UserPTORequestLogs');
                    $ptoRequestLogs = array('UserPTORequestLogs.pto_request_id' => $id);
                    $userPTORequestLogs->deleteAll($ptoRequestLogs,false);
                    
                    $this->updatePTORequestDetail($user_id);

                    $this->Flash->success(__('The PTO Request has been deleted.'));
                } else {
                    $this->Flash->error(__('The PTO Request could not be deleted. Please, try again.'));
                }
            } catch(\PDOException $e) {
                $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
            } catch (\Exception $e) {
                $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
            }    
            return $this->redirect(['action' => 'index']);
        }

        public function getPTOAccrualRates($pto_accrual_rate_id = ''){
            $ptoAccrualRates = $this->fetchTable('PTOAccrualRates');
            $ptoaccrualratelist = $ptoAccrualRates->find('all')
                                                ->select($ptoAccrualRates)
                                                ->select(['users.full_name'])
                                                ->join([
                                                    'users'=>[
                                                        'table'=>'users',
                                                        'type'=>'INNER',
                                                        'conditions'=>'users.id = PTOAccrualRates.added_by'
                                                    ]
                                                ]);
    
            if(!empty($pto_accrual_rate_id)){
                $ptoaccrualratelist = $ptoaccrualratelist->where(['PTOAccrualRates.id'=>$pto_accrual_rate_id])->first();
            }
    
            return $ptoaccrualratelist;
        }

        public function savePTOAccrualRates(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $actionItems='';
                $authUserData = $this->Authentication->getResult()->getData();
                if($authUserData['id'] != 1) {
                    $actionStatus = $this->checkAction();
                    if(array_key_exists('PTO Accrual Rate', $actionStatus))
                    {
                        $actionItems = $actionStatus['PTO Accrual Rate'];
                    }
                }

                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
    
                    $pto_accrual_rate = $postData['pto_accrual_rate'];
                    if(!empty($pto_accrual_rate)){
                        $userPTOAccrualRates = $this->fetchTable('PTOAccrualRates');
                        $ptoaccruaterates = $userPTOAccrualRates->newEmptyEntity();    
                        if(!empty($postData['pto_accrual_rate_id'])){
                            $ptoaccruaterates = $userPTOAccrualRates->get($postData['pto_accrual_rate_id']);
                        }
                        
                        $postData['added_by'] = $authUserData['id'];
                        $postData['created_at'] = new \Cake\I18n\FrozenTime('now');
                        
                        $ptoaccruaterates = $userPTOAccrualRates->patchEntity($ptoaccruaterates, $postData);
                        if ($userPTOAccrualRates->save($ptoaccruaterates)) {
                            $tblhtml = $this->getPTOAccrualRatesHTML();
    
                            $response = ['status'=>'success', 'message'=>'PTO Accrual Rate saved successfully', 'tblhtml'=>$tblhtml];
                            echo json_encode($response);die;
                        }else{
                            $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                            echo json_encode($response);die;
                        }
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function getPTOAccrualRatesHTML(){
            $ptoaccrualratelist = $this->getPTOAccrualRates();
            $tblhtml = '';
            foreach($ptoaccrualratelist as $ptoaccrualrate){
                $tblhtml .= '<tr>';
                $tblhtml .= '<td>'.$ptoaccrualrate['pto_accrual_rate'].'</td>';
                $tblhtml .= '<td>'.$ptoaccrualrate['pto_accrual_rate_value'].'</td>';
                $tblhtml .= '<td>'.$ptoaccrualrate['users']['full_name'].'</td>';
                $tblhtml .= '<td>'.date('m/d/Y', strtotime($ptoaccrualrate['created_at'])).'</td>';
                $tblhtml .= '<td>
                    <button type="button" class="btn btn-default pto_accrual_rate_edit" data-val="'.$ptoaccrualrate['id'].'">Edit</button>
                    <button type="button" class="btn btn-default pto_accrual_rate_delete" data-val="'.$ptoaccrualrate['id'].'">Delete</button>
                </td>';
                $tblhtml .= '</tr>';
            }
    
            return $tblhtml;
        }
    
        public function deletePTOAccrualRates()
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('PTO Accrual Rate', $actionStatus))
                {
                    $actionItems = $actionStatus['PTO Accrual Rate'];
                }
            }
            
            if ($this->request->is('post') || $this->request->is('put')) {
                $postData = $this->request->getData();
    
                $this->request->allowMethod(['post', 'delete']);
                $pto_accrual_rate_id = $postData['pto_accrual_rate_id'];
                try {
                    if ($this->PTOAccrualRates->deleteAll(['id'=>$pto_accrual_rate_id])) {
                        $tblhtml = $this->getPTOAccrualRatesHTML();
    
                        $response = ['status'=>'success', 'message'=>'PTO Accrual Rate deleted successfully', 'tblhtml'=>$tblhtml];
                        echo json_encode($response);die;
                    } else {
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                } catch (\Exception $e) {
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }else{
                $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                echo json_encode($response);die;
            }
        }

        public function savePTOReqeustAutoApprove(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $actionItems='';
                $authUserData = $this->Authentication->getResult()->getData();
                if($authUserData['id'] != 1) {
                    $actionStatus = $this->checkAction();
                    if(array_key_exists('PTO Accrual Rate', $actionStatus))
                    {
                        $actionItems = $actionStatus['PTO Accrual Rate'];
                    }
                }

                if ($this->request->is('post') || $this->request->is('put')) {
                    $postData = $this->request->getData();
                    //echo "<pre>";print_r($postData);exit;
                    $currentdatetime = new \Cake\I18n\FrozenTime('now');

                    $lastptorecord = $this->UserPTORequests->find('all', ['order'=>'id desc'])->where(['user_id'=>$postData['user_id'], 'pto_requests_status !='=>'3'])->select(['previous_balance', 'new_balance'])->first();
                    
                    $userptorequests = $this->UserPTORequests->newEmptyEntity();
                    $ptorequestpost = [];
                    $previous_balance = 0;
                    $new_balance = 0;
                    $hours_used_gained = 0;

                    if(!empty($postData['add_pto_hours'])){
                        $hours_used_gained = $postData['add_pto_hours'];
                    }else{
                        $hours_used_gained = $postData['substract_pto_hours'];
                    }

                    if(!empty($lastptorecord)){
                        $previous_balance = $lastptorecord->new_balance;
                        if(!empty($postData['add_pto_hours'])){
                            $new_balance = $previous_balance+$hours_used_gained;
                            $ptorequestpost['is_pto_add'] = '1';
                        }else{
                            $new_balance = $previous_balance-$hours_used_gained;
                        }
                    }else{
                        $new_balance = $hours_used_gained;
                    }
                    
                    $ptorequestpost['user_id'] = $postData['user_id'];
                    $ptorequestpost['previous_balance'] = $previous_balance;
                    $ptorequestpost['hours_used_gained'] = $hours_used_gained;
                    $ptorequestpost['new_balance'] = $new_balance;
                    $ptorequestpost['pto_requests_status'] = '2';
                    $ptorequestpost['added_by'] = $authUserData['id'];
                    $ptorequestpost['created_at'] = $currentdatetime;
                    $userptorequests = $this->UserPTORequests->patchEntity($userptorequests, $ptorequestpost);
                    
                    if($this->UserPTORequests->save($userptorequests)){
                        $response = ['status'=>'success', 'message'=>'PTO Requests saved successfully'];
                        echo json_encode($response);die;
                    }else{
                        $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                        echo json_encode($response);die;
                    }
                }else{
                    $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                    echo json_encode($response);die;
                }
            }
        }

        public function addNewRowPTORequestAjax(){
            $this->viewBuilder()->setLayout('ajax');

            $postData = $this->request->getData();

            $counter = $postData['counter'];
            $this->set(compact('counter'));
            $this->render('/element/PTORequests/create_pto_request_row');
        }
    }