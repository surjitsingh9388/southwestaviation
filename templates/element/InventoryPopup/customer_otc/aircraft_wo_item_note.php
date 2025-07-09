<div id="aircraftWorkOrderItemNoteModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create($aircraftwoitems, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWorkOrderNote'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Item Notes for # <?php echo @$aircraftwoitems->wo_item_position; ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="wo_item_id" id="note_wo_item_id" value="<?php echo @$aircraftwoitems->id; ?>" />
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <?php echo $this->Form->control('item_notes', array('type'=>'textarea','class' => 'form-control col-md-10 col-xs-12', 'label'=> false, 'row mb-3s'=>2, 'id'=>'item_notes', 'required'=>'required')); ?>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <?php
                        $show_on_estimate_invoice_chk = '';
                        if(!empty($aircraftwoitems->show_on_estimate_invoice)){
                            $show_on_estimate_invoice_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="show_on_estimate_invoice" id="show_on_estimate_invoice" value="1" <?php echo $show_on_estimate_invoice_chk; ?> />&nbsp;Show on Estimate/Invoice
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saveWorkOrderItemNotebtn">Save</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>