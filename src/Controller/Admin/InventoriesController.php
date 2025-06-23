<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use App\View\Helper\InventoryStatusHTMLHelper;
    use Cake\Datasource\FactoryLocator;
    use Cake\ORM\Locator\LocatorAwareTrait;
    use Cake\I18n\FrozenTime;

    class InventoriesController extends AppController
    {
        protected \App\Model\Table\InventoryItemsTable $InventoryItems;
        protected \App\Model\Table\InventoryLocationsTable $InventoryLocations;
        protected \App\Model\Table\InventoryVendorsTable $InventoryVendors;
        protected \App\Model\Table\InventoryPurchaseOrdersTable $InventoryPurchaseOrders;
        protected \App\Model\Table\InventoryRepairOrdersTable $InventoryRepairOrders;
        protected \App\Model\Table\InventoryPOItemsTable $InventoryPOItems;
        protected \App\Model\Table\InventoryRequestsTable $InventoryRequests;
        protected \App\Model\Table\InventoryHistoriesTable $InventoryHistories;
        protected \App\Model\Table\InventoryTransactionHistoriesTable $InventoryTransactionHistories;

        public function initialize():void {
            parent::initialize();

            $this->InventoryItems = $this->fetchTable('InventoryItems');
            $this->InventoryLocations = $this->fetchTable('InventoryLocations');
            $this->InventoryVendors = $this->fetchTable('InventoryVendors');
            $this->InventoryPurchaseOrders = $this->fetchTable('InventoryPurchaseOrders');
            $this->InventoryRepairOrders = $this->fetchTable('InventoryRepairOrders');
            $this->InventoryPOItems = $this->fetchTable('InventoryPOItems');
            $this->InventoryRequests = $this->fetchTable('InventoryRequests');
            $this->InventoryHistories = $this->fetchTable('InventoryHistories');
            $this->InventoryTransactionHistories = $this->fetchTable('InventoryTransactionHistories');

            $this->loadComponent('Address');
            $this->loadComponent('InventoryFilter');
            $this->loadComponent('InventoryAttachment');
            $this->loadComponent('Inventory');
            $this->loadComponent('InventoryHistory');
            $this->loadComponent('AtaCode');
        }

        public function index()
        {
            ini_set('memory_limit', '1024M');
            $actionItems='';
            $reportTime='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Information Center', $actionStatus))
                {
                    $actionItems = $actionStatus['Information Center'];
                }
            }
            
            $expiredItems = 0;
            $expiringItems = 0;
            $lowStockItems = 0;
            $itemOutForRepaires = 0;
            $quarantinedItems = 0;
            $openPurchaseOrders = 0;
            $openExchangeOrders = 0;
            $openRepairOrders = 0;
            $openRequests = 0;
            $pastDueUrgntRequests = 0;

            $currentdate = date('Y-m-d');

            $invenotries = $this->Inventories->find('all')->select(['Inventories.status', 'Inventories.expiration', 'Inventories.warranty_expire', 'Inventories.received']);
            if(!empty($invenotries)){
                $statusarr = array('1', '2', '7', '9', '12');
                foreach($invenotries as $val){
                    if($val->status == '7'){
                        $itemOutForRepaires++;
                    }else if($val->status == '12'){
                        $quarantinedItems++;
                    }/*else if($val->status == '9'){
                        $openPurchaseOrders++;
                    }*/

                    if(!empty($val->expiration) && strtotime($val->expiration) <= strtotime("-1 days") && in_array($val->status, $statusarr)){
                        $expiredItems++;
                    }
                    if(!empty($val->expiration) && strtotime($val->expiration) >= strtotime(date("Y-m-d")) && strtotime($val->expiration) <= strtotime("+1 month -1 days") && in_array($val->status, $statusarr)){
                        $expiringItems++;
                    }
                }
            }
            
            $inventorypoarr = $this->InventoryPurchaseOrders->find('all')->where(['status !='=>'2'])->select(['id', 'po_type', 'po_status', 'exchange_status', 'status']);
            $postatusarr = array('0', '1', '2');
            foreach($inventorypoarr as $invpo){
                if($invpo['status'] == '1' && $invpo['po_type'] == '1' && in_array($invpo['po_status'], $postatusarr)){
                    $openPurchaseOrders++;
                }else if($invpo['status'] == '1' && $invpo['po_type'] == '2' && $invpo['exchange_status'] == '2'){
                    $openExchangeOrders++;
                }
            }
            $inventoryroarr = $this->InventoryRepairOrders->find('all')->where(['status !='=>'2', 'ro_status IN'=>array('0','1','2')])->select(['id']);
            $openRepairOrders = $inventoryroarr->count();

            $inventoryrequestarr = $this->InventoryRequests->find('all')->where(['status'=>'1', 'request_status IN'=>array('0','1')])->select(['id', 'urgency', 'need_by', 'request_status']);
            $openRequests = $inventoryrequestarr->count();
            $openrequeststatus = array('0', '1');
            foreach($inventoryrequestarr as $invrequest){
                if(in_array($invrequest['request_status'], $openrequeststatus) && (!empty($invrequest['urgency']) || strtotime($invrequest['need_by']) < strtotime($currentdate))){
                    $pastDueUrgntRequests++;
                }
            }

            $inventorypoitems = $this->InventoryPOItems->find('all')->where(['InventoryPOItems.tracking_number !='=>'', 'invpo.po_status IN'=>['1','2']])->select($this->InventoryPOItems)->select(['invpo.po_number', 'invpo.ship_via', 'invitms.name', 'invitms.part_number','vendor.name'])
            ->join([
                'invpo' => [
                    'table' => 'inventory_purchase_orders',
                    'type' => 'LEFT',
                    'conditions' => 'invpo.id = InventoryPOItems.inventory_po_id',
                ],
                'invitms' => [
                    'table' => 'inventory_items',
                    'type' => 'LEFT',
                    'conditions' => 'invitms.id = InventoryPOItems.inventory_item_id',
                ],
                'vendor' => [
                    'table' => 'inventory_vendors',
                    'type' => 'LEFT',
                    'conditions' => 'vendor.id = invpo.vendor',
                ]
            ]);

            $query = "SELECT invitm.id, invitm.name, invitm.part_number, invitm.unit_cost, invitm.currency, invitm.default_uom, CASE WHEN invloc.location_name is null THEN 'Global' ELSE invloc.location_name END as location_name, (CASE WHEN invitm.item_instock >= invitmthreshold.safety_stock_threshold OR invitmthreshold.safety_stock_threshold is null THEN invitm.safety_stock_threshold ELSE invitmthreshold.safety_stock_threshold END) as total_safety_stock_threshold, invitm.item_instock, (select count(*) from inventory_po_items where inventory_item_id = invitm.id) as ordered, invitm.capital_equipment FROM `inventory_items` as invitm left join inventory_item_thresholds as invitmthreshold on invitm.id = invitmthreshold.inventory_item_id left join inventory_locations as invloc on invitmthreshold.location_id = invloc.id WHERE invitm.status = '1' AND invitm.item_instock < invitmthreshold.safety_stock_threshold UNION SELECT invitm.id, invitm.name, invitm.part_number, invitm.unit_cost, invitm.currency, invitm.default_uom, 'Global' AS location_name, invitm.safety_stock_threshold as total_safety_stock_threshold, invitm.item_instock, (select count(*) from inventory_po_items where inventory_item_id = invitm.id) as ordered, invitm.capital_equipment FROM `inventory_items` as invitm WHERE invitm.item_instock < invitm.safety_stock_threshold";

            $conn = ConnectionManager::get('default');
            $inventoryreportitems = $conn->execute( $query )->fetchAll('assoc');
            $lowStockItems = count($inventoryreportitems);
            
            $this->set(compact('expiredItems', 'expiringItems', 'lowStockItems', 'itemOutForRepaires', 'quarantinedItems', 'openPurchaseOrders', 'openExchangeOrders', 'openRepairOrders', 'openRequests', 'pastDueUrgntRequests', 'inventorypoitems'));
        }

        public function search()
        {
            $query = [];        
            
            $requestData= $this->request->getData();

            $cond = " and inventory_item_id=".$requestData['inventory_item_id'];
            
            if( isset($requestData['columns'][2]['search']['value']) && !empty($requestData['columns'][2]['search']['value'])){
                parse_str($requestData['columns'][2]['search']['value'], $requestData);
            }

            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                $search = $requestData['columns'][1]['search']['value'];
                if($search == 1){
                    $requestData['showinactive'] = '1';
                }
            }

            if( isset($requestData['columns'][4]['search']['value']) && !empty($requestData['columns'][4]['search']['value'])){
                $qtystatus = $requestData['columns'][4]['search']['value'];
                $requestData['qtystatus'] = $qtystatus;
            }else if(!empty($requestData['defualt_status'])){
                $requestData['qtystatus'] = '1';
            }
            
            /*if( isset($requestData['columns'][2]['search']['value']) && !empty($requestData['columns'][2]['search']['value'] && $requestData['columns'][2]['search']['value'] != 'all')) {
                $cond .=" AND inv.status = '".$requestData['columns'][2]['search']['value']."'";
            }else */
            
            
            //echo $cond;exit;

            $queryData = $this->getRequest()->getQuery();
            $cond .= $this->InventoryFilter->completeInventoryFilter($requestData, $queryData);

            $query['count'] = "SELECT count(inv.id) AS count  FROM `inventories` as inv join inventory_items invitm on inv.inventory_item_id = invitm.id left join inventory_locations as invloc on inv.location_id = invloc.id WHERE 1=1 ".$cond;

            $query['detail'] = "SELECT inv.id, inv.install_to, inv.serial_no, inv.qty, inv.cost, inv.currency, invloc.location_name, inv.inventory_item_id, inv.received, inv.modified, inv.status, inv.in_holdingbox FROM `inventories` as inv join inventory_items invitm on inv.inventory_item_id = invitm.id left join inventory_locations as invloc on inv.location_id = invloc.id WHERE 1=1 ".$cond;
            
            return $query;
        }

        public function ajaxQantitiesSearch(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('InventoryItems', $actionStatus))
                {
                    $actionItems = $actionStatus['InventoryItems'];
                }
            }
            $this->autoRender = false;
            $this->viewBuilder()->setLayout('ajax');

            $query = $this->search();

            $requestData= $this->request->getData();

            $cond = "";
            if( isset($requestData['columns'][0]['search']['value']) && !empty($requestData['columns'][0]['search']['value'])){
                $search = $requestData['columns'][0]['search']['value'];
                $cond.=" AND ( serial_no LIKE '%".$search."%' OR  display_name LIKE '%".$search."%')";
            }
            
            $columns = array(
                0 => 'id',
                1 => 'serial_no',
                2 => 'qty',
                3 => 'cost',
                4 => 'inventory_locations',
                5 => 'received',
                6 => 'modified'
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
            $itemtypearr = unserialize(INVENTORY_ITEM_TYPE);
            $currency = unserialize(CURRENCY);
            $InventoryStatusHTMLHelper = new InventoryStatusHTMLHelper(new \Cake\View\View());

            foreach ( $results as $row){
                $statushtml = $InventoryStatusHTMLHelper->getInventoriesStatusHTML($row['status']);
                
                $inholdingbox = !empty($row['in_holdingbox']) ? '<img src="../../../images/icons/holdingbox.png" style="width:20px; height:20px;">' : '';
                if(empty($row['location_name']) && $row['status'] == '2'){
                    $invenotriesdata = $this->Inventory->getInventoryInstallToDetails($row['install_to']);
                    $location = $invenotriesdata['_matchingData']['InventoryItems']['name'].' (PN:'.$invenotriesdata['_matchingData']['InventoryItems']['part_number'].') (SN:'.$invenotriesdata['serial_no'].')';
                }else if(empty($row['location_name']) && $row['status'] != 7 && $row['status'] != 9){
                    $location = '<span style="color:red;">[Inactive]</span>';
                }else{
                    $location = $row["location_name"];
                }

                $nestedData= [];
                $nestedData[] = '<input type="checkbox" class="chkBoxCls" name="childcheckbox" value="'.$row['id'].'" >&nbsp;&nbsp;'.$inholdingbox;
                $nestedData[] = $row["serial_no"];
                $nestedData[] = $row["qty"];
                $nestedData[] = !empty($row["cost"]) ? '$ '.$row["cost"]*$row["qty"] : '-';
                $nestedData[] = $location;
                $nestedData[] = isset($row["received"]) ? date('d-m-Y', strtotime($row["received"])) : '-';
                if($row['status'] == '11'){
                    $invwodet = $this->Inventory->getInventroyWorkOrderDet($row["serial_no"], $row['status']);
                    $nestedData[] = !empty($invwodet) ? $invwodet['work_order_no'] : '';
                }else{
                    $nestedData[] = '';
                }
                $nestedData[] = $row["modified"];
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
        public function create($id = null)
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $invenotries = $this->Inventories->newEmptyEntity();
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                foreach($postData as $key=>$val){
                    $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                }
                
                if(!empty($postData['serial_no'])){
                    $invenotries = $this->Inventories->find('all')->where(['serial_no'=>$postData['serial_no']])->select($this->Inventories);

                    if($invenotries->count() > 0){
                        $this->Flash->error(__('Inventory with the serial/lot number '.$postData['serial_no'].' already exists. It may be inactive.'));

                        return $this->redirect( Router::url( $this->referer(), true ) );
                    }
                }
                $invenotries = $this->Inventories->newEmptyEntity();
                
                $postData['added_by'] = $authUserData['id'];
                $postData['qty'] = isset($postData['qty']) ? $postData['qty'] : 1;//print_r($postData);exit;
                $invenotries = $this->Inventories->patchEntity($invenotries, $postData);
                if ($this->Inventories->save($invenotries)) {
                    $id = $invenotries->id;
                    
                    //save to inventory item history table
                    $this->InventoryHistory->saveInventoryHistory($invenotries);

                    $this->InventoryAttachment->saveAttachment($id, $postData);

                    $this->Flash->success(__('The inventory has been saved.'));
                    return $this->redirect(['controller'=>'InventoryItems', 'action' => 'detail', $invenotries->inventory_item_id]);
                }

                //for test error while saving data to database
                /*$x = $invenotries->errors();
                if ($x) {
                    debug($invenotries);
                    debug($x);
                    return false;
                }*/
                
                $this->Flash->error(__('The inventory could not be saved. Please, try again.'));
            }
            
            $ataCode   = $this->AtaCode->getAtaCodes();
            $invenotryitems = $this->InventoryItems->get($id);
            $vendor = $this->Inventory->getVendorList();
            $location = $this->Inventory->getAllLocations();
            
            $countries = $this->Address->getCountryList();
            $states = '';
            $this->set(compact('invenotries', 'actionItems', 'location', 'ataCode', 'invenotryitems', 'countries', 'vendor', 'states'));
        }

        public function edit($id = null)
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $connection = ConnectionManager::get('default');
            $invenotries = $this->Inventories->get($id);//echo "<pre>";print_r($invenotries);exit;
            
            //$invenotries->hours_new = !empty($invenotries->hours_new) ? date("H:i", strtotime())
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();
                foreach($postData as $key=>$val){
                    $postData[$key] = preg_replace('/[\$\%]/', '', $val);
                }

                $postData['updated_by'] = $authUserData['id'];
                
                $invenotries = $this->Inventories->patchEntity($invenotries, $postData);
                if ($this->Inventories->save($invenotries)) {
                    //save attachment
                    $this->InventoryAttachment->saveAttachment($id, $postData);

                    $this->Flash->success(__('The Inventory Items been saved.'));
                    return $this->redirect(['action' => 'detail', $id]);
                }
                $this->Flash->error(__('The Inventory could not be saved. Please, try again.'));
            }
            
            $attachments = $this->InventoryAttachment->getAttachedFiles($id);
            
            $ataCode   = $this->AtaCode->getAtaCodes();
            $invenotryitems = $this->InventoryItems->get($invenotries->inventory_item_id);
            $vendor = $this->Inventory->getVendorList();
            $location = $this->Inventory->getAllLocations();

            $countries = $this->Address->getCountryList();
            $states = '';
            $this->set(compact('invenotries', 'actionItems', 'location', 'ataCode', 'invenotryitems', 'countries', 'vendor', 'attachments', 'states'));
        }

        public function detail($id = null)
        {
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            $invenotries = $this->Inventories->find()->where(['Inventories.id'=>$id])->select($this->Inventories)->select(['InventoryItems.id', 'InventoryItems.name', 'InventoryItems.part_number', 'InventoryItems.is_this_item_serialized', 'InventoryLocations.location_name', 'InventoryVendors.name'])->innerJoinWith('InventoryItems')->leftJoinWith('InventoryLocations')->leftJoinWith('InventoryVendors')->first();
            if(empty($invenotries)){
                return $this->redirect(['action' => 'index']);
            }
            $ataCode = '';
            if(!empty($invenotries->ata_chapter)){
                $ataCode   = $this->AtaCode->ataTitle($invenotries->ata_chapter);
            }
            $attachments = $this->InventoryAttachment->getAttachedFiles($id);

            $inventoryhistories = $this->InventoryHistories->find('all')
                                            ->where(['user_id'=>$authUserData['id'], 'inventory_id'=>$id])
                                            ->select($this->InventoryHistories)->select(['users.email'])
                                            ->join([
                                                'users' => [
                                                    'table' => 'users',
                                                    'type' => 'INNER',
                                                    'conditions' => 'users.id = InventoryHistories.user_id',
                                                ]
                                            ])->order(['InventoryHistories.id'=>'DESC']);

            $install_to = '';
            if($invenotries['status'] == '2'){
                $install_to = $this->Inventory->getInventoryInstallToDetails($invenotries->install_to);
            }

            $query = "SELECT inv.id, inv.install_to, inv.in_holdingbox, invitm.part_number, invitm.name, inv.serial_no, inv.qty, inv.display_name FROM `inventories` inv join inventory_items invitm on inv.inventory_item_id = invitm.id where invitm.accept_install = '1' and inv.status = '2' and invitm.status = '1' and inv.install_to = '".$id."'";
            $conn = ConnectionManager::get('default');
            $results = $conn->execute($query)->fetchAll('assoc');

            $installedcomponents = [];
            foreach($results as $key=>$val){
                $installedcomponents[$key] = $val;
                $installtopath = $this->Inventory->getInstallToInventoryDetails($val['install_to']);
                $installedcomponents[$key]['installtopath'] = $installtopath;
            }

            $transactionhistory = $this->InventoryTransactionHistories->find('all')->where(['inventory_id'=>$id])->select(['InventoryTransactionHistories.id', 'InventoryTransactionHistories.created', 'InventoryTransactionHistories.type', 'InventoryTransactionHistories.from_description', 'InventoryTransactionHistories.to_description', 'InventoryTransactionHistories.qty', 'users.email'])
            ->join([
                'users' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'users.id = InventoryTransactionHistories.added_by',
                ]
            ]);
            //echo "<pre>";print_r($transactionhistory);exit;

            $InventoryStatusHTMLHelper = new InventoryStatusHTMLHelper(new \Cake\View\View());
            $statushtml = $InventoryStatusHTMLHelper->getInventoriesStatusHTML($invenotries->status);

            $this->set(compact('invenotries', 'actionItems', 'ataCode', 'attachments', 'inventoryhistories', 'install_to', 'installedcomponents', 'transactionhistory', 'statushtml'));
        }
        
        public function bulkInventoryupload(){
            $params = $this->request->getData();
            if(!empty($params['file_name'])) 
            {
                $authUserData = $this->Authentication->getResult()->getData();

                $isvalidfile = 1;
                $arr_ext = array('pdf','txt');
                
                $attachment = $params['file_name']; 
                $name = $attachment->getClientFilename();
                $type = $attachment->getClientMediaType();
                $temp = $attachment->getStream()->getMetadata('uri');
                $size = $attachment->getSize();

                $ext = substr(strrchr($name , '.'), 1);
                
                /*if (!in_array($ext, $arr_ext)) {
                    $isvalidfile = 0;
                }*/
                
                $tblrow = '';
                if($isvalidfile){
                    $iconcss = '';
                    if($ext == 'pdf'){
                        $iconcss = 'icon-pdf';
                    }else if($ext == 'doc' || $ext == 'docx'){
                        $iconcss = 'icon-doc';
                    }else if($ext == 'xls' || $ext == 'xlsx'){
                        $iconcss = 'icon-excel';
                    }else if($ext == 'txt'){
                        $iconcss = 'icon-text';
                    }else{
                        $iconcss = 'icon-generic';
                    }

                    $filelocation = WWW_ROOT . 'inventory/' . $name;
                    if(move_uploaded_file($temp, $filelocation)) {
                        $filesize = ($size/1000).' KB';
                        $tblrow = '<tr>
                            <td class="document-name"><span class="document-management-icon '.$iconcss.'"></span><a href="'.Router::url('/', true).'inventoryitems/' . $name.'">'.$name.'</a>
                            <input type="hidden" name="filenames[]" value="'.$name.'">
                            <input type="hidden" name="filesize[]" value="'.$filesize.'">
                            </td>
                            <td>'.$filesize.'</td>
                            <td class="text-uppercase">'.date("d-M-Y").'</td>
                            <td>'.$authUserData['full_name'].'</td>
                            <td><i class="fa fa-times deleteattachment" title="Remove File"></i></td>
                        </tr>';
                        /*$tblrow = array(
                                            'filename'=> $name,
                                            'filepath'=> $filelocation,
                                            'size'=> ($params['file_name']['size']/1000).' KB',
                                            'uploaded'=> date("d-M-Y"),
                                            'uploaded_by'=> $authUserData['full_name']
                                            );*/
                    }
                    
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

        public function deleteInventoryAttachment(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('InventoryItems', $actionStatus))
                {
                    $actionItems = $actionStatus['InventoryItems'];
                }
            }
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['id'])){
                    $attachments = $this->InventoryAttachment->deleteAttachment($postData['id']);

                    $result = array('status'=>'success', 'message'=>"Deleted successfully.");
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);die;
        }

        public function updateInventoryCost(){
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                $ids = $postData['ids'];
                $data = [];
                $data['cost'] = $postData['invadjustnewcost'];
                $data['updated_by'] = $authUserData['id'];
                
                $res = $this->Inventories->updateAll(
                    $data,
                    array('id IN' => $ids)
                );
                if($res){
                    $this->Flash->success(__('The inventory has been saved.'));
                }else{
                    $this->Flash->error(__('The inventory could not be saved. Please, try again.'));
                }
            }else{
                $this->Flash->error(__('The inventory could not be saved. Please, try again.'));
            }

            $this->redirect( Router::url( $this->referer(), true ) );
        }

        public function addQuantitiesToHoldingBox(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            if ($this->request->is(['post'])) {
                $postData = $this->request->getData();
                if(!empty($postData['ids']) && !empty($postData['inventory_item_id'])){
                    $inventory_item_id = $postData['inventory_item_id'];
                    $ids = explode(',', $postData['ids']);
                    $flag = 0;
                    foreach($ids as $id){
                        $invenotries = $this->Inventories->get($id);
                        if($invenotries->in_holdingbox == '0'){
                            $postData['in_holdingbox'] = '1';
                            $postData['updated_by'] = $authUserData['id'];
                            $invenotries = $this->Inventories->patchEntity($invenotries, $postData);
                            $this->Inventories->save($invenotries);
                        }else{
                            $flag = 1;
                        }
                    }

                    if($flag == 0){
                        $this->Flash->success(__('The Qantities added to holding box.'));
                    }else{
                        $this->Flash->error(__('The Qantities already added to holding box.'));
                    }
                    
                    return $this->redirect(['controller'=>'InventoryItems', 'action' => 'detail', $inventory_item_id]);
                }else{
                    $this->Flash->error(__('Something went wrong.'));
                }
            }else{
                $this->Flash->error(__('Something went wrong.'));
            }
        }

        public function addQuantitiesToHoldingBoxAjax(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $authUserData = $this->Authentication->getResult()->getData();
                $this->viewBuilder()->setLayout('ajax');
                $postData = $this->request->getData();
                if(isset($postData['ids']) && !empty($postData['ids'])){
                    //echo "<pre>";print_r($postData['ids']);exit;
                    $flag = 0;
                    foreach($postData['ids'] as $id){
                        $invenotries = $this->Inventories->get($id);//echo "<pre>";print_r($invenotries);exit;
                        if($invenotries->in_holdingbox == '0'){
                            $postData['in_holdingbox'] = '1';
                            $postData['updated_by'] = $authUserData['id'];
                            $invenotries = $this->Inventories->patchEntity($invenotries, $postData);
                            $this->Inventories->save($invenotries);
                        }else{
                            $flag = 1;
                        }
                    }
                    if($flag == 0){
                        $result = array('status'=>'success', 'message'=>"The Qantities added to holding box.");
                    } else {
                        $result = array('status'=>'failure', 'message'=>'The Qantities already added to holding box.');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                
                echo json_encode($result);die;
            }
        }

        public function bulkTransfer(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            if ($this->request->is(['post'])) {
                $postData = $this->request->getData();
                if(!empty($postData['ids']) && !empty($postData['location_id'])){
                    
                    $ids = explode(',', $postData['ids']);
                    //echo "<pre>";print_r($postData);exit;
                    $errormsg = '';
                    $invenotriesarr = $this->Inventories->find('all')->where(['Inventories.id IN'=>$ids])->select($this->Inventories)->select(['invitms.name','invitms.part_number'])
                    ->join([
                        'invitms' => [
                            'table' => 'inventory_items',
                            'type' => 'INNER',
                            'conditions' => 'invitms.id = Inventories.inventory_item_id',
                        ]
                    ]);

                    $inventorystatus = unserialize(INVENTORY_STATUS);
                    foreach($invenotriesarr as $inventoriesdata){
                        if($inventoriesdata->status != '1' && $inventoriesdata->status != '12'){
                            $errormsg .= "Physical Inventory: ".$inventoriesdata['invitms']['name']." (PN: ".$inventoriesdata['invitms']['part_number'].") (SN: ".$inventoriesdata['serial_no'].") - Cannot transfer item ".$inventoriesdata['invitms']['name']." - ".$inventoriesdata['serial_no'].". Transfer item cannot be in '".$inventorystatus[$inventoriesdata['status']]."' status.\r\n";
                        }
                    }
                    
                    if(empty($errormsg)){
                        $this->Inventories->updateAll(
                            ['location_id' => $postData['location_id'], 'updated_by'=>$authUserData['id']],
                            ['id IN' => $ids]
                        );

                        $this->Flash->success(__('Bulk Transfer successful.'));
                    }else{
                        $this->Flash->error(__($errormsg));
                    }
                    return $this->redirect( Router::url( $this->referer(), true ));
                }else{
                    $this->Flash->error(__('Something went wrong.'));
                    return $this->redirect( Router::url( $this->referer(), true ));
                }
            }else{
                $this->Flash->error(__('Something went wrong.'));
                return $this->redirect( Router::url( $this->referer(), true ));
            }
        }

        public function bulkDiscard(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            if ($this->request->is(['post'])) {
                $postData = $this->request->getData();
                if(!empty($postData['ids'])){
                    
                    $ids = explode(',', $postData['ids']);
                    //echo "<pre>";print_r($postData);exit;
                    $errormsg = '';
                    $invenotriesarr = $this->Inventories->find('all')->where(['Inventories.id IN'=>$ids])->select($this->Inventories);

                    $discardedstatus = array('1', '5', '13', '12', '6', '8');
                    foreach($invenotriesarr as $inventoriesdata){
                        if(!in_array($inventoriesdata->status, $discardedstatus)){
                            $errormsg = "Only inventory that is in an Active, Damaged, Needs Repair, Quarantine, Unavailable or Unrepairable status may be discarded.";
                        }
                    }
                    
                    if(empty($errormsg)){
                        $this->Inventories->updateAll(
                            ['status' => '4', 'discard_reason'=>$postData['reason'], 'location_id'=>'0', 'updated_by'=>$authUserData['id']],
                            ['id IN' => $ids]
                        );

                        $this->Flash->success(__('Bulk Discard successful.'));
                    }else{
                        $this->Flash->error(__($errormsg));
                    }
                    
                    return $this->redirect( Router::url( $this->referer(), true ));
                }else{
                    $this->Flash->error(__('Something went wrong.'));
                    return $this->redirect( Router::url( $this->referer(), true ));
                }
            }else{
                $this->Flash->error(__('Something went wrong.'));
                return $this->redirect( Router::url( $this->referer(), true ));
            }
        }

        public function bulkInstall(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            if ($this->request->is(['post'])) {
                $postData = $this->request->getData();
                if(!empty($postData['ids']) && !empty($postData['install_to'])){
                    
                    $ids = explode(',', $postData['ids']);
                    //echo "<pre>";print_r($postData);exit;
                    $errormsg = '';
                    $invenotriesarr = $this->Inventories->find('all')->where(['Inventories.id IN'=>$ids])->select($this->Inventories)->select(['invitms.name','invitms.part_number'])
                    ->join([
                        'invitms' => [
                            'table' => 'inventory_items',
                            'type' => 'INNER',
                            'conditions' => 'invitms.id = Inventories.inventory_item_id',
                        ]
                    ]);

                    $inventorystatus = unserialize(INVENTORY_STATUS);
                    foreach($invenotriesarr as $inventoriesdata){
                        if($inventoriesdata->status == '2'){
                            $errormsg .= "Physical Inventory: ".$inventoriesdata['invitms']['name']." (PN: ".$inventoriesdata['invitms']['part_number'].") (SN: ".$inventoriesdata['serial_no'].") - Item is already installed to a location. Please uninstall before attempting bulk install.\r\n";
                        }else if($inventoriesdata->status != '1'){
                            $errormsg .= "Physical Inventory: ".$inventoriesdata['invitms']['name']." (PN: ".$inventoriesdata['invitms']['part_number'].") (SN: ".$inventoriesdata['serial_no'].") - Cannot install item in ".$inventorystatus[$inventoriesdata->status]." status.\r\n";
                        }
                    }
                    
                    if(empty($errormsg)){
                        $invdataupdate = [];

                        $invdataupdate['updated_by'] = $authUserData['id'];
                        $invdataupdate['install_to'] = $postData['install_to'];
                        $invdataupdate['status'] = '2';
                        $invdataupdate['conditions'] = '3';
                        $invdataupdate['location_id'] = '';
                        
                        $this->Inventories->updateAll(
                            $invdataupdate,
                            ['id IN' => $ids]
                        );

                        $this->Flash->success(__('Bulk Install successful.'));
                    }else{
                        $this->Flash->error(__($errormsg));
                    }
                    return $this->redirect( Router::url( $this->referer(), true ));
                }else{
                    $this->Flash->error(__('Something went wrong.'));
                    return $this->redirect( Router::url( $this->referer(), true ));
                }
            }else{
                $this->Flash->error(__('Something went wrong.'));
                return $this->redirect( Router::url( $this->referer(), true ));
            }
        }

        public function discard($id=null){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $invenotries = $this->Inventories->find()->where(['Inventories.id'=>$id])->select($this->Inventories)->select(['InventoryItems.name', 'InventoryItems.part_number', 'InventoryItems.is_this_item_serialized', 'InventoryItems.item_type', 'InventoryLocations.location_name'])->innerJoinWith('InventoryItems')->leftJoinWith('InventoryLocations')->first();
            
            if(empty($invenotries)){
                return $this->redirect(['action' => 'index']);
            }

            if ($this->request->is(['patch', 'post', 'put'])) {
                $inventoriesdet = $this->Inventories->get($id);
                //echo "<pre>";print_r($inventoriesdet);exit;
                $postData = $this->request->getData();
                if($postData['qty'] > $invenotries->qty){
                    $this->Flash->error(__('Attempting to remove '.$postData['qty'].' when only '.$invenotries->qty.' is available.'));
                }else{
                    if($postData['qty'] == $invenotries->qty){
                        $postData['location_id'] = '0';
                        $postData['status'] = '4';
                        $postData['conditions'] = '0';
                    }else{
                        $postData['conditions'] = '3';
                    }

                    $qtydiscarded = $postData['qty'];
                    $postData['qty'] = $invenotries->qty - $postData['qty'];
                    $postData['updated_by'] = $authUserData['id'];

                    $conditions = $postData['conditions'];

                    $inventoriesdet = $this->Inventories->patchEntity($inventoriesdet, $postData);
                    if ($this->Inventories->save($inventoriesdet)) {
                        //transaction history data save
                        
                        $itemtypelist = unserialize(INVENTORY_ITEM_TYPE);
                        $defaultUOM = unserialize(DEFAULT_UOM);
                        
                        $from_description = $invenotries->qty.' '.$defaultUOM[$invenotries->uom];
                        $to_description = $postData['qty'].' '.$defaultUOM[$invenotries->uom];
                        $type = 'Dispose';
                        $itemtype_from = $invenotries['_matchingData']['InventoryItems']['item_type'];
                        $itemtype_to = $invenotries['_matchingData']['InventoryItems']['item_type'];

                        $otherparamaterarr = array(
                                                    'from_description'=>$from_description,
                                                    'to_description'=>$to_description,
                                                    'type'=>$type,
                                                    'qty'=>$qtydiscarded,
                                                    'itemtype_from'=>$itemtype_from,
                                                    'itemtype_to'=>$itemtype_to,
                                                    'from_condition'=>$conditions,
                                                    'to_condition'=>$conditions,
                                                    'from_status'=>$invenotries->status,
                                                    'to_status'=>$inventoriesdet->status
                                                );
                        $this->Inventory->saveInventoryTransactionHistory($invenotries, $otherparamaterarr);

                        $this->Flash->success(__('Discarded '.$postData['qty'].' '.$invenotries['_matchingData']['InventoryItems']['name'].'..'));
                        return $this->redirect(['action' => 'detail', $id]);
                    }
                    $this->Flash->error(__('The Inventory could not be saved. Please, try again.'));
                }
            }

            $this->set(compact('invenotries', 'actionItems'));
        }

        public function error_correct($id=null){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $invenotries = $this->Inventories->find()->where(['Inventories.id'=>$id])->select($this->Inventories)->select(['InventoryItems.name', 'InventoryItems.part_number', 'InventoryItems.is_this_item_serialized', 'InventoryItems.item_type', 'InventoryLocations.location_name'])->innerJoinWith('InventoryItems')->leftJoinWith('InventoryLocations')->first();
            
            if(empty($invenotries)){
                return $this->redirect(['action' => 'index']);
            }

            if ($this->request->is(['patch', 'post', 'put'])) {
                $inventoriesdet = $this->Inventories->get($id);
                $postData = $this->request->getData();
                $postData['updated_by'] = $authUserData['id'];
                if($postData['status'] != '2'){
                    $postData['install_to'] = '0';
                }else if($postData['status'] != '3'){
                    $postData['consume_to'] = '0';
                }
                if($postData['status'] == '3'){
                    $postData['location_id'] = '0';
                    $postData['qty'] = '0';
                }
                
                //echo "<pre>";print_r($postData);exit;
                if(isset($postData['qty']) && $postData['qty'] > $invenotries->qty){
                    $this->Flash->error(__('Attempting to remove '.$postData['qty'].' when only '.$invenotries->qty.' is available.'));
                }else{
                    $postData['qty'] = !isset($postData['qty']) ? '1' : $postData['qty'];
                    $qty = $postData['qty'];
                    $inventoriesdet = $this->Inventories->patchEntity($inventoriesdet, $postData);
                    if ($this->Inventories->save($inventoriesdet)) {
                        //transaction history data save
                        
                        $location_to = '';
                        if(!empty($inventoriesdet->location_id)){
                            $locationarr = $this->InventoryLocations->get($inventoriesdet->location_id);
                            $location_to = $locationarr->location_name;
                        }

                        $inventoryStatus = unserialize(INVENTORY_STATUS);
                        $defaultUOM = unserialize(DEFAULT_UOM);
                        
                        $location_from = !empty($invenotries['_matchingData']['InventoryLocations']['location_name']) ? $invenotries['_matchingData']['InventoryLocations']['location_name'] : 'No Location';

                        $from_description = 'Status was '.$inventoryStatus[$invenotries->status].'. Location='.$location_from.' Qty='.$invenotries->qty.' '.$defaultUOM[$invenotries->uom];
                        $to_description = 'Status changed to '.$inventoryStatus[$inventoriesdet->status].'. Location='.$location_to.' Qty='.$inventoriesdet->qty.' '.$defaultUOM[$inventoriesdet->uom];
                        $type = 'Error Correct';
                        $itemtype_from = $invenotries['_matchingData']['InventoryItems']['item_type'];
                        $itemtype_to = $invenotries['_matchingData']['InventoryItems']['item_type'];
                        
                        $otherparamaterarr = array(
                            'from_description'=>$from_description,
                            'to_description'=>$to_description,
                            'type'=>$type,
                            'qty'=>$qty,
                            'itemtype_from'=>$itemtype_from,
                            'itemtype_to'=>$itemtype_to,
                            'from_status'=>$invenotries->status,
                            'to_status'=>$inventoriesdet->status
                        );
                        $this->Inventory->saveInventoryTransactionHistory($invenotries, $otherparamaterarr);
                        
                        $this->Flash->success(__('Physical inventory was error corrected successfully.'));

                        return $this->redirect(['action' => 'detail', $id]);
                    }
                    $this->Flash->error(__('Physical inventory could not be saved. Please, try again.'));
                }
            }
            $location = $this->Inventory->getAllLocations();

            $this->set(compact('invenotries', 'actionItems', 'location'));
        }

        public function consume($id=null){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $invenotries = $this->Inventories->find()->where(['Inventories.id'=>$id])->select($this->Inventories)->select(['InventoryItems.name', 'InventoryItems.part_number', 'InventoryItems.is_this_item_serialized', 'InventoryItems.item_type', 'InventoryLocations.location_name'])->innerJoinWith('InventoryItems')->leftJoinWith('InventoryLocations')->first();
            
            if(empty($invenotries)){
                return $this->redirect(['action' => 'index']);
            }

            if ($this->request->is(['patch', 'post', 'put'])) {
                $inventoriesdet = $this->Inventories->get($id);
                $postData = $this->request->getData();
                $postData['updated_by'] = $authUserData['id'];
                
                //echo "<pre>";print_r($postData);exit;
                if(isset($postData['qty']) && $postData['qty'] > $invenotries->qty){
                    $this->Flash->error(__('Attempting to remove '.$postData['qty'].' when only '.$invenotries->qty.' is available.'));
                }else{
                    $itemtype_from = '';
                    $itemtype_to = '';
                    $qty = $postData['qty'];
                    if($postData['qty'] == $invenotries->qty){
                        $postData['status'] = '3';
                        $postData['location_id'] = '0';

                        if(!empty($postData['consume_to'])){
                            $consumetoinvdet = $this->Inventories->get($postData['consume_to']);
                            $inventoryitemsdet = $this->InventoryItems->get($consumetoinvdet->inventory_item_id);
                            $invitemdata = [];
                            $invitemdata['item_type'] = '1';
                            $inventoryitemsdet = $this->InventoryItems->patchEntity($inventoryitemsdet, $invitemdata);
                            $this->InventoryItems->save($inventoryitemsdet);

                            $itemtype_from = $inventoryitemsdet->item_type;
                            $itemtype_to = '1';
                        }
                    }
                    $postData['qty'] = $invenotries->qty - $postData['qty'];

                    $inventoriesdet = $this->Inventories->patchEntity($inventoriesdet, $postData);
                    if ($this->Inventories->save($inventoriesdet)) {
                        //transaction history data save
                        
                        $location_to = '';
                        if(!empty($inventoriesdet->location_id)){
                            $locationarr = $this->InventoryLocations->get($inventoriesdet->location_id);
                            $location_to = $locationarr->location_name;
                        }

                        $inventoryStatus = unserialize(INVENTORY_STATUS);
                        $defaultUOM = unserialize(DEFAULT_UOM);
                        
                        $location_from = !empty($invenotries['_matchingData']['InventoryLocations']['location_name']) ? $invenotries['_matchingData']['InventoryLocations']['location_name'] : 'No Location';

                        $from_description = $location_from.' with '.$invenotries->qty.' '.$defaultUOM[$invenotries->uom].' originally available';
                        $to_description = isset($inventoryitemsdet) ? 'Consumed to '.$inventoryitemsdet->name.' (PN:'.$inventoryitemsdet->part_number.') (SN:'.$inventoriesdet->serial_no.') resulting in '.$inventoriesdet->qty.' '.$defaultUOM[$inventoriesdet->uom] : '';

                        $type = 'Consumed';
                        
                        $otherparamaterarr = array(
                            'from_description'=>$from_description,
                            'to_description'=>$to_description,
                            'type'=>$type,
                            'qty'=>$qty,
                            'itemtype_from'=>$itemtype_from,
                            'itemtype_to'=>$itemtype_to,
                            'from_status'=>$invenotries->status,
                            'to_status'=>$inventoriesdet->status
                        );
                        $this->Inventory->saveInventoryTransactionHistory($invenotries, $otherparamaterarr);

                        $this->Flash->success(__('Consumed '.$invenotries['_matchingData']['InventoryItems']['name']));

                        return $this->redirect(['action' => 'detail', $id]);
                    }
                    $this->Flash->error(__('Physical inventory could not be saved. Please, try again.'));
                }
            }
            
            $consumeto = $this->Inventory->getAllInstallToInventory($id);

            $this->set(compact('invenotries', 'actionItems', 'consumeto'));
        }

        public function install($id=null){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $invenotries = $this->Inventories->find()->where(['Inventories.id'=>$id])->select($this->Inventories)->select(['InventoryItems.name', 'InventoryItems.part_number', 'InventoryItems.is_this_item_serialized', 'InventoryItems.item_type', 'InventoryLocations.location_name'])->innerJoinWith('InventoryItems')->leftJoinWith('InventoryLocations')->first();
            
            if(empty($invenotries)){
                return $this->redirect(['action' => 'index']);
            }

            if ($this->request->is(['patch', 'post', 'put'])) {
                $inventoriesdet = $this->Inventories->get($id);
                $postData = $this->request->getData();
                $postData['updated_by'] = $authUserData['id'];
                $postData['status'] = '2';
                $postData['conditions'] = '3';
                $postData['location_id'] = '';
                
                if(isset($postData['qty']) && $postData['qty'] > $invenotries->qty){
                    $this->Flash->error(__('Quantity should be '.$invenotries->qty));
                }else{
                    $qty = $postData['qty'];
                    $inventoriesdet = $this->Inventories->patchEntity($inventoriesdet, $postData);//echo "<pre>";print_r($inventoriesdet);exit;
                    if ($this->Inventories->save($inventoriesdet)) {
                        //transaction history data save
                        
                        $location_from = !empty($invenotries['_matchingData']['InventoryLocations']['location_name']) ? $invenotries['_matchingData']['InventoryLocations']['location_name'] : 'No Location';

                        $installtodet = $this->Inventories->get($postData['install_to']);

                        $from_description = $location_from;
                        $to_description = !empty($installtodet->display_name) ? $installtodet->display_name : $installtodet->serial_no;
                        $itemtype_from = $invenotries['_matchingData']['InventoryItems']['item_type'];
                        $itemtype_to = $invenotries['_matchingData']['InventoryItems']['item_type'];

                        $type = 'Consumed';

                        $otherparamaterarr = array(
                            'from_description'=>$from_description,
                            'to_description'=>$to_description,
                            'type'=>$type,
                            'qty'=>$qty,
                            'itemtype_from'=>$itemtype_from,
                            'itemtype_to'=>$itemtype_to,
                            'from_condition'=>'3',
                            'to_condition'=>'3',
                            'from_status'=>$invenotries->status,
                            'to_status'=>$inventoriesdet->status
                        );
                        $this->Inventory->saveInventoryTransactionHistory($invenotries, $otherparamaterarr);

                        $this->Flash->success(__('Item '.$invenotries['_matchingData']['InventoryItems']['name'].' successfully installed.'));

                        return $this->redirect(['action' => 'detail', $id]);
                    }
                    $this->Flash->error(__('Physical inventory could not be saved. Please, try again.'));
                }
            }

            $installto = $this->Inventory->getAllInstallToInventory($id);

            $this->set(compact('invenotries', 'actionItems', 'installto'));
        }

        public function uninstall($id=null){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $invenotries = $this->Inventories->find()->where(['Inventories.id'=>$id])->select($this->Inventories)->select(['InventoryItems.name', 'InventoryItems.part_number', 'InventoryItems.is_this_item_serialized', 'InventoryItems.item_type'])->innerJoinWith('InventoryItems')->first();
            
            if(empty($invenotries)){
                return $this->redirect(['action' => 'index']);
            }

            if ($this->request->is(['patch', 'post', 'put'])) {
                $inventoriesdet = $this->Inventories->get($id);
                $postData = $this->request->getData();
                $postData['updated_by'] = $authUserData['id'];
                
                if($postData['status'] == '4'){
                    $postData['location_id'] = '';
                }
                
                if(isset($postData['qty']) && $postData['qty'] > $invenotries->qty){
                    $this->Flash->error(__('Quantity should be '.$invenotries->qty));
                }else{
                    $qty = $postData['qty'];
                    $install_to = $invenotries->install_to;
                    $location_to_id = $postData['location_id'];

                    $inventoriesdet = $this->Inventories->patchEntity($inventoriesdet, $postData);//echo "<pre>";print_r($inventoriesdet);exit;
                    if ($this->Inventories->save($inventoriesdet)) {
                        //transaction history data save
                        $locationdet = $this->InventoryLocations->get($location_to_id);

                        $location_to = $locationdet->location_name;

                        $invuninstalldet = $this->Inventories->find()->where(['Inventories.id'=>$install_to])->select($this->Inventories)->select(['InventoryItems.name', 'InventoryItems.part_number', 'InventoryItems.item_type'])->innerJoinWith('InventoryItems')->first();

                        $to_description = $location_to;
                        
                        $from_description = $invuninstalldet['_matchingData']['InventoryItems']['name'].' (PN:'.$invuninstalldet['_matchingData']['InventoryItems']['part_number'].') (SN:'.$invuninstalldet->serial_no.')';

                        $itemtype_from = $invenotries['_matchingData']['InventoryItems']['item_type'];
                        $itemtype_to = $invenotries['_matchingData']['InventoryItems']['item_type'];
                        $from_location_id = $invenotries->location_id;
                        $to_location_id = $invuninstalldet->location_id;

                        $type = 'Uninstall';

                        $otherparamaterarr = array(
                            'from_description'=>$from_description,
                            'to_description'=>$to_description,
                            'type'=>$type,
                            'qty'=>$qty,
                            'itemtype_from'=>$itemtype_from,
                            'itemtype_to'=>$itemtype_to,
                            'from_location_id'=>$from_location_id,
                            'to_location_id'=>$to_location_id,
                            'from_status'=>$invenotries->status,
                            'to_status'=>$inventoriesdet->status
                        );
                        $this->Inventory->saveInventoryTransactionHistory($invenotries, $otherparamaterarr);

                        $this->Flash->success(__('Item '.$invenotries['_matchingData']['InventoryItems']['name'].' successfully uninstalled.'));

                        return $this->redirect(['action' => 'detail', $id]);
                    }
                    $this->Flash->error(__('Physical inventory could not be saved. Please, try again.'));
                }
            }

            $install_to = '';
            if($invenotries['status'] == '2'){
                $install_to = $this->Inventory->getInventoryInstallToDetails($invenotries->install_to);
            }

            $locations = $this->Inventory->getAllLocations();

            $this->set(compact('invenotries', 'actionItems', 'locations', 'install_to'));
        }

        public function adjust($id=null){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $invenotries = $this->Inventories->find()->where(['Inventories.id'=>$id])->select($this->Inventories)->select(['InventoryItems.name', 'InventoryItems.part_number', 'InventoryItems.is_this_item_serialized', 'InventoryLocations.location_name'])->innerJoinWith('InventoryItems')->leftJoinWith('InventoryLocations')->first();
            
            if(empty($invenotries)){
                return $this->redirect(['action' => 'index']);
            }

            if ($this->request->is(['patch', 'post', 'put'])) {
                $inventoriesdet = $this->Inventories->get($id);
                $postData = $this->request->getData();
                $postData['updated_by'] = $authUserData['id'];
                $postData['conditions'] = '3';
                //echo "<pre>";print_r($postData);exit;
                if($invenotries['_matchingData']['InventoryItems']['is_this_item_serialized'] == '1' && $postData['qty'] > $invenotries->qty){
                    $this->Flash->error(__('Physical Inventory cannot have a quantity greater than 1 for serialized items.'));
                }else{
                    $qty = $postData['qty'];
                    $inventoriesdet = $this->Inventories->patchEntity($inventoriesdet, $postData);
                    if ($this->Inventories->save($inventoriesdet)) {
                        //transaction history data save
                        $inventoryStatus = unserialize(INVENTORY_STATUS);
                        $defaultUOM = unserialize(DEFAULT_UOM);
                        
                        $location_from = !empty($invenotries['_matchingData']['InventoryLocations']['location_name']) ? $invenotries['_matchingData']['InventoryLocations']['location_name'] : 'No Location';

                        $from_description = $invenotries->qty.' '.$defaultUOM[$invenotries->uom];
                        $to_description = $inventoriesdet->qty.' '.$defaultUOM[$inventoriesdet->uom];
                        $itemtype_from = $invenotries['_matchingData']['InventoryItems']['item_type'];
                        $itemtype_to = $invenotries['_matchingData']['InventoryItems']['item_type'];
                        $from_location_id = $invenotries->location_id;
                        $to_location_id = $inventoriesdet->location_id;

                        $type = 'Adjust';

                        $otherparamaterarr = array(
                            'from_description'=>$from_description,
                            'to_description'=>$to_description,
                            'type'=>$type,
                            'qty'=>$qty,
                            'itemtype_from'=>$itemtype_from,
                            'itemtype_to'=>$itemtype_to,
                            'from_location_id'=>$from_location_id,
                            'to_location_id'=>$to_location_id,
                            'from_condition'=>'3',
                            'to_condition'=>'3',
                            'from_status'=>$invenotries->status,
                            'to_status'=>$inventoriesdet->status
                        );
                        $this->Inventory->saveInventoryTransactionHistory($invenotries, $otherparamaterarr);

                        $this->Flash->success(__('Adjusted '.$postData['qty'].' '.$invenotries['_matchingData']['InventoryItems']['name']));

                        return $this->redirect(['action' => 'detail', $id]);
                    }
                    $this->Flash->error(__('Physical inventory could not be adjusted. Please, try again.'));
                }
            }
            $location = $this->Inventory->getAllLocations();

            $this->set(compact('invenotries', 'actionItems', 'location'));
        }

        public function transfer($id=null){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $invenotries = $this->Inventories->find()->where(['Inventories.id'=>$id])->select($this->Inventories)->select(['InventoryItems.name', 'InventoryItems.part_number', 'InventoryItems.is_this_item_serialized', 'InventoryItems.item_type', 'InventoryLocations.location_name'])->innerJoinWith('InventoryItems')->leftJoinWith('InventoryLocations')->first();
            
            if(empty($invenotries)){
                return $this->redirect(['action' => 'index']);
            }

            if ($this->request->is(['patch', 'post', 'put'])) {
                $inventoriesdet = $this->Inventories->get($id);
                $postData = $this->request->getData();
                $postData['updated_by'] = $authUserData['id'];
                $postData['conditions'] = '3';

                //echo "<pre>";print_r($postData);exit;
                if(isset($postData['qty']) && $postData['qty'] > $invenotries->qty){
                    $this->Flash->error(__('Attempting to transfer '.$postData['qty'].' when only '.$invenotries->qty.' is available.'));
                }else{
                    $original = $this->Inventories->get($id)->toArray();

                    $updateval = [];
                    if($postData['qty'] == $invenotries->qty){
                        $updateval['location_id'] = $postData['location_id'];
                        $updateval['qty'] = $postData['qty'];
                    }else{
                        $updateval['qty'] = $invenotries->qty - $postData['qty'];
                    }
                    $updateval['updated_by'] = $authUserData['id'];
                    $qty = $updateval['qty'];
                    $inventoriesdet = $this->Inventories->patchEntity($inventoriesdet, $updateval);
                    if ($this->Inventories->save($inventoriesdet)) {
                        if($postData['qty'] != $invenotries->qty){
                            
                            $copy = $this->Inventories->newEmptyEntity();
                            $copy = $this->Inventories->patchEntity($copy, $original);
                            $copy['added_by'] = $authUserData['id'];
                            $copy['updated_by'] = $authUserData['id'];
                            $transferinvntory = $copy;
                            
                            unset($copy['id']);
                            // Unset or modify all others what you need
                            $copy['qty'] = $postData['qty'];
                            $copy['location_id'] = $postData['location_id'];
                            $copy['added_by'] = $authUserData['id'];
                            $copy['updated_by'] = $authUserData['id'];
                            $this->Inventories->save($copy);
                            $id = $copy->id;
                            
                            //save to inventory item history table
                            $this->InventoryHistory->saveInventoryHistory($transferinvntory);
                        }

                        $updatedinvenotries = $this->Inventories->find()->where(['Inventories.id'=>$id])->select($this->Inventories)->select(['InventoryItems.name', 'InventoryItems.part_number', 'InventoryItems.is_this_item_serialized', 'InventoryItems.item_type', 'InventoryLocations.location_name'])->innerJoinWith('InventoryItems')->leftJoinWith('InventoryLocations')->first();


                        //transaction history data save
                        $inventoryStatus = unserialize(INVENTORY_STATUS);
                        $defaultUOM = unserialize(DEFAULT_UOM);
                        
                        $from_description = !empty($invenotries['_matchingData']['InventoryLocations']['location_name']) ? $invenotries['_matchingData']['InventoryLocations']['location_name'] : 'No Location';

                        $to_description = !empty($updatedinvenotries['_matchingData']['InventoryLocations']['location_name']) ? $updatedinvenotries['_matchingData']['InventoryLocations']['location_name'] : 'No Location';

                        $type = 'Transfer';

                        $otherparamaterarr = array(
                            'from_description'=>$from_description,
                            'to_description'=>$to_description,
                            'type'=>$type,
                            'qty'=>$qty,
                            'itemtype_from'=>$invenotries['_matchingData']['InventoryItems']['item_type'],
                            'itemtype_to'=>$updatedinvenotries['_matchingData']['InventoryItems']['item_type'],
                            'from_location_id'=>$from_description,
                            'to_location_id'=>$to_description,
                            'from_condition'=>'3',
                            'to_condition'=>'3',
                            'from_status'=>$invenotries->status,
                            'to_status'=>$updatedinvenotries->status
                        );
                        $this->Inventory->saveInventoryTransactionHistory($invenotries, $otherparamaterarr);
                        
                        $this->Flash->success(__('Item `'.$updatedinvenotries['_matchingData']['InventoryItems']['name'].'` successfully transfered to `'.$updatedinvenotries['_matchingData']['InventoryLocations']['location_name'].'`'));

                        return $this->redirect(['action' => 'detail', $id]);
                    }
                    $this->Flash->error(__('Physical inventory could not be saved. Please, try again.'));
                }
            }
            
            $location = $this->Inventory->getAllLocations();

            $this->set(compact('invenotries', 'actionItems', 'location'));
        }

        public function getInventoryLocationQtyCount(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->viewBuilder()->setLayout('ajax');
                $postData = $this->request->getData();
                if(isset($postData['inventory_item_id']) && !empty($postData['inventory_item_id'])){
                    $inventories = $this->Inventories->find('all')->where(['inventory_item_id'=>$postData['inventory_item_id'], 'location_id'=>$postData['location_id']]);
                    if (!empty($inventories)) {
                        $qtycount = $inventories->count();
                        $result = array('status'=>'success', 'message'=>"", 'qtycount'=>$qtycount);
                    } else {
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                
                echo json_encode($result);die;
            }
        }

        public function updateInventoriesStatus(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $postData = $this->request->getData();
            $id = $postData['id'];
            $invenotries = $this->Inventories->get($id);
            $oldstatus = $invenotries->status;
            if(empty($invenotries)){
                return $this->redirect(['action' => 'index']);
            }

            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData['updated_by'] = $authUserData['id'];
                $invenotries = $this->Inventories->patchEntity($invenotries, $postData);
                if ($this->Inventories->save($invenotries)) {
                    if($invenotries->status == '12'){
                        $type = 'Quarantined';
                        $this->Flash->success(__('Inventory successfully quarantined'));
                    }else{
                        $type = 'Unquarantined';
                        $this->Flash->success(__('Inventory successfully unquarantined.'));
                    }

                    //transaction history data save
                    $invenotryitmdet = $this->InventoryItems->get($invenotries->inventory_item_id);

                    $inventoryStatus = unserialize(INVENTORY_STATUS);
                    $defaultUOM = unserialize(DEFAULT_UOM);
                    
                    $from_description = 'Status was '.$inventoryStatus[$oldstatus];
                    $to_description = 'Status changed to '.$inventoryStatus[$invenotries->status];

                    $otherparamaterarr = array(
                        'from_description'=>$from_description,
                        'to_description'=>$to_description,
                        'type'=>$type,
                        'qty'=>$invenotries->qty,
                        'itemtype_from'=>$invenotryitmdet->item_type,
                        'itemtype_to'=>$invenotryitmdet->item_type,
                        'from_location_id'=>$invenotries->location_id,
                        'to_location_id'=>$invenotries->location_id,
                        'from_status'=>$oldstatus,
                        'to_status'=>$invenotries->status
                    );
                    $this->Inventory->saveInventoryTransactionHistory($invenotries, $otherparamaterarr);
                }else{
                    $this->Flash->error(__('Something went wrong. Please, try again.'));
                }
            }

            return $this->redirect(['action' => 'detail', $id]);
        }

        //Generate Flightlog report pdf
        public function generateTransactionHistoryPdf()
        {
            ini_set('memory_limit', '1024M');
            $this->autoRender = false;
            $req = $this->request->getData();
            $pdfTitle = 'TRANSACTION HISTORY REPORT';
            
            $mResults = '<div style="page-break-after: always;"></div>
                <header>
                    <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td style="width:10%; font-size:17px; font-weight: normal; text-align:left; padding:0 0 5px 0;"></td>
                                <td style="width:90%; font-size:17px; font-weight: bold; text-align:center; padding:0 0 5px 0;">'.$pdfTitle.'</td>
                            </tr>
                            <tr>
                                <td style="width:10%; font-size:10px; font-weight: normal; text-align:left; padding:0 0 5px 0;">8720 JACK BATES AVENUE</td>
                                <td style="width:90%; font-size:10px; font-weight: normal; text-align:center; padding:0 0 5px 0;">A list of all transactions for a given filter criteria</td>
                            </tr>
                            <tr>
                                <td style="width:10%; font-size:10px; font-weight: normal; text-align:left; padding:0 0 5px 0;">TULSA, OK 74132</td>
                                <td style="width:90%; font-size:17px; padding:0 0 5px 0;"></td>
                            </tr>
                            <tr>
                                <td style="width:10%; font-size:10px; font-weight: normal; text-align:left; padding:0 0 5px 0;">UNITED STATES</td>
                                <td style="width:90%; font-size:10px; text-align:center; padding:0 0 5px 0;">Capital Equipment: Show All</td>
                            </tr>
                        </tbody>
                    </table>
                </header>';
            
            $mResults .= '<table class="table table-bordered" cellpadding="0" cellspacing="0" style="width:100%; margin:0 auto; padding:0; border-collapse: collapse;border: 1px solid #c0c0c0; text-align:center;">
                    <thead>
                        <tr>
                            <th style="width:4%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0;  background:#c0c0c0;">Date</th>
                            
                            <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Description</th>
                            
                            <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Part Name / Part Number</th>
                            
                            <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Serial or Lot / Display Name</th>
                                                    
                            <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Qty</th>
    
                            <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Unit Cost</th>
    
                            <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Total Cost</th>
                            
                             <th style="width:9%;font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: normal;padding: 2px;border: 1px solid #c0c0c0; margin: 0; background:#c0c0c0;">Status</th>
                            
                        </tr>
                    </thead>
                    <tbody>';
    
            $query = "SELECT invth.id, invth.created, invth.from_description, invth.to_description, invitm.part_number, invitm.name, invitm.currency, inv.serial_no, inv.display_name, invth.qty, invth.unit_cost, (invth.qty*invth.unit_cost) as cost, invth.type FROM `inventory_transaction_histories` as invth join inventories as inv on invth.inventory_id = inv.id join inventory_items as invitm on inv.inventory_item_id = invitm.id WHERE inventory_id = '".$req['id']."'";
            $conn = ConnectionManager::get('default');
            $results = $conn->execute( $query )->fetchAll('assoc');
            
            $currencyarr = unserialize(CURRENCY);
            $transactionActionList = unserialize(TRANSACTION_ACTION_LIST);
            $totalCost = 0;
            $currencyName = '';
            foreach ( $results as $row){
                $cost = isset($row["cost"]) ? $row["cost"] : 0;
                $unitcost = !empty($row['unit_cost']) ? $row['unit_cost'] : '0';
                
                $key = !empty($row['type']) ? array_search($row['type'], array_column($transactionActionList, 'id')) : '';
                $type = !empty($key) ? $transactionActionList[$key]['name'] : '';

                $tableData = '<tr>';
                $tableData .= '<td style="font-size: 11px;font-family: Arial, Helvetica, sans-serif;font-weight:normal;padding:0;margin:0;">'.date("d-M-Y", strtotime($row['created'])).'</td>';
                $tableData .= '<td style="font-size: 11px;font-family: Arial, Helvetica, sans-serif;font-weight:normal;padding:0;margin:0;"><b>From: </b>'.$row["from_description"].'<br/> <b>To: </b>'.$row["to_description"].'</td>';
                $tableData .= '<td style="font-size: 11px;font-family: Arial, Helvetica, sans-serif;font-weight:normal;padding:0;margin:0;">'.$row["part_number"].'<br/>'.$row['name'].'</td>';
                $tableData .= '<td style="font-size: 11px;font-family: Arial, Helvetica, sans-serif;font-weight:normal;padding:0;margin:0;">'.$row['serial_no'].'<br/>'.$row['display_name'].'</td>';
                $tableData .= '<td style="font-size: 11px;font-family: Arial, Helvetica, sans-serif;font-weight:normal;padding:0;margin:0;">'.$row['qty'].'</td>';
                $tableData .= '<td style="font-size: 11px;font-family: Arial, Helvetica, sans-serif;font-weight:normal;padding:0;margin:0;">'.$unitcost.(!empty($row['currency']) ? ' '.$currencyarr[$row['currency']] : '').'</td>';
                $tableData .= '<td style="font-size: 11px;font-family: Arial, Helvetica, sans-serif;font-weight:normal;padding:0;margin:0;">'.$cost.(!empty($row['currency']) ? ' '.$currencyarr[$row['currency']] : '').'</td>';
                $tableData .= '<td style="font-size: 11px;font-family: Arial, Helvetica, sans-serif;font-weight:normal;padding:0;margin:0;">'.$type.'</td>';
                $tableData .= '</tr>';
                
                $mResults .= $tableData;
                $currencyName = (!empty($row['currency']) ? ' '.$currencyarr[$row['currency']] : '');
                $totalCost += $cost;
            }
            
            $mResults .= '<tr><td colspan="5"></td><td><b>Total Cost:</b></td><td>'.$totalCost.' '.$currencyName.'</td></tr>';
            $mResults .= '</tbody></table>';
    
            $footerHTML = '<footer>
                                <table style="width:100%; margin:0 auto; padding:15px 0 15px 0;" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="font-size:13px; text-align:center;">'.$pdfTitle.'</td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:13px; text-align:right; float:right;">
                                        Page {PAGENO} of {nbpg}
                                        </td>
                                    </tr>
                                </table>
                            </footer>';
    
            $html ='<!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="utf-8">
                        <style>
                            @page {
                                margin: 0cm 0cm;
                            }
    
                            body {
                                margin-left: .5cm;
                                margin-right: .5cm;
                                margin-bottom: 1cm;
                            }
    
                            header {
                                top: .5cm;
                                left: 0cm;
                                right: 0cm;
                                bottom: 0cm;
                                height: 3cm;
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
                        </style>
                    </head>
                    <body>
                        <main>'.$mResults.'</main>        
                    </body>
                    </html>';
            //print_r($html);die;
           
            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', '600');
            ini_set("pcre.backtrack_limit", "50000000");
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
    
            $mpdf->SetHTMLHeader($headerHTML);
            $mpdf->SetHTMLFooter($footerHTML);
            $chunks = explode("chunk", $html);
            foreach($chunks as $key => $val) {
                $mpdf->WriteHTML($val);
            }
    
            //save file on particular location
            //https://mpdf.github.io/reference/mpdf-functions/output.html
            $fileName = "TransactionHistory".date('YmdHis').".pdf";
            $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
            if($mResults) {
                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
        }

        public function printInventoriesListBarCode(){
            $postData = $this->request->getData();//print_r($postData);exit;
            $statusarr = array('1', '2', '7', '9', '12');
            if($postData['catalogprintsize'] == '1' && !empty($postData['inventoriesids'])){
                
                $html ='<!DOCTYPE html>
                <html>
                <head>
                <meta charset="utf-8">
                <style>
                div.container4 {
                    height: 10em;
                    position: relative }
                div.container4 p {
                    margin: 0;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    margin-right: -50%;
                    transform: translate(-50%, -50%) }
                </style>
                </head>
                <body>';
                
                $inventoryitems = $this->Inventories->find('all')->where(['Inventories.id IN'=>$postData['inventoriesids'], 'invitm.status'=>'1', 'Inventories.status IN'=>$statusarr])->select($this->Inventories)->select(['invitm.name', 'invitm.part_number'])
                    ->join([
                        'invitm' => [
                            'table' => 'inventory_items',
                            'type' => 'INNER',
                            'conditions' => 'invitm.id = Inventories.inventory_item_id',
                        ]
                    ]);
                
                foreach($inventoryitems as $invitems){
                    $bar_code = !empty($invitems['bar_code']) ? $invitems['bar_code'] : Router::url(['controller' => 'Inventories', 'action' => 'detail', $invitems['id']]);
                    $html .='<div class=container4><p>
                    <span style="width:200px; float:left;">';
                    $html .='<span>SN: '.$invitems['serial_no'].'</span><br/>';
                    $html .='<span>PN: '.$invitems['invitm']['part_number'].'</span><br/>
                        <span>'.$invitems['invitm']['name'].'</span>
                    </span>
                    
                    <span>
                        <img src="'.BARCODEAPIURL.'chl='.$bar_code.'" />
                    </span>
                    </p></div>';
                }
                
            }else if($postData['catalogprintsize'] == '2' && !empty($postData['inventoriesids'])){
                $invItemType = unserialize(INVENTORY_ITEM_TYPE);
                $defaultUOM = unserialize(DEFAULT_UOM);
                $invConditions = unserialize(INVENTORY_CONDITION);

                $connection = ConnectionManager::get('default');
                $statusarr = "'" . implode ( "', '", $statusarr ) . "'";
                $invquantities = $connection
                    ->execute(
                        'SELECT inv.*, invloc.location_name, invitm.part_number, invitm.name, invitm.item_type, invitm.tags, vendor.name as vendor_name, ata_codes.ata_code FROM inventories as inv join inventory_items as invitm on inv.inventory_item_id = invitm.id join inventory_locations as invloc on inv.location_id = invloc.id left join inventory_vendors as vendor on inv.vendor = vendor.id left join ata_codes on ata_codes.id = inv.ata_chapter WHERE inv.id in('.implode(',', $postData['inventoriesids']).') and inv.status in('.$statusarr.')',
                        ['created' => 'datetime']
                    )
                    ->fetchAll('assoc');

                $html = '<!DOCTYPE html>
                <html>
                <head>
                <meta charset="utf-8">
                <style>
                </style>
                </head>
                <body>';
                foreach($invquantities as $invqty){   
                    $bar_code = !empty($invqty['bar_code']) ? $invqty['bar_code'] : Router::url(['controller' => 'Inventories', 'action' => 'detail', $invqty['id']]); 
                    $html .= '<div>
                        <div style="border-bottom: 2px solid;padding-bottom: 5px;">
                            <div style="width:77%; float:left;">SOUTHWEST AVIATION SPECIALTIES</div>
                                <div style="text-align:left;">Qty : '.$invqty['qty'].' '.$defaultUOM[$invqty['uom']].'</div>
                        </div>
                        <div style="clear:both;">
                            <table style="width:100%;">
                                <tr>
                                    <td style="width:50%;">
                                        <table>
                                            <tr>
                                                <td>Desc</td>
                                                <td>:</td>
                                                <td>'.$invqty['name'].'</td>
                                            </tr>
                                            <tr>
                                                <td>P/N</td>
                                                <td>:</td>
                                                <td>'.$invqty['part_number'].'</td>
                                            </tr>
                                            <tr>
                                                <td>ATA</td>
                                                <td>:</td>
                                                <td>'.$invqty['ata_code'].'</td>
                                            </tr>
                                            <tr>
                                                <td>Rec</td>
                                                <td>:</td>
                                                <td>'.(!empty($invqty['received']) ? date('d-M-Y', strtotime($invqty['received'])) : '').'</td>
                                            </tr>
                                            <tr>
                                                <td>Vend</td>
                                                <td>:</td>
                                                <td>'.$invqty['vendor_name'].'</td>
                                            </tr>
                                            <tr>
                                                <td>Cond</td>
                                                <td>:</td>
                                                <td>'.(!empty($invqty['conditions']) ? $invConditions[$invqty['conditions']] : '').'</td>
                                            </tr>
                                            <tr>
                                                <td>Rev</td>
                                                <td>:</td>
                                                <td>'.$invqty['revision'].'</td>
                                            </tr>
                                            <tr>
                                                <td>TSN</td>
                                                <td>:</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>TSO</td>
                                                <td>:</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Loc</td>
                                                <td>:</td>
                                                <td>'.$invqty['location_name'].'</td>
                                            </tr>
                                            <tr>
                                                <td>Tags</td>
                                                <td>:</td>
                                                <td>'.$invqty['tags'].'</td>
                                            </tr>
                                            <tr>
                                                <td>Type</td>
                                                <td>:</td>
                                                <td>'.(!empty($invqty['item_type']) ? $invItemType[$invqty['item_type']] : '').'</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td colspan="3"></td>
                                            </tr>
                                            <tr>
                                                <td>SN/Lot #</td>
                                                <td>:</td>
                                                <td>'.$invqty['serial_no'].'</td>
                                            </tr>
                                            <tr>
                                                <td>Exp</td>
                                                <td>:</td>
                                                <td>'.(!empty($invqty['expiration']) ? date('d-M-Y', strtotime($invqty['expiration'])) : '').'</td>
                                            </tr>
                                            <tr>
                                                <td>Warr Exp</td>
                                                <td>:</td>
                                                <td>'.(!empty($invqty['warranty_expire']) ? date('d-M-Y', strtotime($invqty['warranty_expire'])): '').'</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td><img src="'.BARCODEAPIURL.'chl='.$bar_code.'" /></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">Printed on '.date('m/d/Y h:i:s A').'.</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                    </div>';    
                }
            }else{
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }

            $html .='</body></html>';
            //echo $html;die;
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
            $fileName = "InventoryLocationsBarcode".date('YmdHis').".pdf";
            $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
            if($html) {
                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
        }

        public function exportInventoriesToExcel() {
            
            $dataTable = '';
            $dataTable .='<table class="table">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Part Number</th>
                                        <th>Serial / Lot</th>
                                        <th>Display Name</th>
                                        <th>Div. Abbr.</th>
                                        <th>Division Name</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Qty.</th>
                                        <th>UOM</th>
                                        <th>Expiration</th>
                                        <th>Unit Cost</th>
                                        <th>Total Cost</th>
                                        <th>Exchange Cost</th>
                                        <th>Currency Code</th>
                                        <th>ATA Chapter</th>
                                        <th>Vendor</th>
                                        <th>Manufacturer</th>
                                        <th>Notes</th>
                                        <th>Warranty Expiration</th>
                                        <th>Condition</th>
                                        <th>Item Type</th>
                                        <th>Capital Equipment</th>
                                        <th>PhysicalInventoryId</th>
                                        <th>InventoryItemId</th>
                                        <th>Inventory Tags</th>
                                    </tr>
                                </thead>
                        <tbody>';

            $requestData = $this->getRequest()->getQuery();//echo "<pre>";print_r($requestData);exit;
            
            $cond = $this->InventoryFilter->completeInventoryFilter($requestData);
            
            if(isset($requestData['source']) && $requestData['source'] == 'holdingbox'){
                $cond .= ' AND inv.in_holdingbox = "1" AND inv.id in('.$requestData['ids'].')';
            }

            $inventorydata = $this->Inventory->getExportExcelInventories($requestData, $cond);

            $setData = '';  
            $itemtypearr = unserialize(INVENTORY_ITEM_TYPE);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $currency = unserialize(CURRENCY);
            $inventorystatus = unserialize(INVENTORY_STATUS);
            $invConditions = unserialize(INVENTORY_CONDITION);

            foreach($inventorydata as $invreqitems){
                if(empty($invreqitems['location_name']) && $invreqitems['status'] == '2'){
                    $invenotriesdata = $this->Inventory->getInventoryInstallToDetails($invreqitems['install_to']);
                    $location = $invenotriesdata['_matchingData']['InventoryItems']['name'].' (PN:'.$invenotriesdata['_matchingData']['InventoryItems']['part_number'].') (SN:'.$invenotriesdata['serial_no'].')';
                }else if(empty($invreqitems['location_name']) && $invreqitems['status'] != 7 && $invreqitems['status'] != 9){
                    $location = '<span style="color:red;">[Inactive]</span>';
                }else{
                    $location = $invreqitems["location_name"];
                }

                $dataTable .='
                            <tr>
                                <td>'.$invreqitems['name'].'</td>
                                <td>'.$invreqitems['part_number'].'</td>
                                <td>'.$invreqitems['serial_no'].'</td>
                                <td>'.$invreqitems['display_name'].'</td>
                                <td></td>
                                <td></td>
                                <td>'.$location.'</td>
                                <td>'.$inventorystatus[$invreqitems["status"]].'</td>
                                <td>'.$invreqitems["qty"].'</td>
                                <td>'.(!empty($invreqitems["uom"]) ? $defaultUOM[$invreqitems["uom"]] : '').'</td>
                                <td>'.$invreqitems["expiration"].'</td>
                                <td>'.$invreqitems['unit_cost'].'</td>
                                <td>'.($invreqitems["qty"]*$invreqitems['unit_cost']).'</td>
                                <td>'.$invreqitems['exchange_cost'].'</td>
                                <td>'.(!empty($invreqitems['currency']) ? $currency[$invreqitems['currency']] : '').'</td>
                                <td>'.$invreqitems['ata_code'].'</td>
                                <td>'.$invreqitems["vendor_name"].'</td>
                                <td>'.$invreqitems["manufacturer_name"].'</td>
                                <td>'.$invreqitems["notes"].'</td>
                                <td>'.$invreqitems["warranty_expire"].'</td>
                                <td>'.(!empty($invreqitems['conditions']) ? $invConditions[$invreqitems['conditions']] : '').'</td>
                                <td>'.(!empty($invreqitems['item_type']) ? $itemtypearr[$invreqitems['item_type']] : '').'</td>
                                <td>'.$invreqitems['capital_equipment'].'</td>
                                <td>'.$invreqitems['id'].'</td>
                                <td>'.$invreqitems["itemid"].'</td>
                                <td>'.$invreqitems["tags"].'</td>
                            </tr>';
            }  
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=PhysicalInventory".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }
    }

?>