<section class="top-form-section">
    <div class="row">
        <?php
        $formid = '';
        if($section == 'cust_otc_aircraft_maintenance'){
            $formid = 'frmAircraftMaintJetEngine';
            $isdisabled = '';
        }else{
            $formid = 'frmAircraftWOLogBookValJetEngine';
            $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '';
        }

        echo $this->Form->create($aircraftmaintengine, array('class' => 'form-horizontal form-label-left', 'id' => $formid, 'autocomplete' => 'off'));
        

        if($section == 'cust_otc_aircraft_maintenance'){
        ?>
            <input type="hidden" name="aircraft_id" value="<?php echo $aircraft_id; ?>" />
            <input type="hidden" name="maintenance_jet_engine_id" id="maintenance_jet_engine_id" value="<?php echo @$aircraftmaintengine->id; ?>" />
        <?php }else{ ?>
            <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
            <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
            <input type="hidden" name="logbook_value_jet_engine_id" id="logbook_value_jet_engine_id" value="<?php echo @$aircraftmaintengine->id; ?>" />
        <?php } ?>

        <div class="col-md-12">
            <b>Engine #1</b>
        </div>
        <div class="col-md-12">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">Model No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine1_model_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_model_no')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">Serial No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine1_serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_serial_no')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TT Vacum</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine1_tt_vacum', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_tt_vacum')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    $engine1_use_enginechk = '';
                    if(!empty($aircraftmaintengine->engine1_use_engine)){
                        $engine1_use_enginechk = 'checked';
                    }
                    ?>
                    <input type="checkbox" name="engine1_use_engine" id="maint_engine1_use_engine" value="1" <?php echo $engine1_use_enginechk; ?> />&nbsp;Use Engine
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TT</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine1_tt', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_tt')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TSO</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine1_tso', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_tso')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TC</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine1_tc', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_tc')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TC 2</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine1_tc2', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_tc2')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TCSO</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine1_tcso', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_tcso')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">HSI/MPI</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine1_hsi_mpi', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine1_hsi_mpi')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <b>Engine #2</b>
        </div>
        <div class="col-md-12">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">Model No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine2_model_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_model_no')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">Serial No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine2_serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_serial_no')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TT Vacum</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine2_tt_vacum', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_tt_vacum')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    $engine2_use_enginechk = '';
                    if(!empty($aircraftmaintengine->engine2_use_engine)){
                        $engine2_use_enginechk = 'checked';
                    }
                    ?>
                    <input type="checkbox" name="engine2_use_engine" id="maint_engine2_use_engine" value="1" <?php echo $engine2_use_enginechk; ?> />&nbsp;Use Engine
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TT</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine2_tt', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_tt')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TSO</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine2_tso', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_tso')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TC</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine2_tc', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_tc')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TC 2</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine2_tc2', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_tc2')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TCSO</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine2_tcso', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_tcso')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">HSI/MPI</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine2_hsi_mpi', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine2_hsi_mpi')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <b>Engine #3</b>
        </div>
        <div class="col-md-12">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">Model No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine3_model_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine3_model_no')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">Serial No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine3_serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine3_serial_no')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TT Vacum</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine3_tt_vacum', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine3_tt_vacum')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <?php
                    $engine3_use_enginechk = '';
                    if(!empty($aircraftmaintengine->engine3_use_engine)){
                        $engine3_use_enginechk = 'checked';
                    }
                    ?>
                    <input type="checkbox" name="engine3_use_engine" id="maint_engine3_use_engine" value="1" <?php echo $engine3_use_enginechk; ?> />&nbsp;Use Engine
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TT</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine3_tt', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine3_tt')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TSO</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine3_tso', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine3_tso')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TC</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine3_tc', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine3_tc')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TC 2</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine3_tc2', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine3_tc2')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">TCSO</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine3_tcso', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine3_tcso')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">HSI/MPI</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('engine3_hsi_mpi', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_engine3_hsi_mpi')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <?php 
            $btnclass = '';
            if($section == 'cust_otc_aircraft_maintenance'){ 
                $btnclass = 'saveAircraftMainJetEngine';
            }else{
                $btnclass = 'saveAircraftWOLogBookValJetEngine';
            }
            ?>
            <button type="button" class="btn btn-primary float-right <?php echo $btnclass; ?>" <?php echo $isdisabled; ?>>Save</button>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
    
</section>