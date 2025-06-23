
/*function myFunction() {
    document.getElementById("create_btn_dropdown").classList.toggle("create_btn_dropdown_show");
}
  
// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.technical_publication_create_box')) {
      var dropdowns = document.getElementsByClassName("create_btn_dropdown");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('create_btn_dropdown_show')) {
          openDropdown.classList.remove('create_btn_dropdown_show');
        }
      }
    }
}*/

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
                window.location.reload();
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
                window.location.reload();
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