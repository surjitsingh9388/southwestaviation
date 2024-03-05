<div id="toolCertifiedHistoryAddPopup" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Add to Certified History</h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create($certificationhistories, array('class' => 'form-horizontal form-label-left', 'id' => 'frmToolCertifiedHistory', 'autocomplete' => 'off'));
                ?>
                <div  class="row">
                    <input type="hidden" name="tool_id" id="certified_hist_tool_id" value="<?php echo $tool_id; ?>" />
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Date Sent Out</label>
                                <div class="form-input-frame">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('date_sent_out', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'date_sent_out', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Date of Calibration</label>
                                <div class="form-input-frame">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('date_of_calibration', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'date_of_calibration', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Date Received Back</label>
                                <div class="form-input-frame">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('date_received_back', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'date_received_back', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label" for="reference">Sent to</label>
                                <div class="form-input-frame">
                                    <?php
                                    echo $this->Form->control('sent_to', array('options' => [], 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'sent_to'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <input type="checkbox" name="was_in_calibration" id="was_in_calibration" value="1" />&nbsp;Was in calibration
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Adjustment Needed</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('adjustment_needed', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5', 'style'=>'height: 78px; width: 566px;')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Notes</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('notes', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5', 'style'=>'height: 78px; width: 566px;')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary saveAddToCertifiedHistory">Add</button>
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
            </div>
        </div>
    </div>
</div>