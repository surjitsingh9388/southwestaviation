<section class="top-form-section">
    <div class="row">
        <?php
        echo $this->Form->create($aircraftwologbookvalengine, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOLogBookValEngine', 'autocomplete' => 'off'));
        ?>
        <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
        <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
        <input type="hidden" name="logbook_value_engine_id" id="logbook_value_engine_id" value="<?php echo @$aircraftwologbookvalengine->id; ?>" />

        <div class="col-md-2">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TSMOH</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tsmoh', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_tsmoh')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TTL</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('ttl', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_ttl')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TT Vac. Pump</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tt_vac_pump', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_tt_vac_pump')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TSMOH-R</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tsmoh_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'maint_eng_tsmoh_r')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TT-R</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tt_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'maint_eng_tt_r')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TT Vac. Pump-R</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tt_vac_pump_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'maint_eng_tt_vac_pump_r')); ?>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="col-md-2">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">O/H Date</label>
                    <div class="form-input-frame">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('oh_date', array('class' => 'form-control', 'id' => 'maint_eng_oh_date', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Model No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('model_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_model_no')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TSN</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_tsn')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">O/H Date-R</label>
                    <div class="form-input-frame">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('oh_date_r', array('class' => 'form-control', 'id' => 'maint_eng_oh_date_r', 'placeholder' => '', 'label' => false, 'readonly'=>'readonly')); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Model No.-R</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('model_no_r', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'maint_eng_model_no_r')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TSN-R</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tsn_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'maint_eng_tsn_r')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Serial No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_serial_no')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TBO</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tbo', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_tbo')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Manufacturer</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('manufacturer', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_manufacturer')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Serial No.-R</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('serial_no_r', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'maint_eng_serial_no_r')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12"></div>

            <div class="col-md-12"></div>
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-primary float-right saveAircraftWOLogBookValEngine">Save</button>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
    
</section>
<script>
    var saveAircraftWOLogBookValEngineURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftWOLogBookValEngine']); ?>";
</script>