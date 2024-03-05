<section class="top-form-section">
    <?php
    echo $this->Form->create($wooptionbillinginfoes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOptBillingInfo'));
    ?>
    <input type="hidden" name="work_order_id" id="billing_info_work_order_id" value="<?php echo $work_order_id; ?>" />
    <input type="hidden" name="wo_item_id" id="billing_info_wo_item_id" value="<?php echo $wo_item_id; ?>" />
    <input type="hidden" name="option_billing_info_id" id="option_billing_info_id" value="<?php echo @$wooptionbillinginfoes->id; ?>" />
    <?php $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '' ?>

    <div class="row">
        <div class="col-md-12">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Billing Rate</legend>
                <?php
                $billing_rate_disp = 'none';
                $aircraft_rate_disp = 'none';
                if(!empty($wooptionbillinginfoes->billing_rate_method) && $wooptionbillinginfoes->billing_rate_method == '1'){
                    $billing_rate_disp = '';
                }else if(!empty($wooptionbillinginfoes->billing_rate_method) && $wooptionbillinginfoes->billing_rate_method == '2'){
                    $aircraft_rate_disp = '';
                }
                ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">Billing Rate Method</label>
                        <div class="form-input-frame">
                            <?php
                            $billRateMethod = unserialize(WOOPTIONBILLINGRATEMETHOD);
                            
                            echo $this->Form->control('billing_rate_method', array('options' => $billRateMethod, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id'=>'option_billing_rate_method'));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 technician_rate_block" style="display:<?php echo $billing_rate_disp; ?>;">
                    <div class="form-group">
                        <label class="control-label" for="reference">< Min. Hours Rate</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('min_hour_rate', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 technician_rate_block" style="width:100%;display:<?php echo $billing_rate_disp; ?>;"><div class="form-group">&nbsp;</div></div>

                <div class="col-md-6 aircraft_rate_block" style="display:<?php echo $aircraft_rate_disp; ?>;">
                    <div class="form-group">
                        <label class="control-label" for="reference">Aircraft Rate Method</label>
                        <div class="form-input-frame">
                            <?php
                            $woOptAircraftMethod = unserialize(WOOPTIONAIRCRAFTMETHOD);
                            
                            echo $this->Form->control('aircraft_rate_method', array('options' => $woOptAircraftMethod, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 aircraft_rate_block" style="display:<?php echo $aircraft_rate_disp; ?>;">
                    <div class="form-group">
                        <label class="control-label" for="reference">Rate/Hour</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('aircraft_rate_hour', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <?php
                    $use_special_rate_hrs_chk = '';
                    if(!empty($wooptionbillinginfoes->use_special_rate_hrs)){
                        $use_special_rate_hrs_chk = 'checked';
                    }
                    ?>
                    <input type="checkbox" name="use_special_rate_hrs" value="1" <?php echo $use_special_rate_hrs_chk; ?> />&nbsp;Use Special Rate / Hr.
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('use_special_rate_amount', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-input-frame">&nbsp;</div>
                    <p style="color:red;">"Contract Raets" are being used for this aircraft</p>
                </div>
                
            </fieldset>
        </div>
        <div class="col-md-12 pd0">
            <div class="col-md-7">
                <div class="form-group">
                    <label class="control-label" for="reference">Default Bill-to-Customer</label>
                    <div class="form-input-frame">
                        <?php
                        $billtocustomer = [$inventorycustomers['id']=>$inventorycustomers['customer_name']];
                        
                        echo $this->Form->control('default_bill_to_customer', array('options' => $billtocustomer, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-5">&nbsp;</div>
        </div>
        <div class="col-md-12 pd0">
            <div class="col-md-7">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Reset Bill-To on All Items</legend>
                    <p>This will reset the "Bill-to" on all items to the default bill-to customer</p>
                    <p><button type="button" class="btn btn-default col-md-12 wo-option-billing-info-reset">Reset</button></p>
                </fieldset>
            </div>
            <div class="col-md-5">
                <p><button type="button" class="btn btn-default col-md-12 reset-estimated-rate-to-current-rate">Reset Estimated Rate to Current Rate</button></p>
                <p><button type="button" class="btn btn-default col-md-12 set-all-items-specific-dept">Set All Items Specific Department</button></p>
            </div>
        </div>
        <div class="col-md-12">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Accounting</legend>
                <div class="col-md-7">
                    <div class="form-group">
                        <label class="control-label" for="reference">Type</label>
                        <div class="form-input-frame">
                            <?php
                            $billingType = unserialize(WOOPTIONBILLINGTYPE);
                            echo $this->Form->control('accounting_type', array('options' => $billingType, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">&nbsp;</div>
                <div class="col-md-7">&nbsp;</div>
                <div class="col-md-5">
                    <p><button type="button" class="btn btn-default col-md-12 wo-option-billing-clear-integ-log">Clear Integration Log</button></p>
                </div>
            </fieldset>
        </div>
        <div class="col-md-12 pd0">
            <div class="col-md-7">
                <div class="form-group">
                    <label class="control-label" for="reference">Customer Currency Format Override</label>
                    <div class="form-input-frame">
                        <?php
                        $currencyoverride = unserialize(CURRENCYOVERRIDE);
                        echo $this->Form->control('customer_currency_format_override', array('options' => $currencyoverride, 'empty' => '', 'class' => 'form-control selectpicker  col-md-7', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_grouping'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-5">&nbsp;</div>
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-primary float-right saveWOOptionBillingInfo" <?php echo $isdisabled; ?>>Save</button>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</section>
<script>
    var saveWOViewOptionBillingInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOViewOptionBillingInfo']); ?>";
</script>