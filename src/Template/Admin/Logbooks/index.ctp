<?php 
$sessionUser = $this->request->session()->read('Auth.User');
echo $this->Html->css('/css/treeview'); 
?>
<style type="text/css">
    .ui-autocomplete {
        z-index: 99999;
    }

    ul#tree1 li {
        text-decoration: none;
        list-style: none;
        cursor: pointer;
        font-weight: bold;
        line-height: 30px;
        color: #369;
    }
</style>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Aircraft Tree</h2>
        </div>
        
        <div class="page-content mt-35">
            <div class="tableScroll">
                <div class="col-md-12">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <ul id="tree1" class="tree">
                            <?php
                            foreach($aircrafts as $aircraft) {
                            ?>
                            <li>
                                <i class="indicator glyphicon glyphicon-plus-sign displayChild" data-id="<?php echo $aircraft['id']; ?>" data-level="0"></i>
                                <strong><?php echo $aircraft['plane_code'].' - '.$aircraft['plane_serial_number']; ?></strong> &nbsp;&nbsp;
                                <div class="btn-group split-btn">
                                    <button type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <!-- <a class="dropdown-item addPopUp" data-id="<?php echo $aircraft['id']; ?>" data-level="0" data-toggle="modal" data-target="#edit-modal" href="#">Add</a> -->
                                        <a class="dropdown-item openPopUp" data-id="<?php echo $aircraft['id']; ?>" data-level="0" data-toggle="modal" data-target="#addItemModel" href="#">Properties</a>
                                    </div>
                                </div>
                                <ul class="addChilds_<?php echo $aircraft['id']; ?>"></ul>
                            </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logbooks popup -->
<?php echo $this->element('logbooks_popup'); ?>
<!-- Logbooks popup -->

<?php //echo $this->Html->script('/js/treeview'); ?>
<script>
$(document).ready(function() {
    //Display all the child records
    $(document).on('click', '.displayChild', function(e) {
        e.preventDefault();

        var curObj = $(this);
        var that = $(this).data('id');
        var level = $(this).data('level');
        if($(this).hasClass('glyphicon-minus-sign')) {
            //Toggle ul li and change class
            $(this).parent().find('ul').toggle();
            $(this).removeClass('glyphicon-minus-sign').addClass('glyphicon-plus-sign');
        } else {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller'=>'Logbooks', 'action'=>'getChilds']); ?>",
                data: {pid: $(this).data('id'), level: level},
                async : true,
                beforeSend: function () {
                    $('.loader').show();
                },
                success: function (response) {      
                    var obj = JSON.parse(response);
                    if (obj.status == 'success') {
                        var resHtml='';
                        $.each(obj.data, function (key, value) {
                            var title = '';
                            if(level == 0) {
                                if(value.serial_no != '') {
                                    logtitle = value.log_book+' - '+value.serial_no;
                                } else {
                                    if(value.log_book == 'Airframe') {
                                        logtitle = value.log_book;
                                    } else {
                                        logtitle = value.log_book+' '+value.position;
                                    }
                                }
                            } else if(level == 1 || level == 2) {
                                logtitle = value.title;
                                /*if(value.serial_number != '') {
                                    logtitle = value.title+' - '+value.serial_number;
                                }*/
                            }

                            if(level < 2) {
                                resHtml += '<li class="record_'+value.id+'" data-id="'+value.id+'"><i class="indicator glyphicon glyphicon-plus-sign displayChild" data-id="'+value.id+'" data-pid="'+value.id+'" data-level="'+(level+1)+'"></i>'+logtitle+'&nbsp;<div class="btn-group split-btn"><button type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-caret-down"></i></button><div class="dropdown-menu"><a class="dropdown-item openPopUp" data-id="'+value.id+'" data-level="'+(level+1)+'" data-toggle="modal" data-target="#addItemModel" href="#">Properties</a></div></div><ul class="addChilds_'+value.id+'"></ul></li>';
                            } else {
                                resHtml += '<li class="record_'+value.id+'" data-id="'+value.id+'">'+logtitle+'&nbsp;<div class="btn-group split-btn"><button type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-caret-down"></i></button><div class="dropdown-menu"><a class="dropdown-item openPopUp" data-id="'+value.id+'" data-level="'+(level+1)+'" data-toggle="modal" data-target="#addItemModel" href="#">Properties</a></div></div><ul class="addChilds_'+value.id+'"></ul></li>';
                            }
                        });
                        
                        setTimeout(function() {
                            $('.loader').hide();

                            curObj.siblings('.addChilds_'+that).show();
                            curObj.siblings('.addChilds_'+that).html(resHtml);
                            curObj.toggleClass("glyphicon-plus-sign glyphicon-minus-sign");
                        }, 500);
                    } else {                        
                        setTimeout(function() {
                            $('.loader').hide();

                            curObj.siblings('.addChilds_'+that).html('');
                            curObj.toggleClass("glyphicon-plus-sign glyphicon-minus-sign");
                        }, 500);
                    }
                }
            });  
        }
    });

    //Open properties list popup
    $(document).on('click', '.openPopUp', function(e) {
        e.preventDefault();

        var curObj = $(this);
        var that = $(this).data('id');
        var level = $(this).data('level');
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Logbooks', 'action'=>'addItemPopup']); ?>",
            data: {pid: $(this).data('id'), level: level},
            async : true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function (response) {      
                var obj = JSON.parse(response);
                if (obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#addItemModel .modal-body').html(obj.data);
                        $('#addItemModel').modal('show');
                    }, 500);
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#addItemModel .modal-body').html(obj.data);
                        $('#addItemModel').modal('show');
                    }, 500);
                }
            }
        });
    });

    //Validation on report time form
    //https://stackoverflow.com/questions/24670447/how-to-validate-array-of-inputs-using-validate-plugin-jquery
    $("#addItemFrm").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'nomenclature': {
                required: true
            }
        },
        messages: {
            'nomenclature': {
                required: "Please enter nomenclature."
            }
        }
    });

    //Save Report Time details
    $(document).on('click', '#addItemBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        if($('#addItemFrm').valid()) {
            var data = $('#addItemFrm').serialize();
            $.ajax({
                url : "<?php echo $this->Url->build(['controller'=>'Logbooks', 'action'=>'addItemDetails']); ?>",
                type : 'post',
                data : data,
                beforeSend: function () {
                    $('.loader').show();
                },
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#addItemMsg').html('<span style="color:green;">'+obj.message+'</span>').hide(5000);
                    } else {
                        $('#addItemMsg').html('<span style="color:red;">'+obj.message+'</span>').hide(5000);
                    }                    
                },
                error : function(){
                    alert('Some error occured. Please try again!');
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#addItemModel').modal('hide');
                    }, 1000);
                },
                complete: function () {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#addItemModel').modal('hide');
                    }, 1000);
                }
            });
        }
    });

    //Update item popup
    $(document).on('click', '.editItemCls', function(e) {
        e.preventDefault();

        var curObj = $(this);
        var that = $(this).data('id');
        var level = $(this).data('level');
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Logbooks', 'action'=>'updateItemPopup']); ?>",
            data: {pid: $(this).data('id'), level: level},
            async : true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function (response) {      
                var obj = JSON.parse(response);
                if (obj.status == 'success') {                    
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateItemModel .modal-body').html(obj.data);
                        $('#updateItemModel').modal('show');
                    }, 500);
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateItemModel .modal-body').html(obj.data);
                        $('#updateItemModel').modal('show');
                    }, 500);
                }
            }
        });
    });

    //Validation on report time form
    //https://stackoverflow.com/questions/24670447/how-to-validate-array-of-inputs-using-validate-plugin-jquery
    $("#updateItemFrm").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'nomenclature': {
                required: true
            }
        },
        messages: {
            'nomenclature': {
                required: "Please enter nomenclature."
            }
        }
    });

    //Update Report Time details
    $(document).on('click', '#updateItemBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        if($('#updateItemFrm').valid()) {
            var data = $('#updateItemFrm').serialize();
            $.ajax({
                url : "<?php echo $this->Url->build(['controller'=>'Logbooks', 'action'=>'addItemDetails']); ?>",
                type : 'post',
                data : data,
                beforeSend: function () {
                    $('.loader').show();
                },
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#updateItemMsg').html('<span style="color:green;">'+obj.message+'</span>').hide(5000);
                    } else {
                        $('#updateItemMsg').html('<span style="color:red;">'+obj.message+'</span>').hide(5000);
                    }                    
                },
                error : function(){
                    alert('Some error occured. Please try again!');
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateItemModel').modal('hide');
                    }, 1000);
                },
                complete: function () {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateItemModel').modal('hide');
                    }, 1000);
                }
            });
        }
    });

    //Initiate select picker
    $(document).on('show.bs.modal','.modal', function () {
        $('.installedDate').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: false
        });

        $('.lastInspDate').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: false
        });

        $('.dateROCls').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: false
        });

        //Overhaul check
        $('.overhIndiCheck').on('click', function() {
            if($('input.overhIndiCheck').is(':checked')) {
                $('.timeCyCls').prop('readonly', false);
            } else {
                $('.timeCyCls').val('').prop('readonly', true);
            }
        });

        //Hours check
        $('.hourCheck').on('click', function() {
            if($('input.hourCheck').is(':checked')) {
                $('.hrROCls').prop('readonly', false);
            } else {
                $('.hrROCls').val('').prop('readonly', true);
            }
        });

        //Cycles check
        $('.cyclesCheck').on('click', function() {
            if($('input.cyclesCheck').is(':checked')) {
                $('.cyROCls').prop('readonly', false);
            } else {
                $('.cyROCls').val('').prop('readonly', true);
            }
        });

        //Days check
        $('.daysCheck').on('click', function() {
            if($('input.daysCheck').is(':checked')) {
                $('.daROCls').prop('readonly', false);
            } else {
                $('.daROCls').val('').prop('readonly', true);
            }
        });

        /***** Auto calculate *************/
        //Change compliance date
        $("#last_inspection_date").datetimepicker({
            useCurrent: false,
            format: 'MM/DD/YYYY'
        }).on('dp.change', function(e) {
            var a = moment(e.date._d);
            var lastCwMos = a.format('M/D/Y');
            if ($('input.overhIndiCheck').is(':checked')) {
                var overhaulChk = 'yes';
            } else {
                var overhaulChk = 'no';
            }        
            
            addNextDues(lastCwMos, overhaulChk);
        });

        //Change next due values
        $(".keypress").keyup(function() {
            var lastCwMos = $('#last_inspection_date').val();
            if ($('input.overhIndiCheck').is(':checked')) {
                var overhaulChk = 'yes';
            } else {
                var overhaulChk = 'no';
            } 
            
            addNextDues(lastCwMos, overhaulChk);
        });

        //When add value in part (New/Overhaul) and any option selected
        $(".overhIndiCheck").on('click', function() {
            var lastCwMos = $('#last_inspection_date').val();         
            
            if ($('input.overhIndiCheck').is(':checked')) {
                var overhaulChk = 'yes'; 
                addNextDues(lastCwMos, overhaulChk);
            } else {
                var overhaulChk = 'no';
                addNextDues(lastCwMos, overhaulChk);
            }
        });

        //Common function to update next and remaining dues
        function addNextDues(lastCwMos, overhaulChk) { 
            var partHrs;
            var partAfl;
            if(overhaulChk == 'yes') {
                partHrs = $('#time_since_overhaul').val();
                partAfl = $('#cycle_since_overhaul').val();
            } else if(overhaulChk == 'no') {
                partHrs = $('#time_since_new').val();
                partAfl = $('#cycle_since_new').val();
            }

            var data = {
                    levelId: $('#levelid').val(),
                    planeId: $('#plane_id').val(), 
                    compId: $('#airframe_component_id').val(),
                    subCompId: $('#sub_component_id').val(),
                    subComp2Id: $('#sub_component2_id').val(),  
                    lastCwMos: lastCwMos, 
                    lastCwHrs: $('#last_inspection_hours').val(), 
                    lastCwAfl: $('#last_inspection_cycles').val(),
                    intvHrs: $('#interval_hours').val(), 
                    intvAfl: $('#interval_cycles').val(),
                    intvDay: $('#interval_days').val(),
                    partHrs: partHrs,
                    partAfl: partAfl
            };
            
            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller'=>'Logbooks', 'action'=>'addUpdateNextdues']); ?>",
                data: data,
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#due_date').val(obj.data.nextMos);
                        $('#due_hours').val(obj.data.nextHrs);
                        $('#due_cycles').val(obj.data.nextAfl);
                    }
                }                   
            });
        }

        /*============================Update nextdue =================**/
        //Change compliance date
        $("#lastInspDate").datetimepicker({
            useCurrent: false,
            format: 'MM/DD/YYYY'
        }).on('dp.change', function(e) {
            var a = moment(e.date._d);
            var lastCwMos = a.format('M/D/Y');
            if ($('input.upOHIndiChk').is(':checked')) {
                var overhaulChk = 'yes';
            } else {
                var overhaulChk = 'no';
            }        
            
            updateNextdues(lastCwMos, overhaulChk);
        });

        //Change next due values
        $(".upkeypress").keyup(function() {
            var lastCwMos = $('#lastInspDate').val();
            if ($('input.upOHIndiChk').is(':checked')) {
                var overhaulChk = 'yes';
            } else {
                var overhaulChk = 'no';
            } 
            
            updateNextdues(lastCwMos, overhaulChk);
        });

        //When add value in part (New/Overhaul) and any option selected
        $('.upOHIndiChk').on('click', function() {
            var lastCwMos = $('#lastInspDate').val();         
            
            if ($('input.upOHIndiChk').is(':checked')) {
                var overhaulChk = 'yes'; 
                updateNextdues(lastCwMos, overhaulChk);
            } else {
                var overhaulChk = 'no';
                updateNextdues(lastCwMos, overhaulChk);
            }
        });

        //Common function to update next and remaining dues
        function updateNextdues(lastCwMos, overhaulChk) { 
            var partHrs;
            var partAfl;
            if(overhaulChk == 'yes') {
                partHrs = $('#timeSinceOverhaul').val();
                partAfl = $('#cycleSinceOverhaul').val();
            } else if(overhaulChk == 'no') {
                partHrs = $('#timeSinceNew').val();
                partAfl = $('#cycleSinceNew').val();
            }

            var data = {
                    levelId: $('#levelId').val(),
                    planeId: $('#planeId').val(), 
                    compId: $('#airCompId').val(),
                    subCompId: $('#subCompId').val(),
                    subComp2Id: $('#subComp2Id').val(),  
                    lastCwMos: lastCwMos, 
                    lastCwHrs: $('#lastInspHours').val(), 
                    lastCwAfl: $('#lastInspCycles').val(),
                    intvHrs: $('#intvHours').val(), 
                    intvAfl: $('#intvCycles').val(),
                    intvDay: $('#intvDays').val(),
                    partHrs: partHrs,
                    partAfl: partAfl
                };
            
            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller'=>'Logbooks', 'action'=>'addUpdateNextdues']); ?>",
                data: data,
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#dueDate').val(obj.data.nextMos);
                        $('#dueHours').val(obj.data.nextHrs);
                        $('#dueCycles').val(obj.data.nextAfl);
                    }
                }                   
            });
        }
        /***** End Auto calculate *************/
    });

    //Delete item
    $(document).on('click', '.deleteItemCls', function(e) {
        e.preventDefault();

        var curObj = $(this);
        var that = $(this).data('id');
        var level = $(this).data('level');
        if (confirm('Are you sure you want to delete this?')) {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller'=>'Logbooks', 'action'=>'deleteItem']); ?>",
                data: {pid: $(this).data('id'), level: level},
                async : true,
                beforeSend: function () {
                    $('.loader').show();
                },
                success: function (response) {      
                    var obj = JSON.parse(response);
                    if (obj.status == 'success') {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('.logItem_'+that).html('<td colspan="13" style="text-align:center;color:green;">'+obj.message+'</td>').hide(3000);
                        }, 500);
                        
                    } else {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('.logItem_'+that).html('<td colspan="13" style="text-align:center;color:red;">'+obj.message+'</td>').hide(3000);
                        }, 500);
                    }
                }
            });
        }
    });

});
</script>