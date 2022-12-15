<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $AirframeComponentTime
 */
use Cake\Routing\Router;
?>
<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User'); 
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Add Airframe Component Time</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="tableScroll">
            <?php echo $this->Form->create($airCompTimes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAirCompTime')); ?>
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
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="airframe_component_id">Airframe Component <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12" id="airCompsList">
                                <?php echo $this->Form->control('airframe_component_id', array(/*'options' => $airComps,*/ 'empty' => 'Select Aircraft Component', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="log_date">Log Date <!-- <span class="required">*</span> -->
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group date datePicker">
                                    <?php echo $this->Form->Text('log_date', array('class' => 'form-control col-md-7 col-xs-12', 'id' => 'logDatepicker', 'placeholder' => 'Log Date', 'label' => false)); ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hours">Hours
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('hours', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Hours', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cycles">Cycles
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('cycles', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Cycles', 'label' => false)); ?>
                            </div>
                        </div>                        

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
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
</div>

<script>    
$(document).ready(function() {
    $('#logDatepicker').datetimepicker({
        format: 'MM-DD-YYYY'
    });

    $('.datePicker').datetimepicker({
        format: 'MM-DD-YYYY'
    });

    $('#frmAirCompTime select.selectpicker').on('change', function(e) {
        $('#frmAirCompTime').validate().element($(this));
    });
    
    $( "#frmAirCompTime" ).validate( {
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: true,
        rules: {
            'plane_id': {
                required: true
            },
            'airframe_component_id': {
                required: true
            }
        },
        messages: {
            'plane_id': {
                required: "Please select aircraft."
            },
            'airframe_component_id': {
                required: "Please select component."
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
       var validator = $("#frmAirCompTime").validate();
       validator.resetForm();
    });

    $('#planeName').on('change', function() {
        var planeId = $( this ).val();
        
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'AirframeComponents', 'action'=>'getComponents']); ?>",
            data: {planeId:planeId},
            async : true,
            success: function(response) {
                $("#airCompsList").html(response);
                $("#airframe_component_id").selectpicker();
                $("#airframe_component_id.selectpicker").rules('add', {
                    required: true,
                    messages: {
                        required: "Please select component."
                    }
                });
                // show validation message on change
                $('#frmAirCompTime select.selectpicker').on('change', function(e) {
                    $('#frmAirCompTime').validate().element($(this));
                });
            }                   
        }); 
    });
});    
</script>

