<?php
    namespace App\Controller\Admin;

    use App\Controller\Admin\AppController;
    use Cake\Routing\Router;
    use Cake\ORM\TableRegistry;
    use Cake\Datasource\ConnectionManager;
    use Cake\Http\Response;
    use Cake\View\View;
    use Cake\Database\Expression\QueryExpression;
    use Cake\Datasource\FactoryLocator;
    use Cake\ORM\Locator\LocatorAwareTrait;

    class SearchesController extends AppController
    {
        public function initialize():void {
            parent::initialize();
            $this->loadComponent('HeaderSearch');
        }

        public function search(){
            $postData = $this->request->getData();
            if(!empty($postData['search_txt'])){
                $searchResult = [];
                $listhtml = '';

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'plane'){
                    $aircraft_results = $this->HeaderSearch->searchAircraft($postData['search_txt']);
                    if(!empty($aircraft_results)){
                        $listhtml .= '<li class="list-group-item"><b>Aircraft</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($aircraft_results as $rows){
                            $url = Router::url(['controller' => 'Planes', 'action' => 'view', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['plane_name'].' ('.$rows['plane_code'].')'.'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'airframe_component'){
                    $airframe_comp_results = $this->HeaderSearch->searchAirframeComponent($postData['search_txt']);
                    if(!empty($airframe_comp_results)){
                        $listhtml .= '<li class="list-group-item"><b>Airframe Component</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($airframe_comp_results as $rows){
                            $url = Router::url(['controller' => 'AirframeComponents', 'action' => 'view', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['log_book'].' ('.$rows['description'].')</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'sub_component'){
                    $subcomp_results = $this->HeaderSearch->searchSubComponent($postData['search_txt']);
                    if(!empty($subcomp_results)){
                        $listhtml .= '<li class="list-group-item"><b>Sub Component</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($subcomp_results as $rows){
                            $url = Router::url(['controller' => 'SubComponents', 'action' => 'view', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['title'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'airframe_component_time'){
                    $airframe_comptimes_results = $this->HeaderSearch->searchAirframeCompTimes($postData['search_txt']);
                    if(!empty($airframe_comptimes_results)){
                        $listhtml .= '<li class="list-group-item"><b>Airframe Component Times</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($airframe_comptimes_results as $rows){
                            $url = Router::url(['controller' => 'AirframeComponentTimes', 'action' => 'view', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['plane_code'].' ('.$rows['log_book'].')</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'airframe_component_part'){
                    $airframe_comppart_results = $this->HeaderSearch->searchAirframeCompPart($postData['search_txt']);
                    if(!empty($airframe_comppart_results)){
                        $listhtml .= '<li class="list-group-item"><b>Airframe Component Parts</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($airframe_comppart_results as $rows){
                            $url = Router::url(['controller' => 'AirframeComponentParts', 'action' => 'view', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['plane_code'].' ('.$rows['log_book'].', '.$rows['ata_code'].')</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'ata_code'){
                    $atacode_results = $this->HeaderSearch->searchATACode($postData['search_txt']);
                    if(!empty($atacode_results)){
                        $listhtml .= '<li class="list-group-item"><b>ATA Codes</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($atacode_results as $rows){
                            $url = Router::url(['controller' => 'AtaCodes', 'action' => 'view', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['ata_code'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'disposition'){
                    $disposition_results = $this->HeaderSearch->searchDisposition($postData['search_txt']);
                    if(!empty($disposition_results)){
                        $listhtml .= '<li class="list-group-item"><b>Disposition</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($disposition_results as $rows){
                            $url = Router::url(['controller' => 'Dispositions', 'action' => 'view', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['title'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'adsb_status'){
                    $adsb_results = $this->HeaderSearch->searchADSB($postData['search_txt']);
                    if(!empty($adsb_results)){
                        $listhtml .= '<li class="list-group-item"><b>AD/SB Class</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($adsb_results as $rows){
                            $url = Router::url(['controller' => 'AdsbStatus', 'action' => 'view', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['title'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'pilot'){
                    $pilot_results = $this->HeaderSearch->searchPilots($postData['search_txt']);
                    if(!empty($pilot_results)){
                        $listhtml .= '<li class="list-group-item"><b>Pilots</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($pilot_results as $rows){
                            $url = Router::url(['controller' => 'Pilots', 'action' => 'edit', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['full_name'].' ('.$rows['role_name'].')</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'inventory_item'){
                    $invitems_results = $this->HeaderSearch->searchInventoryItems($postData['search_txt']);
                    if(!empty($invitems_results)){
                        $listhtml .= '<li class="list-group-item"><b>Inventory Items</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($invitems_results as $rows){
                            $url = Router::url(['controller' => 'InventoryItems', 'action' => 'detail', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['name'].' ('.$rows['part_number'].')</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'inventory'){
                    $inv_results = $this->HeaderSearch->searchInventory($postData['search_txt']);
                    if(!empty($inv_results)){
                        $listhtml .= '<li class="list-group-item"><b>Inventory</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($inv_results as $rows){
                            $url = Router::url(['controller' => 'Inventories', 'action' => 'detail', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['serial_no'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'inventory_location'){
                    $invlocation_results = $this->HeaderSearch->searchInventoryLocation($postData['search_txt']);
                    if(!empty($invlocation_results)){
                        $listhtml .= '<li class="list-group-item"><b>Inventory Location</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($invlocation_results as $rows){
                            $url = Router::url(['controller' => 'InventoryLocations', 'action' => 'detail', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['location_name'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'inventory_request'){
                    $invrequest_results = $this->HeaderSearch->searchInventoryRequest($postData['search_txt']);
                    if(!empty($invrequest_results)){
                        $listhtml .= '<li class="list-group-item"><b>Inventory Requests</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($invrequest_results as $rows){
                            $url = Router::url(['controller' => 'InventoryRequests', 'action' => 'detail', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['request_number'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'inventory_purchase_order'){
                    $invpo_results = $this->HeaderSearch->searchPurchaseOrders($postData['search_txt']);
                    if(!empty($invpo_results)){
                        $listhtml .= '<li class="list-group-item"><b>Inventory Purchase Orders</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($invpo_results as $rows){
                            $url = Router::url(['controller' => 'InventoryPurchaseOrders', 'action' => 'detail', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['po_number'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'inventory_repair_order'){
                    $invro_results = $this->HeaderSearch->searchRepairOrders($postData['search_txt']);
                    if(!empty($invro_results)){
                        $listhtml .= '<li class="list-group-item"><b>Inventory Repair Orders</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($invro_results as $rows){
                            $url = Router::url(['controller' => 'InventoryRepairOrders', 'action' => 'detail', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['ro_number'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'inventory_shipping_order'){
                    $invshippingorder_results = $this->HeaderSearch->searchShippingOrders($postData['search_txt']);
                    if(!empty($invshippingorder_results)){
                        $listhtml .= '<li class="list-group-item"><b>Inventory Shipping Orders</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($invshippingorder_results as $rows){
                            $url = Router::url(['controller' => 'InventoryShippingOrders', 'action' => 'detail', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['shipping_order_number'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'customer_otc'){
                    $invcustomer_results = $this->HeaderSearch->searchInventoryCustomer($postData['search_txt']);
                    if(!empty($invcustomer_results)){
                        $listhtml .= '<li class="list-group-item"><b>Customer/OTC</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($invcustomer_results as $rows){
                            $url = Router::url(['controller' => 'InventoryCustomers', 'action' => 'customerinfo', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['customer_name'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'inventory_vendor'){
                    $invvendor_results = $this->HeaderSearch->searchInventoryVendor($postData['search_txt']);
                    if(!empty($invvendor_results)){
                        $listhtml .= '<li class="list-group-item"><b>Inventory Vendors</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($invvendor_results as $rows){
                            $url = Router::url(['controller' => 'InventoryVendors', 'action' => 'detail', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['name'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'inventory_manufacturer'){
                    $invmfg_results = $this->HeaderSearch->searchInventoryManufacturer($postData['search_txt']);
                    if(!empty($invmfg_results)){
                        $listhtml .= '<li class="list-group-item"><b>Inventory Manufacturers</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($invmfg_results as $rows){
                            $url = Router::url(['controller' => 'InventoryManufacturers', 'action' => 'detail', $rows['id']]);
                            $listhtml .= '<li class="list-group-item"><a href="'.$url.'">'.$rows['name'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($postData['search_fld']) || $postData['search_fld'] == 'work_order'){
                    $workorder_results = $this->HeaderSearch->searchWorkOrder($postData['search_txt']);
                    if(!empty($workorder_results)){
                        $listhtml .= '<li class="list-group-item"><b>Work Order</b>';
                        $listhtml .= '<ul class="sub-list-group">';
                        foreach($workorder_results as $rows){
                            $listhtml .= '<li class="list-group-item loadaircraftworkorder" data-val="'.$rows['work_order_no'].'"><a href="javascript:void(0);">'.$rows['work_order_no'].'</a></li>';
                        }
                        $listhtml .= '</ul></li>';
                    }
                }

                if(empty($listhtml)){
                    $listhtml = '<li class="list-group-item">No Record Found!</li>';
                }
                $response = ['status'=>'success', 'message'=>'', 'listhtml'=>$listhtml];
                echo json_encode($response);die;
            }else{
                $response = ['status'=>'failure', 'message'=>'Something went wrong, please try again'];
                echo json_encode($response);die;
            }
        }
    }

?>