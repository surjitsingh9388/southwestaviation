<div id="aircraftMaintenanceUpdtTimeModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Update Helicopter Times (<?php echo $aircraftregdetail->aircraft_registration_number; ?>)</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>Update times on helicopter based off of the current information, or add a specific amount of time.</p>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="reference">Update Type</label>
                            <div class="form-input col-md-9">
                                <?php
                                $updateType = ['1'=>'Update from current time', '2'=>'Update from current hobbs', '3'=>'Add specific amount of time'];
                                echo $this->Form->control('aircraft_update_type', array('options' => $updateType, 'class' => 'form-control selectpicker col-md-9', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'aircraft_update_type'));
                                ?>   
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt10 update_current_hobbs hide-block">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="reference">Current Hobbs</label>
                            <div class="form-input col-md-9">
                                <?php echo $this->Form->control('current_hobbs', array('class' => 'form-control col-md-9', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'update_mint_current_hobbs')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt10 update_specific_amount_time hide-block">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="reference">Add Time</label>
                            <div class="form-input col-md-9">
                                <?php echo $this->Form->control('add_time', array('class' => 'form-control col-md-9', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'update_mint_add_time')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt10 update_specific_amount_time hide-block">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="reference">Add Cycles</label>
                            <div class="form-input col-md-9">
                                <?php echo $this->Form->control('add_cycles', array('class' => 'form-control col-md-9', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'update_mint_add_cycles')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt10 update_current_time">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="reference">Current TT</label>
                            <div class="form-input col-md-9">
                                <?php echo $this->Form->control('current_tt', array('class' => 'form-control col-md-9', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'update_mint_current_tt')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt10 update_current_time">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="reference">Current TC</label>
                            <div class="form-input col-md-9">
                                <?php echo $this->Form->control('current_tc', array('class' => 'form-control col-md-9', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'update_mint_current_tc')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary updatemainthelicoptertimebtn">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>