<div id="userTimeClockAdjustmentPopup" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Adjust Time Clock</h4>
            </div>
            <div class="modal-body">
                <div  class="row">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label" for="reference">Employee Name</label>
                            <div class="form-input-frame">
                                <?php 
                                echo $this->Form->control('user_id', array('options' => $userlist, 'empty' => 'Select...', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'time_clock_adjustment_user_id')); 
                                ?>
                            </div>
                        </div>
                    </div>
                    <div  class="col-md-12 pd0">
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class="control-label" for="reference">Date</label>
                                <div class="form-input-frame">
                                    <div class="input-group date datePicker">
                                        <?php 
                                        echo $this->Form->Text('time_clock_adjustment_date', array('class' => 'form-control', 'id' => 'po_date', 'placeholder' => '', 'label' => false, 'value'=>date('m/d/Y'), 'id'=>'time_clock_adjustment_date')); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-default load_time_clock_record" style="margin-top: 25px;">Load Info</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-default" style="margin-top: 25px;">Bad Entries</button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <fieldset class="scheduler-border time_clock_adjustment_fieldset">
                            <legend class="scheduler-border time_clock_adjustment_legend">Time Clock Records For Date</legend>
                            <table class="table table-bordered quote-table-height">
                                <thead>
                                    <tr>
                                        <td>Logged In</td>
                                        <td>Logged Out</td>
                                        <td>Job Code</td>
                                    </tr>
                                </thead>
                                <tbody id="time_clock_adjustment_tbody">
                                    
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default float-left fetchUserTimeClockPopup" data-val='add_new_time_clock_record'>Create New Adjustment</button>
                <button type="button" class="btn btn-default">Batch Adjustment Job Codes</button>
            </div>
        </div>
    </div>
</div>