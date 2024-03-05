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

    class InventoryRequestsController extends AppController
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
                    'InventoryRequestItems',
                    'InventoryRequestHistories',
                    'InventoryPurchaseOrderLinks'
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
                if(array_key_exists('Requests', $actionStatus))
                {
                    $actionItems = $actionStatus['Requests'];
                }
                $this->set(compact('actionItems'));
            }
        }

        public function search()
        {
            $query = [];        
            
            $query['count'] = "SELECT count(invreq.id) AS count  FROM `inventory_requests` as invreq WHERE 1=1 ";

            $query['detail'] = "SELECT invreq.id, invreq.request_number, invreq.title, invreq.description, invreq.requested_by, invreq.need_by, invreq.urgency, invreq.modified, invreq.request_status, invreq.status, invreq.created FROM `inventory_requests` as invreq WHERE 1=1 ";
            
            return $query;
        }

        public function ajaxInventoryRequestSearch(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Requests', $actionStatus))
                {
                    $actionItems = $actionStatus['Requests'];
                }
            }
            $this->autoRender = false;
            $this->layout = 'ajax';
            $requestData= $this->request->data;

            $query = $this->search();

            $cond = "";

            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                parse_str($requestData['columns'][1]['search']['value'], $requestData);
            }
            $cond = $this->InventoryFilter->inventoryRequestFilter($requestData);
            $requestData= $this->request->data;

            //echo $cond;exit;
            $columns = array(
                0 => 'request_number',
                1 => 'title',
                2 => 'requested_by',
                3 => 'created',
                4 => 'need_by',
                5 => 'urgency',
                6 => 'request_status',
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
            $urgency = unserialize(URGENCY);

            $InventoryStatusHTMLHelper = new InventoryStatusHTMLHelper(new \Cake\View\View());

            foreach ( $results as $row){
                $statushtml = $InventoryStatusHTMLHelper->getInventoryRequestStatusHTML($row['status'], $row['request_status']);
                
                $nestedData= [];
                $nestedData[] = '<input type="hidden" value="'.$row['id'].'" class="chkBoxCls">';
                $nestedData[] = $row["request_number"];
                $nestedData[] = $row["title"];
                $nestedData[] = $row["requested_by"];
                $nestedData[] = date('d-M-Y', strtotime($row["created"]));
                $nestedData[] = isset($row["need_by"]) ? date('d-M-Y', strtotime($row["need_by"])) : '-';
                $nestedData[] = !empty($row["urgency"]) ? '<div id="ro-request-line-item-urgency-1" title="AOG" class="ro-request-urgency-column ro-request-is-urgent">
                <img src="../images/icons/urgenticon.png" width="20px" height="auto">&nbsp;'.$urgency[$row["urgency"]].'</div>' : '';
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
        public function create()
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Requests', $actionStatus))
                {
                    $actionItems = $actionStatus['Requests'];
                }
            }
            $linkedOrderId = isset($this->request->query['linkedOrderId']) ? $this->request->query['linkedOrderId'] : '0';
            $linkedOrderType = isset($this->request->query['linkedOrderType']) ? $this->request->query['linkedOrderType'] : '';
            $parentLinkedType = isset($this->request->query['parentLinkedType']) ? $this->request->query['parentLinkedType'] : '';
            
            $invenotryrequests = $this->InventoryRequests->newEntity();
            $invenotryrequestitems = $this->InventoryRequestItems->newEntity();
            if ($this->request->is('post')) {
                $postData = $this->request->getData();//echo "<pre>";print_r($postData);exit;
                
                if(empty($postData['qty']) || count($postData['qty']) == 0){
                    $this->Flash->error(__('Purchase Order Requests require at least 1 line item.'));
                    return $this->redirect( Router::url( $this->referer(), true ) );
                }else{
                    for($i=0; $i<count($postData['qty']); $i++){
                        if(isset($postData['inventory_item_id'][$i]) && $postData['inventory_item_id'][$i] == 'Select a part number'){
                            $this->Flash->error(__('LineItems['.$i.'] Inventory Item is a required field.'));
                            return $this->redirect($this->referer());
                        }else if(empty($postData['inventory_item_id'][$i]) && isset($postData['noninventory_item'][$i]) && empty($postData['noninventory_item'][$i])){
                            $this->Flash->error(__('LineItems['.$i.'] Non-Inventory Item Description is a required field.'));
                            return $this->redirect($this->referer());
                        }else if(empty($postData['qty'][$i])){
                            $this->Flash->error(__('LineItems['.$i.'] Quantity is a required field.'));
                            return $this->redirect($this->referer());
                        }
                    }
                }
                $invrequestduplicatecheck = $this->InventoryRequests->find('all')->where(['request_number'=>$postData['request_number']])->select($this->InventoryRequests);
                
                $errorflag = 0;
                if($invrequestduplicatecheck->count() > 0){
                    $this->Flash->error(__('Request order with the number '.$postData['request_number'].' already exists. It may be inactive.'));

                    return $this->redirect( Router::url( $this->referer(), true ) );
                }else if(!isset($postData['qty'])){
                    $errorflag = 1;

                    $this->Flash->error(__('Purchase Order Requests require at least 1 line item.'));
                }else{
                    if(count($postData['inventory_item_id']) !== count(array_unique($postData['inventory_item_id']))){
                        $errorflag = 1;

                        $this->Flash->error(__('Duplicate line item found.'));
                    }
                }
                
                if($errorflag == 1){
                    return $this->redirect( Router::url( $this->referer(), true ) );
                }
                
                $postData['added_by'] = $this->Auth->user('id');
                $invrequestpost['updated_by'] = $this->Auth->user('id');
                $invenotryrequests = $this->InventoryRequests->patchEntity($invenotryrequests, $postData);//print_r($part);exit;
                if ($this->InventoryRequests->save($invenotryrequests)) {
                    $id = $invenotryrequests->id;

                    $this->Inventory->saveInventoryPurchaseOrderLinks($parentLinkedType, $linkedOrderType, $linkedOrderId, $id);
                    
                    for($i=0; $i<count($postData['uom']); $i++){
                        $invenotryrequestitems = $this->InventoryRequestItems->newEntity();

                        $invrequestpost = [];
                        $invrequestpost['inventory_request_id'] = $id;
                        $invrequestpost['inventory_item_id'] = isset($postData['inventory_item_id'][$i]) ? $postData['inventory_item_id'][$i] : '';
                        $invrequestpost['noninventory_item'] = isset($postData['noninventory_item'][$i]) && !empty($postData['noninventory_item'][$i]) ? $postData['noninventory_item'][$i] : '';
                        $invrequestpost['qty'] = $postData['qty'][$i];
                        $invrequestpost['uom'] = !empty($postData['uom'][$i]) ? $postData['uom'][$i] : '1';
                        $invrequestpost['location_id'] = isset($postData['location_id'][$i]) ? $postData['location_id'][$i] : '';
                        $invrequestpost['added_by'] = $this->Auth->user('id');
                        $invrequestpost['updated_by'] = $this->Auth->user('id');

                        $invenotryrequestitems = $this->InventoryRequestItems->patchEntity($invenotryrequestitems, $invrequestpost);
                        $this->InventoryRequestItems->save($invenotryrequestitems);
                    }
                    
                    //save data to request history table
                    $this->InventoryHistory->saveInventoryRequestHistory($invenotryrequests);

                    $this->Flash->success(__('The inventory request has been saved.'));
                    return $this->redirect(['action' => 'detail', $id]);
                }

                //for test error while saving data to database
                /*$x = $invenotryrequests->errors();
                if ($x) {
                    debug($invenotryrequests);
                    debug($x);
                    return false;
                }*/
                
                $this->Flash->error(__('The inventory request could not be saved. Please, try again.'));
            }
            
            $manufacturer = $this->Inventory->getManufacturerList();
            $countries = $this->Address->getCountryList();

            $inventoryreqid = $this->InventoryRequests->find('all', array('limit'=>1, 'order'=>'InventoryRequests.id DESC', 'recursive' => 1,))->select(['id'])->last();
            if(!empty($inventoryreqid)){
                $inventoryreqid = $inventoryreqid->id+1;
                $remaingtoaddzero = 6-strlen($inventoryreqid);
                $request_number = '';
                for($i=1; $i<=$remaingtoaddzero; $i++){
                    $request_number .= '0';
                }
                $request_number .= $inventoryreqid;
            }else{
                $request_number = '000001';
            }

            $this->set(compact('invenotryrequests', 'actionItems', 'manufacturer', 'countries', 'request_number'));
        }

        public function edit($id = null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('Requests', $actionStatus))
                {
                    $actionItems = $actionStatus['Requests'];
                }
            }
            $invenotryrequests = $this->InventoryRequests->newEntity();
            $invenotryrequests = $this->InventoryRequests->get($id);

            if($invenotryrequests->request_status != '0'){
                $this->Flash->error(__('Can only edit requests that are pending review.'));
                return $this->redirect(['action' => 'detail', $id]);
            }

            if ($this->request->is(['patch', 'post', 'put'])) {
                
                $postData = $this->request->getData();

                if(empty($postData['qty']) || count($postData['qty']) == 0){
                    $this->Flash->error(__('Purchase Order Requests require at least 1 line item.'));
                    return $this->redirect( Router::url( $this->referer(), true ) );
                }else{
                    for($i=0; $i<count($postData['qty']); $i++){
                        if(isset($postData['inventory_item_id'][$i]) && $postData['inventory_item_id'][$i] == 'Select a part number'){
                            $this->Flash->error(__('LineItems['.$i.'] Inventory Item is a required field.'));
                            return $this->redirect($this->referer());
                        }else if(empty($postData['inventory_item_id'][$i]) && isset($postData['noninventory_item'][$i]) && empty($postData['noninventory_item'][$i])){
                            $this->Flash->error(__('LineItems['.$i.'] Non-Inventory Item Description is a required field.'));
                            return $this->redirect($this->referer());
                        }else if(empty($postData['qty'][$i])){
                            $this->Flash->error(__('LineItems['.$i.'] Quantity is a required field.'));
                            return $this->redirect($this->referer());
                        }
                    }
                }

                $invrequestduplicatecheck = $this->InventoryRequests->find('all')->where(['request_number'=>$postData['request_number'], 'id !='=>$id])->select($this->InventoryRequests);
                $errorflag = 0;
                if($invrequestduplicatecheck->count() > 0){
                    $this->Flash->error(__('Request order with the number '.$postData['request_number'].' already exists. It may be inactive.'));

                    return $this->redirect( Router::url( $this->referer(), true ) );
                }else if(!isset($postData['qty'])){
                    $errorflag = 1;

                    $this->Flash->error(__('Purchase Order Requests require at least 1 line item.'));
                }else{
                    if(count($postData['inventory_item_id']) !== count(array_unique($postData['inventory_item_id']))){
                        $errorflag = 1;

                        $this->Flash->error(__('Duplicate line item found.'));
                    }
                }
                
                if($errorflag == 1){
                    return $this->redirect( Router::url( $this->referer(), true ) );
                }

                $postData['updated_by'] = $this->Auth->user('id');
                $invenotryrequests = $this->InventoryRequests->patchEntity($invenotryrequests, $postData);//print_r($part);exit;
                if ($this->InventoryRequests->save($invenotryrequests)) {
                    $connection = ConnectionManager::get('default');
                    
                    if(isset($postData['itemid'])){
                        $results = $connection
                        ->execute(
                            'delete from inventory_request_items WHERE inventory_request_id = :inventory_request_id and id not in('."'" . implode ( "', '", $postData['itemid'] ) . "'".')',
                            ['inventory_request_id' => $id],
                            ['created' => 'datetime']
                        );
                    }
                    for($i=0; $i<count($postData['uom']); $i++){
                        $invenotryrequestitems = $this->InventoryRequestItems->newEntity();
                        $itemid = isset($postData['itemid'][$i]) ? $postData['itemid'][$i] : '';
                        if(!empty($itemid)){
                            $invenotryrequestitems = $this->InventoryRequestItems->get($itemid);
                        }
                        $invrequestpost = [];
                        $invrequestpost['inventory_request_id'] = $id;
                        $invrequestpost['inventory_item_id'] = isset($postData['inventory_item_id'][$i]) ? $postData['inventory_item_id'][$i] : '';
                        $invrequestpost['noninventory_item'] = isset($postData['noninventory_item'][$i]) && !empty($postData['noninventory_item'][$i]) ? $postData['noninventory_item'][$i] : '';
                        $invrequestpost['qty'] = $postData['qty'][$i];
                        $invrequestpost['uom'] = !empty($postData['uom'][$i]) ? $postData['uom'][$i] : '1';
                        $invrequestpost['location_id'] = isset($postData['location_id'][$i]) ? $postData['location_id'][$i] : '';
                        $invrequestpost['added_by'] = $this->Auth->user('id');
                        $invrequestpost['updated_by'] = $this->Auth->user('id');
                        
                        $invenotryrequestitems = $this->InventoryRequestItems->patchEntity($invenotryrequestitems, $invrequestpost);
                        $this->InventoryRequestItems->save($invenotryrequestitems);
                    }

                    $this->Flash->success(__('The inventory request has been saved.'));
                    return $this->redirect(['action' => 'detail', $id]);
                }
                
                $this->Flash->error(__('The Inventory request could not be saved. Please, try again.'));
            }
            
            $inventoryrequestitems = $this->InventoryRequestItems->find('all')->where(['InventoryRequestItems.inventory_request_id'=>$id])->select($this->InventoryRequestItems)->select(['invitms.name', 'invitms.part_number', 'invloc.location_name'])
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
            
            $inventoryitems = $this->InventoryItems->find('all');
            $location = $this->Inventory->getAllLocations();
            $manufacturer = $this->Inventory->getManufacturerList();
            $countries = $this->Address->getCountryList();

            $this->set(compact('invenotryrequests', 'actionItems', 'inventoryrequestitems', 'inventoryitems', 'location', 'manufacturer', 'countries'));
        }

        public function detail($id = null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Requests', $actionStatus))
                {
                    $actionItems = $actionStatus['Requests'];
                }
            }
            
            $invenotryrequests = $this->InventoryRequests->get($id);
            
            $inventoryrequestitems = $this->InventoryRequestItems->find('all')->where(['InventoryRequestItems.inventory_request_id'=>$id])->select($this->InventoryRequestItems)->select(['invitms.name', 'invitms.part_number', 'invloc.location_name'])
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

            $inventoryitems = $this->InventoryItems->find('all');
            $location = $this->Inventory->getAllLocations();

            $inventoryrequesthistories = $this->InventoryRequestHistories->find('all')
                                    ->where(['user_id'=>$this->Auth->user('id'), 'inventory_request_id'=>$id])
                                    ->select($this->InventoryRequestHistories)->select(['users.email'])
                                    ->join([
                                        'users' => [
                                            'table' => 'users',
                                            'type' => 'INNER',
                                            'conditions' => 'users.id = InventoryRequestHistories.user_id',
                                        ]
                                    ])->order(['InventoryRequestHistories.id'=>'DESC']);
            
            //link other order
            $linkorderdata = $this->Inventory->getLinkOrdersData($id, '4');
            
            $InventoryStatusHTMLHelper = new InventoryStatusHTMLHelper(new \Cake\View\View());
            $statushtml = $InventoryStatusHTMLHelper->getInventoryRequestStatusHTML($invenotryrequests->status, $invenotryrequests->request_status);
            
            $this->set(compact('invenotryrequests', 'actionItems', 'inventoryrequestitems', 'inventoryitems', 'location', 'inventoryrequesthistories', 'linkorderdata', 'statushtml'));
        }

        public function approval($id = null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Requests', $actionStatus))
                {
                    $actionItems = $actionStatus['Requests'];
                }
            }
            
            $invenotryrequests = $this->InventoryRequests->get($id);
            if(empty($invenotryrequests)){
                return $this->redirect(['action' => 'index']);
            }

            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();
                if(empty($postData['comment']) && $postData['request_status'] == 3){
                    $this->Flash->error(__('Comments can not be blank.'));
                    return $this->redirect(['action' => 'approval', $id]);
                }else if($invenotryrequests->request_status != '0'){
                    $msg = 'Already Approved/Denied purchase request `'.$invenotryrequests->request_number.'`';
                    $this->Flash->error(__($msg));
                    return $this->redirect(['action' => 'detail', $id]);
                }
                if(!empty($postData['request_status'])){
                    $postData['comment'] = $postData['comment'];
                    $postData['request_status'] = $postData['request_status'];//print_r($postData);exit;
                    $postData['updated_by'] = $this->Auth->user('id');
                    $invenotryrequests = $this->InventoryRequests->patchEntity($invenotryrequests, $postData);
                    if ($this->InventoryRequests->save($invenotryrequests)) {
                        if($postData['request_status'] == '3'){
                            $msg = 'Denied purchase request `'.$invenotryrequests->request_number.'`';
                        }else{
                            $msg = 'Approved purchase request `'.$invenotryrequests->request_number.'`';
                        }
                        $this->Flash->success(__($msg));
                        return $this->redirect(['action' => 'detail', $id]);
                    }else{
                        $this->Flash->error(__('Something went wrong.'));
                    }
                }else{
                    $this->Flash->error(__('Something went wrong.'));
                }
            }
            
            $inventoryrequestitems = $this->InventoryRequestItems->find('all')->where(['InventoryRequestItems.inventory_request_id'=>$id])->select($this->InventoryRequestItems)->select(['invitms.name', 'invitms.part_number', 'invloc.location_name'])
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

            $inventoryitems = $this->InventoryItems->find('all');
            $location = $this->Inventory->getAllLocations();

            $this->set(compact('invenotryrequests', 'actionItems', 'inventoryrequestitems', 'inventoryitems', 'location'));
        }

        public function inventoryDropDown(){
            
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $inventoryitems = $this->InventoryItems->find('all')->where(['status'=>'1']);
                $location = $this->Inventory->getAllLocations();
                
                $this->set('location', $location);
                $this->set('inventoryitems', $inventoryitems);
                $this->set('invtype', $postData['invtype']);
                
                $this->layout = 'ajax';
                $this->render("/Element/Inventory/inventory_request_item_add");
            }
        }

        public function updaterequeststatus($id=null){
            $this->request->allowMethod(['post', 'delete']);
            $id = $_POST['id'];
            $invenotryrequests = $this->InventoryRequests->get($id);
            if($_POST['requeststatus'] != '6' && $_POST['requeststatus'] != '1' && ($invenotryrequests->request_status == '2' || $invenotryrequests->request_status == '3' || $invenotryrequests->request_status == '4' || $invenotryrequests->request_status == '5')){
                $msg = 'You may not close a PO Request if it is Cancelled, Denied, PO Created, or already Closed.';
                $this->Flash->error(__($msg));

                return $this->redirect(['action' => 'detail', $id]);
            }
            try {
                $postData = array();
                $requeststatus = $_POST['requeststatus'];
                $status = $_POST['requeststatus'] == '6' ? '0' : $_POST['requeststatus'];
                
                if($requeststatus == '2' || $requeststatus == '4'){
                    $postData['request_status'] = $requeststatus;
                }else if($requeststatus == '1' || $requeststatus == '6'){
                    $postData['status'] = $status;
                }
                $postData['updated_by'] = $this->Auth->user('id');
                $invenotryrequests = $this->InventoryRequests->patchEntity($invenotryrequests, $postData);
                if ($this->InventoryRequests->save($invenotryrequests)) {
                    if($requeststatus == '2'){
                        $msg = 'Canceled purchase order request `'.$invenotryrequests->request_number.'`.';
                    }else if($requeststatus == '4'){
                        $msg = 'Closed purchase order request `'.$invenotryrequests->request_number.'`.';
                    }else if($requeststatus == '6'){
                        $msg = 'Invenotry request deactivated `'.$invenotryrequests->request_number.'`.';
                    }else if($requeststatus == '1'){
                        $msg = 'Invenotry request activated `'.$invenotryrequests->request_number.'`.';
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

        //Generate InventoryRequests report pdf
        public function generateInvReqPdf()
        {
            $postData = $this->request->data;
            $mainHtml = '';
            $flHtml = '';
            
            $flRes = $this->Inventory->InventoryRequestReportData($postData);
            if(!empty($flRes)) {
                $flHtml = $flRes;
            } else {
                $flHtml = '<tr><td colspan="16" style="text-align: center;">No Records Found for that Inventory Request.</td></tr>';
            }

            $mainHtml .= '<header>
            <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td style="font-size: 14px; font-weight: normal; text-align:center;"><span style="font-weight: bold;">INVENTORY REQUESTS</span></td>
                    </tr>
                    <tr>
                        <td style="font-size: 10px; font-weight: normal; text-align:center;"><span>A list of inventory request for a given filter criteria.</span></td>
                    </tr>
                </tbody>
            </table>
        </header>';

            $mainHtml .= '<table style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center; font-size:11px;" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Number</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Title</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Requested By</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Date Requested</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Date Required</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Urgency</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    '.$flHtml.'
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
            $fileName = "InventoryRequests".date('YmdHis').".pdf";
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
            $inventoryrequestorders = $this->InventoryRequests->get($id);
            $invPurchaseOrderType = unserialize(INVENTORY_PURCHASE_TYPE);

            $requeststatus = unserialize(INVENTORY_REQUEST_STATUS);
            $bar_code = Router::url(['controller' => 'InventoryRequests', 'action' => 'detail', $inventoryrequestorders->id]);

            $mainHtml .= '<header>
                                <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="font-size: 14px; font-weight: normal; text-align:center;"><span style="font-weight: bold;">INVENTORY REQUEST</span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; font-weight: normal; text-align:center; margin-top:15px;">
                                            <table style="width:100%">
                                            <tr>
                                            <td><b>Number:</b></td><td>'.$inventoryrequestorders->request_number.'</td>
                                            <td>Status:</td><td>'.$requeststatus[$inventoryrequestorders->request_status].'</td>
                                            </tr>
                                            <tr>
                                            <td>Date:</td><td>'.date('d-M-Y', strtotime($inventoryrequestorders->created)).'</td>
                                            <td>Reference</td><td>'.$inventoryrequestorders->reference.'</td>
                                            </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.$bar_code.'&choe=UTF-8" />
                                        </td>
                                    </tr>
                                    
                                </tbody>    
                            </table>
                        </header>';
            
            $urgency = unserialize(URGENCY);
            $urgencystatus = !empty($inventoryrequestorders->urgency) ? $urgency[$inventoryrequestorders->urgency] : '';
            
            $mainHtml .= '<table style="width:100%; margin:0 auto; padding:7% 0 10px 0; text-align:center; font-size:11px; clear:both;" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Title</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Description</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Requested By</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Needed By</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Urgency</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td>'.$inventoryrequestorders->title.'</td>
                                    <td>'.$inventoryrequestorders->description.'</td>
                                    <td>'.$inventoryrequestorders->requested_by.'</td>
                                    <td>'.$inventoryrequestorders->need_by.'</td>
                                    <td>'.$urgencystatus.'</td>
                                    </tr>
                                </tbody>
                        </table>';

            
            $inventoryrequestitems = $this->InventoryRequestItems->find('all')->where(['InventoryRequestItems.inventory_request_id'=>$id])->select($this->InventoryRequestItems)->select(['invitms.name', 'invitms.part_number','invloc.location_name'])
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
            
            $mainHtml .= '<table style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center; font-size:11px; clear:both;" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Item</th>
                                    <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Quantity</th>
                                    <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Location Needed</th>
                                </tr>
                            </thead>
                            <tbody>';
            $flHtml = '';
            $defaultUOM = unserialize(DEFAULT_UOM);
            
            foreach($inventoryrequestitems as $row){
                $inventoryitemname = empty($row['noninventory_item']) ? $row['invitms']["name"].' ('.$row['invitms']["part_number"].')' : $row['noninventory_item'] ;

                $flHtml .= '<tr class="flRow">
                    <td style="line-height: 2.5;">'.$inventoryitemname.'</td>
                    <td style="line-height: 2.5;">'.$row["qty"].' '.$defaultUOM[$row["uom"]].'</td>
                    <td style="line-height: 2.5;">'.$row['invloc']["location_name"].'</td>
                </tr>';
            }
            
            $mainHtml .= $flHtml.'</tbody>
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
            $fileName = "InventoryRequestDetails".date('YmdHis').".pdf";
            $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
            if($mainHtml) {
                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
            
        }

        public function exportRequestLineItems() {
            
            $dataTable = '';
            $dataTable .='<table class="table">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Part Number</th>
                                        <th>Line Item Description</th>
                                        <th>Quantity</th>
                                        <th>Unit of Measure</th>
                                        <th>Location Needed</th>
                                        <th>Line Item Created Date</th>
                                        <th>Line Item Created By</th>
                                        <th>Line Item Last Updated Date</th>
                                        <th>Line Item Last Updated By</th>
                                        <th>Div. Abbr.</th>
                                        <th>Division Name</th>
                                        <th>Date Requested</th>
                                        <th>Requested By</th>
                                        <th>Date Required</th>
                                        <th>Urgency</th>
                                        <th>Is Urgent?</th>
                                        <th>Status</th>
                                        <th>Approver Comments</th>
                                        <th>Created By</th>
                                        <th>Last Updated Date</th>
                                        <th>Last Updated By</th>
                                        <th>Is Active</th>
                                    </tr>
                                </thead>
                        <tbody>';

            $query = "SELECT invreq.request_number, invreq.title, invreq.description, invreq.requested_by, invreq.need_by, invreq.urgency, invreq.request_status, invreq.status, invreq.created as invreq_created, invreq.modified as invreq_modified, invreq.comment, invitms.name, invitms.part_number, invloc.location_name, addedby.email as reqitm_addedby_email, updatedby.email as reqitm_updatedby_email, reqcreatedby.email as reqcreatedby_email, requpdatedby.email as requpdatedby_email, invreqitm.qty, invreqitm.uom, invreqitm.created as invreqitm_created, invreqitm.modified as invreqitm_modified, invreqitm.noninventory_item, invreqitm.inventory_item_id FROM `inventory_requests` as invreq join inventory_request_items as invreqitm on invreq.id = invreqitm.inventory_request_id left join inventory_items as invitms on invreqitm.inventory_item_id = invitms.id left join inventory_locations as invloc on invloc.id = invreqitm.location_id left join users as reqcreatedby on reqcreatedby.id = invreq.added_by left join users as requpdatedby on requpdatedby.id = invreq.updated_by left join users as addedby on addedby.id = invreqitm.added_by left join users as updatedby on updatedby.id = invreqitm.updated_by  WHERE 1=1 ";

            $requestData= $this->request->query;//echo "<pre>";print_r($requestData);exit;
            
            $cond = $this->InventoryFilter->inventoryRequestFilter($requestData);      
            
            //echo $cond;exit;
            $columns = array(
                0 => 'request_number',
                1 => 'title',
                2 => 'requested_by',
                3 => 'invreq.created',
                4 => 'need_by',
                5 => 'urgency',
                6 => 'request_status',
            );
            
            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sort = 'asc';
            
            $SQL = $query.$cond." ORDER BY $sidx $sort";//echo $SQL;exit;
            $inventoryrequestitems = $conn->execute( $SQL )->fetchAll('assoc');
            
            $setData = '';  
            $urgencyStatus = unserialize(URGENCY);
            $invRequestStatus = unserialize(INVENTORY_REQUEST_STATUS);
            $defaultUOM = unserialize(DEFAULT_UOM);

            foreach($inventoryrequestitems as $invreqitems){
                $urgency = !empty($invreqitems['urgency']) ? $urgencyStatus[$invreqitems['urgency']] : '';
                
                $invitmname = !empty($invreqitems['name']) ? $invreqitems['name'] : $invreqitems['noninventory_item'];
                $reqaddedby = isset($invreqitems['reqcreatedby_email']) ? $invreqitems['reqcreatedby_email'] : '';
                $requpdatedby = isset($invreqitems['requpdatedby_email']) ? $invreqitems['reqcreatedby_email']:'';

                $dataTable .='
                            <tr>
                                <td>'.$invreqitems['request_number'].'</td>
                                <td>'.$invreqitems['title'].'</td>
                                <td>'.$invreqitems['description'].'</td>
                                <td>'.$invreqitems['part_number'].'</td>
                                <td>'.$invitmname.'</td>
                                <td>'.$invreqitems['qty'].'</td>
                                <td>'.$defaultUOM[$invreqitems['uom']].'</td>
                                <td>'.(isset($invreqitems['location_name']) ? $invreqitems['location_name'] : '').'</td>
                                <td>'.date('m/d/Y', strtotime($invreqitems['invreqitm_created'])).'</td>
                                <td>'.$invreqitems['reqitm_addedby_email'].'</td>
                                <td>'.(!empty($invreqitems['invreqitm_modified']) ? date('m/d/Y', strtotime($invreqitems['invreqitm_modified'])) : '').'</td>
                                <td>'.(isset($invreqitems['reqitm_updatedby_email']) ? $invreqitems['reqitm_updatedby_email'] : '').'</td>
                                <td></td>
                                <td></td>
                                <td>'.date('m/d/Y', strtotime($invreqitems['invreq_created'])).'</td>
                                <td>'.$invreqitems['requested_by'].'</td>
                                <td>'.date('m/d/Y', strtotime($invreqitems['need_by'])).'</td>
                                <td>'.$urgency.'</td>
                                <td>'.$invreqitems['urgency'].'</td>
                                <td>'.$invRequestStatus[$invreqitems['request_status']].'</td>
                                <td>'.$invreqitems['comment'].'</td>
                                <td>'.$reqaddedby.'</td>
                                <td>'.(isset($invreqitems['invreq_modified']) ? date('m/d/Y', strtotime($invreqitems['invreq_modified'])) : '').'</td>
                                <td>'.$requpdatedby.'</td>
                                <td>'.$invreqitems['status'].'</td>
                            </tr>';
            }  
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=InventoryRequestWithLineItems".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }

        public function exportRequestListToExcel() {
            
            $dataTable = '';
            $dataTable .='<table class="table">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Customer</th>
                                        <th>Div. Abbr.</th>
                                        <th>Division Name</th>
                                        <th>Requested By</th>
                                        <th>Date Requested</th>
                                        <th>Date Required</th>
                                        <th>Urgency</th>
                                        <th>Urgency Item</th>
                                        <th>Status</th>
                                        <th>Approver Comments</th>
                                        <th>Last Updated Date</th>
                                        <th>Last Updated By</th>
                                        <th>Is Active</th>
                                    </tr>
                                </thead>
                        <tbody>';

            $query = "SELECT invreq.request_number, invreq.title, invreq.description, invreq.requested_by, invreq.need_by, invreq.urgency, invreq.request_status, invreq.status, invreq.created as invreq_created, invreq.modified as invreq_modified, invreq.comment, updatedby.email FROM `inventory_requests` as invreq left join users as updatedby on invreq.updated_by = updatedby.id  WHERE 1=1 ";

            $requestData= $this->request->query;//echo "<pre>";print_r($requestData);exit;
            
            $cond = $this->InventoryFilter->inventoryRequestFilter($requestData);            
            
            //echo $cond;exit;
            $columns = array(
                0 => 'request_number',
                1 => 'title',
                2 => 'requested_by',
                3 => 'invreq.created',
                4 => 'need_by',
                5 => 'urgency',
                6 => 'request_status',
            );
            
            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sort = 'asc';
            
            $SQL = $query.$cond." ORDER BY $sidx $sort";//echo $SQL;exit;
            $inventoryrequestitems = $conn->execute( $SQL )->fetchAll('assoc');
            
            $setData = '';  
            $urgencyStatus = unserialize(URGENCY);
            $invRequestStatus = unserialize(INVENTORY_REQUEST_STATUS);

            foreach($inventoryrequestitems as $invreqitems){
                $urgency = !empty($invreqitems['urgency']) ? $urgencyStatus[$invreqitems['urgency']] : '';
                
                $dataTable .='
                            <tr>
                                <td>'.$invreqitems['request_number'].'</td>
                                <td>'.$invreqitems['title'].'</td>
                                <td>'.$invreqitems['description'].'</td>
                                <td>Southwest Aviation Specialties</td>
                                <td></td>
                                <td></td>
                                <td>'.$invreqitems['requested_by'].'</td>
                                <td>'.date('m/d/Y', strtotime($invreqitems['invreq_created'])).'</td>
                                <td>'.date('m/d/Y', strtotime($invreqitems['need_by'])).'</td>
                                <td>'.(!empty($invreqitems["urgency"]) ? $urgency : '').'</td>
                                <td>'.$invreqitems["urgency"].'</td>
                                <td>'.$invRequestStatus[$invreqitems['request_status']].'</td>
                                <td>'.$invreqitems["comment"].'</td>
                                <td>'.(!empty($invreqitems['invreq_modified']) ? date('m/d/Y', strtotime($invreqitems['invreq_modified'])) : '').'</td>
                                <td>'.$invreqitems["email"].'</td>
                                <td>'.$invreqitems["status"].'</td>
                            </tr>';
            }  
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=InventoryRequests".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }
        
    }

?>