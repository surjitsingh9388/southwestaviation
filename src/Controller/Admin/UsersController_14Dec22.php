<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\Mailer\Exception;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Database\Expression\QueryExpression;

/**
 * Users Controller
 */
class UsersController extends AppController
{   
    public $paginate = array(
        'limit' => PAGINATION_LIMIT
    );
    
    public function initialize() {
        parent::initialize();
        $this->loadComponent('Address');
        $this->loadComponent('User');
        $this->loadComponent('Pilot');
        $this->loadComponent('Plane');

        $this->loadModel('ResetPasswords');
        $this->loadModel('EmergencyContacts');
        $this->loadModel('EmailQueues');
        $this->loadModel('UserMenuItems');
        $this->loadModel('MenuItems');
        $this->loadModel('Roles');
        $this->loadModel('Pilots');
        $this->loadModel('UserAircrafts');
    }
    
    /**
     * This function used to allow access for controller actions without authentication.
     */
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['isEmailExist','forgotPassword','sendPasswordDetails','isEmailNotExist','generateToken','resetPassword','setNewPassword']);
    }

    /**
     * resetPassword method
     * This function is used to reset password.
     *
     * @return
     */
    public function resetPassword($token=null) {
        $url = Router::url( $this->here, true );
        $prefix = $this->checkIsPilot($url);
        $prefix = lcfirst($prefix);
        //pr($prefix);exit;
        $this->set('title', $prefix.' Reset Password');
        $this->viewBuilder()->setLayout('admin_login');
        if(empty($token)){
            $this->Flash->error(__('Invalid token. Please try again.'));
            return $this->redirect(['controller'=>'Users','action' => 'login', 'prefix' => $prefix]);
        }
        
        //$resetModel = TableRegistry::get('ResetPasswords');
        $tokenExist = $this->ResetPasswords->find('all', ['conditions' => ['token' => $token]])->first();
        
        $this->set(compact('tokenExist'));
        $this->set(compact('prefix'));
        if(empty($tokenExist)){
            $this->Flash->error(__('Invalid token. Please try again.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login', 'prefix' => $prefix]);
        }
        $currentDateTime = strtotime(date('Y-m-d H:i:s'));
        $expired = strtotime(date('Y-m-d H:i:s',strtotime($tokenExist->expired)));
        if($currentDateTime > $expired){
            //delete existing tokens            
            $id = $tokenExist->id;
            //$resetModel = TableRegistry::get('ResetPasswords');
            $resetPasswords = $this->ResetPasswords->get($id);
            //$resetModel->delete($resetPasswords);

            $this->Flash->error(__('Token expired. Please try again lost your password?.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
        
    }

    public function setNewPassword() {
        if ($this->request->is('post')) {
            $user = $this->Users->newEntity();
            $password = $this->request->getData();
            //pr($password['prefix']);exit;
            $user['id'] = $password['user_id'];
            $prefix = $password['prefix'];
            $id = $password['ResetPasswords']['id'];
            //$resetModel = TableRegistry::get('ResetPasswords');
            $resetPasswords = $this->ResetPasswords->get($id);
            $user = $this->Users->get($resetPasswords->user_id, [
            'contain' => ['Roles']]);
            //pr($user->role->role_name);exit;
            $user['password'] = $password['password'];
            if($user->role->role_name == 'Admin' || $user->role->role_name == ROLE_PILOTS)
            {
                if ($this->Users->save($user)) {
                    $resetModel->delete($resetPasswords);
                    $this->Flash->success(__('Password reset successfully.'));
                    if($user->role->role_name == 'Admin' || $user->role->role_name == ROLE_PILOTS){
                        return $this->redirect(['controller' => 'users', 'action' => 'login', 'prefix' => $prefix]);
                    }else{
                        return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
                    }
                }else{
                    $this->Flash->error(__('Somthing went worng. Please, try again.'));
                }
            } else {
                $this->Flash->error(__('You are not authorized user.'));
                return $this->redirect(['controller' => 'users', 'action' => 'login', 'prefix' => $prefix]);
            }
            
        } else {
            $this->Flash->error(__('Invalid request. Please try again.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'home']);
        }
    }

    /**
     * Login method
     * This function checks user identity.
     *
     * @access public
     * @return void
     */
    public function login() {
        //If pilots are coming for login
        //$url = Router::url( $this->here, true );
        $url = Router::url(null, true);
        $prefix = $this->checkIsPilot($url);
        
        $this->set(compact('prefix'));
        //End here.

        $this->viewBuilder()->setLayout('admin_login');
        if ($this->Auth->user('role') == 'Admin') {
            return $this->redirect(['controller'=>'Reports', 'action'=>'customReport']);    
        }

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                if($user['suspended'] == 1) {
                    $this->Flash->error(__('Sorry, your account is Suspended, please call us at +1-918-298-3718 or write to us at admin@aircraft.com for account Activation.'));
                    if($prefix === 'Admin'){
                        return $this->redirect($this->Auth->logout());
                    }else{ //else user is pilot
                        $this->Auth->logout();
                        return $this->redirect(['action' => 'login', 'prefix' => lcfirst($prefix)]);
                    }
                }

                $user['role'] = isset($user['role']['role_name']) ? $user['role']['role_name'] : '';
                //pr($user['role_id']);exit;
                $permissionRoles = PERMISSION_ROLE_ID;
                $permissionRoles = explode(',', $permissionRoles);
                if ($user['role'] === 'Admin' || in_array($user['role_id'], $permissionRoles)) {
                    //$menuItemsModel = TableRegistry::get('MenuItems');
                    $menuItems = $this->MenuItems->find('all')->where(['MenuItems.deleted is null'])->toArray();
                    foreach ($menuItems as $key => $value) {
                        $menuItemsArray[$value['id']] = $value['name'];
                    }
                    if(($user['role'] == 'Admin' || in_array($user['role_id'], $permissionRoles)) && $user['id'] != 1){
                        $user['AllMenuItems'] = $menuItemsArray;
                        $menuAccess = $this->User->getUserMenuItems($user['role_id']);
                        $menuAction = $this->User->getUserActionItems($user['role_id']);
                        $user['UserMenu'] = $menuAccess;
                        $user['UserAction'] = $menuAccess;
                    } else {
                        $user['AllMenuItems'] = $menuItemsArray;
                        $menuAccess = $this->User->getAdminMenuItems();
                        $user['UserMenu'] = $menuAccess;
                    }
                    
                    $this->Auth->setUser($user);
                    $this->updateLastLoginTime();
                    //return $this->redirect($this->Auth->redirectUrl());
                    return $this->redirect(['controller'=>'Reports', 'action'=>'customReport']);
                } else {
                    if($prefix == PILOTS_PREFIX) {
                        $this->Flash->error(__(UNAUTHORIZED_PILOT_USER));
                    } else {
                        $this->Flash->error(__(UNAUTHORIZED_ADMIN_USER));
                    }
                }
            } else {
                $this->Flash->error(__('Invalid username or password, try again'));
            }
        }    
    }
    
    /**
     * Logout Method
     * This function used to destroy user session and redirect to login page.
     * 
     * @access public
     * @return void
     */
    public function logout() {
        $this->request->session()->delete('user');
        //$this->request->session()->delete('quickBook_refresh_token');
        //$this->request->session()->delete('actionData');
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Index method
     * This function used to get list of all users.
     *
     * @return array
     */
    public function index()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Users', $actionStatus))
            {
                $actionItems = $actionStatus['Users'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count(Users.id) AS count  FROM `users` Users LEFT JOIN `roles` Roles ON Users.role_id = Roles.id WHERE 1=1 ";
        $query['detail'] = "SELECT Users.id, Users.full_name, Users.email, Users.phone, Users.phone_ext, Users.suspended, Roles.role_name FROM `users` Users LEFT JOIN `roles` Roles ON Users.role_id = Roles.id WHERE  1=1";

        return $query;
    }

    public function ajaxManageUsersSearch() {
        $actionItems = $actionSubscriptionItems = '';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Users', $actionStatus))
            {
                $actionItems = $actionStatus['Users'];
            }
            $this->set(compact('actionItems'));
        }

        if($this->Auth->user('id') != 1) {
            $actionSubscriptionStatus = $this->checkAction();
            if(array_key_exists('Subscriptions', $actionSubscriptionStatus))
            {
                $actionSubscriptionItems = $actionSubscriptionStatus['Subscriptions'];
            }
            $this->set(compact('actionSubscriptionItems'));
        }

        $this->autoRender = false;
        $this->layout = 'ajax';
        $requestData= $this->request->data;

        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $statusSearch = strtolower($search);
            $status = '';
            if($statusSearch == 'active' || $statusSearch == 'activ' || $statusSearch == 'acti' || $statusSearch == 'act' || $statusSearch == 'ac'){
                $status = "OR Users.suspended = '0'";
            }else if($statusSearch == 'suspended' || $statusSearch == 'suspende' || $statusSearch == 'suspend' || $statusSearch == 'suspen' || $statusSearch == 'suspe' || $statusSearch == 'susp' || $statusSearch == 'sus' || $statusSearch == 'su'){
                $status =  "OR Users.suspended = '1'";
            }
            $cond.=" AND ( Users.id LIKE '%".$search."%' OR Users.full_name LIKE '%".$search."%' OR Users.phone LIKE '%".$search."%' OR  Users.email LIKE '%".$search."%' OR Users.phone_ext LIKE '%".$search."%' OR Roles.role_name LIKE '%".$search."%' ".$status."
            )";
        }

        $columns = array(
            0 => 'Users.id',
            1 => 'Users.full_name',
            2 => 'Users.email',          
            3 => 'Users.phone',
            4 => 'Roles.role_name',
            5 => 'Users.suspended',
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
        $j = 1;
        $data = array();

        $view = $edit = $delete = '';
        foreach ($results as $row) {
            $nestedData= [];
            $nestedData[] = $row["id"];
            $nestedData[] = $row["full_name"];
            $nestedData[] = $row["email"];
            $nestedData[] = $row["phone_ext"].'- '.$row["phone"];
            $nestedData[] = $row["role_name"];
            $companionOf = '';
            $compan = '';
                        
            $nestedData[] = $row["suspended"]? __('Suspended').$compan : __('Active').$compan;
            if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $this->Auth->user('id') == 1)
            {
                $view = '<a href="users/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
            }

            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $this->Auth->user('id') == 1) {
                $edit = ' <a href="users/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }

            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $this->Auth->user('id') == 1) {
                $delete = ' <a data-user_id="'.$row['id'].'" data-url="users/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete '.$row["full_name"].' ?"><i class="fa fa-trash-o"></i> Delete</a>';
            }

            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $users = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );

        echo json_encode($users);die;
    }
    
    /**
     * View method
     * This function is used to get details of a user by id. 
     *
     * @param integer|null $id User id.
     * @return array.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $actionItems = '';
        $loginAsActionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Users', $actionStatus))
            {
                $actionItems = $actionStatus['Users'];
            }
            
        }
        if($this->Auth->user('id') != 1){
            $loginAsActionStatus = $this->checkAction();
            if(array_key_exists('Login As', $loginAsActionStatus))
            {
                $loginAsActionItems = $loginAsActionStatus['Login As'];
            }
        }
        $notAjax = true;
        if($this->request->is('ajax')){
            $this->layout = 'ajax';
            $notAjax = false;
        }
        try {
            $user = $this->Users->get($id, [
                'contain' => [
                            'Roles', 
                            'Addresses' => [
                                            'Countries', 
                                            'States', 
                                            'Cities'
                                        ]
                        ]
            ]);
        } catch (\Exception $e) {
            $this->Flash->error(__('No records found for this User id.'));
            return $this->redirect(['action' => 'index']);
        }
        
        //pr($subscriptionDetails);exit;
        $this->set(compact('user','actionItems', 'loginAsActionItems'));
        $this->set('notAjax', $notAjax);
    }

    /**
     * Add method
     * This function is used to save user and their address.
     *
     * @return Redirects on successful add, renders view otherwise.
     *
     */
    public function add($id=null) 
    {
        $actionItems = '';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Users', $actionStatus))
            {
                $actionItems = $actionStatus['Users'];
            }            
        }

        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            $insertData = array();
            $role = $this->Roles->find('all')->where(['id' => $postData['role_id']])->first()->toArray();
            
            $postData['email'] = trim($postData['email']);
            $user = $this->Users->patchEntity($user, $postData, array('associated' => array('Addresses')));
            $user['timezone_id'] = '1';
                       
            if ($this->Users->save($user)) {

                //Insert in pilots table if pilot roles selected
                $pilotRoles = explode(',', PILOT_ROLE_ID);
                if(in_array($postData['role_id'], $pilotRoles)) {
                    //Save pilot and associated data
                    $postData['user_id'] = $user->id;
                    $postData['certificate_number'] = '000';
                    $pilot = $this->Pilots->newEntity();
                    $this->Pilot->savePilotAndAssociatedData($pilot, $postData, 'addUser');
                }

                //Assign menu items to Operation and Account Roles Users
                $permissionRoles = PERMISSION_ROLE_ID;
                if (!empty($permissionRoles)) {
                    $permissionRoles = explode(',', $permissionRoles);
                    if(in_array($role['id'], $permissionRoles)) {
                        $roleMenuItems = $this->UserMenuItems->find('all')->where(['UserMenuItems.role_id' => $role['id']])->toArray();
                        
                        if(!empty($roleMenuItems)) {
                            $result= $this->Users->save($user);
                            foreach ($roleMenuItems as $key => $value) {
                                $parentId = $this->User->getParentMenuId($value['menu_item_id']);
                                $insertData[$key]['user_id'] = $result->id;
                                $insertData[$key]['menu_item_id'] = $value['menu_item_id'];
                                $insertData[$key]['parent_id'] = $parentId;
                                if(isset($value['action_add']) && $value['action_add'] != 0){
                                        $insertData[$key]['action_add'] = $value['action_add'];
                                }else{
                                    $insertData[$key]['action_add'] = '0';
                                }
                                if(isset($value['action_edit']) && $value['action_edit'] != 0){
                                    $insertData[$key]['action_edit'] = $value['action_edit'];
                                }else{
                                    $insertData[$key]['action_edit'] = '0';
                                }
                                if(isset($value['action_view']) && $value['action_view'] != 0){
                                    $insertData[$key]['action_view'] = $value['action_view'];
                                }else{
                                    $insertData[$key]['action_view'] = '0';
                                }
                                if(isset($value['action_delete']) && $value['action_delete'] != 0){
                                    $insertData[$key]['action_delete'] = $value['action_delete'];
                                }else{
                                    $insertData[$key]['action_delete'] = '0';
                                }
                                $insertData[$key]['updated_by'] = $this->Auth->User('id');
                            }
                            
                            $entities = $this->UserMenuItems->newEntities($insertData);
                            $this->UserMenuItems->deleteAll(['user_id'=>$result->id]);
                            $this->UserMenuItems->saveMany($entities);
                        }
                    }
                }
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $roles = $countries = array();
        $roles = $this->User->getRoles();
        $countries = $this->Address->getCountryList();
        
        if(isset($id)) {
            $selectedUser = $this->Users->get($id, [
                'contain' => ['Roles']
            ]);

            $user['role_name'] = $selectedUser['role']['role_name'];
            $userRoleName = $this->Auth->user('role');
            // To set user role_id for all users with role admin or starts with admin
            if($userRoleName == ROLE_ADMIN || preg_match('#^Admin#', $userRoleName) === 1) {
                $user['role_id'] = $selectedUser['role']['id'];
            } else {
                $userR = array($userRoleName);
                $roles = array_intersect($roles,$userR);
                $user['role_id'] = $selectedUser['role']['id'];
            }
        }

        //pr($user);die;
        
        $this->set(compact('user', 'roles', 'countries', 'actionItems'));
    }

    /**
     * Edit method
     * This function is used to update user deatils.
     *
     * @param integer|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     * 
     * Upadte customer details
     */
    public function edit($id = null, $action = null)
    {   
        $actionItems = '';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Users', $actionStatus))
            {
                $actionItems = $actionStatus['Users'];
            }
        }
        
        try{
            $user = $this->Users->get($id, [
                'contain' => ['Roles','Addresses']
            ]);
        } catch (\Exception $e) {
            $this->Flash->error(__('No records found for this User id.'));
            return $this->redirect(['action' => 'index']);
        }

        //pr($user);die;
        $user['sessionUser'] = $this->Auth->user('id');
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            // get form data
            $postData = $this->request->getData();
            $insertData = array();
                        
            $postData['email'] = trim($postData['email']);
            $new_password = isset($postData['new_password']) ? $postData['new_password'] : '';
            $confirm_password = isset($postData['confirm_password']) ? $postData['confirm_password'] : '';
            
            // check if new password not empty, set the new password
            if (!empty($new_password) && !empty($confirm_password)) {
                if ($new_password == $confirm_password) {
                    $postData['password'] = $new_password;
                } 
            }
            // update user details
            $user = $this->Users->patchEntity($user, $postData, 
                    array('associated' => array('Addresses')));
            //pr($user);die;
            if ($this->Users->save($user)) {
                
                //If pilot roles selected then add data in pilot table
                $pilotRoles = explode(',', PILOT_ROLE_ID);
                if(in_array($postData['role_id'], $pilotRoles)) {
                    //Save pilot and associated data
                    $postData['user_id'] = $user->id;
                    
                    //Find pilot id
                    $pilotRes = $this->Pilots->find()
                                ->select(['id'])
                                ->where(['user_id'=>$user->id])->enableHydration(false)->first();

                    if(!empty($pilotRes)) {
                        
                        $pilot = $this->Pilots->get($pilotRes['id']);
                        $this->Pilot->savePilotAndAssociatedData($pilot, $postData, 'updateUser');
                    } else {
                        $postData['certificate_number'] = '000';
                        $pilot = $this->Pilots->newEntity();
                        $this->Pilot->savePilotAndAssociatedData($pilot, $postData, 'addUser');
                    }
                }

                //Assign menu items to Operation and Account Roles Users
                $permissionRoles = PERMISSION_ROLE_ID;
                if (!empty($permissionRoles)) {
                    $permissionRoles = explode(',', $permissionRoles);
                    $permissionRoles[] = 1;
                    if(in_array($postData['role_id'], $permissionRoles)) {
                        $roleMenuItems = $this->UserMenuItems->find('all')->where(['UserMenuItems.role_id' => $postData['role_id']])->toArray();
                        
                        if(!empty($roleMenuItems)) {
                            $result= $this->Users->save($user);
                            foreach ($roleMenuItems as $key => $value) {
                                $parentId = $this->User->getParentMenuId($value['menu_item_id']);
                                $insertData[$key]['user_id'] = $result->id;
                                $insertData[$key]['menu_item_id'] = $value['menu_item_id'];
                                $insertData[$key]['parent_id'] = $parentId;
                                if(isset($value['action_add']) && $value['action_add'] != 0){
                                        $insertData[$key]['action_add'] = $value['action_add'];
                                }else{
                                    $insertData[$key]['action_add'] = '0';
                                }
                                if(isset($value['action_edit']) && $value['action_edit'] != 0){
                                    $insertData[$key]['action_edit'] = $value['action_edit'];
                                }else{
                                    $insertData[$key]['action_edit'] = '0';
                                }
                                if(isset($value['action_view']) && $value['action_view'] != 0){
                                    $insertData[$key]['action_view'] = $value['action_view'];
                                }else{
                                    $insertData[$key]['action_view'] = '0';
                                }
                                if(isset($value['action_delete']) && $value['action_delete'] != 0){
                                    $insertData[$key]['action_delete'] = $value['action_delete'];
                                }else{
                                    $insertData[$key]['action_delete'] = '0';
                                }
                                $insertData[$key]['updated_by'] = $this->Auth->User('id');
                            }
                            
                            $entities = $this->UserMenuItems->newEntities($insertData);
                            $this->UserMenuItems->deleteAll(['user_id'=>$result->id]);
                            $this->UserMenuItems->saveMany($entities);
                        } else {
                            $result= $this->Users->save($user);
                            $this->UserMenuItems->deleteAll(['user_id'=>$result->id]);
                        }
                    }
                }
                                
                // update user name on session
                if ($id == $this->Auth->user('id')) {
                    $session = $this->getRequest()->getSession();
                    $session->write('Auth.User.full_name', $user->full_name);
                }

                if (isset($postData['welcome_email'])) {
                    if ($postData['welcome_email']) {
                        $result = $this->sendWelcomeEmail($postData);
                        if ($result) {
                            $this->Flash->set('The user details have been updated successfully and new password sent on user\'s email.', ['element' => 'success', 'escape' => false]);
                        } else {
                            $this->Flash->error(__('The user has been updated successfully. But email could not be sent.'));
                        }
                    }    
                } else {
                    $this->Flash->success(__('The user details has been updated.'));
                }
                return $this->redirect(['action' => 'index']);
            } else {
                $error = $user->errors();
                if (isset($error['email']['unique'])) {
                    $this->Flash->error(__($error['email']['unique']));
                } else {
                    $this->Flash->error(__('The user could not be updated. Please, try again.'));
                }
                // set redirection
                if (!empty($action)) {
                    return $this->redirect(['action' => 'edit/'.$id.'/companion']);
                } else {
                    return $this->redirect(['action' => 'edit', $id]);
                }
            }
        }
        
        $roles = $countries = $states = $cities = array();
        $roles = $this->User->getRoles();
        
        $countries = $this->Address->getCountryList();
        if (isset($user->addresses[0]->country_id)) {
            $states = $this->Address->getStateListByCountryId($user->addresses[0]->country_id);
        }
        if (isset($user->addresses[0]->country_id)) {
            $cities = $this->Address->getCityListByStateId($user->addresses[0]->state_id);
        }
                  
        $users = $this->Users->find('list', [
            'keyField' => 'id',
            'valueField' => 'full_name'
        ])->where(array('Users.id !=' => $id, 'Users.role_id'=>'4', 'Users.suspended'=>'0'))->toArray();
        
        if(isset($id)) {
            $selectedUser = $this->Users->get($id, [
                'contain' => ['Roles']
            ]);
            $userRoleName = $this->Auth->user('role');
            // To set user role_id for all users with role admin or starts with admin
            if($userRoleName == ROLE_ADMIN || preg_match('#^Admin#', $userRoleName) === 1) {
                $user['role_id'] = $selectedUser['role']['id'];
            } else {
                $userR = array($userRoleName);
                $roles = array_intersect($roles,$userR);
                $user['role_id'] = $selectedUser['role']['id'];
            }
        }
       
        $this->set(compact('user', 'roles', 'countries', 'states', 'cities', 'users', 'action', 'timezones', 'actionItems'));
    }
    
    /**
     * Delete method
     * To set deleted status.
     *
     * @param integer|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */    
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        try {
            if ($this->Users->delete($user)) {
                $this->Flash->success(__('The user has been deleted.'));
            } else {
                $this->Flash->error(__('The user could not be deleted. Please, try again.'));
            }
        } catch(\PDOException $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        } catch (\Exception $e) {
            $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
        }
        return $this->redirect(['action' => 'index']);
    }
   
    /**
     * IsEmailExist method
     * This function is used to check if the user email already registered.
     *
     * @return boolean true|false.
     */
    public function isEmailExist() {
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $email = $this->request->getData('email');
            $email = trim($email);
            $exists = $this->Users->exists(['email' => $email]);
            if ($exists) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    /**
     * IsEmailExist method
     * This function is used to check is email already exist.
     * 
     * @return boolean true/false
     */
    public function isEmailNotExist() {
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $email = $this->request->getData('email');
            $exists = $this->Users->exists(['email' => $email]);
            if ($exists) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }
    
    /**
     * SendWelcomeEmail method
     * To send welcome email on creation of new user.
     *
     * @param array $emailData.
     * @return boolean true|false
     */    
    public function sendWelcomeEmail($emailData = array()) {
        if (!empty($emailData)) {
            //extract data for email template
            $data['name'] = $emailData['first_name'];
            $data['email'] = $emailData['email'];
            $data['password'] = $emailData['password'];
            $pilotRoles = explode(',', PILOT_ROLE_ID);
            
            if ($emailData['role_id'] == '1') {
                $data['subject'] = 'Welcome to Aircraft Report Admin Portal';
                $data['userType'] = 'Admin';
                $data['url'] = Router::url('/admin', true);
            } elseif (in_array($emailData['role_id'], $pilotRoles)) {
                $data['subject'] = 'Welcome to Aircraft Report Tech Portal';
                $data['userType'] = 'Pilot';
                $data['url'] = Router::url('/pilots', true);
            } else {
                $data['subject'] = 'Welcome to Aircraft Report User Portal';
                $data['userType'] = 'Customer';
                $data['url'] = Router::url('/', true);
            }
            // set senders details
            $data['senderTitle'] = ADMIN_SENDER_NAME;
            $data['senderEmail'] = ADMIN_SENDER_EMAIL;
            $data['senderPhone'] = ADMIN_SENDER_PHONE;
            $data['senderAddress'] = ADMIN_SENDER_ADDRESS;
            $emailArr[] = $emailData['email'];
            $data['emailUsersList'] = $emailArr;

            $userId = $this->Auth->user('id');
            $emailQueueArr['email_type'] = 'User_Welcome';
            $emailQueueArr['email_data'] = json_encode($data);
            $emailQueueArr['status'] = 0;
            $emailQueueArr['updated_by'] = $userId;
            $emailQueue = $this->EmailQueues->newEntity();
            $emailQueue = $this->EmailQueues->patchEntity($emailQueue, $emailQueueArr);
            if ($this->EmailQueues->save($emailQueue)) {
                return 1;
            } else {
                return 0;
            }
        }
    }
    
    /**
     * updateLastLoginTime method
     * To update the login tme of user on login.
     *
     * @return void
     */
    public function updateLastLoginTime() {
        if ($this->Auth->user('id')) {
            $user = $this->Users->get($this->Auth->user('id'));
            $user->last_login = Time::now();
            $this->Users->save($user);
        }
    }

    /**
     * forgotPassword method
     * This function is used to sent mail request.
     *
     * @return Redirects on successful add, renders view otherwise.
     */
    public function forgotPassword() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            $userData = $this->request->getData();  

            if (!empty($userData)) {
                if(!empty($userData['login_type'])) {
                    $prefix = lcfirst($userData['login_type']);
                } else {
                    $prefix = 'admin';
                }
                $result = $this->sendPasswordDetails($userData);
                //pr($result);exit;
                if ($result) {
                    $this->Flash->success(__('Your password reset details successfully sent to your email address.'));
                } else {
                    $userData=$this->Users->find('all', ['conditions' => ['email'=>$userData['email']]])->first()->toArray();
                    //pr($prefix);exit;
                    if($userData['role_id'] != PILOTS_ID && $prefix == 'pilots'){
                        $this->Flash->error(__("Please provide valid Pilot Email Id."));
                    }elseif($userData['role_id'] != '1' && $prefix == 'admin'){
                        $this->Flash->error(__("Please provide valid Admin Email Id"));
                    }elseif($userData['role_id'] == PILOTS_ID && $prefix == 'admin'){
                        $this->Flash->error(__("Please provide valid Admin Email Id"));
                    }elseif($userData['role_id'] == '1' && $prefix == 'pilots'){
                        $this->Flash->error(__("Please provide valid Pilot Email Id"));
                    }else{
                    $this->Flash->error(__('The request could not be sent. Please try again.'));
                    }
                }
            } else {
                $this->Flash->error(__('Please enter required details.'));
            }
            return $this->redirect(['controller' => 'Users', 'action' => 'login', 'prefix' => $prefix]);
        } else {
            $this->Flash->error(__('Invalid request. Please try again.'));
        }
    }

    /**
     * sendPasswordDetails method
     * To send details reset password.
     *
     * @param array $emailData.
     * @return boolean true|false
     */    
    public function sendPasswordDetails($emailData = array()) {
        if (!empty($emailData)) {
            //pr($emailData['login_type']);
            if(!empty($emailData['login_type'])){
                $prefix = lcfirst($emailData['login_type']);
            }else{
                $prefix = 'admin';
            }
            $resetPasswords = $this->ResetPasswords->newEntity();
            $email = $emailData['email'];
            $userData=$this->Users->find('all', ['conditions' => ['email'=>$emailData['email']]])->first()->toArray();
            //return $userData;
            if($userData['role_id'] != PILOTS_ID && $userData['role_id'] != '1'){
                return 0;
            }elseif($userData['role_id'] == PILOTS_ID && $prefix == 'admin'){
                return 0;
            }elseif($userData['role_id'] == '1' && $prefix == 'pilots'){
                return 0;
            }
            $passwordToken = $this->generateToken($userData['id']);
            $resetUrlLink = Router::url('/'.$prefix.'/users/reset-password/'.$passwordToken, true);
            //pr($resetUrlLink);exit;
            $resetPasswords['user_id'] = $userData['id'];
            $resetPasswords['token'] = $passwordToken;
            $resetPasswords['password_reset_link'] = $resetUrlLink;
            $resetPasswords['expired'] = date('Y-m-d H:i:s', strtotime('+24 hours'));
            
            //If token already exist delete them
            //$tokenModel = TableRegistry::get('ResetPasswords');
            $tokenExist = $this->ResetPasswords->find('all', ['conditions' => ['user_id' => $userData['id']]])->toArray();
            $tokenModel->deleteAll(['user_id'=>$userData['id']]);
            if (!$this->ResetPasswords->save($resetPasswords)) {
                $this->Flash->error(__('Somthing went worng. Please try again.'));
                return $this->redirect(['controller' => 'Users', 'action' => 'login', 'prefix' => $prefix]);
            }
                        
            $data['email'] = $emailData['email'];
            $data['url'] = Router::url('/', true);
            $data['subject'] = 'Forgot password details';
            $data['email_to'] = $emailData['email'];
            $data['user_id'] = $userData['id'];
            $data['full_name'] = $userData['full_name'];
            $data['name'] = $userData['full_name'];
            $data['token'] = $passwordToken;
            $data['password_reset_link'] = Router::url('/'.$prefix.'/users/reset-password/'.$passwordToken, true);
            $data['expired'] = date('Y-m-d H:i:s', strtotime('+24 hours'));
                        
            $data['senderTitle'] = ADMIN_SENDER_NAME;
            $data['senderEmail'] = ADMIN_SENDER_EMAIL;
            $data['senderPhone'] = ADMIN_SENDER_PHONE;
            $data['senderAddress'] = ADMIN_SENDER_ADDRESS;
            $emailArr[] = $emailData['email'];
            $data['emailUsersList'] = $emailArr;
            //pr($data);
            $userId = $this->Auth->user('id');
            if(isset($userId)){
                $userId = $userId;
            }else{
                $userId = $userData['id'];
            }
            //pr($userId);exit;
            $emailQueueArr['email_type'] = 'Forgot_Password';
            $emailQueueArr['email_data'] = json_encode($data);
            $emailQueueArr['status'] = 0;
            $emailQueueArr['updated_by'] = $userId;
            $emailQueue = $this->EmailQueues->newEntity();
            $emailQueue = $this->EmailQueues->patchEntity($emailQueue, $emailQueueArr);
            if ($this->EmailQueues->save($emailQueue)) {
                return 1;
            } else {
                return 0;
            }
            // Setting email config
            /*try{
                $email = new Email();
                $email->transport('smtp');
                $email->template('forgotPassword');
                $email->emailFormat('html');
                // set senders information
                $email->from(['admin@tuxedoair.com' => 'Tuxedo Air']);
                // set receiver information
                $email->to($emailData['email']);
                $email->bcc(ADMIN_SENDER_EMAIL, ADMIN_SENDER_NAME);
                $email->subject($subject);
                $email->viewVars(['data' => $emailData]);
                if ($email->send()) {
                    return 1;
                } else {
                    return 0;
                }
            }catch(\Exception $e){
                $this->Flash->error(__('Unable to send email. Please contact admin.'));
            }*/
        } else {
            $this->Flash->error(__('Invalid request. Please try again.'));
        }
    }

    /**
     * generateToken method
     * This function is used to generate reset password token.
     *
     * @return token.
     */
    public function generateToken($user_id=null) {
        if(empty($user_id)){
            $this->Flash->error(__('Invalid user data. Please try again.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        //$resetModel = TableRegistry::get('ResetPasswords');
        do {
            unset($exists);
            $token = bin2hex(openssl_random_pseudo_bytes(16));            
            $exists = $this->ResetPasswords->find('all', ['conditions' => ['token' => $token]])->first();
        }while(count($exists) != 0);
            
        return $token;
    }

    public function checkIsPilot($url=null){
        $url_arr=explode("/",$url);
        //If session has expired go to relavent login page
        if (strpos(end($url_arr), '?') !== false) {
            $url_arr=explode("?",end($url_arr));
        }else{
            $url_arr = $url_arr;
        }
        $part = array();
        foreach($url_arr as $key => $link)
        {
            if(empty($link))
            {
                unset($link);
            }else{
                $part[] = $link;
            }
        }
        if(in_array("pilots", $part)){
            $prefix = PILOTS_PREFIX;
        }else{
            $prefix = 'Admin';
        }
        return $prefix;
    }

    public function assignMenus()
    {
        $userMenuItems = $this->UserMenuItems->newEntity();
        $userAircraft = $this->UserAircrafts->newEntity();
        //$menuItemsModel = TableRegistry::get('MenuItems');
        $allMenu = $this->MenuItems->find('all')->where(['MenuItems.parent_id' => 0, 'MenuItems.deleted is null'])->order(['MenuItems.order_by ASC'])->toArray();
        //pr($allMenu);die;
        
        if ($this->request->is('post')) {
            $insertData = array();
            $postData = $this->request->getData();
            //pr($postData);die;
            /*if(!empty($postData['aircraft_ids'])) {
                $postData['aircraft_ids'] = implode(",",$postData['aircraft_ids']);
            }*/
            //pr($postData);die;
            foreach ($postData['menu_item_id'] as $key => $value) {
                if($value['menu_item_id'] != 0) {
                    $parentId = $this->User->getParentMenuId($value['menu_item_id']);
                    $insertData[$key]['user_id'] = $postData['user_id'];
                    $insertData[$key]['menu_item_id'] = $value['menu_item_id'];
                    $insertData[$key]['parent_id'] = $parentId;
                    //Selected aircraft ids
                    //$insertData[$key]['aircraft_ids'] = $postData['aircraft_ids'];
                    
                    if(isset($value['action_add']) && $value['action_add'] != 0) {
                        $insertData[$key]['action_add'] = $value['action_add'];
                    } else {
                        $insertData[$key]['action_add'] = '0';
                    }

                    if(isset($value['action_edit']) && $value['action_edit'] != 0) {
                        $insertData[$key]['action_edit'] = $value['action_edit'];
                    } else {
                        $insertData[$key]['action_edit'] = '0';
                    }

                    if(isset($value['action_view']) && $value['action_view'] != 0) {
                        $insertData[$key]['action_view'] = $value['action_view'];
                    } else {
                        $insertData[$key]['action_view'] = '0';
                    }

                    if(isset($value['action_delete']) && $value['action_delete'] != 0) {
                        $insertData[$key]['action_delete'] = $value['action_delete'];
                    } else {
                        $insertData[$key]['action_delete'] = '0';
                    }
                    $insertData[$key]['updated_by'] = $postData['updated_by'];
                }
            }
            //$userMenuItemsModel = TableRegistry::get('UserMenuItems');
            $entities = $this->UserMenuItems->newEntities($insertData);
            //pr($entities);die;
            $this->UserMenuItems->deleteAll(['user_id'=>$postData['user_id']]);
            if ($this->UserMenuItems->saveMany($entities)) {

                //Save user selected aircrafts
                $this->UserAircrafts->deleteAll(['user_id'=>$postData['user_id']]);
                $userAirData['user_id'] = $postData['user_id'];
                $userAirData['aircraft_ids'] = serialize($postData['aircraft_ids']);
                $userAircraft = $this->UserAircrafts->patchEntity($userAircraft, $userAirData);
                $this->UserAircrafts->save($userAircraft);

                $this->Flash->success(__('Menu items assigned successfully.'));
                return $this->redirect(['controller'=>'Users','action' => 'assignMenus']);
            } else {
                $this->Flash->error(__('Somthing went worng. Please, try again.'));
            }
        }
        
        foreach ($allMenu as $key => $value) {
            $menuItemsss = [];
            $chieldMenu = $this->getChielsMenu($value['id']);
            if($chieldMenu['count'] > '0') {
                unset($chieldMenu['count']);
                foreach ($chieldMenu as $key1 => $value1) {
                    $lastMenu = $this->getChielsMenu($key1);
                    if($lastMenu['count'] > '0') {
                        unset($lastMenu['count']);
                        $tempMenuItems = [];
                        foreach ($lastMenu as $key2 => $value2) {
                            $tempMenuItems[] = array($key2 => $value2);
                        }                        
                        $menuItemsss[$key1] = array($value1 => $tempMenuItems);
                    } else {
                        unset($lastMenu['count']);
                        $menuItemsss[$key1] = $value1;
                    }
                    $menuItems[$value['id']] = array($value['name'] => $menuItemsss);
                }                
            } else {
                unset($chieldMenu['count']);
                $menuItems[$value['id']] = $value['name'];
            }
        }
        //$adminUsersModel = TableRegistry::get('Users');
        $roles = '1,'.PERMISSION_ROLE_ID;
        
        $allAdmins = $this->Users->find('list', array (
                                            'keyField' => 'id',
                                            'valueField' => 'full_name'
                                        ))
                                    ->where(['Users.role_id IN ('.$roles.')', 'Users.id !=' => 1, 'Users.suspended' => 0])
                                    ->toArray();
        
        $this->set(compact('userMenuItems', 'menuItems', 'allAdmins'));
    }

    public function getAccessList()
    {
        if ($this->request->is('post')) {
            $this->layout = 'ajax';
            $insertData = array();
            $userMenuItems = $this->UserMenuItems->newEntity();
            $postData = $this->request->getData();
            //pr($postData);die;
            //$userMenuItemsModel = TableRegistry::get('UserMenuItems');
            $userMenuItems = $this->UserMenuItems->find('all')->where(['UserMenuItems.user_id' => $postData['user_id']])->toArray();
            //pr($userMenuItems);die; 
            $airArr = $this->Plane->getPlanes();
            
            if(!empty($userMenuItems)) {
                //$menuItemsModel = TableRegistry::get('MenuItems');
                $allMenu = $this->MenuItems->find('all')->where(['MenuItems.parent_id' => 0, 'MenuItems.deleted is null'])->order(['MenuItems.order_by ASC'])->toArray();
                //pr($allMenu);die;
                foreach ($allMenu as $key => $value) {
                    $menuItemsss = [];
                    $chieldMenu = $this->getChielsMenu($value['id']);
                    //pr($chieldMenu);
                    if($chieldMenu['count'] > '0') {
                        unset($chieldMenu['count']);
                        foreach ($chieldMenu as $key1 => $value1) {
                            $lastMenu = $this->getChielsMenu($key1);
                            
                            if($lastMenu['count'] > '0') {
                                unset($lastMenu['count']);
                                $tempMenuItems = [];
                                foreach ($lastMenu as $key2 => $value2) {
                                    $tempMenuItems[] = array($key2 => $value2);
                                }                        
                                $menuItemsss[$key1] = array($value1 => $tempMenuItems);
                            } else {
                                unset($lastMenu['count']);
                                $menuItemsss[$key1] = $value1;
                            }
                            $menuItems[$value['id']] = array($value['name'] => $menuItemsss);
                        }                
                    } else {
                        unset($chieldMenu['count']);
                        $menuItems[$value['id']] = $value['name'];
                    }
                }
                //pr($userMenuItems);die;
                $selAirIds = [];
                foreach ($userMenuItems as $key6 => $value6) {
                    $temp[$value6['menu_item_id']] = array('action_add' => $value6['action_add'], 'action_edit' => $value6['action_edit'], 'action_view' => $value6['action_view'], 'action_delete' => $value6['action_delete']);
                }

                //User selected aircraft ids
                $userAirRes = $this->UserAircrafts->find('all')->where(['UserAircrafts.user_id'=>$postData['user_id']])->enableHydration(false)->first();
                //pr($userAirRes);die;
                $selAirIds = unserialize($userAirRes['aircraft_ids']);
                $this->set(compact('menuItems', 'temp', 'airArr', 'selAirIds'));
            } else {
                echo '';die;
            }
        }
    }

    public function getChielsMenu($parentID=null)
    {
        $allMenu = $this->MenuItems->find('all')->where(['MenuItems.parent_id' => $parentID, 'MenuItems.deleted is null'])->order(['MenuItems.order_by ASC'])->toArray();
        if(empty($allMenu)){
            $chieldMenu['count']=0;
            $chieldMenu['chieldItem']=[];
        }else{
            $chieldMenu['count']=count($allMenu);
            foreach ($allMenu as $key => $value) {
                $chieldMenu[$value['id']]=$value['name'];
            }
        }
        return $chieldMenu;
    }
    
}

