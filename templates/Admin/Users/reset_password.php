<div>
    <div class="login_wrapper">
        <?php echo $this->Flash->render(); ?>
        <?php echo $this->Flash->render('auth'); ?>
        <div class="clearfix"></div>
        <div class="animate form login_form">
            <section class="login_content">
                <div class="adminLogo">
                <?php
                    echo $this->Html->link($this->Html->image('/images/logo.png'), array('controller' => 'Users', 'action'=>'login'), array('alt'=>'SOUTHWEST AVAITION SPECIALTIES, LLC', 'escape' => false));
                ?>
                </div>
                <?php echo $this->Form->create(null, array('action' => 'setNewPassword', 'id' => 'frmSetPassword')); ?>
                    <h1>Set New Password</h1>
                    <small id="passHelp" class="form-text text-muted">
                        Password length should be 5-15 characters and contain at least one number, one lowercase and one uppercase letter.
                    </small>                   
                    <div class="form-group">
                        <?php echo $this->Form->password('password',['class'=>'form-control','placeholder'=>'Password', 'maxlength' => 15, 'aria-describedby' => 'passHelp', 'label'=>false]); ?>                        
                    </div>                    
                    <div class="form-group">
                        <?php echo $this->Form->password('confirm_password',['class'=>'form-control','placeholder'=>'Confirm Password','label'=>false,'required'=>true]); ?>
                    </div>
                    <div>
                        <?php echo $this->Form->button(__d('gentelella','Reset'),['class'=>'btn btn-default submit']); ?>
                    </div>
                    <div class="clearfix"></div>
                    <?php echo $this->Form->control('user_id', array('type' => 'hidden', 'value' => $tokenExist['user_id'], 'label'=> false)); ?>
                    <?php echo $this->Form->control('ResetPasswords.id', array('type' => 'hidden', 'value' => $tokenExist['id'], 'label'=> false)); ?>
                    <?php echo $this->Form->control('prefix', array('type' => 'hidden', 'value' => $prefix, 'label'=> false)); ?>
                <?php echo $this->Form->end(); ?>
            </section>
        </div>
    </div>
</div> 
<script>
    $(document).ready(function() {
        $("#frmSetPassword").validate({
             rules: {
                'password': {
                    required: true,
                    minlength: 5,
                    checkPassword:true
                },
                'confirm_password': {
                    required: true,
                    equalTo: '[name="password"]'
                }   
            },
            messages: {
                'password': {
                    required: "Please enter new password.",
                    minlength: "Password must be at least 5 characters long."
                },
                'confirm_password': {
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