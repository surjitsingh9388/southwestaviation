<?php
$years  = $pilotComp->getYearsList();
$months = $pilotComp->getMonthsList();
$days   = $pilotComp->getDaysList();
$class  = $pilotComp->getClassList();

$daId = @$pilot['duty_assignments'][0]['id'];
$pilotId = @$pilot['duty_assignments'][0]['pilot_id'];
?>

<div class="panel-heading">
    <div class="panel-title">Duty Assignments</div>
</div>
<div class="panel-body">
    <div class="form-group">
        <label class="col-sm-3 col-xs-6" for="director_operation">Director of Operations: </label>
        <div class="col-sm-3 col-xs-6">
            <?php echo $this->Form->checkbox('director_operation', array('checked'=>@$pilot['duty_assignments'][0]['director_operation'], 'label'=>'')); ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 col-xs-6" for="chief_pilot">Chief Pilot: </label>
        <div class="col-sm-3 col-xs-6">
            <?php echo $this->Form->checkbox('chief_pilot', array('checked'=>@$pilot['duty_assignments'][0]['chief_pilot'], 'label'=>'')); ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 col-xs-6" for="director_maintenance">Director of Maintenance: </label>
        <div class="col-sm-3 col-xs-6">
            <?php echo $this->Form->checkbox('director_maintenance', array('checked'=>@$pilot['duty_assignments'][0]['director_maintenance'], 'label'=>'')); ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 col-xs-6">PIC: </label>
        <div class="col-sm-3 col-xs-6">
            <a href="javascript:void(0);" class="btn btn-success dutySpUpCls" data-title="Captain" data-datype="PIC" data-da_id="<?php echo $daId; ?>" data-pilot_id="<?php echo $pilotId; ?>">Specify</a>
            <input type="hidden" name="PIC_type1" class="PICtypeCheck1" value="<?php echo @$pilot['duty_assignments'][0]['PIC_type1']; ?>">
            <input type="hidden" name="PIC_designation1" class="PICdesignation1" value="<?php echo @$pilot['duty_assignments'][0]['PIC_designation1']; ?>">
            <input type="hidden" name="PIC_date_assigned1" class="PICdate_assigned1" value="<?php echo !empty(@$pilot['duty_assignments'][0]['PIC_date_assigned1']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['PIC_date_assigned1'])) : ''; ?>">
            <input type="hidden" name="PIC_date_unassigned1" class="PICdate_unassigned1" value="<?php echo !empty(@$pilot['duty_assignments'][0]['PIC_date_unassigned1']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['PIC_date_unassigned1'])) : ''; ?>">
            <input type="hidden" name="PIC_type2" class="PICtypeCheck2" value="<?php echo @$pilot['duty_assignments'][0]['PIC_type2']; ?>">
            <input type="hidden" name="PIC_designation2" class="PICdesignation2" value="<?php echo @$pilot['duty_assignments'][0]['PIC_designation2']; ?>">
            <input type="hidden" name="PIC_date_assigned2" class="PICdate_assigned2" value="<?php echo !empty(@$pilot['duty_assignments'][0]['PIC_date_assigned2']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['PIC_date_assigned2'])) : ''; ?>">
            <input type="hidden" name="PIC_date_unassigned2" class="PICdate_unassigned2" value="<?php echo !empty(@$pilot['duty_assignments'][0]['PIC_date_unassigned2']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['PIC_date_unassigned2'])) : ''; ?>">
        </div>
        <div class="col-sm-3 col-xs-6 PICDisplay">
            <?php
            if(@$pilot['duty_assignments'][0]['PIC_type1'] == 'true' && @$pilot['duty_assignments'][0]['PIC_type2'] == 'true') {
                echo @$pilot['duty_assignments'][0]['PIC_designation1'].', '.@$pilot['duty_assignments'][0]['PIC_designation2'];
            } elseif(@$pilot['duty_assignments'][0]['PIC_type1'] == 'true') {
                echo @$pilot['duty_assignments'][0]['PIC_designation1'];
            } elseif(@$pilot['duty_assignments'][0]['PIC_type2'] == 'true') {
                echo @$pilot['duty_assignments'][0]['PIC_designation2'];
            } 
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 col-xs-6">SIC: </label>
        <div class="col-sm-3 col-xs-6">
            <a href="javascript:void(0);" class="btn btn-success dutySpUpCls" data-title="First Officer" data-datype="SIC" data-da_id="<?php echo $daId; ?>" data-pilot_id="<?php echo $pilotId; ?>">Specify</a>
            <input type="hidden" name="SIC_type1" class="SICtypeCheck1" value="<?php echo @$pilot['duty_assignments'][0]['SIC_type1']; ?>">
            <input type="hidden" name="SIC_designation1" class="SICdesignation1" value="<?php echo @$pilot['duty_assignments'][0]['SIC_designation1']; ?>">
            <input type="hidden" name="SIC_date_assigned1" class="SICdate_assigned1" value="<?php echo !empty(@$pilot['duty_assignments'][0]['SIC_date_assigned1']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['SIC_date_assigned1'])) : ''; ?>">
            <input type="hidden" name="SIC_date_unassigned1" class="SICdate_unassigned1" value="<?php echo !empty(@$pilot['duty_assignments'][0]['SIC_date_unassigned1']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['SIC_date_unassigned1'])) : ''; ?>">
            <input type="hidden" name="SIC_type2" class="SICtypeCheck2" value="<?php echo @$pilot['duty_assignments'][0]['SIC_type2']; ?>">
            <input type="hidden" name="SIC_designation2" class="SICdesignation2" value="<?php echo @$pilot['duty_assignments'][0]['SIC_designation2']; ?>">
            <input type="hidden" name="SIC_date_assigned2" class="SICdate_assigned2" value="<?php echo !empty(@$pilot['duty_assignments'][0]['SIC_date_assigned2']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['SIC_date_assigned2'])) : ''; ?>">
            <input type="hidden" name="SIC_date_unassigned2" class="SICdate_unassigned2" value="<?php echo !empty(@$pilot['duty_assignments'][0]['SIC_date_unassigned2']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['SIC_date_unassigned2'])) : ''; ?>">
        </div>
        <div class="col-sm-3 col-xs-6 SICDisplay">
            <?php
            if(@$pilot['duty_assignments'][0]['SIC_type1'] == 'true' && @$pilot['duty_assignments'][0]['SIC_type2'] == 'true') {
                echo @$pilot['duty_assignments'][0]['SIC_designation1'].', '.@$pilot['duty_assignments'][0]['SIC_designation2'];
            } elseif(@$pilot['duty_assignments'][0]['SIC_type1'] == 'true') {
                echo @$pilot['duty_assignments'][0]['SIC_designation1'];
            } elseif(@$pilot['duty_assignments'][0]['SIC_type2'] == 'true') { 
                echo @$pilot['duty_assignments'][0]['SIC_designation2'];
            } 
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 col-xs-6">Ground Instructor: </label>
        <div class="col-sm-3 col-xs-6">
            <a href="javascript:void(0);" class="btn btn-success dutySpUpCls" data-title="Ground Instructor" data-datype="GI" data-da_id="<?php echo $daId; ?>" data-pilot_id="<?php echo $pilotId; ?>">Specify</a>
            <input type="hidden" name="GI_type1" class="GItypeCheck1" value="<?php echo @$pilot['duty_assignments'][0]['GI_type1']; ?>">
            <input type="hidden" name="GI_designation1" class="GIdesignation1" value="<?php echo @$pilot['duty_assignments'][0]['GI_designation1']; ?>">
            <input type="hidden" name="GI_date_assigned1" class="GIdate_assigned1" value="<?php echo !empty(@$pilot['duty_assignments'][0]['GI_date_assigned1']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['GI_date_assigned1'])) : ''; ?>">
            <input type="hidden" name="GI_date_unassigned1" class="GIdate_unassigned1" value="<?php echo !empty(@$pilot['duty_assignments'][0]['GI_date_unassigned1']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['GI_date_unassigned1'])) : ''; ?>">
            <input type="hidden" name="GI_type2" class="GItypeCheck2" value="<?php echo @$pilot['duty_assignments'][0]['GI_type2']; ?>">
            <input type="hidden" name="GI_designation2" class="GIdesignation2" value="<?php echo @$pilot['duty_assignments'][0]['GI_designation2']; ?>">
            <input type="hidden" name="GI_date_assigned2" class="GIdate_assigned2" value="<?php echo !empty(@$pilot['duty_assignments'][0]['GI_date_assigned2']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['GI_date_assigned2'])) : ''; ?>">
            <input type="hidden" name="GI_date_unassigned2" class="GIdate_unassigned2" value="<?php echo !empty(@$pilot['duty_assignments'][0]['GI_date_unassigned2']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['GI_date_unassigned2'])) : ''; ?>">
        </div>
        <div class="col-sm-3 col-xs-6 GIDisplay">
            <?php
            if(@$pilot['duty_assignments'][0]['GI_type1'] == 'true' && @$pilot['duty_assignments'][0]['GI_type2'] == 'true') {
                echo @$pilot['duty_assignments'][0]['GI_designation1'].', '.@$pilot['duty_assignments'][0]['GI_designation2'];
            } elseif(@$pilot['duty_assignments'][0]['GI_type1'] == 'true') {
                echo @$pilot['duty_assignments'][0]['GI_designation1'];
            } elseif(@$pilot['duty_assignments'][0]['GI_type2'] == 'true') { 
                echo @$pilot['duty_assignments'][0]['GI_designation2'];
            } 
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 col-xs-6">Flight Instructor: </label>
        <div class="col-sm-3 col-xs-6">
            <a href="javascript:void(0);" class="btn btn-success dutySpUpCls" data-title="Flight Instructor" data-datype="FI" data-da_id="<?php echo $daId; ?>" data-pilot_id="<?php echo $pilotId; ?>">Specify</a>
            <input type="hidden" name="FI_type1" class="FItypeCheck1" value="<?php echo @$pilot['duty_assignments'][0]['FI_type1']; ?>">
            <input type="hidden" name="FI_designation1" class="FIdesignation1" value="<?php echo @$pilot['duty_assignments'][0]['FI_designation1']; ?>">
            <input type="hidden" name="FI_date_assigned1" class="FIdate_assigned1" value="<?php echo !empty(@$pilot['duty_assignments'][0]['FI_date_assigned1']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['FI_date_assigned1'])) : ''; ?>">
            <input type="hidden" name="FI_date_unassigned1" class="FIdate_unassigned1" value="<?php echo !empty(@$pilot['duty_assignments'][0]['FI_date_unassigned1']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['FI_date_unassigned1'])) : ''; ?>">
            <input type="hidden" name="FI_type2" class="FItypeCheck2" value="<?php echo @$pilot['duty_assignments'][0]['FI_type2']; ?>">
            <input type="hidden" name="FI_designation2" class="FIdesignation2" value="<?php echo @$pilot['duty_assignments'][0]['FI_designation2']; ?>">
            <input type="hidden" name="FI_date_assigned2" class="FIdate_assigned2" value="<?php echo !empty(@$pilot['duty_assignments'][0]['FI_date_assigned2']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['FI_date_assigned2'])) : ''; ?>">
            <input type="hidden" name="FI_date_unassigned2" class="FIdate_unassigned2" value="<?php echo !empty(@$pilot['duty_assignments'][0]['FI_date_unassigned2']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['FI_date_unassigned2'])) : ''; ?>">
        </div>
        <div class="col-sm-3 col-xs-6 FIDisplay">
            <?php
            if(@$pilot['duty_assignments'][0]['FI_type1'] == 'true' && @$pilot['duty_assignments'][0]['FI_type2'] == 'true') {
                echo @$pilot['duty_assignments'][0]['FI_designation1'].', '.@$pilot['duty_assignments'][0]['FI_designation2'];
            } elseif(@$pilot['duty_assignments'][0]['FI_type1'] == 'true') {
                echo @$pilot['duty_assignments'][0]['FI_designation1'];
            } elseif(@$pilot['duty_assignments'][0]['FI_type2'] == 'true') { 
                echo @$pilot['duty_assignments'][0]['FI_designation2'];
            } 
            ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 col-xs-6">Check Airmen: </label>
        <div class="col-sm-3 col-xs-6">
            <a href="javascript:void(0);" class="btn btn-success dutySpUpCls" data-title="Check Airmen" data-datype="CA" data-da_id="<?php echo $daId; ?>" data-pilot_id="<?php echo $pilotId; ?>">Specify</a>
            <input type="hidden" name="CA_type1" class="CAtypeCheck1" value="<?php echo @$pilot['duty_assignments'][0]['CA_type1']; ?>">
            <input type="hidden" name="CA_designation1" class="CAdesignation1" value="<?php echo @$pilot['duty_assignments'][0]['CA_designation1']; ?>">
            <input type="hidden" name="CA_date_assigned1" class="CAdate_assigned1" value="<?php echo !empty(@$pilot['duty_assignments'][0]['CA_date_assigned1']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['CA_date_assigned1'])) : ''; ?>">
            <input type="hidden" name="CA_date_unassigned1" class="CAdate_unassigned1" value="<?php echo !empty(@$pilot['duty_assignments'][0]['CA_date_unassigned1']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['CA_date_unassigned1'])) : ''; ?>">
            <input type="hidden" name="CA_type2" class="CAtypeCheck2" value="<?php echo @$pilot['duty_assignments'][0]['CA_type2']; ?>">
            <input type="hidden" name="CA_designation2" class="CAdesignation2" value="<?php echo @$pilot['duty_assignments'][0]['CA_designation2']; ?>">
            <input type="hidden" name="CA_date_assigned2" class="CAdate_assigned2" value="<?php echo !empty(@$pilot['duty_assignments'][0]['CA_date_assigned2']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['CA_date_assigned2'])) : ''; ?>">
            <input type="hidden" name="CA_date_unassigned2" class="CAdate_unassigned2" value="<?php echo !empty(@$pilot['duty_assignments'][0]['CA_date_unassigned2']) ? date('m/d/Y', strtotime(@$pilot['duty_assignments'][0]['CA_date_unassigned2'])) : ''; ?>">
        </div>
        <div class="col-sm-3 col-xs-6 CADisplay">
            <?php
            if(@$pilot['duty_assignments'][0]['CA_type1'] == 'true' && @$pilot['duty_assignments'][0]['CA_type2'] == 'true') {
                echo @$pilot['duty_assignments'][0]['CA_designation1'].', '.@$pilot['duty_assignments'][0]['CA_designation2'];
            } elseif(@$pilot['duty_assignments'][0]['CA_type1'] == 'true') {
                echo @$pilot['duty_assignments'][0]['CA_designation1'];
            } elseif(@$pilot['duty_assignments'][0]['CA_type2'] == 'true') { 
                echo @$pilot['duty_assignments'][0]['CA_designation2'];
            } 
            ?>
        </div>
    </div>
</div>
