<div class="row">
    <?php
    echo $this->Form->create($wooutstandingoutside, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOSR'));
    ?>
    <input type="hidden" name="wo_item_id" id="osrinfo_item_id" value="<?php echo $wo_item_id; ?>" />
    <input type="hidden" name="work_order_id" id="osrinfo_wo_id" value="<?php echo $work_order_id; ?>" />
    <input type="hidden" name="wo_osrinfo_id" id="wo_osrinfo_id" value="<?php echo @$wooutstandingoutside->id; ?>" />
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label label-heading-left" for="reference">Repair Done By</label>
                    <span class="label-chkbox-right <?php if(!empty($woitemdata->wo_item_status) && $woitemdata->wo_item_status != '3'){ ?>wo-vendor-list-sec<?php } ?>">
                        <span class="form-check-label" for="flexCheckDefault">Go-to Vendor</a></span>
                    </span>
                    <div class="form-input-frame">
                        <?php
                            echo $this->Form->control('osr_repair_done_by', array('options' => $inventoryvendors, 'empty' => 'Select Repair Done By', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'osr_repair_done_by'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="reference">Part Number</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('osr_part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="reference">Old Serial Number</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('osr_old_serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="reference">Labor Charge (To Customer)</label>
                    <div class="form-input-frame">
                        <?php 
                        $osr_labor_charge = '$0.00';
                        if(!empty($wooutstandingoutside->osr_labor_charge)){
                            $osr_labor_charge = '$'.number_format($wooutstandingoutside->osr_labor_charge, 2);
                        }
                        echo $this->Form->control('osr_labor_charge', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'osr_labor_charge', 'value'=>$osr_labor_charge)); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php
                    $osr_tax_labor_chk = '';
                    if(!empty($wooutstandingoutside->osr_tax_labor)){
                        $osr_tax_labor_chk = 'checked';
                    }
                    ?>
                    <input type="checkbox" name="osr_tax_labor" value="1" <?php echo $osr_tax_labor_chk; ?> />&nbsp;Tax Labor
                </div>
                <div class="form-group">
                    <label class="control-label" for="reference">Shipping Out</label>
                    <div class="form-input-frame">
                        <?php 
                        $osr_shipping_out = '$0.00';
                        if(!empty($wooutstandingoutside->osr_shipping_out)){
                            $osr_shipping_out = '$'.number_format($wooutstandingoutside->osr_shipping_out, 2);
                        }
                        echo $this->Form->control('osr_shipping_out', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'osr_shipping_out', 'value'=>$osr_shipping_out)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="reference">Condition</label>
                    <div class="form-input-frame">
                        <?php
                            $outsideRepairInfoCondition = unserialize(OUTSIDE_REPAIR_INFO_CONDITION);
                            echo $this->Form->control('osr_condition', array('options' => $outsideRepairInfoCondition, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'osr_condition'));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="reference">New Serial Number</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('osr_new_serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="reference">Parts Charge (To Customer)</label>
                    <div class="form-input-frame">
                        <?php 
                        $osr_parts_charge = '$0.00';
                        if(!empty($wooutstandingoutside->osr_parts_charge)){
                            $osr_parts_charge = '$'.number_format($wooutstandingoutside->osr_parts_charge, 2);
                        }
                        echo $this->Form->control('osr_parts_charge', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'osr_parts_charge', 'value'=>$osr_parts_charge)); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php
                    $osr_tax_parts_chk = '';
                    if(!empty($wooutstandingoutside->osr_tax_parts)){
                        $osr_tax_parts_chk = 'checked';
                    }
                    ?>
                    <input type="checkbox" name="osr_tax_parts" value="1" <?php echo $osr_tax_parts_chk; ?> />&nbsp;Tax Parts
                </div>
                <div class="form-group">
                    <label class="control-label" for="reference">Shipping In</label>
                    <div class="form-input-frame">
                        <?php 
                        $osr_shipping_in = '$0.00';
                        if(!empty($wooutstandingoutside->osr_shipping_in)){
                            $osr_shipping_in = '$'.number_format($wooutstandingoutside->osr_shipping_in, 2);
                        }
                        echo $this->Form->control('osr_shipping_in', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'osr_shipping_in', 'value'=>$osr_shipping_in)); ?>
                    </div>
                </div>
            </div>
        </div>
         <div class="col-md-6 mol-sm-12 col-xs-12">
            <div class="col-md-6 mol-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label label-heading-left" for="reference">Invoice No.</label>
                    <span class="label-chkbox-right woosr-ro-list-sec" <?php if(empty(@$wooutstandingoutside->is_create_new_ro) || $wodetails->order_type== '2'){ ?> style="display:none;" <?php } ?>>
                        <span class="form-check-label" for="flexCheckDefault">Go-to R/O</a></span>
                    </span>
                    <div class="form-input-frame">
                        <input type="hidden" name="is_create_new_ro" id="is_create_new_ro" value="<?php echo @$wooutstandingoutside->is_create_new_ro; ?>" />
                        <?php echo $this->Form->control('osr_invoice_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'id'=>'osr_invoice_no')); ?>
                    </div>
                </div>
            </div>
           <div class="col-md-6 mol-sm-6 col-xs-12">
                <div class="form-group">
                    <label class="control-label" for="reference"><span class="woosr-po-link">Purchase Order No.</span></label>
                    <div class="form-input-frame">
                        <input type="hidden" name="is_add_to_po" id="is_add_to_po" value="<?php echo @$wooutstandingoutside->is_add_to_po; ?>" />
                        <?php echo $this->Form->control('osr_purchase_order_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'id'=>'osr_purchase_order_no')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                        <label class="control-label" for="reference">Description of Work</label>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('osr_description_of_work', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label" for="reference">Vendor Labor Charge</label>
                <div class="form-input-frame">
                    <?php 
                    $osr_vendor_labor_charges = '$0.00';
                    if(!empty($wooutstandingoutside->osr_vendor_labor_charges)){
                        $osr_vendor_labor_charges = '$'.number_format($wooutstandingoutside->osr_vendor_labor_charges, 2);
                    }
                    
                    echo $this->Form->control('osr_vendor_labor_charges', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'osr_vendor_labor_charges', 'value'=>$osr_vendor_labor_charges)); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label" for="reference">Vendor Parts Charge</label>
                <div class="form-input-frame">
                    <?php 
                    $osr_vendor_part_charges = '$0.00';
                    if(!empty($wooutstandingoutside->osr_vendor_part_charges)){
                        $osr_vendor_part_charges = '$'.number_format($wooutstandingoutside->osr_vendor_part_charges, 2);
                    }
                    echo $this->Form->control('osr_vendor_part_charges', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'osr_vendor_part_charges', 'value'=>$osr_vendor_part_charges)); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label" for="reference">Date Due</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('osr_date_due', array('class' => 'form-control', 'id' => 'outside_date_due', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="control-label" for="reference">Inspector Code</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('osr_inspector_code', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0')); ?>
                </div>
            </div>
        </div>
    </div>
    <?php 
    echo $this->Form->end(); 
    ?>
</div>