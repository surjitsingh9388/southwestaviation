<div class="purchaseorder mt5">
    <div class="col-sm-12" style="margin-bottom: 10px;">
        <div class="col-sm-4">
            <input type="text" id="quantitesSearchItem" name="search" class="form-control linkedOrderSearchItem" placeholder="Search Line Item" style="border-radius: 5px">
            <div style="display: inline; position:absolute;right: 20px;top: 6px;color: darkgray">
                <i class="fa fa-search"></i>
            </div>
        </div>
        <?php if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) { ?>
        <div class="split-btn pull-right actionMenu sortWrap col-sm-8" style="width:auto; padding-right:3%;">
            <div class="col-sm-8">
                <button type="button" class="btn-dropdown btn-default">Action on Selected<span class="selectCount"></span></button>
                <button type="button" class="icon-part dropdown-toggle actionCls" data-toggle="dropdown">
                    <i class="fa fa-caret-down"></i>
                </button>
                                    
                <div class="dropdown-content dropdown-menu actionLinks">
                    <a href="javascript:void(0);" class="actionOnSelected" id="unlinkOrder" style="pointer-events: none">Unlink</a>
                </div>
            </div>
            <div class="col-sm-4">
                <button type="button" class="btn btn-primary linktoanotherorder">+ Linked Order</button>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<div class="g-0 bg-light position-relative">
    <div class="linked-orders">
        <?php
        if($linkorderdata['linkedpurchaseorderscount'] > 0){
        ?>
        <label>Purchase Orders (<?php echo $linkorderdata['linkedpurchaseorderscount']; ?>)</label>
        <table class="table" id="invPOHistoryTable">
            <thead class="thead-dark">
                <tr>
                    <th style="vertical-align: top; width:5%;" class="check"><input type="checkbox" name="air_check" id="ckbPOCheckAll"></th>
                    <th style="width:14%;">Number / Type</th>
                    <th style="width:14%;">Reference</th>
                    <th style="width:14%;">Vendor</th>
                    <th style="width:14%;">Submitted</th>
                    <th style="width:14%;">&nbsp;</th>
                    <th style="width:14%;">&nbsp;</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="linkedPurchaseOrderList">
                <?php
                foreach($linkorderdata['linkedpurchaseorders'] as $purchaseorder){//echo "<pre>";print_r($purchaseorder['purchase_order']);exit;
                    $vendor_name = isset($purchaseorder['vendor']['name']) ? $purchaseorder['vendor']['name'] : '';
                    
                    $statushtml = '';
                    if($purchaseorder['status'] == '1'){
                        if($purchaseorder['po_status'] == '1'){
                            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-approved">Partial</div>';
                        }else if($purchaseorder['po_status'] == '0'){
                            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-pending">Draft</div>';
                        }else if($purchaseorder['po_status'] == '3'){
                            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-canceled">Canceled</div>';
                        }else if($purchaseorder['po_status'] == '5'){
                            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-created">PO Created</div>';
                        }else if($purchaseorder['po_status'] == '2'){
                            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-sent">Sent</div>';
                        }else if($purchaseorder['po_status'] == '4'){
                            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-closed">Close</div>';
                        }
                    }else{
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-inactive po-inactive">Inactive</div>';
                    }

                    if($purchaseorder['po_type'] == '2'){
                        if($purchaseorder['exchange_status'] == '1'){
                            $statushtml .= '<div class="exchange-status-indicator">
                                                    <span title="Core exchange status" class="badge badge-dark exchange-status-badge">
                                                    <i class="fa fa-exchange"></i>
                                                    <span>&nbsp;None</span>
                                                </span>
                                            </div>';
                        }else if($purchaseorder['exchange_status'] == '2'){
                            $statushtml .= '<div class="exchange-status-indicator">
                                                    <span title="Core exchange status" class="badge badge-dark exchange-status-badge exchange-status-open">
                                                    <i class="fa fa-exchange"></i>
                                                    <span>&nbsp;Open</span>
                                                </span>
                                            </div>';
                        }else if($purchaseorder['exchange_status'] == '3'){
                            $statushtml .= '<div class="exchange-status-indicator">
                                                    <span title="Core exchange status" class="badge badge-dark exchange-status-badge exchange-status-returned">
                                                    <i class="fa fa-exchange"></i>
                                                    <span>&nbsp;Returned</span>
                                                </span>
                                            </div>';
                        }
                    }
                    
                    $isexchange = $purchaseorder['po_type'] == '2' ? '<br/><span style="color:#8d8888; font-size:10px;" title="Purchase order type">Exchange</span>' : '';
                ?>
                    <tr>
                        <td style="text-align:center"><input type="checkbox" value="<?php echo $purchaseorder['id']; ?>" class="chkBoxPO linkedOrderChkbox"></td>
                        <td>
                            <a href="<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'detail', $purchaseorder['id']]); ?>"><?php echo $purchaseorder['po_number']; ?></a>
                        </td>
                        <td><?php echo $purchaseorder['reference']; ?></td>
                        <td><?php echo $vendor_name; ?></td>
                        <td><?php echo date('d-M-Y', strtotime($purchaseorder['created'])); ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><?php echo $statushtml; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>

        <?php
        if($linkorderdata['linkedrequestscount'] > 0){
        ?>
        <label>Requests (<?php echo $linkorderdata['linkedrequestscount']; ?>)</label>
        <table class="table" id="invPOHistoryTable">
            <thead class="thead-dark">
                <tr>
                    <th style="vertical-align: top; width:5%;" class="check noExl"><input type="checkbox" name="air_check" id="ckbReqCheckAll"></th>
                    <th style="width:14%;">Number</th>
                    <th style="width:14%;">Title</th>
                    <th style="width:14%;">&nbsp;</th>
                    <th style="width:14%;">Date Requested</th>
                    <th style="width:14%;">Date Required</th>
                    <th style="width:14%;">Urgency</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="linkedRequestList">
                <?php
                foreach($linkorderdata['linkedrequests'] as $requests){
                    $requests = isset($requests['requests']) ? $requests['requests'] : $requests;
                    $urgency = unserialize(URGENCY);

                    $statushtml = '';
                    if($requests['status'] == '0'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status">Inactive</div>';
                    }
                    if($requests['request_status'] == '1'){
                        $statushtml .= '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-approved">Approved</div>';
                    }else if($requests['request_status'] == '0'){
                        $statushtml .= '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-pending">Pending Review</div>';
                    }else if($requests['request_status'] == '2'){
                        $statushtml .= '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-canceled">Canceled</div>';
                    }else if($requests['request_status'] == '5'){
                        $statushtml .= '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-created">PO Created</div>';
                    }else if($requests['request_status'] == '3'){
                        $statushtml .= '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-denied">Denied</div>';
                    }else if($requests['request_status'] == '4'){
                        $statushtml .= '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-closed">Close</div>';
                    }
                ?>
                    <tr>
                        <td style="text-align:center"><input type="checkbox" value="<?php echo $requests['id']; ?>" class="chkBoxReq linkedOrderChkbox"></td>
                        <td>
                            <a href="<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'detail', $requests['id']]); ?>"><?php echo $requests['request_number']; ?></a>
                        </td>
                        <td><?php echo $requests['title']; ?></td>
                        <td>&nbsp;</td>
                        <td><?php echo date('d-M-Y', strtotime($requests['created'])); ?></td>
                        <td><?php echo date('d-M-Y', strtotime($requests['need_by'])); ?></td>
                        <td><?php echo !empty($requests['urgency']) ? $urgency[$requests['urgency']] : ''; ?></td>
                        <td><?php echo $statushtml; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>

        <?php
        if($linkorderdata['linkedshippingorderscount'] > 0){
        ?>
        <label>Shipping Orders (<?php echo $linkorderdata['linkedshippingorderscount']; ?>)</label>
        <table class="table" id="invPOHistoryTable">
            <thead class="thead-dark">
                <tr>
                    <th style="vertical-align: top; width:5%;" class="check noExl"><input type="checkbox" name="air_check" id="ckbSOCheckAll"></th>
                    <th style="width:14%;">Number</th>
                    <th style="width:14%;">Reference</th>
                    <th style="width:14%;">&nbsp;</th>
                    <th style="width:14%;">Submitted</th>
                    <th style="width:14%;">&nbsp;</th>
                    <th style="width:14%;">&nbsp;</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="linkedShippingOrderList">
                <?php
                foreach($linkorderdata['linkedshippingorders'] as $shippingorder){
                    $shippingorder = isset($shippingorder['shippingorder']) ? $shippingorder['shippingorder'] : $shippingorder;
                    $statushtml = '';
                
                    if($shippingorder['shipping_order_status'] == '1'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-approved">Partial</div>';
                    }else if($shippingorder['shipping_order_status'] == '0'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-pending">Draft</div>';
                    }else if($shippingorder['shipping_order_status'] == '3'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-canceled">Canceled</div>';
                    }else if($shippingorder['shipping_order_status'] == '5'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-created">RO Created</div>';
                    }else if($shippingorder['shipping_order_status'] == '2'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-sent">Shipped</div>';
                    }else if($shippingorder['shipping_order_status'] == '4'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-sent">Received</div>';
                    }
                ?>
                    <tr>
                        <td style="text-align:center"><input type="checkbox" value="<?php echo $shippingorder['id']; ?>" class="chkBoxSO linkedOrderChkbox"></td>
                        <td>
                            <a href="<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'detail', $shippingorder['id']]); ?>"><?php echo $shippingorder['shipping_order_number']; ?></a>
                        </td>
                        <td><?php echo $shippingorder['reference']; ?></td>
                        <td>&nbsp;</td>
                        <td><?php echo date('d-M-Y', strtotime($shippingorder['created'])); ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><?php echo $statushtml; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>

        <?php
        if($linkorderdata['linkedrepairorderscount'] > 0){
        ?>
        <label>Repair Orders (<?php echo $linkorderdata['linkedrepairorderscount']; ?>)</label>
        <table class="table" id="invPOHistoryTable">
            <thead class="thead-dark">
                <tr>
                    <th style="vertical-align: top; width:5%;" class="check noExl"><input type="checkbox" name="air_check" id="ckbROCheckAll"></th>
                    <th style="width:14%;">Number</th>
                    <th style="width:14%;">Reference</th>
                    <th style="width:14%;">Vendor</th>
                    <th style="width:14%;">Submitted</th>
                    <th style="width:14%;">&nbsp;</th>
                    <th style="width:14%;">&nbsp;</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="linkedRepairOrderList">
                <?php
                foreach($linkorderdata['linkedrepairorders'] as $repairorder){
                    $vendor_name = isset($repairorder['vendor']['name']) ? $repairorder['vendor']['name'] : '';
                    $repairorder = isset($repairorder['repairorder']) ? $repairorder['repairorder'] : $repairorder;
                    $statushtml = '';
                
                    if($repairorder['ro_status'] == '1'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-approved">Partial</div>';
                    }else if($repairorder['ro_status'] == '0'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-pending">Draft</div>';
                    }else if($repairorder['ro_status'] == '3'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-canceled">Canceled</div>';
                    }else if($repairorder['ro_status'] == '5'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-created">RO Created</div>';
                    }else if($repairorder['ro_status'] == '2'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-sent">Shipped</div>';
                    }else if($repairorder['ro_status'] == '4'){
                        $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-sent">Received</div>';
                    }
                ?>
                    <tr>
                        <td style="text-align:center"><input type="checkbox" value="<?php echo $repairorder['id']; ?>" class="chkBoxRO linkedOrderChkbox"></td>
                        <td>
                            <a href="<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'detail', $repairorder['id']]); ?>"><?php echo $repairorder['ro_number']; ?></a>
                        </td>
                        <td><?php echo $repairorder['reference']; ?></td>
                        <td><?php echo $vendor_name; ?></td>
                        <td><?php echo date('d-M-Y', strtotime($repairorder['created'])); ?></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><?php echo $statushtml; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>
    
    </div>
</div>