<div id="contractPricingDeptAddModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create($customercontractrates, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftContractRate'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Department</h4>
            </div>
            <div class="modal-body">
                <?php
                $contract_rate_id = !empty($customercontractrates->id) ? $customercontractrates->id : '';
                $deptdisabled = !empty($contract_rate_id) ? 'disabled' : '';
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-4" for="plane_id">Department</label>
                            <div class="col-md-8">
                                <?php
                                $contractRateDepartment = unserialize(CONTRACT_RATE_DEPARTMENT);
                                echo $this->Form->control('contract_rate_department', array('options' => $contractRateDepartment, 'empty' => '', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'contract_rate_department_new', $deptdisabled));
                                ?>
                                <input type="hidden" name="id" id="contract_rate_id" value="<?php echo $contract_rate_id; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="plane_id">Rate an hour</label>
                            <div class="col-md-8">
                                <?php echo $this->Form->control('rate_an_hour', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'contract_rate_hour_new')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary saveAircraftContractRate" data-dismiss="modal">Save</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>