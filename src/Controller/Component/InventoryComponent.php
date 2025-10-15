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

class InventoryComponent extends Component {
    public array $components = ['Timezone', 'InventoryFilter', 'InventoryHistory', 'Authentication.Authentication'];

    protected \App\Model\Table\InventoryLocationsTable $InventoryLocations;
    protected \App\Model\Table\InventoryPOReceivesTable $InventoryPOReceives;
    protected \App\Model\Table\InventoryPOItemsTable $InventoryPOItems;
    protected \App\Model\Table\InventoryROReceivesTable $InventoryROReceives;
    protected \App\Model\Table\InventoryROItemsTable $InventoryROItems;
    protected \App\Model\Table\InventoriesTable $Inventories;
    protected \App\Model\Table\InventoryShippingOrderReceivesTable $InventoryShippingOrderReceives;
    protected \App\Model\Table\InventoryShippingOrderItemsTable $InventoryShippingOrderItems;
    protected \App\Model\Table\InventoryVendorsTable $InventoryVendors;
    protected \App\Model\Table\InventoryAddressesTable $InventoryAddresses;
    protected \App\Model\Table\InventoryTransactionHistoriesTable $InventoryTransactionHistories;
    protected \App\Model\Table\InventoryPurchaseOrderLinksTable $InventoryPurchaseOrderLinks;
    protected \App\Model\Table\InventoryPurchaseOrdersTable $InventoryPurchaseOrders;
    protected \App\Model\Table\InventoryShippingOrdersTable $InventoryShippingOrders;
    protected \App\Model\Table\InventoryRepairOrdersTable $InventoryRepairOrders;
    protected \App\Model\Table\InventoryRequestsTable $InventoryRequests;
    protected \App\Model\Table\InventoryItemsTable $InventoryItems;
    protected \App\Model\Table\InventoryShippingOrderHistoriesTable $InventoryShippingOrderHistories;
    protected \App\Model\Table\InventoryRequestItemsTable $InventoryRequestItems;

    public function initialize(array $config): void
    {
        parent::initialize($config);
    }

    public function getAllLocations(){
        
        $inventoryLocationModel = $this->getController()->fetchTable('InventoryLocations');
        $locationarr = $inventoryLocationModel->find('all')->where(['InventoryLocations.status'=>'1']);
        $location = array();
        if(!empty($locationarr)){
            foreach($locationarr as $val){
                $location[$val['id']] = $val['location_name'];
            }
        }

        return $location;
    }

    public function getAllParentLocations(){
        
        $inventoryLocationModel = $this->getController()->fetchTable('InventoryLocations');
        $locationarr = $inventoryLocationModel->find('all')->where(['InventoryLocations.parent_location_id'=>0, 'InventoryLocations.status'=>'1'])->select(['InventoryLocations.id', 'InventoryLocations.location_name']);
        $location = array();
        if(!empty($locationarr)){
            foreach($locationarr as $val){
                $location[$val['id']] = $val['location_name'];
            }
        }

        return $location;
    }

    public function getAllItemThresholds($id){
        $connection = ConnectionManager::get('default');
        $results = $connection
            ->execute(
                'SELECT invthres.id, invthres.location_id, invloc.location_name, invthres.safety_stock_threshold, (select sum(qty) from inventories where status = "1" and inventory_item_id = invthres.inventory_item_id and location_id = invthres.location_id) as instock FROM inventory_item_thresholds as invthres join inventory_locations as invloc on invthres.location_id = invloc.id WHERE invthres.inventory_item_id = :inventory_item_id',
                ['inventory_item_id' => $id]
            )
            ->fetchAll('assoc');

        return $results;
    }

    public function getAllItemsByLocation($id){
        $connection = ConnectionManager::get('default');
        $inventoryLocationModel = $this->getController()->fetchTable('InventoryLocations');
        
        $locationarr = $inventoryLocationModel->get($id);
        $childlocationsdata = $this->getAllLocationsPrintBarcode($id, $locationarr->location_name);
            
        $childlocationids = [];
        foreach ( $childlocationsdata as $val){
            $childlocationids[] = $val['id'];
        }
        if(!empty($childlocationids)){
            $cond = " and (inv.location_id=".$id." OR inv.location_id in(".implode(',', $childlocationids)."))";
        }else{
            $cond = " and inv.location_id=".$id;
        }
        
        $results = $connection
            ->execute(
                'SELECT inv.id, invloc.location_name, invitm.part_number, invitm.name, inv.serial_no, inv.qty, inv.uom, inv.cost, inv.received, inv.modified, inv.status, inv.currency FROM inventories as inv join inventory_items as invitm on inv.inventory_item_id = invitm.id join inventory_locations as invloc on inv.location_id = invloc.id WHERE invloc.status="1"'.$cond
            )
            ->fetchAll('assoc');

        return $results;
    }

    public function getAllChildLocations($parent_location_id, $status=array('1'), $id=''){
        $connection = ConnectionManager::get('default');

        /*$results = $connection
        ->execute(
            'WITH RECURSIVE cte ( id, location_name, location_path, parent_location_id, `description`, `status`) AS ( SELECT id, location_name, CAST(location_name AS varchar(500)), parent_location_id, `description`, `status` FROM inventory_locations WHERE parent_location_id = 0 UNION ALL SELECT inventory_locations.id, inventory_locations.location_name, CONCAT ( cte.location_path, " > ", inventory_locations.location_name ), inventory_locations.parent_location_id, inventory_locations.description, inventory_locations.status FROM cte JOIN inventory_locations ON cte.id = inventory_locations.parent_location_id) SELECT * FROM cte WHERE parent_location_id = :location_id and `status` in('."'" . implode ( "', '", $status ) . "'".')',
            ['location_id' => $id]
        )
        ->fetchAll('assoc');*/

        $inventoryLocationModel = $this->getController()->fetchTable('InventoryLocations');
        $wherecond = ['InventoryLocations.status IN'=>$status,'InventoryLocations.parent_location_id'=>$parent_location_id];
        if(!empty($id)){
            $wherecond['id'] = $id;
        }

        $results = $inventoryLocationModel->find('all')->where($wherecond)->select($inventoryLocationModel);
        
        foreach($results as $key=>$currentlocation){
            $sublocation = [];
            if(!empty($currentlocation->parent_location_id)){
                $label1location = $inventoryLocationModel->find('all')->where(['InventoryLocations.status IN'=>$status,'InventoryLocations.id'=>$currentlocation->parent_location_id])->select($inventoryLocationModel)->first();
                if(!empty($label1location)){
                    $sublocation[] = $label1location->location_name;
                }

                if(!empty($label1location->parent_location_id)){
                    $label2location = $inventoryLocationModel->find('all')->where(['InventoryLocations.status IN'=>$status,'InventoryLocations.id'=>$label1location->parent_location_id])->select($inventoryLocationModel)->first();
                    if(!empty($label2location)){
                        $sublocation[] = $label2location->location_name;
                    }
                    if(!empty($label2location->parent_location_id)){
                        $label3location = $inventoryLocationModel->find('all')->where(['InventoryLocations.status IN'=>$status,'InventoryLocations.id'=>$label2location->parent_location_id])->select($inventoryLocationModel)->first();
                        if(!empty($label3location)){
                            $sublocation[] = $label3location->location_name;
                        }
                        if(!empty($label3location->parent_location_id)){
                            $label4location = $inventoryLocationModel->find('all')->where(['InventoryLocations.status IN'=>$status,'InventoryLocations.id'=>$label3location->parent_location_id])->select($inventoryLocationModel)->first();
                            if(!empty($label4location)){
                                $sublocation[] = $label4location->location_name;
                            }
                            if(!empty($label4location->parent_location_id)){
                                $label5location = $inventoryLocationModel->find('all')->where(['InventoryLocations.status IN'=>$status,'InventoryLocations.id'=>$label4location->parent_location_id])->select($inventoryLocationModel)->first();
                                if(!empty($label5location)){
                                    $sublocation[] = $label5location->location_name;
                                }
                            }
                        }
                    }
                }
            }
            
            if(!empty($sublocation)){
                $sublocation = array_reverse($sublocation);
            }

            if(!empty($currentlocation)){
                $sublocation[] = $currentlocation->location_name;
            }
            if(!empty($sublocation)){
                $sublocationlist = implode(' > ', $sublocation);
            }else{
                $sublocationlist = '';
            }

            $currentlocation->location_path = $sublocationlist;
        }
        
        return $results;
    }

    public function getAllLocationsPrintBarcode($parent_location_id, $location_name){
        $connection = ConnectionManager::get('default');

        $inventoryLocationModel = $this->getController()->fetchTable('InventoryLocations');
        $wherecond = ['InventoryLocations.status'=>'1','InventoryLocations.parent_location_id'=>$parent_location_id];
        
        $results = $inventoryLocationModel->find('all')->where($wherecond)->select($inventoryLocationModel);
        
        $sublocation = [];
        foreach($results as $key=>$currentlocation){
            $label0txt = $location_name.' > '.$currentlocation->location_name;
            $currentlocation->location_path = $label0txt;
            $sublocation[] = $currentlocation;

            if(!empty($currentlocation->id)){
                $label1location = $inventoryLocationModel->find('all')->where(['InventoryLocations.status'=>'1','InventoryLocations.parent_location_id'=>$currentlocation->id])->select($inventoryLocationModel);
                foreach($label1location as $label1){
                    $label1txt = $label0txt.' > '.$label1->location_name;
                    $label1->location_path = $label1txt;
                    $sublocation[] = $label1;
                    
                    if(!empty($label1->id)){
                        $label2location = $inventoryLocationModel->find('all')->where(['InventoryLocations.status'=>'1','InventoryLocations.parent_location_id'=>$label1->id])->select($inventoryLocationModel);
                        foreach($label2location as $label2){
                            $label2txt = $label1txt.' > '.$label2->location_name;
                            $label2->location_path = $label2txt;
                            $sublocation[] = $label2;
                            if(!empty($label2->id)){
                                $label3location = $inventoryLocationModel->find('all')->where(['InventoryLocations.status'=>'1','InventoryLocations.parent_location_id'=>$label2->id])->select($inventoryLocationModel);
                                foreach($label3location as $label3){
                                    $label3txt = $label2txt.' > '.$label3->location_name;
                                    $label3->location_path = $label3txt;
                                    $sublocation[] = $label3;
                                    if(!empty($label3->id)){
                                        $label4location = $inventoryLocationModel->find('all')->where(['InventoryLocations.status'=>'1','InventoryLocations.parent_location_id'=>$label3->id])->select($inventoryLocationModel)->first();
                                        foreach($label4location as $label4){
                                            $label4txt = $label3txt.' > '.$label4->location_name;
                                            $label4->location_path = $label4txt;
                                            $sublocation[] = $label4;
                                            if(!empty($label4->id)){
                                                $label5location = $inventoryLocationModel->find('all')->where(['InventoryLocations.status'=>'1','InventoryLocations.parent_location_id'=>$label4->id])->select($inventoryLocationModel)->first();
                                                foreach($label5location as $label5){
                                                    $label5txt = $label4txt.' > '.$label5->location_name;
                                                    $label5->location_path = $label5txt;
                                                    $sublocation[] = $label5;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $sublocation;
    }

    public function getParentLocationById($id){
        $connection = ConnectionManager::get('default');
        $results = $connection
        ->execute(
            'SELECT * from inventory_locations WHERE id = :location_id',
            ['location_id' => $id]
        )
        ->fetch('assoc');
        
        return $results;
    }

    //Inventory Request report data
    public function InventoryRequestReportData($postData)
    {
        $flHtml = '';
        
        $conn = ConnectionManager::get('default');
        $cond = '';
        if(!empty($postData['searchItem'])){
            $search = $postData['searchItem'];
            $cond.=" AND ( request_number LIKE '%".$search."%' OR  title LIKE '%".$search."%' OR  description LIKE '%".$search."%' OR  requested_by LIKE '%".$search."%' OR  need_by LIKE '%".$search."%' OR  created LIKE '%".$search."%')";
        }
        $SQL = "SELECT inv.id, inv.request_number, inv.title, inv.description, inv.requested_by, inv.need_by, inv.urgency, inv.modified, inv.request_status, inv.status, inv.created FROM `inventory_requests` as inv WHERE 1=1 $cond order by urgency desc";

        $invrequestdata = $conn->execute( $SQL )->fetchAll('assoc');
        
        if(!empty($invrequestdata)) {
            $urgency = unserialize(URGENCY);

            foreach ($invrequestdata as $key => $value) {
                $status = '';
                
                if($value['request_status'] == '1'){
                    $status = 'Approved';
                }else if($value['request_status'] == '0'){
                    $status = 'Pending Review';
                }else if($value['request_status'] == '2'){
                    $status = 'Canceled';
                }else if($value['request_status'] == '5'){
                    $status = 'PO Created';
                }else if($value['request_status'] == '3'){
                    $status = 'Denied';
                }else if($value['request_status'] == '4'){
                    $status = 'Close';
                }
                $needby = isset($value["need_by"]) ? date('d-M-Y', strtotime($value["need_by"])) : '-';
                $urgency = !empty($value["urgency"]) ? $urgency[$value["urgency"]] : '';
                $flHtml .= '<tr class="flRow">
                        <td style="line-height: 2.5;">'.$value['request_number'].'</td>
                        <td style="line-height: 2.5;">'.$value['title'].'</td>
                        <td style="line-height: 2.5;">'.$value['requested_by'].'</td>
                        <td style="line-height: 2.5;">'.date("d-M-Y", strtotime($value['created'])).'</td>
                        <td style="line-height: 2.5;">'.$needby.'</td>
                        <td style="line-height: 2.5;">'.$urgency.'</td>
                        <td style="line-height: 2.5;">'.$status.'</td>
                    </tr>';
            }
        }
        
        return $flHtml;
    }

    //Inventory Location report data
    public function InventoryLocationReportData($postData)
    {
        $flHtml = '';
        
        $conn = ConnectionManager::get('default');
        $cond = '';
        if(!empty($postData['searchItem'])){
            $search = $postData['searchItem'];
            $cond.=" AND ( location_name LIKE '%".$search."%' OR  description LIKE '%".$search."%')";
        }
        $SQL = "SELECT id, location_name, status, description, parent_location_id FROM `inventory_locations` WHERE 1=1 and status='1' $cond order by id desc";

        $invlocationdata = $conn->execute( $SQL )->fetchAll('assoc');
        
        if(!empty($invlocationdata)) {
            foreach ($invlocationdata as $key => $value) {
                $parentlocation = $this->getParentLocationById($value['parent_location_id']);
                $fullpath = $value['location_name'];
                if(!empty($parentlocation)){
                    $fullpath = $fullpath.' > '.$parentlocation['location_name'];
                }

                $flHtml .= '<tr class="flRow">
                        <td style="line-height: 2.5;">'.$value['location_name'].'</td>
                        <td style="line-height: 2.5;">'.$fullpath.'</td>
                        <td style="line-height: 2.5;">'.$value['description'].'</td>
                        <td style="line-height: 2.5;"><i style="color: green" class="fa fa-check"></i></td>
                    </tr>';
            }
        }
        
        return $flHtml;
    }

    public function getVendorList(){
        $connection = ConnectionManager::get('default');
        
        $vendordata = $connection->execute("SELECT id, name FROM inventory_vendors WHERE `status` = '1' order by id DESC")->fetchAll('assoc');
        
        $vendor = [];
        if(!empty($vendordata)){
            foreach($vendordata as $val){
                $vendor[$val['id']] = $val['name'];
            }
        }

        return $vendor;
    }

    public function getManufacturerList(){
        $connection = ConnectionManager::get('default');
        
        $manufacturerdata = $connection->execute("SELECT id, name FROM inventory_part_manufacturers WHERE `status`='1' order by id ASC")->fetchAll('assoc');
        
        $manufacturer = [];
        if(!empty($manufacturerdata)){
            foreach($manufacturerdata as $val){
                $manufacturer[$val['id']] = $val['name'];
            }
        }

        return $manufacturer;
    }
    
    //Inventory PO PDF data
    public function InventoryPOPDFData($postData)
    {
        $flHtml = '';
        
        $conn = ConnectionManager::get('default');
        $cond = '';
        if(!empty($postData['searchItem'])){
            $search = $postData['searchItem'];
            $cond.=" AND ( request_number LIKE '%".$search."%' OR  title LIKE '%".$search."%' OR  description LIKE '%".$search."%' OR  requested_by LIKE '%".$search."%' OR  need_by LIKE '%".$search."%' OR  created LIKE '%".$search."%')";
        }
        $SQL = "SELECT inv.id, inv.po_number, inv.reference, v.name as vendor_name, inv.requestor, inv.account_code, inv.po_tax, inv.po_tax_amount, inv.po_shipping, inv.currency, (select (SUM(ip.cost*ip.qty)) as total_cost from inventory_po_items ip where ip.inventory_po_id = inv.id group by inventory_po_id) as tcost, inv.created, inv.po_status FROM `inventory_purchase_orders` as inv left join inventory_vendors as v on inv.vendor = v.id WHERE 1=1 $cond order by name ASC";

        $results = $conn->execute( $SQL )->fetchAll('assoc');
        
        if(!empty($results)) {
            $currency = unserialize(CURRENCY);
            foreach ( $results as $row){
                $status = '';
                
                if($row['po_status'] == '1'){
                    $status = 'Partial';
                }else if($row['po_status'] == '0'){
                    $status = 'Draft';
                }else if($row['po_status'] == '3'){
                    $status = 'Canceled';
                }else if($row['po_status'] == '2'){
                    $status = 'Sent';
                }else if($row['po_status'] == '4'){
                    $status = 'Close';
                }
                
                
                $submitted_date = isset($row["created"]) ? date('d-m-Y', strtotime($row["created"])) : '-';
                $flHtml .= '<tr class="flRow">
                        <td style="line-height: 2.5;">'.$row["po_number"].'</td>
                        <td style="line-height: 2.5;">'.$row["reference"].'</td>
                        <td style="line-height: 2.5;">'.$row["vendor_name"].'</td>
                        <td style="line-height: 2.5;">'.$row["requestor"].'</td>
                        <td style="line-height: 2.5;">'.($row["tcost"]+$row['po_tax_amount']+$row['po_shipping']).' '.$currency[$row['currency']].'</td>
                        <td style="line-height: 2.5;">'.$submitted_date.'</td>
                        <td style="line-height: 2.5;">'.$status.'</td>
                    </tr>';
            }
        }
        
        return $flHtml;
    }

    //Inventory RO PDF data
    public function InventoryROPDFData($postData)
    {
        $flHtml = '';
        
        $conn = ConnectionManager::get('default');
        $cond = '';
        if(!empty($postData['searchItem'])){
            $search = $postData['searchItem'];
            $cond.=" AND ( ro_number LIKE '%".$search."%' OR  reference LIKE '%".$search."%' OR  v.name LIKE '%".$search."%' OR  requestor LIKE '%".$search."%')";
        }
        $SQL = "SELECT inv.id, inv.ro_number, inv.reference, v.name as vendor_name, inv.requestor, inv.account_code, inv.ro_tax, inv.ro_tax_amount, inv.ro_shipping, inv.currency, (select (SUM(ip.cost*ip.qty)) as total_cost from inventory_ro_items ip where ip.inventory_ro_id = inv.id group by inventory_ro_id) as tcost, inv.created, inv.ro_status FROM `inventory_repair_orders` as inv left join inventory_vendors as v on inv.vendor = v.id WHERE 1=1 $cond order by name ASC";

        $results = $conn->execute($SQL)->fetchAll('assoc');
        
        if(!empty($results)) {
            $currency = unserialize(CURRENCY);
            foreach ( $results as $row){
                $status = '';
                
                if($row['ro_status'] == '1'){
                    $status = 'Partial Received';
                }else if($row['ro_status'] == '0'){
                    $status = 'Draft';
                }else if($row['ro_status'] == '3'){
                    $status = 'Canceled';
                }else if($row['ro_status'] == '2'){
                    $status = 'Shipped To Vendor';
                }else if($row['ro_status'] == '4'){
                    $status = 'Close';
                }
                
                $submitted_date = isset($row["created"]) ? date('d-m-Y', strtotime($row["created"])) : '-';
                $flHtml .= '<tr class="flRow">
                                <td style="line-height: 2.5;">'.$row["ro_number"].'</td>
                                <td style="line-height: 2.5;">'.$row["reference"].'</td>
                                <td style="line-height: 2.5;">'.$row["vendor_name"].'</td>
                                <td style="line-height: 2.5;">'.$row["requestor"].'</td>
                                <td style="line-height: 2.5;">'.$status.'</td>
                                <td style="line-height: 2.5;">'.($row["tcost"]+$row['ro_tax_amount']+$row['ro_shipping']).' '.$currency[$row['currency']].'</td>
                                <td style="line-height: 2.5;">'.$submitted_date.'</td>
                            </tr>';
            }
        }
        
        return $flHtml;
    }

    //Inventory Shipping Order PDF data
    public function InventoryShippingOrderPDFData($postData)
    {
        $flHtml = '';
        
        $conn = ConnectionManager::get('default');
        $cond = '';
        if(!empty($postData['searchItem'])){
            $search = $postData['searchItem'];
            $cond.=" AND ( ro_number LIKE '%".$search."%' OR  reference LIKE '%".$search."%' OR  v.name LIKE '%".$search."%' OR  requestor LIKE '%".$search."%')";
        }
        $SQL = "SELECT inv.id, inv.shipping_order_number, inv.reference, inv.requestor, inv.account_code, inv.shipping_order_tax, inv.shipping_order_tax_amount, inv.shipping_order_shipping, inv.currency, (select (SUM(ip.cost*ip.qty)) as total_cost from inventory_shipping_order_items ip where ip.inventory_shipping_order_id = inv.id group by inventory_shipping_order_id) as tcost, inv.created, inv.shipping_order_status FROM `inventory_repair_orders` as inv left join inventory_vendors as v on inv.vendor = v.id WHERE 1=1 $cond order by name ASC";

        $results = $conn->execute($SQL)->fetchAll('assoc');
        
        if(!empty($results)) {
            $currency = unserialize(CURRENCY);
            foreach ( $results as $row){
                $status = '';
                
                if($row['shipping_order_status'] == '1'){
                    $status = 'Partial Received';
                }else if($row['shipping_order_status'] == '0'){
                    $status = 'Draft';
                }else if($row['shipping_order_status'] == '3'){
                    $status = 'Canceled';
                }else if($row['shipping_order_status'] == '2'){
                    $status = 'Shipped';
                }else if($row['shipping_order_status'] == '4'){
                    $status = 'Close';
                }
                
                $submitted_date = isset($row["created"]) ? date('d-m-Y', strtotime($row["created"])) : '-';
                $flHtml .= '<tr class="flRow">
                                <td style="line-height: 2.5;">'.$row["shipping_order_number"].'</td>
                                <td style="line-height: 2.5;">'.$row["reference"].'</td>
                                <td style="line-height: 2.5;">'.$row["requestor"].'</td>
                                <td style="line-height: 2.5;">'.$status.'</td>
                                <td style="line-height: 2.5;">'.($row["tcost"]+$row['shipping_order_tax_amount']+$row['shipping_order_shipping']).' '.$currency[$row['currency']].'</td>
                                <td style="line-height: 2.5;">'.$submitted_date.'</td>
                            </tr>';
            }
        }
        
        return $flHtml;
    }

    public function getCountPOReceivedAndPOItem($inventory_po_id){
        $InventoryPOReceivesModel = $this->getController()->fetchTable('InventoryPOReceives');
        $InventoryPOItemsModel = $this->getController()->fetchTable('InventoryPOItems');

        $invporecquery = $InventoryPOReceivesModel->find('list', array(
            'fields'=>'inventory_po_item_id',
             'order'=>'InventoryPOReceives.inventory_po_item_id ASC',
             'conditions'=> array('InventoryPOReceives.inventory_po_id'=>$inventory_po_id),
             'group' => 'inventory_po_item_id'));
        $invporeccount = $invporecquery->count();

        $query = $InventoryPOItemsModel->find('all', [
            'conditions' => ['InventoryPOItems.inventory_po_id' => $inventory_po_id]
        ]);
        $invpoitmcount = $query->count();

        return array('invporeccount'=>$invporeccount, 'invpoitmcount'=>$invpoitmcount);
    }

    public function getCountROReceivedAndROItem($inventory_ro_id){
        $InventoryROReceivesModel = $this->getController()->fetchTable('InventoryROReceives');
        $InventoryROItemsModel = $this->getController()->fetchTable('InventoryROItems');

        $invrorecquery = $InventoryROReceivesModel->find('list', array(
            'fields'=>'inventory_ro_item_id',
             'order'=>'InventoryROReceives.inventory_ro_item_id ASC',
             'conditions'=> array('InventoryROReceives.inventory_ro_id'=>$inventory_ro_id),
             'group' => 'inventory_ro_item_id'));
        $invroreccount = $invrorecquery->count();

        $query = $InventoryROItemsModel->find('all', [
            'conditions' => ['InventoryROItems.inventory_ro_id' => $inventory_ro_id]
        ]);
        $invroitmcount = $query->count();

        return array('invroreccount'=>$invroreccount, 'invroitmcount'=>$invroitmcount);
    }

    public function getInventoryROReceivedItem($id){
        $InventoryROReceivesModel = $this->getController()->fetchTable('InventoryROReceives');

        $inventorypoitemsrec = $InventoryROReceivesModel->find('all')->where(['InventoryROReceives.inventory_ro_id'=>$id])->select($InventoryROReceivesModel)->select(['invitms.id', 'invitms.name', 'invitms.part_number','inv.serial_no', 'invloc.location_name', 'invloc.id'])
        ->join([
            'inv' => [
                'table' => 'inventories',
                'type' => 'LEFT',
                'conditions' => 'inv.id = InventoryROReceives.inventory_id',
            ],
            'invitms' => [
                'table' => 'inventory_items',
                'type' => 'LEFT',
                'conditions' => 'invitms.id = inv.inventory_item_id',
            ],
            'invloc' => [
                'table' => 'inventory_locations',
                'type' => 'left',
                'conditions' => 'invloc.id = inv.location_id',
            ]
        ]);

        $inventoryporeceivedarr = [];
        $invitmreceived = [];
        foreach($inventorypoitemsrec as $val){
            $inventoryporeceivedarr[$val['inventory_ro_item_id']][] = $val;
            if(isset($invitmreceived[$val['inventory_ro_item_id']])){
                $invitmreceived[$val['inventory_ro_item_id']] += $val['received'];
            }else{
                $invitmreceived[$val['inventory_ro_item_id']] = $val['received'];
            }
        }

        return array('inventoryporeceivedarr'=>$inventoryporeceivedarr, 'invitmreceived'=>$invitmreceived);
    }

    public function getInventoryRODetailsByROId($id){
        $InventoryROItemsModel = $this->getController()->fetchTable('InventoryROItems');

        $inventoryroitems = $InventoryROItemsModel->find('all')->where(['InventoryROItems.inventory_ro_id'=>$id])->select($InventoryROItemsModel)->select(['inv.id', 'inv.serial_no', 'invitms.name', 'invitms.part_number','invloc.location_name'])
        ->join([
            'inv' => [
                'table' => 'inventories',
                'type' => 'LEFT',
                'conditions' => 'inv.id = InventoryROItems.inventory_id',
            ],
            'invitms' => [
                'table' => 'inventory_items',
                'type' => 'LEFT',
                'conditions' => 'invitms.id = inv.inventory_item_id',
            ],
            'invloc' => [
                'table' => 'inventory_locations',
                'type' => 'left',
                'conditions' => 'invloc.id = InventoryROItems.location_id',
            ]
        ]);

        return $inventoryroitems;
    }

    public function getAllInventoriesDetails($isedit = '0'){
        $InventoriesModel = $this->getController()->fetchTable('Inventories');

        $statusarr = array('1', '2', '7', '9', '12');
        $whereArr = ['invitm.status'=>'1'];
        if(empty($isedit)){
            $whereArr['Inventories.status IN'] = $statusarr;
        }else{
            $whereArr['Inventories.status !='] = '14';
        }
        $inventoryitems = $InventoriesModel->find('all')->where($whereArr)->select($InventoriesModel)->select(['invitm.name', 'invitm.part_number'])
        ->join([
            'invitm' => [
                'table' => 'inventory_items',
                'type' => 'INNER',
                'conditions' => 'invitm.id = Inventories.inventory_item_id',
            ]
        ]);

        return $inventoryitems;
    }

    public function getCountSOReceivedAndSOItem($inventory_shipping_order_id){
        $InventoryShippingOrderReceivesModel = $this->getController()->fetchTable('InventoryShippingOrderReceives');
        $InventoryShippingOrderItemsModel = $this->getController()->fetchTable('InventoryShippingOrderItems');

        $invsorecquery = $InventoryShippingOrderReceivesModel->find('list', array(
            'fields'=>'inventory_shipping_order_item_id',
             'order'=>'InventoryShippingOrderReceives.inventory_shipping_order_item_id ASC',
             'conditions'=> array('InventoryShippingOrderReceives.inventory_shipping_order_id'=>$inventory_shipping_order_id),
             'group' => 'inventory_shipping_order_item_id'));
        $invsoreccount = $invsorecquery->count();

        $query = $InventoryShippingOrderItemsModel->find('all', [
            'conditions' => ['InventoryShippingOrderItems.inventory_shipping_order_id' => $inventory_shipping_order_id]
        ]);
        $invsoitmcount = $query->count();

        return array('invsoreccount'=>$invsoreccount, 'invsoitmcount'=>$invsoitmcount);
    }

    public function getInventorySOReceivedItem($id){
        $InventoryShippingOrderReceivesModel = $this->getController()->fetchTable('InventoryShippingOrderReceives');

        $inventorysoitemsrec = $InventoryShippingOrderReceivesModel->find('all')->where(['InventoryShippingOrderReceives.inventory_shipping_order_id'=>$id])->select($InventoryShippingOrderReceivesModel)->select(['invitms.id', 'invitms.name', 'invitms.part_number','inv.serial_no', 'invloc.location_name', 'invloc.id'])
        ->join([
            'inv' => [
                'table' => 'inventories',
                'type' => 'LEFT',
                'conditions' => 'inv.id = InventoryShippingOrderReceives.inventory_id',
            ],
            'invitms' => [
                'table' => 'inventory_items',
                'type' => 'LEFT',
                'conditions' => 'invitms.id = inv.inventory_item_id',
            ],
            'invloc' => [
                'table' => 'inventory_locations',
                'type' => 'left',
                'conditions' => 'invloc.id = inv.location_id',
            ]
        ]);

        $inventorysoreceivedarr = [];
        $invitmreceived = [];
        foreach($inventorysoitemsrec as $val){
            $inventorysoreceivedarr[$val['inventory_shipping_order_item_id']][] = $val;
            if(isset($invitmreceived[$val['inventory_shipping_order_item_id']])){
                $invitmreceived[$val['inventory_shipping_order_item_id']] += $val['received'];
            }else{
                $invitmreceived[$val['inventory_shipping_order_item_id']] = $val['received'];
            }
        }

        return array('inventorysoreceivedarr'=>$inventorysoreceivedarr, 'invitmreceived'=>$invitmreceived);
    }

    public function getInventorySODetailsBySOId($id){
        $InventoryShippingOrderItemsModel = $this->getController()->fetchTable('InventoryShippingOrderItems');

        $inventorysoitems = $InventoryShippingOrderItemsModel->find('all')->where(['InventoryShippingOrderItems.inventory_shipping_order_id'=>$id])->select($InventoryShippingOrderItemsModel)->select(['inv.id', 'inv.serial_no', 'invitms.name', 'invitms.part_number','invloc.location_name', 'inv.uom'])
        ->join([
            'inv' => [
                'table' => 'inventories',
                'type' => 'LEFT',
                'conditions' => 'inv.id = InventoryShippingOrderItems.inventory_id',
            ],
            'invitms' => [
                'table' => 'inventory_items',
                'type' => 'LEFT',
                'conditions' => 'invitms.id = inv.inventory_item_id',
            ],
            'invloc' => [
                'table' => 'inventory_locations',
                'type' => 'left',
                'conditions' => 'invloc.id = InventoryShippingOrderItems.location_id',
            ]
        ]);

        return $inventorysoitems;
    }

    public function getDestinationAddressDetails($postData){
        $InventoryVendorsModel = $this->getController()->fetchTable('InventoryVendors');
        $InventoryAddressesModel = $this->getController()->fetchTable('InventoryAddresses');
        
        if($postData['destination'] == '2'){
            $addressarr = $InventoryVendorsModel->get($postData['vendor']);
        }else if($postData['destination'] == '1'){
            $addressarr = $InventoryAddressesModel->get($postData['to_address']);
        }

        if(!empty($addressarr)){
            $postData['description']    = $addressarr->name;
            $postData['street1']        = $addressarr->street1;
            $postData['street2']        = $addressarr->street2;
            $postData['street3']        = $addressarr->street3;
            $postData['city']           = $addressarr->city;
            $postData['country']        = $addressarr->country;
            $postData['state']          = $addressarr->state;
            $postData['province']       = $addressarr->province;
            $postData['postal']         = $addressarr->postal;
        }

        return $postData;
    }

    public function getAllInstallToInventory($id=''){
        $inventoriesModel = $this->getController()->fetchTable('Inventories');
        
        $query = "SELECT inv.id, invitm.part_number, invitm.name, inv.serial_no FROM `inventories` inv join inventory_items invitm on inv.inventory_item_id = invitm.id where invitm.accept_install = '1' and inv.status = '1' and invitm.status = '1'";
        if(!empty($id)){
            $query .= " and inv.id != '".$id."'";
        }
        $conn = ConnectionManager::get('default');
        $results = $conn->execute($query)->fetchAll('assoc');

        $installto = [];
        foreach($results as $val){
            $installtxt = $val['name'].' (PN:'.$val['part_number'].') (SN:'.$val['serial_no'].')';
            $installto[$val['id']] = $installtxt;
            $wherecond = ['Inventories.install_to'=>$val['id']];

            $label0arr = $inventoriesModel->find('all')->where($wherecond)->select(['Inventories.id', 'invitm.part_number', 'invitm.name', 'Inventories.serial_no', 'Inventories.install_to'])->join([
                'invitm' => [
                    'table' => 'inventory_items',
                    'type' => 'INNER',
                    'conditions' => 'invitm.id = Inventories.inventory_item_id',
                ]
            ]);
            
            $subinventories = [];
            foreach($label0arr as $key=>$currentinventories){
                $label0txt = $installtxt.' > '.$currentinventories['invitm']['name'].' (PN:'.$currentinventories['invitm']['part_number'].') (SN:'.$currentinventories['serial_no'].')';
                $subinventories[$currentinventories['id']] = $label0txt;
                if(!empty($currentinventories->id)){
                    $label1inventories = $inventoriesModel->find('all')->where(['Inventories.install_to'=>$currentinventories->id])->select(['Inventories.id', 'invitm.part_number', 'invitm.name', 'Inventories.serial_no', 'Inventories.install_to'])->join([
                                    'invitm' => [
                                        'table' => 'inventory_items',
                                        'type' => 'INNER',
                                        'conditions' => 'invitm.id = Inventories.inventory_item_id',
                                    ]
                                ]);
                    foreach($label1inventories as $key=>$label1){
                        $label1txt = $label0txt.' > '.$label1['invitm']['name'].' (PN:'.$label1['invitm']['part_number'].') (SN:'.$label1['serial_no'].')';
                        $subinventories[$label1['id']] = $label1txt;

                        if(!empty($label1->id)){
                            $label2inventories = $inventoriesModel->find('all')->where(['Inventories.install_to'=>$label1->id])->select(['Inventories.id', 'invitm.part_number', 'invitm.name', 'Inventories.serial_no', 'Inventories.install_to'])->join([
                                            'invitm' => [
                                                'table' => 'inventory_items',
                                                'type' => 'INNER',
                                                'conditions' => 'invitm.id = Inventories.inventory_item_id',
                                            ]
                                        ]);
                            foreach($label2inventories as $key=>$label2){
                                $label2txt = $label1txt.' > '.$label2['invitm']['name'].' (PN:'.$label2['invitm']['part_number'].') (SN:'.$label2['serial_no'].')';
                                $subinventories[$label2['id']] = $label2txt;
                                
                                if(!empty($label2->id)){
                                    $label3inventories = $inventoriesModel->find('all')->where(['Inventories.install_to'=>$label2->id])->select(['Inventories.id', 'invitm.part_number', 'invitm.name', 'Inventories.serial_no', 'Inventories.install_to'])->join([
                                                    'invitm' => [
                                                        'table' => 'inventory_items',
                                                        'type' => 'INNER',
                                                        'conditions' => 'invitm.id = Inventories.inventory_item_id',
                                                    ]
                                                ]);
                                    foreach($label3inventories as $key=>$label3){
                                        $label3txt = $label2txt.' > '.$label3['invitm']['name'].' (PN:'.$label3['invitm']['part_number'].') (SN:'.$label3['serial_no'].')';
                                        $subinventories[$label3['id']] = $label3txt;
                                    }
                                }
                            }
                        }

                    }
                }
                
            }
            $installto = $installto+$subinventories;
        }
        return $installto;
    }

    public function getInventoryInstallToDetails($install_to){
        $inventoriesModel = $this->getController()->fetchTable('Inventories');
        $invenotriesdata = [];
        if(!empty($install_to)){
            $invenotriesdata = $inventoriesModel->find()->where(['Inventories.id'=>$install_to])->select($inventoriesModel)->select(['InventoryItems.name', 'InventoryItems.part_number'])->innerJoinWith('InventoryItems')->leftJoinWith('InventoryLocations')->first();
        }
        //$install_to_details = $invenotriesdata['_matchingData']['InventoryItems']['name'].' (PN:'.$invenotriesdata['_matchingData']['InventoryItems']['part_number'].') (SN:'.$invenotriesdata['serial_no'].')';

        return $invenotriesdata;
    }

    public function getInstallToInventoryDetails($install_to){
        $inventoriesModel = $this->getController()->fetchTable('Inventories');
        $wherecond = ['Inventories.id'=>$install_to];
        
        $results = $inventoriesModel->find('all')->where($wherecond)->select(['Inventories.id', 'invitm.part_number', 'invitm.name', 'Inventories.serial_no', 'Inventories.install_to'])->join([
            'invitm' => [
                'table' => 'inventory_items',
                'type' => 'INNER',
                'conditions' => 'invitm.id = Inventories.inventory_item_id',
            ]
        ]);
        
        $labeltxt = '';
        
        foreach($results as $key=>$currentinventories){
            $labeltxt = $currentinventories['invitm']['name'].' (PN:'.$currentinventories['invitm']['part_number'].') (SN:'.$currentinventories['serial_no'].')';
            if(!empty($currentinventories->install_to)){
                $label1inventories = $inventoriesModel->find('all')->where(['Inventories.id'=>$currentinventories->install_to])->select(['Inventories.id', 'invitm.part_number', 'invitm.name', 'Inventories.serial_no', 'Inventories.install_to'])->join([
                                'invitm' => [
                                    'table' => 'inventory_items',
                                    'type' => 'INNER',
                                    'conditions' => 'invitm.id = Inventories.inventory_item_id',
                                ]
                            ]);
                foreach($label1inventories as $key=>$label1){
                    $labeltxt .= ' > '.$label1['invitm']['name'].' (PN:'.$label1['invitm']['part_number'].') (SN:'.$label1['serial_no'].')';
                    
                    if(!empty($label1->install_to)){
                        $label2inventories = $inventoriesModel->find('all')->where(['Inventories.id'=>$label1->install_to])->select(['Inventories.id', 'invitm.part_number', 'invitm.name', 'Inventories.serial_no', 'Inventories.install_to'])->join([
                                        'invitm' => [
                                            'table' => 'inventory_items',
                                            'type' => 'INNER',
                                            'conditions' => 'invitm.id = Inventories.inventory_item_id',
                                        ]
                                    ]);
                        foreach($label2inventories as $key=>$label2){
                            $labeltxt .= ' > '.$label2['invitm']['name'].' (PN:'.$label2['invitm']['part_number'].') (SN:'.$label2['serial_no'].')';
                            
                            if(!empty($label2->install_to)){
                                $label3inventories = $inventoriesModel->find('all')->where(['Inventories.id'=>$label2->install_to])->select(['Inventories.id', 'invitm.part_number', 'invitm.name', 'Inventories.serial_no', 'Inventories.install_to'])->join([
                                                'invitm' => [
                                                    'table' => 'inventory_items',
                                                    'type' => 'INNER',
                                                    'conditions' => 'invitm.id = Inventories.inventory_item_id',
                                                ]
                                            ]);
                                foreach($label3inventories as $key=>$label3){
                                    $labeltxt .= ' > '.$label3['invitm']['name'].' (PN:'.$label3['invitm']['part_number'].') (SN:'.$label3['serial_no'].')';
                                }
                            }
                        }
                    }

                }
            }
            
        }
        
        return $labeltxt;
    }

    //transaction history data save
    public function saveInventoryTransactionHistory($invenotries, $otherparameter){
        $InventoryTransactionHistoriesModel = $this->getController()->fetchTable('InventoryTransactionHistories');
        $inventoryTransactionHistory = $InventoryTransactionHistoriesModel->newEmptyEntity();
        $authUserData = $this->Authentication->getResult()->getData();

        $inventoryLocationModel = $this->getController()->fetchTable('InventoryLocations');
        if(!empty($otherparameter['from_location_id']) && !is_numeric($otherparameter['from_location_id'])){
            $locationarr = $inventoryLocationModel->find('all')->where(['status'=>'1', 'location_name'=>$otherparameter['from_location_id']])->first();

            $otherparameter['from_location_id'] = !empty($locationarr) ? $locationarr->id : '';
        }

        if(!empty($otherparameter['to_location_id']) && !is_numeric($otherparameter['to_location_id'])){
            $locationarr = $inventoryLocationModel->find('all')->where(['status'=>'1', 'location_name'=>$otherparameter['to_location_id']])->first();

            $otherparameter['to_location_id'] = !empty($locationarr) ? $locationarr->id : '';
        }
                        
        $itemtypelist = unserialize(INVENTORY_ITEM_TYPE);
        $defaultUOM = unserialize(DEFAULT_UOM);
        $transactionActionList = unserialize(TRANSACTION_ACTION_LIST);

        $key = !empty($otherparameter['type']) ? array_search($otherparameter['type'], array_column($transactionActionList, 'name')) : '';
        $actions = !empty($key) ? $transactionActionList[$key]['id'] : '0';

        $item_type = !empty($invenotries['_matchingData']['InventoryItems']['item_type']) ? $itemtypelist[$invenotries['_matchingData']['InventoryItems']['item_type']] : '';
        
        $inventoryTransactionHistory->puchase_order_id = isset($otherparameter['puchase_order_id']) ? $otherparameter['puchase_order_id'] : '0';
        $inventoryTransactionHistory->shipping_order_id = isset($otherparameter['shipping_order_id']) ? $otherparameter['shipping_order_id'] : '0';
        $inventoryTransactionHistory->request_id = isset($otherparameter['request_id']) ? $otherparameter['request_id'] : '0';
        $inventoryTransactionHistory->repair_order_id = isset($otherparameter['repair_order_id']) ? $otherparameter['repair_order_id'] : '0';
        $inventoryTransactionHistory->from_description = $otherparameter['from_description'];
        $inventoryTransactionHistory->to_description = $otherparameter['to_description'];
        $inventoryTransactionHistory->type = $actions;
        $inventoryTransactionHistory->from_item_type = isset($otherparameter['itemtype_from']) ? $otherparameter['itemtype_from'] : '0';
        $inventoryTransactionHistory->to_item_type = isset($otherparameter['itemtype_to']) ? $otherparameter['itemtype_to'] : '0';
        $inventoryTransactionHistory->from_location_id = (isset($otherparameter['to_location_id']) ? $otherparameter['from_location_id'] : $invenotries->location_id);
        $inventoryTransactionHistory->to_location_id = (isset($otherparameter['to_location_id']) ? $otherparameter['to_location_id'] : '0');
        $inventoryTransactionHistory->from_condition = isset($otherparameter['from_condition']) ? $otherparameter['from_condition'] : '0';
        $inventoryTransactionHistory->to_condition = isset($otherparameter['to_condition']) ? $otherparameter['to_condition'] : '0';
        $inventoryTransactionHistory->from_status = isset($otherparameter['from_status']) ? $otherparameter['from_status'] : '0';
        $inventoryTransactionHistory->to_status = isset($otherparameter['to_status']) ? $otherparameter['to_status'] : '0';
        $inventoryTransactionHistory->inventory_id = $invenotries->id;
        $inventoryTransactionHistory->qty = isset($otherparameter['qty']) ? $otherparameter['qty'] : $invenotries->qty;
        $inventoryTransactionHistory->uom = $invenotries->uom;
        $inventoryTransactionHistory->unit_cost = $invenotries->cost;
        $inventoryTransactionHistory->reason = $invenotries->reason;
        $inventoryTransactionHistory->tags = $invenotries->tags;
        $inventoryTransactionHistory->vendor_id = $invenotries->vendor_id;
        $inventoryTransactionHistory->account_code = $invenotries->account_code;
        $inventoryTransactionHistory->ata_chapter = $invenotries->ata_chapter;
        $inventoryTransactionHistory->added_by = $authUserData['id'];

        $InventoryTransactionHistoriesModel->save($inventoryTransactionHistory);
    }

    public function saveInventoryPurchaseOrderLinks($parentLinkType, $linkedOrderType, $linkedOrderId, $id){
        if(!empty($linkedOrderType) && !empty($linkedOrderId) && !empty($id)){
            $authUserData = $this->Authentication->getResult()->getData();
            $InventoryPurchaseOrderLinksModel = $this->getController()->fetchTable('InventoryPurchaseOrderLinks');
            $invlinkedorders = $InventoryPurchaseOrderLinksModel->newEmptyEntity();

            $linkorderarr = [];
            $linkorderarr['link_type'] = $linkedOrderType;
            $linkorderarr['parent_link_type'] = $parentLinkType;
            $linkorderarr['parent_purchase_order_id'] = $linkedOrderId;
            if($linkedOrderType == '1'){
                $linkorderarr['purchase_order_id'] = $id;
            }else if($linkedOrderType == '2'){
                $linkorderarr['repair_order_id'] = $id;
            }else if($linkedOrderType == '3'){
                $linkorderarr['shipping_order_id'] = $id;
            }else if($linkedOrderType == '4'){
                $linkorderarr['request_id'] = $id;
            }
            
            $linkorderarr['added_by'] = $authUserData['id'];
            $invlinkedorders = $InventoryPurchaseOrderLinksModel->patchEntity($invlinkedorders, $linkorderarr);
            $InventoryPurchaseOrderLinksModel->save($invlinkedorders);
        }
    }

    public function getLinkOrdersData($id, $linkType){
        $conn = ConnectionManager::get('default');
        $this->InventoryPurchaseOrders = $this->getController()->fetchTable('InventoryPurchaseOrders');
        $this->InventoryShippingOrders = $this->getController()->fetchTable('InventoryShippingOrders');
        $this->InventoryRepairOrders = $this->getController()->fetchTable('InventoryRepairOrders');
        $this->InventoryRequests = $this->getController()->fetchTable('InventoryRequests');

        if($linkType == '1'){
            $column = 'purchase_order_id';
        }else if($linkType == '2'){
            $column = 'repair_order_id';
        }else if($linkType == '3'){
            $column = 'shipping_order_id';
        }else if($linkType == '4'){
            $column = 'request_id';
        }

        //link repair item 

        $requestquery = "select * from inventory_purchase_order_links where (parent_link_type = '".$linkType."' and parent_purchase_order_id = '".$id."') or (link_type = '".$linkType."' and $column = '".$id."' and parent_link_type = '2')";
        $linkedrepairorders = $conn->execute($requestquery)->fetchAll('assoc');

        $repairidarr = [];
        foreach($linkedrepairorders as $linkorder){
            if($linkorder['parent_purchase_order_id'] == $id && $linkorder['parent_link_type'] == $linkType && $linkorder['link_type'] == '2' && !empty($linkorder['repair_order_id'])){
                $repairidarr[] = $linkorder['repair_order_id'];
            }else if($linkorder['link_type'] == $linkType && $linkorder[$column] == $id && $linkorder['parent_link_type'] == '2'){
                $repairidarr[] = $linkorder['parent_purchase_order_id'];
            }
        }
        $linkedrepairorderscount = count($repairidarr);
        
        $linkedrepairorders = [];
        if(!empty($repairidarr)){
            $linkedrepairorders = $this->InventoryRepairOrders->find('all')->where(['InventoryRepairOrders.id IN' => $repairidarr])->select(['InventoryRepairOrders.id', 'InventoryRepairOrders.ro_number', 'vendor.name', 'InventoryRepairOrders.reference', 'InventoryRepairOrders.created', 'InventoryRepairOrders.ro_status', 'InventoryRepairOrders.status'])
            ->join([
                'vendor' => [
                    'table' => 'inventory_vendors',
                    'type' => 'LEFT',
                    'conditions' => 'vendor.id = InventoryRepairOrders.vendor',
                ]
            ]);
        }
        
        //link request item

        $requestquery = "select * from inventory_purchase_order_links where (parent_link_type = '".$linkType."' and parent_purchase_order_id = '".$id."') or (link_type = '".$linkType."' and $column = '".$id."' and parent_link_type = '4')";
        $linkedrequests = $conn->execute($requestquery)->fetchAll('assoc');

        $requestidarr = [];
        foreach($linkedrequests as $linkorder){
            if($linkorder['parent_purchase_order_id'] == $id && $linkorder['parent_link_type'] == $linkType && $linkorder['link_type'] == '4' && !empty($linkorder['request_id'])){
                $requestidarr[] = $linkorder['request_id'];
            }else if($linkorder['link_type'] == $linkType && $linkorder[$column] == $id && $linkorder['parent_link_type'] == '4'){
                $requestidarr[] = $linkorder['parent_purchase_order_id'];
            }
        }
        if(!empty($requestidarr)){
            $linkedrequests = $this->InventoryRequests->find('all')->where(['InventoryRequests.id IN'=>$requestidarr])->select($this->InventoryRequests);
        }
        $linkedrequestscount = count($requestidarr);

        //link shipping item

        $linkedshippingorders = "select * from inventory_purchase_order_links where (parent_link_type = '".$linkType."' and parent_purchase_order_id = '".$id."') or (link_type = '".$linkType."' and $column = '".$id."' and parent_link_type = '3')";
        $linkedshippingorders = $conn->execute($linkedshippingorders)->fetchAll('assoc');

        $shippingidarr = [];
        foreach($linkedshippingorders as $linkorder){
            if($linkorder['parent_purchase_order_id'] == $id && $linkorder['parent_link_type'] == $linkType && $linkorder['link_type'] == '3' && !empty($linkorder['shipping_order_id'])){
                $shippingidarr[] = $linkorder['shipping_order_id'];
            }else if($linkorder['link_type'] == $linkType && $linkorder[$column] == $id && $linkorder['parent_link_type'] == '3'){
                $shippingidarr[] = $linkorder['parent_purchase_order_id'];
            }
        }
        
        if(!empty($shippingidarr)){
            $linkedshippingorders = $this->InventoryShippingOrders->find('all')->where(['InventoryShippingOrders.id IN'=>$shippingidarr])->select($this->InventoryShippingOrders);
        }
        $linkedshippingorderscount = count($shippingidarr);

        //link purchase item

        $purchasequery = "select * from inventory_purchase_order_links where (parent_link_type = '".$linkType."' and parent_purchase_order_id = '".$id."') or (link_type = '".$linkType."' and $column = '".$id."' and parent_link_type = '1')";
        $linkedpurchaseorders = $conn->execute($purchasequery)->fetchAll('assoc');

        $poidarr = [];
        foreach($linkedpurchaseorders as $linkorder){
            if($linkorder['parent_purchase_order_id'] == $id && $linkorder['parent_link_type'] == $linkType && $linkorder['link_type'] == '1' && !empty($linkorder['purchase_order_id'])){
                $poidarr[] = $linkorder['purchase_order_id'];
            }else if($linkorder['link_type'] == $linkType && $linkorder[$column] == $id && $linkorder['parent_link_type'] == '1'){
                $poidarr[] = $linkorder['parent_purchase_order_id'];
            }
        }
        if(!empty($poidarr)){
            $linkedpurchaseorders = $this->InventoryPurchaseOrders->find('all')->where(['InventoryPurchaseOrders.id IN'=>$poidarr])->select($this->InventoryPurchaseOrders)->select(['vendor.name'])
            ->join([
                'vendor' => [
                    'table' => 'inventory_vendors',
                    'type' => 'LEFT',
                    'conditions' => 'vendor.id = InventoryPurchaseOrders.vendor',
                ]
            ]);
        }

        $linkedpurchaseorderscount = count($poidarr);

        $resparr = array(
                        'linkedpurchaseorderscount'=>$linkedpurchaseorderscount,
                        'linkedpurchaseorders'=>$linkedpurchaseorders,
                        'linkedrepairorderscount'=>$linkedrepairorderscount,
                        'linkedrepairorders'=>$linkedrepairorders,
                        'linkedshippingorderscount'=>$linkedshippingorderscount,
                        'linkedshippingorders'=>$linkedshippingorders,
                        'linkedrequestscount'=>$linkedrequestscount,
                        'linkedrequests'=>$linkedrequests,
                        );

        return $resparr;
    }

    public function getExportExcelInventoryItems($requestData, $cond){
        $query = "SELECT *, (select count(*) from inventory_po_items where inventory_item_id = inventory_items.id) as ordered FROM `inventory_items` WHERE 1=1 ";

        //echo $cond;exit;
        $columns = array(
            0 => 'id',
            1 => 'part_number',
            2 => 'name',
            3 => 'unit_cost',
            4 => 'is_this_item_serialized',
            5 => 'item_type',
            6 => 'instock',
            7 => 'ordered',
        );
        
        $conn = ConnectionManager::get('default');
        
        $sidx = $columns[$requestData['sortBy']];
        $sort = 'asc';
        
        $SQL = $query.$cond." ORDER BY $sidx $sort";//echo $SQL;exit;
        $inventoryrequestitems = $conn->execute( $SQL )->fetchAll('assoc');

        return $inventoryrequestitems;
    }

    public function getExportExcelInventories($requestData, $cond){
        $query = "SELECT inv.*, invitm.part_number, invitm.name, invitm.id as itemid, invitm.tags, invitm.item_type, vendor.name as vendor_name, manufacturer.name as manufacturer_name, ata.ata_code, invloc.location_name, inv.in_holdingbox, invitm.unit_cost, invitm.is_this_item_serialized, invitm.item_instock, (select count(*) from inventory_po_items where inventory_item_id = invitm.id) as ordered FROM `inventories` as inv join inventory_items invitm on inv.inventory_item_id = invitm.id left join inventory_locations as invloc on inv.location_id = invloc.id left join inventory_vendors as vendor on inv.vendor = vendor.id left join inventory_part_manufacturers as manufacturer on invitm.manufacturer_id = manufacturer.id left join ata_codes ata on ata.id = inv.ata_chapter WHERE 1=1 ";

        //echo $cond;exit;
        $columns = array(
            0 => 'id',
            1 => 'inv.created',
            2 => 'invitm.name',
            3 => 'invitm.part_number',
            4 => 'inv.serial_no',
            5 => 'location_name',
            6 => 'qty',
            7 => 'expiration',
            8 => 'inv.cost',
            9 => 'inv.status'
        );
        
        $conn = ConnectionManager::get('default');
        
        $sidx = $columns[$requestData['sortBy']];
        $sort = 'asc';
        
        $SQL = $query.$cond." ORDER BY $sidx $sort";//echo $SQL;exit;
        $inventoriesdata = $conn->execute( $SQL )->fetchAll('assoc');

        return $inventoriesdata;
    }

    public function getAllInventoryItems(){
        $this->InventoryItems = $this->getController()->fetchTable('InventoryItems');
        $inventoryItemList = $this->InventoryItems->find('all')->where(['status'=>'1']);

        return $inventoryItemList;
    }

    public function getShippingOrderHistoryList($id){
        $authUserData = $this->Authentication->getResult()->getData();
        $this->InventoryShippingOrderHistories = $this->getController()->fetchTable('InventoryShippingOrderHistories');
        $inventorysohistories = $this->InventoryShippingOrderHistories->find('all')
                                    ->where(['user_id'=>$authUserData['id'], 'inventory_shipping_order_id'=>$id])
                                    ->select($this->InventoryShippingOrderHistories)->select(['users.email'])
                                    ->join([
                                        'users' => [
                                            'table' => 'users',
                                            'type' => 'INNER',
                                            'conditions' => 'users.id = InventoryShippingOrderHistories.user_id',
                                        ]
                                    ])->order(['InventoryShippingOrderHistories.id'=>'DESC']);


        return $inventorysohistories;
    }

    public function getPurchaseOrderNumber(){
        $this->InventoryPurchaseOrders = $this->getController()->fetchTable('InventoryPurchaseOrders');
        
        $inventorypodet = $this->InventoryPurchaseOrders->find('all', ['order'=>['id asc']])->select(['po_number'])->all()->last();
        
        if(!empty($inventorypodet)){
            $currentyear = date('y');
            $inventorypoarr = explode('-', $inventorypodet->po_number);
            if($inventorypoarr[0] == $currentyear){
                $inventorypoid = $inventorypoarr[1]+1;
                $remaingtoaddzero = 4-strlen($inventorypoid);
                $po_number = $inventorypoarr[0].'-';
                for($i=1; $i<=$remaingtoaddzero; $i++){
                    $po_number .= '0';
                }
                $po_number .= $inventorypoid;
            }else{
                $po_number = $currentyear.'-0001';
            }
        }else{
            $po_number = $currentyear.'-0001';
        }
        
        return $po_number;
    }

    public function getShippingOrderNumber(){
        $this->InventoryShippingOrders = $this->getController()->fetchTable('InventoryShippingOrders');
        
        $inventorysodet = $this->InventoryShippingOrders->find('all', ['order'=>['id asc']])->select(['shipping_order_number'])->all()->last();
        
        if(!empty($inventorysodet)){
            $currentyear = date('y');
            $inventorysoarr = explode('-', $inventorysodet->shipping_order_number);
            if($inventorysoarr[0] == $currentyear){
                $inventorypoid = $inventorysoarr[1]+1;
                $remaingtoaddzero = 4-strlen($inventorypoid);
                $shipping_order_number = $inventorysoarr[0].'-';
                for($i=1; $i<=$remaingtoaddzero; $i++){
                    $shipping_order_number .= '0';
                }
                $shipping_order_number .= $inventorypoid;
            }else{
                $shipping_order_number = $currentyear.'-0001';
            }
        }else{
            $shipping_order_number = $currentyear.'-0001';
        }
        
        return $shipping_order_number;
    }

    public function validatePurchaseOrderSaveData($postData){
        $error_message = '';
        if(empty($postData['qty']) || count($postData['qty']) == 0){
            $error_message = 'Purchase Order Requests require at least 1 line item.';
        }else{
            for($i=0; $i<count($postData['qty']); $i++){
                if(isset($postData['inventory_item_id'][$i]) && $postData['inventory_item_id'][$i] == 'Select a part number'){
                    $error_message = 'LineItems['.$i.'] Inventory Item is a required field.';
                }else if(empty($postData['inventory_item_id'][$i]) && isset($postData['noninventory_item'][$i]) && empty($postData['noninventory_item'][$i])){
                    $error_message = 'LineItems['.$i.'] Non-Inventory Item Description is a required field.';
                }else if(empty($postData['qty'][$i])){
                    $error_message = 'LineItems['.$i.'] Quantity is a required field.';
                }else if(empty($postData['uom'][$i])){
                    $error_message = 'LineItems['.$i.'] UOM is a required field.';
                }
            }

            if(count($postData['inventory_item_id']) !== count(array_unique($postData['inventory_item_id']))){
                $error_message = 'Duplicate line item found.';
            }
        }

        return $error_message;
    }

    public function getAllInventoriesConditions($inventory_item_id){
        $this->Inventories = $this->getController()->fetchTable('Inventories');
        $invconditionList = $this->Inventories->find('all')->where(['status'=>'1'])->select(['conditions'])->group('conditions');
        $invconddropdown = [];
        $inventoryconditions = unserialize(INVENTORY_CONDITION);
        foreach($invconditionList as $invcond){
            if(!empty($inventoryconditions[$invcond['conditions']])){
                $invconddropdown[$invcond['conditions']] = $inventoryconditions[$invcond['conditions']];
            }
        }
        return $invconddropdown;
    }

    public function getAllInventoriesSerialNoByCondId($inventory_item_id, $conditions){
        $this->Inventories = $this->getController()->fetchTable('Inventories');
        $serialnoarr = [];
        if(!empty($conditions)){
            $invconditionList = $this->Inventories->find('all')->where(['inventory_item_id'=>$inventory_item_id, 'conditions'=>$conditions, 'status'=>'1'])->select(['id', 'serial_no']);
            
            foreach($invconditionList as $invcond){
                $serialnoarr[$invcond['id']] = $invcond['serial_no'];
            }
        }
        return $serialnoarr;
    }

    public function getInventroyWorkOrderDet($serial_no, $inventory_status){
        $connection = ConnectionManager::get('default');
        
        $inventorywodet = $connection->execute(
            "SELECT wo.work_order_no FROM `customer_aircraft_wo_item_parts` p join inventories inv on p.serial_number = inv.serial_no join customer_aircraft_wo_items woitm  on p.wo_item_id = woitm.id join customer_aircraft_work_orders wo on woitm.work_order_id = wo.id WHERE p.serial_number = :serial_number and inv.status = :inventory_status",
            ['serial_number' => $serial_no, 'inventory_status'=>$inventory_status])->fetch('assoc');

        return $inventorywodet;
    }

    public function getInventoryRequestNumber(){
        $this->InventoryRequests = $this->getController()->fetchTable('InventoryRequests');
        
        $invrequestdet = $this->InventoryRequests->find('all', ['order'=>['id asc']])->select(['request_number'])->all()->last();
        
        $currentyear = date('y');
        if(!empty($invrequestdet)){
            $invrequestarr = explode('-', $invrequestdet->request_number);
            if($invrequestarr[0] == $currentyear){
                $invrequestid = $invrequestarr[1]+1;
                $remaingtoaddzero = 4-strlen($invrequestid);
                $request_number = $invrequestarr[0].'-';
                for($i=1; $i<=$remaingtoaddzero; $i++){
                    $request_number .= '0';
                }
                $request_number .= $invrequestid;
            }else{
                $request_number = $currentyear.'-0001';
            }
        }else{
            $request_number = $currentyear.'-0001';
        }
        return $request_number;
    }

    public function saveInventoryRequestForWO($postData, $wo_item_part_id){
        $authUserData = $this->Authentication->getResult()->getData();
        $this->InventoryRequests = $this->getController()->fetchTable('InventoryRequests');
        $this->InventoryRequestItems = $this->getController()->fetchTable('InventoryRequestItems');
        $this->InventoryItems = $this->getController()->fetchTable('InventoryItems');
        
        $inventoryrequests = $this->InventoryRequests->newEmptyEntity();
        $request_number = $this->getInventoryRequestNumber();

        $requestdata = [];

        $requestdata['work_order_id'] = $postData['work_order_id'];
        $requestdata['wo_item_part_id'] = $wo_item_part_id;
        $requestdata['request_number'] = $request_number;
        $requestdata['title'] = 'WO-'.$postData['work_order_no'];
        $requestdata['requested_by'] = $authUserData['full_name'];
        $requestdata['need_by'] = $postData['date_needed'];
        $requestdata['added_by'] = $authUserData['id'];
        
        $inventoryrequests = $this->InventoryRequests->patchEntity($inventoryrequests, $requestdata);

        if ($this->InventoryRequests->save($inventoryrequests)) {
            $id = $inventoryrequests->id;

            $invitemsdet = $this->InventoryItems->find('all')->where(['part_number'=>$postData['part_number']])->select(['id'])->first();
            if(!empty($invitemsdet)){
                $invenotryrequestitems = $this->InventoryRequestItems->newEmptyEntity();

                $invrequestpost = [];
                $invrequestpost['inventory_request_id'] = $id;
                $invrequestpost['inventory_item_id'] = $invitemsdet->id;
                $invrequestpost['noninventory_item'] = '';
                $invrequestpost['wo_item_id'] = $postData['wo_item_id'];
                $invrequestpost['work_order_id'] = $postData['work_order_id'];
                $invrequestpost['wo_item_part_id'] = $wo_item_part_id;
                $invrequestpost['qty'] = $postData['qty_needed'];
                $invrequestpost['uom'] = '1';
                $invrequestpost['location_id'] = '';
                $invrequestpost['added_by'] = $authUserData['id'];
                $invrequestpost['updated_by'] = $authUserData['id'];

                $invenotryrequestitems = $this->InventoryRequestItems->patchEntity($invenotryrequestitems, $invrequestpost);
                $this->InventoryRequestItems->save($invenotryrequestitems);
            }

            //save data to request history table
            $this->InventoryHistory->saveInventoryRequestHistory($inventoryrequests);
        }
    }

    public function removeInventoryRequestForWO($wo_item_part_id){
        $authUserData = $this->Authentication->getResult()->getData();
        $this->InventoryRequests = $this->getController()->fetchTable('InventoryRequests');
        $this->InventoryRequestItems = $this->getController()->fetchTable('InventoryRequestItems');
    
        $inventoryrequests = $this->InventoryRequests
                                ->find()
                                ->where(['wo_item_part_id' => $wo_item_part_id])
                                ->first();
    
        if (!empty($inventoryrequests)) {
            $count = $this->InventoryRequestItems
                        ->find()
                        ->where(['inventory_request_id' => $inventoryrequests->id])
                        ->count();
    
            if ($count > 0) {
                $this->InventoryRequestItems->deleteAll([
                    'inventory_request_id' => $inventoryrequests->id
                ]);
            }
    
            $this->InventoryRequests->delete($inventoryrequests);
        }
    
    }
}