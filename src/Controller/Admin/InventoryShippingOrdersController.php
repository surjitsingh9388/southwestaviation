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

    class InventoryShippingOrdersController extends AppController
    {
        public function initialize() {
            parent::initialize();
            array_map(
                [
                    $this, 
                    'loadModel'
                ], 
                [
                    'InventoryItems',
                    'InventoryLocations',
                    'InventoryShippingOrderItems',
                    'InventoryShippingOrderReceives',
                    'InventoryVendors',
                    'InventoryAddresses',
                    'Inventories',
                    'InventoryShippingOrderHistories',
                    'InventoryPurchaseOrderLinks',
                    'InventoryPurchaseOrders',
                ]
            );
            
            array_map(
                [
                    $this, 
                    'loadComponent'
                ],
                [
                    'Address', 
                    'InventoryFilter', 
                    'InventoryAttachment', 
                    'Inventory', 
                    'InventoryHistory'
                ]
            );
            
        }

        public function beforeRender(\Cake\Event\Event $event) {
            $this->set('userData', $this->Auth->user());
        }

        public function index()
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Shipping Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Shipping Orders'];
                }
                $this->set(compact('actionItems'));
            }
        }

        public function search()
        {
            $query = [];        
            
            $query['count'] = "SELECT count(inv.id) AS count  FROM `inventory_shipping_orders` as inv WHERE 1=1 ";

            $query['detail'] = "SELECT inv.id, inv.shipping_order_number, inv.reference, inv.requestor, inv.shipping_order_tax, inv.shipping_order_tax_amount, inv.shipping_order_additional_fees, inv.currency, inv.shipping_order_cost_total, inv.created, inv.shipping_order_status, inv.status FROM `inventory_shipping_orders` as inv WHERE 1=1 ";
            
            return $query;
        }

        public function ajaxInventoryShippingOrdersearch(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Shipping Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Shipping Orders'];
                }
            }
            $this->autoRender = false;
            $this->layout = 'ajax';
            $requestData= $this->request->data;

            $query = $this->search();

            $cond = " AND inv.status = '1'";
            
            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                parse_str($requestData['columns'][1]['search']['value'], $requestData);
            }
            
            $cond .= $this->InventoryFilter->inventoryShippingOrderFilter($requestData);
            $requestData= $this->request->data;

            $columns = array(
                0 => 'inv.shipping_order_number',
                1 => 'inv.reference',
                2 => 'inv.requestor',
                3 => 'inv.shipping_order_status',
                4 => 'shipping_order_cost_total',
                5 => 'inv.created',
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

            $SQL = $detail." ORDER BY $sidx $sort LIMIT $start , $length ";
            $results = $conn->execute( $SQL )->fetchAll('assoc');
            
            $data = array();
            $currency = unserialize(CURRENCY);
            $InventoryStatusHTMLHelper = new InventoryStatusHTMLHelper(new \Cake\View\View());

            foreach ( $results as $row){//print_r($row);exit;
                $statushtml = $InventoryStatusHTMLHelper->getShippingOrderStatusHTML($row['status'], $row['shipping_order_status']);

                $nestedData= [];
                $nestedData[] = '<input type="hidden" value="'.$row['id'].'" class="chkBoxCls">';
                $nestedData[] = $row["shipping_order_number"];
                $nestedData[] = isset($row["reference"]) ? $row["reference"] : '-';
                $nestedData[] = isset($row["requestor"]) ? $row["requestor"] : '-';
                $nestedData[] = $row['shipping_order_cost_total'].' '.$currency[$row['currency']];
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
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Shipping Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Shipping Orders'];
                }
            }
            $linkedOrderId = isset($this->request->query['linkedOrderId']) ? $this->request->query['linkedOrderId'] : '0';
            $linkedOrderType = isset($this->request->query['linkedOrderType']) ? $this->request->query['linkedOrderType'] : '';
            $parentLinkedType = isset($this->request->query['parentLinkedType']) ? $this->request->query['parentLinkedType'] : '';
            
            $po_id = isset($this->request->query['po_id']) ? $this->request->query['po_id'] : '';

            $InventoryShippingOrders = $this->InventoryShippingOrders->newEntity();
            $InventoryShippingOrderItems = $this->InventoryShippingOrderItems->newEntity();
            if(!empty($po_id)){
                $inventorypurchaseorders = $this->InventoryPurchaseOrders->get($po_id);
                $InventoryShippingOrders->vendor = $inventorypurchaseorders->vendor;
            }
            
            $InventoryShippingOrders->destination = '2';
            
            $invid = isset($this->request->query['invid']) ? $this->request->query['invid'] : '';
            
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();//print_r($postData);exit;
                if(empty($postData['qty']) || count($postData['qty']) == 0){
                    $this->Flash->error(__('Purchase Order Requests require at least 1 line item.'));
                    return $this->redirect( Router::url( $this->referer(), true ) );
                }else{
                    for($i=0; $i<count($postData['qty']); $i++){
                        if(isset($postData['inventory_id'][$i]) && $postData['inventory_id'][$i] == 'Select a part number'){
                            $this->Flash->error(__('LineItems['.$i.'] Inventory Item is a required field.'));
                            return $this->redirect( Router::url( $this->referer(), true ) );
                        }else if(empty($postData['inventory_id'][$i]) && isset($postData['noninventory_item'][$i]) && empty($postData['noninventory_item'][$i])){
                            $this->Flash->error(__('LineItems['.$i.'] Non-Inventory Item Description is a required field.'));
                            return $this->redirect( Router::url( $this->referer(), true ) );
                        }else if(empty($postData['qty'][$i])){
                            $this->Flash->error(__('LineItems['.$i.'] Quantity is a required field.'));
                            return $this->redirect( Router::url( $this->referer(), true ) );
                        }
                    }
                }
                
                $invshippingduplicatecheck = $this->InventoryShippingOrders->find('all')->where(['shipping_order_number'=>$postData['shipping_order_number']])->select($this->InventoryShippingOrders);
                
                $errorflag = 0;
                if($invshippingduplicatecheck->count() > 0){
                    $this->Flash->error(__('Shipping order with the number '.$postData['shipping_order_number'].' already exists. It may be inactive.'));

                    $errorflag = 1;
                }else if(!isset($postData['qty'])){
                    $errorflag = 1;

                    $this->Flash->error(__('Shipping orders require at least one line item.'));
                }else{
                    if(count($postData['inventory_id']) !== count(array_unique($postData['inventory_id']))){
                        $errorflag = 1;

                        $this->Flash->error(__('Duplicate line item found.'));
                    }
                }

                if($errorflag == 1){
                    return $this->redirect( Router::url( $this->referer(), true ) );
                }
                
                $postData = $this->Inventory->getDestinationAddressDetails($postData);

                $postData['added_by'] = $this->Auth->user('id');//echo "<pre>";print_r($postData);exit;
                $subTotalCost = 0;
                for($i=0; $i<count($postData['qty']); $i++){
                    $subTotalCost += $postData['cost'][$i];
                }

                $totalCost = $subTotalCost+$postData['shipping_order_tax_amount']+$postData['shipping_order_additional_fees'];
                
                $postData['shipping_order_cost_subtotal'] = $subTotalCost;
                $postData['shipping_order_cost_total'] = $totalCost;

                $InventoryShippingOrders = $this->InventoryShippingOrders->patchEntity($InventoryShippingOrders, $postData);
                if ($this->InventoryShippingOrders->save($InventoryShippingOrders)) {
                    $shipping_order_id = $InventoryShippingOrders->id;

                    $this->Inventory->saveInventoryPurchaseOrderLinks($parentLinkedType, $linkedOrderType, $linkedOrderId, $shipping_order_id);

                    for($i=0; $i<count($postData['qty']); $i++){
                        $InventoryShippingOrderItems = $this->InventoryShippingOrderItems->newEntity();
                        
                        $inventory_id = isset($postData['inventory_id'][$i]) ? $postData['inventory_id'][$i] : '';

                        $invrequestpost = [];
                        $invrequestpost['inventory_shipping_order_id'] = $shipping_order_id;
                        $invrequestpost['inventory_id'] = $inventory_id;
                        $invrequestpost['noninventory_item'] = isset($postData['noninventory_item'][$i]) && !empty($postData['noninventory_item'][$i]) ? $postData['noninventory_item'][$i] : '';
                        $invrequestpost['qty'] = $postData['qty'][$i];
                        $invrequestpost['cost'] = $postData['cost'][$i];
                        
                        $InventoryShippingOrderItems = $this->InventoryShippingOrderItems->patchEntity($InventoryShippingOrderItems, $invrequestpost);
                        
                        $this->InventoryShippingOrderItems->save($InventoryShippingOrderItems);  
                    }
                    $this->InventoryAttachment->saveInvShippingOrderAttachment($shipping_order_id, $postData);

                    //save data to shipping order history table
                    $this->InventoryHistory->saveInventoryShippingOrderHistory($InventoryShippingOrders);

                    $this->Flash->success(__('The Shipping order has been saved.'));
                    return $this->redirect(['action' => 'detail', $shipping_order_id]);
                }

                $this->Flash->error(__('The Shipping order could not be saved. Please, try again.'));
            }
            
            $countries = $this->Address->getCountryList();
            $states = '';
            $inventoryvendors = $this->InventoryVendors->newEntity();
            $inventoryaddresses = $this->InventoryAddresses->newEntity();
            $inventoryaddressesarr = $this->InventoryAddresses->find('all')->where(['added_by'=>$this->Auth->user('id')]);
            $from_address = array();
            $to_address = array();
            foreach($inventoryaddressesarr as $address){
                if($address['is_shipping_address'] == '1'){
                    $to_address[$address['id']] = $address['name'];
                    $from_address[$address['id']] = $address['name'];
                }
            }
            $vendor = $this->Inventory->getVendorList();
            $InventoryShippingOrderItems = [];
            $invtype = '';
            if(!empty($invid)){
                $InventoryShippingOrderItems = $this->Inventories->find('all')->where(['id'=>$invid])->select(['id', 'location_id']);
                $invtype = 'invitem';
            }

            $inventoryitems = $this->Inventory->getAllInventoriesDetails();

            $inventorysoid = $this->InventoryShippingOrders->find('all', array('limit'=>1, 'order'=>'InventoryShippingOrders.id DESC', 'recursive' => 1,))->select(['id'])->last();
            if(!empty($inventorysoid)){
                $inventorysoid = $inventorysoid->id+1;
                $remaingtoaddzero = 6-strlen($inventorysoid);
                $so_number = '';
                for($i=1; $i<=$remaingtoaddzero; $i++){
                    $so_number .= '0';
                }
                $so_number .= $inventorysoid;
            }else{
                $so_number = '000001';
            }

            $this->set(compact('InventoryShippingOrders', 'actionItems', 'countries', 'states', 'inventoryvendors', 'inventoryaddresses', 'from_address', 'to_address', 'vendor', 'InventoryShippingOrderItems', 'invid', 'inventoryitems', 'invtype', 'po_id', 'so_number'));
        }

        public function edit($id = null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('Shipping Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Shipping Orders'];
                }
            }

            $InventoryShippingOrders = $this->InventoryShippingOrders->find('all')->where(['id'=>$id])->select()->first($this->InventoryShippingOrders);
            if(empty($InventoryShippingOrders)){
                return $this->redirect(['action' => 'index']);
            }

            //echo "<pre>";print_r($InventoryShippingOrders);exit;
            if ($this->request->is(['patch', 'post', 'put'])) {
                $errorflag = 0;
                $postData = $this->request->getData();

                $invshippingduplicatecheck = $this->InventoryShippingOrders->find('all')->where(['shipping_order_number'=>$postData['shipping_order_number'], 'id !='=>$id])->select($this->InventoryShippingOrders);
                
                $errorflag = 0;
                if($invshippingduplicatecheck->count() > 0){
                    $this->Flash->error(__('Shipping order with the number '.$postData['shipping_order_number'].' already exists. It may be inactive.'));

                    $errorflag = 1;
                }else if(!isset($postData['qty'])){
                    $errorflag = 1;

                    $this->Flash->error(__('Shipping orders require at least one line item.'));
                }else{
                    
                    if(count($postData['inventory_id']) !== count(array_unique($postData['inventory_id']))){
                        $errorflag = 1;

                        $this->Flash->error(__('Duplicate line item found.'));
                    }
                }

                if($errorflag == 1){
                    return $this->redirect( Router::url( $this->referer(), true ) );
                }
                
                $postData = $this->request->getData();//print_r($postData);exit;
                $postData['updated_by'] = $this->Auth->user('id');
                $subTotalCost = 0;
                for($i=0; $i<count($postData['qty']); $i++){
                    $subTotalCost += $postData['cost'][$i];
                }

                $totalCost = $subTotalCost+$postData['shipping_order_tax_amount']+$postData['shipping_order_additional_fees'];
                
                $postData['shipping_order_cost_subtotal'] = $subTotalCost;
                $postData['shipping_order_cost_total'] = $totalCost;

                $postData = $this->Inventory->getDestinationAddressDetails($postData);
                $countdet = $this->Inventory->getCountSOReceivedAndSOItem($id);
                
                //update shipping order status
                if($countdet['invsoreccount'] == $countdet['invsoitmcount']){
                    $postData['shipping_order_status'] = '4';
                }
                $InventoryShippingOrders = $this->InventoryShippingOrders->patchEntity($InventoryShippingOrders, $postData);

                if ($this->InventoryShippingOrders->save($InventoryShippingOrders)) {
                    //delete all po items
                    $connection = ConnectionManager::get('default');
                    $results = $connection
                    ->execute(
                        'delete from inventory_shipping_order_items WHERE inventory_shipping_order_id = :inventory_shipping_order_id and id not in('."'" . implode ( "', '", $postData['itemid'] ) . "'".')',
                        ['inventory_shipping_order_id' => $id],
                        ['created' => 'datetime']
                    );

                    $results = $connection
                    ->execute(
                        'delete from inventory_shipping_order_receives WHERE inventory_shipping_order_id = :inventory_shipping_order_id and inventory_id != 0 and inventory_shipping_order_item_id not in('."'" . implode ( "', '", $postData['itemid'] ) . "'".')',
                        ['inventory_shipping_order_id' => $id],
                        ['created' => 'datetime']
                    );
                    
                    for($i=0; $i<count($postData['qty']); $i++){
                        $InventoryShippingOrderItems = $this->InventoryShippingOrderItems->newEntity();

                        $itemid = isset($postData['itemid'][$i]) ? $postData['itemid'][$i] : '';
                        if(!empty($itemid)){
                            $InventoryShippingOrderItems = $this->InventoryShippingOrderItems->get($itemid);
                        }

                        $inventory_id = isset($postData['inventory_id'][$i]) ? $postData['inventory_id'][$i] : '';

                        $invrequestpost = [];
                        $invrequestpost['inventory_shipping_order_id'] = $id;
                        $invrequestpost['inventory_id'] = $inventory_id;
                        $invrequestpost['noninventory_item'] = isset($postData['noninventory_item'][$i]) && !empty($postData['noninventory_item'][$i]) ? $postData['noninventory_item'][$i] : '';
                        $invrequestpost['qty'] = $postData['qty'][$i];
                        $invrequestpost['cost'] = $postData['cost'][$i];
                        
                        $InventoryShippingOrderItems = $this->InventoryShippingOrderItems->patchEntity($InventoryShippingOrderItems, $invrequestpost);
                        
                        $this->InventoryShippingOrderItems->save($InventoryShippingOrderItems);
                    }
                    $this->InventoryAttachment->saveInvShippingOrderAttachment($id, $postData);
                    $this->Flash->success(__('The Shipping order has been saved.'));
                    return $this->redirect(['action' => 'detail', $id]); 
                }
                
                $this->Flash->error(__('The Shipping order could not be saved. Please, try again.'));
            }
            
            $countries = $this->Address->getCountryList();
            $states = $this->Address->getStateListByCountryId($InventoryShippingOrders->country);
            $inventoryvendors = $this->InventoryVendors->newEntity();
            $inventoryaddresses = $this->InventoryAddresses->newEntity();
            $inventoryaddressesarr = $this->InventoryAddresses->find('all')->where(['added_by'=>$this->Auth->user('id')]);
            $from_address = array();
            $to_address = array();
            foreach($inventoryaddressesarr as $address){
                if($address['is_shipping_address'] == '1'){
                    $to_address[$address['id']] = $address['name'];
                    $from_address[$address['id']] = $address['name'];
                }
            }
            $vendor = $this->Inventory->getVendorList();

            $InventoryShippingOrderItems = $this->Inventory->getInventorySODetailsBySOId($id);

            $inventoryitems = $this->Inventory->getAllInventoriesDetails('1');
            $location = $this->Inventory->getAllLocations();
            $attachments = $this->InventoryAttachment->getInvShippingOrderAttachedFiles($id);

            $inventorypoitemsrec = $this->Inventory->getInventorySOReceivedItem($id);
            //echo "<pre>";print_r($inventorypoitemsrec);exit;
            $invitmreceived = $inventorypoitemsrec['invitmreceived'];

            $inventorysohistories = $this->Inventory->getShippingOrderHistoryList($id);
            
            $this->set(compact('InventoryShippingOrders', 'actionItems', 'countries', 'states', 'inventoryvendors', 'inventoryaddresses', 'to_address', 'from_address', 'vendor', 'InventoryShippingOrderItems', 'inventoryitems', 'location', 'attachments', 'invitmreceived', 'inventorysohistories'));
        }

        public function detail($id = null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Shipping Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Shipping Orders'];
                }
            }
            
            $InventoryShippingOrders = $this->InventoryShippingOrders->find('all')->where(['id'=>$id])->select()->first($this->InventoryShippingOrders);
            if(empty($InventoryShippingOrders)){
                return $this->redirect(['action' => 'index']);
            }

            $postData = $this->request->getData();

            if ($this->request->is(['patch', 'post', 'put']) && isset($postData['tracking_number'])) {
                
                for($i=0; $i<count($postData['tracking_number']); $i++){
                    $InventoryShippingOrderItems = $this->InventoryShippingOrderItems->newEntity();

                    $itemid = isset($postData['itemid'][$i]) ? $postData['itemid'][$i] : '';
                    if(!empty($itemid)){
                        $InventoryShippingOrderItems = $this->InventoryShippingOrderItems->get($itemid);
                    }

                    $invrequestpost = [];
                    $invrequestpost['inventory_shipping_order_id'] = $id;
                    $invrequestpost['tracking_number'] = $postData['tracking_number'][$i];
                    
                    $InventoryShippingOrderItems = $this->InventoryShippingOrderItems->patchEntity($InventoryShippingOrderItems, $invrequestpost);
                    
                    $this->InventoryShippingOrderItems->save($InventoryShippingOrderItems); 
                }

                $inventoryShippingOrderItems = $this->InventoryShippingOrderItems->find('all')->where(['inventory_shipping_order_id'=>$id, 'status'=>'4'])->select($this->InventoryShippingOrderItems);
                
                //update shipping order status
                $shipping_order_status = '1';
                if(count($postData['itemid']) == $inventoryShippingOrderItems->count()){
                    $shipping_order_status = '4';
                }

                $invpodata = [];
                $invpodata['shipping_order_status'] = $shipping_order_status;
                $invpodata['updated_by'] = $this->Auth->user('id');
                $InventoryShippingOrders = $this->InventoryShippingOrders->patchEntity($InventoryShippingOrders, $invpodata);

                $this->InventoryShippingOrders->save($InventoryShippingOrders);
                
                $this->InventoryAttachment->saveInvPOAttachment($id, $postData);
                $this->Flash->success(__('The Shipping order has been saved.'));

                return $this->redirect(['action' => 'detail', $id]); 
            }
            
            $InventoryShippingOrderItems = $this->Inventory->getInventorySODetailsBySOId($id);
            
            $inventorysoitemsrec = $this->Inventory->getInventorySOReceivedItem($id);
            $inventorysoreceivedarr = $inventorysoitemsrec['inventorysoreceivedarr'];
            $invitmreceived = $inventorysoitemsrec['invitmreceived'];

            //print_r($invitmreceived);exit;
            $vendors = '';
            if(!empty($InventoryShippingOrders->vendor)){
                $vendors = $this->InventoryVendors->get($InventoryShippingOrders->vendor);
            }

            $inventorybillingaddress = $this->InventoryAddresses->get($InventoryShippingOrders->from_address);
            $inventoryshippingaddress = '';
            if(!empty($InventoryShippingOrders->to_address)){
                $inventoryshippingaddress = $this->InventoryAddresses->get($InventoryShippingOrders->to_address);
            }

            $countries = $this->Address->getCountryList();
            $attachments = $this->InventoryAttachment->getInvShippingOrderAttachedFiles($id);

            //link other order
            $linkorderdata = $this->Inventory->getLinkOrdersData($id, '3');

            $states = $this->Address->getStateListByCountryId($InventoryShippingOrders->country);

            $inventorysohistories = $this->Inventory->getShippingOrderHistoryList($id);
            
            //echo "<pre>";print_r($InventoryShippingOrders);exit;

            $InventoryStatusHTMLHelper = new InventoryStatusHTMLHelper(new \Cake\View\View());
            $statushtml = $InventoryStatusHTMLHelper->getShippingOrderStatusHTML($InventoryShippingOrders->status, $InventoryShippingOrders->shipping_order_status);

            $this->set(compact('InventoryShippingOrders', 'actionItems', 'InventoryShippingOrderItems', 'inventorybillingaddress', 'inventoryshippingaddress', 'countries', 'vendors', 'attachments', 'invitmreceived', 'inventorysoreceivedarr', 'states', 'inventorysohistories', 'linkorderdata', 'statushtml'));
        }

        public function inventoryShippingOrderItemDropDown(){
            
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                
                $inventoryitems = $this->Inventory->getAllInventoriesDetails();

                $location = $this->Inventory->getAllLocations();
                
                $currencyarr = unserialize(CURRENCY);

                $this->set('inventoryitems', $inventoryitems);
                $this->set('invtype', $postData['invtype']);
                $this->set('pocurrency', $currencyarr[$postData['currency']]);
                
                $this->layout = 'ajax';
                $this->render("/Element/Inventory/inventory_shipping_order_item_add");
            }
        }

        public function updateShippingOrderStatus($id=null){
            $this->request->allowMethod(['post', 'delete']);
            $id = $_POST['id'];
            $invenotryrepairorders = $this->InventoryShippingOrders->get($id);
            
            try {
                $postData = array();
                $status = $_POST['status'];
                $shipping_order_status = $_POST['shipping_order_status'];
                
                if(!empty($shipping_order_status)){
                    $postData['shipping_order_status'] = $shipping_order_status;
                }else if(!empty($status)){
                    $postData['status'] = $status;
                }
                $postData['updated_by'] = $this->Auth->user('id');
                $invenotryrepairorders = $this->InventoryShippingOrders->patchEntity($invenotryrepairorders, $postData);
                if ($this->InventoryShippingOrders->save($invenotryrepairorders)) {
                    if($shipping_order_status == '3'){
                        $msg = 'Canceled Shipping order request `'.$invenotryrepairorders->shipping_order_number.'`.';
                        $res = $this->InventoryShippingOrderItems->updateAll(
                            array('status'=>$shipping_order_status, 'updated_by'=>$this->Auth->user('id')),
                            array('inventory_shipping_order_id' => $id)
                        );
                    }else if($shipping_order_status == '1'){
                        $msg = 'Reopen Shipping order request `'.$invenotryrepairorders->shipping_order_number.'`.';
                        $res = $this->InventoryShippingOrderItems->updateAll(
                            array('updated_by'=>$this->Auth->user('id')),
                            array('inventory_shipping_order_id' => $id)
                        );
                    }else if($shipping_order_status == '2'){
                        $inventoryshippingorderitems = $this->InventoryShippingOrderItems->find('all')->where(['inventory_id IS NOT'=>NULL, 'inventory_shipping_order_id'=>$id])->select($this->InventoryShippingOrderItems);
                        foreach($inventoryshippingorderitems as $invitm){
                            $inventories = $this->Inventories->newEntity();
                            $inventories = $this->Inventories->get($invitm->inventory_id);
                            $inventorylocationarr = !empty($inventories->location_id) ? $this->InventoryLocations->get($inventories->location_id) : [];
                            $inventoryarr = [];
                            if($invenotryrepairorders->destination == '1'){
                                $inventory_status = '9';
                                $location_id = '';
                            }else{
                                $inventory_status = '10';
                                $location_id = '0';
                            }
                            $inventoryarr['location_id'] = $location_id;
                            $inventoryarr['status'] = $inventory_status;
                            $inventoryarr['updated_by'] = $this->Auth->user('id');
                            $inventories = $this->Inventories->patchEntity($inventories, $inventoryarr);
                            
                            $this->Inventories->save($inventories);

                            //transaction history data save
                            if($invenotryrepairorders->destination == '1'){
                                $inventoryitems = $this->InventoryItems->get($inventories->inventory_item_id);
                                
                                $from_description = isset($inventorylocationarr->location_name) ? $inventorylocationarr->location_name : '';
                                $to_description = 'Shipping Order #:'.$invenotryrepairorders->shipping_order_number;
                                $type = 'Internal Ship';
                                $itemtype_from = $inventoryitems->item_type;
                                $itemtype_to = $inventoryitems->item_type;
                                $from_location_id = isset($inventorylocationarr->id) ? $inventorylocationarr->id : 0;

                                $otherparamaterarr = array(
                                                            'from_description'=>$from_description,
                                                            'to_description'=>$to_description,
                                                            'type'=>$type,
                                                            'qty'=>$inventories->qty,
                                                            'from_location_id'=>$from_location_id,
                                                            'itemtype_from'=>$itemtype_from,
                                                            'itemtype_to'=>$itemtype_to,
                                                            'from_status'=>$inventories->status,
                                                            'to_status'=>$inventory_status,
                                                            'shipping_order_id'=>$invenotryrepairorders->id
                                                        );
                                $this->Inventory->saveInventoryTransactionHistory($inventories, $otherparamaterarr);
                            }
                        }

                        $msg = 'Sent Shipping order request `'.$invenotryrepairorders->shipping_order_number.'`.';
                        $res = $this->InventoryShippingOrderItems->updateAll(
                            array('status'=>$shipping_order_status, 'updated_by'=>$this->Auth->user('id')),
                            array('inventory_shipping_order_id' => $id)
                        );
                    }else if($status == '2'){
                        $msg = 'Invenotry request deactivated `'.$invenotryrepairorders->shipping_order_number.'`.';
                    }else if($status == '1'){
                        $msg = 'Invenotry request activated `'.$invenotryrepairorders->shipping_order_number.'`.';
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

        //Generate InventoryShippingOrders report pdf
        public function generateInvShippingOrderPdf()
        {
            $postData = $this->request->data;
            $mainHtml = '';
            $flHtml = '';
            
            $flRes = $this->Inventory->InventoryShippingOrderPDFData($postData);
            if(!empty($flRes)) {
                $flHtml = $flRes;
            } else {
                $flHtml = '<tr><td colspan="16" style="text-align: center;">No Records Found for that Shipping order.</td></tr>';
            }

            $mainHtml .= '<header>
            <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td style="font-size: 14px; font-weight: normal; text-align:center;"><span style="font-weight: bold;">SHIPPING ORDER</span></td>
                    </tr>
                    <tr>
                        <td style="font-size: 10px; font-weight: normal; text-align:center;"><span>A list of shipping orders for a given filter criteria.</span></td>
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
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Order</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Reference</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Requestor</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Status</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Cost</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Submitted</th>
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
            $fileName = "InventoryShippingOrders".date('YmdHis').".pdf";
            $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
            if($mainHtml) {
                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
            
        }

        public function generateInvShippingDetPdf()
        {
            $postData = $this->request->data;
            $mainHtml = '';
            $flHtml = '';
            
            $id = $postData['id'];
            $InventoryShippingOrders = $this->InventoryShippingOrders->get($id);

            $status = '';
                
            if($InventoryShippingOrders->shipping_order_status == '1'){
                $status = 'Partial Received';
            }else if($InventoryShippingOrders->shipping_order_status == '0'){
                $status = 'Draft';
            }else if($InventoryShippingOrders->shipping_order_status == '3'){
                $status = 'Canceled';
            }else if($InventoryShippingOrders->shipping_order_status == '2'){
                $status = 'Shipped To Vendor';
            }else if($InventoryShippingOrders->shipping_order_status == '4'){
                $status = 'Close';
            }

            $mainHtml .= '<header>
                                <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td style="font-size: 14px; font-weight: normal; text-align:center;"><span style="font-weight: bold;">SHIPPING ORDER</span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; font-weight: normal; text-align:center; margin-top:15px;">
                                            <table style="width:100%">
                                                <tr>
                                                    <td><b>Order Number</b></td>
                                                    <td><b>Date</b></td>
                                                    <td><b>Status</b></td>
                                                </tr>
                                                <tr>
                                                    <td>'.$InventoryShippingOrders->shipping_order_number.'</td>
                                                    <td>'.$InventoryShippingOrders->po_date.'</td>
                                                    <td>'.$status.'</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Account Code</b></td>
                                                    <td><b>Reference</b></td>
                                                    <td><b>Contact</b></td>
                                                </tr>
                                                <tr>
                                                    <td>'.$InventoryShippingOrders->account_code.'</td>
                                                    <td>'.$InventoryShippingOrders->reference.'</td>
                                                    <td>'.$InventoryShippingOrders->contact.'</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </header>';
            $vendor_name = '';
            if(!empty($InventoryShippingOrders->vendor)){
                $vendors = $this->InventoryVendors->get($InventoryShippingOrders->vendor);
                $vendor_name = $vendors->name;
            }

            $inventorybillingaddress = $this->InventoryAddresses->get($InventoryShippingOrders->from_address);
            $shipping_address = '';
            if(!empty($InventoryShippingOrders->ship_to_address)){
                $inventoryshippingaddress = $this->InventoryAddresses->get($InventoryShippingOrders->to_address);
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
                                        <td>'.(!empty($InventoryShippingOrders->ship_via) ? $shipviaarr[$InventoryShippingOrders->ship_via] : '').'</td>
                                    </tr>
                                </tbody>
                        </table>';

            
            $InventoryShippingOrderItems = $this->InventoryShippingOrderItems->find('all')->where(['InventoryShippingOrderItems.inventory_shipping_order_id'=>$id])->select($this->InventoryShippingOrderItems)->select(['invitms.name', 'invitms.part_number','invloc.location_name'])
            ->join([
                'invitms' => [
                    'table' => 'inventory_items',
                    'type' => 'LEFT',
                    'conditions' => 'invitms.id = InventoryShippingOrderItems.inventory_id',
                ],
                'invloc' => [
                    'table' => 'inventory_locations',
                    'type' => 'left',
                    'conditions' => 'invloc.id = InventoryShippingOrderItems.location_id',
                ]
            ]);
            
            $mainHtml .= '<table style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center; font-size:11px; clear:both;" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Item</th>
                                    <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Quantity</th>
                                    <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Unit Price</th>
                                    <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Total</th>
                                </tr>
                            </thead>
                            <tbody>';
            $flHtml = '';
            $currency = unserialize(CURRENCY);
            $subTotal = 0;
            $shipping = $InventoryShippingOrders->po_shipping;
            $salesTax = $InventoryShippingOrders->po_tax_amount;
            $currencyName = $currency[$InventoryShippingOrders->currency];

            foreach($InventoryShippingOrderItems as $row){
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
            $fileName = "InventoryRepairOrderDetails".date('YmdHis').".pdf";
            $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
            if($mainHtml) {
                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
        }

        public function bulkInvShippingOrderAttachmentUpload(){
            $postData = $this->request->data;
            if(!empty($postData['file_name'])) 
            {
                
                $isvalidfile = 1;
                $arr_ext = array('pdf','txt');
                
                $temp = $postData['file_name']['tmp_name'];
                $name = $postData['file_name']['name'];
                $ext = substr(strrchr($name , '.'), 1);
                
                /*if (!in_array($ext, $arr_ext)) {
                    $isvalidfile = 0;
                }*/
                
                if($isvalidfile){
                    $foldername = 'inventoryshippingorderitems';
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

        public function deleteInvShippingOrderAttachment(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Shipping Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Shipping Orders'];
                }
            }
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['id'])){
                    $attachments = $this->InventoryAttachment->deleteInvShippingOrderAttachment($postData['id']);

                    $result = array('status'=>'success', 'message'=>"Deleted successfully.");
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);die;
        }

        public function receive($inventory_shipping_order_id, $inventory_ro_item_id=null){
            $InventoryShippingOrders = $this->InventoryShippingOrders->newEntity();
            $InventoryShippingOrders = $this->InventoryShippingOrders->find('all')->where(['id'=>$inventory_shipping_order_id])->select($this->InventoryShippingOrders)->first();
            if(empty($InventoryShippingOrders)){
                return $this->redirect(['action' => 'index']);
            }
            //echo "<pre>";print_r($InventoryShippingOrders);exit;
            
            $wherecond = ['InventoryShippingOrderItems.inventory_shipping_order_id'=>$inventory_shipping_order_id];
            if(!empty($inventory_ro_item_id)){
                $wherecond['InventoryShippingOrderItems.id'] = $inventory_ro_item_id;
            }
            
            $InventoryShippingOrderItems = $this->InventoryShippingOrderItems->find()->where($wherecond)->select($this->InventoryShippingOrderItems)->select(['invitms.id', 'invitms.name', 'invitms.part_number', 'invitms.is_this_item_serialized', 'inv.serial_no', 'inv.cost', 'inv.id', 'inv.uom'])
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
                ]
            ]);
            $inventoryroitemcount = $InventoryShippingOrderItems->count();
            if($inventoryroitemcount == 0){
                return $this->redirect(['action' => 'index']);
            }

            //echo "<pre>";print_r($invitemnotrec);exit;
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();//echo "<pre>";print_r($postData);exit;
                
                $message = [];
                for($i=0; $i<count($postData['itemid']); $i++){
                    if(empty($postData['received'][$i])){
                        $message[] = 'At least one line item must have a quantity to receive';
                    }
                }
                if(count($message) == count($postData['itemid'])){
                    $msgtxt = 'At least one line item must have a quantity to receive';

                    $this->Flash->error(__($msgtxt));
                    return $this->redirect( Router::url( $this->referer(), true ) ); 
                }
                $message = '';
                for($i=0; $i<count($postData['itemid']); $i++){
                    $invshiporderitems = $this->InventoryShippingOrderItems->get($postData['itemid'][$i]);
                    if($invshiporderitems->status == '4'){
                        continue;
                    }
                    if(empty($postData['received'][$i]) && empty($postData['location_id'][$i])){
                        continue;
                    }
                    if(!empty($invshiporderitems->inventory_id)){
                        if(empty($postData['location_id'][$i])){
                            $message = 'Location is required';
                        }
                    }else{
                        if(empty($postData['received'][$i])){
                            $message = 'At least one line item must have a quantity to receive';
                        }
                    }
                   
                    if(!empty($postData['received'][$i]) && $postData['received'][$i] > $invshiporderitems->qty){
                        $this->Flash->error(__('Received should be equal or less from qantity'));
                        return $this->redirect( Router::url( $this->referer(), true ) );  
                    }
                    if(!empty($message)){
                        $this->Flash->error(__($message));
                        return $this->redirect( Router::url( $this->referer(), true ) );  
                    }
                }
                for($i=0; $i<count($postData['received']); $i++){
                    $invshiporderitems = $this->InventoryShippingOrderItems->get($postData['itemid'][$i]);
                    if($invshiporderitems->status == '4'){
                        continue;
                    }
                    
                    //echo "<pre>";print_r($postData);exit;
                    $inventory_id = !empty($invshiporderitems->inventory_id) ? $invshiporderitems->inventory_id : '0';
                    if(!empty($inventory_id) && !empty($postData['received'][$i])){
                        $inventories = $this->Inventories->get($inventory_id);
                        $inventorydata = [];
                        $inventorydata['location_id'] = $postData['location_id'][$i];
                        $inventorydata['status'] = '1';
                        
                        $inventories = $this->Inventories->patchEntity($inventories, $inventorydata);
                        $this->Inventories->save($inventories);
                    }

                    if(!empty($postData['received'][$i])){
                        $inventorysorecdata = [];

                        $inventorysorecdata['received'] = ($invshiporderitems->received+$postData['received'][$i]);
                        $inventorysorecdata['location_id'] = isset($postData['location_id'][$i]) ? $postData['location_id'][$i] : '0';
                        $inventorysorecdata['account_code'] = isset($postData['account_code'][$i]) ? $postData['account_code'][$i] : '0';
                        $inventorysorecdata['notes'] = isset($postData['notes'][$i]) ? $postData['notes'][$i] : '';
                        $inventorysorecdata['updated_by'] = $this->Auth->user('id');
                        $status = '1';
                        if($inventorysorecdata['received'] == $invshiporderitems->qty){
                            $status = '4';
                        }
                        $inventorysorecdata['status'] = $status;

                        $invshiporderitems = $this->InventoryShippingOrderItems->patchEntity($invshiporderitems, $inventorysorecdata);
                        
                        $this->InventoryShippingOrderItems->save($invshiporderitems);
                    }

                    //transaction history data save
                    if(isset($postData['location_id'][$i]) && !empty($postData['location_id'][$i])){
                        $inventoryitems = $this->InventoryItems->get($inventories->inventory_item_id);
                        $locationarr = $this->InventoryLocations->get($postData['location_id'][$i]);
                        
                        $from_description = 'Received From Shipping Order '.$InventoryShippingOrders->shipping_order_number;
                        $to_description = isset($locationarr->location_name) ? $locationarr->location_name : '';
                        $type = 'Receive';
                        $itemtype_from = $inventoryitems->item_type;
                        $itemtype_to = $inventoryitems->item_type;

                        $otherparamaterarr = array(
                                                    'from_description'=>$from_description,
                                                    'to_description'=>$to_description,
                                                    'type'=>$type,
                                                    'qty'=>$inventories->qty,
                                                    'itemtype_from'=>$itemtype_from,
                                                    'itemtype_to'=>$itemtype_to,
                                                    'to_status'=>$inventories->status,
                                                    'shipping_order_id'=>$inventory_shipping_order_id
                                                );
                        $this->Inventory->saveInventoryTransactionHistory($inventories, $otherparamaterarr);
                    }
                }
                
                $inventoryShippingOrderItems = $this->InventoryShippingOrderItems->find('all')->where(['inventory_shipping_order_id'=>$inventory_shipping_order_id, 'status'=>'4'])->select($this->InventoryShippingOrderItems);

                //update shipping order status
                $shipping_order_status = '1';
                if($inventoryroitemcount == $inventoryShippingOrderItems->count()){
                    $shipping_order_status = '4';
                }

                $invpodata = [];
                $invpodata['shipping_order_status'] = $shipping_order_status;
                $invpodata['updated_by'] = $this->Auth->user('id');
                $InventoryShippingOrders = $this->InventoryShippingOrders->patchEntity($InventoryShippingOrders, $invpodata);

                $this->InventoryShippingOrders->save($InventoryShippingOrders);

                $this->Flash->success(__('The inventory has been saved.'));
                return $this->redirect(['action' => 'detail', $inventory_shipping_order_id]);

            }

            $inventoryitemsarr = $this->InventoryItems->find('all');
            $invitemdropdown = [];
            foreach($inventoryitemsarr as $val){
                $invitemdropdown[$val['id']] = $val['name'].' (PN: '.$val['part_number'].')';
            }
            $location = $this->Inventory->getAllLocations();
            $inventorybillingaddress = $this->InventoryAddresses->get($InventoryShippingOrders->from_address);
            $inventoryshippingaddress = '';
            if(!empty($InventoryShippingOrders->to_address)){
                $inventoryshippingaddress = $this->InventoryAddresses->get($InventoryShippingOrders->to_address);
            }
            $countries = $this->Address->getCountryList();
            $states = $this->Address->getStateListByCountryId($InventoryShippingOrders->country);
            
            $inventoryaddresses = $this->InventoryAddresses->newEntity();
            $inventoryaddressesarr = $this->InventoryAddresses->find('all')->where(['added_by'=>$this->Auth->user('id')]);
            $from_address = array();
            $to_address = array();
            foreach($inventoryaddressesarr as $address){
                if($address['is_shipping_address'] == '1'){
                    $to_address[$address['id']] = $address['name'];
                    $from_address[$address['id']] = $address['name'];
                }
            }

            $this->set(compact('InventoryShippingOrders', 'InventoryShippingOrderItems', 'location', 'inventorybillingaddress', 'inventoryshippingaddress', 'countries', 'states', 'from_address', 'to_address'));
        }

        public function getInventoryAddressDetails(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->layout = 'ajax';
                $postData = $this->request->getData();
                if(!empty($postData['address_id'])){
                    $inventorybillingaddress = $this->InventoryAddresses->get($postData['address_id']);
                    $countries = $this->Address->getCountryList();
                    $states = $this->Address->getStateListByCountryId($inventorybillingaddress->country);

                    $address = $inventorybillingaddress->city.', ';
                    if(!empty($inventorybillingaddress->state)){
                        $address .= $states[$inventorybillingaddress->state];
                    }else{
                        $address .= $inventorybillingaddress->province;
                    }
                    $addrdata = '<p class="form-control-static">
                                        '.$inventorybillingaddress->name.'
                                    </p>
                                    <p>'.$inventorybillingaddress->street1.'</p>
                                    <p>'.$address.' '.$inventorybillingaddress->postal_code.'</p>
                                    <p>'.$countries[$inventorybillingaddress->country].'</p>';
                    $result = array('status'=>'success', 'message'=>'', 'addrdata'=>$addrdata);
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                
                echo json_encode($result);die;
            }
        }

        public function exportShippingOrderListToExcel() {
            
            $dataTable = '';
            $dataTable .='<table class="table">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Div. Abbr.</th>
                                        <th>Division Name</th>
                                        <th>Reference</th>
                                        <th>Requestor</th>
                                        <th>Status</th>
                                        <th>Account Code</th>
                                        <th>Cost</th>
                                        <th>Currency Code</th>
                                        <th>Submitted</th>
                                    </tr>
                                </thead>
                            <tbody>';

            $query = "SELECT inv.* FROM `inventory_shipping_orders` as inv  WHERE 1=1 ";

            $requestData= $this->request->query;//echo "<pre>";print_r($requestData);exit;
            
            $cond = $this->InventoryFilter->inventoryShippingOrderFilter($requestData);            
            
            //echo $cond;exit;
            $columns = array(
                0 => 'inv.shipping_order_number',
                1 => 'inv.reference',
                2 => 'inv.requestor',
                3 => 'inv.shipping_order_status',
                4 => 'shipping_order_cost_total',
                5 => 'inv.created',
            );
            
            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sort = 'asc';
            
            $SQL = $query.$cond." ORDER BY $sidx $sort";//echo $SQL;exit;
            $inventoryrequestitems = $conn->execute( $SQL )->fetchAll('assoc');
            
            $setData = '';  
            $currency = unserialize(CURRENCY);
            $shippingOrderStatus = unserialize(SHIPPING_ORDER_STATUS);

            foreach($inventoryrequestitems as $invroitems){
                $urgency = !empty($invreqitems['urgency']) ? $urgencyStatus[$invreqitems['urgency']] : '';

                $dataTable .='<tr>
                                <td>'.$invroitems['shipping_order_number'].'</td>
                                <td></td>
                                <td></td>
                                <td>'.$invroitems['reference'].'</td>
                                <td>'.$invroitems['requestor'].'</td>
                                <td>'.$shippingOrderStatus[$invroitems['shipping_order_status']].'</td>
                                <td>'.$invroitems['account_code'].'</td>
                                <td>'.$invroitems['shipping_order_cost_total'].'</td>
                                <td>'.(!empty($invroitems['currency']) ? $currency[$invroitems['currency']] : '').'</td>
                                <td>'.(!empty($invroitems['created']) ? date('m/d/Y', strtotime($invroitems['created'])) : '').'</td>
                            </tr>';
            }  
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=ShippingOrders".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }
        
    }

?>