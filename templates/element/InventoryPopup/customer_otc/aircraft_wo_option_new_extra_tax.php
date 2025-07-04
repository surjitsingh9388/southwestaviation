<div id="aircraftWOOptNewExtTaxModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create($wooptionextrataxes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOptNewExtraTaxes'));
        ?> 
        <input type="hidden" name="tax_id" value="<?php echo $tax_id; ?>" />
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Enter New Tax Name</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label col-md-2" for="airframe_component_id">Tax Name</label>
                        <div class="col-md-10">
                        <?php echo $this->Form->control('extra_tax_name', array('class' => 'form-control col-md-10', 'label'=> false, 'id'=>'new_extra_tax_name')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary saveWOOptionNewExtraTaxes">Add</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>