<?php
$session = $this->request->session();
if(!empty($session->read('user'))){
    $sessionUser = $session->read('user');
}else{
    $sessionUser = $session->read('Auth.User');
}
?>
</style>
<section class="contactBlock">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="address">
                    <p>107 West Piper Drive, Tulsa, OK 74132</p>
                    <p>Phone: <a href="tel:918-298-3718">918-298-3718</a></p>
                    <p>
                        Email: <a href="mailto:info@tuxedoair.com">info@tuxedoair.com</a>
                    </p>
                </div>
                <div class="form-wrapper">
                    <?php echo $this->Form->create(null, array('url' => array('action' => 'sendEmailRequest', 'controller' => 'Pages', 'prefix' => false), 'class' => 'form-horizontal form-label-left', 'id' => 'frmContactUs')); ?>
                    <div class="form-group">
                        <label class="text-uppercase text-center d-block">Your Name (required)</label>
                        <?php echo $this->Form->control('name', array('class' => 'form-control', 'required' => 'required', 'label' => false, 'id' => 'name', 'value' => @$sessionUser['full_name'])); ?>
                    </div>
                    <div class="form-group">
                        <label class="text-uppercase text-center d-block">Your Email (required)</label>
                        <?php echo $this->Form->control('email', array('class' => 'form-control', 'required' => 'required', 'label' => false, 'id' => 'email', 'value' => @$sessionUser['email'])); ?>
                    </div>
                    <div class="form-group">
                        <label class="text-uppercase text-center d-block">Subject</label>
                        <?php echo $this->Form->control('subject', array('class' => 'form-control', 'label' => false, 'id' => 'subject')); ?>
                    </div>
                    <div class="form-group">
                        <label class="text-uppercase text-center d-block">Your Message</label>
                        <?php echo $this->Form->textarea('message', array('class' => 'form-control', 'label' => false, 'id' => 'message')); ?>
                    </div>
                    <div class="form-group">
                        <div id="recaptcha" class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_SITE_KEY; ?>"></div>
                        <span class="msg-error"></span>
                    </div>
                    <div class="form-group text-center">
                        <?php
                        echo $this->Form->button('SEND', ['type' => 'submit', 'class' => 'btn btn-primary clicked']);
                        ?>
                        <button class="btn btn-primary buttonload" style="display: none;">
                            <i class="fa fa-spinner fa-spin"></i>  <?php echo SUBMITING; ?>
                        </button>
                    </div>
                    <?php echo $this->Form->end() ?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="embed-responsive embed-responsive-21by9">
                    <!--iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d3226.2755177741797!2d-95.99199748480095!3d36.037981240337906!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sJones+Airport+8720+Jack+Bates+Ave.+Tulsa%2C+OK+74132!5e0!3m2!1sen!2sua!4v1484779277021"></iframe-->
                    <a href="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12904.85369218127!2d-95.9944883!3d36.0394969!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x1e896dc1815f0c70!2sTuxedo+Air!5e0!3m2!1sen!2sin!4v1530871858819" target="_blank"><iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12904.85369218127!2d-95.9944883!3d36.0394969!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x1e896dc1815f0c70!2sTuxedo+Air!5e0!3m2!1sen!2sin!4v1530871858819" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe></a>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">    
    $(document).ready(function() {
        $("#frmContactUs").validate({
            rules: {
                'name': {
                    required: true,
                    minlength: 2,
                    maxlength: 50
                },    
                'email': {
                    required: true
                },    
                'subject': {
                    minlength: 10,
                    maxlength: 100
                },    
                'message': {
                    required: true,
                    minlength: 10,
                    maxlength: 500
                }
            },
            messages: {
                'name': {
                    required: "Please enter your full name.",
                    minlength: "Name must consist of at least 2 characters.",
                    maxlength: "Name could not be more than 50 characters."
                },
                'email': {
                    required: "Please enter valid email address."
                },    
                'subject': {
                    minlength: "Subject must consist of at least 10 characters.",
                    maxlength: "Subject could not be more than 100 characters."
                },    
                'message': {
                    minlength: "Message must consist of at least 10 characters.",
                    maxlength: "Message could not be more than 500 characters."
                }
            },
            errorClass: "error",
            errorElement: "label"
        });
        
        // to validate recaptcha on form submit
        $('#frmContactUs').submit(function(event){
            if ($("#frmContactUs").valid()) {
                var $captcha = $( '#recaptcha' ), 
                response = grecaptcha.getResponse();
                if (response.length === 0) {
                    event.preventDefault();
                    $( '.msg-error').text( "reCAPTCHA is mandatory" );
                    if( !$captcha.hasClass( "error" ) ){
                        $captcha.addClass( "error" );
                    }
                    $('body').removeClass('backgroundFixed');
                    $('#overlay').hide();
                } else {
                    $( '.msg-error' ).text('');
                    $captcha.removeClass( "error" );
                    $('body').addClass('backgroundFixed');
                    $('.clicked').hide();
                    $('.buttonload').show();
                    $('.buttonload').attr("disabled", true);
                    $("#overlay, #PleaseWait").show();
                }
            }
        });
    });    
</script>