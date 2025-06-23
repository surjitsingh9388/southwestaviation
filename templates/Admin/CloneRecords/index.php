<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth'); 
?>
    
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Clone Aircraft</h2>
        </div>

        <div class="page-content mt-35">
            <?php echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmCloneRecords')); ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-5">
                            <div><h4><u>Clone From</u></h4></div>
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-5 col-xs-12" for="plane_id">Select Aircraft <span class="required">*</span></label>
                                <div class="col-md-8 col-sm-7 col-xs-12">
                                <?php echo $this->Form->control('plane_id', array('options' => $planes, 'empty' => 'Select Aircraft', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1 col-xs-12" style="text-align: center;">
                            <span class="d-none-mobile" style="border: 1px solid #c0c0c0; min-height: 400px; width: 1px; display: block; margin: 0 auto;"></span>
                            <div class="ln_solid d-block-mobile" style="display: none"></div>
                        </div>
                        
                        <div class="col-sm-6">
                            <div><h4><u>Clone To</u></h4></div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-5 col-xs-12" for="plane_code">Registration Code <span class="required">*</span>
                                </label>
                                <div class="col-md-8 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->control('plane_code', array('class' => 'form-control col-md-7 col-xs-12', 'requred' => 'required', 'placeholder' => 'Registration Code (Ex. N435CM)', 'label' => false)); ?>
                                </div>
                            </div>

                            <div class="">
                                <div class="form-group">
                                <?php echo $this->Form->checkbox('exclude_details', array('label'=>false)); ?> <span style="font-size: 17px; padding-left: 5px;"> Clear LAST C/W and Serial Number.</span>
                                </div>

                                <div class="form-group">
                                <?php echo $this->Form->checkbox('add_info', array('id'=>'addAllInfo', 'label'=>false)); ?> <span style="font-size: 17px; padding-left: 5px;"> Add new aircraft infomation.(<span style="font-size: 12px;">NOTE: If not included it will copy source aircraft details.</span>)</span>
                                </div>

                                <div class="form-group">
                                <?php echo $this->Form->checkbox('keep_attachments', array('label'=>false)); ?> <span style="font-size: 17px; padding-left: 5px;"> Keep Attachments</span>
                                </div>

                                <div class="well aircraftInfoCls" style="display: none;">
                                    <!-- <div class="ln_solid"></div> -->

                                    <div><h5><u>Aircraft Information</u></h5></div>
                            
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_serial_number">Serial Nnumber
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $this->Form->control('plane_serial_number', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Serial Nnumber', 'label' => false)); ?>
                                        </div>
                                    </div>

                                    <div class="form-group"> 
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airworthiness_date">Airworthiness Date
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <div class="input-group date datePicker">
                                                <?php echo $this->Form->Text('airworthiness_date', array('class' => 'form-control col-md-7 col-xs-12', 'id' => 'airWorthDatepicker', 'placeholder' => 'Airworthiness Date', 'label' => false)); ?>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_type">Make & Model
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $this->Form->control('plane_type', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Make & Model', 'label' => false)); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="federal_aviation_regulation">Schedule Revision Level
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $this->Form->control('federal_aviation_regulation', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Federal Aviation Regulation', 'label' => false)); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="hours">Hours 
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $this->Form->control('hours', array('class' => 'form-control col-md-7 col-xs-12', 'requred' => 'required', 'placeholder' => 'Hours', 'label' => false)); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="cycles">Cycles 
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $this->Form->control('cycles', array('class' => 'form-control col-md-7 col-xs-12', 'requred' => 'required', 'placeholder' => 'Cycles', 'label' => false)); ?>
                                        </div>
                                    </div>

                                    <div><h5><u>Operator Information</u></h5></div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_name">Name
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $this->Form->control('plane_name', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Name', 'label' => false)); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="address">Address
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $this->Form->control('address', array('class' => 'form-control col-md-7 col-xs-12', 'rows'=>2, 'label'=> false)); ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="manufacturered_by">Manufacturered By
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <?php echo $this->Form->control('manufacturered_by', array('class' => 'form-control col-md-7 col-xs-12', 'requred' => 'required', 'placeholder' => 'Manufacturered By', 'label' => false)); ?>
                                        </div>
                                    </div>

                                    <div class="form-group"> 
                                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="manufacturered_on">Manufacturered On 
                                        </label>
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <div class="input-group date datePicker">
                                                <?php echo $this->Form->Text('manufacturered_on', array('class' => 'form-control col-md-7 col-xs-12', 'id' => 'myDatepicker', 'placeholder' => 'Manufacture Date', 'label' => false)); ?>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div id="cloneMsg"></div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                                echo $this->Form->button('Copy', ['type' => 'submit', 'class' => 'btn btn-success', 'id'=>'cloneAirId']);
                            }
                            echo $this->Form->button('Reset', ['type' => 'reset', 'class' => 'btn btn-primary', 'id' => 'reset']);
                            ?>
                        </div>
                    </div>

                </div>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<script>    
$(document).ready(function() {
    $('#frmCloneRecords select.selectpicker').on('change', function(e) {
        $('#frmCloneRecords').validate().element($(this));
    });

    //Validation
    $("#frmCloneRecords").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'plane_id': {
                required: true
            },
            'plane_code': {
                required: true,
                maxlength: 40,
                remote: {
                    url: "<?php echo $this->Url->build(['controller'=>'Planes', 'action'=>'isPlaneExist']); ?>",
                    type: "post"
                }
            },   
        },
        messages: {
            'plane_id': {
                required: "Please select aircraft."
            },
            'plane_code': {
                required: "Please enter registration code.",
                maxlength: "Registration code consist of 40 characters only.",
                remote: "Registration code already exist."
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
            $.each(validator.errorList, function (index, item) {
                var jelm = $(item.element);
                if (jelm.hasClass('selectpicker') && (jelm.parents('div.select').find('label.error').length != 0 || jelm.parents('div.select').find('label.error').length != 1)) {
                    jelm.siblings('.bootstrap-select').find('.selectpicker').focus();
                    return false;
                }
            });
        }
    });

    //$('#myDatepicker').datetimepicker();
    //$('#airWorthDatepicker').datetimepicker(); 
           
    $('#reset').click(function() {
       var validator = $("#frmCloneRecords").validate();
       validator.resetForm();
       $('.aircraftInfoCls').hide();
    });

    //Hide/Show aircraft information
    $("#addAllInfo").click(function () {
        if($('input#addAllInfo').is(':checked')) {
            $('.aircraftInfoCls').show();
        } else {
            $('.aircraftInfoCls').hide();
        }
    });

    $(document).on("click", "#cloneAirId", function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        if($('#frmCloneRecords').valid()) {
            var data = $("#frmCloneRecords").serialize();
            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller'=>'CloneRecords', 'action'=>'cloneAircraft']); ?>",
                data: data,
                async : true,
                beforeSend: function () {
                    $('.loader').show();
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('.loader').hide();
                        $('#cloneMsg').show();
                        $('#cloneMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        setTimeout(function() {
                            $('#cloneMsg').hide();
                        }, 5000);
                    } else {
                        $('.loader').hide();
                        $('#cloneMsg').show();
                        $('#cloneMsg').html('<span style="color:red;">'+obj.message+'</span>');
                        setTimeout(function() {
                            $('#cloneMsg').hide();
                        }, 5000);
                    }
                }
            });
        }
    });

});    
</script>
