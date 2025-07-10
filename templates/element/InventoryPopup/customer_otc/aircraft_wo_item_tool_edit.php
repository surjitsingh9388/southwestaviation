<div id="aircraftWOItemToolEditModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Edit Tool Information</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default wo-item-tool-prev-btn" data-val="first"><<</button>
                            <button type="button" class="btn btn-default wo-item-tool-prev-btn" data-val="prev"><</button>
                            <button type="button" class="btn btn-default wo-item-tool-next-btn" data-val="next">></button>
                            <button type="button" class="btn btn-default wo-item-tool-next-btn" data-val="last">>></button>
                            <button type="button" class="btn btn-default wo-item-tool-add-btn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>New</button>
                            <button type="button" class="btn btn-default inventory-tool-delete-btn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Remove Tool</button>
                            <button type="button" class="btn btn-default">Preview</button>
                            <button type="button" class="btn btn-default">Print</button>
                        </div>
                    </div>
                </div>
                
                <div class="row wo-item-tool-edit-block">
                    <?php echo $this->element('Inventory/customer_otc/aircraft_wo_item_tool_edit_block'); ?>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" class="btn btn-primary saveWorkOrderItemEditTools" data-val='edit' <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Save</button>
            </div>
        </div>
    </div>
</div>
<script>
    var getWOItemToolNextPrevURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getWOItemToolNextPrev']); ?>";
    var deleteWOItemToolURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteWOItemTool']); ?>";
</script>