$(document).ready(function() {
    $('#aircraftId').on('change', function() {
        var airid = $(this).val();
        $('#addDiscrepancy').data('airid', airid);

        $.ajax({
            type: "POST",
            url: airDueRecord,
            data: {plane_id:airid},
            async : true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    //setTimeout(function() {
                        //$('.loader').hide();
                        $('#dispListing').html(obj.data);
                    //}, 1000);
                } else {
                    //setTimeout(function() {
                        //$('.loader').hide();
                        $('#dispListing').html(obj.data);
                    //}, 1000);
                }
            }                   
        });        
    });

    $(document).on("click", "#addDiscrepancy", function() {
        var planeId  = $(this).data('airid');
        console.log(planeId);

        if(planeId == '' || typeof planeId === 'undefined') {
            alert('Please select aircraft.');
        } else {
            $.ajax({
                type: "POST",
                url: addDiscrepancy,
                data: {plane_id: planeId},
                async : true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('.discPlaneId').val(planeId);
                        $('.dispId').val('');
                        $('#discrepancyModel .modal-body').html(obj.data);
                        $('#discrepancyModel').modal('show');
                        $("#discrepancyFrm :input").prop("disabled", false);
                    }
                }
            });
        }
    });

    //Save Discrepancy in DB
    //https://stackoverflow.com/questions/64716679/js-error-element-is-not-attached-to-a-document
    $(document).on('click', '#saveDiscrepBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();

        if($('#discrepancyFrm').valid()) {
            if($('input#discrepCorrectId').is(':checked')) {

                $('#discrepancyModel .signature_data').val("");
                //console.log('aaa');
                html2canvas($("#sign-pad")[0]).then((canvas) => {
                    //console.log('bbb');
                    var img_data = canvas.toDataURL('image/png');
                    var img_data = img_data.replace(/^data:image\/(png|jpg);base64,/, "");
                    $('#discrepancyModel .signature_data').val(img_data);

                    var form = document.getElementById('discrepancyFrm');
                    var formData = new FormData(form);
                    saveDataInDB(formData);
                });
            } else {
                var form = document.getElementById('discrepancyFrm');
                var formData = new FormData(form);
                saveDataInDB(formData);
            }            
        }
    });

    function saveDataInDB(formData) {
        $.ajax({
            type : 'POST',
            url : addDispData,
            data : formData,
            contentType: false,
            processData: false,
            success:function(response) {
                var obj = JSON.parse(response);

                if(obj.status == 'success') {
                    $('#discrepancyModel .discrepMsg').html('<span style="color:green;">'+obj.message+'</span>');
                } else {
                    $('#discrepancyModel .discrepMsg').html('<span style="color:red;">'+obj.message+'</span>');
                }

                setTimeout(function() {
                    $('#discrepancyModel').modal('hide');
                    $('#dispListing').html(obj.data);
                }, 1000);
            },
            error : function() {
                setTimeout(function() {
                    //reloadPage();
                    $('#discrepancyModel').modal('hide');
                }, 1000);
            },
            complete: function () {
                setTimeout(function() {
                    //reloadPage();
                    $('#discrepancyModel').modal('hide');
                }, 1000);
            }
        });
    }

    //Validation on group form
    $("#discrepancyFrm").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'discrepancy_date': {
                required: true
            },
            'discrepancy': {
                required: true
            },
            'discovered_by': {
                required: true
            },
            'discovered_cert': {
                required: true
            }
        },
        messages: {
            'discrepancy_date': {
                required: "Please select discrepancy date."
            },
            'discrepancy': {
                required: "Please enter discrepancy."
            },
            'discovered_by': {
                required: "Please enter discovered by name."
            },
            'discovered_cert': {
                required: "Please enter cert number."
            }
        }
    });

    //Update discrepancy
    $(document).on("click", ".updateDiscrepancy", function() {
        var planeId = $(this).data('plane_id');
        var dispId = $(this).data('id');

        $.ajax({
            type: "POST",
            url: upDispRecord,
            data: {plane_id: planeId, disp_id: dispId},
            async : true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('.discPlaneId').val(planeId);
                    $('.dispId').val(dispId);
                    $('#discrepancyModel .modal-body').html(obj.data);
                    $('#discrepancyModel').modal('show');
                    $('#discrepancyModel .discrepMsg').html('');

                    if(obj.correction == 1) {
                        $("#discrepancyFrm :input").prop("disabled", true);
                        $("#discrepancyFrm .closeCls").prop("disabled", false);
                    }

                    //Display selected value
                    $(".melCategory").selectpicker("val", obj.category);
                    $(".dispHourCls").selectpicker("val", obj.hour);
                    $(".dispMinutCls").selectpicker("val", obj.minut);
                } else {
                    alert('No data exists.');
                }
            }
        });
    });

    //Initiate select picker
    $(document).on('show.bs.modal','.modal', function () {
        //Creating select picker
        $(".melCategory").selectpicker();
        $(".dispHourCls").selectpicker();
        $(".dispMinutCls").selectpicker();
        $(".crewListCls").selectpicker();

        /*
        jQuery autocomplete
        $( ".crewListCls" ).autocomplete({
          source: availableTags
        });*/

        $('#discrepancy-date').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: false
        });

        $('#mel-repair-by').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: false
        });

        $('#corrected-date').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: false,
            widgetPositioning: {
                horizontal: 'right',
                vertical: 'top'
            }
        });

        //Hide/Show popup fields
        $("#discrepMelId").click(function () {
            if($('input#discrepMelId').is(':checked')) {
                $('.discrepancyMelUpCls').show();
            } else {
                $('.discrepancyMelUpCls').hide();
            }
        });

        //Hide/Show popup fields
        $("#discrepCorrectId").click(function () {
            if($('input#discrepCorrectId').is(':checked')) {
                $('.discrepancyUpdateCls').show();
            } else {
                $('.discrepancyUpdateCls').hide();
            }
        });

        $('#signArea').signaturePad({drawOnly:true, lineTop:90});
    });

});

