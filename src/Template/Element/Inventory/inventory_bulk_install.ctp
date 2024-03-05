<div class="modal-header" style="background-color: #e5e5e5;">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><span class="gpTypeCls"></span>Bulk Install</h4>
</div>
<div class="modal-body" style="max-height: 500px; overflow-y: auto;">
    <?php echo $this->Form->create('', ['url' =>'', 'id' => 'frmInventoryQuantitiesBulkInstall', 'autocomplete'=>'off']); ?>
    <div class="page-content">
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Install To&nbsp;<span class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php 
                            echo $this->Form->control('install_to', array('options' => $installto, 'empty' => 'Select Install To ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'install_to')); 
                            ?>
                        </div>
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
                        echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Enter an account code ...', 'class' => 'form-control col-md-4 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'install_account_code')); 
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
        <button type="button" class="btn btn-primary applyinvqtyinstallbtn" disabled>Install</button>
    </div>
</div>