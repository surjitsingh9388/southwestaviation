<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

$allPilots = $pilotComp->getPilots();
?>     
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Crew Currency</h2>
        </div>

        <div class="page-content mt-35" id="pilotStatusCls">
            <div class="tableScroll">
                <?php 
                if(!empty($pilot)) {
                    echo $this->Form->create($pilot, array('class' => 'form-horizontal form-label-left', 'id' => 'frmPilot')); 
                    ?>
                    
                    <input type="hidden" name="pilotId" value="<?php echo $pilot['id']; ?>">
                    <input type="hidden" name="first_name" value="<?php echo $pilot->user->first_name; ?>">
                    <input type="hidden" name="middle_name" value="<?php echo $pilot->user->middle_name; ?>">
                    <input type="hidden" name="last_name" value="<?php echo $pilot->user->last_name; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $pilot->user->id; ?>">
                    <input type="hidden" name="role_id" value="<?php echo $pilot->user->role_id; ?>">
                    
                    <?php
                    //Suspended
                    $suspended = 0;
                    if($pilot->user->suspended) {
                        $suspended = $pilot->user->suspended;
                    }
                    echo $this->Form->control('suspended', array('type'=>'hidden', 'value'=>$suspended));
                    ?>
                    
                    <?php
                    //Pilot certificate
                    $mediNDue = $pilotComp->nextdueProcess($pilot['pilot_certificates'][0], 'medical', 'mm');
                    $passNDue = $pilotComp->nextdueProcess($pilot['pilot_certificates'][0], 'passport', 'yy');
                    $dlNDue   = $pilotComp->nextdueProcess($pilot['pilot_certificates'][0], 'DL', 'yy');
                    $tpcNDue  = $pilotComp->nextdueProcess($pilot['pilot_certificates'][0], 'TPC', 'dd');

                    $pilotCert = '';
                    $certStatus = '';
                    if((!empty($mediNDue['colorcls']) && $mediNDue['colorcls'] == 'ndred') || (!empty($passNDue['colorcls']) && $passNDue['colorcls'] == 'ndred') || (!empty($dlNDue['colorcls']) && $dlNDue['colorcls'] == 'ndred') || (!empty($tpcNDue['colorcls']) && $tpcNDue['colorcls'] == 'ndred')) {
                        
                        $pilotCert  = '<i class="fa fa-warning fa-1x ndred" aria-hidden="true"></i>';
                        $certStatus = 'red';

                    } elseif((!empty($mediNDue['colorcls']) && $mediNDue['colorcls'] == 'ndyellow') || (!empty($passNDue['colorcls']) && $passNDue['colorcls'] == 'ndyellow') || (!empty($dlNDue['colorcls']) && $dlNDue['colorcls'] == 'ndyellow') || (!empty($tpcNDue['colorcls']) && $tpcNDue['colorcls'] == 'ndyellow')) {
                        
                        $pilotCert  = '<i class="fa fa-warning fa-1x ndyellow" aria-hidden="true"></i>';
                        $certStatus = 'yellow';

                    } elseif((!empty($mediNDue['colorcls']) && $mediNDue['colorcls'] == 'ndblue') || (!empty($passNDue['colorcls']) && $passNDue['colorcls'] == 'ndblue') || (!empty($dlNDue['colorcls']) && $dlNDue['colorcls'] == 'ndblue') || (!empty($tpcNDue['colorcls']) && $tpcNDue['colorcls'] == 'ndblue')) {
                        
                        $pilotCert  = '<i class="fa fa-warning fa-1x ndblue" aria-hidden="true"></i>';
                        $certStatus = 'blue';

                    } elseif((!empty($mediNDue['colorcls']) && $mediNDue['colorcls'] == 'ndgreen') || (!empty($passNDue['colorcls']) && $passNDue['colorcls'] == 'ndgreen') || (!empty($dlNDue['colorcls']) && $dlNDue['colorcls'] == 'ndgreen') || (!empty($tpcNDue['colorcls']) && $tpcNDue['colorcls'] == 'ndgreen')) {
                        
                        $pilotCert  = '<i class="fa fa-check-circle fa-1x ndgreen" aria-hidden="true"></i>';
                        $certStatus = 'green';

                    } else {
                        $pilotCert  = '<i class="fa fa-warning fa-1x" aria-hidden="true"></i>';
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
                        
                        $pilotChecking = '<i class="fa fa-warning fa-1x ndred" aria-hidden="true"></i>';
                        $checkStatus   = 'red';

                    } elseif((!empty($asNDue['colorcls']) && $asNDue['colorcls'] == 'ndyellow') || (!empty($iccNDue['colorcls']) && $iccNDue['colorcls'] == 'ndyellow') || (!empty($owNDue['colorcls']) && $owNDue['colorcls'] == 'ndyellow') || (!empty($lcNDue['colorcls']) && $lcNDue['colorcls'] == 'ndyellow') || (!empty($apcNDue['colorcls']) && $apcNDue['colorcls'] == 'ndyellow') || (!empty($ioNDue['colorcls']) && $ioNDue['colorcls'] == 'ndyellow') || (!empty($caoNDue['colorcls']) && $caoNDue['colorcls'] == 'ndyellow')) {
                        
                        $pilotChecking = '<i class="fa fa-warning fa-1x ndyellow" aria-hidden="true"></i>';
                        $checkStatus   = 'yellow';

                    } elseif((!empty($asNDue['colorcls']) && $asNDue['colorcls'] == 'ndblue') || (!empty($iccNDue['colorcls']) && $iccNDue['colorcls'] == 'ndblue') || (!empty($owNDue['colorcls']) && $owNDue['colorcls'] == 'ndblue') || (!empty($lcNDue['colorcls']) && $lcNDue['colorcls'] == 'ndblue') || (!empty($apcNDue['colorcls']) && $apcNDue['colorcls'] == 'ndblue') || (!empty($ioNDue['colorcls']) && $ioNDue['colorcls'] == 'ndblue') || (!empty($caoNDue['colorcls']) && $caoNDue['colorcls'] == 'ndblue')) {
                        
                        $pilotChecking = '<i class="fa fa-warning fa-1x ndblue" aria-hidden="true"></i>';
                        $checkStatus   = 'blue';

                    } elseif((!empty($asNDue['colorcls']) && $asNDue['colorcls'] == 'ndgreen') || (!empty($iccNDue['colorcls']) && $iccNDue['colorcls'] == 'ndgreen') || (!empty($owNDue['colorcls']) && $owNDue['colorcls'] == 'ndgreen') || (!empty($lcNDue['colorcls']) && $lcNDue['colorcls'] == 'ndgreen') || (!empty($apcNDue['colorcls']) && $apcNDue['colorcls'] == 'ndgreen') || (!empty($ioNDue['colorcls']) && $ioNDue['colorcls'] == 'ndgreen') || (!empty($caoNDue['colorcls']) && $caoNDue['colorcls'] == 'ndgreen')) {
                        
                        $pilotChecking = '<i class="fa fa-check-circle fa-1x ndgreen" aria-hidden="true"></i>';
                        $checkStatus   = 'green';

                    } else {
                        $pilotChecking = '<i class="fa fa-warning fa-1x" aria-hidden="true"></i>';
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
                        
                        $pilotTraining = '<i class="fa fa-warning fa-1x ndred" aria-hidden="true"></i>';
                        $trainStatus   = 'red';

                    } elseif((!empty($aftNDue['colorcls']) && $aftNDue['colorcls'] == 'ndyellow') || (!empty($edtNDue['colorcls']) && $edtNDue['colorcls'] == 'ndyellow') || (!empty($cwotNDue['colorcls']) && $cwotNDue['colorcls'] == 'ndyellow') || (!empty($crmNDue['colorcls']) && $crmNDue['colorcls'] == 'ndyellow') || (!empty($efbNDue['colorcls']) && $efbNDue['colorcls'] == 'ndyellow') || (!empty($egtNDue['colorcls']) && $egtNDue['colorcls'] == 'ndyellow') || (!empty($gitNDue['colorcls']) && $gitNDue['colorcls'] == 'ndyellow') || (!empty($hzNDue['colorcls']) && $hzNDue['colorcls'] == 'ndyellow') || (!empty($irNDue['colorcls']) && $irNDue['colorcls'] == 'ndyellow') || (!empty($irgNDue['colorcls']) && $irgNDue['colorcls'] == 'ndyellow') || (!empty($icatNDue['colorcls']) && $icatNDue['colorcls'] == 'ndyellow') || (!empty($lbftNDue['colorcls']) && $lbftNDue['colorcls'] == 'ndyellow') || (!empty($rvsmNDue['colorcls']) && $rvsmNDue['colorcls'] == 'ndyellow') || (!empty($stNDue['colorcls']) && $stNDue['colorcls'] == 'ndyellow')) {
                        
                        $pilotTraining = '<i class="fa fa-warning fa-1x ndyellow" aria-hidden="true"></i>';
                        $trainStatus   = 'yellow';

                    } elseif((!empty($aftNDue['colorcls']) && $aftNDue['colorcls'] == 'ndblue') || (!empty($edtNDue['colorcls']) && $edtNDue['colorcls'] == 'ndblue') || (!empty($cwotNDue['colorcls']) && $cwotNDue['colorcls'] == 'ndblue') || (!empty($crmNDue['colorcls']) && $crmNDue['colorcls'] == 'ndblue') || (!empty($efbNDue['colorcls']) && $efbNDue['colorcls'] == 'ndblue') || (!empty($egtNDue['colorcls']) && $egtNDue['colorcls'] == 'ndblue') || (!empty($gitNDue['colorcls']) && $gitNDue['colorcls'] == 'ndblue') || (!empty($hzNDue['colorcls']) && $hzNDue['colorcls'] == 'ndblue') || (!empty($irNDue['colorcls']) && $irNDue['colorcls'] == 'ndblue') || (!empty($irgNDue['colorcls']) && $irgNDue['colorcls'] == 'ndblue') || (!empty($icatNDue['colorcls']) && $icatNDue['colorcls'] == 'ndblue') || (!empty($lbftNDue['colorcls']) && $lbftNDue['colorcls'] == 'ndblue') || (!empty($rvsmNDue['colorcls']) && $rvsmNDue['colorcls'] == 'ndblue') || (!empty($stNDue['colorcls']) && $stNDue['colorcls'] == 'ndblue')) {
                        
                        $pilotTraining = '<i class="fa fa-warning fa-1x ndblue" aria-hidden="true"></i>';
                        $trainStatus   = 'blue';

                    } elseif((!empty($aftNDue['colorcls']) && $aftNDue['colorcls'] == 'ndgreen') || (!empty($edtNDue['colorcls']) && $edtNDue['colorcls'] == 'ndgreen') || (!empty($cwotNDue['colorcls']) && $cwotNDue['colorcls'] == 'ndgreen') || (!empty($crmNDue['colorcls']) && $crmNDue['colorcls'] == 'ndgreen') || (!empty($efbNDue['colorcls']) && $efbNDue['colorcls'] == 'ndgreen') || (!empty($egtNDue['colorcls']) && $egtNDue['colorcls'] == 'ndgreen') || (!empty($gitNDue['colorcls']) && $gitNDue['colorcls'] == 'ndgreen') || (!empty($hzNDue['colorcls']) && $hzNDue['colorcls'] == 'ndgreen') || (!empty($irNDue['colorcls']) && $irNDue['colorcls'] == 'ndgreen') || (!empty($irgNDue['colorcls']) && $irgNDue['colorcls'] == 'ndgreen') || (!empty($icatNDue['colorcls']) && $icatNDue['colorcls'] == 'ndgreen') || (!empty($lbftNDue['colorcls']) && $lbftNDue['colorcls'] == 'ndgreen') || (!empty($rvsmNDue['colorcls']) && $rvsmNDue['colorcls'] == 'ndgreen') || (!empty($stNDue['colorcls']) && $stNDue['colorcls'] == 'ndgreen')) {
                        
                        $pilotTraining = '<i class="fa fa-check-circle fa-1x ndgreen" aria-hidden="true"></i>';
                        $trainStatus   = 'green';

                    } else {
                        $pilotTraining = '<i class="fa fa-warning fa-1x" aria-hidden="true"></i>';
                        $trainStatus   = 'black';
                    }
                    ?>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                            <?php
                            if($certStatus == 'red' || $checkStatus == 'red' || $trainStatus == 'red') {
                                echo '<div class="panel-title"><i class="fa fa-warning fa-1x ndred" aria-hidden="true"></i> <span style="top:0;">This crew member is out of compliance</span></div>';
                            } elseif($certStatus == 'yellow' || $checkStatus == 'yellow' || $trainStatus == 'yellow') {
                                echo '<div class="panel-title"><i class="fa fa-warning fa-1x ndyellow" aria-hidden="true"></i> <span style="top:0;">This crew member will be out of compliance soon</span></div>';
                            } elseif ($certStatus == 'blue' || $checkStatus == 'blue' || $trainStatus == 'blue') {
                                echo '<div class="panel-title"><i class="fa fa-warning fa-1x ndblue" aria-hidden="true"></i> This crew member will be out of compliance soon</div>';
                            } elseif ($certStatus == 'green' && $checkStatus == 'green' && $trainStatus == 'green') {
                                echo '<div class="panel-title"><i class="fa fa-check-circle fa-1x ndgreen" aria-hidden="true"></i> This crew member is in compliance</div>';
                            } else {
                                echo '<div class="panel-title"><i class="fa fa-warning fa-1x black" aria-hidden="true"></i> Please fill all the required information</div>';
                            }
                            ?>
                            </div>
                            <div class="col-md-6" style="text-align: right;">
                            <?php echo $this->Form->control('name', array('options'=>$allPilots, 'class'=>'selectpicker changePilot', 'label' => false, 'value'=>$pilot['id'])); ?>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Flight Crew Information</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Name:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $pilot->user->full_name; ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Email Address:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $pilot->user->email; ?>
                                            <input type="hidden" name="email" value="<?php echo $pilot->user->email; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Certificate Number:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php 
                                            if(!empty($pilot['certificate_number']) && $pilot['certificate_number'] != '000') { 
                                                echo $pilot['certificate_number'];
                                            } 
                                            ?>
                                            <input type="hidden" name="certificate_number" value="<?php echo $pilot['certificate_number']; ?>">
                                        </div>
                                    </div>

                                    <?php
                                    if (isset($pilot->user->phone_ext)) {
                                        $phoneExt = !empty($pilot->user->phone_ext) ? $pilot->user->phone_ext : '+1';
                                    } else {
                                        $phoneExt = '+1';
                                    }
                                    ?> 
                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Primary Phone:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php
                                            echo $phoneExt.'- '.$pilot->user->phone; 
                                            ?>
                                            <input type="hidden" name="phone_ext" value="<?php echo $phoneExt; ?>">
                                            <input type="hidden" name="phone" value="<?php echo $pilot->user->phone; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Secondary Phone:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php
                                            if(!empty($pilot['secondary_phone'])) {
                                                echo $phoneExt.'- '.$pilot['secondary_phone'];
                                            } 
                                            ?>
                                            <input type="hidden" name="secondary_phone" value="<?php echo $pilot['secondary_phone']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Emergency Contact:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $pilot['emergency_contact']; ?>
                                            <input type="hidden" name="emergency_contact" value="<?php echo $pilot['emergency_contact']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Emergency Phone:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php
                                            if(!empty($pilot['emergency_phone'])) {
                                                echo $phoneExt.'- '.$pilot['emergency_phone'];
                                            } 
                                            ?>
                                            <input type="hidden" name="emergency_phone" value="<?php echo $pilot['emergency_phone']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Address:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php
                                            $address1 = '';
                                            if(!empty($pilot->user->addresses[0]->address_line1)) {
                                                $address1 = $pilot->user->addresses[0]->address_line1;
                                            } 
                                            echo $address1;
                                            echo $this->Form->control('addresses.0.address_line1', array('type' => 'hidden', 'value' => $address1)); 
                                            ?>
                                        </div>
                                    </div>
                                                            
                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Country:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php
                                            $country = '';
                                            if(!empty($pilot->user->addresses[0]->country->name)) {
                                                $country = $pilot->user->addresses[0]->country->name;
                                            }
                                            echo $country;
                                            echo $this->Form->control('addresses.0.country_id', array('type' => 'hidden', 'value' => !empty($pilot->user->addresses[0]->country_id) ? $pilot->user->addresses[0]->country_id : ''));  
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">State:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php
                                            $state = '';
                                            if(!empty($pilot->user->addresses[0]->state->name)) {
                                                $state = $pilot->user->addresses[0]->state->name;
                                            } 
                                            echo $state;
                                            echo $this->Form->control('addresses.0.state_id', array('type' => 'hidden', 'value' => !empty($pilot->user->addresses[0]->state_id) ? $pilot->user->addresses[0]->state_id : '')); 
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">City:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php
                                            $city = '';
                                            if(!empty($pilot->user->addresses[0]->city->name)) {
                                                $city = $pilot->user->addresses[0]->city->name;
                                            }
                                            echo $city;
                                            echo $this->Form->control('addresses.0.city_id', array('type' => 'hidden', 'value' => !empty($pilot->user->addresses[0]->city_id) ? $pilot->user->addresses[0]->city_id : '')); 
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 col-sm-4 col-xs-12">Zip Code:</label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php 
                                            $zipcode = '';
                                            if(!empty($pilot->user->addresses[0]->zip_code)) {
                                                $zipcode = $pilot->user->addresses[0]->zip_code;
                                            }
                                            echo $zipcode;
                                            echo $this->Form->control('addresses.0.zip_code', array('type' => 'hidden', 'value' => $zipcode)); 
                                            ?>
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
                                            'chk'=>['1'], 
                                            'type'=>'crew'
                                        )
                                    ); 
                        ?>

                        <?php
                        //Pilot certificate 
                        echo $this->element(
                                        'pilot/certificate', 
                                        array(
                                            'pilot'     => $pilot, 
                                            'chk'       => ['1'], 
                                            'type'      => 'crew',
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
                                            'chk'           => ['1'], 
                                            'type'          => 'crew',
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
                                            'chk'           => ['1'], 
                                            'type'          => 'crew',
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
                                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1){
                                    echo $this->Form->button('Save Data', ['type' => 'submit', 'class' => 'btn btn-success']);
                                }
                                echo $this->Form->control('updated_by', array('type' => 'hidden', 'value' => $sessionArray['id']));
                                echo $this->Form->control('addresses.0.updated_by', array('type' => 'hidden', 'value' => $sessionUser['id']));
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                <?php 
                } else { 
                ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                        No record available
                        </div>
                </div>
            <?php 
            } 
            ?>
            </div>
        </div>
    </div>
</div>

<?php echo $this->element('pilots_popup'); ?>

<?php echo $this->Html->script('pilots'); ?>
<script>    
var getCitiesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getCitiesList']); ?>";
var getStatesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getStatesList']); ?>";
//var isEmailExist  = "<?php //echo $this->Url->build(['controller' => 'Pilots', 'action' => 'isEmailExist']); ?>";
var specifyInfo = "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'specifyInfo']); ?>";
var crewCurrency = "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'crewCurrency']);?>";
var getNextDue = "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'getNextDue']); ?>";
var certNextDue = "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'certNextDue']); ?>";
</script>