<div id="updateTimeClockRecordPopup" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Adjust Time Clock Time</h4>
            </div>
            <div class="modal-body">
                <?php echo $this->Form->create($timeclocks, array('class' => 'form-horizontal form-label-left', 'id' => 'frmUpdateTimeClockRecord', 'autocomplete'=>'off')); ?>
                <input type="hidden" name="time_clock_id" value="<?php echo $timeclocks->id; ?>" />
                <div  class="row">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label" for="reference">Employee Name</label>
                            <div class="form-input-frame">
                                <?php 
                                echo $this->Form->control('user_id', array('options' => $userlist, 'empty' => 'Select...', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'adjustment_user_id', 'disabled'=>'disabled')); 
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 pd0">
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class="control-label" for="reference">Original Log In</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $original_in_time = !empty($timeclocks->in_time) ? date('m/d/Y h:i:s A', strtotime($timeclocks->in_time)) : '';
                                    echo $this->Form->Text('original_in_time', array('class' => 'form-control', 'id' => 'po_date', 'placeholder' => '', 'label' => false, 'id'=>'original_in_time', 'value'=>$original_in_time, 'readonly'=>'readonly')); 
                                    ?>
                                </div>
                            </div>

                            <div class="form-group"> 
                                <label class="control-label" for="reference">Original Log Out</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $original_out_time = !empty($timeclocks->out_time) ? date('m/d/Y h:i:s A', strtotime($timeclocks->out_time)) : '';

                                    echo $this->Form->Text('original_out_time', array('class' => 'form-control', 'id' => 'po_date', 'placeholder' => '', 'label' => false, 'id'=>'original_out_time', 'value'=>$original_out_time, 'readonly'=>'readonly')); 
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class="control-label" for="reference">New Log In</label>
                                <div class="form-input-frame">
                                    <div class="input-group date datePicker">
                                        <?php 
                                        echo $this->Form->Text('in_time', array('class' => 'form-control', 'id' => 'po_date', 'placeholder' => '', 'label' => false, 'id'=>'login_time_clock_date')); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group"> 
                                <label class="control-label label-heading-left" for="reference">New Log Out</label>
                                <span class="label-heading-right">
                                    <a href="javascript:void(0);" class="fetchUserTimeClockPopup" data-val='add_hours_to_start_time' style="color:blue;">Calculate</a>
                                </span>
                                <div class="form-input-frame">
                                    <div class="input-group date datePicker">
                                        <?php 
                                        echo $this->Form->Text('out_time', array('class' => 'form-control', 'id' => 'po_date', 'placeholder' => '', 'label' => false, 'id'=>'logout_time_clock_date')); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="reference">New Calculated Hours</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('user_time_clock_cal_hour', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.0', 'required'=>'required', 'id'=>'user_time_clock_cal_hour', 'value'=>'', 'readonly'=>'readonly')); ?>
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default saveUpdateTimeClock">Update & New Adjust</button>
                <button type="button" class="btn btn-default saveUpdateTimeClock">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
    <style>
        .bootstrap-datetimepicker-widget .list-unstyled li:nth-child(2){
    display: none !important;
}
    </style>
</div>