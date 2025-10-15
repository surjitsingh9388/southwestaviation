<div id="editFileFolderPopupModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span><i class="fa fa-folder-o" aria-hidden="true"></i> Edit Folder/File Name</h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmRenameTechnicalPublications', 'autocomplete' => 'off'));
                ?>
                <input type="hidden" id="renameId" name="technical_publication_id" />
                <input type="hidden" id="folder_file_path" name="folder_file_path" />
                <div class="row">
                    <div  class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Folder/File Name</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('folder_file_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Folder/File Name', 'required'=>'required', 'id'=>'folder_file_name')); ?>
                            </div>
                        </div>
                    </div>

                    <div  class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Owner</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('added_by', [
                                    'type'        => 'select',                     
                                    'options'     => $usersArr,
                                    'class'       => 'form-control',
                                    'label'       => false,
                                    'empty'       => 'Select Owner',         
                                    'required'    => true,
                                    'id'          => 'added_by'
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save_technical_publication_btn">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>