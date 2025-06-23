<div id="woPrintPreviewDateRangeModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <?php
        echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOPrintPreview'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Enter Date Range</h4>
                <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
                <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
                <input type="hidden" name="wo_report_type" value="<?php echo $wo_report_type; ?>" />
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" style="border-bottom:1px solid gray;">Please enter a date range.</div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Start Date</label>
                            <div class="form-input-frame">
                                <div class="input-group date datePicker">
                                    <?php echo $this->Form->Text('start_date', array('class' => 'form-control', 'id' => 'wo_daterange_start_date', 'placeholder' => '', 'label' => false)); ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">End Date</label>
                            <div class="form-input-frame">
                                <div class="input-group date datePicker">
                                    <?php echo $this->Form->Text('end_date', array('class' => 'form-control', 'id' => 'wo_daterange_end_date', 'placeholder' => '', 'label' => false)); ?>
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
                <button type="button" class="btn btn-primary continueWOPrintDateRangeBtn" data-dismiss="modal">Continue</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>