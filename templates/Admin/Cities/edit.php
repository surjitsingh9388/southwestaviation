<?php $sessionUser = $this->request->getSession()->read('Auth');; ?>
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>City</h3>
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
                    <h2>Edit City</h2>
                    <?php
                        echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-primary pull-right', 'escape' => false));
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <?php echo $this->Form->create($cities, array('class' => 'form-horizontal form-label-left', 'id' => 'frmCity')); ?>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Country <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('country_id', array('options' => $countries, 'empty' => 'Please Select Country', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false, 'default' => !empty($cities->state->country->id)?$cities->state->country->id:'')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">State <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('state_id', array('options' => $states, 'empty' => 'Please Select State', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tier_level">City Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('name', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'City Name', 'requred' => 'required', 'label' => false, 'maxlength'=>'30')); ?>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="serial_no">Serial Number <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('serial_no', 
                                    array('type' => 'number', 'class' => 'form-control col-md-7 col-xs-12 numericOnly', 'placeholder' => 'Serial Number', 'label' => false)); ?>
                            </div>
                        </div> -->
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
        $('#frmCity select.selectpicker').on('change', function(e) {
            $('#frmCity').validate().element($(this));
        });
        
        $("#frmCity").validate({
            ignore: [],
            validateHiddenInputs: true,
            rules: {
                'name': {
                    required: true
                },
                'state_id': {
                    required: true
                },
                'country_id': {
                    required: true
                }
            },
            messages: {
                'name': {
                    required: "Please enter city name."
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
           var validator = $("#frmCity").validate();
           validator.resetForm();
        });

        $('#country-id').change(function(){
            if ($(this).val() != '') {
                $.ajax({
                  type: "POST",
                  url: "<?php echo $this->Url->build(['controller'=>'Cities', 'action'=>'getAjaxStatesList']); ?>",
                  data: {country_id:$(this).val()},
                  dataType: 'json',
                  async : true,
                  /*beforeSend: function(){
                    $('body').addClass('backgroundFixed');
                    $('.loader').show();
                  },*/
                  success: function(response) {
                    var html = '<option value="">Please Select State</option>';
                    if (response != '') {
                        $.each(response, function (index, item) {
                            //console.log(index);
                            html += '<option value="'+ index +'">'+ item +'</option>';
                        });
                    }else{
                        alert('Sorry no state found.')
                    }
                    $('#state-id').html(html);
                    $('#state-id').selectpicker('refresh');
                  }
                  /*complete: function(){
                    $('body').removeClass('backgroundFixed');
                    $('.loader').hide();
                  }*/
                });
            }
        });
    });
</script>    

