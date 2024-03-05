<section class="top-form-section">
    <?php
    echo $this->Form->create($wooptionpricinginfoes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOptPricingInfo'));
    ?>
    <div class="row">
        <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
        <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
        <input type="hidden" name="pricing_info_id" id="pricing_info_id" value="<?php echo @$wooptionpricinginfoes->id; ?>" />
        <?php $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '' ?>

        <div class="col-md-12">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Labor Discount Info</legend>
                <div class="col-md-12">
                    <p>This labor discount information is applied when printing the actual invoice and applies to all service items.</p>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                        $labor_discount_chk = '';
                        if(@$wooptionpricinginfoes->labor_discount == '1'){
                            $labor_discount_chk = 'checked';
                        }
                        ?>
                        <input type="checkbox" name="labor_discount" value="1" <?php echo $labor_discount_chk; ?> />&nbsp;Labor % Discount
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('labor_discount_percentage', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="reference">Flat Discount Amount</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('flat_discount_amount', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-md-12">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Parts Discount Info</legend>
                <div class="col-md-12">
                    <p>This part discount information is applied when printing the actual invoice and applies to all parts, regardless of their individual discounts.</p>
                </div>
                <div class="col-md-12">
                    <?php
                        $parts_user_dealer_price_chk = '';
                        if(@$wooptionpricinginfoes->parts_user_dealer_price == '1'){
                            $parts_user_dealer_price_chk = 'checked';
                        }
                    ?>
                    <input type="checkbox" name="parts_user_dealer_price" value="1" <?php echo $parts_user_dealer_price_chk; ?> />&nbsp;Use Dealer Prices
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                            $parts_discount_chk = '';
                            if(@$wooptionpricinginfoes->parts_discount == '1'){
                                $parts_discount_chk = 'checked';
                            }
                        ?>
                        <input type="checkbox" name="parts_discount" value="1" <?php echo $parts_discount_chk; ?> />&nbsp;Part % Discount
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('parts_discount_percentage', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="reference">Flat Discount Amount</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('parts_flat_discount_amount', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <?php
                        $discount_over_cost_chk = '';
                        if(@$wooptionpricinginfoes->discount_over_cost == '1'){
                            $discount_over_cost_chk = 'checked';
                        }
                    ?>
                    <input type="checkbox" name="discount_over_cost" value="1" <?php echo $discount_over_cost_chk; ?> />&nbsp;Discount is % Over Cost
                </div>
                <div class="col-md-12">
                    <?php
                        $donot_use_markup_formula_chk = '';
                        if(@$wooptionpricinginfoes->donot_use_markup_formula == '1'){
                            $donot_use_markup_formula_chk = 'checked';
                        }
                    ?>
                    <input type="checkbox" name="donot_use_markup_formula" value="1" <?php echo $donot_use_markup_formula_chk; ?> />&nbsp;Do not use mark-up formula
                </div>
            </fieldset>
        </div>
        <div class="col-md-12">
            <?php
                $donot_charge_shipping_chk = '';
                if(@$wooptionpricinginfoes->donot_charge_shipping == '1'){
                    $donot_charge_shipping_chk = 'checked';
                }
            ?>
            <input type="checkbox" name="donot_charge_shipping" value="1" <?php echo $donot_charge_shipping_chk; ?> />&nbsp;Do not charge shipping
        </div>
        <div class="col-md-12">
            <?php
                $is_internal_bill_at_cost_chk = '';
                if(@$wooptionpricinginfoes->is_internal_bill_at_cost == '1'){
                    $is_internal_bill_at_cost_chk = 'checked';
                }
            ?>
            <input type="checkbox" name="is_internal_bill_at_cost" value="1" <?php echo $is_internal_bill_at_cost_chk; ?> />&nbsp;Is Internal - Ball at Costs
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-primary float-right saveWOOptionPricingInfo" <?php echo $isdisabled; ?>>Save</button>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</section>
<script>
    var saveWOViewOptionPricingInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOViewOptionPricingInfo']); ?>";
</script>