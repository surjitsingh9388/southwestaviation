<section class="top-form-section">
    <?php
    $formid = '';
    if($section == 'cust_otc_aircraft_maintenance'){
        $formid = 'frmAircraftMaintOverview';
        $isdisabled = '';
    }else{
        $formid = 'frmAircraftWOLogBookValOverview';
        $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '';
    }
    echo $this->Form->create($aircraftmaintoverview, array('class' => 'form-horizontal form-label-left', 'id' => $formid, 'autocomplete' => 'off'));
    
    ?>
    <div class="row">
        <?php if($section == 'cust_otc_aircraft_maintenance'){ ?>
            <input type="hidden" name="aircraft_id" value="<?php echo $aircraft_id; ?>" />
            <input type="hidden" name="maintenance_overview_id" id="maintenance_overview_id" value="<?php echo @$aircraftmaintoverview->id; ?>" />
        <?php }else{ ?>
            <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
            <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
            <input type="hidden" name="logbook_value_overview_id" id="logbook_value_overview_id" value="<?php echo @$aircraftmaintoverview->id; ?>" />
        <?php } ?>
        
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">Next Annual</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('next_annual', array('class' => 'form-control', 'id' => 'maint_next_annual', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Next Far 91.413</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('next_far_91_413', array('class' => 'form-control', 'id' => 'maint_next_far_91_413', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Current A/C TT</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('current_ac_tt', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_current_ac_tt')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">50 Hour</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('50_hour', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_50_hour')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">Next ELT Date</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('next_elt_date', array('class' => 'form-control', 'id' => 'maint_next_elt_date', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Warranty Date</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('warranty_date', array('class' => 'form-control', 'id' => 'maint_warranty_date', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
        
            <div class="form-group">
                <?php
                $current_ac_tach_h = 'Current A/C Tach';
                if(!empty($aircraftmaintoverview->tach_is_flight_time)){
                    $current_ac_tach_h = 'A/C FlightTime';
                }
                $current_ac_tach_readonly = '';
                if(!empty($aircraftmaintoverview->use_hobbs)){
                    $current_ac_tach_readonly = 'readonly';
                }
                ?>
                <label class="control-label maint_current_ac_tach" for="reference"><?php echo $current_ac_tach_h; ?></label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('current_ac_tach', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_current_ac_tach', 'readonly'=>$current_ac_tach_readonly)); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">100 Hour</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('100_hour', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_100_hour')); ?>
                </div>
            </div>
        </div>
            
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">Next Corrosion</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('next_corrosion', array('class' => 'form-control', 'id' => 'maint_next_corrosion', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">A/C Battery Date</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('ac_battery_date', array('class' => 'form-control', 'id' => 'maint_ac_battery_date', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Hobbs</label>
                <div class="form-input-frame">
                    <?php 
                    $hobbs_readonly = 'readonly';
                    if(!empty($aircraftmaintoverview->use_hobbs)){
                        $hobbs_readonly = '';
                    }

                    echo $this->Form->control('hobbs', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>$hobbs_readonly, 'id'=>'maint_hobbs')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Heater Hobbs</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('heater_hobbs', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'maint_heater_hobbs')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">Next O/2 Bottle</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('next_o2_bottle', array('class' => 'form-control', 'id' => 'maint_next_o2_bottle', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Last Oil Chg Date</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('last_oil_change_date', array('class' => 'form-control', 'id' => 'maint_last_oil_change_date', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference"></label>
                <?php
                $use_hobbs_chk = '';
                if(!empty($aircraftmaintoverview->use_hobbs)){
                    $use_hobbs_chk = 'checked';
                }
                ?>
                <div class="form-check use-hobbs-chkbox">
                    <input class="form-check-input" type="checkbox" value="1" id="maint_use_hobbs_chkbox" name="use_hobbs" <?php echo $use_hobbs_chk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">Use Hobbs</span>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Airframe Lndgs</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('airframe_lndgs', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_airframe_lndgs')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">Next Far 91.411</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('next_far_91_411', array('class' => 'form-control', 'id' => 'maint_next_far_91_411', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Last Oil Chg Time</label>
                <div class="form-input-frame">
                    <?php 
                    echo $this->Form->control('last_oil_change_time', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_last_oil_change_time')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Airswitch</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('airswitch', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_airswitch')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Gross Weight</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('gross_weight', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_gross_weight')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" for="reference">Reg. Expires</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('reg_expires', array('class' => 'form-control', 'id' => 'maint_reg_expires', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label" for="reference"></label>
                <div class="form-check">
                    <?php
                    $tach_is_flight_timechk = '';
                    if(!empty($aircraftmaintoverview->tach_is_flight_time)){
                        $tach_is_flight_timechk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="maint_tach_is_flight_time" name="tach_is_flight_time" <?php echo $tach_is_flight_timechk; ?>>
                    <span class="form-check-label" for="maint_tach_is_flight_time">
                    Tach is Flight Time
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Tach Correction</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('tach_correction', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_tach_correction')); ?>
                </div>
            </div>

            <div class="form-group">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label" for="reference">General Specs and Info:</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('general_specs_info', array('type'=>'textarea', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_general_specs_info')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            $btnclass = '';
            if($section == 'cust_otc_aircraft_maintenance'){
                $btnclass = 'saveAircraftMaintOverviewBtn';
            }else{
                $btnclass = 'saveAircraftWOLogBookValOverviewBtn';
            }
            ?>
            <button type="button" class="btn btn-primary <?php echo $btnclass; ?> float-right" <?php echo $isdisabled; ?>>Save</button>
        </div>
    </div>
    <?php 
    echo $this->Form->end(); 
    ?>
</section>
<script>
    var saveAircraftMaintOverviewURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftMaintOverview']); ?>";
    var saveAircraftWOLogBookValOverviewURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftWOLogBookValOverview']); ?>";
</script>