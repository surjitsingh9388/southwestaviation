$(document).ready(function() {
    $('#technical_publication_datatable').DataTable({
        paging: true,          // enable pagination
        pageLength: 10,        // show 10 rows per page
        lengthChange: false,   // hide "show 10/25/50 entries"
        searching: false,      // disable search (set true if you want search box)
        ordering: true,        // enable column sorting
        info: true,            // "Showing 1 to 10 of X entries"
        language: {
            paginate: {
                previous: "&laquo;",
                next: "&raquo;"
            }
        }
    });
});

$(document).on("click", ".rename_tech_publ_btn", function () {
    $("#renameId").val($(this).data("id"));
    $("#folder_file_name").val($(this).attr("folder-file-name"));
    $("#folder_file_path").val($(this).attr("folder-path"));
    $("#added_by").val($(this).attr("data-added-by"));
    $('.selectpicker').selectpicker('refresh');

    $("#editFileFolderPopupModel").modal("show");
});

$(document).on("click", "#save_technical_publication_btn", function (e) {
    e.preventDefault(); // stop default form submission if inside a form

    if ($.trim($('#folder_file_name').val()) === '') {
        alert("Please fill folder/file name");
        return false;
    }

    $.ajax({
        url: editTechPublicationURL,
        method: "POST",
        data: $("#frmRenameTechnicalPublications").serialize(), // serialize the form data
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.status == 'success') {
                alert(obj.message);
                location.reload();
            } else {
                alert("Error: " + obj.message);
            }
        },
        error: function () {
            alert("Something went wrong while saving.");
        }
    });
});


$(document).on('click', '.create_btn_dropdown a, .technical_publication_folder_box', function(e){
    var clickoptionval = $(this).attr('data-val');
    if(clickoptionval == 'folder'){
        $('#create_folder_title').text('Create');
        $('.saveTechPublFolder').html('Create');
        $('#tech_publ_folder_name').val('');
        $('#createNewFolderPopupModel').modal('show');
    }else{
        $.ajax({
            type: "POST",
            url: createDocumentFileURL,
            data: {},
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    window.open(obj.data);
                } else if(obj.status == 'failure') {
                    alert(obj.message);
                }
            },
            error : function() {
                alert('Some error occured. Please try again!');
            }
        });
    }
});

$(document).on('click', '.saveTechPublFolder', function(e){
    var folder_name = $.trim($('#tech_publ_folder_name').val());
    if(folder_name != ''){
        $('form#frmTechnicalPublications').submit();
    }else{
        alert("Please fill folder name.");
    }
});

$(document).on('click', '.tech_publ_edit_btn', function(e){
    var is_folder = $(this).attr('is-folder');
    var technical_publication_id = $(this).attr('data-val');
    $('#technical_publication_id').val(technical_publication_id);
    if(is_folder == '1'){
        var folder_name = $(this).parent().siblings(":first").text();
        folder_name = $.trim(folder_name);
        if(technical_publication_id != '' && technical_publication_id != undefined && folder_name!= '' && folder_name!=undefined){
            $('#create_folder_title').text('Edit');
            $('.saveTechPublFolder').html('Save');
            $('#tech_publ_folder_name').val(folder_name);
            $('#createNewFolderPopupModel').modal('show');
        }
    }else{
        $('#editFilePopupModel').modal('show');
    }
});

$(document).on('click', '.tech_publ_delete_btn', function(e){
    var folder_name = $(this).closest("tr").children("td:first").text();
    folder_name = $.trim(folder_name);
    if(confirm("Are you sure you want to delete `"+folder_name+"`")){
        var id=$(this).attr('data-val');
        var folder_path = $(this).attr('folder-path');

        $("#actionForm").attr("action",deleteTechPublicationURL);
        $("#actionForm").append("<input type='hidden' name='id' value='"+id+"'/>");
        $("#actionForm").append("<input type='hidden' name='folder_path' value='"+folder_path+"'/>");
        $("#actionForm").append("<input type='hidden' name='delete_folder_name' value='"+folder_name+"'/>");
        $("#actionForm").submit();
    }
});

$(document).on('change', "#tech_publication_attachment", function(){
    var totalfiles = document.getElementById('tech_publication_attachment').files.length;
    for (var index = 0; index < totalfiles; index++) {
        var form_data = new FormData();
        form_data.append("file_name", document.getElementById('tech_publication_attachment').files[index]);
        
        uploadData(form_data);
    }
});

$(document).on('drop', '.upload-file-area', function (e) {
    e.stopPropagation();
    e.preventDefault();
    
    var file = e.originalEvent.dataTransfer.files;
    var fd = new FormData();
    fd.append('file_name', file[0]);
    
    uploadData(fd);
});

$(document).on('dragenter', '.upload-file-area', function (e) {
    e.stopPropagation();
    e.preventDefault();
});

// Drag over
$(document).on('dragover', '.upload-file-area', function (e) {
    e.stopPropagation();
    e.preventDefault();
});

function uploadData(form_data){
    var folderpath = $('#folderpath').val();
    form_data.append('folderpath', folderpath);
    form_data.append('action', $('#pageaction').val());
    form_data.append('params', $('#pageparams').val());
    form_data.append('main_page_id', $('#main_page_id').val());
    form_data.append('subpage_id', $('#subpage_id').val());
    $.ajax({
        url: uploadTechPublicationFileURL, 
        type: 'post',
        data: form_data,
        contentType: false,
        processData: false,
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                window.location.replace(window.location.href);
            } else {
                alert(obj.message);
            }
        }
    });
}

$(document).on('click', '.tech_publ_permission', function(e){
    var technical_publication_id = $(this).attr('data-val');
    $.ajax({
        type: "POST",
        url: techPublPermissionPopupURL,
        data: {technical_publication_id:technical_publication_id},
        success:function(response) {
            $('.techpublicationpopup').html(response);
            $('#technicalPublicationPopupModel').modal('show');
            $('.selectpicker').selectpicker('refresh');
        },
        error : function() {
            alert('Some error occured. Please try again!');
        }
    });
});

$(document).on('click', '#saveTechPublPermission', function(e){
    var permission_user_id = $.trim($('#permission-user-ids').val());
    if(permission_user_id != ''){
        $.ajax({
            type: "POST",
            url: saveTechPublPermissionURL,
            data: $('form#frmTechnicalPublPermission').serialize(),
            success:function(response) {
                var obj = JSON.parse(response);
                alert(obj.message);
                $('#technicalPublicationPopupModel').modal('hide');
                window.location.replace(window.location.href);
            },
            error : function() {
                alert('Some error occured. Please try again!');
            }
        });
    }else{
        alert("Please select user.");
    }
});

$(document).on('click', '.tech-publ-download-folder', function(e){
    var folder_name = $(this).attr('data-val');
    var is_folder = $(this).attr('is_folder');
    if(folder_name != ''){
        window.open(downloadFolderURL+'?folder_path='+folder_name+'&is_folder='+is_folder);
    }
});