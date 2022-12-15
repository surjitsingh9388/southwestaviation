<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User'); 
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Update Aircraft Category</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>

        <div class="page-content mt-35">
            <div class="tableScroll">
            <?php echo $this->Form->create($airCats, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAirCat')); ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Aircraft <span class="required">*</span>
                            </label>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('plane_id', array('options' => $planes, 'empty' => 'Select Aircraft', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false, 'id' => 'planeName')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_name">Category Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('category_name', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Category Name', 'label' => false, 'required' => 'required')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="serial_number">Serial Number
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('serial_number', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Serial Number', 'label' => false)); ?>
                            </div>
                        </div>
                        
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <?php
                                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1){
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
</div>

<script>    
$(document).ready(function() {
    $('#frmAirCat select.selectpicker').on('change', function(e) {
        $('#frmAirCat').validate().element($(this));
    });
    
    $( "#frmAirCat" ).validate( {
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'plane_id': {
                required: true
            },
            'category_name': {
                required: true
            }
        },
        messages: {
            'plane_id': {
                required: "Please select aircraft."
            },
            'category_name': {
                required: 'Please enter category name.'
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
       var validator = $("#frmAirCat").validate();
       validator.resetForm();
    });

    $('#planeName').on('change', function() {
        var planeId = $( this ).val();
        
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'AircraftComponents', 'action'=>'getComponents']); ?>",
            data: {planeId:planeId},
            async : true,
            success: function(response) {
                $("#airCompsList").html(response);
                $("#Aircraft_component_id").selectpicker();
                $("#Aircraft_component_id.selectpicker").rules('add', {
                    required: true,
                    messages: {
                        required: "Please select component."
                    }
                });
                // show validation message on change
                $('#frmAirCat select.selectpicker').on('change', function(e) {
                    $('#frmAirCat').validate().element($(this));
                });
            }                   
        }); 
    });
    
});    
</script>
