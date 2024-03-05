<div id="aircarftOptionWOLogBookHelperModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">LogBook Helper</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <?php
                    echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOItemLogBookHelper'));
                    ?>
                    <input type="hidden" name="wo_item_labor_kit_id" id="wo_item_labor_kit_id" value="" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">All Corrective Actions</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('all_corrective_actions', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 pd0">
                            <div class="col-md-9">
                                <p>This is a list of all Corrective Actions for this Work Order. Please copy this information and paste it into a Word Processor and edit the information. If you make changes here, the information will not be saved when this screen is reloaded.</p>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-default col-md-12">Copy Info</button>
                                <button type="button" class="btn btn-default col-md-12">Sort by Category</button>
                                <button type="button" class="btn btn-default col-md-12" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>