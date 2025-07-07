<?php
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');

use Cake\Routing\Router;

$allPilots = $pilotComp->getPilots();
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Crew Member Report</h2>
        </div>

        <div class="page-content mt-35" id="pilotStatusCls">
            <div class="tableScroll">

                <div class="form-horizontal form-label-left">
                    <div class=" panel-default">
                        <div class="panel-heading">
                            <div class="selectWrap">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 btn-crew">
                                        <?php
                                        echo $this->Form->control('pilot_id', array(
                                            'options' => $allPilots,
                                            'class' => 'form-control selectpicker selDropDCls',
                                            'div' => false,
                                            'label' => 'Choose Crew Member',
                                            'value' => $pilotId,
                                           
                                        ));
                                        ?>
                                    </div>

                                    <div class="col-md-6 col-sm-12 ">
                                        <div class="row">
                                            <div class='col-sm-5 col-xs-12'>
                                                <div class="form-group ml-6">
                                                    <label>Duty Range</label>
                                                    <div class='input-group date' id='startDate'>
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                        <input type='text' class="form-control startDate" name="start_date" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class='col-sm-2 col-xs-12 text-center'>
                                                <div class="form-group mb-0" style="margin-top: 25px;">
                                                    <strong>to</strong>
                                                </div>
                                            </div>

                                            <div class='col-sm-5 col-xs-12'>
                                                <div class="form-group ">
                                                    <label>&nbsp;</label>
                                                    <div class='input-group date' id='endDate'>
                                                        <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                        <input type='text' class="form-control endDate" name="end_date" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-12 text-right" style="margin-top: 25px;">
                                        <button id="crewReportBtn" class="btn btn-success btn-block-sm">Print Report</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row" id="displayHtmlCls">
                                <div class="col-md-12 col-xs-12">
                                    <h5>Report Details</h5>

                                    <div class="row form-group">
                                        <div class="col-md-4 col-sm-4 col-xs-12"><strong>Pilot Name:</strong></div>
                                        <div class="col-md-8 col-sm-8 col-xs-12" id="pilotNameCls"></div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-md-4 col-sm-4 col-xs-12"><strong>Pilot Certificate Number:</strong></div>
                                        <div class="col-md-8 col-sm-8 col-xs-12" id="pilotCertCls"></div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-md-4 col-sm-4 col-xs-12"><strong>Report Range:</strong></div>
                                        <div class="col-md-8 col-sm-8 col-xs-12" id="reportRangeCls"></div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-xs-12">
                                    <h5>Total Times</h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Duty Time</th>
                                                    <th>Admin Duty Time</th>
                                                    <th>Flight Time</th>
                                                    <th>PIC FLT Time</th>
                                                    <th>SIC FLT Time</th>
                                                    <th>Night Flight Time</th>
                                                    <th>IFR Flight Time</th>
                                                    <th>Approaches</th>
                                                    <th>Day Landings</th>
                                                    <th>Night Landings</th>
                                                    <th>Time Off</th>
                                                </tr>
                                            </thead>
                                            <tbody id="totalTimeCls">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-md-12 col-xs-12">
                                    <h5>Detailed Times</h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Duty Time</th>
                                                    <th>Duty Length</th>
                                                    <th>Start Time</th>
                                                    <th>Leg Length</th>
                                                    <th>Night FLT</th>
                                                    <th>IFR FLT</th>
                                                    <th>Approach</th>
                                                    <th>Landing</th>
                                                    <th>Duty Position</th>
                                                    <th>Part</th>
                                                    <th>Aircraft Type</th>
                                                </tr>
                                            </thead>
                                            <tbody id="detailedTimeCls">
                                            </tbody>
                                        </table>
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

<script>
    $(document).ready(function() {

        $(window).on("load", function() {
            var pilotid = $('#pilot-id').val();
            var startDate = $('.startDate').val();
            var endDate = $('.endDate').val();

            getCrewReports(pilotid, startDate, endDate);
        });

        //Select pilot
        $(".selDropDCls").on("change", function() {
            var pilotid = $('#pilot-id').val();
            var startDate = $('.startDate').val();
            var endDate = $('.endDate').val();

            getCrewReports(pilotid, startDate, endDate);
        });

        //Date picker
        var sDate = new Date();
        var startd = new Date(sDate.getFullYear(), sDate.getMonth() - 1, sDate.getDate());
        $('.startDate').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: false,
            defaultDate: startd
        }).on('dp.change', function(e) {
            var st = moment(e.date._d);
            var startDate = st.format('MM/DD/YYYY');
            var pilotid = $('#pilot-id').val();
            var endDate = $('.endDate').val();
            getCrewReports(pilotid, startDate, endDate);
        });

        var eDate = new Date();
        var today = new Date(eDate.getFullYear(), eDate.getMonth(), eDate.getDate());
        $('.endDate').datetimepicker({
            format: "MM/DD/YYYY",
            useCurrent: false,
            defaultDate: today
        }).on('dp.change', function(e) {
            var en = moment(e.date._d);
            var endDate = en.format('MM/DD/YYYY');
            var pilotid = $('#pilot-id').val();
            var startDate = $('.startDate').val();
            getCrewReports(pilotid, startDate, endDate);
        });

        //Get crew reports
        function getCrewReports(pilotid, startDate, endDate) {
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller' => 'Pilots', 'action' => 'getCrewReports']); ?>",
                data: {
                    pilotid: pilotid,
                    startDate: startDate,
                    endDate: endDate,
                    type: 'web'
                },
                async: true,
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    var totalTimeHtml = '<tr><td colspan="11" style="text-align:center;">No Records Found.</td></tr>';
                    if (obj.status == 'success') {
                        setTimeout(function() {
                            $('.loader').hide();

                            //Report details
                            $('#pilotNameCls').html(obj.data.full_name);
                            $('#pilotCertCls').html(obj.data.certificate_number);
                            if (obj.data.certificate_number === '000' || obj.data.certificate_number == '') {
                                $('#pilotCertCls').html('');
                            }
                            $('#reportRangeCls').html(obj.data.date_range);

                            //Total time html
                            totalTimeHtml = '<tr><td>' + obj.data.duty_time + '</td><td>' + obj.data.admin_duty_time + '</td><td>' + obj.data.flight_time + '</td><td>' + obj.data.pic_flt_time + '</td><td>' + obj.data.sic_flt_time + '</td><td>' + obj.data.night_flt_time + '</td><td>' + obj.data.ifr_flt_time + '</td><td>' + obj.data.approaches + '</td><td>' + obj.data.day_landings + '</td><td>' + obj.data.night_landings + '</td><td>' + obj.data.days_off + '</td></tr>';
                            $('#totalTimeCls').html(totalTimeHtml);

                            $('#detailedTimeCls').html(obj.detail);

                        }, 1000);
                    }

                }
            });
        }

        //Generate crew report
        $('#crewReportBtn').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var pilotid = $('#pilot-id').val();
            var startDate = $('.startDate').val();
            var endDate = $('.endDate').val();

            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller' => 'Pilots', 'action' => 'crewReportPdf']); ?>",
                data: {
                    pilotid: pilotid,
                    startDate: startDate,
                    endDate: endDate,
                    type: 'pdf'
                },
                beforeSend: function() {
                    $('.loader').show();
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status == 'success') {
                        $('.loader').hide();
                        //Open pdf in new link
                        window.open(obj.data);
                    } else if (obj.status == 'failure') {
                        $('.loader').hide();
                        alert('Some error occured. Please try again!');
                    } else {
                        $('.loader').hide();
                    }
                },
                error: function() {
                    $('.loader').hide();
                    alert('Some error occured. Please try again!');
                },
                complete: function() {
                    $('.loader').hide();
                }
            });
        });

    });
</script>