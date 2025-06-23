<?php $sessionUser = $this->request->getSession()->read('Auth');; ?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Add Role</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>

        <div class="page-content mt-35">
            <div class="tableScroll">
            <?php echo $this->Form->create($role, array('class' => 'form-horizontal form-label-left', 'id' => 'frmRole')); ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role_name">Role Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo $this->Form->control('role_name', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Role Name', 'requred' => 'required', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-sm-offset-3">
                                <?php
                                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1){
                                    echo $this->Form->button('Submit', ['type' => 'submit', 'class' => 'btn btn-success']);
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
</div>

<script>
    $(document).ready(function() {
        $( "#frmRole" ).validate( {
            rules: {
                'role_name': {
                    required: true,
                    minlength: 5,
                    remote: {
                        url: "<?php echo $this->Url->build(['controller'=>'Roles', 'action'=>'isRoleExist']); ?>",
                        type: "post"
                    }
                }
            },
            messages: {
                'role_name': {
                    required: "Please enter role name.",
                    minlength: "Role name must consist of at least 5 characters.",
                    remote: "Role already exist."
                }
            },
            errorClass: "error",
            errorElement: "label"
        });
        
        $('#reset').click(function() {
           var validator = $("#frmRole").validate();
           validator.resetForm();
        });
    });     
</script>
