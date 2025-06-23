<?php 
$sessionUser = $this->request->getSession()->read('Auth');; 
$sessionArray = $this->request->getSession()->read('Auth');
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Update ATA Code</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="tableScroll">
            <?php echo $this->Form->create($ataCodes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAirCat')); ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ata_code">ATA Code <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('ata_code', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'ATA Code', 'label' => false, 'required' => 'required')); ?>
                            </div>
                        </div>
                        
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-sm-offset-3">
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
        validateHiddenInputs: true,
        rules: {
            'ata_code': {
                required: true
            }
        },
        messages: {
            'ata_code': {
                required: 'Please enter ATA Code.'
            }
        }
    });

    $('#reset').click(function() {
       var validator = $("#frmAirCat").validate();
       validator.resetForm();
    });
    
});    
</script>
