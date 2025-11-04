//Sorting script
var table = $('.settings_tbl');

//Search
$("#searchItem").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".settings_tbl tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
});


$(document).on('click', '.upload-new-logo-popup', function(e){
    var dataval = {};
    fetchDataFromServer(dataval);
});

function fetchDataFromServer(dataval){
    $.ajax({
        url: fetchSettingPopupURL, 
        type: 'post',
        data: dataval,
        success: function (response) {
            $('#settingsAddModel').remove();
            $("#settingspopup").append(response);
            $('.selectpicker').selectpicker('refresh');
            $('#settingsAddModel').modal('show');

            tinyMCE.remove();   
            loadTinymceEditor();
        }
    });
}

function loadTinymceEditor(){
    tinymce.init({
        selector: 'textarea#office_address',
        plugins:
        "print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen codesample charmap hr nonbreaking toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap",
        toolbar: "undo redo | bold italic",
        toolbar_sticky: true,
        autosave_ask_before_unload: true,
        autosave_interval: "30s",
        autosave_prefix: "{path}{query}-{id}-",
        autosave_restore_when_empty: false,
        autosave_retention: "2m",
        height: 400,
        templates: [
            { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
        { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
        { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
        ],
        template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
        template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
        image_caption: false,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: "mceNonEditable",
        toolbar_drawer: 'sliding',
        spellchecker_dialog: true,
        spellchecker_whitelist: ['Ephox', 'Moxiecode'],
        content_style: ".mymention{ color: green; }",
        contextmenu: "",
        content_style: '.mce-annotation { background: #fff0b7; } .tc-active-annotation {background: #ffe168; color: black; }',
        height: 400,
        toolbar_sticky: true
    });
}

$(document).on('click', '.saveSettings', function(e){
    var office_address = tinyMCE.activeEditor.getContent();
    
    if(office_address != '' && office_address != undefined){
        tinyMCE.triggerSave();
        $.ajax({
            url: saveSettingsURL, 
            type: 'post',
            data: $('#frmSettingsAdd').serialize(),
            async : true,
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    alert("Setting Saved Successfully.");
                    window.location.replace(window.location.href);
                }
            }
        });
    }
});

$(document).on('click', '.editsettings', function(e){
    $('.editsettings').removeClass('editsettings-active');
    $(this).addClass('editsettings-active');
});

$(document).on('dblclick', '.editsettings', function(e){
    $('.editsettings').removeClass('editsettings-active');
    $(this).addClass('editsettings-active');
    
    var setting_id = $(this).attr('data-val');
    if(setting_id != '' && setting_id != undefined){
        var section = 'settings_add';
        var url = fetchPopupURL;
        var dataval = {section:section, setting_id:setting_id};
        fetchPopupDataFromServer(url, dataval, section);
    }
});

$(document).on('click', '.deletesettings', function(e){
    var setting_id = $(this).attr('data-val');
    if(setting_id != '' && setting_id != undefined){
        if(confirm('Are you sure want to delete this setting?')){
            $.ajax({
                url: deleteSettingsURL, 
                type: 'post',
                data: {setting_id:setting_id},
                async : true,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        alert("Setting Deleted Successfully.");
                        window.location.replace(window.location.href);
                    }
                }
            });
        }
    }
});


$(document).on('change', "#upload_site_logo", function(){
    // Read selected files
    var totalfiles = document.getElementById('upload_site_logo').files.length;
    for (var index = 0; index < totalfiles; index++) {
        var form_data = new FormData();
        form_data.append("file_name", document.getElementById('upload_site_logo').files[index]);
        
        uploadData(form_data);
    }
});

function uploadData(form_data){
    $.ajax({
        url: uploadSettingLogoURL, 
        type: 'post',
        data: form_data,
        contentType: false,
        processData: false,
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $("#filetbody").html(obj.tblrow);
            } else {
                $('#filetbody').html('<tr><td colspan="5"><span style="color:red;">'+obj.message+'</span></td></tr>');
            }
        }
    });
}

$(document).on('click', '.deleteattachment', function (e) {
    if(confirm('Are you sure want to delete this attachment')){
        $(this).parent().parent().remove();
    }
});

$(document).on('click', '.statement_create_btn', function(e){
    var statement_id = $(this).attr('data-val');
    
    $.ajax({
        url: fetchStatementPopupURL, 
        type: 'post',
        data: {statement_id:statement_id},
        success: function (response) {
            $('#statementsAddModel').remove();
            $("#settingspopup").append(response);
            $('.selectpicker').selectpicker('refresh');
            $('#statementsAddModel').modal('show');
        }
    });
    
});

$(document).on('click', '.saveStatements', function(e){
    var statement_name = $('#statement_name').val();
    var statement_description = $('#statement_description').val();
    
    var flags = '0';
    if(statement_name == '' || statement_name == undefined){
        alert("Please enter statement name.");
        $('#statement_name').focus();

        flags = '1';
    }else if(statement_description == '' || statement_description == undefined){
        alert("Please enter statement description.");
        $('#statement_description').focus();

        flags = '1';
    }

    if(flags == '0'){
        $.ajax({
            url: saveStatementsURL, 
            type: 'post',
            data: $('#frmStatementsAdd').serialize(),
            async : true,
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    alert("Statement Saved Successfully.");
                    window.location.replace(window.location.href);
                }
            }
        });
    }
});

$(document).on('click', '.statement_delete_btn', function(e){
    if(confirm('Are you sure want to delete this statement')){
        var statement_id = $(this).attr('data-val');
    
        if(statement_id != '' && statement_id != undefined){
            $.ajax({
                url: deleteStatementsURL, 
                type: 'post',
                data: {statement_id:statement_id},
                async : true,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        alert("Statement Deleted Successfully.");
                        window.location.replace(window.location.href);
                    }
                }
            });
        }
    }
});