<section class="topBanner" style="background-image: url(<?php echo $this->Url->image('../site_theme/images/tuxedo-register-now.jpg'); ?>);">
	<hgroup>
		<h1>Register Now</h1>
		<h3>Save time when you travel. Contact us today to begin the membership process. We look forward to serving you!</h3>
	</hgroup>
</section>
<section class="main-container">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<p>
				Simply give us a little information and we’ll contact you to complete your registration through our VIP enrollment process. We look forward to serving you!
				</p>
				<div class="form-wrapper">
					<?php echo $this->Form->create(null, array('action' => 'sendEmailRequest', 'class' => 'form-horizontal form-label-left', 'id' => 'frmRegisterNow')); ?>
						<div class="form-group">
							<label class="text-uppercase">Full Name*</label>
							<!--input type="text" name="" class="form-control"-->
							<?php echo $this->Form->control('name', array('class' => 'form-control', 'required'=>'required', 'label' => false, 'id' => 'name')); ?>
						</div>
						<div class="form-group">
							<label class="text-uppercase">Email*</label>
							<!--input type="text" name="" class="form-control"-->
							<?php echo $this->Form->control('email', array('class' => 'form-control', 'required'=>'required', 'label' => false, 'id' => 'email')); ?>
						</div>
						<div class="form-group">
							<label class="text-uppercase">Phone*</label>
							<!--input type="text" name="" class="form-control"-->
							<?php echo $this->Form->control('phone', array('class' => 'form-control', 'required'=>'required', 'label' => false, 'id' => 'phone')); ?>
						</div>
						<div class="form-group">
							<label class="text-uppercase">Home Airport*</label>
							<div class="customSelect">
								<i class="fa fa-caret-down downArrow"></i>
								<?php
                                    echo $this->Form->control('home_airport', array('class' => 'form-control', 'label'=> false, 'required' => 'required', 'options' => $airportList, 'id' => 'home_airport', 'empty' => '---'));
                                ?>
							</div>
						</div>
						<div class="form-group">
							<label class="text-uppercase">Destination Airport*</label>
							<div class="customSelect">
								<i class="fa fa-caret-down downArrow"></i>
								<?php
                                    echo $this->Form->control('destination_airport', array('class' => 'form-control', 'label'=> false, 'required' => 'required', 'options' => $airportList, 'id' => 'destination_airport', 'empty' => '---'));
                                ?>
							</div>
						</div>
						<div class="form-group text-center">
							<?php
                                echo $this->Form->button('Submit', ['type' => 'submit', 'class' => 'btn btn-primary rounded-0']);
                            ?>
						</div>
					<?= $this->Form->end() ?>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">    
    $(document).ready(function() {
        $("#frmRegisterNow").validate({
            rules: {
                'name': {
                    required: true
                },    
                'email': {
                    required: true
                },    
                'phone': {
                    required: true,
                    digits: true
                },
                'home_airport': {
                    required: true
                },    
                'destination_airport': {
                    required: true
                }    
            },
            messages: {
                'name': {
                    required: "Please enter your full name."
                },
                'email': {
                    required: "Please enter valid email address."
                },
                'phone': {
                	required: "Please enter phone number.",
                	digits: 'Please enter digits only.'
                },
                'home_airport': {
                    required: "Please select home airport."
                },
                'destination_airport': {
                    required: "Please select destination airport."
                },
            },
            errorClass: "error",
            errorElement: "label"
        });
    });    
</script>