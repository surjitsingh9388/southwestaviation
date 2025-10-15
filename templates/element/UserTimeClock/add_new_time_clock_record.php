<div id="addNewTimeClockRecordPopup" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Add New Time Clock Record</h4>
            </div>
            <div class="modal-body">
                <?php echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAddNewTimeClockRecord', 'autocomplete'=>'off')); ?>
                <div  class="row">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3" for="reference">Employee Name</label>
                            <div class="col-md-9 form-label-input-wrapper">
                                <?php 
                                echo $this->Form->control('user_id', array('options' => $userlist, 'empty' => 'Select...', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'adjustment_user_id')); 
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt10"> 
                        <div class="form-group"> 
                            <label class="control-label col-md-3" for="reference">Log In</label> 
                            <div class="col-md-7 form-label-input-wrapper"> 
                                <div class="input-group date datePicker"> 
                                    <?php echo $this->Form->Text('in_time', array('class' => 'form-control', 'placeholder' => '', 'label' => false, 'id'=>'login_time_clock_date')); ?> 
                                    <span class="input-group-addon"> 
                                        <span class="glyphicon glyphicon-calendar"></span> 
                                    </span> 
                                </div> 
                            </div> 
                            <div class="col-md-2">&nbsp;</div> 
                        </div> 
                    </div> 
                    <div class="col-md-12 mt5"> 
                        <div class="form-group"> 
                            <label class="control-label col-md-3" for="reference">Log Out</label> 
                            <div class="col-md-7 form-label-input-wrapper"> 
                                <div class="input-group date datePicker"> 
                                    <?php echo $this->Form->Text('out_time', array('class' => 'form-control', 'placeholder' => '', 'label' => false, 'id'=>'logout_time_clock_date')); ?> 
                                    <span class="input-group-addon"> 
                                        <span class="glyphicon glyphicon-calendar"></span> 
                                    </span> 
                                </div> 
                            </div> 
                            <div class="col-md-2"> 
                                <a href="javascript:void(0);" class="fetchUserTimeClockPopup" data-val='add_hours_to_start_time' style="color:blue;">Calculate</a> 
                            </div> 
                        </div> 
                    </div> 
                    <div class="col-md-12 mt5"> 
                        <div class="form-group"> 
                            <label class="control-label col-md-3" for="reference">Hour</label> 
                            <div class="col-md-7 form-label-input-wrapper"> 
                                <?php echo $this->Form->control('user_time_clock_cal_hour', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.0', 'required'=>'required', 'id'=>'user_time_clock_cal_hour', 'value'=>'', 'readonly'=>'readonly')); ?> 
                            </div> 
                            <div class="col-md-2">&nbsp;</div> 
                        </div> 
                    </div> 
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default saveAddNewTimeClockRecord">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

