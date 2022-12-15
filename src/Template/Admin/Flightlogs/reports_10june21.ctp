<?php 
$sessionUser = $this->request->session()->read('Auth.User');
use Cake\Routing\Router;
$startDate = date('m/d/Y', strtotime("-1 month", strtotime(date('m/d/Y'))));
?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Flightlog Report</h2>
             <?php
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1){
            ?>
                <a class="btn btn-default" href="javascript:void(0);" id="downloadFLPdf">Print Report</a>
            <?php
            }
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Choose Aircraft</label>
                            <?php 
                            echo $this->Form->control('plane_id', array('options' => $planes, 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => false, 'data-live-search' => false, 'required'=>'required', 'label'=>false, 'id'=>'airListId')); 
                            ?>
                        </div>
                        <div class="col-md-2">
                            <label>Date Range</label>
                            <input type="text" name="start_date" id="startDId" class="form-control datePicker" value="<?php echo $startDate; ?>">
                        </div>

                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <input type="text" name="end_date" id="endDId" class="form-control datePicker" value="<?php echo date('m/d/Y'); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive" style="padding:10px;">
                            <h4>Report Details</h4>
                            <table width="100%">
                                <thead style="border-bottom: 1px solid #c0c0c0;">
                                    <tr>
                                        <th>Date/Time</th>
                                        <th>Trip ID</th>
                                        <th>Crew</th>
                                        <th>Depart</th>
                                        <th>Arrive</th>
                                        <th>Leg Time</th>
                                        <th>Fuel Burn</th>
                                        <th># Pax</th>
                                        <th>Appr.</th>
                                        <th>Type</th>
                                        <th>Total Time</th>
                                        <th>L/G Cyc</th>
                                        <th>Eng 1 Time</th>
                                        <th>Eng 2 Time</th>
                                        <th>Eng 1 Cyc</th>
                                        <th>Eng 2 Cyc</th>
                                    </tr>
                                </thead>
                                <tbody id="flightLogReportList">
                                    <tr class="flRow">
                                        <td colspan="16" style="text-align: center;">No Records Found for that Aircraft.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.datePicker').datetimepicker({
        format: 'MM/DD/YYYY',
        useCurrent: true
    });

    //Default display flight log data
    $( window ).on( "load", function() {
        var planeId = $('#airListId').val();
        var startDt = $('#startDId').val();
        var endDt = $('#endDId').val();
        
        //Get flight log listing
        getFlightLogList(planeId, startDt, endDt);
    });

    //On change aircraft
    $("#airListId").on('change', function() {
        var planeId = $('#airListId').val();
        var startDt = $('#startDId').val();
        var endDt = $('#endDId').val();
        
        //Get flight log listing
        getFlightLogList(planeId, startDt, endDt);
    });

    //On change start date
    $("#startDId").datetimepicker({
        useCurrent: false,
        format: 'MM/DD/YYYY'
    }).on('dp.change', function(e) {
        var dt = moment(e.date._d);
        var startDt = dt.format('MM/DD/YYYY');
        var planeId = $('#airListId').val();
        var endDt = $('#endDId').val();

        //Get flight log listing
        getFlightLogList(planeId, startDt, endDt);
    });

    //On change end date
    $("#endDId").datetimepicker({
        useCurrent: false,
        format: 'MM/DD/YYYY'
    }).on('dp.change', function(e) {
        var dt = moment(e.date._d);
        var endDt = dt.format('MM/DD/YYYY');
        var planeId = $('#airListId').val();
        var startDt = $('#startDId').val();

        //Get flight log listing
        getFlightLogList(planeId, startDt, endDt);
    });

    //Get flight log listing
    function getFlightLogList(planeId, startDt, endDt) {
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'getFlightLogData']); ?>",
            data: {planeId:planeId, startDt:startDt, endDt:endDt},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#flightLogReportList .flRow').remove();
                        $('#flightLogReportList').append(obj.data);
                    }, 500);
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#flightLogReportList .flRow').remove();
                        $('#flightLogReportList').html('<tr class="flRow"><td colspan="16" style="text-align: center;">No Records Found for that Aircraft.</td></tr>');
                    }, 500);
                }
            }                   
        });
    }

    //Download pdf
    $('#downloadFLPdf').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var planeId = $('#airListId').val();
        var startDt = $('#startDId').val();
        var endDt = $('#endDId').val();

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'generateFLPdf']); ?>",
            data: {planeId: planeId, startDt: startDt, endDt: endDt},
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('.loader').hide();
                    //Open pdf in new link
                    window.open(obj.data);
                } else if(obj.status == 'failure') {
                    $('.loader').hide();
                    alert('Some error occured. Please try again!');
                } else {
                    $('.loader').hide();
                }
            },
            error : function() {
                $('.loader').hide();
                alert('Some error occured. Please try again!');
            },
            complete: function () {
                $('.loader').hide();
            }
        });
    });
});
</script>