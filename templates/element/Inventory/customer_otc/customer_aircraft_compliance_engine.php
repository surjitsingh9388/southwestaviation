<section class="top-form-section aricraftcomplengblock">
    <?php
    echo $this->Form->create($aircraftcomplengines, array('action'=>'saveAircraftComplEngines', 'class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftComplEngines', 'autocomplete' => 'off'));
    ?>

    <div class="row">
        <input type="hidden" name="aircraft_id" value="<?php echo $aircraft_id; ?>" />
        <input type="hidden" name="compliance_engine_id" id="compliance_engine_id" value="<?php echo @$aircraftcomplengines->id; ?>" />

       <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Name</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('name', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'engine_name')); ?>
                    </div>
                </div>
    
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Part Number</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('part_number', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'engine_part_number')); ?>
                    </div>
                </div>
            </div>

           <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Time Limit of Part</label>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <?php echo $this->Form->control('time_limit_of_part', array('type'=>'number', 'class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'engine_time_limit_of_part')); ?>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <?php
                        $engdiabletimelimitchk = '';
                        if(!empty($aircraftcomplengines->disable_time_limit)){
                            $engdiabletimelimitchk = 'checked';
                        }
                        ?>
                        <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="disable_time_limit" <?php echo $engdiabletimelimitchk; ?>>
                        <span class="form-check-label" for="flexCheckDefault">Disable Time Limit</span>
                    </div>
                </div>
            </div>

          <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Cycle Limit of Part</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('cycles_limit_of_part', array('type'=>'number', 'class'=>'form-control col-md-8 col-sm-9 col-xs-12', 'placeholder' => '', 'label' => false, 'id'=>'engine_cycles_limit_of_part')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Time at Install</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('time_at_install', array('class'=>'form-control col-md-8 col-sm-9 col-xs-12', 'placeholder' => '', 'label' => false, 'id'=>'engine_time_at_install')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Cycle at Install</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('interval_cycles', array('type'=>'number', 'class'=>'form-control col-md-8 col-sm-9 col-xs-12', 'placeholder' => '', 'label' => false, 'id'=>'engine_cycles_at_install')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
          
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Eng TT at Install</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('current_ac_tach', array('type'=>'number', 'class'=>'form-control col-md-8 col-sm-9 col-xs-12', 'placeholder' => '', 'label' => false, 'id'=>'engine_eng_tt_at_install')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
          
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Eng Cycle at Inst.</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('current_ac_tach', array('type'=>'number', 'class'=>'form-control col-md-8 col-sm-9 col-xs-12', 'placeholder' => '', 'label' => false, 'id'=>'engine_eng_cycle_at_install')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Due (Date)</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('due_date', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'compliance_eng_due_date', 'placeholder' => '', 'label' => false, 'id'=>'engine_due_date')); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id"></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <?php
                        $enguse_due_datechk = '';
                        if(!empty($aircraftcomplengines->use_due_date)){
                            $enguse_due_datechk = 'checked';
                        }
                        ?>
                        <input class="form-check-input" type="checkbox" value="1" id="engine_use_due_date" name="use_due_date" <?php echo $enguse_due_datechk; ?>>
                        <span class="form-check-label" for="engine_use_due_date">Use Due Date</span>
                    </div>
                </div>
            </div>

             <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Due (Hours)</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('due_hours', array('type'=>'number', 'class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'engine_due_hours', 'readonly'=>'readonly')); ?>
                    </div>
                </div>
       
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Due (Cycle)</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('due_cycles', array('type'=>'number', 'class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'engine_due_cycles', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Due Next - Eng TT</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('due_next_eng_tt', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'engine_due_next_eng_tt', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
          
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Due Next - Cycles</label>
                       <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('due_next_cycles', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'engine_due_next_cycles', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>

           <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Time on Part</label>
                    <div class="col-md-5 col-sm-6 col-xs-12">
                        <?php echo $this->Form->control('time_on_part', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'engine_time_on_part', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3">
                        <?php
                        $engstatuschk = '';
                        if(!empty($aircraftcomplengines->status)){
                            $engstatuschk = 'checked';
                        }
                        ?>
                        <input class="form-check-input" type="checkbox" value="1" id="engine_status" name="status" <?php echo $engstatuschk; ?>>
                        <span class="form-check-label" for="flexCheckDefault">Inactive</span>
                    </div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Cycle on Part</label>
                   <div class="col-md-5 col-sm-6 col-xs-12">
                        <?php echo $this->Form->control('cycles_on_part', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'engine_cycles_on_part', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-default">Refresh</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-6 col-sm-12 col-xs-12">
               <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">ATA Code</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('ata_code', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'engine_ata_code')); ?>
                    </div>
                </div>
          
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Engine</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php
                        $engineArr = ['1'=>'Left'];
                        echo $this->Form->control('engine', array('options' => $engineArr, 'empty' => '', 'class' => 'form-control col-md-8 col-sm-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'engine_eng', 'disabled'=>'disabled'));
                        ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Type</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php
                        $aircraftComplianceType = unserialize(AIRCRAFT_COMPLIANCE_TYPE);
                        echo $this->Form->control('type', array('options' => $aircraftComplianceType, 'empty' => '', 'class' => 'form-control col-md-5 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'engine_type'));
                        ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
          
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Serial Number</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('serial_number', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'engine_serial_number')); ?>
                    </div>
                </div>
          
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Date Installed</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('date_installed', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'engine_date_installed', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
          
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">AC TT at Installed</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('ac_tt_at_install', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'engine_ac_tt_at_install')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
         
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Tach Correction</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('tach_correction', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'engine_tach_correction', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
          
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">AC Tach at Installed</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('ac_tach_at_install', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'engine_ac_tach_at_install', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
          
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Current Eng TT</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('date_override', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
         
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Current Eng Cycles</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('current_eng_tt', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'engine_current_eng_tt', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
         
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Notes</label>
                    <div class="form-input-frame col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('notes', array('type'=>'textarea', 'class' => 'form-control aircraft-notes', 'label'=> false, 'style'=>'width: 100%; height: 220px;', 'id'=>'engine_notes')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-primary saveCompEngineBtn float-right">Save</button>
        </div>
    </div>
    <?php 
    echo $this->Form->end(); 
    ?>
</section>
<script>
    var saveAircraftComplEnginesURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftComplEngines']); ?>";
</script>