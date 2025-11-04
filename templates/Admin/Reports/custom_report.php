<?php 
$sessionUser = $this->request->getSession()->read('Auth');; 
use Cake\Routing\Router;
?>
<style>
.dropdown-submenu {
  position: relative;
}

.dropdown-submenu .dropdown-menu {
  top: 0;
  left: -100%;
  margin-top: -1px;
}
</style>
<div class="content sliding">
    <div class="outerWrapper">
        <h2 class="heading border-btm">Maintenance Information Center</h2>
        <h6>Maintenance Items</h6>
        <div class="boxWrapper">
            <div class="box box1 past-due-widget">
                <div id="pastDueId" class="title tilebody"><?php if($pastDue>=100){echo "99+";}else{echo $pastDue;} ?></div>
                <div class="lbltxt tile-label past-due-label">Over Due</div>
            </div>

            <div class="box box2 tolerance-widget" >
                <div id="toleranceDueId" class="title tilebody"><?php if($toleranceDue>=100){echo "99+";}else{echo $toleranceDue;} ?></div>
                <div class="lbltxt tile-label tolerance-label">Current Due</div>
            </div>

            <!-- <div class="box box3 coming-due-widget">
                <div id="comingDueId" class="title tilebody"><?php if($comingDue>=100){echo "99+";}else{echo $comingDue;} ?></div>
                <div class="lbltxt tile-label coming-due-label">Projected Due</div>
            </div> -->

            <div class="box box3 alert-due-widget">
                <div id="alertDueId" class="title tilebody"><?php if($alertDue>=100){echo "99+";}else{echo $alertDue;} ?></div>
                <div class="lbltxt tile-label alert-due-label">Projected Due</div>
            </div>
        </div>
        <!-- table -->
        <div class="page-content mt-35">
            <h2 class="heading">Aircraft Information</h2>
            <div>
                <div class="action-bar dflex">
                    <?php echo $this->element('search_form', array('title'=>'custom')); ?>
                    <?php echo $this->element('sort_by', array('title'=>'custom')); ?>
                </div>

                <div class="table-responsive">
                    <table id="customReport" class="table mb-0 ">
                        <thead>
                            <tr>
                                <th class="check text-nowrap"><input type="checkbox" name="air_check" id="ckbCheckAll" checked="checked"></th>
                                <th class="hidden-xs"  class="text-nowrap"></th>
                                <th style="min-width:180px" id="aircraftId" class="text-nowrap">Aircraft <i class="fa fa-fw fa-sort"></i></th>
                                <th id="reportedDateId"  class="text-nowrap">Reported Date <i class="fa fa-fw fa-sort"></i></th>
                                <th id="reportedHrsId"  class="text-nowrap">Reported Hours <i class="fa fa-fw fa-sort"></i></th>
                                <th id="reportedAflId"  class="text-nowrap">Reported Landings <i class="fa fa-fw fa-sort"></i></th>
                                <th class="text-nowrap">Next Item Due</th>
                                <th class="text-nowrap">Availability</th>
                                <th class="text-nowrap">Action</th>
                                <th id="statusId"  class="text-nowrap">Status <i class="fa fa-fw fa-sort"></i></th>
                            </tr>
                        </thead>
                        <tbody id="aircraftList">
                            <?php
                            foreach ($results as $key=>$row) {
                                $key = $key+1;
                                $evenOdd = (!empty($key) && ($key % 2) == 0) ? 'even' : 'odd';              
                            ?>
                                <tr class="mainTR activeTble <?php echo $evenOdd; ?>">
                                    <td class="check ccc"><input type="checkbox" class="chkBoxCls" name="childcheckbox" value="<?php echo $row['plane']['plane_id']; ?>" checked="checked"></td>
                                    <td class="collapse-tr"><div class="expand-tr"></div></td>
                                    <td class="collapse-tr"><?= h($row['plane']['plane_code']) ?></td>
                                    <td class="collapse-tr"><?= !empty($row['plane']['reported_date']) ? h(strtoupper(date('m/d/Y', strtotime($row['plane']['reported_date'])))) : ''; ?></td>
                                    <td class="collapse-tr"><?= !empty($row['plane']['hours']) ? h($row['plane']['hours']) : ''; ?></td>
                                    <td class="collapse-tr"><?= !empty($row['plane']['cycles']) ? h($row['plane']['cycles']) : ''; ?></td>
                                    <td class="collapse-tr">
                                    <?php
                                    if(!empty($row['extDetails'])) {
                                        $i=0;
                                        foreach ($row['extDetails'] as $key2 => $value2) {
                                            $dateCompare = function($a,$b)
                                                            {
                                                                if(!empty($a['mos']) && !empty($b['mos'])) {
                                                                    $t1 = strtotime($a['mos']);
                                                                    $t2 = strtotime($b['mos']);
                                                                    return $t1 - $t2;
                                                                }
                                                            };  
                                            usort($value2, $dateCompare);
                                            $nxtDueRecord = '';
                                            foreach ($value2 as $key3 => $value3) {
                                                if($i == 0) {
                                                    if(!empty($value3['mos'])) {
                                                        $nxtDueRecord = $value3['mos'];
                                                    } elseif (!empty($value3['hrs'])) {
                                                        $nxtDueRecord = $value3['hrs'];
                                                    } elseif (!empty($value3['afl'])) {
                                                        $nxtDueRecord = $value3['afl'];
                                                    } else {
                                                        $nxtDueRecord = '';
                                                    }
                                                }
                                                $i++;
                                            }
                                            echo $nxtDueRecord;
                                        }
                                    }
                                    ?>
                                    </td>
                                    <td class="collapse-tr updateStatus<?php echo $row['plane']['plane_id']; ?>"><?= !empty(ucwords($row['plane']['status'])) ? h(ucwords($row['plane']['status'])) : '' ?></td>
                                    <td>
                                        <div class="split-btn">
                                            <button class="btn-dropdown btn-default dueListCls" data-plane_id="<?= h($row['plane']['plane_id']) ?>">Due List</button>
                                            <button class="icon-part dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-caret-down"></i>
                                            </button>                               
                                            <ul class="dropdown-content dropdown-menu">
                                                <li><a href="javascript:void(0);" class="quickRefCls" data-plane_id="<?= h($row['plane']['plane_id']) ?>">Quick Reference</a></li>
                                                <li class="dropdown-submenu">
                                                    <a href="#" class="test">Maintenance Items <i class="fa fa-caret-down"></i></a>
                                                    <ul class="dropdown-menu">
                                                        <li><a tabindex="-1" href="javascript:void(0);" class="maintListCls" data-plane_id="<?= h($row['plane']['plane_id']) ?>" data-action="active">Active</a></li>
                                                        <li><a tabindex="-1" href="javascript:void(0);" class="maintListCls" data-plane_id="<?= h($row['plane']['plane_id']) ?>" data-action="historical">Historical</a></li>
                                                    </ul>
                                                </li>
                                                <li class="dropdown-submenu">
                                                    <a href="#" class="test">AD/SB Class <i class="fa fa-caret-down"></i></a>
                                                    <ul class="dropdown-menu">
                                                        <li><a tabindex="-1" href="javascript:void(0);" class="adSBStatusCls" data-plane_id="<?= h($row['plane']['plane_id']) ?>" data-action="active">Active</a></li>
                                                        <li><a tabindex="-1" href="javascript:void(0);" class="adSBStatusCls" data-plane_id="<?= h($row['plane']['plane_id']) ?>" data-action="historical">Historical</a></li>
                                                    </ul>
                                                </li>
                                                <!--li><a href="javascript:void(0);" class="pptReportCls" data-plane_id="<?= h($row['plane']['plane_id']) ?>">PPT Report</a></li-->
                                                <?php
                                                if((!empty($reportTime) && ($reportTime['action']['action_add']==1 || $reportTime['action']['action_edit']==1)) || $sessionUser['id'] == 1){
                                                ?>
                                                <li><a href="javascript:void(0);" class="reportTimeCls" data-plane_id="<?= h($row['plane']['plane_id']) ?>">Report Times</a></li>
                                                <?php } ?>
                                                <li><a href="javascript:void(0);" class="airUtilizeCls" data-plane_id="<?= h($row['plane']['plane_id']) ?>">Update Utilizations</a></li>
                                                <?php
                                                if((!empty($actionItems) && ($actionItems['action']['action_add']==1 || $actionItems['action']['action_edit']==1)) || $sessionUser['id'] == 1){
                                                ?>
                                                <li><a href="javascript:void(0);" class="airAvailbCls" data-plane_id="<?= h($row['plane']['plane_id']) ?>">Update Availability</a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>   
                                    </td>
                                    <td class="collapse-tr">
                                        <?php
                                        if(!empty($row['extDetails'])) {
                                        ?>
                                        <button class="status due-status-overdue btn-due">Past Due</button>
                                        <?php
                                        } else {
                                        ?>
                                        <button class="status btn-notdue">Not Due</button>    
                                        <?php } ?>
                                    </td>
                                </tr>

                                <?php
                                if(!empty($row['extDetails'])) {
                                    $extHtml = '';
                                    foreach ($row['extDetails'] as $key2 => $value2) {
                                        $dateCompare = function($a,$b)
                                                        {
                                                            if(!empty($a['mos']) && !empty($b['mos'])) {
                                                                $t1 = strtotime($a['mos']);
                                                                $t2 = strtotime($b['mos']);
                                                                return $t1 - $t2;
                                                            }
                                                        };
                                        usort($value2, $dateCompare);
                                        
                                        foreach ($value2 as $key3 => $value3) {
                                            $extHtml .='<tr>
                                                <td>&nbsp;</td>';
                                                 
                                            //Title
                                            $title = '';
                                            if(!empty($value3['remMos']) || !empty($value3['remDays'])) {
                                                $title = 'By Date';
                                            } elseif(!empty($value3['remHrs'])) {
                                                $title = 'By Hours';
                                            } elseif(!empty($value3['remAfl'])) {
                                                $title = 'By Cycles';
                                            }
                                            
                                            $extHtml .='<td>'.$title.'</td>';
                                            $extHtml .='<td>'.$value3['description'].'</td>';
                                            $extHtml .='<td>'.$row['plane']['plane_code'].' - '.$value3['log_book'].'</td>';
                                            
                                            //nextdue
                                            $mha = '';
                                            if(!empty($value3['mos'])) {
                                                $mha = $value3['mos'];
                                            } elseif (!empty($value3['hrs'])) {
                                                $mha = round($value3['hrs'],1);
                                            } elseif (!empty($value3['afl'])) {
                                                $mha = $value3['afl'];
                                            }
                                            $extHtml .='<td>'.$mha.'</td>';
                                            
                                            //Remaining
                                            $remaining = '';
                                            if(!empty($value3['remMos'])) {
                                                $remaining .= 'Months: '.$value3['remMos'].'<br>';
                                            }

                                            if(!empty($value3['remDays'])) {
                                                $remaining .= 'Days: '.$value3['remDays'].'<br>';
                                            }

                                            if(!empty($value3['remHrs'])) {
                                                $remaining .= 'Hours: '.round($value3['remHrs'],1).'<br>';
                                            }

                                            if(!empty($value3['remAfl'])) {
                                                $remaining .= 'Cycles: '.$value3['remAfl'].'<br>';
                                            }
                                            $extHtml .='<td>'.$remaining.'</td>';

                                            $extHtml .='<td>'.$this->Html->link("View Item", array('controller' => 'AirframeComponentParts', 'action' => 'edit', $value3['id']), array('class'=>'view-link'), array('escape' => false)).'</td></tr>';
                                        
                                        }
                                    }
                                ?>
                                <tr class="overview-detail" style="display: none;">
                                    <td colspan="11" style="padding: 0">
                                        <table class="table mb-0  ">
                                            <thead>
                                                <tr>
                                                    <th width="8%">&nbsp;</th>
                                                    <th width="15%">Next Item Due</th>
                                                    <th width="24%">Description</th>
                                                    <th width="15%">Tracked By</th>
                                                    <th width="15%">Next Due</th>
                                                    <th width="15%">Remaining</th>
                                                    <th width="8%">Action</th>                              
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                echo $extHtml;
                                                ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <?php
                                }
                            } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- table end -->
    </div>
</div>

<!-- Report time popup -->
<?php echo $this->element('report_time_popup'); ?>
<!-- Report time popup -->

<!-- Update Availability popup -->
<?php echo $this->element('update_availability_popup'); ?>
<!-- Update Availability popup -->

<?php echo $this->Html->script('jquery.sortElements'); ?>
<?php //echo $this->Html->script('customReports'); ?>
<script>
$(document).ready(function() {
    $('.dropdown-submenu a.test').on("click", function(e) {
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });

    //Checkbox script to count past due, tolerance and coming due
    $("#ckbCheckAll").click(function () {
        $(".chkBoxCls").prop('checked', $(this).prop('checked'));

        var vals = [];
        $("input.chkBoxCls:checked").each(function() {
            vals.push($(this).val());
        });

        //Select any aircraft and then click on pastdue/tolerance/coming due to display related items.
        $('.past-due-widget').data('pids', '');
        $('.tolerance-widget').data('pids', '');
        //$('.coming-due-widget').data('pids', '');
        $('.alert-due-widget').data('pids', '');

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Reports', 'action'=>'getItemCounts']); ?>",
            data: {values: vals},
            async : false,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    if(obj.data.pastDue >= 100) {
                        $('#pastDueId').html("99+");
                    } else {
                        $('#pastDueId').html(obj.data.pastDue);
                    }

                    if(obj.data.toleranceDue >= 100) {
                        $('#toleranceDueId').html("99+");
                    } else {
                        $('#toleranceDueId').html(obj.data.toleranceDue);
                    }
                    
                    /*if(obj.data.comingDue >= 100) {
                        $('#comingDueId').html("99+");
                    } else {
                        $('#comingDueId').html(obj.data.comingDue);
                    }*/

                    if(obj.data.alertDue >= 100) {
                        $('#alertDueId').html("99+");
                    } else {
                        $('#alertDueId').html(obj.data.alertDue);
                    }
                } else {
                    $('#pastDueId').html(0);
                    $('#toleranceDueId').html(0);
                    //$('#comingDueId').html(0);
                    $('#alertDueId').html(0);
                }
            }                   
        });
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

        //Select any aircraft and then click on pastdue/tolerance/coming due to display related items.
        $('.past-due-widget').data('pids', vals);
        $('.tolerance-widget').data('pids', vals);
        //$('.coming-due-widget').data('pids', vals);
        $('.alert-due-widget').data('pids', vals);

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Reports', 'action'=>'getItemCounts']); ?>",
            data: {values: vals},
            async : false,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    if(obj.data.pastDue >= 100) {
                        $('#pastDueId').html("99+");
                    } else {
                        $('#pastDueId').html(obj.data.pastDue);
                    }

                    if(obj.data.toleranceDue >= 100) {
                        $('#toleranceDueId').html("99+");
                    } else {
                        $('#toleranceDueId').html(obj.data.toleranceDue);
                    }
                    
                    /*if(obj.data.comingDue >= 100) {
                        $('#comingDueId').html("99+");
                    } else {
                        $('#comingDueId').html(obj.data.comingDue);
                    }*/

                    if(obj.data.alertDue >= 100) {
                        $('#alertDueId').html("99+");
                    } else {
                        $('#alertDueId').html(obj.data.alertDue);
                    }
                } else {
                    $('#pastDueId').html(0);
                    $('#toleranceDueId').html(0);
                    //$('#comingDueId').html(0);
                    $('#alertDueId').html(0);
                }
            }                   
        });
    });
    //Checkbox script to count past due, tolerance and coming due

    //Sorting script
    var table = $('#customReport');
    $('#aircraftId, #reportedDateId, #reportedHrsId, #reportedAflId, #statusId')
        .wrapInner('<span title="sort this column"/>')
        .each(function() {
            var th = $(this),
                thIndex = th.index(),
                inverse = false;
            th.click(function() {
                table.find('td.collapse-tr').filter(function() {
                    return $(this).index() === thIndex;
                }).sortElements(function(a, b) {
                    return $.text([a]) > $.text([b]) ?
                        inverse ? -1 : 1
                        : inverse ? 1 : -1;
                }, function() {
                    // parentNode is the element we want to move
                    return this.parentNode; 
                });
                inverse = !inverse;   
            });     
        });

    //When load click sorting
    /*$( window ).on( "load", function() {
        setTimeout(function() {
            $('#aircraftId').click();
            $('#aircraftId').click();
        }, 500);
    });*/

    //Change sorting dynamically
    $("select#sortById").change(function() {
        $('#aircraftId').click();
    });

    //Search
    $("#searchItem").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#aircraftList tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            $('.overview-detail').css('display','none');
        });
    });
    
    //Show/hide past due values
    $(document).on("click", ".collapse-tr", function() {
        $(this).parent().next('tr.overview-detail:first').toggle();
    });

    //Maintenance due list
    $(document).on("click", ".dueListCls", function() {
        var plane_id  = $(this).data('plane_id');
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'maintenance']);?>">');
        form.append('<input type="hidden" name="AircraftIds" value="'+plane_id+'">');
        form.append('<input type="hidden" name="type" value="maintenance">');
        form.append('<input type="hidden" name="action" value="">');
        $('body').append(form);
        form.submit();
    });

    //Quick reference
    $(document).on("click", ".quickRefCls", function() {
        var plane_id  = $(this).data('plane_id');
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'quickReference']);?>">');
        form.append('<input type="hidden" name="AircraftIds" value="'+plane_id+'">');
        form.append('<input type="hidden" name="type" value="quickRef">');
        form.append('<input type="hidden" name="action" value="">');
        $('body').append(form);
        form.submit();
    });

    //Maintenance list items
    $(document).on("click", ".maintListCls", function() {
        var plane_id  = $(this).data('plane_id');
        var action  = $(this).data('action');
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'maintenance']);?>">');
        form.append('<input type="hidden" name="AircraftIds" value="'+plane_id+'">');
        form.append('<input type="hidden" name="type" value="maintenanceItems">');
        form.append('<input type="hidden" name="action" value="'+action+'">');
        $('body').append(form);
        form.submit();
    });

    //AD/SB status items
    $(document).on("click", ".adSBStatusCls", function() {
        var plane_id  = $(this).data('plane_id');
        var action  = $(this).data('action');
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'maintenance']);?>">');
        form.append('<input type="hidden" name="AircraftIds" value="'+plane_id+'">');
        form.append('<input type="hidden" name="type" value="adsbstatus">');
        form.append('<input type="hidden" name="action" value="'+action+'">');
        $('body').append(form);
        form.submit();
    });

    //Past due list
    $(document).on("click", ".past-due-widget", function() {
        var pids = $(this).data('pids') || [];
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'maintenance']);?>">');
        if(pids !='') {
            form.append('<input type="hidden" name="AircraftIds" value="'+pids+'">');
        }
        form.append('<input type="hidden" name="type" value="past_due">');
        form.append('<input type="hidden" name="action" value="&nbsp;">');
        $('body').append(form);
        form.submit();
    });

    //Coming due list
    $(document).on("click", ".coming-due-widget", function() {
        var pids = $(this).data('pids') || [];
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'maintenance']);?>">');
        if(pids !='') {
            form.append('<input type="hidden" name="AircraftIds" value="'+pids+'">');
        }
        form.append('<input type="hidden" name="type" value="coming_due">');
        form.append('<input type="hidden" name="action" value="">');
        $('body').append(form);
        form.submit();
    });

    //Alert due list
    $(document).on("click", ".alert-due-widget", function() {
        var pids = $(this).data('pids') || [];
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'maintenance']);?>">');
        if(pids !='') {
            form.append('<input type="hidden" name="AircraftIds" value="'+pids+'">');
        }
        form.append('<input type="hidden" name="type" value="alert_due">');
        form.append('<input type="hidden" name="action" value="&nbsp;">');
        $('body').append(form);
        form.submit();
    });

    //Tolerance due list
    $(document).on("click", ".tolerance-widget", function() {
        var pids = $(this).data('pids') || [];
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'maintenance']);?>">');
        if(pids !='') {
            form.append('<input type="hidden" name="AircraftIds" value="'+pids+'">');
        }
        form.append('<input type="hidden" name="type" value="tolerance">');
        form.append('<input type="hidden" name="action" value="&nbsp;">');
        $('body').append(form);
        form.submit();
    });

    //Add discrepancy
    $(document).on("click", ".addDiscrepancyCls", function() {
        var plane_id  = $(this).data('plane_id');
        
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'addDiscrepancy']);?>">');
        form.append('<input type="hidden" name="plane_id" value="'+plane_id+'">');
        $('body').append(form);
        form.submit();
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
                //console.log(obj.data);
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

    //Initiate after html load
    $(document).on('show.bs.modal','.modal', function () {
        $('.logDatepicker').datetimepicker({
            format: 'MM-DD-YYYY',
            useCurrent: false,
            //maxDate: new Date()
        });

        $(".log_date_0").on("dp.change", function() {
            $(".logDatepicker").val($(".log_date_0").val());
        });

        //Update hours
        $('#upTCId .hrsUpCls').on("change", function(e) {
            if($(this).data('comp') == 'Airframe') {
                $('#upTCId .hrsUpCls').val($(this).val());

                var changedHrTxt = $("#upTCId .newHrsCls");
                var addHrVal = 0;
                if($(this).val() != '') {
                    addHrVal = $(this).val();
                }
                $( "#upTCId .newHrsCls" ).each(function( index ) {
                    //console.log(index);
                    //console.log($("#upTCId #hours_"+index).data('currenthr_'+index));
                    if($("#upTCId .newHrsCls") !== changedHrTxt) {
                        var finalHrVal = parseFloat($("#upTCId #hours_"+index).data('currenthr_'+index)) + parseFloat(addHrVal);
                        finalHrVal = finalHrVal.toFixed(1);
                        $("#upTCId #hours_"+index).val(finalHrVal);
                    }
                });
            }
        });

        $('#upTCId .hrsUpCls').on("keyup", function(e) {
            if($(this).data('comp') == 'Airframe') {
                $('#upTCId .hrsUpCls').val($(this).val());

                var changedHrTxt = $("#upTCId .newHrsCls");
                var addHrVal = 0;
                if($(this).val() != '') {
                    addHrVal = $(this).val();
                }
                $( "#upTCId .newHrsCls" ).each(function( index ) {
                    //console.log(index);
                    //console.log($("#upTCId #hours_"+index).data('currenthr_'+index));
                    if($("#upTCId .newHrsCls") !== changedHrTxt) {
                        var finalHrVal = parseFloat($("#upTCId #hours_"+index).data('currenthr_'+index)) + parseFloat(addHrVal);
                        finalHrVal = finalHrVal.toFixed(1);
                        $("#upTCId #hours_"+index).val(finalHrVal);
                    }
                });
            }
        });

        //Update cycles
        $('#upTCId .cycleUpCls').on("change", function(e) {
            if($(this).data('comp') == 'Airframe') {
                $('#upTCId .cycleUpCls').val($(this).val());

                var changedCyTxt = $("#upTCId .newCycleCls");
                var addCyVal = 0;
                if($(this).val() != '') {
                    addCyVal = $(this).val();
                }
                $( "#upTCId .newCycleCls" ).each(function( index ) {
                    //console.log(index);
                    //console.log($("#upTCId #cycles_"+index).data('currentcy_'+index));
                    if($("#upTCId .newCycleCls") !== changedCyTxt) {
                        var finalCyVal = parseFloat($("#upTCId #cycles_"+index).data('currentcy_'+index)) + parseFloat(addCyVal);
                        $("#upTCId #cycles_"+index).val(finalCyVal);
                    }
                });
            }
        });

        $('#upTCId .cycleUpCls').on("keyup", function(e) {
            if($(this).data('comp') == 'Airframe') {
                $('#upTCId .cycleUpCls').val($(this).val());

                var changedCyTxt = $("#upTCId .newCycleCls");
                var addCyVal = 0;
                if($(this).val() != '') {
                    addCyVal = $(this).val();
                }
                $( "#upTCId .newCycleCls" ).each(function( index ) {
                    //console.log(index);
                    //console.log($("#upTCId #cycles_"+index).data('currentcy_'+index));
                    if($("#upTCId .newCycleCls") !== changedCyTxt) {
                        var finalCyVal = parseFloat($("#upTCId #cycles_"+index).data('currentcy_'+index)) + parseFloat(addCyVal);
                        $("#upTCId #cycles_"+index).val(finalCyVal);
                    }
                });
            }
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
                    setTimeout(function() {
                        $('#reportTimeModel').modal('hide');
                    }, 1000);
                },
                complete: function () {
                    setTimeout(function() {
                        $('#reportTimeModel').modal('hide');
                    }, 1000);
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

    //Update aircraft availability model
    $(document).on("click", ".airAvailbCls", function() {
        var plane_id  = $(this).data('plane_id');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'aircraftAvailability']); ?>",
            data: {plane_id: plane_id},
            async : true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#updateAvalModel .modal-body').html('<div class="x_panel"><div class="x_content"><form method="post" id="airAvailFrm"><input type="hidden" name="plane_id" value="'+obj.data.id+'"><select name="status" id="status" class="form-control col-md-7 col-xs-12 selectpicker" data-show-subtext="true" data-live-search="true" value="'+obj.data.status+'"><option value="" disabled selected>Enter status</option><option value="available">Available</option><option value="grounded">Grounded</option><option value="out_for_service">Out For Service</option><option value="unavailable">Unavailable</option></select></form></div></div>');
                    $('#updateAvalModel').modal('show');
                    
                    //Set selected value in dropdown
                    var text = $("select[name=status] option[value='"+obj.data.status+"']").text();
                    $('.bootstrap-select .filter-option').text(text);
                    $('select[name=status]').val(obj.data.status);
                    $('.selectpicker').selectpicker('refresh');

                    $('#errorMsgId').html('');
                } else {
                    alert('No data exists.');
                }
            }
        });
    });

    //Save Report Time details
    $(document).on('click', '#updateAirStatusBtn', function(e){
        e.stopPropagation();
        e.preventDefault();
        var data = $('#airAvailFrm').serialize();
        $.ajax({
            url : "<?php echo $this->Url->build(['controller' => 'Reports', 'action' => 'updateAirStatus']); ?>",
            type : 'post',
            data : data,
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#errorMsgId').html('<span style="color:green;">'+obj.message+'</span>');
                    $('.updateStatus'+obj.data.plane_id).html(obj.data.status);
                } else {
                    $('#errorMsgId').html('<span style="color:red;">'+obj.message+'</span>');
                }                    
            },
            error : function(){
                alert('Some error occured. Please try again!');
                $('#updateAvalModel').modal('hide');
            },
            complete: function () {
            }
        });
    });

    //Initiate select picker
    $(document).on('show.bs.modal','.modal', function () {
        $('#airAvailFrm select.selectpicker').selectpicker();
    });

    //Historical times
    $(document).on("click", "#historyTime", function() {
        var plane_id = $(this).data('plane_id');
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'aircraftHistoricalTime']);?>">');
        form.append('<input type="hidden" name="plane_id" value="'+plane_id+'">');
        $('body').append(form);
        form.submit();
    });

    //Update Utilization
    $(document).on("click", ".airUtilizeCls", function() {
        var plane_id = $(this).data('plane_id');
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Utilizations', 'action'=>'aircraftUtilization']);?>">');
        form.append('<input type="hidden" name="plane_id" value="'+plane_id+'">');
        $('body').append(form);
        form.submit();
    });

    //Download PPT
    $('.pptReportCls').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();

        var airId  = $(this).data('plane_id');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'PptReports', 'action'=>'index']); ?>",
            data: {airId: airId, type:'alldues'},
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
            }
        });
    });

});
</script>