<?php
echo $this->Form->create($aircraftwoosrvendors, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOSRVendorInfo'));
?>
<div class="row">
    <input type="hidden" name="osr_vendor_id" id="osr_vendor_id" value="<?php echo @$aircraftwoosrvendors->id; ?>" />
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="reference">Contact</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('vendor_contact', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_contact')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Terms</label>
                <div class="form-input-frame">
                    <?php
                    $termsArr = unserialize(CUSTOMERTERMS);
                    echo $this->Form->control('vendor_terms', array('options' => $termsArr, 'empty' => 'Select Terms', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'vendor_terms'));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Phone</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('vendor_phone', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_phone')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="reference">Address</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('address', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_address')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Ship Address</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('ship_address', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_ship_address')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Alt Phone</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('alt_phone', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_alt_phone')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="reference">Address2</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('address2', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_address2')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Ship Address2</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('ship_address2', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'ship_address2')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Alt Phone2</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('alt_phone2', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_alt_phone2')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="reference">City</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('city', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_city')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Ship City</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('ship_city', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_ship_city')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Fax</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('fax', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_fax')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="col-md-6 padding0">
                <div class="form-group">
                    <label class="control-label" for="reference">State</label>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('state', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_state')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="reference">Zip</label>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('zip', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_zip')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="col-md-6 padding0">
                <div class="form-group">
                    <label class="control-label" for="reference">Ship State</label>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('ship_state', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_ship_state')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="reference">Ship Zip</label>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('ship_zip', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_ship_zip')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Email</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('email', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_email')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="reference">Country</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('country', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_country')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Ship Country</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('ship_country', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_ship_country')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Website</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('website', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_website')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="reference">Account Number</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('account_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_account_number')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">No. Days Until Cores Due</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('no_days_until_cores_due', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'no_days_until_cores_due')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Website Notes</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('website_notes', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_website_notes')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label" for="reference">Currency</label>
                <div class="form-input-frame">
                    <?php
                    $currencyArr = unserialize(CURRENCYOVERRIDE);
                    echo $this->Form->control('currency', array('options' => $currencyArr, 'empty' => 'Select Currency', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'vendor_currency'));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Approval Expires</label>
                <div class="input-group date datePicker">
                    <?php echo $this->Form->Text('approval_expires', array('class' => 'form-control', 'id' => 'vendor_approval_expires', 'placeholder' => '', 'label' => false)); ?>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Vendor Class</label>
                <div class="form-input-frame">
                    <?php
                    $vendorClassArr = unserialize(WO_OSR_VENDOR_CLASS);
                    echo $this->Form->control('vendor_class', array('options' => $vendorClassArr, 'empty' => 'Select Vendor Class', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'vendor_class'));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <?php
                $aleays_use_this_currencychk = '';
                if(!empty($aircraftwoosrvendors->aleays_use_this_currency)){
                    $aleays_use_this_currencychk = 'checked';
                }
                ?>
                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="aleays_use_this_currency" <?php echo $aleays_use_this_currencychk; ?>>
                <span class="form-check-label" for="flexCheckDefault">Always Use This Currency</span>
            </div>
            <div class="form-group">
                <?php
                $show_country_on_printoutschk = '';
                if(!empty($aircraftwoosrvendors->show_country_on_printouts)){
                    $show_country_on_printoutschk = 'checked';
                }
                ?>
                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="show_country_on_printouts" <?php echo $show_country_on_printoutschk; ?>>
                <span class="form-check-label" for="flexCheckDefault">Show Country on Printout</span>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <?php
                $is_approvedchk = '';
                if(!empty($aircraftwoosrvendors->is_approved)){
                    $is_approvedchk = 'checked';
                }
                ?>
                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="is_approved" <?php echo $is_approvedchk; ?>>
                <span class="form-check-label" for="flexCheckDefault">Is Approved</span>
            </div>
            <div class="form-group">
                <?php
                $not_approvedchk = '';
                if(!empty($aircraftwoosrvendors->not_approved)){
                    $not_approvedchk = 'checked';
                }
                ?>
                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="not_approved" <?php echo $not_approvedchk; ?>>
                <span class="form-check-label" for="flexCheckDefault">Not Approved</span>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">P/O Tax Rate</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('po_tax_rate', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'%', 'id'=>'vendor_po_tax_rate  ')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-4">
            <div class="form-group">
                <?php
                $country_on_printoutchk = '';
                if(!empty($aircraftwoosrvendors->country_on_printout)){
                    $country_on_printoutchk = 'checked';
                }
                ?>
                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="country_on_printout" <?php echo $country_on_printoutchk; ?>>
                <span class="form-check-label" for="flexCheckDefault">Has Seperate Shipping Address</span>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <?php
                $does_tool_calbrationschk = '';
                if(!empty($aircraftwoosrvendors->does_tool_calbrations)){
                    $does_tool_calbrationschk = 'checked';
                }
                ?>
                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="does_tool_calbrations" <?php echo $does_tool_calbrationschk; ?>>
                <span class="form-check-label" for="flexCheckDefault">Does Tool Calbrations</span>
            </div>
            <div class="form-group">
                <?php
                $does_outside_repairchk = '';
                if(!empty($aircraftwoosrvendors->does_outside_repair)){
                    $does_outside_repairchk = 'checked';
                }
                ?>
                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="does_outside_repair" <?php echo $does_outside_repairchk; ?>>
                <span class="form-check-label" for="flexCheckDefault">Does Outside Repair</span>
            </div>
        </div>
        <div class="col-md-4"> 
            <div class="form-group">
                <label class="control-label" for="reference">Total Amount Spent</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('total_amount_spent', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'%', 'id'=>'vendor_total_amount_spent')); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>