<section class="top-form-section">
    <?php $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '' ?>

    <div class="row">
        
        <div class="col-md-12">
            <fieldset class="scheduler-border" style="padding:0px !important;">
                <legend class="scheduler-border" style="padding:0px !important;">Warranty Companies</legend>
                <table class='table table-bordered' style="margin-bottom:0px !important;">
                    <?php
                    $warrantywoarr = unserialize(WOITEMOVERVIEWWARRANTY);
                    foreach($warrantylist as $itemhistory){
                    ?>
                    <tr class="wo_option_warranty_info_row" data-val="<?php echo $itemhistory['wo_item_overviews']['warranty']; ?>">
                        <td><?php echo $warrantywoarr[$itemhistory['wo_item_overviews']['warranty']]; ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </fieldset>
        </div>

        <div class="col-md-12" id="warranty_info_payment_options">
            <?php echo $this->element('Inventory/customer_otc/wo_option_warranty_info_payment'); ?>
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-primary float-right saveWOOptionWarrantyInfo" <?php echo $isdisabled; ?>>Save</button>
        </div>
    </div>
</section>
<script>
    var saveWOViewOptionWarrantyInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOViewOptionWarrantyInfo']); ?>";
    var getWarrantyInfoPaymentOptionsURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getWarrantyInfoPaymentOptions']); ?>";
</script>