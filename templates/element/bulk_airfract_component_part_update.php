<div id="aircraftCompPartImportModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Import Excel File</h4>
            </div>
            <?= $this->Form->create(null, ['type' => 'file', 'id' => 'importForm']) ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <div id="upload-area" class="form-input-frame upload-area">
                                <p>Drag & Drop your file here or</p>
                                <button type="button" id="uploadBtn" class="btn btn-primary btn-sm">Choose File</button>

                                <?php
                                echo $this->Form->control('aircraft_component_part_edit_file', [
                                    'type' => 'file',
                                    'class' => 'form-control',
                                    'label' => false,
                                    'id' => 'aircraft_component_part_edit_file',
                                    'accept' => '.xls,.xlsx',
                                    'style' => 'display:none;'
                                ]);
                                ?>
                            </div>
                            <span id="importExlErrorMsg" style="color:red;"></span>
                            <div id="importedFileName" style="margin-top:10px; color:green;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="button" id="undoImportBtn" class="btn btn-danger" value="Undo Last Import" style="display:none;">
                <input type="button" value="Upload Excel" class="btn btn-primary" id="uploadExcelFileBtn">
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<style>
.upload-area {
    border: 2px dashed #999;
    border-radius: 10px;
    padding: 25px;
    text-align: center;
    color: #666;
    cursor: pointer;
}
.upload-area.dragover {
    border-color: #007bff;
    background: #f0f8ff;
}
</style>