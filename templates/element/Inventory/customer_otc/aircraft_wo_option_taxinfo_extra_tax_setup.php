<?php echo $this->Form->create($wooptionextrataxes, ['class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOptExtraTaxes', 'autocomplete'=>'off']); ?>
    <input type="hidden" name="tax_id" value="<?php echo $tax_id; ?>" />
    <input type="hidden" name="extra_taxes_id" id="extra_taxes_id" value="<?php echo @$wooptionextrataxes->id; ?>" />
    
    <fieldset class="scheduler-border ">
        <legend class="scheduler-border">Extra Tax Setup</legend>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label" for="reference">Extra Tax Name</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('extra_tax_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            Total Tax is from---------------------------
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_labor_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_labor)){
                        $tax_from_labor_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_labor" <?php echo $tax_from_labor_chk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">Labor</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_shipout_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_shipout)){
                        $tax_from_shipout_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_shipout" <?php echo $tax_from_shipout_chk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">Ship Out</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_plot_services_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_plot_services)){
                        $tax_from_plot_services_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_plot_services" <?php echo $tax_from_plot_services_chk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">Plot Services</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_parts_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_parts)){
                        $tax_from_parts_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_parts" <?php echo $tax_from_parts_chk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">Parts</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_shipin_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_shipin)){
                        $tax_from_shipin_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_shipin" <?php echo $tax_from_shipin_chk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">Ship In</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_shop_supplies_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_shop_supplies)){
                        $tax_from_shop_supplies_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_shop_supplies" <?php echo $tax_from_shop_supplies_chk; ?> >
                    <span class="form-check-label" for="flexCheckDefault">Shop Supplies</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_labor_osr_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_labor_osr)){
                        $tax_from_labor_osr_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_labor_osr" <?php echo $tax_from_labor_osr_chk; ?> >
                    <span class="form-check-label" for="flexCheckDefault">Labor OSR</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_epa_charges_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_epa_charges)){
                        $tax_from_epa_charges_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_epa_charges" <?php echo $tax_from_epa_charges_chk; ?> >
                    <span class="form-check-label" for="flexCheckDefault">EPA Charges</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_misc_charges_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_misc_charges)){
                        $tax_from_misc_charges_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_misc_charges" <?php echo $tax_from_misc_charges_chk; ?> >
                    <span class="form-check-label" for="flexCheckDefault">Misc. Charges</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_parts_osr_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_parts_osr)){
                        $tax_from_parts_osr_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_parts_osr" <?php echo $tax_from_parts_osr_chk; ?> >
                    <span class="form-check-label" for="flexCheckDefault">Parts OSR</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_oil_analysis_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_oil_analysis)){
                        $tax_from_oil_analysis_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_oil_analysis" <?php echo $tax_from_oil_analysis_chk; ?> >
                    <span class="form-check-label" for="flexCheckDefault">Oil Analysis</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_fuel_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_fuel)){
                        $tax_from_fuel_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_fuel" <?php echo $tax_from_fuel_chk; ?> >
                    <span class="form-check-label" for="flexCheckDefault">Fuel</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">&nbsp;</div>
            <div class="col-md-4">&nbsp;</div>
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $tax_from_tire_disposal_chk = '';
                    if(!empty($wooptionextrataxes->tax_from_tire_disposal)){
                        $tax_from_tire_disposal_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_from_tire_disposal" <?php echo $tax_from_tire_disposal_chk; ?> >
                    <span class="form-check-label" for="flexCheckDefault">Tire Disposal</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label" for="reference">Tax Percentage</label>
                    <div class="form-input-frame">
                        <?php 
                        $tax_percentage = '0.00%';
                        if(!empty($wooptionextrataxes->tax_percentage)){
                            $tax_percentage = number_format($wooptionextrataxes->tax_percentage, 2).'%';
                        }
                        echo $this->Form->control('tax_percentage', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%', 'id'=>'tax_percentage', 'value'=>$tax_percentage)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <?php
                    $always_charge_customer_not_taxable_chk = '';
                    if(!empty($wooptionextrataxes->always_charge_customer_not_taxable)){
                        $always_charge_customer_not_taxable_chk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="always_charge_customer_not_taxable" <?php echo $always_charge_customer_not_taxable_chk; ?> >
                    <span class="form-check-label" for="flexCheckDefault">Always charge, even if customer is not taxable</span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label" for="reference">Minimum Amount to Charge</label>
                    <div class="form-input-frame">
                        <?php 
                        $minimum_amount_to_charge = '0.00%';
                        if(!empty($wooptionextrataxes->minimum_amount_to_charge)){
                            $minimum_amount_to_charge = number_format($wooptionextrataxes->minimum_amount_to_charge, 2).'%';
                        }

                        echo $this->Form->control('minimum_amount_to_charge', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%', 'id'=>'minimum_amount_to_charge', 'value'=>$minimum_amount_to_charge)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">&nbsp;</div>
        </div>
        <div class="col-md-12">
            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label" for="reference">Maximum Amount to Charge</label>
                    <div class="form-input-frame">
                        <?php 
                        $maximum_amount_to_charge = '$0.00';
                        if(!empty($wooptionextrataxes->maximum_amount_to_charge)){
                            $maximum_amount_to_charge = '$'.number_format($wooptionextrataxes->maximum_amount_to_charge, 2);
                        }

                        echo $this->Form->control('maximum_amount_to_charge', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%', 'id'=>'maximum_amount_to_charge', 'value'=>$maximum_amount_to_charge)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">&nbsp;</div>
        </div>
    </fieldset>

<?php echo $this->Form->end(); ?>