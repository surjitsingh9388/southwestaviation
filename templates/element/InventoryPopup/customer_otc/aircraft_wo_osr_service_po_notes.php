<div id="aircraftWOOSRServicePONotesModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create($woosrinfopo, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOSRServicePONotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Notes for Service P/O # <?php echo $woosrinfopo->po_no; ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="osr_infopoes_id" id="osr_infopoes_id" value="<?php echo @$woosrinfopo->id; ?>" />
                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo $this->Form->control('service_po_notes', array('type'=>'textarea','class' => 'form-control col-md-10 col-xs-12', 'label'=> false, 'row mb-3s'=>2, 'id'=>'service_po_notes', 'required'=>'required')); ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <?php
                        $hide_note_on_printout_chk = '';
                        if(!empty($woosrinfopo->hide_note_on_printout)){
                            $hide_note_on_printout_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="hide_note_on_printout" id="hide_note_on_printout" value="1" <?php echo $hide_note_on_printout_chk; ?> />&nbsp;Hide on Printouts
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saveWOOSRServicePONotesbtn">Save</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>