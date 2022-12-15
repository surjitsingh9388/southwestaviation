$(document).ready(function() {
    $('#frmAirCompPart select.selectpicker').on('change', function(e) {
        $('#frmAirCompPart').validate().element($(this));
    });

    $("#frmAirCompPart").validate({
        ignore: [],
        validateHiddenInputs: true,
        rules: {
            'plane_id': {
                required: true
            },    
            'airframe_component_id': {
                required: true
            }  
        },
        messages: {
            'plane_id': {
                required: "Please select aircraft."
            },
            'airframe_component_id': {
                required: "Please select aircraft component."
            }
        },
        errorClass: "error",
        errorElement: "label",
        errorPlacement: function(error, element) {
            if (element.hasClass('selectpicker')) {
                error.insertAfter(element.next('.btn-group'));
            } else {
                error.insertAfter(element);
            }
        },
        invalidHandler: function(event, validator) {
            // formInvalidHandler(validator.errorList);
            $.each(validator.errorList, function (index, item) {
                var jelm = $(item.element);
                if (jelm.hasClass('selectpicker') && (jelm.parents('div.select').find('label.error').length != 0 || jelm.parents('div.select').find('label.error').length != 1)) {
                    jelm.siblings('.bootstrap-select').find('.selectpicker').focus();
                    return false;
                }
            });
        }
    });
    
    $('#reset').click(function() {
        var validator = $("#frmAirCompPart").validate();
        validator.resetForm();
    });

    //Select Plane
    $('#planeName').on('change', function() {
        var planeId = $( this ).val();
        
        $.ajax({
            type: "POST",
            url: getComponents,
            data: {planeId:planeId},
            async : true,
            success: function(response) {
                $("#airCompsList").html(response);
                $("#airframe_component_id").selectpicker();
                $("#airframe_component_id.selectpicker").rules('add', {
                    required: true,
                    messages: {
                        required: "Please select component."
                    }
                });
                // show validation message on change
                $('#frmAirCompPart select.selectpicker').on('change', function(e) {
                    $('#frmAirCompPart').validate().element($(this));
                });
            }                   
        }); 
    });

    //Close success message
    $( window ).load(function() {
        setTimeout(function() {
            $('.alert-success').hide();
        }, 2000);
    });
}); 