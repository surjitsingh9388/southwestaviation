<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
use Cake\Routing\Router;
$pilotCnt = !empty($allPilots) ? count($allPilots) : 0;
$airCnt = !empty($aircrafts) ? count($aircrafts) : 0;
$discpCnt = !empty($discrepancies) ? count($discrepancies) : 0;
$notifCnt = !empty($notifications) ? count($notifications) : 0;
?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Flight Activity</h2>
        </div>
        
        <div class="page-content mt-35" id="pilotStatusCls">
            <div class="tableScroll">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row" style="padding-bottom: 30px;">
                            <div class="col-md-6" >
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-text">
                                            <div style="padding-left: 5px;">You have <?php echo $notifCnt; ?> notifications.</div>
                                            <?php
                                            if(!empty($notifications)){
                                                $i = 1;
                                                $status = '';
                                                foreach ($notifications as $key => $value) {
                                                    $legDate = !empty($value['leg_date']) ? date('m/d/Y', strtotime($value['leg_date'])) : '';
                                                    if($value['status'] == 'schedule_process') {
                                                        $status = "Flight <a href='../flightlogs/release/".$value['trip_id']."' style='color:#428bca;font-size:12px;'>".$value['trip_id']."</a> is in progress.";
                                                        $bgColor = '#1c84c6';
                                                    } elseif($value['status'] == 'scheduled') {
                                                        $status = "A new flight is ready to be released: <a href='../flightlogs/release/".$value['trip_id']."' style='color:#428bca;font-size:12px;'>".$value['trip_id']."</a>";
                                                        $bgColor = '#23c6c8';
                                                    } elseif ($value['status'] == 'released') {
                                                        $status = "Flight <a href='../flightlogs/preplanning/".$value['trip_id']."' style='color:#428bca;font-size:12px;'>".$value['trip_id']."</a> is ready for pre-planning.";
                                                        $bgColor = '#d1dade';
                                                    } elseif ($value['status'] == 'in_progress' && $value['flstatus'] == 'yes') {
                                                        $status = "Data has been synched but not closed for Flight <a href='../flightlogs/flightlog/".$value['trip_id']."' style='color:#428bca;font-size:12px;'>".$value['trip_id']."</a>.";
                                                        $bgColor = '#1c84c6';
                                                    } elseif ($value['status'] == 'in_progress') {
                                                        $status = "Flight <a href='../flightlogs/flightlog/".$value['trip_id']."' style='color:#428bca;font-size:12px;'>".$value['trip_id']."</a> is ready for crew data input.";
                                                        $bgColor = '#d1dade';
                                                    }
                                                    ?>
                                                    <div style="padding: 10px;">
                                                        <span style="font-size:10px;padding: 3px 8px;color: #fff; background-color:<?php echo $bgColor; ?>;"><?php echo $i; ?></span>&nbsp;
                                                        <span><?php echo $status; ?></span>
                                                        <small class="pull-right"><?php echo $legDate; ?> </small>
                                                    </div>
                                                    <?php  
                                                    $i++;
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 col-xs-12" id="crewAlert">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Crew Member Compliance Alerts <span class="badge right" style="font-weight: normal;font-size: 12px !important;"><?php echo $pilotCnt; ?> Alerts</span></h5>
                                        <div class="card-text" style="padding: 0 20px 0 5px;">
                                            <?php
                                            if(!empty($allPilots)) {
                                                foreach ($allPilots as $key => $pilot) {
                                                    //Pilot certificate
                                                    $mediNDue = !empty($pilot['pilot_certificates'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_certificates'][0], 'medical', 'mm') : [];
                                                    $passNDue = !empty($pilot['pilot_certificates'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_certificates'][0], 'passport', 'yy') : [];
                                                    $dlNDue   = !empty($pilot['pilot_certificates'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_certificates'][0], 'DL', 'yy') : [];
                                                    $tpcNDue  = !empty($pilot['pilot_certificates'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_certificates'][0], 'TPC', 'dd') : [];

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
                                                    $asNDue  = !empty($pilot['pilot_checkings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'AS') : [];
                                                    $iccNDue = !empty($pilot['pilot_checkings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'ICC') : [];
                                                    $owNDue  = !empty($pilot['pilot_checkings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'OW') : [];
                                                    $lcNDue  = !empty($pilot['pilot_checkings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'LC') : [];
                                                    $apcNDue = !empty($pilot['pilot_checkings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'APC') : [];
                                                    $ioNDue  = !empty($pilot['pilot_checkings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'IO') : [];
                                                    $caoNDue = !empty($pilot['pilot_checkings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_checkings'][0], 'CAO') : [];

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
                                                    $aftNDue  = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'AFT') : [];
                                                    $edtNDue  = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'EDT') : [];
                                                    $cwotNDue = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'CWOT') : [];
                                                    $crmNDue  = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'CRM') : [];
                                                    $efbNDue  = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'EFB') : [];
                                                    $egtNDue  = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'EGT') : [];
                                                    $gitNDue  = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'GIT') : [];
                                                    $hzNDue   = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'Hz') : [];
                                                    $irNDue   = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'IR') : [];
                                                    $irgNDue  = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'IRG') : [];
                                                    $icatNDue = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'ICAT') : [];
                                                    $lbftNDue = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'LBFT') : [];
                                                    $rvsmNDue = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'RVSM') : [];
                                                    $stNDue   = !empty($pilot['pilot_trainings'][0]) ? $pilotComp->nextdueProcess($pilot['pilot_trainings'][0], 'ST') : [];

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
                                                    <div style="padding: 28px 0 5px 10px;">
                                                        <i class="fa fa-user-circle-o" style="font-size:20px;padding-right: 5px;color: #23c6c8;"></i> 
                                                        <a href="../pilots/crewCurrency?pilotId=<?php echo $pilot['id']; ?>" style="color:#676a6c;font-size:13px;font-weight: bold;"><?php echo $pilot['user']['first_name'].' '.$pilot['user']['last_name']; ?></a> 
                                                        <small class="pull-right">
                                                            <?php
                                                            $pstatus = '';
                                                            if($certStatus == 'red' || $checkStatus == 'red' || $trainStatus == 'red') {
                                                                echo '<i class="fa fa-warning fa-1x ndred" aria-hidden="true"></i>';
                                                                $pstatus = 'Is Past Due';
                                                            } elseif($certStatus == 'yellow' || $checkStatus == 'yellow' || $trainStatus == 'yellow') {
                                                                echo '<i class="fa fa-warning fa-1x ndyellow" aria-hidden="true"></i>';
                                                                $pstatus = '';
                                                            } elseif ($certStatus == 'blue' || $checkStatus == 'blue' || $trainStatus == 'blue') {
                                                                echo '<i class="fa fa-warning fa-1x ndblue" aria-hidden="true"></i>';
                                                                $pstatus = '';
                                                            } elseif ($certStatus == 'green' && $checkStatus == 'green' && $trainStatus == 'green') {
                                                                echo '<i class="fa fa-check-circle fa-1x ndgreen" aria-hidden="true"></i>';
                                                                $pstatus = '';
                                                            } else {
                                                                echo '<i class="fa fa-warning fa-1x black" aria-hidden="true"></i>';
                                                                $pstatus = 'No Details Available';
                                                            }
                                                            ?>
                                                        </small>
                                                        <br>
                                                        <small class="pull-left" style="padding-left: 30px;"><?php echo $pstatus; ?></small>
                                                    </div>
                                                    <?php                                                   
                                                }
                                            } else {
                                                echo "<div style='padding: 28px 15px 5px 10px;'><i class='fa fa-user-circle-o' style='font-size:15px;padding-right: 5px;color: #23c6c8;'></i> No alerts at this Time</div>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-12" id="aircraftAlert">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Aircraft Compliance Alerts <span class="badge right" style="font-weight: normal;font-size: 13px !important;"><?php echo $airCnt; ?> Alerts</span></h5>
                                        <div class="card-text">
                                            <?php
                                            if(!empty($aircrafts)){
                                                foreach ($aircrafts as $key => $value) {
                                                    ?>
                                                    <div style="padding: 28px 15px 5px 10px;">
                                                        <i class="fa fa-plane" style="font-size:15px;padding-right: 5px;color: #428bca;"></i> 
                                                        <a href="../reports/maintenance?AircraftIds=<?php echo $value['plane']['plane_id']; ?>&type=maintenanceItems" style="color:#676a6c;font-size:12px;font-weight: bold;"><?php echo $value['plane']['plane_code']; ?></a> 
                                                        <small class="pull-right">
                                                            <?php
                                                            $airstatus = '';
                                                            if(!empty($value['airPastDue'])) {
                                                                echo '<i class="fa fa-warning fa-1x ndred" aria-hidden="true"></i>';
                                                                $airstatus = 'Is Past Due';
                                                            } elseif(!empty($value['airTolrDue'])) {
                                                                echo '<i class="fa fa-warning fa-1x ndyellow" aria-hidden="true"></i>';
                                                                $airstatus = 'Is Late';
                                                            } elseif (!empty($value['airCurtDue'])) {
                                                                echo '<i class="fa fa-check-circle fa-1x ndgreen" style="font-size:15px;" aria-hidden="true"></i>';
                                                                $airstatus = 'Is Free & Clear';
                                                            } else {
                                                                echo '<i class="fa fa-warning fa-1x black" aria-hidden="true"></i>';
                                                                $airstatus = 'No Inspection Available';
                                                            }
                                                            ?>
                                                        </small>
                                                        <br>
                                                        <small class="pull-left" style="padding-left: 20px;"><?php echo $airstatus; ?></small>
                                                    </div>
                                                    <?php  
                                                }
                                            } else {
                                                echo "<div style='padding: 28px 15px 5px 10px;'><i class='fa fa-plane' style='font-size:15px;padding-right: 5px;color: #428bca;'></i> No alerts at this Time</div>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-xs-12" id="discrepAlert">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Aircraft Discrepancies <span class="badge right" style="font-weight: normal;font-size: 13px !important;"><?php echo $discpCnt; ?> Alerts</span></h5>
                                        <div class="card-text">
                                            <?php
                                            if(!empty($discrepancies)) {
                                                foreach ($discrepancies as $key => $value) {
                                                    $repairDate = !empty($value['mel_repair_by']) ? date('m/d/Y', strtotime($value['mel_repair_by'])) : '';
                                                    $currentDate = date('m/d/Y');
                                                    $repDDisplay = !empty($repairDate) ? '('.$repairDate.')' : '';                                         
                                                    
                                                    $discpStr = !empty($value['discrepancy']) ? $value['discrepancy'] : '';
                                                    if (!empty($value['discrepancy']) && strlen($value['discrepancy']) > 45) {
                                                        $discpStr = substr($value['discrepancy'], 0, 45).'...';                             
                                                    }
                                                    ?>
                                                    <div style="padding: 28px 15px 5px 10px;">
                                                        <i class="fa fa-plane" style="font-size:15px;padding-right: 5px;color: #428bca;"></i> 
                                                        <a href="../aircraft_discrepancies/index/<?php echo $value['plane_id']; ?>" style="color:#676a6c;font-size:12px;font-weight: bold;"><?php echo $value['plane']['plane_code'].' '.$repDDisplay; ?></a> 
                                                        <small class="pull-right">
                                                            <?php
                                                            $airstatus = '';
                                                            if(strtotime($repairDate) < strtotime($currentDate)) {
                                                                echo '<i class="fa fa-warning fa-1x ndred" aria-hidden="true"></i>';
                                                                $airstatus = 'Is Past Due';
                                                            } elseif(strtotime($repairDate) == strtotime($currentDate)) {
                                                                echo '<i class="fa fa-warning fa-1x ndyellow" aria-hidden="true"></i>';
                                                                $airstatus = 'Is Current Due';
                                                            } else {
                                                                echo '<i class="fa fa-warning fa-1x black" aria-hidden="true"></i>';
                                                                $airstatus = 'No Inspection Available';
                                                            }
                                                            ?>
                                                        </small>
                                                        <br>
                                                        <small class="pull-left" style="padding-left: 20px;"><?php echo $discpStr; ?></small>
                                                    </div>
                                                    <?php  
                                                }
                                            } else {
                                                echo "<div style='padding: 28px 15px 5px 10px;'><i class='fa fa-wrench' style='font-size:15px;padding-right: 5px;color: #428bca;'></i> No alerts at this Time</div>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
