<div id="technicalPublicationPopupModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>File/Folder Permission</h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmTechnicalPublPermission', 'autocomplete' => 'off'));
                ?>
                <input type="hidden" id="technical_publication_id" name="technical_publication_id" value="<?php echo $technicalpublicationdata->id; ?>" />
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Users</label>
                            <div class="form-input-frame">
                                <?php 
                                echo $this->Form->control('permission_user_ids', array('options' => $usersArr, 'empty' => 'Select Role', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false, 'multiple'=>'multiple', 'value'=>$userIdArr));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveTechPublPermission">Save</button>
            </div>
        </div>
    </div>
</div>