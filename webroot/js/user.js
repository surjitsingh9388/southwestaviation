$(document).ready(function() {
    $('#reset').click(function() {
       var validator = $("#frmUser").validate();
       validator.resetForm();
    });
            
    $("#chkWelcomeEmail").click(function () {
        if ($(this).is(":checked")) {
            $("#new-password").rules("add", {
                required: true,
                checkPassword: true,
                messages: {
                    required: "Please provide new Password for re-sending new credentials."
                }
            });
            $("#confirm-password").rules("add", {
                required: true,
                equalTo: '[name="new_password"]',
                messages: {
                    required: "Please re-enter password.",
                    equalTo: "Confirm password should be same as new password."
                }
            });   
        } else {
            $("#new-password").rules("remove", "required");
            $("#new-password").rules("remove", "checkPassword");
            $("#confirm-password").rules("remove", "required");
            $("#confirm-password").rules("remove", "equalTo");
        }
    });    

    $("#new-password").on("keyup", function() {
        if ($("#new-password").val() != '') {
            $( "#new-password" ).rules( "add", {
                checkPassword: true,
            });
        } else {
            $( "#new-password" ).rules("remove", "checkPassword");
        }
    });
        
    // To show list of cities on change of country dropdown
    $('#addresses-0-state-id').on('change', function() {
        var stateId = $( this ).val();
        $('#addresses-0-city-id').children('option:not(:first)').remove();
        if (stateId != '') {
            $.ajax({
                type: "POST",
                url: getCitiesListURL,
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
    
    //Set full name by concatenate first, middle and last name fields value
    $('#title, #first-name, #middle-name, #last-name, #suffix').on('keyup input change', function() {
        var fullName = '';
        var title = $("#title").val();
        if (title != '') {
            fullName += title; 
        }
        var firstName = $("#first-name").val();
        if (firstName != '') {
            if(title!=''){
                fullName += ' '+firstName; 
            }else{
                fullName += firstName; 
            }
        }
        var middleName = $("#middle-name").val();
        if (middleName != '') {
            fullName += ' '+middleName; 
        }
        var lastName = $("#last-name").val();
        if (lastName != '') {
            fullName += ' '+lastName;
        }
        var suffix = $("#suffix").val();
        if (suffix != '') {
            fullName += ' '+suffix;
        }
        $("#full-name").val(fullName);
    });

    // Validation rule to check email
    jQuery.validator.addMethod("checkEmail", function (value, element) {
        var pattern = /\s*[-+.'\w]+@[-.\w]+\.[-.\w]+\s*/;
        if (pattern.test(value)) {
            return true;
        } else {
            return false;
        }
    }, "Please enter valid email.");
});

function getStates(countryId) {
    $('#addresses-0-state-id').children('option:not(:first)').remove();
    $('#addresses-0-city-id').children('option:not(:first)').remove();
    if (countryId != '') {
        $.ajax({
            type: "POST",
            url: getStatesListURL,
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

$(document).on('click', '#is_manager_chk', function(e){
    if($(this).is(':checked') == true){
        $('#direct-manager-id').prop('disabled', false);
        $('#team_member_id').prop('disabled', false);
    }else{
        $('#direct-manager-id').val('');
        $('#team_member_id').val('');
        $('#direct-manager-id').prop('disabled', true);
        $('#team_member_id').prop('disabled', true);
    }
    $('#direct-manager-id').selectpicker('refresh');
    $('#team_member_id').selectpicker('refresh');
});

$(document).on('click', '#user_add_pto_popup_btn', function(e){
    $('#add_pto_hours').val('');
    $('#substract_pto_hours').val('');
    var user_id = window.location.pathname.split('/').pop();
    $('#pto_user_id').val(user_id);

    $('#addSubstractPTOPopupModel').modal('show');
});

$(document).on('click', '.pto_hours_savebtn', function(e){
    var add_pto_hours = $.trim($('#add_pto_hours').val());
    var substract_pto_hours = $.trim($('#substract_pto_hours').val());
    if((add_pto_hours != '' && add_pto_hours != undefined) || (substract_pto_hours != '' && substract_pto_hours != undefined)){
        $.ajax({
            type: "POST",
            url: savePTOReqeustAutoApproveURl,
            data: $('#frmPTORequestAddSub').serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#add_pto_hours').val('');
                    $('#substract_pto_hours').val('');

                    $('#addSubstractPTOPopupModel').modal('hide');
                }
                alert(obj.message);                   
            }
        });
    }else{
        alert("Please fill PTO Hours");
    }
});