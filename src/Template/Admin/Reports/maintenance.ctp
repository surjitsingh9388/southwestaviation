<?php 
$sessionUser = $this->request->session()->read('Auth.User'); 
use Cake\Routing\Router;

$urlp = $_GET;

$airIds = [];
if(!empty($aircraftIds)) {
    $airIds = explode(',', $aircraftIds);
}

if(!empty($airIds)) {
    $airChkCnt = count($airIds);
    $pdfCheck = 'style="pointer-events: auto;"';
} else {
    $airChkCnt = 0;
    $pdfCheck = 'style="pointer-events: none; background: #c6c6c6;"';
}

if(!empty($airChkCnt) && $airChkCnt == 1) {
    $selectVal = $airChkCnt." Selected";
} elseif(!empty($airChkCnt) && $airChkCnt > 1) {
    $selectVal = $airChkCnt." Selected";
} else {
    $selectVal = "No Selection";
}

$action = '';
if(!empty($params['action'])) {
    $action = $params['action'];
}
?>

<div class="content sliding" style="position:fixed;">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">
            <?php 
                if($type == 'maintenanceItems') {
                    echo "Maintenance Items";

                } elseif($type == 'adsbstatus') {
                    echo "Airworthiness Directives & Service Bulletins";

                } elseif($type == 'past_due') {
                    echo "Maintenance Overdue List";

                } elseif($type == 'tolerance') {
                    echo "Maintenance Current Due List";

                } elseif($type == 'alert_due') {
                    echo "Maintenance Projected Due List";
                    
                } else {
                    echo "Maintenance Due List";
                }
            ?>
            </h2>
            <div class="btnWrap">
                <?php
                if((!empty($reportAction) && $reportAction['action']['action_add']==1) || $sessionUser['id'] == 1) {
                ?>
                <a class="btn btn-default projectedTimeCls" href="javascript:void(0);" data-pid="<?= h($aircraftIds); ?>" data-action="<?= h($action); ?>" id="projectedPdfId" <?php echo $pdfCheck;?>>Projected Report</a>

                <a class="btn btn-default" href="javascript:void(0);" data-pids="<?= h($aircraftIds); ?>" data-action="<?= h($action); ?>" id="downloadPdfId" <?php echo $pdfCheck;?>>Generate Report</a>
                <?php
                }
                ?>
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1){
                    echo $this->Html->link("Create Item", array('controller'=>'AirframeComponentParts', 'action'=>'add'), array('class' => 'btn btn-default', 'escape' => false));
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="maintenanceWrap">
                <div class="action-bar ">
                    <div class="actionbar-lft">
                        <div class="lftWrap">
                            <div class="inputWrap btn-group">
                                <button id="aircraftSelectorBtn" class="btn btn-default">
                                <?php echo $selectVal; ?> <i class="fa fa-caret-down"></i>
                                </button>
                                <button id="airCompDetailBtn" data-pids="<?= h($aircraftIds); ?>" class="btn btn-default" <?php echo $pdfCheck;?>><i class="fa fa-plane" aria-hidden="true"></i></button>
                            </div>
                            
                            <div id="aircraftDetailsPopup" style="display: none;">
                                <div class="modal-content">
                                    <form id="aircraftDetailForm" action="maintenance">
                                        <div style="max-height: 340px; overflow-y: auto;">
                                            <table id="aircraftUtilization" class="table table-hover table-header-dark">
                                                <thead>
                                                    <tr>
                                                        <th width="10%"><input type="checkbox" name="aircraft_info" id="airCheckAll" <?php if(count($allAircraft) == $airChkCnt) { echo "checked"; } ?>></th>
                                                        <th width="20%">Registration</th>
                                                        <th width="10%">Div.</th>
                                                        <th width="30%">Make & Model</th>
                                                        <th width="20%">Serial Number</th>
                                                        <th width="10%">&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $i=1;
                                                foreach ($allAircraft as $key => $value) {
                                                    if(in_array($value['id'], $airIds)) {
                                                        $airSelected = 'checked';
                                                    } else {
                                                        $airSelected = '';
                                                    }
                                                ?>
                                                    <tr>
                                                        <td><input type="checkbox" class="airChkBoxCls" name="AircraftIds[]" value="<?php echo $value['id']; ?>" <?php echo $airSelected; ?>></td>
                                                        <td><?php echo $value['plane_code']; ?></td>
                                                        <td> - </td>
                                                        <td><?php echo $value['plane_type']; ?></td>
                                                        <td><?php echo $value['plane_serial_number']; ?></td>
                                                        <td>
                                                            <?php
                                                            if((!empty($reportTime) && ($reportTime['action']['action_add']==1 || $reportTime['action']['action_edit']==1)) || $sessionUser['id'] == 1){
                                                            ?>
                                                            <a href="javascript:void(0);" class="reportTimeCls" data-plane_id="<?php echo $value['id']; ?>"><i class="fa fa-clock-o" aria-hidden="true"></i></a>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>            
                                                <?php
                                                $i++;    
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <input type="hidden" name="type" value="<?php echo $type; ?>" id="statusType">
                                        <input type="hidden" name="action" value="<?php echo $action; ?>">
                                        <div class="modal-footer">
                                            <span style="float: left; font-size: 17px;" id="airCountId"><?php echo $airChkCnt.'/'.count($allAircraft); ?></span>
                                            <button type="button" class="btn btn-default" id="closeAircraftInfo">Close</button>
                                            <input type="submit" value="Apply" class="btn btn-primary" />
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div id="airCompDetailsPopup" style="display: none;">
                                <div class="modal-content">
                                    <div class="container">  
                                        <div id="myCarousel" class="carousel" data-interval="false" data-ride="carousel">
                                            <div class="carousel-inner">
                                            <?php
                                            $modelDes = '';
                                            $i = 0;
                                            foreach ($allAircraft as $key => $value) {
                                                if(in_array($value['id'], $airIds)) {                               
                                                    $modelDes = !empty($value['plane_type']) ? $value['plane_type'] : '';
                                                    foreach ($value['airframe_components'] as $key2 => $value2) {
                                                        if($value2['log_book'] == 'Airframe') {
                                                            $modelDes = $value2['description'];
                                                        }
                                                    }

                                                    $active = '';
                                                    if($i <= 0) {
                                                        $active = 'active';
                                                    }
                                            ?>
                                                <div class="item <?php echo $active; ?>">
                                                    <div class="airDetailPopupHeader">
                                                        <strong><?php echo $value['plane_code']; ?></strong><?php echo " ".$modelDes; ?>
                                                        <button type="button" class="btn btn-default closeAirInfoBtn">Close</button>
                                                        
                                                        <?php
                                                        if((!empty($reportAction) && $reportAction['action']['action_add']==1) || $sessionUser['id'] == 1) {
                                                        ?>
                                                        <button type="button" class="btn btn-default downloadPdfCls" data-pids="<?= h($value['id']); ?>">Print Times</button>
                                                        <?php } ?>

                                                        <?php
                                                        if((!empty($reportTime) && ($reportTime['action']['action_add']==1 || $reportTime['action']['action_edit']==1)) || $sessionUser['id'] == 1){
                                                        ?>
                                                        <button type="button" class="btn btn-default reportTimeCls" data-plane_id="<?php echo $value['id']; ?>">Update Times</button>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="" style="height: 400px; overflow-y: auto; width: 100%;">
                                                        <div class="col-md-6" style="margin: 3px 0 3px 0;">
                                                            <div class="air-detail-title">Aircraft Information</div>

                                                            <div class="form-group"> 
                                                                <div class="col-md-5 col-sm-5 col-xs-12">Serial Number</div>
                                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                                    <?php echo $value['plane_serial_number']; ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group"> 
                                                                <div class="col-md-5 col-sm-5 col-xs-12">Airworthiness Date</div>
                                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                                    <?php echo !empty($value['airworthiness_date']) ? date('d-M-Y', strtotime($value['airworthiness_date'])) : '&nbsp;'; ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group"> 
                                                                <div class="col-md-5 col-sm-5 col-xs-12">Schedule Rev Level</div>
                                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                                    <?php echo !empty($value['federal_aviation_regulation']) ? $value['federal_aviation_regulation'] : '&nbsp;'; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6" style="margin: 3px 0 3px 0;">
                                                            <div class="air-detail-title">Operator Information</div>
                                                            <div class="form-group"> 
                                                                <div class="col-md-4 col-sm-4 col-xs-12">Owner</div>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo !empty($value['plane_name']) ? $value['plane_name'] : '&nbsp;'; ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group"> 
                                                                <div class="col-md-4 col-sm-4 col-xs-12">Address</div>
                                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                                    <?php echo !empty($value['address']) ? $value['address'] : '&nbsp;'; ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <table class="table table-hover table-header-dark table-condensed">
                                                            <thead>
                                                                <tr>
                                                                    <th width="18%">Equipment</th>
                                                                    <th width="30%">Model</th>
                                                                    <th width="10%">Serial</th>
                                                                    <th width="13%">Report Date</th>
                                                                    <th width="13%">Reported By</th>
                                                                    <th width="8%">Hours</th>
                                                                    <th width="8%">Cycles</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            foreach ($value['airframe_components'] as $key2 => $value2) {

                                                                if($value2['log_book'] == 'Airframe') {
                                                                    $logBook = $value2['log_book'];
                                                                } else {
                                                                    $logBook = $value2['log_book'].' '.$value2['position'];
                                                                }

                                                                $date = !empty($value2['airframe_component_times'][0]['log_date']) ? date('m-d-Y', strtotime($value2['airframe_component_times'][0]['log_date'])) : '';
                                                                $compHours  = !empty($value2['airframe_component_times'][0]['hours']) ? $value2['airframe_component_times'][0]['hours'] : ''; 
                                                                $compCycles = !empty($value2['airframe_component_times'][0]['cycles']) ? $value2['airframe_component_times'][0]['cycles'] : '';
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $logBook; ?></td>
                                                                    <td><?php echo $value2['description']; ?></td>
                                                                    <td><?php echo $value2['serial_no']; ?></td>
                                                                    <td><?php echo $date; ?></td>
                                                                    <td> - </td>
                                                                    <td><?php echo $compHours; ?></td>
                                                                    <td><?php echo $compCycles; ?></td>
                                                                </tr>            
                                                            <?php    
                                                            }
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            <?php
                                                $i++;
                                                }
                                            }
                                            ?>
                                            </div>

                                            <?php
                                            if($airChkCnt > 1) {
                                            ?>    
                                            <a class="mvleft carousel-control" href="#myCarousel" data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-left"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="mvright carousel-control" href="#myCarousel" data-slide="next">
                                                <span class="glyphicon glyphicon-chevron-right"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php echo $this->element('search_form', array('title'=>'maintenance')); ?>
                            <?php echo $this->element('search_by', array('title'=>'maintenance')); ?>
                            <?php echo $this->element('sort_by', array('title'=>'maintenance')); ?>
                        </div>

                        <div class="split-btn pull-right actionMenu sortWrap ">
                            <button class="btn-dropdown btn-default">Action<span class="selectCount"></span></button>
                            <button class="icon-part dropdown-toggle actionCls" data-toggle="dropdown">
                                <i class="fa fa-caret-down"></i>
                            </button>
                            <?php
                            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $sessionUser['id'] == 1){
                            ?>                              
                            <div class="dropdown-content dropdown-menu actionLinks" style="pointer-events: none;">
                                <a href="javascript:void(0);" class="removePartCls" data-pids="<?= h($aircraftIds); ?>">Remove</a>
                                <a href="javascript:void(0);" class="addComplianceCls" data-pids="<?= h($aircraftIds); ?>">Add Compliance</a>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                    <div class="delErrorMsg"></div>
                </div>

                <div id="activeHistId">
                <?php
                $actions = ['active'=>'Active', 'historical'=>'Historical'];
                if(!empty($action)) {
                    echo $this->Form->control('display_action', array('options'=>$actions, 'class'=>'actionAHCls selectpicker', 'data-show-subtext'=>true, 'data-live-search'=>false, 'div'=>false, 'label'=>false, 'value'=>$action));
                } 
                ?>
                </div>

                <div class="table-responsive">
                    <table id="customReport" class="table mb-0" width="100%">
                        <thead>
                            <tr>
                                <th width="4%" class="check"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                                <th width="4%">Aircraft</th>
                                <th width="4%">ATA</th>
                                <th width="14%" class="order" data-datasort="1" data-titlen="mfg_code">Reference & Component & Item Type</th>
                                <th width="20%" class="order" data-datasort="1" data-titlen="description">Description</th>
                                <th width="11%">Current Hr/Cy</th>
                                <th width="10%">Last C/W</th>
                                <th width="8%">Intervals</th>
                                <th width="10%">Next Due</th>
                                <th width="8%">Remaining</th>
                                <th width="7%">Status</th>
                                <th width="0%"></th>
                                <th width="0%"></th>
                            </tr>
                        </thead>
                        <tbody id="aircraftPartsList">
                        <?php
                        if(!empty($results)) {
                            foreach ($results as $row) {
                                if($type == 'maintenanceItems' || $type == 'adsbstatus') {

                                    echo $this->element('partslist', array('row'=>$row, 'typ'=>$urlp['type'], 'act'=>!empty($urlp['action'])?$urlp['action']:''));
                                
                                } elseif((!empty($row['nextDue']['mos']) || !empty($row['nextDue']['hrs']) || !empty($row['nextDue']['afl'])) && $type != 'maintenanceItems') {
                                    
                                    echo $this->element('partslist', array('row'=>$row, 'typ'=>$urlp['type'], 'act'=>!empty($urlp['action'])?$urlp['action']:'')); 
                                }
                            }
                        } elseif(empty($aircraftIds)) {
                            echo "<tr class='activeTble'>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>No Aircraft Selected.</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>";
                        } 
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report time popup -->
<?php echo $this->element('report_time_popup'); ?>
<!-- Report time popup -->

<script>
$(document).ready(function() {
    //Active/Historical dropdown
    $(document).on("change", "#display-action", function() {
        var pids = $("#downloadPdfId").data('pids') || [];
        //console.log(pids);
        var type = $("#statusType").val();
        var action = $(this).val();

        //Set action attribute with downloadpdf element
        //$('#downloadPdfId').data('action',action);

        var form = $('<form action="maintenance">');
        if(Math.floor(pids) == pids && $.isNumeric(pids)) {
            form.append('<input type="hidden" name="AircraftIds[]" value="'+pids+'">');
        } else {
            pids = pids.split(',');
            $.each(pids, function( index, value ) {
                form.append('<input type="hidden" name="AircraftIds[]" value="'+value+'">');
            });
        }
        form.append('<input type="hidden" name="type" value="'+type+'">');
        form.append('<input type="hidden" name="action" value="'+action+'">');
        $('body').append(form);
        form.submit();
    });

    //Checkbox script to count past due, tolerance and coming due
    $("#ckbCheckAll").click(function () {
        $(".chkBoxCls").prop('checked', $(this).prop('checked'));

        var vals = [];
        $("input.chkBoxCls:visible:checked").each(function() {
            vals.push($(this).val());
        });

        //Add part ids to generate pdf
        $('#downloadPdfId').data('partids',vals);
        $('#projectedPdfId').data('partids',vals);
        
        //Add search value to generate pdf
        $('#downloadPdfId').data('searchval', $('#searchItem').val().toLowerCase());
        $('#projectedPdfId').data('searchval', $('#searchItem').val().toLowerCase());

        //Part ids to delete parts
        var checkedcount = $('input.chkBoxCls:visible:checked').length;
        //console.log(checkedcount, vals);
        $('.removePartCls').data('partids', vals);
        $('.addComplianceCls').data('partids', vals);
        if(checkedcount >= 1) {
            $('.removePartCls').data('aircounts', 'all');
            $('.removePartCls').css('pointer-events', 'auto');
            $('.addComplianceCls').css('pointer-events', 'auto');
            $('.selectCount').html("("+checkedcount+")");
        } else {
            $('.removePartCls').data('aircounts', '');
            $('.removePartCls').css('pointer-events', 'none');
            $('.addComplianceCls').css('pointer-events', 'none');
            $('.selectCount').html('');
        }
    });

    $(".chkBoxCls").click(function () {
        var totalCheckboxes = $('input.chkBoxCls:checkbox').length;
        var checkedcount = $('input.chkBoxCls:checked').length;
        
        if(totalCheckboxes == checkedcount) {
            $('#ckbCheckAll').prop('checked', true);
        } else {
            $('#ckbCheckAll').prop('checked', false);
        }
                
        var vals = [];
        $("input.chkBoxCls:checked").each(function() {
            vals.push($(this).val());
        });

        //Add part ids to generate pdf
        $('#downloadPdfId').data('partids',vals);
        $('#projectedPdfId').data('partids',vals);

        //Add search value to generate pdf
        $('#downloadPdfId').data('searchval', $('#searchItem').val().toLowerCase());
        $('#projectedPdfId').data('searchval', $('#searchItem').val().toLowerCase());

        //Part ids to delete parts
        $('.removePartCls').data('partids', vals);
        $('.addComplianceCls').data('partids', vals);
        if(checkedcount >= 1) {
            $('.removePartCls').data('aircounts', '');
            $('.removePartCls').css('pointer-events', 'auto');
            $('.addComplianceCls').css('pointer-events', 'auto');
            $('.selectCount').html("("+checkedcount+")");
        } else {
            $('.removePartCls').data('aircounts', '');
            $('.removePartCls').css('pointer-events', 'none');
            $('.addComplianceCls').css('pointer-events', 'none');
            $('.selectCount').html('');
        }
    });
    //Checkbox script to count past due, tolerance and coming due

    //Change sorting dynamically
    var oTable = $('#customReport').DataTable({
        "scrollY": $(window).height()/1.70,
        "scrollCollapse": true,
        "searching": false,
        "paging": false,
        "info": false,
        "responsive": true, 
        "columnDefs": [
            { "orderable": false, "targets": 0 },
            { "orderData": [ 11 ], "targets": [ 10 ] },
            { "orderData": [ 12 ], "targets": [ 9 ] }, 
            { "visible": false, "targets": [ 11,12 ] },
            { "searchable": false, "targets": [ 0,5,6,7,8,9,10,11,12 ] }
        ],
        "order": []
    });

    new $.fn.dataTable.FixedHeader( oTable );

    $("select#sortById").change(function() {
        var val = $(this).prop('selectedIndex') + 1;
        oTable.order( [ val, 'asc' ] )
        .draw();
    });

    //Get custom sort values added in columns
    var temp = '';
    $("#customReport").find("thead").on('click', 'th.order', function(event) {
        event.preventDefault();
        //var col_idx = oTable.column(this).index();
        var datasort = $(this).data('datasort');
        var titlen = $(this).data('titlen');

        if(temp != titlen) {
            $(this).prevAll().data('datasort', 1);
            $(this).nextAll().data('datasort', 1);
        } 

        if($(this).data('datasort') == 0) {
            $('#downloadPdfId').data('datasort', 'DESC');
            $('#projectedPdfId').data('datasort', 'DESC');
        } else {
            $('#downloadPdfId').data('datasort', 'ASC');
            $('#projectedPdfId').data('datasort', 'ASC');
        }

        $('#downloadPdfId').data('titlen', titlen);
        $('#projectedPdfId').data('titlen', titlen);
        $(this).data('datasort', !datasort);
        var temp = titlen;
    });
    
    //jQuery custom search
    $("#searchItem").on("keyup", function() {
        var value = $(this).val().toLowerCase();

        $('.loader').show();
        setTimeout(function() {
            $('.loader').hide();
            
            //Searching
            oTable.search(value).draw();

            /**************** 10/12/2020 ******************/
            //Add search value to generate pdf
            $('#downloadPdfId').data('searchval',value);
            $('#projectedPdfId').data('searchval',value);
            var vals = [];
            $("input.chkBoxCls:visible").each(function() {
                vals.push($(this).val());
            });

            //Add part ids to generate pdf
            $('#downloadPdfId').data('partids',vals);
            $('#projectedPdfId').data('partids',vals);
            /**********************************/

            //Remove checked part ids if search used to generate pdf
            //$('#downloadPdfId').data('partids', '');
            $('.removePartCls').data('partids', '');
            $('.addComplianceCls').data('partids', '');
            $(".chkBoxCls").prop('checked', false);
            $("#ckbCheckAll").prop('checked', false);

            //Disable checkbox when search
            //$("#ckbCheckAll").css('pointer-events', 'none');
            $('.selectCount').html('');
        }, 100);

        $("#aircraftPartsList tr").filter(function() {
            //console.log("aaa: ", $(this).text().toLowerCase().indexOf(value));
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $(document).on("change", "#searchByVal", function() {
        $('#downloadPdfId').data('searchby',$(this).val());
        $('#projectedPdfId').data('searchby',$(this).val());
    });

    //Download pdf
    $('#downloadPdfId').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var pids = $(this).data('pids') || [];
        var partids = $(this).data('partids');
        var type = $("#statusType").val();
        var searchval = $(this).data('searchval');
        var searchby = $(this).data('searchby');
        var titlen = $(this).data('titlen');
        var datasort = $(this).data('datasort');
        var action = $(this).data('action');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'generateMultiAircraftPdf']); ?>",
            data: {pids: pids, partids: partids, type: type, searchval:searchval, searchby:searchby, titlen: titlen, datasort: datasort, action: action},
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

    //Get all aircraft details
    $('#aircraftSelectorBtn').on('click', function(e) {
        $('#aircraftDetailsPopup').toggle();
    });

    //Close div
    $('#closeAircraftInfo').on('click', function() {
       $('#aircraftDetailsPopup').hide();
    });

    $('#airCompDetailBtn').on('click', function(e) {
        $('#airCompDetailsPopup').toggle();
    });

    $('.closeAirInfoBtn').on('click', function() {
        $('#airCompDetailsPopup').hide(); 
    });

    //Generate Aircraft Times PDF from popup
    $('.downloadPdfCls').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var pids = $(this).data('pids');
        
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'generateAircraftTimesPdf']); ?>",
            data: {pids: pids},
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

    //Aircraft Checkbox script to count
    $("#airCheckAll").click(function () {
        $(".airChkBoxCls").prop('checked', $(this).prop('checked'));
        var totalCheckboxes = $('input.airChkBoxCls:checkbox').length;
        var checkedcount = $('input.airChkBoxCls:checked').length;
        if(totalCheckboxes == checkedcount) {
            $('#airCountId').html(totalCheckboxes+'/'+totalCheckboxes);
        } else {
            $('#airCountId').html(checkedcount+'/'+totalCheckboxes);
        }
    });

    $(".airChkBoxCls").click(function () {
        var totalCheckboxes = $('input.airChkBoxCls:checkbox').length;
        var checkedcount = $('input.airChkBoxCls:checked').length;
        if(totalCheckboxes == checkedcount) {
            $('#airCheckAll').prop('checked', true);
        } else {
            $('#airCheckAll').prop('checked', false);
        }    
        $('#airCountId').html(checkedcount+'/'+totalCheckboxes);
    });
    
    //Report time model
    $(document).on("click", ".reportTimeCls", function() {
        var plane_id  = $(this).data('plane_id');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'compNewTime']); ?>",
            data: {plane_id: plane_id},
            async : true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#planeCode').html(obj.planeCode);
                    $('#reportTimeModel .modal-body').html(obj.data);
                    $('#reportTimeModel').modal('show');
                    $('#historyTime').data('plane_id', plane_id);
                    $('#errorMsg').html('');
                } else {
                    alert('No data exists.');
                }
            }
        });
    });

    //Projected report time model
    $(document).on("click", "#projectedPdfId", function() {
        var pid = $(this).data('pid');
        var partids = $(this).data('partids');
        var type = $("#statusType").val();
        var reporttype = 'projected';
        var searchval = $(this).data('searchval');
        var searchby = $(this).data('searchby');
        var titlen = $(this).data('titlen');
        var datasort = $(this).data('datasort');
        var action = $(this).data('action');
        //var pidArr = pid.split(',');
        if(Number.isInteger(pid) == true) {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'compProjectedTime']); ?>",
                data: {plane_id: pid, partids: partids, type: type, reporttype:reporttype, searchval:searchval, searchby:searchby, titlen: titlen, datasort: datasort, action: action},
                async : true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    //console.log(obj.data);
                    if(obj.status == 'success') {
                        $('#projectedAirCode').html(obj.planeCode);
                        $('#projectedTimeModel .modal-body').html(obj.data);
                        $('#projectedTimeModel').modal('show');
                        $('#projErrorMsg').html('');
                    } else {
                        alert('No data exists.');
                    }
                }
            });
        } else {
            alert('Please select single aircraft to generate projected report.');
        }
    });

    //Generate projected report
    $(document).on('click', '#saveProjTimeBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var data = $('#projectedTimeFrm').serialize();
        $.ajax({
            url : "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'generateMultiAircraftPdf']); ?>",
            type : 'post',
            data : data,
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

    //Initiate after html load
    $(document).on('show.bs.modal','.modal', function () {
        $('.logDatepicker').datetimepicker({
            format: 'MM-DD-YYYY',
            useCurrent: false,
            maxDate: new Date()
        });
        
        $('.projDatepicker').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: false
        });
    });

    //Validation on report time form
    //https://stackoverflow.com/questions/24670447/how-to-validate-array-of-inputs-using-validate-plugin-jquery
    $("#reportTimeFrm").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'hours_accrued[0]': {
                required: true
            },
            'cycles_accrued[0]': {
                required: true
            }
        },
        messages: {
            'hours_accrued[0]': {
                required: "Please enter hours."
            },
            'cycles_accrued[0]': {
                required: "Please enter cycles."
            }
        }
    });
   
    //Save Report Time details
    $(document).on('click', '#saveRTimeBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        if($('#reportTimeFrm').valid()) {
            var data = $('#reportTimeFrm').serialize();
            $.ajax({
                url : "<?php echo $this->Url->build(['controller' => 'Reports', 'action' => 'addTime']); ?>",
                type : 'post',
                data : data,
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#errorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                    } else {
                        $('#errorMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    }                    
                },
                error : function(){
                    alert('Some error occured. Please try again!');
                    $('#reportTimeModel').modal('hide');
                },
                complete: function () {
                    $('#reportTimeModel').modal('hide');
                }
            });
        }
    });

    //Change readonly status
    $(document).on('click', '.chkClass', function () {
        var key = $(this).data('key');
        var val = $(this).data('val');
        if ($(this).is(':checked')) {
            $('.'+val+'_'+key).prop('readonly', false);
        } else {
            $('.'+val+'_'+key).prop('readonly', true);
        }
    });

    //Historical times
    $(document).on("click", "#historyTime", function() {
        var plane_id  = $(this).data('plane_id');
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'aircraftHistoricalTime']);?>">');
        form.append('<input type="hidden" name="plane_id" value="'+plane_id+'">');
        $('body').append(form);
        form.submit();
    });

    //Parent/Child details popup
    $(document).on("click", ".prChildCls", function() {
        var airid = $(this).data('airid');
        var pid   = $(this).data('pid');
        var type  = $(this).data('type');
        var cid   = $(this).data('cid');
        var typ   = $(this).data('typ');
        var act   = $(this).data('act');
        
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'parentChildInfo']); ?>",
            data: {airid: airid, pid: pid, type: type, cid:cid, typ:typ, act:act},
            async : true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                $('.loader').hide();
                $('#parentChildModel .modal-content').html(response);
                $('#parentChildModel').modal('show');
            },
            error : function() {
                $('.loader').hide();
                $('#parentChildModel .modal-content').html('');
                $('#parentChildModel').modal('hide');
            }
        });
    });

    //Delete parts
    $('.removePartCls').on('click', function() {
        var pids = $(this).data('pids') || [];
        var partids = $(this).data('partids');
        var aircounts = $(this).data('aircounts');

        if (confirm('Are you sure you want to delete this?')) {
            $.ajax({
                url : "<?php echo $this->Url->build(['controller'=>'AirframeComponentParts', 'action'=>'deleteMultiParts']); ?>",
                type : 'post',
                data : {pids: pids, partids: partids, aircounts: aircounts},
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#delErrorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        
                        setTimeout(function() {
                            location.reload(true);
                        }, 1000);
                    } else {
                        $('#delErrorMsg').html('<span style="color:red;">'+obj.message+'</span>').hide(5000);
                    }                  
                }
            });
        }
    });

    //Add compliance to inspections without create a group
    /*$('.addComplianceCls').on('click', function() {
        var pids = $(this).data('pids') || [];
        var partids = $(this).data('partids');

        $('#addComplianceModel .pidCls').val(pids);
        $('#addComplianceModel .partsCls').val(partids);
        $('#addComplianceModel').modal('show');
    });*/

    //Add custom compliance to inspections without create a group
    $(document).on("click", ".addComplianceCls", function() {
        var pids = $(this).data('pids') || [];
        var partids = $(this).data('partids');
        
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Compliance', 'action'=>'addCustomCompliance']);?>">');
        form.append('<input type="hidden" name="aircraftId" value="'+pids+'">');
        form.append('<input type="hidden" name="partids" value="'+partids+'">');
        form.append('<input type="hidden" name="ftype" value="initial">');
        $('body').append(form);
        form.submit();
    });

});
</script>