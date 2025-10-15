//Sorting script
var table = $('.settings_tbl');
$('#toolName, #toolDescription, #toolModel, #toolSerialNumber, #toolLocation')
    .wrapInner('<span title="sort this column"/>')
    .each(function() {
        var th = $(this),
            thIndex = th.index(),
            inverse = false;
        th.click(function() {
            table.find('td.collapse-tr').filter(function() {
                return $(this).index() === thIndex;
            }).sortElements(function(a, b) {
                return $.text([a]) > $.text([b]) ?
                    inverse ? -1 : 1
                    : inverse ? 1 : -1;
            }, function() {
                // parentNode is the element we want to move
                return this.parentNode; 
            });
            inverse = !inverse;   
        });     
    });

//When load click sorting
/*$( window ).on( "load", function() {
    setTimeout(function() {
        $('#aircraftId').click();
        $('#aircraftId').click();
    }, 500);
});*/

//Change sorting dynamically
$("select#sortById").change(function() {
    $('#aircraftId').click();
});

//Search
$("#searchItem").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#inventoryToolsList tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
});

$(document).on('click', '.add-new-tool-popup', function(e){
    $('#inventoryToolDetailModel').modal('hide');
    
    $.ajax({
        url: openToolAddPopupURL, 
        type: 'post',
        data: {},
        async : true,
        success: function (response) {
            //$('#inventoryToolAddPopup').remove();
            $(".inventorytoolspopup").html(response);
            $('.selectpicker').selectpicker('refresh');
            $('#inventoryToolAddPopup').modal('show');
        }
    });
});

$(document).on('click', '.saveAddNewTool', function(e){
    var tool_name = $.trim($('#inventory_tool_name').val());
    if(tool_name != '' && tool_name != undefined){
        $.ajax({
            url: saveInventoryToolURL, 
            type: 'post',
            data: {tool_name:tool_name},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    $('#inventoryToolsList').html(obj.toolshtml);

                    openToolDetailPopup(obj.tool_id);
                }
            }
        });
    }else{
        alert("Enter tool name");
    }
});

function openToolDetailPopup(tool_id){
    if(tool_id != '' && tool_id !=  undefined){
        $.ajax({
            url: openToolDetailPopupURL, 
            type: 'post',
            data: {tool_id:tool_id},
            async : true,
            success: function (response) {
                $('#inventoryToolDetailModel').remove();
                $(".inventorytoolspopup").append(response);
                toolInfoBlockDate();
                $('.selectpicker').selectpicker('refresh');
                $('#inventoryToolDetailModel').modal('show');
            }
        });
    }
}

$(document).on('dblclick', '.inventory_tool_list', function(e){
    var tool_id = $(this).attr('data-val');
    $('.inventory_tool_list').removeClass('inventory_tool_list_active');
    if(tool_id != '' && tool_id !=  undefined){
        $(this).addClass('inventory_tool_list_active');
        openToolDetailPopup(tool_id);
    }
});

function toolInfoBlockDate(){
    $('#calibration_date, #due_date, #date_labeled, #date_purchased, #date_sent_out, #date_received_back, #date_of_calibration').datetimepicker({
        format: 'MM-DD-YYYY'
    });
}

$(document).on('click', '.saveToolInfoDetail', function(e){
    var tool_name = $('#tool_info_name').val();
    if(tool_name != '' && tool_name != undefined){
        $.ajax({
            url: saveInventoryToolURL, 
            type: 'post',
            data: $('#frmInventoryToolsDet').serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    alert('Tool detail saved successfully.');
                    $('#inventoryToolsList').html(obj.toolshtml);
                    $('#inventoryToolAddPopup').modal('hide');
                }
            }
        });
    }
});

$(document).on('click', '.tool-add-history-item', function(e){
    var tool_id = $('#tool_id').val();
    if(tool_id != '' && tool_id != undefined){
        $.ajax({
            url: openCertificationHistoryPopupURL, 
            type: 'post',
            data: {tool_id:tool_id},
            async : true,
            success: function (response) {
                $('#toolCertifiedHistoryAddPopup').remove();
                $(".inventorytoolspopup").append(response);

                toolInfoBlockDate();

                $('.selectpicker').selectpicker('refresh');
                $('#toolCertifiedHistoryAddPopup').modal('show');
            }
        });
    }
});

$(document).on('click', '.saveAddToCertifiedHistory', function(e){
    var tool_name = $('#tool_info_name').val();
    if(tool_name != '' && tool_name != undefined){
        $.ajax({
            url: saveToolCertifiedHistoryURL, 
            type: 'post',
            data: $('#frmToolCertifiedHistory').serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    alert('Certified History saved successfully.');
                    $('#toolCertifiedHistoryAddPopup').modal('hide');
                    $('#inventoryToolsCertHistList').html(obj.certifhisthtml);
                }
            }
        });
    }
});

$(document).on('click', '.certified_history_list', function(e){
    $('.certified_history_list').removeClass('certified_history_list_active');
    $(this).addClass('certified_history_list_active');
});

$(document).on('click', '.tool-delete-history-item', function(e){
    var certified_history_id = $('.certified_history_list_active').attr('data-val');
    if(certified_history_id != '' && certified_history_id != undefined){
        if(confirm('Are you sure want to delete this certification history?')){
            $.ajax({
                url: deleteToolCertifiedHistoryURL, 
                type: 'post',
                data: {certified_history_id:certified_history_id},
                dataType: 'text',
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        alert('Selected record deleted successfully.');
                        $('#inventoryToolsCertHistList').html(obj.certifhisthtml);
                    }
                }
            });
        }
    }
});

$(document).on("change", "#inventory_tool_files", function(){
    // Read selected files
    var fldid = 'inventory_tool_files';
    var tableid = 'toolfileattachlist';
    uploadFileToServer(fldid, tableid, uploadToolFilesURL);
});

$(document).on("change", "#inventory_tool_photo", function(){
    // Read selected files
    var fldid = 'inventory_tool_photo';
    var tableid = 'toolphotosattachlist';
    uploadFileToServer(fldid, tableid, uploadToolPhotosURL);
});

function uploadFileToServer(fldid, tableid, url){
    var totalfiles = document.getElementById(fldid).files.length;
    for (var index = 0; index < totalfiles; index++) {
        var form_data = new FormData();
        form_data.append("file_name", document.getElementById(fldid).files[index]);
        if(fldid == 'inventory_tool_files' || fldid == 'inventory_tool_photo'){
            var tool_id = $('#tool_id').val();
            form_data.append("tool_id", tool_id);
        }

        $.ajax({
            url: url, 
            type: 'post',
            data: form_data,
            contentType: false,
            processData: false,
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $("#"+tableid).append(obj.tblrow);
                    if(fldid == 'inventory_tool_photo'){
                        var toolphotocount = parseInt($('.inventory_tool_photo_count').html())+1;
                        $('.inventory_tool_photo_count').html(toolphotocount);
                    }
                    if(fldid == 'inventory_tool_files'){
                        var toolfilecount = parseInt($('.inventory_tool_file_count').html())+1;
                        $('.inventory_tool_file_count').html(toolfilecount);
                    }
                } else {
                    //$('#'+tableid).html('<tr><td colspan="5"><span style="color:red;">'+obj.message+'</span></td></tr>');
                    alert(obj.message);
                }
            }
        });
    }
}

$(document).on('click', '.inv-tool-prev-btn, .inv-tool-next-btn', function(e){
    var clickbtn = $(this).attr('data-val');
    
    var tool_id = $('#tool_id').val();

    if(tool_id != '' && tool_id != undefined){
        $.ajax({
            url: getInventoryToolNextPrevURL, 
            type: 'post',
            data: {clickbtn:clickbtn, tool_id:tool_id},
            dataType: "text",
            success: function (response) {
                if(response == 'Failed'){
                    alert('Something went wrong, please try again.');
                }else if(response != 'Empty'){
                    $('.inventory_tool_detail_block').html(response);
                    toolInfoBlockDate();
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    }
});

$(document).on('click', '.inventory-tool-delete-btn', function(e){
    var tool_id = $('#tool_id').val();
    if(tool_id != '' && tool_id != undefined){
        if(confirm('Are you sure want to delete this tool?')){
            $.ajax({
                url: deleteToolDetailURL, 
                type: 'post',
                data: {tool_id:tool_id},
                dataType: 'text',
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        alert('Tool detail deleted successfully.');
                        $('#inventoryToolDetailModel').modal('hide');
                        $('#inventoryToolsList').html(obj.toolshtml);
                    }
                }
            });
        }
    }
});