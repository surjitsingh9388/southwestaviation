<div id="aircarftOptionWOLaborKitModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Enter New Labor Kit Name</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <?php
                    echo $this->Form->create($woitemlaborkits, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOItemATACode'));
                    ?>
                    <input type="hidden" name="wo_item_labor_kit_id" id="wo_item_labor_kit_id" value="<?php echo @$woitemlaborkits->id; ?>" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Labor Kit Name</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('labor_kit', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'item_labor_kit')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary saveAircraftWOLaborKitbtn">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>