<div id="statementsAddModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php if(!empty(@$statements->id)){ echo 'Edit'; }else{echo 'Add';} ?> Statement</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <?php
                    echo $this->Form->create($statements, array('class' => 'form-horizontal form-label-left', 'id' => 'frmStatementsAdd'));
                    ?>
                    <input type="hidden" name="statement_id" value="<?php echo @$statements->id; ?>" />
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label label-heading-left" for="reference">Statement Name</label>
                                <span class="label-chkbox-right">
                                    <button type="button" class="btn btn-default" onclick="$('#statementInfoModel').modal('show');">Info</button>
                                </span>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->input('statement_name', array('class'=>'form-control col-md-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'statement_name')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Statement Description</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->input('statement_description', array('type'=>'textarea', 'class'=>'form-control col-md-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'statement_description')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label" for="reference">Status</label>
                                <div class="form-input-frame">
                                    <?php
                                    $statementsstatus = ['0'=>'Inactive', '1'=>'Active'];
                                    echo $this->Form->control('status', array('options' => $statementsstatus, 'empty' => '', 'class' => 'form-control col-md-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'status'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <?php 
                $isdisabled = '';
                if((empty($dashboardMenuItems->action_edit) && !empty(@$statements->id)) && $user_role != '1'){ 
                    $isdisabled = 'disabled';
                }
                ?>
                <button type="button" class="btn btn-primary saveStatements" <?php echo $isdisabled; ?>>Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>