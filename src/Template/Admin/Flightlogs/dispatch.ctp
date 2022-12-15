<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User'); 
use Cake\Routing\Router;

$formTag = '<form method="post" class="form-horizontal frmFlightLegs form-label-left">';
$redirectURL = BASE_URL.ROOT_DIR.'admin/flightlogs/dispatch/';

$copyTrip = '';
$newTrip = '';
if(!empty($legDate) && $legDate == 'copy') {
    $copyTrip = $legDate;
    $legDate = '';
} elseif (!empty($legDate) && $legDate != 'copy') {
    $newTrip = 'copy';
}
$customTripId = !empty($legDate) ? strtoupper($tripId).' Copy' : $tripId;

//CSS for uploaded trip files
$style = 'style="display:none;"';
if(!empty($tripId)) {
    $style = 'style="display:block;"';
}
?>
    
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Flight Schedule</h2>
        </div>

        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-4 col-xs-12">
                                <h5>Trip ID</h5>
                                <input type="text" name="trip_id" class="form-control" id="tripId" maxlength="15" value="<?php echo $customTripId; ?>">
                            </div>
                            <div class="col-sm-4 col-xs-12" <?php echo $style; ?>>
                                <h5>Trip Files (<span class="filesCountCls"><?php echo $tfcount; ?></span>)</h5>
                                <button type="button" class="btn btn-success" id="uploadTripPopup" data-tripid="<?php echo $tripId; ?>"><i class="fa fa-upload"></i> Add or Edit Trip Files</button>
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <h5>Templates</h5>
                                <button type="button" class="btn btn-success" id="showTemplates"><i class="fa fa-briefcase"></i> Load a Template</button>
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
                            <button class="ui-tabs-tab ui-corner-top ui-state-default ui-tab add-tab" style="cursor: pointer;" id='add-tab'><a class="ui-tabs-anchor"><span class="glyphicon glyphicon-plus"></span></a></button>
                        </ul>
                        <?php
                        if(!empty($fls)) {
                            $i=1;
                            foreach ($fls as $key => $value) {
                                $airRecord = $reportComp->airDues($value['plane_id']);
                                $route = $value['flight_from'].'-'.$value['flight_to'];
                                $savedLegDate = !empty($value['leg_date']) ? date('m/d/Y', strtotime($value['leg_date'])) : date('m/d/Y');
                                $legDate = !empty($legDate) ? $pilotComp->changeFormat($legDate) : $savedLegDate;

                                $legStartTime = !empty($value['leg_start']) ? date('H:i', strtotime($value['leg_start'])) : '';
                                $legLength = !empty($value['leg_length']) ? date('H:i', strtotime($value['leg_length'])) : '';
                                //Leg stop time
                                $legStopTime = $pilotComp->addTimesMulti(array($legStartTime, $legLength));
                                ?>
                                <!-- Dynamic tabs -->
                                <div id="tab<?php echo $i; ?>">
                                    <form method="post" class="form-horizontal frmFlightLegs form-label-left">
                                        <input type="hidden" name="new_trip" class="newTripCls" value="<?php echo $newTrip; ?>">
                                        <input type="hidden" name="id" value="<?php echo $value['id']; ?>">
                                        <input type="hidden" name="fld_id" value="<?php echo $value['flightlog_detail']['id']; ?>">
                                        <input type="hidden" name="plane_id" value="<?php echo $value['plane_id']; ?>">
                                        <input type="hidden" name="form_type" class="formTypeCls" value="fllog">
                                        <input type="hidden" name="tabnum" class="tabnum" value="<?php echo $i; ?>">
                                        <div class="gettab row" style="padding-top: 10px;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <h4>Aircraft</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?php echo $this->Form->control('plane_id', array('options'=>$planes, 'class'=>'form-control selectpicker airListCls', 'data-show-subtext'=>true, 'data-live-search'=>false, 'required'=>'required', 'label'=>false, 'value'=>$value['plane_id'])); ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <table width="100%">
                                                        <thead style="border-bottom: 1px solid #c0c0c0;padding: 2px;">
                                                            <tr>
                                                                <th>Next Due Item</th>
                                                                <th>ToGo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="airDueRecord">
                                                            <?php echo $airRecord['extHtml']; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- Crew information -->
                                                <div class="form-group">
                                                    <div class="col-md-6">
                                                        <h4>Crew Information</h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?php 
                                                        echo $this->Form->button('<i class="fa fa-plus"></i> Add Crew Member', ['type'=>'button', 'class'=>'btn btn-sm btn-success addCrewBtn', 'style'=>'float:right;']); 
                                                        ?>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <table width="100%">
                                                        <thead style="border-bottom: 1px solid #c0c0c0;padding: 2px;">
                                                            <tr>
                                                                <th width="8%">Type</th>
                                                                <th width="15%">Member</th>
                                                                <th width="15%">Duty Date</th>
                                                                <th width="15%">Duty On</th>
                                                                <th width="15%">Duty Off</th>
                                                                <th width="15%">Total</th>
                                                                <th width="2%">&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="crewMemberList">
                                                            <?php
                                                            if(!empty($value['crews'])) {
                                                                foreach ($value['crews'] as $key2 => $value2) {
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
                                                                        <td class="crewRecUpdate" style="color:#428bca;cursor:pointer;" data-id="'.$value2['id'].'" data-plane_id="'.$value2['plane_id'].'" data-flightlog_id="'.$value2['flightlog_id'].'" data-route="'.$route.'"><input type="hidden" name="crew_member[]" value="'.$value2['crew_member'].'">'.$crewName.'</td>
                                                                        <td><input type="hidden" name="duty_start_date[]" value="'.$value2['duty_start_date'].'">'.date('m/d/Y', strtotime($value2['duty_start_date'])).'</td>
                                                                        <td><input type="hidden" name="duty_on[]" value="'.$dutyOn.'">'.$dutyOn.'</td>
                                                                        <td><input type="hidden" name="duty_off[]" value="'.$dutyOff.'">'.$dutyOff.'</td>
                                                                        <td><input type="hidden" name="required_rest[]" value="'.$reqRest.'"><input type="hidden" name="legs_apply[]" value="'.$legsApply.'">'.$totalTime.'</td>
                                                                        <td style="cursor:pointer;" class="crewDelCls" data-crew_member="'.$value2['crew_member'].'">X</td>
                                                                    </tr>';
                                                                }
                                                            } else {
                                                                echo '<tr class="crewRow">
                                                                        <td colspan="7" style="text-align: center;">No crew members have been added for this leg.</td>
                                                                    </tr>';
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- End Crew information -->
                                            </div>
                                        </div>
                                        
                                        <div style="clear: both;"></div>

                                        <div class="flightInfoCls">
                                            <h4>Leg Flight Information</h4>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Flying From</label>
                                                    <input type="text" name="flight_from" class="form-control flyFromCls" maxlength="4" style="text-transform:uppercase" value="<?php echo $value['flight_from']; ?>">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Flying To</label>
                                                    <input type="text" name="flight_to" class="form-control flyToCls" maxlength="4" style="text-transform:uppercase" value="<?php echo $value['flight_to']; ?>">
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Leg Type</label>
                                                    <?php
                                                    $legType = ['135'=>'Part 135', '91'=>'Part 91'];
                                                    echo $this->Form->control('leg_type', array('options'=>$legType, 'class'=>'form-control col-md-6 col-xs-12', 'label'=>false, 'value'=>$value['leg_type']));
                                                    ?>
                                                </div>

                                                <div class="col-md-3">
                                                    <label># Passengers</label>
                                                    <?php echo $this->Form->control('passengers', array('class'=>'form-control', 'label'=>false, 'value'=>$value['passengers'])); ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Leg Start Date</label>
                                                    <?php echo $this->Form->Text('leg_date', array('class'=>'form-control datePicker', 'label'=>false, 'value'=>$legDate)); ?>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Leg Start Time</label>
                                                    <?php echo $this->Form->Text('leg_start', array('class'=>'form-control legStartCls timePicker keypress', 'label'=>false, 'value'=>$legStartTime, 'placeholder'=>'00:00')); ?>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Leg Length</label>
                                                    <?php echo $this->Form->Text('leg_length', array('class'=>'form-control legLengthCls timePicker keypress', 'label'=>false, 'value'=>$legLength, 'placeholder'=>'00:00')); ?>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Leg Stop Time</label>
                                                    <input type="text" name="leg_stop" class="form-control legStopCls disabledBG" placeholder="00:00" disabled value="<?php echo $legStopTime; ?>">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Leg Notes</label>
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
                                        </div>

                                        <div class="row allBtnCls">
                                            <div class="col-md-10 col-sm-10 col-xs-12">
                                                <?php
                                                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                                                    echo $this->Form->button('<i class="fa fa-times"></i> Delete this Leg', ['type'=>'button', 'class'=>'btn btn-default deleteLeg']);
                                                    echo $this->Form->button('<i class="fa fa-plus"></i> Leg Before', ['type'=>'button', 'class'=>'btn btn-success add-before']);
                                                    echo $this->Form->button('<i class="fa fa-plus"></i> Leg After', ['type'=>'button', 'class'=>'btn btn-success add-after']);
                                                    echo $this->Form->button('<i class="fa fa-save"></i> Save for later', ['type'=>'submit', 'class'=>'btn btn-success saveLaterCls', 'data-flstatus'=>'savelater']);
                                                    echo $this->Form->button('<i class="fa fa-plane"></i> Schedule this Trip', ['type'=>'submit', 'class'=>'btn btn-success saveLaterCls', 'data-flstatus'=>'fllog']);
                                                }
                                                ?>
                                                <a class="checkTempCls">
                                                    <input type="checkbox" name="is_template" class="tempChk"> Save this trip as a template
                                                </a>
                                            </div>
                                            <div class="col-md-2 col-sm-2 col-xs-12">
                                                <div class="flsErrMsg"></div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- End dynamic tabs -->
                                <?php
                                $i++;
                            }
                        } else {
                        ?>
                            <!-- first tab -->
                            <div id='tab1'>
                                <form method="post" class="form-horizontal frmFlightLegs form-label-left">
                                    <input type="hidden" name="form_type" class="formTypeCls" value="fllog">
                                    <input type="hidden" name="data_type" value="initial">
                                    <input type="hidden" name="tabnum" class="tabnum" value="1">
                                    
                                    <div class="gettab row" style="padding-top: 10px;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <h4>Aircraft</h4>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php echo $this->Form->control('plane_id', array('options'=>$planes, 'class'=>'form-control selectpicker airListCls', 'data-show-subtext'=>true, 'data-live-search'=>false, 'required'=>'required', 'label'=>false)); ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <table width="100%">
                                                    <thead style="border-bottom: 1px solid #c0c0c0;padding: 2px;">
                                                        <tr>
                                                            <th>Next Due Item</th>
                                                            <th>ToGo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="airDueRecord">
                                                        <tr>
                                                            <td>N/A</td>
                                                            <td>N/A</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Crew information -->
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    <h4>Crew Information</h4>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php 
                                                    echo $this->Form->button('<i class="fa fa-plus"></i> Add Crew Member', ['type'=>'button', 'class'=>'btn btn-sm btn-success addCrewBtn', 'style'=>'float:right;']); 
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <table width="100%">
                                                    <thead style="border-bottom: 1px solid #c0c0c0;padding: 2px;">
                                                        <tr>
                                                            <th width="8%">Type</th>
                                                            <th width="15%">Member</th>
                                                            <th width="15%">Duty Date</th>
                                                            <th width="15%">Duty On</th>
                                                            <th width="15%">Duty Off</th>
                                                            <th width="15%">Total</th>
                                                            <th width="2%">&nbsp;</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="crewMemberList" class="crewMemberList">
                                                        <tr class="crewRow">
                                                            <td colspan="7" style="text-align: center;">No crew members have been added for this leg.</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- End Crew information -->
                                        </div>
                                    </div>
                                    
                                    <div style="clear: both;"></div>

                                    <div class="flightInfoCls">
                                        <h4>Leg Flight Information</h4>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Flying From</label>
                                                <input type="text" name="flight_from" class="form-control flyFromCls" maxlength="4" style="text-transform:uppercase">
                                            </div>

                                            <div class="col-md-3">
                                                <label>Flying To</label>
                                                <input type="text" name="flight_to" class="form-control flyToCls" maxlength="4" style="text-transform:uppercase">
                                            </div>

                                            <div class="col-md-3">
                                                <label>Leg Type</label>
                                                <select name="leg_type" class="form-control col-md-6 col-xs-12">
                                                    <option value="135">Part 135</option>
                                                    <option value="91">Part 91</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label># Passengers</label>
                                                <input type="text" name="passengers" class="form-control">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Leg Start Date</label>
                                                <input type="text" name="leg_date" class="form-control datePicker" value="<?php echo date('m/d/Y'); ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <label>Leg Start Time</label>
                                                <input type="text" name="leg_start" class="form-control legStartCls timePicker keypress" placeholder="00:00">
                                            </div>

                                            <div class="col-md-3">
                                                <label>Leg Length</label>
                                                <input type="text" name="leg_length" class="form-control legLengthCls timePicker keypress" placeholder="00:00">
                                            </div>

                                            <div class="col-md-3">
                                                <label>Leg Stop Time</label>
                                                <input type="text" name="leg_stop" class="form-control legStopCls disabledBG" placeholder="00:00" disabled>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Leg Notes</label>
                                                <textarea name="notes" rows="3" cols="50" class="form-control col-md-6 col-xs-12" placeholder="Notes"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Flight Information -->       
                                    
                                    <div class="ln_solid"></div>

                                    <div class="row allBtnCls">
                                        <div class="col-md-10 col-sm-10 col-xs-12">
                                            <?php
                                            if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                                                echo $this->Form->button('<i class="fa fa-times"></i> Delete this Leg', ['type'=>'button', 'class'=>'btn btn-default']);
                                                echo $this->Form->button('<i class="fa fa-plus"></i> Leg Before', ['type'=>'button', 'class'=>'btn btn-success add-before']);
                                                echo $this->Form->button('<i class="fa fa-plus"></i> Leg After', ['type'=>'button', 'class'=>'btn btn-success add-after']);
                                                echo $this->Form->button('<i class="fa fa-save"></i> Save for later', ['type'=>'submit', 'class'=>'btn btn-success saveLaterCls', 'data-flstatus'=>'savelater']);
                                                echo $this->Form->button('<i class="fa fa-plane"></i> Schedule this Trip', ['type'=>'submit', 'class'=>'btn btn-success saveLaterCls', 'data-flstatus'=>'fllog']);
                                            }
                                            ?>
                                            <a class="checkTempCls">
                                                <input type="checkbox" name="is_template" class="tempChk"> Save this trip as a template
                                            </a>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-12">
                                            <div class="flsErrMsg"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- End first tab -->
                        <?php
                        }
                        ?>
                    </div>
                    <!-- End Tabs -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    echo $this->element('flightlog_popup'); 
    echo $this->Html->script('/js/flightlogs'); 
?>

<script>    
$(document).ready(function() {
    var tripid = '<?php echo $tripId; ?>';
    var currtDate = new Date();
    currtDate = moment(currtDate).format('MM/DD/YYYY');

    //If copy trip button clicked then open popup
    var copyTrip = '<?php echo $copyTrip; ?>';
    if(copyTrip == 'copy') {
        $( window ).on( "load", function() {
            $('#copyTemplModel .copyTripIdCls').html(tripid);
            $('#copyTemplModel .copyTripId').val(tripid);
            $('#copyTemplModel .legDate').val(currtDate);
            $('#copyTemplModel').modal('show');
        });
    }

    //Form validation
    $(".frmFlightLegs").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {   
            'flight_from': {
                required: true
            },    
            'flight_to': {
                required: true
            },
            'leg_start': {
                required: true
            },
            'leg_length': {
                required: true
            },
        }
    });

    //Tabs
    var newForm = '<?php echo $formTag; ?>';
    $("div#tabs").tabs();

    //Add using plus button
    $(document).on("click", ".add-tab", function() {
        var tabCnt = $("div#tabs ul#tabUI li").length;
        var num_tabs = tabCnt + 1;
                
        $("div#tabs ul li.ui-tabs-active").after(
            "<li class='tab"+num_tabs+"' data-tabnum='"+num_tabs+"'><a href='#tab" + num_tabs + "'><span class='displayFlyFrom'>?</span>-<span class='displayFlyTo'>?</span></a></li>"
        );
        $("div#tabs").append(
            "<div id='tab" + num_tabs + "' data-tabnum='"+num_tabs+"' data-tabcount='"+num_tabs+"'></div>"
        );

        createNewTab(tabCnt, num_tabs);
        $("div#tabs").tabs("refresh");
    });

    //Add after active tab
    $(document).on("click", ".add-after", function() {
        var tabCnt = $("div#tabs ul#tabUI li").length;
        var num_tabs = tabCnt + 1;

        $("div#tabs ul li.ui-tabs-active").after(
            "<li class='tab"+num_tabs+"' data-tabnum='"+num_tabs+"'><a href='#tab" + num_tabs + "'><span class='displayFlyFrom'>?</span>-<span class='displayFlyTo'>?</span></a></li>"
        );
        $("div#tabs").append(
            "<div id='tab" + num_tabs + "' data-tabnum='"+num_tabs+"' data-tabcount='"+num_tabs+"'></div>"
        );
        
        createNewTab(tabCnt, num_tabs);
        $("div#tabs").tabs("refresh");
    });

    //Add before active tab
    $(document).on("click", ".add-before", function() {
        var tabCnt = $("div#tabs ul#tabUI li").length;
        var num_tabs = tabCnt + 1;

        $("div#tabs ul li.ui-tabs-active").before(
            "<li class='tab"+num_tabs+"'><a href='#tab" + num_tabs + "'><span class='displayFlyFrom'>?</span>-<span class='displayFlyTo'>?</span></a></li>"
        );
        $("div#tabs").append(
            "<div id='tab" + num_tabs + "' data-tabnum='"+num_tabs+"' data-tabcount='"+num_tabs+"'></div>"
        );

        createNewTab(tabCnt, num_tabs);
        $("div#tabs").tabs("refresh");
    });

    //Add new tab with form
    function createNewTab(tabCnt, num_tabs) {
        //Get air due
        var selAirId = $('#tab'+tabCnt+' .airListCls option:selected').val();
        var airDue = $('#tab'+tabCnt+' .airDueRecord').html();
        
        //Get crew list
        var crewList = $('#tab'+tabCnt+' .crewMemberList').html();
        
        //Get if selected as template 
        var isTempl = $('#tab'+tabCnt+' .checkTempCls').html();
        var isTempChk = $('#tab'+tabCnt+' input.tempChk').is(':checked');

        //New tab html
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'newTab']); ?>",
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
                        $("#tab"+num_tabs).html(newForm + obj.data);
                        $('#tab'+num_tabs+' .tabnum').val(num_tabs);
                        $('#tab'+num_tabs+' .airListCls').val(selAirId);
                        $('#tab'+num_tabs+' .airDueRecord').html(airDue);
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
        var tabnum = $(this).closest('.flightInfoCls').siblings('.tabnum').val();
        var flyFrm = $(this).val();
        if(flyFrm != '') {
            $('.tab'+tabnum+' .displayFlyFrom').html(flyFrm.toUpperCase());
        } else {
            $('.tab'+tabnum+' .displayFlyFrom').html('?');
        }
    });

    $(document).on("keyup", ".flyToCls", function() {
        var tabnum = $(this).closest('.flightInfoCls').siblings('.tabnum').val();
        var flyTo = $(this).val();
        if(flyTo != '') {
            $('.tab'+tabnum+' .displayFlyTo').html(flyTo.toUpperCase());
        } else {
            $('.tab'+tabnum+' .displayFlyTo').html('?');
        }
    });    

    //Open add crew member popup
    $(document).on("click", ".addCrewBtn", function() {
        var tabnum = $(this).closest('.gettab').siblings('.tabnum').val();
        var flyFrom = $('#tab'+tabnum+' .flyFromCls').val();
        var flyTo = $('#tab'+tabnum+' .flyToCls').val();
        var legInfo = 'TBD-TBD';
        if(flyFrom !='' && flyTo !='') {
            legInfo = flyFrom.toUpperCase()+'-'+flyTo.toUpperCase();
        }
        
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
                        $('#flsCrewErrorMsg').html('');
                        $('#flsCrewModel #flsCrewListId').html(obj.data);
                        $('#flsCrewModel .flsCrewLegInfo').html(legInfo);
                        $('#flsCrewModel .legInfoCls').val(legInfo);
                        $('#flsCrewModel #flsCrewSaveBtn').data("tabnum", tabnum);
                        $('#flsCrewModel .dtStartD').val(currtDate);
                        $('#flsCrewModel').modal('show');
                        $('#flsCrewModel .selectpicker').selectpicker('refresh');
                    }, 500);
                }
            }                   
        });        
    });

    //Add initial crew details in the form
    $(document).on('click', '#flsCrewSaveBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();

        if($('#flsCrewFrm').valid()) {
            var tabnum = $(this).data('tabnum');
            $('#flsCrewFrm .allCrewCls').val('dispatch');
            var formVal = $('#flsCrewFrm').serialize();

            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'displayCrewData']); ?>",
                data: formVal,
                async: true,
                beforeSend: function(){
                    $('.loader').show();
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        setTimeout(function() {
                            $('.loader').hide();
                            //$('#tabs .crewRecord').remove();
                            $('#tabs .crewRecord'+obj.crewmem).remove();
                            $('#tabs .crewRow').remove();
                            $('#tabs .crewMemberList').append(obj.data);
                            $('#flsCrewModel').modal('hide');
                        }, 500);
                    }
                }                   
            });
        }
    });

    //Crew popup validation
    $("#flsCrewFrm").validate({
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

    //Leg Stop time for new tab
    $('body').on('focus',".keypress", function(e) {
        e.preventDefault();

        $(this).datetimepicker({
            useCurrent: false,
            format: 'HH:mm'
        }).on('dp.change', function(e) {
            var tabnum = $(this).closest('.flightInfoCls').siblings('.tabnum').val();
            var leg_start = $('#tab'+tabnum+' .legStartCls').val();
            var leg_length = $('#tab'+tabnum+' .legLengthCls').val();
           
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'getStopTime']); ?>",
                data: {leg_start:leg_start, leg_length:leg_length},
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#tab'+tabnum+' .legStopCls').val(obj.data);
                    }
                }                   
            });
        });
    });

    //Initial added crew record popup
    $(document).on("click", ".initCrewRecUpdate", function() {
        var member_type = $(this).data('member_type');
        var crew_member = $(this).data('crew_member');
        var duty_start_date = $(this).data('duty_start_date');
        var duty_on = $(this).data('duty_on');
        var duty_off = $(this).data('duty_off');
        var required_rest = $(this).data('required_rest');
        var legs_apply = $(this).data('legs_apply');
        var leg_info = $(this).data('leg_info');

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'flsCrewInitUpPopup']); ?>",
            data: {member_type:member_type, crew_member:crew_member, duty_start_date:duty_start_date, duty_on:duty_on, duty_off:duty_off, required_rest:required_rest, legs_apply:legs_apply, leg_info:leg_info},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#flsCrewErrorMsg').html('');
                        $('#flsCrewModel .modal-body').html(obj.data);
                        $('#flsCrewModel').modal('show');
                        $('#flsCrewModel .selectpicker').selectpicker('refresh');
                    }, 500);
                }
            }                   
        });        
    });

    //Open previously added crew details in popup
    $(document).on("click", ".crewRecUpdate", function() {
        var id = $(this).data('id');
        var airId = $(this).data('plane_id');
        var flId = $(this).data('flightlog_id');
        var route = $(this).data('route');

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'flsCrewUpdatePopup']); ?>",
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
                        $('#flsCrewUpdModel crewUpdateMsg').html('');
                        $('#flsCrewUpdModel .modal-body').html(obj.data);
                        $('#flsCrewUpdModel').modal('show');
                    }, 500);
                }
            }                   
        });        
    });

    //Crew update popup validation
    $("#flsCrewUpdFrm").validate({
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

    //Add previously added crew details in the form
    $(document).on('click', '#flsCrewUpdateBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();

        if($('#flsCrewUpdFrm').valid()) {
            $('#flsCrewUpdFrm .allCrewCls').val('dispatch');
            var formVal = $('#flsCrewUpdFrm').serialize();

            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'displayCrewData']); ?>",
                data: formVal,
                async: true,
                beforeSend: function(){
                    $('.loader').show();
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('#tabs .crewRecord'+obj.crewmem).remove();
                            $('#tabs .crewRow').remove();
                            $('#tabs .crewMemberList').append(obj.data);
                            $('#flsCrewUpdModel').modal('hide');
                        }, 500);
                    }
                }                   
            });
        }
    });
    
    //Add template popup
    $(document).on('click', '.tempChk', function() {
        var tabnum = $(this).closest('.allBtnCls').siblings('.tabnum').val();
        if($('#tab'+tabnum+' input.tempChk').is(':checked')) {
            $('#addTempNameModel').modal('show');
        }
    });

    //Template name popup validation
    $("#addTempNameFrm").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'template_name': {
                required: true
            } 
        }
    });

    //Add template name in the FLS form
    $(document).on('click', '#addTempBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        if($('#addTempNameFrm').valid()) {
            var formData = $('#addTempNameFrm').serialize();

            $.ajax({
                type: 'post',
                url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'addTemplateName']); ?>",
                data: formData,
                async: true,
                beforeSend: function () {
                    $('.loader').show();
                },
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('.tempChk').after(obj.data);
                            $('#addTempNameModel').modal('hide');
                        }, 500);
                    }               
                }
            });
        }
    });

    //Display saved templates listing popup
    $(document).on('click', '#showTemplates', function(e) {
        e.stopPropagation();
        e.preventDefault();

        $.ajax({
            type: "post",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'displayTemplates']); ?>",
            data: {},
            async: true,
            beforeSend:function(){
                $('.loader').show();
            },
            success:function(response){
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#showTemplModel .templateList').html(obj.data);
                        $('#showTemplModel').modal('show');
                    }, 500);
                }
            }
        });
    });

    //Remove template
    $(document).on('click', '.removeTempCls', function() {
        var tmpid = $(this).data('id');
        var tripid = $(this).data('tripid');

        if (confirm('Are you sure you want to delete this template?')) {
            $.ajax({
                url : "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'deleteTemplate']); ?>",
                type : 'post',
                data : {tmpid: tmpid, tripid: tripid},
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#showTemplModel .templateList').html(obj.data);
                    } else {
                        $('#showTemplModel .templateList').html(obj.data);
                    }                  
                }
            });
        }
    });

    //Load template popup
    $(document).on('click', '.tempPopupCls', function() {
        var tmpid = $(this).data('id');
        var tripid = $(this).data('tripid');

        $.ajax({
            url : "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'openTempPopup']); ?>",
            type : 'post',
            data : {tmpid: tmpid, tripid: tripid},
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#loadTemplModel .tempNameCls').html(obj.tempName);
                    $('#loadTemplModel .modal-body').html(obj.data);
                    $('#loadTemplModel').modal('show');
                } else {
                    $('#loadTemplModel .modal-body').html(obj.data);
                }                  
            }
        });
    });

    //Load template in the form
    $(document).on('click', '.loadTempCls', function(e) {
        e.stopPropagation();
        e.preventDefault();

        var formData = $('#loadTemplFrm').serialize();

        $.ajax({
            url : "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'loadTemplate']); ?>",
            type : 'post',
            data : formData,
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#loadTemplModel').modal('hide');
                    window.location.href = "<?php echo $redirectURL; ?>"+obj.tripid+'/'+obj.legdate;
                } else {
                    $('#loadTemplModel').modal('hide');
                }                  
            }
        });
    });

    //Load template in the form
    $(document).on('click', '.copyTripBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();

        var formData = $('#copyTemplFrm').serialize();

        $.ajax({
            url : "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'loadTemplate']); ?>",
            type : 'post',
            data : formData,
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#copyTemplModel').modal('hide');
                    window.location.href = "<?php echo $redirectURL; ?>"+obj.tripid+'/'+obj.legdate;
                } else {
                    $('#copyTemplModel').modal('hide');
                }                  
            }
        });
    });

    //On leg date change assign same date on leg start
    /*$(".datePicker").datetimepicker({
        useCurrent: false,
        format: 'MM/DD/YYYY'
    }).on('dp.change', function(e) {
        var a = moment(e.date._d);
        var newDate = a.format('MM/DD/Y');
        $('.timePicker').val(newDate);
        console.log(newDate);
        
    });*/

    //On load aircraft get due record
    $( window ).on( "load", function() {
        var airId = $(".airListCls option:selected").val();
        aircraftDueRecord(1, airId);
    });

    //On change aircraft get due record
    $(document).on('change', '.airListCls', function(e) {
        e.stopPropagation();
        e.preventDefault();

        var tabnum = $(this).closest('.gettab').siblings('.tabnum').val();
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
                    $('#tab'+tabnum+' .airDueRecord').html(obj.data);
                } else {
                    $('#tab'+tabnum+' .airDueRecord').html(obj.data);
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
var delClosedLeg = "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'delClosedLeg']); ?>";
</script>