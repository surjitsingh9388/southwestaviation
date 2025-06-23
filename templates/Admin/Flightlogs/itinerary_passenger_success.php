<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth'); 
use Cake\Routing\Router;

$tripId = !empty($tripId) ? strtoupper($tripId) : '';
$flightFrom = !empty($fls[0]['flight_from']) ? strtoupper($fls[0]['flight_from']) : '';
$legDate = !empty($fls[0]['leg_date']) ? date('m/d/Y', strtotime($fls[0]['leg_date'])) : '';
$legStart = !empty($fls[0]['leg_start']) ? date('H:i', strtotime($fls[0]['leg_start'])) : '';
$airComp = $fls[0]['plane']['plane_type'];
$airCode = $fls[0]['plane']['plane_code'];
$tripNotes = $fls[0]['notes'];
?>
    
<div class="content sliding" id="itinSuccessId">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Passenger Itinerary</h2>
        </div>

        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body" style="min-height: 500px; padding: 30px;">
                    <div class="row">
                        <div class="itinEmailmsg">
                            <div class="itinHeader">
                                <span><i class="fa fa-envelope"></i> Itinerary successfully sent on <?php echo $flightFrom.' '.$legDate.' '.$legStart; ?></span>
                            </div>
                            <div class="itinBody">
                                <div class="form-group">
                                    <h3 style="font-size:20px;">Brazos Valley Air Charter</h3>
                                    <h5 style="font-size:16px; font-weight:normal;">Trip Itinerary: Departing <?php echo $flightFrom.' '.$legDate.' '.$legStart; ?></h5>
                                </div>
                                <div class="form-group">
                                    <table class="table" width="100%" cellspacing="0" cellpadding="6" border="0">
                                        <thead>
                                            <tr>
                                                <th style="text-align:left; background-color: #333; color: #fff;">LEG</th>
                                                <th style="text-align:left; background-color: #333; color: #fff;">DATE ETD</th>
                                                <th style="text-align:left; background-color: #333; color: #fff;">DEPARTURE</th>
                                                <th style="text-align:left; background-color: #333; color: #fff;">ARRIVAL</th>
                                                <th style="text-align:left; background-color: #333; color: #fff;">DATE ETA</th>
                                                <th style="text-align:left; background-color: #333; color: #fff;">PAX</th>
                                                <th style="text-align:left; background-color: #333; color: #fff;">Flight Time</th>
                                                <th style="text-align:left; background-color: #333; color: #fff;">Crew</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($fls as $key => $value) {
                                            //Crew details
                                            $crewName = '';
                                            if(!empty($value['crews'])) {
                                                foreach ($value['crews'] as $key2 => $value2) {
                                                    $crewName .= $pilotComp->getPilotName($value2['crew_member']).'<br>';
                                                }
                                            }

                                            $flightFrom = !empty($value['flight_from']) ? strtoupper($value['flight_from']) : '';
                                            $flightTo = !empty($value['flight_to']) ? strtoupper($value['flight_to']) : '';
                                            $legDate = !empty($value['leg_date']) ? date('m/d/Y', strtotime($value['leg_date'])) : '';
                                            $legStartTime = !empty($value['leg_start']) ? date('H:i', strtotime($value['leg_start'])) : '';
                                            $legLength = !empty($value['leg_length']) ? date('H:i', strtotime($value['leg_length'])) : '';
                                            //Leg stop time
                                            $legStopTime = $pilotComp->addTimesMulti(array($legStartTime, $legLength));
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $legDate.'<br>'.$legStartTime; ?></td>
                                                <td><?php echo $flightFrom; ?></td>
                                                <td><?php echo $flightTo; ?></td>
                                                <td><?php echo $legDate.'<br>'.$legStopTime; ?></td>
                                                <td><?php echo $value['passengers']; ?></td>
                                                <td><?php echo $legLength; ?> (hrs:mins)</td>
                                                <td><?php echo $crewName; ?></td>
                                            </tr>
                                        <?php
                                            $i++;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <h4 style="font-size:16px;">Aircraft: <span style="font-weight:normal;font-size:16px;"><?php echo $airComp; ?></span></h4>
                                    <p style="font-size:16px;">Aircraft Tail Number: <?php echo $airCode; ?></p>
                                </div>
                                <div class="form-group">
                                    <h4 style="font-size:16px;">Trip Notes: <span style="font-weight:normal;font-size:16px;"><?php echo $tripNotes; ?></span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="row">
                        <div class="form-group" style="text-align: center; font-weight: bold;">
                            What would you like to do now?
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-12">
                                <?php
                                echo $this->Html->link("Back to Flight Center", array('controller'=>'Flightlogs', 'action'=>'index'), array('class' => 'btn btn-success col-sm-12 col-xs-12', 'escape' => false));
                                ?>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <?php
                                echo $this->Html->link("Send Another Itinerary", array('controller'=>'Flightlogs', 'action'=>'itineraryPassenger', $tripId), array('class' => 'btn btn-success col-sm-12 col-xs-12', 'escape' => false));
                                ?>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>