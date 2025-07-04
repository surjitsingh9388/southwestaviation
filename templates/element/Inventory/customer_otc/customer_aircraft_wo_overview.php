<section class="top-form-section">
    <?php
    echo $this->Form->create($aircraftwoitemoverviews, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWorkOrderItemOverviews'));
    ?>
    <div class="row">
        <input type="hidden" name="wo_overviews_id" id="wo_overviews_id" value="<?php echo @$aircraftwoitemoverviews->id; ?>" />
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label" for="reference">Category</label>
                <div class="form-input-frame">
                    <?php
                    $aircraftWOCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);
                    $wo_category = isset($aircraftwoitemoverviews->wo_category) && !empty($aircraftwoitemoverviews->wo_category) ? $aircraftwoitemoverviews->wo_category : '7';

                    echo $this->Form->control('wo_category', array('options' => $aircraftWOCategory, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_category', 'value'=>$wo_category));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label" for="reference">Grouping</label>
                <div class="form-input-frame">
                    <?php
                    echo $this->Form->control('wo_grouping', array('options' => [], 'empty' => 'Select Grouping', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_grouping'));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label" for="reference">Labor Kit Name</label>
                <div class="form-input-frame">
                    <?php 
                    $labor_kit = isset($aircraftwoitemoverviews['wo_labor_kits']['labor_kit']) ? $aircraftwoitemoverviews['wo_labor_kits']['labor_kit'] : '';

                    echo $this->Form->control('labor_kit', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'overview_labor_kit', 'value'=>$labor_kit)); ?>
                    <?php echo $this->Form->control('labor_kit_name', array('type'=>'hidden', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'overview_labor_kit_name')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label" for="reference"><span class="text-red">Owner Authorization</span></label>
                <div class="form-input-frame">
                    <input type="hidden" id="owner_authentication_old" value="<?php echo @$aircraftwoitemoverviews->owner_authentication; ?>" />
                    <?php
                    $authrization = ['1'=>'Open', '2'=>'Yes', '3'=>'No'];
                    echo $this->Form->control('owner_authentication', array('options' => $authrization, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'owner_authentication'));
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-3" style="clear: left;">
             <div class="form-group">
                <label class="control-label" for="reference">Warranty</label>
                <div class="form-input-frame">
                    <?php
                    $warrantywoarr = unserialize(WOITEMOVERVIEWWARRANTY);
                    echo $this->Form->control('warranty', array('options' => $warrantywoarr, 'empty' => 'Select Warranty', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'warranty'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php
                $item_is_warrantychk = '';
                if(isset($aircraftwoitemoverviews->item_is_warranty) && !empty($aircraftwoitemoverviews->item_is_warranty)){
                    $item_is_warrantychk = 'checked';
                }
                ?>
                <input type="checkbox" name="item_is_warranty" value="1" <?php echo $item_is_warrantychk; ?>>&nbsp;Item is Warranty
            </div>
        </div>
        <div class="col-md-3">
           
            <div class="form-group">
                <label class="control-label" for="reference">Warranty Claim No.</label>
                <div class="form-input-frame">
                    <?php echo $this->Form->control('warranty_claim_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            
            <div class="form-group">
                <label class="control-label" for="reference">Log Book Category</label>
                <div class="form-input-frame">
                    <?php
                    $aircraftWOLogBookCategory = unserialize(AIRCRAFT_WORKORDER_LOGBOOK_CATEGORY);
                    echo $this->Form->control('log_book_category', array('options' => $aircraftWOLogBookCategory, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'log_book_category'));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php
                $donot_use_inlogbookchk = '';
                if(!empty($aircraftwoitemoverviews->donot_use_inlogbook)){
                    $donot_use_inlogbookchk = 'checked';
                }
                ?>
                <input type="checkbox" name="donot_use_inlogbook" value="1" <?php echo $donot_use_inlogbookchk; ?> />&nbsp;Do not use in Log Book
            </div>
        </div>
        <div class="col-md-3">
            
            <div class="form-group">
                <label class="control-label" for="reference">ATA Code</label>
                <div class="form-input-frame">
                    <?php 
                    $ata_code_name = isset($aircraftwoitemoverviews['wo_ata_codes']['ata_code']) ? $aircraftwoitemoverviews['wo_ata_codes']['ata_code'] : '';

                    echo $this->Form->control('ata_code_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'overview_ata_code', 'value'=>$ata_code_name)); ?>
                    <?php echo $this->Form->control('ata_code', array('type'=>'hidden', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'overview_ata_code_id')); ?>
                </div>
            </div>
            <div class="form-group">
                <?php
                $requires_riichk = '';
                if(!empty($aircraftwoitemoverviews->requires_rii)){
                    $requires_riichk = 'checked';
                }
                ?>
                <input type="checkbox" name="requires_rii" value="1" <?php echo $requires_riichk; ?>>&nbsp;Requires II
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Billing Information</legend>
                <div class="row">
                    <?php
                    $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '';
                    ?>
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="reference">Way of Billing</label>
                                <div class="form-input-frame">
                                    <?php
                                    $way_of_billing = isset($aircraftwoitemoverviews->way_of_billing) && !empty($aircraftwoitemoverviews->way_of_billing) ? $aircraftwoitemoverviews->way_of_billing : '1';

                                    $aircraftWOWayofBilling = unserialize(AIRCRAFT_WORKORDER_WAYOF_BILLING);
                                    echo $this->Form->control('way_of_billing', array('options' => $aircraftWOWayofBilling, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'way_of_billing', 'value'=>$way_of_billing, 'disabled'=>$isdisabled));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="reference">Department</label>
                                <div class="form-input-frame">
                                    <?php
                                    $contractRateDepartment = unserialize(CONTRACT_RATE_DEPARTMENT);
                                    echo $this->Form->control('department', array('options' => $contractRateDepartment, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'department', 'disabled'=>$isdisabled));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="reference">Bill-To Customer</label>
                                <div class="form-input-frame">
                                    <?php
                                    $billtocustomer = [];
                                    $billtocustomer[$inventorycustomers['id']] = $inventorycustomers['customer_name'];
                                    echo $this->Form->control('bill_to_customer', array('options' => $billtocustomer, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'bill_to_customer', 'disabled'=>$isdisabled));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="reference">Shipping In</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $shipping_in = !empty($aircraftwoitemoverviews->shipping_in) ? '$'.number_format($aircraftwoitemoverviews->shipping_in, 2) : '$0.00';
                                    echo $this->Form->control('shipping_in', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'disabled'=>$isdisabled, 'id'=>'overview_shipping_in', 'value'=>$shipping_in)); 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="reference">Special Rate / Hr</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $special_rate_hr = !empty($aircraftwoitemoverviews->special_rate_hr) ? '$'.number_format($aircraftwoitemoverviews->special_rate_hr, 2) : '$0.00';
                                    echo $this->Form->control('special_rate_hr', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'disabled'=>$isdisabled, 'id'=>"overview_special_rate_hr", 'value'=>$special_rate_hr)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="reference">Estimated Hrs</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $estimated_hour = !empty($aircraftwoitemoverviews->estimated_hour) ? number_format($aircraftwoitemoverviews->estimated_hour, 2) : '0.00';

                                    echo $this->Form->control('estimated_hour', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'disabled'=>$isdisabled, 'value'=>$estimated_hour)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="reference">Estimated Rate</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $estimated_rate = !empty(@$aircraftwoitemoverviews->estimated_rate) ? '$'.number_format(@$aircraftwoitemoverviews->estimated_rate, 2) : '$'.number_format(ESTIMATEDRATE, 2);
                                    echo $this->Form->control('estimated_rate', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'value'=>$estimated_rate, 'disabled'=>$isdisabled, 'id'=>'overview_estimated_rate')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="reference">Flat Rate</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $flat_rate = !empty($aircraftwoitemoverviews->flat_rate) ? '$'.number_format($aircraftwoitemoverviews->flat_rate, 2) : '$0.00';
                                    echo $this->Form->control('flat_rate', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'disabled'=>$isdisabled, 'id'=>'overview_flat_rate', 'value'=>$flat_rate)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label" for="reference">Flat Rate Qty</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('flat_rate_qty', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'disabled'=>$isdisabled)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-10">
                            <div class="form-group">
                                <?php
                                $special_hourly_rate_for_itemchk = '';
                                if(!empty($aircraftwoitemoverviews->special_hourly_rate_for_item)){
                                    $special_hourly_rate_for_itemchk = 'checked';
                                }
                                ?>
                                <input type="checkbox" name="special_hourly_rate_for_item" value="1" <?php echo $special_hourly_rate_for_itemchk.' '.$isdisabled; ?> >&nbsp;Use Special Hour Rate for Item
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?php
                                $labor_is_taxablechk = '';
                                if(!empty($aircraftwoitemoverviews->labor_is_taxable)){
                                    $labor_is_taxablechk = 'checked';
                                }
                                ?>
                                <input type="checkbox" name="labor_is_taxable" value="1" <?php echo $labor_is_taxablechk.' '.$isdisabled; ?>>&nbsp;Labor is Taxable
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</section>