<section class="top-form-section">
    <?php
    echo $this->Form->create($inventorycustomers, array('url' => $this->Url->build(['controller' => 'InventoryCustomers', 'action' => 'saveCustomerInfo'], ['fullBase' => true]), 'class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomerInfo', 'autocomplete' => 'off'));
    ?>

    <div class="row">
        <input type="hidden" name="customer_info_id" value="<?php echo $inventorycustomers->id; ?>" />
        <div class="col-md-3">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Customer Name: <span class="required">*</span>
                    </label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('customer_name', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Address:</label>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('address', array('type' => 'Text', 'class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Address2:</label>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('address2', array('type' => 'Text', 'class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label" for="reference">City:</label>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('city', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-6 col-sm-6  col-xs-12 padding0">
                    <div class="form-group">
                        <label class="control-label" for="reference">State:</label>
                        </span>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('state', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="control-label" for="reference">Zip:</label>
                        </span>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('zip', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '')); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Country:</label>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('country', array('options' => $countries, 'empty' => 'Select a country...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'country'));  ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Account Number:</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('account_number', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Ship to Address:</label>
                    <div class="form-input-frame">
                        <div class="col-md-11 col-sm-11 col-xs-11 pd0">
                            <?php echo $this->Form->control('shipping_address_id', array('options' => $customeraddrdropdown, 'empty' => 'Select a ship to address...', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'customer_shipping_address_id'));  ?>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-1 pd0">
                            <button class="btn btn-primary add_customer_shipto_address plus-btn-h" type="button"><i class="fa fa-plus"></i></button>
                        </div>
                        <input type="hidden" name="ship_to_address" id="customer_shipping_ship_to_address" />
                        <input type="hidden" name="ship_to_address2" id="customer_shipping_ship_to_address2" />
                    </div>
                </div>

            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Ship to City:</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('ship_to_city', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '', 'readonly' => 'readonly', 'id' => 'customer_ship_to_city')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div >
                    <div class="form-group">
                        <label class="control-label" for="reference">Ship to State:</label>
                        </span>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('ship_to_state', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '', 'readonly' => 'readonly', 'id' => 'customer_ship_to_state')); ?>
                        </div>
                    </div>
                </div>
                 <div >
                    <div class="form-group">
                        <label class="control-label" for="reference">Ship to Zip:</label>
                        </span>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('ship_to_zip', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '', 'readonly' => 'readonly', 'id' => 'customer_ship_to_zip')); ?>
                        </div>
                    </div>
                </div>
            </div>

       <div class="col-md-12 col-sm-12 col-xs-12">  
              <div class="form-group">
                    <label class="control-label" for="reference">Ship to Country:</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('ship_to_country', array('options' => $countries, 'empty' => 'Select a ship to country...', 'class' => 'form-control  selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'customer_ship_to_country', 'disabled' => 'disabled'));  ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group d-flex">
                    <label class="control-label" for="reference">Phone Number:</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('cellular_phone', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group d-flex">
                    <label class="control-label" for="reference">Fax:</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('fax', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group d-flex">
                    <label class="control-label txtblue" for="reference">Email:</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('email', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group d-flex">
                    <label class="control-label" for="reference">Tax Exempt Expire:</label>
                    <div class="form-input-frame">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('tax_exempt_expire', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'tax_exempt_expire', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-3">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group d-flex">
                    <label class="control-label" for="reference">Customer Since:</label>
                    <div class="form-input-frame">
                        <?php
                        $customer_since = date('m-d-Y');
                        if (!empty($inventorycustomers->created)) {
                            $customer_since = date('m-d-Y', strtotime($inventorycustomers->created));
                        }
                        echo $this->Form->control('customer_since', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '', 'value' => $customer_since, 'readonly' => 'readonly')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group d-flex">
                    <label class="control-label" for="reference">County:</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('county', array('options' => [], 'empty' => 'Select a county...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'county'));  ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group d-flex">
                    <label class="control-label" for="reference">Terms:</label>
                    <div class="form-input-frame">
                        <?php
                        $customerterms = unserialize(CUSTOMERTERMS);
                        echo $this->Form->control('terms', array('options' => $customerterms, 'empty' => 'Select a terms...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'terms'));  ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group d-flex">
                    <label class="control-label" for="reference">Tax ID:</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tax_id', array('type' => 'Text', 'class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group d-flex">
                    <label class="control-label txtblue" for="reference">Total Spent (A/C):</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('total_spent', array('class' => 'form-control', 'label' => false, 'autocomplete' => 'off', 'placeholder' => '', 'value' => '$0.00', 'readonly' => 'readonly')); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-3">
                <div class="form-check">
                    <?php
                    $country_on_printoutchk = !empty($inventorycustomers->country_on_printout) ? 'checked' : '';
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="country_on_printout" <?php echo $country_on_printoutchk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">
                        Use Country on Printout
                    </span>
                </div>
                <div class="form-check">
                    <?php
                    $ship_country_on_printoutchk = !empty($inventorycustomers->ship_country_on_printout) ? 'checked' : '';
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckChecked" name="ship_country_on_printout" <?php echo $ship_country_on_printoutchk; ?>>
                    <span class="form-check-label" for="flexCheckChecked">
                        Use Ship Country on Printout
                    </span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-check">
                    <?php
                    $notes_on_wo_createchk = !empty($inventorycustomers->notes_on_wo_create) ? 'checked' : '';
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="notes_on_wo_create" <?php echo $notes_on_wo_createchk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">
                        Show Notes on W/O Create
                    </span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-check">
                    <?php
                    $change_shop_supplierchk = !empty($inventorycustomers->change_shop_supplier) ? 'checked' : '';
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="change_shop_supplier" <?php echo $change_shop_supplierchk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">
                        Do Not Change Shop Supplier by Default
                    </span>
                </div>
                <div class="form-check">
                    <?php
                    $requires_owner_authorizationchk = !empty($inventorycustomers->requires_owner_authorization) ? 'checked' : '';
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="requires_owner_authorization" <?php echo $requires_owner_authorizationchk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">
                        Always Requires Owner Authorization
                    </span>
                </div>
                <div class="form-check">
                    <?php
                    $country_for_tax_ratechk = !empty($inventorycustomers->country_for_tax_rate) ? 'checked' : '';
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="country_for_tax_rate" <?php echo $country_for_tax_ratechk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">
                        Use County for Tax Rate(OTC, R/O, W/O)
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-md-6">
                <button type="button" class="btn btn-default fetchCustOTCPopup" id="uploadedMedia" title="Click here to upload media" data-val="upload_new_cust_media">
                    
                
                <span>Media</span> <span class="count_circle count_customer_otc_file"><?php echo count($customerInfoMedia); ?></span></button>
                <button type="button" class="btn btn-default fetchCustOTCPopup" id="notes" title="Click here to add notes" data-val="new_cust_note">Notes</button>
            </div>

            <div class="col-md-6 text-right">
                <button type="submit" class="btn btn-primary saveCustomerInfoDetBtn">Save</button>
            </div>
        </div>
    </div>
    <?php
    echo $this->Form->end();
    ?>
</section>