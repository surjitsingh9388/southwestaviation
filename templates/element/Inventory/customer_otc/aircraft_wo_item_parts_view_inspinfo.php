<section class="top-form-section">
    <div class="row">
        <div class="col-md-12 col-xs-12 col-sm-12 p-0">

            <div class="col-md-3 col-xs-3 col-sm-3">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Date Received</label>
                    <div class="form-input-frame">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('date_received', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_item_part_date_received', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xs-3 col-sm-3">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Vendor</label>
                    <div class="form-input-frame">
                        <?php
                        echo $this->Form->control('supplier', array('options' => $vendorlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_part_vendor'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xs-3 col-sm-3">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Warranty Expires</label>
                    <div class="form-input-frame">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('warranty_expires', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_item_part_warranty_expires', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xs-3 col-sm-3">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Invoice #</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('invoice', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_invoice')); ?>
                    </div>
                </div>
            </div>
    
            <div class="col-md-3 col-xs-3 col-sm-3">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Purchase Order</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('purchase_order', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_purchase_order')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xs-3 col-sm-3">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Lot #</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('lot', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_lot')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xs-3 col-sm-3">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Shelf Life</label>
                    <div class="form-input-frame">
                        <?php
                        echo $this->Form->control('shelf_life', array('options' => [], 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_part_shelf_life'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xs-3 col-sm-3">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Approved</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('approved', array('options' => [], 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_part_approved')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-xs-3 col-sm-3">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Initials</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('initials', array('options' => [], 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_part_initials')); ?>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</section>