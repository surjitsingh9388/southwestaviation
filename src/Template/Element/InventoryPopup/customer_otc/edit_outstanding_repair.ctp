<div id="woOutstandingRepairEditModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;">
        <?php
        echo $this->Form->create($wooutstandingoutside, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOSR'));
        ?> 
        
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Outside Repair Information</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default wooutsiderepairaddbtn"><</button>
                            <button type="button" class="btn btn-default wooutsiderepairremovebtn">></button>
                            <button type="button" class="btn btn-default newwosorrecordbtn" <?php if($woitemdata->wo_item_status == '3'){ ?> disabled<?php } ?>>New OSR Record</button>
                            <button type="button" class="btn btn-default removewoosrbtn" <?php if($woitemdata->wo_item_status == '3'){ ?> disabled<?php } ?> >Delete OSR</button>
                            <button type="button" class="btn btn-default woosraddtoporobtn" <?php if($woitemdata->wo_item_status == '3'){ ?> disabled<?php } ?>  data-val="1">Add to P/O</button>
                            <?php if($wodetails->order_type== '1'){ ?>
                            <button type="button" class="btn btn-default woosraddtoporobtn" <?php if($woitemdata->wo_item_status == '3'){ ?> disabled<?php } ?>  data-val="2">Create New R/O</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div id="newwoosrhtmlblock">
                    <?php echo $this->element('Inventory/customer_otc/aircraft_wo_osr_create_fld'); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary saveAircraftWOOSRBtn" <?php if($woitemdata->wo_item_status == '3'){ ?> disabled<?php } ?>>Save</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>