<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>
<section class="top-form-section">
    <?php
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
                                <?php echo $this->Form->control('service_rate_an_hour', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'readonly'=>'readonly')); ?>
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
                        if(@$aircraftwoitemservices->repair_technician == $sessionUser['id']){ 
                            $starttimerdisable = '';
                        } 
                        ?>
                        <div class="form-group">
                            <input type="hidden" name="is_timer_start" id="is_timer_start" value="<?php echo @$aircraftwoitemservices->is_timer_start; ?>" />
                            
                            <?php
                            $time_start_datetime = '';
                            if(!empty($aircraftwoitemservices->is_timer_start) && $aircraftwoitemservices->is_timer_start == '1'){
                                $time_start_datetime = @$aircraftwoitemservices->time_start_datetime;
                            }
                            ?>
                            <input type="hidden" name="time_start_datetime" id="woitem-timerstarttime" value="<?php echo $time_start_datetime; ?>" />

                            <?php if(!empty($aircraftwoitemservices->is_timer_start) && $aircraftwoitemservices->is_timer_start == '1'){ ?>
                                <button type="button" class="btn btn-success woitem-start-timer-btn woitem-start-service-timer" <?php echo $starttimerdisable; ?>>Stop Timer</button>
                            <?php }else{ ?>
                                <button type="button" class="btn btn-success woitem-start-timer-btn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>  <?php echo $starttimerdisable; ?>>Start Timer</button>
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            Time is <b class="starttimestatus"><?php echo (!empty($aircraftwoitemservices->is_timer_start) && $aircraftwoitemservices->is_timer_start == '1') ? 'ACTIVE' : 'NOT ACTIVE'; ?></b>
                            <?php if(!empty($aircraftwoitemservices->time_start_datetime) && $aircraftwoitemservices->is_timer_start == '1'){ ?>
                                <p id="woitem-loggedin-msg">Logged in at <?php echo date('h:i A', strtotime($aircraftwoitemservices->time_start_datetime)); ?></p>
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
                                                <?php echo $this->Form->control('service_override_hrs', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly', 'value'=>@$aircraftwoitemservices->service_override_hrs)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Hrs. Worked</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('hrs_worked', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly', 'id'=>'woitem_technician_hrs_worked', 'value'=>@$aircraftwoitemservices->hrs_worked)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Overtime Hrs.</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('service_overtime_hrs', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly', 'id'=>'woitem_service_overtime_hrs', 'value'=>@$aircraftwoitemservices->service_overtime_hrs)); ?>
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
                                                $estimated_hrs_for_item = @$aircraftwoitemservices->estimated_hrs_for_item;
                                                echo $this->Form->control('estimated_hrs_for_item', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly', 'id'=>'woitem_estimated_hrs_for_item', 'value'=>$estimated_hrs_for_item)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Total Hrs. for Tech</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('total_hrs_for_tech', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly', 'id'=>'woitem_total_hrs_for_tech', 'value'=>@$aircraftwoitemservices->total_hrs_for_tech)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Total Hrs. for Item</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('total_hrs_for_item', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly', 'id'=>'woitem_total_hrs_for_item', 'value'=>@$aircraftwoitemservices->total_hrs_for_item)); ?>
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