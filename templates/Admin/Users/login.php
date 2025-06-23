<!-- Forgot password popup -->
<div class="modal modalPopup fade" id="passwordForgot">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-uppercase">Forgot Password<button type="button" class="dismiss" data-dismiss="modal">&times;</button></h4>
                
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <p>
                    Please enter your registered Email Id/Phone Number and we will send you details to reset your password.
                </p>
                <?php echo $this->Form->create(null, ['url' => ['action' => 'forgotPassword'], 'id' => 'frmForgotPassword']); ?>
                    <div class="form-group">
                        <?php echo $this->Form->control('email',['type' => 'text', 'class'=>'form-control','placeholder'=>'Enter Email/Phone', 'required'=>true, 'label'=>false]); ?>
                        <?php echo $this->Form->control('login_type', array('type' => 'hidden', 'value' => (!empty($prefix)?$prefix:''))); ?>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <?php
                            echo $this->Form->button('Submit', ['type' => 'submit', 'class' => 'btn btn-primary btn-block']);
                        ?>
                    </div>
                <?php echo $this->Form->end(); ?>
            </div>
            
        </div>
    </div>
</div>

<div class="login_wrapper">
    <div class="adminLogo">
        <?php
            echo $this->Html->link($this->Html->image('/images/logo.png'), array('controller' => 'Users', 'action'=>'login'), array('alt'=>'SOUTHWEST AVAITION SPECIALTIES, LLC', 'escape' => false));
        ?>
    </div>
    <?php echo $this->Flash->render(); ?>
    <?php echo $this->Flash->render('auth'); ?>
    <div class="clearfix"></div>
    <div class="animate form login_form">
        <section class="login_content">
            <?php echo $this->Form->create(null, array('id' => 'frmLogin')); ?>
                <h1><?php if($prefix == PILOTS_PREFIX){ echo $prefix; } ?> Login Form</h1>
                
                <div class="form-group">
                    <?php echo $this->Form->control('email',['class'=>'form-control','placeholder'=>'Email','label'=>false]); ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->control('password',['class'=>'form-control','placeholder'=>'Password','label'=>false,'required'=>true]); ?>
                </div>
                <div>
                    <?php echo $this->Form->button(__d('gentelella','Login'),['class'=>'btn btn-default submit']); ?>

                    <a href="javascript:void(0);" style="color:blue !important;" onclick='$("#passwordForgot").modal("show");'><b>Forgot Password?</b></a>
                </div>
                <div class="clearfix"></div>
            <?php echo $this->Form->end(); ?>
        </section>
    </div>
</div>


<script>
    $(document).ready(function() {
        $( "#frmLogin" ).validate( {
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: "Please enter email/hone",
                    email: "Please enter valid email/phone"
                }
            },
            errorClass: "error",
            errorElement: "label"
        });

        $("#frmForgotPassword").validate({
             rules: {
                'email': {
                    required: true,
                    remote: {
                        url: "<?php echo $this->Url->build(array('controller'=>'Users', 'action'=>'isEmailORPhoneNotExist')); ?>",
                        type: "post"
                    }
                }  
            },
            messages: {
                'email': {
                    required: "Please enter valid email/phone.",
                    remote: "Not a registered email/phone."
                }
            },
            errorClass: "error",
            errorElement: "label"
        });

        $('.close').on('click', function () {            
            $("#frmForgotPassword").trigger("reset");
            $('label[for=email]').remove();
        });
        $('#passwordForgot').modal({
            show:false,
            backdrop: 'static',
            keyboard: false
        });
        /*var aTag = '<a class="close" href="#" onclick="$(this).parent().fadeOut();return false;">×</a>';
        $(".close").remove();
        $( ".alert-danger" ).prepend( $( aTag ) );*/
    });     
</script>
<style type="text/css">
    button.dismiss {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: 0 none;
        cursor: pointer;
        padding: 0;
    }
    .dismiss {
        color: #000;
        float: right;
        font-size: 21px;
        font-weight: 700;
        line-height: 1;
        opacity: 0.2;
        text-shadow: 0 1px 0 #fff;
    }
    .dismiss:focus, .dismiss:hover {
        color: #000;
        cursor: pointer;
        opacity: 0.5;
        text-decoration: none;
    }
</style>