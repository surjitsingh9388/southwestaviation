<div id="woPrintPreviewMaintModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <?php
        echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOPrintPreview'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Please enter a date</h4>
                <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
                <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
                <input type="hidden" name="wo_report_type" value="<?php echo $wo_report_type; ?>" />
            </div>
            <div class="modal-body">
                <div class="row">
                    <p style="border-bottom: 1px solid darkgray; margin: 10px;">Enter Date to Show</p>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Date</label>
                            <div class="form-input-frame">
                                <div class="input-group date datePicker">
                                    <?php echo $this->Form->Text('maint_date_to_show', array('class' => 'form-control', 'id' => 'print_prev_date_to_show', 'placeholder' => '', 'label' => false)); ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary continueWOPrintMaintBtn">Continue</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" >Cancel</button>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>