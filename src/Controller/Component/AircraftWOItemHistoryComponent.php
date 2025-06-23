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
use Cake\Datasource\FactoryLocator;
use Cake\ORM\Locator\LocatorAwareTrait;

class AircraftWOItemHistoryComponent extends Component {
    public array $components = ['Inventory', 'Authentication.Authentication'];

    protected \App\Model\Table\AircraftWOItemHistoriesTable $AircraftWOItemHistories;
    protected \App\Model\Table\InventoryHistoriesTable $InventoryHistories;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }
    
    public function saveWorkOrderItemHistory($entity){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');

        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->id;
        
        $AircraftWOItemHistories->title = 'Item '.$entity->wo_item_position.' was created.';
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemTabCreateDataToHistory($entity, $title){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');

        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;
        
        $AircraftWOItemHistories->title = $title;
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemTabDeletedDataToHistory($entity, $title, $description){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');

        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;
        
        $AircraftWOItemHistories->title = $title;
        
        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemTabAttachmentDataToHistory($entity, $title){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');

        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $entity['wo_item_id'];
        
        $AircraftWOItemHistories->title = $title;
        $description = '`'.$entity['file_name'].'` was uploaded on work order item.';

        $AircraftWOItemHistories->user_id = $entity['added_by'];
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveDynamicInventoryHistory($inventory_id, $title, $description){
        $InventoryHistoriesModel = $this->getController()->fetchTable('InventoryHistories');
        $inventoryHistory = $InventoryHistoriesModel->newEmptyEntity();
        $inventoryHistory->inventory_id = $inventory_id;
        
        $inventoryHistory->title = $title;
        $authUserData = $this->Authentication->getResult()->getData();
        $inventoryHistory->user_id = $authUserData['id'];
        $inventoryHistory->description = $description;

        $InventoryHistoriesModel->save($inventoryHistory);
    }

    public function saveWOItemSignOffHistory($wo_item_id, $title, $description){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');
        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        
        $AircraftWOItemHistories->wo_item_id = $wo_item_id;

        $AircraftWOItemHistories->title = $title;
        $authUserData = $this->Authentication->getResult()->getData();
        $AircraftWOItemHistories->user_id = $authUserData['id'];
        $AircraftWOItemHistories->description = $description;
        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemOptionGenInfoHistory($entity){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');
        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;
        
        $AircraftWOItemHistories->title = 'Item General Info was created.';
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemOptionGenInfoDepositHistory($entity, $wo_item_id){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');
        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $wo_item_id;
        
        $AircraftWOItemHistories->title = 'Item General Info Deposit was created.';
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemOptionMiscChargesHistory($entity){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');
        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;
        
        $AircraftWOItemHistories->title = 'Item Misc. Charges was created.';
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemOptionMiscFuelChargesHistory($wo_item_id, $entity){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');
        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $wo_item_id;
        
        $AircraftWOItemHistories->title = 'Item Misc. Fuel Charges was created.';
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemOptionTaxInfoHistory($entity){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');
        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;
        
        $AircraftWOItemHistories->title = 'Item Tax Info was created.';
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemOptionExtraTaxInfoHistory($entity){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');
        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;
        
        $AircraftWOItemHistories->title = 'Item Extra Tax Info was created.';
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemOptionPricingInfoHistory($entity){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');
        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;
        
        $AircraftWOItemHistories->title = 'Item Pricing Info was created.';
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemOptionBillingInfoHistory($entity){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');
        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;
        
        $AircraftWOItemHistories->title = 'Item Billing Info was created.';
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

    public function saveWOItemOptionWarrantyInfoHistory($entity){
        $AircraftWOItemHistoriesModel = $this->getController()->fetchTable('AircraftWOItemHistories');
        $AircraftWOItemHistories = $AircraftWOItemHistoriesModel->newEmptyEntity();
        $AircraftWOItemHistories->wo_item_id = $entity->wo_item_id;
        
        $AircraftWOItemHistories->title = 'Item Warranty Info was created.';
        $description = serialize($entity);

        $AircraftWOItemHistories->user_id = $entity->added_by;
        $AircraftWOItemHistories->description = $description;

        $AircraftWOItemHistoriesModel->save($AircraftWOItemHistories);
    }

}