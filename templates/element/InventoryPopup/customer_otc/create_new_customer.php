<div id="addNewCustomerModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create(null, array('url' => $this->Url->build(['controller' => 'InventoryCustomers', 'action' => 'saveCustomerInfo'], ['fullBase' => true]), 'class' => 'form-horizontal form-label-left', 'id' => 'frmAddNewCustomer'));
        ?>
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Customer</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Customer Name: <span class="required">*</span>
                            </label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('customer_name', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '', 'required' => 'required', 'id' => 'customer_name')); ?>
                            </div>
                            <span id="customer_name_error" style="color:red; display:none;">Please enter customer name.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary saveCustomerInfoBtn">Save</button>
            </div>
        </div>
        <?php
        echo $this->Form->end();
        ?>
    </div>
</div>