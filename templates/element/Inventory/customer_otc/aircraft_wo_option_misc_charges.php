<section class="top-form-section">
    <?php
    echo $this->Form->create($wooptionmiscchargs, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOptMiscCharges'));
    ?>
    <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>">
    <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>">
    <input type="hidden" name="misc_charges_id" id="misc_charges_id" value="<?php echo @$wooptionmiscchargs->id; ?>">
    <?php $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '' ?>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">EPA Charge</legend>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        $epa_charge_chk = '';
                        if(@$wooptionmiscchargs->epa_charge == '1'){
                            $epa_charge_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" value="1" name="epa_charge" <?php echo $epa_charge_chk; ?> />&nbsp;EPA Charge
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php
                            $epa_charge_twin_chk = '';
                            if(@$wooptionmiscchargs->epa_charge_twin == '1'){
                                $epa_charge_twin_chk = 'checked';
                            }
                            ?>
                            <input type="checkbox" value="1" name="epa_charge_twin" <?php echo $epa_charge_twin_chk; ?> />&nbsp;Twin
                        </div>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Amount (Single)</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $epa_charge_amount = '';
                                    if(!empty($wooptionmiscchargs->epa_charge_amount)){
                                        $epa_charge_amount = '$'.number_format($wooptionmiscchargs->epa_charge_amount, 2);
                                    }

                                    echo $this->Form->control('epa_charge_amount', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'epa_charge_amount', 'value'=>$epa_charge_amount)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </fieldset>
           
         
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Oil Analysis</legend>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        $oil_analysis_chk = '';
                        if(@$wooptionmiscchargs->oil_analysis == '1'){
                            $oil_analysis_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" value="1" name="oil_analysis" <?php echo $oil_analysis_chk; ?> />&nbsp;Oil Analysis
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php
                            $oil_analysis_twin_chk = '';
                            if(@$wooptionmiscchargs->oil_analysis_twin == '1'){
                                $oil_analysis_twin_chk = 'checked';
                            }
                            ?>
                            <input type="checkbox" value="1" name="oil_analysis_twin" <?php echo $oil_analysis_twin_chk; ?> />&nbsp;Twin
                        </div>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Amount (Single)</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $oil_analysis_amount = '';
                                    if(!empty($wooptionmiscchargs->oil_analysis_amount)){
                                        $oil_analysis_amount = '$'.number_format($wooptionmiscchargs->oil_analysis_amount, 2);
                                    }

                                    echo $this->Form->control('oil_analysis_amount', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'oil_analysis_amount', 'value'=>$oil_analysis_amount)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </fieldset>
           
          
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Tire Disposal</legend>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        $tire_disposal_chk = '';
                        if(@$wooptionmiscchargs->tire_disposal == '1'){
                            $tire_disposal_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" value="1" name="tire_disposal" <?php echo $tire_disposal_chk; ?> />&nbsp;Tire Disposal
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="reference"># Tires</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('tire', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="control-label" for="reference">Amount Per Tire</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $amount_per_tire = '';
                                    if(!empty($wooptionmiscchargs->amount_per_tire)){
                                        $amount_per_tire = '$'.number_format($wooptionmiscchargs->amount_per_tire, 2);
                                    }
                                    echo $this->Form->control('amount_per_tire', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'amount_per_tire', 'value'=>$amount_per_tire)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                </fieldset>
           
        </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
          
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Misc. Charges</legend>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        $mis_charge_chk = '';
                        if(@$wooptionmiscchargs->mis_charge == '1'){
                            $mis_charge_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" value="1" name="mis_charge" <?php echo $mis_charge_chk; ?> />&nbsp;Misc. Charges
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Amount</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $mis_charge_amount = '';
                                    if(!empty($wooptionmiscchargs->mis_charge_amount)){
                                        $mis_charge_amount = '$'.number_format($wooptionmiscchargs->mis_charge_amount, 2);
                                    }
                                    
                                    echo $this->Form->control('mis_charge_amount', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'mis_charge_amount', 'value'=>$mis_charge_amount)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </fieldset>
         
           
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Pilot Services</legend>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        $pilot_services_chk = '';
                        if(@$wooptionmiscchargs->pilot_services == '1'){
                            $pilot_services_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" value="1" name="pilot_services" <?php echo $pilot_services_chk; ?> />&nbsp;Pilot Services
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Amount</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $pilot_services_amount = '';
                                    if(!empty($wooptionmiscchargs->pilot_services_amount)){
                                        $pilot_services_amount = '$'.number_format($wooptionmiscchargs->pilot_services_amount, 2);
                                    }
                                    
                                    echo $this->Form->control('pilot_services_amount', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'pilot_services_amount', 'value'=>$pilot_services_amount)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </fieldset>
           
        
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Tax Credit</legend>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        $tax_credit_chk = '';
                        if(@$wooptionmiscchargs->tax_credit == '1'){
                            $tax_credit_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" value="1" name="tax_credit" <?php echo $tax_credit_chk; ?> />&nbsp;Tax Credit
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Amount</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $tax_credit_amount = '';
                                    if(!empty($wooptionmiscchargs->tax_credit_amount)){
                                        $tax_credit_amount = '$'.number_format($wooptionmiscchargs->tax_credit_amount, 2);
                                    }
                                    
                                    echo $this->Form->control('tax_credit_amount', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'tax_credit_amount', 'value'=>$tax_credit_amount)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">&nbsp;</div>
                </fieldset>
           
        </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
            
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Shop Supplies</legend>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        $shop_supplies_chk = '';
                        if(@$wooptionmiscchargs->shop_supplies == '1'){
                            $shop_supplies_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" value="1" name="shop_supplies" <?php echo $shop_supplies_chk; ?> />&nbsp;Shop Supplies
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Method</label>
                            <div class="form-input-frame">
                                <?php
                                $woOptionMiscChargeMethod = unserialize(WOOPTIONMISCCHARGEMETHOD);
                                echo $this->Form->control('shop_supplies_method', array('options' => $woOptionMiscChargeMethod, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                ?>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Amount</label>
                            <div class="form-input-frame">
                                <?php 
                                $shop_supplies_amount = '';
                                if(!empty($wooptionmiscchargs->shop_supplies_amount)){
                                    $shop_supplies_amount = '$'.number_format($wooptionmiscchargs->shop_supplies_amount, 2);
                                }

                                echo $this->Form->control('shop_supplies_amount', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'shop_supplies_amount', 'value'=>$shop_supplies_amount)); ?>
                            </div>
                        </div>
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Percentage of Labor</label>
                            <div class="form-input-frame">
                                <?php
                                $percentage_of_labor_disable = '';
                                if(@$wooptionmiscchargs->shop_supplies_method == '1'){
                                    $percentage_of_labor_disable = 'disabled';
                                }
                                $woOptionPercentageOfLabor = unserialize(WOOPTIONPERCENTAGEOFLABOR);
                                echo $this->Form->control('percentage_of_labor', array('options' => $woOptionPercentageOfLabor, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'percentage_of_labor', 'disabled'=>$percentage_of_labor_disable));
                                ?>
                            </div>
                        </div>
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-8" for="reference">Break-off Amount</label>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('break_off_amount', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
                            </div>
                        </div>
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-8" for="reference"><-Break-off %</label>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('break_off_percentage', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%')); ?>
                            </div>
                        </div>
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-8" for="reference">Above Break-off %</label>
                            <div class="col-md-4">
                                <?php echo $this->Form->control('above_break_off_percentage', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%')); ?>
                            </div>
                        </div>
                    </div>
                    
                </fieldset>
          
          
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Fuel</legend>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        $charge_for_fuel_chk = '';
                        if(@$wooptionmiscchargs->charge_for_fuel == '1'){
                            $charge_for_fuel_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" value="1" name="charge_for_fuel" <?php echo $charge_for_fuel_chk; ?> />&nbsp;Charge for Fuel
                    </div>
                    <div class="col-md-12 col-xs-12 col-sm-12" style="padding:0px;">
                        <div class="col-md-2" style="padding:0px;">
                            <div class="form-group">
                                <label class="control-label" for="reference">Gallons</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('fuel_gallons', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12">
                            <label class="control-label" for="reference">&nbsp;</label>
                            <div class="form-group">&times;</div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12" style="padding:0px;">
                            <div class="form-group">
                                <label class="control-label" for="reference">Price/Gallon</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('fuel_price', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'fuel_price')); ?>
                                </div>
                            </div>
                        </div>
                       <div class="col-md-1 col-sm-1 col-xs-12">
                            <label class="control-label" for="reference">&nbsp;</label>
                            <div class="form-group">=</div>
                        </div>
                        <div class="col-md-1 col-sm-1 col-xs-12" style="padding:0px;">
                            <div class="form-group">
                                <label class="control-label" for="reference">Total</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $total_charges = 0;
                                    if(!empty($wooptionmiscchargs->fuel_gallons) && !empty($wooptionmiscchargs->fuel_price)){
                                        $total_charges = $wooptionmiscchargs->fuel_gallons*$wooptionmiscchargs->fuel_price;
                                    }

                                    echo $this->Form->control('fuel_total_charges', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'value'=>$total_charges, 'id'=>'fuel_total_charges', 'id'=>'fuel_total_charges')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label" for="reference">&nbsp;</label>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary saveWOOptionMiscFuelCharges">Add</button>
                            </div>
                        </div>
                    </div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Gallons</th>
                                    <th scope="col">Price (Per Gallon)</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody class="wo-misc-fuel-charges-list">
                                <?php
                                if(!empty($wooptionmiscfuelchargs)){
                                    $fuelchargestr = '';
                                    foreach($wooptionmiscfuelchargs as $fuelcharges){
                                        $fuelchargestr .= '<tr class="wo-mis-fuel-charges" data-val="'.$fuelcharges['id'].'">';
                                        $fuelchargestr .= '<td>'.number_format($fuelcharges['gallon'], 2).'</td>';
                                        $fuelchargestr .= '<td>'.'$'.number_format($fuelcharges['price'], 2).'</td>';
                                        $fuelchargestr .= '<td>'.'$'.number_format(($fuelcharges['gallon']*$fuelcharges['price']), 2).'</td>';
                                        $fuelchargestr .= '<td><i class="fa fa-times remove-wo-misc-fuel-charges" title="Delete fuel charges" data-val="'.$fuelcharges['id'].'" style="cursor:pointer;"></i></td>';
                                        $fuelchargestr .= '</tr>';
                                    }

                                    echo $fuelchargestr;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </fieldset>
                
                <div class="col-md-12 col-sm-12 colxs-12 pd0">
                    <div class="form-group ml-11 mr-11"> 
                        <label class="control-label" for="reference">Misc. Charges Description for Invoice</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->input('misc_charge_description_for_invoice', array('type' => 'textarea', 'class'=>'form-control', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'misc_charge_description_for_invoice')); ?>
                        </div>
                    </div>
               
            </div>
        </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
            <button type="button" class="btn btn-primary float-right saveWOOptionMiscCharges" <?php echo $isdisabled; ?>>Save</button>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</section>
<script>
    var saveWOViewOptionMiscChargesURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOViewOptionMiscCharges']); ?>";
    var saveWOViewOptionMiscFuelChargesURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOViewOptionMiscFuelCharges']); ?>";
    var deleteWOViewOptionMiscFuelChargesURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteWOViewOptionMiscFuelCharges']); ?>";
</script>