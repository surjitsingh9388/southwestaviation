<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User'); 
use Cake\Routing\Router;

$tripId = !empty($tripId) ? strtoupper($tripId) : '';
$flightFrom = !empty($fls[0]['flight_from']) ? strtoupper($fls[0]['flight_from']) : '';
$legDate = !empty($fls[0]['leg_date']) ? date('m/d/Y', strtotime($fls[0]['leg_date'])) : '';
$legStart = !empty($fls[0]['leg_start']) ? date('H:i', strtotime($fls[0]['leg_start'])) : '';
$airComp = $fls[0]['plane']['plane_type'];
$airCode = $fls[0]['plane']['plane_code'];
$tripNotes = $fls[0]['notes'];
?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">    
<div class="content sliding" id="itineraryId">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Passenger Itinerary</h2>
        </div>

        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="post" class="form-horizontal itineraryFormCls form-label-left">
                        <input type="hidden" name="trip_id" id="tripId" value="<?php echo $tripId; ?>">
                        <div class="row">
                            <div class="col-xs-12">
                                <h5 style="font-weight: normal;font-size: 17px;">Passenger Itenirary for trip: <?php echo $tripId; ?></h5>
                            </div>
                        </div>

                        <div style="clear: both;padding-top: 15px;"></div>

                        <div class="row">
                            <div class="col-md-2">
                                <label>From:</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="company_name" id="compName" class="form-control" value="Brazos Valley Air Charter">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label></label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="from" id="fromId" class="form-control" value="ssimons@tuxedoair.com">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label>To:</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="to" id="toId" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label>Subject:</label>
                            </div>
                            <div class="col-md-10">
                                <input type="text" name="subject" id="subjId" class="form-control" value="Brazos Valley Air Charter Trip departing <?php echo $flightFrom.' '.$legDate.' '.$legStart; ?>">
                            </div>
                        </div>

                        <div class="row" style="overflow: auto;">
                            <div class="col-md-12">
                                <?php
                                    $itinHtml = '<div class="emailmsg" style="margin:0;padding:0;">
                                        <div class="form-group">
                                            <h3 style="font-size:20px;">Brazos Valley Air Charter</h3>
                                            <h5 style="font-size:16px; font-weight:normal;">Trip Itinerary: Departing '.$flightFrom.' '.$legDate.' '.$legStart.'</h5>
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
                                                <tbody>';
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
                                                    $itinHtml .= '<tr>
                                                            <td>'.$i.'</td>
                                                            <td>'.$legDate.'<br>'.$legStartTime.'</td>
                                                            <td>'.$flightFrom.'</td>
                                                            <td>'.$flightTo.'</td>
                                                            <td>'.$legDate.'<br>'.$legStopTime.'</td>
                                                            <td>'.$value['passengers'].'</td>
                                                            <td>'.$legLength.' (hrs:mins)</td>
                                                            <td>'.$crewName.'</td>
                                                        </tr>';

                                                    $i++;
                                                }
                                                $itinHtml .= '</tbody>
                                            </table>
                                        </div>
                                        <div class="form-group">
                                            <h4 style="font-size:16px;">Aircraft: <span style="font-weight:normal;font-size:16px;">'.$airComp.'</span></h4>
                                            <p style="font-size:16px;">Aircraft Tail Number: '.$airCode.'</p>
                                        </div>
                                        <div class="form-group">
                                            <h4 style="font-size:16px;">Trip Notes: <span style="font-weight:normal;font-size:16px;">'.$tripNotes.'</span></h4>
                                        </div>
                                    </div>';
                                ?>
                                <textarea name="message" class="form-control" id="summernote" rows="15">
                                    <?php echo $itinHtml; ?>
                                </textarea>
                            </div>
                        </div>

                        <div class="ln_solid"></div>

                        <div class="row">
                            <div class="itineraryMsg"></div>
                            <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: right;">
                                <?php
                                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                                    echo $this->Html->link("Discard", array('action'=>'index'), array('class' => 'btn btn-default', 'escape' => false));
                                    echo $this->Form->button('Send Itinerary', ['type'=>'submit', 'class'=>'btn btn-success sendItineraryCls']);
                                }
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>    
$(document).ready(function() {
    var tripId = '<?php echo $tripId; ?>';
    //Initialise editor
    $('#summernote').summernote({
        tabsize: 2,
        height: 350,
        popover: {},
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['picture', 'link', 'table', 'hr']],
            ['misc', ['undo', 'redo']]
        ],
        minHeight: null,             // set minimum height of editor
        maxHeight: null,             // set maximum height of editor
        dialogsInBody: true
    });

    //Email form validation
    $(".itineraryFormCls").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {   
            'from': {
                required: true
            },    
            'to': {
                required: true
            },
            'subject': {
                required: true
            }
        }
    });

    $(document).on('click', '.sendItineraryCls', function(e) {
        e.stopPropagation();
        e.preventDefault();

        if($('.itineraryFormCls').valid()) {
            var compName = $('#compName').val();
            var fromEmail = $('#fromId').val();
            var toEmail = $('#toId').val();
            var subject = $('#subjId').val();
            var messageData = $('#summernote').summernote('code');
                
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'sendItineraryEmail']); ?>",
                data: {compName:compName, fromEmail:fromEmail, toEmail:toEmail, subject:subject, messageData:messageData},
                async: true,
                beforeSend: function(){
                    $('.loader').show();
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('.loader').hide();
                        $('.itineraryMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        setTimeout(function() {
                            window.location = "../itineraryPassengerSuccess/"+tripId;
                        }, 500);
                    } else {
                        $('.loader').hide();
                        $('.itineraryMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    }
                }                   
            });
        }
    });
});
</script>