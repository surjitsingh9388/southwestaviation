<section class="top-form-section aricraftcomplairframeblock">
    <?php
    echo $this->Form->create($aircraftcomplairframes, array('action'=>'saveAircraftComplAirframe', 'class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftComplAirframe', 'autocomplete' => 'off'));
    ?>
    <div class="row">
        <input type="hidden" name="aircraft_id" value="<?php echo $aircraft_id; ?>" />
        <input type="hidden" name="compliance_airframe_id" id="compliance_airframe_id" value="<?php echo @$aircraftcomplairframes->id; ?>" />

        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                     <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Name</label>
                   <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('name', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'airframe_name')); ?>
                    </div>
                </div>
           
                <div class="form-group">  
                     <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Part Number</label>
                   <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('part_number', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'airframe_part_number')); ?>
                    </div>
                </div>
           
                <div class="form-group">  
                     <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Serial Number</label>
                   <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('serial_number', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'airframe_serial_number')); ?>
                    </div>
                </div>
            </div>

             <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                     <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Time Limit of Part</label>
                     <div class="col-md-5 col-sm-5 col-xs-12">
                        <?php 
                        $aircraftimelimitpartreadonly = '';
                        if(!empty($aircraftcomplairframes->use_cycles)){
                            $aircraftimelimitpartreadonly = 'readonly';
                        }
                        echo $this->Form->control('time_limit_of_part', array('type'=>'number', 'class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'airframe_time_limit_of_part', 'readonly'=>$aircraftimelimitpartreadonly)); ?>
                    </div>
                     <div class="col-md-3 col-sm-3 col-xs-12">
                        <?php
                        $aircraftdisabletimelimitchk = '';
                        if(!empty($aircraftcomplairframes->disable_time_limit)){
                            $aircraftdisabletimelimitchk = 'checked';
                        }
                        ?>
                        <input class="form-check-input" type="checkbox" value="1" id="airframe_disable_time_limit" name="disable_time_limit" <?php echo $aircraftdisabletimelimitchk; ?>>
                        <span class="form-check-label" for="airframe_disable_time_limit">Disable Time Limit</span>
                    </div>
                </div>
            </div>

           <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12 " for="plane_id">Time at Install</label>
               <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php 
                        $aircraftimeatinstallreadonly = '';
                        if(!empty($aircraftcomplairframes->use_cycles)){
                            $aircraftimeatinstallreadonly = 'readonly';
                        }
                        
                        echo $this->Form->control('time_at_install', array('class'=>'form-control', 'placeholder' => '', 'label' => false, 'id'=>'airframe_time_at_install', 'readonly'=>$aircraftimeatinstallreadonly)); ?>
                    </div>
                    <!-- <div class="col-md-3"></div> -->
                </div>
            
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12 " for="plane_id">AC TT at Install</label>
                  <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php 
                        $aircrafacttatinstallreadonly = '';
                        if(!empty($aircraftcomplairframes->use_cycles)){
                            $aircrafacttatinstallreadonly = 'readonly';
                        }
                        echo $this->Form->control('ac_tt_at_install', array('type'=>'number', 'class'=>'form-control ', 'placeholder' => '', 'label' => false, 'id'=>'airframe_ac_tt_at_install', 'readonly'=>$aircrafacttatinstallreadonly)); ?>
                    </div>
                    <!-- <div class="col-md-3"></div> -->
                </div>
            
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12 " for="plane_id">Due (Date)</label>
                   <div class="col-md-8 col-sm-9 col-xs-12">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('due_date', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'airframe_due_date', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
        
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12 " for="plane_id"></label>
                    <div class="col-md-5">
                        <?php
                        $aircraftuseduedatechk = '';
                        if(!empty($aircraftcomplairframes->use_due_date)){
                            $aircraftuseduedatechk = 'checked';
                        }
                        ?>
                        <input class="form-check-input" type="checkbox" value="1" id="airframe_use_due_date" name="use_due_date" <?php echo $aircraftuseduedatechk; ?>>
                        <span class="form-check-label" for="airframe_use_due_date">Use Due Date</span>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>

             <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Tach Correction</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('tach_correction', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'airframe_tach_correction', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
         
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">AC Tach at Install</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('ac_tach_at_install', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'airframe_ac_tach_at_install', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Due (Hours)</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('due_hours', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'airframe_due_hours', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
         
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Current AC TT</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('current_ac_tt', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'airframe_current_ac_tt', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Due Next - AC TT</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('due_next_ac_tt', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'airframe_due_next_ac_tt', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>

           <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Time on Part</label>
                  <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('time_on_part', array('type'=>'number', 'class'=>'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id'=>'airframe_time_on_part', 'readonly'=>'readonly')); ?>
                    </div>
                    <!-- <div class="col-md-3"></div> -->
                </div>
           
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id"></label>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <button type="button" class="btn btn-default">Refresh</button>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <?php
                        $aircraftstatuschk = '';
                        if(!empty($aircraftcomplairframes->status)){
                            $aircraftstatuschk = 'checked';
                        }
                        ?>
                        <input class="form-check-input" type="checkbox" value="1" id="airframe_status" name="status" <?php echo $aircraftstatuschk; ?>>
                        <span class="form-check-label" for="airframe_status">Inactive</span>
                    </div>
                </div>
            </div>
        </div>
       <div class="col-md-6 col-sm-12 col-xs-12">
          <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">ATA Code</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('ata_code', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'airframe_ata_code')); ?>
                    </div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Type</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php
                        $aircraftComplianceType = unserialize(AIRCRAFT_COMPLIANCE_TYPE);
                        echo $this->Form->control('type', array('options' => $aircraftComplianceType, 'empty' => '', 'class' => 'form-control col-md-5 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'airframe_type'));
                        ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Date Installed</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('date_installed', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'airframe_date_installed', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Cycle Limit of Part</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php 
                        $aircrafcyclelmtprtreadonly = 'readonly';
                        if(!empty($aircraftcomplairframes->use_cycles)){
                            $aircrafcyclelmtprtreadonly = '';
                        }
                        echo $this->Form->control('cycles_limit_of_part', array('type'=>'number', 'class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'airframe_cycles_limit_of_part', 'readonly'=>$aircrafcyclelmtprtreadonly)); ?>
                    </div>



                      <label class="control-label  col-md-4 col-sm-3 col-xs-12 " for="plane_id"></label>
                    <div class="col-md-5">
                 
                        <?php
                        $aircraftusecyclechk = '';
                        if(!empty($aircraftcomplairframes->use_cycles)){
                            $aircraftusecyclechk = 'checked';
                        }
                        ?>
                        <input class="form-check-input" type="checkbox" value="1" id="airframe_use_cycles" name="use_cycles" <?php echo $aircraftusecyclechk; ?>>
                        <span class="form-check-label" for="airframe_use_cycles">Use Cycles</span>
                    </div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Cycle at Installed</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php 
                        $aircrafcycleatinstlreadonly = 'readonly';
                        if(!empty($aircraftcomplairframes->use_cycles)){
                            $aircrafcycleatinstlreadonly = '';
                        }
                        echo $this->Form->control('cycles_at_install', array('type'=>'number', 'class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'airframe_cycles_at_install', 'readonly'=>$aircrafcycleatinstlreadonly)); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Lndgs at Installed</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php 
                        $aircraflndatinstlreadonly = 'readonly';
                        if(!empty($aircraftcomplairframes->use_cycles)){
                            $aircraflndatinstlreadonly = '';
                        }
                        echo $this->Form->control('landing_at_install', array('type'=>'number', 'class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'airframe_landing_at_install', 'readonly'=>$aircraflndatinstlreadonly)); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
          
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Current AC Lndgs</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('current_ac_landing', array('type'=>'number', 'class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'airframe_current_ac_landing', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
           
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Due (Cycles)</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('due_cycles', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'airframe_due_cycles', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
          
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Due Next - Lndgs</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('due_next_landings', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'airframe_due_next_landings', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
         
                <div class="form-group">  
                    <label class="control-label  col-md-4 col-sm-3 col-xs-12" for="plane_id">Cycle on Part</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('cycles_on_part', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'airframe_cycles_on_part', 'readonly'=>'readonly')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
         
                <div class="form-group">  
                    <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Notes</label>
                    <div class="form-input-frame col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('notes', array('type'=>'textarea', 'class' => 'form-control aircraft-notes',  'style'=>'width: 320px; height: 220px;','label'=> false, 'id'=>'airframe_notes')); ?>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <button type="button" class="btn btn-primary saveCompAirframeBtn float-right">Save</button>
        </div>
    </div>
    <?php 
    echo $this->Form->end(); 
    ?>
</section>
<script>
    var saveAircraftComplAirframeURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftComplAirframe']); ?>";
</script>