<div id="customerAddlShippingAddressModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAddlShippingAddress'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Additional Shipping Addresses for: <?php echo $inventorycustomers->customer_name; ?></h4>
            </div>
            <div class="modal-body">
                <div class="col-md-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">List of Ship-To Address</label>
                            <p><i>(Double click to select shipping addresss)</i></p>
                        </div>

                        <div class="form-group listitems listofadlshipaddr">
                            <?php
                            foreach($customerAddlShipAddrList as $shippaddr){
                            ?>
                            <div class="addlshipaddrbox" data-val="<?php echo $shippaddr['id']; ?>"><?php echo $shippaddr['additional_shipping_address_description']; ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <!--button type="button" class="btn btn-default fetchCustOTCPopup" data-val='cust_add_ship_addr_btn'>New</button-->
                        <button type="button" class="btn btn-default deleteAdditionalShipAddr">Delete</button>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="row">
                        <input type="hidden" name="additional_shipping_address_id" id="additional_shipping_address_id" value="" />
                        <input type="hidden" id="invoice_customer_name" value="<?php echo $inventorycustomers->customer_name; ?>" />
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Description</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('additional_shipping_address_description', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Full Address Information</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('additional_shipping_full_address', array('type'=>'textarea', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'style'=>'width: 389px; height: 226px;')); ?>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary saveAddlShippingAddrBtn">Save</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>