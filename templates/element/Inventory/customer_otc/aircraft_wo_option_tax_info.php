<section class="top-form-section">
    <?php
    echo $this->Form->create($wooptiontaxinfoes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOptTaxInfo'));
    ?>
    <input type="hidden" name="work_order_id" id="tax_info_work_order_id" value="<?php echo $work_order_id; ?>" />
    <input type="hidden" name="wo_item_id" id="tax_info_wo_item_id" value="<?php echo $wo_item_id; ?>" />
    <input type="hidden" name="option_tax_info_id" id="option_tax_info_id" value="<?php echo @$wooptiontaxinfoes->id; ?>" />
    <?php $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '' ?>

    <div class="row">
        <div class="col-md-12">
            <input type="checkbox" name="taxable" value="1" />&nbsp;Taxable
        </div>
        <div class="col-md-12">
            <div class="col-md-7">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Tax Information</legend>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="reference">Tax Method</label>
                            <div class="form-input-frame">
                                <?php
                                $woOptTaxMethod = unserialize(WOOPTIONTAXMETHOD);
                                echo $this->Form->control('tax_method', array('options' => $woOptTaxMethod, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="reference">Tax Rate</label>
                            <div class="form-input-frame">
                                <?php 
                                $tax_rate = '$0.00';
                                if(!empty($wooptiontaxinfoes->tax_rate)){
                                    $tax_rate = '$'.number_format($wooptiontaxinfoes->tax_rate, 2);
                                }

                                echo $this->Form->control('tax_rate', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%', 'id'=>'tax_rate', 'value'=>$tax_rate)); ?>
                            </div>
                        </div>
                    </div>
                    
                </fieldset>
                <div class="col-md-12">
                    <button type="button" class="btn btn-default col-md-5 wo-option-taxinfo-extra-taxes" <?php echo $isdisabled; ?>>Extra Taxes</button>
                </div>
            </div>
            <div class="col-md-5">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Tax Items</legend>
                    <div class="col-md-6">
                        <?php
                        $tax_item_labor_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_labor)){
                            $tax_item_labor_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_labor" value="1" <?php echo $tax_item_labor_chk; ?> />&nbsp;Labor
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_oil_analysis_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_oil_analysis)){
                            $tax_item_oil_analysis_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_oil_analysis" value="1" <?php echo $tax_item_oil_analysis_chk; ?> />&nbsp;Oil Analysis
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_parts_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_parts)){
                            $tax_item_parts_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_parts" value="1" <?php echo $tax_item_parts_chk; ?> />&nbsp;Parts
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_pilot_services_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_pilot_services)){
                            $tax_item_pilot_services_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_pilot_services" value="1" <?php echo $tax_item_pilot_services_chk; ?> />&nbsp;Pilot Services
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_labor_osr_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_labor_osr)){
                            $tax_item_labor_osr_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_labor_osr" value="1" <?php echo $tax_item_labor_osr_chk; ?> />&nbsp;Labor (OSR)
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_misc_charges_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_misc_charges)){
                            $tax_item_misc_charges_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_misc_charges" value="1" <?php echo $tax_item_misc_charges_chk; ?> />&nbsp;Misc. Charges
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_parts_osr_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_parts_osr)){
                            $tax_item_parts_osr_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_parts_osr" value="1" <?php echo $tax_item_parts_osr_chk; ?> />&nbsp;Part (OSR)
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_shop_supplies_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_shop_supplies)){
                            $tax_item_shop_supplies_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_shop_supplies" value="1" <?php echo $tax_item_shop_supplies_chk; ?> />&nbsp;Shop Supplies
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_ship_out_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_ship_out)){
                            $tax_item_ship_out_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_ship_out" value="1" <?php echo $tax_item_ship_out_chk; ?> />&nbsp;Ship Out
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_fuel_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_fuel)){
                            $tax_item_fuel_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_fuel" value="1" <?php echo $tax_item_fuel_chk; ?> />&nbsp;Fuel
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_shipin_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_shipin)){
                            $tax_item_shipin_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_shipin" value="1" <?php echo $tax_item_shipin_chk; ?> />&nbsp;Ship In
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_tire_disposal_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_tire_disposal)){
                            $tax_item_tire_disposal_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_tire_disposal" value="1" <?php echo $tax_item_tire_disposal_chk; ?> />&nbsp;Tire Disposal
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_ship_out_osr_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_ship_out_osr)){
                            $tax_item_ship_out_osr_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_ship_out_osr" value="1" <?php echo $tax_item_ship_out_osr_chk; ?> />&nbsp;Ship Out (OSR)
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_cores_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_cores)){
                            $tax_item_cores_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_cores" value="1" <?php echo $tax_item_cores_chk; ?> />&nbsp;Cores
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_shipin_osr_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_shipin_osr)){
                            $tax_item_shipin_osr_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_shipin_osr" value="1" <?php echo $tax_item_shipin_osr_chk; ?> />&nbsp;Ship In (OSR)
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_cores_credit_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_cores_credit)){
                            $tax_item_cores_credit_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_cores_credit" value="1" <?php echo $tax_item_cores_credit_chk; ?> />&nbsp;Cores (Credit)
                    </div>
                    <div class="col-md-6">
                        <?php
                        $tax_item_epa_charges_chk = '';
                        if(!empty($wooptiontaxinfoes->tax_item_epa_charges)){
                            $tax_item_epa_charges_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="tax_item_epa_charges" value="1" <?php echo $tax_item_epa_charges_chk; ?> />&nbsp;EPA Charges
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-7">&nbsp;</div>
            <div class="col-md-5">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Verify Taxed Items</legend>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default col-md-12 mark-all-shop-labor-taxable">Mark All Shop Labor Taxable</button>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default col-md-12 mark-all-osr-labor-taxable">Mark All OSR Labor Taxable</button>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default col-md-12 mark-all-shop-parts-taxable">Mark All Shop Parts Taxable</button>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default col-md-12 mark-all-osr-parts-taxable">Mark All OSR Parts Taxable</button>
                    </div>
                <fieldset>
            </div>
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-primary float-right saveWOOptionTaxInfo" <?php echo $isdisabled; ?>>Save</button>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</section>
<script>
    var saveWOViewOptionTaxInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOViewOptionTaxInfo']); ?>";
</script>