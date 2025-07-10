<section class="top-form-section">
    <?php
    echo $this->Form->create($customerotcinfoes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmCustomerOTCInfo', 'autocomplete' => 'off'));
    ?>
    <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $inventorycustomers->id; ?>" />

    <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12 ">
            <div class="form-group">
                <label class="control-label" for="otcinfo_customer_name">Customer ID</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('otcinfo_customer_name', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'otcinfo_customer_name', 'value'=>$inventorycustomers->customer_name, 'readonly'=>'readonly')); ?>
                </div>
            </div>

            <!--div class="form-group">
                <label class="control-label" for="reference">Resale</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('resale', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div-->

            <!--div class="form-group">
                <label class="control-label" for="reference">Name on Credit Card</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('name_on_credit_card', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div-->
            <div class="form-group">
                <div class="form-check">
                    <?php
                    $percentage_off_over_costchk1 = '';
                    $percentage_off_over_costchk2 = '';
                    if(!empty($customerotcinfoes->percentage_off_over_cost)){
                        if($customerotcinfoes->percentage_off_over_cost == '1'){
                            $percentage_off_over_costchk1 = 'checked';
                        }else if($customerotcinfoes->percentage_off_over_cost == '2'){
                            $percentage_off_over_costchk2 = 'checked';
                        }
                    }
                    ?>
                    <input class="form-check-input" type="radio" value="1" id="flexCheckChecked" name="percentage_off_over_cost" <?php echo $percentage_off_over_costchk1; ?>>
                    <span class="form-check-label" for="flexCheckChecked">
                    % Off
                    </span>&nbsp;&nbsp;

                    <input class="form-check-input" type="radio" value="2" id="flexCheckChecked" name="percentage_off_over_cost" <?php echo $percentage_off_over_costchk2; ?>>
                    <span class="form-check-label" for="flexCheckChecked">
                    % Over Cost
                    </span>
                </div>
            </div>

            <div class="form-group">
                <div class="form-input-frame">
                    <?php 
                    $percentage_off_over_cost_val = '';
                    if(!empty($customerotcinfoes->percentage_off_over_cost_val)){
                        $percentage_off_over_cost_val = $customerotcinfoes->percentage_off_over_cost_val.'%';
                    }
                    echo $this->Form->control('percentage_off_over_cost_val', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%', 'id'=>'percentage_off_over_cost_val', 'value'=>$percentage_off_over_cost_val)); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Max Outstanding Amount</label>
                <div class="form-input-frame">
                    <?php 
                    $max_outstanding_amount = '';
                    if(!empty($customerotcinfoes->max_outstanding_amount)){
                        $max_outstanding_amount = '$'.$customerotcinfoes->max_outstanding_amount;
                    }
                    echo $this->Form->control('max_outstanding_amount', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'max_outstanding_amount', 'value'=>$max_outstanding_amount)); ?>
                </div>
            </div>
        </div>

         <div class="col-md-4 col-sm-6 col-xs-12 ">
            <div class="form-group">
                <label class="control-label" for="reference">Terms</label>
                <div class="form-input-frame">
                    <?php
                    $customerterms = unserialize(CUSTOMERTERMS);
                    echo $this->Form->control('terms', array('options' => $customerterms, 'empty' => 'Select Terms', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'sales_rep'));
                    ?>
                </div>
            </div>

            <!--div class="form-group">
                <label class="control-label" for="reference">Account</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('account', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div-->

            <!--div class="form-group">
                <label class="control-label" for="reference">Type of Credit Card</label>
                <div class="form-input-frame">
                    <?php
                    $typeOfOTCCreditCard = unserialize(TYPEOFOTCCREDITCARD);
                    echo $this->Form->control('type_of_credit_card', array('options' => $typeOfOTCCreditCard, 'empty' => 'Select type of Credit Card', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'type_of_credit_card'));
                    ?>
                </div>
            </div-->

            <div class="form-group">
                <label class="control-label" for="reference">Default Shipping Method</label>
                <div class="form-input-frame">
                    <?php
                    $defaultOTCShippingMethod = unserialize(DEFAULTOTCSHIPPINGMETHOD);
                    echo $this->Form->control('default_shipping_method', array('options' => $defaultOTCShippingMethod, 'empty' => 'Select Default Shipping Method', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'default_shipping_method'));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <?php
                    $use_max_outstanding_amount_limitchk = '';
                    if(!empty($customerotcinfoes->use_max_outstanding_amount_limit)){
                        $use_max_outstanding_amount_limitchk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="use_max_outstanding_amount_limit" <?php echo $use_max_outstanding_amount_limitchk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">Use Max Outstanding Amount Limit</span>
                </div>
            </div>
        </div>
            
        <div class="col-md-4 col-sm-6 col-xs-12 ">
            <!--div class="form-group">
                <label class="control-label label-heading-left" for="reference">Username</label>
                <?php
                $otcinfo_username_disablechk = '';
                if(!empty($customerotcinfoes->otcinfo_username_disable)){
                    $otcinfo_username_disablechk = 'checked';
                }
                ?>
                <span class="label-chkbox-right">
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="otcinfo_username_disable" <?php echo $otcinfo_username_disablechk; ?>>
                    <span class="form-check-label" for="flexCheckDefault">Disable</span>
                </span>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('otcinfo_username', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div-->

            <!--div class="form-group d-flex">
                <label class="control-label" for="reference">Default Payment Method</label>
                <div class="form-input-frame">
                    <?php
                    $defaultPaymentMethod = unserialize(DEFAULTPAYMENTMETHOD);
                    echo $this->Form->control('default_payment_method', array('options' => $defaultPaymentMethod, 'empty' => 'Select Default Payment Method', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'default_payment_method'));
                    ?>
                </div>
            </div-->

            <!--div class="form-group d-flex">
                <label class="control-label" for="reference">CC Info - Last 4 Only</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('credit_card_info', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'credit_card_info')); ?>
                </div>
            </div-->

            <div class="form-group d-flex">
                <label class="control-label" for="reference">Default Ship To</label>
                <div class="form-input-frame">
                    <?php
                    $defaultShipTo = ['1'=>'Billing Address', '2'=>'Shipping Address', '3'=>'Other'];
                    echo $this->Form->control('default_ship_to', array('options' => $defaultShipTo, 'empty' => '', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'default_ship_to'));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <?php
                    $show_notes_otc_createchk = '';
                    if(!empty($customerotcinfoes->show_notes_otc_create)){
                        $show_notes_otc_createchk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="show_notes_otc_create" name="show_notes_otc_create" <?php echo $show_notes_otc_createchk; ?>>
                    <span class="form-check-label" for="show_notes_otc_create">Show Notes on OTC Create</span>
                </div>
            </div>
            
        </div>

      <div class="col-md-4 col-sm-6 col-xs-12 ">
            <!--div class="form-group">
                <label class="control-label" for="reference">Password</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('otcinfo_password', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div-->
            
            <!--div class="form-group d-flex">
                <label class="control-label" for="reference">Class</label>
                <div class="form-input-frame">
                    <?php
                    $customerOTCClass = unserialize(CUSTOMEROTCCLASS);
                    echo $this->Form->control('otcinfo_class', array('options' => $customerOTCClass, 'empty' => 'Select Class', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'otcinfo_class'));
                    ?>
                </div>
            </div-->

            <!--div class="form-group">
                <label class="control-label" for="reference">Expires</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('credit_card_expires', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div-->

            <div class="form-group">
                <label class="control-label" for="reference">Total Amount Spent</label>
                <div class="form-input-frame">
                    <?php 
                    $total_amount_spent = '';
                    if(!empty($customerotcinfoes->total_amount_spent)){
                        $total_amount_spent = '$'.$customerotcinfoes->total_amount_spent;
                    }
                    echo $this->Form->control('total_amount_spent', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'total_amount_spent', 'value'=>$total_amount_spent)); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label" for="reference">Outstanding Amount Due</label>
                <div class="form-input-frame">
                    <?php 
                    $outstanding_amount_due = '';
                    if(!empty($customerotcinfoes->outstanding_amount_due)){
                        $outstanding_amount_due = '$'.$customerotcinfoes->outstanding_amount_due;
                    }
                    echo $this->Form->control('outstanding_amount_due', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'outstanding_amount_due', 'value'=>$outstanding_amount_due)); ?>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12 ">
            <div class="form-group">
                <label class="control-label" for="reference">Discount Price Level</label>
                <div class="form-input-frame">
                    <?php
                    $pricelevel = ['1'=>'Level 1', '2'=>'Level 2', '3'=>'Level 3'];
                    echo $this->Form->control('discount_price_level', array('options' => $pricelevel, 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'discount_price_level'));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <?php
                    $use_discount_price_levelchk = '';
                    if(!empty($customerotcinfoes->use_discount_price_level)){
                        $use_discount_price_levelchk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="use_discount_price_level" name="use_discount_price_level" <?php echo $use_discount_price_levelchk; ?>>
                    <span class="form-check-label" for="use_discount_price_level">Use Discount Price Levels</span>
                </div>
            </div>

            <div class="form-group mt10">
                <button type="button" class="btn btn-primary saveOTCInfoBtn">Save</button>

                <button type="button" class="btn btn-default createotcinvoicebtn fetchCustOTCPopup" data-val='create_otc_invoice_btn' <?php if(empty($customerotcinfoes->id)){ ?> disabled <?php } ?> title="Save OTC Info and then create invoice">Create OTC Invoice</button>
            </div>
        </div>

        <div class="col-md-8">
            <div class="form-group">
                <span style="font-weight: bold;">OTC Invoice Parts History</span>
                <span style="float:right;">
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="country_on_printout">
                    <span class="form-check-label" for="flexCheckDefault">Show Quotes</span>
                </span>
                <div style="height:150px; width:100%; overflow:scroll;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>OTC Invoice</th>
                                <th>Part Number</th>
                                <th>Description</th>
                                <th>QTY</th>
                                <th>Serial</th>
                            </tr>
                        </thead>
                        <tbody id="otcinfoinvoicehisttbl">
                            <?php echo $invoiceparthisttblrow; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</section>
<script>
    var saveCustomerOTCInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveCustomerOTCInfo']); ?>";
    var saveCustomerOTCInfoInvoiceURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveCustomerOTCInfoInvoice']); ?>";
    var saveCustomerOTCInfoInvoicePartURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveCustomerOTCInfoInvoicePart']); ?>";
</script>