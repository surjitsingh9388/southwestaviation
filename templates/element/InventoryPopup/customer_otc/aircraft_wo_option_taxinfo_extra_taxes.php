<div id="aircarftOptionWOExtraTaxesModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Extra Taxes</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="reference">List of Extra Taxes</label>
                            </div>

                            <div class="form-group list-of-extra-taxes">
                                <?php
                                foreach($wooptionextrataxlist as $taxdet){
                                ?>
                                    <div class="wo-option-extra-taxes-list" data-val="<?php echo $taxdet['id']; ?>"><?php echo $taxdet['extra_tax_name']; ?></div>
                                <?php
                                }
                                ?>
                            </div>

                            <div class="col-md-12">
                                <button type="button" class="btn btn-default wo-option-new-extra-taxes">New</button>
                                <button type="button" class="btn btn-default wo-option-delete-extra-taxes" disabled>Delete</button>
                            </div>

                        </div>

                        <div class="col-md-9 extra-taxes-setup-block">
                            <?php echo $this->element('Inventory/customer_otc/aircraft_wo_option_taxinfo_extra_tax_setup'); ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary float-right saveWOOptionExtraTaxes">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var saveWOViewOptionExtraTaxesURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOViewOptionExtraTaxes']); ?>";
    var getWOViewOptionExtraTaxesDataURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getWOViewOptionExtraTaxesData']); ?>";
    var deleteWOViewOptionExtraTaxesURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteWOViewOptionExtraTaxes']); ?>";
</script>