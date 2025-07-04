<?php
echo $this->Form->create($aircraftmaintappliances, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftMaintAppliance', 'autocomplete' => 'off'));

?>
<input type="hidden" name="aircraft_id" value="<?php echo $aircraft_id; ?>" />
<input type="hidden" name="maintenance_appliance_id" id="maintenance_appliance_id" value="<?php echo @$aircraftmaintappliances->id; ?>" />

<fieldset class="scheduler-border hide-block" id="editapplianceinfo">
    <legend class="scheduler-border">Edit Appliance Info</legend>
    
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label" for="reference">Appliance</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('a_appliance', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'a_appliance')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="reference">Serial No.</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('a_serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'appliance_serial_no')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="reference">Due Date</label>
            <div class="form-input-frame">
                <div class="input-group date datePicker">
                    <?php echo $this->Form->Text('a_due_date', array('class' => 'form-control', 'id' => 'appliance_due_date', 'placeholder' => '', 'label' => false)); ?>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="reference">Cycles Due</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('a_cycle_due', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'appliance_cycle_due')); ?>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label" for="reference">Manufacturer</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('a_manufacturer', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'appliance_manufacturer')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="reference">Last Update</label>
            <div class="form-input-frame">
                <div class="input-group date datePicker">
                    <?php echo $this->Form->Text('a_last_update', array('class' => 'form-control', 'id' => 'appliance_last_update', 'placeholder' => '', 'label' => false)); ?>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="reference">Time Due</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('a_time_due', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'appliance_time_due')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="reference">Landings Due</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('a_landings_due', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'appliance_landings_due')); ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="reference">Model No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('a_model_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'appliance_model_no')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="reference">Station/Weight</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('a_station_weight', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'appliance_station_weight')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="reference">Part No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('a_part_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'appliance_part_no')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label" for="plane_id">Notes</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('a_notes', array('type'=>'textarea', 'class' => 'form-control appliance-notes', 'label'=> false, 'id'=>'appliance_notes')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>