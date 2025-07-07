<div id="createNewFolderPopupModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span><i class="fa fa-folder-o" aria-hidden="true"></i> <span id="create_folder_title">Create</span> Folder</h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmTechnicalPublications', 'autocomplete' => 'off'));
                ?>
                <input type="hidden" id="technical_publication_id" name="technical_publication_id" />
                <div class="row">
                   <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Name</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('folder_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Folder Name', 'required'=>'required', 'id'=>'tech_publ_folder_name')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary saveTechPublFolder">Create</button>
            </div>
        </div>
    </div>
</div>
<script>
    var createDocumentFileURL = "<?php echo $this->Url->build(['controller'=>'TechnicalPublicationSwas', 'action'=>'createDocumentFile']); ?>";
</script>