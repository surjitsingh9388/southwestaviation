<?php
$sessionUser = $this->request->session()->read('Auth.User');
$companyUserRoles = array(ROLE_ADMIN);
?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Add New User</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>

        <div class="page-content mt-35">
            <div class="tableScroll">
                <?php echo $this->Form->create($user, array('class' => 'form-horizontal form-label-left', 'id' => 'frmUser', 'role' => 'form', 'data-toggle' => 'validator')); ?>
                <div class="panel panel-default">
                    <!--Start User Personal detail Section-->
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <!-- <span class="required">*</span> -->
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('title', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Title(Mr/Mrs)', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first_name">First Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('first_name', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'placeholder' => 'First Name', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="middle_name">Middle Name
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('middle_name', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Middle Name', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_name">Last Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('last_name', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'placeholder' => 'Last Name', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="suffix">Suffix <!-- <span class="required">*</span> -->
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('suffix', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Suffix', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="full_name">Full Name
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('full_name', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Full Name', 'label' => false, 'readonly' => 'readonly')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('email', array('type' => 'text', 'class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'placeholder' => 'Email', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->password('password', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 
                                    'placeholder' => 'Password', 'maxlength' => 15, 'aria-describedby' => 'passHelp', 'label' => false)); ?>
                                <small id="passHelp" class="form-text text-muted">
                                    Password length should be 5-15 characters and contain at least one number, one lowercase and one uppercase letter.
                                </small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="confirm-password">Confirm Password</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php
                                    echo $this->Form->password('confirm_password', array('class' => 'form-control col-md-7 col-xs-12',
                                    'required' => 'required', 'placeholder' => 'Re-enter Password', 'maxlength' => 15, 'label' => false)); 
                                ?>  
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone_ext">Phone Ext <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('phone_ext', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'placeholder' => 'Phone Extension', 'label' => false, 'value' => '+1')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Mobile Number <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('phone', array('class' => 'form-control col-md-7 col-xs-12 ', 'required' => 'required', 'placeholder' => 'Mobile Number', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="home_phone">Office/Home Phone Number 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('home_phone', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Office/Home Phone Number', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Role <span class="required">*</span>
                            </label>
                            <?php if($user['role_id'] != '4') { ?>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('role_id', array('options' => $roles, 'empty' => 'Select Role', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false)); ?>
                            </div>
                            <?php } else { ?>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('role_id', array('options' => $roles, 'empty' => 'Select Role', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'disabled' => 'disabled', 'label' => false)); ?>
                                <?php echo $this->Form->control('role_id', array('type' => 'hidden', 'value' => $user['role_id'])); ?>
                            </div>
                            <?php } ?>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Suspend Account </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('suspended', array('label' => '')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Send Welcome Email </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->checkbox('welcome_email', ['label' => '', 'hiddenField' => false]); ?>
                            </div>
                        </div>
                    </div>
                    <!--End User Personal detail Section-->
                                          
                    <!--Start Add Address Section-->
                    <div class="panel-heading">
                        <h3 class="panel-title">Add Address</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address_line1">Address Line1 <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('addresses.0.address_line1', array('class' => 'form-control col-md-7 col-xs-12', 'required' => 'required', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address_line2">Address Line2
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('addresses.0.address_line2', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false)); ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="country">Country <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('addresses.0.country_id', array('options' => $countries, 'empty' => 'Select Country', 'required' => 'required', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'value' => '231')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="state">State <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('addresses.0.state_id', array('options' => array(), 'empty' => 'Select State', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="city">City <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('addresses.0.city_id', array('options' => array(), 'empty' => 'Select City', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="zip_code">Zip Code <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('addresses.0.zip_code', array('class' => 'form-control col-md-7 col-xs-12 numericOnly', 'required' => 'required', 'placeholder' => 'Zip Code', 'label' => false)); ?>
                            </div>
                        </div>
                    </div>
                    <!--End Add Address Section-->
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <?php
                                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1){
                                    echo $this->Form->button('Submit', ['type' => 'submit', 'id' => 'frmClicked', 'class' => 'btn btn-success clickedd']);
                                }
                                ?>
                                    <button class="btn btn-success buttonload" style="display: none;">
                                        <i class="fa fa-spinner fa-spin"></i>  <?php echo ucfirst(strtolower(SUBMITING)); ?>
                                    </button>
                                <?php
                                    echo $this->Form->button('Reset', ['type' => 'reset', 'class' => 'btn btn-primary', 'id' => 'reset']);
                                    echo $this->Form->control('updated_by', array('type' => 'hidden', 'value' => $sessionUser['id']));
                                    echo $this->Form->control('addresses.0.updated_by', array('type' => 'hidden', 'value' => $sessionUser['id']));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // To remove error message on change of select picker
    $('#frmUser select.selectpicker').on('change', function(e) {
        $('#frmUser').validate().element($(this));
    });
    
    $( "#frmUser" ).validate( {
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: true,
        rules: {
            'first_name': {
                required: true
            },
            'last_name': {
                required: true
            },
            'email': {
                required: true,
                checkEmail: true,
                remote: {
                    url: "<?php echo $this->Url->build(array('controller' => 'Users', 'action' => 'isEmailExist')); ?>",
                    type: "post"
                }
            },
            'password': {
                required: true,
                checkPassword:true
            },
            'confirm_password': {
                required: true,
                equalTo: '[name="password"]'
            },
            'phone_ext': {
                required: true,
                checkExtension: true,
                maxlength: 6
            },    
            'phone': {
                required: true,
                checkPhone: true,
                minlength: 10,
                maxlength: 12
            },
            'role': {
                required: true
            },
            /*'home_phone': {
                checkHomePhone: true,
                minlength: 10,
                maxlength: 20
            },*/
            
            'addresses[0][address_line1]': {
                required: true
            },
            'addresses[0][zip_code]': {
                required: true,
                maxlength: 6
            },
            'addresses[0][country_id]': {
                required: true
            },
            'addresses[0][state_id]': {
                required: true
            },
            'addresses[0][city_id]': {
                required: true
            }    
        },
        messages: {
            'first_name': {
                required: "Please enter first name."
            },
            'last_name': {
                required: "Please enter last name."
            },
            /*'full_name': {
                required: "Please enter full name.",
                minlength: "Name must consist of at least 5 characters."
            },*/
            'email': {
                remote: "Email already registered."
            },
            'confirm_password': {
                equalTo: "Confirm password should be same as password."
            },
            'phone_ext': {
                maxlength: "Phone extension could not be more than 5 digits."
            },
            'phone': {
                minlength: "Phone number must consist of atleast 10 digits.",
                maxlength: "Invalid phone number."
            },
            /*'home_phone': {
                minlength: "Phone number must consist of atleast 10 digits.",
                maxlength: "Invalid phone number."
            },*/
            'addresses[0][zip_code]': {
                maxlength: "Zip code could not be more than 6 digits."
            },
            
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
            
    $('#reset').click(function () {
        var validator = $("#frmUser").validate();
        validator.resetForm();
    });

    $('#addresses-0-state-id').on('change', function() {
        var stateId = $( this ).val();
        $('#addresses-0-city-id').children('option:not(:first)').remove();
        if (stateId != '') {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getCitiesList']); ?>",
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
    
    $('#frmUser').submit(function(event) { 
        if ($("#frmUser").valid()) {
            var suspended = $('#suspended').is(":checked");
            var roleName = $("#role-id :selected").text();
            
            $('body').addClass('backgroundFixed');
            $('#overlay').show();
                
        } else {
            event.preventDefault(); 
            var validator = $("#frmUser").validate();
            validator.focusInvalid();
            return false;
        }
    });

    $('.close').click(function(){
        $('#frmUser').reset();
    });
});

function getStates(countryId) {
    $('#addresses-0-state-id').children('option:not(:first)').remove();
    $('#addresses-0-city-id').children('option:not(:first)').remove();
    if (countryId != '') {
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getStatesList']); ?>",
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
</script>
