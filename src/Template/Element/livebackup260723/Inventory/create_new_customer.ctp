<section class="top-form-section">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group part-number">
                <label class="control-label" for="reference">Customer Name <span class="required">*</span>
                </label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('customer_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group part-number">
                <label class="control-label" for="reference">Account Number</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('account_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
            
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Work Phone</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('work_phone', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="0" id="flexCheckDefault" name="status">
                <span class="form-check-label" for="flexCheckDefault">
                Inactive
                </span>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" name="use_dealer_price" value="1">
                <span class="form-check-label" for="flexCheckChecked">
                Use Dealer Prices
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group part-number">
                <label class="control-label" for="reference">Name2 
                </label>
                <span style="margin-left:35px;">
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="show_on_invoice">
                    <span class="form-check-label" for="flexCheckDefault">
                    Show on Invoice
                </span>
                </span>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('name2', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group part-number">
                <label class="control-label" for="reference">Title</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('title', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
            
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Home Phone</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('home_phone', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="taxable">
                <span class="form-check-label" for="flexCheckDefault">
                Taxable
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group part-number">
                <label class="control-label" for="reference">Address</label>
                </span>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('address', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group part-number">
                <label class="control-label" for="reference">Ship to Address</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('ship_to_address', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
            
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Cellular Phone</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('cellular_phone', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Tax Exempt Expire</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('tax_exempt_expire', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group part-number">
                <label class="control-label" for="reference">Address2</label>
                </span>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('address2', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group part-number">
                <label class="control-label" for="reference">Ship to Address2</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('ship_to_address2', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
            
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Fax</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('fax', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Customer Since</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('customer_since', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>date('m-d-Y'), 'readonly'=>'readonly')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group part-number">
                <label class="control-label" for="reference">City</label>
                </span>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('city', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group part-number">
                <label class="control-label" for="reference">Ship to City</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('ship_to_city', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
            
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Pager</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('pager', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">County</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('county', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="col-md-6">
                <div class="form-group part-number">
                    <label class="control-label" for="reference">State</label>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('state', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group part-number">
                    <label class="control-label" for="reference">Zip</label>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('zip', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="col-md-6">
                <div class="form-group part-number">
                    <label class="control-label" for="reference">Ship to State</label>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('ship_to_state', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group part-number">
                    <label class="control-label" for="reference">Ship to Zip</label>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('ship_to_zip', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Email</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('email', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Terms</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('terms', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group part-number">
                <label class="control-label" for="reference">Country</label>
                </span>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('country', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group part-number">
                <label class="control-label" for="reference">Ship to Country</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
            
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Currency Override</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Tax ID</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <span class="form-check-label" for="flexCheckDefault">
                Use Country on Printout
                </span>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                <span class="form-check-label" for="flexCheckChecked">
                Use Ship Country on Printout
                </span>
            </div>
        </div>
        <div class="col-md-3">
            
        </div>
            
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Sales Rep.</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group d-flex">
                <label class="control-label" for="reference">Total Spent (A/C)</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <button>Media</button>
            <button>Notes</button>
        </div>

        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <span class="form-check-label" for="flexCheckDefault">
                Show Notes on W/O Create
                </span>
            </div>
        </div>
            
        <div class="col-md-6">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <span class="form-check-label" for="flexCheckDefault">
                Do Not Change Shop Supplier by Default
                </span>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <span class="form-check-label" for="flexCheckDefault">
                Always Requires Owner Authorization
                </span>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <span class="form-check-label" for="flexCheckDefault">
                Use County for Tax Rate(OTC, R/O, W/O)
                </span>
            </div>
        </div>
    </div>
</section>