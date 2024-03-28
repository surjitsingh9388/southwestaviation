<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User'); 
?>
    
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Add Aircraft</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>

        <div class="page-content mt-35">
            <div class="tableScroll">
            <?php echo $this->Form->create($plane, array('class' => 'form-horizontal form-label-left', 'id' => 'frmPlane', 'enctype' => 'multipart/form-data' )); ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                    <div><h5><ul>Aircraft Information</ul></h5></div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_code">Registration Code <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->control('plane_code', array('class' => 'form-control col-md-7 col-xs-12', 'requred' => 'required', 'placeholder' => 'Registration Code', 'label' => false)); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_serial_number">Serial Number
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->control('plane_serial_number', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Serial Nnumber', 'label' => false)); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_type">Make & Model
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->control('plane_type', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Make & Model', 'label' => false)); ?>
                        </div>
                    </div>

                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="airworthiness_date">Airworthiness Date
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="input-group date datePicker">
                                <?php echo $this->Form->Text('airworthiness_date', array('class' => 'form-control col-md-7 col-xs-12 datePicker', 'placeholder' => 'Airworthiness Date', 'label' => false)); ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="federal_aviation_regulation">Schedule Revision Level
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->control('federal_aviation_regulation', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Federal Aviation Regulation', 'label' => false)); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="hours">Hours 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->control('hours', array('class' => 'form-control col-md-7 col-xs-12', 'requred' => 'required', 'placeholder' => 'Hours', 'label' => false)); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cycles">Cycles 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->control('cycles', array('class' => 'form-control col-md-7 col-xs-12', 'requred' => 'required', 'placeholder' => 'Cycles', 'label' => false)); ?>
                        </div>
                    </div>

                    <div><h5><ul>Operator Information</ul></h5></div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_name">Name
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->control('plane_name', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Name', 'label' => false)); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Address
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->control('address', array('class' => 'form-control col-md-7 col-xs-12', 'rows'=>2, 'label'=> false)); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="manufacturered_by">Manufactured By
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo $this->Form->control('manufacturered_by', array('class' => 'form-control col-md-7 col-xs-12', 'requred' => 'required', 'placeholder' => 'Manufactured By', 'label' => false)); ?>
                        </div>
                    </div>

                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="manufacturered_on">Manufactured On 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="input-group date datePicker">
                                <?php echo $this->Form->Text('manufacturered_on', array('class' => 'form-control col-md-7 col-xs-12 datePicker', 'placeholder' => 'Manufactured Date', 'label' => false)); ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
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
    $("#frmPlane").validate({
        rules: {
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
            'plane_code': {
                required: "Please enter registration code.",
                maxlength: "Registration code consist of 40 characters only.",
                remote: "Registration code already exist."
            },
        },
        errorClass: "error",
        errorElement: "label"
    }); 
           
    $('#reset').click(function() {
       var validator = $("#frmPlane").validate();
       validator.resetForm();
    });
});    
</script>