<div id="addSubstractPTOPopupModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add/Subtract PTO</h4>
            </div>
            <div class="modal-body">
                <?php echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmPTORequestAddSub', 'role' => 'form')); ?>
                <div class="row">
                    <input type="hidden" name="user_id" id="pto_user_id" />
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Add Hours</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('add_pto_hours', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'add_pto_hours')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="text-align:center; font-weight:bold; font-size:15px;">OR</div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Subtract Hours</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('substract_pto_hours', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'substract_pto_hours')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary pto_hours_savebtn">Save</button>
            </div>
        </div>
    </div>
</div>
