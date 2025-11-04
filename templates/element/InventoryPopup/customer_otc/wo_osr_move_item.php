<div id="woOSRMoveItemModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Enter the new item number</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                    echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOOSRMoveItem'));
                    ?> 
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Item Number</label>
                            <div class="form-input-frame">
                                <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
                                <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
                                <input type="hidden" name="osr_info_id" value="<?php echo $osr_info_id; ?>" />

                                <?php echo $this->Form->control('osr_item_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>$wo_item_no)); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary saveWOMoveItembtn">Continue</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" >Cancel</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var saveWOOSRMoveItemURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOOSRMoveItem']); ?>";
</script>
