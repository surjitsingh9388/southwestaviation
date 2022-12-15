<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Timezones</h3>
        </div>
        <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <!--serch box-->
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Add Timezone</h2>
                    <?php
                        echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-primary pull-right', 'escape' => false));
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <?php echo $this->Form->create($timezone, array('class' => 'form-horizontal form-label-left', 'id' => 'frmTimezone')); ?>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="timezone">Timezone <span class="required">*</span>
                            </label>
                            <!-- <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php //echo $this->Form->control('timezone', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Timezone', 'requred' => 'required', 'label' => false, 'maxlength' => 60)); ?>
                            </div> -->
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('timezone', array('options' => $timezoneList, 'empty' => 'Select Timezone', 'required' => 'required', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->textarea('description', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Timezone description', 'requred' => 'required', 'label' => false, 'maxlength' => 100)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">Code <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('code', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Code', 'requred' => 'required', 'label' => false, 'maxlength' => 10)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="serial_no">Serial Number <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('serial_no', array('class' => 'form-control col-md-7 col-xs-12 numericOnly serial_no', 'placeholder' => 'Serial Number', 'requred' => 'required', 'label' => false, 'maxlength' => '11', 'pattern' => '\d*', 'value' => $serialNo[0]['serial_no']+1)); ?>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <?php
                                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1){
                                    echo $this->Form->button('Submit', ['type' => 'submit', 'class' => 'btn btn-success']);
                                }
                                    echo $this->Form->button('Reset', ['type' => 'reset', 'class' => 'btn btn-primary', 'id' => 'reset']);
                                ?>
                            </div>
                        </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // To remove error message on change of select picker
        $('#frmTimezone select.selectpicker').on('change', function(e) {
            $('#frmTimezone').validate().element($(this));
        });
        
        $("#frmTimezone").validate({
            ignore: [],
            validateHiddenInputs: true,
            rules: {
                'timezone': {
                    required: true,
                    remote: {
                        url: "<?php echo $this->Url->build(['controller'=>'Timezones', 'action'=>'isTimezoneExist']); ?>",
                        type: "post"
                    }
                },
                'description': {
                    required: true,
                },
                'code': {
                    required: true
                },
                'serial_no': {
                    required: true,
                    digits: true,
                    maxlength : 11,
                    remote: {
                        url: "<?php echo $this->Url->build(['controller'=>'Timezones', 'action'=>'isSerialNoExist']); ?>",
                        type: "post"
                    }
                }            
            },
            messages: {
                'timezone': {
                    required: "Please select timezone.",
                    remote: "Timezone already exist."
                },
                'description': {
                    required: "Please enter description."
                },
                'code': {
                    required: "Please enter code."
                },
                'serial_no': {
                    digits: "Please enter digits only.",
                    remote: "Serial no already exist.",
                    maxlength: "Please do not enter more than 11 digits."
                }   
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
           var validator = $("#frmTimezone").validate();
           validator.resetForm();
        });
    });    
</script>    