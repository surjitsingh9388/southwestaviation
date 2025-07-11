<section class="top-form-section">
    <?php
    $formid = '';
    if($section == 'cust_otc_aircraft_maintenance'){
        $formid = 'frmAircraftMaintHelicopterOverview';
        $isdisabled = '';
    }else{
        $formid = 'frmAircraftWOLogBookValHelicopterOverview';
        $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '';
    }
    echo $this->Form->create($aircraftmaintoverview, array('class' => 'form-horizontal form-label-left', 'id' => $formid, 'autocomplete' => 'off'));

    ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php if($section == 'cust_otc_aircraft_maintenance'){ ?>
            <input type="hidden" name="aircraft_id" value="<?php echo $aircraft_id; ?>" />
            <input type="hidden" name="maintenance_helicopter_overview_id" id="maintenance_helicopter_overview_id" value="<?php echo @$aircraftmaintoverview->id; ?>" />
        <?php }else{ ?>
            <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
            <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
            <input type="hidden" name="logbook_value_helicopter_overview_id" id="logbook_value_helicopter_overview_id" value="<?php echo @$aircraftmaintoverview->id; ?>" />
        <?php } ?>

        <div class="col-md-2 col-sm-2 col-xs-2">
            <div class="col-md-12 col-sm-12 col-xs-12 pd0">
                <b>General</b>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 pd0">
            <div class="form-group">
                <label class="control-label" for="reference">ACTT</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('actt', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_actt')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">A/C TRQ EVNT</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('ac_trq_evnt', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_ac_trq_evnt')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">RINS</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('rins', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_rins')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">HOBBS</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('hobbs', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_hobbs')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">XTUBE LNDGS</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('xtube_lndgs', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_xtube_lndgs')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">REG. EXPIRES</label>
                <div class="form-input-frame">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('reg_expires', array('class' => 'form-control', 'id' => 'maint_reg_expires', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-2 col-xs-2">
            <div class="col-md-12 col-sm-12 col-xs-12 pd0">&nbsp;</div>
            <div class="col-md-12 col-sm-12 col-xs-12 pd0">
            <div class="form-group">
                <label class="control-label" for="reference">ACTC</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('actc', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_actc')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">HOIST TRQ EVNT</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('hoist_trq_evnt', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_hoist_trq_evnt')); ?>
                </div>
            </div>
        
            <div class="form-group">
                <label class="control-label maint_current_ac_tach" for="reference">IIDS</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('iids', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_iids')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">CORRECTION</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('correction', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_correction')); ?>
                </div>
            </div>

            <div class="col-md-12">&nbsp;</div>

            <div class="form-group">
                <label class="control-label" for="reference">GROSS WEIGHT</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('gross_weight', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_gross_weight')); ?>
                </div>
            </div>
            </div>
        </div>
            
        <div class="col-md-2 col-sm-2 col-xs-2">
            <div class="col-md-12 col-sm-12 col-xs-12 pd0">
                <b>Engine #1</b>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 pd0">
            <div class="form-group">
                <label class="control-label" for="reference">TTSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_ttsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_ttsn')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TSO</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_tso', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_tso')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">COMP TTSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_comp_ttsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'maint_engine1_comp_ttsn')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">COMP TTSN O/H</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_comp_ttsn_oh', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'maint_engine1_comp_ttsn_oh')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TURB TTSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_turb_ttsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'maint_engine1_turb_ttsn')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TURB TTSN O/H</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_turb_ttsn_oh', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'maint_engine1_turb_ttsn_oh')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">GEARBOX TTSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_gearbox_ttsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'maint_engine1_gearbox_ttsn')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">GEARBOX TTSN O/H</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_gearbox_ttsn_oh', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'maint_engine1_gearbox_ttsn_oh')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">NG</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_ng', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'maint_engine1_ng')); ?>
                </div>
            </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-2 col-xs-2">
            <div class="col-md-12 col-sm-12 col-xs-12 pd0">&nbsp;</div>
            <div class="col-md-12 col-sm-12 col-xs-12 pd0">
            <div class="form-group">
                <label class="control-label" for="reference">TCSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_tcsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_tcsn')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">CSO</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_cso', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_cso')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">COMP TCSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_comp_tcsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_comp_tcsn')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">CORRECTION</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_correction', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_correction')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TURB TCSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_turb_tcsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_turb_tcsn')); ?>
                </div>
            </div>

            <div class="col-md-12" style="margin-top:51px;">&nbsp;</div>

            <div class="form-group">
                <label class="control-label" for="reference">GEARBOX TCSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_grearbox_tcsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_grearbox_tcsn')); ?>
                </div>
            </div>
            
            <div class="col-md-12" style="margin-top:51px;">&nbsp;</div>

            <div class="form-group">
                <label class="control-label" for="reference">NP</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine1_np', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_np')); ?>
                </div>
            </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-2 col-xs-2">
            <div class="col-md-12 col-sm-12 col-xs-12 pd0">
                <b>Engine #2</b>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 pd0">
            <div class="form-group">
                <label class="control-label" for="reference">TTSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine2_ttsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_ttsn')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TSO</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine2_tso', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_tso')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">COMP TTSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine2_comp_ttsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_comp_ttsn')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">COMP TTSN O/H</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine2_comp_ttsn_oh', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_comp_ttsn_oh')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TURB TTSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine2_turb_ttsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_turb_ttsn')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TURB TTSN O/H</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine2_turb_ttsn_oh', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_turb_ttsn_oh')); ?>
                </div>
            </div>
            </div>
        </div>

        <div class="col-md-2 col-sm-2 col-xs-2">
            <div class="col-md-12 col-sm-12 col-xs-12 pd0">&nbsp;</div>
            <div class="col-md-12 col-sm-12 col-xs-12 pd0">
            <div class="form-group">
                <label class="control-label" for="reference">TCSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine2_tcsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_tcsn')); ?>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label" for="reference">CSO</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine2_cso', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_cso')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">COMP TCSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine2_comp_tcsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_comp_tcsn')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">CORRECTION</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine2_correction', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_correction')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">TURB TCSN</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('engine2_turb_tcsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_turb_tcsn')); ?>
                </div>
            </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <?php
            $btnclass = '';
            if($section == 'cust_otc_aircraft_maintenance'){
                $btnclass = 'saveMaintHelicopterOverviewBtn';
            }else{
                $btnclass = 'saveAircraftWOLogBookValHelicopterOverviewBtn';
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
    var saveAircraftMaintHelicopterOverviewURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftMaintHelicopterOverview']); ?>";
    var saveAircraftWOLogBookValHelicopterOverviewURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftWOLogBookValHelicopterOverview']); ?>";
</script>