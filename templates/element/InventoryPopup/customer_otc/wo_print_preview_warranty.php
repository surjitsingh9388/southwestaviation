<div id="woPrintPreviewWarrantyModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <?php
        echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOPrintPreview'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select a warranty estimate to print</h4>
                <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
                <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
                <input type="hidden" name="wo_report_type" value="<?php echo $wo_report_type; ?>" />
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Warranty Name</label>
                            <div class="form-input-frame">
                                <?php
                                $woItemOverviewWarranty = unserialize(WOITEMOVERVIEWWARRANTY);
                                
                                echo $this->Form->control('warranty', array('options' => $woItemOverviewWarranty, 'empty' => 'Select Warranty Name', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_warranty_preview'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary continueWOPrintWarrantyBtn">Continue</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" >Cancel</button>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>