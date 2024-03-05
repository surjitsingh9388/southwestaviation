<section class="top-form-section">
    <?php
    echo $this->Form->create($wooptionwarrantyinfoes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOptWarrantyInfo'));
    ?>
    <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
    <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
    <?php $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '' ?>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label" for="reference">Warranty Companies</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->input('warranty_compaines', array('type' => 'textarea', 'class'=>'form-control', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'warranty_compaines')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Payment Options</legend>
                <div class="col-md-7">
                    <div class="form-group">
                        <label class="control-label" for="reference">Company Name</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('company_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
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
                                    <?php echo $this->Form->control('tax_rate1', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">&nbsp;</div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="control-label" for="reference">Tax Rate</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('tax_rate2', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">&nbsp;</div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label class="control-label" for="reference">Tax Rate</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('tax_rate3', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <?php
                                $pay_labor_chk = '';
                                if(@$wooptionwarrantyinfoes->pay_labor == '1'){
                                    $pay_labor_chk = 'checked';
                                }
                            ?>
                            <input type="checkbox" name="pay_labor" value="1" <?php echo $pay_labor_chk; ?> />&nbsp;Pay Labor
                        </div>
                        <div class="col-md-12">
                            <?php
                                $pay_parts_chk = '';
                                if(@$wooptionwarrantyinfoes->pay_parts == '1'){
                                    $pay_parts_chk = 'checked';
                                }
                            ?>
                            <input type="checkbox" name="pay_parts" value="1" <?php echo $pay_parts_chk; ?> />&nbsp;Pay Parts
                        </div>
                        <div class="col-md-12">
                            <?php
                                $pay_shipping_chk = '';
                                if(@$wooptionwarrantyinfoes->pay_shipping == '1'){
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
                                if(@$wooptionwarrantyinfoes->taxable == '1'){
                                    $taxable_chk = 'checked';
                                }
                            ?>
                            <input type="checkbox" name="taxable" value="1" <?php echo $taxable_chk; ?> />&nbsp;Taxable
                        </div>
                        <div class="col-md-12">
                            <?php
                                $customer_pays_warranty_tax_chk = '';
                                if(@$wooptionwarrantyinfoes->customer_pays_warranty_tax == '1'){
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
                                    echo $this->Form->control('part_pricing_options', array('options' => [], 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">% Over Cost</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('over_cost', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                </div>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Specified Labor Rate</legend>
                        <div class="col-md-12">
                            <?php
                                $use_labor_rate_chk = '';
                                if(@$wooptionwarrantyinfoes->use_labor_rate == '1'){
                                    $use_labor_rate_chk = 'checked';
                                }
                            ?>
                            <input type="checkbox" name="use_labor_rate" value="1" <?php echo $use_labor_rate_chk; ?> />&nbsp;Use Labor Rate
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Specified Labor Rate</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('specified_labor_rate', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-default" disabled>Refresh Item Info</button>
                        </div>
                    </fieldset>
                </div>
            </fieldset>
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-primary float-right saveWOOptionWarrantyInfo" <?php echo $isdisabled; ?>>Save</button>
        </div>
    </div>
</section>
<script>
    var saveWOViewOptionWarrantyInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOViewOptionWarrantyInfo']); ?>";
</script>