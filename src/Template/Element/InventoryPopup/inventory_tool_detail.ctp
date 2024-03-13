<div id="inventoryToolDetailModel" class="modal fade page-content" role="dialog" style="background: transparent; margin-left: -17px ;">
    <div class="modal-dialog" style="width: 65%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Tools: <?php echo $inventorytools->tool_name; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default inv-tool-prev-btn" data-val="first"><<</button>
                            <button type="button" class="btn btn-default inv-tool-prev-btn" data-val="prev"><</button>
                            <button type="button" class="btn btn-default inv-tool-next-btn" data-val="next">></button>
                            <button type="button" class="btn btn-default inv-tool-next-btn" data-val="last">>></button>
                            <button type="button" class="btn btn-default add-new-tool-popup">New</button>
                            <button type="button" class="btn btn-default" onclick='$("#inventoryToolDetailModel").modal("hide");'>List View</button>
                            <button type="button" class="btn btn-default inventory-tool-delete-btn">Delete Record</button>
                            <button type="button" class="btn btn-default">Preview</button>
                            <button type="button" class="btn btn-default">Print</button>
                        </div>
                    </div>
                </div>
                
                <div class="row inventory_tool_detail_block">
                    <?php echo $this->element('Inventory/inventory_tool_detail_block'); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" class="btn btn-primary saveToolInfoDetail">Save</button>
            </div>
        </div>
    </div>
</div>
<script>
    var getInventoryToolNextPrevURL = "<?php echo $this->Url->build(['controller'=>'InventoryTools', 'action'=>'getInventoryToolNextPrev']); ?>";
</script>