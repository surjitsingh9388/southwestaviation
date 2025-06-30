<div class="modal-dialog modal-lg" style="height:auto;">
    <div class="modal-content">
        <div class="modal-header" style="background-color: #e5e5e5;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><span class="gpTypeCls"></span>Purchase Order Filter</h4>
        </div>

        <div class="modal-body" style="max-height: auto; overflow-y: auto;">
            <?php echo $this->Form->create(null, ['url' => '', 'id' => 'formPurchaseOrderFilter', 'autocomplete' => 'off']); ?>
            <div class="page-content">
                <div class="row mt10">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Type</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <?php
                                $invPOType = unserialize(INVENTORY_PURCHASE_TYPE);
                                echo $this->Form->control('po_type', array('options' => $invPOType, 'empty' => 'Enter Type ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_type'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Status</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <?php
                                $invPOStatus = unserialize(INVENTORY_PURCHASE_STATUS);
                                echo $this->Form->control('po_status', array('options' => $invPOStatus, 'empty' => 'Select Status', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_status'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Exchange Status</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <?php
                                $invPOExchangeStatus = unserialize(INVENTORY_PURCHASE_EXCHANGE_STATUS);
                                echo $this->Form->control('exchange_status', array('options' => $invPOExchangeStatus, 'empty' => 'Select Exchange Status', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_exchange_status'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Min Amount</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php echo $this->Form->control('po_min_amount', array('type' => 'number', 'class' => 'form-control col-md-9 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Max Amount</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php echo $this->Form->control('po_max_amount', array('type' => 'number', 'class' => 'form-control col-md-9 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Requestor</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php echo $this->Form->control('requestor', array('class' => 'form-control col-md-9 col-xs-12', 'placeholder' => '', 'label' => false, 'id' => 'po_requestor')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Reference</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php echo $this->Form->control('reference', array('class' => 'form-control col-md-9 col-xs-12', 'placeholder' => '', 'label' => false, 'id' => 'po_reference')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="row mt10">
                    <div class="col-xs-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Start Date</label>
                            <div class=" col-sm-9 col-md-9  col-xs-12">
                                <div class="input-group date datePicker">
                                    <?php echo $this->Form->Text('po_date_start', array('class' => 'form-control datePicker', 'id' => 'po_date_start', 'placeholder' => '', 'label' => false, 'placeholder' => 'e.g. ' . date('m-d-Y'))); ?>
                                    <span class="input-group-addon" style="display:none;">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="col-xs-12 mt10">
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Start Date</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="input-group date datePicker">
                                <?php echo $this->Form->Text('po_date_start', array(
                                    'class' => 'form-control datePicker',
                                    'id' => 'po_date_start',
                                    'label' => false,
                                    'placeholder' => 'e.g. ' . date('m-d-Y')
                                )); ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 mt10">
                <div class="form-group row">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">End Date</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="input-group date datePicker" style="width: 100%;">
                            <?php echo $this->Form->Text('po_date_end', array(
                                'class' => 'form-control datePicker',
                                'id' => 'po_date_end',
                                'label' => false,
                                'placeholder' => 'e.g. ' . date('m-d-Y')
                            )); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt10">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Account Code</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <?php
                            echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Enter Type ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_account_code'));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-xs-12">
                    <div class="form-group pl10">
                        <input id="onlyopenpos" name="openpos" type="checkbox" value="1">&nbsp;Show Only Open POs
                    </div>
                    <div class="form-group pl10">
                        <input id="includeinactive" name="status" type="checkbox" value="1">&nbsp;Include Inactive
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default clearapplyPOfilter">Clear</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary applyfilterPObtn">Apply Filter</button>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
</div>