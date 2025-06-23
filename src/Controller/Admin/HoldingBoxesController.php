<?php
    namespace App\Controller\Admin;
    
    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use App\Model\Entity\InventoryPartManufacturer;
    use App\View\Helper\InventoryStatusHTMLHelper;
    use Cake\Datasource\FactoryLocator;
    use Cake\ORM\Locator\LocatorAwareTrait;
    
    class HoldingBoxesController extends AppController
    {
        protected \App\Model\Table\InventoryItemsTable $InventoryItems;
        protected \App\Model\Table\InventoriesTable $Inventories;

        public function initialize():void {
            parent::initialize();
            $this->loadComponent('Inventory');
            $this->loadComponent('InventoryFilter');
            $this->loadComponent('Plane');

            $this->InventoryItems = $this->fetchTable('InventoryItems');
            $this->Inventories = $this->fetchTable('Inventories');
        }

        public function index()
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

            $location = $this->Inventory->getAllLocations();
            $ataCode   = $this->AtaCode->getAtaCodes();
            $vendor = $this->Inventory->getVendorList();

            $installto = $this->Inventory->getAllInstallToInventory();
            
            $itemcatalogcount = $this->InventoryItems->find('all')->where(['in_holdingbox'=>'1'])->count();
            $inventoriescount = $this->Inventories->find('all')->where(['in_holdingbox'=>'1'])->count();

            $this->set(compact('actionItems', 'location', 'ataCode', 'vendor', 'installto', 'itemcatalogcount', 'inventoriescount'));
        }

        public function searchPhysicalInventory()
        {
            $query = [];        
            
            $requestData= $this->request->getData();

            $cond = "";
            
            if( isset($requestData['columns'][2]['search']['value']) && !empty($requestData['columns'][2]['search']['value'])){
                parse_str($requestData['columns'][2]['search']['value'], $requestData);
            }

            $queryData = $this->getRequest()->getQuery();
            $cond .= $this->InventoryFilter->completeInventoryFilter($requestData, $queryData);

            $query['count'] = "SELECT count(inv.id) AS count  FROM `inventories` as inv join inventory_items invitm on inv.inventory_item_id = invitm.id left join inventory_locations as invloc on inv.location_id = invloc.id WHERE inv.in_holdingbox = '1' ".$cond;

            $query['detail'] = "SELECT inv.id, inv.install_to, inv.serial_no, inv.qty, inv.cost, inv.currency, invloc.location_name, inv.inventory_item_id, inv.expiration, inv.modified, inv.status, inv.in_holdingbox, invitm.part_number, invitm.name, inv.created FROM `inventories` as inv join inventory_items invitm on inv.inventory_item_id = invitm.id left join inventory_locations as invloc on inv.location_id = invloc.id WHERE inv.in_holdingbox = '1' ".$cond;
            
            return $query;
        }

        public function ajaxPhysicalInventorySearch(){
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

            $query = $this->searchPhysicalInventory();

            $requestData= $this->request->getData();

            $cond = "";
            if( isset($requestData['columns'][0]['search']['value']) && !empty($requestData['columns'][0]['search']['value'])){
                $search = $requestData['columns'][0]['search']['value'];
                $cond.=" AND ( serial_no LIKE '%".$search."%' OR  display_name LIKE '%".$search."%')";
            }
            
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
                
                if(empty($row['location_name']) && $row['status'] == '2'){
                    $invenotriesdata = $this->Inventory->getInventoryInstallToDetails($row['install_to']);
                    $location = $invenotriesdata['_matchingData']['InventoryItems']['name'].' (PN:'.$invenotriesdata['_matchingData']['InventoryItems']['part_number'].') (SN:'.$invenotriesdata['serial_no'].')';
                }else if(empty($row['location_name']) && $row['status'] != 7 && $row['status'] != 9){
                    $location = '<span style="color:red;">[Inactive]</span>';
                }else{
                    $location = $row["location_name"];
                }

                $nestedData= [];
                $nestedData[] = '<input type="checkbox" data-val="'.$row['status'].'" item-id = "'.$row['inventory_item_id'].'" class="invPhyChkBoxCls" name="physicalchildcheckbox" value="'.$row['id'].'" >&nbsp;&nbsp;';
                $nestedData[] = $row["created"];
                $nestedData[] = $row["name"];
                $nestedData[] = $row["part_number"];
                $nestedData[] = $row["serial_no"];
                $nestedData[] = $location;
                $nestedData[] = $row["qty"];
                $nestedData[] = isset($row["expiration"]) ? date('d-M-Y', strtotime($row["expiration"])) : '-';
                $nestedData[] = !empty($row["cost"]) ? $row["cost"]*$row["qty"] : '-';
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

        public function searchItemCatalog()
        {
            $query = [];        
            
            $query['count'] = "SELECT count(id) AS count  FROM inventory_items WHERE in_holdingbox = '1' ";

            $query['detail'] = "SELECT id, name, part_number, unit_cost, is_this_item_serialized, item_type, default_uom, in_holdingbox, status, created FROM `inventory_items` WHERE in_holdingbox = '1' ";
            
            return $query;
        }

        public function ajaxItemCatalogSearch(){
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
            $requestData= $this->request->getData();

            $query = $this->searchItemCatalog();

            if( isset($requestData['columns'][2]['search']['value']) && !empty($requestData['columns'][2]['search']['value'])){
                parse_str($requestData['columns'][2]['search']['value'], $requestData);
            }
            
            $cond = $this->InventoryFilter->inventoryCatalogFilter($requestData);
            $requestData= $this->request->getData();
            //echo $cond;exit;
            $columns = array(
                0 => 'id',
                1 => 'created',
                2 => 'part_number',
                3 => 'name',
                4 => 'item_type',
                5 => 'is_this_item_serialized',
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
            $length = PAGINATION_LIMIT;

            $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
            $results = $conn->execute( $SQL )->fetchAll('assoc');
            
            $data = array();
            $itemtypearr = unserialize(INVENTORY_ITEM_TYPE);
            $defaultUOM = unserialize(DEFAULT_UOM);
            
            foreach ( $results as $row){
                
                $inactive = $row["status"] == '0' ? '&nbsp;<span class="label label-danger">Inactive</span>' : '';

                $nestedData= [];
                $nestedData[] = '<input type="checkbox" class="itmCatChkBoxCls noExl" name="catalogchildcheckbox" value="'.$row['id'].'" >';
                $nestedData[] = $row["created"];
                $nestedData[] = $row["part_number"].$inactive;
                $nestedData[] = $row["name"];
                $nestedData[] = isset($itemtypearr[$row["item_type"]]) ? $itemtypearr[$row["item_type"]] : '-';
                $nestedData[] = $row['is_this_item_serialized'] == 1 ? '<i class="fa fa-check"></i>' : '';

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

        public function getHoldingBoxCount(){
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

            $itemcatalogcount = $this->InventoryItems->find('all')->where(['in_holdingbox'=>'1'])->count();
            $inventoriescount = $this->Inventories->find('all')->where(['in_holdingbox'=>'1'])->count();
            
            $holdingboxcount = $itemcatalogcount+$inventoriescount;
            
            echo $holdingboxcount;die;
        }

        public function removeAllHoldingBox(){
            $actionItems='';
            $authUserData = $this->Authentication->getResult()->getData();
            if($authUserData['id'] != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('InventoryItems', $actionStatus))
                {
                    $actionItems = $actionStatus['InventoryItems'];
                }
            }

            if ($this->request->is(['post'])) {
                $postData = $this->request->getData();//echo "<pre>";print_r($postData);exit;
                if(!empty($postData['section_name'])){
                    $cond = array('in_holdingbox'=>'1');
                    if(!empty($postData['id'])){
                        $cond['id IN'] = $postData['id'];
                    }
                    $idcounts = !empty($postData['id']) ? count($postData['id']) : '0';
                    if($postData['section_name'] == 'inventory_catalog'){
                        if(!empty($postData['id'])){
                            $msg = $idcounts.' inventory item records removed from holding box';
                        }else{
                            $msg = 'All inventory items have been removed from your holding box.';
                        }
                        $res = $this->InventoryItems->updateAll(
                            array('in_holdingbox'=>'0', 'updated_by'=>$authUserData['id']),
                            $cond
                        );
                    }else if($postData['section_name'] == 'physical_inventory'){
                        if(!empty($postData['id'])){
                            $msg = $idcounts.' inventory quantity records removed from holding box';
                        }else{
                            $msg = 'All physical inventory items have been removed from your holding box.';
                        }
                        $res = $this->Inventories->updateAll(
                            array('in_holdingbox'=>'0', 'updated_by'=>$authUserData['id']),
                            $cond
                        );
                    }
  
                    if($res){
                        $this->Flash->success(__($msg));
                    }else{
                        $this->Flash->error(__('Something went wrong.'));
                    }
                    
                    return $this->redirect(['action' => 'index']);
                }else{
                    $this->Flash->error(__('Something went wrong.'));
                }
            }else{
                $this->Flash->error(__('Something went wrong.'));
            }
        }
    }
?>