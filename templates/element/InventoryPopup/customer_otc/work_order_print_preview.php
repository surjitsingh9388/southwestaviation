<div id="workOrderPrintPreviewModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <?php
        echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOPrintPreview'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select a Report</h4>
                <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
                <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label class="control-label">Select Report</label>
                            <div class="form-input-frame">
                                <?php
                                    $woReportOption = unserialize(WOREPORTOPTION);
                                    echo $this->Form->control('wo_report_type', array('options' => $woReportOption, 'empty' => '', 'class' => 'form-control selectpicker mh', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_report_type'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary continueWOPrintBtn">Continue</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" >Cancel</button>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>