<div id="aircraftWorkOrderItemPartNotesModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create($aircraftwoitemparts, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOItemPartNotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Notes for Part: <?php echo @$aircraftwoitemparts->part_number; ?></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="wo_item_part_id" id="wo_item_part_id" value="<?php echo @$aircraftwoitemparts->id; ?>" />
                <input type="hidden" name="wo_item_id" id="part_wo_item_id" value="<?php echo @$aircraftwoitemparts->wo_item_id; ?>" />
                <div class="col-md-12 col-xs-12 col-sm-12">
                    <div class="form-group">
                        <?php echo $this->Form->control('part_notes', array('type'=>'textarea','class' => 'form-control col-md-10 col-xs-12', 'label'=> false, 'row mb-3s'=>2, 'id'=>'wo_item_part_notes', 'required'=>'required')); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary wo-itempart-addpart-btn" data-val="part_notes">Save</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>