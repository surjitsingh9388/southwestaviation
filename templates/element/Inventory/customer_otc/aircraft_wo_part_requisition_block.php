<?php
echo $this->Form->create($aircraftwoitemparts, array('action'=>'saveCustomerInfo', 'class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomerInfo', 'autocomplete' => 'off'));
?>
<fieldset class="scheduler-border">
    <legend class="scheduler-border">Individual Information</legend>
    <div>
        <div class="col-md-6 col-sm-6 col-xs-6 p-0">
            <div class="col-md-9 col-xs-9 col-sm-9">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Part Number</label>
                    <div class="form-input-frame">
                        <?php
                        echo $this->Form->control('part_number', array('options' => $invpartnumbers, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_part_number'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xs-3 col-sm-3">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Stock Qty</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('qty_stock', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'vendor_name')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 col-sm-12">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Description</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('part_description', array('type'=>'textarea','class' => 'form-control col-md-10 col-xs-12', 'label'=> false, 'row mb-3s'=>2, 'id'=>'item_notes', 'required'=>'required')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-6 p-0">
            <div class="col-md-12 col-xs-12 col-sm-12 pd0">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label text-left" for="reference">Work Order</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('vendor_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'vendor_name')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label text-left" for="reference">Item Number</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('vendor_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'vendor_name')); ?>
                        </div>
                    </div>
                </div>
          
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label text-left" for="reference">W/O Qty Need</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('vendor_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'vendor_name')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label text-left" for="reference">Qty on P/O for Item</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('vendor_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'vendor_name')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label text-left" for="reference">Qty to Order</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('vendor_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'vendor_name')); ?>
                        </div>
                    </div>
                </div>
           
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label text-left" for="reference">Date Needed</label>
                        <div class="form-input-frame">
                            <div class="input-group date datePicker">
                                <?php echo $this->Form->Text('date_needed', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_item_part_date_needed', 'placeholder' => '', 'label' => false)); ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label text-left" for="reference">General Location</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('general_location', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'vendor_name')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="col-md-6 col-sm-6 col-xs-6">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Add to Purchase Order</legend>
                <div class="col-md-12 col-xs-12 col-sm-12">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label text-left" for="reference">Qty</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('vendor_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'vendor_name')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label text-left" for="reference">Vendor</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('supplier', array('options' => $vendorlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_part_supplier'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label text-left" for="reference">Qty for Stock</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('qty_stock', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'wo_item_part_qty_stock')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                         <label class="control-label text-left">&nbsp;</label>
                        <div class="form-group">
                            <input type="checkbox" name="" value="1" />&nbsp;Add to Purchase Order
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-md-6 col-xs-6 col-sm-6">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Deduct from Stock</legend>
                <div class="col-md-12 col-xs-12 col-sm-12">
                    <div class="col-md-6 col-xs-6 col-sm-6">
                        <div class="form-group">
                            <label class="control-label text-left" for="reference">Qty to Deduct</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('vendor_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'vendor_name')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-6 col-sm-6">
                        <div class="form-group">
                            <label class="control-label text-left" for="reference">Stock Qty</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('vendor_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'vendor_name')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <input type="checkbox" name="is_deduct_from_stock" value="1" />&nbsp;Deduct from Stock
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</fieldset>
<div class="col-md-12 col-xs-12 col-sm-12 p-0" style="display: flex;align-items: center;">
    <div class="col-md-8 col-xs-8 col-sm-8 p-0">
        <div class="form-group">
            <label class="control-label text-left" for="reference">Select a vendor below if you want to use a specific vendor for all parts</label>
            <div class="form-input-frame">
                <?php
                echo $this->Form->control('part_number', array('options' => $vendorlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_part_number'));
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xs-4 col-sm-4 p-0">
        <button type="button" class="btn btn-default float-right" style="margin:0px;">Process Item</button>
    </div>
</div>

<fieldset class="scheduler-border">
    <legend class="scheduler-border">Ordering from Availl</legend>
    <div class="col-sm-5">
        <button type="button" class="btn btn-default">Create P/O for All Selected Parts</button>
    </div>
    <div class="col-sm-7">
        <p>Press this button to bring up a verification screen, showing the items you have selected to order.<br/>
        After verifying this screen, the P/O will be created, and you can elect to electronically send the order.</p>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>