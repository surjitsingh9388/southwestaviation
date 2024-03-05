<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use App\Model\Entity\InventoryPartManufacturer;

    class InventoryItemsController extends AppController
    {
        public function initialize() {
            parent::initialize();
            array_map(
                [
                    $this, 
                    'loadModel'
                ], 
                [
                    'Users',
                    'Inventories',
                    'InventoryItemThresholds',
                    'InventoryPartManufacturers',
                    'InventoryPOReceives',
                    'InventoryItemHistories',
                    'InventoryPOItems'
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
                    'InventoryHistory',
                ]
            );
            
        }

        public function index()
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
                $this->set(compact('actionItems'));
            }
        }

        public function search()
        {
            $query = [];        
            
            $query['count'] = "SELECT count(id) AS count  FROM inventory_items WHERE 1=1 ";

            $query['detail'] = "SELECT id, name, part_number, unit_cost, is_this_item_serialized, item_type, default_uom, in_holdingbox, status, item_instock, tags, (select count(*) from inventory_po_items where inventory_item_id = inventory_items.id) as ordered FROM `inventory_items` WHERE 1=1 ";
            
            return $query;
        }

        public function ajaxInventoryItemsSearch(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $this->autoRender = false;
            $this->layout = 'ajax';
            $requestData= $this->request->data;

            $query = $this->search();

            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                parse_str($requestData['columns'][1]['search']['value'], $requestData);
            }
            
            $cond = $this->InventoryFilter->inventoryCatalogFilter($requestData);
            $requestData= $this->request->data;
            //echo $cond;exit;
            $columns = array(
                0 => 'id',
                1 => 'part_number',
                2 => 'name',
                3 => 'unit_cost',
                4 => 'is_this_item_serialized',
                5 => 'item_type',
                6 => 'item_instock',
                7 => 'ordered',
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
            $sord = $requestData['order'][0]['dir'];
            $start = $requestData['start'];
            $length = $requestData['length'];

            $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
            $results = $conn->execute( $SQL )->fetchAll('assoc');
            
            $data = array();
            $itemtypearr = unserialize(INVENTORY_ITEM_TYPE);
            $defaultUOM = unserialize(DEFAULT_UOM);
            
            foreach ( $results as $row){
                $inholdingbox = !empty($row['in_holdingbox']) ? '<img src="../images/icons/holdingbox.png" style="width:20px; height:20px;">' : '';
                $inactive = $row["status"] == '0' ? '&nbsp;<span class="label label-danger">Inactive</span>' : '';
                $tagarr = !empty($row["tags"]) ? explode(',', $row["tags"]) : [];
                $tags = '';
                if(count($tagarr) > 2){
                    $tags = '<span title="'.implode(',', $tagarr).'">'.$tagarr[0].', '.$tagarr[1].' ...</span>';
                }else if(!empty($tagarr)){
                    $tagstr = implode(',', $tagarr);
                    $tags = '<span title="'.$tagstr.'">'.$tagstr.'</span>';
                }

                $nestedData= [];
                $nestedData[] = '<input type="checkbox" class="chkBoxCls noExl" name="childcheckbox" value="'.$row['id'].'" >&nbsp;&nbsp;'.$inholdingbox;
                $nestedData[] = $row["part_number"].$inactive;
                $nestedData[] = $row["name"];
                $nestedData[] = $row["unit_cost"];
                $nestedData[] = $row['is_this_item_serialized'] == 1 ? '<i class="fa fa-check"></i>' : '';
                $nestedData[] = isset($itemtypearr[$row["item_type"]]) ? $itemtypearr[$row["item_type"]] : '-';
                $nestedData[] = $tags;
                $nestedData[] = $row["item_instock"].' '.$defaultUOM[$row['default_uom']];
                $nestedData[] = $row['ordered'];

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
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $invenotryitems = $this->InventoryItems->newEntity();
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                //echo "<pre>";print_r($postData);exit;
                $inventoryitemdata = $this->InventoryItems->exists(['InventoryItems.part_number'=>$postData['part_number']]);

                if(!$inventoryitemdata){
                    $postData['added_by'] = $this->Auth->user('id');
                    $postData['updated_by'] = $this->Auth->user('id');
                    $invenotryitems = $this->InventoryItems->patchEntity($invenotryitems, $postData);//print_r($part);exit;
                    if ($this->InventoryItems->save($invenotryitems)) {
                        $id = $invenotryitems->id;
                        //save to inventory item history table
                        $this->InventoryHistory->saveInventoryItemHistory($invenotryitems);
                        
                        $this->InventoryAttachment->saveInvItemsAttachment($id, $postData);
                        
                        $this->Flash->success(__('The inventory items has been saved.'));
                        return $this->redirect(['action' => 'index']);
                    }else{
                        $this->Flash->error(__('The inventory items could not be saved. Please, try again.'));
                    }
                }else{
                    $this->Flash->error(__('Part Number '.$postData['part_number'].' already exists.'));
                }

                //for test error while saving data to database
                /*$x = $invenotryitems->errors();
                if ($x) {
                    debug($invenotryitems);
                    debug($x);
                    return false;
                }*/

            }
            
            $manufacturer = $this->Inventory->getManufacturerList();
            
            $inventorymanufacturers = $this->InventoryPartManufacturers->newEntity();
            $countries = $this->Address->getCountryList();
            $states = '';
            $this->set(compact('invenotryitems', 'actionItems', 'manufacturer', 'countries', 'inventorymanufacturers', 'states'));
        }

        public function edit($id = null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            $invenotryitems = $this->InventoryItems->get($id);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();
                //echo "<pre>";print_r( $postData);exit;
                $inventoryitemdata = $this->InventoryItems->exists(['InventoryItems.part_number'=>$postData['part_number'], 'InventoryItems.id !='=>$id]);
                
                if(!$inventoryitemdata){
                    $postData['updated_by'] = $this->Auth->user('id');
                    $invenotryitems = $this->InventoryItems->patchEntity($invenotryitems, $postData);
                    if ($this->InventoryItems->save($invenotryitems)) {
                        //save attachment
                        $this->InventoryAttachment->saveInvItemsAttachment($id, $postData);

                        $this->Flash->success(__('The Inventory Items has been saved.'));
                        return $this->redirect(['action' => 'detail', $id]);
                    }
                    $this->Flash->error(__('The Inventory Items could not be saved. Please, try again.'));
                }else{
                    $this->Flash->error(__('Part Number '.$postData['part_number'].' already exists.'));
                }
            }
            
            $manufacturer = $this->Inventory->getManufacturerList();
            $countries = $this->Address->getCountryList();
            $attachments = $this->InventoryAttachment->getInvItemsAttachedFiles($id);
            $inventorymanufacturers = $this->InventoryPartManufacturers->newEntity();
            $states = '';
            $this->set(compact('invenotryitems', 'actionItems', 'manufacturer', 'countries', 'attachments', 'states', 'inventorymanufacturers'));
        }

        public function bulkitemupload(){
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
                    $foldername = 'inventoryitems';
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

        public function detail($id = null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            $invenotryitems = $this->InventoryItems->find('all')->where(['InventoryItems.id'=>$id])
                                ->select($this->InventoryItems)->select(['manufacturer.name'])
                                ->join([
                                    'manufacturer' => [
                                        'table' => 'inventory_part_manufacturers',
                                        'type' => 'LEFT',
                                        'conditions' => 'manufacturer.id = InventoryItems.manufacturer_id',
                                    ]
                                ])->first();
            $attachments = $this->InventoryAttachment->getInvItemsAttachedFiles($id);
            
            $invenotries = $this->Inventories->find('all')->where(['Inventories.inventory_item_id'=>$id])->select(['Inventories.id', 'Inventories.status', 'Inventories.qty']);
            
            $stock = 0;
            $outforrepair = 0;
            $installed = 0;
            $quarantined = 0;
            $allocated = 0;
            $onorder = 0;
            
            $inventoriesid = [];
            foreach($invenotries as $val){
                $inventoriesid[] = $val->id;
                if($val->status == '1'){
                    $stock += $val->qty;
                }else if($val->status == '2'){
                    $installed+= $val->qty;
                }else if($val->status == '7'){
                    $outforrepair+= $val->qty;
                }else if($val->status == '12'){
                    $quarantined+= $val->qty;
                }else if($val->status == '11'){
                    $allocated += $val->qty;
                }
            }

            if(!empty($inventoriesid)){
                $query = $this->InventoryPOReceives->find(); 
                $inventorypoitems = $query->where(['InventoryPOReceives.inventory_id IN'=>$inventoriesid, 'status'=>'1'])->select(['totalorderreceived' => $query->func()->sum('InventoryPOReceives.received')])->toArray();
                $onorder = !empty($inventorypoitems[0]['totalorderreceived']) ? $inventorypoitems[0]['totalorderreceived'] : 0;
            }
            
            $location = $this->Inventory->getAllLocations();

            $itemthresholds = $this->Inventory->getAllItemThresholds($id);
            $totalThreshold = 0;
            $thresholdRemaining = 0;
            foreach($itemthresholds as $thresholds){
                $totalThreshold += $thresholds['safety_stock_threshold'];
                if($thresholds['instock'] < $thresholds['safety_stock_threshold']){
                    $thresholdRemaining += 1;
                }
            }
            $ataCode   = $this->AtaCode->getAtaCodes();
            $vendor = $this->Inventory->getVendorList();

            $installto = $this->Inventory->getAllInstallToInventory();

            $inventoryitemhistories = $this->InventoryItemHistories->find('all')
                                    ->where(['user_id'=>$this->Auth->user('id'), 'inventory_item_id'=>$id])
                                    ->select($this->InventoryItemHistories)->select(['users.email'])
                                    ->join([
                                        'users' => [
                                            'table' => 'users',
                                            'type' => 'INNER',
                                            'conditions' => 'users.id = InventoryItemHistories.user_id',
                                        ]
                                    ])->order(['InventoryItemHistories.id'=>'DESC']);

            $this->set(compact('invenotryitems', 'actionItems', 'attachments', 'stock', 'outforrepair', 'installed', 'quarantined', 'allocated', 'onorder', 'location', 'itemthresholds', 'ataCode', 'vendor', 'totalThreshold', 'thresholdRemaining', 'inventoryitemhistories', 'installto'));
        }

        public function deleteAttachment(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['id'])){
                    $attachments = $this->InventoryAttachment->deleteInvItemsAttachment($postData['id']);

                    $result = array('status'=>'success', 'message'=>"Deleted successfully.");
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);die;
        }

        public function addThrashold($id=null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            $invenotryitemthresholds = $this->InventoryItemThresholds->newEntity();
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();
                $postData['added_by'] = $this->Auth->user('id');
                $postData['updated_by'] = $this->Auth->user('id');
                $postData['inventory_item_id'] = $id;
                
                $thresholdsdata = [];
                if(isset($postData['location_id']) && empty($postData['id'])){
                    $thresholdsdata = $this->InventoryItemThresholds->exists(['InventoryItemThresholds.inventory_item_id'=>$postData['inventory_item_id'], 'InventoryItemThresholds.location_id'=>$postData['location_id']]);
                }else if(!empty($postData['id'])){
                    $invenotryitemthresholds = $this->InventoryItemThresholds->get($postData['id']);
                }
                
                if(!$thresholdsdata){
                    $invenotryitemthresholds = $this->InventoryItemThresholds->patchEntity($invenotryitemthresholds, $postData);
                    if ($this->InventoryItemThresholds->save($invenotryitemthresholds)) {
                        $this->Flash->success(__('The inventory item threshold has been saved.'));
                    }else{
                        $this->Flash->error(__('Somethiing went wrong.'));
                    }
                }else{
                    $this->Flash->error(__('The inventory item threshold already added for this location.'));
                }
                
                return $this->redirect(['action' => 'detail', $id]);
            }
        }

        public function deleteThresholds($id=null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            if(isset($_POST['id']) && !empty($_POST['id'])){
                $ids = explode(',',$_POST['id']);
                $this->request->allowMethod(['post', 'delete']);
                $inventory_item_id = $_POST['inventory_item_id'];
                try {
                    if ($this->InventoryItemThresholds->deleteAll(['InventoryItemThresholds.id IN' => $ids, 'inventory_item_id'=>$inventory_item_id])) {
                        $this->Flash->success(__('The inventory item threshold has been deleted'));
                    } else {
                        $this->Flash->error(__('The inventory item threshold could not be deleted. Please, try again.'));
                    }
                } catch(\PDOException $e) {
                    $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
                } catch (\Exception $e) {
                    $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
                }
            }else{
                $this->Flash->error(__('Something went wrong. Please, try again.'));
            }

            return $this->redirect(['action' => 'detail', $inventory_item_id]);
        }

        //Generate InventoryItems report pdf
        public function printInventoryItemsBarCode()
        {
            $postData = $this->request->data;
            
            $inventoryitems = $this->InventoryItems->get($postData['id']);
            $invdetpageurl = Router::url(['controller' => 'InventoryItems', 'action' => 'detail', $postData['id']]);
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
            <body>
            <div class=container4>
                <p>
                <span style="width:200px; float:left;">
                    <span>PN: '.$inventoryitems->part_number.'</span><br/>
                    <span>'.$inventoryitems->name.'</span>
                </span>
                
                <span>
                    <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.$invdetpageurl.'&choe=UTF-8" />
                </span>
                </p>
            </div>        
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
            $fileName = "InventoryItemBarcode".date('YmdHis').".pdf";
            $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
            if($html) {
                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
            
        }

        public function saveInventoryItems(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->layout = 'ajax';
                
                $inventoryitems = $this->InventoryItems->newEntity();

                $postData = $this->request->getData();
                $postData['added_by'] = $this->Auth->user('id');
                $inventoryitems = $this->InventoryItems->patchEntity($inventoryitems, $postData);//print_r($part);exit;
                if ($this->InventoryItems->save($inventoryitems)) {
                    $id = $inventoryitems->id;

                    $invitems = array('id'=>$id, 'name'=>$postData['name'], 'part_number'=>$postData['part_number']);
                    $result = array('status'=>'success', 'message'=>"Saved successfully.", 'invitems'=>$invitems);
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                
                echo json_encode($result);die;
            }
        }

        public function getInventoryItem(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $this->layout = 'ajax';
                $postData = $this->request->getData();
                if(isset($postData['inventory_item_id']) && !empty($postData['inventory_item_id'])){
                    $inventoryitems = $this->InventoryItems->get($postData['inventory_item_id']);//print_r($part);exit;
                    if (!empty($inventoryitems)) {
                        $result = array('status'=>'success', 'message'=>"", 'inventoryitems'=>$inventoryitems);
                    } else {
                        $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                    }
                }else{
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
                
                echo json_encode($result);die;
            }
        }

        public function ajaxOpenActionSelectPopup(){
            if (!$this->request->is('ajax')) {
                return $this->redirect(['action' => 'index']);
            }else{
                $postData = $this->request->getData();
                $this->layout = 'ajax';
                $this->set('account_code', []);
                if($postData['bulkpopuptype'] == '2'){
                    $location = $this->Inventory->getAllLocations();
                    $this->set('location', $location);
                    $this->render("/Element/Inventory/inventory_bulk_transfer");
                }else if($postData['bulkpopuptype'] == '3'){
                    $installto = $this->Inventory->getAllInstallToInventory();
                    $this->set('installto', $installto);
                    
                    $this->render("/Element/Inventory/inventory_bulk_install");
                }else if($postData['bulkpopuptype'] == '4'){
                    $this->render("/Element/Inventory/inventory_bulk_discard");
                }
            }
        }

        public function addToHoldingBox(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();
                if(!empty($postData['ids'])){
                    $ids = explode(',', $postData['ids']);
                    $flag = 0;
                    foreach($ids as $id){
                        $invenotryitems = $this->InventoryItems->get($id);
                        if($invenotryitems->in_holdingbox == '0'){
                            $postData['in_holdingbox'] = '1';
                            $postData['updated_by'] = $this->Auth->user('id');
                            $invenotryitems = $this->InventoryItems->patchEntity($invenotryitems, $postData);
                            $this->InventoryItems->save($invenotryitems);
                        }else{
                            $flag = 1;
                        }
                    }

                    if($flag == 0){
                        $this->Flash->success(__('The Inventory Items added to holding box.'));
                    }else{
                        $this->Flash->error(__('The Inventory Items already added to holding box.'));
                    }
                    
                    return $this->redirect(['action' => 'index']);
                }else{
                    $this->Flash->error(__('Something went wrong.'));
                }
            }else{
                $this->Flash->error(__('Something went wrong.'));
            }
        }

        public function updateInventoryItemStatus(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            if ($this->request->is(['post'])) {
            
                $postData = $this->request->getData();
                $id = $postData['id'];
                $inventoryitems = $this->InventoryItems->get($id);

                $invenotrypoitems = $this->InventoryPOItems->find('all')->where(['inventory_item_id'=>$id])->select($this->InventoryPOItems);
                $inventories = $this->Inventories->find('all')->where(['inventory_item_id'=>$id])->select($this->Inventories);
                if($invenotrypoitems->count() == 0 && $inventories->count() == 0){
                    $postData = $this->request->getData();

                    $inventoryitems = $this->InventoryItems->patchEntity($inventoryitems, $postData);
                    if ($this->InventoryItems->save($inventoryitems)) {
                        if($postData['status'] == '0'){
                            $msg = 'Inventory items deactivated `'.$inventoryitems->part_number.'`.';
                        }else if($postData['status'] == '1'){
                            $msg = 'Inventory items activated `'.$inventoryitems->part_number.'`.';
                        }
                        
                        $this->Flash->success(__($msg));
                    } else {
                        $msg = 'You may not close a PO Request if it is Cancelled, Denied, PO Created, or already Closed.';
                        $this->Flash->error(__($msg));
                    }
                    
                }else{
                    $this->Flash->error(__('Cannot disable items with active physical inventory.'));
                }

                return $this->redirect(['action' => 'detail', $id]);
            }
        }

        public function exportInvItemListToExcel() {
            
            $dataTable = '';
            $dataTable .='<table class="table">
                                <thead>
                                    <tr>
                                        <th>Part No.</th>
                                        <th>Name</th>
                                        <th>Currency Code</th>
                                        <th>Cost</th>
                                        <th>Exchange Cost</th>
                                        <th>Serialized</th>
                                        <th>Type</th>
                                        <th>In Stock</th>
                                        <th>Default UOM</th>
                                        <th>On Order</th>
                                        <th>Inventory Tags</th>
                                    </tr>
                                </thead>
                        <tbody>';

            $requestData = $this->request->query;//echo "<pre>";print_r($requestData);exit;
            
            $cond = $this->InventoryFilter->inventoryCatalogFilter($requestData);
            
            if(isset($requestData['source']) && $requestData['source'] == 'holdingbox'){
                $cond .= ' AND in_holdingbox = "1" AND id in('.$requestData['ids'].')';
            }

            $inventoryrequestitems = $this->Inventory->getExportExcelInventoryItems($requestData, $cond);

            $setData = '';  
            $itemtypearr = unserialize(INVENTORY_ITEM_TYPE);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $currency = unserialize(CURRENCY);

            foreach($inventoryrequestitems as $invreqitems){
                $urgency = !empty($invreqitems['urgency']) ? $urgencyStatus[$invreqitems['urgency']] : '';
                
                $dataTable .='
                            <tr>
                                <td>'.$invreqitems['part_number'].'</td>
                                <td>'.$invreqitems['name'].'</td>
                                <td>'.(!empty($invreqitems['currency']) ? $currency[$invreqitems['currency']] : '').'</td>
                                <td>'.$invreqitems['unit_cost'].'</td>
                                <td>'.$invreqitems['exchange_cost'].'</td>
                                <td>'.$invreqitems["is_this_item_serialized"].'</td>
                                <td>'.(!empty($invreqitems['item_type']) ? $itemtypearr[$invreqitems['item_type']] : '').'</td>
                                <td>'.$invreqitems["item_instock"].'</td>
                                <td>'.(!empty($invreqitems["default_uom"]) ? $defaultUOM[$invreqitems["default_uom"]] : '').'</td>
                                <td>'.$invreqitems["ordered"].'</td>
                                <td>'.$invreqitems["tags"].'</td>
                            </tr>';
            }  
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=InventoryItems".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }

        public function printItemCatalogBarCode()
        {
            $postData = $this->request->data;//print_r($postData);exit;
            $statusarr = array('1', '2', '7', '9', '12');
            if(!empty($postData['catalogprinttype']) && $postData['catalogprintsize'] == '1' && !empty($postData['inventoryitemids'])){
                
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
                
                if($postData['catalogprinttype'] == '2'){
                    $inventoryitems = $this->InventoryItems->find('all')->where(['InventoryItems.id IN'=>$postData['inventoryitemids'], 'InventoryItems.status'=>'1', 'inv.status IN'=>$statusarr])->select($this->InventoryItems)->select(['inv.display_name', 'inv.serial_no'])
                        ->join([
                            'inv' => [
                                'table' => 'inventories',
                                'type' => 'INNER',
                                'conditions' => 'inv.inventory_item_id = InventoryItems.id',
                            ]
                        ]);
                }else{
                    $inventoryitems = $this->InventoryItems->find('all')->where(['InventoryItems.id IN'=>$postData['inventoryitemids'], 'InventoryItems.status'=>'1'])->select($this->InventoryItems);
                }
                foreach($inventoryitems as $invitems){
                    $bar_code = Router::url(['controller' => 'InventoryItems', 'action' => 'detail', $invitems['id']]);
                    
                    $html .='<div class=container4><p>
                    <span style="width:200px; float:left;">';
                    if($postData['catalogprinttype'] == '2'){
                        $html .='<span>SN: '.$invitems['inv']['serial_no'].'</span><br/>';
                    }
                    $html .='<span>PN: '.$invitems['part_number'].'</span><br/>
                        <span>'.$invitems['name'].'</span>
                    </span>
                    
                    <span>
                        <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.$bar_code.'&choe=UTF-8" />
                    </span>
                    </p></div>';
                }
                
            }else if(!empty($postData['catalogprinttype']) && $postData['catalogprintsize'] == '2' && !empty($postData['inventoryitemids'])){
                $invItemType = unserialize(INVENTORY_ITEM_TYPE);
                $defaultUOM = unserialize(DEFAULT_UOM);
                $invConditions = unserialize(INVENTORY_CONDITION);

                $connection = ConnectionManager::get('default');
                $statusarr = "'" . implode ( "', '", $statusarr ) . "'";
                $invquantities = $connection
                    ->execute(
                        'SELECT inv.*, invloc.location_name, invitm.part_number, invitm.name, invitm.item_type, invitm.tags, vendor.name as vendor_name, ata_codes.ata_code FROM inventories as inv join inventory_items as invitm on inv.inventory_item_id = invitm.id join inventory_locations as invloc on inv.location_id = invloc.id left join inventory_vendors as vendor on inv.vendor = vendor.id left join ata_codes on ata_codes.id = inv.ata_chapter WHERE inv.inventory_item_id in('.implode(',', $postData['inventoryitemids']).') and inv.status in('.$statusarr.')',
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
                                                <td><img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.$invqty['bar_code'].'&choe=UTF-8" /></td>
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

        public function saveInventoryCatalogTags(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            if ($this->request->is(['post'])) {
                $postData = $this->request->getData();//echo "<pre";print_r($postData);exit;
                $ids = $postData['inventory_item_id'];
                $postData['updated_by'] = $this->Auth->user('id');
                if($postData['activityontags'] == '1'){
                    foreach($ids as $id){
                        $inventoryitems = $this->InventoryItems->get($id);
                        $updated_tags = !empty($inventoryitems->tags) ? $inventoryitems->tags.', '.$postData['tags'] : $postData['tags'];
                        $res = $this->InventoryItems->updateAll(
                            array('tags'=>$updated_tags, 'updated_by'=>$postData['updated_by']),
                            array('id' => $id)
                        );
                    }
                }else{
                    $res = $this->InventoryItems->updateAll(
                        array('tags'=>$postData['tags'], 'updated_by'=>$postData['updated_by']),
                        array('id IN' => $ids)
                    );
                }

                if($res){
                    $this->Flash->success(__('Tags has been updated successfully.'));
                }else{
                    $this->Flash->error(__('Tags could not be saved. Please, try again.'));
                }
            }else{
                $this->Flash->error(__('Something went wrong. Please, try again.'));
            }

            return $this->redirect( Router::url( $this->referer(), true ) );
        }

        public function importItemCatalogCSV(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Item Catalog', $actionStatus))
                {
                    $actionItems = $actionStatus['Item Catalog'];
                }
            }
            
            if ($this->request->is(['post'])) {
                $postData = $this->request->getData();//echo "<pre";print_r($postData);exit;
                $ids = $postData['inventory_item_id'];
                $postData['updated_by'] = $this->Auth->user('id');
                if($postData['activityontags'] == '1'){
                    foreach($ids as $id){
                        $inventoryitems = $this->InventoryItems->get($id);
                        $updated_tags = !empty($inventoryitems->tags) ? $inventoryitems->tags.', '.$postData['tags'] : $postData['tags'];
                        $res = $this->InventoryItems->updateAll(
                            array('tags'=>$updated_tags, 'updated_by'=>$postData['updated_by']),
                            array('id' => $id)
                        );
                    }
                }else{
                    $res = $this->InventoryItems->updateAll(
                        array('tags'=>$postData['tags'], 'updated_by'=>$postData['updated_by']),
                        array('id IN' => $ids)
                    );
                }

                if($res){
                    $this->Flash->success(__('Tags has been updated successfully.'));
                }else{
                    $this->Flash->error(__('Tags could not be saved. Please, try again.'));
                }
            }else{
                $this->Flash->error(__('Something went wrong. Please, try again.'));
            }

            return $this->redirect(['action' => 'index']);
        }
    }

?>