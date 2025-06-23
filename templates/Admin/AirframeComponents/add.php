<?php 
$sessionUser = $this->request->getSession()->read('Auth');
$sessionArray = $this->request->getSession()->read('Auth'); 
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Add Airframe Component</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>

        <div class="page-content mt-35">
            <?php echo $this->Form->create($airComp, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAirComp')); ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Aircraft <span class="required">*</span>
                            </label>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('plane_id', array('options' => $planes, 'empty' => 'Select Aircraft', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="log_book">Log Book <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('log_book', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Ex. Airframe, Engine', 'requred' => 'required', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="position">Position
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php
                                $position = [
                                    '1'=>'1',
                                    '2'=>'2'
                                ]; 
                                echo $this->Form->control('position', array('options' => $position, 'empty' => 'Select Position', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); 
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Model
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('description', array('class' => 'form-control col-md-7 col-xs-12', 'label'=> false)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="serial_no">Serial No
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('serial_no', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Serial No', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-sm-offset-3">
                                <?php
                                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1){
                                    echo $this->Form->button('Submit', ['type' => 'submit', 'class' => 'btn btn-success']);
                                }
                                    echo $this->Form->button('Reset', ['type' => 'reset', 'class' => 'btn btn-primary', 'id' => 'reset', 'id' => 'reset']);
                                ?>
                            </div>
                        </div>
                        <?php echo $this->Form->control('updated_by', array('type' => 'hidden', 'value' => $sessionArray['id'], 'label'=> false)); ?>
                    </div>
                </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<script>    
$(document).ready(function() {
    $('#frmAirComp select.selectpicker').on('change', function(e) {
        $('#frmAirComp').validate().element($(this));
    });

    $("#frmAirComp").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: true,
        rules: {
            'plane_id': {
                required: true
            },    
            'log_book': {
                required: true
            },
            /*'serial_no': {
                required: true
            }*/    
        },
        messages: {
            'plane_id': {
                required: "Please select aircraft."
            },
            'log_book': {
                required: "Please enter log book."
            },
            /*'serial_no': {
                required: "Please enter serial number."
            },*/
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
       var validator = $("#frmAirComp").validate();
       validator.resetForm();
    });
});    
</script>
