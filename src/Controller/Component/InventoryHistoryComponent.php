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

class InventoryHistoryComponent extends Component {
    public $components = ['Auth', 'Inventory'];

    public function saveInventoryItemHistory($entity){
        $InventoryItemHistoriesModel = TableRegistry::get('InventoryItemHistories');

        $inventoryHistory = $InventoryItemHistoriesModel->newEntity();
        $inventoryHistory->inventory_item_id = $entity->id;
        
        $inventoryHistory->title = 'Inventory Item '.$entity->part_number.' was created.';
        $description = serialize($entity);

        $inventoryHistory->user_id = $entity->added_by;
        $inventoryHistory->description = $description;

        $InventoryItemHistoriesModel->save($inventoryHistory);
    }

    public function saveInventoryHistory($entity){
        $InventoryHistoriesModel = TableRegistry::get('InventoryHistories');

        $InventoryItemsModel = TableRegistry::get('InventoryItems');
        $inventoryitems = $InventoryItemsModel->get($entity->inventory_item_id);

        $InventoryLocationsModel = TableRegistry::get('InventoryLocations');
        $inventorylocations = $InventoryLocationsModel->get($entity->location_id);

        $inventoryHistory = $InventoryHistoriesModel->newEntity();
        $inventoryHistory->inventory_id = $entity->id;
        
        $inventoryHistory->title = $inventoryitems->part_number.' '.$entity->serial_no.' was created.';
        $description = serialize($entity);

        $inventoryHistory->user_id = $this->Auth->user('id');
        $inventoryHistory->description = $description;

        $InventoryHistoriesModel->save($inventoryHistory);

        //transaction history data save
        $from_description = 'Not Tracked';
        $to_description = $inventorylocations->location_name;
        $type = 'Manual Entry';
        
        $otherparamaterarr = array(
            'from_description'=>$from_description,
            'to_description'=>$to_description,
            'type'=>$type,
            'qty'=>$entity->qty,
            'to_status'=>$entity->status
        );
        $this->Inventory->saveInventoryTransactionHistory($entity, $otherparamaterarr);

        $InventoryItemHistoriesModel = TableRegistry::get('InventoryItemHistories');

        $inventoryHistory = $InventoryItemHistoriesModel->newEntity();
        $inventoryHistory->inventory_item_id = $entity->inventory_item_id;
        
        $inventoryHistory->title = 'Inventory Item '.$inventoryitems->part_number.' was updated';
        
        $inventoryHistory->user_id = $entity->added_by;
        $inventoryHistory->description = 'New Stock Qty '.$entity->qty.' was added.';

        $InventoryItemHistoriesModel->save($inventoryHistory);
    }

    public function saveInventoryLocationHistory($entity){
        $InventoryLocationHistoriesModel = TableRegistry::get('InventoryLocationHistories');

        $inventoryLocationHistory = $InventoryLocationHistoriesModel->newEntity();
        $inventoryLocationHistory->inventory_location_id = $entity->id;
        
        $inventoryLocationHistory->title = 'Location '.$entity->location_name.' was imported.';
        $description = serialize($entity);

        $inventoryLocationHistory->user_id = $this->Auth->user('id');
        $inventoryLocationHistory->description = $description;

        $InventoryLocationHistoriesModel->save($inventoryLocationHistory);
    }

    public function saveInventoryRequestHistory($entity){
        $InventoryRequestHistoriesModel = TableRegistry::get('InventoryRequestHistories');
        $InventoryRequestItemsModel = TableRegistry::get('InventoryRequestItems');

        $inventoryRequestHistory = $InventoryRequestHistoriesModel->newEntity();
        $inventoryRequestHistory->inventory_request_id = $entity->id;

        $requestdata = [];
        $requestdata['id'] = $entity->id;
        $requestdata['request_number'] = $entity->request_number;
        $requestdata['title'] = $entity->title;
        $requestdata['description'] = $entity->description;
        $requestdata['requested_by'] = $entity->requested_by;
        $requestdata['need_by'] = $entity->need_by;
        $requestdata['urgency'] = $entity->urgency;
        $requestdata['added_by'] = $entity->added_by;
        $requestdata['updated_by'] = $entity->updated_by;
        $requestdata['status'] = $entity->status;
        $requestdata['request_status'] = $entity->request_status;
        $requestdata['comment'] = $entity->comment;
        $requestdata['created'] = $entity->created;
        $requestdata['modified'] = $entity->modified;
        $requestdata['deleted'] = $entity->deleted;
        
        $inventoryrequestitems = $InventoryRequestItemsModel->find('all')->where(['InventoryRequestItems.inventory_request_id'=>$entity->id])->select($InventoryRequestItemsModel)->toArray();
        
        $requestitemarr = [];
        foreach($inventoryrequestitems as $requestitems){
            $data = [];
            $data['inventory_request_id'] = $requestitems['inventory_request_id'];
            $data['inventory_item_id'] = $requestitems['inventory_item_id'];
            $data['noninventory_item'] = $requestitems['noninventory_item'];
            $data['qty'] = $requestitems['qty'];
            $data['uom'] = $requestitems['uom'];
            $data['location_id'] = $requestitems['location_id'];
            $data['status'] = $requestitems['status'];
            $data['added_by'] = $requestitems['added_by'];
            $data['updated_by'] = $requestitems['updated_by'];
            $data['created'] = $requestitems['created'];
            $data['modified'] = $requestitems['modified'];
            $data['deleted'] = $requestitems['deleted'];

            $requestitemarr[] = $data;
        }
        $requestdata['LineItems'] = $requestitemarr;
//echo "<pre>";print_r($requestdata);exit;
        $inventoryRequestHistory->title = 'Inventory request '.$entity->request_number.' was created.';
        $description = serialize($requestdata);

        $inventoryRequestHistory->user_id = $this->Auth->user('id');
        $inventoryRequestHistory->description = $description;
        
        $InventoryRequestHistoriesModel->save($inventoryRequestHistory);
    }

    public function saveInventoryPurchaseOrderHistory($entity){
        $InventoryPurchaseOrderHistoriesModel = TableRegistry::get('InventoryPurchaseOrderHistories');
        $InventoryPOItemsModel = TableRegistry::get('InventoryPOItems');

        $inventoryPurchaseOrderHistory = $InventoryPurchaseOrderHistoriesModel->newEntity();
        $inventoryPurchaseOrderHistory->inventory_purchase_order_id = $entity->id;

        $requestdata = [];
        $requestdata['id'] = $entity->id;
        $requestdata['po_type'] = $entity->po_type;
        $requestdata['po_number'] = $entity->po_number;
        $requestdata['po_date'] = $entity->po_date;
        $requestdata['currency'] = $entity->currency;
        $requestdata['bill_to_address'] = $entity->bill_to_address;
        $requestdata['ship_to_address'] = $entity->ship_to_address;
        $requestdata['account_code'] = $entity->account_code;
        $requestdata['requestor'] = $entity->requestor;
        $requestdata['vendor'] = $entity->vendor;
        $requestdata['reference'] = $entity->reference;
        $requestdata['sales_person'] = $entity->sales_person;
        $requestdata['ship_via'] = $entity->ship_via;
        $requestdata['special_instructions'] = $entity->special_instructions;
        $requestdata['request'] = $entity->request;
        $requestdata['exchange_note'] = $entity->exchange_note;
        $requestdata['exchange_status'] = $entity->exchange_status;
        $requestdata['status'] = $entity->status;
        $requestdata['po_status'] = $entity->po_status;
        $requestdata['created'] = $entity->created;
        $requestdata['modified'] = $entity->modified;
        $requestdata['deleted'] = $entity->deleted;
        
        $inventorypoitems = $InventoryPOItemsModel->find('all')->where(['InventoryPOItems.inventory_po_id'=>$entity->id])->select($InventoryPOItemsModel)->toArray();
        
        $itemarr = [];
        foreach($inventorypoitems as $items){
            $data = [];
            $data['inventory_po_id'] = $items['inventory_po_id'];
            $data['inventory_item_id'] = $items['inventory_item_id'];
            $data['noninventory_item'] = $items['noninventory_item'];
            $data['qty'] = $items['qty'];
            $data['uom'] = $items['uom'];
            $data['location_id'] = $items['location_id'];
            $data['eta'] = $items['eta'];
            $data['cost'] = $items['cost'];
            $data['status'] = $items['status'];
            $data['added_by'] = $items['added_by'];
            $data['updated_by'] = $items['updated_by'];
            $data['created'] = $items['created'];
            $data['modified'] = $items['modified'];
            $data['deleted'] = $items['deleted'];

            $itemarr[] = $data;
        }
        $requestdata['LineItems'] = $itemarr;
        //echo "<pre>";print_r($requestdata);exit;
        $inventoryPurchaseOrderHistory->title = 'Purchase Order '.$entity->po_number.' was created.';
        $description = serialize($requestdata);
        
        $inventoryPurchaseOrderHistory->user_id = $this->Auth->user('id');
        $inventoryPurchaseOrderHistory->description = $description;
        
        $InventoryPurchaseOrderHistoriesModel->save($inventoryPurchaseOrderHistory);
    }

    public function saveInventoryRepairOrderHistory($entity){
        $InventoryRepairOrderHistoriesModel = TableRegistry::get('InventoryRepairOrderHistories');
        $InventoryROItemsModel = TableRegistry::get('InventoryROItems');

        $inventoryRepairOrderHistory = $InventoryRepairOrderHistoriesModel->newEntity();
        $inventoryRepairOrderHistory->inventory_repair_order_id = $entity->id;

        $requestdata = [];
        $requestdata['id'] = $entity->id;
        $requestdata['ro_number'] = $entity->ro_number;
        $requestdata['ro_date'] = $entity->ro_date;
        $requestdata['currency'] = $entity->currency;
        $requestdata['bill_to_address'] = $entity->bill_to_address;
        $requestdata['ship_to_address'] = $entity->ship_to_address;
        $requestdata['account_code'] = $entity->account_code;
        $requestdata['requestor'] = $entity->requestor;
        $requestdata['vendor'] = $entity->vendor;
        $requestdata['reference'] = $entity->reference;
        $requestdata['contact'] = $entity->contact;
        $requestdata['ship_via'] = $entity->ship_via;
        $requestdata['special_instructions'] = $entity->special_instructions;
        $requestdata['request'] = $entity->request;
        $requestdata['status'] = $entity->status;
        $requestdata['ro_status'] = $entity->ro_status;
        $requestdata['created'] = $entity->created;
        $requestdata['modified'] = $entity->modified;
        $requestdata['deleted'] = $entity->deleted;
        
        $inventoryroitems = $InventoryROItemsModel->find('all')->where(['InventoryROItems.inventory_ro_id'=>$entity->id])->select($InventoryROItemsModel)->toArray();
        
        $itemarr = [];
        foreach($inventoryroitems as $items){
            $data = [];
            $data['inventory_ro_id'] = $items['inventory_ro_id'];
            $data['inventory_id'] = $items['inventory_id'];
            $data['noninventory_item'] = $items['noninventory_item'];
            $data['qty'] = $items['qty'];
            $data['location_id'] = $items['location_id'];
            $data['eta'] = $items['eta'];
            $data['cost'] = $items['cost'];
            $data['status'] = $items['status'];
            $data['added_by'] = $items['added_by'];
            $data['updated_by'] = $items['updated_by'];
            $data['created'] = $items['created'];
            $data['modified'] = $items['modified'];
            $data['deleted'] = $items['deleted'];

            $itemarr[] = $data;
        }
        $requestdata['LineItems'] = $itemarr;
        //echo "<pre>";print_r($requestdata);exit;
        $inventoryRepairOrderHistory->title = 'Repair Order '.$entity->ro_number.' was created.';
        $description = serialize($requestdata);
        
        $inventoryRepairOrderHistory->user_id = $this->Auth->user('id');
        $inventoryRepairOrderHistory->description = $description;
        
        $InventoryRepairOrderHistoriesModel->save($inventoryRepairOrderHistory);
    }

    public function saveInventoryShippingOrderHistory($entity){
        $InventoryShippingOrderHistoriesModel = TableRegistry::get('InventoryShippingOrderHistories');
        $InventoryShippingOrderItemsModel = TableRegistry::get('InventoryShippingOrderItems');

        $inventoryShippingOrderHistory = $InventoryShippingOrderHistoriesModel->newEntity();
        $inventoryShippingOrderHistory->inventory_shipping_order_id = $entity->id;

        $requestdata = [];
        $requestdata['id'] = $entity->id;
        $requestdata['shipping_order_number'] = $entity->shipping_order_number;
        $requestdata['shipping_order_date'] = $entity->shipping_order_date;
        $requestdata['currency'] = $entity->currency;
        $requestdata['from_address'] = $entity->from_address;
        $requestdata['to_address'] = $entity->to_address;
        $requestdata['account_code'] = $entity->account_code;
        $requestdata['requestor'] = $entity->requestor;
        $requestdata['vendor'] = $entity->vendor;
        $requestdata['reference'] = $entity->reference;
        $requestdata['ship_via'] = $entity->ship_via;
        $requestdata['special_instructions'] = $entity->special_instructions;
        $requestdata['destination'] = $entity->destination;
        $requestdata['attention'] = $entity->attention;
        $requestdata['shipper'] = $entity->shipper;
        $requestdata['description'] = $entity->description;
        $requestdata['street1'] = $entity->street1;
        $requestdata['street2'] = $entity->street2;
        $requestdata['street3'] = $entity->street3;
        $requestdata['city'] = $entity->city;
        $requestdata['state'] = $entity->state;
        $requestdata['province'] = $entity->province;
        $requestdata['postal'] = $entity->postal;
        $requestdata['country'] = $entity->country;
        $requestdata['status'] = $entity->status;
        $requestdata['shipping_order_status'] = $entity->ro_status;
        $requestdata['created'] = $entity->created;
        $requestdata['modified'] = $entity->modified;
        $requestdata['deleted'] = $entity->deleted;
        
        $inventorysoitems = $InventoryShippingOrderItemsModel->find('all')->where(['InventoryShippingOrderItems.inventory_shipping_order_id'=>$entity->id])->select($InventoryShippingOrderItemsModel)->toArray();
        
        $itemarr = [];
        foreach($inventorysoitems as $items){
            $data = [];
            $data['inventory_shipping_order_id'] = $items['inventory_shipping_order_id'];
            $data['inventory_id'] = $items['inventory_id'];
            $data['noninventory_item'] = $items['noninventory_item'];
            $data['qty'] = $items['qty'];
            $data['location_id'] = $items['location_id'];
            $data['cost'] = $items['cost'];
            $data['status'] = $items['status'];
            $data['added_by'] = $items['added_by'];
            $data['updated_by'] = $items['updated_by'];
            $data['created'] = $items['created'];
            $data['modified'] = $items['modified'];
            $data['deleted'] = $items['deleted'];

            $itemarr[] = $data;
        }
        $requestdata['LineItems'] = $itemarr;
        //echo "<pre>";print_r($requestdata);exit;
        $inventoryShippingOrderHistory->title = 'Shipping Order '.$entity->shipping_order_number.' was created.';
        $description = serialize($requestdata);
        
        $inventoryShippingOrderHistory->user_id = $this->Auth->user('id');
        $inventoryShippingOrderHistory->description = $description;
        
        $InventoryShippingOrderHistoriesModel->save($inventoryShippingOrderHistory);
    }

}