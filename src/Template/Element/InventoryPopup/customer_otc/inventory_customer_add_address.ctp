<div id="customerAddressAddModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 35%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Add Shipping Address</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <div class="page-content">
                    <?php echo $this->Form->create($inventorycustomeraddresses, ['action'=>'saveaddress', 'id' => 'frmCustomerAddAddress', 'autocomplete'=>'off']); ?>
                    <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Name&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php 
                                echo $this->Form->control('name', array('class'=>'form-control col-md-8 col-xs-12 mf_name', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'customer_addr_name'));
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt10">
                        <div class="col-xs-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Address&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php 
                                echo $this->Form->control('address', array('class'=>'form-control col-md-8 col-xs-12 mf_name', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'customer_addr_address'));
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt10">
                        <div class="col-xs-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Address 2</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php 
                                echo $this->Form->control('address2', array('class'=>'form-control col-md-8 col-xs-12 mf_name', 'placeholder' => '', 'label' => false, 'id'=>'customer_addr_address2'));
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt10">
                        <div class="col-xs-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">City&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                    echo $this->Form->control('city', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'customer_addr_city'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt10">
                        <div class="col-xs-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Country&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                    echo $this->Form->control('country', array('options' => $countries, 'empty' => 'Select a country...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'customer_addr_country', 'required' => 'required')); 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt10">
                        <div class="col-xs-12">
                            <?php
                            $stateblock = 1;
                            if(empty($inventoryaddresses->country) || $inventoryaddresses->country != '231'){
                                $stateblock = 0;
                            }
                            ?>
                            <div class="form-group customer_addr_provinceblock" <?php if(!empty($stateblock)){ ?>style="display:none;"<?php } ?>> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Province&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                    echo $this->Form->control('province', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'customer_addr_province'));
                                    ?>
                                </div>
                            </div>
                            
                            <div class="form-group customer_addr_stateblock" <?php if(empty($stateblock)){ ?>style="display:none;"<?php } ?>> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">State&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                    echo $this->Form->control('state', array('options' => $states, 'empty' => 'Select a state...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'customer_addr_state'));
                                    ?>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="row mt10">
                        <div class="col-xs-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Zip&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                    echo $this->Form->input('zip', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'customer_addr_zip'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt10">
                        <div class="col-xs-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Phone Number</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                    echo $this->Form->input('phone_number', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'customer_addr_phone_number'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--div class="row mt10">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <?php 
                                    //echo $this->Form->text('is_billing_address', array('type'=>'checkbox', 'label'=>'Label', 'id'=>'is_billing_address'));
                                    ?>
                                    <span>This address supports being billed to.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <?php 
                                    //echo $this->Form->text('is_shipping_address', array('type'=>'checkbox', 'label'=>'Label', 'id'=>'is_shipping_address'));
                                    ?>
                                    <span>This address supports being shipped to.</span>
                                </div>
                            </div>
                        </div>
                    </div-->
                    
                    <?php 
                    echo $this->Form->end(); 
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary customerAddlAddressSaveBtn">Save</button>
            </div>
        </div>
    </div>
    <script>
        var saveCustomerAddlAddressURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveCustomerAddlAddress']); ?>";
        var getStatesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getStatesList']); ?>";
    </script>
</div>