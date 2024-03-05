<div id="addAircraftModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        //echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomersNotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Registration Number</h4>
            </div>
            <div class="modal-body" style="height: 150px;">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label col-md-5" for="airframe_component_id">Enter Registration Number</label>
                        <div class="col-md-7">
                        <?php echo $this->Form->control('aircraft_registration_number', array('class' => 'form-control col-md-7', 'label'=> false)); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary saveAircraftRegNumber" data-dismiss="modal">Add</button>
            </div>
        </div>
        <?php 
        //echo $this->Form->end(); 
        ?>
    </div>
</div>