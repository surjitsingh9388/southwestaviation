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
                <?php echo $this->Form->create(null, array('id' => 'frmVerifyOTP')); ?>
                    <h1>Enter OTP Code</h1>
                                     
                    <div class="form-group">
                        <?php echo $this->Form->Text('otp_code',['class'=>'form-control','placeholder'=>'OTP Code', 'maxlength' => 6, 'label'=>false]); ?>                        
                    </div>
                    <div>
                        <?php echo $this->Form->button(__d('gentelella','Verify'),['class'=>'btn btn-default submit']); ?>
                    </div>
                    <div class="clearfix"></div>
                    <?php echo $this->Form->control('user_id', array('type' => 'hidden', 'value' => $tokenExist['user_id'], 'label'=> false)); ?>
                    <?php echo $this->Form->control('id', array('type' => 'hidden', 'value' => $tokenExist['id'], 'label'=> false)); ?>
                    <?php echo $this->Form->control('prefix', array('type' => 'hidden', 'value' => $prefix, 'label'=> false)); ?>
                <?php echo $this->Form->end(); ?>
            </section>
        </div>
    </div>
</div> 
<script>
    $(document).ready(function() {
        $("#frmVerifyOTP").validate({
             rules: {
                'otp_code': {
                    required: true,
                    minlength: 6,
                    maxlength: 6,
                    checkOtpCode:true
                }
            },
            messages: {
                'otp_code': {
                    required: "Please enter OTP Code.",
                    minlength: "OTP Code must be 6 characters long."
                },
            },
            errorClass: "error",
            errorElement: "label"
        });

        // Validation rule to check password
        jQuery.validator.addMethod("checkOtpCode", function (value, element) {
            var pattern = /^(?=.*\d).{6}$/;
            if (pattern.test(value)) {
                //$('#passHelp').hide();
                return true;
            } else {
                return false;
            }
        }, "OTP Code length should be 6 characters.");
    });     
</script>