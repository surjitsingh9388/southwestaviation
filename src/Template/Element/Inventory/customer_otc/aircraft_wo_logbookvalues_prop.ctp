<section class="top-form-section">
    <?php
    echo $this->Form->create($aircraftwologbookvalprops, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOLogBookValProp', 'autocomplete' => 'off'));
    ?>
    <div class="row">
        <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
        <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
        <input type="hidden" name="logbook_value_prop_id" id="logbook_value_prop_id" value="<?php echo @$aircraftwologbookvalprops->id; ?>" />
        
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">TSPOH</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tspoh_l', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TT</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tt_l', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Last Prop Bal</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_last_prop_balance_l', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">TSPOH-R</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tspoh_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TT-R</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tt_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Last Prop Bal-R</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_last_prop_bal_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); ?>
                </div>
            </div>
        </div>
            
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">O/H Date</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('p_oh_date_l', array('class' => 'form-control', 'id' => 'p_oh_date_l', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Model No.</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_model_no_l', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tsn_l', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">O/H Date-R</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('p_oh_date_r', array('class' => 'form-control', 'id' => 'p_oh_date_r', 'placeholder' => '', 'label' => false, 'disabled'=>'disabled')); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Model No.-R</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_model_no_r', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TSN-R</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tsn_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">Serial No.</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_serial_no_l', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TBO</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tbo', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Manufacturer</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_manufacturer', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">Serial No.-R</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_serial_no_r', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); ?>
                </div>
            </div>

            <div class="form-group"></div>
            <div class="form-group"></div>
        </div>

        <div class="col-md-12">
            <button type="button" class="btn btn-primary float-right saveAircraftWOLogBookValProp">Save</button>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</section>
<script>
    var saveAircraftWOLogBookValPropURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftWOLogBookValProp']); ?>";
</script>