<?php 
$sessionUser = $this->request->getSession()->read('Auth');; 
$sessionArray = $this->request->getSession()->read('Auth');
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Add Position</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>
       
        <div class="page-content mt-35">
            <div class="tableScroll">
            <?php echo $this->Form->create($positionRes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmPositionId')); ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Position Title <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('title', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Position Title', 'label' => false, 'required' => 'required')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Status</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php
                            $statusArr = ['1'=>'Active', '0'=>'Inactive'];
                            echo $this->Form->control('status', array('options' => $statusArr, 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                            ?>
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
</div>

<script>    
$(document).ready(function() {
    $('#frmAdSbId select.selectpicker').on('change', function(e) {
        $('#frmPositionId').validate().element($(this));
    });
    
    $( "#frmPositionId" ).validate( {
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: true,
        rules: {
            'title': {
                required: true
            }
        },
        messages: {
            'title': {
                required: 'Please enter Position.'
            }
        }
    });

    $('#reset').click(function() {
       var validator = $("#frmPositionId").validate();
       validator.resetForm();
    });
});    
</script>
