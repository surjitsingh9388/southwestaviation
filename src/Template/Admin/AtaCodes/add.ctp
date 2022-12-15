<?php 
$sessionUser = $this->request->session()->read('Auth.User'); 
$sessionArray = $this->Session->read('Auth.User');
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Add ATA Code</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>
       
        <div class="page-content mt-35">
            <div class="tableScroll">
            <?php echo $this->Form->create($ataCodes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAtaCode')); ?>
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
    $('#frmAtaCode select.selectpicker').on('change', function(e) {
        $('#frmAtaCode').validate().element($(this));
    });
    
    $( "#frmAtaCode" ).validate( {
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
       var validator = $("#frmAtaCode").validate();
       validator.resetForm();
    });
});    
</script>