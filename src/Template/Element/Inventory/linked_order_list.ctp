<style>
    th{
        width:14%;
    }
</style>
<div class="purchaseorder mt5 row">
    <div class="col-sm-12" style="margin-bottom: 10px;">
        <div class="col-sm-4 mb-5">
            <input type="text" id="quantitesSearchItem" name="search" class="form-control linkedOrderSearchItem" placeholder="Search Line Item" style="border-radius: 5px">
            <div style="display: inline; position:absolute;right: 20px;top: 6px;color: darkgray">
                <i class="fa fa-search"></i>
            </div>
        </div>
        <?php if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) { ?>
        <div class="split-btn pull-right actionMenu sortWrap col-sm-8" style="width:auto; padding-right:3%;">
            <div class="col-sm-7 mb-5">
                <button type="button" class="btn-dropdown btn-default">Action on Selected<span class="selectCount"></span></button>
                <button type="button" class="icon-part dropdown-toggle actionCls" data-toggle="dropdown">
                    <i class="fa fa-caret-down"></i>
                </button>
                                    
                <div class="dropdown-content dropdown-menu actionLinks">
                    <a href="javascript:void(0);" class="actionOnSelected" id="unlinkOrder" style="pointer-events: none">Unlink</a>
                </div>
            </div>
            <div class="col-sm-5 mb-5">
                <button type="button" class="btn btn-primary linktoanotherorder">+ Linked Order</button>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<div class="g-0 bg-light position-relative">
    <div class="linked-orders pl10 pr10">
        <?php
        if($linkorderdata['linkedpurchaseorderscount'] > 0 || $linkorderdata['linkedrequestscount'] > 0 || $linkorderdata['linkedshippingorderscount'] > 0 || $linkorderdata['linkedrepairorderscount'] > 0){
        
        if($linkorderdata['linkedpurchaseorderscount'] > 0){
        ?>
        <label>Purchase Orders (<?php echo $linkorderdata['linkedpurchaseorderscount']; ?>)</label>
        <div style="overflow-x: auto">
            <table class="table" id="invPOHistoryTable">
                <thead class="thead-dark">
                    <tr>
                        <th style="vertical-align: top; width:5%;" class="check"><input type="checkbox" name="air_check" id="ckbPOCheckAll"></th>
                        <th>Number / Type</th>
                        <th>Reference</th>
                        <th>Vendor</th>
                        <th>Submitted</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="linkedPurchaseOrderList">
                    <?php
                    foreach($linkorderdata['linkedpurchaseorders'] as $purchaseorder){//echo "<pre>";print_r($purchaseorder['purchase_order']);exit;
                        $vendor_name = isset($purchaseorder['vendor']['name']) ? $purchaseorder['vendor']['name'] : '';
                        
                        $statushtml = $this->InventoryStatusHTML->getPurchaseOrderOrderStatusHTML($purchaseorder['status'], $purchaseorder['po_status'], $purchaseorder['po_type'], $purchaseorder['exchange_status']);
                        
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
        </div>
        <?php } ?>

        <?php
        if($linkorderdata['linkedrequestscount'] > 0){
        ?>
        <label>Requests (<?php echo $linkorderdata['linkedrequestscount']; ?>)</label>
        <div style="overflow-x: auto">
            <table class="table" id="invPOHistoryTable">
                <thead class="thead-dark">
                    <tr>
                        <th style="vertical-align: top; width:5%;" class="check noExl"><input type="checkbox" name="air_check" id="ckbReqCheckAll"></th>
                        <th>Number</th>
                        <th>Title</th>
                        <th>&nbsp;</th>
                        <th>Date Requested</th>
                        <th>Date Required</th>
                        <th>Urgency</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="linkedRequestList">
                    <?php
                    foreach($linkorderdata['linkedrequests'] as $requests){
                        $requests = isset($requests['requests']) ? $requests['requests'] : $requests;
                        $urgency = unserialize(URGENCY);

                        $statushtml = $this->InventoryStatusHTML->getInventoryRequestStatusHTML($requests['status'], $requests['request_status']);
                        
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
        </div>
        <?php } ?>

        <?php
        if($linkorderdata['linkedshippingorderscount'] > 0){
        ?>
        <label>Shipping Orders (<?php echo $linkorderdata['linkedshippingorderscount']; ?>)</label>
        <table class="table" id="invPOHistoryTable">
            <thead class="thead-dark">
                <tr>
                    <th style="vertical-align: top; width:5%;" class="check noExl"><input type="checkbox" name="air_check" id="ckbSOCheckAll"></th>
                    <th>Number</th>
                    <th>Reference</th>
                    <th>&nbsp;</th>
                    <th>Submitted</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="linkedShippingOrderList">
                <?php
                foreach($linkorderdata['linkedshippingorders'] as $shippingorder){
                    $shippingorder = isset($shippingorder['shippingorder']) ? $shippingorder['shippingorder'] : $shippingorder;

                    $statushtml = $this->InventoryStatusHTML->getShippingOrderStatusHTML($shippingorder['status'], $shippingorder['shipping_order_status']);
                    
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
                    <th>Number</th>
                    <th>Reference</th>
                    <th>Vendor</th>
                    <th>Submitted</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="linkedRepairOrderList">
                <?php
                foreach($linkorderdata['linkedrepairorders'] as $repairorder){
                    $vendor_name = isset($repairorder['vendor']['name']) ? $repairorder['vendor']['name'] : '';
                    $repairorder = isset($repairorder['repairorder']) ? $repairorder['repairorder'] : $repairorder;
                    
                    $statushtml = $this->InventoryStatusHTML->getRepairOrderStatusHTML($repairorder['status'], $repairorder['ro_status']);
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
        <?php }}else{ ?>
            <div style="font-weight: bold; text-align: center;">No Linked Order Found!</div>
        <?php } ?>
    
    </div>
</div>