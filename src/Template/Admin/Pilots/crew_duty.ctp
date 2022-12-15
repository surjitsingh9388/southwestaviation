<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

$allPilots = $pilotComp->getPilots();
$months = $pilotComp->getMonthsList();
?>

<div class="content sliding">
    <div class="calenderWrap">
   		<div class="selectWrap">
   			<div class="lft-Wrapper">
   				<?php 
   				echo $this->Form->input('pilot_id', array('options'=>$allPilots, 'class'=>'form-control selectpicker selPilotCls selDropDCls', 'div'=>false, 'label' => false, 'value'=>$pilotId)); 
   				
                echo $this->Form->input('current_month', array('options'=>$months, 'class'=>'form-control selectpicker selMonthCls selDropDCls', 'div'=>false, 'label' => false, 'value'=>$currentMon)); 
                
                $years = [];
                $currentYear = date('Y');
		        for($i=2000; $i<=$currentYear+1; $i++) {
		            $years[$i] = $i;
		        }
		        echo $this->Form->input('current_year', array('options'=>$years, 'class'=>'form-control selectpicker selYearCls selDropDCls', 'div'=>false, 'label' => false, 'value'=>$currentYr));
                ?>
   			</div>
   			<div class="rgt-Wrapper">
   				<div class="dataTxt">
   					<span class="blackFill"></span>
   					<span>Duty</span>
   				</div>
   				<div class="dataTxt">
   					<span class="blueFill"></span>
   					<span>Part 91 Flight</span>
   				</div>
   				<div class="dataTxt">
   					<span class="yellowFill"></span>
   					<span>Part 135 Flight</span>
   				</div>
   				<div class="dataTxt">
   					<span class="greyFill"></span>
   					<span>Rest</span>
   				</div>
   				<div class="dataTxt">
   					<span class="redFill"></span>
   					<span>Day Off</span>
   				</div>
   			</div>
   		</div>
   		<div class="table-responsive">
   			<?php
   			//Display calendar
   			echo $result;
   			?>
   		</div>
    </div>
</div>

<?php echo $this->element('pilots_popup'); ?>

<?php //echo $this->Html->script('pilots'); ?>
<script>
$(document).ready(function() {
	//Select pilot
    $(".selDropDCls").on("change", function() {
        var pilotid = $('#pilot-id').val();
        var selMonth = $('#current-month').val();
        var selYear = $('#current-year').val();

        $('.loader').show();

        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'crewDuty']);?>">');
        form.append('<input type="hidden" name="pilotId" value="'+pilotid+'">');
        form.append('<input type="hidden" name="selMonth" value="'+selMonth+'">');
        form.append('<input type="hidden" name="selYear" value="'+selYear+'">');
        $('body').append(form);
        form.submit();

        setTimeout(function() {
            $('.loader').hide();
        }, 1000);
    });

	//Dynamically open popup with values(to display all data listing)
	$(document).on("click", ".dataReportCls", function() {
	    var pilotId  = $(this).data('pilot_id');
	    var selDay   = $(this).data('selday');
	    var selMonth = $(this).data('selmonth');
	    var selYear  = $(this).data('selyear');
	    
	    $.ajax({
	        type: "POST",
	        url: "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'pilotDutyDetail']); ?>",
	        data: {pilotId:pilotId, selDay:selDay, selMonth:selMonth, selYear:selYear},
	        async: true,
            beforeSend: function () {
                $('.loader').show();
            },
	        success: function(response) {
	            var obj = JSON.parse(response);
	            
	            if(obj.status == 'success') {
	                $('#pilotDutyDetailsModel .dateTitleCls').html(obj.dateT);
                    $('#pilotDutyDetailsModel .modal-body').html(obj.data);
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#pilotDutyDetailsModel').modal('show');
                    }, 500);
	            } else {
	                alert('No data exists.');
	            }
	        }
	    });
	});

    //Reload duty details
    $(document).on("click", ".reloadDutyDetail", function() {
        var pilotid = $('#pilot-id').val();
        var selMonth = $('#current-month').val();
        var selYear = $('#current-year').val();

        $('.loader').show();
        setTimeout(function() {
            $('.loader').hide();
            location.reload(true);
        }, 500);
    });

	//Add Duty Time popup
	$(document).on("click", ".addDutyTimeBtn", function() {
		var selDate = $(this).data('seldate');
		var pilotId = $(this).data('pilot_id');

        $('.loader').show();

		$('#pilotDutyTimesModel .selectDCls').val(selDate);
		$('#pilotDutyTimesModel .pilotIdCls').val(pilotId);
		$('#dutyTimeErrorMsg').html('');

        setTimeout(function() {
            $('.loader').hide();
            $('#pilotDutyTimesModel').modal('show');
        }, 500);
	});

	//Add Schedule Day Off popup
	$(document).on("click", ".addDayOffBtn", function() {
		var selDate = $(this).data('seldate');
		var nextdate = $(this).data('nextdate');
		var pilotId = $(this).data('pilot_id');

        $('.loader').show();

		$('#pilotDayOffModel .selectDCls').val(selDate);
		$('#pilotDayOffModel .pilotIdCls').val(pilotId);
		$('#pilotDayOffModel .daysOffStart').val(selDate);
		$('#pilotDayOffModel .daysOffEnd').val(nextdate);
		$('#daysOffErrorMsg').html('');

        setTimeout(function() {
            $('.loader').hide();
            $('#pilotDayOffModel').modal('show');
        }, 500);
	});

	//Add Flight Leg popup
	$(document).on("click", ".addFlightLegBtn", function() {
		var selDate = $(this).data('seldate');
		var pilotId = $(this).data('pilot_id');

        $('.loader').show();

		$('#pilotFlightLegModel .selectDCls').val(selDate);
		$('#pilotFlightLegModel .pilotIdCls').val(pilotId);
		$('#flightLegErrorMsg').html('');

        setTimeout(function() {
            $('.loader').hide();
            $('#pilotFlightLegModel').modal('show');
        }, 500);		
	});

	//Save Pilot Duty Time details
    $(document).on('click', '#dutyTimeBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var data = $('#dutyTimeFrm').serialize();
        //check if value overlap
        $.ajax({
            type: 'post',
            url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'checkDutyTimeOverlap']); ?>",
            data: data,
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    //If success add record
                    $.ajax({
                        type: 'post',
                        url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'addPilotDutyTime']); ?>",
                        data: data,
                        async: true,
                        success:function(response) {
                            var obj = JSON.parse(response);
                            if(obj.status == 'success') {
                                setTimeout(function() {
                                    $('.loader').hide();
                                    $('.dtHtmlClass').html(obj.dthtml);
                                    $('#dutyTimeErrorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                                }, 500);
                            } else {
                                setTimeout(function() {
                                    $('.loader').hide();
                                    $('#dutyTimeErrorMsg').html('<span style="color:red;">'+obj.message+'</span>');
                                }, 500);
                            }                    
                        },
                        error : function() {
                            alert('Some error occured. Please try again!');
                            setTimeout(function() {
                                $('#pilotDutyTimesModel').modal('hide');
                            }, 2000);
                        },
                        complete: function () {
                            setTimeout(function() {
                                $('#pilotDutyTimesModel').modal('hide');
                            }, 2000);
                        }
                    });
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        //Display validation message
                        alert(obj.message);
                    }, 500);
                }                    
            }
        });
    });

    //Save Pilot Schedule Day Off
    $(document).on('click', '#scheduleDayOffBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var disabled = $('#scheduleDayOffFrm').find(':input:disabled').removeAttr('disabled');
        var data = $('#scheduleDayOffFrm').serialize();
        disabled.attr('disabled','disabled');

        //check if value overlap
        $.ajax({
            type: 'post',
            url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'checkDaysOffOverlap']); ?>",
            data: data,
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    //If success add record
                    $.ajax({
                        type: 'post',
                        url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'addDaysOffData']); ?>",
                        data: data,
                        async: true,
                        success:function(response) {
                            var obj = JSON.parse(response);
                            if(obj.status == 'success') {
                                setTimeout(function() {
                                    $('.loader').hide();
                                    $('.doHtmlClass').html(obj.dohtml);
                                    $('#daysOffErrorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                                }, 500);
                            } else {
                                setTimeout(function() {
                                    $('.loader').hide();
                                    $('#daysOffErrorMsg').html('<span style="color:red;">'+obj.message+'</span>');
                                }, 500);
                            }                    
                        },
                        error : function() {
                            alert('Some error occured. Please try again!');
                            setTimeout(function() {
                                $('#pilotDayOffModel').modal('hide');
                            }, 2000);
                        },
                        complete: function () {
                            setTimeout(function() {
                                $('#pilotDayOffModel').modal('hide');
                            }, 2000);
                        }
                    });
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        //Display validation message
                        alert(obj.message);
                    }, 500);
                }                    
            }
        });
    });

    //Save Flight Leg Data
    $(document).on('click', '#flightLegBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var data = $('#flightLegFrm').serialize();
        
        //check if value overlap
        $.ajax({
            type: 'post',
            url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'checkFlightLegOverlap']); ?>",
            data: data,
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    //If success add record
                    $.ajax({
                        type: 'post',
                        url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'addFlightLegData']); ?>",
                        data: data,
                        async: true,
                        success:function(response) {
                            var obj = JSON.parse(response);
                            if(obj.status == 'success') {
                                setTimeout(function() {
                                    $('.loader').hide();
                                    $('.flHtmlClass').html(obj.flhtml);
                                    $('#flightLegErrorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                                }, 500);
                            } else {
                                setTimeout(function() {
                                    $('.loader').hide();
                                    $('#flightLegErrorMsg').html('<span style="color:red;">'+obj.message+'</span>');
                                }, 500);
                            }                    
                        },
                        error : function() {
                            alert('Some error occured. Please try again!');
                            setTimeout(function() {
                                $('#pilotFlightLegModel').modal('hide');
                            }, 2000);
                        },
                        complete: function () {
                            setTimeout(function() {
                                $('#pilotFlightLegModel').modal('hide');
                            }, 2000);
                        }
                    });
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        //Display validation message
                        alert(obj.message);
                    }, 500);
                }                    
            }
        });

        //If success add record
        /*$.ajax({
            type: 'post',
            url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'addFlightLegData']); ?>",
            data: data,
            async: true,
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('.flHtmlClass').html(obj.flhtml);
                        $('#flightLegErrorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                    }, 500);
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#flightLegErrorMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    }, 500);
                }                    
            },
            error : function() {
                alert('Some error occured. Please try again!');
                setTimeout(function() {
                    $('#pilotFlightLegModel').modal('hide');
                }, 2000);
            },
            complete: function () {
                setTimeout(function() {
                    $('#pilotFlightLegModel').modal('hide');
                }, 2000);
            }
        });*/
    });

    //Remove popup values when popup hide
    $(document).on('hidden.bs.modal','.modal', function () {
        $('#pilotDutyTimesModel form')[0].reset();
        $('#pilotDayOffModel form')[0].reset();
        $('#pilotFlightLegModel form')[0].reset();
    });

	//Open pilot duty time data update popup
	$(document).on("click", ".dtUpClass", function() {
	    var dtId = $(this).data('dtid');
	    var pilotId = $(this).data('pilot_id');
	    
	    $.ajax({
	        type: "POST",
	        url: "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'updateDutyTimePopup']); ?>",
	        data: {pilotId:pilotId, dtId:dtId},
	        async: true,
            beforeSend: function () {
                $('.loader').show();
            },
	        success: function(response) {
	            var obj = JSON.parse(response);
	            if(obj.status == 'success') {
					$('#updateDutyTimeMsg').html('');
					$('#updateDutyTimesModel .modal-body').html(obj.data);

                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateDutyTimesModel').modal('show');
                    }, 1000);
	            } else {
	                alert('No data exists.');
	            }
	        }
	    });
	});

	//Update Pilot Duty Time details
    $(document).on('click', '#updateDutyTimeBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var data = $('#updateDutyTimeFrm').serialize();
        $.ajax({
        	type: 'post',
            url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'addPilotDutyTime']); ?>",
            data: data,
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('.dtHtmlClass').html(obj.dthtml);
                        $('#updateDutyTimeMsg').html('<span style="color:green;">'+obj.message+'</span>');
                    }, 1000);
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateDutyTimeMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    }, 1000);
                }                    
            },
            error : function() {
                alert('Some error occured. Please try again!');
                setTimeout(function() {
                    $('#updateDutyTimesModel').modal('hide');
                }, 1000);
            },
            complete: function () {
                setTimeout(function() {
                    $('#updateDutyTimesModel').modal('hide');
                }, 1000);
            }
        });
    });

    //Delete pilot duty time record
	$(document).on("click", ".dtDelClass", function() {
	    var dtId = $(this).data('dtid');
	    var pilotId = $(this).data('pilot_id');
	    
	    if (confirm('Are you sure you want to delete this?')) {
		    $.ajax({
		        type: "POST",
		        url: "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'deleteDutyTimeRec']); ?>",
		        data: {pilotId:pilotId, dtId:dtId},
		        async: true,
                beforeSend: function () {
                    $('.loader').show();
                },
		        success: function(response) {
		            var obj = JSON.parse(response);
		            if(obj.status == 'success') {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('.dtMsg').html('<span style="color:green;">'+obj.message+'</span>').hide(1000);
                        }, 1000);
		            } else {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('.dtMsg').html('<span style="color:red;">'+obj.message+'</span>').hide(1000);
                        }, 1000);
		            }
		        }
		    });
		}	
	});

	//Open pilot days off data update popup
	$(document).on("click", ".doUpClass", function() {
	    var doId = $(this).data('doid');
	    var pilotId = $(this).data('pilot_id');
	    
	    $.ajax({
	        type: "POST",
	        url: "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'updateDaysOffPopup']); ?>",
	        data: {pilotId:pilotId, doId:doId},
	        async: true,
            beforeSend: function () {
                $('.loader').show();
            },
	        success: function(response) {
	            var obj = JSON.parse(response);
	            if(obj.status == 'success') {
					$('#updateDaysOffMsg').html('');
					$('#updateDaysOffModel .modal-body').html(obj.data);

                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateDaysOffModel').modal('show');
                    }, 1000);
	            } else {
	                alert('No data exists.');
	            }
	        }
	    });
	});

	//Update Pilot Days off time details
    $(document).on('click', '#updateDaysOffBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var disabled = $('#updateDaysOffFrm').find(':input:disabled').removeAttr('disabled');
        var data = $('#updateDaysOffFrm').serialize();
        disabled.attr('disabled','disabled');

        $.ajax({
        	type: 'post',
            url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'addDaysOffData']); ?>",
            data: data,
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('.doHtmlClass').html(obj.dohtml);
                        $('#updateDaysOffMsg').html('<span style="color:green;">'+obj.message+'</span>');
                    }, 1000);
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateDaysOffMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    }, 1000);
                }                    
            },
            error : function() {
                alert('Some error occured. Please try again!');
                setTimeout(function() {
                    $('#updateDaysOffModel').modal('hide');
                }, 1000);
            },
            complete: function () {
                setTimeout(function() {
                    $('#updateDaysOffModel').modal('hide');
                }, 1000);
            }
        });
    });

    //Delete pilot days off time record
	$(document).on("click", ".doDelClass", function() {
	    var doId = $(this).data('doid');
	    var pilotId = $(this).data('pilot_id');
	    
	    if (confirm('Are you sure you want to delete this?')) {
		    $.ajax({
		        type: "POST",
		        url: "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'deleteDaysOffRec']); ?>",
		        data: {pilotId:pilotId, doId:doId},
		        async: true,
                beforeSend: function () {
                    $('.loader').show();
                },
		        success: function(response) {
		            var obj = JSON.parse(response);
		            if(obj.status == 'success') {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('.doMsg').html('<span style="color:green;">'+obj.message+'</span>').hide(1000);
                        }, 1000);
		            } else {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('.doMsg').html('<span style="color:red;">'+obj.message+'</span>').hide(1000);
                        }, 1000);
		            }
		        }
		    });
		}	
	});

	//Initiate after html load
    $(document).on("show.bs.modal",".modal", function () {
        $('.datePicker').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: false
        });
    });

    /*********************Flight Leg******************************/
    //Open flight leg update popup
	$(document).on("click", ".flUpClass", function() {
	    var flId = $(this).data('flid');
	    var pilotId = $(this).data('pilot_id');
	    
	    $.ajax({
	        type: "POST",
	        url: "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'updateFlightLegPopup']); ?>",
	        data: {pilotId:pilotId, flId:flId},
	        async: true,
            beforeSend: function () {
                $('.loader').show();
            },
	        success: function(response) {
	            var obj = JSON.parse(response);
	            if(obj.status == 'success') {
					$('#updateFlightLegMsg').html('');
					$('#updateFlightLegModel .modal-body').html(obj.data);

                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateFlightLegModel').modal('show');
                    }, 1000);
	            } else {
	                alert('No data exists.');
	            }
	        }
	    });
	});

	//Update Flight Leg details
    $(document).on('click', '#updateFlightLegBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var data = $('#updateFlightLegFrm').serialize();
        $.ajax({
        	type: 'post',
            url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'addFlightLegData']); ?>",
            data: data,
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('.flHtmlClass').html(obj.flhtml);
                        $('#updateFlightLegMsg').html('<span style="color:green;">'+obj.message+'</span>');
                    }, 1000);
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateFlightLegMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    }, 1000);
                }                    
            },
            error : function() {
                alert('Some error occured. Please try again!');
                setTimeout(function() {
                    $('#updateFlightLegModel').modal('hide');
                }, 1000);
            },
            complete: function () {
                setTimeout(function() {
                    $('#updateFlightLegModel').modal('hide');
                }, 1000);
            }
        });
    });

    //Delete flight leg record
	$(document).on("click", ".flDelClass", function() {
	    var flId = $(this).data('flid');
	    var pilotId = $(this).data('pilot_id');
	    
	    if (confirm('Are you sure you want to delete this?')) {
		    $.ajax({
		        type: "POST",
		        url: "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'deleteFlightLegRec']); ?>",
		        data: {pilotId:pilotId, flId:flId},
		        async: true,
                beforeSend: function () {
                    $('.loader').show();
                },
		        success: function(response) {
		            var obj = JSON.parse(response);
		            if(obj.status == 'success') {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('.flMsg').html('<span style="color:green;">'+obj.message+'</span>').hide(2000);
                        }, 1000);
						
		            } else {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('.flMsg').html('<span style="color:red;">'+obj.message+'</span>').hide(2000);
                        }, 1000);
		                
		            }
		        }
		    });
		}	
	});
    /*********************End Flight Leg**************************/

});
</script>