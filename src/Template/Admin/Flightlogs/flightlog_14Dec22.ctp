<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User'); 
use Cake\Routing\Router;

$formTag = '<form method="post" class="form-horizontal frmFlightLegs tabValidate form-label-left">';
$tripId = !empty($fls[0]['trip_id']) ? strtoupper($fls[0]['trip_id']) : '';
$redirectURL = BASE_URL.ROOT_DIR.'admin/flightlogs/dispatch/';

$timezone = $timezoneComp->getTimezonesList();
?>
<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>   
<div class="content sliding" id="flightLogs">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Flight Log</h2>
        </div>

        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group">
                            <h4 class="col-md-1 col-xs-12">Trip ID:</h4>
                            <div class="col-md-3 col-xs-12">
                                <input type="text" name="trip_id" class="form-control" id="tripId" maxlength="15" value="<?php echo $tripId; ?>">
                            </div>
                            <div class="col-sm-1 col-xs-12">
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <a class="trip-files" id="uploadTripPopup" data-tripid="<?php echo $tripId; ?>">View Trip Files(<span class="filesCountCls"><?php echo $tfcount; ?></span>)</a>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                            </div>
                        </div>
                    </div>

                    <div style="clear: both;padding-top: 15px;"></div>

                    <!-- Tabs --> 
                    <div id='tabs'>
                        <ul id="tabUI">
                            <?php
                            if(!empty($fls)) { 
                                $i=1;
                                foreach ($fls as $key => $value) {
                                    echo '<li class="tab'.$i.'" data-tabnum="'.$i.'" data-flid="'.$value['id'].'"><a href="#tab'.$i.'"><span class="displayFlyFrom">'. strtoupper($value['flight_from']).'</span>-<span class="displayFlyTo">'.strtoupper($value['flight_to']).'</span></a></li>';
                            
                                    $i++;
                                }
                            } else {
                                echo '<li class="tab1" data-tabnum="1" data-flid=""><a href="#tab1"><span class="displayFlyFrom">?</span>-<span class="displayFlyTo">?</span></a></li>';
                            }                   
                            ?>
                        </ul>
                        <?php 
                        if(!empty($fls)) {
                            $i=1;
                            $taxiOut = '';
                            $taxiIn  = '';
                            $takeOff = '';
                            $landing = '';
                            foreach ($fls as $key => $value) {
                                $airRecord = $reportComp->airDues($value['plane_id']);

                                $route = $value['flight_from'].'-'.$value['flight_to'];
                                $legDate = !empty($value['leg_date']) ? date('m/d/Y', strtotime($value['leg_date'])) : date('m/d/Y');
                                $taxiOut = !empty($value['flightlog_detail']['taxi_out']) ? (date('H:i', strtotime($value['flightlog_detail']['taxi_out'])) != '00:00') ? date('H:i', strtotime($value['flightlog_detail']['taxi_out'])) : '' : '';
                                $taxiIn = !empty($value['flightlog_detail']['taxi_in']) ? (date('H:i', strtotime($value['flightlog_detail']['taxi_in'])) != '00:00') ? date('H:i', strtotime($value['flightlog_detail']['taxi_in'])) : '' : '';
                                $takeOff = !empty($value['flightlog_detail']['taxi_off']) ? (date('H:i', strtotime($value['flightlog_detail']['taxi_off'])) != '00:00') ? date('H:i', strtotime($value['flightlog_detail']['taxi_off'])) : '' : '';
                                $landing = !empty($value['flightlog_detail']['landing']) ? (date('H:i', strtotime($value['flightlog_detail']['landing'])) != '00:00') ? date('H:i', strtotime($value['flightlog_detail']['landing'])) : '' : '';           
                                ?>
                                <div id="tab<?php echo $i; ?>">
                                    <form method="post" class="form-horizontal frmFlightLegs tabValidate form-label-left" data-tabnum="<?php echo $i; ?>">
                                        <input type="hidden" name="flight_from" value="<?php echo $value['flight_from']; ?>">
                                        <input type="hidden" name="flight_to" value="<?php echo $value['flight_to']; ?>">

                                        <input type="hidden" name="id" class="flid" value="<?php echo $value['id']; ?>">
                                        <input type="hidden" name="fld_id" value="<?php echo $value['flightlog_detail']['id']; ?>"> 
                                        <input type="hidden" name="plane_id" value="<?php echo $value['plane_id']; ?>">
                                        <input type="hidden" name="form_type" class="formTypeCls" value="fldetail">
                                        <input type="hidden" name="tabnum" class="tabnum" value="<?php echo $i; ?>">          
                                        <div class="flightFields">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <h2>Route: <span><?php echo strtoupper($route); ?></span></h2>
                                                    </div>
                                                </div>
                                            </div>

                                            <div style="clear: both;"></div>

                                            <!-- Aircraft info -->
                                            <div class="row" style="padding-top: 10px;">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <h4>Aircraft Information</h4>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                </div>
                                            </div>
                                            
                                            <div class="flHeading">
                                                <div class="flHElmCls">Aircraft</div>
                                                <div class="flHElmCls">Next Due Item</div>
                                                <div class="flHElmCls">ToGo</div>
                                            </div>
                                            <div class="flItemCls">
                                                <div class="flElmCls">
                                                    <div class="form-group">
                                                        <div class="col-md-10 col-sm-10 col-xs-12">
                                                            <?php echo $this->Form->control('plane_id', array('options'=>$planes, 'class'=>'form-control selectpicker airListCls', 'data-show-subtext'=>true, 'data-live-search'=>false, 'required'=>'required', 'label'=>false, 'value'=>$value['plane_id'])); ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flElmCls">
                                                    <div class="form-group">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 airDueItem">
                                                            <?php echo $airRecord['airitem']; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flElmCls">
                                                    <div class="form-group">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 airDueToGo">
                                                            <?php echo $airRecord['airtogo']; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Aircraft info -->

                                            <div style="clear: both;"></div>

                                            <!-- Crew information -->
                                            <div class="row" style="padding-top: 15px;">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <h4>Crew Information</h4>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table width="100%">
                                                    <thead style="border-bottom: 1px solid #c0c0c0;padding: 2px;">
                                                        <tr>
                                                            <th width="10%">Type</th>
                                                            <th width="15%">Crew Member</th>
                                                            <th width="15%">Pilot Flying</th>
                                                            <th width="15%">Duty Start Date</th>
                                                            <th width="15%">Duty On</th>
                                                            <th width="15%">Duty Off</th>
                                                            <th width="15%">Total Time</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="crewMemberList">
                                                        <?php
                                                        if(!empty($value['crews'])) {
                                                            foreach ($value['crews'] as $key2 => $value2) {
                                                                $pilotFlying = !empty($value2['pilot_flying']) ? '<i class="fa fa-check pilotFlying" style="color:#1ab394; display: inline;"></i>' : '--';
                                                                $pilotFlyingVal = !empty($value2['pilot_flying']) ? $value2['pilot_flying'] : '';
                                                                $dutyOn = date('H:i', strtotime($value2['duty_on']));
                                                                $dutyOff = date('H:i', strtotime($value2['duty_off']));
                                                                $totalTime = $pilotComp->crewDutyTime($dutyOn, $dutyOff);
                                                                $crewName = $pilotComp->getPilotName($value2['crew_member']);
                                                                $reqRest = !empty($value2['required_rest']) ? $value2['required_rest'] : '';
                                                                $legsApply = !empty($value2['legs_apply']) ? $value2['legs_apply'] : '';

                                                                echo '<tr><td><input type="hidden" class="crewTbCls" name="crew_id[]" value="'.$value2['id'].'"></td></tr>
                                                                    <tr class="crewRecord'.$value2['crew_member'].'">
                                                                    <td><input type="hidden" name="member_type[]" value="'.$value2['member_type'].'">'.strtoupper($value2['member_type']).'</td>
                                                                    <td class="crewRecUpdate checkCrewCls" style="color:#428bca;cursor:pointer;" data-id="'.$value2['id'].'" data-plane_id="'.$value2['plane_id'].'" data-flightlog_id="'.$value2['flightlog_id'].'" data-route="'.$route.'" data-crew_member="'.$value2['crew_member'].'"><input type="hidden" name="crew_member[]" value="'.$value2['crew_member'].'">'.$crewName.'</td>
                                                                    <td><input type="hidden" name="pilot_flying[]" value="'.$pilotFlyingVal.'">'.$pilotFlying.'</td>
                                                                    <td><input type="hidden" name="duty_start_date[]" value="'.$value2['duty_start_date'].'">'.date('m/d/Y', strtotime($value2['duty_start_date'])).'</td>
                                                                    <td><input type="hidden" class="dutyOnCls" name="duty_on[]" value="'.$dutyOn.'">'.$dutyOn.'</td>
                                                                    <td><input type="hidden" class="dutyOffCls" name="duty_off[]" value="'.$dutyOff.'">'.$dutyOff.'</td>
                                                                    <td><input type="hidden" name="required_rest[]" value="'.$reqRest.'"><input type="hidden" name="legs_apply[]" value="'.$legsApply.'">'.$totalTime.'</td>
                                                                </tr>';
                                                            }
                                                        } else {
                                                            echo '<tr class="crewRow"><td colspan="8" style="text-align: center;">No crew members have been added for this leg.</td></tr>';
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- End Crew information -->

                                            <div style="clear: both;"></div>

                                            <!-- Flight Information -->
                                            <div class="row" style="padding-top: 20px;margin-top: 15px;">
                                                <h4 class="col-md-2 col-xs-12">Flight Information</h4>
                                                <div class="col-md-4 col-xs-12">
                                                </div>
                                            </div>

                                            <div class="flightInfoCls">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Leg Start Date</label>
                                                        <?php echo $this->Form->Text('leg_date', array('class'=>'form-control datePicker keypressFT legDateCls', 'label'=>false, 'value'=>$legDate)); ?>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label># Passengers</label>
                                                        <?php echo $this->Form->control('passengers', array('class'=>'form-control passengers', 'label'=>false, 'value'=>$value['passengers'])); ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Taxi Out</label>
                                                        <?php echo $this->Form->Text('taxi_out', array('class'=>'form-control timePicker keypress taxiOutId', 'placeholder'=>'00:00', 'label'=>false, 'value'=>$taxiOut)); ?>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Take Off</label>
                                                        <?php echo $this->Form->Text('taxi_off', array('class'=>'form-control timePicker keypressFT takeOffId', 'placeholder'=>'00:00', 'label'=>false, 'value'=>$takeOff)); ?>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Take Off Timezone</label>
                                                        <?php
                                                        echo $this->Form->control('takeoff_timezone', array('options'=>$timezone, 'class'=>'form-control timezoneCls takeoffTZCls', 'label'=>false, 'value'=>$value['flightlog_detail']['takeoff_timezone']));
                                                        ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>Landing</label>
                                                        <?php echo $this->Form->Text('landing', array('class'=>'form-control timePicker keypressFT landingId', 'placeholder'=>'00:00', 'label'=>false, 'value'=>$landing)); ?>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Landing Timezone</label>
                                                        <?php
                                                        echo $this->Form->control('landing_timezone', array('options'=>$timezone, 'class'=>'form-control timezoneCls landingTZCls', 'label'=>false, 'value'=>$value['flightlog_detail']['landing_timezone']));
                                                        ?>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Taxi In</label>
                                                        <?php echo $this->Form->Text('taxi_in', array('class'=>'form-control timePicker keypress taxiInId', 'placeholder'=>'00:00', 'label'=>false, 'value'=>$taxiIn)); ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Hobbs Take Off</label>
                                                        <?php echo $this->Form->Text('takeoff_hobbs', array('class'=>'form-control landingTZCls', 'placeholder'=>'0.0', 'label'=>false, 'value'=>$value['flightlog_detail']['takeoff_hobbs'])); ?>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Hobbs Landing</label>
                                                        <?php echo $this->Form->Text('landing_hobbs', array('class'=>'form-control', 'placeholder'=>'0.0', 'label'=>false, 'value'=>$value['flightlog_detail']['landing_hobbs'])); ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>Flight Time</label>
                                                        <?php echo $this->Form->Text('flight_time', array('class'=>'form-control disabledBG flightTimeId', 'placeholder'=>'00:00', 'label'=>false, 'value'=>$value['flightlog_detail']['flight_time'], 'disabled'=>'disabled')); ?>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>&nbsp;</label>
                                                        <?php echo $this->Form->Text('flight_time_decimal', array('class'=>'form-control disabledBG ftDecimal', 'placeholder'=>'0.0', 'label'=>false, 'value'=>$value['flightlog_detail']['flight_time_decimal'], 'disabled'=>'disabled')); ?>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Block Time </label>
                                                        <?php echo $this->Form->Text('block_time_disable', array('class'=>'form-control disabledBG blockTimeId', 'placeholder'=>'00:00', 'label'=>false, 'value'=>$value['flightlog_detail']['block_time'], 'disabled'=>'disabled')); ?>
                                                        <input type="hidden" name="block_time" class="blockTimeId" value="<?php echo $value['flightlog_detail']['block_time']; ?>">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>&nbsp;</label>
                                                        <?php echo $this->Form->Text('block_time_decimal', array('class'=>'form-control disabledBG decimalBlkTime', 'placeholder'=>'0.0', 'label'=>false, 'value'=>$value['flightlog_detail']['block_time_decimal'], 'disabled'=>'disabled')); ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>Approaches</label>
                                                        <?php echo $this->Form->Text('approaches', array('class'=>'form-control', 'label'=>false, 'value'=>$value['flightlog_detail']['approaches'])); ?>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Approach Type</label>
                                                        <?php
                                                        $aproachType = ['vis'=>'VIS', 'vor'=>'VOR', 'gps'=>'GPS', 'loc'=>'LOC', 'ils'=>'ILS', 'ndb'=>'NDB'];
                                                        echo $this->Form->control('approach_type', array('options'=>$aproachType, 'class'=>'form-control col-md-6 col-xs-12', 'label'=>false, 'value'=>$value['flightlog_detail']['approach_type']));
                                                        ?>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Takeoff Type</label>
                                                        <div>
                                                            <input type="radio" name="takeoff_type" value="day" <?php if(!empty($value['flightlog_detail']['takeoff_type']) && trim($value['flightlog_detail']['takeoff_type']) == "day") { echo "checked='checked'"; } ?>> Day
                                                            <input type="radio" name="takeoff_type" value="night" <?php if(!empty($value['flightlog_detail']['takeoff_type']) && trim($value['flightlog_detail']['takeoff_type']) == "night") { echo "checked='checked'"; } ?>> Night
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Landing Type</label>
                                                        <div>
                                                            <input type="radio" name="landing_type" value="day" <?php if(!empty($value['flightlog_detail']['landing_type']) && $value['flightlog_detail']['landing_type'] == "day") { echo "checked"; } ?>> Day
                                                            <input type="radio" name="landing_type" value="night" <?php if(!empty($value['flightlog_detail']['landing_type']) && $value['flightlog_detail']['landing_type'] == "night") { echo "checked"; } ?>> Night
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Night Time</label>
                                                        <?php echo $this->Form->Text('night_time', array('class'=>'form-control', 'placeholder'=>'0.0', 'label'=>false, 'value'=>$value['flightlog_detail']['night_time'])); ?>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Inst</label>
                                                        <?php echo $this->Form->Text('inst', array('class'=>'form-control', 'placeholder'=>'0.0', 'label'=>false, 'value'=>$value['flightlog_detail']['inst'])); ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>FOB</label>
                                                        <?php echo $this->Form->Text('fuel_on_board', array('class'=>'form-control fuelKeypress fobId', 'placeholder'=>'Fuel-on-board', 'label'=>false, 'value'=>$value['flightlog_detail']['fuel_on_board'])); ?>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Fuel Purchased</label>
                                                        <?php echo $this->Form->Text('fuel_purchased', array('class'=>'form-control', 'placeholder'=>'0.00', 'label'=>false, 'value'=>$value['flightlog_detail']['fuel_purchased'])); ?>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <label>Fuel Type</label>
                                                        <?php
                                                        $fuelType = ['gals'=>'Gals', 'lbs'=>'Lbs', 'lts'=>'Lts'];
                                                        echo $this->Form->control('fuel_type', array('options'=>$fuelType, 'class'=>'form-control col-md-6 col-xs-12 approachesCls', 'label'=>false, 'value'=>$value['flightlog_detail']['fuel_type']));
                                                        ?>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Ending Fuel</label>
                                                        <?php echo $this->Form->Text('ending_fuel', array('class'=>'form-control fuelKeypress endFId', 'placeholder'=>'0', 'label'=>false, 'value'=>$value['flightlog_detail']['ending_fuel'])); ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>Starting Fuel</label>
                                                        <?php echo $this->Form->Text('fuel_total', array('class'=>'form-control startFuelId', 'placeholder'=>'0', 'label'=>false, 'value'=>$value['flightlog_detail']['fuel_total'], 'disabled'=>'disabled')); ?>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Fuel Cost</label>
                                                        <?php echo $this->Form->Text('fuel_cost', array('class'=>'form-control', 'placeholder'=>'0.00', 'label'=>false, 'value'=>$value['flightlog_detail']['fuel_cost'])); ?>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Fuel Burn</label>
                                                        <?php echo $this->Form->Text('fuel_burn', array('class'=>'form-control disabledBG fuelBurnId', 'placeholder'=>'0', 'label'=>false, 'value'=>$value['flightlog_detail']['fuel_burn'], 'disabled'=>'disabled')); ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label> Post-Leg APU Time</label>
                                                        <?php echo $this->Form->Text('apu_time', array('class'=>'form-control', 'placeholder'=>'0.0', 'label'=>false, 'value'=>$value['flightlog_detail']['apu_time'])); ?>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label> Post-Leg APU Time</label>
                                                        <?php
                                                        $legType = ['135'=>'Part 135', '91'=>'Part 91'];
                                                        echo $this->Form->control('leg_type', array('options'=>$legType, 'class'=>'form-control col-md-6 col-xs-12 legType', 'label'=>false, 'value'=>$value['leg_type']));
                                                        ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Notes</label>
                                                        <?php echo $this->Form->control('notes', array('class'=>'form-control col-md-6 col-xs-12', 'label'=>false, 'rows'=>3, 'cols'=>50, 'placeholder'=>'Notes', 'value'=>$value['notes'])); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Flight Information -->
                                            <div class="ln_solid"></div>

                                            <div class="row">
                                                <div class="manifestData">
                                                    <?php
                                                    if(!empty($value['manifest'])) {
                                                    ?>
                                                    <input type="hidden" class="mfId" name="mf_id" value="<?php echo $value['manifest']['id']; ?>">
                                                    <input type="hidden" class="mfMaxWeight" name="max_weight" value="<?php echo $value['manifest']['max_weight']; ?>">
                                                    <input type="hidden" class="mfActualWeight" name="actual_weight" value="<?php echo $value['manifest']['actual_weight']; ?>">
                                                    <input type="hidden" class="mfForwardCg" name="forward_cg" value="<?php echo $value['manifest']['forward_cg']; ?>">
                                                    <input type="hidden" class="mfActualCg" name="actual_cg" value="<?php echo $value['manifest']['actual_cg']; ?>">
                                                    <input type="hidden" class="mfAftCg" name="aft_cg" value="<?php echo $value['manifest']['aft_cg']; ?>">
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="col-md-12">
                                                    <button class="btn btn-success manifestComplete" type="button">
                                                        <i class="fa fa-file-text-o"></i> View/Edit Manifest
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php
                                $i++;
                            }
                        } else {
                        ?>
                            <!-- first tab -->
                            <div id='tab1'>
                                <form method="post" class="form-horizontal frmFlightLegs tabValidate form-label-left" data-tabnum="1">
                                    <input type="hidden" class="newTabCls" value="new">
                                    <input type="hidden" name="form_type" class="formTypeCls" value="fldetail">
                                    <input type="hidden" name="data_type" value="initial">
                                    <input type="hidden" name="tabnum" class="tabnum" value="1">
                                    <div class="flightFields">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <h2>Route: <span class="displayFlyFrom">?</span>-<span class="displayFlyTo">?</span></h2>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="clear: both;"></div>

                                        <!-- Aircraft info -->
                                        <div class="row" style="padding-top: 10px;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h4>Aircraft Information</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                        </div>
                                        
                                        <div class="flHeading">
                                            <div class="flHElmCls">Aircraft</div>
                                            <div class="flHElmCls">Next Due Item</div>
                                            <div class="flHElmCls">ToGo</div>
                                        </div>
                                        <div class="flItemCls">
                                            <div class="flElmCls">
                                                <div class="form-group">
                                                    <div style="width: 300px;">
                                                        <?php echo $this->Form->control('plane_id', array('options' => $planes, 'class' => 'form-control col-md-7 col-xs-12 airListCls', 'data-show-subtext' => true, 'data-live-search' => false, 'required' => 'required', 'label' => false)); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flElmCls">
                                                <div class="form-group">
                                                    <div class="col-md-12 col-sm-12 col-xs-12 airDueItem">
                                                        N/A
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flElmCls" style="width: 20%;">
                                                <div class="form-group">
                                                    <div class="col-md-12 col-sm-12 col-xs-12 airDueToGo">
                                                        N/A
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Aircraft info -->

                                        <div style="clear: both;"></div>

                                        <!-- Crew information -->
                                        <div class="row" style="padding-top: 15px;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h4>Crew Information</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div>
                                                        <?php 
                                                        echo $this->Form->button('<i class="fa fa-plus"></i> Add Crew Member', ['type'=>'button', 'class'=>'btn btn-sm btn-success addCrewBtn', 'style'=>'float:right;']); 
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table width="100%">
                                                <thead style="border-bottom: 1px solid #c0c0c0;padding: 2px;">
                                                    <tr>
                                                        <th width="8%">Type</th>
                                                        <th width="15%">Crew Member</th>
                                                        <th width="15%">Pilot Flying</th>
                                                        <th width="15%">Duty Start Date</th>
                                                        <th width="15%">Duty On</th>
                                                        <th width="15%">Duty Off</th>
                                                        <th width="15%">Total Time</th>
                                                        <th width="2%">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="crewMemberList">
                                                    <tr class="crewRow">
                                                        <td colspan="8" style="text-align: center;">No crew members have been added for this leg.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- End Crew information -->

                                        <div style="clear: both;"></div>

                                        <!-- Flight Information -->
                                        <div class="row" style="padding-top: 20px;margin-top: 15px;">
                                            <h4 class="col-md-2 col-xs-12">Flight Information</h4>
                                        </div>

                                        <div class="flightInfoCls">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Flying From</label>
                                                    <input type="text" name="flight_from" class="form-control flyFromCls" maxlength="4" style="text-transform:uppercase">
                                                </div>

                                                <div class="col-md-6">
                                                    <label>Flying To</label>
                                                    <input type="text" name="flight_to" class="form-control flyToCls" maxlength="4" style="text-transform:uppercase">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Leg Start Date</label>
                                                    <input type="text" name="leg_date" class="form-control datePicker keypressFT legDateCls" value="<?php echo date('m/d/Y'); ?>">
                                                </div>

                                                <div class="col-md-6">
                                                    <label># Passengers</label>
                                                    <input type="text" name="passengers" class="form-control passengers">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Taxi Out</label>
                                                    <input type="text" name="taxi_out" class="form-control timePicker keypress taxiOutId" placeholder="00:00">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Take Off</label>
                                                    <input type="text" name="taxi_off" class="form-control timePicker keypressFT takeOffId" placeholder="00:00">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Take Off Timezone</label>
                                                    <?php
                                                    echo $this->Form->control('takeoff_timezone', array('options'=>$timezone, 'class'=>'form-control timezoneCls takeoffTZCls', 'label'=>false));
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Landing</label>
                                                    <input type="text" name="landing" class="form-control timePicker keypressFT landingId" placeholder="00:00">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Landing Timezone</label>
                                                    <?php
                                                    echo $this->Form->control('landing_timezone', array('options'=>$timezone, 'class'=>'form-control timezoneCls landingTZCls', 'label'=>false));
                                                    ?>
                                                </div>

                                                <div class="col-md-6">
                                                    <label>Taxi In</label>
                                                    <input type="text" name="taxi_in" class="form-control timePicker keypress taxiInId" placeholder="00:00">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Hobbs Take Off</label>
                                                    <input type="text" name="takeoff_hobbs" class="form-control" placeholder="0.0">
                                                </div>

                                                <div class="col-md-6">
                                                    <label>Hobbs Landing</label>
                                                    <input type="text" name="landing_hobbs" class="form-control" placeholder="0.0">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Flight Time</label>
                                                    <input type="text" name="flight_time" class="form-control disabledBG flightTimeId" placeholder="00:00" disabled>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>&nbsp;</label>
                                                    <input type="text" name="flight_time_decimal" class="form-control disabledBG ftDecimal" placeholder="0.0" disabled>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Block Time </label>
                                                    <input type="text" name="block_time_disable" class="form-control disabledBG blockTimeId" placeholder="00:00" disabled>
                                                    <input type="hidden" name="block_time" class="blockTimeId">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>&nbsp;</label>
                                                    <input type="text" name="block_time_decimal" class="form-control disabledBG decimalBlkTime" placeholder="0.0" disabled>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Approaches</label>
                                                    <input type="text" name="approaches" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Approach Type</label>
                                                    <select name="approach_type" class="form-control col-md-6 col-xs-12">
                                                        <option value="vis">VIS</option>
                                                        <option value="vor">VOR</option>
                                                        <option value="gps">GPS</option>
                                                        <option value="loc">LOC</option>
                                                        <option value="ils">ILS</option>
                                                        <option value="ndb">NDB</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Takeoff Type</label>
                                                    <div>
                                                        <input type="radio" name="takeoff_type" value="day" checked> Day
                                                        <input type="radio" name="takeoff_type" value="night"> Night
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Landing Type</label>
                                                    <div>
                                                        <input type="radio" name="landing_type" value="day" checked> Day
                                                        <input type="radio" name="landing_type" value="night"> Night
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Night Time</label>
                                                    <input type="text" name="night_time" class="form-control" placeholder="0.0">
                                                </div>

                                                <div class="col-md-6">
                                                    <label>Inst</label>
                                                    <input type="text" name="inst" class="form-control" placeholder="0.0">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>FOB</label>
                                                    <input type="text" name="fuel_on_board" class="form-control fuelKeypress fobId" placeholder="Fuel-on-board">
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Fuel Purchased</label>
                                                    <input type="text" name="fuel_purchased" class="form-control" placeholder="0.00">
                                                </div>

                                                <div class="col-md-1">
                                                    <label>Fuel Type</label>
                                                    <select name="fuel_type" class="form-control col-md-6 col-xs-12 approachesCls">
                                                        <option value="gals">Gals</option>
                                                        <option value="lbs">Lbs</option>
                                                        <option value="lts">Lts</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label>Ending Fuel</label>
                                                    <input type="text" name="ending_fuel" class="form-control fuelKeypress endFId" placeholder="0">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Starting Fuel</label>
                                                    <input type="text" name="fuel_total" class="form-control disabledBG startFuelId" placeholder="0" disabled>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Fuel Cost</label>
                                                    <input type="text" name="fuel_cost" class="form-control" placeholder="0.00">
                                                </div>

                                                <div class="col-md-6">
                                                    <label>Fuel Burn</label>
                                                    <input type="text" name="fuel_burn" class="form-control disabledBG fuelBurnId" placeholder="0" disabled>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label> Post-Leg APU Time</label>
                                                    <input type="text" name="apu_time" class="form-control" placeholder="0.0">
                                                </div>

                                                <div class="col-md-6">
                                                    <label> Post-Leg APU Time</label>
                                                    <select name="leg_type" class="form-control col-md-6 col-xs-12 legType">
                                                        <option value="135">Part 135</option>
                                                        <option value="91">Part 91</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Notes</label>
                                                    <textarea name="notes" rows="3" cols="50" class="form-control col-md-6 col-xs-12" placeholder="Notes"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Flight Information -->       
                                        
                                        <div class="ln_solid"></div>

                                        <div class="row">
                                            <div class="manifestData"></div>
                                            <div class="col-md-12">
                                                <button class="btn btn-warning manifestWarning" type="button">
                                                    <i class="fa fa-warning"></i> Manifest not complete. Click here to complete
                                                </button>
                                            </div>
                                        </div>                                        
                                    </div>
                                </form>
                            </div>
                            <!-- End first tab -->
                        <?php
                        }
                        ?>
                        <!-- Aircraft Discrepancy -->
                        <div class="allBtnCls airDiscrepancy" style="padding: 30px 0px 40px 0px;">
                            <div class="col-md-12">
                                <h4>Aircraft Discrepancies</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" style="margin: 0px 10px 15px 5px;">
                                        <?php 
                                        echo $this->Form->control('plane_id', array('options' => $planes, 'empty' => 'Select Aircraft', 'class' => 'selectpicker col-md-12', 'data-show-subtext' => true, 'data-live-search' => false, 'label' => false, 'div'=>false, 'id'=>'aircraftId')); 
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" style="margin: 0px 10px 15px 5px;">
                                        <button class='btn btn-success' id='addDiscrepancy'><i class='fa fa-plus'></i> Add Aircraft Discrepancy</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" style="margin: 0px 10px 0px 15px;">
                                        <table class="table" width="100%">
                                            <tr>
                                                <th width="15%">Date</th>
                                                <th width="25%">Discrepancy</th>
                                                <th width="15%">Time</th>
                                                <th width="15%">Discovered By</th>
                                                <th width="15%">MEL</th>
                                                <th width="10%">Repair By</th>
                                                <th width="5%">--</th>
                                            </tr>
                                            <tbody id="dispListing">
                                                <tr>
                                                    <td colspan='7' style='text-align: center;'>No discrepancies found for the selected aircraft</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Aircraft Discrepancy -->

                        <!-- All buttons -->
                        <div class="row allBtnCls">
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <?php
                                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                                    echo $this->Html->link("Back to Flight Center", array('action'=>'index'), array('class' => 'btn btn-default', 'escape' => false));
                                    echo $this->Form->button('<i class="fa fa-times"></i> Delete this Leg', ['type'=>'button', 'class'=>'btn btn-default deleteLeg']);
                                    echo $this->Form->button('<i class="fa fa-plus"></i> Leg Before', ['type'=>'button', 'class'=>'btn btn-success add-before']);
                                    echo $this->Form->button('<i class="fa fa-plus"></i> Leg After', ['type'=>'button', 'class'=>'btn btn-success add-after']);
                                    echo $this->Form->button('<i class="fa fa-save"></i> Save Data for Later', ['type'=>'submit', 'class'=>'btn btn-success saveLaterCls', 'data-flstatus'=>'fldetail']);
                                    echo $this->Form->button('<i class="fa fa-check"></i> Close Leg', ['type'=>'button', 'class'=>'btn btn-success closeLegCls']);
                                    echo $this->Form->button('<i class="fa fa-plane"></i> Close Trip', ['type'=>'button', 'class'=>'btn btn-success closeTripCls']);
                                }
                                ?>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <div class="flsErrMsg"></div>
                            </div>
                        </div>
                        <!-- End all buttons -->
                    </div>
                    <!-- End Tabs -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    echo $this->element('flightlog_popup');
    echo $this->element('discrepancy_popup');

    echo $this->Html->script('/js/flightlogs');
    echo $this->Html->script('/js/discrepancy');
    echo $this->Html->script('signature/jquery.signaturepad.js');
    echo $this->Html->script('html2canvas.min');
    echo $this->Html->script('signature/json2.min');
?>

<script>    
$(document).ready(function() {
    var tripId = '<?php echo $tripId; ?>';
    var dtype = '';
    if(tripId == '') {
        dtype = '<input type="hidden" name="data_type" value="initial">';
    }
    //Tabs
    var newForm = '<?php echo $formTag; ?>';
    $("div#tabs").tabs();

    //Add after active tab
    $(document).on("click", ".add-after", function() {
        var tabCnt = $("div#tabs ul#tabUI li").length;
        var num_tabs = tabCnt + 1;
                        
        $("div#tabs ul li.ui-tabs-active").after(
            "<li class='tab"+num_tabs+"' data-tabnum='"+num_tabs+"'><a href='#tab" + num_tabs + "'><span class='displayFlyFrom'>?</span>-<span class='displayFlyTo'>?</span></a></li>"
        );
        $("div#tabs .allBtnCls").before(
            "<div id='tab" + num_tabs + "' data-tabnum='"+num_tabs+"'></div>"
        );
        
        createNewTab(tabCnt, num_tabs);
        $("div#tabs").tabs("refresh");
    });

    //Add before active tab
    $(document).on("click", ".add-before", function() {
        var tabCnt = $("div#tabs ul#tabUI li").length;
        var num_tabs = tabCnt + 1;

        $("div#tabs ul li.ui-tabs-active").before(
            "<li class='tab"+num_tabs+"' data-tabnum='"+num_tabs+"'><a href='#tab" + num_tabs + "'><span class='displayFlyFrom'>?</span>-<span class='displayFlyTo'>?</span></a></li>"
        );
        $("div#tabs .allBtnCls").before(
            "<div id='tab" + num_tabs + "' data-tabnum='"+num_tabs+"'></div>"
        );

        createNewTab(tabCnt, num_tabs);
        $("div#tabs").tabs("refresh");
    });

    //Add new tab with form
    function createNewTab(tabCnt, num_tabs) {
        //Get air due item
        var selAirId = $('#tab'+tabCnt+' .airListCls option:selected').val();
        var airDueItem = $('#tab'+tabCnt+' .airDueItem').html();
        //Get air due togo
        var airDueToGo = $('#tab'+tabCnt+' .airDueToGo').html();

        //Get crew list
        var crewList = $('#tab'+tabCnt+' .crewMemberList').html();
        //Get if selected as template 
        var isTempl = $('#tab'+tabCnt+' .checkTempCls').html();
        var isTempChk = $('#tab'+tabCnt+' input.tempChk').is(':checked');

        //New tab html
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'newTabForm']); ?>",
            data: {},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $("#tab"+num_tabs).html('<form method="post" class="form-horizontal frmFlightLegs tabValidate form-label-left" data-tabnum="'+num_tabs+'">' + dtype + obj.data);
                        $('#tab'+num_tabs+' .tabnum').val(num_tabs);
                        $('#tab'+num_tabs+' .airListCls').val(selAirId);
                        $('#tab'+num_tabs+' .airDueItem').html(airDueItem);
                        $('#tab'+num_tabs+' .airDueToGo').html(airDueToGo);
                        $('#tab'+num_tabs+' .crewMemberList').html(crewList);
                        $('#tab'+num_tabs+' .checkTempCls').html(isTempl);
                        $('#tab'+num_tabs+' input.tempChk').prop('checked', isTempChk);
                        //$('#tab'+num_tabs+' td.crewRecUpdate').attr('data-id', '');
                        $('#tab'+num_tabs+' input.crewTbCls').remove();
                    }, 500);
                }
            }                   
        });
    }

    //Display tabs route
    $(document).on("keyup", ".flyFromCls", function() {
        var tabnum = $(this).closest('.flightFields').siblings('.tabnum').val();
        var flyFrm = $(this).val();
        if(flyFrm != '') {
            //Tab button
            $('.tab'+tabnum+' .displayFlyFrom').html(flyFrm.toUpperCase());
            //Tab form
            $('#tab'+tabnum+' .displayFlyFrom').html(flyFrm.toUpperCase());
        } else {
            $('.tab'+tabnum+' .displayFlyFrom').html('?');
            $('#tab'+tabnum+' .displayFlyFrom').html('?');
        }
    });

    $(document).on("keyup", ".flyToCls", function() {
        var tabnum = $(this).closest('.flightFields').siblings('.tabnum').val();
        var flyTo = $(this).val();
        if(flyTo != '') {
            //Tab button
            $('.tab'+tabnum+' .displayFlyTo').html(flyTo.toUpperCase());
            //Tab form
            $('#tab'+tabnum+' .displayFlyTo').html(flyTo.toUpperCase());
        } else {
            $('.tab'+tabnum+' .displayFlyTo').html('?');
            $('#tab'+tabnum+' .displayFlyTo').html('?');
        }
    });  

    /*** Added Tabs ***/
    //Flight time for new tab
    $('body').on('focus',".keypressFT", function() {
        $(this).datetimepicker({
            useCurrent: false,
            format: 'HH:mm'
        }).on('dp.change', function(e) {

            var tabnum = $(this).closest('.flightFields').siblings('.tabnum').val();
            updateFlightTime(tabnum);

        });
    });

    //Block time for new tab
    $('body').on('focus',".keypress", function() {
        $(this).datetimepicker({
            useCurrent: false,
            format: 'HH:mm'
        }).on('dp.change', function(e) {
            var tabnum = $(this).closest('.flightFields').siblings('.tabnum').val();
            updateBlockTime(tabnum);
        });
    });

    //Fuel details for new tab
    $('body').on('focus',".fuelKeypress", function() {
        $(this).keyup(function() {
            var tabnum = $(this).closest('.flightFields').siblings('.tabnum').val();
            var startFuel = $('#tab'+tabnum+' .fobId').val();
            var endFuel = $('#tab'+tabnum+' .endFId').val();

            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'getFuelDetails']); ?>",
                data: {startFuel:startFuel, endFuel:endFuel},
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#tab'+tabnum+' .startFuelId').val(obj.data.startingFuel);
                        $('#tab'+tabnum+' .fuelBurnId').val(obj.data.fuelBurn);
                    } else {
                        $('#tab'+tabnum+' .startFuelId').val(0);
                        $('#tab'+tabnum+' .fuelBurnId').val(0);
                    }
                }                   
            });
        });
    });
    /*** Added Tabs ***/

    //On change timezones
    $(document).on('change', '.timezoneCls', function(e) {
        e.stopPropagation();
        e.preventDefault();

        var tabnum = $(this).closest('.flightFields').siblings('.tabnum').val();
        updateFlightTime(tabnum);
        updateBlockTime(tabnum);
    });

    function updateFlightTime(tabnum)
    {
        var leg_date         = $('#tab'+tabnum+' .legDateCls').val();
        var takeOff          = $('#tab'+tabnum+' .takeOffId').val();
        var takeoff_timezone = $('#tab'+tabnum+' .takeoffTZCls').val();
        var landing          = $('#tab'+tabnum+' .landingId').val();
        var landing_timezone = $('#tab'+tabnum+' .landingTZCls').val();

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'getFlightTime']); ?>",
            data: {leg_date:leg_date, takeOff:takeOff, takeoff_timezone:takeoff_timezone, landing:landing, landing_timezone:landing_timezone},
            async: true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#tab'+tabnum+' .flightTimeId').val(obj.data.flightTime);
                    $('#tab'+tabnum+' .ftDecimal').val(obj.data.ftDecimal);
                }
            }                   
        });
    }

    function updateBlockTime(tabnum)
    {
        var leg_date = $('#tab'+tabnum+' .legDateCls').val();
        var taxiOut  = $('#tab'+tabnum+' .taxiOutId').val();
        var taxiIn   = $('#tab'+tabnum+' .taxiInId').val();
        var takeoff_timezone = $('#tab'+tabnum+' .takeoffTZCls').val();
        var landing_timezone = $('#tab'+tabnum+' .landingTZCls').val();

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'getBlockTime']); ?>",
            data: {leg_date:leg_date, taxiOut:taxiOut, taxiIn:taxiIn, takeoff_timezone:takeoff_timezone, landing_timezone:landing_timezone},
            async: true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#tab'+tabnum+' .blockTimeId').val(obj.data.blockTime);
                    $('#tab'+tabnum+' .decimalBlkTime').val(obj.data.decimalTime);
                }
            }                   
        });
    }

    //Open add crew member popup
    $(document).on("click", ".addCrewBtn", function() {
        var tabnum = $(this).closest('.flightFields').siblings('.tabnum').val();
        var flyFrom = $('#tab'+tabnum+' .flyFromCls').val();
        var flyTo = $('#tab'+tabnum+' .flyToCls').val();
        var legInfo = 'TBD-TBD';
        if(flyFrom !='' && flyTo !='') {
            legInfo = flyFrom.toUpperCase()+'-'+flyTo.toUpperCase();
        }
        
        var currtDate = new Date();
        currtDate = moment(currtDate).format('MM/DD/YYYY');
        var airId = $(this).data('plane_id');

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'getCrewList']); ?>",
            data: {airId:airId},
            async: true,
            beforeSend: function() {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#flightLogModel .crewErrorMsg').html('');
                        $('#flightLogModel .crewListCls').html(obj.data);
                        $('#flightLogModel .crewLegInfo').html(legInfo);
                        $('#flightLogModel .legInfoCls').val(legInfo);
                        $('#flightLogModel .crewSaveBtn').data("tabnum", tabnum);
                        $('#flightLogModel .dtStartD').val(currtDate);
                        $('#flightLogModel').modal('show');
                        $('#flightLogModel .selectpicker').selectpicker('refresh');
                    }, 500);
                }
            }                   
        });        
    });

    //Crew update popup validation
    $("#flightLegFrm").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'duty_on': {
                required: true
            },   
            'duty_off': {
                required: true
            }  
        }
    });
        
    //Add crew details in the form
    $(document).on('click', '#flightLegBtn', function(e) {
        e.preventDefault();

        if($('#flightLegFrm').valid()) {
            $('#flightLegFrm .allCrewCls').val('fllog');
            var formVal = $('#flightLegFrm').serialize();

            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'displayCrewData']); ?>",
                data: formVal,
                async: true,
                beforeSend: function () {
                    $('.loader').show();
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('.loader').hide();
                        $('.crewMemberList .crewRecord'+obj.crewmem).remove();
                        $('.crewMemberList .crewRow').remove();
                        $('.crewMemberList').append(obj.data);
                        $('#flightLogModel').modal('hide');
                    } else {
                        $('.loader').hide();
                        $('#flightLogModel').modal('hide');
                    }
                }                   
            });
        }
    });

    //Initial Update Flight Leg popup
    $(document).on("click", ".initCrewRecUpdate", function() {
        var member_type = $(this).data('member_type');
        var crew_member = $(this).data('crew_member');
        var pilot_flying = $(this).data('pilot_flying');
        var duty_start_date = $(this).data('duty_start_date');
        var duty_on = $(this).data('duty_on');
        var duty_off = $(this).data('duty_off');
        var required_rest = $(this).data('required_rest');
        var legs_apply = $(this).data('legs_apply');
        var leg_info = $(this).data('leg_info');

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'crewInitialUpPopup']); ?>",
            data: {member_type:member_type, crew_member:crew_member, pilot_flying:pilot_flying, duty_start_date:duty_start_date, duty_on:duty_on, duty_off:duty_off, required_rest:required_rest, legs_apply:legs_apply, leg_info:leg_info},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#flightLogModel .crewErrorMsg').html('');
                        $('#flightLogModel .modal-body').html(obj.data);
                        $('#flightLogModel').modal('show');
                    }, 500);
                }
            }                   
        });        
    });

    //Update Flight Leg popup
    $(document).on("click", ".crewRecUpdate", function() {
        var id = $(this).data('id');
        var airId = $(this).data('plane_id');
        var flId = $(this).data('flightlog_id');
        var route = $(this).data('route');

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'crewUpdatePopup']); ?>",
            data: {id:id, airId:airId, flId:flId, route:route},
            async: true,
            beforeSend: function(){
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#flUpdateErrorMsg').html('');
                        $('#flightLogUpdateModel .modal-body').html(obj.data);
                        $('#flightLogUpdateModel').modal('show');
                    }, 500);
                }
            }                   
        });        
    });

    //Add previously crew details in the form
    $(document).on('click', '#flUpdateBtn', function(e) {
        e.preventDefault();

        var formVal = $('#flUpdateFrm').serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'displayCrewData']); ?>",
            data: formVal,
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('.crewMemberList .crewRecord'+obj.crewmem).remove();
                        $('.crewMemberList .crewRow').remove();
                        $('.crewMemberList').append(obj.data);
                        $('#flightLogUpdateModel').modal('hide');
                    }, 500);
                }
            }                   
        });
    });

    //Open initial manifest popup
    $(document).on("click", ".manifestWarning", function() {
        var tabnum = $(this).closest('.flightFields').siblings('.tabnum').val();
        var formVal = $('#tab'+tabnum+' .frmFlightLegs').serialize();

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'initManifest']); ?>",
            data: formVal,
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#manifestModel .tabnum').val(obj.tabnum);
                        $('#manifestModel .modal-body').html(obj.data);
                        $('#manifestModel').modal('show');
                    }, 500);
                }
            }                   
        });
    });

    //Add initial manifest in the form
    $(document).on('click', '#saveManifestBtn', function(e) {
        e.preventDefault();

        var formVal = $('#manifestFrm').serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'displayManifest']); ?>",
            data: formVal,
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#tab'+obj.tabnum+' .manifestData').html(obj.data);
                        $('#manifestModel').modal('hide');
                    }, 500);
                }
            }                   
        });
    });

    //Open saved manifest popup
    $(document).on("click", ".manifestComplete", function() {
        var tabnum = $(this).closest('.flightFields').siblings('.tabnum').val();
        var formVal = $('#tab'+tabnum+' .frmFlightLegs').serialize();

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'initManifest']); ?>",
            data: formVal,
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#manifestModel .tabnum').val(obj.tabnum);
                        $('#manifestModel .modal-body').html(obj.data);
                        $('#manifestModel').modal('show');
                    }, 500);
                }
            }                   
        });
    });

    //Close Leg(Save data for the leg)
    $(document).on("click", ".closeLegCls", function() {
        var tabnum  = $('#tabs li.ui-tabs-active').data('tabnum');
        var mfId    = $('#tab'+tabnum+' .mfId').val();
        var dutyOn  = $('#tab'+tabnum+' .dutyOnCls').val();
        var dutyOff = $('#tab'+tabnum+' .dutyOffCls').val();
        var taxiOut = $('#tab'+tabnum+' .taxiOutId').val();
        var takeOff = $('#tab'+tabnum+' .takeOffId').val();
        var landing = $('#tab'+tabnum+' .landingId').val();
        var taxiIn  = $('#tab'+tabnum+' .taxiInId').val();
        var legType = $('#tab'+tabnum+' .legType').val();

        var mfMaxWeight = $('#tab'+tabnum+' .mfMaxWeight').val();
        var mfActWeight = $('#tab'+tabnum+' .mfActualWeight').val();
        var mfForwardCg = $('#tab'+tabnum+' .mfForwardCg').val();
        var mfActualCg  = $('#tab'+tabnum+' .mfActualCg').val();
        var mfAftCg     = $('#tab'+tabnum+' .mfAftCg').val();

        var crewCls = $('#tab'+tabnum+' td').hasClass("checkCrewCls");
        var crewMember = $('#tab'+tabnum+' .checkCrewCls').data('crew_member');
        var that = $('#tab'+tabnum+' form.tabValidate');
        console.log(tabnum);
        console.log(legType);
        
        //Form validation
        var validateTab = that.validate({
            ignore: "input[type='text']:hidden",
            validateHiddenInputs: false,
            rules: {   
                'taxi_out': {
                    required: true
                },    
                'taxi_off': {
                    required: true
                },
                'landing': {
                    required: true
                },
                'taxi_in': {
                    required: true
                },
            }
        }).form();
        //validateTab.form();

        if (confirm('Save and close this leg?')) {
            if(that.valid()) {
                if(crewCls == false || (crewMember == '' || typeof crewMember === 'undefined')) {
                    $('#closeLegModel .modal-body').html('');
                    $('#closeLegModel .modal-body').html('Please add at least one crew member to each leg before closing.');
                    $('#closeLegModel').modal('show');
                    return false;
                } else if((dutyOn < dutyOff) && ((dutyOn > taxiOut || dutyOn > takeOff) || (dutyOff < landing || dutyOff < taxiIn))) {
                    $('#closeLegModel .modal-body').html('');
                    $('#closeLegModel .modal-body').html('Flight leg number '+tabnum+' is not covered by a valid duty time. Please check the leg times and save again.');
                    $('#closeLegModel').modal('show');
                    return false;
                } else if(landing <= takeOff) {
                    $('#closeLegModel .modal-body').html('');
                    $('#closeLegModel .modal-body').html('Your landing time must come after your take off time.');
                    $('#closeLegModel').modal('show');
                    return false;
                } else if(landing > taxiIn) {
                    $('#closeLegModel .modal-body').html('');
                    $('#closeLegModel .modal-body').html('Your taxi-in time must come after your landing time.');
                    $('#closeLegModel').modal('show');
                    return false;
                } else if(tripId != '' && mfId != '') {
                    //Check manifest
                    if(legType == 135 && ((mfMaxWeight == '' || mfMaxWeight == 0 || typeof mfMaxWeight === 'undefined') || (mfActWeight == '' || mfActWeight == 0 || typeof mfActWeight === 'undefined') || (mfForwardCg == '' || mfForwardCg == 0 || typeof mfForwardCg === 'undefined') || (mfActualCg == '' || mfActualCg == 0 || typeof mfActualCg === 'undefined') || (mfAftCg == '' || mfAftCg == 0 || typeof mfAftCg === 'undefined'))) {
                        
                        $('#closeLegModel .modal-body').html('');
                        $('#closeLegModel .modal-body').html('Please complete a manifest for each non Part91 leg of the flight.');
                        $('#closeLegModel').modal('show');
                        return false;
                    } else {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'getManifest']); ?>",
                            data: {tripId:tripId, mfId:mfId, tabnum:tabnum, legType:legType},
                            async: false,
                            success: function(response) {
                                var obj = JSON.parse(response);
                                if(obj.status == 'success') {
                                    //Ajax call to save current leg data
                                    var formData = 'close_trip=yes'+'&'+'trip_id=' + tripId +'&'+ $('#tab'+tabnum+' .frmFlightLegs').serialize();
                                    saveCloseLegData(formData);
                                } else if(legType == 135) {
                                    $('#closeLegModel .modal-body').html('');
                                    $('#closeLegModel .modal-body').html('Please complete a manifest for each non Part91 leg of the flight.');
                                    $('#closeLegModel').modal('show');
                                    return false;
                                }
                            }                   
                        });
                    }
                    
                } else if(legType == 135 && ((mfMaxWeight == '' || mfMaxWeight == 0 || typeof mfMaxWeight === 'undefined') || (mfActWeight == '' || mfActWeight == 0 || typeof mfActWeight === 'undefined') || (mfForwardCg == '' || mfForwardCg == 0 || typeof mfForwardCg === 'undefined') || (mfActualCg == '' || mfActualCg == 0 || typeof mfActualCg === 'undefined') || (mfAftCg == '' || mfAftCg == 0 || typeof mfAftCg === 'undefined'))) {

                    $('#closeLegModel .modal-body').html('');
                    $('#closeLegModel .modal-body').html('Please complete a manifest for each non Part91 leg of the flight.');
                    $('#closeLegModel').modal('show');
                    return false;
                } else {
                    //Get trip id
                    var ctrip = $('#tripId').val();
                    if(tripId != '') {
                        tripId = tripId;
                    } else if(ctrip != '') {
                        tripId = ctrip;
                    } else if(tripId == '' && ctrip == '') {
                        tripId = tripID();
                    }

                    //Ajax call to save current leg data
                    var formData = 'close_trip=yes'+'&'+'trip_id=' + tripId +'&'+ $('#tab'+tabnum+' .frmFlightLegs').serialize();
                    saveCloseLegData(formData);
                }
            }
        }
    });

    function saveCloseLegData(formData)
    {
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'saveFLSData']); ?>",
            data: formData,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    if(obj.datatype == 'initial') {
                        window.location = "flightlogSuccess/"+obj.tripid;
                    } else {
                        window.location = "../flightlogSuccess/"+obj.tripid;
                    }
                } else if(obj.status == 'failure') {
                    $('.flsErrMsg').html('<span style="color:red;">'+obj.message+'</span>');
                }
            }
        });
    }

    //Close Trip(Save data for all the legs)
    $(document).on("click", ".closeTripCls", function(e) {
        e.preventDefault();

        var flag = true;
        var tabCnt = $("div#tabs ul li").length;
        //var tabnum = '';
        //var mfId = '';
        //var taxiIn = '';
        //var takeOff = '';
        //var landing = '';
        //var crewCls = '';
        //var crewMember = '';
        //var that = '';
        if (confirm('Save and close this leg?')) {
            //var promises = [];
            $('form.frmFlightLegs').each(function() {
                var tabnum  = $(this).data('tabnum');
                var mfId    = $('#tab'+tabnum+' .mfId').val();
                var dutyOn  = $('#tab'+tabnum+' .dutyOnCls').val();
                var dutyOff = $('#tab'+tabnum+' .dutyOffCls').val();
                var taxiOut = $('#tab'+tabnum+' .taxiOutId').val();
                var takeOff = $('#tab'+tabnum+' .takeOffId').val();
                var landing = $('#tab'+tabnum+' .landingId').val();
                var taxiIn  = $('#tab'+tabnum+' .taxiInId').val();
                var legType = $('#tab'+tabnum+' .legType').val();
                
                var mfMaxWeight = $('#tab'+tabnum+' .mfMaxWeight').val();
                var mfActWeight = $('#tab'+tabnum+' .mfActualWeight').val();
                var mfForwardCg = $('#tab'+tabnum+' .mfForwardCg').val();
                var mfActualCg  = $('#tab'+tabnum+' .mfActualCg').val();
                var mfAftCg     = $('#tab'+tabnum+' .mfAftCg').val();
                
                var crewCls     = $('#tab'+tabnum+' td').hasClass("checkCrewCls");
                var crewMember = $('#tab'+tabnum+' .checkCrewCls').data('crew_member');
                var that = $('#tab'+tabnum+' form.tabValidate');
                console.log(tabnum);
                console.log(legType);
                

                //Form validation
                var validateTabs = that.validate({
                    ignore: "input[type='text']:hidden",
                    validateHiddenInputs: false,
                    rules: {   
                        'taxi_out': {
                            required: true
                        },    
                        'taxi_off': {
                            required: true
                        },
                        'landing': {
                            required: true
                        },
                        'taxi_in': {
                            required: true
                        },
                    }
                }).form();

                if(that.valid()) {
                    if(crewCls == false || (crewMember == '' || typeof crewMember === 'undefined')) {
                        flag = false;
                        $('#closeLegModel .modal-body').html('');
                        $('#closeLegModel .modal-body').html('Please add at least one crew member to each leg before closing.');
                        $('#closeLegModel').modal('show');
                        return false;
                    }
                    else if((dutyOn < dutyOff) && ((dutyOn > taxiOut || dutyOn > takeOff) || (dutyOff < landing || dutyOff < taxiIn))) {
                        flag = false;
                        $('#closeLegModel .modal-body').html('');
                        $('#closeLegModel .modal-body').html('Flight leg number '+tabnum+' is not covered by a valid duty time. Please check the leg times and save again.');
                        $('#closeLegModel').modal('show');
                        return false;
                    }
                    /* 
                    else if(landing <= takeOff) {
                        flag = false;

                        $('#closeLegModel .modal-body').html('');
                        $('#closeLegModel .modal-body').html('Your landing time must come after your take off time.');
                        $('#closeLegModel').modal('show');
                        return false;
                    }
                    */
                    else if(landing > taxiIn) {
                        flag = false;
                        $('#closeLegModel .modal-body').html('');
                        $('#closeLegModel .modal-body').html('Your taxi-in time must come after your landing time.');
                        $('#closeLegModel').modal('show');
                        return false;
                    }
                    else if(tripId != '' && mfId != '') {
                        //Check manifest
                        if(legType == 135 && ((mfMaxWeight == '' || mfMaxWeight == 0 || typeof mfMaxWeight === 'undefined') || (mfActWeight == '' || mfActWeight == 0 || typeof mfActWeight === 'undefined') || (mfForwardCg == '' || mfForwardCg == 0 || typeof mfForwardCg === 'undefined') || (mfActualCg == '' || mfActualCg == 0 || typeof mfActualCg === 'undefined') || (mfAftCg == '' || mfAftCg == 0 || typeof mfAftCg === 'undefined'))) {
                            
                            flag = false;
                            $('#closeLegModel .modal-body').html('');
                            $('#closeLegModel .modal-body').html('Please complete a manifest for each non Part91 leg of the flight.');
                            $('#closeLegModel').modal('show');
                            return false;
                        } else {
                            var request = $.ajax({
                                type: "POST",
                                url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'getManifest']); ?>",
                                data: {tripId:tripId, mfId:mfId, tabnum:tabnum, legType:legType},
                                async: false,
                                success: function(response) {
                                    var obj = JSON.parse(response);
                                    if(obj.status == 'success') {
                                        //Ajax call to save current leg data
                                        var formData = 'close_trip=yes'+'&'+'trip_id=' + tripId +'&'+ $('#tab'+tabnum+' .frmFlightLegs').serialize();
                                        saveCloseTripData(formData, tabCnt, tabnum);
                                    } else if(legType == 135) {
                                        flag = false;
                                        $('#closeLegModel .modal-body').html('');
                                        $('#closeLegModel .modal-body').html('Please complete a manifest for each non Part91 leg of the flight.');
                                        $('#closeLegModel').modal('show');
                                        return false;
                                    }
                                }                   
                            });
                            //promises.push(request);
                        }
                    } else if(legType == 135 && ((mfMaxWeight == '' || mfMaxWeight == 0 || typeof mfMaxWeight === 'undefined') || (mfActWeight == '' || mfActWeight == 0 || typeof mfActWeight === 'undefined') || (mfForwardCg == '' || mfForwardCg == 0 || typeof mfForwardCg === 'undefined') || (mfActualCg == '' || mfActualCg == 0 || typeof mfActualCg === 'undefined') || (mfAftCg == '' || mfAftCg == 0 || typeof mfAftCg === 'undefined'))) {

                        flag = false;
                        $('#closeLegModel .modal-body').html('');
                        $('#closeLegModel .modal-body').html('Please complete a manifest for each non Part91 leg of the flight.');
                        $('#closeLegModel').modal('show');
                        return false;
                    } else {
                        flag = true;
                        //Get trip id
                        var ctrip = $('#tripId').val();
                        if(tripId != '') {
                            tripId = tripId;
                        } else if(ctrip != '') {
                            tripId = ctrip;
                        } else if(tripId == '' && ctrip == '') {
                            tripId = tripID();
                        }

                        //Ajax call to save current leg data
                        var formData = 'close_trip=yes'+'&'+'trip_id='+tripId+'&'+ $('#tab'+tabnum+' .frmFlightLegs').serialize();
                        saveCloseTripData(formData, tabCnt, tabnum);
                    }
                } else {
                    flag = false;
                    return false;
                }
            });

            /*$.when.apply(null, promises).done(function() {
                console.log('Successfully saved!');
            })*/
        }
    });

    function saveCloseTripData(formData, tabCnt, tabnum)
    {
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'saveFLSData']); ?>",
            data: formData,
            async: false,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    if(tabCnt == tabnum) {
                        if(obj.datatype == 'initial') {
                            window.location = "flightlogSuccess/"+obj.tripid;
                        } else {
                            window.location = "../flightlogSuccess/"+obj.tripid;
                        }
                    }
                }
            }
        });
    }

    //Generate trip id
    function tripID() {
        var encKey = "";
        var possible = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        for (var i = 0; i < 11; i++) {
            encKey += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return encKey;
    }

    //On load aircraft get due record
    $( window ).on( "load", function() {
        var airId = $(".airListCls option:selected").val();
        aircraftDueRecord(1, airId);
    });

    //On change aircraft get due record
    $(document).on('change', '.airListCls', function(e) {
        e.stopPropagation();
        e.preventDefault();

        var tabnum = $(this).closest('.flightFields').siblings('.tabnum').val();
        var airId = $(this).val();
        $('#tab'+tabnum+' .addCrewBtn').data('plane_id', airId);
        aircraftDueRecord(tabnum, airId); 
    });

    function aircraftDueRecord(tabnum, airId)
    {
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'aircraftDueRecord']); ?>",
            data: {airId:airId},
            async : true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#tab'+tabnum+' .airDueItem').html(obj.airitem);
                    $('#tab'+tabnum+' .airDueToGo').html(obj.airtogo);
                } else {
                    $('#tab'+tabnum+' .airDueItem').html(obj.airitem);
                    $('#tab'+tabnum+' .airDueToGo').html(obj.airtogo);
                }
            }                   
        });
    }
    
});

var saveTripFiles = "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'saveTripFiles']); ?>";
var editTFPopup = "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'editTFPopup']); ?>";
var tripFilesList = "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'tripFilesList']); ?>";
var deleteTripFile = "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'deleteTripFile']); ?>";
var saveFLSData = "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'saveFLSData']); ?>";
var addDiscrepancy = "<?php echo Router::url(['controller'=>'AircraftDiscrepancies', 'action'=>'addDiscrepancy']); ?>";
var addDispData = "<?php echo Router::url(['controller' => 'AircraftDiscrepancies', 'action' => 'add']); ?>";
var airDueRecord = "<?php echo Router::url(['controller'=>'AircraftDiscrepancies', 'action'=>'openDiscrepancy']); ?>";
var upDispRecord = "<?php echo Router::url(['controller'=>'AircraftDiscrepancies', 'action'=>'updateDiscrepancy']); ?>";
var delClosedLeg = "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'delClosedLeg']); ?>";
</script>