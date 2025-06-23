<section class="top-form-section">
<?php
    $formid = '';
    if($section == 'cust_otc_aircraft_maintenance'){
        $formid = 'frmAircraftMaintProp';
        $isdisabled = '';
    }else{
        $formid = 'frmAircraftWOLogBookValProp';
        $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '';
    }
    echo $this->Form->create($aircraftmaintprops, array('class' => 'form-horizontal form-label-left', 'id' => $formid, 'autocomplete' => 'off'));
    
    ?>
    <div class="row">
        <?php if($section == 'cust_otc_aircraft_maintenance'){ ?>
            <input type="hidden" name="aircraft_id" value="<?php echo $aircraft_id; ?>" />
            <input type="hidden" name="maintenance_prop_id" id="maintenance_prop_id" value="<?php echo @$aircraftmaintprops->id; ?>" />
        <?php }else{ ?>
            <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
            <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
            <input type="hidden" name="logbook_value_prop_id" id="logbook_value_prop_id" value="<?php echo @$aircraftmaintprops->id; ?>" />
        <?php } ?>
        
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">TSPOH-L</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tspoh_l', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'p_tspoh_l')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TT-L</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tt_l', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'p_tt_l')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Last Prop Bal-L</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_last_prop_balance_l', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'p_last_prop_balance_l')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">TSPOH-R</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tspoh_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'p_tspoh_r')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TT-R</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tt_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'p_tt_r')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Last Prop Bal-R</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_last_prop_bal_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'p_last_prop_bal_r')); ?>
                </div>
            </div>
        </div>
            
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">O/H Date-L</label>
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
                <label class="control-label" for="reference">Model No.-L</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_model_no_l', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TSN-L</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tsn_l', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'p_tsn_l')); ?>
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
                    <?php echo $this->Form->control('p_tsn_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'p_tsn_r')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">Serial No.-L</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_serial_no_l', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TBO</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('p_tbo', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'p_tbo')); ?>
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
                    <?php echo $this->Form->control('p_serial_no_r', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>

            <div class="form-group"></div>
            <div class="form-group"></div>
        </div>

        <div class="col-md-12">
            <?php
            $btnclass = '';
            if($section == 'cust_otc_aircraft_maintenance'){
                $btnclass = 'saveAircraftMaintProp';
            }else{
                $btnclass = 'saveAircraftWOLogBookValProp';
            }
            ?>
            <button type="button" class="btn btn-primary <?php echo $btnclass; ?> float-right" <?php echo $isdisabled; ?>>Save</button>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</section>
<script>
    var saveAircraftMaintPropURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftMaintProp']); ?>";
    var saveAircraftWOLogBookValPropURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftWOLogBookValProp']); ?>";
</script>