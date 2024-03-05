<section class="top-form-section">
    <div class="row">
        <div class="col-md-12 mt10">
            <div class="btn-group">
                <button type="button" class="btn btn-default addoutsiderepair" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Add Outside Repair</button>
                <button type="button" class="btn btn-default removewoosrbtn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Remove Outside Repair</button>
                <button type="button" class="btn btn-default wolistallosrbtn">List All OSR</button>
                <button type="button" class="btn btn-default woosrmoveitembtn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Move Item</button>
                <button type="button" class="btn btn-default fetchCustOTCPopup" data-val='wo_osr_send_msg_btn'>Send Msg.</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt10">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Vendor</th>
                        <th scope="col">Invoice</th>
                        <th scope="col">Part Number</th>
                        <th scope="col">Labor</th>
                        <th scope="col">Part</th>
                        <th scope="col">P/O Num.</th>
                    </tr>
                </thead>
                <tbody class="wo-osr-list">
                    <?php
                    foreach($aircraftwoitemosrinfoes as $key=>$osrinfo){
                        $activeclass = '';
                        if($key == 0){
                            $activeclass = 'wo-osr-list-active';
                        }
                    ?>
                    <tr class="editwoosritem <?php echo $activeclass; ?>" data-val="<?php echo $osrinfo['id']; ?>">
                        <td><?php echo $osrinfo['vendors']['vendor_name']; ?></td>
                        <td><?php echo $osrinfo['osr_invoice_no']; ?></td>
                        <td><?php echo $osrinfo['osr_part_number']; ?></td>
                        <td><?php echo $osrinfo['osr_labor_charge']; ?></td>
                        <td><?php echo $osrinfo['osr_parts_charge']; ?></td>
                        <td><?php echo $osrinfo['osr_purchase_order_no']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script>
    var saveAircraftWOItemOSRInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftWOItemOSRInfo']); ?>";
    var fetchWOOSRCreateHtmlURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'fetchWOOSRCreateHtml']); ?>";
    var setOSRPORODataURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'setOSRPOROData']); ?>";
    var saveWOOSRVendorDetailURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOOSRVendorDetail']); ?>";
    var fetchWOOSRCreateVendorHtmlURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'fetchWOOSRCreateVendorHtml']); ?>";
    var uploadWOOSRVendorMediaURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'uploadWOOSRVendorMedia']); ?>";
    var saveWOOSRVendorMediaURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOOSRVendorMedia']); ?>";
    var deleteWOOSRVendorMediaURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteWOOSRVendorMedia']); ?>";
    var deleteWOOSRVendorURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteWOOSRVendor']); ?>";
    var fetchWOOSRVendorPhonesURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'fetchWOOSRVendorPhones']); ?>";
    var saveWOOSRServicePOURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOOSRServicePO']); ?>";
    var uploadWOOSRPurchaseOrderMediaURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'uploadWOOSRPurchaseOrderMedia']); ?>";
    var saveWOOSRPurchaseOrderMediaURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOOSRPurchaseOrderMedia']); ?>";
    var deleteWOOSRPurchaseOrderMediaURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteWOOSRPurchaseOrderMedia']); ?>";
    var saveWOOSRPurchaseOrderReminderURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOOSRPurchaseOrderReminder']); ?>";
    var fetchWOOSRCheckInLaborHTMLURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'fetchWOOSRCheckInLaborHTML']); ?>";
    var getExistingPONumByVendorIdURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getExistingPONumByVendorId']); ?>";
    var saveWOOSRPOItemsURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOOSRPOItems']); ?>";
    var deleteWOOSRRecordURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteWOOSRRecord']); ?>";
    var deleteWOOSRPOItemsRecordURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteWOOSRPOItemsRecord']); ?>";
</script>