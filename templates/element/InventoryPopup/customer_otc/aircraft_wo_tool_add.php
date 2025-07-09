<div id="aircraftWOAddToolModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create($woitemtools, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWorkOrderItemTools'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Tool</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="wo_item_id" id="tool_wo_item_id" value="<?php echo $wo_item_id; ?>" />
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Tool Name</label>
                            <div class="form-input-frame">
                                <?php
                                    echo $this->Form->control('tool_id', array('options' => $tooldropdown, 'empty' => 'Select', 'class' => 'form-control selectpicker mh', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_tool_id'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary saveWorkOrderItemTools" data-val='add'>Continue</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>