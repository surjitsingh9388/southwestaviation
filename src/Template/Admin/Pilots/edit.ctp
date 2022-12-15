<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;
?>    
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Update Pilot's Detail</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>

        <div class="page-content mt-35" id="pilotStatusCls">
            <div class="tableScroll">
            <?php echo $this->Form->create($pilot, array('class' => 'form-horizontal form-label-left', 'id' => 'frmUpdatePilot')); ?>
            <?php echo $this->Form->control('user_id', array('type' => 'hidden', 'value' => $pilot->user->id)); ?>
                <?php
                //Pilot certificate
                $mediNDue = $pilotComp->nextdueProcess($pilot['pilot_certificates'][0], 'medical', 'mm');
                $passNDue = $pilotComp->nextdueProcess($pilot['pilot_certificates'][0], 'passport', 'yy');
                $dlNDue   = $pilotComp->nextdueProcess($pilot['pilot_certificates'][0], 'DL', 'yy');
                $tpcNDue  = $pilotComp->nextdueProcess($pilot['pilot_certificates'][0], 'TPC', 'dd');

                $pilotCert = '';
                $certStatus = '';
                if((!empty($mediNDue['colorcls']) && $mediNDue['colorcls'] == 'ndred') || (!empty($passNDue['colorcls']) && $passNDue['colorcls'] == 'ndred') || (!empty($dlNDue['colorcls']) && $dlNDue['colorcls'] == 'ndred') || (!empty($tpcNDue['colorcls']) && $tpcNDue['colorcls'] == 'ndred')) {
                    
                    $pilotCert  = '<i class="fa fa-warning fa-2x ndred" aria-hidden="true"></i>';
                    $certStatus = 'red';

                } elseif((!empty($mediNDue['colorcls']) && $mediNDue['colorcls'] == 'ndyellow') || (!empty($passNDue['colorcls']) && $passNDue['colorcls'] == 'ndyellow') || (!empty($dlNDue['colorcls']) && $dlNDue['colorcls'] == 'ndyellow') || (!empty($tpcNDue['colorcls']) && $tpcNDue['colorcls'] == 'ndyellow')) {
                    
                    $pilotCert  = '<i class="fa fa-warning fa-1x ndyellow" aria-hidden="true"></i>';
                    $certStatus = 'yellow';

                } elseif((!empty($mediNDue['colorcls']) && $mediNDue['colorcls'] == 'ndblue') || (!empty($passNDue['colorcls']) && $passNDue['colorcls'] == 'ndblue') || (!empty($dlNDue['colorcls']) && $dlNDue['colorcls'] == 'ndblue') || (!empty($tpcNDue['colorcls']) && $tpcNDue['colorcls'] == 'ndblue')) {
                    
                    $pilotCert  = '<i class="fa fa-warning fa-2x ndblue" aria-hidden="true"></i>';
                    $certStatus = 'blue';

                } elseif((!empty($mediNDue['colorcls']) && $mediNDue['colorcls'] == 'ndgreen') || (!empty($passNDue['colorcls']) && $passNDue['colorcls'] == 'ndgreen') || (!empty($dlNDue['colorcls']) && $dlNDue['colorcls'] == 'ndgreen') || (!empty($tpcNDue['colorcls']) && $tpcNDue['colorcls'] == 'ndgreen')) {
                    
                    $pilotCert  = '<i class="fa fa-check-circle fa-2x ndgreen" aria-hidden="true"></i>';
                    $certStatus = 'green';

                } else {
                    $pilotCert  = '<i class="fa fa-warning fa-2x" aria-hidden="true"></i>';
                    $certStatus = 'black';
                }

                //Pilot Checking
                $asNDue  = $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'AS');
                $iccNDue = $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'ICC');
                $owNDue  = $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'OW');
                $lcNDue  = $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'LC');
                $apcNDue = $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'APC');
                $ioNDue  = $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'IO');
                $caoNDue = $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'CAO');

                $pilotChecking = '';
                $checkStatus   = '';
                if((!empty($asNDue['colorcls']) && $asNDue['colorcls'] == 'ndred') || (!empty($iccNDue['colorcls']) && $iccNDue['colorcls'] == 'ndred') || (!empty($owNDue['colorcls']) && $owNDue['colorcls'] == 'ndred') || (!empty($lcNDue['colorcls']) && $lcNDue['colorcls'] == 'ndred') || (!empty($apcNDue['colorcls']) && $apcNDue['colorcls'] == 'ndred') || (!empty($ioNDue['colorcls']) && $ioNDue['colorcls'] == 'ndred') || (!empty($caoNDue['colorcls']) && $caoNDue['colorcls'] == 'ndred')) {
                    
                    $pilotChecking = '<i class="fa fa-warning fa-2x ndred" aria-hidden="true"></i>';
                    $checkStatus   = 'red';

                } elseif((!empty($asNDue['colorcls']) && $asNDue['colorcls'] == 'ndyellow') || (!empty($iccNDue['colorcls']) && $iccNDue['colorcls'] == 'ndyellow') || (!empty($owNDue['colorcls']) && $owNDue['colorcls'] == 'ndyellow') || (!empty($lcNDue['colorcls']) && $lcNDue['colorcls'] == 'ndyellow') || (!empty($apcNDue['colorcls']) && $apcNDue['colorcls'] == 'ndyellow') || (!empty($ioNDue['colorcls']) && $ioNDue['colorcls'] == 'ndyellow') || (!empty($caoNDue['colorcls']) && $caoNDue['colorcls'] == 'ndyellow')) {
                    
                    $pilotChecking = '<i class="fa fa-warning fa-1x ndyellow" aria-hidden="true"></i>';
                    $checkStatus   = 'yellow';

                } elseif((!empty($asNDue['colorcls']) && $asNDue['colorcls'] == 'ndblue') || (!empty($iccNDue['colorcls']) && $iccNDue['colorcls'] == 'ndblue') || (!empty($owNDue['colorcls']) && $owNDue['colorcls'] == 'ndblue') || (!empty($lcNDue['colorcls']) && $lcNDue['colorcls'] == 'ndblue') || (!empty($apcNDue['colorcls']) && $apcNDue['colorcls'] == 'ndblue') || (!empty($ioNDue['colorcls']) && $ioNDue['colorcls'] == 'ndblue') || (!empty($caoNDue['colorcls']) && $caoNDue['colorcls'] == 'ndblue')) {
                    
                    $pilotChecking = '<i class="fa fa-warning fa-2x ndblue" aria-hidden="true"></i>';
                    $checkStatus   = 'blue';

                } elseif((!empty($asNDue['colorcls']) && $asNDue['colorcls'] == 'ndgreen') || (!empty($iccNDue['colorcls']) && $iccNDue['colorcls'] == 'ndgreen') || (!empty($owNDue['colorcls']) && $owNDue['colorcls'] == 'ndgreen') || (!empty($lcNDue['colorcls']) && $lcNDue['colorcls'] == 'ndgreen') || (!empty($apcNDue['colorcls']) && $apcNDue['colorcls'] == 'ndgreen') || (!empty($ioNDue['colorcls']) && $ioNDue['colorcls'] == 'ndgreen') || (!empty($caoNDue['colorcls']) && $caoNDue['colorcls'] == 'ndgreen')) {
                    
                    $pilotChecking = '<i class="fa fa-check-circle fa-2x ndgreen" aria-hidden="true"></i>';
                    $checkStatus   = 'green';

                } else {
                    $pilotChecking = '<i class="fa fa-warning fa-2x" aria-hidden="true"></i>';
                    $checkStatus   = 'black';
                }

                //Pilot Training
                $aftNDue  = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'AFT');
                $edtNDue  = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'EDT');
                $cwotNDue = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'CWOT');
                $crmNDue  = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'CRM');
                $efbNDue  = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'EFB');
                $egtNDue  = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'EGT');
                $gitNDue  = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'GIT');
                $hzNDue   = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'Hz');
                $irNDue   = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'IR');
                $irgNDue  = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'IRG');
                $icatNDue = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'ICAT');
                $lbftNDue = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'LBFT');
                $rvsmNDue = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'RVSM');
                $stNDue   = $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'ST');

                $pilotTraining = '';
                $trainStatus   = '';
                if((!empty($aftNDue['colorcls']) && $aftNDue['colorcls'] == 'ndred') || (!empty($edtNDue['colorcls']) && $edtNDue['colorcls'] == 'ndred') || (!empty($cwotNDue['colorcls']) && $cwotNDue['colorcls'] == 'ndred') || (!empty($crmNDue['colorcls']) && $crmNDue['colorcls'] == 'ndred') || (!empty($efbNDue['colorcls']) && $efbNDue['colorcls'] == 'ndred') || (!empty($egtNDue['colorcls']) && $egtNDue['colorcls'] == 'ndred') || (!empty($gitNDue['colorcls']) && $gitNDue['colorcls'] == 'ndred') || (!empty($hzNDue['colorcls']) && $hzNDue['colorcls'] == 'ndred') || (!empty($irNDue['colorcls']) && $irNDue['colorcls'] == 'ndred') || (!empty($irgNDue['colorcls']) && $irgNDue['colorcls'] == 'ndred') || (!empty($icatNDue['colorcls']) && $icatNDue['colorcls'] == 'ndred') || (!empty($lbftNDue['colorcls']) && $lbftNDue['colorcls'] == 'ndred') || (!empty($rvsmNDue['colorcls']) && $rvsmNDue['colorcls'] == 'ndred') || (!empty($stNDue['colorcls']) && $stNDue['colorcls'] == 'ndred')) {
                    
                    $pilotTraining = '<i class="fa fa-warning fa-2x ndred" aria-hidden="true"></i>';
                    $trainStatus   = 'red';

                } elseif((!empty($aftNDue['colorcls']) && $aftNDue['colorcls'] == 'ndyellow') || (!empty($edtNDue['colorcls']) && $edtNDue['colorcls'] == 'ndyellow') || (!empty($cwotNDue['colorcls']) && $cwotNDue['colorcls'] == 'ndyellow') || (!empty($crmNDue['colorcls']) && $crmNDue['colorcls'] == 'ndyellow') || (!empty($efbNDue['colorcls']) && $efbNDue['colorcls'] == 'ndyellow') || (!empty($egtNDue['colorcls']) && $egtNDue['colorcls'] == 'ndyellow') || (!empty($gitNDue['colorcls']) && $gitNDue['colorcls'] == 'ndyellow') || (!empty($hzNDue['colorcls']) && $hzNDue['colorcls'] == 'ndyellow') || (!empty($irNDue['colorcls']) && $irNDue['colorcls'] == 'ndyellow') || (!empty($irgNDue['colorcls']) && $irgNDue['colorcls'] == 'ndyellow') || (!empty($icatNDue['colorcls']) && $icatNDue['colorcls'] == 'ndyellow') || (!empty($lbftNDue['colorcls']) && $lbftNDue['colorcls'] == 'ndyellow') || (!empty($rvsmNDue['colorcls']) && $rvsmNDue['colorcls'] == 'ndyellow') || (!empty($stNDue['colorcls']) && $stNDue['colorcls'] == 'ndyellow')) {
                    
                    $pilotTraining = '<i class="fa fa-warning fa-1x ndyellow" aria-hidden="true"></i>';
                    $trainStatus   = 'yellow';

                } elseif((!empty($aftNDue['colorcls']) && $aftNDue['colorcls'] == 'ndblue') || (!empty($edtNDue['colorcls']) && $edtNDue['colorcls'] == 'ndblue') || (!empty($cwotNDue['colorcls']) && $cwotNDue['colorcls'] == 'ndblue') || (!empty($crmNDue['colorcls']) && $crmNDue['colorcls'] == 'ndblue') || (!empty($efbNDue['colorcls']) && $efbNDue['colorcls'] == 'ndblue') || (!empty($egtNDue['colorcls']) && $egtNDue['colorcls'] == 'ndblue') || (!empty($gitNDue['colorcls']) && $gitNDue['colorcls'] == 'ndblue') || (!empty($hzNDue['colorcls']) && $hzNDue['colorcls'] == 'ndblue') || (!empty($irNDue['colorcls']) && $irNDue['colorcls'] == 'ndblue') || (!empty($irgNDue['colorcls']) && $irgNDue['colorcls'] == 'ndblue') || (!empty($icatNDue['colorcls']) && $icatNDue['colorcls'] == 'ndblue') || (!empty($lbftNDue['colorcls']) && $lbftNDue['colorcls'] == 'ndblue') || (!empty($rvsmNDue['colorcls']) && $rvsmNDue['colorcls'] == 'ndblue') || (!empty($stNDue['colorcls']) && $stNDue['colorcls'] == 'ndblue')) {
                    
                    $pilotTraining = '<i class="fa fa-warning fa-2x ndblue" aria-hidden="true"></i>';
                    $trainStatus   = 'blue';

                } elseif((!empty($aftNDue['colorcls']) && $aftNDue['colorcls'] == 'ndgreen') || (!empty($edtNDue['colorcls']) && $edtNDue['colorcls'] == 'ndgreen') || (!empty($cwotNDue['colorcls']) && $cwotNDue['colorcls'] == 'ndgreen') || (!empty($crmNDue['colorcls']) && $crmNDue['colorcls'] == 'ndgreen') || (!empty($efbNDue['colorcls']) && $efbNDue['colorcls'] == 'ndgreen') || (!empty($egtNDue['colorcls']) && $egtNDue['colorcls'] == 'ndgreen') || (!empty($gitNDue['colorcls']) && $gitNDue['colorcls'] == 'ndgreen') || (!empty($hzNDue['colorcls']) && $hzNDue['colorcls'] == 'ndgreen') || (!empty($irNDue['colorcls']) && $irNDue['colorcls'] == 'ndgreen') || (!empty($irgNDue['colorcls']) && $irgNDue['colorcls'] == 'ndgreen') || (!empty($icatNDue['colorcls']) && $icatNDue['colorcls'] == 'ndgreen') || (!empty($lbftNDue['colorcls']) && $lbftNDue['colorcls'] == 'ndgreen') || (!empty($rvsmNDue['colorcls']) && $rvsmNDue['colorcls'] == 'ndgreen') || (!empty($stNDue['colorcls']) && $stNDue['colorcls'] == 'ndgreen')) {
                    
                    $pilotTraining = '<i class="fa fa-check-circle fa-2x ndgreen" aria-hidden="true"></i>';
                    $trainStatus   = 'green';

                } else {
                    $pilotTraining = '<i class="fa fa-warning fa-2x" aria-hidden="true"></i>';
                    $trainStatus   = 'black';
                }
                ?>

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
                                        <?php echo $this->Form->control('first_name', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'placeholder' => 'First Name', 'label' => false, 'value'=>$pilot->user->first_name)); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="middle_name">Middle Name
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('middle_name', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Middle Name', 'label' => false, 'value'=>$pilot->user->middle_name)); ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="last_name">Last Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('last_name', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'placeholder' => 'Last Name', 'label' => false, 'value'=>$pilot->user->last_name)); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="email">Email Address <span class="required">*</span></label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('email', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Email Address', 'label' => false, 'value'=>$pilot->user->email)); ?>
                                    </div>
                                </div>

                                <?php 
                                //if (!isset($action)) {
                                    //if ((($user->id == 1) && ($user->id == $user->sessionUser)) || ($user->id != 1)) { 
                                    ?>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12" for="password">Password </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $this->Form->control('new_password', array('type' => 'password', 'class' => 'form-control col-md-7 col-xs-12', 
                                                    'placeholder' => 'Password', 'maxlength' => 15, 'aria-describedby' => 'passHelp', 'label' => false)); ?>
                                                <small id="passHelp" class="form-text text-muted">
                                                    Password length should be 5-15 characters and contain at least one number, one lowercase and one uppercase letter.
                                                </small>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 col-sm-4 col-xs-12" for="confirm_password">Confirm Password</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $this->Form->control('confirm_password', array('type' => 'password', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Re-enter Password', 'maxlength' => 15, 'label' => false)); ?>
                                            </div>
                                        </div>
                                <?php 
                                   //} 
                                //} 
                                ?>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="certificate_number">Certificate Number <span class="required">*</span></label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php
                                        $certificate = '';
                                        if(!empty($pilot['certificate_number']) && $pilot['certificate_number'] != '000') { 
                                            $certificate = $pilot['certificate_number'];
                                        } 
                                        echo $this->Form->control('certificate_number', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Certificate Number', 'label' => false, 'value'=>$certificate)); 
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="phone_ext">Phone Ext <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php
                                            if (isset($pilot->user->phone_ext)) {
                                                $phoneExt = !empty($pilot->user->phone_ext) ? $pilot->user->phone_ext : '+1';
                                            } else {
                                                $phoneExt = '+1';
                                            }
                                            echo $this->Form->control('phone_ext', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Phone Extension', 'requred' => 'required', 'label' => false, 'value' => $phoneExt)); 
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="phone">Primary Phone <span class="required">*</span></label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('phone', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Primary Phone', 'label' => false, 'value'=>$pilot->user->phone)); ?>
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
                                        <?php echo $this->Form->control('role_id', array('options' => $roles, 'empty' => 'Select Role', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'value'=>$pilot->user->role_id)); ?>
                                    </div>
                                </div>

                                <?php if(!isset($action)){ ?>
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12">Suspend Account </label>
                                    <div class="col-md-8 col-sm-8 col-xs-6">
                                        <?php 
                                            echo $this->Form->checkbox('suspended', array('label' => '')); 
                                            echo $this->Form->control('previous_suspended_status', array('type' => 'hidden', 'value' => $pilot->user->suspended));
                                        ?>
                                    </div>
                                </div>
                                <?php } ?>

                                <hr />

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="address_line1">Address <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php
                                        echo $this->Form->control('addresses.0.address_line1', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'label' => false, 'value'=>!empty($pilot->user->addresses[0]->address_line1) ? $pilot->user->addresses[0]->address_line1 : '')); 
                                        ?>
                                    </div>
                                </div>
                                                        
                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="country">Country <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php 
                                            if (isset($pilot->user->addresses[0]->country_id)) {
                                                echo $this->Form->control('addresses.0.country_id', array('options' => $countries, 'empty' => 'Select Country', 'required' => 'required', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'value'=>$pilot->user->addresses[0]->country_id));
                                            } else {
                                                echo $this->Form->control('addresses.0.country_id', array('options' => $countries, 'empty' => 'Select Country', 'required' => 'required', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'value' => '231', 'label' => false));
                                            }
                                             ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="state">State <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('addresses.0.state_id', array('options' => $states, 'empty' => 'Select State', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'value'=>!empty($pilot->user->addresses[0]->state_id) ? $pilot->user->addresses[0]->state_id : '')); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="city">City <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('addresses.0.city_id', array('options' => $cities, 'empty' => 'Select City', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'value'=>!empty($pilot->user->addresses[0]->city_id) ? $pilot->user->addresses[0]->city_id : '')); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 col-sm-4 col-xs-12" for="zip_code">Zip Code <span class="required">*</span>
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('addresses.0.zip_code', array('class' => 'form-control col-md-7 col-xs-12 numericOnly', 'required' => 'required', 'placeholder' => 'Zip Code', 'label' => false, 'value'=>!empty($pilot->user->addresses[0]->zip_code) ? $pilot->user->addresses[0]->zip_code : '')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    //Duty Assignment
                    echo $this->element(
                                    'pilot/dutyAssign', 
                                    array(
                                        'pilot'=>$pilot, 
                                        'chk'=>[NULL, '0','1'], 
                                        'type'=>'edit'
                                    )
                                ); 
                    ?>

                    <?php
                    //Pilot certificate 
                    echo $this->element(
                                    'pilot/certificate', 
                                    array(
                                        'pilot'     => $pilot, 
                                        'chk'       => [NULL, '0','1'], 
                                        'type'      => 'edit',
                                        'mediNDue'  => $mediNDue,
                                        'passNDue'  => $passNDue,
                                        'dlNDue'    => $dlNDue,
                                        'tpcNDue'   => $tpcNDue,
                                        'pilotCert' => $pilotCert
                                    )
                                ); 
                    ?>

                    <?php
                    //Pilot Checking 
                    echo $this->element(
                                    'pilot/checking', 
                                    array(
                                        'pilot'         => $pilot, 
                                        'chk'           => [NULL, '0','1'], 
                                        'type'          => 'edit',
                                        'asNDue'        => $asNDue,
                                        'iccNDue'       => $iccNDue,
                                        'owNDue'        => $owNDue,
                                        'lcNDue'        => $lcNDue,
                                        'apcNDue'       => $apcNDue,
                                        'ioNDue'        => $ioNDue,
                                        'caoNDue'       => $caoNDue,
                                        'pilotChecking' => $pilotChecking,
                                    )
                                ); 
                    ?>

                    <?php
                    //Pilot Training 
                    echo $this->element(
                                    'pilot/training', 
                                    array(
                                        'pilot'         => $pilot, 
                                        'chk'           => [NULL, '0','1'], 
                                        'type'          => 'edit',
                                        'aftNDue'       => $aftNDue,
                                        'edtNDue'       => $edtNDue,
                                        'cwotNDue'      => $cwotNDue,
                                        'crmNDue'       => $crmNDue,
                                        'efbNDue'       => $efbNDue,
                                        'egtNDue'       => $egtNDue,
                                        'gitNDue'       => $gitNDue,
                                        'hzNDue'        => $hzNDue,
                                        'irNDue'        => $irNDue,
                                        'irgNDue'       => $irgNDue,
                                        'icatNDue'      => $icatNDue,
                                        'lbftNDue'      => $lbftNDue,
                                        'rvsmNDue'      => $rvsmNDue,
                                        'stNDue'        => $stNDue,
                                        'pilotTraining' => $pilotTraining,
                                    )
                                ); 
                    ?>

                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                                echo $this->Form->button('Submit', ['type' => 'submit', 'class' => 'btn btn-success']);
                            }
                            echo $this->Form->button('Reset', ['type' => 'reset', 'class' => 'btn btn-primary', 'id' => 'reset']);
                            echo $this->Form->control('updated_by', array('type' => 'hidden', 'value' => $sessionArray['id']));
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
$(document).ready(function() {
    //To remove error message on change of select picker
    $('#frmUpdatePilot select.selectpicker').on('change', function(e) {
        $('#frmUpdatePilot').validate().element($(this));
    });

    $("#frmUpdatePilot").validate({
        ignore: [],
        validateHiddenInputs: true,
        rules: {
            'pilot_type': {
                required: true
            },
            'name': {
                required: true
            },
            'email': {
                required: true,
            },
            'certificate_number': {
                required: true,
                
            },
            'primary_phone': {
                required: true,
                checkPhone: true,
                minlength: 10,
                maxlength: 12
            },   
        },
        messages: {
            'pilot_type': {
                required: "Please select pilot level."
            },
            'name': {
                required: "Please enter pilot name."
            },
            'email': {
                required: "Please enter email address."
            },
            'certificate_number': {
                required: "Please enter certificate number."
                
            },
            'primary_phone': {
                required: "Please enter primary phone number.",
                minlength: "Phone number must consist of atleast 10 digits.",
                maxlength: "Invalid phone number."
            },
        },
        errorClass: "error",
        errorElement: "label"
    }); 
           
    $("#reset").click(function() {
       var validator = $("#frmUpdatePilot").validate();
       validator.resetForm();
    });

});

var getCitiesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getCitiesList']); ?>";
var getStatesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getStatesList']); ?>";
//var isEmailExist  = "<?php //echo $this->Url->build(['controller' => 'Pilots', 'action' => 'isEmailExist']); ?>";
var specifyInfo = "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'specifyInfo']); ?>";
var getNextDue = "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'getNextDue']); ?>";
var certNextDue = "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'certNextDue']); ?>";
</script>