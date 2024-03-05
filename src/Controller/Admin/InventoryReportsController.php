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

    class InventoryReportsController extends AppController
    {
        public function initialize() {
            parent::initialize();
            $this->loadModel('Inventories');
            $this->loadModel('InventoryItems');
            $this->loadModel('InventoryTransactionHistories');
            $this->loadComponent('Address');
            $this->loadComponent('Inventory');
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
                if(array_key_exists('Reports', $actionStatus))
                {
                    $actionItems = $actionStatus['Reports'];
                }    
            }

            $this->set(compact('actionItems'));
        }

        public function completeinventory()
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Reports', $actionStatus))
                {
                    $actionItems = $actionStatus['Reports'];
                }
            }

            $location = $this->Inventory->getAllLocations();
            $ataCode   = $this->AtaCode->getAtaCodes();
            $vendor = $this->Inventory->getVendorList();
            $installto = $this->Inventory->getAllInstallToInventory();
            //echo "<pre>";print_r($installto);exit;
            $this->set(compact('actionItems', 'location', 'ataCode', 'vendor', 'installto'));
        }

        public function ajaxCompleteInventorySearch(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('InventoryReports', $actionStatus))
                {
                    $actionItems = $actionStatus['InventoryReports'];
                }
            }
            $this->autoRender = false;
            $this->layout = 'ajax';
            $requestData= $this->request->data;

            $query = [];        
            
            $query['count'] = "SELECT count(inv.id) AS count, SUM(inv.qty*inv.cost) as total_cost  FROM `inventories` as inv join inventory_items invitm on inv.inventory_item_id = invitm.id left join inventory_locations as invloc on inv.location_id = invloc.id WHERE 1=1 ";

            $query['detail'] = "SELECT inv.id, inv.serial_no, inv.qty, inv.cost, inv.currency, invloc.location_name, inv.inventory_item_id, inv.received, inv.modified, inv.status, inv.in_holdingbox, invitm.item_instock, invitm.part_number, invitm.name, inv.display_name, inv.uom, (inv.qty*inv.cost) as total_cost FROM `inventories` as inv join inventory_items invitm on inv.inventory_item_id = invitm.id left join inventory_locations as invloc on inv.location_id = invloc.id WHERE 1=1 ";

            $cond = "";
            
            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                parse_str($requestData['columns'][1]['search']['value'], $requestData);
            }

            $cond .= $this->InventoryFilter->completeInventoryFilter($requestData);
            $requestData= $this->request->data;
            //echo $cond;exit;
            $columns = array(
                0 => 'inv.id',
                1 => 'invitm.part_number',
                //2 => 'invitm.name',
                2 => 'inv.serial_no',
                //4 => 'inv.display_name',
                3 => 'inv.qty',
                4 => 'total_cost',
                5 => 'invloc.location_name',
                6 => 'inv.status',
            );

            $count = $query['count'].$cond;
            $detail = $query['detail'].$cond;
            $totalCount = $query['count'];

            $conn = ConnectionManager::get('default');
            $results = $conn->execute($count)->fetchAll('assoc');
            $totalData = isset($results[0]['count']) ? $results[0]['count'] : 0;
            $totalcost = isset($results[0]['total_cost']) ? $results[0]['total_cost'] : 0;

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
            $currencyarr = unserialize(CURRENCY);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $InventoryStatusHTMLHelper = new InventoryStatusHTMLHelper(new \Cake\View\View());

            foreach ( $results as $row){
                $statushtml = $InventoryStatusHTMLHelper->getInventoriesStatusHTML($row['status']);
                
                $inholdingbox = !empty($row['in_holdingbox']) ? '<img src="../../images/icons/holdingbox.png" style="width:20px; height:20px;">' : '';
                $inactive = $row["status"] == '0' ? '&nbsp;<span class="label label-danger">Inactive</span>' : '';

                $nestedData= [];
                $nestedData[] = '<input type="checkbox" class="chkBoxCls noExl" name="childcheckbox" value="'.$row['id'].'" >&nbsp;&nbsp;'.$inholdingbox;
                $nestedData[] = $row["part_number"].$inactive.'<br/>'.$row["name"];
                $nestedData[] = $row["serial_no"].'<br/>'.$row["display_name"];
                $nestedData[] = $row["qty"].' '.$defaultUOM[$row['uom']];
                $nestedData[] = '<span class="invcost">'.(!empty($row['total_cost']) ? $row['total_cost'] : '0').'</span><span style="padding-left:5px;">'.$currencyarr[$row['currency']].'</span>';
                $nestedData[] = isset($row['location_name']) ? $row['location_name'] : '-';
                $nestedData[] = $statushtml;

                $data[] = $nestedData;

            }

            $roles = array(
                "draw"            => intval( $requestData['draw'] ),
                "recordsTotal"    => intval( $totalRecords ),
                "recordsFiltered" => intval( $totalFiltered ),
                "data"            => $data,
                "totalcost"       => $totalcost
            );
        
            echo json_encode($roles);die;
            
        }

        public function exportCompleteInventory(){
            
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
            
            //echo "<pre>";print_r($requestData);exit;
            
            $query = "SELECT invitm.id as invitm_id, invitm.name, invitm.part_number, invitm.item_type, invitm.exchange_cost, inv.id, inv.uom, invitm.in_holdingbox, inv.status, inv.cost, invloc.location_name, inv.serial_no, inv.display_name, inv.currency, inv.expiration, inv.qty, invitm.capital_equipment, inv.tags, vendor.name as vendor_name, invmfg.name as invmfg_name, inv.ata_chapter, inv.notes, (inv.qty*inv.cost) as total_cost FROM `inventory_items` as invitm join inventories as inv on invitm.id = inv.inventory_item_id left join inventory_locations as invloc on inv.location_id = invloc.id left join inventory_part_manufacturers as invmfg on invmfg.id = invitm.manufacturer_id left join inventory_vendors as vendor on vendor.id = inv.vendor WHERE 1=1 ";
            
            $requestData= $this->request->query;

            $cond = $this->InventoryFilter->completeInventoryFilter($requestData);
            
            //echo $cond;exit;
            $columns = array(
                0 => 'inv.id',
                1 => 'invitm.part_number',
                //2 => 'invitm.name',
                2 => 'inv.serial_no',
                //4 => 'inv.display_name',
                3 => 'invitm.qty',
                4 => 'total_cost',
                5 => 'invloc.location_name',
                6 => 'inv.status',
            );

            $query = $query.$cond;
            
            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sord = 'asc';

            $SQL = $query." ORDER BY $sidx $sord";//echo $SQL;exit;
            $inventoryreportitems = $conn->execute( $SQL )->fetchAll('assoc');
            
            $setData = '';  
            $invItemType = unserialize(INVENTORY_ITEM_TYPE);
            $inventoryStatus = unserialize(INVENTORY_STATUS);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $currencyCode = unserialize(CURRENCY);
            $invConditions = unserialize(INVENTORY_CONDITION);
            
            foreach($inventoryreportitems as $invrepitems){
                $dataTable .='
                            <tr>
                                <td>'.$invrepitems['name'].'</td>
                                <td>'.$invrepitems['part_number'].'</td>
                                <td>'.$invrepitems['serial_no'].'</td>
                                <td>'.$invrepitems['display_name'].'</td>
                                <td></td>
                                <td></td>
                                <td>'.(isset($invrepitems['location_name']) ? $invrepitems['location_name'] : '').'</td>
                                <td>'.$inventoryStatus[$invrepitems['status']].'</td>
                                <td>'.$invrepitems['qty'].'</td>
                                <td>'.$defaultUOM[$invrepitems['uom']].'</td>
                                <td>'.(!empty($invrepitems['expiration']) ? date('m/d/Y', strtotime($invrepitems['expiration'])) : '').'</td>
                                <td>'.$invrepitems['cost'].'</td>
                                <td>'.(!empty($invrepitems['cost']) ? $invrepitems['cost']*$invrepitems['qty'] : '').'</td>
                                <td>'.$invrepitems['exchange_cost'].'</td>
                                <td>'.$currencyCode[$invrepitems['currency']].'</td>
                                <td>'.$invrepitems['ata_chapter'].'</td>
                                <td>'.(isset($invrepitems['vendor_name']) ? $invrepitems['vendor_name'] : '').'</td>
                                <td>'.(isset($invrepitems['invmfg_name']) ? $invrepitems['invmfg_name'] : '').'</td>
                                <td>'.$invrepitems['notes'].'</td>
                                <td>'.(!empty($invrepitems['warranty_expire']) ? date('m/d/Y', strtotime($invrepitems['warranty_expire'])) : '').'</td>
                                <td>'.(!empty($invrepitems['conditions']) ? $invConditions[$invrepitems['conditions']] : '').'</td>
                                <td>'.(!empty($invrepitems['item_type']) ? $invItemType[$invrepitems['item_type']] : '').'</td>
                                <td>'.$invrepitems['capital_equipment'].'</td>
                                <td>'.$invrepitems['id'].'</td>
                                <td>'.$invrepitems['invitm_id'].'</td>
                                <td>'.$invrepitems['tags'].'</td>
                            </tr>';
                
            }  
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=CompleteInventory".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }
        
        public function expiringinventory()
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Reports', $actionStatus))
                {
                    $actionItems = $actionStatus['Reports'];
                }
            }

            $pagesource = isset($this->request->query['from']) ? $this->request->query['from'] : '';
            $minexpiration = '';
            if($pagesource == 'exprd'){
                $expirationdate = date('m-d-Y', strtotime("-1 days"));
            }else{
                $minexpiration = date('m-d-Y');
                $expirationdate = date('m-d-Y', strtotime("+1 month -1 days"));
            }

            $location = $this->Inventory->getAllLocations();
            
            $this->set(compact('actionItems', 'location', 'expirationdate', 'minexpiration', 'pagesource'));
        }

        public function ajaxExpiringInventorySearch(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('InventoryReports', $actionStatus))
                {
                    $actionItems = $actionStatus['InventoryReports'];
                }
            }
            $this->autoRender = false;
            $this->layout = 'ajax';
            $requestData= $this->request->data;

            $query = [];
            
            $query['count'] = "SELECT count(inv.id) AS count  FROM `inventory_items` invitm join inventories inv on invitm.id = inv.inventory_item_id left join inventory_locations invloc on inv.location_id = invloc.id WHERE inv.expiration is not null ";

            $query['detail'] = "SELECT invitm.name, invitm.part_number, inv.id, inv.uom, invitm.in_holdingbox, inv.status, inv.cost, invloc.location_name, inv.serial_no, inv.display_name, inv.currency, inv.expiration, inv.qty FROM `inventory_items` as invitm join inventories as inv on invitm.id = inv.inventory_item_id left join inventory_locations as invloc on inv.location_id = invloc.id WHERE inv.expiration is not null ";
            
            $cond = "";
            
            /*$statusarr = array('1', '2', '7', '9', '12');
            if( isset($requestData['columns'][4]['search']['value']) && !empty($requestData['columns'][4]['search']['value'])){
                $filterVal = $requestData['columns'][4]['search']['value'];
                $statusarr = array($filterVal);
            }else {
                $result = "'" . implode ( "', '", $statusarr ) . "'";
                $cond.=" AND (inv.status in ($result))";
            }*/
            
            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                parse_str($requestData['columns'][1]['search']['value'], $requestData);
            }
            
            //print_r($requestData);exit;
            $cond .= $this->InventoryFilter->expiringInventoryFilter($requestData);
            $requestData= $this->request->data;

            //echo $cond;exit;
            $columns = array(
                0 => 'inv.id',
                1 => 'invitm.name',
                2 => 'invitm.part_number',
                4 => 'invloc.location_name',
                5 => 'inv.status',
                6 => 'inv.qty',
                7 => 'inv.expiration'
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

            $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";//echo $SQL;exit;
            $results = $conn->execute( $SQL )->fetchAll('assoc');

            $data = array();
            $currencyarr = unserialize(CURRENCY);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $inventoryStatus = unserialize(INVENTORY_STATUS);
            
            foreach ( $results as $row){
                $nestedData= [];
                $nestedData[] = '<input type="checkbox" class="chkBoxCls noExl" name="childcheckbox" value="'.$row['id'].'" >';
                $nestedData[] = $row["name"];
                $nestedData[] = $row["part_number"];
                $nestedData[] = $row["serial_no"];
                $nestedData[] = isset($row['location_name']) ? $row['location_name'] : '-';
                $nestedData[] = $inventoryStatus[$row['status']];
                $nestedData[] = $row['qty'];
                $nestedData[] = date('d-M-Y', strtotime($row['expiration']));

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

        public function exportExpiringInventory(){
            
            $dataTable = '';
            $dataTable .='<table class="table">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Part Number</th>
                                        <th>Div. Abbr.</th>
                                        <th>Division Name</th>
                                        <th>Serial / Lot</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Qty.</th>
                                        <th>Expiration</th>
                                        <th>Capital Equipment</th>
                                        <th>Inventory Tags</th>
                                    </tr>
                                </thead>
                        <tbody>';
            
            //echo "<pre>";print_r($requestData);exit;
            
            $query = "SELECT invitm.name, invitm.part_number, inv.id, inv.uom, invitm.in_holdingbox, inv.status, inv.cost, invloc.location_name, inv.serial_no, inv.display_name, inv.currency, inv.expiration, inv.qty, invitm.capital_equipment, inv.tags FROM `inventory_items` as invitm join inventories as inv on invitm.id = inv.inventory_item_id left join inventory_locations as invloc on inv.location_id = invloc.id WHERE inv.expiration is not null ";
            
            $requestData= $this->request->query;
            $cond = $this->InventoryFilter->expiringInventoryFilter($requestData);
            
            //echo $cond;exit;
            $columns = array(
                0 => 'inv.id',
                1 => 'invitm.name',
                2 => 'invitm.part_number',
                4 => 'invloc.location_name',
                5 => 'inv.status',
                6 => 'inv.qty',
                7 => 'inv.expiration'
            );

            $query = $query.$cond;
            
            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sord = 'asc';

            $SQL = $query." ORDER BY $sidx $sord";//echo $SQL;exit;
            $inventoryreportitems = $conn->execute( $SQL )->fetchAll('assoc');
            
            $setData = '';  
            $invItemType = unserialize(INVENTORY_ITEM_TYPE);
            $inventoryStatus = unserialize(INVENTORY_STATUS);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $currencyCode = unserialize(CURRENCY);
            $invConditions = unserialize(INVENTORY_CONDITION);
            
            foreach($inventoryreportitems as $invrepitems){
                $dataTable .='
                            <tr>
                                <td>'.$invrepitems['name'].'</td>
                                <td>'.$invrepitems['part_number'].'</td>
                                <td></td>
                                <td></td>
                                <td>'.$invrepitems['serial_no'].'</td>
                                <td>'.(isset($invrepitems['location_name']) ? $invrepitems['location_name'] : '').'</td>
                                <td>'.$inventoryStatus[$invrepitems['status']].'</td>
                                <td>'.$invrepitems['qty'].'</td>
                                <td>'.(!empty($invrepitems['expiration']) ? date('m/d/Y', strtotime($invrepitems['expiration'])) : '').'</td>
                                <td>'.$invrepitems['capital_equipment'].'</td>
                                <td>'.$invrepitems['tags'].'</td>
                            </tr>';
                
            }  
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=ExpiringInventory".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }

        public function belowthresholdinventory()
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Reports', $actionStatus))
                {
                    $actionItems = $actionStatus['Reports'];
                }
            }
            
            $location = $this->Inventory->getAllLocations();
            
            $this->set(compact('actionItems', 'location'));
        }

        public function ajaxBelowThresholdSearch(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('InventoryReports', $actionStatus))
                {
                    $actionItems = $actionStatus['InventoryReports'];
                }
            }
            $this->autoRender = false;
            $this->layout = 'ajax';
            $requestData= $this->request->data;

            $query = [];
            
            $query['count'] = "SELECT count(invitm.id) AS count  FROM `inventory_items` as invitm left join inventory_item_thresholds as invitmthreshold on invitm.id = invitmthreshold.inventory_item_id left join inventory_locations as invloc on invitmthreshold.location_id = invloc.id WHERE 1=1 ";

            $query['detail'] = "SELECT invitm.id, name, invitm.part_number, invitm.unit_cost, invitm.currency, invitm.default_uom, CASE WHEN invloc.location_name is null THEN 'Global' ELSE invloc.location_name END as location_name, (CASE WHEN invitm.item_instock >= invitmthreshold.safety_stock_threshold OR invitmthreshold.safety_stock_threshold is null THEN invitm.safety_stock_threshold ELSE invitmthreshold.safety_stock_threshold END) as total_safety_stock_threshold, invitm.item_instock, (select count(*) from inventory_po_items where inventory_item_id = invitm.id) as ordered FROM `inventory_items` as invitm left join inventory_item_thresholds as invitmthreshold on invitm.id = invitmthreshold.inventory_item_id left join inventory_locations as invloc on invitmthreshold.location_id = invloc.id WHERE 1=1 ";

            $query1 = [];
            
            $query1['count'] = " UNION SELECT count(invitm.id) AS count  FROM `inventory_items` as invitm WHERE 1=1 ";

            $query1['detail'] = " UNION SELECT invitm.id, name, invitm.part_number, invitm.unit_cost, invitm.currency, invitm.default_uom, 'Global' AS location_name, invitm.safety_stock_threshold as total_safety_stock_threshold, invitm.item_instock, (select count(*) from inventory_po_items where inventory_item_id = invitm.id) as ordered FROM `inventory_items` as invitm WHERE 1=1 ";
            
            $cond1 = " AND invitm.item_instock < invitmthreshold.safety_stock_threshold ";

            $cond2 = " AND invitm.item_instock < invitm.safety_stock_threshold";

            $cond = " AND invitm.status = '1'";
            
            $islocationfilter = 0;
            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                parse_str($requestData['columns'][1]['search']['value'], $requestData);
            }

            $cond .= $this->InventoryFilter->thresholdInventoryFilter($requestData);

            $requestData= $this->request->data;

            //echo $cond;exit;
            $columns = array(
                0 => 'id',
                1 => 'name',
                2 => 'part_number',
                3 => 'location_name',
                4 => 'item_instock',
                5 => 'total_safety_stock_threshold',
                6 => 'ordered',
                7 => 'unit_cost'
            );

            if(empty($requestData['location_id'])){
                $count = $query['detail'].$cond.$cond1.$query1['detail'].$cond.$cond2;
                $detail = $query['detail'].$cond.$cond1.$query1['detail'].$cond.$cond2;
                $totalCount = $query['detail'].$query1['detail'];
            }else{
                $count = $query['detail'].$cond.$cond1;
                $detail = $query['detail'].$cond.$cond1;
                $totalCount = $query['detail'];
            }
            $conn = ConnectionManager::get('default');
            $results = $conn->execute($count)->fetchAll('assoc');
            $totalData = count($results);

            $totalFiltered = $totalData;
            $results = $conn->execute( $totalCount )->fetchAll('assoc');
            $totalRecords = count($results);
            
            $sidx = $columns[$requestData['order'][0]['column']];
            $sord = $requestData['order'][0]['dir'];
            $start = $requestData['start'];
            $length = PAGINATION_LIMIT;

            $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
            $results = $conn->execute( $SQL )->fetchAll('assoc');

            $data = array();
            $currencyarr = unserialize(CURRENCY);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $inventoryStatus = unserialize(INVENTORY_STATUS);
            
            foreach ( $results as $row){
                $instock = isset($row["instock"]) ? $row["instock"] : 0;
                $unitcost = !empty($row['unit_cost']) ? $row['unit_cost'] : '0';
                
                $nestedData= [];
                $nestedData[] = '<input type="checkbox" class="chkBoxCls noExl" name="childcheckbox" value="'.$row['id'].'" >';
                $nestedData[] = $row["name"];
                $nestedData[] = $row["part_number"];
                $nestedData[] = $row['location_name'];
                $nestedData[] = $row['item_instock'].' '.$defaultUOM[$row['default_uom']];
                $nestedData[] = $row['total_safety_stock_threshold'];
                $nestedData[] = $row['ordered'];
                $nestedData[] = $unitcost.(!empty($row['currency']) ? ' '.$currencyarr[$row['currency']] : '');

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

        public function exportBelowThresholdInventory(){
            
            $dataTable = '';
            $dataTable .='<table class="table">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Part Number</th>
                                        <th>Div. Abbr.</th>
                                        <th>Division Name</th>
                                        <th>Location</th>
                                        <th>In Stock</th>
                                        <th>UOM</th>
                                        <th>Safety Stock</th>
                                        <th>On Order</th>
                                        <th>Unit Cost</th>
                                        <th>Currency Code</th>
                                        <th>Capital Equipment</th>
                                    </tr>
                                </thead>
                        <tbody>';
            
            $query = "SELECT invitm.id, invitm.name, invitm.part_number, invitm.unit_cost, invitm.currency, invitm.default_uom, CASE WHEN invloc.location_name is null THEN 'Global' ELSE invloc.location_name END as location_name, (CASE WHEN invitm.item_instock >= invitmthreshold.safety_stock_threshold OR invitmthreshold.safety_stock_threshold is null THEN invitm.safety_stock_threshold ELSE invitmthreshold.safety_stock_threshold END) as total_safety_stock_threshold, invitm.item_instock, (select count(*) from inventory_po_items where inventory_item_id = invitm.id) as ordered, invitm.capital_equipment FROM `inventory_items` as invitm left join inventory_item_thresholds as invitmthreshold on invitm.id = invitmthreshold.inventory_item_id left join inventory_locations as invloc on invitmthreshold.location_id = invloc.id WHERE invitm.status = '1' AND invitm.item_instock < invitmthreshold.safety_stock_threshold";
            
            $query1 = " UNION SELECT invitm.id, invitm.name, invitm.part_number, invitm.unit_cost, invitm.currency, invitm.default_uom, 'Global' AS location_name, invitm.safety_stock_threshold as total_safety_stock_threshold, invitm.item_instock, (select count(*) from inventory_po_items where inventory_item_id = invitm.id) as ordered, invitm.capital_equipment FROM `inventory_items` as invitm WHERE invitm.status = '1' AND invitm.item_instock < invitm.safety_stock_threshold";
            
            $requestData= $this->request->query;
            //echo "<pre>";print_r($requestData);exit;

            $cond = $this->InventoryFilter->thresholdInventoryFilter($requestData);
            
            //echo $cond;exit;
            $columns = array(
                0 => 'id',
                1 => 'name',
                2 => 'part_number',
                3 => 'location_name',
                4 => 'item_instock',
                5 => 'total_safety_stock_threshold',
                6 => 'ordered',
                7 => 'unit_cost'
            );

            if(empty($requestData['location_id'])){
                $query = $query.$cond.$query1.$cond;
            }else{
                $query = $query.$cond;
            }
            
            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sord = 'asc';

            $SQL = $query." ORDER BY $sidx $sord";//echo $SQL;exit;
            $inventoryreportitems = $conn->execute( $SQL )->fetchAll('assoc');
            
            $currencyarr = unserialize(CURRENCY);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $inventoryStatus = unserialize(INVENTORY_STATUS);
            
            foreach($inventoryreportitems as $invrepitems){
                $instock = isset($invrepitems["instock"]) ? $invrepitems["instock"] : 0;
                $unitcost = !empty($invrepitems['unit_cost']) ? $invrepitems['unit_cost'] : '0';
                
                $dataTable .='
                            <tr>
                                <td>'.$invrepitems['name'].'</td>
                                <td>'.$invrepitems['part_number'].'</td>
                                <td></td>
                                <td></td>
                                <td>'.$invrepitems['location_name'].'</td>
                                <td>'.$invrepitems['item_instock'].'</td>
                                <td>'.$defaultUOM[$invrepitems['default_uom']].'</td>
                                <td>'.$invrepitems['total_safety_stock_threshold'].'</td>
                                <td>'.$invrepitems['ordered'].'</td>
                                <td>'.$unitcost.'</td>
                                <td>'.(!empty($invrepitems['currency']) ? $currencyarr[$invrepitems['currency']] : '').'</td>
                                <td>'.$invrepitems['capital_equipment'].'</td>
                            </tr>';
            }
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=InventoryBelowThresholdReport".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }

        public function completethresholdinventory()
        {
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Reports', $actionStatus))
                {
                    $actionItems = $actionStatus['Reports'];
                }
            }
            
            $location = $this->Inventory->getAllLocations();
            
            $this->set(compact('actionItems', 'location'));
        }

        public function ajaxCompleteThresholdSearch(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('InventoryReports', $actionStatus))
                {
                    $actionItems = $actionStatus['InventoryReports'];
                }
            }
            $this->autoRender = false;
            $this->layout = 'ajax';
            $requestData= $this->request->data;

            $query = [];
            
            $query['count'] = "SELECT count(invitm.id) AS count  FROM `inventory_items` as invitm left join inventory_item_thresholds as invitmthreshold on invitm.id = invitmthreshold.inventory_item_id left join inventory_locations as invloc on invitmthreshold.location_id = invloc.id WHERE 1=1 ";

            $query['detail'] = "SELECT invitm.id, name, invitm.part_number, invitm.unit_cost, invitm.currency, invitm.default_uom, CASE WHEN invloc.location_name is null THEN 'Global' ELSE invloc.location_name END as location_name, (CASE WHEN invitm.item_instock >= invitmthreshold.safety_stock_threshold OR invitmthreshold.safety_stock_threshold is null THEN invitm.safety_stock_threshold ELSE invitmthreshold.safety_stock_threshold END) as total_safety_stock_threshold, invitm.item_instock, (select count(*) from inventory_po_items where inventory_item_id = invitm.id) as ordered FROM `inventory_items` as invitm left join inventory_item_thresholds as invitmthreshold on invitm.id = invitmthreshold.inventory_item_id left join inventory_locations as invloc on invitmthreshold.location_id = invloc.id WHERE 1=1 ";

            $query1 = [];
            
            $query1['count'] = " UNION SELECT count(invitm.id) AS count  FROM `inventory_items` as invitm WHERE 1=1 ";

            $query1['detail'] = " UNION SELECT invitm.id, name, invitm.part_number, invitm.unit_cost, invitm.currency, invitm.default_uom, 'Global' AS location_name, invitm.safety_stock_threshold as total_safety_stock_threshold, invitm.item_instock, (select count(*) from inventory_po_items where inventory_item_id = invitm.id) as ordered FROM `inventory_items` as invitm WHERE 1=1 ";
            
            $cond1 = " AND invitm.item_instock < invitmthreshold.safety_stock_threshold ";

            //$cond2 = " AND invitm.item_instock < invitm.safety_stock_threshold";

            $cond = " AND invitm.status = '1'";
            
            $islocationfilter = 0;
            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                parse_str($requestData['columns'][1]['search']['value'], $requestData);
            }
            $cond .= $this->InventoryFilter->thresholdInventoryFilter($requestData);

            $requestData= $this->request->data;
            //echo $cond;exit;
            $columns = array(
                0 => 'id',
                1 => 'name',
                2 => 'part_number',
                3 => 'location_name',
                4 => 'item_instock',
                5 => 'total_safety_stock_threshold',
                6 => 'ordered',
                7 => 'unit_cost'
            );

            if(empty($islocationfilter)){
                $count = $query['detail'].$cond.$cond1.$query1['detail'].$cond;
                $detail = $query['detail'].$cond.$cond1.$query1['detail'].$cond;
                $totalCount = $query['detail'].$query1['detail'];
            }else{
                $count = $query['detail'].$cond.$cond1;
                $detail = $query['detail'].$cond.$cond1;
                $totalCount = $query['detail'];
            }
            $conn = ConnectionManager::get('default');
            $results = $conn->execute($count)->fetchAll('assoc');
            $totalData = count($results);

            $totalFiltered = $totalData;
            $results = $conn->execute( $totalCount )->fetchAll('assoc');
            $totalRecords = count($results);
            
            $sidx = $columns[$requestData['order'][0]['column']];
            $sord = $requestData['order'][0]['dir'];
            $start = $requestData['start'];
            $length = PAGINATION_LIMIT;

            $SQL = $detail." ORDER BY $sidx $sord LIMIT $start , $length ";
            $results = $conn->execute( $SQL )->fetchAll('assoc');

            $data = array();
            $currencyarr = unserialize(CURRENCY);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $inventoryStatus = unserialize(INVENTORY_STATUS);
            
            foreach ( $results as $row){
                $instock = isset($row["instock"]) ? $row["instock"] : 0;
                $unitcost = !empty($row['unit_cost']) ? $row['unit_cost'] : '0';
                
                $nestedData= [];
                $nestedData[] = '<input type="checkbox" class="chkBoxCls noExl" name="childcheckbox" value="'.$row['id'].'" >';
                $nestedData[] = $row["name"];
                $nestedData[] = $row["part_number"];
                $nestedData[] = $row['location_name'];
                $nestedData[] = $row['item_instock'].' '.$defaultUOM[$row['default_uom']];
                $nestedData[] = $row['total_safety_stock_threshold'];
                $nestedData[] = $row['ordered'];
                $nestedData[] = $unitcost.(!empty($row['currency']) ? ' '.$currencyarr[$row['currency']] : '');

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

        public function exportCompleteThresholdInventory(){
            
            $dataTable = '';
            $dataTable .='<table class="table">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Part Number</th>
                                        <th>Div. Abbr.</th>
                                        <th>Division Name</th>
                                        <th>Location</th>
                                        <th>In Stock</th>
                                        <th>UOM</th>
                                        <th>Safety Stock</th>
                                        <th>On Order</th>
                                        <th>Unit Cost</th>
                                        <th>Currency Code</th>
                                        <th>Capital Equipment</th>
                                    </tr>
                                </thead>
                        <tbody>';
            
            $query = "SELECT invitm.id, invitm.name, invitm.part_number, invitm.unit_cost, invitm.currency, invitm.default_uom, CASE WHEN invloc.location_name is null THEN 'Global' ELSE invloc.location_name END as location_name, (CASE WHEN invitm.item_instock >= invitmthreshold.safety_stock_threshold OR invitmthreshold.safety_stock_threshold is null THEN invitm.safety_stock_threshold ELSE invitmthreshold.safety_stock_threshold END) as total_safety_stock_threshold, invitm.item_instock, (select count(*) from inventory_po_items where inventory_item_id = invitm.id) as ordered, invitm.capital_equipment FROM `inventory_items` as invitm left join inventory_item_thresholds as invitmthreshold on invitm.id = invitmthreshold.inventory_item_id left join inventory_locations as invloc on invitmthreshold.location_id = invloc.id WHERE invitm.status = '1' AND invitm.item_instock < invitmthreshold.safety_stock_threshold";
            
            $query1 = " UNION SELECT invitm.id, invitm.name, invitm.part_number, invitm.unit_cost, invitm.currency, invitm.default_uom, 'Global' AS location_name, invitm.safety_stock_threshold as total_safety_stock_threshold, invitm.item_instock, (select count(*) from inventory_po_items where inventory_item_id = invitm.id) as ordered, invitm.capital_equipment FROM `inventory_items` as invitm WHERE invitm.status = '1'";
            
            $requestData= $this->request->query;
            $cond = $this->InventoryFilter->thresholdInventoryFilter($requestData);
            
            //echo "<pre>";print_r($requestData);exit;

            //echo $cond;exit;
            $columns = array(
                0 => 'id',
                1 => 'name',
                2 => 'part_number',
                3 => 'location_name',
                4 => 'item_instock',
                5 => 'total_safety_stock_threshold',
                6 => 'ordered',
                7 => 'unit_cost'
            );

            if(empty($requestData['location_id'])){
                $query = $query.$cond.$query1.$cond;
            }else{
                $query = $query.$cond;
            }
            
            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sord = 'asc';

            $SQL = $query." ORDER BY $sidx $sord";//echo $SQL;exit;
            $inventoryreportitems = $conn->execute( $SQL )->fetchAll('assoc');
            
            $currencyarr = unserialize(CURRENCY);
            $defaultUOM = unserialize(DEFAULT_UOM);
            $inventoryStatus = unserialize(INVENTORY_STATUS);
            
            foreach($inventoryreportitems as $invrepitems){
                $instock = isset($invrepitems["instock"]) ? $invrepitems["instock"] : 0;
                $unitcost = !empty($invrepitems['unit_cost']) ? $invrepitems['unit_cost'] : '0';
                
                $dataTable .='
                            <tr>
                                <td>'.$invrepitems['name'].'</td>
                                <td>'.$invrepitems['part_number'].'</td>
                                <td></td>
                                <td></td>
                                <td>'.$invrepitems['location_name'].'</td>
                                <td>'.$invrepitems['item_instock'].'</td>
                                <td>'.$defaultUOM[$invrepitems['default_uom']].'</td>
                                <td>'.$invrepitems['total_safety_stock_threshold'].'</td>
                                <td>'.$invrepitems['ordered'].'</td>
                                <td>'.$unitcost.'</td>
                                <td>'.(!empty($invrepitems['currency']) ? $currencyarr[$invrepitems['currency']] : '').'</td>
                                <td>'.$invrepitems['capital_equipment'].'</td>
                            </tr>';
            }
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=InventoryCompleteThresholdReport".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }

        public function transactionhistory(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Reports', $actionStatus))
                {
                    $actionItems = $actionStatus['Reports'];
                }
            }
            
            $this->set(compact('actionItems'));
        }

        public function ajaxTransctionHistorySearch(){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('InventoryReports', $actionStatus))
                {
                    $actionItems = $actionStatus['InventoryReports'];
                }
            }
            $this->autoRender = false;
            $this->layout = 'ajax';
            $requestData= $this->request->data;

            $query = [];
            
            $query['count'] = "SELECT count(invth.id) as count, SUM(invth.qty*invth.unit_cost) as cost  FROM `inventory_transaction_histories` as invth join inventories as inv on invth.inventory_id = inv.id join inventory_items as invitm on inv.inventory_item_id = invitm.id WHERE 1=1 ";

            $query['detail'] = "SELECT invth.id, invth.created, invth.from_description, invth.to_description, invitm.currency, invitm.part_number, invitm.name, inv.serial_no, inv.display_name, invth.qty, invth.unit_cost, (invth.qty*invth.unit_cost) as cost, invth.type FROM `inventory_transaction_histories` as invth join inventories as inv on invth.inventory_id = inv.id join inventory_items as invitm on inv.inventory_item_id = invitm.id WHERE 1=1 ";

            //$cond = " AND invitm.status = '1'";
            $cond = "";
            
            if( isset($requestData['columns'][1]['search']['value']) && !empty($requestData['columns'][1]['search']['value'])){
                parse_str($requestData['columns'][1]['search']['value'], $requestData);
            }else{
                $cond.=" AND DATE(invth.created) >= '".date("Y-m-d", strtotime('-30 days'))."' AND DATE(invth.created) <= '".date("Y-m-d")."'";
            }
            $cond .= $this->InventoryFilter->inventoryTransactionHistoryFilter($requestData);

            $requestData= $this->request->data;//echo "<pre>";print_r($requestData);exit;
            //echo $cond;exit;
            
            $columns = array(
                0 => 'invth.created',
                1 => 'invth.from_description',
                2 => 'invth.to_description',
                3 => 'invitm.name',
                4 => 'invitm.part_number',
                5 => 'inv.serial_no',
                6 => 'invth.qty',
                7 => 'invth.unit_cost',
                8 => 'cost',
                9 => 'invth.type',
            );

            $count = $query['count'].$cond;
            $detail = $query['detail'].$cond;
            $totalCount = $query['count'];

            $conn = ConnectionManager::get('default');
            $results = $conn->execute($count)->fetchAll('assoc');
            $totalData = isset($results[0]['count']) ? $results[0]['count'] : 0;
            $totalcost = isset($results[0]['cost']) ? $results[0]['cost'] : 0;

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
            $currencyarr = unserialize(CURRENCY);
            $transactionActionList = unserialize(TRANSACTION_ACTION_LIST);
            
            foreach ( $results as $row){
                $cost = isset($row["cost"]) ? $row["cost"] : 0;
                $unitcost = !empty($row['unit_cost']) ? $row['unit_cost'] : '0';
                
                $key = !empty($row['type']) ? array_search($row['type'], array_column($transactionActionList, 'id')) : '';
                $type = !empty($key) ? $transactionActionList[$key]['name'] : '';

                $nestedData= [];
                $nestedData[] = '<input type="hidden" class="transactionrepid" value="'.$row['id'].'">'.date("d-M-Y", strtotime($row['created']));
                $nestedData[] = '<b>From: </b>'.$row["from_description"];
                $nestedData[] = '<b>To: </b>'.$row["to_description"];
                $nestedData[] = $row["name"];
                $nestedData[] = $row['part_number'];
                $nestedData[] = $row['serial_no'].'<br/>'.$row['display_name'];
                $nestedData[] = $row['qty'];
                $nestedData[] = $unitcost.(!empty($row['currency']) ? ' '.$currencyarr[$row['currency']] : '');
                $nestedData[] = $cost.(!empty($row['currency']) ? ' '.$currencyarr[$row['currency']] : '');
                $nestedData[] = $type;

                $data[] = $nestedData;
            }

            $respdata = array(
                "draw"            => intval( $requestData['draw'] ),
                "recordsTotal"    => intval( $totalRecords ),
                "recordsFiltered" => intval( $totalFiltered ),
                "data"            => $data,
                "totalcost"       => $totalcost
            );
        
            echo json_encode($respdata);die;
            
        }

        public function exportInventoryTransactionHistory(){
            
            $dataTable = '';
            $dataTable .='<table class="table">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>From Status</th>
                                        <th>To Status</th>
                                        <th>Date</th>
                                        <th>From Description</th>
                                        <th>From Type</th>
                                        <th>To Description</th>
                                        <th>To Type</th>
                                        <th>Order Number</th>
                                        <th>From Div. Abbr.</th>
                                        <th>From Division Name</th>
                                        <th>To Div. Abbr.</th>
                                        <th>To Division Name</th>
                                        <th>Item Name</th>
                                        <th>Part Number</th>
                                        <th>Lot/Serial</th>
                                        <th>Display Name</th>
                                        <th>Quantity</th>
                                        <th>UOM</th>
                                        <th>Unit Cost</th>
                                        <th>Total Cost</th>
                                        <th>Order Line Item Quantity</th>
                                        <th>Order Line Item UOM</th>
                                        <th>Order Line Item Unit Cost</th>
                                        <th>Order Line Item Total Cost</th>
                                        <th>Account Code</th>
                                        <th>Reason / Notes</th>
                                        <th>Capital</th>
                                        <th>User</th>
                                        <th>TransactionId</th>
                                        <th>FromPhysicalInventoryId</th>
                                        <th>ToPhysicalInventoryId</th>
                                        <th>InventoryItemId</th>
                                        <th>Inventory Tags</th>
                                        <th>From Cost</th>
                                        <th>Overall Change in Cost</th>
                                        <th>PO Line Item ID</th>
                                        <th>RO Line Item ID</th>
                                        <th>From Condition</th>
                                        <th>To Condition</th>
                                        <th>From Item Type</th>
                                        <th>To Item Type</th>
                                        <th>ATA Chapter</th>
                                        <th>Inventory Reconciliation Cost</th>
                                    </tr>
                                </thead>
                        <tbody>';
            
            $query = "SELECT invth.*, invitm.id as inventory_item_id, inv.id as inventory_id, invitm.part_number, invitm.name, inv.serial_no, inv.display_name, inv.capital_equipment, u.full_name, (invth.qty*invth.unit_cost) as total_cost, invth.type, CASE WHEN shipping_order_id != '0' THEN (select shipping_order_number from inventory_shipping_orders where id = invth.shipping_order_id)  WHEN purchase_order_id != '0' THEN (select po_number from inventory_purchase_orders where id = invth.purchase_order_id)  WHEN repair_order_id != '0' THEN (select ro_number from inventory_repair_orders where id = invth.repair_order_id)  END as orderno FROM `inventory_transaction_histories` as invth join inventories as inv on invth.inventory_id = inv.id join inventory_items as invitm on inv.inventory_item_id join users as u on u.id = invth.added_by = invitm.id WHERE 1=1";
            
            $requestData= $this->request->query;
            $cond = $this->InventoryFilter->inventoryTransactionHistoryFilter($requestData);
            
            //echo "<pre>";print_r($requestData);exit;

            //echo $cond;exit;
            $columns = array(
                0 => 'id',
                1 => 'invth.created',
                2 => 'invth.from_description',
                3 => 'invth.to_description',
                4 => 'invitm.name',
                5 => 'invitm.part_number',
                6 => 'inv.serial_no',
                7 => 'invth.qty',
                8 => 'invth.unit_cost',
                9 => 'cost',
                10 => 'invth.type',
            );

            $conn = ConnectionManager::get('default');
            
            $sidx = $columns[$requestData['sortBy']];
            $sord = 'asc';

            $SQL = $query.$cond." ORDER BY $sidx $sord";//echo $SQL;exit;
            $transactionhistoryreport = $conn->execute( $SQL )->fetchAll('assoc');
            
            $invConditions = unserialize(INVENTORY_CONDITION);
            $invStatus = unserialize(INVENTORY_STATUS);
            $invItemType = unserialize(INVENTORY_ITEM_TYPE);
            $transactionActionList = unserialize(TRANSACTION_ACTION_LIST);
            $defaultUOM = unserialize(DEFAULT_UOM);
            
            foreach($transactionhistoryreport as $transactions){
                $key = !empty($transactions['type']) ? array_search($transactions['type'], array_column($transactionActionList, 'id')) : '';
                $actions = !empty($key) ? $transactionActionList[$key]['name'] : '';
                $unitcost = !empty($invrepitems['unit_cost']) ? $invrepitems['unit_cost'] : '0';
                
                $dataTable .='
                            <tr>
                                <td>'.$actions.'</td>
                                <td>'.(!empty($transactions['from_status']) ? $invStatus[$transactions['from_status']] :'').'</td>
                                <td>'.(!empty($transactions['to_status']) ? $invStatus[$transactions['to_status']] :'').'</td>
                                <td>'.date('m/d/Y', strtotime($transactions['created'])).'</td>
                                <td>'.$transactions['from_description'].'</td>
                                <td>'.(!empty($transactions['from_type']) ? $invItemType[$transactions['from_type']] : '').'</td>
                                <td>'.$transactions['to_description'].'</td>
                                <td>'.(!empty($transactions['to_type']) ? $invItemType[$transactions['to_type']] : '').'</td>
                                <td>'.$transactions['orderno'].'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>'.$transactions['name'].'</td>
                                <td>'.$transactions['part_number'].'</td>
                                <td>'.$transactions['serial_no'].'</td>
                                <td>'.$transactions['display_name'].'</td>
                                <td>'.$transactions['qty'].'</td>
                                <td>'.(!empty($transactions['uom']) ? $defaultUOM[$transactions['uom']] : '').'</td>
                                <td>'.$transactions['unit_cost'].'</td>
                                <td>'.$transactions['total_cost'].'</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>'.$transactions['account_code'].'</td>
                                <td>'.$transactions['reason'].'</td>
                                <td>'.$transactions['capital_equipment'].'</td>
                                <td>'.$transactions['full_name'].'</td>
                                <td>'.$transactions['id'].'</td>
                                <td>'.$transactions['inventory_id'].'</td>
                                <td>'.$transactions['inventory_id'].'</td>
                                <td>'.$transactions['inventory_item_id'].'</td>
                                <td>'.$transactions['tags'].'</td>
                                <td>'.$transactions['from_cost'].'</td>
                                <td>'.($transactions['total_cost'] - $transactions['from_cost']).'</td>
                                <td></td>
                                <td></td>
                                <td>'.(!empty($transactions['from_conditions']) ? $invConditions[$transactions['from_conditions']] : '').'</td>
                                <td>'.(!empty($transactions['to_conditions']) ? $invConditions[$transactions['to_conditions']] : '').'</td>
                                <td>'.(!empty($transactions['from_item_type']) ? $invItemType[$transactions['from_item_type']] : '').'</td>
                                <td>'.(!empty($transactions['to_item_type']) ? $invItemType[$transactions['to_item_type']] : '').'</td>
                                <td>'.$transactions['ata_chapter'].'</td>
                                <td>'.($transactions['total_cost'] - $transactions['from_cost']).'</td>
                            </tr>';
            }
            
            $dataTable .= '  </tbody></table>';

            header("Content-type: application/octet-stream");  
            header("Content-Disposition: attachment; filename=TransactionHistoryReport".date("Ymd").".xls");  
            header("Pragma: no-cache");  
            header("Expires: 0");  
            echo $dataTable;exit;
        }

        public function transactiondetail($id=null){
            $actionItems='';
            if($this->Auth->user('id') != 1) {
                $actionStatus = $this->checkAction();
                if(array_key_exists('Reports', $actionStatus))
                {
                    $actionItems = $actionStatus['Reports'];
                }
            }
            
            $transactionhistory = $this->InventoryTransactionHistories->find()->where(['InventoryTransactionHistories.id'=>$id])->select($this->InventoryTransactionHistories)->select(['invitms.name', 'invitms.currency', 'invitms.part_number', 'inv.display_name', 'inv.serial_no', 'users.email', 'users.full_name', 'fromlocation.location_name', 'tolocation.location_name', 'vendor.name', 'atacode.ata_code'])
            ->join([
                'inv' => [
                    'table' => 'inventories',
                    'type' => 'INNER',
                    'conditions' => 'inv.id = InventoryTransactionHistories.inventory_id',
                ]
            ])
            ->join([
                'invitms' => [
                    'table' => 'inventory_items',
                    'type' => 'INNER',
                    'conditions' => 'invitms.id = inv.inventory_item_id',
                ]
            ])
            ->join([
                'users' => [
                    'table' => 'users',
                    'type' => 'INNER',
                    'conditions' => 'users.id = InventoryTransactionHistories.added_by',
                ]
            ])
            ->join([
                'fromlocation' => [
                    'table' => 'inventory_locations',
                    'type' => 'LEFT',
                    'conditions' => 'fromlocation.id = InventoryTransactionHistories.from_location_id',
                ]
            ])
            ->join([
                'tolocation' => [
                    'table' => 'inventory_locations',
                    'type' => 'LEFT',
                    'conditions' => 'tolocation.id = InventoryTransactionHistories.to_location_id',
                ]
            ])
            ->join([
                'vendor' => [
                    'table' => 'inventory_vendors',
                    'type' => 'LEFT',
                    'conditions' => 'vendor.id = InventoryTransactionHistories.vendor_id',
                ]
            ])
            ->join([
                'atacode' => [
                    'table' => 'ata_codes',
                    'type' => 'LEFT',
                    'conditions' => 'atacode.id = InventoryTransactionHistories.ata_chapter',
                ]
            ])
            ->first();
            //echo "<pre>";print_r($transactionhistory);exit;
            
            $this->set(compact('transactionhistory'));
        }

    }

?>