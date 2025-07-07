<div id="customerNotesModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create($inventorycustomers, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomersNotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Notes</h4>
            </div>
            <div class="modal-body" style="height: 150px;">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="airframe_component_id">Notes</label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                        <?php echo $this->Form->control('notes', array('type'=>'textarea','class' => 'form-control col-md-10 col-xs-12', 'label'=> false, 'row mb-3s'=>2, 'id'=>'customer_info_notes', 'required'=>'required')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saveCustomerInfoNotesbtn">Save</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>