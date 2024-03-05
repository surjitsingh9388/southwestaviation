<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;
use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\ConnectionManager;

class InventoryFilterComponent extends Component {
    public $components = ['Auth'];

    public function inventoryRequestFilter($requestData){
        
        $cond ="";
        if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
            $search = $requestData['searchItem'];
            $cond.=" AND ( invreq.request_number LIKE '%".$search."%' OR  invreq.title LIKE '%".$search."%' OR  invreq.description LIKE '%".$search."%' OR  invreq.requested_by LIKE '%".$search."%' OR  invreq.need_by LIKE '%".$search."%' OR  invreq.created LIKE '%".$search."%')";
            
        }
        
        //if(!empty($requestData['applyfilter'])){
            if(isset($requestData['request_status']) && $requestData['request_status']!=''){
                $cond.=" AND request_status = '".$requestData['request_status']."'";
            }
            if(!empty($requestData['request_date']) && !empty($requestData['request_date_start'])){
                $request_date_start = '';
                $request_date_end = '';

                if(!empty($requestData['request_date_start'])){
                    $request_date_start = str_replace('-', '/', $requestData['request_date_start']);
                    $request_date_start = date("Y-m-d", strtotime($request_date_start));
                }
                if(!empty($requestData['request_date_end'])){
                    $request_date_end = str_replace('-', '/', $requestData['request_date_end']);
                    $request_date_end = date("Y-m-d", strtotime($request_date_end));
                }

                if($requestData['request_date'] == 1){
                    $cond.=" AND DATE(invreq.created) = '".$request_date_start."'";
                }else if($requestData['request_date'] == 2){
                    $cond.=" AND DATE(invreq.created) >= '".$request_date_start."' AND DATE(invreq.created) <= '".$request_date_end."'";
                }else if($requestData['request_date'] == 3){
                    $cond.=" AND DATE(invreq.created) <= '".$request_date_start."'";
                }else if($requestData['request_date'] == 4){
                    $cond.=" AND DATE(invreq.created) >= '".$request_date_start."'";
                }
            }
            if(!empty($requestData['required_date']) && !empty($requestData['required_date_start'])){
                $required_date_start = '';
                $required_date_end = '';
                
                if(!empty($requestData['required_date_start'])){
                    $required_date_start = str_replace('-', '/', $requestData['required_date_start']);
                    $required_date_start = date("Y-m-d", strtotime($required_date_start));
                }
                if(!empty($requestData['required_date_end'])){
                    $required_date_end = str_replace('-', '/', $requestData['required_date_end']);
                    $required_date_end = date("Y-m-d", strtotime($required_date_end));
                }

                if($requestData['required_date'] == 1){
                    $cond.=" AND need_by = '".$required_date_start."'";
                }else if($requestData['required_date'] == 2){
                    $cond.=" AND need_by >= '".$required_date_start."' AND need_by <= '".$required_date_end."'";
                }else if($requestData['required_date'] == 3){
                    $cond.=" AND need_by >= '".$required_date_start."'";
                }else if($requestData['required_date'] == 4){
                    $cond.=" AND need_by <= '".$required_date_start."'";
                }
            }
            if(isset($requestData['urgent_item'])){
                $urgent_item = array('0','1');
                if($requestData['urgent_item'] == '1'){
                    $urgent_item = array('1');
                }else if($requestData['urgent_item'] == '0'){
                    $urgent_item = array('0');
                }
                $cond.=" AND invreq.urgency in (".implode(',',$urgent_item).")";
            }
            if(!empty($requestData['urgency'])){
                $cond.=" AND invreq.urgency = '".$requestData['urgency']."'";
            }
            if(isset($requestData['show_inactive']) && $requestData['show_inactive'] == '1'){
                $results = array('0','1');
                $result = "'" . implode ( "', '", $results ) . "'";
                $cond.=" AND invreq.status in (".$result.")";
            }else{
                $cond .= " AND invreq.status='1'";
            }
            if(isset($this->request->query['open_request']) && !empty($this->request->query['open_request'])){
                $cond.=" AND invreq.request_status in ('0', '1') AND invreq.status = '1'";
            }
            if(isset($this->request->query['past_due']) && !empty($this->request->query['past_due'])){
                $cond.=" AND (invreq.urgency != '0' or invreq.need_by < CURDATE()) AND invreq.request_status in ('0', '1') AND invreq.status = '1'";
            }
        /*}else{
            $cond .= " AND status='1'";
        }*/

        return $cond;
    }

    public function inventoryPurchaseOrderFilter($requestData){ 
        $cond = "";
        //echo "<pre>";print_r($this->request->query);exit;
        if( isset($this->request->query['inventoryitemid']) && !empty($this->request->query['inventoryitemid'])){
            $requestData['searchPOItem'] = $this->request->query['inventoryitemid'];
        }else if( isset($this->request->query['open_po']) && !empty($this->request->query['open_po'])){
            $requestData['open_po'] = '1';
        }else if( isset($this->request->query['open_exchange']) && !empty($this->request->query['open_exchange'])){
            $requestData['open_exchange'] = $this->request->query['open_exchange'];
        }
        
        if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
            $search = $requestData['searchItem'];
            $cond.=" AND ( po_number LIKE '%".$search."%' OR  reference LIKE '%".$search."%' OR  invpovendor.name LIKE '%".$search."%' OR  requestor LIKE '%".$search."%')";
        }

        if( isset($requestData['searchPOItem']) && !empty($requestData['searchPOItem'])){
            $search = $requestData['searchPOItem'];
            $cond.=" AND ( invpo.id in (select inventory_po_id from inventory_po_items where inventory_item_id = '".$search."'))";
        }
        
        //if(!empty($requestData['applyfilter'])){
            if(!empty($requestData['po_type'])){
                $cond.=" AND invpo.po_type = '".$requestData['po_type']."'";
            }
            if(!empty($requestData['po_status'])){
                $cond.=" AND invpo.po_status = '".$requestData['po_status']."'";
            }
            if(!empty($requestData['exchange_status'])){
                $cond.=" AND invpo.po_type = '2' AND invpo.exchange_status = '".$requestData['exchange_status']."'";
            }
            if(!empty($requestData['requestor'])){
                $cond.=" AND invpo.requestor = '".$requestData['requestor']."'";
            }
            if(!empty($requestData['reference'])){
                $cond.=" AND invpo.reference = '".$requestData['reference']."'";
            }
            if(!empty($requestData['conditions'])){
                $cond.=" AND invpo.conditions = '".$requestData['conditions']."'";
            }
            if(!empty($requestData['vendor'])){
                $cond.=" AND invpo.vendor = '".$requestData['vendor']."'";
            }
            if(!empty($requestData['po_date_start']) || !empty($requestData['po_date_end'])){
                $po_date_start = '';
                $po_date_end = '';

                if(!empty($requestData['po_date_start'])){
                    $po_date_start = str_replace('-', '/', $requestData['po_date_start']);
                    $po_date_start = date("Y-m-d", strtotime($po_date_start));
                }
                if(!empty($requestData['po_date_end'])){
                    $po_date_end = str_replace('-', '/', $requestData['po_date_end']);
                    $po_date_end = date("Y-m-d", strtotime($po_date_end));
                }

                if(!empty($po_date_start)){
                    $cond.=" AND invpo.po_date >= '".$po_date_start."'";
                }
                if(!empty($po_date_end)){
                    $cond.=" AND invpo.po_date <= '".$po_date_end."'";
                }
            }
            if(!empty($requestData['openpos'])){
                $cond.=" AND invpo.po_status IN('0','1','2')";
            }
            if(!empty($requestData['status'])){
                $cond.=" AND invpo.status IN('0','1')";
            }else{
                $cond .= " AND invpo.status='1'";
            }
            if(!empty($requestData['po_min_amount'])){
                $po_min_amount = $requestData['po_min_amount'];
                $cond.=" AND po_cost_total >= '".$po_min_amount."'";
            }
            if(!empty($requestData['po_max_amount'])){
                $po_max_amount = $requestData['po_max_amount'];
                $cond.=" AND po_cost_total <= '".$po_max_amount."'";
            }
            if(isset($requestData['open_po']) && !empty($requestData['open_po'])){
                $cond.=" AND invpo.po_status IN('0','1','2') AND invpo.po_type='1'";
            }
            if(isset($requestData['open_exchange']) && !empty($requestData['open_exchange'])){
                $cond.=" AND invpo.exchange_status = '2' AND invpo.po_type='2'";
            }
            
        /*}else{
            $cond.=" AND invpo.status ='1'";
        }*/

        return $cond;
    }

    public function inventoryRepairFilter($requestData){
        $cond = "";

        if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
            $search = $requestData['searchItem'];
            $cond.=" AND ( ro_number LIKE '%".$search."%' OR  reference LIKE '%".$search."%' OR  v.name LIKE '%".$search."%' OR  requestor LIKE '%".$search."%')";
        }
        
        //if(!empty($requestData['applyfilter'])){
            
            if(isset($requestData['ro_status']) && $requestData['ro_status'] != ''){
                $cond.=" AND ro_status = '".$requestData['ro_status']."'";
            }
        
            if(!empty($requestData['ro_date_start']) || !empty($requestData['ro_date_end'])){
                $ro_date_start = '';
                $ro_date_end = '';
        
                if(!empty($requestData['ro_date_start'])){
                    $ro_date_start = str_replace('-', '/', $requestData['ro_date_start']);
                    $ro_date_start = date("Y-m-d", strtotime($ro_date_start));
                }
                if(!empty($requestData['ro_date_end'])){
                    $ro_date_end = str_replace('-', '/', $requestData['ro_date_end']);
                    $ro_date_end = date("Y-m-d", strtotime($ro_date_end));
                }
        
                if(!empty($ro_date_start)){
                    $cond.=" AND invro.ro_date >= '".$ro_date_start."'";
                }
                if(!empty($ro_date_end)){
                    $cond.=" AND invro.ro_date <= '".$ro_date_end."'";
                }
            }
        
            if(!empty($requestData['account_code'])){
                $cond.=" AND account_code = '".$requestData['account_code']."'";
            }
            if(!empty($requestData['ro_min_amount'])){
                $ro_min_amount = $requestData['ro_min_amount'];
                $cond.=" AND ro_cost_total >= '".$ro_min_amount."'";
            }
            if(!empty($requestData['ro_max_amount'])){
                $ro_max_amount = $requestData['ro_max_amount'];
                $cond.=" AND ro_cost_total <= '".$ro_max_amount."'";
            }
            if(isset($this->request->query['open_ro']) && !empty($this->request->query['open_ro'])){
                $cond.=" AND invro.ro_status in ('0', '1', '2') AND invro.status = '1'";
            }
        //}

        return $cond;
    }

    public function completeInventoryFilter($requestData){
        $cond = "";
        //echo "<pre>";print_r($requestData);exit;
        if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
            $search = $requestData['searchItem'];
            $cond.=" AND ( invitm.name LIKE '%".$search."%' OR  invitm.part_number LIKE '%".$search."%')";
        }
        
        if(!isset($requestData['showinactive']) && !isset($requestData['qtystatus'])){
            $statusarr = array('1', '2', '5', '7', '9', '12');
            
            if( isset($requestData['SearchBy']) && !empty($requestData['SearchBy'])){
                $filterVal = $requestData['SearchBy'];
                $statusarr = array($filterVal);
            }else if(!empty($requestData['is_active'])){
                if($requestData['is_active'] == 2){
                    $result = "'" . implode ( "', '", $statusarr ) . "'";
                    $cond.=" AND (inv.status in ($result))";
                }else if($requestData['is_active'] == 3){
                    $result = "'" . implode ( "', '", $statusarr ) . "'";
                    $cond.=" AND (inv.status not in ($result))";
                }
            }else{
                $result = "'" . implode ( "', '", $statusarr ) . "'";
                $cond.=" AND (inv.status in ($result))";
            }
            
        }else if(isset($requestData['qtystatus']) && !empty($requestData['qtystatus'])){
            $cond.=" AND inv.status = '".$requestData['qtystatus']."'";
        }

        if(!empty($requestData['item_type'])){
            $cond.=" AND invitm.item_type = '".$requestData['item_type']."'";
        }
        if(!empty($requestData['is_this_item_serialized'])){
            $is_this_item_serialized = array('0','1');
            if($requestData['is_this_item_serialized'] == 2){
                $is_this_item_serialized = array('1');
            }else if($requestData['is_this_item_serialized'] == 3){
                $is_this_item_serialized = array('0');
            }
            $cond.=" AND invitm.is_this_item_serialized in (".implode(',',$is_this_item_serialized).")";
        }
        if(!empty($requestData['capital_equipment'])){
            $capital_equipment = array('0','1');
            if($requestData['capital_equipment'] == 2){
                $capital_equipment = array('1');
            }else if($requestData['capital_equipment'] == 3){
                $capital_equipment = array('0');
            }
            $cond.=" AND invitm.capital_equipment in (".implode(',',$capital_equipment).")";
        }
        if(!empty($requestData['install_to'])){
            $cond.=" AND inv.install_to = '".$requestData['install_to']."'";
        }
        if(!empty($requestData['part_number'])){
            $cond.=" AND invitm.part_number like '%".$requestData['part_number']."%'";
        }
        if(!empty($requestData['ata_chapter'])){
            $cond.=" AND inv.ata_chapter = '".$requestData['ata_chapter']."'";
        }
        if(!empty($requestData['location_id'])){
            $cond.=" AND inv.location_id = '".$requestData['location_id']."'";
        }
        if(!empty($requestData['status'])){
            $cond.=" AND inv.status = '".$requestData['status']."'";
        }
        if(!empty($requestData['conditions'])){
            $cond.=" AND inv.conditions = '".$requestData['conditions']."'";
        }
        if(!empty($requestData['vendor'])){
            $cond.=" AND inv.vendor = '".$requestData['vendor']."'";
        }
        if(!empty($requestData['cost_condition']) && !empty($requestData['cost_start'])){
            if($requestData['cost_condition'] == 1){
                $cond.=" AND (inv.cost*inv.qty) = '".$requestData['cost_start']."'";
            }else if($requestData['cost_condition'] == 2){
                $cond.=" AND (inv.cost*inv.qty) >= '".$requestData['cost_start']."' AND (inv.cost*inv.qty) <= '".$requestData['cost_end']."'";
            }else if($requestData['cost_condition'] == 3){
                $cond.=" AND (inv.cost*inv.qty) >= '".$requestData['cost_start']."'";
            }else if($requestData['cost_condition'] == 4){
                $cond.=" AND (inv.cost*inv.qty) <= '".$requestData['cost_start']."'";
            }
        }
        
        if(!empty($requestData['has_expiration'])){
            if($requestData['has_expiration'] == 2){
                $cond.=" AND inv.expiration is not NULL";
            }else if($requestData['has_expiration'] == 3){
                $cond.=" AND inv.expiration is NULL";
            }
        }
        if(!empty($requestData['expiration_date']) && !empty($requestData['expiration_date_start'])){
            $expiration_date_start = '';
            $expiration_date_end = '';

            if(!empty($requestData['expiration_date_start'])){
                $expiration_date_start = str_replace('-', '/', $requestData['expiration_date_start']);
                $expiration_date_start = date("Y-m-d", strtotime($expiration_date_start));
            }
            if(!empty($requestData['expiration_date_end'])){
                $expiration_date_end = str_replace('-', '/', $requestData['expiration_date_end']);
                $expiration_date_end = date("Y-m-d", strtotime($expiration_date_end));
            }

            if($requestData['expiration_date'] == 1){
                $cond.=" AND inv.expiration = '".$expiration_date_start."'";
            }else if($requestData['expiration_date'] == 2){
                $cond.=" AND inv.expiration >= '".$expiration_date_start."' AND inv.expiration <= '".$expiration_date_end."'";
            }else if($requestData['expiration_date'] == 3){
                $cond.=" AND inv.expiration >= '".$expiration_date_start."'";
            }else if($requestData['expiration_date'] == 4){
                $cond.=" AND inv.expiration <= '".$expiration_date_start."'";
            }
        }
        if(isset($this->request->query['out_for_repair']) && !empty($this->request->query['out_for_repair'])){
            $cond.=" AND inv.status = '".$this->request->query['out_for_repair']."'";
        }

        return $cond;
    }

    public function expiringInventoryFilter($requestData){
        
        $cond = "";
        
        if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
            $search = $requestData['searchItem'];
            $cond.=" AND ( invitm.name LIKE '%".$search."%' OR  invitm.part_number LIKE '%".$search."%')";
        }
        
        $statusarr = array('1', '2', '7', '9', '12');
        $result = "'" . implode ( "', '", $statusarr ) . "'";
        $cond.=" AND (inv.status in ($result))";

        if(isset($requestData['is_this_item_serialized']) && $requestData['is_this_item_serialized'] != ''){
            $is_this_item_serialized = array('0','1');
            if($requestData['is_this_item_serialized'] == 1){
                $is_this_item_serialized = array('1');
            }else if($requestData['is_this_item_serialized'] == 0){
                $is_this_item_serialized = array('0');
            }
            $cond.=" AND invitm.is_this_item_serialized in (".implode(',',$is_this_item_serialized).")";
        }
        if(!empty($requestData['capital_equipment'])){
            $capital_equipment = array('0','1');
            if($requestData['capital_equipment'] == 2){
                $capital_equipment = array('1');
            }else if($requestData['capital_equipment'] == 3){
                $capital_equipment = array('0');
            }
            $cond.=" AND invitm.capital_equipment in (".implode(',',$capital_equipment).")";
        }
        if(!empty($requestData['location_id'])){
            $cond.=" AND inv.location_id = '".$requestData['location_id']."'";
        }
        if(!empty($requestData['expiration_date_start']) || !empty($requestData['expiration_date_end'])){
            $expiration_date_start = '';
            $expiration_date_end = '';

            if(!empty($requestData['expiration_date_start'])){
                $expiration_date_start = str_replace('-', '/', $requestData['expiration_date_start']);
                $expiration_date_start = date("Y-m-d", strtotime($expiration_date_start));

                $cond.=" AND inv.expiration >= '".$expiration_date_start."'";
            }
            if(!empty($requestData['expiration_date_end'])){
                $expiration_date_end = str_replace('-', '/', $requestData['expiration_date_end']);
                $expiration_date_end = date("Y-m-d", strtotime($expiration_date_end));

                $cond.=" AND inv.expiration <= '".$expiration_date_end."'";
            }
        }else{
            $pagesource = isset($this->request->query['from']) ? $this->request->query['from'] : '';
            if($pagesource == 'exprd'){
                if(isset($requestData['search_expiration_date']) && !empty($requestData['search_expiration_date'])){
                    $expiration_date = $requestData['search_expiration_date'];
                    if(!empty($expiration_date)){
                        $expiration_date = str_replace('-', '/', $expiration_date);
                        $expiration_date = date("Y-m-d", strtotime($expiration_date));
                    }
                }else{
                    $expiration_date = date('Y-m-d', strtotime("-1 days"));
                }
                
                $cond .= " AND inv.expiration <= '".$expiration_date."'";
            }else{
                $expiration_date = date('Y-m-d', strtotime("+1 month -1 days"));
                if(isset($requestData['search_expiration_date']) && !empty($requestData['search_expiration_date'])){
                    $expiration_date = $requestData['search_expiration_date'];
                    if(!empty($expiration_date)){
                        $expiration_date = str_replace('-', '/', $expiration_date);
                        $expiration_date = date("Y-m-d", strtotime($expiration_date));
                    }
                }
                $cond .= " AND inv.expiration >= '".date('Y-m-d', strtotime("-1 days"))."' AND inv.expiration <= '".$expiration_date."'";
            }
        }
        if(!empty($requestData['tags'])){
            $cond.=" AND inv.tags = '".$requestData['tags']."'";
        }
        
        return $cond;
    }

    public function thresholdInventoryFilter($requestData){
        $cond = "";
            
        if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
            $search = $requestData['searchItem'];
            $cond.=" AND ( invitm.name LIKE '%".$search."%' OR  invitm.part_number LIKE '%".$search."%')";
        }
        
        if(isset($requestData['is_this_item_serialized']) && $requestData['is_this_item_serialized'] != ''){
            $is_this_item_serialized = array('0','1');
            if($requestData['is_this_item_serialized'] == 1){
                $is_this_item_serialized = array('1');
            }else if($requestData['is_this_item_serialized'] == 0){
                $is_this_item_serialized = array('0');
            }
            $cond.=" AND invitm.is_this_item_serialized in (".implode(',',$is_this_item_serialized).")";
        }
        if(!empty($requestData['capital_equipment'])){
            $capital_equipment = array('0','1');
            if($requestData['capital_equipment'] == 2){
                $capital_equipment = array('1');
            }else if($requestData['capital_equipment'] == 3){
                $capital_equipment = array('0');
            }
            $cond.=" AND invitm.capital_equipment in (".implode(',',$capital_equipment).")";
        }
        if(!empty($requestData['location_id'])){
            $cond.=" AND location_id = '".$requestData['location_id']."'";
        }
        if(!empty($requestData['min_unit_cost'])){
            $cond.=" AND invitm.unit_cost >= '".$requestData['min_unit_cost']."'";
        }
        if(!empty($requestData['max_unit_cost'])){
            $cond.=" AND invitm.unit_cost <= '".$requestData['max_unit_cost']."'";
        }
        
        return $cond;
    }
    
    public function inventoryShippingOrderFilter($requestData){
        $cond = " AND inv.status = '1'";
        if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
            $search = $requestData['searchItem'];
            $cond.=" AND ( shipping_order_number LIKE '%".$search."%' OR  reference LIKE '%".$search."%' OR  requestor LIKE '%".$search."%')";
        }
        
        if(isset($requestData['shipping_order_status']) && $requestData['shipping_order_status'] != ''){
            $cond.=" AND shipping_order_status = '".$requestData['shipping_order_status']."'";
        }

        if(!empty($requestData['shipping_order_date_start']) || !empty($requestData['shipping_order_date_end'])){
            $shipping_order_date_start = '';
            $shipping_order_date_end = '';

            if(!empty($requestData['shipping_order_date_start'])){
                $shipping_order_date_start = str_replace('-', '/', $requestData['shipping_order_date_start']);
                $shipping_order_date_start = date("Y-m-d", strtotime($shipping_order_date_start));
            }
            if(!empty($requestData['shipping_order_date_end'])){
                $shipping_order_date_end = str_replace('-', '/', $requestData['shipping_order_date_end']);
                $shipping_order_date_end = date("Y-m-d", strtotime($shipping_order_date_end));
            }

            if(!empty($shipping_order_date_start)){
                $cond.=" AND inv.shipping_order_date >= '".$shipping_order_date_start."'";
            }
            if(!empty($shipping_order_date_end)){
                $cond.=" AND inv.shipping_order_date <= '".$shipping_order_date_end."'";
            }
        }

        if(!empty($requestData['account_code'])){
            $cond.=" AND account_code = '".$requestData['account_code']."'";
        }
        if(!empty($requestData['shipping_order_min_amount'])){
            $shipping_order_min_amount = $requestData['shipping_order_min_amount'];
            $cond.=" AND inv.shipping_order_cost_total >= '".$shipping_order_min_amount."'";
        }
        if(!empty($requestData['shipping_order_max_amount'])){
            $shipping_order_max_amount = $requestData['shipping_order_max_amount'];
            $cond.=" AND inv.shipping_order_cost_total <= '".$shipping_order_max_amount."'";
        }

        return $cond;
        
    }

    public function inventoryCatalogFilter($requestData){
        $cond = '';
        if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
            $search = $requestData['searchItem'];
            $cond.=" AND ( name LIKE '%".$search."%' OR  part_number LIKE '%".$search."%' OR  tags LIKE '%".$search."%')";
        }
        
        if(!empty($requestData['item_type'])){
            $cond.=" AND item_type = '".$requestData['item_type']."'";
        }
        if(!empty($requestData['tags'])){
            $cond.=" AND tags like '%".$requestData['tags']."%'";
        }
        if(isset($requestData['is_this_item_serialized'])){
            $cond.=" AND is_this_item_serialized = '".$requestData['is_this_item_serialized']."'";
        }
        if(isset($requestData['capital_equipment'])){
            $cond.=" AND capital_equipment = '".$requestData['capital_equipment']."'";
        }
        if(isset($requestData['accept_install'])){
            $accept_install = array('0','1');
            if($requestData['accept_install'] == '1'){
                $accept_install = array('1');
            }else if($requestData['accept_install'] == '0'){
                $accept_install = array('0');
            }
            $cond.=" AND accept_install in (".implode(',',$accept_install).")";
        }
        if(isset($requestData['status'])){
            $statusarr = array('1');
            if($requestData['status'] == '2'){
                $statusarr[] = '0';
            }
            $statusarr = "'" . implode ( "', '", $statusarr ) . "'";
            $cond.=" AND status in (".$statusarr.")";
        }else{
            $cond .= ' AND status="1"';
        }
        if(!empty($requestData['minqty'])){
            $cond .= " AND item_instock >= '".$requestData['minqty']."'";
        }
        if(!empty($requestData['maxqty'])){
            $cond .= " AND item_instock >= '".$requestData['maxqty']."'";
        }

        return $cond;
    }

    public function inventoryLocationFilter($requestData){
        $cond = '';            
            
        if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
            $search = $requestData['searchItem'];
            $cond.=" AND ( location_name LIKE '%".$search."%' OR  description LIKE '%".$search."%')";
        }

        if(!empty($requestData['location_status'])){
            $cond.=" AND location_status = '".$requestData['location_status']."'";
        }
        if(isset($requestData['show_inactive']) && $requestData['show_inactive'] != ''){
            $statusarr = array('1');
            if($requestData['show_inactive'] == '1'){
                $statusarr[] = '0';
            }
            $result = "'" . implode ( "', '", $statusarr ) . "'";
            $cond.=" AND status in (".$result.")";
        }else{
            $cond .= " AND status='1'";
        }

        return $cond;
    }

    public function inventoryTransactionHistoryFilter($requestData){
        $cond = '';
        if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
            $search = $requestData['searchItem'];
            $cond.=" AND ( invth.from_description LIKE '%".$search."%' OR  invitm.name LIKE '%".$search."%' OR  invitm.part_number LIKE '%".$search."%' OR  inv.serial_no LIKE '%".$search."%' OR  inv.display_name LIKE '%".$search."%')";
        }
        
        $todaydate = date("Y-m-d");
        if(!empty($requestData['transaction_period'])){
            if($requestData['transaction_period'] == '1'){
                $cond.=" AND DATE(invth.created) >= '".date("Y-m-d", strtotime('-7 days'))."' AND DATE(invth.created) <= '".$todaydate."'";
            }else if($requestData['transaction_period'] == '2'){
                $cond.=" AND DATE(invth.created) >= '".date("Y-m-d", strtotime('-30 days'))."' AND DATE(invth.created) <= '".$todaydate."'";
            }else if($requestData['transaction_period'] == '3'){
                $cond.=" AND DATE(invth.created) >= '".date("Y-m-01")."' AND DATE(invth.created) <= '".$todaydate."'";
            }else if($requestData['transaction_period'] == '4'){
                if($requestData['period_date'] == '1' && !empty($requestData['period_date_start'])){
                    $period_date_start = str_replace('-', '/', $requestData['period_date_start']);
                    $period_date_start = date("Y-m-d", strtotime($period_date_start));
                    
                    $cond.=" AND DATE(invth.created) = '".$period_date_start."'";
                }else if($requestData['period_date'] == '2' && !empty($requestData['period_date_start']) && !empty($requestData['period_date_end'])){
                    $period_date_start = str_replace('-', '/', $requestData['period_date_start']);
                    $period_date_start = date("Y-m-d", strtotime($period_date_start));

                    $period_date_end = str_replace('-', '/', $requestData['period_date_end']);
                    $period_date_end = date("Y-m-d", strtotime($period_date_end));

                    $cond.=" AND DATE(invth.created) >= '".$period_date_start."' AND DATE(invth.created) <= '".$period_date_end."'";
                }else if($requestData['period_date'] == '3' && !empty($requestData['period_date_start'])){
                    $period_date_start = str_replace('-', '/', $requestData['period_date_start']);
                    $period_date_start = date("Y-m-d", strtotime($period_date_start));

                    $cond.=" AND DATE(invth.created) < '".$period_date_start."'";
                }else if($requestData['period_date'] == '4' && !empty($requestData['period_date_start'])){
                    $period_date_start = str_replace('-', '/', $requestData['period_date_start']);
                    $period_date_start = date("Y-m-d", strtotime($period_date_start));

                    $cond.=" AND DATE(invth.created) > '".$period_date_start."'";
                }
            }
        }
        if(!empty($requestData['transaction_action'])){
            $cond.=" AND type IN (".$requestData['transaction_action'].")";
        }
        if(isset($requestData['is_this_item_serialized']) && $requestData['is_this_item_serialized'] != '2' && $requestData['is_this_item_serialized'] != ''){
            $cond.=" AND invitm.is_this_item_serialized = '".$requestData['is_this_item_serialized']."'";
        }
        if(isset($requestData['capital_equipment']) && $requestData['capital_equipment'] != '' && $requestData['capital_equipment'] != '2'){
            $cond.=" AND invitm.capital_equipment = '".$requestData['capital_equipment']."'";
        }
        if(!empty($requestData['qty_range'])){
            if($requestData['qty_range'] == '1' && !empty($requestData['minqty'])){
                $cond .= " AND invth.qty = '".$requestData['minqty']."'";
            }else if($requestData['qty_range'] == '2' && !empty($requestData['minqty']) && !empty($requestData['maxqty'])){
                $cond .= " AND invth.qty >= '".$requestData['minqty']."' AND invth.qty <= '".$requestData['maxqty']."'";
            }
            if(!empty($requestData['minqty']) && !empty($requestData['minqty'])){
                $cond .= " AND invth.qty >= '".$requestData['minqty']."'";
            }
            if(!empty($requestData['maxqty']) && !empty($requestData['minqty'])){
                $cond .= " AND invth.qty >= '".$requestData['maxqty']."'";
            }
        }
        if(!empty($requestData['part_number'])){
            $cond.=" AND invitm.part_number = '".$requestData['part_number']."'";
        }
        if(!empty($requestData['serial_no'])){
            $cond.=" AND inv.serial_no = '".$requestData['serial_no']."'";
        }
        if(!empty($requestData['accont_code'])){
            $cond.=" AND invitm.accont_code = '".$requestData['accont_code']."'";
        }

        return $cond;
    }

    public function inventoryCustomersFilter($requestData){
        $cond = '';
        if( isset($requestData['searchItem']) && !empty($requestData['searchItem'])){
            $search = $requestData['searchItem'];
            $cond.=" AND ( customer_name LIKE '%".$search."%' OR  name2 LIKE '%".$search."%' OR  city LIKE '%".$search."%' OR  home_phone LIKE '%".$search."%')";
        }

        return $cond;
    }
}