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

class UserComponent extends Component {
    /**
     * GetRoles method
     * This function is used to get list of all roles.
     *
     * @return array.
     */
    public function getRoles() {
        $roleModel = TableRegistry::get('Roles');
        $roles = $roleModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'role_name'
        ))->toArray();
        return $roles;
    }

    public function getPilotRoles() {
        $pilotRoles = explode(',', PILOT_ROLE_ID);
        $roleModel = TableRegistry::get('Roles');
        $roles = $roleModel->find('list', array (
            'keyField' => 'id', 
            'valueField' => 'role_name'
        ))->where(['id IN'=>$pilotRoles])->toArray();
        return $roles;
    }
                   
    public function getUserMenuItems($roleID=null)
    {
        $menuItems=[];
        $userMenuItemsModel = TableRegistry::get('UserMenuItems');
        $userMenu = $userMenuItemsModel->find('all', ['contain' => ['MenuItems' => ['fields' => ['parent_id', 'name']]]])->where(['UserMenuItems.role_id' => $roleID, 'MenuItems.deleted is null'])->toArray(); 
        foreach ($userMenu as $key => $value) {
            $menuItems[$value['menu_item_id']] = $value['menu_item']['name'];
        } 
        return $menuItems;
    }

    public function getUserActionItems($roleID=null)
    {
        $menuItems=[];
        $userMenuItemsModel = TableRegistry::get('UserMenuItems');
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
        $menuItemsModel = TableRegistry::get('MenuItems');
        $adminMenu = $menuItemsModel->find('all')->toArray();

        foreach ($adminMenu as $key => $value) {
            $menuItems[$value['id']] = $value['name'];
        } 
        //pr($menuItems);die;
        return $menuItems;    
    }

    public function getParentMenuId($subMenuId=null)
    {        
        $menuItemsModel = TableRegistry::get('MenuItems');
        $allMenu = $menuItemsModel->find('all')->where(['MenuItems.id' => $subMenuId, 'MenuItems.deleted is null'])->first()->toArray();
        return $allMenu['parent_id'];
    }

    //User Name
    public function getUserName($id) 
    {
        $userModel = TableRegistry::get('Users');
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

}