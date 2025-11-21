<fieldset class="scheduler-border">
    <legend class="scheduler-border">Payment Options</legend>
    <?php
    echo $this->Form->create($wooptionwarrantyinfopayments, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOptWarrantyInfo'));
    ?>
    <input type="hidden" name="warranty_info_id" value="<?php echo @$wooptionwarrantyinfoes->id; ?>" />
    <input type="hidden" name="id" value="<?php echo @$wooptionwarrantyinfopayments->id; ?>" />
    
    <div class="col-md-7">
        <div class="form-group">
            <label class="control-label" for="reference">Company Name</label>
            <div class="form-input-frame">
                <?php 
                $warrantycomparr = unserialize(WOITEMOVERVIEWWARRANTY);
                
                $warrantyoption = [];
                $company_id = !empty($wooptionwarrantyinfopayments->company_id) ? $wooptionwarrantyinfopayments->company_id: '';
                if(empty($company_id) && !empty($warranty_id)){
                    $company_id = $warranty_id;
                }
                if(!empty($company_id)){
                    $warrantyoption[$company_id] = $warrantycomparr[$company_id];
                }
                
                echo $this->Form->control('company_id', array('options' => $warrantyoption, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'value'=>$company_id)); ?>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="reference">Contact</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('contact', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="reference">Currency</label>
                <div class="form-input-frame">
                    <?php
                    $currency = unserialize(CURRENCY);
                    echo $this->Form->control('currency', array('options' => $currency, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Tax Information</legend>
            <div class="col-md-5">
                <div class="form-group">
                    <label class="control-label" for="reference">Tax Method</label>
                    <div class="form-input-frame">
                        <?php
                        $woOptTaxMethod = unserialize(WOOPTIONTAXMETHOD);
                        echo $this->Form->control('tax_method', array('options' => $woOptTaxMethod, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <label class="control-label" for="reference">Tax Rate</label>
                    <div class="form-input-frame">
                        <?php 
                        $tax_rate1 = !empty($wooptionwarrantyinfopayments->tax_rate1) ? '$'.number_format((float)$wooptionwarrantyinfopayments->tax_rate1, 2) : '$0.00';

                        echo $this->Form->control('tax_rate1', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'tax_rate1', 'value'=>$tax_rate1)); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-5">&nbsp;</div>
            <div class="col-md-7">
                <div class="form-group">
                    <label class="control-label" for="reference">Tax Rate</label>
                    <div class="form-input-frame">
                        <?php 
                        $tax_rate2 = !empty($wooptionwarrantyinfopayments->tax_rate2) ? '$'.number_format((float)$wooptionwarrantyinfopayments->tax_rate2, 2) : '$0.00';

                        echo $this->Form->control('tax_rate2', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'tax_rate2', 'value'=>$tax_rate2)); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-5">&nbsp;</div>
            <div class="col-md-7">
                <div class="form-group">
                    <label class="control-label" for="reference">Tax Rate</label>
                    <div class="form-input-frame">
                        <?php 
                        $tax_rate3 = !empty($wooptionwarrantyinfopayments->tax_rate3) ? '$'.number_format((float)$wooptionwarrantyinfopayments->tax_rate3, 2) : '$0.00';

                        echo $this->Form->control('tax_rate3', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'tax_rate3', 'value'=>$tax_rate3)); ?>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="col-md-6">
            <div class="col-md-12">
                <?php
                    $pay_labor_chk = '';
                    if(@$wooptionwarrantyinfopayments->pay_labor == '1'){
                        $pay_labor_chk = 'checked';
                    }
                ?>
                <input type="checkbox" name="pay_labor" value="1" <?php echo $pay_labor_chk; ?> />&nbsp;Pay Labor
            </div>
            <div class="col-md-12">
                <?php
                    $pay_parts_chk = '';
                    if(@$wooptionwarrantyinfopayments->pay_parts == '1'){
                        $pay_parts_chk = 'checked';
                    }
                ?>
                <input type="checkbox" name="pay_parts" value="1" <?php echo $pay_parts_chk; ?> />&nbsp;Pay Parts
            </div>
            <div class="col-md-12">
                <?php
                    $pay_shipping_chk = '';
                    if(@$wooptionwarrantyinfopayments->pay_shipping == '1'){
                        $pay_shipping_chk = 'checked';
                    }
                ?>
                <input type="checkbox" name="pay_shipping" value="1" <?php echo $pay_shipping_chk; ?> />&nbsp;Pay Shipping
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-12">
                <?php
                    $taxable_chk = '';
                    if(@$wooptionwarrantyinfopayments->taxable == '1'){
                        $taxable_chk = 'checked';
                    }
                ?>
                <input type="checkbox" name="taxable" value="1" <?php echo $taxable_chk; ?> />&nbsp;Taxable
            </div>
            <div class="col-md-12">
                <?php
                    $customer_pays_warranty_tax_chk = '';
                    if(@$wooptionwarrantyinfopayments->customer_pays_warranty_tax == '1'){
                        $customer_pays_warranty_tax_chk = 'checked';
                    }
                ?>
                <input type="checkbox" name="customer_pays_warranty_tax" value="1" <?php echo $customer_pays_warranty_tax_chk; ?> />&nbsp;Customer Pays Warranty Tax
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Part Pricing</legend>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Part Pricing Options</label>
                    <div class="form-input-frame">
                        <?php
                        $warrantyPartPricingOptions = unserialize(WARRANTYPARTPRICINGOPTIONS);
                        echo $this->Form->control('part_pricing_options', array('options' => $warrantyPartPricingOptions, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">% Over Cost</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('over_cost', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>
                </div>
            </div>

        </fieldset>

        <fieldset class="scheduler-border">
            <legend class="scheduler-border">Specified Labor Rate</legend>
            <div class="col-md-12">
                <?php
                    $use_labor_rate_chk = '';
                    if(@$wooptionwarrantyinfopayments->use_labor_rate == '1'){
                        $use_labor_rate_chk = 'checked';
                    }
                ?>
                <input type="checkbox" name="use_labor_rate" value="1" <?php echo $use_labor_rate_chk; ?> />&nbsp;Use Labor Rate
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Specified Labor Rate</label>
                    <div class="form-input-frame">
                        <?php 
                        $specified_labor_rate = !empty($wooptionwarrantyinfopayments->specified_labor_rate) ? '$'.number_format((float)$wooptionwarrantyinfopayments->specified_labor_rate, 2) : '$0.00';

                        echo $this->Form->control('specified_labor_rate', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'specified_labor_rate', 'value'=>$specified_labor_rate)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <button type="button" class="btn btn-default" disabled>Refresh Item Info</button>
            </div>
        </fieldset>
    </div>
    <?php echo $this->Form->end(); ?>
</fieldset>