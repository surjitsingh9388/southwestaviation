<div id="advancedWOFindOptionsModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 70%;">
        <?php
        //echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomersNotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Advanced Find Options</h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <div class="col-sm-6 col-xs-12">
                        <div class="col-xs-12"><b>Advanced Find Options</b></div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Reg. Number</label>
                                <div class="form-input-frame">
                                    <?php
                                        echo $this->Form->control('adv_filter_wo_reg_number', array('options' => $aircraftregnumlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'adv_filter_wo_reg_number'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Discrepancy</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('adv_filter_wo_discrpancy', array('type'=>'textarea','class' => 'form-control', 'label'=> false, 'row mb-3s'=>2, 'id'=>'adv_filter_wo_discrpancy')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Corrective Action</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('adv_filter_wo_corrective_action', array('type'=>'textarea','class' => 'form-control', 'label'=> false, 'row mb-3s'=>2, 'id'=>'adv_filter_wo_corrective_action')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-default float-right searchAdvFindOptInWO">Find</button>
                        </div>
                        <div class="col-xs-12"><b>Other Find Options</b></div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Misc. Charges Notes</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('misc_charges_notes', array('type'=>'textarea','class' => 'form-control', 'label'=> false, 'row mb-3s'=>2, 'id'=>'adv_filter_wo_misc_charges_notes')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-default float-right searchOtherFindOptInWO">Find</button>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12">
                        <div class="col-xs-12"><b>Advanced Parts Search</b></div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Part Number</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('adv_filter_wo_part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'adv_filter_wo_part_number')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Description</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('adv_filter_wo_description', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'adv_filter_wo_description')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Old Serial No.</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('adv_filter_wo_old_serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'adv_filter_wo_old_serial_no')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">New Serial Number</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('adv_filter_wo_new_serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'adv_filter_wo_new_serial_no')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Supplier</label>
                                <div class="form-input-frame">
                                    <?php
                                        echo $this->Form->control('adv_filter_wo_supplier', array('options' => $vendorlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'adv_filter_wo_supplier'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <button type="button" class="btn btn-default float-right searchPartSearchInWO">Find</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var filterOpenWODepartsURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'filterOpenWODeparts']); ?>";
</script>
