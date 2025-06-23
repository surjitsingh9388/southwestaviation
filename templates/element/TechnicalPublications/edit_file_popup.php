<div id="editFilePopupModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span><i class="fa fa-folder-o" aria-hidden="true"></i> Edit File</h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmTechnicalPublications', 'autocomplete' => 'off'));
                ?>
                <input type="hidden" id="technical_publication_id" name="technical_publication_id" />
                <div class="row">
                    <div class="col-md-12">
                        <div class="technical_publication_upload_box" onclick="$('#tech_publication_attachment').trigger('click'); return false;">
                            <input type="hidden" name="folderpath" id="folderpath" value="<?php echo $folderpath; ?>" />
                            <input type="hidden" name="pageaction" id="pageaction" value="<?php echo $action; ?>" />
                            <input type="hidden" name="pageparams" id="pageparams" value="<?php echo implode('/',$params); ?>" />
                            <input type="hidden" name="main_page_id" id="main_page_id" value="<?php echo $main_page_id; ?>" />
                            <input type="hidden" name="subpage_id" id="subpage_id" value="<?php echo $subpage_id; ?>" />
                            <i class="fa fa-upload" aria-hidden="true"></i>
                            <div>Upload or drop</div>
                        </div>
                        <input type="file" name="files[]" id="tech_publication_attachment" style="display:none;" multiple="">
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>