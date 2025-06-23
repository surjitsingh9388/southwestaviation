<section class="top-form-section">
    <div class="row">
        <?php
        echo $this->Form->create($woitemtools, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWorkOrderItemEditTools'));
        ?> 
        <input type="hidden" name="wo_item_tool_id" id="wo_item_edit_tool_id" value="<?php echo $woitemtools->id; ?>" />
        <input type="hidden" name="wo_item_id" value="<?php echo $woitemtools->wo_item_id; ?>" />
        <div class="col-md-6">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Tool Name</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('wo_item_tool_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Equipment Description</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('equipment_description', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5')); ?>
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
        </div>
        <div class="col-md-6">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Date Added</label>
                    <div class="form-input-frame">
                        <?php 
                        $date_added = date('m/d/Y', strtotime($woitemtools->created_at));
                        echo $this->Form->control('date_added', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>$date_added, 'readonly'=>'readonly')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Calibration Date</label>
                    <div class="form-input-frame">
                        <?php 
                        echo $this->Form->control('calibration_date', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Due Date</label>
                    <div class="form-input-frame">
                        <?php 
                        echo $this->Form->control('due_date', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); 
                        ?>
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
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</section>