<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use Cake\View\View;
    use Dompdf\Dompdf;
    use App\View\Helper\InventoryStatusHTMLHelper;
    use Cake\Datasource\FactoryLocator;
    use Cake\ORM\Locator\LocatorAwareTrait;
    use Cake\Event\EventInterface;
    use Cake\I18n\FrozenTime;

    class InventoryPurchaseOrdersController extends AppController
    {
        protected \App\Model\Table\InventoryItemsTable $InventoryItems;
        protected \App\Model\Table\InventoryLocationsTable $InventoryLocations;
        protected \App\Model\Table\InventoryPOItemsTable $InventoryPOItems;
        protected \App\Model\Table\InventoryVendorsTable $InventoryVendors;
        protected \App\Model\Table\InventoryAddressesTable $InventoryAddresses;
        protected \App\Model\Table\InventoriesTable $Inventories;
        protected \App\Model\Table\InventoryPOReceivesTable $InventoryPOReceives;
        protected \App\Model\Table\InventoryPurchaseOrderLinksTable $InventoryPurchaseOrderLinks;
        protected \App\Model\Table\InventoryRepairOrdersTable $InventoryRepairOrders;
        protected \App\Model\Table\InventoryRequestItemsTable $InventoryRequestItems;
        protected \App\Model\Table\InventoryShippingOrdersTable $InventoryShippingOrders;
        protected \App\Model\Table\InventoryRequestsTable $InventoryRequests;
        protected \App\Model\Table\InventoryPurchaseOrderHistoriesTable $InventoryPurchaseOrderHistories;
        
        public function initialize():void {
            parent::initialize();

            $this->InventoryItems = $this->fetchTable('InventoryItems');
            $this->InventoryLocations = $this->fetchTable('InventoryLocations');
            $this->InventoryPOItems = $this->fetchTable('InventoryPOItems');
            $this->InventoryVendors = $this->fetchTable('InventoryVendors');
            $this->InventoryAddresses = $this->fetchTable('InventoryAddresses');
            $this->Inventories = $this->fetchTable('Inventories');
            $this->InventoryPOReceives = $this->fetchTable('InventoryPOReceives');
            $this->InventoryPurchaseOrderLinks = $this->fetchTable('InventoryPurchaseOrderLinks');
            $this->InventoryRepairOrders = $this->fetchTable('InventoryRepairOrders');
            $this->InventoryRequestItems = $this->fetchTable('InventoryRequestItems');
            $this->InventoryShippingOrders = $this->fetchTable('InventoryShippingOrders');
            $this->InventoryRequests = $this->fetchTable('InventoryRequests');
            $this->InventoryPurchaseOrderHistories = $this->fetchTable('InventoryPurchaseOrderHistories');

            $this->loadComponent('Address');
            $this->loadComponent('InventoryFilter');
            $this->loadComponent('InventoryAttachment');
            $this->loadComponent('Inventory');
            $this->loadComponent('InventoryHistory');
        }

        public function beforeFilter(EventInterface $event) {
            parent::beforeFilter($event);

            $authUserData = $this->Authentication->getResult()->getData();
            $this->set('userData', $authUserData);
        }
        
        public function index()
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Purchase Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Purchase Orders'];
                }
                $this->set(compact('actionItems'));
            }
        }

        public function search()
        {
            $query = [];        
            
            $wherecond = '';
            
            $queryData = $this->getRequest()->getQuery();
            if(isset($queryData['inv_item_id']) && !empty($queryData['inv_item_id'])){
                $wherecond = " and invpo.id in(select distinct inventory_po_id from inventory_po_items where inventory_item_id = '".$queryData['inv_item_id']."')";
            }

            $query['count'] = "SELECT count(invpo.id) AS count  FROM `inventory_purchase_orders` as invpo left join inventory_vendors as invpovendor on invpo.vendor = invpovendor.id WHERE 1=1$wherecond ";
            
            //$query['detail'] = "SELECT inv.id, inv.po_number, inv.reference, v.name as vendor_name, inv.requestor, inv.account_code, inv.po_tax, inv.po_tax_amount, inv.po_shipping, inv.currency, (select (SUM(ip.cost*ip.qty)) as total_cost from inventory_po_items ip where ip.inventory_po_id = inv.id group by inventory_po_id) as tcost, inv.created, inv.po_status FROM `inventory_purchase_orders` as inv left join inventory_vendors as v on inv.vendor = v.id WHERE 1=1 ";

            $query['detail'] = "SELECT invpo.id, invpo.po_type, invpo.po_number, invpo.reference, invpovendor.name as vendor_name, invpo.requestor, invpo.account_code, invpo.po_tax, invpo.po_tax_amount, invpo.po_shipping, invpo.currency, invpo.exchange_status, invpo.po_cost_total, invpo.created, invpo.po_status, invpo.status FROM `inventory_purchase_orders` as invpo left join inventory_vendors as invpovendor on invpo.vendor = invpovendor.id WHERE 1=1$wherecond ";
            
            return $query;
        }

        public function ajaxInventoryPurchaseOrdersearch(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Purchase Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Purchase Orders'];
                }
            }
            $this->autoRender = false;
            $this->viewBuilder()->setLayout('ajax');
            $requestData= $this->request->getData();

            $query = $this->search();
            //echo "<pre>";print_r($this->getRequest()->getQuery());exit;
            $cond = "";
            
            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                parse_str($requestData['columns'][1]['search']['value'], $requestData);
            }
            
            $queryData = $this->getRequest()->getQuery();
            $cond = $this->InventoryFilter->inventoryPurchaseOrderFilter($requestData, $queryData);
            
            $requestData= $this->request->getData();
            $columns = array(
                0 => 'invpo.po_number',
                1 => 'invpo.reference',
                2 => 'vendor_name',
                3 => 'invpo.requestor',
                4 => 'invpo.account_code',
                5 => 'invpo.po_status',
                6 => 'invpo.po_cost_total',
                7 => 'invpo.created',
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
            $sort = $requestData['order'][0]['dir'];
            $start = $requestData['start'];
            $length = PAGINATION_LIMIT;

            $SQL = $detail." ORDER BY $sidx $sort LIMIT $start , $length ";//echo $SQL;exit;
            $results = $conn->execute( $SQL )->fetchAll('assoc');

            $data = array();
            $currency = unserialize(CURRENCY);
            $InventoryStatusHTMLHelper = new InventoryStatusHTMLHelper(new \Cake\View\View());
            
            foreach ( $results as $row){
                $statushtml = $InventoryStatusHTMLHelper->getPurchaseOrderOrderStatusHTML($row['status'], $row['po_status'], $row['po_type'], $row['exchange_status']);
                
                $isexchange = $row['po_type'] == '2' ? '<br/><span style="color:#8d8888; font-size:10px;" title="Purchase order type">Exchange</span>' : '';

                $nestedData= [];
                $nestedData[] = '<input type="hidden" value="'.$row['id'].'" class="chkBoxCls">';
                $nestedData[] = $row["po_number"].$isexchange;
                $nestedData[] = isset($row["reference"]) ? $row["reference"] : '-';
                $nestedData[] = isset($row["vendor_name"]) ? $row["vendor_name"] : '-';
                $nestedData[] = isset($row["requestor"]) ? $row["requestor"] : '-';
                $nestedData[] = $row["account_code"];
                $nestedData[] = $row['po_cost_total'].' '.$currency[$row['currency']];
                $nestedData[] = isset($row["created"]) ? date('d-m-Y', strtotime($row["created"])) : '-';
                $nestedData[] = $statushtml;

                $data[] = $nestedData;
            }

            $roles = array(
                "draw"            => intval( $requestData['draw'] ),
                "recordsTotal"    => intval( $totalRecords ),
                "recordsFiltered" => intval( $totalFiltered ),
                "data"            => $data
            );
        
            echo json_encode($roles);die;
        }

        /**
         * Add method
         *
         * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
         */
        public function create($id=null)
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Purchase Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Purchase Orders'];
                }
            }

            $queryData = $this->getRequest()->getQuery();

            $porequestid = isset($queryData['porequestid']) ? $queryData['porequestid'] : '';
            $invitemid = isset($queryData['invitemid']) ? $queryData['invitemid'] : '';
            $po_type = isset($queryData['po_type']) ? $queryData['po_type'] : '';
            $linkedOrderId = isset($queryData['linkedOrderId']) ? $queryData['linkedOrderId'] : '0';
            $linkedOrderType = isset($queryData['linkedOrderType']) ? $queryData['linkedOrderType'] : '';
            $parentLinkedType = isset($queryData['parentLinkedType']) ? $queryData['parentLinkedType'] : '';

            $inventorypurchaseorders = $this->InventoryPurchaseOrders->newEmptyEntity();
            $inventorypoitems = $this->InventoryPOItems->newEmptyEntity();
            
            if(!empty($id)){
                $inventorypurchaseorders = $this->InventoryPurchaseOrders->find('all')->where(['id'=>$id])->select($this->InventoryPurchaseOrders)->first();
            }
            
            if ($this->request->is(['patch', 'post', 'put'])) {
                $inventorypurchaseorders = $this->InventoryPurchaseOrders->newEmptyEntity();
                
                $postData = $this->request->getData();//print_r($postData);exit;
                
                $invpoduplicatecheck = $this->InventoryPurchaseOrders->find('all')->where(['po_number'=>$postData['po_number']])->select($this->InventoryPurchaseOrders);

                if($invpoduplicatecheck->count() > 0){
                    $this->Flash->error(__('Purchase order with the number '.$postData['po_number'].' already exists. It may be inactive.'));

                    return $this->redirect( Router::url( $this->referer(), true ) );
                }

                $error_message = $this->Inventory->validatePurchaseOrderSaveData($postData);
                if(!empty($error_message)){
                    $this->Flash->error(__($error_message));
                    return $this->redirect( Router::url( $this->referer(), true ) );
                }
                
                $postData['added_by'] = $authUserData['id'];//echo "<pre>";print_r($postData);exit;
                $subTotalCost = 0;
                for($i=0; $i<count($postData['qty']); $i++){
                    $subTotalCost += !empty($postData['cost'][$i]) ? $postData['cost'][$i] : 0;
                }

                $po_tax_amount = !empty($postData['po_tax_amount']) ? $postData['po_tax_amount'] : 0;
                $po_shipping = !empty($postData['po_shipping']) ? $postData['po_shipping'] : 0;

                $totalCost = $subTotalCost+$po_tax_amount+$po_shipping;
                
                $postData['po_cost_subtotal'] = $subTotalCost;
                $postData['po_cost_total'] = $totalCost;

                $inventorypurchaseorders = $this->InventoryPurchaseOrders->patchEntity($inventorypurchaseorders, $postData);
                if ($this->InventoryPurchaseOrders->save($inventorypurchaseorders)) {
                    $po_id = $inventorypurchaseorders->id;
                    
                    $this->Inventory->saveInventoryPurchaseOrderLinks($parentLinkedType, $linkedOrderType, $linkedOrderId, $po_id);

                    if(!empty($postData['request'])){
                        $inventoryrequests = $this->InventoryRequests->newEmptyEntity();
                        $inventoryrequests = $this->InventoryRequests->get($postData['request']);
                        $invrequest = [];
                        $invrequest['request_status'] = '5';
                        $invrequest['updated_by'] = $authUserData['id'];
                        $inventoryrequests = $this->InventoryRequests->patchEntity($inventoryrequests, $invrequest);
                        $this->InventoryRequests->save($inventoryrequests);
                    }

                    for($i=0; $i<count($postData['uom']); $i++){
                        $inventorypoitems = $this->InventoryPOItems->newEmptyEntity();

                        $invrequestpost = [];
                        $invrequestpost['inventory_po_id'] = $po_id;
                        $invrequestpost['inventory_item_id'] = isset($postData['inventory_item_id'][$i]) ? $postData['inventory_item_id'][$i] : '';
                        $invrequestpost['noninventory_item'] = isset($postData['noninventory_item'][$i]) && !empty($postData['noninventory_item'][$i]) ? $postData['noninventory_item'][$i] : '';
                        $invrequestpost['qty'] = $postData['qty'][$i];
                        $invrequestpost['uom'] = !empty($postData['uom'][$i]) ? $postData['uom'][$i] : '1';
                        
                        $invrequestpost['cost'] = $postData['cost'][$i];
                        $invrequestpost['location_id'] = isset($postData['location_id'][$i]) ? $postData['location_id'][$i] : '';
                        
                        $inventorypoitems = $this->InventoryPOItems->patchEntity($inventorypoitems, $invrequestpost);
                        
                        $this->InventoryPOItems->save($inventorypoitems);  
                                   
                    }

                    //save data to purchase order history table
                    $this->InventoryHistory->saveInventoryPurchaseOrderHistory($inventorypurchaseorders);

                    $this->InventoryAttachment->saveInvPOAttachment($po_id, $postData);
                    $this->Flash->success(__('The purchase order has been saved.'));
                    return $this->redirect(['action' => 'detail', $po_id]);
                }

                $this->Flash->error(__('The purchase order could not be saved. Please, try again.'));
            }
            
            $countries = $this->Address->getCountryList();
            $states = '';
            $inventoryvendors = $this->InventoryVendors->newEmptyEntity();
            $inventoryaddresses = $this->InventoryAddresses->newEmptyEntity();
            $inventoryaddressesarr = $this->InventoryAddresses->find('all')->where(['added_by'=>$authUserData['id']]);
            $billingaddress = array();
            $shippingaddress = array();
            foreach($inventoryaddressesarr as $address){
                if($address['is_billing_address'] == '1'){
                    $billingaddress[$address['id']] = $address['name'];
                }
                if($address['is_shipping_address'] == '1'){
                    $shippingaddress[$address['id']] = $address['name'];
                }
            }
            $vendor = $this->Inventory->getVendorList();
            $manufacturer = $this->Inventory->getManufacturerList();
            
            $inventorypoitems = [];
            $inventoryitems = [];
            $invtype = '';
            if(!empty($porequestid)){
                $inventorypoitems = $this->InventoryRequestItems->find('all')->where(['InventoryRequestItems.inventory_request_id'=>$porequestid])->select($this->InventoryRequestItems)->select(['invitms.name', 'invitms.part_number', 'invloc.location_name'])
                ->join([
                    'invitms' => [
                        'table' => 'inventory_items',
                        'type' => 'LEFT',
                        'conditions' => 'invitms.id = InventoryRequestItems.inventory_item_id',
                    ],
                    'invloc' => [
                        'table' => 'inventory_locations',
                        'type' => 'left',
                        'conditions' => 'invloc.id = InventoryRequestItems.location_id',
                    ]
                ]);
                $po_type = 1;
            }else if(!empty($invitemid)){
                $invitemarr = $this->InventoryItems->get($invitemid);
                $invitemarr->cost = $invitemarr->unit_cost;
                $inventorypoitems[] = $invitemarr;
                $invtype = 'invitem';
            }
            
            $inventoryitems = $this->InventoryItems->find('all')->where(['status'=>'1'])->select($this->InventoryItems);
            $location = $this->Inventory->getAllLocations();

            $po_number = $this->Inventory->getPurchaseOrderNumber();
            
            $this->set(compact('inventorypurchaseorders', 'actionItems', 'countries', 'states', 'inventoryvendors', 'inventoryaddresses', 'billingaddress', 'shippingaddress', 'vendor', 'manufacturer', 'inventorypoitems', 'porequestid', 'inventoryitems', 'location', 'po_type', 'invitemid', 'invtype', 'po_number'));
        }

        public function edit($id = null)
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('Purchase Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Purchase Orders'];
                }
            }

            $inventorypurchaseorders = $this->InventoryPurchaseOrders->find('all')->where(['id'=>$id])->select()->first($this->InventoryPurchaseOrders);
            if(empty($inventorypurchaseorders)){
                return $this->redirect(['action' => 'index']);
            }

            //echo "<pre>";print_r($inventorypurchaseorders);exit;
            if ($this->request->is(['patch', 'post', 'put'])) {
                
                $postData = $this->request->getData();//print_r($postData);exit;
                
                $invpoduplicatecheck = $this->InventoryPurchaseOrders->find('all')->where(['po_number'=>$postData['po_number'], 'id !='=>$id])->select($this->InventoryPurchaseOrders);

                if($invpoduplicatecheck->count() > 0){
                    $this->Flash->error(__('Purchase order with the number '.$postData['po_number'].' already exists. It may be inactive.'));

                    return $this->redirect( Router::url( $this->referer(), true ) );
                }
                
                $error_message = $this->Inventory->validatePurchaseOrderSaveData($postData);
                if(!empty($error_message)){
                    $this->Flash->error(__($error_message));
                    return $this->redirect( Router::url( $this->referer(), true ) );
                }

                $postData['updated_by'] = $authUserData['id'];
                $subTotalCost = 0;
                for($i=0; $i<count($postData['qty']); $i++){
                    $subTotalCost += $postData['cost'][$i];
                }
                
                $po_tax_amount = !empty($postData['po_tax_amount']) ? $postData['po_tax_amount'] : 0;
                $po_shipping = !empty($postData['po_shipping']) ? $postData['po_shipping'] : 0;

                $totalCost = $subTotalCost+$po_tax_amount+$po_shipping;
                
                $postData['po_cost_subtotal'] = $subTotalCost;
                $postData['po_cost_total'] = $totalCost;

                $countdet = $this->Inventory->getCountPOReceivedAndPOItem($id);
                
                //update purchase order status
                if($countdet['invporeccount'] == $countdet['invpoitmcount']){
                    $postData['po_status'] = '4';
                }
                
                $inventorypurchaseorders = $this->InventoryPurchaseOrders->patchEntity($inventorypurchaseorders, $postData);//print_r($part);exit;
                if ($this->InventoryPurchaseOrders->save($inventorypurchaseorders)) {
                    //delete all po items
                    $connection = ConnectionManager::get('default');
                    
                    if(!empty($postData['itemid'])){
                        $results = $connection
                        ->execute(
                            'delete from inventory_po_items WHERE inventory_po_id = :inventory_po_id and id not in('."'" . implode ( "', '", $postData['itemid'] ) . "'".')',
                            ['inventory_po_id' => $id],
                            ['created' => 'datetime']
                        );
                    

                        $results = $connection
                        ->execute(
                            'delete from inventory_po_receives WHERE inventory_po_id = :inventory_po_id and inventory_id != 0 and inventory_po_item_id not in('."'" . implode ( "', '", $postData['itemid'] ) . "'".')',
                            ['inventory_po_id' => $id],
                            ['created' => 'datetime']
                        );
                    }
                    
                    for($i=0; $i<count($postData['uom']); $i++){
                        $inventorypoitems = $this->InventoryPOItems->newEmptyEntity();

                        $itemid = isset($postData['itemid'][$i]) ? $postData['itemid'][$i] : '';
                        if(!empty($itemid)){
                            $inventorypoitems = $this->InventoryPOItems->get($itemid);
                        }

                        $invrequestpost = [];
                        $invrequestpost['inventory_po_id'] = $id;
                        $invrequestpost['inventory_item_id'] = isset($postData['inventory_item_id'][$i]) ? $postData['inventory_item_id'][$i] : '';
                        $invrequestpost['noninventory_item'] = isset($postData['noninventory_item'][$i]) && !empty($postData['noninventory_item'][$i]) ? $postData['noninventory_item'][$i] : '';
                        $invrequestpost['qty'] = $postData['qty'][$i];
                        $invrequestpost['uom'] = !empty($postData['uom'][$i]) ? $postData['uom'][$i] : '1';
                        
                        $invrequestpost['cost'] = $postData['cost'][$i];
                        $invrequestpost['location_id'] = isset($postData['location_id'][$i]) ? $postData['location_id'][$i] : '';
                        
                        $inventorypoitems = $this->InventoryPOItems->patchEntity($inventorypoitems, $invrequestpost);
                        
                        $this->InventoryPOItems->save($inventorypoitems);  
                                
                    }
                    $this->InventoryAttachment->saveInvPOAttachment($id, $postData);
                    $this->Flash->success(__('The purchase order has been saved.'));
                    return $this->redirect(['action' => 'detail', $id]); 
                }

                $this->Flash->error(__('The purchase order could not be saved. Please, try again.'));
            }
            
            $countries = $this->Address->getCountryList();
            $states = '';
            $inventoryvendors = $this->InventoryVendors->newEmptyEntity();
            $inventoryaddresses = $this->InventoryAddresses->newEmptyEntity();
            $inventoryaddressesarr = $this->InventoryAddresses->find('all')->where(['added_by'=>$authUserData['id']]);
            $billingaddress = array();
            $shippingaddress = array();
            foreach($inventoryaddressesarr as $address){
                if($address['is_billing_address'] == '1'){
                    $billingaddress[$address['id']] = $address['name'];
                }
                if($address['is_shipping_address'] == '1'){
                    $shippingaddress[$address['id']] = $address['name'];
                }
            }
            $vendor = $this->Inventory->getVendorList();
            $manufacturer = $this->Inventory->getManufacturerList();

            $inventorypoitems = $this->InventoryPOItems->find('all')->where(['InventoryPOItems.inventory_po_id'=>$id])->select($this->InventoryPOItems)->select(['invitms.name', 'invitms.part_number','invloc.location_name'])
            ->join([
                'invitms' => [
                    'table' => 'inventory_items',
                    'type' => 'LEFT',
                    'conditions' => 'invitms.id = InventoryPOItems.inventory_item_id',
                ],
                'invloc' => [
                    'table' => 'inventory_locations',
                    'type' => 'left',
                    'conditions' => 'invloc.id = InventoryPOItems.location_id',
                ]
            ]);
            $inventoryitems = $this->InventoryItems->find('all')->where(['status'=>'1'])->select($this->InventoryItems);
            $location = $this->Inventory->getAllLocations();
            $attachments = $this->InventoryAttachment->getInvPOAttachedFiles($id);
            
            $this->set(compact('inventorypurchaseorders', 'actionItems', 'countries', 'states', 'inventoryvendors', 'inventoryaddresses', 'billingaddress', 'shippingaddress', 'vendor', 'manufacturer', 'inventorypoitems', 'inventoryitems', 'location', 'attachments'));
        }

        public function detail($id = null)
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Purchase Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Purchase Orders'];
                }
            }
            
            $inventorypurchaseorders = $this->InventoryPurchaseOrders->find('all')->where(['id'=>$id])->select()->first($this->InventoryPurchaseOrders);
            if(empty($inventorypurchaseorders)){
                return $this->redirect(['action' => 'index']);
            }

            $postData = $this->request->getData();

            if ($this->request->is(['patch', 'post', 'put']) && isset($postData['tracking_number'])) {

                for($i=0; $i<count($postData['tracking_number']); $i++){
                    $inventorypoitems = $this->InventoryPOItems->newEmptyEntity();

                    $itemid = isset($postData['itemid'][$i]) ? $postData['itemid'][$i] : '';
                    if(!empty($itemid)){
                        $inventorypoitems = $this->InventoryPOItems->get($itemid);
                    }

                    $invrequestpost = [];
                    $invrequestpost['inventory_po_id'] = $id;
                    $invrequestpost['tracking_number'] = $postData['tracking_number'][$i];
                    
                    $inventorypoitems = $this->InventoryPOItems->patchEntity($inventorypoitems, $invrequestpost);
                    
                    $this->InventoryPOItems->save($inventorypoitems); 
                }
                
                $purchaseOrderItems = $this->InventoryPOItems->find('all')->where(['inventory_po_id'=>$inventorypurchaseorders->id])->select($this->InventoryPOItems);
                $invpoitemids = [];
                foreach($purchaseOrderItems as $invpoitm){
                    $invpoitemids[] = $invpoitm->id;
                }
                $poreceivedcount = $this->InventoryPOReceives->find('all')->where(['InventoryPOReceives.inventory_po_id'=>$id, 'InventoryPOReceives.inventory_po_item_id IN'=>$invpoitemids])->select('inventory_po_item_id')->distinct()->count();
                
                if(count($invpoitemids) == $poreceivedcount){
                    $podata = [];
                    $podata['po_status'] = '4';
                    $inventorypoupdate = $this->InventoryPurchaseOrders->patchEntity($inventorypurchaseorders, $podata);
                    $this->InventoryPurchaseOrders->save($inventorypoupdate); 
                }
                $this->InventoryAttachment->saveInvPOAttachment($id, $postData);
                $this->Flash->success(__('The purchase order has been saved.'));

                return $this->redirect(['action' => 'detail', $id]); 
            }
            
            $inventorypoitems = $this->InventoryPOItems->find('all')->where(['InventoryPOItems.inventory_po_id'=>$id])->select($this->InventoryPOItems)->select(['invitms.name', 'invitms.part_number','invloc.location_name'])
            ->join([
                'invitms' => [
                    'table' => 'inventory_items',
                    'type' => 'LEFT',
                    'conditions' => 'invitms.id = InventoryPOItems.inventory_item_id',
                ],
                'invloc' => [
                    'table' => 'inventory_locations',
                    'type' => 'left',
                    'conditions' => 'invloc.id = InventoryPOItems.location_id',
                ]
            ]);

            $inventorypoitemsrec = $this->InventoryPOReceives->find('all')->where(['InventoryPOReceives.inventory_po_id'=>$id])->select($this->InventoryPOReceives)->select(['invitms.id', 'invitms.name', 'invitms.part_number','inv.serial_no', 'invloc.location_name', 'invloc.id'])
            ->join([
                'inv' => [
                    'table' => 'inventories',
                    'type' => 'LEFT',
                    'conditions' => 'inv.id = InventoryPOReceives.inventory_id',
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
                $inventoryporeceivedarr[$val['inventory_po_item_id']][] = $val;
                if(isset($invitmreceived[$val['inventory_po_item_id']])){
                    $invitmreceived[$val['inventory_po_item_id']] += $val['received'];
                }else{
                    $invitmreceived[$val['inventory_po_item_id']] = $val['received'];
                }
            }
            //print_r($inventoryporeceivedarr);exit;
            $vendors = '';
            if(!empty($inventorypurchaseorders->vendor)){
                $vendors = $this->InventoryVendors->get($inventorypurchaseorders->vendor);
            }

            $inventorybillingaddress = $this->InventoryAddresses->get($inventorypurchaseorders->bill_to_address);
            $inventoryshippingaddress = '';
            if(!empty($inventorypurchaseorders->ship_to_address)){
                $inventoryshippingaddress = $this->InventoryAddresses->get($inventorypurchaseorders->ship_to_address);
            }

            $countries = $this->Address->getCountryList();
            $attachments = $this->InventoryAttachment->getInvPOAttachedFiles($id);

            //link order
            $linkorderdata = $this->Inventory->getLinkOrdersData($id, '1');
            //echo "<pre>";print_r($linkorderdata);exit;

            $request = '';
            if(!empty($inventorypurchaseorders->request)){
                $request = $this->InventoryRequests->get($inventorypurchaseorders->request);
            }

            $inventorypohistories = $this->InventoryPurchaseOrderHistories->find('all')
                                    ->where(['user_id'=>$authUserData['id'], 'inventory_purchase_order_id'=>$id])
                                    ->select($this->InventoryPurchaseOrderHistories)->select(['users.email'])
                                    ->join([
                                        'users' => [
                                            'table' => 'users',
                                            'type' => 'INNER',
                                            'conditions' => 'users.id = InventoryPurchaseOrderHistories.user_id',
                                        ]
                                    ])->order(['InventoryPurchaseOrderHistories.id'=>'DESC']);

            //echo "<pre>";print_r($inventorypurchaseorders);exit;
            
            $InventoryStatusHTMLHelper = new InventoryStatusHTMLHelper(new \Cake\View\View());
            $statushtml = $InventoryStatusHTMLHelper->getPurchaseOrderOrderStatusHTML($inventorypurchaseorders->status, $inventorypurchaseorders->po_status, $inventorypurchaseorders->po_type, $inventorypurchaseorders->exchange_status);

            $this->set(compact('inventorypurchaseorders', 'actionItems', 'inventorypoitems', 'inventorybillingaddress', 'inventoryshippingaddress', 'countries', 'vendors', 'inventoryporeceivedarr', 'invitmreceived', 'attachments', 'linkorderdata', 'request', 'inventorypohistories', 'statushtml'));
        }

        public function inventoryPOItemDropDown(){
            
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $inventoryitems = $this->InventoryItems->find('all')->where(['status'=>'1'])->select($this->InventoryItems);
                $location = $this->Inventory->getAllLocations();
                
                $currencyarr = unserialize(CURRENCY);

                $this->set('location', $location);
                $this->set('inventoryitems', $inventoryitems);
                $this->set('invtype', $postData['invtype']);
                $this->set('pocurrency', $currencyarr[$postData['currency']]);
                
                $this->viewBuilder()->setLayout('ajax');
                $this->render("/element/Inventory/inventory_po_item_add");
            }
        }

        public function updatePurchaseOrderStatus($id=null){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Purchase Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Purchase Orders'];
                }
            }
            $id = $_POST['id'];
            $inventorypurchaseorders = $this->InventoryPurchaseOrders->get($id);
            
            try {
                $postData = array();
                $status = $_POST['status'];
                $po_status = $_POST['po_status'];
                
                if(!empty($po_status)){
                    $postData['po_status'] = $po_status;
                }else if($status != ''){
                    $postData['status'] = $status;
                }
                $postData['updated_by'] = $authUserData['id'];
                $inventorypurchaseorders = $this->InventoryPurchaseOrders->patchEntity($inventorypurchaseorders, $postData);//echo "<pre>";print_r($inventorypurchaseorders);exit;
                if ($this->InventoryPurchaseOrders->save($inventorypurchaseorders)) {
                    if($po_status == '3'){
                        $msg = 'Canceled purchase order request `'.$inventorypurchaseorders->po_number.'`.';
                    }else if($po_status == '1'){
                        $msg = 'Reopen purchase order request `'.$inventorypurchaseorders->po_number.'`.';
                    }else if($po_status == '2'){
                        $msg = 'Sent purchase order request `'.$inventorypurchaseorders->po_number.'`.';
                    }else if($status == '0'){
                        $msg = 'Invenotry request deactivated `'.$inventorypurchaseorders->po_number.'`.';
                    }else if($status == '1'){
                        $msg = 'Invenotry request activated `'.$inventorypurchaseorders->po_number.'`.';
                    }
                    
                    $this->Flash->success(__($msg));
                } else {
                    $msg = 'You may not close a PO Request if it is Cancelled, Denied, PO Created, or already Closed.';
                    $this->Flash->error(__($msg));
                }
            } catch(\PDOException $e) {
                $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
            } catch (\Exception $e) {
                $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
            }

            return $this->redirect(['action' => 'detail', $id]);
        }

        //Generate InventoryPurchaseOrders report pdf
        public function generateInvPOPdf()
        {
            $postData = $this->request->getData();
            $mainHtml = '';
            $flHtml = '';
            
            $flRes = $this->Inventory->InventoryPOPDFData($postData);
            if(!empty($flRes)) {
                $flHtml = $flRes;
            } else {
                $flHtml = '<tr><td colspan="16" style="text-align: center;">No Records Found for that Purchase Order.</td></tr>';
            }

            $mainHtml .= '<header>
            <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td style="font-size: 14px; font-weight: normal; text-align:center;"><span style="font-weight: bold;">PURCHASE ORDERS</span></td>
                    </tr>
                    <tr>
                        <td style="font-size: 10px; font-weight: normal; text-align:center;"><span>A list of purchase orders for a given filter criteria.</span></td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; font-weight: normal; text-align:left;"><span>Address block.</span></td>
                    </tr>
                </tbody>
            </table>
        </header>';

            $mainHtml .= '<p><table style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center; font-size:11px;" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Order / Type</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Reference</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Vendor</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Requestor</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Cost</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Submitted</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    '.$flHtml.'
                                </tbody>
                            </table></p>';

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
                                line-height: 2.5;
                            }

                            header {
                                position: fixed;
                                top: .5cm;
                                left: 0cm;
                                right: 0cm;
                                bottom: 0cm;
                                height: 1cm;
                            }

                            footer {
                                position: fixed; 
                                bottom: .3cm;
                                left: 0cm; 
                                right: 0cm;
                                height: 1cm;
                            }

                            .page_break { 
                                page-break-before: always; 
                            }

                            th{
                                background-color:#d3d3d3;
                            }
                        </style>
                    </head>
                    <body>
                        <main>'.$mainHtml.'</main>        
                    </body>
                    </html>';
        
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
            
            //save the file on particular location
            //https://mpdf.github.io/reference/mpdf-functions/output.html
            $fileName = "InventoryPurchaseOrders".date('YmdHis').".pdf";
            $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
            if($mainHtml) {
                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
            
        }

        public function generateInvPODetPdf()
        {
            $postData = $this->request->getData();
            $mainHtml = '';
            $flHtml = '';
            
            $id = $postData['id'];
            $inventorypurchaseorders = $this->InventoryPurchaseOrders->get($id);
            $invPurchaseOrderType = unserialize(INVENTORY_PURCHASE_TYPE);
            $bar_code = Router::url(['controller' => 'InventoryPurchaseOrders', 'action' => 'detail', $inventorypurchaseorders->id]);

            $status = '';
                
            if($inventorypurchaseorders->po_status == '1'){
                $status = 'Partial';
            }else if($inventorypurchaseorders->po_status == '0'){
                $status = 'Draft';
            }else if($inventorypurchaseorders->po_status == '3'){
                $status = 'Canceled';
            }else if($inventorypurchaseorders->po_status == '2'){
                $status = 'Sent';
            }else if($inventorypurchaseorders->po_status == '4'){
                $status = 'Close';
            }

            $mainHtml .= '<header>
                                <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="font-size: 14px; font-weight: normal; text-align:center;"><span style="font-weight: bold;">PURCHASE ORDERS</span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; font-weight: normal; text-align:center; margin-top:15px;">
                                            <table style="width:100%">
                                                <tr>
                                                    <td><b>Order Numbe</b></td>
                                                    <td><b>Date</b></td>
                                                    <td><b>Status</b></td>
                                                </tr>
                                                <tr>
                                                    <td>'.$inventorypurchaseorders->po_number.'</td>
                                                    <td>'.$inventorypurchaseorders->po_date.'</td>
                                                    <td>'.$status.'</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Account Code</b></td>
                                                    <td><b>Reference</b></td>
                                                    <td><b>Type</b></td>
                                                </tr>
                                                <tr>
                                                    <td>'.$inventorypurchaseorders->account_code.'</td>
                                                    <td>'.$inventorypurchaseorders->reference.'</td>
                                                    <td>'.$invPurchaseOrderType[$inventorypurchaseorders->po_type].'</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <img src="'.BARCODEAPIURL.'chl='.$bar_code.'" />
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </header>';
            $vendor_name = '';
            if(!empty($inventorypurchaseorders->vendor)){
                $vendors = $this->InventoryVendors->get($inventorypurchaseorders->vendor);
                $vendor_name = $vendors->name;
            }

            $inventorybillingaddress = $this->InventoryAddresses->get($inventorypurchaseorders->bill_to_address);
            $shipping_address = '';
            if(!empty($inventorypurchaseorders->ship_to_address)){
                $inventoryshippingaddress = $this->InventoryAddresses->get($inventorypurchaseorders->ship_to_address);
                $shipping_address = $inventoryshippingaddress->name;
            }
            $shipviaarr = unserialize(SHIP_VIA);
            $mainHtml .= '<table style="width:100%; margin:0 auto; padding:7% 0 10px 0; text-align:center; font-size:11px; clear:both;" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Vendor</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Ship To</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Bill To</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Shipping</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td>'.$vendor_name.'</td>
                                    <td>'.$inventorybillingaddress->name.'</td>
                                    <td>'.$shipping_address.'</td>
                                    <td>'.(!empty($inventorypurchaseorders->ship_via) ? $shipviaarr[$inventorypurchaseorders->ship_via] : '').'</td>
                                    </tr>
                                </tbody>
                        </table>';

            
            $inventorypoitems = $this->InventoryPOItems->find('all')->where(['InventoryPOItems.inventory_po_id'=>$id])->select($this->InventoryPOItems)->select(['invitms.name', 'invitms.part_number','invloc.location_name'])
            ->join([
                'invitms' => [
                    'table' => 'inventory_items',
                    'type' => 'LEFT',
                    'conditions' => 'invitms.id = InventoryPOItems.inventory_item_id',
                ],
                'invloc' => [
                    'table' => 'inventory_locations',
                    'type' => 'left',
                    'conditions' => 'invloc.id = InventoryPOItems.location_id',
                ]
            ]);
            
            $mainHtml .= '<table style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center; font-size:11px; clear:both;" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Item</th>
                                    <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Quantity</th>
                                    <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Cost</th>
                                    <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Total</th>
                                </tr>
                            </thead>
                            <tbody>';
            $flHtml = '';
            $currency = unserialize(CURRENCY);
            $subTotal = 0;
            $shipping = $inventorypurchaseorders->po_shipping;
            $salesTax = $inventorypurchaseorders->po_tax_amount;
            $currencyName = $currency[$inventorypurchaseorders->currency];

            foreach($inventorypoitems as $row){
                $totalcost = $row["cost"]*$row["qty"];
                $subTotal += $totalcost;

                $inventoryitemname = empty($row['noninventory_item']) ? $row['invitms']["name"].' ('.$row['invitms']["part_number"].')' : $row['noninventory_item'] ;

                $flHtml .= '<tr class="flRow">
                    <td style="line-height: 2.5;">'.$inventoryitemname.'</td>
                    <td style="line-height: 2.5;">'.$row["qty"].'</td>
                    <td style="line-height: 2.5;">'.$row["cost"].' '.$currencyName.'</td>
                    <td style="line-height: 2.5;">'.$totalcost.' '.$currencyName.'</td>
                </tr>';
            }
            $totalAmount = $subTotal+$shipping+$salesTax;

            $mainHtml .= $flHtml.'<tr>
                                    <td colspan="3" style="text-align: right;">Subtotal</td>
                                    <td>'.$subTotal.' '.$currencyName.'</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right;">Shipping</td>
                                    <td>'.$shipping.' '.$currencyName.'</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right;">Sales Tax</td>
                                    <td>'.$salesTax.' '.$currencyName.'</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right;"><b>Total</b></td>
                                    <td>'.$totalAmount.' '.$currencyName.'</td>
                                </tr>    
        
                            </tbody>
                        </table>';

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
                                line-height: 2.5;
                            }

                            header {
                                position: fixed;
                                top: .5cm;
                                left: 0cm;
                                right: 0cm;
                                bottom: 0cm;
                                height: 1cm;
                            }

                            footer {
                                position: fixed; 
                                bottom: .3cm;
                                left: 0cm; 
                                right: 0cm;
                                height: 1cm;
                            }

                            .page_break { 
                                page-break-before: always; 
                            }

                            th{
                                background-color:#d3d3d3;
                            }
                        </style>
                    </head>
                    <body>
                        <main>'.$mainHtml.'</main>        
                    </body>
                    </html>';
        
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
            
            //save the file on particular location
            //https://mpdf.github.io/reference/mpdf-functions/output.html
            $fileName = "InventoryPurchaseOrderDetails".date('YmdHis').".pdf";
            $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
            if($mainHtml) {
                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
            
        }

        public function saveInventoryAddress(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                
                $inventoryaddresses = $this->InventoryAddresses->newEmptyEntity();
                $authUserData = $this->Authentication->getResult()->getData();
                $postData = $this->request->getData();
                $postData['added_by'] = $authUserData['id'];
                $inventoryaddresses = $this->InventoryAddresses->patchEntity($inventoryaddresses, $postData);//print_r($part);exit;
                if ($this->InventoryAddresses->save($inventoryaddresses)) {
                    $id = $inventoryaddresses->id;

                    $is_billing_address = isset($postData['is_billing_address']) ? $postData['is_billing_address'] : '0';
                    $is_shipping_address = isset($postData['is_shipping_address']) ? $postData['is_shipping_address'] : '0';
                    
                    $invaddresses = array('id'=>$id, 'name'=>$postData['name'], 'is_billing_address'=>$is_billing_address, 'is_shipping_address'=>$is_shipping_address);
                    $result = array('status'=>'success', 'message'=>"Saved successfully.", 'invaddresses'=>$invaddresses);
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                
                echo json_encode($result);die;
            }
        }

        public function bulkInvPOAttachmentUpload(){
            $postData = $this->request->getData();
            if(!empty($postData['file_name'])) 
            {
                
                $isvalidfile = 1;
                $arr_ext = array('pdf','txt');
                
                $attachment = $postData['file_name']; 
                $name = $attachment->getClientFilename();
                $type = $attachment->getClientMediaType();
                $size = $attachment->getSize();
                $temp = $attachment->getStream()->getMetadata('uri');
                $ext = substr(strrchr($name , '.'), 1);
                
                /*if (!in_array($ext, $arr_ext)) {
                    $isvalidfile = 0;
                }*/
                
                if($isvalidfile){
                    $foldername = 'inventorypoitems';
                    $filelocation = WWW_ROOT . $foldername.'/' . $name;
                    $tblrow = $this->InventoryAttachment->uploadSelectedFilesToServer($postData, $filelocation, $foldername);
                    
                    if($tblrow != ''){
                        $result = array('status'=>'success', 'message'=>"Saved successfully.", 'tblrow'=>$tblrow);
                    } else {
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                } else {
                    $result = array('status'=>'failed', 'message'=>'Please upload correct format.');
                }
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                echo json_encode($result);die;
            }
        }

        public function deleteInvPOAttachment(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Purchase Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Purchase Orders'];
                }
            }
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['id'])){
                    $attachments = $this->InventoryAttachment->deleteInvPOAttachment($postData['id']);

                    $result = array('status'=>'success', 'message'=>"Deleted successfully.");
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);die;
        }

        public function saveInventoryReceived(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                $postData = $this->request->getData();

                $authUserData = $this->Authentication->getResult()->getData();
                $invenotryporeceives = $this->InventoryPOReceives->newEmptyEntity();
                $inventoryporecdata = [];

                $inventoryporecdata['inventory_po_id']      = $postData['inventory_po_id'];
                $inventoryporecdata['inventory_po_item_id'] = $postData['inventory_po_item_id'];
                $inventoryporecdata['inventory_id']         = 0;
                $inventoryporecdata['received']             = $postData['received'];
                $inventoryporecdata['added_by']             = $authUserData['id'];
                
                $invenotryporeceives = $this->InventoryPOReceives->patchEntity($invenotryporeceives, $inventoryporecdata);

                if ($this->InventoryPOReceives->save($invenotryporeceives)) {
                    $countdet = $this->Inventory->getCountPOReceivedAndPOItem($postData['inventory_po_id']);
                    
                    $po_status = '1';
                    if($countdet['invporeccount'] == $countdet['invpoitmcount']){
                        $po_status = '4';
                    }

                    $inventorypurchaseorders = $this->InventoryPurchaseOrders->newEmptyEntity();
                    $inventorypurchaseorders = $this->InventoryPurchaseOrders->get($postData['inventory_po_id']);
                    $invpodata = [];
                    $invpodata['po_status'] = $po_status;
                    $invpodata['updated_by'] = $authUserData['id'];
                    $inventorypurchaseorders = $this->InventoryPurchaseOrders->patchEntity($inventorypurchaseorders, $invpodata);

                    $this->InventoryPurchaseOrders->save($inventorypurchaseorders);
                    
                    $invreceived = array('received'=>$postData['received']);
                    $result = array('status'=>'success', 'message'=>"Saved successfully.", 'invreceived'=>$invreceived);
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                
                echo json_encode($result);die;
            }
        }

        public function receive($inventory_po_id, $po_item_id=null){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Purchase Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Purchase Orders'];
                }
            }
            
            $inventorypurchaseorders = $this->InventoryPurchaseOrders->newEmptyEntity();
            $inventorypurchaseorders = $this->InventoryPurchaseOrders->find('all')->where(['id'=>$inventory_po_id])->select()->first($this->InventoryPurchaseOrders);
            if(empty($inventorypurchaseorders)){
                return $this->redirect(['action' => 'index']);
            }
            
            $wherecond = ['InventoryPOItems.inventory_po_id'=>$inventory_po_id];
            if(!empty($po_item_id)){
                $wherecond['InventoryPOItems.id'] = $po_item_id;
            }
            $inventorypoitems = $this->InventoryPOItems->find('all')->where($wherecond)->select($this->InventoryPOItems)->select(['invitms.name', 'invitms.part_number', 'invitms.is_this_item_serialized'])
            ->join([
                'invitms' => [
                    'table' => 'inventory_items',
                    'type' => 'LEFT',
                    'conditions' => 'invitms.id = InventoryPOItems.inventory_item_id',
                ]
            ]);

            $inventoryitems = [];
            $poitemidarr = [];
            foreach($inventorypoitems as $val){
                $poitemidarr[] = $val['id'];
                //if(empty($po_item_id) || $po_item_id == $val['id']){
                    $inventoryitems[] = $val;
                //}
            }
            
            $connection = ConnectionManager::get('default');
            $results = $connection
            ->execute(
                'select * from inventory_po_receives WHERE inventory_po_id = :inventory_po_id and inventory_po_item_id in('."'" . implode ( "', '", $poitemidarr ) . "'".') and inventory_id != 0',
                ['inventory_po_id' => $inventory_po_id],
                ['created' => 'datetime']
            );

            $inventorypoitemsrec = [];
            foreach($results as $invrecval){
                if(isset($inventorypoitemsrec[$invrecval['inventory_po_item_id']])){
                    $inventorypoitemsrec[$invrecval['inventory_po_item_id']] += $invrecval['received']; 
                }else{
                    $inventorypoitemsrec[$invrecval['inventory_po_item_id']] = $invrecval['received'];
                }
            }
            
            if ($this->request->is(['patch', 'post', 'put'])) {
                
                $postData = $this->request->getData();//echo "<pre>";print_r($postData);exit;
                
                for($i=0; $i<count($postData['inventory_item_id']); $i++){
                    $invenotries = $this->Inventories->newEmptyEntity();
                    $inventoryitems = $this->InventoryItems->get($postData['inventory_item_id'][$i]);
                    
                    $inventorydata = [];
                    $inventorydata['inventory_item_id'] = $postData['inventory_item_id'][$i];
                    $inventorydata['capital_equipment'] = $postData['capital_equipment'][$i];
                    $inventorydata['serial_no']         = $postData['serial_no'][$i];
                    $inventorydata['conditions']        = $postData['conditions'][$i];
                    $inventorydata['location_id']       = $postData['location_id'][$i];
                    $inventorydata['received']          = date("Y-m-d");
                    $inventorydata['notes']             = $postData['notes'][$i];
                    $inventorydata['uom']               = $inventoryitems['default_uom'];
                    $inventorydata['currency']          = $inventoryitems['currency'];
                    $inventorydata['cost']              = $postData['cost'][$i];
                    $inventorydata['account_code']      = $postData['account_code'][$i];
                    $inventorydata['months_new']        = isset($postData['months_new'][$i]) ? $postData['months_new'][$i] : '0';
                    $inventorydata['months_overhaul']   = isset($postData['months_overhaul'][$i]) ? $postData['months_overhaul'][$i] : '0';
                    $inventorydata['months_repair']     = isset($postData['months_repair'][$i]) ? $postData['months_repair'][$i] : '0';
                    $inventorydata['hours_new']         = isset($postData['hours_new'][$i]) ? $postData['hours_new'][$i] : '0';
                    $inventorydata['hours_overhaul']    = isset($postData['hours_overhaul'][$i]) ? $postData['hours_overhaul'][$i] : '0';
                    $inventorydata['hours_repair']      = isset($postData['hours_repair'][$i]) ? $postData['hours_repair'][$i] : '0';
                    $inventorydata['landings_new']      = isset($postData['landings_new'][$i]) ? $postData['landings_new'][$i] : '0';
                    $inventorydata['landings_overhaul'] = isset($postData['landings_overhaul'][$i]) ? $postData['landings_overhaul'][$i] : '0';
                    $inventorydata['landings_repair']   = isset($postData['landings_repair'][$i]) ? $postData['landings_repair'][$i] : '0';
                    $inventorydata['cycles_new']        = isset($postData['cycles_new'][$i]) ? $postData['cycles_new'][$i] : '0';
                    $inventorydata['cycles_overhaul']   = isset($postData['cycles_overhaul'][$i]) ? $postData['cycles_overhaul'][$i] : '0';
                    $inventorydata['cycles_repair']     = isset($postData['cycles_repair'][$i]) ? $postData['cycles_repair'][$i] : '0';
                    $inventorydata['added_by']          = $authUserData['id'];
                    $inventorydata['qty']               = isset($postData['qty'][$i]) ? $postData['qty'][$i] : 1;

                    $invenotries = $this->Inventories->patchEntity($invenotries, $inventorydata);//print_r($invenotries);exit;
                    if ($this->Inventories->save($invenotries)) {
                        $id = $invenotries->id;

                        $counts = $i+1;
                        if(isset($postData['filenames-'.$counts][$i])){
                            $fileattarr = [];
                            $fileattarr['filenames'][$i] = $postData['filenames-'.$counts][$i];
                            $fileattarr['filesize'][$i] = $postData['filesize-'.$counts][$i];
                            $fileattarr['files'][$i] = $postData['files-'.$counts][$i];

                            $this->InventoryAttachment->saveAttachment($id, $fileattarr);
                        }

                        $invenotryporeceives = $this->InventoryPOReceives->newEmptyEntity();
                        $inventoryporecdata = [];

                        $inventoryporecdata['inventory_po_id'] = $postData['inventory_po_id'][$i];
                        $inventoryporecdata['inventory_po_item_id'] = $postData['inventory_po_item_id'][$i];
                        $inventoryporecdata['received'] = 1;
                        $inventoryporecdata['inventory_id'] = $id;
                        $inventoryporecdata['added_by'] = $authUserData['id'];

                        $invenotryporeceives = $this->InventoryPOReceives->patchEntity($invenotryporeceives, $inventoryporecdata);
                        
                        $this->InventoryPOReceives->save($invenotryporeceives);

                        //transaction history data save
                        $inventorypurchaseorders = $this->InventoryPurchaseOrders->get($postData['inventory_po_id'][$i]);
                        $inventoryitems = $this->InventoryItems->get($postData['inventory_item_id'][$i]);
                        $locationarr = $this->InventoryLocations->get($postData['location_id'][$i]);

                        $from_description = 'Received from Purchase Order '.$inventorypurchaseorders->po_number;
                        $to_description = isset($locationarr->location_name) ? $locationarr->location_name : '';
                        $type = 'Receive';
                        $itemtype_from = $inventoryitems->item_type;
                        $itemtype_to = $inventoryitems->item_type;

                        $otherparamaterarr = array(
                                                    'from_description'=>$from_description,
                                                    'to_description'=>$to_description,
                                                    'type'=>$type,
                                                    'qty'=>$invenotries->qty,
                                                    'itemtype_from'=>$itemtype_from,
                                                    'itemtype_to'=>$itemtype_to,
                                                    'to_status'=>$invenotries->status,
                                                    'puchase_order_id'=>$postData['inventory_po_id'][$i]
                                                );
                        $this->Inventory->saveInventoryTransactionHistory($invenotries, $otherparamaterarr);
                    }
                }

                $countdet = $this->Inventory->getCountPOReceivedAndPOItem($postData['inventory_po_id'][0]);
                
                //update po status
                $po_status = '1';
                if($countdet['invporeccount'] == $countdet['invpoitmcount']){
                    $po_status = '4';
                }

                $inventorypurchaseorders = $this->InventoryPurchaseOrders->newEmptyEntity();
                $inventorypurchaseorders = $this->InventoryPurchaseOrders->get($postData['inventory_po_id'][0]);
                $invpodata = [];
                $invpodata['po_status'] = $po_status;
                $invpodata['updated_by'] = $authUserData['id'];
                $inventorypurchaseorders = $this->InventoryPurchaseOrders->patchEntity($inventorypurchaseorders, $invpodata);

                $this->InventoryPurchaseOrders->save($inventorypurchaseorders);

                $this->Flash->success(__('The inventory has been saved.'));
                return $this->redirect(['action' => 'detail', $inventory_po_id]);

                //for test error while saving data to database
                /*$x = $invenotries->errors();
                if ($x) {
                    debug($invenotries);
                    debug($x);
                    return false;
                }*/
                
                //$this->Flash->error(__('The inventory could not be saved. Please, try again.'));
            }

            $this->set(compact('actionItems', 'inventorypurchaseorders', 'inventoryitems', 'inventorypoitemsrec'));
        }

        public function inventoryPOReceivedBlock(){
            
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $inventory_po_id = $postData['inventory_po_id'];
                $po_item_id = $postData['po_item_id'];
                $quantity = $postData['quantity'];

                $inventorypoitems = $this->InventoryPOItems->find('all')->where(['InventoryPOItems.id'=>$po_item_id, 'InventoryPOItems.inventory_po_id'=>$inventory_po_id])->select($this->InventoryPOItems)->select(['invitms.name', 'invitms.part_number', 'invitms.is_this_item_serialized'])
                ->join([
                    'invitms' => [
                        'table' => 'inventory_items',
                        'type' => 'LEFT',
                        'conditions' => 'invitms.id = InventoryPOItems.inventory_item_id',
                    ]
                ]);

                $inventoryitems = [];
                foreach($inventorypoitems as $val){
                    $inventoryitems[] = $val;
                }

                $location = $this->Inventory->getAllLocations();
                $inventoryitemsarr = $this->InventoryItems->find('all');
                $invitemdropdown = [];
                foreach($inventoryitemsarr as $val){
                    $invitemdropdown[$val['id']] = $val['name'].' (PN: '.$val['part_number'].')';
                }
                
                $this->set('inventoryitems', $inventoryitems);
                $this->set('quantity', $quantity);
                $this->set('invitemdropdown', $invitemdropdown);
                $this->set('location', $location);
                
                $this->viewBuilder()->setLayout('ajax');
                if(count($inventoryitems) == 1 && (isset($inventoryitems[0]['invitms']['is_this_item_serialized']) && $inventoryitems[0]['invitms']['is_this_item_serialized'] == '0')){
                    $this->set('start', '1');
                    $this->render("/element/Inventory/po_receive_nonserialized_item");
                }else{
                    $this->set('start', $postData['start']);
                    $this->render("/element/Inventory/po_receive_add");
                }
            }
        }

        public function bulkInvPORecAttachmentUpload(){
            $postData = $this->request->getData();
            if(!empty($postData['ids'])) 
            {
                
                $isvalidfile = 1;
                $arr_ext = array('pdf','txt');
                $fldname = $postData['ids'];

                $attachment = $postData[$fldname]; 
                $name = $attachment->getClientFilename();
                $type = $attachment->getClientMediaType();
                $size = $attachment->getSize();
                $temp = $attachment->getStream()->getMetadata('uri');

                //$temp = $postData[$fldname]['tmp_name'];
                //$name = $postData[$fldname]['name'];
                $ext = substr(strrchr($name , '.'), 1);
                
                /*if (!in_array($ext, $arr_ext)) {
                    $isvalidfile = 0;
                }*/
                
                if($isvalidfile){
                    $foldername = 'inventory';
                    $filelocation = WWW_ROOT . $foldername.'/' . $name;
                    $tblrow = $this->InventoryAttachment->uploadInvPORecFilesToServer($postData, $filelocation, $foldername);
                    
                    if($tblrow != ''){
                        $result = array('status'=>'success', 'message'=>"Saved successfully.", 'tblrow'=>$tblrow);
                    } else {
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                } else {
                    $result = array('status'=>'failed', 'message'=>'Please upload correct format.');
                }
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                echo json_encode($result);die;
            }
        }

        public function getInvAjustCostData(){
            
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $inventory_po_item_id = $postData['inventory_po_item_id'];

                $inventoryporeceives = $this->InventoryPOReceives->find('all')->where(['InventoryPOReceives.inventory_po_item_id'=>$inventory_po_item_id])->select(['invitms.name', 'invitms.part_number', 'inv.qty', 'invloc.location_name', 'inv.status', 'inv.currency', 'inv.id', 'inv.cost', 'inv.serial_no', 'invpoitms.cost'])
                ->join([
                    'invpoitms' => [
                        'table' => 'inventory_po_items',
                        'type' => 'LEFT',
                        'conditions' => 'invpoitms.id = InventoryPOReceives.inventory_po_item_id',
                    ],
                    'invitms' => [
                        'table' => 'inventory_items',
                        'type' => 'LEFT',
                        'conditions' => 'invitms.id = invpoitms.inventory_item_id',
                    ],
                    'inv' => [
                        'table' => 'inventories',
                        'type' => 'LEFT',
                        'conditions' => 'inv.id = InventoryPOReceives.inventory_id',
                    ],
                    'invloc' => [
                        'table' => 'inventory_locations',
                        'type' => 'LEFT',
                        'conditions' => 'invloc.id = inv.location_id',
                    ]
                ]);

                $invpodata = [];
                $invitmcost = 0;
                foreach($inventoryporeceives as $val){
                    $invpodata[] = $val;
                    $invitmcost = $val['invpoitms']['cost'];
                }

                $this->set(compact('invpodata', 'invitmcost'));

                $this->viewBuilder()->setLayout('ajax');
                $this->render("/element/Inventory/adjust_inventory_cost");
            }
        }

        public function inventoryExistOrderDropDown(){
            
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                
                
                $inventoryorderdropdown = '
                <div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:15px; padding-right:15px;"><div class="input-select"><select name="link_order_id" class="form-control col-md-12 col-xs-12 selectpicker" id="link_order_id" aria-label="Default select example">';

                $inventoryorderdropdown .= '<option value="" selected>Select a '.$postData['order_type'].' order ...</option>';
                if($postData['order_type'] == 'repair'){
                    $inventorypolist = $this->InventoryRepairOrders->find('all')->where(['InventoryRepairOrders.status'=>'1', 'InventoryRepairOrders.id !='=>$postData['inventory_po_id']])->select(['InventoryRepairOrders.id', 'InventoryRepairOrders.ro_number', 'vendor.name'])
                    ->join([
                        'vendor' => [
                            'table' => 'inventory_vendors',
                            'type' => 'LEFT',
                            'conditions' => 'vendor.id = InventoryRepairOrders.vendor',
                        ]
                    ]);
                    foreach($inventorypolist as $val){
                        $opttext = $val->ro_number;
                        $opttext .= !empty($val['vendor']['name']) ? ' '.$val['vendor']['name'] : '';

                        $inventoryorderdropdown .= '<option value="'.$val->id.'">'.$opttext.'</option>';
                    }
                }else if($postData['order_type'] == 'shipping'){
                    $inventorypolist = $this->InventoryShippingOrders->find('all')->where(['InventoryShippingOrders.status'=>'1', 'InventoryShippingOrders.id !='=>$postData['inventory_po_id']])->select(['InventoryShippingOrders.id', 'InventoryShippingOrders.shipping_order_number', 'vendor.name'])
                    ->join([
                        'vendor' => [
                            'table' => 'inventory_vendors',
                            'type' => 'LEFT',
                            'conditions' => 'vendor.id = InventoryShippingOrders.vendor',
                        ]
                    ]);
                    foreach($inventorypolist as $val){
                        $opttext = $val->shipping_order_number;
                        $opttext .= !empty($val['vendor']['name']) ? ' '.$val['vendor']['name'] : '';

                        $inventoryorderdropdown .= '<option value="'.$val->id.'">'.$opttext.'</option>';
                    }
                }else if($postData['order_type'] == 'request'){
                    $inventorypolist = $this->InventoryRequests->find('all')->where(['status'=>'1', 'id !='=>$postData['inventory_po_id']])->select(['id', 'request_number', 'title']);
                    foreach($inventorypolist as $val){
                        $inventoryorderdropdown .= '<option value="'.$val->id.'">'.($val->request_number.' '.$val->title).'</option>';
                    }
                }else{
                    $inventorypolist = $this->InventoryPurchaseOrders->find('all')->where(['InventoryPurchaseOrders.status'=>'1', 'InventoryPurchaseOrders.id !='=>$postData['inventory_po_id']])->select(['InventoryPurchaseOrders.id', 'InventoryPurchaseOrders.po_number', 'InventoryPurchaseOrders.reference', 'vendor.name'])
                    ->join([
                        'vendor' => [
                            'table' => 'inventory_vendors',
                            'type' => 'LEFT',
                            'conditions' => 'vendor.id = InventoryPurchaseOrders.vendor',
                        ]
                    ]);
                    foreach($inventorypolist as $val){
                        $opttext = $val->po_number;
                        $opttext .= !empty($val['vendor']['name']) ? ' '.$val['vendor']['name'] : '';
                        $opttext .= !empty($val['reference']) ? ' '.$val['reference'] : '';

                        $inventoryorderdropdown .= '<option value="'.$val->id.'">'.$opttext.'</option>';
                    }
                }
                $inventoryorderdropdown .= '</select></div>';

                echo $inventoryorderdropdown;exit;
            }
        }

        public function existingOrderLinkSave(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Purchase Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Purchase Orders'];
                }
            }
            if ($this->request->is('post')) {
                $postData = $this->request->getData();

                if($postData['order_type'] == 'purchase'){
                    
                    $invlinkedorders = $this->InventoryPurchaseOrderLinks->find('all')->where(['link_type'=>'1','OR' => [
                        ['InventoryPurchaseOrderLinks.purchase_order_id' => $postData['inventory_po_id'],
                        'InventoryPurchaseOrderLinks.parent_purchase_order_id' => $postData['linked_order_id']],
                        ['InventoryPurchaseOrderLinks.purchase_order_id' => $postData['linked_order_id'],
                        'InventoryPurchaseOrderLinks.parent_purchase_order_id' => $postData['inventory_po_id']]
                    ]])->select($this->InventoryPurchaseOrderLinks);
                    $invlinkedorderscount = $invlinkedorders->count();
                    if(empty($invlinkedorderscount)){
                        $invlinkedorders = $this->InventoryPurchaseOrderLinks->newEmptyEntity();
                        $linkorderarr = [];
                        $linkorderarr['link_type'] = '1';
                        $linkorderarr['parent_link_type'] = $postData['parent_link_type'];
                        $linkorderarr['parent_purchase_order_id'] = $postData['inventory_po_id'];
                        $linkorderarr['purchase_order_id'] = $postData['linked_order_id'];
                        $linkorderarr['added_by'] = $authUserData['id'];
                        $invlinkedorders = $this->InventoryPurchaseOrderLinks->patchEntity($invlinkedorders, $linkorderarr);
                        if($this->InventoryPurchaseOrderLinks->save($invlinkedorders)){
                            $this->Flash->success(__('Purchase order link created successfully.'));
                        }else{
                            $this->Flash->error(__('Purchase order link could not created. Please, try again.'));
                        }
                    }else{
                        $this->Flash->error(__('Purchase order link already exist.'));
                    }
                }else if($postData['order_type'] == 'repair'){
                    $invlinkedorders = $this->InventoryPurchaseOrderLinks->find('all')->where(['link_type'=>'2','InventoryPurchaseOrderLinks.repair_order_id' => $postData['linked_order_id'],'InventoryPurchaseOrderLinks.parent_purchase_order_id' => $postData['inventory_po_id']])->select($this->InventoryPurchaseOrderLinks);//echo $postData['linked_order_id'].'--'.$postData['inventory_po_id'];exit;
                    $invlinkedorderscount = $invlinkedorders->count();
                    if(empty($invlinkedorderscount)){
                        $invlinkedorders = $this->InventoryPurchaseOrderLinks->newEmptyEntity();
                        $linkorderarr = [];
                        $linkorderarr['link_type'] = '2';
                        $linkorderarr['parent_link_type'] = $postData['parent_link_type'];
                        $linkorderarr['parent_purchase_order_id'] = $postData['inventory_po_id'];
                        $linkorderarr['repair_order_id'] = $postData['linked_order_id'];
                        $linkorderarr['added_by'] = $authUserData['id'];
                        $invlinkedorders = $this->InventoryPurchaseOrderLinks->patchEntity($invlinkedorders, $linkorderarr);
                        if($this->InventoryPurchaseOrderLinks->save($invlinkedorders)){
                            $this->Flash->success(__('Repair order link was created successfully.'));
                        }else{
                            $this->Flash->error(__('Repair order link could not created. Please, try again.'));
                        }
                    }else{
                        $this->Flash->error(__('Repair order link already exist.'));
                    }
                }else if($postData['order_type'] == 'shipping'){
                    $invlinkedorders = $this->InventoryPurchaseOrderLinks->find('all')->where(['link_type'=>'3','InventoryPurchaseOrderLinks.shipping_order_id' => $postData['linked_order_id'],'InventoryPurchaseOrderLinks.parent_purchase_order_id' => $postData['inventory_po_id']])->select($this->InventoryPurchaseOrderLinks);
                    $invlinkedorderscount = $invlinkedorders->count();
                    if(empty($invlinkedorderscount)){
                        $invlinkedorders = $this->InventoryPurchaseOrderLinks->newEmptyEntity();
                        $linkorderarr = [];
                        $linkorderarr['link_type'] = '3';
                        $linkorderarr['parent_link_type'] = $postData['parent_link_type'];
                        $linkorderarr['parent_purchase_order_id'] = $postData['inventory_po_id'];
                        $linkorderarr['shipping_order_id'] = $postData['linked_order_id'];
                        $linkorderarr['added_by'] = $authUserData['id'];
                        $invlinkedorders = $this->InventoryPurchaseOrderLinks->patchEntity($invlinkedorders, $linkorderarr);
                        if($this->InventoryPurchaseOrderLinks->save($invlinkedorders)){
                            $this->Flash->success(__('Shipping order link created successfully.'));
                        }else{
                            $this->Flash->error(__('Shipping order link could not created. Please, try again.'));
                        }
                    }else{
                        $this->Flash->error(__('Shipping order link already exist.'));
                    }
                }else if($postData['order_type'] == 'request'){
                    $invlinkedorders = $this->InventoryPurchaseOrderLinks->find('all')->where(['link_type'=>'4','InventoryPurchaseOrderLinks.request_id' => $postData['linked_order_id'],'InventoryPurchaseOrderLinks.parent_purchase_order_id' => $postData['inventory_po_id']])->select($this->InventoryPurchaseOrderLinks);
                    $invlinkedorderscount = $invlinkedorders->count();
                    if(empty($invlinkedorderscount)){
                        $invlinkedorders = $this->InventoryPurchaseOrderLinks->newEmptyEntity();
                        $linkorderarr = [];
                        $linkorderarr['link_type'] = '4';
                        $linkorderarr['parent_link_type'] = $postData['parent_link_type'];
                        $linkorderarr['parent_purchase_order_id'] = $postData['inventory_po_id'];
                        $linkorderarr['request_id'] = $postData['linked_order_id'];
                        $linkorderarr['added_by'] = $authUserData['id'];
                        $invlinkedorders = $this->InventoryPurchaseOrderLinks->patchEntity($invlinkedorders, $linkorderarr);
                        if($this->InventoryPurchaseOrderLinks->save($invlinkedorders)){
                            $this->Flash->success(__('Request link created successfully.'));
                        }else{
                            $this->Flash->error(__('Request link could not created. Please, try again.'));
                        }
                    }else{
                        $this->Flash->error(__('Request link already exist.'));
                    }
                }
                if($postData['parent_link_type'] == '1'){
                    return $this->redirect(['action' => 'detail', $postData['inventory_po_id']]);
                }else if($postData['parent_link_type'] == '2'){
                    return $this->redirect(['controller'=>'InventoryRepairOrders', 'action' => 'detail', $postData['inventory_po_id']]);
                }else if($postData['parent_link_type'] == '3'){
                    return $this->redirect(['controller'=>'InventoryShippingOrders', 'action' => 'detail', $postData['inventory_po_id']]);
                }else if($postData['parent_link_type'] == '4'){
                    return $this->redirect(['controller'=>'InventoryRequests', 'action' => 'detail', $postData['inventory_po_id']]);
                }
                
            }
        }

        public function updatePOExchangeStatus(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Purchase Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Purchase Orders'];
                }
            }
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                $id = $postData['inventory_po_id'];
                $invenotrypurchaseorders = $this->InventoryPurchaseOrders->get($id);
            
                if(!empty($invenotrypurchaseorders)){
                    if($invenotrypurchaseorders->exchange_status != $postData['exchange_status']){
                        $postData['updated_by'] = $authUserData['id'];
                        $invenotrypurchaseorders = $this->InventoryPurchaseOrders->patchEntity($invenotrypurchaseorders, $postData);
                        if ($this->InventoryPurchaseOrders->save($invenotrypurchaseorders)) {
                            $this->Flash->success(__('Core exchange status successfully updated.'));
                        } else {
                            $this->Flash->error(__('Something went wrong'));
                        }
                    }else{
                        $this->Flash->error(__('Exchange Order is already in this status. Nothing to change.'));
                    }
                }else{
                    $this->Flash->error(__('Something went wrong'));
                }

                return $this->redirect(['action' => 'detail', $id]);
            }
        }

        public function exportPurchaseLineItems() {
            $dataTable = '';
            $dataTable .='<table class="table">
                            <thead>
                                <tr>
                                    <th>PO Number</th>
                                    <th>Type</th> 
                                    <th>Div. Abbr.</th> 
                                    <th>Division Name</th> 
                                    <th>Status</th> 
                                    <th>Vendor</th>
                                    <th>Requestor</th>
                                    <th>PO Date</th> 
                                    <th>Reference</th> 
                                    <th>Sales Person</th> 
                                    <th>Tax Amount</th> 
                                    <th>Shipping Amount</th> 
                                    <th>Subtotal</th>
                                    <th>Total Cost</th> 
                                    <th>Ship To</th> 
                                    <th>Bill To</th> 
                                    <th>Shipment Method</th> 
                                    <th>Account Code</th> 
                                    <th>PO Created</th>
                                    <th>PO Last Changed</th> 
                                    <th>Line Item Num.</th> 
                                    <th>Part Number</th> 
                                    <th>Line Item Description</th> 
                                    <th>Quantity</th> 
                                    <th>Line Item Status</th> 
                                    <th>UOM</th> 
                                    <th>Unit Price</th> 
                                    <th>ETA</th> 
                                    <th>Line Item Created</th> 
                                    <th>Line Item Last Changed</th> 
                                    <th>Currency</th> 
                                    <th>Is Active</th> 
                                    <th>Line Item ID</th> 
                                    <th>Request Number</th> 
                                </tr>
                            </thead>
                        <tbody>';
            
            $wherecond = '';
            $queryData = $this->getRequest()->getQuery();

            if(isset($queryData['inv_item_id']) && !empty($queryData['inv_item_id'])){
                $wherecond = " and invpoitm.inventory_item_id = '".$queryData['inv_item_id']."'";
            }
            
            $query = "SELECT invpo.po_type, invpoitm.id, invpoitm.inventory_item_id, invpoitm.noninventory_item, invpoitm.qty, invpoitm.cost, invpoitm.eta, invpoitm.created as inventory_po_items_created, invpoitm.modified as inventory_po_items_modified, invpoitm.status, invpoitm.id, invpo.po_number, invpo.po_date, invpo.reference, invpo.requestor, invpo.account_code, invpo.po_tax, invpo.po_tax_amount, invpo.po_shipping, invpo.currency, invpo.po_cost_subtotal, invpo.po_cost_total, invpo.ship_via, invpo.created, invpo.modified, invpo.po_status, invitms.name as inventory_item_name, invitms.part_number, invitms.added_by, invitms.updated_by, invpoitm.uom, invpovendor.name as vendor_name, invbilladdr.name as bill_to_address, invshippingaddr.name as ship_to_address, invporeceive.received, invrequest.request_number, invpo.sales_person FROM `inventory_purchase_orders` as invpo join inventory_po_items invpoitm on invpo.id = invpoitm.inventory_po_id left join inventory_items as invitms on invitms.id = invpoitm.inventory_item_id left join inventory_vendors as invpovendor on invpo.vendor = invpovendor.id left join inventory_addresses as invbilladdr on invbilladdr.id = invpo.bill_to_address left join inventory_addresses as invshippingaddr on invshippingaddr.id = invpo.ship_to_address left join inventory_po_receives as invporeceive on invporeceive.inventory_po_item_id = invpoitm.id left join inventory_requests as invrequest on invrequest.id = invpo.request WHERE 1=1$wherecond";

            $requestData = $this->getRequest()->getQuery();//echo "<pre>";print_r($requestData);exit;
            $cond = $this->InventoryFilter->inventoryPurchaseOrderFilter($requestData);
            
            $columns = array(
                            0 => 'invpo.po_number',
                            1 => 'invpo.reference',
                            2 => 'v.vendor_name',
                            3 => 'invpo.requestor',
                            4 => 'invpo.account_code',
                            5 => 'invpo.po_status',
                            6 => 'po_cost_total',
                            7 => 'invpo.created',
                        );
            
            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sort = 'asc';
            
            $SQL = $query.$cond." ORDER BY $sidx $sort";//echo $SQL;exit;
            $inventorypoitems = $conn->execute( $SQL )->fetchAll('assoc');
            
            $setData = '';  
            $inventoryPurchaseType = unserialize(INVENTORY_PURCHASE_TYPE);
            $invPurchaseOrderStatus = unserialize(INVENTORY_PURCHASE_STATUS);
            $shipViaArr = unserialize(SHIP_VIA);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $currency = unserialize(CURRENCY);

            $ponumberarr = [];
            foreach($inventorypoitems as $invpoitems){ //echo "<pre>";print_r($invpoitems);exit;
                $po_type = !empty($invpoitems['po_type']) ? $inventoryPurchaseType[$invpoitems['po_type']] : '';
                
                $shipvia = !empty($invpoitems['ship_via']) ? $shipViaArr[$invpoitems['ship_via']] : '';
                $lineitemnum = 1;
                if(in_array($invpoitems['po_number'], $ponumberarr)){
                    $lineitemnum ++;
                }else{
                    $ponumberarr[] = $invpoitems['po_number'];
                }

                $poreceivestatus = '';
                if($invpoitems['po_status'] == '3'){
                    $poreceivestatus = 'Canceled';
                }if(!empty($invpoitems['invporeceive']['received'])){
                    if(!empty($invpoitems['inventory_item_id'])){
                        $poreceivestatus = 'Received';
                    }else{
                        $poreceivestatus = 'Closed';
                    }
                }else{
                    $poreceivestatus = 'Pending Shipment';
                }

                $dataTable .='
                            <tr>
                                <td>'.$invpoitems['po_number'].'</td>
                                <td>'.$po_type.'</td>
                                <td></td>
                                <td></td>
                                <td>'.$invPurchaseOrderStatus[$invpoitems['po_status']].'</td>
                                <td>'.$invpoitems['vendor_name'].'</td>
                                <td>'.$invpoitems['requestor'].'</td>
                                <td>'.date('m/d/Y', strtotime($invpoitems['po_date'])).'</td>
                                <td>'.$invpoitems['reference'].'</td>
                                <td>'.$invpoitems['sales_person'].'</td>
                                <td>'.$invpoitems['po_tax_amount'].'</td>
                                <td>'.$invpoitems['po_shipping'].'</td>
                                <td>'.$invpoitems['po_cost_subtotal'].'</td>
                                <td>'.$invpoitems['po_cost_total'].'</td>
                                <td>'.$invpoitems['ship_to_address'].'</td>
                                <td>'.$invpoitems['bill_to_address'].'</td>
                                <td>'.$shipvia.'</td>
                                <td>'.$invpoitems['account_code'].'</td>
                                <td>'.date('m/d/Y', strtotime($invpoitems['created'])).'</td>
                                <td>'.(!empty($invpoitems['modified']) ? date('m/d/Y', strtotime($invpoitems['modified'])) : '').'</td>
                                <td>'.$lineitemnum.'</td>
                                <td>'.$invpoitems['part_number'].'</td>
                                <td>'.(!empty($invpoitems['inventory_item_id']) ? $invpoitems['inventory_item_name'].'(PN: '.$invpoitems['part_number'].')' : $invpoitems['noninventory_item']).'</td>
                                <td>'.$invpoitems['qty'].'</td>
                                <td>'.$poreceivestatus.'</td>
                                <td>'.$defaultUOM[$invpoitems['uom']].'</td>
                                <td>'.$invpoitems['cost'].'</td>
                                <td>'.$invpoitems['eta'].'</td>
                                <td>'.date('m/d/Y', strtotime($invpoitems['inventory_po_items_created'])).'</td>
                                <td>'.(!empty($invpoitems['inventory_po_items_modified']) ? date('m/d/Y', strtotime($invpoitems['inventory_po_items_modified'])) : '').'</td>
                                <td>'.$currency[$invpoitems['currency']].'</td>
                                <td>'.$invpoitems['status'].'</td>
                                <td>'.$invpoitems['id'].'</td>
                                <td>'.$invpoitems['request_number'].'</td>
                            </tr>';
            }  
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=InventoryPurchaseOrderWithLineItems".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }

        public function exportPurchaseListToExcel() {
            $dataTable = '';
            $dataTable .='<table class="table">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Type</th>
                                    <th>Div. Abbr.</th>
                                    <th>Division Name</th>
                                    <th>Reference</th> 
                                    <th>Vendor</th>
                                    <th>Requestor</th>
                                    <th>Status</th>
                                    <th>Account Code</th> 
                                    <th>Cost</th> 
                                    <th>Currency Code</th> 
                                    <th>Submitted</th>
                                    <th>Is Active</th>
                                </tr>
                            </thead>
                        <tbody>';

            $query = "SELECT invpo.*, invpovendor.name as vendor_name FROM `inventory_purchase_orders` as invpo left join inventory_vendors as invpovendor on invpo.vendor = invpovendor.id WHERE 1=1";

            $requestData = $this->getRequest()->getQuery();//echo "<pre>";print_r($requestData);exit;
            $cond = $this->InventoryFilter->inventoryPurchaseOrderFilter($requestData);
            
            $columns = array(
                            0 => 'invpo.po_number',
                            1 => 'invpo.reference',
                            2 => 'v.vendor_name',
                            3 => 'invpo.requestor',
                            4 => 'invpo.account_code',
                            5 => 'invpo.po_status',
                            6 => 'po_cost_total',
                            7 => 'invpo.created',
                        );
            
            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sort = 'asc';
            
            if(isset($requestData['inv_item_id']) && !empty($requestData['inv_item_id'])){
                $query .= " and invpo.id in(select distinct inventory_po_id from inventory_po_items where inventory_item_id = '".$requestData['inv_item_id']."')";
            }
            $SQL = $query.$cond." ORDER BY $sidx $sort";//echo $SQL;exit;
            $inventorypoitems = $conn->execute( $SQL )->fetchAll('assoc');
            
            $setData = '';  
            $inventoryPurchaseType = unserialize(INVENTORY_PURCHASE_TYPE);
            $invPurchaseOrderStatus = unserialize(INVENTORY_PURCHASE_STATUS);
            $shipViaArr = unserialize(SHIP_VIA);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $currency = unserialize(CURRENCY);

            $ponumberarr = [];
            foreach($inventorypoitems as $invpoitems){ //echo "<pre>";print_r($invpoitems);exit;
                $po_type = !empty($invpoitems['po_type']) ? $inventoryPurchaseType[$invpoitems['po_type']] : '';
                
                /*$poreceivestatus = '';
                if($invpoitems['po_status'] == '1'){
                    $poreceivestatus = 'Partial';
                }else if($invpoitems['po_status'] == '0'){
                    $poreceivestatus = 'Draft';
                }else if($invpoitems['po_status'] == '3'){
                    $poreceivestatus = 'Canceled';
                }else if($invpoitems['po_status'] == '5'){
                    $poreceivestatus = 'PO Created';
                }else if($invpoitems['po_status'] == '2'){
                    $poreceivestatus = 'Sent';
                }else if($invpoitems['po_status'] == '4'){
                    $poreceivestatus = 'Close';
                }*/

                $dataTable .='
                            <tr>
                                <td>'.$invpoitems['po_number'].'</td>
                                <td>'.$po_type.'</td>
                                <td></td>
                                <td></td>
                                <td>'.$invpoitems['reference'].'</td>
                                <td>'.$invpoitems['vendor_name'].'</td>
                                <td>'.$invpoitems['requestor'].'</td>
                                <td>'.$invPurchaseOrderStatus[$invpoitems['po_status']].'</td>
                                <td>'.$invpoitems['account_code'].'</td>
                                <td>'.$invpoitems['po_cost_total'].'</td>
                                <td>'.(!empty($invpoitems['currency']) ? $currency[$invpoitems['currency']] : '').'</td>
                                <td>'.date('m/d/Y', strtotime($invpoitems['created'])).'</td>
                                <td>'.$invpoitems['status'].'</td>
                            </tr>';
            }  
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=InventoryPurchaseOrder".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }

        public function unlinkExistingOrder(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Purchase Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Purchase Orders'];
                }
            }
            $postData = $this->request->getData();
            if(!empty($postData['purchaseorderids']) || !empty($postData['requestids']) || !empty($postData['shippingorderids']) || !empty($postData['repairorderids'])){
                $this->request->allowMethod(['post', 'delete']);
                try {
                    $whereORCond = [];
                    if(!empty($postData['purchaseorderids'])){
                        $whereORCond['purchase_order_id IN'] = $postData['purchaseorderids'];
                    }
                    if(!empty($postData['requestids'])){
                        $whereORCond['request_id IN'] = $postData['requestids'];
                    }
                    if(!empty($postData['shippingorderids'])){
                        $whereORCond['shipping_order_id IN'] = $postData['shippingorderids'];
                    }
                    if(!empty($postData['repairorderids'])){
                        $whereORCond['repair_order_id IN'] = $postData['repairorderids'];
                    }
                    if ($this->InventoryPurchaseOrderLinks->deleteAll(['OR' => $whereORCond, 'parent_purchase_order_id'=>$postData['parent_purchase_ordre_id'], 'parent_link_type'=>$postData['parent_link_type']])){
                        $this->Flash->success(__('Unlink order was successfully done'));
                    } else {
                        $this->Flash->error(__('Unlink order was not done. Please, try again.'));
                    }
                } catch(\PDOException $e) {
                    $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
                } catch (\Exception $e) {
                    $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
                }
            }else{
                $this->Flash->error(__('Something went wrong. Please, try again.'));
            }
            $parent_purchase_ordre_id = $postData['parent_purchase_ordre_id'];
            if($postData['parent_link_type'] == '1'){
                return $this->redirect(['action' => 'detail', $parent_purchase_ordre_id]);
            }else if($postData['parent_link_type'] == '2'){
                return $this->redirect(['controller'=>'InventoryRepairOrders', 'action' => 'detail', $parent_purchase_ordre_id]);
            }else if($postData['parent_link_type'] == '3'){
                return $this->redirect(['controller'=>'InventoryShippingOrders', 'action' => 'detail', $parent_purchase_ordre_id]);
            }else if($postData['parent_link_type'] == '4'){
                return $this->redirect(['controller'=>'InventoryRequests', 'action' => 'detail', $parent_purchase_ordre_id]);
            }
        }

    }

?>