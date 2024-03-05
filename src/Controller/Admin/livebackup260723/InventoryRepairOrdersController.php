<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use Cake\View\View;
    use Dompdf\Dompdf;

    class InventoryRepairOrdersController extends AppController
    {
        public function initialize() {
            parent::initialize();
            $this->loadModel('InventoryItems');
            $this->loadModel('InventoryLocations');
            $this->loadModel('InventoryROItems');
            $this->loadModel('InventoryROReceives');
            $this->loadModel('InventoryVendors');
            $this->loadModel('InventoryAddresses');
            $this->loadModel('Inventories');
            $this->loadModel('InventoryPurchaseOrderLinks');
            $this->loadModel('InventoryRepairOrderHistories');
            $this->loadModel('InventoryPurchaseOrders');
            $this->loadModel('InventoryRequests');
            $this->loadModel('InventoryShippingOrders');
            $this->loadComponent('Address');
            $this->loadComponent('InventoryAttachment');
            $this->loadComponent('Inventory');
            $this->loadComponent('InventoryHistory');
            $this->loadComponent('InventoryFilter');
        }

        public function beforeRender(\Cake\Event\Event $event) {
            $this->set('userData', $this->Auth->user());
        }

        public function index()
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Repair Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Repair Orders'];
                }
                $this->set(compact('actionItems'));
            }
        }

        public function search()
        {
            $query = [];        
            
            $query['count'] = "SELECT count(invro.id) AS count  FROM `inventory_repair_orders` as invro left join inventory_vendors as invrovendor on invro.vendor = invrovendor.id WHERE 1=1 ";

            $query['detail'] = "SELECT invro.id, invro.ro_number, invro.reference, invrovendor.name as vendor_name, invro.requestor, invro.account_code, invro.ro_tax, invro.ro_tax_amount, invro.ro_shipping, invro.currency, invro.ro_cost_total, invro.created, invro.ro_status FROM `inventory_repair_orders` as invro left join inventory_vendors as invrovendor on invro.vendor = invrovendor.id WHERE 1=1 ";
            
            return $query;
        }

        public function ajaxInventoryRepairOrdersearch(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Repair Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Repair Orders'];
                }
            }
            $this->autoRender = false;
            $this->layout = 'ajax';
            $requestData= $this->request->data;

            $query = $this->search();

            $cond = " AND invro.status = '1'";
            
            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                parse_str($requestData['columns'][1]['search']['value'], $requestData);
            }

            $cond .= $this->InventoryFilter->inventoryRepairFilter($requestData);
            
            $requestData= $this->request->data;
            $columns = array(
                0 => 'invro.ro_number',
                1 => 'invro.reference',
                2 => 'invrovendor.name',
                3 => 'invro.requestor',
                4 => 'invro.ro_status',
                5 => 'invro.ro_cost_total',
                6 => 'invro.created',
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
            foreach ( $results as $row){//print_r($row);exit;
                
                $statushtml = '';
                
                if($row['ro_status'] == '1'){
                    $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-approved">Partial</div>';
                }else if($row['ro_status'] == '0'){
                    $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-pending">Draft</div>';
                }else if($row['ro_status'] == '3'){
                    $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-canceled">Canceled</div>';
                }else if($row['ro_status'] == '5'){
                    $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-created">RO Created</div>';
                }else if($row['ro_status'] == '2'){
                    $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-sent">To Vendor</div>';
                }else if($row['ro_status'] == '4'){
                    $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-closed">Close</div>';
                }

                $nestedData= [];
                $nestedData[] = '<input type="hidden" value="'.$row['id'].'" class="chkBoxCls">';
                $nestedData[] = $row["ro_number"];
                $nestedData[] = isset($row["reference"]) ? $row["reference"] : '-';
                $nestedData[] = isset($row["vendor_name"]) ? $row["vendor_name"] : '-';
                $nestedData[] = isset($row["requestor"]) ? $row["requestor"] : '-';
                $nestedData[] = $row['ro_cost_total'].' '.$currency[$row['currency']];
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
                if(array_key_exists('Repair Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Repair Orders'];
                }
            }
            $linkedOrderId = isset($this->request->query['linkedOrderId']) ? $this->request->query['linkedOrderId'] : '0';
            $linkedOrderType = isset($this->request->query['linkedOrderType']) ? $this->request->query['linkedOrderType'] : '';
            $parentLinkedType = isset($this->request->query['parentLinkedType']) ? $this->request->query['parentLinkedType'] : '';

            $InventoryRepairOrders = $this->InventoryRepairOrders->newEntity();
            $InventoryROItems = $this->InventoryROItems->newEntity();

            $invid = isset($this->request->query['invid']) ? $this->request->query['invid'] : '';
            
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();//print_r($postData);exit;

                if(empty($postData['qty']) || count($postData['qty']) == 0){
                    $this->Flash->error(__('Repair Order require at least 1 line item.'));
                    return $this->redirect( Router::url( $this->referer(), true ) );
                }else{
                    for($i=0; $i<count($postData['qty']); $i++){
                        if(isset($postData['inventory_id'][$i]) && $postData['inventory_id'][$i] == 'Select a part number'){
                            $this->Flash->error(__('LineItems['.$i.'] Physical Inventory is a required field.'));
                            return $this->redirect( Router::url( $this->referer(), true ) );
                        }else if(empty($postData['inventory_id'][$i]) && isset($postData['noninventory_item'][$i]) && empty($postData['noninventory_item'][$i])){
                            $this->Flash->error(__('LineItems['.$i.'] Physical Inventory is a required field.'));
                            return $this->redirect( Router::url( $this->referer(), true ) );
                        }else if(empty($postData['qty'][$i])){
                            $this->Flash->error(__('LineItems['.$i.'] Qty Requested is a required field.'));
                            return $this->redirect( Router::url( $this->referer(), true ) );
                        }
                    }
                }
                $invrepairduplicatecheck = $this->InventoryRepairOrders->find('all')->where(['ro_number'=>$postData['ro_number']])->select($this->InventoryRepairOrders);
                
                $errorflag = 0;
                if($invrepairduplicatecheck->count() > 0){
                    $this->Flash->error(__('Repair order with the number '.$postData['ro_number'].' already exists. It may be inactive.'));

                    return $this->redirect( Router::url( $this->referer(), true ) );
                }else if(!isset($postData['qty'])){
                    $errorflag = 1;

                    $this->Flash->error(__('Repair Orders require at least one line item.'));
                }else{
                    if(count($postData['inventory_id']) !== count(array_unique($postData['inventory_id']))){
                        $errorflag = 1;

                        $this->Flash->error(__('Duplicate line item found.'));
                    }
                }
                
                if($errorflag == 1){
                    return $this->redirect( Router::url( $this->referer(), true ) );
                }
                
                $subTotalCost = 0;
                for($i=0; $i<count($postData['qty']); $i++){
                    $subTotalCost += $postData['cost'][$i];
                }

                $totalCost = $subTotalCost+$postData['ro_tax_amount']+$postData['ro_shipping'];
                
                $postData['ro_cost_subtotal'] = $subTotalCost;
                $postData['ro_cost_total'] = $totalCost;

                $postData['added_by'] = $this->Auth->user('id');//echo "<pre>";print_r($postData);exit;
                $InventoryRepairOrders = $this->InventoryRepairOrders->patchEntity($InventoryRepairOrders, $postData);
                if ($this->InventoryRepairOrders->save($InventoryRepairOrders)) {
                    $ro_id = $InventoryRepairOrders->id;

                    $this->Inventory->saveInventoryPurchaseOrderLinks($parentLinkedType, $linkedOrderType, $linkedOrderId, $ro_id);
                    
                    for($i=0; $i<count($postData['qty']); $i++){
                        $InventoryROItems = $this->InventoryROItems->newEntity();
                        
                        $inventory_id = isset($postData['inventory_id'][$i]) ? $postData['inventory_id'][$i] : '';

                        $invrequestpost = [];
                        $invrequestpost['inventory_ro_id'] = $ro_id;
                        $invrequestpost['inventory_id'] = $inventory_id;
                        $invrequestpost['noninventory_item'] = isset($postData['noninventory_item'][$i]) && !empty($postData['noninventory_item'][$i]) ? $postData['noninventory_item'][$i] : '';
                        $invrequestpost['qty'] = $postData['qty'][$i];
                        $invrequestpost['eta'] = isset($postData['eta'][$i]) ? $postData['eta'][$i] : '';
                        $invrequestpost['cost'] = $postData['cost'][$i];
                        $invrequestpost['location_id'] = isset($postData['location_id'][$i]) ? $postData['location_id'][$i] : '';
                        
                        $InventoryROItems = $this->InventoryROItems->patchEntity($InventoryROItems, $invrequestpost);
                        
                        $this->InventoryROItems->save($InventoryROItems);  
                        
                        $subTotalCost += $postData['cost'][$i];

                        /*if(!empty($inventory_id)){
                            $inventories = $this->Inventories->newEntity();
                            $inventories = $this->Inventories->get($inventory_id);
                            $inventoryarr = [];
                            $inventoryarr['status'] = '7';
                            $inventories = $this->Inventories->patchEntity($inventories, $inventoryarr);
                        
                            $this->Inventories->save($inventories); 
                        }*/
                    }

                    //save data to repair order history table
                    $this->InventoryHistory->saveInventoryRepairOrderHistory($InventoryRepairOrders);

                    $this->InventoryAttachment->saveInvROAttachment($ro_id, $postData);
                    $this->Flash->success(__('The repair order has been saved.'));
                    return $this->redirect(['action' => 'detail', $ro_id]);
                }

                $this->Flash->error(__('The repair order could not be saved. Please, try again.'));
            }
            
            $countries = $this->Address->getCountryList();
            $states = '';
            $inventoryvendors = $this->InventoryVendors->newEntity();
            $inventoryaddresses = $this->InventoryAddresses->newEntity();
            $inventoryaddressesarr = $this->InventoryAddresses->find('all')->where(['added_by'=>$this->Auth->user('id')]);
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

            $InventoryROItems = [];
            $invtype = '';
            if(!empty($invid)){
                $InventoryROItems = $this->Inventories->find('all')->where(['id'=>$invid])->select(['id', 'location_id']);
                $invtype = 'invitem';
            }
            $inventoryitems = $this->Inventory->getAllInventoriesDetails();
            $location = $this->Inventory->getAllLocations();

            $inventoryroid = $this->InventoryRepairOrders->find('all', array('limit'=>1, 'order'=>'InventoryRepairOrders.id DESC', 'recursive' => 1,))->select(['id'])->last();
            if(!empty($inventoryroid)){
                $inventoryroid = $inventoryroid->id+1;
                $remaingtoaddzero = 6-strlen($inventoryroid);
                $ro_number = '';
                for($i=1; $i<=$remaingtoaddzero; $i++){
                    $ro_number .= '0';
                }
                $ro_number .= $inventoryroid;
            }else{
                $ro_number = '000001';
            }
            
            $this->set(compact('InventoryRepairOrders', 'actionItems', 'countries', 'states', 'inventoryvendors', 'inventoryaddresses', 'billingaddress', 'shippingaddress', 'vendor', 'manufacturer', 'InventoryROItems', 'inventoryitems', 'invtype', 'invid', 'location', 'ro_number'));
        }

        public function edit($id = null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('Repair Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Repair Orders'];
                }
            }

            $InventoryRepairOrders = $this->InventoryRepairOrders->find('all')->where(['id'=>$id])->select()->first($this->InventoryRepairOrders);
            if(empty($InventoryRepairOrders)){
                return $this->redirect(['action' => 'index']);
            }

            //echo "<pre>";print_r($InventoryRepairOrders);exit;
            if ($this->request->is(['patch', 'post', 'put'])) {
                $errorflag = 0;
                $postData = $this->request->getData();

                for($i=0; $i<count($postData['qty']); $i++){
                    if(isset($postData['inventory_id'][$i]) && $postData['inventory_id'][$i] == 'Select a part number'){
                        $this->Flash->error(__('LineItems['.$i.'] Physical Inventory is a required field.'));
                        return $this->redirect( Router::url( $this->referer(), true ) );
                    }else if(empty($postData['inventory_id'][$i]) && isset($postData['noninventory_item'][$i]) && empty($postData['noninventory_item'][$i])){
                        $this->Flash->error(__('LineItems['.$i.'] Physical Inventory is a required field.'));
                        return $this->redirect( Router::url( $this->referer(), true ) );
                    }else if(empty($postData['qty'][$i])){
                        $this->Flash->error(__('LineItems['.$i.'] Qty Requested is a required field.'));
                        return $this->redirect( Router::url( $this->referer(), true ) );
                    }
                }

                $postData['updated_by'] = $this->Auth->user('id');
                $invrepairduplicatecheck = $this->InventoryRepairOrders->find('all')->where(['ro_number'=>$postData['ro_number'], 'id !='=>$id])->select($this->InventoryRepairOrders);
                
                $errorflag = 0;
                if($invrepairduplicatecheck->count() > 0){
                    $this->Flash->error(__('Repair order with the number '.$postData['ro_number'].' already exists. It may be inactive.'));

                    $errorflag = 1;
                }else if(!isset($postData['qty'])){
                    $errorflag = 1;

                    $this->Flash->error(__('Repair Orders require at least one line item.'));
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

                $totalCost = $subTotalCost+$postData['ro_tax_amount']+$postData['ro_shipping'];
                
                $postData['ro_cost_subtotal'] = $subTotalCost;
                $postData['ro_cost_total'] = $totalCost;

                $InventoryRepairOrders = $this->InventoryRepairOrders->patchEntity($InventoryRepairOrders, $postData);

                if ($this->InventoryRepairOrders->save($InventoryRepairOrders)) {
                    //delete all po items
                    $connection = ConnectionManager::get('default');
                    $results = $connection
                    ->execute(
                        'delete from inventory_ro_items WHERE inventory_ro_id = :inventory_ro_id and id not in('."'" . implode ( "', '", $postData['itemid'] ) . "'".')',
                        ['inventory_ro_id' => $id],
                        ['created' => 'datetime']
                    );

                    $results = $connection
                    ->execute(
                        'delete from inventory_ro_receives WHERE inventory_ro_id = :inventory_ro_id and inventory_id != 0 and inventory_ro_item_id not in('."'" . implode ( "', '", $postData['itemid'] ) . "'".')',
                        ['inventory_ro_id' => $id],
                        ['created' => 'datetime']
                    );
                    
                    for($i=0; $i<count($postData['qty']); $i++){
                        $InventoryROItems = $this->InventoryROItems->newEntity();

                        $itemid = isset($postData['itemid'][$i]) ? $postData['itemid'][$i] : '';
                        if(!empty($itemid)){
                            $InventoryROItems = $this->InventoryROItems->get($itemid);
                        }

                        $inventory_id = isset($postData['inventory_id'][$i]) ? $postData['inventory_id'][$i] : '';

                        $invrequestpost = [];
                        $invrequestpost['inventory_ro_id'] = $id;
                        $invrequestpost['inventory_id'] = $inventory_id;
                        $invrequestpost['noninventory_item'] = isset($postData['noninventory_item'][$i]) && !empty($postData['noninventory_item'][$i]) ? $postData['noninventory_item'][$i] : '';
                        $invrequestpost['qty'] = $postData['qty'][$i];
                        $invrequestpost['eta'] = isset($postData['eta'][$i]) ? $postData['eta'][$i] : '';
                        $invrequestpost['cost'] = $postData['cost'][$i];
                        $invrequestpost['location_id'] = isset($postData['location_id'][$i]) ? $postData['location_id'][$i] : '';
                        
                        $InventoryROItems = $this->InventoryROItems->patchEntity($InventoryROItems, $invrequestpost);
                        
                        $this->InventoryROItems->save($InventoryROItems);

                    }
                    $this->InventoryAttachment->saveInvROAttachment($id, $postData);
                    $this->Flash->success(__('The repair order has been saved.'));
                    return $this->redirect(['action' => 'detail', $id]); 
                }

                $this->Flash->error(__('The repair order could not be saved. Please, try again.'));
            }
            
            $countries = $this->Address->getCountryList();
            $states = '';
            $inventoryvendors = $this->InventoryVendors->newEntity();
            $inventoryaddresses = $this->InventoryAddresses->newEntity();
            $inventoryaddressesarr = $this->InventoryAddresses->find('all')->where(['added_by'=>$this->Auth->user('id')]);
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

            $InventoryROItems = $this->Inventory->getInventoryRODetailsByROId($id);

            $inventoryitems = $this->Inventory->getAllInventoriesDetails();
            $location = $this->Inventory->getAllLocations();
            $attachments = $this->InventoryAttachment->getInvROAttachedFiles($id);

            $inventorypoitemsrec = $this->Inventory->getInventoryROReceivedItem($id);
            //echo "<pre>";print_r($inventorypoitemsrec);exit;
            $invitmreceived = $inventorypoitemsrec['invitmreceived'];
            
            $this->set(compact('InventoryRepairOrders', 'actionItems', 'countries', 'states', 'inventoryvendors', 'inventoryaddresses', 'billingaddress', 'shippingaddress', 'vendor', 'manufacturer', 'InventoryROItems', 'inventoryitems', 'location', 'attachments', 'invitmreceived'));
        }

        public function detail($id = null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Repair Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Repair Orders'];
                }
            }
            
            $InventoryRepairOrders = $this->InventoryRepairOrders->find('all')->where(['id'=>$id])->select()->first($this->InventoryRepairOrders);
            if(empty($InventoryRepairOrders)){
                return $this->redirect(['action' => 'index']);
            }

            $postData = $this->request->getData();

            if ($this->request->is(['patch', 'post', 'put']) && isset($postData['tracking_number'])) {

                for($i=0; $i<count($postData['tracking_number']); $i++){
                    $InventoryROItems = $this->InventoryROItems->newEntity();

                    $itemid = isset($postData['itemid'][$i]) ? $postData['itemid'][$i] : '';
                    if(!empty($itemid)){
                        $InventoryROItems = $this->InventoryROItems->get($itemid);
                    }

                    $invrequestpost = [];
                    $invrequestpost['inventory_ro_id'] = $id;
                    $invrequestpost['tracking_number'] = $postData['tracking_number'][$i];
                    
                    $InventoryROItems = $this->InventoryROItems->patchEntity($InventoryROItems, $invrequestpost);
                    
                    $this->InventoryROItems->save($InventoryROItems); 
                }
                
                $this->InventoryAttachment->saveInvPOAttachment($id, $postData);
                $this->Flash->success(__('The repair order has been saved.'));

                return $this->redirect(['action' => 'detail', $id]); 
            }

            
            $InventoryROItems = $this->Inventory->getInventoryRODetailsByROId($id);
            $is_inventory_ro_exist = 0;
            foreach($InventoryROItems as $invroitems){
                if(!empty($invroitems->inventory_id)){
                    $is_inventory_ro_exist = 1;
                }
            }

            $inventorypoitemsrec = $this->Inventory->getInventoryROReceivedItem($id);
            $inventoryporeceivedarr = $inventorypoitemsrec['inventoryporeceivedarr'];
            $invitmreceived = $inventorypoitemsrec['invitmreceived'];

            $invitmandreceivearr = $this->Inventory->getCountROReceivedAndROItem($id);
            $isallitemreceived = 0;
            if($invitmandreceivearr['invroreccount'] == $invitmandreceivearr['invroitmcount']){
                $isallitemreceived = 1;
            }

            //print_r($invitmreceived);exit;
            $vendors = '';
            if(!empty($InventoryRepairOrders->vendor)){
                $vendors = $this->InventoryVendors->get($InventoryRepairOrders->vendor);
            }

            $inventorybillingaddress = $this->InventoryAddresses->get($InventoryRepairOrders->bill_to_address);
            $inventoryshippingaddress = '';
            if(!empty($InventoryRepairOrders->ship_to_address)){
                $inventoryshippingaddress = $this->InventoryAddresses->get($InventoryRepairOrders->ship_to_address);
            }

            $countries = $this->Address->getCountryList();
            $attachments = $this->InventoryAttachment->getInvROAttachedFiles($id);

            //link other order
            $linkorderdata = $this->Inventory->getLinkOrdersData($id, '2');
            //echo "<pre>";print_r($linkorderdata);exit;
            $inventoryrohistories = $this->InventoryRepairOrderHistories->find('all')
                                    ->where(['user_id'=>$this->Auth->user('id'), 'inventory_repair_order_id'=>$id])
                                    ->select($this->InventoryRepairOrderHistories)->select(['users.email'])
                                    ->join([
                                        'users' => [
                                            'table' => 'users',
                                            'type' => 'INNER',
                                            'conditions' => 'users.id = InventoryRepairOrderHistories.user_id',
                                        ]
                                    ])->order(['InventoryRepairOrderHistories.id'=>'DESC']);
            
            //echo "<pre>";print_r($InventoryRepairOrders);exit;
            $this->set(compact('InventoryRepairOrders', 'actionItems', 'InventoryROItems', 'inventorybillingaddress', 'inventoryshippingaddress', 'countries', 'vendors', 'attachments', 'invitmreceived', 'inventoryporeceivedarr', 'invitmreceived', 'isallitemreceived', 'inventoryrohistories', 'is_inventory_ro_exist', 'linkorderdata'));
        }

        public function inventoryROItemDropDown(){
            
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                
                $inventoryitems = $this->Inventory->getAllInventoriesDetails();

                $location = $this->Inventory->getAllLocations();
                
                $currencyarr = unserialize(CURRENCY);

                $this->set('location', $location);
                $this->set('inventoryitems', $inventoryitems);
                $this->set('invtype', $postData['invtype']);
                $this->set('pocurrency', $currencyarr[$postData['currency']]);
                
                $this->layout = 'ajax';
                $this->render("/Element/Inventory/inventory_ro_item_add");
            }
        }

        public function updateRepairOrderStatus($id=null){
            $this->request->allowMethod(['post', 'delete']);
            $id = $_POST['id'];
            $invenotryrepairorders = $this->InventoryRepairOrders->get($id);
            
            try {
                $postData = array();
                $status = $_POST['status'];
                $ro_status = $_POST['ro_status'];
                
                if(!empty($ro_status)){
                    if($invenotryrepairorders->ro_status == '3'){
                        $postData['ro_status'] = '0';
                    }else{
                        $postData['ro_status'] = $ro_status;
                    }
                }else if(!empty($status)){
                    $postData['status'] = $status;
                }
                $postData['updated_by'] = $this->Auth->user('id');
                $invenotryrepairorders = $this->InventoryRepairOrders->patchEntity($invenotryrepairorders, $postData);
                if ($this->InventoryRepairOrders->save($invenotryrepairorders)) {
                    if($ro_status == '3'){
                        $msg = 'Canceled repair order request `'.$invenotryrepairorders->ro_number.'`.';

                        $inventoryrepairorderitems = $this->InventoryROItems->find('all')->where(['inventory_id IS NOT'=>NULL, 'inventory_ro_id'=>$id])->select($this->InventoryROItems);
                        foreach($inventoryrepairorderitems as $invitm){
                            $inventories = $this->Inventories->newEntity();
                            $inventories = $this->Inventories->get($invitm->inventory_id);
                            
                            if(!empty($inventories->location_id)){
                                $inventorylocationarr = $this->InventoryLocations->get($inventories->location_id);
                            }
                            $inventoryarr = [];
                            $inventoryarr['status'] = '1';
                            $inventoryarr['updated_by'] = $this->Auth->user('id');
                            $inventories = $this->Inventories->patchEntity($inventories, $inventoryarr);
                            
                            $this->Inventories->save($inventories); 

                            //transaction history data save
                            $inventoryvendors = $this->InventoryVendors->get($invenotryrepairorders->vendor);
                            
                            $from_description = 'Repair Order '.$invenotryrepairorders->ro_number;
                            $to_description = isset($inventorylocationarr->location_name) ? $inventorylocationarr->location_name : '';
                            
                            $type = 'Cancel';
                            
                            $otherparamaterarr = array(
                                                        'from_description'=>$from_description,
                                                        'to_description'=>$to_description,
                                                        'type'=>$type,
                                                        'qty'=>$inventories->qty,
                                                        'from_location_id'=>$inventories->location_id,
                                                        'from_status'=>$inventories->status,
                                                        'to_status'=>$inventories->status,
                                                        'repair_order_id'=>$invenotryrepairorders->id
                                                    );
                            $this->Inventory->saveInventoryTransactionHistory($inventories, $otherparamaterarr);
                        }
                    }else if($ro_status == '1'){
                        $msg = 'Reopen repair order request `'.$invenotryrepairorders->ro_number.'`.';
                    }else if($ro_status == '2'){
                        $msg = 'Sent repair order request `'.$invenotryrepairorders->ro_number.'`.';
                        $inventoryrepairorderitems = $this->InventoryROItems->find('all')->where(['inventory_id IS NOT'=>NULL, 'inventory_ro_id'=>$id])->select($this->InventoryROItems);
                        foreach($inventoryrepairorderitems as $invitm){
                            $inventories = $this->Inventories->newEntity();
                            $inventories = $this->Inventories->get($invitm->inventory_id);
                            $oldstatus = $inventories->ro_status;
                            if(!empty($inventories->location_id)){
                                $inventorylocationarr = $this->InventoryLocations->get($inventories->location_id);
                            }
                            $inventoryarr = [];
                            $inventoryarr['status'] = '7';
                            $inventoryarr['updated_by'] = $this->Auth->user('id');
                            $inventories = $this->Inventories->patchEntity($inventories, $inventoryarr);
                            
                            $this->Inventories->save($inventories); 

                            //transaction history data save
                            $inventoryvendors = $this->InventoryVendors->get($invenotryrepairorders->vendor);
                            
                            $from_description = isset($inventorylocationarr->location_name) ? $inventorylocationarr->location_name : '';
                            $to_description = 'Sent to Vendor '.$inventoryvendors->name.'  for Repair Order '.$invenotryrepairorders->ro_number;
                            $type = 'Shipped';
                            
                            $otherparamaterarr = array(
                                                        'from_description'=>$from_description,
                                                        'to_description'=>$to_description,
                                                        'type'=>$type,
                                                        'qty'=>$inventories->qty,
                                                        'from_location_id'=>$inventories->location_id,
                                                        'from_status'=>$oldstatus,
                                                        'to_status'=>$inventories->status,
                                                        'repair_order_id'=>$invenotryrepairorders->id
                                                    );
                            $this->Inventory->saveInventoryTransactionHistory($inventories, $otherparamaterarr);
                                
                        }
                    }else if($status == '0'){
                        $msg = 'Invenotry request deactivated `'.$invenotryrepairorders->ro_number.'`.';
                    }else if($status == '1'){
                        $msg = 'Invenotry request activated `'.$invenotryrepairorders->ro_number.'`.';
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

        //Generate InventoryRepairOrders report pdf
        public function generateInvROPdf()
        {
            $postData = $this->request->data;
            $mainHtml = '';
            $flHtml = '';
            
            $flRes = $this->Inventory->InventoryROPDFData($postData);
            if(!empty($flRes)) {
                $flHtml = $flRes;
            } else {
                $flHtml = '<tr><td colspan="16" style="text-align: center;">No Records Found for that Repair Order.</td></tr>';
            }

            $mainHtml .= '<header>
            <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td style="font-size: 14px; font-weight: normal; text-align:center;"><span style="font-weight: bold;">REPAIR ORDERS</span></td>
                    </tr>
                    <tr>
                        <td style="font-size: 10px; font-weight: normal; text-align:center;"><span>A list of repair orders for a given filter criteria.</span></td>
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
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Vendor</th>
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
            $fileName = "InventoryRepairOrders".date('YmdHis').".pdf";
            $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
            if($mainHtml) {
                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
            
        }

        public function generateInvRODetPdf()
        {
            $postData = $this->request->data;
            $mainHtml = '';
            $flHtml = '';
            
            $id = $postData['id'];
            $InventoryRepairOrders = $this->InventoryRepairOrders->get($id);

            $status = '';
                
            if($InventoryRepairOrders->ro_status == '1'){
                $status = 'Partial Received';
            }else if($InventoryRepairOrders->ro_status == '0'){
                $status = 'Draft';
            }else if($InventoryRepairOrders->ro_status == '3'){
                $status = 'Canceled';
            }else if($InventoryRepairOrders->ro_status == '2'){
                $status = 'Shipped To Vendor';
            }else if($InventoryRepairOrders->ro_status == '4'){
                $status = 'Close';
            }

            $mainHtml .= '<header>
                                <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td style="font-size: 14px; font-weight: normal; text-align:center;"><span style="font-weight: bold;">REPAIR ORDERS</span></td>
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
                                            <td>'.$InventoryRepairOrders->ro_number.'</td>
                                            <td>'.$InventoryRepairOrders->po_date.'</td>
                                            <td>'.$status.'</td>
                                            </tr>
                                            <tr>
                                            <td><b>Account Code</b></td>
                                            <td><b>Reference</b></td>
                                            <td><b>Contact</b></td>
                                            </tr>
                                            <tr>
                                            <td>'.$InventoryRepairOrders->account_code.'</td>
                                            <td>'.$InventoryRepairOrders->reference.'</td>
                                            <td>'.$InventoryRepairOrders->contact.'</td>
                                            </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </header>';
            $vendor_name = '';
            if(!empty($InventoryRepairOrders->vendor)){
                $vendors = $this->InventoryVendors->get($InventoryRepairOrders->vendor);
                $vendor_name = $vendors->name;
            }

            $inventorybillingaddress = $this->InventoryAddresses->get($InventoryRepairOrders->bill_to_address);
            $shipping_address = '';
            if(!empty($InventoryRepairOrders->ship_to_address)){
                $inventoryshippingaddress = $this->InventoryAddresses->get($InventoryRepairOrders->ship_to_address);
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
                                    <td>'.(!empty($InventoryRepairOrders->ship_via) ? $shipviaarr[$InventoryRepairOrders->ship_via] : '').'</td>
                                    </tr>
                                </tbody>
                        </table>';

            
            $InventoryROItems = $this->InventoryROItems->find('all')->where(['InventoryROItems.inventory_ro_id'=>$id])->select($this->InventoryROItems)->select(['invitms.name', 'invitms.part_number','invloc.location_name'])
            ->join([
                'invitms' => [
                    'table' => 'inventory_items',
                    'type' => 'LEFT',
                    'conditions' => 'invitms.id = InventoryROItems.inventory_id',
                ],
                'invloc' => [
                    'table' => 'inventory_locations',
                    'type' => 'left',
                    'conditions' => 'invloc.id = InventoryROItems.location_id',
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
            $shipping = $InventoryRepairOrders->po_shipping;
            $salesTax = $InventoryRepairOrders->po_tax_amount;
            $currencyName = $currency[$InventoryRepairOrders->currency];

            foreach($InventoryROItems as $row){
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

        public function bulkInvROAttachmentUpload(){
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
                    $foldername = 'inventoryroitems';
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

        public function deleteInvROAttachment(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Repair Orders', $actionStatus))
                {
                    $actionItems = $actionStatus['Repair Orders'];
                }
            }
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['id'])){
                    $attachments = $this->InventoryAttachment->deleteInvROAttachment($postData['id']);

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
                $this->layout = 'ajax';
                $postData = $this->request->getData();

                $invenotryporeceives = $this->InventoryROReceives->newEntity();
                $inventoryporecdata = [];

                $inventory_ro_id = $postData['inventory_po_id'];

                $inventoryporecdata['inventory_ro_id']      = $inventory_ro_id;
                $inventoryporecdata['inventory_ro_item_id'] = $postData['inventory_po_item_id'];
                $inventoryporecdata['inventory_id']         = 0;
                $inventoryporecdata['received']             = $postData['received'];
                $inventoryporecdata['added_by']             = $this->Auth->user('id');
                
                $invenotryporeceives = $this->InventoryROReceives->patchEntity($invenotryporeceives, $inventoryporecdata);

                if ($this->InventoryROReceives->save($invenotryporeceives)) {
                    $countdet = $this->Inventory->getCountROReceivedAndROItem($inventory_ro_id);
                    
                    $ro_status = '1';
                    if($countdet['invroreccount'] == $countdet['invroitmcount']){
                        $ro_status = '4';
                    }

                    $InventoryRepairOrders = $this->InventoryRepairOrders->newEntity();
                    $InventoryRepairOrders = $this->InventoryRepairOrders->get($inventory_ro_id);
                    $invpodata = [];
                    $invpodata['ro_status'] = $ro_status;
                    $invpodata['updated_by'] = $this->Auth->user('id');
                    $InventoryRepairOrders = $this->InventoryRepairOrders->patchEntity($InventoryRepairOrders, $invpodata);

                    $this->InventoryRepairOrders->save($InventoryRepairOrders);
                    
                    $invreceived = array('received'=>$postData['received']);
                    $result = array('status'=>'success', 'message'=>"Saved successfully.", 'invreceived'=>$invreceived);
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                
                echo json_encode($result);die;
            }
        }

        public function receive($inventory_ro_id, $inventory_ro_item_id=null){
            $InventoryRepairOrders = $this->InventoryRepairOrders->newEntity();
            $InventoryRepairOrders = $this->InventoryRepairOrders->find('all')->where(['id'=>$inventory_ro_id])->select($this->InventoryRepairOrders)->first();
            if(empty($InventoryRepairOrders)){
                return $this->redirect(['action' => 'index']);
            }
            
            $wherecond = ['InventoryROItems.inventory_ro_id'=>$inventory_ro_id, 'InventoryROItems.inventory_id IS NOT'=>NULL];
            if(!empty($inventory_ro_item_id)){
                $wherecond['InventoryROItems.id'] = $inventory_ro_item_id;
            }
            
            $InventoryROItems = $this->InventoryROItems->find()->where($wherecond)->select($this->InventoryROItems)->select(['invitms.id', 'invitms.name', 'invitms.part_number', 'invitms.is_this_item_serialized', 'inv.serial_no', 'inv.cost', 'inv.id'])
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
                ]
            ]);
            $inventoryroitemcount = $InventoryROItems->count();
            if($inventoryroitemcount == 0){
                return $this->redirect(['action' => 'index']);
            }

            $invroitemrecarr = $this->Inventory->getInventoryROReceivedItem($inventory_ro_id);
            $inventoryporeceivedarr = $invroitemrecarr['inventoryporeceivedarr'];
            $invitemnotrec = [];
            foreach($InventoryROItems as $invitem){
                //if(!isset($inventoryporeceivedarr[$invitem['id']])){
                    $invitemnotrec[] = $invitem;
               // }
            }
            $invitemnotreccount = count($invitemnotrec);
            //echo "<pre>";print_r($invitemnotrec);exit;
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();//echo "<pre>";print_r($postData);exit;

                for($i=0; $i<count($postData['inventory_item_id']); $i++){
                    $invenotries = $this->Inventories->newEntity();
                    $inventory_id = $postData['inventory_id'][$i];
                    
                    $inventories = $this->Inventories->get($inventory_id);
                    
                    $inventorydata = [];
                    $inventorydata['inventory_item_id'] = $postData['inventory_item_id'][$i];
                    $inventorydata['location_id']       = $postData['location_id'][$i];
                    $inventorydata['warranty_expire']   = $postData['warranty_expire'][$i];
                    $inventorydata['expiration']        = $postData['expiration'][$i];
                    $inventorydata['received']          = date("Y-m-d");
                    $inventorydata['notes']             = $postData['notes'][$i];
                    $inventorydata['currency']          = $postData['currency'][$i];
                    $inventorydata['cost']              = $postData['cost'][$i];
                    $inventorydata['account_code']      = $postData['account_code'][$i];
                    $inventorydata['months_new']        = (isset($postData['months_new'][$i]) ? $postData['months_new'][$i] : '');
                    $inventorydata['months_overhaul']   = (isset($postData['months_overhaul'][$i]) ? $postData['months_overhaul'][$i] : '');
                    $inventorydata['months_repair']     = (isset($postData['months_repair'][$i]) ? $postData['months_repair'][$i] : '');
                    $inventorydata['hours_new']         = (isset($postData['hours_new'][$i]) ? $postData['hours_new'][$i] : '');
                    $inventorydata['hours_overhaul']    = (isset($postData['hours_overhaul'][$i]) ? $postData['hours_overhaul'][$i] : '');
                    $inventorydata['hours_repair']      = (isset($postData['hours_repair'][$i]) ? $postData['hours_repair'][$i] : '');
                    $inventorydata['landings_new']      = (isset($postData['landings_new'][$i]) ? $postData['landings_new'][$i] : '');
                    $inventorydata['landings_overhaul'] = (isset($postData['landings_overhaul'][$i]) ? $postData['landings_overhaul'][$i] : '');
                    $inventorydata['landings_repair']   = (isset($postData['landings_repair'][$i]) ? $postData['landings_repair'][$i] : '');
                    $inventorydata['cycles_new']        = (isset($postData['cycles_new'][$i]) ? $postData['cycles_new'][$i] : '');
                    $inventorydata['cycles_overhaul']   = (isset($postData['cycles_overhaul'][$i]) ? $postData['cycles_overhaul'][$i] : '');
                    $inventorydata['cycles_repair']     = (isset($postData['cycles_repair'][$i]) ? $postData['cycles_repair'][$i] : '');
                    $inventorydata['added_by']          = $this->Auth->user('id');
                    $inventorydata['updated_by']          = $this->Auth->user('id');
                    $inventorydata['qty']               = isset($postData['qty'][$i]) ? $postData['qty'][$i] : 1;
                    $inventorydata['status']            = '1';
                    
                    $inventories = $this->Inventories->patchEntity($inventories, $inventorydata);
                                
                    if ($this->Inventories->save($inventories)) {
                        $counts = $i+1;
                        if(isset($postData['filenames-'.$counts][$i])){
                            $fileattarr = [];
                            $fileattarr['filenames'][$i] = $postData['filenames-'.$counts][$i];
                            $fileattarr['filesize'][$i] = $postData['filesize-'.$counts][$i];
                            $fileattarr['files'][$i] = $postData['files-'.$counts][$i];

                            $this->InventoryAttachment->saveAttachment($inventory_id, $fileattarr);
                        }

                        $invenotryporeceives = $this->InventoryROReceives->newEntity();
                        $inventoryporecdata = [];

                        $inventoryporecdata['inventory_ro_id'] = $postData['inventory_ro_id'][$i];
                        $inventoryporecdata['inventory_ro_item_id'] = $postData['inventory_ro_item_id'][$i];
                        $inventoryporecdata['received'] = 1;
                        $inventoryporecdata['inventory_id'] = $inventory_id;
                        $inventoryporecdata['added_by'] = $this->Auth->user('id');

                        $invenotryporeceives = $this->InventoryROReceives->patchEntity($invenotryporeceives, $inventoryporecdata);
                        
                        $this->InventoryROReceives->save($invenotryporeceives);

                        //transaction history data save
                        $inventoryitems = $this->InventoryItems->get($inventories->inventory_item_id);
                        $locationarr = $this->InventoryLocations->get($postData['location_id'][$i]);
                        
                        $from_description = 'Received from Repair Order '.$InventoryRepairOrders->ro_number;
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
                                                    'shipping_order_id'=>$inventory_ro_id
                                                );
                        $this->Inventory->saveInventoryTransactionHistory($inventories, $otherparamaterarr);
                    }
                }
                $countdet = $this->Inventory->getCountROReceivedAndROItem($inventory_ro_id);
                
                //update po status
                $ro_status = '1';
                if($countdet['invroreccount'] == $countdet['invroitmcount']){
                    $ro_status = '4';
                }

                $InventoryRepairOrders = $this->InventoryRepairOrders->newEntity();
                $InventoryRepairOrders = $this->InventoryRepairOrders->get($inventory_ro_id);
                $invpodata = [];
                $invpodata['ro_status'] = $ro_status;
                $invpodata['updated_by'] = $this->Auth->user('id');
                $InventoryRepairOrders = $this->InventoryRepairOrders->patchEntity($InventoryRepairOrders, $invpodata);

                $this->InventoryRepairOrders->save($InventoryRepairOrders);

                $this->Flash->success(__('The inventory has been saved.'));
                return $this->redirect(['action' => 'detail', $inventory_ro_id]);

            }

            $inventoryitemsarr = $this->InventoryItems->find('all');
            $invitemdropdown = [];
            foreach($inventoryitemsarr as $val){
                $invitemdropdown[$val['id']] = $val['name'].' (PN: '.$val['part_number'].')';
            }
            $location = $this->Inventory->getAllLocations();
            
            $this->set(compact('InventoryRepairOrders', 'invitemnotrec', 'invitemdropdown', 'location', 'invitemnotreccount'));
        }

        public function bulkInvRORecAttachmentUpload(){
            $postData = $this->request->data;
            if(!empty($postData['ids'])) 
            {
                
                $isvalidfile = 1;
                $arr_ext = array('pdf','txt');
                $fldname = $postData['ids'];
                $temp = $postData[$fldname]['tmp_name'];
                $name = $postData[$fldname]['name'];
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

        public function exportRepairLineItems() {
            $dataTable = '';
            $dataTable .='<table class="table">
                                <thead>
                                    <tr>
                                        <th>RO Number</th>
                                        <th>Div. Abbr.</th>
                                        <th>Division Name</th>
                                        <th>Status</th>
                                        <th>Vendor</th>
                                        <th>Requestor</th>
                                        <th>RO Date</th>
                                        <th>Reference</th>
                                        <th>Contact</th>
                                        <th>Tax Amount</th>
                                        <th>Shipping Amount (Cost)</th>
                                        <th>Subtotal</th>
                                        <th>Total Cost</th>
                                        <th>Ship To</th>
                                        <th>Bill To</th>
                                        <th>Ship Via</th>
                                        <th>Account Code</th>
                                        <th>RO Created</th>
                                        <th>RO Last Changed</th>
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
                                    </tr>
                                </thead>
                                <tbody>';

            $query = "SELECT invroitm.id, invroitm.inventory_id, invroitm.noninventory_item, invroitm.qty, invroitm.cost, invroitm.eta, invroitm.created as inventory_ro_items_created, invroitm.modified as inventory_ro_items_modified, invroitm.status, invroitm.id, invro.ro_number, invro.ro_date, invro.contact, invro.reference, invro.requestor, invro.account_code, invro.ro_tax, invro.ro_tax_amount, invro.ro_shipping, invro.currency, invro.ro_cost_subtotal, invro.ro_cost_total, invro.ship_via, invro.created, invro.modified, invro.ro_status, invitms.name as inventory_name, invitms.part_number, invitms.added_by, invitms.updated_by, invitms.default_uom, invrovendor.name as vendor_name, invbilladdr.name as bill_to_address, invshippingaddr.name as ship_to_address, invroreceive.received FROM `inventory_repair_orders` as invro join inventory_ro_items invroitm on invro.id = invroitm.inventory_ro_id left join inventories as inv on inv.id = invroitm.inventory_id left join inventory_items as invitms on invitms.id = inv.inventory_item_id left join inventory_vendors as invrovendor on invro.vendor = invrovendor.id left join inventory_addresses as invbilladdr on invbilladdr.id = invro.bill_to_address left join inventory_addresses as invshippingaddr on invshippingaddr.id = invro.ship_to_address left join inventory_po_receives as invroreceive on invroreceive.inventory_po_item_id = invroitm.id WHERE invro.status = '1'";

            $requestData= $this->request->query;//echo "<pre>";print_r($requestData);exit;
            $cond = $this->InventoryFilter->inventoryRepairFilter($requestData);

            $columns = array(
                            0 => 'invro.ro_number',
                            1 => 'invro.reference',
                            2 => 'vendor_name',
                            3 => 'invro.requestor',
                            4 => 'invro.ro_status',
                            5 => 'ro_cost_total',
                            6 => 'invro.created',
                        );
            
            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sort = 'asc';
            
            $SQL = $query.$cond." ORDER BY $sidx $sort";//echo $SQL;exit;
            $inventoryroitems = $conn->execute( $SQL )->fetchAll('assoc');
            
            $setData = '';
            $invRepairOrderStatus = unserialize(REPAIR_ORDER_STATUS);
            $shipViaArr = unserialize(SHIP_VIA);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $currency = unserialize(CURRENCY);

            $ponumberarr = [];
            foreach($inventoryroitems as $invroitems){ //echo "<pre>";print_r($invroitems);exit;
                
                $shipvia = !empty($invroitems['ship_via']) ? $shipViaArr[$invroitems['ship_via']] : '';
                $lineitemnum = 1;
                if(in_array($invroitems['ro_number'], $ponumberarr)){
                    $lineitemnum ++;
                }else{
                    $ponumberarr[] = $invroitems['ro_number'];
                }

                $poreceivestatus = '';
                if($invroitems['ro_status'] == '3'){
                    $poreceivestatus = 'Canceled';
                }if(!empty($invroitems['received'])){
                    if(!empty($invroitems['inventory_item_id'])){
                        $poreceivestatus = 'Received';
                    }else{
                        $poreceivestatus = 'Closed';
                    }
                }else{
                    $poreceivestatus = 'Pending Shipment';
                }

                $dataTable .='
                            <tr>
                                <td>'.$invroitems['ro_number'].'</td>
                                <td></td>
                                <td></td>
                                <td>'.$invRepairOrderStatus[$invroitems['ro_status']].'</td>
                                <td>'.$invroitems['vendor_name'].'</td>
                                <td>'.$invroitems['requestor'].'</td>
                                <td>'.date('m/d/Y', strtotime($invroitems['ro_date'])).'</td>
                                <td>'.$invroitems['reference'].'</td>
                                <td>'.$invroitems['contact'].'</td>
                                <td>'.$invroitems['ro_tax_amount'].'</td>
                                <td>'.$invroitems['ro_shipping'].'</td>
                                <td>'.$invroitems['ro_cost_subtotal'].'</td>
                                <td>'.$invroitems['ro_cost_total'].'</td>
                                <td>'.$invroitems['ship_to_address'].'</td>
                                <td>'.$invroitems['bill_to_address'].'</td>
                                <td>'.$shipvia.'</td>
                                <td>'.$invroitems['account_code'].'</td>
                                <td>'.date('m/d/Y', strtotime($invroitems['created'])).'</td>
                                <td>'.(!empty($invroitems['modified']) ? date('m/d/Y', strtotime($invroitems['modified'])) : '').'</td>
                                <td>'.$lineitemnum.'</td>
                                <td>'.$invroitems['part_number'].'</td>
                                <td>'.(!empty($invroitems['inventory_id']) ? $invroitems['inventory_name'] : $invroitems['noninventory_item']).'</td>
                                <td>'.$invroitems['qty'].'</td>
                                <td>'.$poreceivestatus.'</td>
                                <td>'.(isset($invroitems['default_uom']) ? $defaultUOM[$invroitems['default_uom']] : '').'</td>
                                <td>'.$invroitems['cost'].'</td>
                                <td>'.$invroitems['eta'].'</td>
                                <td>'.date('m/d/Y', strtotime($invroitems['inventory_ro_items_created'])).'</td>
                                <td>'.(!empty($invroitems['inventory_ro_items_modified']) ? date('m/d/Y', strtotime($invroitems['inventory_ro_items_modified'])) : '').'</td>
                                <td>'.(!empty($invroitems['currency']) ? $currency[$invroitems['currency']] : '').'</td>
                                <td>'.$invroitems['status'].'</td>
                                <td>'.$invroitems['id'].'</td>
                            </tr>';
                
            }  
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=InventoryRepairWithLineItems".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }

        public function exportRepairOrderListToExcel() {
            $dataTable = '';
            $dataTable .='<table class="table">
                                <thead>
                                    <tr>
                                        <th>Order</th>
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
                                    </tr>
                                </thead>
                                <tbody>';

            $query = "SELECT invro.*, invrovendor.name as vendor_name FROM `inventory_repair_orders` as invro left join inventory_vendors as invrovendor on invro.vendor = invrovendor.id WHERE invro.status = '1'";

            $requestData = $this->request->query;//echo "<pre>";print_r($requestData);exit;
            $cond = $this->InventoryFilter->inventoryRepairFilter($requestData);

            $columns = array(
                            0 => 'invro.ro_number',
                            1 => 'invro.reference',
                            2 => 'vendor_name',
                            3 => 'invro.requestor',
                            4 => 'invro.ro_status',
                            5 => 'ro_cost_total',
                            6 => 'invro.created',
                        );
            
            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sort = 'asc';
            
            $SQL = $query.$cond." ORDER BY $sidx $sort";//echo $SQL;exit;
            $inventoryroitems = $conn->execute( $SQL )->fetchAll('assoc');
            
            $setData = '';
            $invRepairOrderStatus = unserialize(REPAIR_ORDER_STATUS);
            $shipViaArr = unserialize(SHIP_VIA);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $currency = unserialize(CURRENCY);

            $ponumberarr = [];
            foreach($inventoryroitems as $invroitems){ //echo "<pre>";print_r($invroitems);exit;
                $dataTable .='
                            <tr>
                                <td>'.$invroitems['ro_number'].'</td>
                                <td></td>
                                <td></td>
                                <td>'.$invroitems['reference'].'</td>
                                <td>'.$invroitems['vendor_name'].'</td>
                                <td>'.$invroitems['requestor'].'</td>
                                <td>'.$invRepairOrderStatus[$invroitems['ro_status']].'</td>
                                <td>'.$invroitems['account_code'].'</td>
                                <td>'.$invroitems['ro_cost_total'].'</td>
                                <td>'.(!empty($invroitems['currency']) ? $currency[$invroitems['currency']] : '').'</td>
                                <td>'.(!empty($invroitems['created']) ? date('m/d/Y', strtotime($invroitems['created'])) : '').'</td>
                            </tr>';
                
            }  
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=RepairOrders".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }
        
    }

?>