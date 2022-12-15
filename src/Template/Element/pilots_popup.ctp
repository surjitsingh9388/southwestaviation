<!-- Pilot Specify popup -->
<div id="pilotSpecifyModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title pilotSpTitle"></h4>
                <span>Check each applicable aircraft.</span>
            </div>
            <div class="modal-body" style="max-height: 580px;">
            <table class="table">
                <tr>
                    <th width="5%">--</th>
                    <th width="10%">Type Designation</th>
                    <th width="15%">Date Assigned</th>
                    <th width="15%">Date Unassigned</th>
                </tr>
                <tbody class="dutyAssignInfo">
                    <tr>
                        <td><?php echo $this->Form->checkbox('check_1', array('class'=>'typeCheck1', 'label'=>false)); ?></td>
                        <td><?php echo $this->Form->Text('designation_1', array('class'=>'form-control designation1', 'label'=>false, 'value'=>'BE20', 'disabled'=>true)); ?></td>
                        <td>        
                            <?php echo $this->Form->Text('date_assigned_1', array('class'=>'form-control date_assigned1 datePicker', 'label'=>false)); ?>
                        </td>
                        <td>       
                            <?php echo $this->Form->Text('date_unassigned_1', array('class'=>'form-control date_unassigned1 datePicker', 'label'=>false)); ?>
                        </td>
                    </tr>

                    <tr>
                        <td><?php echo $this->Form->checkbox('check_2', array('class'=>'typeCheck2', 'label'=>false)); ?></td>
                        <td><?php echo $this->Form->Text('designation_2', array('class'=>'form-control designation2', 'label'=>false, 'value'=>'P180', 'disabled'=>true)); ?></td>
                        <td>        
                            <?php echo $this->Form->Text('date_assigned_2', array('class'=>'form-control date_assigned2 datePicker', 'label'=>false)); ?>
                        </td>
                        <td>      
                            <?php echo $this->Form->Text('date_unassigned_2', array('class'=>'form-control date_unassigned2 datePicker', 'label'=>false)); ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="modal-footer">
                <span id="errorMsgId"></span>
                <button type="submit" class="btn btn-success dutyAssignBtn" id="dutyAssignBtn">Close Duty Assignments</button>
            </div>
        </div>
    </div>
</div>
<!-- End Pilot Specify popup -->

<!-- Pilot Duty Details -->
<div id="pilotDutyDetailsModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                <button type="button" class="close reloadDutyDetail" data-dismiss="modal">&times;</button>
                <h4 class="modal-title dateTitleCls"></h4>
            </div>
            <div class="modal-body" style="max-height: 580px; padding:25px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default reloadDutyDetail" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Pilot Duty Details -->

<!-- Duty Times -->
<div id="pilotDutyTimesModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="dutyTimeFrm" class="form-horizontal">
                <input type="hidden" name="pilot_id" class="pilotIdCls">
                <input type="hidden" name="selected_date" class="selectDCls">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Duty Times</h4>
                    <span>Add or edit a duty time.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:25px;">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Duty On Time (hrs/mins)</label>
                            <select name="duty_start_hour" class="form-control col-md-6 col-xs-12 dutyStartHourCls">
                                <?php
                                $hour = array();
                                for ($i=0; $i < 24; $i++) { 
                                    if($i<10) {
                                        $hour = '0'.$i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    } else {
                                        $hour = $i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <select name="duty_start_minute" class="form-control col-md-6 col-xs-12 dutyStartMinutCls">
                                <?php
                                $time = array();
                                for ($i=0; $i < 60; $i++) {
                                    if($i<10) {
                                        $time = '0'.$i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    } else {
                                        $time = $i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Duty Off Time (hrs/mins)</label>
                            <select name="duty_stop_hour" class="form-control col-md-6 col-xs-12 dutyStopHourCls">
                                <?php
                                $hour = array();
                                for ($i=0; $i < 24; $i++) { 
                                    if($i<10) {
                                        $hour = '0'.$i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    } else {
                                        $hour = $i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <select name="duty_stop_minute" class="form-control col-md-6 col-xs-12 dutyStopMinutCls">
                                <?php
                                $time = array();
                                for ($i=0; $i < 60; $i++) { 
                                    if($i<10) {
                                        $time = '0'.$i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    } else {
                                        $time = $i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Required Rest</label>
                            <select name="required_rest" class="form-control col-md-6 col-xs-12 requiredRestCls">
                                <option value="8">8 Hours</option>
                                <option value="9">9 Hours</option>
                                <option value="10" selected>10 Hours</option>
                                <option value="11">11 Hours</option>
                                <option value="12">12 Hours</option>
                                <option value="16">16 Hours</option>
                                <option value="0">00 Hours (Part 91)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Duty Classification</label>
                            <select name="classification" class="form-control col-md-6 col-xs-12 classifiCls">
                                <option value="flight">Flight</option>
                                <option value="training">Training</option>
                                <option value="admin">Admin</option>
                                <option value="on_call">On Call</option>
                                <option value="travel">Travel</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="dutyTimeErrorMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="dutyTimeBtn">Save Duty Time</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Duty Times -->

<!-- Schedule Day Off -->
<div id="pilotDayOffModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="scheduleDayOffFrm" class="form-horizontal">
                <input type="hidden" name="pilot_id" class="pilotIdCls">
                <input type="hidden" name="selected_date" class="selectDCls">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Schedule Days Off</h4>
                    <span>Add or edit days off.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:25px;">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Day Off Start Time (hrs/mins)</label>
                            <select name="off_start_hour" class="form-control col-md-6 col-xs-12 dispHourCls">
                                <?php
                                $hour = array();
                                for ($i=0; $i < 24; $i++) { 
                                    if($i<10) {
                                        $hour = '0'.$i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    } else {
                                        $hour = $i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <select name="off_start_minute" class="form-control col-md-6 col-xs-12 dispMinutCls">
                                <?php
                                $time = array();
                                for ($i=0; $i < 60; $i++) { 
                                    if($i<10) {
                                        $time = '0'.$i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    } else {
                                        $time = $i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Start Date</label>
                            <input type="text" name="start_date" class="form-control col-md-7 col-xs-12 daysOffStart datePicker" disabled="disabled">
                        </div>

                        <div class="col-md-6">
                            <label>End Date</label>
                            <input type="text" name="end_date" class="form-control col-md-7 col-xs-12 daysOffEnd datePicker">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="daysOffErrorMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="scheduleDayOffBtn">Save Days Off</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Schedule Day Off -->

<!-- Flight Leg -->
<div id="pilotFlightLegModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="flightLegFrm" class="form-horizontal">
                <input type="hidden" name="pilot_id" class="pilotIdCls">
                <input type="hidden" name="selected_date" class="selectDCls">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Flight Leg Details</h4>
                    <span>Add or edit a flight leg.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:25px;">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Start Time (hrs/mins)</label>
                            <select name="start_hour" class="form-control col-md-6 col-xs-12 startHourCls">
                                <?php
                                $hour = array();
                                for ($i=0; $i < 24; $i++) { 
                                    if($i<10) {
                                        $hour = '0'.$i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    } else {
                                        $hour = $i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <select name="start_minute" class="form-control col-md-6 col-xs-12 startMinutCls">
                                <?php
                                $time = array();
                                for ($i=0; $i < 60; $i++) { 
                                    if($i<10) {
                                        $time = '0'.$i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    } else {
                                        $time = $i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Leg Length (hrs/mins)</label>
                            <select name="leg_hour" class="form-control col-md-6 col-xs-12 legHourCls">
                                <?php
                                $hour = array();
                                for ($i=0; $i < 24; $i++) { 
                                    if($i<10) {
                                        $hour = '0'.$i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    } else {
                                        $hour = $i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <select name="leg_minute" class="form-control col-md-6 col-xs-12 legMinutCls">
                                <?php
                                $time = array();
                                for ($i=0; $i < 60; $i++) { 
                                    if($i<10) {
                                        $time = '0'.$i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    } else {
                                        $time = $i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Night FLT</label>
                            <select name="night_flt_hour" class="form-control col-md-6 col-xs-12 fltHourCls">
                                <?php
                                $hour = array();
                                for ($i=0; $i < 24; $i++) { 
                                    if($i<10) {
                                        $hour = '0'.$i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    } else {
                                        $hour = $i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <select name="night_flt_minute" class="form-control col-md-6 col-xs-12 fltMinutCls">
                                <?php
                                $time = array();
                                for ($i=0; $i < 60; $i++) { 
                                    if($i<10) {
                                        $time = '0'.$i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    } else {
                                        $time = $i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>IFR FLT</label>
                            <select name="ifr_flight_hour" class="form-control col-md-6 col-xs-12 ifrHourCls">
                                <?php
                                $hour = array();
                                for ($i=0; $i < 24; $i++) { 
                                    if($i<10) {
                                        $hour = '0'.$i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    } else {
                                        $hour = $i;
                                        echo '<option value="'.$i.'">'.$hour.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <select name="ifr_flight_minute" class="form-control col-md-6 col-xs-12 ifrMinutCls">
                                <?php
                                $time = array();
                                for ($i=0; $i < 60; $i++) { 
                                    if($i<10) {
                                        $time = '0'.$i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    } else {
                                        $time = $i;
                                        echo '<option value="'.$i.'">'.$time.'</option>';
                                    }     
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Approaches</label>
                            <select name="approaches" class="form-control col-md-6 col-xs-12 approachesCls">
                                <option value="">--</option>
                                <option value="vis">VIS</option>
                                <option value="vor">VOR</option>
                                <option value="gps">GPS</option>
                                <option value="loc">LOC</option>
                                <option value="ils">ILS</option>
                                <option value="ndb">NDB</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Landings</label>
                            <select name="landings" class="form-control col-md-6 col-xs-12 landingCls">
                                <option value="">--</option>
                                <option value="day">Day</option>
                                <option value="night">Night</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Duty Position</label>
                            <select name="duty_position" class="form-control col-md-6 col-xs-12 dutyPositionCls">
                                <option value="">--</option>
                                <option value="pic">PIC</option>
                                <option value="sic">SIC</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            Part 135 <input type="checkbox" name="part_135">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="flightLegErrorMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="flightLegBtn">Save Flight Leg</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Flight Leg -->

<!-- Update Duty Times -->
<div id="updateDutyTimesModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="updateDutyTimeFrm" class="form-horizontal">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Duty Times</h4>
                    <span>Add or edit a duty time.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:25px;">
                </div>
                <div class="modal-footer">
                    <span id="updateDutyTimeMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="updateDutyTimeBtn">Save Duty Time</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Duty Times -->

<!-- Update Days Off -->
<div id="updateDaysOffModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="updateDaysOffFrm" class="form-horizontal">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Schedule Days Off</h4>
                    <span>Add or edit days off.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:25px;">
                </div>
                <div class="modal-footer">
                    <span id="updateDaysOffMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="updateDaysOffBtn">Save Days Off</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Days Off -->

<!-- Update Flight Leg -->
<div id="updateFlightLegModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="updateFlightLegFrm" class="form-horizontal">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Flight Leg Details</h4>
                    <span>Add or edit a flight leg.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:25px;">
                </div>
                <div class="modal-footer">
                    <span id="updateFlightLegMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="updateFlightLegBtn">Save Days Off</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Flight Leg -->

<!-- Upload documents -->
<div id="uploadDocsModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="uploadDocsFrm" enctype="multipart/form-data" class="form-horizontal">
                <input type="hidden" name="pilot_id" class="pilotIdCls">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload Files</h4>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:30px 40px 30px 40px">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Browse to upload</label>
                            <input type="file" name="file_name" id="fileNameId" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Document Category</label>
                            <select id="crewDocsCategory" name="document_category" class="form-control">
                                <option value="documents">Crew Documents</option>
                                <option value="training">Training Records</option>
                                <option value="checking">Checking Currency</option>
                                <option value="drug">Drug & Alcohol Testing</option>
                                <option value="pria">PRIA</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Document Type</label>
                            <select id="crewDocuments" name="document_type" class="form-control docNameHide">
                                <option value="Pilot Certificate">Pilot Certificate</option>
                                <option value="Photo ID">Photo ID</option>
                                <option value="Current Medical">Current Medical</option>
                                <option value="Current Passport">Current Passport</option>
                                <option value="Pilot Qualifications Record">Pilot Qualifications Record</option>
                                <option value="Radiotelephone Operators Permit">Radiotelephone Operators Permit</option>
                                <option value="Pilot Resume">Pilot Resume</option>
                                <option value="Company Flight Instructor LOA">Company Flight Instructor LOA</option>
                                <option value="Other">Other</option>
                            </select>

                            <select id="crewTraining" name="document_type" class="form-control docNameHide" style="display:none;" disabled>
                                <option value="Initial New Hire Basic INDOC">Initial New Hire Basic INDOC</option>
                                <option value="Initial Flight/SIM Training">Initial Flight/SIM Training</option>
                                <option value="Annual Recurrent Ground Training">Annual Recurrent Ground Training</option>
                                <option value="Recurrent Flight/SIM Training">Recurrent Flight/SIM Training</option>
                                <option value="RVSM Training">RVSM Training</option>
                                <option value="Instructor/Check Airman Training">Instructor/Check Airman Training</option>
                                <option value="Security Training">Security Training</option>
                                <option value="Other">Other</option>
                            </select>

                            <select id="crewChecking" name="document_type" class="form-control docNameHide" style="display:none;" disabled>
                                <option value="293 (a) 1, 4-8 General Operations">293 (a) 1, 4-8 General Operations</option>
                                <option value="293 (a) 2-3 (b) Aircraft Specific">293 (a) 2-3 (b) Aircraft Specific</option>
                                <option value="297 Instrument Proficiency Check">297 Instrument Proficiency Check</option>
                                <option value="293 (a) 2-3 (b) / 297 Combo Check">293 (a) 2-3 (b) / 297 Combo Check</option>
                                <option value="299 Line check">299 Line check</option>
                                <option value="Other">Other</option>
                            </select>

                            <select id="crewDrug" name="document_type" class="form-control docNameHide" style="display:none;" disabled>
                                <option value="Pre-Employment">Pre-Employment</option>
                                <option value="Random">Random</option>
                                <option value="Reasonable Suspicion">Reasonable Suspicion</option>
                                <option value="Post Accident">Post Accident</option>
                                <option value="Return to Duty">Return to Duty</option>
                                <option value="Follow Up">Follow Up</option>
                                <option value="Other">Other</option>
                            </select>

                            <select id="crewPria" name="document_type" class="form-control docNameHide" style="display:none;" disabled>
                                <option value="Previous Employment Records">Previous Employment Records</option>
                                <option value="FAA Records">FAA Records</option>
                                <option value="Driving Background Check">Driving Background Check</option>
                                <option value="Drug and Alcohol Testing Records">Drug and Alcohol Testing Records</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="docNameContainer" style="display:none;">
                        <div class="col-md-12">
                            <label>Type a Name for the Document</label>
                            <input type="text" name="document_name" id="docNameId" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="form-group" id="docAircraftContainer" style="display:none;">
                        <div class="col-md-12">
                            <label>Select an Aircraft Type Designation</label>
                             <select id="aircraftDocuments" name="aircraft_designation" class="form-control" disabled>
                                <option value="BE20">BE20</option>
                                <option value="P180">P180</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Select a Date for the Document</label>
                            <input type="text" name="document_date" class="form-control datePicker dPCls" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="uploadDocsMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="uploadDocsSaveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Upload documents -->

<!-- Edit details and upload new documents -->
<div id="updateDocsModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="updateDocsFrm" enctype="multipart/form-data" class="form-horizontal">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload Files</h4>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:30px 40px 30px 40px">
                </div>
                <div class="modal-footer">
                    <span id="updateDocsMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="updateDocsSaveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End edit details and upload new documents -->