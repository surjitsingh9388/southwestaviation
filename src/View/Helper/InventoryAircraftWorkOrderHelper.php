<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Core\App;
use Cake\Routing\Router;

class InventoryAircraftWorkOrderHelper extends Helper
{
    public function getListOfOpenWorkOrderHTML($aircrafOpenWorkOrders){
        $listofopenwohtml = '';
        $aircraftWOStatus = unserialize(AIRCRAFT_WORKORDER_STATUS);
        foreach($aircrafOpenWorkOrders as $workorder){
            $listofopenwohtml .= '<tr class="loadaircraftworkordertr" data-val="'.$workorder['work_order_no'].'">';
            $listofopenwohtml .= '<td>'.$workorder['work_order_no'].'</td>';
            $listofopenwohtml .= '<td>'.$workorder['otc_aircrafts']['aircraft_registration_number'].'</td>';
            $listofopenwohtml .= '<td>'.$workorder['aircraft_make']['make'].'</td>';
            $listofopenwohtml .= '<td>'.$workorder['customers']['customer_name'].'</td>';
            $listofopenwohtml .= '<td></td>';
            $listofopenwohtml .= '<td></td>';
            $listofopenwohtml .= '<td>'.date('Y-m-d', strtotime($workorder['created_at'])).'</td>';
            $listofopenwohtml .= '<td></td>';
            $listofopenwohtml .= '<td>'.(!empty($workorder['updated_at']) ? date('Y-m-d', strtotime($workorder['updated_at'])) : '').'</td>';
            $listofopenwohtml .= '<td>'.$workorder['itemcount'].'</td>';
            $listofopenwohtml .= '<td>'.$aircraftWOStatus[$workorder['wo_status']].'</td>';
            $listofopenwohtml .= '</tr>';
        }

        return $listofopenwohtml;
    }

    public function getListOfAllWorkOrderQuotesHTML($aircrafAllWorkOrders, $search_by){
        $listofopenwohtml = '';
        $aircraftWOStatus = unserialize(AIRCRAFT_WORKORDER_STATUS);
        $woItemOverviewWarranty = unserialize(WOITEMOVERVIEWWARRANTY);
        if($search_by == 'aircraft_registration_number' || $search_by == 'aircraft_serial_number' || $search_by == 'customer_name'){
            $listofopenwohtml .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">W/O or Quote</th>
                    <th class="th-sm">Reg. Number</th>
                    <th class="th-sm">Status</th>
                    <th class="th-sm">Is Quote</th>
                    <th class="th-sm">Date Created</th>
                    <th class="th-sm">Date Completed</th>
                    <th class="th-sm">First Discrepancy</th>
                </tr>
            </thead>
            <tbody id="list-of-open-work-order-block">';
            foreach($aircrafAllWorkOrders as $workorder){
                $listofopenwohtml .= '<tr class="loadaircraftworkordertr" data-val="'.$workorder['work_order_no'].'">';
                $listofopenwohtml .= '<td>'.$workorder['work_order_no'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['otc_aircrafts']['aircraft_registration_number'].'</td>';
                $listofopenwohtml .= '<td>'.$aircraftWOStatus[$workorder['wo_status']].'</td>';
                $listofopenwohtml .= '<td><input type="checkbox" readonly /></td>';
                $listofopenwohtml .= '<td>'.date('Y-m-d', strtotime($workorder['created_at'])).'</td>';
                $listofopenwohtml .= '<td>'.(!empty($workorder['updated_at'] && $workorder['wo_status'] == '6') ? date('Y-m-d', strtotime($workorder['updated_at'])) : '').'</td>';
                $listofopenwohtml .= '<td>'.$workorder['discrepancy_histories']['discrepancy'].'</td>';
                $listofopenwohtml .= '</tr>';
            }
            $listofopenwohtml .= '</tbody></table>';
        }else if($search_by == 'customer_po'){
            $listofopenwohtml .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">Customer P/O</th>
                    <th class="th-sm">W/O or Quote</th>
                    <th class="th-sm">Reg. Number</th>
                    <th class="th-sm">Status</th>
                    <th class="th-sm">Is Quote</th>
                    <th class="th-sm">Date Created</th>
                    <th class="th-sm">Date Completed</th>
                    <th class="th-sm">First Discrepancy</th>
                </tr>
            </thead>
            <tbody id="list-of-open-work-order-block">';
            foreach($aircrafAllWorkOrders as $workorder){
                $listofopenwohtml .= '<tr class="loadaircraftworkordertr" data-val="'.$workorder['work_order_no'].'">';
                $listofopenwohtml .= '<td>'.$workorder['customer_po'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['work_order_no'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['otc_aircrafts']['aircraft_registration_number'].'</td>';
                $listofopenwohtml .= '<td>'.$aircraftWOStatus[$workorder['wo_status']].'</td>';
                $listofopenwohtml .= '<td><input type="checkbox" readonly /></td>';
                $listofopenwohtml .= '<td>'.date('Y-m-d', strtotime($workorder['created_at'])).'</td>';
                $listofopenwohtml .= '<td>'.(!empty($workorder['updated_at'] && $workorder['wo_status'] == '6') ? date('Y-m-d', strtotime($workorder['updated_at'])) : '').'</td>';
                $listofopenwohtml .= '<td>'.$workorder['wo_items']['wo_discrepancy'].'</td>';
                $listofopenwohtml .= '</tr>';
            }
            $listofopenwohtml .= '</tbody></table>';
        }else if($search_by == 'discrepancy' || $search_by == 'corrective_action'){
            $listofopenwohtml .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">Item</th>
                    <th class="th-sm">W/O or Quote</th>
                    <th class="th-sm">Reg. Number</th>
                    <th class="th-sm">Date Created</th>
                    <th class="th-sm">Date Completed</th>';
            if($search_by == 'discrepancy'){
                $listofopenwohtml .= '<th class="th-sm">Discrepancy</th>';
            }else if($search_by == 'corrective_action'){
                $listofopenwohtml .= '<th class="th-sm">Curr. Action</th>';
            }
            $listofopenwohtml .= '<th class="th-sm">Time Worked</th>
                </tr>
            </thead>
            <tbody id="list-of-open-work-order-block">';
            foreach($aircrafAllWorkOrders as $workorder){
                $listofopenwohtml .= '<tr class="loadaircraftworkordertr" data-val="'.$workorder['work_order_no'].'">';
                $listofopenwohtml .= '<td>'.$workorder['wo_items']['wo_item_position'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['work_order_no'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['otc_aircrafts']['aircraft_registration_number'].'</td>';
                $listofopenwohtml .= '<td>'.date('Y-m-d', strtotime($workorder['created_at'])).'</td>';
                $listofopenwohtml .= '<td>'.(!empty($workorder['updated_at'] && $workorder['wo_status'] == '6') ? date('Y-m-d', strtotime($workorder['updated_at'])) : '').'</td>';
                if($search_by == 'discrepancy'){
                    $listofopenwohtml .= '<td>'.$workorder['wo_items']['wo_discrepancy'].'</td>';
                }else if($search_by == 'corrective_action'){
                    $listofopenwohtml .= '<td>'.$workorder['wo_items']['wo_corrective_action'].'</td>';
                }
                $listofopenwohtml .= '<td></td>';
                $listofopenwohtml .= '</tr>';
            }
            $listofopenwohtml .= '</tbody></table>';
        }else if($search_by == 'warranty_claim_no'){
            $listofopenwohtml .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">Item</th>
                    <th class="th-sm">W/O or Quote</th>
                    <th class="th-sm">Reg. Number</th>
                    <th class="th-sm">Status</th>
                    <th class="th-sm">Date Comp.</th>
                    <th class="th-sm">Warranty</th>
                    <th class="th-sm">Claim No.</th>
                    <th class="th-sm">Time Worked</th>
                </tr>
            </thead>
            <tbody id="list-of-open-work-order-block">';
            foreach($aircrafAllWorkOrders as $workorder){
                $listofopenwohtml .= '<tr class="loadaircraftworkordertr" data-val="'.$workorder['work_order_no'].'">';
                $listofopenwohtml .= '<td>'.$workorder['wo_items']['wo_item_position'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['work_order_no'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['otc_aircrafts']['aircraft_registration_number'].'</td>';
                $listofopenwohtml .= '<td>'.$aircraftWOStatus[$workorder['wo_status']].'</td>';
                $listofopenwohtml .= '<td>'.(!empty($workorder['updated_at'] && $workorder['wo_status'] == '6') ? date('Y-m-d', strtotime($workorder['updated_at'])) : '').'</td>';
                $listofopenwohtml .= '<td>'.$woItemOverviewWarranty[$workorder['item_overviews']['warranty']].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['item_overviews']['warranty_claim_no'].'</td>';
                $listofopenwohtml .= '<td></td>';
                $listofopenwohtml .= '</tr>';
            }
            $listofopenwohtml .= '</tbody></table>';
        }else if($search_by == 'warranty_wo_status'){
            $listofopenwohtml .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">Work Order</th>
                    <th class="th-sm">Customer</th>
                    <th class="th-sm">Item</th>
                    <th class="th-sm">Discrep.</th>
                    <th class="th-sm">Vendor</th>
                    <th class="th-sm">Claim No.</th>
                    <th class="th-sm">Hrs.</th>
                </tr>
            </thead>
            <tbody id="list-of-open-work-order-block">';
            foreach($aircrafAllWorkOrders as $workorder){
                $listofopenwohtml .= '<tr class="loadaircraftworkordertr" data-val="'.$workorder['work_order_no'].'">';
                $listofopenwohtml .= '<td>'.$workorder['work_order_no'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['customers']['customer_name'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['wo_items']['wo_item_position'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['wo_items']['wo_discrepancy'].'</td>';
                $listofopenwohtml .= '<td>'.$woItemOverviewWarranty[$workorder['item_overviews']['warranty']].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['item_overviews']['warranty_claim_no'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['hrs_worked'].'</td>';
                $listofopenwohtml .= '</tr>';
            }
            $listofopenwohtml .= '</tbody></table>';
        }else if($search_by == 'part_number'){
            $listofopenwohtml .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">Item</th>
                    <th class="th-sm">W/O or Quote</th>
                    <th class="th-sm">Reg. Number</th>
                    <th class="th-sm">Date Created</th>
                    <th class="th-sm">Date Completed</th>
                    <th class="th-sm">Part Number</th>
                    <th class="th-sm">Time Worked</th>
                </tr>
            </thead>
            <tbody id="list-of-open-work-order-block">';
            foreach($aircrafAllWorkOrders as $workorder){
                $listofopenwohtml .= '<tr class="loadaircraftworkordertr" data-val="'.$workorder['work_order_no'].'">';
                $listofopenwohtml .= '<td>'.$workorder['wo_items']['wo_item_position'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['work_order_no'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['otc_aircrafts']['aircraft_registration_number'].'</td>';
                $listofopenwohtml .= '<td>'.date('Y-m-d', strtotime($workorder['created_at'])).'</td>';
                $listofopenwohtml .= '<td>'.(!empty($workorder['updated_at'] && $workorder['wo_status'] == '6') ? date('Y-m-d', strtotime($workorder['updated_at'])) : '').'</td>';
                $listofopenwohtml .= '<td>'.$workorder['item_parts']['part_number'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['time_worked'].'</td>';
                $listofopenwohtml .= '</tr>';
            }
            $listofopenwohtml .= '</tbody></table>';
        }else if($search_by == 'advanced_find_option'){
            $listofopenwohtml .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">Work Order</th>
                    <th class="th-sm">Status</th>
                    <th class="th-sm">Date Completed</th>
                    <th class="th-sm">Discrepancy</th>
                    <th class="th-sm">Corrective Action</th>
                </tr>
            </thead>
            <tbody id="list-of-open-work-order-block">';
            foreach($aircrafAllWorkOrders as $workorder){
                $listofopenwohtml .= '<tr class="loadaircraftworkordertr" data-val="'.$workorder['work_order_no'].'">';
                $listofopenwohtml .= '<td>'.$workorder['work_order_no'].'</td>';
                $listofopenwohtml .= '<td>'.$aircraftWOStatus[$workorder['wo_status']].'</td>';
                $listofopenwohtml .= '<td>'.(!empty($workorder['updated_at'] && $workorder['wo_status'] == '6') ? date('Y-m-d', strtotime($workorder['updated_at'])) : '').'</td>';
                $listofopenwohtml .= '<td>'.$workorder['wo_items']['wo_discrepancy'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['wo_items']['wo_corrective_action'].'</td>';
                $listofopenwohtml .= '</tr>';
            }
            $listofopenwohtml .= '</tbody></table>';
        }else if($search_by == 'other_find_option'){
            $listofopenwohtml .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">Work Order</th>
                    <th class="th-sm">Primary Customer</th>
                    <th class="th-sm">Reg. Number</th>
                    <th class="th-sm">Date Created</th>
                    <th class="th-sm">Date Completed</th>
                </tr>
            </thead>
            <tbody id="list-of-open-work-order-block">';
            foreach($aircrafAllWorkOrders as $workorder){
                $listofopenwohtml .= '<tr class="loadaircraftworkordertr" data-val="'.$workorder['work_order_no'].'">';
                $listofopenwohtml .= '<td>'.$workorder['work_order_no'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['customers']['customer_name'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['otc_aircrafts']['aircraft_registration_number'].'</td>';
                $listofopenwohtml .= '<td>'.date('Y-m-d', strtotime($workorder['created_at'])).'</td>';
                $listofopenwohtml .= '<td>'.(!empty($workorder['updated_at'] && $workorder['wo_status'] == '6') ? date('Y-m-d', strtotime($workorder['updated_at'])) : '').'</td>';
                $listofopenwohtml .= '</tr>';
            }
            $listofopenwohtml .= '</tbody></table>';
        }else if($search_by == 'advanced_parts_search'){
            $listofopenwohtml .= '<table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="th-sm">Work Order</th>
                    <th class="th-sm">Type</th>
                    <th class="th-sm">Customer</th>
                    <th class="th-sm">Status</th>
                    <th class="th-sm">Date Comp.</th>
                    <th class="th-sm">Part Number</th>
                    <th class="th-sm">Description</th>
                    <th class="th-sm">Old Serial</th>
                    <th class="th-sm">New Serial</th>
                </tr>
            </thead>
            <tbody id="list-of-open-work-order-block">';
            foreach($aircrafAllWorkOrders as $workorder){
                $listofopenwohtml .= '<tr class="loadaircraftworkordertr" data-val="'.$workorder['work_order_no'].'">';
                $listofopenwohtml .= '<td>'.$workorder['work_order_no'].'</td>';
                $listofopenwohtml .= '<td>'.($workorder['order_type'] == '1' ? 'WO' : 'RO').'</td>';
                $listofopenwohtml .= '<td>'.$workorder['customers']['customer_name'].'</td>';
                $listofopenwohtml .= '<td>'.$aircraftWOStatus[$workorder['wo_status']].'</td>';
                $listofopenwohtml .= '<td>'.(!empty($workorder['updated_at'] && $workorder['wo_status'] == '6') ? date('Y-m-d', strtotime($workorder['updated_at'])) : '').'</td>';
                $listofopenwohtml .= '<td>'.$workorder['wo_item_parts']['part_number'].'</td>';
                $listofopenwohtml .= '<td>'.$workorder['wo_item_parts']['part_description'].'</td>';
                $listofopenwohtml .= '<td></td>';
                $listofopenwohtml .= '<td>'.$workorder['wo_item_parts']['serial_number'].'</td>';
                $listofopenwohtml .= '</tr>';
            }
            $listofopenwohtml .= '</tbody></table>';
        }

        return $listofopenwohtml;
    }

    public function getWorkOrderItemPartListHTML($woitempartlists){
        $tblrow = '';
        $invrequeststatus = unserialize(INVENTORY_REQUEST_STATUS);
        foreach($woitempartlists as $key=>$row){
            $activeclass = '';
            if($key=='0'){
                $activeclass = 'wo-item-part-list-active';
            }
            $tblrow .= '<tr class="woitempartstblrow woitempartslisttblrow '.$activeclass.'" data-val="'.$row['id'].'">';
            $tblrow .= '<td>'.$row['part_number'].'</td>';
            $tblrow .= '<td>'.$row['part_description'].'</td>';
            $tblrow .= '<td>'.$row['serial_number'].'</td>';
            $tblrow .= '<td>'.$row['qty_needed'].'</td>';
            $tblrow .= '<td>'.$row['qty_used'].'</td>';
            $tblrow .= '<td>'.(!empty($row['invrequest']['request_status']) ? $invrequeststatus[$row['invrequest']['request_status']] : '').'</td>';
            $tblrow .= '<td>'.(!empty($row['invrequest']['request_number']) ? '<a href="'.Router::url(['controller'=>'InventoryRequests', 'action'=>'detail', $row['invrequest']['id']]).'" title="Click here">'.$row['invrequest']['request_number'].'</a>' : '').'</td>';
            $tblrow .= '<td></td>';
            $tblrow .= '<td>'.$row['price_each'].'</td>';
            $tblrow .= '</tr>';
        }

        return $tblrow;
    }

    public function getWorkOrderAllPartsListHTML($workorderpartslist){
        $tblrow = '';
        foreach($workorderpartslist as $row){
            $tblrow .= '<tr class="woitempartstblrow woitempartsalllisttblrow" data-val="'.$row['id'].'">';
            $tblrow .= '<td>'.$row['wo_items']['wo_item_position'].'</td>';
            $tblrow .= '<td>'.$row['part_number'].'</td>';
            $tblrow .= '<td>'.$row['part_description'].'</td>';
            $tblrow .= '<td>'.$row['serial_number'].'</td>';
            $tblrow .= '<td>'.$row['qty_needed'].'</td>';
            $tblrow .= '<td>'.$row['qty_cust_owned'].'</td>';
            $tblrow .= '<td></td>';
            $tblrow .= '<td></td>';
            $tblrow .= '<td>'.$row['price_each'].'</td>';
            $tblrow .= '</tr>';
        }
        
        return $tblrow;
    }

    public function getWorkOrderItemToolListHTML($woitemtoollists){
        $tblrow = '';
        foreach($woitemtoollists as $key=>$row){
            $activeclass = '';
            if($key=='0'){
                $activeclass = 'woitem-tools-tblrow-active';
            }
            $tblrow .= '<tr class="woitem-tools-tblrow '.$activeclass.'" data-val="'.$row['id'].'">';
            $tblrow .= '<td>'.$row['wo_item_tool_name'].'</td>';
            $tblrow .= '<td>'.$row['model_no'].'</td>';
            $tblrow .= '<td>'.$row['serial_no'].'</td>';
            $tblrow .= '<td>'.$row['equipment_description'].'</td>';
            $tblrow .= '</tr>';
        }
        
        return $tblrow;
    }

    public function getWOMessageListHTML($receivedmsglist){
        $tblrow = '';
        foreach($receivedmsglist as $msg){
            $unread_msg_class = '';
            if($msg['is_mark_read'] == '0'){
                $unread_msg_class = 'wo_new_message';
            }
            
            $tblrow .= '<tr class="wo-option-message-tr '.$unread_msg_class.'" data-val="'.$msg['id'].'" is-read="'.$msg['is_mark_read'].'">';
            $tblrow .= '<td>'.$msg['sent_from'].'</td>';
            $tblrow .= '<td>'.$msg['message_subject'].'</td>';
            $tblrow .= '<td>'.$msg['created_at'].'</td>';
            $tblrow .= '<td>
                            <input type="checkbox" name="messageid[]" class="check_messages" value="'.$msg['id'].'" />
                        </td>';
            $tblrow .= '</tr>';
        }
        
        return $tblrow;
    }

    public function getWOItemSignoffCategoryHTML($woitemsignoffdet, $wo_item_id){
        $woItemSignOff = unserialize(WOITEMSIGNOFF);
        $tblrow = '';
        foreach($woItemSignOff as $id =>$signoff){
            $inspected_by = '';
            $inspected_date = '';
            if(!empty($woitemsignoffdet[$id])){
                $singoffdata = $woitemsignoffdet[$id];

                $inspected_by = !empty(@$singoffdata['users']['full_name']) ? @$singoffdata['users']['full_name'] : '';
                $inspected_date = !empty(@$singoffdata['inspected_date']) ? date('m/d/Y', strtotime($singoffdata['inspected_date'])) : '';
            }
        
            $tblrow .='<tr class="signoff-categories-tr" wo-item-id="'.$wo_item_id.'" signoff-category = "'.$id.'">';
            $tblrow .='<td>'.$signoff.'</td>';
            $tblrow .='<td>'.$inspected_by.'</td>';
            $tblrow .='<td>'.$inspected_date.'</td>';
            $tblrow .='</tr>';
        }

        return $tblrow;
    }

    public function getWOItemListForSignOffHTML($aircraftwoitems){
        $itemstatus = unserialize(AIRCRAFT_WORKORDER_ITEMSTATUS);
        $tblrow = '';
        foreach($aircraftwoitems as $key=>$woitem){
            $classname = '';
            if($woitem['wo_item_status'] == '1' || $woitem['wo_item_status'] == '2') {
                $classname = 'wosignoff-color-box2';
            }else if($woitem['wo_item_status'] == '3') {
                $classname = 'wosignoff-color-box1';
            }
            $tblrow .='<tr class="wo-signoff-tr '.$classname.'" data-val="'.$woitem['id'].'">';
            $tblrow .='<td>'.$woitem['wo_item_position'].'</td>';
            $tblrow .='<td>'.$woitem['wo_discrepancy'].'</td>';
            $tblrow .='<td>'.$woitem['wo_corrective_action'].'</td>';
            $tblrow .='<td>'.(!empty($woitem['wo_item_status']) ? $itemstatus[$woitem['wo_item_status']] : '').'</td>';
            $tblrow .='</tr>';
        }

        return $tblrow;
    }
}