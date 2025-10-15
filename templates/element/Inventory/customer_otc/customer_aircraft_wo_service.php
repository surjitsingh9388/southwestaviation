<?php $sessionUser = $this->request->getSession()->read('Auth');; ?>
<section class="top-form-section">
    <?php
    $aircraftwoitemservices = !empty($aircraftwoitemservices) ? $aircraftwoitemservices : null;
    echo $this->Form->create($aircraftwoitemservices, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWorkOrderItemServices'));
    ?>
    <input type="hidden" name="wo_services_id" id="wo_services_id" value="<?php echo @$aircraftwoitemservices->id; ?>" />
    <input type="hidden" name="service_wo_item_id" id="wo_services_item_id" value="<?php echo @$aircraftwoitemservices->wo_item_id; ?>" />

    <div class="row">
        <div class="col-md-3 col-sm-12 col-xs-12">
            <div class="col-md-12 col-sm-12  col-xs-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Technicians</label>
                </div>

                <div class="form-group listitems" id="technician-add-item">
                    <?php
                    $isdisabled = [];
                    $disabledchkbox = '';
                    if(isset($technicianData) && count($technicianData) > 0){
                        $i=0;
                        foreach($technicianData as $user_id=>$technician){
                            $selectedclass = '';
                            if(!empty($repair_technician_id)){
                                if($repair_technician_id == $user_id){
                                    $selectedclass = 'wo-service-technician-list-active';
                                }
                            }else{
                                if($i==0){
                                    $selectedclass = 'wo-service-technician-list-active';
                                }
                            }
                            $i++;
                    ?>
                    <div class="wo-service-technician-list <?php echo $selectedclass; ?>" data-val="<?php echo $user_id; ?>"><?php echo $technician; ?></div>
                    <?php 
                    }}else{
                        $isdisabled['disabled'] = 'disabled';
                        $disabledchkbox = 'disabled';
                    } 
                    if($aircraftwoitems->wo_item_status == '3'){
                        $disabledchkbox = 'disabled';
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-12 col-sm-12">
                <button type="button" class="btn btn-default services_add_technician_btn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>New</button>
                <button type="button" class="btn btn-default deleteWOServicesBtn" <?php echo $disabledchkbox; ?>>Delete</button>
            </div>
        </div>

        <div class="col-md-9 col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="reference">Service Info</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Repair Technician</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('repair_technician_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', $isdisabled, 'id'=>'repair_technician_name', 'readonly'=>'readonly')); ?>
                                <input type="hidden" name="repair_technician" id="repair_technician" value="<?php echo @$aircraftwoitemservices->repair_technician; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Rate an Hour</label>
                            <div class="form-input-frame">
                                <?php 
                                $rateanhourreadonly = true; // default readonly
                                if (!empty($aircraftwoitemservices->technician_billing_style) && $aircraftwoitemservices->technician_billing_style == '2') {
                                    $rateanhourreadonly = false; // make editable
                                }

                                $service_rate_an_hour = !empty($aircraftwoitemservices->service_rate_an_hour) 
                                    ? '$' . number_format($aircraftwoitemservices->service_rate_an_hour, 2) 
                                    : '$0.00';

                                echo $this->Form->control('service_rate_an_hour', [
                                    'type' => 'text',
                                    'class' => 'form-control',
                                    'label' => false,
                                    'autocomplete' => 'off',
                                    'placeholder' => '$0.00',
                                    'readonly' => $rateanhourreadonly, // ✅ boolean, not string
                                    'id' => 'service_rate_an_hour',
                                    'value' => $service_rate_an_hour
                                ]);
                                ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Add Time</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('service_add_time', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', $disabledchkbox, 'value'=>'')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Technician Billing Style</label>
                            <div class="form-input-frame">
                                <?php
                                $technicianBillingStyle = unserialize(TECHNICIANBILLINGSTYLE);
                                
                                echo $this->Form->control('technician_billing_style', array('options' => $technicianBillingStyle, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'technician_billing_style', $isdisabled));
                                ?> 
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php
                            $is_lead_tech_on_itemchk = '';
                            if(isset($aircraftwoitemservices->is_lead_tech_on_item) && !empty($aircraftwoitemservices->is_lead_tech_on_item)){
                                $is_lead_tech_on_itemchk = 'checked';
                            }
                            ?>
                            <input type="checkbox" name="is_lead_tech_on_item" value="1" <?php echo $is_lead_tech_on_itemchk.' '.$disabledchkbox; ?> />&nbsp;Is Lead Tech on Item
                        </div>
                        <div class="form-group">
                            <?php
                            $currently_on_overtimechk = '';
                            if(isset($aircraftwoitemservices->currently_on_overtime) && !empty($aircraftwoitemservices->currently_on_overtime)){
                                $currently_on_overtimechk = 'checked';
                            }
                            ?>
                            <input type="checkbox" name="currently_on_overtime" id="woitem_services_currently_on_overtime" value="1" <?php echo $currently_on_overtimechk.' '.$disabledchkbox; ?> />&nbsp;Currently on Overtime
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default woservicenotebtn" <?php echo $disabledchkbox; ?> >Notes</button>
                        </div>
                        <?php 
                        $starttimerdisable = 'disabled';
                        if(@$aircraftwoitemservices->repair_technician == $sessionUser['id'] || $sessionUser['role_id'] == '1'){ 
                            $starttimerdisable = '';
                        } 
                        ?>
                        <div class="form-group">
                            <input type="hidden" name="is_timer_start" id="is_timer_start" value="<?php echo @$aircraftwoitemservices->login_time; ?>" />
                            
                            <?php
                            if(!empty($aircraftwoitemservices->login_time)){ ?>
                                <button type="button" class="btn btn-success woitem-start-timer-btn woitem-start-service-timer" <?php echo $starttimerdisable; ?>>Stop Timer</button>
                            <?php }else{ ?>
                                <button type="button" class="btn btn-success woitem-start-timer-btn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>  <?php echo $starttimerdisable; ?>>Start Timer</button>
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            Time is <b class="starttimestatus"><?php echo (!empty($aircraftwoitemservices->login_time)) ? 'ACTIVE' : 'NOT ACTIVE'; ?></b>
                            <?php if(!empty($aircraftwoitemservices->login_time)){ ?>
                                <p id="woitem-loggedin-msg">Logged in at <?php echo date('h:i A', strtotime($aircraftwoitemservices->login_time)); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Time Summary</legend>
                            <div class="">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Override Hrs.</label>
                                            <div class="form-input-frame">
                                                <?php 
                                                $service_override_hrs = '0.00';
                                                if(!empty($aircraftwoitemservices->service_override_hrs)){
                                                    $service_override_hrs = number_format($aircraftwoitemservices->service_override_hrs, 2);
                                                }
                                                echo $this->Form->control('service_override_hrs', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly', 'value'=>$service_override_hrs)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Hrs. Worked</label>
                                            <div class="form-input-frame">
                                                <?php 
                                                $hrs_worked = '0.00';
                                                if(!empty($aircraftwoitemservices->hrs_worked)){
                                                    $hrs_worked = number_format($aircraftwoitemservices->hrs_worked, 2);
                                                }
                                                echo $this->Form->control('hrs_worked', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly', 'id'=>'woitem_technician_hrs_worked', 'value'=>$hrs_worked)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Overtime Hrs.</label>
                                            <div class="form-input-frame">
                                                <?php 
                                                $service_overtime_hrs = '0.00';
                                                if(!empty($aircraftwoitemservices->service_overtime_hrs)){
                                                    $service_overtime_hrs = number_format($aircraftwoitemservices->service_overtime_hrs, 2);
                                                }
                                                echo $this->Form->control('service_overtime_hrs', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly', 'id'=>'woitem_service_overtime_hrs', 'value'=>$service_overtime_hrs)); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Estimated Hrs. for Item</label>
                                            <div class="form-input-frame">
                                                <?php 
                                                $estimated_hrs_for_item = !empty($aircraftwoitemservices->estimated_hrs_for_item) ? number_format($aircraftwoitemservices->estimated_hrs_for_item, 2) : '0.00';

                                                echo $this->Form->control('estimated_hrs_for_item', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly', 'id'=>'woitem_estimated_hrs_for_item', 'value'=>$estimated_hrs_for_item)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Total Hrs. for Tech</label>
                                            <div class="form-input-frame">
                                                <?php 
                                                $total_hrs_for_tech = '0.00';
                                                if(!empty($aircraftwoitemservices->total_hrs_for_tech)){
                                                    $total_hrs_for_tech = number_format($aircraftwoitemservices->total_hrs_for_tech, 2);
                                                }
                                                echo $this->Form->control('total_hrs_for_tech', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly', 'id'=>'woitem_total_hrs_for_tech', 'value'=>$total_hrs_for_tech)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Total Hrs. for Item</label>
                                            <div class="form-input-frame">
                                                <?php 
                                                $total_hrs_for_item = '0.00';
                                                if(!empty($aircraftwoitemservices->total_hrs_for_item)){
                                                    $total_hrs_for_item = number_format($aircraftwoitemservices->total_hrs_for_item, 2);
                                                }
                                                echo $this->Form->control('total_hrs_for_item', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly', 'id'=>'woitem_total_hrs_for_item', 'value'=>$total_hrs_for_item)); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</section>
<script>
    var saveAircraftWOServicesTechnicianURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftWOServicesTechnician',]); ?>";
</script>
