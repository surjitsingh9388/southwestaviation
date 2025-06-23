<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth'); 
use Cake\Routing\Router;

$formTag = '<form method="post" class="form-horizontal frmFlightLegs form-label-left">';
$tripId = !empty($fls[0]['trip_id']) ? strtoupper($fls[0]['trip_id']) : '';
$redirectURL = BASE_URL.ROOT_DIR.'admin/flightlogs/dispatch/';
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
            <h2 class="heading">Flight Review</h2>
        </div>

        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group">
                            <h5 class="col-md-2 col-xs-12">Trip ID: <span style="font-weight: normal;"><?php echo $tripId; ?></span></h5>
                            <div class="col-md-3 col-xs-12">
                                <input type="hidden" name="trip_id" id="tripId" value="<?php echo $tripId; ?>">
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <a class="trip-files" id="viewTripFiles" data-tripid="<?php echo $tripId; ?>">View Trip Files(<span class="filesCountCls"><?php echo $tfcount; ?></span>)</a>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                            </div>
                        </div>
                    </div>

                    <div style="clear: both;padding-top: 15px;"></div>

                    <!-- Tabs -->
                    <div id='tabs'>
                        <ul>
                            <?php
                            if(!empty($fls)) {
                                $i=1;
                                foreach ($fls as $key => $value) {
                                    echo '<li class="tab'.$i.'"><a href="#tab'.$i.'"><span class="displayFlyFrom">'. strtoupper($value['flight_from']).'</span>-<span class="displayFlyTo">'.strtoupper($value['flight_to']).'</span></a></li>';
                            
                                    $i++;
                                }
                            }                  
                            ?>
                        </ul>
                        <?php
                        if(!empty($fls)) {
                            $i=1;
                            foreach ($fls as $key => $value) {
                                $airRecord = $reportComp->airDues($value['plane_id']);

                                $route = $value['flight_from'].'-'.$value['flight_to'];
                                $legDate = !empty($value['leg_date']) ? date('m/d/Y', strtotime($value['leg_date'])) : date('m/d/Y');
                                $legStartTime = !empty($value['leg_start']) ? date('H:i', strtotime($value['leg_start'])) : '00:00';
                                $legLength = !empty($value['leg_length']) ? date('H:i', strtotime($value['leg_length'])) : '00:00';
                                //Leg stop time
                                $legStopTime = $pilotComp->addTimesMulti(array($legStartTime, $legLength));
                                ?>
                                <!-- Dynamic tabs -->
                                <div id="tab<?php echo $i; ?>">
                                    <form method="post" class="form-horizontal frmFlightLegs form-label-left">
                                        <input type="hidden" name="id" value="<?php echo $value['id']; ?>">
                                        <input type="hidden" name="fld_id" value="<?php echo $value['flightlog_detail']['id']; ?>">
                                        <input type="hidden" name="plane_id" value="<?php echo $value['plane_id']; ?>">
                                        <input type="hidden" name="form_type" class="formTypeCls" value="flpreplanning">
                                        <input type="hidden" name="tabnum" class="tabnum" value="<?php echo $i; ?>">
                                        <div class="flightFields">
                                            <div class="flightInfoCls">
                                                <div class="row">
                                                    <h4>Aircraft Information</h4>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table width="100%">
                                                            <thead style="border-bottom: 1px solid #c0c0c0;">
                                                                <tr>
                                                                    <th>Aircraft</th>
                                                                    <th>Next Due Item</th>
                                                                    <th>Due At</th>
                                                                    <th>ToGo</th>
                                                                    <th>-</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td><?php echo $fls[0]['plane']['plane_code']; ?></td>
                                                                    <td><?php echo $airRecord['airitem']; ?></td>
                                                                    <td><?php echo $airRecord['airDueAt']; ?></td>
                                                                    <td><?php echo $airRecord['airtogo']; ?></td>
                                                                    <td>-</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                <div style="clear: both; padding-top: 20px;"></div>

                                                <div class="row">
                                                    <h4>Crew Information</h4>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table width="100%">
                                                            <thead style="border-bottom: 1px solid #c0c0c0;">
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
                                                                        $dutyOn = date('H:i', strtotime($value2['duty_on']));
                                                                        $dutyOff = date('H:i', strtotime($value2['duty_off']));
                                                                        $totalTime = $pilotComp->timeDiffFL($dutyOn, $dutyOff);
                                                                        $crewName = $pilotComp->getPilotName($value2['crew_member']);
                                                                        echo '<tr class="crewRecord'.$value2['crew_member'].'">
                                                                            <td>'.strtoupper($value2['member_type']).'</td>
                                                                            <td class="crewRecUpdate">'.$crewName.'</td>
                                                                            <td>'.date('d/m/Y', strtotime($value2['duty_start_date'])).'</td>
                                                                            <td>'.$dutyOn.'</td>
                                                                            <td>'.$dutyOff.'</td>
                                                                            <td>'.$totalTime.'</td>
                                                                            <td>&nbsp;</td>
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
                                                </div>

                                                <div style="clear: both;padding-top: 20px;"></div>

                                                <div class="row">
                                                    <h4>Flight Information</h4>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>Flying From</label>
                                                        <input type="hidden" name="flight_from" value="<?php echo $value['flight_from']; ?>">
                                                        <input type="text" name="flight_from" class="form-control disabledBG flyFromCls" maxlength="4" style="text-transform:uppercase" value="<?php echo $value['flight_from']; ?>" disabled>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Flying To</label>
                                                        <input type="hidden" name="flight_to" value="<?php echo $value['flight_to']; ?>">
                                                        <input type="text" name="flight_to" class="form-control disabledBG flyToCls" maxlength="4" style="text-transform:uppercase" value="<?php echo $value['flight_to']; ?>" disabled>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Leg Type</label>
                                                        <?php
                                                        $legType = ['135'=>'Part 135', '91'=>'Part 91'];
                                                        echo $this->Form->control('leg_type', array('class'=>'form-control disabledBG col-md-6 col-xs-12', 'label'=>false, 'value'=>$value['leg_type'], 'disabled'=>'disabled'));
                                                        ?>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label># Passengers</label>
                                                        <input type="hidden" name="passengers" value="<?php echo $value['passengers']; ?>">
                                                        <?php echo $this->Form->control('passengers', array('class'=>'form-control disabledBG', 'label'=>false, 'value'=>$value['passengers'], 'disabled'=>'disabled')); ?>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <label>Leg Start Date</label>
                                                        <input type="hidden" name="leg_date" value="<?php echo $legDate; ?>">
                                                        <?php echo $this->Form->Text('leg_date', array('class'=>'form-control disabledBG datePicker', 'label'=>false, 'value'=>$legDate, 'disabled'=>'disabled')); ?>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Leg Start Time</label>
                                                        <?php echo $this->Form->Text('leg_start', array('class'=>'form-control disabledBG legStartCls timePicker keypress', 'label'=>false, 'value'=>$legStartTime, 'disabled'=>'disabled')); ?>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Leg Length</label>
                                                        <?php echo $this->Form->Text('leg_length', array('class'=>'form-control disabledBG legLengthCls timePicker keypress', 'label'=>false, 'value'=>$legLength, 'disabled'=>'disabled')); ?>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label>Leg Stop Time</label>
                                                        <input type="text" name="leg_stop" class="form-control disabledBG legStopCls" placeholder="00:00" value="<?php echo $legStopTime; ?>" disabled>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Leg Notes</label>
                                                        <?php echo $this->Form->control('notes', array('class'=>'form-control col-md-6 col-xs-12', 'label'=>false, 'rows'=>3, 'cols'=>50, 'placeholder'=>'Notes', 'value'=>$value['notes'])); ?>
                                                    </div>
                                                </div>

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
                                                    <div class="col-md-12 manifestComplete" style="padding-top: 30px;font-size: 14px; font-weight: bold; cursor: pointer;">
                                                        <i class="fa fa-file-text-o font16"></i>
                                                        <a class="">Edit Manifest for this Leg</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Flight Information -->       
                                            
                                            <div class="ln_solid"></div>

                                            <div class="row allBtnCls">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <?php
                                                    if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                                                        echo $this->Html->link("Back to Flight Center", array('action'=>'index'), array('class' => 'btn btn-default', 'escape' => false));
                                                        echo $this->Form->button('Accept Flight', ['type'=>'button', 'class'=>'btn btn-success saveLaterCls', 'data-flstatus'=>'flpreplanning']);
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- End dynamic tabs -->
                                <?php
                                $i++;
                            }
                        } else {
                            echo "Please create flight.";
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
    $("div#tabs").tabs();

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

});

var tripFilesList = "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'tripFilesList']); ?>";
var saveFLSData = "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'saveFLSData']); ?>";
</script>