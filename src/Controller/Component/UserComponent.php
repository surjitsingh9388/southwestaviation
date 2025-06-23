<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Query;

class UserComponent extends Component {
    
    protected \App\Model\Table\RolesTable $Roles;
    protected \App\Model\Table\UserMenuItemsTable $UserMenuItems;
    protected \App\Model\Table\UsersTable $Users;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }
    /**
     * GetRoles method
     * This function is used to get list of all roles.
     *
     * @return array.
     */
    public function getRoles() {
        $roleModel = $this->getController()->fetchTable('Roles');
        $roles = $roleModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'role_name'
        ))->toArray();
        return $roles;
    }

    public function getRoleById($role_id) {
        $roleModel = $this->getController()->fetchTable('Roles');
        $roles = $roleModel->find('all')->where(['id'=>$role_id])->select(['role_name'])->first()->toArray();
        return $roles;
    }

    public function getPilotRoles() {
        $pilotRoles = explode(',', PILOT_ROLE_ID);
        $roleModel = $this->getController()->fetchTable('Roles');
        $roles = $roleModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'role_name'
        ))->where(['id IN'=>$pilotRoles])->toArray();
        return $roles;
    }
                   
    public function getUserMenuItems($roleID=null)
    {
        $menuItems=[];
        $userMenuItemsModel = $this->getController()->fetchTable('UserMenuItems');
        $userMenu = $userMenuItemsModel->find('all', ['contain' => ['MenuItems' => ['fields' => ['parent_id', 'name']]]])->where(['UserMenuItems.role_id' => $roleID, 'MenuItems.deleted is null'])->toArray(); 
        $techpubl = '';
        $techpublmenulist = unserialize(TECHNICALPUBLICATIONMENU);
        foreach ($userMenu as $key => $value) {
            /*if($value['menu_item']['name'] == 'SWAS' || $value['menu_item']['name'] == 'BVAC' || $value['menu_item']['name'] == 'RJC'){
                $techpubl = $value['menu_item']['name'].' ';
            }
            if(in_array($value['menu_item']['name'], $techpublmenulist)){
                $menuItems[$value['menu_item_id']] = $techpubl.$value['menu_item']['name'];
            }else{
                $menuItems[$value['menu_item_id']] = $value['menu_item']['name'];
            }*/
            if($value['menu_item_id'] >= '76' && $value['menu_item_id'] <= '79'){
                $techpubl = 'SWAS ';
            }else if($value['menu_item_id'] >= '80' && $value['menu_item_id'] <= '83'){
                $techpubl = 'BVAC ';
            }else if($value['menu_item_id'] >= '84' && $value['menu_item_id'] <= '87'){
                $techpubl = 'RJC ';
            }
            if($value['menu_item_id'] >= '76' && $value['menu_item_id'] <= '87'){
                $menuItems[$value['menu_item_id']] = $techpubl.$value['menu_item']['name'];
            }else{
                $menuItems[$value['menu_item_id']] = $value['menu_item']['name'];
            }
        } 
        return $menuItems;
    }

    public function getUserActionItems($roleID=null)
    {
        $menuItems=[];
        $userMenuItemsModel = $this->getController()->fetchTable('UserMenuItems');
        $userMenu = $userMenuItemsModel->find('all', ['contain' => ['MenuItems' => ['fields' => ['parent_id', 'name']]]])->where(['UserMenuItems.role_id' => $roleID, 'MenuItems.deleted is null'])->toArray(); 
        foreach ($userMenu as $key => $value) {
            $menuItems[$value['menu_item_id']] = array($value['menu_item']['name'] => array('add' => $value['action_add'], 'edit' => $value['action_edit'], 'view' => $value['action_view'], 'delete' => $value['action_delete']));
        } 
        //pr($menuItems);exit;
        return $menuItems;    
    }

    public function getAdminMenuItems()
    {
        $menuItems=[];
        $menuItemsModel = $this->getController()->fetchTable('MenuItems');
        $adminMenu = $menuItemsModel->find('all')->toArray();

        $techpubl = '';
        $techpublmenulist = unserialize(TECHNICALPUBLICATIONMENU);
        foreach ($adminMenu as $key => $value) {
            if($value['id'] >= '76' && $value['id'] <= '79'){
                $techpubl = 'SWAS ';
            }else if($value['id'] >= '80' && $value['id'] <= '83'){
                $techpubl = 'BVAC ';
            }else if($value['id'] >= '84' && $value['id'] <= '87'){
                $techpubl = 'RJC ';
            }
            if($value['id'] >= '76' && $value['id'] <= '87'){
                $menuItems[$value['id']] = $techpubl.$value['name'];
            }else{
                $menuItems[$value['id']] = $value['name'];
            }
            
            //$menuItems[$value['id']] = $value['name'];
        } 
        //pr($menuItems);die;
        return $menuItems;    
    }

    public function getParentMenuId($subMenuId=null)
    {        
        $menuItemsModel = $this->getController()->fetchTable('MenuItems');
        $allMenu = $menuItemsModel->find('all')->where(['MenuItems.id' => $subMenuId, 'MenuItems.deleted is null'])->first()->toArray();
        return $allMenu['parent_id'];
    }

    //User Name
    public function getUserName($id) 
    {
        $userModel = $this->getController()->fetchTable('Users');
        $user = $userModel->find()->where(['Users.id'=>$id])->select(['Users.id', 'Users.first_name', 'Users.last_name'])->enableHydration(false)->first();
        $name = '';
        if(!empty($user['first_name']) || !empty($user['last_name'])) {
            $name =  ucfirst($user['last_name'].', '.$user['first_name']);
            if($user['first_name'] == 'Admin') {
                $name =  ucfirst($user['first_name']);
            }
        }
        return $name;
    }

    public function getUsers() {
        $userModel = $this->getController()->fetchTable('Users');
        $users = $userModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'full_name'
        ))->where(['id != '=>'1'])->toArray();

        return $users;
    }

    public function validateUserInitials($user_initials, $user_id = '0'){
        $userModel = $this->getController()->fetchTable('Users');
        
        $userlist = $userModel->find()
                                ->select(['id', 'time_clock_code', 'certification_code', 'user_initials'])
                                ->where(function (QueryExpression $exp, Query $q) use ($user_initials) {
                                    $orConditions = [];

                                    // Only add int comparisons if input is numeric
                                    if (is_numeric($user_initials)) {
                                        $orConditions['time_clock_code'] = (int)$user_initials;
                                        $orConditions['certification_code'] = (int)$user_initials;
                                    }

                                    // Always add the string comparison
                                    $orConditions['user_initials'] = $user_initials;

                                    return $exp->or($orConditions);
                                });
        
        $is_exist = '0';
        foreach($userlist as $user){
            if(!empty($user['time_clock_code']) && $user['time_clock_code'] == $user_initials){
                $is_exist = '1';
                break;
            }else if(!empty($user['certification_code']) && $user['certification_code'] == $user_initials){
                $is_exist = '1';
                break;
            }else if(!empty($user['user_initials']) && $user['user_initials'] == $user_initials){
                if($user['id'] != $user_id){
                    $is_exist = '1';
                    break;
                }
            }
        }

        return $is_exist;
    }
}