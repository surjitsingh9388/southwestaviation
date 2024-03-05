<?php
/**
 * edit user details
 */
$sessionUser = $this->request->session()->read('Auth.User');
$companyUserRoles = array(ROLE_ADMIN);
?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Edit User Details</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>        

        <div class="page-content mt-35">
            <div class="tableScroll">                   
                <div class="panel panel-default">
                    <?php echo $this->Form->create($user, array('class' => 'form-horizontal form-label-left', 'id' => 'frmUser', 'role' => 'form', 'data-toggle' => 'validator')); ?>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id">User ID
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('userid', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'User ID', 'label' => false, 'readonly' => true, 'value' => $user->id)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title <!-- <span class="required">*</span> -->
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('title', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Title', 'label' => false)); ?>
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
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="suffix">Suffix </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('suffix', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Suffix', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="full_name">Full Name </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('full_name', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Full Name', 'readonly' => 'readonly', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('email', array('type' => 'text', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Email', 'requred' => 'required', 'label' => false)); ?>
                            </div>
                        </div>
                        <?php if (!isset($action)) {
                            if ((($user->id == 1) && ($user->id == $user->sessionUser)) || ($user->id != 1)) { ?>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo $this->Form->control('new_password', array('type' => 'password', 'class' => 'form-control col-md-7 col-xs-12', 
                                            'placeholder' => 'Password', 'maxlength' => 15, 'aria-describedby' => 'passHelp', 'label' => false)); ?>
                                        <small id="passHelp" class="form-text text-muted">
                                            Password length should be 5-15 characters and contain at least one number, one lowercase and one uppercase letter.
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="confirm_password">Confirm Password</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo $this->Form->control('confirm_password', array('type' => 'password', 'class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Re-enter Password', 'maxlength' => 15, 'label' => false)); ?>
                                    </div>
                                </div>
                        <?php } } ?>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone_ext">Phone Ext <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php
                                    if (isset($user->phone_ext)) {
                                        $phoneExt = !empty($user->phone_ext) ? $user->phone_ext : '+1';
                                    } else {
                                        $phoneExt = '+1';
                                    }
                                    echo $this->Form->control('phone_ext', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Phone Extension', 'requred' => 'required', 'label' => false, 'value' => $phoneExt)); 
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">Mobile Number <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('phone', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Mobile Number', 'requred' => 'required', 'label' => false)); ?>
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
                            <?php if($user['role_id'] != '4'){?>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('role_id', array('options' => $roles, 'empty' => 'Select Role', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false)); ?>
                            </div>
                            <?php }else{?>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('role_id', array('options' => $roles, 'empty' => 'Select Role', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                                <?php //echo $this->Form->control('role_id', array('type' => 'hidden', 'value' => $user['role_id'])); ?>
                            </div>
                            <?php }?>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Is Manager </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php 
                                $ismanagerchk = '';
                                $isdisabled = 'disabled';
                                if(!empty(@$user->is_manager)){
                                    $ismanagerchk = 'checked';
                                    $isdisabled = '';
                                }
                                echo $this->Form->control('is_manager', array('type'=>'checkbox', 'label' => '', 'id'=>'is_manager_chk', 'checked'=>$ismanagerchk)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Direct Manager </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('direct_manager_id', array('options' => $users, 'empty' => 'Select Direct Manager', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'disabled'=>$isdisabled)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="reference">Employment Date</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group date datePicker">
                                    <?php echo $this->Form->Text('employment_date', array('class' => 'form-control col-md-7 col-xs-12', 'id' => 'employment_date', 'placeholder' => '', 'label' => false)); ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="home_phone">Salary</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('salary', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Salary', 'label' => false)); ?>
                            </div>
                        </div>
                        
                        <?php if(!isset($action)){ ?>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Suspend Account </label>
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <?php 
                                echo $this->Form->control('suspended', array('label' => '')); 
                                echo $this->Form->control('previous_suspended_status', array('type' => 'hidden', 'value' => $user->suspended));
                                ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Send Welcome Email </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->checkbox('welcome_email', ['label' => '', 'id' => 'chkWelcomeEmail', 'hiddenField' => false]); ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <div class="panel-heading">
                        <h3 class="panel-title">Address</h3>
                    </div>
                    <div class="panel-body">
                        <?php echo $this->Form->control('addresses.0.id', array('type' => 'hidden')); ?>
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
                                <?php 
                                    if (isset($user->addresses[0]->country_id)) {
                                        echo $this->Form->control('addresses.0.country_id', array('options' => $countries, 'empty' => 'Select Country', 'required' => 'required', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    } else {
                                        echo $this->Form->control('addresses.0.country_id', array('options' => $countries, 'empty' => 'Select Country', 'required' => 'required', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'value' => '231', 'label' => false));
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="state">State <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('addresses.0.state_id', array('options' => $states, 'empty' => 'Select State', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="city">City <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('addresses.0.city_id', array('options' => $cities, 'empty' => 'Select City', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
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

                    <!--Start Add Access Code Section-->
                    <div class="panel-heading">
                        <h3 class="panel-title">Access Code</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address_line1">Time Clock Code</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('time_clock_code', array('type'=>'password', 'class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'placeholder'=>'Enter 4 digit time clock code')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address_line1">Inspection/Certification Code</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('certification_code', array('type'=>'password', 'class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'placeholder'=>'Enter 4 digit certification code')); ?>
                            </div>
                        </div>
                    </div>
                    <!--End Add Access Code Section-->
                    
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <?php   
                                if(isset($action)){
                                    if((!empty($actionCompanionItems) && $actionCompanionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1){ 
                                        echo $this->Form->button('Submit', ['type' => 'submit', 'id' => 'frmClicked', 'class' => 'btn btn-success clickedd']);
                                    }
                                }else{
                                    if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1){ 
                                        echo $this->Form->button('Submit', ['type' => 'submit', 'id' => 'frmClicked', 'class' => 'btn btn-success clickedd']);
                                    }
                                }
                                ?>
                                    <button class="btn btn-success buttonload" style="display: none;">
                                        <i class="fa fa-spinner fa-spin"></i>  <?php echo ucfirst(strtolower(SUBMITING)); ?>
                                    </button>
                                <?php
                                    echo $this->Form->button('Reset', ['type' => 'reset', 'class' => 'btn btn-primary', 'id' => 'reset']);
                                    echo $this->Form->control('updated_by', array('type' => 'hidden', 'value' => $user->sessionUser));
                                    echo $this->Form->control('addresses.0.updated_by', array('type' => 'hidden', 'value' => $user->sessionUser));
                                ?>
                            </div>
                        </div>
                    </div>                    
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="showPopup" style="display: none;"></div>

<script>
$(document).ready(function() {
    // To remove error message on change of select picker
    $('#frmUser select.selectpicker').on('change', function(e) {
        $('#frmUser').validate().element($(this));
    });

    $( "#frmUser" ).validate( {
        ignore: [],
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
                checkEmail: true
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
            'home_phone': {
                checkHomePhone: true,
                minlength: 10,
                maxlength: 20
            },                
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
            'email': {
                email: "Please enter valid email."
            },
            'phone_ext': {
                maxlength: "Phone extension could not be more than 5 digits."
            },
            'phone': {
                minlength: "Phone number must consist of atleast 10 digits.",
                maxlength: "Invalid phone number."
            },
            'home_phone': {
                minlength: "Phone number must consist of atleast 10 digits.",
                maxlength: "Invalid phone number."
            },
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
                url: "<?php echo $this->Url->build(['controller'=>'addresses', 'action'=>'getCitiesList']); ?>",
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

    $('#frmClicked').on('click', function(e) {
        if ($("#frmUser").valid()) {
            var suspended = $('#suspended').is(":checked");
            var roleName = $("#role-id :selected").text();
            
            $('body').addClass('backgroundFixed');
            $('#overlay').show();
            
        } else {
            var validator = $("#frmUser").validate();
            validator.focusInvalid();
            return false;
        }
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

$(document).on('click', '#is_manager_chk', function(e){
    if($(this).is(':checked') == true){
        $('#direct-manager-id').prop('disabled', false);
    }else{
        $('#direct-manager-id').val('');
        $('#direct-manager-id').prop('disabled', true);
    }
    $('#direct-manager-id').selectpicker('refresh');
});
</script>