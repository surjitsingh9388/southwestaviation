$(document).ready(function() { 
    $('#request_date').change(function(e){
        $("#request_date_end").attr('disabled',true);
        $("#request_date_end").val('');
        if($(this).val() == '2'){
            $("#request_date_end").attr('disabled',false);
        }
        $('.selectpicker').selectpicker('refresh');
    });

    $('#required_date').change(function(e){
        $("#required_date_end").attr('disabled',true);
        $("#required_date_end").val('');
        if($(this).val() == '2'){
            $("#required_date_end").attr('disabled',false);
        }
        $('.selectpicker').selectpicker('refresh');
    });
    
    $('#frmInventoryRequest').validate({ // initialize the plugin
        rules: {
            request_number: {
                required: true,
            },
            title: {
                required: true,
            },
            requested_by: {
                required: true,
            },
            need_by: {
                required: true,
            }
        }
    });

    $("form#frmInventoryRequest #request-number, #title, #requested-by, #need_by").on("keyup change blur", function(){
        disableEnableInvRequestSaveBtn();
    });

    disableEnableInvRequestSaveBtn();

    $(".invrequestitemsave").click(function(){
        $('form#frmInventoryRequest').submit();
    });
    
    $(".addinvitem").click(function(e){
        var invtype = $(this).attr('data-val');

        $.ajax({
            url: getInventoryItemDropdownURL, 
            type: 'post',
            data: {'invtype':invtype},
            dataType: 'text',
            success: function (response) {
                $('#invrequeststbl').before(response);
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });

    $(".changeinvrequests").click(function(e){
        var requestnumber = '';
        var msg = '';
        if($(this).attr('data-val') == '2'){
            msg = 'Are you sure you want to cancel inventory request '+requestnumber;
        }else if($(this).attr('data-val') == '4'){
            msg = 'Are you sure you want to close inventory request '+requestnumber;
        }else if($(this).attr('data-val') == '6'){
            msg = 'This record will no longer be displayed in the system. Confirm deactivation?';
        }else if($(this).attr('data-val') == '1'){
            msg = 'This record will be reactivated. Confirm activation?';
        }

        if(confirm(msg)){
            var request_status = $(this).attr('data-val');
            var request_id = window.location.pathname.split('/').pop();
            var url = updaterequeststatusURL;
            
            $("#actionForm").attr("action",url);
            $("#actionForm").append("<input type='hidden' name='id' value='"+request_id+"'/>");
            $("#actionForm").append("<input type='hidden' name='requeststatus' value='"+request_status+"'/>");
            $("#actionForm").submit();
        }
    });

});

function disableEnableInvRequestSaveBtn(){
    var errors = 0;
    $("form#frmInventoryRequest #request-number, #title, #requested-by, #need_by").map(function(){
        if( !$(this).val() ) {
            errors++;
        } 
    });
    if(errors > 0){
        $(".invrequestitemsave").attr("disabled", "disabled");
    }else{
        $(".invrequestitemsave").removeAttr("disabled");
    }
}

$(document).on('click', '.invreqtable tbody tr', function (e) {
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".chkBoxCls").val();
    if(values != undefined){
        window.location.href = inventoryRequestDetailsURL+'/'+values;
    }
});

$(document).on('click', ".invItemSaveBtn", function (e) {
    var data = $('form#frmItemCatalog').serialize();
    $.ajax({
        url: saveInventoryItemsURL, 
        type: 'post',
        data: data,
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                var invitems = obj.invitems;
                $('.inventory_item_id').append('<option value="'+invitems.id+'" selected>'+invitems.name+' ('+invitems.part_number+')</option>');
                $('.selectpicker').selectpicker('refresh');
                
                $("#inventoryItemModel").modal('hide');
                $('#frmItemCatalog')[0].reset();
            }else{
                alert(obj.message);
            }
        }
    });
});

$(".invrequestsapprovedeny").click(function(){
    $("#request_status").val($(this).attr('data-val'));
    $('form#frmInventoryApproveDenyRequest').submit();
});

$(document).on('click', '.request_item_approve, .request_item_deny', function(e){
    var request_item_status = $(this).attr('data-val');
    var request_item_id = window.location.pathname.split('/').pop();

    updateRequestItemStatus(request_item_id, request_item_status);
});

$(document).on('change', '.request_item_status', function(e){
    var request_item_status = $(this).val();
    var request_item_id = window.location.pathname.split('/').pop();
    updateRequestItemStatus(request_item_id, request_item_status);
});

function updateRequestItemStatus(request_item_id, request_item_status){
    if(request_item_status != '' && request_item_status != undefined){
        $.ajax({
            url: updateRequestItemStatusURL, 
            type: 'post',
            data: {request_item_id:request_item_id, request_item_status:request_item_status},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    alert(obj.message);
                    window.location.replace(window.location.href);
                }else{
                    alert(obj.message);
                }
            }
        });
    }
}