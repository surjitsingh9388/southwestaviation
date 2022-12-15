<?php 
$sessionUser = $this->request->session()->read('Auth.User');
use Cake\Routing\Router;

$releaseURL = BASE_URL.ROOT_DIR.'admin/flightlogs/release/';
$prePlanURL = BASE_URL.ROOT_DIR.'admin/flightlogs/preplanning/';
$dispatchURL = BASE_URL.ROOT_DIR.'admin/flightlogs/dispatch/';
$flightLogURL = BASE_URL.ROOT_DIR.'admin/flightlogs/flightlog/';
$itinPassnURL = BASE_URL.ROOT_DIR.'admin/flightlogs/itineraryPassenger/';
?>
<style type="text/css">
    .dts_label {
        display: none;
    }
</style>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Flight Center</h2>
            <div style="float: right;">
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1){
                echo $this->Html->link("<i class='fa fa-plane'></i> Initiate New Flight", array('action'=>'dispatch'), array('class' => 'btn btn-default', 'escape' => false));
                echo "&nbsp;&nbsp";
                echo $this->Html->link("<i class='fa fa-plus'></i> Create Trip From Flightlog", array('action'=>'flightlog'), array('class' => 'btn btn-default', 'escape' => false));
            }
            ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Flight Details <span id="statusMsg" style="float:right;font-size:12px;"></span></h4>
                        </div>
                    </div>

                    <div class="row">                    
                        <!-- Tabs Start -->
                        <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                            <div class="container">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#open" class="flTabs" data-tbname="">Open</a></li>
                                    <li><a data-toggle="tab" href="#scheduled" class="flTabs" data-tbname="scheduled">Scheduled</a></li>
                                    <li><a data-toggle="tab" href="#inprogress" class="flTabs" data-tbname="in_progress">In Progress</a></li>
                                    <li><a data-toggle="tab" href="#closed" class="flTabs" data-tbname="closed">Closed</a></li>
                                    <li style="float:right; top:4px; width: 40%;">
                                        <div class="input-group search-control">
                                            <input id="searchItem" type="text" class="form-control" placeholder="Trip ID, Aircraft, Airport Code or mm/dd/yyyy" style="height: 37px;">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default" type="submit">
                                                    <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img', 'style'=>'max-width:64px;')); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="open" class="tab-pane fade in active">
                                        <div class="table-responsive" style="padding:10px;height: 450px;">
                                            <table width="100%">
                                                <thead style="border-bottom: 1px solid #c0c0c0;">
                                                    <tr>
                                                        <th width="12%">Trip ID</th>
                                                        <th width="12%">Flight Date</th>
                                                        <th width="12%">Aircraft</th>
                                                        <th width="40%">Route</th>
                                                        <th width="12%">Status</th>
                                                        <th width="12%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="fcopen">
                                                    <tr class="fcRow">
                                                        <td colspan="6" style="text-align: center;">No Records Found.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div id="scheduled" class="tab-pane fade">
                                        <div class="table-responsive" style="padding:10px;height: 450px;">
                                            <table width="100%">
                                                <thead style="border-bottom: 1px solid #c0c0c0;">
                                                    <tr>
                                                        <th width="12%">Trip ID</th>
                                                        <th width="12%">Flight Date</th>
                                                        <th width="12%">Aircraft</th>
                                                        <th width="40%">Route</th>
                                                        <th width="12%">Status</th>
                                                        <th width="12%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="fcscheduled">
                                                    <tr class="fcRow">
                                                        <td colspan="6" style="text-align: center;">No Records Found.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div id="inprogress" class="tab-pane fade">
                                        <div class="table-responsive" style="padding:10px;height: 450px;">
                                            <table width="100%">
                                                <thead style="border-bottom: 1px solid #c0c0c0;">
                                                    <tr>
                                                        <th width="12%">Trip ID</th>
                                                        <th width="12%">Flight Date</th>
                                                        <th width="10%">Aircraft</th>
                                                        <th width="40%">Route</th>
                                                        <th width="10%">Status</th>
                                                        <th width="16%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="fcin_progress">
                                                    <tr class="fcRow">
                                                        <td colspan="6" style="text-align: center;">No Records Found.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div id="closed" class="tab-pane fade">
                                        <div class="table-responsive" style="padding:10px;">
                                            <table width="100%" id="flightClosed" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th width="12%" style="border-bottom: 1px solid #c0c0c0;">Trip ID</th>
                                                        <th width="12%" style="border-bottom: 1px solid #c0c0c0;">Flight Date</th>
                                                        <th width="10%" style="border-bottom: 1px solid #c0c0c0;">Aircraft</th>
                                                        <th width="40%" style="border-bottom: 1px solid #c0c0c0;">Route</th>
                                                        <th width="10%" style="border-bottom: 1px solid #c0c0c0;">Status</th>
                                                        <th width="16%" style="border-bottom: 1px solid #c0c0c0;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="fcclosed">
                                                    <tr class="fcRow">
                                                        <td colspan="6" style="text-align: center;">No Records Found.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tabs end -->                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    //Default display flight log data
    $( window ).on( "load", function() {
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'getFlightCenterData']); ?>",
            data: {},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#fcopen .fcRow').remove();
                        $('#fcopen').append(obj.data);
                    }, 500);
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#fcopen .fcRow').remove();
                        $('#fcopen').html('<tr class="fcRow"><td colspan="16" style="text-align: center;">No Records Found.</td></tr>');
                    }, 500);
                }
            }                   
        });
    });

    //Change flight status
    $(document).on('click', '.changeFLStatus', function(e) {
        var tripId = $(this).data('tripid');
        var flStatus = $(this).data('fls');

        if(flStatus == 'schedule') {
            window.location.href = "<?php echo $dispatchURL; ?>"+tripId;
        } else if(flStatus == 'release') {
            window.location.href = "<?php echo $releaseURL; ?>"+tripId;
        } else if(flStatus == 'pre_plan') {
            window.location.href = "<?php echo $prePlanURL; ?>"+tripId;
        } else if(flStatus == 'flight_log') {
            window.location.href = "<?php echo $flightLogURL; ?>"+tripId;
        } else if(flStatus == 'copy') {
            window.location.href = "<?php echo $dispatchURL; ?>"+tripId+'/copy';
        } else if(flStatus == 'send_itinerary') {
            window.location.href = "<?php echo $itinPassnURL; ?>"+tripId;
        }
    });

    //Delete flight
    $(document).on('click', '.deleteFlightCls', function(e) {
        e.preventDefault();
        var tripId = $(this).data('tripid');

        if (confirm('Delete this flight?')) {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller'=>'Flightlogs', 'action'=>'deleteFlight']); ?>",
                data: {tripId: tripId},
                beforeSend: function () {
                    $('.loader').show();
                },
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('#statusMsg').html('<span style="color:green;">'+obj.message+'</span>');
                            location.reload(true);
                        }, 1000);    
                    } else {
                        $('.loader').hide();
                        $('#statusMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    }
                }
            });
        }
    });

    //Send Itinerary
    $(document).on('click', '.sendItineraryCls', function(e) {
        e.preventDefault();
        var tripId = $(this).data('tripid');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Flightlogs', 'action'=>'itineraryPassenger']); ?>",
            data: {tripId: tripId},
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#statusMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        location.reload(true);
                    }, 1000);    
                } else {
                    $('.loader').hide();
                    $('#statusMsg').html('<span style="color:red;">'+obj.message+'</span>');
                }
            }
        });
    });

    //Change tab
    $(document).on('click', '.flTabs', function(e){
        e.preventDefault();

        var tbname = $(this).data('tbname');
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Flightlogs', 'action'=>'getFlightCenterData']); ?>",
            data: {tbname:tbname},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(tbname == '') {
                    tbname = 'open';
                }
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#fc'+tbname+' '+'.fcRow').remove();
                        $('#fc'+tbname).append(obj.data);
                        
                        //Data table for closed flights
                        if(tbname == 'closed') {
                            $('#flightClosed').DataTable({
                                "searching": false,
                                "responsive": true,
                                "paging":   true,
                                "ordering": false,
                                "info":     false,
                                "lengthChange": false,
                                "pageLength": 50,
                                "bDestroy": true,
                                "fixedHeader": true,
                                scrollY: 380,
                                //scrollX: true,
                                scroller: true
                            });
                        }
                    }, 500);
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#fc'+tbname+' '+'.fcRow').remove();
                        $('#fc'+tbname).html('<tr class="fcRow"><td colspan="16" style="text-align: center;">No Records Found.</td></tr>');
                    }, 500);
                }
            }                   
        });
    });

    //jQuery search on flight log center page
    $("#searchItem").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        var tbname = $('.active .flTabs').data('tbname');
        if(tbname == '') {
            tbname = 'open';
        }
        
        $('#fc'+tbname+' '+'tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
</script>