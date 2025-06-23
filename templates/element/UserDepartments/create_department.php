<div id="addUserDepartmentsPopup" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Add Department/Job Title</h4>
                <input type="hidden" name="user_department_id" id="user_department_id" />
            </div>
            <div class="modal-body">
                <div  class="row">
                    <div  class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Department/Job Title</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('department_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Department/Job Title', 'required'=>'required', 'id'=>'user_department_name')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary save_user_departments">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>