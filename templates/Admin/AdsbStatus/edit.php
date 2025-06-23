<?php 
$sessionUser = $this->request->getSession()->read('Auth');; 
$sessionArray = $this->request->getSession()->read('Auth');
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Update AD/SB Class</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="tableScroll">
            <?php echo $this->Form->create($adsbRes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAdSbId')); ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">AD/SB Class <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('title', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'AD/SB Class', 'label' => false, 'required' => 'required')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Status</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            $statusArr = ['active'=>'Active', 'inactive'=>'Inactive'];
                            echo $this->Form->control('status', array('options' => $statusArr, 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                            ?>
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
    $('#frmAdSbId select.selectpicker').on('change', function(e) {
        $('#frmAdSbId').validate().element($(this));
    });
    
    $( "#frmAdSbId" ).validate( {
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: true,
        rules: {
            'title': {
                required: true
            }
        },
        messages: {
            'title': {
                required: 'Please enter AD/SB Class.'
            }
        }
    });

    $('#reset').click(function() {
       var validator = $("#frmAdSbId").validate();
       validator.resetForm();
    });
    
});    
</script>
