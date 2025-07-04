<div class="modal-header" style="background-color: #e5e5e5;">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><span class="gpTypeCls"></span>Bulk Transfer</h4>
</div>
<div class="modal-body" style="max-height: 700px; overflow-y: auto;">
    <?php echo $this->Form->create(null, ['url' =>'', 'id' => 'frmInventoryQuantitiesBulkTransfer', 'autocomplete'=>'off']); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Destination Location&nbsp;<span class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('location_id', array('options' => $location, 'empty' => 'Select a location...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id'=>'transfer_location_id'));  ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Account Code</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                    <?php 
                        echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Enter an account code ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'transfer_account_code')); 
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt5">
            <div class="col-md-12">
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary applyinvqtytransferbtn" disabled>Transfer</button>
    </div>
</div>