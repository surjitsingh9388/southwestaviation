<div id="woSetAllItemsSpecificDeptModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftContractRate'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reset All Items to Department</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-4" for="plane_id">Department</label>
                            <div class="col-md-8">
                                <?php
                                $contractRateDepartment = unserialize(CONTRACT_RATE_DEPARTMENT);
                                echo $this->Form->control('contract_rate_department', array('options' => $contractRateDepartment, 'empty' => '', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'contract_rate_department_new'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary saveWOSetAllItemsSpecificDept" data-dismiss="modal">Continue</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>