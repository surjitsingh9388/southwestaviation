<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use App\View\Helper\InventoryStatusHTMLHelper;

    class InventoryLocationsController extends AppController
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
                    'Inventories',
                    'InventoryLocationHistories',
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
                    'AtaCode'
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
            
            $query['count'] = "SELECT count(id) AS count  FROM inventory_locations WHERE 1=1 and parent_location_id = 0 ";

            $query['detail'] = "SELECT id, location_name, status, description FROM `inventory_locations` WHERE 1=1 and parent_location_id = 0 ";
            
            return $query;
        }

        public function ajaxInventoryLocationsSearch(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Locations', $actionStatus))
                {
                    $actionItems = $actionStatus['Locations'];
                }
            }
            $this->autoRender = false;
            $this->layout = 'ajax';
            $requestData= $this->request->data;

            $query = $this->search();
            
            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                parse_str($requestData['columns'][1]['search']['value'], $requestData);
            }

            $cond = $this->InventoryFilter->inventoryLocationFilter($requestData);
            
            $requestData= $this->request->data;
            $columns = array(
                0 => 'id',
                1 => 'location_name',
                2 => 'description',
                3 => 'status',
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
            $sord = isset($requestData['order'][0]['dir']) ? $requestData['order'][0]['dir'] : 'ASC';
            $start = $requestData['start'];
            $length = PAGINATION_LIMIT;

            $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
            $results = $conn->execute( $SQL )->fetchAll('assoc');
            
            $data = array();
            $invtLocationStatus = unserialize(INVENTORY_LOCATION_STATUS);
            foreach ( $results as $row){
                $nestedData= [];
                $nestedData[] = '<input type="checkbox" class="chkBoxCls" name="childcheckbox" value="'.$row['id'].'" >';
                $nestedData[] = $row["location_name"];
                $nestedData[] = !empty($row["description"]) ? $row["description"] : '-';
                $nestedData[] = ($row['status'] == '1') ? '<i style="color: green" class="fa fa-check"></i>' : '<i style="color: red" class="fa fa-times"></i>';
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
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Locations', $actionStatus))
                {
                    $actionItems = $actionStatus['Locations'];
                }
            }

            $parentlocation = [];
            $parentlocarr = $this->InventoryLocations->find('all')->where(['id'=>$id, 'status'=>'1'])->select(['InventoryLocations.id', 'InventoryLocations.location_name'])->first();
            if(!empty($parentlocarr)){
                $parentlocation[$parentlocarr->id] = $parentlocarr->location_name;
            }
            $invenotrylocations = $this->InventoryLocations->newEntity();
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                if(!empty($id)){
                    $postData['parent_location_id'] = $id;
                }
                $postData['parent_location_id'] = isset($postData['parent_location_id']) && !empty($postData['parent_location_id']) ? $postData['parent_location_id'] : 0;
                $postData['added_by'] = $this->Auth->user('id');
                $postData['updated_by'] = $this->Auth->user('id');
                $postData['location_status'] = '1';
                $invenotrylocations = $this->InventoryLocations->patchEntity($invenotrylocations, $postData);//print_r($part);exit;
                if ($this->InventoryLocations->save($invenotrylocations)) {
                    $id = $invenotrylocations->id;
                    //save data to inventory location history table
                    $this->InventoryHistory->saveInventoryLocationHistory($invenotrylocations);

                    $this->InventoryAttachment->saveInventoryLocAttachment($id, $postData);
                    
                    $this->Flash->success(__('The inventory location has been saved.'));
                    return $this->redirect(['action' => 'index']);
                }

                $this->Flash->error(__('The inventory location could not be saved. Please, try again.'));
            }
            $location_id = $id;
            $invenotrylocations->parent_location_id = $id;
            $this->set(compact('invenotrylocations', 'actionItems', 'parentlocation', 'location_id'));
        }

        public function edit($id = null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1){
                $actionStatus = $this->checkAction();
                if(array_key_exists('InventoryItems', $actionStatus))
                {
                    $actionItems = $actionStatus['InventoryItems'];
                }
            }
            $parentlocation = $this->Inventory->getAllParentLocations();
            
            $invenotrylocations = $this->InventoryLocations->get($id);
            if ($this->request->is(['patch', 'post', 'put'])) {
                $postData = $this->request->getData();
                $postData['updated_by'] = $this->Auth->user('id');
                $postData['parent_location_id'] = isset($postData['parent_location_id']) && !empty($postData['parent_location_id']) ? $postData['parent_location_id'] : 0;//echo "<pre>";print_r($postData);exit;
                $invenotrylocations = $this->InventoryLocations->patchEntity($invenotrylocations, $postData);
                if ($this->InventoryLocations->save($invenotrylocations)) {
                    //save attachment
                    $this->InventoryAttachment->saveInventoryLocAttachment($id, $postData);

                    $this->Flash->success(__('The Inventory Location has been saved.'));
                    return $this->redirect(['action' => 'detail', $id]);
                }
                $this->Flash->error(__('The Inventory Location could not be saved. Please, try again.'));
            }
            
            $attachments = $this->InventoryAttachment->getInventoryLocAttachments($id);
            $this->set(compact('invenotrylocations', 'actionItems', 'parentlocation', 'attachments'));
        }

        public function detail($id = null)
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Locations', $actionStatus))
                {
                    $actionItems = $actionStatus['Locations'];
                }
            }
            $status = array(1);
            $childlocations = $this->Inventory->getAllChildLocations($id, $status);//echo "<pre>";print_r($childlocations);exit;
            //$invquantities = $this->Inventory->getAllItemsByLocation($id);
            
            $invenotrylocations = $this->InventoryLocations->get($id);
            $locationdata = $this->Inventory->getParentLocationById($invenotrylocations->parent_location_id);
            $attachments = $this->InventoryAttachment->getInventoryLocAttachments($id);
            
            $inventorylocationhistories = $this->InventoryLocationHistories->find('all')
                                    ->where(['user_id'=>$this->Auth->user('id'), 'inventory_location_id'=>$id])
                                    ->select($this->InventoryLocationHistories)->select(['users.email'])
                                    ->join([
                                        'users' => [
                                            'table' => 'users',
                                            'type' => 'INNER',
                                            'conditions' => 'users.id = InventoryLocationHistories.user_id',
                                        ]
                                    ])->order(['InventoryLocationHistories.id'=>'DESC']);

            $this->set(compact('invenotrylocations', 'actionItems', 'childlocations', 'attachments', 'locationdata', 'inventorylocationhistories'));
        }
        
        public function ajaxQantitiesSearch(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('InventoryItems', $actionStatus))
                {
                    $actionItems = $actionStatus['InventoryItems'];
                }
            }
            $this->autoRender = false;
            $this->layout = 'ajax';
            $requestData= $this->request->data;
            $locationarr = $this->InventoryLocations->get($requestData['location_id']);
            $childlocationsdata = $this->Inventory->getAllLocationsPrintBarcode($requestData['location_id'], $locationarr->location_name);
              
            $childlocationids = [];
            $childlocationlist = [];
            foreach ( $childlocationsdata as $val){
                $childlocationids[] = $val['id'];
                $childlocationlist[$val['id']] = $val['location_path'];
            }
            if(!empty($childlocationids)){
                $cond = " and (inv.location_id=".$requestData['location_id']." OR inv.location_id in(".implode(',', $childlocationids)."))";
            }else{
                $cond = " and inv.location_id=".$requestData['location_id'];
            }
            $statusarr = array('1', '2', '7', '9', '12');
            if( isset($requestData['inactiveinv']) && !empty($requestData['inactiveinv'])){
                $search = $requestData['inactiveinv'];
                if($search == 1){
                    $inactivestatus = array('3', '4', '5', '6', '8', '10', '11', '13', '14');
                    $statusarr = array_merge($statusarr, $inactivestatus);
                }
            }
            $result = "'" . implode ( "', '", $statusarr ) . "'";
            //$cond.=" AND (inv.status in ($result))";

            $query = "SELECT invitm.part_number, inv.id, inv.serial_no, inv.display_name, inv.qty, inv.cost, inv.currency, invloc.location_name, inv.location_id, inv.inventory_item_id, inv.received, inv.modified, inv.status, inv.in_holdingbox FROM `inventories` as inv join inventory_items invitm on inv.inventory_item_id = invitm.id left join inventory_locations as invloc on inv.location_id = invloc.id WHERE 1=1 ".$cond;
            
            $conn = ConnectionManager::get('default');
            $results = $conn->execute( $query )->fetchAll('assoc');
            
            $data = '';
            if(count($results) > 0){
                $itemtypearr = unserialize(INVENTORY_ITEM_TYPE);
                $currency = unserialize(CURRENCY);
                $InventoryStatusHTMLHelper = new InventoryStatusHTMLHelper(new \Cake\View\View());

                foreach ( $results as $key=>$row){
                    $statushtml = $InventoryStatusHTMLHelper->getInventoriesStatusHTML($row['status']);
                    
                    $inholdingbox = !empty($row['in_holdingbox']) ? '<img src="../../../images/icons/holdingbox.png" style="width:20px; height:20px;">' : '';
                    if(empty($row['location_name']) && $row['status'] != 7 && $row['status'] != 9){
                        $location = '<span style="color:red;">[Inactive]</span>';
                    }else{
                        $location = ($requestData['location_id'] == $row['location_id']) ? $row["location_name"] : '<span title="'.$childlocationlist[$row['location_id']].'">'.$row['location_name'].'&nbsp;<i class="fa fa-info-circle"></i></span>';
                    }

                    $key = $key+1;    
                    $evenOdd = (!empty($key) && ($key % 2) == 0) ? 'even' : 'odd';

                    $data .= '<tr class="mainTR activeTble '.$evenOdd.'">';
                    $data .= '<td class="collapse-tr"><input type="checkbox" class="chkBoxCls" name="childcheckbox" value="'.$row['id'].'" >&nbsp;&nbsp;'.$inholdingbox.'</td>';
                    $data .= '<td class="collapse-tr">'.$row["part_number"].'</td>';
                    $data .= '<td class="collapse-tr">'.$row["serial_no"].(!empty($row['display_name']) ? '<br/>'.$row['display_name'] : '').'</td>';
                    $data .= '<td class="collapse-tr">'. $row["qty"];
                    $data .= '<td class="collapse-tr">'. (!empty($row["cost"]) ? '$ '.$row["cost"]*$row["qty"] : '-').'</td>';
                    $data .= '<td class="collapse-tr">'. $location.'</td>';
                    $data .= '<td class="collapse-tr">'. (isset($row["received"]) ? date('d-M-Y', strtotime($row["received"])) : '-').'</td>';
                    $data .= '<td class="collapse-tr">'. (!empty($row["modified"]) ? date('d-M-Y', strtotime($row["modified"])) : '').'</td>';
                    $data .= '<td class="collapse-tr">'. $statushtml.'</td>';

                    $data .= '</tr>';
                }
            }else{
                $data = '<tr><td colspan="9"><i>No quantities available.</i></td></tr>';
            }

            echo $data;die;
        }

        public function bulkInventoryLocUpload(){
            $params = $this->request->data;
            if(!empty($params['file_name'])) 
            {
                
                $isvalidfile = 1;
                $arr_ext = array('pdf','txt');
                
                $temp = $params['file_name']['tmp_name'];
                $name = $params['file_name']['name'];
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

                    $filelocation = WWW_ROOT . 'inventorylocation/' . $name;
                    if(move_uploaded_file($temp, $filelocation)) {
                        $filesize = ($params['file_name']['size']/1000).' KB';
                        $tblrow = '<tr>
                            <td class="document-name"><span class="document-management-icon '.$iconcss.'"></span><a href="'.Router::url('/', true).'inventoryitems/' . $name.'">'.$name.'</a>
                            <input type="hidden" name="filenames[]" value="'.$name.'">
                            <input type="hidden" name="filesize[]" value="'.$filesize.'">
                            </td>
                            <td>'.$filesize.'</td>
                            <td class="text-uppercase">'.date("d-M-Y").'</td>
                            <td>'.$this->Auth->user('full_name').'</td>
                            <td><i class="fa fa-times deleteattachment" title="Remove File"></i></td>
                        </tr>';
                        /*$tblrow = array(
                                            'filename'=> $name,
                                            'filepath'=> $filelocation,
                                            'size'=> ($params['file_name']['size']/1000).' KB',
                                            'uploaded'=> date("d-M-Y"),
                                            'uploaded_by'=> $this->Auth->user('full_name')
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

        public function deleteInventoryLocAttachment(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('InventoryItems', $actionStatus))
                {
                    $actionItems = $actionStatus['InventoryItems'];
                }
            }
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                
                if(!empty($postData['id'])){
                    $attachments = $this->InventoryAttachment->deleteInventoryLocAttachment($postData['id']);

                    $result = array('status'=>'success', 'message'=>"Deleted successfully.");
                } else {
                    $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
                }
            } else {
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);die;
        }

        public function updatelocationstatus($id=null){
            $this->request->allowMethod(['post', 'delete']);
            $postData = $this->request->getData();
            $id = $postData['id'];
            $invenotrylocations = $this->InventoryLocations->get($id);
            if(empty($invenotrylocations)){
                return $this->redirect(['action' => 'index']);
            }
            $invactivestatus = ['1'];
            $locinventory = $this->Inventories->find('all')->where(['location_id'=>$id, 'status IN'=>$invactivestatus])->select($this->Inventories);
            if($locinventory->count() == 0){
                try {
                    $invenotrylocations = $this->InventoryLocations->patchEntity($invenotrylocations, $postData);
                    if ($this->InventoryLocations->save($invenotrylocations)) {
                        $msg = $postData['status'] == '0' ? 'Item `'.$invenotrylocations->location_name.'` deactivated.' : 'Item `'.$invenotrylocations->location_name.'` activated.';
                        $this->Flash->success(__($msg));
                    } else {
                        $msg = $postData['status'] == '0' ? 'Item `'.$invenotrylocations->location_name.'` could not be deactivated. Please, try again.' : 'Item `'.$invenotrylocations->location_name.'` could not be activated. Please, try again.';
                        $this->Flash->error(__($msg));
                    }
                } catch(\PDOException $e) {
                    $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
                } catch (\Exception $e) {
                    $this->Flash->error(__($this->setDeleteExceptionMessage($e->getMessage())));
                }
            }else{
                $this->Flash->error(__("Location/Sub-Location 'Kolkata' has active inventory."));
            }

            return $this->redirect(['action' => 'detail', $id]);
        }

        public function childLocationSearchAjax(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Locations', $actionStatus))
                {
                    $actionItems = $actionStatus['Locations'];
                }
            }
            if ($this->request->is('post')) {
                $postData = $this->request->getData();
                $invenotrylocations = $this->InventoryLocations->get($postData['location_id']);
                $status = $postData['status'] == '0' ? array(0,1) : array(1);
                $locations = $this->Inventory->getAllChildLocations($postData['location_id'], $status);
                
                $tblrow = '';
                foreach ( $locations as $val){
                    $tblrow .= '<tr>
                    <td><a href="'.Router::url(['controller' => 'InventoryLocations', 'action' => 'detail', $val['id']]).'">'.$invenotrylocations->location_name.' > '.$val['location_name'].'</a></td>
                    <td>'.$val['description'].'</td></tr>';
                }

                if($tblrow != ''){
                    $result = array('status'=>'success', 'message'=>"", 'tblrow'=>$tblrow);
                } else {
                    $result = array('status'=>'failure', 'message'=>'No sub locations found');
                }
            }else{
                $result = array('status'=>'failure', 'message'=>'Something went wrong. Please try again');
            }

            echo json_encode($result);die;
        }

        //Generate InventoryItems report pdf
        public function generateInventoryLocationsPdf()
        {
            $postData = $this->request->data;
            $mainHtml = '';
            $flHtml = '';
            
            $flRes = $this->Inventory->InventoryLocationReportData($postData);
            if(!empty($flRes)) {
                $flHtml = $flRes;
            } else {
                $flHtml = '<tr><td colspan="16" style="text-align: center;">No Records Found for that Inventory Locations.</td></tr>';
            }

            $mainHtml .= '<header>
                            <table id="header" style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center;" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td style="font-size: 14px; font-weight: normal; text-align:center;"><span style="font-weight: bold;">INVENTORY LOCATION LIST REPORT</span></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px; font-weight: normal; text-align:center;"><span>A list of Inventory Locations for a given filter criteria.</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </header>';

            $mainHtml .= '<table style="width:100%; margin:0 auto; padding:0 0 10px 0; text-align:center; font-size:11px;" cellspacing="0" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Name</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Full Path</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:10%;">Description</th>
                                        <th style="font-size: 11px; font-family: Arial, Helvetica, sans-serif;font-weight: bold;padding: 5px;border-bottom: 1px solid #c0c0c0; margin: 0; color:#676a6c;width:6%;">Active</th>
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
            $fileName = "InventoryLocations".date('YmdHis').".pdf";
            $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
            if($mainHtml) {
                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
            
        }

        //Generate InventoryLocations report pdf
        public function printInventoryLocationBarCode()
        {
            $postData = $this->request->data;
            
            $inventorylocations = $this->InventoryLocations->get($postData['id']);

            $bar_code = !empty($inventorylocations->bar_code) ? $inventorylocations->bar_code : Router::url(['controller' => 'InventoryLocations', 'action' => 'detail', $inventorylocations->id]); 
            
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
                    <span>'.$inventorylocations->location_name.'</span>
                </span>
                
                <span>
                    <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.$bar_code.'&choe=UTF-8" />
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

        public function printLocationSubLocationBarCode()
        {
            $postData = $this->request->data;//print_r($postData);exit;
            
            if(!empty($postData['locationdata']) && !empty($postData['checkboxdata'])){
                
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
                $locidarr = [];
                foreach($postData['checkboxdata'] as $location_id){
                    $inventorylocationdata = [];
                    $inventorylocationdata[] = $this->InventoryLocations->find('all')->where(['id'=>$location_id])->select($this->InventoryLocations)->first();
                    $inventorysublocationdata = [];
                    if(in_array(2, $postData['locationdata'])){
                        $inventorysublocationdata = $this->Inventory->getAllLocationsPrintBarcode($location_id, $inventorylocationdata[0]->location_name);
                    }
                    if(in_array(1, $postData['locationdata'])){
                        $inventorylocationdata = array_merge($inventorylocationdata, $inventorysublocationdata);
                    }else{
                        $inventorylocationdata = $inventorysublocationdata;
                    }
                    //echo "<pre>";print_r($inventorylocationdata);exit;
                    foreach($inventorylocationdata as $locationdet){
                        if(!isset($locationdet->location_path)){
                            $locationdet->location_path = $locationdet->location_name;
                        }
                        $bar_code = !empty($locationdet->bar_code) ? $locationdet->bar_code : Router::url(['controller' => 'InventoryLocations', 'action' => 'detail', $locationdet->id]); 

                        $html .='<div class=container4><p>
                        <span style="width:200px; float:left;">
                            <span>'.$locationdet->location_path.'</span>
                        </span>
                        
                        <span>
                            <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl='.$bar_code.'&choe=UTF-8" />
                        </span>
                        </p></div>';
                    }
                }
                $html .='</body>
                </html>';
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
            
        }

        public function exportLocationListToExcel(){
            $dataTable = '';
            $dataTable .='<table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Div. Abbr.</th>
                                        <th>Division Name</th>
                                        <th>Location Full Path</th>
                                        <th>Description</th>
                                        <th>Active</th>
                                    </tr>
                                </thead>
                        <tbody>';
    
            //$query = "WITH RECURSIVE cte ( id, location_name, path, description, status, location_status) AS ( SELECT id, location_name, CAST(location_name AS varchar(500)), description, status, location_status FROM inventory_locations WHERE parent_location_id = 0 UNION ALL SELECT inventory_locations.id, inventory_locations.location_name, CONCAT ( cte.path, ' > ', inventory_locations.location_name ), inventory_locations.description, inventory_locations.status, inventory_locations.location_status FROM cte JOIN inventory_locations ON cte.id = inventory_locations.parent_location_id ) SELECT * FROM cte WHERE 1=1";
            
            $query = "select * from inventory_locations where 1=1";
            $requestData= $this->request->query;//echo "<pre>";print_r($requestData);exit;
            
            $cond = $this->InventoryFilter->inventoryLocationFilter($requestData);
            $status = isset($requestData['show_inactive']) ? array(0,1) : array(1);
            
            $columns = array(
                0 => 'id',
                1 => 'location_name',
                2 => 'description',
                3 => 'status',
            );
            
            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sort = 'asc';
            
            $SQL = $query.$cond." ORDER BY $sidx $sort";//echo $SQL;exit;
            $inventorylocationitems = $conn->execute( $SQL )->fetchAll('assoc');
            
            $setData = '';  
            $invtLocationStatus = unserialize(INVENTORY_LOCATION_STATUS);
    
            foreach($inventorylocationitems as $invlocations){
                $childlocations = $this->Inventory->getAllChildLocations($invlocations['parent_location_id'], $status, $invlocations['id']);
                $location_path = '';
                if($childlocations->count() > 0){
                    foreach($childlocations as $val){
                        $location_path = $val['location_path'];
                    }
                }
                $dataTable .='
                            <tr>
                                <td>'.$invlocations['location_name'].'</td>
                                <td></td>
                                <td></td>
                                <td>'.$location_path.'</td>
                                <td>'.$invlocations['description'].'</td>
                                <td>'.$invlocations['status'].'</td>
                            </tr>';
            }  
            
            $dataTable .= '  </tbody></table>';
    
            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=InventoryLocations".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }

        //Generate printQantitiesBarCode report pdf
        public function printQantitiesBarCode()
        {
            $postData = $this->request->data;
            
            $invquantities = $this->Inventory->getAllItemsByLocation($postData['id']);
            $pagesizeval = $postData['pagesizeval'];
            
            if($pagesizeval == '1'){
                $html ='<!DOCTYPE html>
                <html>
                <head>
                <meta charset="utf-8">
                <style>
                div.container4 {
                    height: 10em;
                    position: relative }
                div.container4 {
                    margin: 0;
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    margin-right: -50%;
                    width: 20%;
                    transform: translate(-50%, -50%);
                }
                </style>
                </head>
                <body>
                    <div class=container4>';
                foreach($invquantities as $invqty){
                $html .='<div style="clear:both;">
                            <div style="width:200px; float:left;">
                                <div><span>LOT: '.$invqty['serial_no'].'</span></div>
                                <div><span>PN: '.$invqty['part_number'].'</span></div>
                                <div><span>'.$invqty['name'].'</span></div>
                            </div>
                            
                            <div>
                                <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=http%3A%2F%2Fwww.yahoo.com%2F&choe=UTF-8" />
                            </div>
                        </div>';
                }        
                $html .='</div>    
                    
                    </body>
                </html>';
            }else{
                $invItemType = unserialize(INVENTORY_ITEM_TYPE);
                $defaultUOM = unserialize(DEFAULT_UOM);
                $invConditions = unserialize(INVENTORY_CONDITION);

                $connection = ConnectionManager::get('default');
                $invquantities = $connection
                    ->execute(
                        'SELECT inv.*, invloc.location_name, invitm.part_number, invitm.name, invitm.item_type, vendor.name as vendor_name, ata_codes.ata_code FROM inventories as inv join inventory_items as invitm on inv.inventory_item_id = invitm.id join inventory_locations as invloc on inv.location_id = invloc.id left join inventory_vendors as vendor on inv.vendor = vendor.id left join ata_codes on ata_codes.id = inv.ata_chapter WHERE inv.location_id = :location_id and invloc.status="1"',
                        ['location_id' => $postData['id']],
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
                $html .='</body>
                </html>';
            }
            //echo $html;exit;
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
            $fileName = "InventoryLocationsQtyBarcode".date('YmdHis').".pdf";
            $mpdf->Output(WWW_ROOT.PDF_DIR.$fileName, "F");
            if($html) {
                $result = array('status'=>'success', 'data'=>ROOT_DIR.PDF_DIR.$fileName);
                echo json_encode($result);die;
            } else {
                $result = array('status'=>'failure', 'data'=>'');
                echo json_encode($result);die;
            }
            
        }
    }

?>