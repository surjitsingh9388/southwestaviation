<div id="aircraftWOPhotoUploadModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Photo</h4>
            </div>
            <?= $this->Form->create(null, ['type' => 'file', 'id' => 'importForm']) ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <div id="upload-area" class="form-input-frame upload-area">
                                <p>Drag & Drop your file here or</p>
                                <button type="button" id="uploadPhotoBtn" class="btn btn-primary btn-sm">Choose File</button>

                                <input type="file" name="files[]" id="aircraft_wo_photo" class="hide-block" multiple  accept=".png, .gif, .jpeg" style="display:none;" />
                            </div>
                            <span id="importPhotoErrorMsg" style="color:red;"></span>
                            <div id="importedPhotoName" style="margin-top:10px; color:green;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="button" value="Submit" class="btn btn-primary" id="uploadWOPhotoBtn">
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

<script>
    $(document).ready(function() {
        //import excel code
        var fileInput = $("#aircraft_wo_photo");

        // open file dialog when button clicked
        $("#uploadPhotoBtn").click(function () {
            fileInput.click();
        });

        // function to validate Excel file
        function validatePhoto(file, fileInput) {
            var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

            if (!allowedExtensions.exec(file.name)) {
                $("#importPhotoErrorMsg").text("Only image files (.jpg, .jpeg, .png, .gif) are allowed.");
                $("#importedPhotoName").text("");
                fileInput.val(""); // reset file input
                return false;
            }

            $("#importPhotoErrorMsg").text(""); // clear error
            $("#importedPhotoName").text("Selected file: " + file.name);
            return true;
        }

        // validate when file selected
        fileInput.on("change", function () {
            var file = this.files[0];
            if (file) {
                validatePhoto(file);
            }
        });

        // drag & drop support
        $("#upload-area").on("dragover", function (e) {
            e.preventDefault();
            $(this).addClass("dragover");
        });

        $("#upload-area").on("dragleave", function (e) {
            e.preventDefault();
            $(this).removeClass("dragover");
        });

        $("#upload-area").on("drop", function (e) {
            e.preventDefault();
            $(this).removeClass("dragover");

            var file = e.originalEvent.dataTransfer.files[0];
            if (file) {
                fileInput[0].files = e.originalEvent.dataTransfer.files; // link dropped file to input
                validatePhoto(file);
            }
        });
    });
</script>