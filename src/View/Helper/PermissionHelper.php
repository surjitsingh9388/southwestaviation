<?php 
/* src/View/Helper/PermissionHelper.php */
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Cake\Datasource\FactoryLocator;

class PermissionHelper extends Helper
{
    public function getAction($link, $action)
    {
        // Logic to return true or false of them based on $link and $action
        $sessionUser = $this->request->getSession()->read('Auth');;
        $menuItemsModel = FactoryLocator::get('Table')->get('MenuItems');
        $allMenu = $menuItemsModel->find('all')
            ->select(['MenuItems.name','UserMenuItems.action_add', 'UserMenuItems.action_edit', 'UserMenuItems.action_view', 'UserMenuItems.action_delete', 'UserMenuItems.aircraft_ids'])
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
            ->where(['UserMenuItems.user_id' => $sessionUser['id'], 'MenuItems.name' => $link, 'UserMenuItems.'.$action => 1])->first();
        if($sessionUser['id'] == 1){
        	return true;
        }else{
            if($allMenu){
            	return true;
            }
            return false;
        }
    }
}
?>