<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Response;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Roles Controller
 */
class RolesController extends AppController
{

    public array $paginate = array(
        'limit' => PAGINATION_LIMIT
    );

    protected \App\Model\Table\UserMenuItemsTable $UserMenuItems;
    protected \App\Model\Table\MenuItemsTable $MenuItems;
    protected \App\Model\Table\RolesTable $Roles;

    public function initialize():void {
        parent::initialize();
        $this->loadComponent('User');
        $this->UserMenuItems = $this->fetchTable('UserMenuItems');
        $this->MenuItems = $this->fetchTable('MenuItems');
        $this->Roles = $this->fetchTable('Roles');
    }
    
    public function beforeFilter(\Cake\Event\EventInterface $event) {
        parent::beforeFilter($event);
        
        $user = $this->Authentication->getResult()->getData();
        //$controller = $this->request->params['controller'];
        //$action = $this->request->params['action'];
    }
    
    /**
     * Index method
     * This function used to get list of all roles.
     *
     * @return array
     */
    public function index()
    {
        $actionItems='';
        
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Roles', $actionStatus))
            {
                $actionItems = $actionStatus['Roles'];
            }
        }
        $this->set(compact('actionItems'));
    }

    public function search()
    {
        //$this->viewBuilder()->layout('datatables');
        $query = array();
        $query['count']  = "SELECT count( Roles.id) AS count  FROM `roles` Roles  WHERE 1=1 ";

        $query['detail'] = "SELECT Roles.id, Roles.`role_name` FROM `roles` Roles WHERE  1=1 ";
        //$this->request->session()->write('query', $query);
        return $query;
    }

    public function ajaxManageRolesSearch(){
        $actionItems='';
        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Roles', $actionStatus))
            {
                $actionItems = $actionStatus['Roles'];
            }
        }
        //pr($actionItems);exit;
        $this->autoRender = false;
        $this->viewBuilder()->setLayout('ajax');
        $requestData= $this->request->getData();
        //pr($requestData);exit;
        $query = $this->search();
        //pr($query);exit;
        $cond = "";
        if( isset($requestData['search']['value']) && !empty( $requestData['search']['value'] ) ){
            $search = $requestData['search']['value'];
            $cond.=" AND ( Roles.role_name LIKE '%".$search."%'
            )";
        }


        $columns = array(
            0 => 'Roles.id',
            1 => 'Roles.role_name',          
        );

        $count = $query['count'].$cond;
        $detail = $query['detail'].$cond;
        $totalCount = $query['count'];
        //pr($count);
        //pr($detail);
        $conn = ConnectionManager::get('default');
        $results = $conn->execute($count)->fetchAll('assoc');
        $totalData = isset($results[0]['count']) ? $results[0]['count'] : 0;
        //pr($totalData);
        $totalFiltered = $totalData;
        $results = $conn->execute( $totalCount )->fetchAll('assoc');
        $totalRecords = isset($results[0]['count']) ? $results[0]['count'] : 0;
        //pr($totalRecords);exit;
        if(isset($requestData['order'][0])){
            $sidx = $columns[$requestData['order'][0]['column']];
            $sord = $requestData['order'][0]['dir'];
            $start = $requestData['start'];
        }else{
            $sidx = $columns[1];
            $sord = 'asc';
            $start = 0;
        }
        
        $length = PAGINATION_LIMIT;

        $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
        //pr($SQL);exit;
        $results = $conn->execute( $SQL )->fetchAll('assoc');
        //pr($count);
        //pr($results);exit;
        $i = 0;
        $j=1;
        
        $data = array();
        //$data['url'] = Router::url('/admin/roles/', true);
        $view = $edit = $delete = '';
        foreach ( $results as $row){
            $nestedData= [];
            $nestedData[] = $j++;
            //$nestedData[] = $row["id"];
            $nestedData[] = $row["role_name"];
            //if(!empty($actionItems)){
                if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $authUserData['id'] == 1){
                    $view = '<a href="roles/view/'.$row['id'].'" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View</a>';
                }
                if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $authUserData['id'] == 1){
                    $edit = ' <a href="roles/edit/'.$row['id'].'" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>';
                }
                if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $authUserData['id'] == 1){
                    $delete = ' <a data-role_id="'.$row['id'].'" data-url="roles/delete" href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="action(this);" data-msg="Are you sure you want to delete '.$row["role_name"].' Role?"><i class="fa fa-trash-o"></i> Delete</a>';
                }
            //}
            $nestedData[] = $view.$edit.$delete;
            $data[] = $nestedData;
            $i++;
        }
        //pr($data);exit;
        $roles = array(
            //"draw"            => intval( $requestData['draw'] ),
            "recordsTotal"    => intval( $totalRecords ),
            "recordsFiltered" => intval( $totalFiltered ),
            "data"            => $data
        );
       // var_dump($roles);die;
        /*$this->set(compact('roles'));
        $this->set('_serialize', ['roles']);*/
        echo json_encode($roles);die;
        //$this->request->session()->write('roles', $roles);
        //pr($json_data);exit;
        //echo json_encode($roles);exit;
    }
    
    /**
     * View method
     * This function is used to get details of a role by id.
     *
     * @param integer|null $id Role id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $role = $this->Roles->get($id, [
            'contain' => ['Users']
        ]);
        $this->set('role', $role);
    }
    
    /**
     * Add method
     * This function is used to add new role.
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $actionItems='';

        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Roles', $actionStatus))
            {
                $actionItems = $actionStatus['Roles'];
            }
        }
        $role = $this->Roles->newEmptyEntity();
        if ($this->request->is('post')) {
            $role = $this->Roles->patchEntity($role, $this->request->getData());
            $role->updated_by = $authUserData['id'];
            if ($this->Roles->save($role)) {
                $this->Flash->success('The role has been saved.');

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('The role could not be saved. Please, try again.');
        }
        $this->set(compact('role', 'actionItems'));
    }

    /**
     * Edit method
     * This function is used to update role deatils.
     *
     * @param integer|null $id Role id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $actionItems='';

        $authUserData = $this->Authentication->getResult()->getData();
        if($authUserData['id'] != 1){
            $actionStatus = $this->checkAction();
            if(array_key_exists('Roles', $actionStatus))
            {
                $actionItems = $actionStatus['Roles'];
            }
        }
        $role = $this->Roles->get($id);
        
        //$permissionRoles = PERMISSION_ROLE_ID;
        $permissionRoles = $this->User->getRoles();
        if (!empty($permissionRoles)) {
            //$permissionRoles = explode(',', $permissionRoles);

            //if(in_array($role['id'], $permissionRoles)){
                $roleMenuItems = $this->UserMenuItems->find('all')->where(['UserMenuItems.role_id' => $role['id']])->toArray(); 

                if(!empty($roleMenuItems)) {
                    $allMenu = $this->MenuItems->find('all')->where(['MenuItems.parent_id' => 0, 'MenuItems.deleted is null'])->order(['MenuItems.order_by ASC'])->toArray();
                    foreach ($allMenu as $key => $value) {
                        $menuItemsss = [];
                        $chieldMenu = $this->getChielsMenu($value['id']);
                        if($chieldMenu['count'] > '0'){
                            unset($chieldMenu['count']);
                            foreach ($chieldMenu as $key1 => $value1) {
                                $lastMenu = $this->getChielsMenu($key1);
                                
                                if($lastMenu['count'] > '0'){
                                    unset($lastMenu['count']);
                                    $tempMenuItems = [];
                                    foreach ($lastMenu as $key2 => $value2) {
                                        $tempMenuItems[] = array($key2 => $value2);
                                    }                        
                                    $menuItemsss[$key1] = array($value1 => $tempMenuItems);
                                }else{
                                    unset($lastMenu['count']);
                                    $menuItemsss[$key1] = $value1;
                                }
                                $menuItems[$value['id']] = array($value['name'] => $menuItemsss);
                            }                
                        }else{
                            unset($chieldMenu['count']);
                            $menuItems[$value['id']] = $value['name'];
                        }
                    }
                    foreach ($roleMenuItems as $key6 => $value6) {
                        $temp[$value6['menu_item_id']] = array('action_add' => $value6['action_add'], 'action_edit' => $value6['action_edit'], 'action_view' => $value6['action_view'], 'action_delete' => $value6['action_delete'], 'action_approve_deny' => $value6['action_approve_deny'], 'action_reopen_work_order' => $value6['action_reopen_work_order'], 'action_final_inspections' => $value6['action_final_inspections'], 'action_clear_signoff' => $value6['action_clear_signoff']);
                    }
                } else {
                    $allMenu = $this->MenuItems->find('all')->where(['MenuItems.parent_id' => 0, 'MenuItems.deleted is null'])->order(['MenuItems.order_by ASC'])->toArray();
                    foreach ($allMenu as $key => $value) {
                        $menuItemsss = [];
                        $chieldMenu = $this->getChielsMenu($value['id']);
                        if($chieldMenu['count'] > '0'){
                            unset($chieldMenu['count']);
                            foreach ($chieldMenu as $key1 => $value1) {
                                $lastMenu = $this->getChielsMenu($key1);
                                
                                if($lastMenu['count'] > '0'){
                                    unset($lastMenu['count']);
                                    $tempMenuItems = [];
                                    foreach ($lastMenu as $key2 => $value2) {
                                        $tempMenuItems[] = array($key2 => $value2);
                                    }                        
                                    $menuItemsss[$key1] = array($value1 => $tempMenuItems);
                                }else{
                                    unset($lastMenu['count']);
                                    $menuItemsss[$key1] = $value1;
                                }
                                $menuItems[$value['id']] = array($value['name'] => $menuItemsss);
                            }                
                        }else{
                            unset($chieldMenu['count']);
                            $menuItems[$value['id']] = $value['name'];
                        }
                    }
                    $temp = array();
                }
                $this->set(compact('menuItems', 'temp'));
            //}
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $insertData = array();
            $postData = $this->request->getData();
            //pr($postData);exit;
            if ($this->Roles->exists(['role_name' => $postData['role_name'], 'id !=' => $id])) {
                $this->Flash->error('Role already exists.');
                return $this->redirect(['action' => 'edit', $id]);
            }
            $role = $this->Roles->patchEntity($role, $postData);
            //pr($role['id']);exit;
            $role->updated_by = $authUserData['id'];
            if ($this->Roles->save($role)) {
                foreach ($postData['menu_item_id'] as $key => $value) {
                    if($value['menu_item_id'] != 0){
                        $parentId = $this->User->getParentMenuId($value['menu_item_id']);
                        $insertData[$key]['role_id'] = $role['id'];
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
                        if(isset($value['action_approve_deny']) && $value['action_approve_deny'] != 0){
                            $insertData[$key]['action_approve_deny'] = $value['action_approve_deny'];
                        }else{
                            $insertData[$key]['action_approve_deny'] = '0';
                        }
                        if(isset($value['action_reopen_work_order']) && $value['action_reopen_work_order'] != 0){
                            $insertData[$key]['action_reopen_work_order'] = $value['action_reopen_work_order'];
                        }else{
                            $insertData[$key]['action_reopen_work_order'] = '0';
                        }
                        if(isset($value['action_final_inspections']) && $value['action_final_inspections'] != 0){
                            $insertData[$key]['action_final_inspections'] = $value['action_final_inspections'];
                        }else{
                            $insertData[$key]['action_final_inspections'] = '0';
                        }
                        if(isset($value['action_clear_signoff']) && $value['action_clear_signoff'] != 0){
                            $insertData[$key]['action_clear_signoff'] = $value['action_clear_signoff'];
                        }else{
                            $insertData[$key]['action_clear_signoff'] = '0';
                        }
                        $insertData[$key]['updated_by'] = $authUserData['id'];
                    }
                }
                //pr($insertData);exit;
                $entities = $this->UserMenuItems->newEntities($insertData);
                $this->UserMenuItems->deleteAll(['role_id'=>$role['id']]);
                $this->UserMenuItems->saveMany($entities);
                $this->Flash->success('The role has been saved.');

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('The role could not be saved. Please, try again.');
        }

        $this->set(compact('role', 'actionItems'));
    }

    /*public function getParentMenuId($subMenuId=null)
    {        
        $menuItemsModel = $this->fetchTable('MenuItems');
        $allMenu = $menuItemsModel->find('all')->where(['MenuItems.id' => $subMenuId, 'MenuItems.deleted is null'])->first()->toArray();
        return $allMenu['parent_id'];
    }*/

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

    /**
     * Delete method
     * To set deleted status.
     *
     * @param integer|null $id Role id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $id = $_POST['id'];
        $this->request->allowMethod(['post', 'delete']);
        $role = $this->Roles->get($id);
        try {
            if ($this->Roles->delete($role)) {
                $this->Flash->success('The role has been deleted.');
            } else {
                $this->Flash->error('The role could not be deleted. Please, try again.');
            }
        } catch(\PDOException $e) {
            $this->Flash->error($this->setDeleteExceptionMessage($e->getMessage()));
        } catch (\Exception $e) {
            $this->Flash->error($this->setDeleteExceptionMessage($e->getMessage()));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * IsRoleExist method
     * This function is used to check is role name already exist.
     * 
     * @return boolean true/false
     */
    public function isRoleExist() {
        if ($this->request->is('post')) {
            $this->autoRender = false;
            $role = $this->request->getData('role_name');
            $exists = $this->Roles->exists(['role_name' => $role]);
            if ($exists) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }
}
