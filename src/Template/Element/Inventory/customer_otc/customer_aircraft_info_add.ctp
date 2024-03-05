<section class="top-form-section" id="aircraft_info_add_section">    
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label" for="reference">List of Aircraft <span class="required">*</span>
                </label>
            </div>

            <div class="form-group listitems listofaircraft">
                <?php
                $aircraft_id = '';
                $airfcraftregnumber = '';
                foreach($aircraftregdet as $aircraftreg){
                    $aircraftregbox_active = '';
                    if(!empty($customerotcaircrafts->id) && $customerotcaircrafts->id == $aircraftreg['id'])
                    {
                        $aircraftregbox_active = 'aircraftregbox-active';
                        $aircraft_id = $aircraftreg['id'];
                        $airfcraftregnumber = $aircraftreg['aircraft_registration_number'];
                    }
                ?>
                <div class="col-md-12 aircraftregbox <?php echo $aircraftregbox_active; ?>" data-val="<?php echo $aircraftreg['id']; ?>"><?php echo $aircraftreg['aircraft_registration_number']; ?></div>
                <?php } ?>
            </div>
            
        </div>

        <div class="col-md-9">
            <?php echo $this->Form->create($customerotcaircrafts, ['class' => 'form-horizontal form-label-left', 'id' => 'frmUpdateCustomerOTCAircrafts', 'autocomplete'=>'off']); ?>
            <input type="hidden" name="aircraft_id" id="aircraft_id" value="<?php echo $aircraft_id; ?>" />
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Reg. Number</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('aircraft_reg_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'value'=>$airfcraftregnumber)); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="reference">Serial</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('aircraft_serial', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <?php
                            $aircraft_fleet_ac_chk = !empty($customerotcaircrafts->aircraft_is_owner) ? 'checked' : '';
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="aircraft_fleet_ac" <?php echo $aircraft_fleet_ac_chk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">
                            Fleet A/C
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <?php
                            $aircraft_is_owner_chk = !empty($customerotcaircrafts->aircraft_is_owner) ? 'checked' : '';
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckChecked" name="aircraft_is_owner" <?php echo $aircraft_is_owner_chk; ?>>
                            <span class="form-check-label" for="flexCheckChecked">
                            Is Owner
                            </span>
                        </div>
                    </div>
                </div>
                    
                <div class="col-md-3">
                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">Make</label>
                        <div class="form-input-frame">
                            <?php
                            echo $this->Form->control('aircraft_make_id', array('options' => $aircraftmakes, 'empty' => 'Select Make', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'aircraft_make_id'));
                            ?>
                        </div>
                    </div>

                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">Class</label>
                        <div class="form-input-frame">
                            <?php
                            echo $this->Form->control('aircraft_class', array('options' => [], 'empty' => 'Select Class', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'aircraft_class'));
                            ?>
                        </div>
                    </div>

                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">Aircraft Rate</label>
                        <div class="form-input-frame">
                            <?php
                            $aircraftRate = unserialize(AIRCRAFTRATE);
                            echo $this->Form->control('aircraft_rate', array('options' => $aircraftRate, 'empty' => 'Select Aircraft Rate', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'aircraft_rate'));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">Model</label>
                        <div class="form-input-frame">
                            <?php
                            echo $this->Form->control('aircraft_model_id', array('options' => $aircraftmodels, 'empty' => 'Select Model', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'aircraft_model_id'));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="reference">Location</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('aircraft_location', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <span>
                            <?php
                            $aircraft_labor_discount_chk = !empty($customerotcaircrafts->aircraft_labor_discount) ? 'checked' : '';
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="aircraft_labor_discount" <?php echo $aircraft_labor_discount_chk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">
                            Labor Discount
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('aircraft_labor_discount_percentage', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%')); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">Year</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('aircraft_year', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="reference">Parts Programs</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('aircraft_parts_program', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <span>
                            <?php
                            $aircraft_part_discount_chk = !empty($customerotcaircrafts->aircraft_part_discount) ? 'checked' : '';
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="aircraft_part_discount" <?php echo $aircraft_part_discount_chk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">
                            Parts Discount
                            </span>
                        </span>
                    </div>
                    <div class="form-group">
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('aircraft_part_discount_percentage', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%')); ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">Engine Type</label>
                        <div class="form-input-frame">
                            <?php
                            $aircraftEngineType = unserialize(AIRCRAFTENGINETYPE);
                            echo $this->Form->control('aircraft_engine_type', array('options' => $aircraftEngineType, 'empty' => 'Select Engine Type', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'aircraft_engine_type'));
                            ?>
                        </div>
                    </div>

                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">Fuel Code</label>
                        <div class="form-input-frame">
                            <?php
                            echo $this->Form->control('aircraft_fuel_code', array('options' => [], 'empty' => 'Select Fuel Code', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'aircraft_fuel_code'));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="reference">Do not tax:</label>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <?php
                            $aircraft_labor_chk = !empty($customerotcaircrafts->aircraft_labor) ? 'checked' : '';
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="aircraft_labor" <?php echo $aircraft_labor_chk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">Labor</span>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-check">
                            <?php
                            $aircraft_hide_on_upcoming_report_chk = !empty($customerotcaircrafts->aircraft_hide_on_upcoming_report) ? 'checked' : '';
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="aircraft_hide_on_upcoming_report" <?php echo $aircraft_hide_on_upcoming_report_chk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">
                            Hide on Upcomming Reports
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <?php
                            $aircraft_use_contract_pricing_chk = !empty($customerotcaircrafts->aircraft_use_contract_pricing) ? 'checked' : '';
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="user_contract_pricing" name="aircraft_use_contract_pricing" <?php echo $aircraft_use_contract_pricing_chk; ?>>
                            <span class="form-check-label" for="user_contract_pricing">
                            Use Contract Pricing
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php
                        $viewcontractpricebtn = 'disabled';
                        if(!empty($customerotcaircrafts->aircraft_use_contract_pricing)){
                            $viewcontractpricebtn = '';
                        }
                        ?>
                        <button type="button" class="btn btn-default contractpricesbtn fetchCustOTCPopup" data-val='aircraft_view_contract_price_btn' <?php echo $viewcontractpricebtn; ?>>View Contract Prices</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-check">
                            <?php
                            $aircraft_part_discount_over_cost_chk = !empty($customerotcaircrafts->aircraft_part_discount_over_cost) ? 'checked' : '';
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="aircraft_part_discount_over_cost" <?php echo $aircraft_part_discount_over_cost_chk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">
                            Part Discount Is % Over Cost
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <?php
                            $aircraft_use_specific_rate_hrs_chk = !empty($customerotcaircrafts->aircraft_use_specific_rate_hrs) ? 'checked' : '';
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="aircraft_use_specific_rate_hrs" <?php echo $aircraft_use_specific_rate_hrs_chk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">
                            Use Specific Rate/Hr.
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('aircraft_use_specific_rate_hrs_val', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <div class="form-check">
                            <?php
                            $aircraft_parts_chk = !empty($customerotcaircrafts->aircraft_parts) ? 'checked' : '';
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="aircraft_parts" <?php echo $aircraft_parts_chk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">Parts</span>
                        </div>
                    </div>
                    <div class="form-group d-flex"></div>
                    <div class="form-group">
                        <button type="button" class="btn btn-default fetchCustOTCPopup" data-val='customer_aircraft_info_more_btn'>More...</button>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <button type="button" class="btn btn-default addaircrafttolist fetchCustOTCPopup" data-val='add_new_aircraft_btn'>Add</button>
            <button type="button" class="btn btn-default deleteAircraftInfoBtn" disabled>Remove</button>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-default customer_aircraft_maintenance">Maintenance</button>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-default fetchCustOTCPopup" data-val='cust_otc_aircraft_compliance'>Compliance</button>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-default aircraft_create_new_wo_btn">Create W/O</button>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-default fetchCustOTCPopup" disabled>Fuel Discounts</button>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-primary updateAircraftInfoBtn">Save</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 aircraft-work-order-history">
            <label>Work Order / Service Quote History</label>
            <table class="table table-bordered quote-table-height">
                <thead>
                    <tr>
                        <th>Work Order</th>
                        <th>Date Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($aircraftworkorderdata as $workorder){
                    ?>
                    <tr class="aircraftworkordertr" data-val="<?php echo $workorder['id']; ?>">
                        <td><?php echo $workorder['work_order_no']; ?></td>
                        <td><?php echo $workorder['created_at']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <button type="button" class="btn btn-default fetchCustOTCPopup" data-val='aircraft_upload_media_btn'>Media</button>
        </div>

        <div class="col-md-6 aircraft-work-order-history">
            <label>Scheduled Events</label>
            <table class="table table-bordered quote-table-height">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Service</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php echo $this->element('Inventory/customer_otc/aircraft_work_order_ajax_url'); ?>