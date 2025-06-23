<div id="inventoryToolAddPopup" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Add Tool</h4>
            </div>
            <div class="modal-body">
                <div  class="row">
                    <div  class="col-xs-12">
                        <div class="form-group">
                            <label class="col-sm-3 col-xs-12 control-label">Enter new tool name</label>
                            <div class="col-sm-9 col-xs-12 form-label-input-wrapper">
                                <?php echo $this->Form->control('tool_name', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'id'=>'inventory_tool_name')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" class="btn btn-primary saveAddNewTool">Add</button>
            </div>
        </div>
    </div>
</div>
