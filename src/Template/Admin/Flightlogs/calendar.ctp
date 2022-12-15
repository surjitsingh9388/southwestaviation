<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User'); 
use Cake\Routing\Router;
?>
<style type="text/css">
    #calendar .fc-event-main-frame {
        padding-left: 5px;
    }

    table.fc-col-header th {
        border:1px solid #ddd;
    }

    table.fc-scrollgrid-sync-table td {
        border:1px solid #ddd;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.6.0/main.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Flight Center</h2>
        </div>

        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-9">
                            <label>Just show flights for these aircraft:</label>
                            <?php echo $this->Form->control('plane_id', array('options'=>$planes, 'class'=>'form-control', 'multiple'=>true, 'label'=>false)); ?>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                    <div style="clear: both; padding-top: 30px;"></div>
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    echo $this->element('calendar_popup');
?>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.6.0/main.min.js"></script>
<script>
$(document).ready(function() {
    var airIds = '';
    //Initial calendar data
    $( window ).on( "load", function() {
        getFlightsData(airIds);
    });

    //Select aircraft to display data in calendar
    $('#plane-id').select2({
        allowClear: true,
        placeholder: 'Choose Aircraft to Show'
    }).on("select2:select", function (e) {
        var selected_element = $(e.currentTarget);
        var airIds = selected_element.val();
        getFlightsData(airIds);
    }).on("select2:unselect", function (e) {
        var selected_element = $(e.currentTarget);
        var airIds = selected_element.val();
        getFlightsData(airIds);
    });

    //Get flight data to display in calendar
    function getFlightsData(airIds) {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            schedulerLicenseKey: '0634207327-fcs-1617375721',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            themeSystem: 'bootstrap',
            editable: false,
            initialView: 'dayGridMonth',
            showNonCurrentDates: true,
            events: function(info, successCallback, failureCallback) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->Url->build(['controller'=>'Flightlogs', 'action'=>'getFlights']); ?>",
                    data: {airIds:airIds},
                    beforeSend: function () {
                        $('.loader').show();
                    },
                    success:function(response) {
                        var obj = JSON.parse(response);
                        if(obj.status == 'success') {
                            var events = [];
                            $.each(obj.data, function(key,value) {
                                events.push(value);
                            });
                            
                            setTimeout(function() {
                                $('.loader').hide();
                                successCallback(events);
                            }, 500);
                        } else {
                            setTimeout(function() {
                                $('.loader').hide();
                                failureCallback('');
                            }, 500);
                        }
                    }
                });
            },
            eventDisplay:'block',//To hide dots
            eventDidMount: function (info) {// Add custom colors
                if (info.event.extendedProps.background) {
                    info.el.style.background = info.event.extendedProps.background;
                }

                if (info.event.extendedProps.color) {
                    info.el.style.color = info.event.extendedProps.color;
                }
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                $('#calendarModal .tripid').html('<a style="color:#23c6c8;font-weight:bold;" href="../flightlogs/dispatch/'+info.event.extendedProps.trip_id+'">'+info.event.extendedProps.trip_id+'</a>');
                $('#calendarModal .statusCls').html(info.event.extendedProps.status);
                $('#calendarModal .modal-body').html(info.event.extendedProps.description);
                $('#calendarModal').modal('show');
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false,
                hour12: false
            },
        });
        calendar.render();
    }
});
</script> 