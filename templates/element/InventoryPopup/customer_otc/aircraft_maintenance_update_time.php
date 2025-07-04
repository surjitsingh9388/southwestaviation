<div id="aircraftMaintenanceUpdtTimeModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span> <?php echo $aircraftregdetail->aircraft_registration_number; ?>: Update Aircraft Times</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                    $optiondisable = '';
                    if($aircraftregdetail->aircraft_engine_type == '1' || $aircraftregdetail->aircraft_engine_type == '2'){
                        $optiondisable = 'disabled';
                    }
                    ?>
                    <div class="col-md-12">
                        <fieldset class="scheduler-border mt10">
                            <legend class="scheduler-border wosignoffleg">Make a Selection</legend>
                            <div class="col-md-12">
                                <div class="form-group"> 
                                    <input class="form-check-input update_maint_overview_time" type="radio" value="1" id="update_aircraft_maint_overview_time1" name="update_aircraft_maint_overview_time" checked>
                                    <span class="form-check-label" for="update_aircraft_maint_overview_time1">Calculate from current tach on aircraft</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group"> 
                                    <input class="form-check-input update_maint_overview_time" type="radio" value="2" id="update_aircraft_maint_overview_time2" name="update_aircraft_maint_overview_time">
                                    <span class="form-check-label" for="update_aircraft_maint_overview_time2">Calculate from adding a specified amount of time</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group"> 
                                    <input class="form-check-input update_maint_overview_time" type="radio" value="3" id="update_aircraft_maint_overview_time3" name="update_aircraft_maint_overview_time" <?php echo $optiondisable; ?>>
                                    <span class="form-check-label" for="update_aircraft_maint_overview_time3">Add a specified amount of cycles</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group"> 
                                    <input class="form-check-input update_maint_overview_time" type="radio" value="4" id="update_aircraft_maint_overview_time4" name="update_aircraft_maint_overview_time" <?php echo $optiondisable; ?>>
                                    <span class="form-check-label" for="update_aircraft_maint_overview_time4">Add a specified amount of cycle & landings</span>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-12" id="maint_update_time_heading">Enter the current tach time on the aircraft</div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('aircraft_maint_overview_techtime', array('class' => 'form-control', 'label'=> false, 'id'=>'aircraft_maint_overview_techtime')); ?>
                            </div>
                        </div>
                        <div class="form-group"> 
                            <label class="control-label" for="plane_id">Latest A/C TT: </label><?php echo @$aircraftmaintoverview->current_ac_tt; ?>
                        </div>
                        <?php 
                        $hobbs = '';
                        if(!empty(@$aircraftmaintoverview->use_hobbs)){
                            $hobbs = @$aircraftmaintoverview->hobbs;
                        }
                        if(!empty($hobbs)){
                        ?>
                        <div class="form-group"> 
                            <label class="control-label" for="plane_id">Latest Hobbs:</label> <?php echo $hobbs; ?>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group hide-block" id="is_tc2_block"> 
                            <input type="checkbox" name="is_tc2" value="1" id="is_tc2" />&nbsp;is TC2
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary updatemaintoverviewtimebtn">Update</button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>