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
use Cake\Datasource\ConnectionManager;

class AircraftWOItemHistoryComponent extends Component {
    public $components = ['Auth', 'Inventory'];

    public function saveWorkOrderItemHistory($entity){
        $AircraftWOItemHistoriesModel = TableRegistry::get('AircraftWOItemHistories');

        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->id;
        
        $AircraftWOItemHistories->title = 'Item '.$entity->wo_item_position.' was created.';
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemTabCreateDataToHistory($entity, $title){
        $AircraftWOItemHistoriesModel = TableRegistry::get('AircraftWOItemHistories');

        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;
        
        $AircraftWOItemHistories->title = $title;
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemTabDeletedDataToHistory($entity, $title, $description){
        $AircraftWOItemHistoriesModel = TableRegistry::get('AircraftWOItemHistories');

        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;
        
        $AircraftWOItemHistories->title = $title;
        
        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemTabAttachmentDataToHistory($entity, $title){
        $AircraftWOItemHistoriesModel = TableRegistry::get('AircraftWOItemHistories');

        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEntity();
        $AircraftWOItemHistories->wo_item_id = $entity['wo_item_id'];
        
        $AircraftWOItemHistories->title = $title;
        $description = '`'.$entity['file_name'].'` was uploaded on work order item.';

        $AircraftWOItemHistories->user_id = $entity['added_by'];
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveDynamicInventoryHistory($inventory_id, $title, $description){
        $InventoryHistoriesModel = TableRegistry::get('InventoryHistories');

        $inventoryHistory = $InventoryHistoriesModel->newEntity();
        $inventoryHistory->inventory_id = $inventory_id;
        
        $inventoryHistory->title = $title;
        
        $inventoryHistory->user_id = $this->Auth->user('id');
        $inventoryHistory->description = $description;

        $InventoryHistoriesModel->save($inventoryHistory);
    }

}