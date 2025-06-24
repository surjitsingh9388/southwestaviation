<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;
?>
<div class="content sliding" style="position:fixed;">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Quick Reference Maintenance Items</h2>
            <div class="btnWrap">
                <?php
                if((!empty($reportAction) && $reportAction['action']['action_add']==1) || $sessionUser['id'] == 1) {
                    if(!empty($refResults)) {
                ?>
                        <a class="btn btn-default" href="javascript:void(0);" data-pids="<?= h($planeId); ?>" data-action="<?= h($action); ?>" data-partids="" id="downloadPdfId">Generate Report</a>
                <?php
                    }
                }
                ?>
            </div>
        </div>

        <div class="page-content mt-35">
            <div class="maintenanceWrap">
                <div class="action-bar">
                    <div class="actionbar-lft">
                        <div class="lftWrap">
                            <?php echo $this->element('search_form', array('title'=>'quickRef')); ?>
                        </div>

                        <div class="split-btn pull-right actionMenu sortWrap">
                            <button class="btn-dropdown btn-default">Action<span class="selectCount"></span></button>
                            <button class="icon-part dropdown-toggle actionCls" data-toggle="dropdown">
                                <i class="fa fa-caret-down"></i>
                            </button>
                            <?php
                            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $sessionUser['id'] == 1){
                            ?>                               
                            <div class="dropdown-content dropdown-menu actionLinks" style="pointer-events: none;">
                                <a href="javascript:void(0);" class="removeRefPartCls" data-planeid="<?php echo $planeId; ?>">Remove</a>
                            </div>
                            <?php
                            }
                            ?>
                            
                            <?php
                            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1){
                            ?>
                            <a href="javascript:void(0);" class="btn btn-default ml-10" data-planeid="<?php echo $planeId; ?>" id="allPartList">Add</a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="customReport" class="table mb-0" width="100%">
                        <thead>
                            <tr>
                                <th width="4%" class="check text-nowrap"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                                <th width="2%" class="text-nowrap"></th>
                                <th width="2%" class="text-nowrap">Aircraft</th>
                                <th width="4%" class="text-nowrap">ATA</th>
                                <th width="14%" class="text-nowrap">Reference & Component & Item Type</th>
                                <th width="20%" class="text-nowrap">Description</th>
                                <th width="11%"class="text-nowrap">Current Hr/Cy</th>
                                <th width="10%"class="text-nowrap">Last C/W</th>
                                <th width="8%" class="text-nowrap">Intervals</th>
                                <th width="10%" class="text-nowrap">Next Due</th>
                                <th width="8%" class="text-nowrap">Remaining</th>
                                <th width="7%" class="text-nowrap">Status</th>
                                <th width="0%" class="text-nowrap" style="display: none;"></th>
                                <th width="0%" class="text-nowrap" style="display: none;"></th>
                            </tr>
                        </thead>
                        <tbody id="aircraftPartsList">
                            <?php
                            if(!empty($refResults)) {
                                foreach ($refResults as $row) {                       
                                    echo $this->element('partslist', array('row'=>$row, 'prChild'=>true, 'typ'=>'quickRef', 'act'=>''));
                                } 
                            } else {
                                echo "<tr class='activeTble'>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>No Parts Selected.</td>
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
$(document).ready(function(){
    //Checkbox script to count past due, tolerance and coming due
    $("#ckbCheckAll").click(function () {
        $(".chkBoxCls").prop('checked', $(this).prop('checked'));

        var vals = [];
        $("input.chkBoxCls:visible:checked").each(function() {
            vals.push($(this).val());
        });

        //Add part ids to generate pdf
        $('#downloadPdfId').data('partids',vals);

        //Add search value to generate pdf
        $('#downloadPdfId').data('searchval', $('#searchItem').val().toLowerCase());

        //Part ids to delete quick reference
        var checkedcount = $('input.chkBoxCls:visible:checked').length;
        $('.removeRefPartCls').data('partids', vals);
        if(checkedcount >= 1) {
            $('.removeRefPartCls').css('pointer-events', 'auto');
            $('.selectCount').html("("+checkedcount+")");
        } else {
            $('.removeRefPartCls').css('pointer-events', 'none');
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

        //Add search value to generate pdf
        $('#downloadPdfId').data('searchval', $('#searchItem').val().toLowerCase());

        //Part ids to delete quick reference
        $('.removeRefPartCls').data('partids', vals);
        if(checkedcount >= 1) {
            $('.removeRefPartCls').css('pointer-events', 'auto');
            $('.selectCount').html("("+checkedcount+")");
        } else {
            $('.removeRefPartCls').css('pointer-events', 'none');
            $('.selectCount').html('');
        }
    });
    //Checkbox script to count past due, tolerance and coming due

    var oTable = $('#customReport').DataTable({
        "scrollY": $(window).height()/1.70,
        "scrollCollapse": true,
        "searching": false,
        "paging": false,
        "info": false,
        "responsive": true, 
        "columnDefs": [
            { "orderable": false, "targets": 0 },
            { "orderData": [ 12 ], "targets": [ 11 ] },
            { "orderData": [ 13 ], "targets": [ 10 ] }, 
            { "visible": false, "targets": [ 12,13 ] },
        ],
        "order": []
    });
    //console.log($(window).height()/1.75);
    new $.fn.dataTable.FixedHeader( oTable );

    //jQuery custom search
    $("#searchItem").on("keyup", function() {
        var value = $(this).val().toLowerCase();

        $('.loader').show();
        setTimeout(function() {
            $('.loader').hide();
            
            /**************** 10/12/2020 ******************/
            //Add search value to generate pdf
            $('#downloadPdfId').data('searchval',value);
            var vals = [];
            $("input.chkBoxCls:visible").each(function() {
                vals.push($(this).val());
            });

            //Add part ids to generate pdf
            $('#downloadPdfId').data('partids',vals);
            /**********************************/

            //Remove checked part ids if search used to generate pdf
            //$('#downloadPdfId').data('partids', '');
            $('.removeRefPartCls').data('partids', '');
            $(".chkBoxCls").prop('checked', false);
            $("#ckbCheckAll").prop('checked', false);
            $('.selectCount').html('');
        }, 100);  

        $("#aircraftPartsList tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    /*** Parts list popup ***/
    //Parts List Popup
    $(document).on('click', '#allPartList', function() {
        var planeId = $(this).data('planeid');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'AirframeComponentParts', 'action'=>'getPartsRecord']); ?>",
            data: {planeId: planeId, quickRef:'quickRef'},
            async : true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                //console.log(obj.data);
                if(obj.status == 'success') {
                    $('#partsListModel .modal-body').html(obj.data);
                    $('#partsListModel').modal('show');
                    $('.loader').hide();
                } else {
                    $('.loader').hide();
                    alert('No data exists.');
                }
            }
        });
    });

    //Initiate popup model after html load
    $(document).on('show.bs.modal','.modal', function () {
        //Dropdown select
        $("#sortByIdPopup").selectpicker();

        //Change sorting dynamically
        var oTable = $('#customReportPopup').DataTable({
            "searching": false,
            "paging": false,
            "info": false,
            "columnDefs": [
                { "orderable": false, "targets": 0 }
            ],
            "order": []
        });

        $("select#sortByIdPopup").change(function() {
            var val = $(this).prop('selectedIndex') + 1;
            oTable.order( [ val, 'asc' ] )
            .draw();
        });
        
        //jQuery custom search
        $("#searchItemPopup").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#aircraftPartsListPopup tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        //Checkbox script to select/unselect all records
        $("#ckbCheckAllPopup").click(function () {
            $(".chkBoxClsPopup").prop('checked', $(this).prop('checked'));

            var vals = [];
            $("input.chkBoxClsPopup:checked").each(function() {
                vals.push($(this).val());
            });
            $('#selPartIds').data('childpartids',vals);
        });

        $(".chkBoxClsPopup").click(function () {
            var totalCheckboxes = $('input.chkBoxClsPopup:checkbox').length;
            var checkedcount = $('input.chkBoxClsPopup:checked').length;
            
            if(totalCheckboxes == checkedcount) {
                $('#ckbCheckAllPopup').prop('checked', true);
            } else {
                $('#ckbCheckAllPopup').prop('checked', false);
            }
                    
            var vals = [];
            $("input.chkBoxClsPopup:checked").each(function() {
                vals.push($(this).val());
            });
            $('#selPartIds').data('childpartids',vals);
        });
        //Checkbox script

        //All parts for quick reference
        $(document).on('click', '.addToPBtn', function(e) {
            e.stopPropagation();
            e.preventDefault();

            var btnVal = $(this).val();
            var planeId = $('#selPlaneId').data('planeid');
            var partIds = $('#selPartIds').data('childpartids');

            $.ajax({
                url : "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'addToQuickRef']); ?>",
                type : 'post',
                data : {planeId: planeId, partIds: partIds},
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#addToPEMsg').html('<span style="color:green;">'+obj.message+'</span>').hide(5000);
                        if(btnVal != 'Add') {
                            location.reload(true);
                        }
                    } else {
                        $('#addToPEMsg').html('<span style="color:red;">'+obj.message+'</span>').hide(5000);
                    }                    
                },
                error : function() {
                    alert('Some error occured. Please try again!');
                    $('#partsListModel').modal('hide');
                },
                complete: function () {
                    //$('#partsListModel').modal('hide');
                }
            });
        });
    });
    /*** Parts list popup ***/

    //Delete parts
    $('.removeRefPartCls').on('click', function() {
        var planeId = $(this).data('planeid');
        var partIds = $(this).data('partids');

        if (confirm('Are you sure you want to remove this item from Quick Reference?')) {
            $.ajax({
                url : "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'deleteQuickRef']); ?>",
                type : 'post',
                data : {planeId: planeId, partIds: partIds},
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#delErrorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        location.reload(true);
                    } else {
                        $('#delErrorMsg').html('<span style="color:red;">'+obj.message+'</span>').hide(5000);
                    }                  
                }
            });
        }
    });

    //Download pdf
    $('#downloadPdfId').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var type      = 'quickRef';
        var pids      = $(this).data('pids') || [];
        var partids   = $(this).data('partids');
        var searchval = $(this).data('searchval');
        var action    = $(this).data('action');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'generateMultiAircraftPdf']); ?>",
            data: {pids: pids, partids: partids, type: type, searchval: searchval, action:action},
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('.loader').hide();
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