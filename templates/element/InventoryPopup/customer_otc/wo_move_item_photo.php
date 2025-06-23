<div id="woMoveItemPhotoModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 20%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Move: <?php echo strlen($wo_move_item_photo) > '30' ? substr($wo_move_item_photo, 0, 30) . '...' : $wo_move_item_photo; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                    echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOMoveItemPhoto'));
                    ?> 
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Enter new item number</label>
                            <div class="form-input-frame">
                                <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
                                <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
                                <input type="hidden" name="wo_move_item_photo_id" value="<?php echo $wo_move_item_photo_id; ?>" />

                                <?php echo $this->Form->control('wo_photo_item_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>$wo_item_no, 'id'=>'wo_photo_item_number')); ?>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary saveWOMoveItemPhotobtn">Continue</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" >Cancel</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var saveWOOSRMoveItemURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOOSRMoveItem']); ?>";
</script>
