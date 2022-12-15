$(document).ready(function () {
    // Used for sidebar menu to show links active inactive
    $('.side-menu li a').on('click', function () {
        $(this).parent().addClass('current-page').siblings().removeClass('current-page');
    });
    //$('#addresses-0-zip-code, #serial-no').numericOnly();
    
    // Validation rule to check password
    jQuery.validator.addMethod("checkPassword", function (value, element) {
        var pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,15}$/;
        if (pattern.test(value)) {
            return true;
        } else {
            return false;
        }
    }, "Invalid password.");
    
    // Validation rule to check phone number
    jQuery.validator.addMethod("checkPhone", function (value, element) {
        var pattern = /^\(?([0-9]{3})\)?[-]?([0-9]{3})[-]?([0-9]{4})$/;
        if (value != '') {
            if (pattern.test(value)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }, "Invalid Phone Number.");

    jQuery.validator.addMethod("checkHomePhone", function (value, element) {
        var pattern = /^[0-9(),+-]+$/;
        if (value != '') {
            if (pattern.test(value)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }, "Invalid Phone Number.");
    
    // Validation rule to check fax number (allow numeric values between 0-9 and - only)
    jQuery.validator.addMethod("checkFax", function (value, element) {
        var pattern = /^[0-9-]+$/;
        if (value != '') {
            if (pattern.test(value)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }, "Invalid Fax Number.");
    
    // Validation rule to check phone and fax extension (allow numeric values between 0-9 and + only)
    jQuery.validator.addMethod("checkExtension", function (value, element) {
        var pattern = /^[0-9+]+$/;
        if (value != '') {
            if (pattern.test(value)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }, "Invalid Extension.");

    // Validation rule to check email
    jQuery.validator.addMethod("checkEmail", function (value, element) {
        var pattern = /\s*[-+.'\w]+@[-.\w]+\.[-.\w]+\s*/;
        if (pattern.test(value)) {
            return true;
        } else {
            return false;
        }
    }, "Please enter valid email.");
        
    $('#role-id').on('change', function () {
        var role = $("#role-id option:selected").text();
        var companyId = $("#company-id").val();
        //$("#selCompany option[value='']").attr('selected', true);
        var companyUsers = ["Corporate Admin", "Admin", "Corporate User"];
        if ($.inArray(role, companyUsers) != -1 || role.match("^Admin")) {
            $("#company-id").val(companyId);
            $("#company-id").selectpicker('refresh');
            $('#selCompany').show();
            $("#company-id").rules("add", {
                required: true,
            });
        } else {
            $("#company-id").val('');
            $("#company-id").selectpicker('refresh');
            $('#selCompany').hide();
            $("#company-id").rules("remove", "required");
        }
    });
    
    // To show list of states for selected country on page load
    var countryId = $("#addresses-0-country-id").val();
    var stateId = $("#addresses-0-state-id").val();
    if (countryId != '' && stateId == '') {
        getStates(countryId);
    }

    // To show list of states on change of country dropdown
    $('#addresses-0-country-id').on('change', function () {
        var countryId = $(this).val();
        getStates(countryId);
    });

    // To set datpicker
    $('.datePicker').datetimepicker({
        format: 'MM-DD-YYYY',
        useCurrent: false,
        allowInputToggle: true
    });

    // To set date and time picker
    $('.dateTimePicker').datetimepicker({
        format: 'MM-DD-YYYY HH:mm',
        allowInputToggle: true
    });
    
    var specialKeys = [8, 9, 37, 39, 46];
    // To allow only numeric value without decimal
    $(".numericOnly").on("keypress keyup blur",function (event) {
        if ($.inArray(event.keyCode, specialKeys) == -1) {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                return false;
            }
        }
    });
    
}); 


