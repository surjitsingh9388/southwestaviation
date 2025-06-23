$(document).ready(function() {
    $('.certDatePicker').datetimepicker({
        format: 'MM/DD/YYYY',
        useCurrent: false,
    });

    $('.datePicker').datetimepicker({
        format: 'MM/DD/YYYY',
        useCurrent: false,
    });

    $('.dateNextDue').datetimepicker({
        format: 'MM/YYYY',
        useCurrent: false,
        widgetPositioning: {
            horizontal: "auto",
            vertical: "auto"
        }
    });

    //Get cities list
    $('#addresses-0-state-id').on('change', function() {
        var stateId = $( this ).val();
        $('#addresses-0-city-id').children('option:not(:first)').remove();
        if (stateId != '') {
            $.ajax({
                type: "POST",
                url: getCitiesList,
                data: {stateId:stateId},
                async : true,
                success: function(response) {
                    if (response != '') {
                        $('#addresses-0-city-id').append(response);
                    }
                    $('#addresses-0-city-id').selectpicker('refresh');
                }                   
            });
        } else {
            $('#addresses-0-city-id').selectpicker('refresh');
        }
    });

    //Duty Assignment model popup
    $(".dutySpCls").on("click", function() {
        var title  = $(this).data('title');
        var datype = $(this).data('datype');

        $('.pilotSpTitle').html(title);
        $('.dutyAssignBtn').data('dutyatype', datype);
        $('#pilotSpecifyModel').modal('show');
    });

    //Assign selected values to form values
    $(".dutyAssignBtn").on("click", function() {
        var dutyatype  = $(this).data('dutyatype');
        
        //Assign values to hidden inputs
        $('.'+dutyatype+'typeCheck1').val($('input.typeCheck1').is(':checked'));
        $('.'+dutyatype+'designation1').val($('.designation1').val());
        $('.'+dutyatype+'date_assigned1').val($('.date_assigned1').val());
        $('.'+dutyatype+'date_unassigned1').val($('.date_unassigned1').val());
        $('.'+dutyatype+'typeCheck2').val($('input.typeCheck2').is(':checked'));
        $('.'+dutyatype+'designation2').val($('.designation2').val());
        $('.'+dutyatype+'date_assigned2').val($('.date_assigned2').val());
        $('.'+dutyatype+'date_unassigned2').val($('.date_unassigned2').val());

        //Display selected values
        if($('input.typeCheck1').is(':checked') && $('input.typeCheck2').is(':checked')) {
            $('.'+dutyatype+'Display').html($('.designation1').val() + ', ' + $('.designation2').val());
        } else if($('input.typeCheck1').is(':checked')) {
            $('.'+dutyatype+'Display').html($('.designation1').val());
        } else if($('input.typeCheck2').is(':checked')) {
            $('.'+dutyatype+'Display').html($('.designation2').val());
        }
        
        $('#pilotSpecifyModel').modal('hide');
    });

    //Remove popup values when popup hide
    $(document).on('hidden.bs.modal','.modal', function () {
        $('.typeCheck1').prop( "checked", false );
        $('.date_assigned1').val('');
        $('.date_unassigned1').val('');
        $('.typeCheck2').prop( "checked", false );
        $('.date_assigned2').val('');
        $('.date_unassigned2').val('');

        //$('#pilotDutyTimesModel form')[0].reset();
        //$('#pilotDayOffModel form')[0].reset();
        //$('#pilotFlightLegModel form')[0].reset();
    });

    //Dynamically open popup with values
    $(document).on("click", ".dutySpUpCls", function() {
        var daId    = $(this).data('da_id');
        var pilotId = $(this).data('pilot_id');
        var title   = $(this).data('title');
        var datype  = $(this).data('datype');
        
        $.ajax({
            type: "POST",
            url: specifyInfo,
            data: {daId: daId, pilotId: pilotId, title: title, datype: datype},
            async: true,
            success: function(response) {
                var obj = JSON.parse(response);
                //console.log(obj.data);
                if(obj.status == 'success') {
                    $('.pilotSpTitle').html(title);
                    $('.dutyAssignBtn').data('dutyatype', datype);
                    $('#pilotSpecifyModel .modal-body').html(obj.data);
                    $('#pilotSpecifyModel').modal('show');
                    $('#errorMsg').html('');
                } else {
                    alert('No data exists.');
                }
            }
        });
    });

    //Initiate after html load
    $(document).on("show.bs.modal",".modal", function () {
        $('.datePicker').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: false
        });
    });

    //Select pilot
    $(".changePilot").on("change", function() {
        var pilotid = $( this ).val();
        
        var form = $('<form method="GET" action="'+crewCurrency+'">');
        form.append('<input type="hidden" name="pilotId" value="'+pilotid+'">');
        $('body').append(form);
        form.submit(); 
    });

    //If nextdue updated manuelly
    $(".dateNextDue").datetimepicker({
        format: 'MM/DD/YYYY',
        useCurrent: false,
    }).on('dp.change', function(e) {
        //console.log($(this).nextAll('.ndStatus:first').val());
        $(this).nextAll('.ndStatus').val('yes');
        $(this).nextAll('.input-group-addon').css("pointer-events", "auto");    
    });

    //Remove manuel updated date
    $(".remT").click(function() {
        //console.log('here');
        $(this).parent().siblings().prevAll('.dateNextDue:first').val('');
        $(this).parent().siblings().nextAll('.ndStatus:first').val('no');
        $(this).parent('.input-group-addon').css("pointer-events", "none");
    });

    //Certificate nextdue update on change last completed date
    $(".certDatePicker").datetimepicker({
        useCurrent: false,
        format: 'MM/DD/YYYY'
    }).on('dp.change', function(e) {
        var a = moment(e.date._d);
        var lastCompleted = a.format('M/D/Y');
        var thisObj = $(this);
        var certType = thisObj.next("input.certTypeCls").val();
        var frequency = thisObj.closest('td').prevAll('td').children().find('.frequencyM').val();
        $.ajax({
            type: "POST",
            url: certNextDue,
            data: {certType:certType, frequency:frequency, lastCompleted:lastCompleted},
            async: true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    thisObj.closest('td').nextAll('td').children().find('.dateNextDue').val(obj.data);
                    thisObj.closest('td').nextAll('td').children().find('.dateNextDue').removeClass("ndred ndblue ndgreen ndyellow");
                    thisObj.closest('td').nextAll('td').children().find('.dateNextDue').addClass(obj.scolor);
                }
            }                   
        });
    });

    //Update nextdue on change last completed date
    $(".datePicker").datetimepicker({
        useCurrent: false,
        format: 'MM/DD/YYYY'
    }).on('dp.change', function(e) {
        var a = moment(e.date._d);
        var lastCompleted = a.format('M/D/Y');
        var thisObj = $(this);
        var baseMonth = thisObj.closest('td').prevAll('td').children().find('.baseM').val();
        var frequency = thisObj.closest('td').prevAll('td').children().find('.frequencyM').val();
        
        if(typeof getNextDue !== 'undefined') {
            $.ajax({
                type: "POST",
                url: getNextDue,
                data: {baseMonth:baseMonth, frequency:frequency, lastCompleted:lastCompleted},
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        thisObj.closest('td').nextAll('td').children().find('.dateNextDue').val(obj.data);
                        thisObj.closest('td').nextAll('td').children().find('.dateNextDue').removeClass("ndred ndblue ndgreen ndyellow");
                        thisObj.closest('td').nextAll('td').children().find('.dateNextDue').addClass(obj.scolor);
                    }
                }                   
            });
        }
    });


    /*
    //Enable if basemonth, frequency and last completed not blank: will do later
    $(".baseM").on("change", function() {
        console.log('here');
        console.log($(this).closest('td').nextAll('td').children().find('.frequency').val());
        if($(this).closest('td').nextAll('td').children().find('.frequency').val() !='' || $(this).closest('td').nextAll('td').children().find('.lastComp').val() !='') {
            $(this).closest('td').nextAll('td').children().find('.dateNextDue').prop("disabled", false);
        }
    });*/

}); 

//Get state list
function getStates(countryId) {
    $('#addresses-0-state-id').children('option:not(:first)').remove();
    $('#addresses-0-city-id').children('option:not(:first)').remove();
    if (countryId != '') {
        $.ajax({
            type: "POST",
            url: getStatesList,
            data: {countryId:countryId},
            async : true,
            success: function(response) {
                if (response != '') {
                    $('#addresses-0-state-id').append(response);
                }
                $('#addresses-0-state-id').selectpicker('refresh');
                $('#addresses-0-city-id').selectpicker('refresh');
            }
        });
    } else {
        $('#addresses-0-state-id').selectpicker('refresh');
        $('#addresses-0-city-id').selectpicker('refresh');
    }
}