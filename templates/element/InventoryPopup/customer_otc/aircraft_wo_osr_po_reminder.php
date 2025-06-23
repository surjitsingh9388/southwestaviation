<div id="aircraftWOOSRPONotifyModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Schedule Reminder Notification for P/O</h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOSRPOReminder'));
                ?> 
                <input type="hidden" name="wo_osr_po_id" id="wo_osr_po_id" value="<?php echo $wo_osr_po_id; ?>" />
                <div class="row">
                    <div class="col-md-12">
                        Create reminders for the P/O, which will be sent through the EBIs Message System.
                        <hr/>
                    </div>
                    <div class="col-md-4"><b>Reminder</b></div>
                    <div class="col-md-4"><b>Send Reminder To</b></div>
                    <div class="col-md-4"><b>Notify Around Date/Time</b></div>    
                </div>
                <?php
                for($i=0; $i<5; $i++){
                ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="reference"></label>
                            <div class="form-input-frame">
                                <?php
                                $reminder_id = isset($osrPOReminderlist[$i]['id']) ? $osrPOReminderlist[$i]['id'] : '';
                                $reminder = isset($osrPOReminderlist[$i]['reminder']) ? $osrPOReminderlist[$i]['reminder'] : '';

                                echo $this->Form->control('reminder[]', array('options' => [], 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                ?>
                                <input type="hidden" name="reminder_id[]" id="reminder_id<?php echo $i; ?>" value="<?php echo $reminder_id; ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="reference"></label>
                            <div class="form-input-frame">
                                <?php
                                $send_reminder_to = isset($osrPOReminderlist[$i]['send_reminder_to']) ? $osrPOReminderlist[$i]['send_reminder_to'] : '';
                                echo $this->Form->control('send_reminder_to[]', array('options' => $userlist, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'value'=>$send_reminder_to));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="reference"></label>
                            <div class="input-group date datePicker">
                                <?php 
                                $notify_date_time = isset($osrPOReminderlist[$i]['notify_date_time']) ? $osrPOReminderlist[$i]['notify_date_time'] : '';
                                echo $this->Form->Text('notify_date_time[]', array('class' => 'form-control osr_po_notify_datetime', 'placeholder' => '', 'label' => false, 'value'=>$notify_date_time)); ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>    
                </div>
                <?php 
                }
                echo $this->Form->end(); 
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary saveWOOSRPOReminder">Save</button>
            </div>
        </div>
    </div>
</div>