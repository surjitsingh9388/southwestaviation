<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
/**
 * Pilots Controller
 *
 * @property \App\Model\Table\PilotsTable $Pilots
 *
 * @method \App\Model\Entity\pilot[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PilotsController extends AppController
{
    private $dutyAssignObj;
    private $pilotCertObj;
    private $pilotCheckObj;
    private $pilotTrainObj;

    public $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Address');
        $this->loadComponent('Pilot');
        $this->loadComponent('User');

        $this->loadModel('UserMenuItems');
        $this->loadModel('MenuItems');
        $this->loadModel('Roles');
        $this->loadModel('Users');

        $this->dutyAssignObj = TableRegistry::get('DutyAssignments');
        $this->pilotCertObj  = TableRegistry::get('PilotCertificates');
        $this->pilotCheckObj = TableRegistry::get('PilotCheckings');
        $this->pilotTrainObj = TableRegistry::get('PilotTrainings');
    }
    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Pilots List', $actionStatus))
            {
                $actionItems = $actionStatus['Pilots List'];
            }
            $this->set(compact('actionItems'));
        }
    }

    public function search()
    {
        $query = array();
        $query['count']  = "SELECT count(Pilots.`id`) AS count FROM `pilots` Pilots LEFT JOIN `users` Users ON Pilots.`user_id` = Users.`id` LEFT JOIN `roles` Roles ON Users.`role_id` = Roles.`id` WHERE 1=1";

        $query['detail'] = "SELECT Pilots.`id`, Pilots.`certificate_number`, Users.`phone_ext`, Users.`phone`, Users.`full_name`, Users.`email`, Roles.`role_name` FROM `pilots` Pilots LEFT JOIN `users` Users ON Pilots.`user_id` = Users.`id` LEFT JOIN `roles` Roles ON Users.`role_id` = Roles.`id` WHERE 1=1";
        
        return $query;
    }

    public function ajaxManagePilotsSearch()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Pilots List', $actionStatus))
            {
                $actionItems = $actionStatus['Pilots List'];
            }
        }

        $this->autoRender = false;
        $this->layout = 'ajax';
        $requestData = $this->request->data;
        $query = $this->search();

        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ) {
            $search = $requestData['search']['value'];
            $cond.=" AND ( Pilots.id LIKE '%".$search."%' OR Pilots.certificate_number LIKE '%".$search."%' OR Users.full_name LIKE '%".$search."%' OR Users.phone_ext LIKE '%".$search."%' OR Users.phone LIKE '%".$search."%' OR  Users.email LIKE '%".$search."%' OR Roles.role_name LIKE '%".$search."%' )";
        }

        $columns = array(
            0 => 'Pilots.id',
            1 => 'Users.full_name',
            2 => 'Users.email',
            3 => 'Pilots.certificate_number',
            4 => 'Users.phone',
            5 => 'Roles.role_name'
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
        
        $data = array();
        $view = $edit = $delete = '';
        foreach ( $results as $row) {
            $nestedData= [];
            $nestedData[] = $j++;
            $nestedData[] = $row["full_name"];
            $nestedData[] = $row["email"];
            $nestedData[] = (!empty($row["certificate_number"]) && $row["certificate_number"] != '000') ? $row["certificate_number"] : '';
            $nestedData[] = $row["phone_ext"].'- '.$row["phone"];
            $nestedData[] = $row["role_name"];          
           
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $this->Auth->user('id') == 1) {
                $edit = ' <a href="pilots/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $this->Auth->user('id') == 1) {
                $delete = ' <a data-pilot_id="'.$row['id'].'" data-url="pilots/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete this pilot?"><i class="fa fa-trash-o"></i> Delete</a>';
            }
            $nestedData[] = $edit.$delete;
            $data[] = $nestedData;
            $i++;
        }

        $roles = array(
            "draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       
        echo json_encode($roles);die;  
    }

    //Profile details
    public function profile()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Pilot Profile', $actionStatus))
            {
                $actionItems = $actionStatus['Pilot Profile'];
            }
        }

        $userId = $this->Auth->user('id');
        $pilot = $this->Pilots->find()
                    ->where(['Pilots.user_id'=>$userId])
                    ->contain([
                        'Users'=>[
                            'Roles',
                            'Addresses'=>[
                                'strategy' => 'select',
                                'queryBuilder' => function ($q) {
                                    return $q->order(['Addresses.id' =>'DESC'])->limit(1);
                                },
                                'Countries', 
                                'States', 
                                'Cities'                                    
                            ]
                        ]
                    ])
                    ->first();      
        $this->set(compact('actionItems', 'pilot'));
    }

    /**
     * View method
     *
     * @param string|null $id pilot id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $pilot = $this->Pilots->get($id);
        $this->set('pilot', $pilot);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {        
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Pilots List', $actionStatus))
            {
                $actionItems = $actionStatus['Pilots List'];
            }
        }

        $pilot = $this->Pilots->newEntity();
        if ($this->request->is('post')) {

            $postData = $this->request->getData();

            //Save user
            $userId = $this->saveUser($postData);

            if(!empty($userId)) {
                $postData['user_id'] = $userId;

                //Save pilot and associated data
                $res = $this->Pilot->savePilotAndAssociatedData($pilot, $postData, 'add');
                if($res) {
                    $this->Flash->success(__('The Pilot has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->Flash->error(__('The Pilot could not be saved. Please, try again.'));
        }
        $roles = $this->User->getPilotRoles();
        $countries = $this->Address->getCountryList();
        $pilotComp = $this->Pilot;
        $this->set(compact('actionItems', 'pilot', 'roles', 'countries', 'pilotComp'));
    }

    /**
     * Edit method
     *
     * @param string|null $id pilot id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Pilots List', $actionStatus))
            {
                $actionItems = $actionStatus['Pilots List'];
            }
        }
        $pilot = $this->Pilots->get($id, [
                    'contain' => [
                        'Users'=>[
                            'Roles',
                            'Addresses'=>[
                                'strategy' => 'select',
                                'queryBuilder' => function ($q) {
                                    return $q->order(['Addresses.id' =>'DESC'])->limit(1);
                                }                                    
                            ]
                        ],
                        'DutyAssignments'=>[
                            'strategy' => 'select',
                            'queryBuilder' => function ($q) {
                                return $q->order(['DutyAssignments.id' =>'DESC'])->limit(1);
                            }
                        ], 
                        'PilotCertificates'=>[
                            'strategy' => 'select',
                            'queryBuilder' => function ($q) {
                                return $q->order(['PilotCertificates.id' =>'DESC'])->limit(1);
                            }
                        ], 
                        'PilotCheckings'=>[
                            'strategy' => 'select',
                            'queryBuilder' => function ($q) {
                                return $q->order(['PilotCheckings.id' =>'DESC'])->limit(1);
                            }
                        ], 
                        'PilotTrainings'=>[
                            'strategy' => 'select',
                            'queryBuilder' => function ($q) {
                                return $q->order(['PilotTrainings.id' =>'DESC'])->limit(1);
                            }
                        ]
                    ]
                ]);

        $user['sessionUser'] = $this->Auth->user('id');
        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();

            //Update user
            $userRes = $this->updateUser($postData);
            if(!empty($userRes['status'])) {
                //Save pilot and associated data
                $res = $this->Pilot->savePilotAndAssociatedData($pilot, $postData, 'edit');
                if($res) {
                    $this->Flash->success(__('The Pilot has been updated.'));
                    return $this->redirect(['action' => 'index']);
                }
            } else {
                $this->Flash->error(__($userRes['message']));
            }
        }
        
        $roles = $countries = $states = $cities = array();
        $roles = $this->User->getPilotRoles();
        
        $countries = $this->Address->getCountryList();
        if (isset($pilot->user->addresses[0]->country_id)) {
            $states = $this->Address->getStateListByCountryId($pilot->user->addresses[0]->country_id);
        }
        if (isset($pilot->user->addresses[0]->country_id)) {
            $cities = $this->Address->getCityListByStateId($pilot->user->addresses[0]->state_id);
        }
        $pilotComp = $this->Pilot;
        $this->set(compact('actionItems', 'pilot', 'roles', 'countries', 'states', 'cities', 'pilotComp'));
    }

    /**
     * Delete method
     *
     * @param string|null $id pilot id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);

        //Delete Duty Assignment data
        $dutyAssign = array('DutyAssignments.pilot_id' => $id);
        $this->dutyAssignObj->deleteAll($dutyAssign,false);

        //Delete Pilot Certificates data
        $pilotCertificate = array('PilotCertificates.pilot_id' => $id);
        $this->pilotCertObj->deleteAll($pilotCertificate,false);

        //Delete checking data
        $pilotCheck = array('PilotCheckings.pilot_id' => $id);
        $this->pilotCheckObj->deleteAll($pilotCheck,false);

        //Delete training data
        $pilotTrain = array('PilotTrainings.pilot_id' => $id);
        $this->pilotTrainObj->deleteAll($pilotTrain,false);

        $pilot = $this->Pilots->get($id);
        try {
            if ($this->Pilots->delete($pilot)) {
                $this->Flash->success(__('The Pilot has been deleted.'));
            } else {
                $this->Flash->error(__('The Pilot could not be deleted. Please, try again.'));
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
            $exists = $this->Pilots->exists(['email' => $email]);
            if ($exists) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    /**
     * IspilotExist method
     * This function is used to check is pilot name already exist.
     * 
     * @return boolean true/false
     */
    public function ispilotExist() {
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $pilotCode = $this->request->getData('certificate_number');

            $exists = $this->Pilots->exists(['certificate_number' => trim($pilotCode)]);
            if ($exists) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    //Duty Assignments popup
    public function specifyInfo()
    {
        $req = $this->request->data;
        $reports = $this->dutyAssignObj->find()
                    ->where(['DutyAssignments.id'=>$req['daId'], 'DutyAssignments.pilot_id'=>$req['pilotId']])
                    ->enableHydration(false)->first();

        $tHtml = '';
        if(!empty($reports)) {
            $daType   = $req['datype'];
            $readonly = 'readonly="readonly"';

            $checked1 = '';
            $checked2 = '';
            if($reports[$daType.'_type1'] == 'true'){ $checked1='checked'; }
            if($reports[$daType.'_type2'] == 'true'){ $checked2='checked'; }

            $dateAssign1   = !empty($reports[$daType.'_date_assigned1']) ? date('m/d/Y', strtotime($reports[$daType.'_date_assigned1'])) : '';
            $dateUnAssign1 = !empty($reports[$daType.'_date_unassigned1']) ? date('m/d/Y', strtotime($reports[$daType.'_date_unassigned1'])) : '';
            $dateAssign2   = !empty($reports[$daType.'_date_assigned2']) ? date('m/d/Y', strtotime($reports[$daType.'_date_assigned2'])) : '';
            $dateUnAssign2 = !empty($reports[$daType.'_date_unassigned2']) ? date('m/d/Y', strtotime($reports[$daType.'_date_unassigned2'])) : '';
            $tHtml = '<table class="table table-borderless">
                            <tr>
                                <th width="5%">--</th>
                                <th width="10%">Type Designation</th>
                                <th width="15%">Date Assigned</th>
                                <th width="15%">Date Unassigned</th>                              
                            </tr>
                        <tbody>
                            <tr>
                                <td><input type="checkbox" name="check_1" class="typeCheck1" '.$checked1.'/></td>
                                <td><input type="text" name="designation_1" class="form-control designation1" value="BE20" '.$readonly.'></td>
                                <td> 
                                    <input type="text" name="date_assigned_1" value="'.$dateAssign1.'" class="form-control date_assigned1 datePicker">
                                </td>
                                <td>
                                    <input type="text" name="date_unassigned_1" value="'.$dateUnAssign1.'" class="form-control date_unassigned1 datePicker">
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="check_2" class="typeCheck2" '.$checked2.'/></td>
                                <td><input type="text" name="designation_2" class="form-control designation2" value="P180" '.$readonly.'></td>
                                <td> 
                                    <input type="text" name="date_assigned_2" value="'.$dateAssign2.'" class="form-control date_assigned2 datePicker">
                                </td>
                                <td>
                                    <input type="text" name="date_unassigned_2" value="'.$dateUnAssign2.'" class="form-control date_unassigned2 datePicker">
                                </td>
                            </tr>
                        </tbody>
                    </table>';

            $result = array('status'=>'success', 'data'=>$tHtml);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>$tHtml);
            echo json_encode($result);die;
        }
    }

    /**
     * Crew Currency
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */    
    public function crewCurrency()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Pilot Details', $actionStatus))
            {
                $actionItems = $actionStatus['Pilot Details'];
            }
        }

        $pilotId = !empty($_GET['pilotId']) ? $_GET['pilotId'] : '';
        if(empty($pilotId)) {
            $res = $this->Pilots->find()->select('id')->first();

            if(!empty($res)) {
                $pilotId = $res->id;
            }
        }

        $pilot = array();
        if(!empty($pilotId)) {
            $pilot = $this->Pilots->get($pilotId, [
                        'contain' => [
                            'Users'=>[
                                'Roles',
                                'Addresses'=>[
                                    'strategy' => 'select',
                                    'queryBuilder' => function ($q) {
                                        return $q->order(['Addresses.id' =>'DESC'])->limit(1);
                                    },
                                    'Countries', 
                                    'States', 
                                    'Cities'                                    
                                ]
                            ],
                            'DutyAssignments'=>[
                                'strategy' => 'select',
                                'queryBuilder' => function ($q) {
                                    return $q->order(['DutyAssignments.id' =>'DESC'])->limit(1);
                                }
                            ], 
                            'PilotCertificates'=>[
                                'strategy' => 'select',
                                'queryBuilder' => function ($q) {
                                    return $q->order(['PilotCertificates.id' =>'DESC'])->limit(1);
                                }
                            ], 
                            'PilotCheckings'=>[
                                'strategy' => 'select',
                                'queryBuilder' => function ($q) {
                                    return $q->order(['PilotCheckings.id' =>'DESC'])->limit(1);
                                }
                            ], 
                            'PilotTrainings'=>[
                                'strategy' => 'select',
                                'queryBuilder' => function ($q) {
                                    return $q->order(['PilotTrainings.id' =>'DESC'])->limit(1);
                                }
                            ]
                        ]
                    ]);

            //pr($pilot);die;
        }
        
        $user['sessionUser'] = $this->Auth->user('id');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $postData = $this->request->getData();
            //Update user
            $userId = $this->updateUser($postData);

            if(!empty($userId)) {
                //Save pilot and associated data
                $res = $this->Pilot->savePilotAndAssociatedData($pilot, $postData, 'crew');
                if($res) {
                    $this->Flash->success(__('Pilot details has been updated.'));
                    return $this->redirect($this->referer());
                }
            }
            $this->Flash->error(__('The Pilot could not be updated. Please, try again.'));
        }
        
        $pilotComp = $this->Pilot;
        $this->set(compact('actionItems', 'pilot', 'pilotComp'));
    }

    //When new pilot added then new user will create and save details in users table 
    public function saveUser($postData)
    {
        if($postData) {
            $user_id = '';

            $user = $this->Users->newEntity();

            $insertData = array();
            $role = $this->Roles->find('all')->where(['id' => $postData['role_id']])->first()->toArray();
            $postData['full_name'] = trim($postData['first_name']).' '.trim($postData['middle_name']).' '.trim($postData['last_name']);
            $postData['email'] = trim($postData['email']);
            
            $user = $this->Users->patchEntity($user, $postData, array('associated' => array('Addresses')));
         
            if ($this->Users->save($user)) {
                $user_id = $user->id;

                //Assign menu items to Operation and Account Roles Users
                $permissionRoles = PERMISSION_ROLE_ID;

                if (!empty($permissionRoles)) {
                    $permissionRoles = explode(',', $permissionRoles);
                    if(in_array($role['id'], $permissionRoles)) {
                        $roleMenuItems = $this->UserMenuItems->find('all')->where(['UserMenuItems.role_id' => $role['id']])->toArray();
                        if(!empty($roleMenuItems)) {
                            $result = $this->Users->save($user);
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

                            $user_id = $result->id;
                        }
                    }
                }
            }
            return $user_id;
        }
    }

    //When pilot updated then associated user will updated
    public function updateUser($postData)
    {
        if($postData) {
            $insertData = array();   
            $postData['email'] = trim($postData['email']);
            $postData['full_name'] = trim($postData['first_name']).' '.trim($postData['middle_name']).' '.trim($postData['last_name']);
            $new_password = isset($postData['new_password']) ? $postData['new_password'] : '';
            $confirm_password = isset($postData['confirm_password']) ? $postData['confirm_password'] : '';
            
            // Check if new password not empty, set the new password
            if (!empty($new_password) && !empty($confirm_password)) {
                if ($new_password == $confirm_password) {
                    $postData['password'] = $new_password;
                } 
            }

            $user = $this->Users->get($postData['user_id'], [
                'contain' => ['Roles','Addresses']
            ]);

            //Update user details
            $user = $this->Users->patchEntity($user, $postData, 
                    array('associated' => array('Addresses')));

            $userRes = [];
            if ($this->Users->save($user)) {
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

                $userRes['status'] = true;
                $userRes['message'] = 'User updated successfully.';              
                return $userRes;
            } else {
                $error = $user->errors();
                $userRes['status'] = false;
                if (isset($error['email']['unique'])) {
                    $userRes['message'] = $error['email']['unique'];
                } else {
                    $userRes['message'] = 'The pilot could not be updated. Please, try again.';
                }
                return $userRes;                
            }
        }
    }

    //Dynamically update nextdue
    public function getNextDue()
    {
        $params = $this->request->getData();
        if(!empty($params)) {
            $res = $this->Pilot->getMNextDue($params['baseMonth'], $params['frequency'], $params['lastCompleted']);
            if(!empty($res)) {
                $statusColor = $this->Pilot->getStatusColor($res, $params['lastCompleted']);
                $result = array('status'=>'success', 'data'=>$res, 'scolor'=>$statusColor);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
        } else {
            $result = array('status'=>'failure', 'data'=>'');
            echo json_encode($result);die;
        }
    }

    //Dynamically update certificate nextdue
    public function certNextDue()
    {
        $params = $this->request->getData();
        if(!empty($params)) {
            $res = '';
            if(!empty($params['certType']) && $params['certType'] == 'years') {
                $res = $this->Pilot->getYNextDue($params['frequency'], $params['lastCompleted']);
            } elseif (!empty($params['certType']) && $params['certType'] == 'months') {
                $res = $this->Pilot->getMMNextDue($params['frequency'], $params['lastCompleted']);
            } elseif (!empty($params['certType']) && $params['certType'] == 'days') {
                $res = $this->Pilot->getDNextDue($params['frequency'], $params['lastCompleted']);
            }

            if(!empty($res)) {
                $statusColor = $this->Pilot->getStatusColor($res, $params['lastCompleted']);
                $result = array('status'=>'success', 'data'=>$res, 'scolor'=>$statusColor);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
        } else {
            $result = array('status'=>'failure', 'data'=>'');
            echo json_encode($result);die;
        }
    }

    //Pilot duty
    public function crewDuty()
    {
        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Pilot Duty', $actionStatus))
            {
                $actionItems = $actionStatus['Pilot Duty'];
            }
        }

        $pilotId = !empty($_GET['pilotId']) ? $_GET['pilotId'] : '';
        if(empty($pilotId)) {
            $res = $this->Pilots->find()->select('id')->first();

            if(!empty($res)) {
                $pilotId = $res->id;
            }
        }

        //Current month data
        if(!empty($_GET['selMonth']) && !empty($_GET['selYear'])) {
            $currentMon = $_GET['selMonth'];
            $currentYr = $_GET['selYear'];
            $numericMon = date('m', strtotime($currentYr.'-'.$currentMon));
        } else {
            $dateComp = getdate();
            $numericMon = ($dateComp['mon'] < 10) ? "0".$dateComp['mon'] : $dateComp['mon'];
            $currentMon = strtolower($dateComp['month']);
            $currentYr = $dateComp['year'];
        }
        
        $startDMonth = date($currentYr.'-'.$numericMon.'-01');
        $endDMonth  = date($currentYr.'-'.$numericMon.'-t');

        //Duty time conditions
        $dutyTimeCond = function ($q) use ($pilotId, $startDMonth, $endDMonth) {
                return $q->where(['DutyTimes.pilot_id'=>$pilotId, 'DutyTimes.selected_date >='=>$startDMonth, 'DutyTimes.selected_date <='=>$endDMonth])
                        ->select(['id', 'pilot_id', 'duty_start_hour', 'duty_start_minute', 'duty_stop_hour', 'duty_stop_minute', 'required_rest', 'classification', 'selected_date']);
            };

        //Days off conditions
        $daysOffCond = function ($q) use ($pilotId, $startDMonth, $endDMonth) {
                return $q->where(['DaysOff.pilot_id'=>$pilotId, 'DaysOff.selected_date >='=>$startDMonth, 'DaysOff.selected_date <='=>$endDMonth])
                        ->select(['id', 'pilot_id', 'off_start_hour', 'off_start_minute', 'selected_date']);
            };

        //Flight leg conditions
        $flightLegCond = function ($q) use ($pilotId, $startDMonth, $endDMonth) {
                return $q->where(['FlightLegDetails.pilot_id'=>$pilotId, 'FlightLegDetails.selected_date >='=>$startDMonth, 'FlightLegDetails.selected_date <='=>$endDMonth])
                    ->select(['id', 'pilot_id', 'start_hour', 'start_minute', 'leg_hour', 'leg_minute', 'night_flt_hour', 'night_flt_minute', 'ifr_flight_hour', 'ifr_flight_minute', 'approaches', 'landings', 'duty_position', 'part_135', 'selected_date']);
            };

        //Get records
        $records = $this->Pilots->find()
                                ->where(['Pilots.id'=>$pilotId])
                                ->contain([
                                    'DutyTimes'=>$dutyTimeCond, 
                                    'DaysOff'=>$daysOffCond, 
                                    'FlightLegDetails'=>$flightLegCond
                                ])
                                ->select(['Pilots.id', 'Pilots.user_id'])
                                ->enableHydration(false)
                                ->first();

        $result = $this->buildCalendar($pilotId, $currentYr, $currentMon, $numericMon, $records);

        $pilotComp = $this->Pilot;
        $this->set(compact('actionItems', 'pilotId', 'currentMon', 'currentYr', 'pilotComp', 'result'));
    }

    public function buildCalendar($pilotId, $currentYr, $currentMon, $numericMon, $records=null)
    {
        // Total minutes in an hour
        $minInHour = $this->Pilot->getHoursMinute();
 
        //Total hours in a day
        $hoursOfDay = $this->Pilot->getDayHours();
        
        //First day of the month
        $firstDayOfMonth = mktime(0,0,0,$numericMon,1,$currentYr);

        //Number of days does this month contain
        $numberDays = date('t',$firstDayOfMonth);

        $dateComponents = getdate($firstDayOfMonth);

        //Day of week
        $dayOfWeek = $dateComponents['wday'];

        //Create the table tag and header
        $calendar = "<table class='calendarCls' cellpadding='10' cellspacing='0' border='1'>";
        $calendar .= "<tr><td colspan='2'>&nbsp;</td><td colspan='24'>Duty and Flight Times</td></tr>";
        $calendar .= "<tr>";
        $calendar .= "<th colspan='2' class='header'>&nbsp;</th>";
        foreach($hoursOfDay as $hour) {
            $calendar .= "<th class='header' style='font-weight:normal;'>$hour</th>";
        }
        $calendar .= "<th class='header' style='font-weight:normal;'>Duty<br>Time</th>";
        $calendar .= "<th class='header' style='font-weight:normal;'>Flight<br>Time</th>";
        $calendar .= "</tr>";
        
        $currentDay = 1;
        $totalDTTime = '';
        $totalFLTime = '';
        $totalDTTimeArr = [];
        $totalFLTimeArr = [];
        while ($currentDay <= $numberDays) {
            $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
            $date = "$currentYr-$numericMon-$currentDayRel";
            $calendar .= "<tr>";
            if($currentDay == 1) {
                $calendar .= "<td rowspan='".$numberDays."' style='padding-right:20;'><span class='verticle-char'>Duty Days</span></td>";
            }
            $calendar .= "<td class='dataReportCls' data-pilot_id='$pilotId' data-selday='$currentDay' data-selmonth='$currentMon' data-selyear='$currentYr' style='cursor:pointer;'><a style='text-decoration:underline;'>$currentDay</a></td>";
            
            $t=0;
            $dtTime = '';
            $flTime = '';
            $dtPDTime = '';
            $flPDTime = '';
            $ext = 1;
            $tmp = 0;
            foreach($hoursOfDay as $hour) {
                $hourM = ($hour < 10) ? "0".$hour.":00" : $hour.":00";
                
                //$hour = ($hour < 10) ? "0".$hour : $hour;
                
                //Days off, duty times or flight_leg_details 
                if(!empty($records['days_off']) || !empty($records['duty_times']) || !empty($records['flight_leg_details'])) {
                    
                    //Get current date duty time
                    $curDateDuty = $this->Pilot->getPrevDateDuty($pilotId, $date);
                    
                    //Check if Current date off exist
                    $curDateOff = $this->Pilot->getPrevDateOff($pilotId, $date);
                    //Days off time listing
                    if(!empty($records['days_off'])) {
                        $doFlag = true;
                        foreach($records['days_off'] as $key => $value) {
                            $seldate = date('Y-m-d', strtotime($value['selected_date']));
                            $selday = date('d', strtotime($value['selected_date']));

                            if($seldate == $date || $selday == $currentDayRel) {
                                $value['off_end_hour'] = $value['off_start_hour']+24;
                                $value['off_end_minute'] = $value['off_start_minute'];
                                
                                $calendar .= "<td class='day'>";
                                if($value['off_start_hour'] <= $hour && strtotime($value['off_start_hour'].':'.$value['off_start_minute']) >= strtotime($hourM) && !empty($value['off_start_minute']) && $value['off_start_minute'] != 0) 
                                {
                                    //Days off start from mid of slot
                                    $percent = round(($value['off_start_minute'] * 100)/60, 2);
                                    $remain = 100 - $percent;

                                    $cal = '';                                                               
                                    $cal .= "<div style='background:linear-gradient(to right, #fff $percent%, #DC8273 $percent% $remain%); height:100%;'></div>";

                                } elseif($value['off_start_hour'] <= $hour && ($value['off_end_hour'] >= $hour || $value['off_end_hour'] <= $value['off_start_hour'])) {

                                    //Days off time lies on complete slot
                                    $cal = ''; 
                                    $cal .= "<div style='background:#DC8273;border-right:1px solid #DC8273;height:100%;'></div>";

                                } elseif($value['off_start_hour'] <= $hour && strtotime($value['off_end_hour'].':'.$value['off_end_minute']) > strtotime($hourM) && !empty($value['off_end_minute']) && $value['off_end_minute'] != 0) {

                                    //Days off end in mid of slot
                                    $percent = round(($value['off_end_minute'] * 100)/60, 2);
                                    $remain = 100 - $percent; 
                                    
                                    $cal = '';
                                    $cal .= "<div style='background:linear-gradient(to right, #DC8273 $percent%, #fff $percent%$remain%); height:5px;'></div>";

                                } elseif(empty($curDateDuty)) {
                                    //If hour slots are blank in a day
                                    $cal = ''; 
                                    $cal .= "<div></div>";
                                }

                                $calendar .= $cal;
                                $calendar .= "</td>";

                                $doFlag = false;
                            }
                        }

                        if($doFlag) {
                            //Days off previous day record
                            $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($date)));
                            $prevDayOffRes = $this->Pilot->getPrevDateOff($pilotId, $prevDate);
                            if(!empty($prevDayOffRes)) {
                                $prevDayOffRes['off_end_hour'] = $prevDayOffRes['off_start_hour'];
                                $prevDayOffRes['off_end_minute'] = $prevDayOffRes['off_start_minute'];
                                
                                if(0 <= $hour && $prevDayOffRes['off_end_hour'] > $hour) {
                                    //Days off time lies on complete slot
                                    $calendar .= "<td class='day'><div style='background:#DC8273;border-right:1px solid #DC8273;height:100%;'></div></td>";
                                } elseif(0 <= $hour && strtotime($prevDayOffRes['off_end_hour'].':'.$prevDayOffRes['off_end_minute']) >= strtotime($hourM) && $prevDayOffRes['off_end_minute'] !=0) {
                                    //Days off end in mid of slot
                                    $percent = round(($prevDayOffRes['off_end_minute'] * 100)/60, 2);
                                    $remain = 100 - $percent;                                                              
                                    $calendar .= "<td class='day'><div style='background:linear-gradient(to right, #DC8273 $percent%, #fff $percent% $remain%); height:100%;'></div></td>";

                                } elseif((strtotime($prevDayOffRes['off_end_hour'].':'.$prevDayOffRes['off_end_minute']) <= strtotime($hourM)) && (strtotime($prevDayOffRes['off_end_hour'].':'.$prevDayOffRes['off_end_minute']) >= strtotime('00:00')) && empty($curDateDuty)) {
                                    
                                    $calendar .= "<td class='day'></td>";
                                }
                            }
                        }
                    }

                    //Duty times listing with flight leg details listing over duty time
                    if(!empty($records['duty_times'])) {
                        
                        $dtFlag = true;
                        
                        foreach($records['duty_times'] as $key => $value) {
                            $seldate = date('Y-m-d', strtotime($value['selected_date']));
                            $selday = date('d', strtotime($value['selected_date']));

                            if($seldate == $date || $selday == $currentDayRel) {
                                $dtTime = $this->Pilot->timeDiffFL($value['duty_start_hour'].':'.$value['duty_start_minute'], $value['duty_stop_hour'].':'.$value['duty_stop_minute']);

                                if($value['duty_stop_hour'] == 0) {
                                    $value['duty_stop_hour'] = 24;
                                }

                                //Duty time start from mid of slot
                                if($value['duty_start_hour'] <= $hour && strtotime($value['duty_start_hour'].':'.$value['duty_start_minute']) >= strtotime($hourM) && !empty($value['duty_start_minute']) && $value['duty_start_minute'] != 0) 
                                {
                                    $percent = round(($value['duty_start_minute'] * 100)/60, 2);
                                    $remain = 100 - $percent;

                                    //To display classification
                                    $dtLabel = '';
                                    if($t == 0 && !empty($value['classification']) && $value['classification'] != 'flight') {
                                        $temp = explode('_', $value['classification']);
                                        if(count($temp) > 1) {
                                            $dtLabel = ucfirst($temp[0]).' '.ucfirst($temp[1]);
                                        } else {
                                            $dtLabel = ucfirst($value['classification']);
                                        }
                                    } 

                                    $calendar .= "<td class='day'>";
                                    $cal = "<div style='background:#fff; width:$percent%; height:100%;float:left;'></div><div style='background: #008000;width: $remain%;height: 5px;float:left;margin-top: 12px;'><span class='dutyTimeLabel' style='position:absolute;margin-top:-14px;color:#000;z-index:1000;'>".$dtLabel."</span></div>";
                                    
                                    //Flight leg details data
                                    if(!empty($records['flight_leg_details'])) {
                                        $flArray = [];
                                        foreach($records['flight_leg_details'] as $keyl => $valuef) {
                                            $fdate = date('Y-m-d', strtotime($valuef['selected_date']));
                                           
                                            //Add two times(Flight start time and Flight leg hours)
                                            $time1 = $valuef['start_hour'].':'.$valuef['start_minute'];
                                            $time2 = $valuef['leg_hour'].':'.$valuef['leg_minute'];
                                            $timeResult = $this->Pilot->addTwoTimes($time1, $time2);
                                            $resflHI = $timeResult['resflHI'];
                                            $resflH  = $timeResult['resflH'];
                                            $resflt  = $timeResult['resflt'];

                                            if($fdate == $date) {
                                                $addFLTime = $this->Pilot->addTimes(array($time1, $time2));
                                                if(strtotime($addFLTime) > strtotime("24:00")) {
                                                    $flArray[] = $this->Pilot->timeDiffFL($time1, "24:00");
                                                } else {
                                                    $flArray[] = $time2;
                                                }
                                            }
                                            
                                            //If Flight leg start and end in same hour slot
                                            if(($valuef['start_hour'] <= $hour && strtotime($hourM) < strtotime($resflHI) && strtotime($time2) < strtotime('01:00') && (strtotime($valuef['start_hour'].':00') == strtotime($resflH))) && $fdate == $date) {
                                                
                                                $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                $remainF = 100 - $percentF;

                                                $percentS = $percentF - $percent;

                                                $percentL = round(($resflt * 100)/60, 2);
                                                $remainL = 100 - $percentL;
                                                $remainM = $remainF - $remainL;

                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#fff; width:$percent%; height:100%;float:left;'></div><div style='background: #008000;width: $percentS%;height: 5px;float:left;margin-top: 12px;'><span class='dutyTimeLabel' style='position:absolute;margin-top:-14px;color:#000;z-index:1000;'>".$dtLabel."</span></div><div style='background:#f7f76a; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#fff; width:$percent%; height:100%;float:left;'></div><div style='background: #008000;width: $percentS%;height: 5px;float:left;margin-top: 12px;'><span class='dutyTimeLabel' style='position:absolute;margin-top:-14px;color:#000;z-index:1000;'>".$dtLabel."</span></div><div style='background:#7abadc; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                }
                                            }
                                            //If Flight leg time start from mid of the slot
                                            elseif(($valuef['start_hour'] <= $hour && strtotime($time1) > strtotime($hourM) && !empty($valuef['start_minute']) && $valuef['start_minute'] != 0) && $fdate == $date) {
                                                
                                                $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                $remainF = 100 - $percentF;
                                                $remainM = $remain - $remainF;                                                
                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#fff; width:$percent%; height:100%;float:left;'></div><div style='background:#008000; width:$remainM%; height:5px;float:left;margin-top: 12px;'><span class='dutyTimeLabel' style='position:absolute;margin-top:-14px;color:#000;z-index:1000;'>".$dtLabel."</span></div><div style='background:#f7f76a; width:$remainF%; height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#fff; width:$percent%; height:100%;float:left;'></div><div style='background:#008000; width:$remainM%; height:5px;float:left;margin-top: 12px;'><span class='dutyTimeLabel' style='position:absolute;margin-top:-14px;color:#000;z-index:1000;'>".$dtLabel."</span></div><div style='background:#7abadc; width:$remainF%; height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                }
                                            }
                                        }
                                        //Add flight leg
                                        $flTime = $this->Pilot->addTimes($flArray);
                                    }
                                    $calendar .= $cal;
                                    $calendar .= "</td>";
                                    
                                    $t++;
                                } 
                                
                                //Duty time lies on complete slot
                                elseif($value['duty_start_hour'] <= $hour && ($value['duty_stop_hour'] > $hour || $value['duty_stop_hour'] < $value['duty_start_hour'])) {

                                    //To display classification
                                    $dtLabel = '';
                                    if($t == 0 && !empty($value['classification']) && $value['classification'] != 'flight') {
                                        $temp = explode('_', $value['classification']);
                                        if(count($temp) > 1) {
                                            $dtLabel = ucfirst($temp[0]).' '.ucfirst($temp[1]);
                                        } else {
                                            $dtLabel = ucfirst($value['classification']);
                                        }
                                    }

                                    $calendar .= "<td class='day'>";
                                    $cal = "<div style='background:#008000; height:5px;'><span class='dutyTimeLabel' style='position:absolute;margin-top:-14px;color:#000;z-index: 1000;'>".$dtLabel."</span></div>";
                                    
                                    //Flight leg details data
                                    if(!empty($records['flight_leg_details'])) {
                                        $flArray = [];
                                        foreach($records['flight_leg_details'] as $keyl => $valuef) {
                                            $fdate = date('Y-m-d', strtotime($valuef['selected_date']));
                                           
                                            //Add two times(Flight start time and Flight leg hours)
                                            $time1 = $valuef['start_hour'].':'.$valuef['start_minute'];
                                            $time2 = $valuef['leg_hour'].':'.$valuef['leg_minute'];
                                            $timeResult = $this->Pilot->addTwoTimes($time1, $time2);
                                            $resflHI = $timeResult['resflHI'];
                                            $resflH  = $timeResult['resflH'];
                                            $resflt  = $timeResult['resflt'];

                                            if($fdate == $date) {
                                                $addFLTime = $this->Pilot->addTimes(array($time1, $time2));
                                                if(strtotime($addFLTime) > strtotime("24:00")) {
                                                    $flArray[] = $this->Pilot->timeDiffFL($time1, "24:00");
                                                } else {
                                                    $flArray[] = $time2;
                                                }
                                            }
                                            
                                            //If Flight leg start and end in same hour slot
                                            if(($valuef['start_hour'] <= $hour && strtotime($hourM) < strtotime($resflHI) && strtotime($time2) < strtotime('01:00') && (strtotime($valuef['start_hour'].':00') == strtotime($resflH))) && $fdate == $date) {
                                            
                                                $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                $remainF = 100 - $percentF;

                                                $percentL = round(($resflt * 100)/60, 2);
                                                $remainL = 100 - $percentL;
                                                $remainM = $remainF - $remainL;

                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background: #008000;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#f7f76a; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background: #008000;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#7abadc; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                }
                                            } 
                                            //If Flight leg time start from mid of the slot
                                            elseif(($valuef['start_hour'] <= $hour && strtotime($time1) > strtotime($hourM) && !empty($valuef['start_minute']) && $valuef['start_minute'] != 0) && $fdate == $date) {
                                                
                                                $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                $remainF = 100 - $percentF;

                                                $percentL = 0;
                                                $remainL = 0;
                                                $sm="<div style='background: #008000;width:$percentL%;height:5px;float:left;margin-top:12px;'></div>";
                                                //Check if end time(starttime+flightlength) of any flight leg is equal to starttime of next fl
                                                foreach($records['flight_leg_details'] as $keys => $vals) {
                                                    $fsdate = date('Y-m-d', strtotime($vals['selected_date']));

                                                    //Add two times(Flight start time and Flight leg hours)
                                                    $ts1 = $vals['start_hour'].':'.$vals['start_minute'];
                                                    $ts2 = $vals['leg_hour'].':'.$vals['leg_minute'];
                                                    $tResult = $this->Pilot->addTwoTimes($ts1, $ts2);
                                                    $tResflHI = $tResult['resflHI'];
                                                    $tResflH  = $tResult['resflH'];
                                                    $tResflt  = $tResult['resflt'];

                                                    //If Flight leg time end in mid of the slot
                                                    if((strtotime($valuef['start_hour'].':00') == strtotime($tResflH)) && strtotime($fdate) == strtotime($fsdate)) {
                                                        $percentL = round(($tResflt * 100)/60, 2);
                                                        $remainL = 100 - $percentL;

                                                        if(!empty($vals['part_135'])) {
                                                            $sm = "<div style='background:#f7f76a; width:$percentL%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                        } else {
                                                            $sm = "<div style='background:#7abadc; width:$percentL%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                        }
                                                    } 
                                                }

                                                $remainM = 100 - ($percentL+$remainF);
                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= $sm."<div style='background: #008000;width: $remainM%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#f7f76a; width:$remainF%; height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= $sm."<div style='background: #008000;width: $remainM%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#7abadc; width:$remainF%; height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                }
                                            } 
                                            //If Flight leg time lied on complete hour slot
                                            elseif(($valuef['start_hour'] <= $hour && strtotime($resflH) > strtotime($hourM)) && $fdate == $date) {
                                            
                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#f7f76a; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#7abadc; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                } 

                                            } 
                                            //If Flight leg time end in mid of the slot
                                            elseif(($valuef['start_hour'] <= $hour && strtotime($resflHI) > strtotime($hourM)) && $fdate == $date) {
                                                
                                                $percentF = round(($resflt * 100)/60, 2);
                                                $remainF = 100 - $percentF;

                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#f7f76a; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainF%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#7abadc; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainF%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                }
                                            }
                                        }
                                        //Add flight leg
                                        $flTime = $this->Pilot->addTimes($flArray);
                                    }
                                    $calendar .= $cal;
                                    $calendar .= "</td>";

                                    $t++;
                                } 
                                //Duty time end in mid of slot
                                elseif($value['duty_start_hour'] <= $hour && strtotime($value['duty_stop_hour'].':'.$value['duty_stop_minute']) > strtotime($hourM) && !empty($value['duty_stop_minute']) && $value['duty_stop_minute'] != 0) {

                                    $percent = round(($value['duty_stop_minute'] * 100)/60, 2);
                                    $remain = 100 - $percent;

                                    $calendar .= "<td class='day'>";
                                    $cal = "<div style='background:linear-gradient(to right, #008000 $percent%, #fff $percent%$remain%); height:5px;'></div>";
                                    //If rest time start from mid
                                    if(!empty($value['required_rest'])) {
                                        $cal = "<div style='background:#008000; width:$percent%; height:5px; float:left;'></div><div style='background:grey; width:$remain%; height:5px; float:right;'></div>";
                                    }
                                    
                                    //Flight leg details data
                                    if(!empty($records['flight_leg_details'])) {
                                        $flArray = [];
                                        foreach($records['flight_leg_details'] as $keyl => $valuef) {
                                            $fdate = date('Y-m-d', strtotime($valuef['selected_date']));

                                            //Add two times(Flight start time and Flight leg hours)
                                            $time1 = $valuef['start_hour'].':'.$valuef['start_minute'];
                                            $time2 = $valuef['leg_hour'].':'.$valuef['leg_minute'];
                                            $timeResult = $this->Pilot->addTwoTimes($time1, $time2);
                                            $resflHI = $timeResult['resflHI'];
                                            $resflH  = $timeResult['resflH'];
                                            $resflt  = $timeResult['resflt'];

                                            if($fdate == $date) {
                                                $addFLTime = $this->Pilot->addTimes(array($time1, $time2));
                                                if(strtotime($addFLTime) > strtotime("24:00")) {
                                                    $flArray[] = $this->Pilot->timeDiffFL($time1, "24:00");
                                                } else {
                                                    $flArray[] = $time2;
                                                }
                                            }
                                                                                            
                                            //If Flight leg start and end in same hour slot
                                            if(($valuef['start_hour'] <= $hour && strtotime($hourM) < strtotime($resflHI) && strtotime($time2) < strtotime('01:00') && (strtotime($valuef['start_hour'].':00') == strtotime($resflH))) && $fdate == $date) {
                                                
                                                $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                $remainF = 100 - $percentF;

                                                $percentL = round(($resflt * 100)/60, 2);
                                                $remainL = 100 - $percentL;
                                                $remainM = $remainF - $remainL;

                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background: #008000;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#f7f76a; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background: #008000;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#7abadc; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                }
                                            } 
                                            //If Flight leg time end in mid of the slot
                                            elseif(($valuef['start_hour'] <= $hour && strtotime($resflHI) > strtotime($hourM)) && $fdate == $date) {

                                                $percentF = round(($resflt * 100)/60, 2);
                                                $remainF = 100 - $percentF;
                                                $rPercent = $percent - $percentF;

                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#f7f76a; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $rPercent%;height: 5px;float:left;margin-top: 12px;'></div><div style='background: #fff;width: $remain%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#7abadc; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $rPercent%;height: 5px;float:left;margin-top: 12px;'></div><div style='background: #fff;width: $remain%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                }

                                                //If flight leg end in mid and rest time start from mid
                                                if(!empty($value['required_rest'])) {
                                                    if(!empty($valuef['part_135'])) {
                                                        $cal = '';
                                                        $cal .= "<div style='background:#f7f76a; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $rPercent%;height: 5px;float:left;margin-top: 12px;'></div><div style='background: grey;width: $remain%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                    } else {
                                                        $cal = '';
                                                        $cal .= "<div style='background:#7abadc; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $rPercent%;height: 5px;float:left;margin-top: 12px;'></div><div style='background: grey;width: $remain%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                    }
                                                }
                                            }
                                        }
                                        //Add flight leg
                                        $flTime = $this->Pilot->addTimes($flArray);
                                    }
                                    $calendar .= $cal;
                                    $calendar .= "</td>";
                                } 
                                //Rest time display
                                else {
                                    //Add times as numeric value
                                    $totalRH = $this->Pilot->addTimes(array($value['duty_stop_hour'].':'.$value['duty_stop_minute'], $value['required_rest'].':00'));
                                    $timeResult = $this->Pilot->addTwoTimes($value['duty_stop_hour'].':'.$value['duty_stop_minute'], $value['required_rest'].':00');
                                    $nextDHI = $timeResult['resflHI'];
                                    $nextDH = $timeResult['resflH'];
                                    $nextDI = $timeResult['resflt'];

                                    //Display rest time
                                    if($value['required_rest'] > 0 && strtotime($value['duty_stop_hour'].':'.$value['duty_stop_minute']) <= strtotime($hourM) && $hour < $totalRH && $value['duty_start_hour'] <= $value['duty_stop_hour']) {
                                        
                                        //Lies in complete slot
                                        $calendar .= "<td class='day'><div style='background:grey; height:5px;'></div></td>";
                                    } 
                                    //Rest time end in mid of slot
                                    elseif($value['required_rest'] > 0 && strtotime($nextDHI) > strtotime($hourM) && strtotime($value['duty_stop_hour'].':'.$value['duty_stop_minute']) <= strtotime($hourM) && $value['duty_start_hour'] <= $value['duty_stop_hour']) {
                                        
                                        if($nextDH > $hour) {
                                            $calendar .= "<td class='day'><div style='background:grey; height:5px;'></div></td>";
                                        } 

                                        if ($nextDH <= $hour && strtotime($nextDHI) > strtotime($hourM) && $nextDI != 0) {
                                            $percent = round(($nextDI * 100)/60, 2);
                                            $remain = 100 - $percent; 
                                            $calendar .= "<td class='day'><div style='background:linear-gradient(to right, grey $percent%, #fff $percent%$remain%); height:5px;'></div></td>";
                                        }
                                    } /*elseif($value['required_rest'] == 0 || $value['required_rest'] == "") {
                                        $calendar .= "<td class='day'>aa</td>";
                                    //If any type of hour slots are blank in a day
                                    }*/ else {
                                        $curDateDutyTime = $this->Pilot->getCurrDateDutytime($pilotId, $date);
                                        //pr(count($curDateDutyTime));die;
                                        //echo $ext."<br>";
                                        if(($ext == $tmp && count($curDateDutyTime) >= 2) || (empty($curDateDutyTime) || (count($curDateDutyTime) == 1))) {

                                            //echo $ext.' - '.$tmp."<br>";
                                            //$cal = "<td class='day'></td>";
                                            $calendar .= "<td class='day'>";
                                            if(!empty($prevDayOffRes) && (0 <= $hour && $prevDayOffRes['off_end_hour'] > $hour) && $prevDayOffRes['off_start_minute'] == 0) {
                                                $cal = "";
                                            } elseif(!empty($prevDayOffRes) && (0 <= $hour && $prevDayOffRes['off_end_hour'] >= $hour) && $prevDayOffRes['off_start_minute'] != 0) {
                                                $cal = "";
                                            }
                                            //$calendar .= $cal;

                                            /************** Display flight leg if duty time not exist ***************/
                                            //$calendar .= "<td class='day'>";
                                            $cal = "";
                                            if(!empty($records['flight_leg_details'])) {
                                                $flArray = [];
                                                foreach($records['flight_leg_details'] as $keyl => $valuef) {
                                                    $fdate = date('Y-m-d', strtotime($valuef['selected_date']));
                                                   
                                                    //Add two times(Flight start time and Flight leg hours)
                                                    $time1 = $valuef['start_hour'].':'.$valuef['start_minute'];
                                                    $time2 = $valuef['leg_hour'].':'.$valuef['leg_minute'];
                                                    $timeResult = $this->Pilot->addTwoTimes($time1, $time2);
                                                    $resflHI = $timeResult['resflHI'];
                                                    $resflH  = $timeResult['resflH'];
                                                    $resflt  = $timeResult['resflt'];

                                                    if(strtotime($fdate) == strtotime($date)) {
                                                        $addFLTime = $this->Pilot->addTimes(array($time1, $time2));
                                                        if(strtotime($addFLTime) > strtotime("24:00")) {
                                                            $flArray[] = $this->Pilot->timeDiffFL($time1, "24:00");
                                                            $resflHI = "24:00";
                                                            $resflH = "24:00";
                                                        } else {
                                                            $flArray[] = $time2;
                                                        }
                                                    }

                                                    //If Flight leg start and end in same hour slot
                                                    if(($valuef['start_hour'] <= $hour && strtotime($hourM) < strtotime($resflHI) && strtotime($time2) < strtotime('01:00') && (strtotime($valuef['start_hour'].':00') == strtotime($resflH))) && $fdate == $date) {

                                                        $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                        $remainF = 100 - $percentF;
                                                        $percentL = round(($resflt * 100)/60, 2);
                                                        $remainL = 100 - $percentL;
                                                        $remainM = $remainF - $remainL;

                                                        if(!empty($valuef['part_135'])) {
                                                            $cal = '';
                                                            $cal .= "<div style='background: #fff;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#f7f76a; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                        } else {
                                                            $cal = '';
                                                            $cal .= "<div style='background: #fff;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#7abadc; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                        }
                                                    } 
                                                    //If Flight leg time start from mid of the slot
                                                    elseif(($valuef['start_hour'] <= $hour && strtotime($time1) > strtotime($hourM) && !empty($valuef['start_minute']) && $valuef['start_minute'] != 0) && $fdate == $date) {

                                                        $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                        $remainF = 100 - $percentF;

                                                        $percentL = 0;
                                                        $remainL = 0;
                                                        $sm="<div style='background: #fff;width:$percentL%;height:5px;float:left;margin-top:12px;'></div>";
                                                        //Check if end time(starttime+length) of any flight leg is equal to starttime of next fl
                                                        foreach($records['flight_leg_details'] as $keys => $vals) {
                                                            $fsdate = date('Y-m-d', strtotime($vals['selected_date']));

                                                            //Add two times(Flight start time and Flight leg hours)
                                                            $ts1 = $vals['start_hour'].':'.$vals['start_minute'];
                                                            $ts2 = $vals['leg_hour'].':'.$vals['leg_minute'];
                                                            $tResult = $this->Pilot->addTwoTimes($ts1, $ts2);
                                                            $tResflHI = $tResult['resflHI'];
                                                            $tResflH  = $tResult['resflH'];
                                                            $tResflt  = $tResult['resflt'];

                                                            //If Flight leg time end in mid of the slot
                                                            if((strtotime($valuef['start_hour'].':00') == strtotime($tResflH)) && strtotime($fdate) == strtotime($fsdate)) {
                                                                $percentL = round(($tResflt * 100)/60, 2);
                                                                $remainL = 100 - $percentL;

                                                                if(!empty($vals['part_135'])) {
                                                                    $sm = "<div style='background:#f7f76a; width:$percentL%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                                } else {
                                                                    $sm = "<div style='background:#7abadc; width:$percentL%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                                }
                                                            } 
                                                        }

                                                        $remainM = 100 - ($percentL+$remainF);
                                                        if(!empty($valuef['part_135'])) {
                                                            $cal = '';
                                                            $cal .= $sm."<div style='background: #fff;width: $remainM%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#f7f76a; width:$remainF%; height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                        } else {
                                                            $cal = '';
                                                            $cal .= $sm."<div style='background: #fff;width: $remainM%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#7abadc; width:$remainF%; height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                        }
                                                    } 
                                                    //If Flight leg time lied on complete hour slot
                                                    elseif(($valuef['start_hour'] <= $hour && strtotime($resflH) > strtotime($hourM)) && $fdate == $date) {
                                                        
                                                        if(!empty($valuef['part_135'])) {
                                                            $cal = '';
                                                            $cal .= "<div style='background:#f7f76a; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                        } else {
                                                            $cal = '';
                                                            $cal .= "<div style='background:#7abadc; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                        } 
                                                    } 
                                                    //If Flight leg time end in mid of the slot
                                                    elseif(($valuef['start_hour'] <= $hour && strtotime($resflHI) > strtotime($hourM)) && $fdate == $date) {
                                                        $percentF = round(($resflt * 100)/60, 2);
                                                        $remainF = 100 - $percentF;
                                                        if(!empty($valuef['part_135'])) {
                                                            $cal = '';
                                                            $cal .= "<div style='background:#f7f76a; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainF%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                        } else {
                                                            $cal = '';
                                                            $cal .= "<div style='background:#7abadc; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainF%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                        }
                                                    }
                                                }
                                                //Add flight leg
                                                $flTime = $this->Pilot->addTimes($flArray);
                                            }
                                            $calendar .= $cal;
                                            $calendar .= "</td>";
                                            /************* End flight leg time without duty time ***************/  
                                            
                                        }
                                        $tmp = $ext;
                                    }
                                }
                                $dtFlag = false;
                            }
                        } 

                        //Previous day data display(Duty Time and Flight Leg)
                        if($dtFlag) {
                            //Get previous day duty time which lies on next day
                            $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($date)));
                            $prevDayRes = $this->Pilot->getPrevDateDuty($pilotId, $prevDate);
                            $dtPDTime = '';
                            if(!empty($prevDayRes)) {
                                //Previous day time
                                if(strtotime($prevDayRes['duty_stop_hour'].':'.$prevDayRes['duty_stop_minute']) <= strtotime("24:00") && strtotime($prevDayRes['duty_start_hour'].':'.$prevDayRes['duty_start_minute']) > strtotime($prevDayRes['duty_stop_hour'].':'.$prevDayRes['duty_stop_minute'])) {
                                    $dtPDTime = $prevDayRes['duty_stop_hour'].':'.$prevDayRes['duty_stop_minute'];
                                }

                                //Duty time lies in complete slot
                                if(0 <= $hour && $prevDayRes['duty_stop_hour'] > $hour && $prevDayRes['duty_start_hour'] > $prevDayRes['duty_stop_hour']) {
                                    $calendar .= "<td class='day'>";
                                    $cal = "<div style='background:#008000; height:5px;'></div>";

                                    //Current date Flight Leg display 
                                    if(!empty($records['flight_leg_details'])) {
                                        $flArray = [];
                                        foreach($records['flight_leg_details'] as $keyl => $valuef) {
                                            $fdate = date('Y-m-d', strtotime($valuef['selected_date']));
                                           
                                            //Add two times(Flight start time and Flight leg hours)
                                            $time1 = $valuef['start_hour'].':'.$valuef['start_minute'];
                                            $time2 = $valuef['leg_hour'].':'.$valuef['leg_minute'];
                                            $timeResult = $this->Pilot->addTwoTimes($time1, $time2);
                                            $resflHI = $timeResult['resflHI'];
                                            $resflH  = $timeResult['resflH'];
                                            $resflt  = $timeResult['resflt'];

                                            if($fdate == $date) {
                                                $addFLTime = $this->Pilot->addTimes(array($time1, $time2));
                                                if(strtotime($addFLTime) > strtotime("24:00")) {
                                                    $flArray[] = $this->Pilot->timeDiffFL($time1, "24:00");
                                                } else {
                                                    $flArray[] = $time2;
                                                }
                                            }
                                            
                                            //If Flight leg start and end in same hour slot
                                            if(($valuef['start_hour'] <= $hour && strtotime($hourM) < strtotime($resflHI) && strtotime($time2) < strtotime('01:00') && (strtotime($valuef['start_hour'].':00') == strtotime($resflH))) && $fdate == $date) {
                                                
                                                $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                $remainF = 100 - $percentF;
                                                $percentL = round(($resflt * 100)/60, 2);
                                                $remainL = 100 - $percentL;
                                                $remainM = $remainF - $remainL;

                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background: #008000;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#f7f76a; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background: #008000;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#7abadc; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                }
                                            }
                                            //If Flight leg time start from mid of the slot
                                            elseif(($valuef['start_hour'] <= $hour && strtotime($time1) > strtotime($hourM) && !empty($valuef['start_minute']) && $valuef['start_minute'] != 0) && $fdate == $date) {
                                                
                                                $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                $remainF = 100 - $percentF;

                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background: #008000;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#f7f76a; width:$remainF%; height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background: #008000;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#7abadc; width:$remainF%; height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                }
                                            } 
                                            //If Flight leg time lied on complete hour slot
                                            elseif(($valuef['start_hour'] <= $hour && strtotime($resflH) > strtotime($hourM)) && $fdate == $date) {
                                                
                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#f7f76a; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#7abadc; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                } 
                                            } 
                                            //If Flight leg time end in mid of the slot
                                            elseif(($valuef['start_hour'] <= $hour && strtotime($resflHI) > strtotime($hourM)) && $fdate == $date) {
                                                
                                                $percentF = round(($resflt * 100)/60, 2);
                                                $remainF = 100 - $percentF;

                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#f7f76a; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainF%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#7abadc; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainF%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                }
                                            }
                                        }
                                        //Add Flight leg times
                                        $flTime = $this->Pilot->addTimes($flArray);
                                    }


                                    //Previous flight leg data which lies on next day
                                    $prevDayFLRes = $this->Pilot->getPrevDateFL($pilotId, $prevDate);
                                    $prevDDate = " on " . date('M d', strtotime($prevDate));
                                    $flPDTime = '';
                                    if(!empty($prevDayFLRes)) {
                                        //Add two times(Flight start time and Flight leg hours)
                                        $time1 = $prevDayFLRes['start_hour'].':'.$prevDayFLRes['start_minute'];
                                        $time2 = $prevDayFLRes['leg_hour'].':'.$prevDayFLRes['leg_minute'];
                                        $timeResult = $this->Pilot->addTwoTimes($time1, $time2);
                                        $resflHI = $timeResult['resflHI'];
                                        $resflH  = $timeResult['resflH'];
                                        $resflt  = $timeResult['resflt'];

                                        //Total hours
                                        $totalFH = $this->Pilot->addTimes(array($time1, $time2));

                                        if(strtotime($totalFH) > strtotime("24:00")) {
                                            $flPDTime = $resflHI;
                                        }
                                                                                                                                
                                        //Days off lied in complete slot
                                        if(0 <= $hour && strtotime($resflH) > strtotime($hourM) && strtotime($totalFH) >= strtotime("24:00")) {
                                            if(!empty($prevDayFLRes['part_135'])) {
                                                $cal = '';
                                                $cal .= "<div style='background:#f7f76a; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                            } else {
                                                $cal = '';
                                                $cal .= "<div style='background:#7abadc; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                            } 
                                        } 
                                        //Days off end in mid of slot and current date duty time start from same slot
                                        elseif(0 <= $hour && strtotime($resflHI) > strtotime($hourM) && strtotime($totalFH) >= strtotime("24:00")) {
                                            $percentF = round(($resflt * 100)/60, 2);
                                            $remainF = 100 - $percentF;
                                            
                                            //current date duty time
                                            $percentS = 0;
                                            $remainS = 0;
                                            $sm = "<div style='background: #008000;width: $remainS%;height: 5px;float:right;margin-top: 12px;'></div>";
                                            $curDayFLRes = $this->Pilot->getCurrentDateFL($pilotId, $date);
                                            if(!empty($curDayFLRes)) {
                                                $time1 = $curDayFLRes['start_hour'].':'.$curDayFLRes['start_minute'];
                                                if($curDayFLRes['start_hour'] <= $hour && strtotime($time1) >= strtotime($hourM) && !empty($curDayFLRes['start_minute']) && $curDayFLRes['start_minute'] != 0) {
                                                    
                                                    $percentS = round(($curDayFLRes['start_minute'] * 100)/60, 2);
                                                    $remainS = 100 - $percentS;
                                                    if(!empty($curDayFLRes['part_135'])) { 
                                                        $sm = "<div style='background: #f7f76a;width: $remainS%;height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                    } else {
                                                        $sm = "<div style='background: #7abadc;width: $remainS%;height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                    }
                                                }
                                            }
                                            
                                            //Middle blank value
                                            $remainM = 100 - ($percentF + $remainS);
                                            if(!empty($prevDayFLRes['part_135'])) {
                                                $cal = '';
                                                $cal .= "<div style='background:#f7f76a; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainM%;height: 5px;float:left;margin-top: 12px;'></div>".$sm;
                                            } else {
                                                $cal = '';
                                                $cal .= "<div style='background:#7abadc; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainM%;height: 5px;float:left;margin-top: 12px;'></div>".$sm;
                                            }
                                        }
                                    }
                                    $calendar .= $cal;
                                    $calendar .= "</td>";
                                } 
                                //Duty time end in mid of slot
                                elseif(0 <= $hour && strtotime($prevDayRes['duty_stop_hour'].':'.$prevDayRes['duty_stop_minute']) > strtotime($hourM) && $prevDayRes['duty_start_hour'] > $prevDayRes['duty_stop_hour']) {
                                    
                                    $percent = round(($prevDayRes['duty_stop_minute'] * 100)/60, 2);
                                    $remain = 100 - $percent; 
                                    $calendar .= "<td class='day'>";
                                    $cal = "<div style='background:#008000; width:$percent%; height:5px; float:left;'></div><div style='background:#fff; width:$remain%; height:5px; float:right;'></div>";

                                    //Current date Flight Leg display
                                    if(!empty($records['flight_leg_details'])) {
                                        $flArray = [];
                                        foreach($records['flight_leg_details'] as $keyl => $valuef) {
                                            $fdate = date('Y-m-d', strtotime($valuef['selected_date']));

                                            //Add two times(Flight start time and Flight leg hours)
                                            $time1 = $valuef['start_hour'].':'.$valuef['start_minute'];
                                            $time2 = $valuef['leg_hour'].':'.$valuef['leg_minute'];
                                            $timeResult = $this->Pilot->addTwoTimes($time1, $time2);
                                            $resflHI = $timeResult['resflHI'];
                                            $resflH  = $timeResult['resflH'];
                                            $resflt  = $timeResult['resflt'];

                                            if($fdate == $date) {
                                                $addFLTime = $this->Pilot->addTimes(array($time1, $time2));
                                                if(strtotime($addFLTime) > strtotime("24:00")) {
                                                    $flArray[] = $this->Pilot->timeDiffFL($time1, "24:00");
                                                } else {
                                                    $flArray[] = $time2;
                                                }
                                            }
                                                                                            
                                            //If Flight leg start and end in same hour slot
                                            if(($valuef['start_hour'] <= $hour && strtotime($hourM) < strtotime($resflHI) && strtotime($time2) < strtotime('01:00') && (strtotime($valuef['start_hour'].':00') == strtotime($resflH))) && $fdate == $date) {
                                                
                                                $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                $remainF = 100 - $percentF;
                                                $percentL = round(($resflt * 100)/60, 2);
                                                $remainL = 100 - $percentL;
                                                $remainM = $remainF - $remainL;

                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background: #008000;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#f7f76a; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background: #008000;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#7abadc; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                }
                                            } 
                                            //If Flight leg time end in mid of the slot
                                            elseif(($valuef['start_hour'] <= $hour && strtotime($resflHI) > strtotime($hourM)) && $fdate == $date) {
                                                
                                                $percentF = round(($resflt * 100)/60, 2);
                                                $remainF = 100 - $percentF;
                                                $rPercent = $percent - $percentF;

                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#f7f76a; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $rPercent%;height: 5px;float:left;margin-top: 12px;'></div><div style='background: #fff;width: $remain%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#7abadc; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $rPercent%;height: 5px;float:left;margin-top: 12px;'></div><div style='background: #fff;width: $remain%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                }

                                                //If flight leg end in mid and rest time start from mid
                                                if(!empty($value['required_rest'])) {
                                                    if(!empty($valuef['part_135'])) {
                                                        $cal = '';
                                                        $cal .= "<div style='background:#f7f76a; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $rPercent%;height: 5px;float:left;margin-top: 12px;'></div><div style='background: grey;width: $remain%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                    } else {
                                                        $cal = '';
                                                        $cal .= "<div style='background:#7abadc; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #008000;width: $rPercent%;height: 5px;float:left;margin-top: 12px;'></div><div style='background: grey;width: $remain%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                    }
                                                }
                                            } else {
                                                //Previous flight leg data which lies on next day
                                                $prevDayFLRes = $this->Pilot->getPrevDateFL($pilotId, $prevDate);
                                                $prevDDate = " on " . date('M d', strtotime($prevDate));
                                                $flPDTime = '';
                                                if(!empty($prevDayFLRes)) {
                                                    //Add two times(Flight start time and Flight leg hours)
                                                    $time1 = $prevDayFLRes['start_hour'].':'.$prevDayFLRes['start_minute'];
                                                    $time2 = $prevDayFLRes['leg_hour'].':'.$prevDayFLRes['leg_minute'];
                                                    $timeResult = $this->Pilot->addTwoTimes($time1, $time2);
                                                    $resflHI = $timeResult['resflHI'];
                                                    $resflH  = $timeResult['resflH'];
                                                    $resflt  = $timeResult['resflt'];

                                                    //Total hours
                                                    $totalFH = $this->Pilot->addTimes(array($time1, $time2));

                                                    if(strtotime($totalFH) > strtotime("24:00")) {
                                                        $flPDTime = $resflHI;
                                                    }
                                                                                                                                            
                                                    //Days off lied in complete slot
                                                    if(0 <= $hour && strtotime($resflH) > strtotime($hourM) && strtotime($totalFH) >= strtotime("24:00")) {
                                                        if(!empty($prevDayFLRes['part_135'])) {
                                                            $cal = '';
                                                            $cal .= "<div style='background:#f7f76a; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                        } else {
                                                            $cal = '';
                                                            $cal .= "<div style='background:#7abadc; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                        } 
                                                    } 
                                                    //Days off end in mid of slot and current date duty time start from same slot
                                                    elseif(0 <= $hour && strtotime($resflHI) > strtotime($hourM) && strtotime($totalFH) >= strtotime("24:00")) {
                                                        $percentF = round(($resflt * 100)/60, 2);
                                                        $remainF = 100 - $percentF;
                                                        $remainM = 0;
                                                        if($percent > $percentF) {
                                                            $remainM = $percent - $percentF;
                                                        }
                                                        $remainL = $remainF - $remainM;

                                                        if(!empty($prevDayFLRes['part_135'])) {
                                                            $cal = '';
                                                            $cal .= "<div style='background:#f7f76a; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background:#008000; width:$remainM%; height:5px; margin-top:12px;float:left;'></div><div style='background:#fff; width:$remainL%; height:5px; float:right;'></div>";
                                                        } else {
                                                            $cal = '';
                                                            $cal .= "<div style='background:#7abadc; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background:#008000; width:$remainM%; height:5px; margin-top:12px;float:left;'></div><div style='background:#fff; width:$remainL%; height:5px; float:right;'></div>";
                                                        }
                                                    } // Add else here if previous day fl 
                                                }
                                            }
                                        }
                                        //Add flight leg
                                        $flTime = $this->Pilot->addTimes($flArray);
                                    }

                                    //If rest time start from mid
                                    if(!empty($prevDayRes['required_rest'])) {
                                        $cal = "<div style='background:#008000; width:$percent%; height:5px; float:left;'></div><div style='background:grey; width:$remain%; height:5px; float:right;'></div>";
                                    }
                                    $calendar .= $cal;
                                    $calendar .= "</td>";
                                } else {
                                    //Total time (if crossed 24 hour then display next day hour) extra '00' for seconds
                                    //$nextDH = $this->Pilot->addTimesMulti(array($prevDayRes['duty_stop_hour'].':'.$prevDayRes['duty_stop_minute'].':00', $prevDayRes['required_rest'].':00:00'));
                                    $timeResult = $this->Pilot->addTwoTimes($prevDayRes['duty_stop_hour'].':'.$prevDayRes['duty_stop_minute'], $prevDayRes['required_rest'].':00');
                                    $nextDHI = $timeResult['resflHI'];
                                    $nextDH = $timeResult['resflH'];
                                    $nextDI = $timeResult['resflt'];

                                    //Add times as numeric value
                                    $totalH = $this->Pilot->addTimes(array($prevDayRes['duty_stop_hour'].':'.$prevDayRes['duty_stop_minute'], $prevDayRes['required_rest'].':00'));
                                    if(strtotime($prevDayRes['duty_stop_hour'].':'.$prevDayRes['duty_stop_minute']) <= strtotime($hourM) && strtotime($nextDHI) > strtotime($hourM) && $prevDayRes['duty_start_hour'] > $prevDayRes['duty_stop_hour'] && $prevDayRes['required_rest'] > 0 && $totalH < 24) {
                                        
                                        //Rest after duty time on next day
                                        if($nextDH > $hour) {
                                            $calendar .= "<td class='day'><div style='background:grey; height:5px;'></div></td>";
                                        }

                                        //End in mid of slot
                                        if ($nextDH <= $hour && strtotime($nextDHI) > strtotime($hourM) && $nextDI != 0) {
                                            $percent = round(($nextDI * 100)/60, 2);
                                            $remain = 100 - $percent; 
                                            $calendar .= "<td class='day'><div style='background:linear-gradient(to right, grey $percent%, #fff $percent%$remain%); height:5px;'></div></td>";
                                        }

                                    } elseif(0 <= $hour && strtotime($nextDHI) > strtotime($hourM) && $prevDayRes['duty_start_hour'] < $prevDayRes['duty_stop_hour'] && $prevDayRes['required_rest'] > 0 && $totalH > 24 && empty($curDateOff)) {

                                        //Rest next day if 10 hours not complete in same day
                                        if($nextDH > $hour) {
                                            $calendar .= "<td class='day'><div style='background:grey; height:5px;'></div></td>";
                                        } 

                                        //End in mid of slot
                                        if ($nextDH <= $hour && strtotime($nextDHI) > strtotime($hourM) && $nextDI != 0) {
                                            $percent = round(($nextDI * 100)/60, 2);
                                            $remain = 100 - $percent; 
                                            $calendar .= "<td class='day'><div style='background:linear-gradient(to right, grey $percent%, #fff $percent%$remain%); height:5px;'></div></td>";
                                        }
                                    } else {
                                        //If current date days off exist then hide td
                                        /*if(empty($curDateOff) && empty($prevDayOffRes)) {
                                            //If hour slots are blank in a day
                                            $calendar .= "<td class='day'>k</td>";
                                        }*/

                                        /************** Display flight leg if duty time not exist ***************/
                                        $calendar .= "<td class='day'>";
                                        $cal = '';
                                        if(!empty($records['flight_leg_details'])) {
                                            $flArray = [];
                                            foreach($records['flight_leg_details'] as $keyl => $valuef) {
                                                $fdate = date('Y-m-d', strtotime($valuef['selected_date']));
                                               
                                                //Add two times(Flight start time and Flight leg hours)
                                                $time1 = $valuef['start_hour'].':'.$valuef['start_minute'];
                                                $time2 = $valuef['leg_hour'].':'.$valuef['leg_minute'];
                                                $timeResult = $this->Pilot->addTwoTimes($time1, $time2);
                                                $resflHI = $timeResult['resflHI'];
                                                $resflH  = $timeResult['resflH'];
                                                $resflt  = $timeResult['resflt'];

                                                if(strtotime($fdate) == strtotime($date)) {
                                                    $addFLTime = $this->Pilot->addTimes(array($time1, $time2));
                                                    if(strtotime($addFLTime) > strtotime("24:00")) {
                                                        $flArray[] = $this->Pilot->timeDiffFL($time1, "24:00");
                                                        $resflHI = "24:00";
                                                        $resflH = "24:00";
                                                    } else {
                                                        $flArray[] = $time2;
                                                    }
                                                }

                                                //If Flight leg start and end in same hour slot
                                                if(($valuef['start_hour'] <= $hour && strtotime($hourM) < strtotime($resflHI) && strtotime($time2) < strtotime('01:00') && (strtotime($valuef['start_hour'].':00') == strtotime($resflH))) && $fdate == $date) {

                                                    $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                    $remainF = 100 - $percentF;
                                                    $percentL = round(($resflt * 100)/60, 2);
                                                    $remainL = 100 - $percentL;
                                                    $remainM = $remainF - $remainL;

                                                    if(!empty($valuef['part_135'])) {
                                                        $cal = '';
                                                        $cal .= "<div style='background: #fff;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#f7f76a; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                    } else {
                                                        $cal = '';
                                                        $cal .= "<div style='background: #fff;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#7abadc; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                    }
                                                } 
                                                //If Flight leg time start from mid of the slot
                                                elseif(($valuef['start_hour'] <= $hour && strtotime($time1) > strtotime($hourM) && !empty($valuef['start_minute']) && $valuef['start_minute'] != 0) && $fdate == $date) {

                                                    $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                    $remainF = 100 - $percentF;

                                                    $percentL = 0;
                                                    $remainL = 0;
                                                    $sm="<div style='background: #fff;width:$percentL%;height:5px;float:left;margin-top:12px;'></div>";
                                                    //Check if end time(starttime+length) of any flight leg is equal to starttime of next fl
                                                    foreach($records['flight_leg_details'] as $keys => $vals) {
                                                        $fsdate = date('Y-m-d', strtotime($vals['selected_date']));

                                                        //Add two times(Flight start time and Flight leg hours)
                                                        $ts1 = $vals['start_hour'].':'.$vals['start_minute'];
                                                        $ts2 = $vals['leg_hour'].':'.$vals['leg_minute'];
                                                        $tResult = $this->Pilot->addTwoTimes($ts1, $ts2);
                                                        $tResflHI = $tResult['resflHI'];
                                                        $tResflH  = $tResult['resflH'];
                                                        $tResflt  = $tResult['resflt'];

                                                        //If Flight leg time end in mid of the slot
                                                        if((strtotime($valuef['start_hour'].':00') == strtotime($tResflH)) && strtotime($fdate) == strtotime($fsdate)) {
                                                            $percentL = round(($tResflt * 100)/60, 2);
                                                            $remainL = 100 - $percentL;

                                                            if(!empty($vals['part_135'])) {
                                                                $sm = "<div style='background:#f7f76a; width:$percentL%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                            } else {
                                                                $sm = "<div style='background:#7abadc; width:$percentL%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                            }
                                                        } 
                                                    }

                                                    $remainM = 100 - ($percentL+$remainF);
                                                    if(!empty($valuef['part_135'])) {
                                                        $cal = '';
                                                        $cal .= $sm."<div style='background: #fff;width: $remainM%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#f7f76a; width:$remainF%; height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                    } else {
                                                        $cal = '';
                                                        $cal .= $sm."<div style='background: #fff;width: $remainM%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#7abadc; width:$remainF%; height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                    }
                                                } 
                                                //If Flight leg time lied on complete hour slot
                                                elseif(($valuef['start_hour'] <= $hour && strtotime($resflH) > strtotime($hourM)) && $fdate == $date) {
                                                    
                                                    if(!empty($valuef['part_135'])) {
                                                        $cal = '';
                                                        $cal .= "<div style='background:#f7f76a; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                    } else {
                                                        $cal = '';
                                                        $cal .= "<div style='background:#7abadc; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                    } 
                                                } 
                                                //If Flight leg time end in mid of the slot
                                                elseif(($valuef['start_hour'] <= $hour && strtotime($resflHI) > strtotime($hourM)) && $fdate == $date) {
                                                    $percentF = round(($resflt * 100)/60, 2);
                                                    $remainF = 100 - $percentF;
                                                    if(!empty($valuef['part_135'])) {
                                                        $cal = '';
                                                        $cal .= "<div style='background:#f7f76a; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainF%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                    } else {
                                                        $cal = '';
                                                        $cal .= "<div style='background:#7abadc; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainF%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                    }
                                                }
                                            }
                                            //Add flight leg
                                            $flTime = $this->Pilot->addTimes($flArray);
                                        }
                                        $calendar .= $cal;
                                        $calendar .= "</td>";
                                        /************* End flight leg time without duty time ***************/
                                    }
                                }
                            } else {
                                //If current date days off exist then hide td
                                if(!empty($curDateOff) || !empty($prevDayOffRes)) {
                                    $calendar .= "";
                                } else {
                                    //If hour slots are blank in a day
                                    //$calendar .= "<td class='day'>C</td>";

                                    /************** Display flight leg if duty time not exist ***************/
                                    $calendar .= "<td class='day'>";
                                    $cal = '';
                                    if(!empty($records['flight_leg_details'])) {
                                        $flArray = [];
                                        foreach($records['flight_leg_details'] as $keyl => $valuef) {
                                            $fdate = date('Y-m-d', strtotime($valuef['selected_date']));
                                           
                                            //Add two times(Flight start time and Flight leg hours)
                                            $time1 = $valuef['start_hour'].':'.$valuef['start_minute'];
                                            $time2 = $valuef['leg_hour'].':'.$valuef['leg_minute'];
                                            $timeResult = $this->Pilot->addTwoTimes($time1, $time2);
                                            $resflHI = $timeResult['resflHI'];
                                            $resflH  = $timeResult['resflH'];
                                            $resflt  = $timeResult['resflt'];

                                            if(strtotime($fdate) == strtotime($date)) {
                                                $addFLTime = $this->Pilot->addTimes(array($time1, $time2));
                                                if(strtotime($addFLTime) > strtotime("24:00")) {
                                                    $flArray[] = $this->Pilot->timeDiffFL($time1, "24:00");
                                                    $resflHI = "24:00";
                                                    $resflH = "24:00";
                                                } else {
                                                    $flArray[] = $time2;
                                                }
                                            }

                                            //If Flight leg start and end in same hour slot
                                            if(($valuef['start_hour'] <= $hour && strtotime($hourM) < strtotime($resflHI) && strtotime($time2) < strtotime('01:00') && (strtotime($valuef['start_hour'].':00') == strtotime($resflH))) && $fdate == $date) {

                                                $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                $remainF = 100 - $percentF;
                                                $percentL = round(($resflt * 100)/60, 2);
                                                $remainL = 100 - $percentL;
                                                $remainM = $remainF - $remainL;

                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background: #fff;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#f7f76a; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background: #fff;width: $percentF%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#7abadc; width:$remainM%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainL%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                }
                                            } 
                                            //If Flight leg time start from mid of the slot
                                            elseif(($valuef['start_hour'] <= $hour && strtotime($time1) > strtotime($hourM) && !empty($valuef['start_minute']) && $valuef['start_minute'] != 0) && $fdate == $date) {

                                                $percentF = round(($valuef['start_minute'] * 100)/60, 2);
                                                $remainF = 100 - $percentF;

                                                $percentL = 0;
                                                $remainL = 0;
                                                $sm="<div style='background: #fff;width:$percentL%;height:5px;float:left;margin-top:12px;'></div>";
                                                //Check if end time(starttime+length) of any flight leg is equal to starttime of next fl
                                                foreach($records['flight_leg_details'] as $keys => $vals) {
                                                    $fsdate = date('Y-m-d', strtotime($vals['selected_date']));

                                                    //Add two times(Flight start time and Flight leg hours)
                                                    $ts1 = $vals['start_hour'].':'.$vals['start_minute'];
                                                    $ts2 = $vals['leg_hour'].':'.$vals['leg_minute'];
                                                    $tResult = $this->Pilot->addTwoTimes($ts1, $ts2);
                                                    $tResflHI = $tResult['resflHI'];
                                                    $tResflH  = $tResult['resflH'];
                                                    $tResflt  = $tResult['resflt'];

                                                    //If Flight leg time end in mid of the slot
                                                    if((strtotime($valuef['start_hour'].':00') == strtotime($tResflH)) && strtotime($fdate) == strtotime($fsdate)) {
                                                        $percentL = round(($tResflt * 100)/60, 2);
                                                        $remainL = 100 - $percentL;

                                                        if(!empty($vals['part_135'])) {
                                                            $sm = "<div style='background:#f7f76a; width:$percentL%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                        } else {
                                                            $sm = "<div style='background:#7abadc; width:$percentL%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                        }
                                                    } 
                                                }

                                                $remainM = 100 - ($percentL+$remainF);
                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= $sm."<div style='background: #fff;width: $remainM%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#f7f76a; width:$remainF%; height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= $sm."<div style='background: #fff;width: $remainM%;height: 5px;float:left;margin-top: 12px;'></div><div style='background:#7abadc; width:$remainF%; height:100%;float:right;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                }
                                            } 
                                            //If Flight leg time lied on complete hour slot
                                            elseif(($valuef['start_hour'] <= $hour && strtotime($resflH) > strtotime($hourM)) && $fdate == $date) {
                                                
                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#f7f76a; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#7abadc; height:100%;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div>";
                                                } 
                                            } 
                                            //If Flight leg time end in mid of the slot
                                            elseif(($valuef['start_hour'] <= $hour && strtotime($resflHI) > strtotime($hourM)) && $fdate == $date) {
                                                $percentF = round(($resflt * 100)/60, 2);
                                                $remainF = 100 - $percentF;
                                                if(!empty($valuef['part_135'])) {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#f7f76a; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_yellow.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainF%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                } else {
                                                    $cal = '';
                                                    $cal .= "<div style='background:#7abadc; width:$percentF%; height:100%;float:left;'><img src='".ROOT_DIR_IMG."/images/line_blue.png' style='width:100%; height: 25px;margin-top:3px;'></div><div style='background: #fff;width: $remainF%;height: 5px;float:right;margin-top: 12px;'></div>";
                                                }
                                            }
                                        }
                                        //Add flight leg
                                        $flTime = $this->Pilot->addTimes($flArray);
                                    }
                                    $calendar .= $cal;
                                    $calendar .= "</td>";
                                    /************* End flight leg time without duty time ***************/
                                }
                            }
                        }
                    } else {
                        //If duty off exist and duty time not exist in particular months. So if current date days off exist then hide td
                        if(!empty($curDateOff) || !empty($prevDayOffRes)) {
                            $calendar .= "";
                        } else {
                            //If hour slots are blank in a day
                            $calendar .= "<td class='day'></td>";
                        }
                    }
                } else {
                    //In selected month no record added
                    $calendar .= "<td class='day'></td>";
                }

                $ext++;
            }

            $perDayDTTime = $this->Pilot->addTimes(array($dtPDTime, $dtTime));
            $perDayDTTime = (!empty($perDayDTTime) && $perDayDTTime !='00:00') ? $perDayDTTime : '';

            $perDayFLTime = $this->Pilot->addTimes(array($flTime, $flPDTime));
            $perDayFLTime = (!empty($perDayFLTime) && $perDayFLTime !='00:00') ? $perDayFLTime : '';
            $calendar .= "<td>".$perDayDTTime."</td><td>".$perDayFLTime."</td>";
            $calendar .= "</tr>";

            $currentDay++;

            $totalDTTimeArr[] = $perDayDTTime;
            $totalFLTimeArr[] = $perDayFLTime;
        }
        
        $totalDTTime = $this->Pilot->addTimes($totalDTTimeArr);
        $totalFLTime = $this->Pilot->addTimes($totalFLTimeArr);

        $calendar .= "<tr><td colspan='26'>Monthly Totals</td><td>".$totalDTTime."</td><td>".$totalFLTime."</td></tr>";
        $calendar .= "</table>";

        return $calendar;
    }

    //Get pilot duty details
    public function pilotDutyDetail()
    {
        $params = $this->request->getData();

        //Date display on header
        $dateTitle = ucfirst($params['selMonth']).' '.$params['selDay'].', '.$params['selYear'];
        $selectedDate = date('Y-m-d', strtotime($dateTitle));

        $pilotId = '';
        $results = [];
        if(!empty($params['pilotId'])) {
            $pilotId = $params['pilotId'];

            //Duty time conditions
            $dutyTimeCond = function ($q) use ($pilotId, $selectedDate) { 
                    return $q->where(['DutyTimes.pilot_id'=>$pilotId, 'DutyTimes.selected_date'=>$selectedDate]);
                };

            //Days off conditions
            $daysOffCond = function ($q) use ($pilotId, $selectedDate) { 
                    return $q->where(['DaysOff.pilot_id'=>$pilotId, 'DaysOff.selected_date'=>$selectedDate]);
                };

            //Flight leg conditions
            $flightLegCond = function ($q) use ($pilotId, $selectedDate) { 
                    return $q->where(['FlightLegDetails.pilot_id'=>$pilotId, 'FlightLegDetails.selected_date'=>$selectedDate]);
                };

            //Get records
            $results = $this->Pilots->get($pilotId, [
                        'contain' => [
                            'DutyTimes'=>$dutyTimeCond, 
                            'DaysOff'=>$daysOffCond, 
                            'FlightLegDetails'=>$flightLegCond
                        ]
                    ]);
        }

        $tHtml = '';
        $dutyTimes = [];
        $dayOffTimes = [];
        $flightLeg = [];
        if(!empty($params) && !empty($results)) {
            //Duty time listing
            $dutyTimeHtml = '';
            if(!empty($results['duty_times'])) {
                $i = 1;
                foreach ($results['duty_times'] as $key => $value) {                    
                    //Check if record lies on next day
                    $nextDayDuty = '';
                    $timeD = $value['duty_stop_hour'].':'.$value['duty_stop_minute'];
                    if($value['duty_start_hour'] > $value['duty_stop_hour']) {
                        $nextDayDuty = " on ". date('M d', strtotime("+1 day", strtotime($value['selected_date'])));
                    }

                    $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($value['selected_date'])));
                    $prevDayRes = $this->Pilot->getPrevDateDuty($value['pilot_id'], $prevDate);
                    if(!empty($prevDayRes)) {
                        //$nextDayDuty = " on ". date('M d', strtotime("+1 day", strtotime($value['selected_date'])));
                        $prevDDate = " on ". date('M d', strtotime($prevDate));
                        if(($prevDayRes['duty_stop_hour']==0 || $prevDayRes['duty_stop_hour'] < $prevDayRes['duty_start_hour']) && ($prevDayRes['duty_stop_minute']==0 || $prevDayRes['duty_stop_minute'] > 0)) {
                            $i++;
                        }
                    }

                    $value = $this->Pilot->dtCorrectVal($value);
                    $dutyTimeHtml .= '<tr class="dtMsg">
                                        <td class="dtUpClass" data-dtid="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'">Duty Time '.$i.'</td>
                                        <td>'.$value['duty_start_hour'].':'.$value['duty_start_minute'].'</td>
                                        <td>'.$value['duty_stop_hour'].':'.$value['duty_stop_minute'].$nextDayDuty.'</td>
                                        <td class="dtDelClass" data-dtid="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'">X</td>
                                    </tr>';

                    //Display previous day time
                    if(!empty($prevDayRes)) {
                        if(($prevDayRes['duty_stop_hour']==0 || $prevDayRes['duty_stop_hour'] < $prevDayRes['duty_start_hour']) && ($prevDayRes['duty_stop_minute']==0 || $prevDayRes['duty_stop_minute'] > 0)) {
                            $prevDayRes = $this->Pilot->dtCorrectVal($prevDayRes);
                            $dutyTimeHtml .= '<tr>
                                            <td>*Prev Day</td>
                                            <td>'.$prevDayRes['duty_start_hour'].':'.$prevDayRes['duty_start_minute'].$prevDDate.'</td>
                                            <td>'.$prevDayRes['duty_stop_hour'].':'.$prevDayRes['duty_stop_minute'].'</td>
                                            <td></td>
                                        </tr>';
                        }
                    }

                    $i++;
                }
            } else {
                $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($selectedDate)));
                $prevDayRes = $this->Pilot->getPrevDateDuty($pilotId, $prevDate);
                if(!empty($prevDayRes)) {
                    $prevDDate = " on " . date('M d', strtotime($prevDate));
                    if(($prevDayRes['duty_stop_hour']==0 || $prevDayRes['duty_stop_hour'] < $prevDayRes['duty_start_hour']) && ($prevDayRes['duty_stop_minute']==0 || $prevDayRes['duty_stop_minute'] > 0)) {
                        $prevDayRes = $this->Pilot->dtCorrectVal($prevDayRes);
                        $dutyTimeHtml .= '<tr>
                                        <td>*Prev Day</td>
                                        <td>'.$prevDayRes['duty_start_hour'].':'.$prevDayRes['duty_start_minute'].$prevDDate.'</td>
                                        <td>'.$prevDayRes['duty_stop_hour'].':'.$prevDayRes['duty_stop_minute'].'</td>
                                        <td>&nbsp;</td>
                                    </tr>';
                    }
                }
            }

            //Days off listing
            $daysOffHtml = '';
            if(!empty($results['days_off'])) {
                $i = 1;
                foreach ($results['days_off'] as $key => $value) {                    
                    $nextDayOff = " on " . date('M d', strtotime("+1 day", strtotime($value['selected_date'])));
                    $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($value['selected_date'])));
                    $prevDayRes = $this->Pilot->getPrevDateOff($value['pilot_id'], $prevDate);
                    if(!empty($prevDayRes)) {
                        $prevDDate = " on " . date('M d', strtotime($prevDate));
                        if(($prevDayRes['off_start_hour']==0 || $prevDayRes['off_start_hour']>0) && ($prevDayRes['off_start_minute']==0 || $prevDayRes['off_start_minute']>0)) {
                            $i++;
                        }
                    }

                    $value = $this->Pilot->doCorrectVal($value);                    
                    $daysOffHtml .= '<tr class="doMsg">
                                        <td class="doUpClass" data-doid="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'">Time '.$i.'</td>
                                        <td>'.$value['off_start_hour'].':'.$value['off_start_minute'].'</td>
                                        <td>'.$value['off_start_hour'].':'.$value['off_start_minute'].$nextDayOff.'</td>
                                        <td class="doDelClass" data-doid="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'">X</td>
                                    </tr>';

                    if(!empty($prevDayRes)) {
                        if(($prevDayRes['off_start_hour']==0 || $prevDayRes['off_start_hour']>0) && ($prevDayRes['off_start_minute']==0 || $prevDayRes['off_start_minute']>0)) {
                            $prevDayRes = $this->Pilot->doCorrectVal($prevDayRes);
                            $daysOffHtml .= '<tr>
                                            <td>*Prev Day</td>
                                            <td>'.$prevDayRes['off_start_hour'].':'.$prevDayRes['off_start_minute'].$prevDDate.'</td>
                                            <td>'.$prevDayRes['off_start_hour'].':'.$prevDayRes['off_start_minute'].'</td>
                                            <td></td>
                                        </tr>';
                        }
                    }
                    $i++;
                }
            } else {
                $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($selectedDate)));
                $prevDayRes = $this->Pilot->getPrevDateOff($pilotId, $prevDate);
                if(!empty($prevDayRes)) {                   
                    $prevDDate = " on " . date('M d', strtotime($prevDate));
                    if(($prevDayRes['off_start_hour']==0 || $prevDayRes['off_start_hour']>0) && ($prevDayRes['off_start_minute']==0 || $prevDayRes['off_start_minute']>0)) {
                        $prevDayRes = $this->Pilot->doCorrectVal($prevDayRes);
                        $daysOffHtml .= '<tr>
                                        <td>*Prev Day</td>
                                        <td>'.$prevDayRes['off_start_hour'].':'.$prevDayRes['off_start_minute'].$prevDDate.'</td>
                                        <td>'.$prevDayRes['off_start_hour'].':'.$prevDayRes['off_start_minute'].'</td>
                                        <td></td>
                                    </tr>';
                    }
                }
            }

            //Flight leg listing
            $flightLegHtml = '';
            if(!empty($results['flight_leg_details'])) {
                $i=$j=1;
                $countFl = count($results['flight_leg_details']);
                foreach ($results['flight_leg_details'] as $key => $value) {
                    $value = $this->Pilot->flCorrectVal($value);

                    //Check if record lies on next day
                    $time1 = $value['start_hour'].':'.$value['start_minute'];
                    $time2 = $value['leg_hour'].':'.$value['leg_minute'];
                    $totalFH = $this->Pilot->addTimes(array($time1, $time2));                                                                               
                    $nextDayFL = '';
                    if(strtotime($totalFH) >= strtotime("24:00") && $countFl == $i) {
                        $nextDayFL = " on ". date('M d', strtotime("+1 day", strtotime($value['selected_date'])));
                    }

                    $part135 = 'no';
                    if($value['part_135']) {
                        $part135 = 'yes';
                    }

                    //Get previous day record
                    $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($value['selected_date'])));
                    $prevDayRes = $this->Pilot->getPrevDateFL($value['pilot_id'], $prevDate);
                    if(!empty($prevDayRes)) {
                        $prevDayRes = $this->Pilot->flCorrectVal($prevDayRes);

                        $prevDDate = " on ". date('M d', strtotime($prevDate));
                        $timeP1 = $prevDayRes['start_hour'].':'.$prevDayRes['start_minute'];
                        $timeP2 = $prevDayRes['leg_hour'].':'.$prevDayRes['leg_minute'];
                        $totalPFH = $this->Pilot->addTimes(array($timeP1, $timeP2));
                        //If previous day record exist then increase count once
                        if(strtotime($totalPFH) >= strtotime("24:00")) {
                            if($i<=2) {
                                $i++;
                            }
                        }
                    }
                    
                    //Normal record
                    $flightLegHtml .= '<tr class="flMsg">
                                        <td class="flUpClass" data-flid="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'">Leg '.$i.'</td>
                                        <td>'.$value['start_hour'].':'.$value['start_minute'].'</td>
                                        <td>'.$value['leg_hour'].':'.$value['leg_minute'].$nextDayFL.'</td>
                                        <td>'.$value['night_flt_hour'].':'.$value['night_flt_minute'].'</td>
                                        <td>'.$value['ifr_flight_hour'].':'.$value['ifr_flight_minute'].'</td>
                                        <td>'.strtoupper($value['approaches']).'</td>
                                        <td>'.$value['landings'].'</td>
                                        <td>'.$value['duty_position'].'</td>
                                        <td>'.$part135.'</td>
                                        <td class="flDelClass" data-flid="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'">X</td>
                                    </tr>';

                    
                    //Display previous day flight leg
                    if(!empty($prevDayRes)) {
                        if(strtotime($totalPFH) >= strtotime("24:00") && $countFl == $j) {
                            $part135 = 'no';
                            if($prevDayRes['part_135']) {
                                $part135 = 'yes';
                            }

                            $flightLegHtml .= '<tr>
                                            <td>*Prev Day</td>
                                            <td>'.$prevDayRes['start_hour'].':'.$prevDayRes['start_minute'].$prevDDate.'</td>
                                            <td>'.$prevDayRes['leg_hour'].':'.$prevDayRes['leg_minute'].'</td>
                                            <td>'.$prevDayRes['night_flt_hour'].':'.$prevDayRes['night_flt_minute'].'</td>
                                            <td>'.$prevDayRes['ifr_flight_hour'].':'.$prevDayRes['ifr_flight_minute'].'</td>
                                            <td>'.strtoupper($prevDayRes['approaches']).'</td>
                                            <td>'.$prevDayRes['landings'].'</td>
                                            <td>'.$prevDayRes['duty_position'].'</td>
                                            <td>'.$part135.'</td>
                                            <td></td>
                                        </tr>';
                        }
                    }
                    $i++;
                    $j++;
                }
            } else {
                //Display previous day flight leg
                $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($selectedDate)));
                $prevDayRes = $this->Pilot->getPrevDateFL($pilotId, $prevDate);
                if(!empty($prevDayRes)) {
                    $prevDayRes = $this->Pilot->flCorrectVal($prevDayRes);

                    $prevDDate = " on ". date('M d', strtotime($prevDate));
                    $timeP1 = $prevDayRes['start_hour'].':'.$prevDayRes['start_minute'];
                    $timeP2 = $prevDayRes['leg_hour'].':'.$prevDayRes['leg_minute'];
                    $totalPFH = $this->Pilot->addTimes(array($timeP1, $timeP2));
                    
                    if(strtotime($totalPFH) >= strtotime("24:00")) {
                        $part135 = 'no';
                        if($prevDayRes['part_135']) {
                            $part135 = 'yes';
                        }

                        $flightLegHtml .= '<tr>
                                        <td>*Prev Day</td>
                                        <td>'.$prevDayRes['start_hour'].':'.$prevDayRes['start_minute'].$prevDDate.'</td>
                                        <td>'.$prevDayRes['leg_hour'].':'.$prevDayRes['leg_minute'].'</td>
                                        <td>'.$prevDayRes['night_flt_hour'].':'.$prevDayRes['night_flt_minute'].'</td>
                                        <td>'.$prevDayRes['ifr_flight_hour'].':'.$prevDayRes['ifr_flight_minute'].'</td>
                                        <td>'.strtoupper($prevDayRes['approaches']).'</td>
                                        <td>'.$prevDayRes['landings'].'</td>
                                        <td>'.$prevDayRes['duty_position'].'</td>
                                        <td>'.$part135.'</td>
                                        <td></td>
                                    </tr>';
                    }
                }
            }

            //Date display on header
            $selDate = date('m/d/Y', strtotime($dateTitle));
            $nextDate = date('m/d/Y', strtotime("+1 day", strtotime($selDate)));
            $tHtml = '<div class="container">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <strong>Manage Duty Times</strong>
                                <table class="table table-borderless timesResCls">
                                    <thead>
                                        <tr>
                                            <th>--</th> 
                                            <th>Duty On</th>
                                            <th>Duty Off</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody class="dtHtmlClass">';
                                    if(!empty($dutyTimeHtml)) {
                                        $tHtml .= $dutyTimeHtml;
                                    } else {
                                        $tHtml .='<tr>
                                            <td colspan="4">No Duty Times Currently Logged</td>
                                        </tr>';
                                    }
                            $tHtml .='</tbody>
                                </table>
                                <button type="submit" class="btn btn-success addDutyTimeBtn" data-seldate="'.$selDate.'" data-pilot_id="'.$pilotId.'">Add New Duty Time</button>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <strong>Manage Day Off Times</strong>
                                <table class="table table-borderless timesResCls">
                                    <thead>
                                        <tr>
                                            <th>--</th>
                                            <th>Start Time</th>
                                            <th>Stop Time</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody class="doHtmlClass">';
                                    if(!empty($daysOffHtml)) {
                                        $tHtml .= $daysOffHtml;
                                    } else {
                                        $tHtml .='<tr>
                                            <td colspan="4">No Day Off Currently Scheduled</td>
                                        </tr>';
                                    }
                            $tHtml .='</tbody>
                                </table>
                                <button type="submit" class="btn btn-success addDayOffBtn" data-seldate="'.$selDate.'" data-nextdate="'.$nextDate.'" data-pilot_id="'.$pilotId.'">Schedule Day Off</button>
                            </div>
                        </div>

                        <div class="row">
                        <hr><br>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <strong>Flight Times</strong>
                                <table class="table table-borderless timesResCls">
                                    <thead>
                                        <tr>
                                            <th>--</th>
                                            <th>Start Time</th>
                                            <th>Leg Length</th>
                                            <th>Night FLT</th>
                                            <th>IFR FLT</th>
                                            <th>Approaches</th>
                                            <th>Landings</th>
                                            <th>Duty Position</th>
                                            <th>Part 135</th>
                                            <th>--</th>
                                        </tr>
                                    </thead>
                                    <tbody class="flHtmlClass">';
                                    if(!empty($flightLegHtml)) {
                                        $tHtml .= $flightLegHtml;
                                    } else {
                                        $tHtml .='<tr>
                                            <td colspan="10">No Flight Legs Currently Logged</td>
                                        </tr>';
                                    }
                            $tHtml .='</tbody>
                                </table>
                                <button type="submit" class="btn btn-success addFlightLegBtn" data-seldate="'.$selDate.'" data-pilot_id="'.$pilotId.'">Add New Flight Leg</button>
                            </div>
                        </div>
                    </div>';

            $res = array('status'=>'success', 'data'=>$tHtml, 'dateT'=>$dateTitle);
            echo json_encode($res);die;
        } else {
            $res = array('status'=>'failure', 'data'=>$tHtml);
            echo json_encode($res);die;
        }
    }

    //Add/Update Duty time record
    public function addPilotDutyTime()
    {
        $params = $this->request->data;
        if(!empty($params['pilot_id'])) {
            $dutyTimeModel = TableRegistry::get('DutyTimes');
            $result = $dutyTimeModel->savePilotDutyTimesData($params);
            
            if($result) {
                //Get duty time details to display
                $dtRes = $this->getDutyTime($params['pilot_id'], $params['selected_date']);

                $res = array('status'=>'success', 'message'=>'Pilot duty time added successfully.', 'dthtml'=>$dtRes);
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'message'=>'Please enter details.');
                echo json_encode($res);die;
            }
        } else {
            $res = array('status'=>'failure', 'message'=>'Please enter details.');
            echo json_encode($res);die;
        }
    }

    //Get duty time details of any date
    public function getDutyTime($pilotId, $selectedDate)
    {
        $tHtml = '';
        if(!empty($pilotId) && !empty($selectedDate)) {
            $selectedDate = date('Y-m-d', strtotime($selectedDate));
            $dutyTimeModel = TableRegistry::get('DutyTimes');
            $dtRes = $dutyTimeModel->find()->where(['DutyTimes.pilot_id'=>$pilotId, 'DutyTimes.selected_date'=>$selectedDate])->enableHydration(false)->toArray();

            $dutyTimeHtml = '';
            if(!empty($dtRes)) {
                $i = 1;
                foreach ($dtRes as $key => $value) {                    
                    //Check if record lies on next day
                    $nextDayDuty = '';
                    $timeD = $value['duty_stop_hour'].':'.$value['duty_stop_minute'];
                    if($value['duty_start_hour'] > $value['duty_stop_hour']) {
                        $nextDayDuty = " on ". date('M d', strtotime("+1 day", strtotime($value['selected_date'])));
                    }

                    $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($value['selected_date'])));
                    $prevDayRes = $this->Pilot->getPrevDateDuty($value['pilot_id'], $prevDate);
                    if(!empty($prevDayRes)) {
                        $prevDDate = " on ". date('M d', strtotime($prevDate));
                        if(($prevDayRes['duty_stop_hour']==0 || $prevDayRes['duty_stop_hour'] < $prevDayRes['duty_start_hour']) && ($prevDayRes['duty_stop_minute']==0 || $prevDayRes['duty_stop_minute'] > 0)) {
                            $i++;
                        }
                    }

                    $value = $this->Pilot->dtCorrectVal($value);
                    $dutyTimeHtml .= '<tr class="dtMsg">
                                        <td class="dtUpClass" data-dtid="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'">Duty Time '.$i.'</td>
                                        <td>'.$value['duty_start_hour'].':'.$value['duty_start_minute'].'</td>
                                        <td>'.$value['duty_stop_hour'].':'.$value['duty_stop_minute'].$nextDayDuty.'</td>
                                        <td class="dtDelClass" data-dtid="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'">X</td>
                                    </tr>';

                    //Display previous day time
                    if(!empty($prevDayRes)) {
                        if(($prevDayRes['duty_stop_hour']==0 || $prevDayRes['duty_stop_hour'] < $prevDayRes['duty_start_hour']) && ($prevDayRes['duty_stop_minute']==0 || $prevDayRes['duty_stop_minute'] > 0)) {
                            $prevDayRes = $this->Pilot->dtCorrectVal($prevDayRes);
                            $dutyTimeHtml .= '<tr>
                                            <td>*Prev Day</td>
                                            <td>'.$prevDayRes['duty_start_hour'].':'.$prevDayRes['duty_start_minute'].$prevDDate.'</td>
                                            <td>'.$prevDayRes['duty_stop_hour'].':'.$prevDayRes['duty_stop_minute'].'</td>
                                            <td></td>
                                        </tr>';
                        }
                    }

                    $i++;
                }
            } else {
                $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($selectedDate)));
                $prevDayRes = $this->Pilot->getPrevDateDuty($pilotId, $prevDate);
                if(!empty($prevDayRes)) {
                    $prevDDate = " on " . date('M d', strtotime($prevDate));
                    if(($prevDayRes['duty_stop_hour']==0 || $prevDayRes['duty_stop_hour'] < $prevDayRes['duty_start_hour']) && ($prevDayRes['duty_stop_minute']==0 || $prevDayRes['duty_stop_minute'] > 0)) {
                        $prevDayRes = $this->Pilot->dtCorrectVal($prevDayRes);
                        $dutyTimeHtml .= '<tr>
                                        <td>*Prev Day</td>
                                        <td>'.$prevDayRes['duty_start_hour'].':'.$prevDayRes['duty_start_minute'].$prevDDate.'</td>
                                        <td>'.$prevDayRes['duty_stop_hour'].':'.$prevDayRes['duty_stop_minute'].'</td>
                                        <td>&nbsp;</td>
                                    </tr>';
                    }
                }
            }

            if(!empty($dutyTimeHtml)) {
                $tHtml .= $dutyTimeHtml;
            } else {
                $tHtml .='<tr>
                    <td colspan="4">No Duty Times Currently Logged</td>
                </tr>';
            }                
        }
        return $tHtml;
    }

    //Add Days Off data
    public function addDaysOffData()
    {
        $params = $this->request->data;
        if(!empty($params['pilot_id'])) {
            $daysOffModel = TableRegistry::get('DaysOff');
            $result = $daysOffModel->saveDaysOffData($params);
            
            if($result) {
                //Get days off details to display
                $doRes = $this->getDaysOff($params['pilot_id'], $params['selected_date']);

                $res = array('status'=>'success', 'message'=>'Pilot days off added successfully.', 'dohtml'=>$doRes);
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'message'=>'Please enter details.');
                echo json_encode($res);die;
            }
        } else {
            $res = array('status'=>'failure', 'message'=>'Please enter details.');
            echo json_encode($res);die;
        }
    }

    //Get days off details of any date
    public function getDaysOff($pilotId, $selectedDate)
    {
        $tHtml = '';
        if(!empty($pilotId) && !empty($selectedDate)) {
            $selectedDate = date('Y-m-d', strtotime($selectedDate));
            $daysOffModel = TableRegistry::get('DaysOff');
            $doRes = $daysOffModel->find()->where(['DaysOff.pilot_id'=>$pilotId, 'DaysOff.selected_date'=>$selectedDate])->enableHydration(false)->toArray();

            $daysOffHtml = '';
            if(!empty($doRes)) {
                $i = 1;
                foreach ($doRes as $key => $value) {                    
                    $nextDayOff = " on " . date('M d', strtotime("+1 day", strtotime($value['selected_date'])));
                    $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($value['selected_date'])));
                    $prevDayRes = $this->Pilot->getPrevDateOff($value['pilot_id'], $prevDate);
                    if(!empty($prevDayRes)) {
                        $prevDDate = " on " . date('M d', strtotime($prevDate));
                        if(($prevDayRes['off_start_hour']==0 || $prevDayRes['off_start_hour']>0) && ($prevDayRes['off_start_minute']==0 || $prevDayRes['off_start_minute']>0)) {
                            $i++;
                        }
                    }

                    $value = $this->Pilot->doCorrectVal($value);                    
                    $daysOffHtml .= '<tr class="doMsg">
                                        <td class="doUpClass" data-doid="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'">Time '.$i.'</td>
                                        <td>'.$value['off_start_hour'].':'.$value['off_start_minute'].'</td>
                                        <td>'.$value['off_start_hour'].':'.$value['off_start_minute'].$nextDayOff.'</td>
                                        <td class="doDelClass" data-doid="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'">X</td>
                                    </tr>';

                    if(!empty($prevDayRes)) {
                        if(($prevDayRes['off_start_hour']==0 || $prevDayRes['off_start_hour']>0) && ($prevDayRes['off_start_minute']==0 || $prevDayRes['off_start_minute']>0)) {
                            $prevDayRes = $this->Pilot->doCorrectVal($prevDayRes);
                            $daysOffHtml .= '<tr>
                                            <td>*Prev Day</td>
                                            <td>'.$prevDayRes['off_start_hour'].':'.$prevDayRes['off_start_minute'].$prevDDate.'</td>
                                            <td>'.$prevDayRes['off_start_hour'].':'.$prevDayRes['off_start_minute'].'</td>
                                            <td></td>
                                        </tr>';
                        }
                    }
                    $i++;
                }
            } else {
                $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($selectedDate)));
                $prevDayRes = $this->Pilot->getPrevDateOff($pilotId, $prevDate);
                if(!empty($prevDayRes)) {                   
                    $prevDDate = " on " . date('M d', strtotime($prevDate));
                    if(($prevDayRes['off_start_hour']==0 || $prevDayRes['off_start_hour']>0) && ($prevDayRes['off_start_minute']==0 || $prevDayRes['off_start_minute']>0)) {
                        $prevDayRes = $this->Pilot->doCorrectVal($prevDayRes);
                        $daysOffHtml .= '<tr>
                                        <td>*Prev Day</td>
                                        <td>'.$prevDayRes['off_start_hour'].':'.$prevDayRes['off_start_minute'].$prevDDate.'</td>
                                        <td>'.$prevDayRes['off_start_hour'].':'.$prevDayRes['off_start_minute'].'</td>
                                        <td></td>
                                    </tr>';
                    }
                }
            }

            if(!empty($daysOffHtml)) {
                $tHtml .= $daysOffHtml;
            } else {
                $tHtml .='<tr>
                    <td colspan="4">No Day Off Currently Scheduled</td>
                </tr>';
            }                
        }
        return $tHtml;
    }

    //Add Flight Leg data
    public function addFlightLegData()
    {
        $params = $this->request->data;
        if(!empty($params['pilot_id'])) {
            $flightLegModel = TableRegistry::get('FlightLegDetails');
            $result = $flightLegModel->saveFlightLegData($params);
            
            if($result) {
                //Get flight leg details to display
                $flRes = $this->getFlightLeg($params['pilot_id'], $params['selected_date']);

                $res = array('status'=>'success', 'message'=>'Flight leg data added successfully.', 'flhtml'=>$flRes);
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'message'=>'Please enter details.');
                echo json_encode($res);die;
            }
        } else {
            $res = array('status'=>'failure', 'message'=>'Please enter details.');
            echo json_encode($res);die;
        }
    }

    //Get flight leg details of any date
    public function getFlightLeg($pilotId, $selectedDate)
    {
        $tHtml = '';
        if(!empty($pilotId) && !empty($selectedDate)) {
            $selectedDate = date('Y-m-d', strtotime($selectedDate));
            $flightLegModel = TableRegistry::get('FlightLegDetails');
            $flRes = $flightLegModel->find()->where(['FlightLegDetails.pilot_id'=>$pilotId, 'FlightLegDetails.selected_date'=>$selectedDate])->enableHydration(false)->toArray();

            $flightLegHtml = '';
            if(!empty($flRes)) {
                $i=$j=1;
                $countFl = count($flRes);
                foreach ($flRes as $key => $value) {
                    $value = $this->Pilot->flCorrectVal($value);

                    //Check if record lies on next day
                    $time1 = $value['start_hour'].':'.$value['start_minute'];
                    $time2 = $value['leg_hour'].':'.$value['leg_minute'];
                    $totalFH = $this->Pilot->addTimes(array($time1, $time2));                                                                               
                    $nextDayFL = '';
                    if(strtotime($totalFH) >= strtotime("24:00") && $countFl == $i) {
                        $nextDayFL = " on ". date('M d', strtotime("+1 day", strtotime($value['selected_date'])));
                    }

                    $part135 = 'no';
                    if($value['part_135']) {
                        $part135 = 'yes';
                    }

                    //Get previous day record
                    $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($value['selected_date'])));
                    $prevDayRes = $this->Pilot->getPrevDateFL($value['pilot_id'], $prevDate);
                    if(!empty($prevDayRes)) {
                        $prevDayRes = $this->Pilot->flCorrectVal($prevDayRes);

                        $prevDDate = " on ". date('M d', strtotime($prevDate));
                        $timeP1 = $prevDayRes['start_hour'].':'.$prevDayRes['start_minute'];
                        $timeP2 = $prevDayRes['leg_hour'].':'.$prevDayRes['leg_minute'];
                        $totalPFH = $this->Pilot->addTimes(array($timeP1, $timeP2));
                        //If previous day record exist then increase count once
                        if(strtotime($totalPFH) >= strtotime("24:00")) {
                            if($i<=2) {
                                $i++;
                            }
                        }
                    }
                    
                    //Normal record
                    $flightLegHtml .= '<tr class="flMsg">
                                        <td class="flUpClass" data-flid="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'">Leg '.$i.'</td>
                                        <td>'.$value['start_hour'].':'.$value['start_minute'].'</td>
                                        <td>'.$value['leg_hour'].':'.$value['leg_minute'].$nextDayFL.'</td>
                                        <td>'.$value['night_flt_hour'].':'.$value['night_flt_minute'].'</td>
                                        <td>'.$value['ifr_flight_hour'].':'.$value['ifr_flight_minute'].'</td>
                                        <td>'.strtoupper($value['approaches']).'</td>
                                        <td>'.$value['landings'].'</td>
                                        <td>'.$value['duty_position'].'</td>
                                        <td>'.$part135.'</td>
                                        <td class="flDelClass" data-flid="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'">X</td>
                                    </tr>';

                    
                    //Display previous day flight leg
                    if(!empty($prevDayRes)) {
                        if(strtotime($totalPFH) >= strtotime("24:00") && $countFl == $j) {
                            $part135 = 'no';
                            if($prevDayRes['part_135']) {
                                $part135 = 'yes';
                            }

                            $flightLegHtml .= '<tr>
                                            <td>*Prev Day</td>
                                            <td>'.$prevDayRes['start_hour'].':'.$prevDayRes['start_minute'].$prevDDate.'</td>
                                            <td>'.$prevDayRes['leg_hour'].':'.$prevDayRes['leg_minute'].'</td>
                                            <td>'.$prevDayRes['night_flt_hour'].':'.$prevDayRes['night_flt_minute'].'</td>
                                            <td>'.$prevDayRes['ifr_flight_hour'].':'.$prevDayRes['ifr_flight_minute'].'</td>
                                            <td>'.strtoupper($prevDayRes['approaches']).'</td>
                                            <td>'.$prevDayRes['landings'].'</td>
                                            <td>'.$prevDayRes['duty_position'].'</td>
                                            <td>'.$part135.'</td>
                                            <td></td>
                                        </tr>';
                        }
                    }
                    $i++;
                    $j++;
                }
            } else {
                //Display previous day flight leg
                $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($selectedDate)));
                $prevDayRes = $this->Pilot->getPrevDateFL($pilotId, $prevDate);
                if(!empty($prevDayRes)) {
                    $prevDayRes = $this->Pilot->flCorrectVal($prevDayRes);

                    $prevDDate = " on ". date('M d', strtotime($prevDate));
                    $timeP1 = $prevDayRes['start_hour'].':'.$prevDayRes['start_minute'];
                    $timeP2 = $prevDayRes['leg_hour'].':'.$prevDayRes['leg_minute'];
                    $totalPFH = $this->Pilot->addTimes(array($timeP1, $timeP2));
                    
                    if(strtotime($totalPFH) >= strtotime("24:00")) {
                        $part135 = 'no';
                        if($prevDayRes['part_135']) {
                            $part135 = 'yes';
                        }

                        $flightLegHtml .= '<tr>
                                        <td>*Prev Day</td>
                                        <td>'.$prevDayRes['start_hour'].':'.$prevDayRes['start_minute'].$prevDDate.'</td>
                                        <td>'.$prevDayRes['leg_hour'].':'.$prevDayRes['leg_minute'].'</td>
                                        <td>'.$prevDayRes['night_flt_hour'].':'.$prevDayRes['night_flt_minute'].'</td>
                                        <td>'.$prevDayRes['ifr_flight_hour'].':'.$prevDayRes['ifr_flight_minute'].'</td>
                                        <td>'.strtoupper($prevDayRes['approaches']).'</td>
                                        <td>'.$prevDayRes['landings'].'</td>
                                        <td>'.$prevDayRes['duty_position'].'</td>
                                        <td>'.$part135.'</td>
                                        <td></td>
                                    </tr>';
                    }
                }
            }

            if(!empty($flightLegHtml)) {
                $tHtml .= $flightLegHtml;
            } else {
                $tHtml .='<tr>
                    <td colspan="10">No Flight Legs Currently Logged</td>
                </tr>';
            }                
        }
        return $tHtml;
    }

    //Duty Time Update popup
    public function updateDutyTimePopup()
    {
        $params = $this->request->getData();
        //pr($params);die;
        if(!empty($params['pilotId']) && !empty($params['dtId'])) {
            $dutyTimeModel = TableRegistry::get('DutyTimes');
            $res = $dutyTimeModel->find()->where(['DutyTimes.id'=>$params['dtId'], 'DutyTimes.pilot_id'=>$params['pilotId']])->first();
            
            $dtHtml = '';
            if(!empty($res)) {
                $selDate = date('m/d/Y', strtotime($res['selected_date']));
                $dtHtml .= '<div>
                                <input type="hidden" name="id" value="'.$res['id'].'">
                                <input type="hidden" name="pilot_id" value="'.$res['pilot_id'].'">
                                <input type="hidden" name="selected_date" value="'.$selDate.'">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Duty On Time (hrs/mins)</label>
                                        <select name="duty_start_hour" class="form-control col-md-6 col-xs-12 dutyStartHourCls">';
                                            for ($i=0; $i < 24; $i++) {
                                                if($i<10) {
                                                    $hour = '0'.$i;
                                                } else {
                                                    $hour = $i;
                                                }

                                                if($res['duty_start_hour'] == $i) {
                                                    $dtHtml .= '<option value="'.$i.'" selected>'.$hour.'</option>';    
                                                } else {
                                                    $dtHtml .='<option value="'.$i.'">'.$hour.'</option>';
                                                }
                                            }
                                    $dtHtml .= '</select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;</label>
                                        <select name="duty_start_minute" class="form-control col-md-6 col-xs-12 dutyStartMinutCls">';
                                            for ($i=0; $i < 60; $i++) {
                                                if($i<10) {
                                                    $time = '0'.$i;
                                                } else {
                                                    $time = $i;
                                                }

                                                if($res['duty_start_minute'] == $i) {
                                                    $dtHtml .= '<option value="'.$i.'" selected>'.$time.'</option>';    
                                                } else {
                                                    $dtHtml .='<option value="'.$i.'">'.$time.'</option>';
                                                }     
                                            } 
                                    $dtHtml .= '</select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Duty Off Time (hrs/mins)</label>
                                        <select name="duty_stop_hour" class="form-control col-md-6 col-xs-12 dutyStopHourCls">';
                                            for ($i=0; $i < 24; $i++) {
                                                if($i<10) {
                                                    $hour = '0'.$i;
                                                } else {
                                                    $hour = $i;
                                                }

                                                if($res['duty_stop_hour'] == $i) {
                                                    $dtHtml .= '<option value="'.$i.'" selected>'.$hour.'</option>';    
                                                } else {
                                                    $dtHtml .='<option value="'.$i.'">'.$hour.'</option>';
                                                }
                                            }
                                    $dtHtml .= '</select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;</label>
                                        <select name="duty_stop_minute" class="form-control col-md-6 col-xs-12 dutyStopMinutCls">';
                                            for ($i=0; $i < 60; $i++) {
                                                if($i<10) {
                                                    $time = '0'.$i;
                                                } else {
                                                    $time = $i;
                                                }

                                                if($res['duty_stop_minute'] == $i) {
                                                    $dtHtml .= '<option value="'.$i.'" selected>'.$time.'</option>';    
                                                } else {
                                                    $dtHtml .='<option value="'.$i.'">'.$time.'</option>';
                                                }     
                                            } 
                                    $dtHtml .= '</select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Required Rest</label>
                                        <select name="required_rest" class="form-control col-md-6 col-xs-12 requiredRestCls">';
                                            $restArr = [0, 8, 9, 10, 11, 12, 16];
                                            foreach ($restArr as $key => $value) {
                                                if($res['required_rest'] == $value && $res['required_rest'] == 0) {
                                                    $dtHtml .= '<option value="'.$value.'" selected>00 Hours (Part 91)</option>';
                                                } elseif($res['required_rest'] != $value && $value == 0) {
                                                    $dtHtml .= '<option value="'.$value.'">00 Hours (Part 91)</option>';
                                                } elseif($res['required_rest'] == $value && $res['required_rest'] != 0) {
                                                    $dtHtml .= '<option value="'.$value.'" selected>'.$value.' Hours</option>';
                                                } else {
                                                    $dtHtml .= '<option value="'.$value.'">'.$value.' Hours</option>';
                                                }
                                            }
                                    $dtHtml .= '</select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Duty Classification</label>
                                        <select name="classification" class="form-control col-md-6 col-xs-12 classifiCls">';
                                            $restArr = ['flight', 'training', 'admin', 'on_call', 'travel'];
                                            foreach ($restArr as $key => $value) {
                                                $mVal = $value;
                                                $temp = explode('_', $value);
                                                if(count($temp) > 1) {
                                                    $mVal = ucfirst($temp[0]).' '.ucfirst($temp[1]);
                                                }

                                                if($res['classification'] == $value) {
                                                    $dtHtml .= '<option value="'.$value.'" selected>'.ucfirst($mVal).'</option>';
                                                } else {
                                                    $dtHtml .= '<option value="'.$value.'">'.ucfirst($mVal).'</option>';
                                                }
                                            }
                                    $dtHtml .= '</select>
                                    </div>
                                </div>                                
                            </div>';

                $res = array('status'=>'success', 'data'=>$dtHtml);
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'data'=>$dtHtml);
                echo json_encode($res);die;
            }
        }
    }

    //Delete duty time record
    public function deleteDutyTimeRec()
    {
        $params = $this->request->data;        
        if(!empty($params['dtId']) && !empty($params['pilotId'])) 
        {
            $dutyTimeModel = TableRegistry::get('DutyTimes');
            $dutyTimeRec = $dutyTimeModel->get($params['dtId']);

            if($dutyTimeModel->delete($dutyTimeRec)) {
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

    //Days Off Update popup
    public function updateDaysOffPopup()
    {
        $params = $this->request->getData();
        //pr($params);die;
        if(!empty($params['pilotId']) && !empty($params['doId'])) {
            $daysOffModel = TableRegistry::get('DaysOff');
            $res = $daysOffModel->find()->where(['DaysOff.id'=>$params['doId'], 'DaysOff.pilot_id'=>$params['pilotId']])->first();
            
            $doHtml = '';
            if(!empty($res)) {
                $selDate = date('m/d/Y', strtotime($res['selected_date']));
                $nextDate = date('m/d/Y', strtotime("+1 day", strtotime($selDate)));
                $doHtml .= '<div>
                                <input type="hidden" name="id" value="'.$res['id'].'">
                                <input type="hidden" name="pilot_id" value="'.$res['pilot_id'].'">
                                <input type="hidden" name="selected_date" value="'.$selDate.'">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Day Off Start Time (hrs/mins)</label>
                                        <select name="off_start_hour" class="form-control col-md-6 col-xs-12">';
                                            for ($i=0; $i < 24; $i++) {
                                                if($i<10) {
                                                    $hour = '0'.$i;
                                                } else {
                                                    $hour = $i;
                                                }

                                                if($res['off_start_hour'] == $i) {
                                                    $doHtml .= '<option value="'.$i.'" selected>'.$hour.'</option>';    
                                                } else {
                                                    $doHtml .='<option value="'.$i.'">'.$hour.'</option>';
                                                }
                                            }
                                    $doHtml .= '</select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;</label>
                                        <select name="off_start_minute" class="form-control col-md-6 col-xs-12 dispMinutCls">';
                                            for ($i=0; $i < 60; $i++) {
                                                if($i<10) {
                                                    $time = '0'.$i;
                                                } else {
                                                    $time = $i;
                                                }

                                                if($res['off_start_minute'] == $i) {
                                                    $doHtml .= '<option value="'.$i.'" selected>'.$time.'</option>';    
                                                } else {
                                                    $doHtml .='<option value="'.$i.'">'.$time.'</option>';
                                                }     
                                            } 
                                    $doHtml .= '</select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Start Date</label>
                                        <input type="text" name="start_date" class="form-control col-md-7 col-xs-12 daysOffStart datePicker" value="'.$selDate.'" disabled="disabled">
                                    </div>

                                    <div class="col-md-6">
                                        <label>End Date</label>
                                        <input type="text" name="end_date" class="form-control col-md-7 col-xs-12 daysOffEnd datePicker" value="'.$nextDate.'">
                                    </div>
                                </div>                             
                            </div>';

                $res = array('status'=>'success', 'data'=>$doHtml);
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'data'=>$doHtml);
                echo json_encode($res);die;
            }
        }
    }

    //Delete days off time record
    public function deleteDaysOffRec()
    {
        $params = $this->request->data;        
        if(!empty($params['doId']) && !empty($params['pilotId'])) 
        {
            $daysOffModel = TableRegistry::get('DaysOff');
            $daysOffRec = $daysOffModel->get($params['doId']);

            if($daysOffModel->delete($daysOffRec)) {
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

    //Flight Leg Update popup
    public function updateFlightLegPopup()
    {
        $params = $this->request->getData();
        if(!empty($params['pilotId']) && !empty($params['flId'])) {
            $flightLegModel = TableRegistry::get('FlightLegDetails');
            $res = $flightLegModel->find()->where(['FlightLegDetails.id'=>$params['flId'], 'FlightLegDetails.pilot_id'=>$params['pilotId']])->first();
            
            $flHtml = '';
            if(!empty($res)) {
                $selDate = date('m/d/Y', strtotime($res['selected_date']));
                $flHtml .= '<div>
                                <input type="hidden" name="id" value="'.$res['id'].'">
                                <input type="hidden" name="pilot_id" value="'.$res['pilot_id'].'">
                                <input type="hidden" name="selected_date" value="'.$selDate.'">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Start Time (hrs/mins)</label>
                                        <select name="start_hour" class="form-control col-md-6 col-xs-12 startHourCls">';
                                            for ($i=0; $i < 24; $i++) { 
                                                if($i<10) {
                                                    $hour = '0'.$i;
                                                } else {
                                                    $hour = $i;
                                                }

                                                if($res['start_hour'] == $i) {
                                                    $flHtml .= '<option value="'.$i.'" selected>'.$hour.'</option>';    
                                                } else {
                                                    $flHtml .='<option value="'.$i.'">'.$hour.'</option>';
                                                }
                                            }
                                    $flHtml .= '</select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;</label>
                                        <select name="start_minute" class="form-control col-md-6 col-xs-12 startMinutCls">';
                                            for ($i=0; $i < 60; $i++) {
                                                if($i<10) {
                                                    $time = '0'.$i;
                                                } else {
                                                    $time = $i;
                                                }

                                                if($res['start_minute'] == $i) {
                                                    $flHtml .= '<option value="'.$i.'" selected>'.$time.'</option>';    
                                                } else {
                                                    $flHtml .='<option value="'.$i.'">'.$time.'</option>';
                                                }     
                                            } 
                                    $flHtml .= '</select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Leg Length (hrs/mins)</label>
                                        <select name="leg_hour" class="form-control col-md-6 col-xs-12 legHourCls">';
                                            for ($i=0; $i < 24; $i++) { 
                                                if($i<10) {
                                                    $hour = '0'.$i;
                                                } else {
                                                    $hour = $i;
                                                }

                                                if($res['leg_hour'] == $i) {
                                                    $flHtml .= '<option value="'.$i.'" selected>'.$hour.'</option>';    
                                                } else {
                                                    $flHtml .='<option value="'.$i.'">'.$hour.'</option>';
                                                }
                                            }
                                    $flHtml .= '</select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;</label>
                                        <select name="leg_minute" class="form-control col-md-6 col-xs-12 legMinutCls">';
                                            for ($i=0; $i < 60; $i++) {
                                                if($i<10) {
                                                    $time = '0'.$i;
                                                } else {
                                                    $time = $i;
                                                }

                                                if($res['leg_minute'] == $i) {
                                                    $flHtml .= '<option value="'.$i.'" selected>'.$time.'</option>';    
                                                } else {
                                                    $flHtml .='<option value="'.$i.'">'.$time.'</option>';
                                                }     
                                            } 
                                    $flHtml .= '</select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Night FLT</label>
                                        <select name="night_flt_hour" class="form-control col-md-6 col-xs-12 fltHourCls">';
                                            for ($i=0; $i < 24; $i++) { 
                                                if($i<10) {
                                                    $hour = '0'.$i;
                                                } else {
                                                    $hour = $i;
                                                }

                                                if($res['night_flt_hour'] == $i) {
                                                    $flHtml .= '<option value="'.$i.'" selected>'.$hour.'</option>';    
                                                } else {
                                                    $flHtml .='<option value="'.$i.'">'.$hour.'</option>';
                                                }
                                            }
                                    $flHtml .= '</select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;</label>
                                        <select name="night_flt_minute" class="form-control col-md-6 col-xs-12 fltMinutCls">';
                                            for ($i=0; $i < 60; $i++) {
                                                if($i<10) {
                                                    $time = '0'.$i;
                                                } else {
                                                    $time = $i;
                                                }

                                                if($res['night_flt_minute'] == $i) {
                                                    $flHtml .= '<option value="'.$i.'" selected>'.$time.'</option>';    
                                                } else {
                                                    $flHtml .='<option value="'.$i.'">'.$time.'</option>';
                                                }     
                                            } 
                                    $flHtml .= '</select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>IFR FLT</label>
                                        <select name="ifr_flight_hour" class="form-control col-md-6 col-xs-12 ifrHourCls">';
                                            for ($i=0; $i < 24; $i++) { 
                                                if($i<10) {
                                                    $hour = '0'.$i;
                                                } else {
                                                    $hour = $i;
                                                }

                                                if($res['ifr_flight_hour'] == $i) {
                                                    $flHtml .= '<option value="'.$i.'" selected>'.$hour.'</option>';    
                                                } else {
                                                    $flHtml .='<option value="'.$i.'">'.$hour.'</option>';
                                                }
                                            }
                                    $flHtml .= '</select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>&nbsp;</label>
                                        <select name="ifr_flight_minute" class="form-control col-md-6 col-xs-12 ifrMinutCls">';
                                            for ($i=0; $i < 60; $i++) {
                                                if($i<10) {
                                                    $time = '0'.$i;
                                                } else {
                                                    $time = $i;
                                                }

                                                if($res['ifr_flight_minute'] == $i) {
                                                    $flHtml .= '<option value="'.$i.'" selected>'.$time.'</option>';    
                                                } else {
                                                    $flHtml .='<option value="'.$i.'">'.$time.'</option>';
                                                }     
                                            } 
                                    $flHtml .= '</select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Approaches</label>
                                        <select name="approaches" class="form-control col-md-6 col-xs-12 approachesCls">
                                            <option value="">--</option>';
                                            $restArr = ['vis', 'vor', 'gps', 'loc', 'ils', 'ndb'];
                                            foreach ($restArr as $key => $value) {
                                                if($res['approaches'] == $value) {
                                                    $flHtml .= '<option value="'.$value.'" selected>'.strtoupper($value).'</option>';
                                                } else {
                                                    $flHtml .= '<option value="'.$value.'">'.strtoupper($value).'</option>';
                                                }
                                            }
                                    $flHtml .= '</select>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Landings</label>
                                        <select name="landings" class="form-control col-md-6 col-xs-12 landingCls">
                                            <option value="">--</option>';
                                            $restArr = ['day', 'night'];
                                            foreach ($restArr as $key => $value) {
                                                if($res['landings'] == $value) {
                                                    $flHtml .= '<option value="'.$value.'" selected>'.strtoupper($value).'</option>';
                                                } else {
                                                    $flHtml .= '<option value="'.$value.'">'.strtoupper($value).'</option>';
                                                }
                                            }
                                    $flHtml .= '</select>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Duty Position</label>
                                        <select name="duty_position" class="form-control col-md-6 col-xs-12 dutyPositionCls">
                                            <option value="">--</option>';
                                            $restArr = ['pic', 'sic'];
                                            foreach ($restArr as $key => $value) {
                                                if($res['duty_position'] == $value) {
                                                    $flHtml .= '<option value="'.$value.'" selected>'.strtoupper($value).'</option>';
                                                } else {
                                                    $flHtml .= '<option value="'.$value.'">'.strtoupper($value).'</option>';
                                                }
                                            }
                                    $flHtml .= '</select>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">';
                                        $chk = '';
                                        if($res['part_135']) {
                                            $chk = "checked='checked'";
                                        }
                                    $flHtml .= 'Part 135 <input type="checkbox" name="part_135" '.$chk.'>
                                    </div>
                                </div>                                
                            </div>';

                $res = array('status'=>'success', 'data'=>$flHtml);
                echo json_encode($res);die;
            } else {
                $res = array('status'=>'failure', 'data'=>$dtHtml);
                echo json_encode($res);die;
            }
        }
    }

    //Delete flight leg record
    public function deleteFlightLegRec()
    {
        $params = $this->request->data;        
        if(!empty($params['flId']) && !empty($params['pilotId'])) 
        {
            $flightLegModel = TableRegistry::get('FlightLegDetails');
            $flightLegRec = $flightLegModel->get($params['flId']);

            if($flightLegModel->delete($flightLegRec)) {
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

    //Check if duty time overlap duty time or days off
    public function checkDutyTimeOverlap()
    {
        $params = $this->request->data;
        if(!empty($params['pilot_id']) && !empty($params['selected_date']) && ((!empty($params['duty_start_hour']) || !empty($params['duty_start_minute'])) || (!empty($params['duty_stop_hour']) || !empty($params['duty_stop_minute'])))) {
            $selDate = date('Y-m-d', strtotime($params['selected_date']));
            
            //Duty time record
            $dutyTimeModel = TableRegistry::get('DutyTimes');
            $resultDT = $dutyTimeModel->find()
                                    ->where(['DutyTimes.pilot_id'=>$params['pilot_id'], 'DutyTimes.selected_date'=>$selDate])
                                    ->select(['duty_start_hour', 'duty_start_minute', 'duty_stop_hour', 'duty_stop_minute'])
                                    ->enableHydration(false)
                                    ->first();

            //Days off record
            $daysOffModel = TableRegistry::get('DaysOff');
            $resultDO = $daysOffModel->find()
                                    ->where(['DaysOff.pilot_id'=>$params['pilot_id'], 'DaysOff.selected_date'=>$selDate])
                                    ->select(['off_start_hour', 'off_start_minute'])
                                    ->enableHydration(false)
                                    ->first();

            if(!empty($resultDT) && (((strtotime($params['duty_start_hour'].':'.$params['duty_start_minute']) >= strtotime($resultDT['duty_start_hour'].':'.$resultDT['duty_start_minute'])) && (strtotime($params['duty_start_hour'].':'.$params['duty_start_minute']) >= strtotime($resultDT['duty_stop_hour'].':'.$resultDT['duty_stop_minute']))))) {
                
                $res = array('status'=>'success', 'message'=>'Continue to add record.');
                echo json_encode($res);die;

            } elseif(!empty($resultDT) && (((strtotime($params['duty_start_hour'].':'.$params['duty_start_minute']) >= strtotime($resultDT['duty_start_hour'].':'.$resultDT['duty_start_minute'])) && (strtotime($params['duty_start_hour'].':'.$params['duty_start_minute']) <= strtotime($resultDT['duty_stop_hour'].':'.$resultDT['duty_stop_minute']))) || ((strtotime($params['duty_stop_hour'].':'.$params['duty_stop_minute']) >= strtotime($resultDT['duty_start_hour'].':'.$resultDT['duty_start_minute'])) && (strtotime($params['duty_stop_hour'].':'.$params['duty_stop_minute']) <= strtotime($resultDT['duty_stop_hour'].':'.$resultDT['duty_stop_minute']))) || ((strtotime($params['duty_start_hour'].':'.$params['duty_start_minute']) <= strtotime($resultDT['duty_start_hour'].':'.$resultDT['duty_start_minute'])) && (strtotime($params['duty_stop_hour'].':'.$params['duty_stop_minute']) >= strtotime($resultDT['duty_stop_hour'].':'.$resultDT['duty_stop_minute']))))) {
                
                $res = array('status'=>'failure', 'message'=>'The duty time you are trying to save overlaps with one of your existing duty time. Please enter a valid duty time before continuing.');
                echo json_encode($res);die;

            } elseif(!empty($resultDO) && ((strtotime($params['duty_start_hour'].':'.$params['duty_start_minute']) >= strtotime($resultDO['off_start_hour'].':'.$resultDO['off_start_minute'])) && (strtotime($params['duty_stop_hour'].':'.$params['duty_stop_minute']) <= strtotime($resultDO['off_start_hour'].':'.$resultDO['off_start_minute'])))) {

                $res = array('status'=>'failure', 'message'=>'The duty time you are trying to save overlaps with one of your existing days off. Please fix the problem before continuing.');
                echo json_encode($res);die;

            } else {
                $res = array('status'=>'success', 'message'=>'Continue to add record.');
                echo json_encode($res);die;
            }
        } else {
            $res = array('status'=>'failure', 'message'=>'Please enter a valid duty time.');
            echo json_encode($res);die;
        }
    }

    //Check if days off overlap duty time or days off
    public function checkDaysOffOverlap()
    {
        $params = $this->request->data;
        //pr($params);die;
        if(!empty($params['pilot_id']) && !empty($params['selected_date'])) {
            $selDate = date('Y-m-d', strtotime($params['selected_date']));
            
            //Duty time record
            $dutyTimeModel = TableRegistry::get('DutyTimes');
            $resultDT = $dutyTimeModel->find()
                                    ->where(['DutyTimes.pilot_id'=>$params['pilot_id'], 'DutyTimes.selected_date'=>$selDate])
                                    ->select(['duty_start_hour', 'duty_start_minute', 'duty_stop_hour', 'duty_stop_minute'])
                                    ->enableHydration(false)
                                    ->first();

            //Days off record
            $daysOffModel = TableRegistry::get('DaysOff');
            $resultDO = $daysOffModel->find()
                                    ->where(['DaysOff.pilot_id'=>$params['pilot_id'], 'DaysOff.selected_date'=>$selDate])
                                    ->select(['off_start_hour', 'off_start_minute'])
                                    ->enableHydration(false)
                                    ->first();

            if(!empty($resultDT) && (!empty($params['off_start_hour']) || !empty($params['off_start_minute'])) && ((strtotime($params['off_start_hour'].':'.$params['off_start_minute']) >= strtotime($resultDT['duty_start_hour'].':'.$resultDT['duty_start_minute'])) && (strtotime($params['off_start_hour'].':'.$params['off_start_minute']) <= strtotime($resultDT['duty_stop_hour'].':'.$resultDT['duty_stop_minute'])))) {
                
                $res = array('status'=>'failure', 'message'=>'The days off you are trying to save overlaps with one of your existing duty time. Please delete the overlapping duty times before continuing.');
                echo json_encode($res);die;

            } elseif(!empty($resultDT) && (empty($params['off_start_hour']) && empty($params['off_start_minute']))) {
                
                $res = array('status'=>'failure', 'message'=>'The days off you are trying to save overlaps with one of your existing duty time. Please delete the overlapping duty times before continuing.');
                echo json_encode($res);die;

            } elseif(!empty($resultDO) && ((strtotime($params['off_start_hour'].':'.$params['off_start_minute']) >= strtotime($resultDO['off_start_hour'].':'.$resultDO['off_start_minute'])) && (strtotime($params['off_start_hour'].':'.$params['off_start_minute']) <= strtotime($resultDO['off_start_hour'].':'.$resultDO['off_start_minute'])))) {

                $res = array('status'=>'failure', 'message'=>'The days off you are trying to save overlaps with one of your existing days off. Please fix the problem before continuing.');
                echo json_encode($res);die;

            } else {
                $res = array('status'=>'success', 'message'=>'Continue to add record.');
                echo json_encode($res);die;
            }
        } else {
            $res = array('status'=>'failure', 'message'=>'Please enter a valid days off.');
            echo json_encode($res);die;
        }
    }

    //Check if flight leg overlap flight leg or cross duty time
    public function checkFlightLegOverlap()
    {
        $params = $this->request->data;
        if(!empty($params['pilot_id']) && !empty($params['selected_date']) && (!empty($params['leg_hour']) || !empty($params['leg_minute']))) {
            $selDate = date('Y-m-d', strtotime($params['selected_date']));
            $prevDate = date('Y-m-d', strtotime("-1 day", strtotime($params['selected_date'])));
            
            //Duty time record
            $dutyTimeModel = TableRegistry::get('DutyTimes');
            $resultDT = $dutyTimeModel->find()
                                    ->where(['DutyTimes.pilot_id'=>$params['pilot_id'], 'DutyTimes.selected_date'=>$selDate])
                                    ->select(['duty_start_hour', 'duty_start_minute', 'duty_stop_hour', 'duty_stop_minute'])
                                    ->enableHydration(false)
                                    ->first();
            //pr($resultDT);
            //Previous day duty time record
            $prevResDT = $dutyTimeModel->find()
                                    ->where(['DutyTimes.pilot_id'=>$params['pilot_id'], 'DutyTimes.selected_date'=>$prevDate])
                                    ->select(['duty_start_hour', 'duty_start_minute', 'duty_stop_hour', 'duty_stop_minute'])
                                    ->enableHydration(false)
                                    ->first();                       
            //Days off record
            $daysOffModel = TableRegistry::get('DaysOff');
            $resultDO = $daysOffModel->find()
                                    ->where(['DaysOff.pilot_id'=>$params['pilot_id'], 'DaysOff.selected_date'=>$selDate])
                                    ->select(['off_start_hour', 'off_start_minute'])
                                    ->enableHydration(false)
                                    ->first();
            //Previous days off record
            $prevResDO = $daysOffModel->find()
                                    ->where(['DaysOff.pilot_id'=>$params['pilot_id'], 'DaysOff.selected_date'=>$prevDate])
                                    ->select(['off_start_hour', 'off_start_minute'])
                                    ->enableHydration(false)
                                    ->first();

            //Flight leg records
            $flightLegModel = TableRegistry::get('FlightLegDetails');
            $resultFL = $flightLegModel->find()
                                    ->where(['FlightLegDetails.pilot_id'=>$params['pilot_id'], 'FlightLegDetails.selected_date'=>$selDate])
                                    ->enableHydration(false)
                                    ->toArray();

            //Remaining Hours if crossed 24 hour
            $time1 = $params['start_hour'].':'.$params['start_minute'];
            $time2 = $params['leg_hour'].':'.$params['leg_minute'];
            $timeResult = $this->Pilot->addTwoTimes($time1, $time2);
            $resflHI = $timeResult['resflHI'];

            //Total hours
            $totalFH = $this->Pilot->addTimes(array($time1, $time2));

            $totalDO = 0;
            if(!empty($resultDO['off_start_hour']) || !empty($resultDO['off_start_minute'])) {
                $totalDO = $this->Pilot->addTimes(array($resultDO['off_start_hour'].':'.$resultDO['off_start_minute'], '24:00'));
            }

            /*if(!empty($resultDT) && ((strtotime($time1) < strtotime($resultDT['duty_start_hour'].':'.$resultDT['duty_start_minute'])) || (strtotime($resflHI) > strtotime($resultDT['duty_stop_hour'].':'.$resultDT['duty_stop_minute']) && strtotime($resultDT['duty_start_hour'].':'.$resultDT['duty_start_minute']) < strtotime($resultDT['duty_stop_hour'].':'.$resultDT['duty_stop_minute'])))) {
                
                $res = array('status'=>'failure', 'message'=>'The leg time you are trying to enter is not covered by a valid duty time. Please enter a valid duty time before continuing11.');
                echo json_encode($res);die;

            } else*/if(!empty($resultDO) && ((strtotime($time1) >= strtotime($resultDO['off_start_hour'].':'.$resultDO['off_start_minute'])) || (strtotime($totalFH) <= strtotime($totalDO)))) {

                $res = array('status'=>'failure', 'message'=>'The time you are trying to save overlaps with one of your days off. Please correct the problem before continuing.');
                echo json_encode($res);die;

            }  elseif(!empty($prevResDO) && ($prevResDO['off_start_hour'] > 0 || $prevResDO['off_start_minute'] > 0)) {
                
                $res = array('status'=>'failure', 'message'=>'The leg time you are trying to enter is not covered by a valid duty time. Please enter a valid duty time before continuing.');
                echo json_encode($res);die;

            } elseif (!empty($resultDT) && !empty($resultFL)) {
                foreach ($resultFL as $key => $value) {
                    
                    $flAdd = $this->Pilot->addTimes(array($value['start_hour'].':'.$value['start_minute'], $value['leg_hour'].':'.$value['leg_minute']));

                    if(strtotime($time1) >= strtotime($value['start_hour'].':'.$value['start_minute']) && strtotime($time1) <= strtotime($flAdd)) {
                        
                        $res = array('status'=>'failure', 'message'=>'The time you are trying to save overlaps with another one of your times. Please correct the problem before continuing.');
                        echo json_encode($res);die;

                    } elseif (strtotime($resflHI) >= strtotime($value['start_hour'].':'.$value['start_minute']) && strtotime($resflHI) <= strtotime($flAdd)) {
                        $res = array('status'=>'failure', 'message'=>'The time you are trying to save overlaps with another one of your times. Please correct the problem before continuing.');
                        echo json_encode($res);die;

                    } elseif (strtotime($time1) <= strtotime($value['start_hour'].':'.$value['start_minute']) && strtotime($resflHI) >= strtotime($flAdd)) {
                        $res = array('status'=>'failure', 'message'=>'The time you are trying to save overlaps with another one of your times. Please correct the problem before continuing.');
                        echo json_encode($res);die;
                    } else {
                        $res = array('status'=>'success', 'message'=>'Continue to add record.');
                        echo json_encode($res);die;
                    }
                }
            } /*elseif(empty($resultDT) && empty($prevResDT)) {
                $res = array('status'=>'failure', 'message'=>'The leg time you are trying to enter is not covered by a valid duty time. Please enter a valid duty time before continuing33.');
                echo json_encode($res);die;
            }*/ else {
                $res = array('status'=>'success', 'message'=>'Continue to add record.');
                echo json_encode($res);die;
            }
        } else {
            $res = array('status'=>'failure', 'message'=>'Please enter a valid leg time.');
            echo json_encode($res);die;
        }
    }

    //Crew reports
    public function crewReports()
    {
        $params = $this->request->data;

        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Pilot Duty', $actionStatus))
            {
                $actionItems = $actionStatus['Pilot Duty'];
            }
        }

        $pilotId = !empty($_GET['pilotId']) ? $_GET['pilotId'] : '';
        if(empty($pilotId)) {
            $res = $this->Pilots->find()->select('id')->first();

            if(!empty($res)) {
                $pilotId = $res->id;
            }
        }
        
        $pilotComp = $this->Pilot;
        $this->set(compact('actionItems', 'pilotId', 'pilotComp'));
    }

    //Get crew report
    public function getCrewReports()
    {
        $params = $this->request->data;

        if(!empty($params['pilotid']) && !empty($params['startDate']) && !empty($params['endDate'])) {
            $pilotId = $params['pilotid'];
            $startDate = date('Y-m-d', strtotime($params['startDate']));
            $endDate = date('Y-m-d', strtotime($params['endDate']));
            //Duty time conditions
            $dutyTimeCond = function ($q) use ($pilotId, $startDate, $endDate) {
                    return $q->where(['DutyTimes.pilot_id'=>$pilotId, 'DutyTimes.selected_date >='=>$startDate, 'DutyTimes.selected_date <'=>$endDate])
                            ->select(['id', 'pilot_id', 'duty_start_hour', 'duty_start_minute', 'duty_stop_hour', 'duty_stop_minute', 'required_rest', 'classification', 'selected_date'])
                            ->order(['DutyTimes.selected_date'=>'ASC']);
                };

            //Days off conditions
            $daysOffCond = function ($q) use ($pilotId, $startDate, $endDate) {
                    return $q->where(['DaysOff.pilot_id'=>$pilotId, 'DaysOff.selected_date >='=>$startDate, 'DaysOff.selected_date <='=>$endDate])
                            ->select(['id', 'pilot_id', 'off_start_hour', 'off_start_minute', 'start_date', 'end_date', 'selected_date'])
                            ->order(['DaysOff.selected_date'=>'ASC']);
                };

            //Flight leg conditions
            $flightLegCond = function ($q) use ($pilotId, $startDate, $endDate) {
                    return $q->where(['FlightLegDetails.pilot_id'=>$pilotId, 'FlightLegDetails.selected_date >='=>$startDate, 'FlightLegDetails.selected_date <'=>$endDate])
                        ->select(['id', 'pilot_id', 'start_hour', 'start_minute', 'leg_hour', 'leg_minute', 'night_flt_hour', 'night_flt_minute', 'ifr_flight_hour', 'ifr_flight_minute', 'approaches', 'landings', 'duty_position', 'part_135', 'selected_date'])
                        ->order(['FlightLegDetails.selected_date'=>'ASC']);
                };

            //Get records
            $records = $this->Pilots->find()
                                    ->where(['Pilots.id'=>$pilotId])
                                    ->contain([
                                        'Users'=>[
                                            'fields'=>['full_name']
                                        ],
                                        'DutyTimes'=>$dutyTimeCond, 
                                        'DaysOff'=>$daysOffCond, 
                                        'FlightLegDetails'=>$flightLegCond
                                    ])
                                    ->select(['Pilots.id', 'Pilots.user_id', 'Pilots.certificate_number'])
                                    ->enableHydration(false)
                                    ->first();

            $results = [];
            if(!empty($records)) {
                $results['full_name'] = $records['user']['full_name'];
                $results['certificate_number'] = $records['certificate_number'];
                $results['date_range'] = $params['startDate'].' - '.$params['endDate'];
                                
                //Total times
                if(!empty($records['duty_times'])) {
                    //Duty time records
                    $dutyTime = [];
                    $dutyTimeAdmin = [];
                    foreach ($records['duty_times'] as $key => $value) {
                        //If time lied in next day
                        if(strtotime($value['duty_stop_hour'].':'.$value['duty_stop_minute']) < strtotime($value['duty_start_hour'].':'.$value['duty_start_minute'])) {
                            $dutyTime[] = $this->Pilot->addTimes(array($this->Pilot->timeDiffFL($value['duty_start_hour'].':'.$value['duty_start_minute'], $value['duty_stop_hour'].':'.$value['duty_stop_minute']), $value['duty_stop_hour'].':'.$value['duty_stop_minute']));
                            
                        } else {
                            $dutyTime[] = $this->Pilot->timeDiffFL($value['duty_start_hour'].':'.$value['duty_start_minute'], $value['duty_stop_hour'].':'.$value['duty_stop_minute']);
                        }
                        
                        if($value['classification'] == 'admin') {
                            $dutyTimeAdmin[] = $this->Pilot->timeDiffFL($value['duty_start_hour'].':'.$value['duty_start_minute'], $value['duty_stop_hour'].':'.$value['duty_stop_minute']);
                        }
                    }

                    //Duty time
                    if(!empty($dutyTime)) {
                        $results['duty_time'] = $this->Pilot->addTimes($dutyTime);
                    } else {
                        $results['duty_time'] = '00:00';
                    }
                    
                    //Admin duty time
                    if(!empty($dutyTimeAdmin)) {
                        $results['admin_duty_time'] = $this->Pilot->addTimes($dutyTimeAdmin);
                    } else {
                        $results['admin_duty_time'] = '00:00';
                    }
                } else {
                    $results['duty_time'] = '00:00';
                    $results['admin_duty_time'] = '00:00';
                }

                if(!empty($records['flight_leg_details'])) {
                    //Flight leg time records
                    $flightTime = [];
                    $picFltTime = [];
                    $sicFltTime = [];
                    $ifrFltTime = [];
                    $approaches = [];
                    $dayLandings = [];
                    $nightFltTime = [];
                    $nightLandings = [];
                    foreach ($records['flight_leg_details'] as $key => $value) {
                        $flightTime[] = $value['leg_hour'].':'.$value['leg_minute'];

                        if($value['duty_position'] == 'pic') {
                            $picFltTime[] = $value['leg_hour'].':'.$value['leg_minute'];
                        } elseif ($value['duty_position'] == 'sic') {
                            $sicFltTime[] = $value['leg_hour'].':'.$value['leg_minute'];
                        }

                        $nightFltTime[] = $value['night_flt_hour'].':'.$value['night_flt_minute'];
                        $ifrFltTime[] = $value['ifr_flight_hour'].':'.$value['ifr_flight_minute'];
                        $approaches[] = $value['approaches'];
                        
                        if($value['landings'] == 'day') {
                            $dayLandings[] = $value['landings'];
                        }

                        if($value['landings'] == 'night') {
                            $nightLandings[] = $value['landings'];
                        }
                    }

                    //flight time
                    if(!empty($flightTime)) {
                        $results['flight_time'] = $this->Pilot->addTimes($flightTime);
                    } else {
                        $results['flight_time'] = '00:00';
                    }

                    //pic flt time
                    if(!empty($picFltTime)) {
                        $results['pic_flt_time'] = $this->Pilot->addTimes($picFltTime);
                    } else {
                        $results['pic_flt_time'] = '00:00';
                    }

                    //sic flt time
                    if(!empty($sicFltTime)) {
                        $results['sic_flt_time'] = $this->Pilot->addTimes($sicFltTime);
                    } else {
                        $results['sic_flt_time'] = '00:00';
                    }

                    //night flt time
                    if(!empty($nightFltTime)) {
                        $results['night_flt_time'] = $this->Pilot->addTimes($nightFltTime);
                    } else {
                        $results['night_flt_time'] = '00:00';
                    }

                    //ifr flt time
                    if(!empty($ifrFltTime)) {
                        $results['ifr_flt_time'] = $this->Pilot->addTimes($ifrFltTime);
                    } else {
                        $results['ifr_flt_time'] = '00:00';
                    }

                    //approaches
                    if(!empty($approaches)) {
                        $results['approaches'] = count($approaches);
                    } else {
                        $results['approaches'] = 0;
                    }

                    //Day landings
                    if(!empty($dayLandings)) {
                        $results['day_landings'] = count($dayLandings);
                    } else {
                        $results['day_landings'] = 0;
                    }

                    //Night landings
                    if(!empty($nightLandings)) {
                        $results['night_landings'] = count($nightLandings);
                    } else {
                        $results['night_landings'] = 0;
                    }

                } else {
                    $results['flight_time'] = '00:00';
                    $results['pic_flt_time'] = '00:00';
                    $results['sic_flt_time'] = '00:00';
                    $results['night_flt_time'] = '00:00';
                    $results['ifr_flt_time'] = '00:00';
                    $results['approaches'] = 0;
                    $results['day_landings'] = 0;
                    $results['night_landings'] = 0;
                }

                if(!empty($records['days_off'])) {
                    //Days off records
                    $daysOff = [];
                    foreach ($records['days_off'] as $key => $value) {
                        $daysOff[] = 24;
                    }

                    //days off
                    if(!empty($daysOff)) {
                        $results['days_off'] = $this->Pilot->addTimes($daysOff);
                    } else {
                        $results['days_off'] = '00:00';
                    }
                } else {
                    $results['days_off'] = '00:00';
                }

                //Detailed time html -----------------------------------------------------
                $align = "";
                if(!empty($params['type']) && $params['type'] == 'pdf') {
                    $align = "left";
                }

                $detailedHtml = '';
                if(!empty($records['duty_times']) || !empty($records['flight_leg_details']) || !empty($records['days_off'])) {
                    $begin = new \DateTime($startDate);
                    $end = new \DateTime($endDate);

                    $interval = \DateInterval::createFromDateString('1 day');
                    $period = new \DatePeriod($begin, $interval, $end);
                    
                    foreach ($period as $dt) {
                        //Days off
                        foreach ($records['days_off'] as $key => $value) {
                            if(strtotime($dt->format("Y-m-d")) == strtotime($value['selected_date'])) {
                                $value = $this->Pilot->doCorrectVal($value);
                                $offStartD = date('m/d/Y', strtotime($value['start_date']));
                                $offEndD = date('m/d/Y', strtotime($value['end_date']));
                                $detailedHtml .= '<tr><td colspan="11" style="font-weight:bold;text-align:center;">TIME OFF '.$offStartD.' '.$value['off_start_hour'].':'.$value['off_start_minute'].' - '.$offEndD.' '.$value['off_start_hour'].':'.$value['off_start_minute'].'</td></tr>';
                            }
                        }

                        foreach ($records['duty_times'] as $key => $value) {
                            if(strtotime($dt->format("Y-m-d")) == strtotime($value['selected_date'])) {
                                $value = $this->Pilot->dtCorrectVal($value);
                                
                                $classification = '';
                                if($value['classification'] != 'flight') {
                                    $temp = explode('_', $value['classification']);
                                    if(count($temp) > 1) {
                                        $classification = '('.ucfirst($temp[0]).' '.ucfirst($temp[1]).')';
                                    } else {
                                        $classification = '('.ucfirst($value['classification']).')';
                                    }
                                }

                                $dtEndDate = date('m/d/Y', strtotime($value['selected_date']));
                                if(strtotime($value['duty_start_hour'].':'.$value['duty_start_minute']) > strtotime($value['duty_stop_hour'].':'.$value['duty_stop_minute'])) {
                                    $dtEndDate = date('m/d/Y', strtotime("+1 day", strtotime($value['selected_date'])));
                                }
                                $detailedHtml .= '<tr><td style="text-align:'.$align.'">'.$dt->format("m/d/Y").' '.$value['duty_start_hour'].':'.$value['duty_start_minute'].' - '.$dtEndDate.' '.$value['duty_stop_hour'].':'.$value['duty_stop_minute'].' '.$classification.'</td>';

                                //Duty length
                                $detailedHtml .= '<td>'.$this->Pilot->dutyTimeLength($value['duty_start_hour'].':'.$value['duty_start_minute'], $value['duty_stop_hour'].':'.$value['duty_stop_minute']).'</td>';
                            
                                //------------------------
                                //Check if current date FL is lies in duty time
                                $currentFL = $this->Pilot->getCurrentDateFL($pilotId, $value['selected_date']);
                                
                                if(empty($currentFL)) {
                                    $detailedHtml .= '<td>-</td><td>-</td><td>-</td><td colspan="6"></td><td>-</td></tr>';
                                }

                                if(!empty($records['flight_leg_details'])) {
                                    $i = 1;
                                    foreach ($records['flight_leg_details'] as $key2 => $value2) {
                                        //echo $key2.' - '.$value['selected_date'].' - '.$value2['selected_date']."<br>";
                                        $prevSelDate = date('Y-m-d', strtotime("-1 day", strtotime($value2['selected_date'])));
                                        $legTime = $this->Pilot->addTimes(array($value2['start_hour'].':'.$value2['start_minute'], $value2['leg_hour'].':'.$value2['leg_minute']));

                                        $dutyStopHr = strtotime($value['duty_stop_hour'].':'.$value['duty_stop_minute']);
                                        if(strtotime($value['duty_start_hour'].':'.$value['duty_start_minute']) > strtotime($value['duty_stop_hour'].':'.$value['duty_stop_minute'])) {
                                            $dutyStopHr = strtotime('24:00');
                                        }

                                        if(strtotime($value['selected_date']) == strtotime($value2['selected_date']) && strtotime($value['duty_start_hour'].':'.$value['duty_start_minute']) <= strtotime($value2['start_hour'].':'.$value2['start_minute']) && strtotime($legTime) <= $dutyStopHr && strtotime($value['duty_start_hour'].':'.$value['duty_start_minute']) <= $dutyStopHr) {
                                            $value2 = $this->Pilot->flCorrectVal($value2);
                                            $airType = !empty($value2['part_135']) ? 135 : 91; 
                                            if($i == 1) {
                                                $detailedHtml .= '<td>'.$value2['start_hour'].':'.$value2['start_minute'].'</td><td>'.$value2['leg_hour'].':'.$value2['leg_minute'].'</td><td>'.$value2['night_flt_hour'].':'.$value2['night_flt_minute'].'</td><td>'.$value2['ifr_flight_hour'].':'.$value2['ifr_flight_minute'].'</td><td>'.strtoupper($value2['approaches']).'</td><td>'.strtoupper($value2['landings']).'</td><td>'.strtoupper($value2['duty_position']).'</td><td>'.$airType.'</td><td>P180</td></tr>';
                                                $i++;
                                            } elseif($i > 1) {
                                                $detailedHtml .= '<tr><td>'.$classification.'</td><td></td><td>'.$value2['start_hour'].':'.$value2['start_minute'].'</td><td>'.$value2['leg_hour'].':'.$value2['leg_minute'].'</td><td>'.$value2['night_flt_hour'].':'.$value2['night_flt_minute'].'</td><td>'.$value2['ifr_flight_hour'].':'.$value2['ifr_flight_minute'].'</td><td>'.strtoupper($value2['approaches']).'</td><td>'.strtoupper($value2['landings']).'</td><td>'.strtoupper($value2['duty_position']).'</td><td>'.$airType.'</td><td>P180</td></tr>';
                                            }

                                        } elseif(strtotime($value['selected_date']) == strtotime($prevSelDate) && strtotime($legTime) <= strtotime($value['duty_stop_hour'].':'.$value['duty_stop_minute']) && !empty($currentFL)) {
                                            //echo $value['selected_date'] .' - '. $value2['selected_date']."<br>";
                                            //$res = $this->prevDateFL($pilotId, $prevSelDate);
                                            $value2 = $this->Pilot->flCorrectVal($value2);
                                            $airType = !empty($value2['part_135']) ? 135 : 91; 
                                            if($i == 1) {
                                                $detailedHtml .= '<td>'.$value2['start_hour'].':'.$value2['start_minute'].'</td><td>'.$value2['leg_hour'].':'.$value2['leg_minute'].'</td><td>'.$value2['night_flt_hour'].':'.$value2['night_flt_minute'].'</td><td>'.$value2['ifr_flight_hour'].':'.$value2['ifr_flight_minute'].'</td><td>'.strtoupper($value2['approaches']).'</td><td>'.strtoupper($value2['landings']).'</td><td>'.strtoupper($value2['duty_position']).'</td><td>'.$airType.'</td><td>P180</td></tr>';
                                                $i++;
                                            } elseif($i > 1) {
                                                $detailedHtml .= '<tr><td>'.$classification.'</td><td></td><td>'.$value2['start_hour'].':'.$value2['start_minute'].'</td><td>'.$value2['leg_hour'].':'.$value2['leg_minute'].'</td><td>'.$value2['night_flt_hour'].':'.$value2['night_flt_minute'].'</td><td>'.$value2['ifr_flight_hour'].':'.$value2['ifr_flight_minute'].'</td><td>'.strtoupper($value2['approaches']).'</td><td>'.strtoupper($value2['landings']).'</td><td>'.strtoupper($value2['duty_position']).'</td><td>'.$airType.'</td><td>P180</td></tr>';
                                            }
                                        }
                                    }
                                } else {
                                    $detailedHtml .= '<td colspan="10"></td></tr>';
                                }
                                //------------------------
                            }
                        }
                    }
                } else {
                    $detailedHtml = '<tr>
                                    <td colspan="11" style="text-align:center;">No Records Found.</td>
                                </tr>';
                }
                //End Detailed time -----------------------------------------------------
            }

            //If request for pdf
            $crewArray = [];
            if(!empty($params['type']) && $params['type'] == 'pdf') {
                $crewArray['reportTimes'] = $results;
                $crewArray['detailedTimes'] = $detailedHtml;
                return $crewArray;
            } else {
                $res = array('status'=>'success', 'data'=>$results, 'detail'=>$detailedHtml);
                echo json_encode($res);die;
            }
        } else {
            //If request for pdf
            $crewArray = [];
            if(!empty($params['type']) && $params['type'] == 'pdf') {
                $crewArray['reportTimes'] = $results;
                return $crewArray;
            } else {
                $res = array('status'=>'failure', 'data'=>$results);
                echo json_encode($res);die;
            }
        }
    }

    //Generate crew report pdf
    public function crewReportPdf()
    {
        $this->autoRender = false;
        $params = $this->request->data;

        //Get crew data
        $result = $this->getCrewReports($params);

        $resHtml = '';
        if(!empty($result)) {
            $resHtml='<div style="background-color: #fff; font-size: 12px; color: #000; border-color:#000;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div style="width:30%;float:left;padding-top:10px;">Pilot Name:</div>
                                <div style="width:70%;float:right;padding-top:10px;">'.$result['reportTimes']['full_name'].'</div>
                            </div>
                            <div class="form-group">
                                <div style="width:30%;float:left;padding-top:10px;">Pilot Certificate Number:</div>
                                <div style="width:70%;float:right;padding-top:10px;">'.$result['reportTimes']['certificate_number'].'</div>
                            </div>

                            <div class="form-group">
                                <div style="width:30%;float:left;padding-top:10px;">Report Range:</div>
                                <div style="width:70%;float:right;padding-top:10px;">'.$result['reportTimes']['date_range'].'</div>
                            </div>
                        </div>
                        
                        <h2 style="margin-top:40px; font-size: 24px;font-weight: 100;">Total Times</h2>
                        <div class="table-responsive">
                            <table class="table table-striped" width="100%" style="font-size:12px; text-align:center; font-family: "open sans", "Helvetica Neue", Helvetica, Arial, sans-serif;" cellspacing="0" cellpadding="5">
                                <thead>
                                    <tr>
                                        <th>Duty Time</th>
                                        <th>Admin Duty Time</th>
                                        <th>Flight Time</th>
                                        <th>PIC FLT Time</th>
                                        <th>SIC FLT Time</th>
                                        <th>Night Flight Time</th>
                                        <th>IFR Flight time</th>
                                        <th>Approaches</th>
                                        <th>Day Landings</th>
                                        <th>Night Landings</th>
                                        <th>Time Off</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr> 
                                        <td colspan="11"> <hr style="border-top:1px solid #e7eaec;" /> </td>      
                                    </tr>
                                    <tr>
                                        <td>'.$result['reportTimes']['duty_time'].'</td>
                                        <td>'.$result['reportTimes']['admin_duty_time'].'</td>
                                        <td>'.$result['reportTimes']['flight_time'].'</td>
                                        <td>'.$result['reportTimes']['pic_flt_time'].'</td>
                                        <td>'.$result['reportTimes']['sic_flt_time'].'</td>
                                        <td>'.$result['reportTimes']['night_flt_time'].'</td>
                                        <td>'.$result['reportTimes']['ifr_flt_time'].'</td>
                                        <td>'.$result['reportTimes']['approaches'].'</td>
                                        <td>'.$result['reportTimes']['day_landings'].'</td>
                                        <td>'.$result['reportTimes']['night_landings'].'</td>
                                        <td>'.$result['reportTimes']['days_off'].'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                   
                        <h2 style="margin-top:30px;font-size: 24px;font-weight: 100;">Detailed Times</h2>
                        <div class="table-responsive">
                            <table class="table table-striped" width="100%" style="font-size:12px; text-align:center; line-height:1.8; font-family: "open sans", "Helvetica Neue", Helvetica, Arial, sans-serif;" cellspacing="0" cellpadding="5">
                                <thead>
                                    <tr>
                                        <th style="text-align:left;width:33%;">Duty Time</th>
                                        <th style="width:8%;">Start Time</th>
                                        <th style="width:8%;">Leg Length</th>
                                        <th style="width:8%;">Night FLT</th>
                                        <th style="width:8%;">IFR FLT</th>
                                        <th style="width:7%;">Approach</th>
                                        <th style="width:7%;">Landing</th>
                                        <th style="width:7%;">Duty Position</th>
                                        <th style="width:7%;">Part</th>
                                        <th style="width:7%;">Aircraft Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr> 
                                        <td colspan="10"> <hr style="border-top:1px solid #e7eaec;" /> </td>      
                                    </tr>'.$result['detailedTimes'].'</tbody>
                            </table>
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
                            .page_break { 
                                page-break-before: always; 
                            }
                        </style>
                    </head>
                    <body>
                        <main>'.$resHtml.'</main>        
                    </body>
                </html>';
       
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

        $mpdf->WriteHTML($html);

        $fileName = "crewReport".date('YmdHis').".pdf";
        $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
        if($resHtml) {
            $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'data'=>'');
            echo json_encode($result);die;
        }
    }

    //Crew documents
    public function crewDocuments()
    {
        $params = $this->request->data;

        $actionItems='';
        if($this->Auth->user('id') != 1) {
            $actionStatus = $this->checkAction();
            if(array_key_exists('Pilot Duty', $actionStatus))
            {
                $actionItems = $actionStatus['Pilot Duty'];
            }
        }

        $pilotId = !empty($_GET['pilotId']) ? $_GET['pilotId'] : '';
        if(empty($pilotId)) {
            $res = $this->Pilots->find()->select('id')->first();
            if(!empty($res)) {
                $pilotId = $res->id;
            }
        }

        $pilotComp = $this->Pilot;
        $this->set(compact('actionItems', 'pilotId', 'pilotComp'));
    }

    //Get Crew Documents
    public function getCrewDocuments()
    {
        $params = $this->request->data;
        if(!empty($params['pilotid'])) {
            $docsModel = TableRegistry::get('Documents');
            $records = $docsModel->find()
                                    ->where(['Documents.pilot_id'=>$params['pilotid']])
                                    ->enableHydration(false)
                                    ->toArray();
            $results = [];
            $documentsHtml = '';
            $trainingHtml  = '';
            $checkingHtml  = '';
            $drugHtml = '';
            $priaHtml = '';
            $docCount = 0;
            $trnCount = 0;
            $chkCount = 0;
            $drgCount = 0;
            $prnCount = 0;
            $blankHtml = '<div class="displayDoc">
                            <h5><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;No Documents Found</h5>
                        </div>';
            if(!empty($records)) {
                foreach ($records as $key => $value) {
                    $docDate = date('m/d/Y', strtotime($value['document_date']));

                    if(!empty($value['document_name'])) {
                        $docName = $value['document_name'].'<br><span class="dateStyl">'.$docDate.'</span>';
                    } else {
                        $docName = $value['document_category']."-".explode('.', $value['file_name'])[0].'<br><span class="dateStyl">'.$docDate.'</span>';
                    }

                    if($value['document_category'] == 'documents') {
                        $documentsHtml .= '<div class="displayDoc">
                                            <h5><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;'.$docName.'</h5>
                                            <div class="docAction"><span><i class="fa fa-edit actionCls" data-id="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'"></i>&nbsp;&nbsp;<i class="fa fa-trash actionCls" data-id="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'"></i></span></div>
                                        </div>';
                        $docCount++; 
                    } elseif($value['document_category'] == 'training') {
                        $trainingHtml .= '<div class="displayDoc">
                                            <h5><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;'.$docName.'</h5>
                                            <div class="docAction"><span><i class="fa fa-edit actionCls" data-id="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'"></i>&nbsp;&nbsp;<i class="fa fa-trash actionCls" data-id="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'"></i></span></div>
                                        </div>';
                        $trnCount++;
                    } elseif($value['document_category'] == 'checking') {
                        $checkingHtml .= '<div class="displayDoc">
                                            <h5><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;'.$docName.'</h5>
                                            <div class="docAction"><span><i class="fa fa-edit actionCls" data-id="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'"></i>&nbsp;&nbsp;<i class="fa fa-trash actionCls" data-id="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'"></i></span></div>
                                        </div>';
                        $chkCount++;
                    } elseif($value['document_category'] == 'drug') {
                        $drugHtml .= '<div class="displayDoc">
                                            <h5><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;'.$docName.'</h5>
                                            <div class="docAction"><span><i class="fa fa-edit actionCls" data-id="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'"></i>&nbsp;&nbsp;<i class="fa fa-trash actionCls" data-id="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'"></i></span></div>
                                        </div>';
                        $drgCount++;
                    } elseif($value['document_category'] == 'pria') {
                        $priaHtml .= '<div class="displayDoc">
                                            <h5><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;'.$docName.'</h5>
                                            <div class="docAction"><span><i class="fa fa-edit actionCls" data-id="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'"></i>&nbsp;&nbsp;<i class="fa fa-trash actionCls" data-id="'.$value['id'].'" data-pilot_id="'.$value['pilot_id'].'"></i></span></div>
                                        </div>';
                        $prnCount++;
                    }
                }

                $results['documents'] = !empty($documentsHtml) ? $documentsHtml : $blankHtml;
                $results['training'] = !empty($trainingHtml) ? $trainingHtml : $blankHtml;
                $results['checking'] = !empty($checkingHtml) ? $checkingHtml : $blankHtml;
                $results['drug'] = !empty($drugHtml) ? $drugHtml : $blankHtml;
                $results['pria'] = !empty($priaHtml) ? $priaHtml : $blankHtml;
                $results['docCount'] = $docCount;
                $results['trnCount'] = $trnCount;
                $results['chkCount'] = $chkCount;
                $results['drgCount'] = $drgCount;
                $results['prnCount'] = $prnCount;
                $res = array('status'=>'success', 'data'=>$results);
                echo json_encode($res);die;
            } else {
                $results['documents'] = $blankHtml;
                $results['training'] = $blankHtml;
                $results['checking'] = $blankHtml;
                $results['drug'] = $blankHtml;
                $results['pria'] = $blankHtml;
                $results['docCount'] = $docCount;
                $results['trnCount'] = $trnCount;
                $results['chkCount'] = $chkCount;
                $results['drgCount'] = $drgCount;
                $results['prnCount'] = $prnCount;
                $res = array('status'=>'failure', 'data'=>$results);
                echo json_encode($res);die;
            }
        }
 
    }

    //Upload docs and save details
    public function uploadDocs()
    {
        $params = $this->request->data;
        if(!empty($params['file_name']) && isset($params['file_name']['tmp_name'])) 
        {
            $temp = $params['file_name']['tmp_name'];
            $name = $params['file_name']['name'];
            $ext = substr(strrchr($name , '.'), 1);
            $arr_ext = array('pdf');
            $setNewFileName =  date('YmdHis').".pdf";
            $params['file_name'] =  $setNewFileName;
            //pr($params);die;
            if (in_array($ext, $arr_ext)) {
                if(move_uploaded_file($temp, WWW_ROOT . 'documents/' . $setNewFileName)) {
                    $docsModel = TableRegistry::get('Documents');
                    $documents = $docsModel->newEntity(); 
                    $documents = $docsModel->patchEntity($documents, $params);
                    //pr($documents);die;
                    if($docsModel->save($documents)) {
                        $result = array('status'=>'success', 'message'=>"Saved successfully.");
                    }                    
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong.');
                }
            } else {
                $result = array('status'=>'failed', 'message'=>'Please upload correct format.');
            }
            
            echo json_encode($result);die;
        } else {
            $result = array('status'=>'failure', 'message'=>'Something went wrong.');
            echo json_encode($res);die;
        }
    }

    //Update docs popup
    public function updateDocsPopup()
    {
        $params = $this->request->data;

        if(!empty($params['id']) || !empty($params['pilotid'])) {
            $docsModel = TableRegistry::get('Documents');
            $record = $docsModel->find()
                                    ->where(['Documents.id'=>$params['id'], 'Documents.pilot_id'=>$params['pilotid']])
                                    ->enableHydration(false)
                                    ->first();
            //pr($record);die;
            $editHtml = '';
            if(!empty($record)) {
                if(!empty($record['document_name'])) {
                    $docName = $record['document_name'];
                } else {
                    $docName = $record['document_category']."-".explode('.', $record['file_name'])[0];
                }

                $docNone = 'style="display:none;" disabled';
                if($record['document_category'] == 'documents') {
                    $docNone = 'style="display:block;"';
                }

                $trainNone = 'style="display:none;" disabled';
                if($record['document_category'] == 'training') {
                    $trainNone = 'style="display:block;"';
                } 

                $checkNone = 'style="display:none;" disabled';
                if($record['document_category'] == 'checking') {
                    $checkNone = 'style="display:block;"' ;
                } 

                $drugNone = 'style="display:none;" disabled';
                if($record['document_category'] == 'drug') {
                    $drugNone = 'style="display:block;"';
                } 

                $priaNone = 'style="display:none;" disabled';
                if($record['document_category'] == 'pria') {
                    $priaNone = 'style="display:block;"';
                }

                $docsDate = date('m/d/Y', strtotime($record['document_date']));

                $editHtml .= '<div>
                                <input type="hidden" name="id" value="'.$record['id'].'">
                                <input type="hidden" name="pilot_id" value="'.$record['pilot_id'].'">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label>Currently uploaded file</label> <input type="checkbox" name="new_file" id="docDiffFile" style="margin-left:40px;"> Upload a different file
                                        <div class="form-control" style="display: block;border: 1px solid #ccc;background-color: #ddd;">'.$docName.'</div>
                                    </div>
                                    <div class="col-md-12" id="uploadNewFile" style="display:none;">
                                        <label>Browse to upload</label>
                                        <input type="file" name="file_name" id="updateFileNameId" class="form-control" disabled>
                                    </div>  
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label>Document Category</label>
                                        <select id="updateCrewDocsCategory" name="document_category" class="form-control" data-document_type="'.$record['document_type'].'">';
                                            $catsArr = ['documents'=>'Crew Documents', 'training'=>'Training Records', 'checking'=>'Checking Currency', 'drug'=>'Drug & Alcohol Testing', 'pria'=>'PRIA'];
                                            foreach ($catsArr as $key => $value) {
                                                if($record['document_category'] == $key) {
                                                    $editHtml .= '<option value="'.$key.'" selected>'.$value.'</option>';
                                                } else {
                                                    $editHtml .= '<option value="'.$key.'">'.$value.'</option>';
                                                }
                                            }
                                $editHtml .= '</select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label>Document Type</label>';
                                $editHtml .= '<select id="updateCrewDocuments" name="document_type" class="form-control updateDocNameHide" '.$docNone.'>';
                                            $dtArr = ['Pilot Certificate'=>'Pilot Certificate', 'Photo ID'=>'Photo ID', 'Current Medical'=>'Current Medical', 'Current Passport'=>'Current Passport', 'Pilot Qualifications Record'=>'Pilot Qualifications Record', 'Radiotelephone Operators Permit'=>'Radiotelephone Operators Permit', 'Pilot Resume'=>'Pilot Resume', 'Company Flight Instructor LOA'=>'Company Flight Instructor LOA', 'Other'=>'Other'];
                                            foreach ($dtArr as $key => $value) {
                                                if($record['document_type'] == $value) {
                                                    $editHtml .= '<option value="'.$value.'" selected>'.$value.'</option>';
                                                } else {
                                                    $editHtml .= '<option value="'.$value.'">'.$value.'</option>';
                                                }
                                            }
                                $editHtml .= '</select>';
                                $editHtml .= '<select id="updateCrewTraining" name="document_type" class="form-control updateDocNameHide" '.$trainNone.'>';
                                        $dtArr = ['Initial New Hire Basic INDOC'=>'Initial New Hire Basic INDOC', 'Initial Flight/SIM Training'=>'Initial Flight/SIM Training', 'Annual Recurrent Ground Training'=>'Annual Recurrent Ground Training', 'Recurrent Flight/SIM Training'=>'Recurrent Flight/SIM Training', 'RVSM Training'=>'RVSM Training', 'Instructor/Check Airman Training'=>'Instructor/Check Airman Training', 'Security Training'=>'Security Training', 'Other'=>'Other'];
                                            foreach ($dtArr as $key => $value) {
                                                if($record['document_type'] == $value) {
                                                    $editHtml .= '<option value="'.$value.'" selected>'.$value.'</option>';
                                                } else {
                                                    $editHtml .= '<option value="'.$value.'">'.$value.'</option>';
                                                }
                                            }
                                $editHtml .= '</select>';
                                $editHtml .= '<select id="updateCrewChecking" name="document_type" class="form-control updateDocNameHide" '.$checkNone.'>';
                                        $catsArr = ['293 (a) 1, 4-8 General Operations'=>'293 (a) 1, 4-8 General Operations', '293 (a) 2-3 (b) Aircraft Specific'=>'293 (a) 2-3 (b) Aircraft Specific', '297 Instrument Proficiency Check'=>'297 Instrument Proficiency Check', '293 (a) 2-3 (b) / 297 Combo Check'=>'293 (a) 2-3 (b) / 297 Combo Check', '299 Line check'=>'299 Line check', 'Other'=>'Other'];
                                            foreach ($catsArr as $key => $value) {
                                                if($record['document_type'] == $key) {
                                                    $editHtml .= '<option value="'.$key.'" selected>'.$value.'</option>';
                                                } else {
                                                    $editHtml .= '<option value="'.$key.'">'.$value.'</option>';
                                                }
                                            }
                                $editHtml .= '</select>';
                                $editHtml .= '<select id="updateCrewDrug" name="document_type" class="form-control updateDocNameHide" '.$drugNone.'>';
                                        $dtArr = ['Pre-Employment'=>'Pre-Employment', 'Random'=>'Random', 'Reasonable Suspicion'=>'Reasonable Suspicion', 'Post Accident'=>'Post Accident', 'Return to Duty'=>'Return to Duty', 'Follow Up'=>'Follow Up', 'Other'=>'Other'];
                                            foreach ($dtArr as $key => $value) {
                                                if($record['document_type'] == $value) {
                                                    $editHtml .= '<option value="'.$value.'" selected>'.$value.'</option>';
                                                } else {
                                                    $editHtml .= '<option value="'.$value.'">'.$value.'</option>';
                                                }
                                            }
                                $editHtml .= '</select>';
                                $editHtml .= '<select id="updateCrewPria" name="document_type" class="form-control updateDocNameHide" '.$priaNone.'>';
                                        $dtArr = ['Previous Employment Records'=>'Previous Employment Records', 'FAA Records'=>'FAA Records', 'Driving Background Check'=>'Driving Background Check', 'Drug and Alcohol Testing Records'=>'Drug and Alcohol Testing Records', 'Other'=>'Other'];
                                            foreach ($dtArr as $key => $value) {
                                                if($record['document_type'] == $value) {
                                                    $editHtml .= '<option value="'.$value.'" selected>'.$value.'</option>';
                                                } else {
                                                    $editHtml .= '<option value="'.$value.'">'.$value.'</option>';
                                                }
                                            }
                                $editHtml .= '</select>';
                                $editHtml .= '</div>
                                </div>';

                                if($record['document_type'] == 'Other') {
                                    $editHtml .= '<div class="form-group" id="updateDocNameContainer" style="display:block;">
                                        <div class="col-md-12">
                                            <label>Type a Name for the Document</label>
                                            <input type="text" name="document_name" id="updateDocNameId" class="form-control" value="'.$record['document_name'].'">
                                        </div>
                                    </div>';
                                } else {
                                    $editHtml .= '<div class="form-group" id="updateDocNameContainer" style="display:none;">
                                        <div class="col-md-12">
                                            <label>Type a Name for the Document</label>
                                            <input type="text" name="document_name" id="updateDocNameId" class="form-control" disabled>
                                        </div>
                                    </div>';
                                }

                                $optArray = ['Initial Flight/SIM Training', 'Recurrent Flight/SIM Training', 'Instructor/Check Airman Training', '293 (a) 2-3 (b) Aircraft Specific', '293 (a) 2-3 (b) / 297 Combo Check'];
                                if(in_array($record['document_type'], $optArray)) {
                                    $editHtml .= '<div class="form-group" id="updateDocAircraftContainer" style="display:block;">
                                        <div class="col-md-12">
                                            <label>Select an Aircraft Type Designation</label>
                                            <select id="updateAircraftDocuments" name="aircraft_designation" class="form-control">';
                                            $adArr = ['P180'=>'P180', 'BE20'=>'BE20'];
                                            foreach ($adArr as $key => $value) {
                                                if($record['aircraft_designation'] == $value) {
                                                    $editHtml .= '<option value="'.$value.'" selected>'.$value.'</option>';
                                                } else {
                                                    $editHtml .= '<option value="'.$value.'">'.$value.'</option>';
                                                }
                                            }    
                                        $editHtml .= '</select>
                                        </div>
                                    </div>';
                                } else {
                                    $editHtml .= '<div class="form-group" id="updateDocAircraftContainer" style="display:none;">
                                        <div class="col-md-12">
                                            <label>Select an Aircraft Type Designation</label>
                                             <select id="updateAircraftDocuments" name="aircraft_designation" class="form-control" disabled>
                                                <option value="P180">P180</option>
                                                <option value="BE20">BE20</option>
                                            </select>
                                        </div>
                                    </div>';
                                }
                                $editHtml .= '<div class="form-group">
                                    <div class="col-md-12">
                                        <label>Select a Date for the Document</label>
                                        <input type="text" name="document_date" class="form-control datePicker dPCls" value="'.$docsDate.'">
                                    </div>
                                </div>
                            </div>';
            }

            $res = array('status'=>'success', 'data'=>$editHtml);
            echo json_encode($res);die;
        } else {
            $res = array('status'=>'failure', 'data'=>$editHtml);
            echo json_encode($res);die;
        }
    }

    //Update docs and save details
    public function updateDocsDetails()
    {
        $params = $this->request->data;
        $docsModel = TableRegistry::get('Documents');
        $documents = $docsModel->get($params['id']);

        if(!empty($params['file_name']) && isset($params['file_name']['tmp_name'])) 
        {
            $temp = $params['file_name']['tmp_name'];
            $name = $params['file_name']['name'];
            $ext = substr(strrchr($name , '.'), 1);
            $arr_ext = array('pdf');
            $setNewFileName =  date('YmdHis').".pdf";
            $params['file_name'] =  $setNewFileName;
            if (in_array($ext, $arr_ext)) {
                if(move_uploaded_file($temp, WWW_ROOT . 'documents/' . $setNewFileName)) {
                    $documents = $docsModel->patchEntity($documents, $params);
                    if($docsModel->save($documents)) {
                        $result = array('status'=>'success', 'message'=>"Saved successfully.");
                    }                    
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong.');
                }
            } else {
                $result = array('status'=>'failed', 'message'=>'Please upload correct format.');
            }
            echo json_encode($result);die;
        } else {
            $documents = $docsModel->patchEntity($documents, $params);
            if($docsModel->save($documents)) {
                $result = array('status'=>'success', 'message'=>"Saved successfully.");
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong.');
            }
            echo json_encode($result);die;
        }
    }

    //Delete documents
    public function deleteDocuments()
    {
        $params = $this->request->data;        
        if(!empty($params['docId']) && !empty($params['pilotId'])) 
        {
            $docsModel = TableRegistry::get('Documents');
            $docRec = $docsModel->get($params['docId']);

            if($docsModel->delete($docRec)) {
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
