<section class="topBanner" style="background-image: url(<?php echo $this->Url->image('../site_theme/images/tuxedo-register-now.jpg'); ?>);">
	<hgroup>
		<h1>Reset Password</h1>
	</hgroup>
</section>
<section class="main-container">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<div class="form-wrapper">
					<?php echo $this->Form->create(null, array('action' => 'setNewPassword', 'class' => 'form-horizontal form-label-left', 'id' => 'frmResetPassword')); ?>
						<div class="form-group">
                            <small id="passHelp" class="form-text text-muted">
                                Password length should be 5-15 characters and contain at least one number, one lowercase and one uppercase letter.
                            </small>  
							<label class="text-uppercase">New Password*</label>
							<?php echo $this->Form->password('new_password', array('class' => 'form-control', 'required'=>'required', 'label' => false, 'id' => 'new_password')); ?>
						</div>
						<div class="form-group">
							<label class="text-uppercase">Confirm Password*</label>
							<?php echo $this->Form->password('new_confirm_password', array('class' => 'form-control', 'required'=>'required', 'label' => false, 'id' => 'new_confirm_password')); ?>
						</div>
						<div class="form-group text-center">
							<?php
                                echo $this->Form->button('Submit', ['type' => 'submit', 'class' => 'btn btn-primary rounded-0']);
                            ?>
						</div>
						<?php echo $this->Form->control('user_id', array('type' => 'hidden', 'value' => $tokenExist['user_id'], 'label'=> false)); ?>
						<?php echo $this->Form->control('ResetPasswords.id', array('type' => 'hidden', 'value' => $tokenExist['id'], 'label'=> false)); ?>
					<?= $this->Form->end() ?>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">    
    $(document).ready(function() {
        $("#frmResetPassword").validate({
            rules: {
                'new_password': {
                    required: true,
                    minlength: 5,
                    checkPassword:true
                },
                'new_confirm_password': {
                    required: true,
                    equalTo: '[name="new_password"]'
                }   
            },
            messages: {
            	'new_password': {
                    required: "Please enter new password.",
                    minlength: "Password must be at least 5 characters long."
                },
                'new_confirm_password': {
                	required: "Please enter confirm password.",
                    equalTo: "Please enter the same password as above."
                },
            },
            errorClass: "error",
            errorElement: "label"
        });

        // Validation rule to check password
        jQuery.validator.addMethod("checkPassword", function (value, element) {
            var pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,15}$/;
            if (pattern.test(value)) {
                //$('#passHelp').hide();
                return true;
            } else {
                return false;
            }
        }, "Password length should be 5-15 characters and contain at least one number, one lowercase and one uppercase letter.");
    });    
</script>