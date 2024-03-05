<section class="top-form-section">
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Equipment Description</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('equipment_description', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5', 'style'=>'height: 113px; width: 442px;')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Manufacturer</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('manufacturer', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'manufacturer')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Model No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('model_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'model_no')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Serial No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'serial_no')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Vendor</label>
                    <div class="form-input-frame">
                        <?php
                        echo $this->Form->control('vendor_id', array('options' => $vendorlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'vendor_id'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Calibration Schedule</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('calibration_schedule', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'calibration_schedule')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Cost</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('cost', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'cost')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <?php
                $tool_status_chk = '';
                /*if(!empty(@$aircraftwoitemparts->tool_status)){
                    $tool_status_chk = 'checked';
                }*/
                ?>
                <input type="checkbox" name="tool_status" value="1" <?php echo $tool_status_chk; ?> />&nbsp;Inactive
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Calibration Date</label>
                    <div class="form-input-frame">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('calibration_date', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'calibration_date', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Due Date</label>
                    <div class="form-input-frame">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('due_date', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'due_date', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Date Labeled</label>
                    <div class="form-input-frame">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('date_labeled', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'date_labeled', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Certification</label>
                    <div class="form-input-frame">
                        <?php
                        $toolCertification = unserialize(TOOLCERTIFICATION);
                        echo $this->Form->control('certification', array('options' => $toolCertification, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'certification'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">General Location</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('general_location', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'general_location')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Tool Location</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tool_location', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'tool_location')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Company Location</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('company_location', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'company_location')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Calibration Status</label>
                    <div class="form-input-frame">
                        <?php
                        $calibrationStatus = unserialize(CALIBRATIONSTATUS);
                        echo $this->Form->control('calibration_status', array('options' => $calibrationStatus, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'calibration_status'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Date Purchased</label>
                    <div class="form-input-frame">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('date_purchased', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'date_purchased', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>