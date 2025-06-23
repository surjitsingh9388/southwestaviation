<div id="woItemDiscrepancyModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create($workorderinfo, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOItemDiscrepancy'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <input type="hidden" name="wo_item_id" value="<?php echo @$wo_item_id; ?>">
                <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />

                <button type="button" class="close saveWOItemDiscrepancybtn" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Discrepancy</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('wo_discrepancy', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5', 'style'=>'height: 200px;', 'required'=>'required')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary saveWOItemDiscrepancybtn">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
    <script type="text/javascript">
        var saveWOItemDiscrepancyURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOItemDiscrepancy']); ?>";
    </script>
</div>