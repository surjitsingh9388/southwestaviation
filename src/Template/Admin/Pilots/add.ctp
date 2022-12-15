<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router; 

$years   = $pilotComp->getYearsList();
$dlyears = $pilotComp->getDLYearsList();
$months  = $pilotComp->getMonthsList();
$days    = $pilotComp->getDaysList();
$class   = $pilotComp->getClassList();
?>     
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Add Pilot's Detail</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>

        <div class="page-content mt-35" id="pilotStatusCls">
            <div class="tableScroll">
            <?php echo $this->Form->create($pilot, array('class' => 'form-horizontal form-label-left', 'id' => 'frmPilot')); ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Flight Crew Information</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="first_name">First Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('first_name', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'placeholder' => 'First Name', 'label' => false)); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="middle_name">Middle Name</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('middle_name', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Middle Name', 'label' => false)); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="last_name">Last Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('last_name', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'placeholder' => 'Last Name', 'label' => false)); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="email">Email Address <span class="required">*</span></label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('email', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Email Address', 'label' => false)); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="password">Password <span class="required">*</span></label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->password('password', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 
                                            'placeholder' => 'Password', 'maxlength' => 15, 'aria-describedby' => 'passHelp', 'label' => false)); ?>
                                        <small id="passHelp" class="form-text text-muted">
                                            Password length should be 5-15 characters and contain at least one number, one lowercase and one uppercase letter.
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="confirm-password">Confirm Password</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php
                                            echo $this->Form->password('confirm_password', array('class' => 'form-control col-md-7 col-xs-12',
                                            'required' => 'required', 'placeholder' => 'Re-enter Password', 'maxlength' => 15, 'label' => false)); 
                                        ?>  
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="certificate_number">Certificate Number <span class="required">*</span></label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('certificate_number', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Certificate Number', 'label' => false)); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="phone_ext">Phone Ext <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('phone_ext', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'placeholder' => 'Phone Extension', 'label' => false, 'value' => '+1')); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="phone">Primary Phone <span class="required">*</span></label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('phone', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Primary Phone', 'label' => false)); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="secondary_phone">Secondary Phone</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('secondary_phone', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Secondary Phone', 'label' => false)); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="emergency_contact">Emergency Contact</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('emergency_contact', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Emergency Contact', 'label' => false)); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="emergency_phone">Emergency Phone</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('emergency_phone', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Emergency Phone', 'label' => false)); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="role">Role <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('role_id', array('options' => $roles, 'empty' => 'Select Role', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false)); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12">Suspend Account </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->checkbox('suspended', array('label' => '')); ?>
                                    </div>
                                </div>

                                <hr />

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="address_line1">Address <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('addresses.0.address_line1', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'label' => false)); ?>
                                    </div>
                                </div>
                                                        
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="country">Country <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('addresses.0.country_id', array('options' => $countries, 'empty' => 'Select Country', 'required' => 'required', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'id'=>'addresses-0-country-id', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'value' => '231')); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="state">State <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('addresses.0.state_id', array('options' => array(), 'empty' => 'Select State', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'id'=>'addresses-0-state-id', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="city">City <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('addresses.0.city_id', array('options' => array(), 'empty' => 'Select City', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'id'=>'addresses-0-city-id', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="zip_code">Zip Code <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('addresses.0.zip_code', array('class' => 'form-control col-md-7 col-xs-12 numericOnly', 'required' => 'required', 'placeholder' => 'Zip Code', 'label' => false)); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                   

                    <div class="panel-heading">
                        <h3 class="panel-title">Duty Assignments</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-md-2 col-sm-2 col-xs-12" for="director_operation">Director of Operations: </label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php echo $this->Form->checkbox('director_operation', array('label'=>'')); ?>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12" for="chief_pilot">Chief Pilot: </label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php echo $this->Form->checkbox('chief_pilot', array('label'=>'')); ?>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12" for="director_maintenance">Director of Maintenance: </label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <?php echo $this->Form->checkbox('director_maintenance', array('label'=>'')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 col-sm-2 col-xs-12">PIC: </label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <a href="javascript:void(0);" class="btn btn-success dutySpCls" data-title="Captain" data-datype="PIC">Specify</a>
                                <input type="hidden" name="PIC_type1" class="PICtypeCheck1">
                                <input type="hidden" name="PIC_designation1" class="PICdesignation1">
                                <input type="hidden" name="PIC_date_assigned1" class="PICdate_assigned1">
                                <input type="hidden" name="PIC_date_unassigned1" class="PICdate_unassigned1">
                                <input type="hidden" name="PIC_type2" class="PICtypeCheck2">
                                <input type="hidden" name="PIC_designation2" class="PICdesignation2">
                                <input type="hidden" name="PIC_date_assigned2" class="PICdate_assigned2">
                                <input type="hidden" name="PIC_date_unassigned2" class="PICdate_unassigned2">

                                <span class="PICDisplay"></span>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12">SIC: </label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <a href="javascript:void(0);" class="btn btn-success dutySpCls" data-title="First Officer" data-datype="SIC">Specify</a>
                                <input type="hidden" name="SIC_type1" class="SICtypeCheck1">
                                <input type="hidden" name="SIC_designation1" class="SICdesignation1">
                                <input type="hidden" name="SIC_date_assigned1" class="SICdate_assigned1">
                                <input type="hidden" name="SIC_date_unassigned1" class="SICdate_unassigned1">
                                <input type="hidden" name="SIC_type2" class="SICtypeCheck2">
                                <input type="hidden" name="SIC_designation2" class="SICdesignation2">
                                <input type="hidden" name="SIC_date_assigned2" class="SICdate_assigned2">
                                <input type="hidden" name="SIC_date_unassigned2" class="SICdate_unassigned2">

                                <span class="SICDisplay"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 col-sm-2 col-xs-12">Ground Instructor: </label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <a href="javascript:void(0);" class="btn btn-success dutySpCls" data-title="Ground Instructor" data-datype="GI">Specify</a>
                                <input type="hidden" name="GI_type1" class="GItypeCheck1">
                                <input type="hidden" name="GI_designation1" class="GIdesignation1">
                                <input type="hidden" name="GI_date_assigned1" class="GIdate_assigned1">
                                <input type="hidden" name="GI_date_unassigned1" class="GIdate_unassigned1">
                                <input type="hidden" name="GI_type2" class="GItypeCheck2">
                                <input type="hidden" name="GI_designation2" class="GIdesignation2">
                                <input type="hidden" name="GI_date_assigned2" class="GIdate_assigned2">
                                <input type="hidden" name="GI_date_unassigned2" class="GIdate_unassigned2">

                                <span class="GIDisplay"></span>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12">Flight Instructor: </label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <a href="javascript:void(0);" class="btn btn-success dutySpCls" data-title="Flight Instructor" data-datype="FI">Specify</a>
                                <input type="hidden" name="FI_type1" class="FItypeCheck1">
                                <input type="hidden" name="FI_designation1" class="FIdesignation1">
                                <input type="hidden" name="FI_date_assigned1" class="FIdate_assigned1">
                                <input type="hidden" name="FI_date_unassigned1" class="FIdate_unassigned1">
                                <input type="hidden" name="FI_type2" class="FItypeCheck2">
                                <input type="hidden" name="FI_designation2" class="FIdesignation2">
                                <input type="hidden" name="FI_date_assigned2" class="FIdate_assigned2">
                                <input type="hidden" name="FI_date_unassigned2" class="FIdate_unassigned2">

                                <span class="FIDisplay"></span>
                            </div>

                            <label class="col-md-2 col-sm-2 col-xs-12">Check Airmen: </label>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <a href="javascript:void(0);" class="btn btn-success dutySpCls" data-title="Check Airmen" data-datype="CA">Specify</a>
                                <input type="hidden" name="CA_type1" class="CAtypeCheck1">
                                <input type="hidden" name="CA_designation1" class="CAdesignation1">
                                <input type="hidden" name="CA_date_assigned1" class="CAdate_assigned1">
                                <input type="hidden" name="CA_date_unassigned1" class="CAdate_unassigned1">
                                <input type="hidden" name="CA_type2" class="CAtypeCheck2">
                                <input type="hidden" name="CA_designation2" class="CAdesignation2">
                                <input type="hidden" name="CA_date_assigned2" class="CAdate_assigned2">
                                <input type="hidden" name="CA_date_unassigned2" class="CAdate_unassigned2">

                                <span class="CADisplay"></span>
                            </div>
                        </div>
                    </div>

                    <div class="panel-heading">
                        <h3 class="panel-title">Pilot Certificates</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-borderless table-condensed table-hover">
                            <tr>
                                <th width="20%">Certificate Type</th>
                                <th width="10%">Under 40 yrs</th>
                                <th width="15%">Med Class</th>
                                <th width="15%">Limitations</th>
                                <th width="15%">Frequency</th>
                                <th width="15%">Last Completed</th>
                                <th width="10%">Next Due</th>
                            </tr>
                            <tbody>
                                <tr>
                                    <td>Medical</td>
                                    <td></td>
                                    <td>
                                        <?php
                                        echo $this->Form->control('medical_class', array('options' => $class, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('medical_limitation', array('class' => 'form-control', 'label'=>false)); ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('medical_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date certDatePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('medical_last_completed', array('class' => 'form-control', 'label' => false)); 
                                        ?>
                                        </div>
                                        <input type="hidden" name="cert_type" value="months" class="certTypeCls">
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('medical_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="medical_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Passport</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <?php
                                        echo $this->Form->control('passport_frequency', array('options' => $years, 'class' => 'form-control col-md-7 col-xs-12 frequencyM', 'label' => false));
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date certDatePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('passport_last_completed', array('class' => 'form-control', 'label' => false)); 
                                        ?>
                                        </div>
                                        <input type="hidden" name="cert_type" value="years" class="certTypeCls">
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('passport_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="passport_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Drivers License</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <?php
                                        echo $this->Form->control('DL_frequency', array('options' => $dlyears, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 frequencyM', 'label' => false));
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date certDatePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('DL_last_completed', array('class' => 'form-control', 'label' => false)); 
                                        ?>
                                        </div>
                                        <input type="hidden" name="cert_type" value="years" class="certTypeCls">
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('DL_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="DL_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Temporary Pilot Certificates</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <?php
                                        echo $this->Form->control('TPC_frequency', array('options' => $days, 'class' => 'form-control col-md-7 col-xs-12 frequencyM', 'label' => false));
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date certDatePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('TPC_last_completed', array('class' => 'form-control', 'label' => false));
                                        ?>
                                        </div>
                                        <input type="hidden" name="cert_type" value="days" class="certTypeCls">
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('TPC_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="TPC_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="panel-heading">
                        <h3 class="panel-title">Checking</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-borderless table-condensed table-hover">
                            <tr>
                                <th width="5%"></th>
                                <th width="35%">Aircraft Specific 135.293 (a) 2-3 (b)</th>
                                <th width="20%">Base Month</th>
                                <th width="15%">Frequency</th>
                                <th width="15%">Last Completed</th>
                                <th width="10%">Next Due</th>
                            </tr>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('AS_check', array('label'=>'')); ?>
                                    </td>
                                    <td>P180</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('AS_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('AS_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('AS_last_completed', array('class' => 'form-control', 'label' => false)); 
                                        ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('AS_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="AS_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td></td><td colspan="5"><b>Instrument Currency Check</b></td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('ICC_check', array('label'=>'')); ?>
                                    </td>
                                    <td>135.297:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('ICC_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('ICC_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('ICC_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('ICC_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="ICC_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td></td><td colspan="5"><b>Other Required Checks</b></td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('OW_check', array('label'=>'')); ?>
                                    </td>
                                    <td>135.293 (a) 1, 4-8 Oral/Written:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('OW_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('OW_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('OW_last_completed', array('class' => 'form-control', 'label' => false)); 
                                        ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('OW_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="OW_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('LC_check', array('label'=>'')); ?>
                                    </td>
                                    <td>135.299 Line Check:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('LC_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('LC_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php echo $this->Form->Text('LC_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('LC_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="LC_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('APC_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Auto Pilot Check (Single Pilot):</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('APC_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('APC_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('APC_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('APC_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="APC_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('IO_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Instructor Observation:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('IO_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('IO_frequency', array('class' => 'form-control frequencyM', 'label'=>false));
                                        ?>  
                                        </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php echo $this->Form->Text('IO_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('IO_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="IO_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('CAO_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Check Airman Observation:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('CAO_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('CAO_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('CAO_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('CAO_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="CAO_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="panel-heading">
                        <h3 class="panel-title">Training</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-borderless table-condensed table-hover">
                            <tr>
                                <th width="5%"></th>
                                <th width="35%">Aircraft Flight Training</th>
                                <th width="20%">Base Month</th>
                                <th width="15%">Frequency</th>
                                <th width="15%">Last Completed</th>
                                <th width="10%">Next Due</th>
                            </tr>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('AFT_check', array('label'=>'')); ?>
                                    </td>
                                    <td>P180</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('AFT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('AFT_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('AFT_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('AFT_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="AFT_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td></td><td colspan="5"><b>Emergency Drill Training</b></td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('EDT_check', array('label'=>'')); ?>
                                    </td>
                                    <td>P180</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('EDT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('EDT_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php echo $this->Form->Text('EDT_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('EDT_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="EDT_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td></td><td colspan="5"><b>Ground Training</b></td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('CWOT_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Cold Weather Operations Training</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('CWOT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('CWOT_fequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('CWOT_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('CWOT_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="CWOT_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('CRM_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Crew Resource Management (CRM):</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('CRM_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('CRM_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('CRM_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('CRM_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="CRM_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('EFB_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Electronic Flight Bag (EFB):</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('EFB_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('EFB_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('EFB_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('EFB_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="EFB_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('EGT_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Emergency Ground Training:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('EGT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('EGT_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('EGT_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('EGT_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="EGT_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('GIT_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Global/International Training:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('GIT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('GIT_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('GIT_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('GIT_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="GIT_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('Hz_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Hazmat (Will Not Carry):</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('Hz_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('Hz_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('Hz_last_completed', array('class' => 'form-control', 'label' => false)); 
                                        ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('Hz_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="Hz_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('IR_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Indoctrination/Recurrent 135:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('IR_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('IR_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('IR_last_completed', array('class' => 'form-control', 'label' => false)); 
                                        ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('IR_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="IR_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('IRG_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Initial or Recurrent Ground:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('IRG_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('IRG_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('IRG_last_completed', array('class' => 'form-control', 'label' => false)); ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('IRG_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="IRG_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('ICAT_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Instructor/Check Airman Training:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('ICAT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('ICAT_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>     
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('ICAT_last_completed', array('class' => 'form-control', 'label' => false)); 
                                        ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('ICAT_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false));
                                        ?>
                                        <input type="hidden" name="ICAT_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('LBFT_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Lithium Battery Fire Training:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('LBFT_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('LBFT_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>     
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('LBFT_last_completed', array('class' => 'form-control', 'label' => false));
                                        ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('LBFT_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="LBFT_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('RVSM_check', array('label'=>'')); ?>
                                    </td>
                                    <td>RVSM Training:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('RVSM_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('RVSM_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('RVSM_last_completed', array('class' => 'form-control', 'label' => false));
                                        ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('RVSM_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="RVSM_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php echo $this->Form->checkbox('ST_check', array('label'=>'')); ?>
                                    </td>
                                    <td>Security Training:</td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('ST_month', array('options' => $months, 'empty' => 'Select', 'class' => 'form-control col-md-7 col-xs-12 baseM', 'label' => false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        echo $this->Form->control('ST_frequency', array('class' => 'form-control frequencyM', 'label'=>false)); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="input-group date datePicker">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <?php 
                                        echo $this->Form->Text('ST_last_completed', array('class' => 'form-control', 'label' => false));
                                        ?>
                                        </div>
                                    </td>
                                    <td>
                                       <div class="input-group">
                                        <?php
                                        echo $this->Form->Text('ST_nextdue', array('class'=>'form-control dateNextDue', 'label'=>false)); 
                                        ?>
                                        <input type="hidden" name="ST_nextdue_status" class="ndStatus">
                                        </div>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>

                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php
                                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1){
                                    echo $this->Form->button('Save Data', ['type' => 'submit', 'class' => 'btn btn-success']);
                                }
                                echo $this->Form->button('Reset', ['type' => 'reset', 'class' => 'btn btn-primary', 'id' => 'reset', 'id' => 'reset']);
                                echo $this->Form->control('updated_by', array('type' => 'hidden', 'value' => $sessionArray['id'], 'label'=> false));
                                echo $this->Form->control('addresses.0.updated_by', array('type' => 'hidden', 'value' => $sessionUser['id']));
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
            <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<?php echo $this->element('pilots_popup'); ?>

<?php echo $this->Html->script('pilots'); ?>
<script>
$(document).ready(function(){
    // To remove error message on change of select picker
    $('#frmPilot select.selectpicker').on('change', function(e) {
        $('#frmPilot').validate().element($(this));
    });

    $("#frmPilot").validate({
        ignore: [],
        validateHiddenInputs: true,
        rules: {
            'first_name': {
                required: true
            },
            'last_name': {
                required: true
            },
            'email': {
                required: true,
                checkEmail: true,
                remote: {
                    url: "<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'isEmailExist']); ?>",
                    type: "POST"
                }
            },
            'password': {
                required: true,
                checkPassword:true
            },
            'confirm_password': {
                required: true,
                equalTo: '[name="password"]'
            },
            'certificate_number': {
                required: true,
            },
            'phone_ext': {
                required: true,
                checkExtension: true,
                maxlength: 6
            },
            'phone': {
                required: true,
                checkPhone: true,
                minlength: 10,
                maxlength: 12
            },
            'role': {
                required: true
            },
            'addresses[0][address_line1]': {
                required: true
            },
            'addresses[0][country_id]': {
                required: true
            },
            'addresses[0][state_id]': {
                required: true
            },
            'addresses[0][city_id]': {
                required: true
            },
            'addresses[0][zip_code]': {
                required: true,
                maxlength: 6
            },       
        },
        messages: {
            'first_name': {
                required: "Please enter first name."
            },
            'last_name': {
                required: "Please enter last name."
            },
            'email': {
                remote: "Email already registered."
            },
            'confirm_password': {
                equalTo: "Confirm password should be same as password."
            },
            'certificate_number': {
                required: "Please enter certificate number."
            },
            'phone_ext': {
                maxlength: "Phone extension could not be more than 5 digits."
            },
            'phone': {
                required: "Please enter primary phone number.",
                minlength: "Phone number must consist of atleast 10 digits.",
                maxlength: "Invalid phone number."
            },
            'addresses[0][zip_code]': {
                maxlength: "Zip code could not be more than 6 digits."
            },
        },
        errorClass: "error",
        errorElement: "label"
    }); 
           
    $('#reset').click(function() {
       var validator = $("#frmPilot").validate();
       validator.resetForm();
    });
});

var getCitiesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getCitiesList']); ?>";
var getStatesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getStatesList']); ?>";
//var isEmailExist  = "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'isEmailExist']); ?>";
var getNextDue = "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'getNextDue']); ?>";
var certNextDue = "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'certNextDue']); ?>";
</script>