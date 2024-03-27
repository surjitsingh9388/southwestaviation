<div id="woCreateNewWOModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        //echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomersNotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create New Work Order</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Registration Number</label>
                            <div class="form-input-frame">
                                <?php
                                    echo $this->Form->control('filter_aircraft_registration_number', array('options' => $aircraftoptiondata, 'empty' => '', 'class' => 'form-control selectpicker mh', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'filter_aircraft_registration_number'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary float-left" onclick="$('#addNewCustomerModal').modal('show');">Create New Customer</button>
                <button type="button" class="btn btn-primary continueToCreateWO">Continue</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        <?php 
        //echo $this->Form->end(); 
        ?>
    </div>
</div>