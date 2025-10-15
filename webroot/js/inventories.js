$(document).ready(function() {
    //Sorting script
    var installedCompTable = $('#tblInstalledComponents');
    $('#instlCompitemName, #instlComppartNumber, #instlCompSerialLot, #instlCompdisplayName, #instlCompqty, #instlComplocation')
        .wrapInner('<span title="sort this column"/>')
        .each(function() {
            var th = $(this),
                thIndex = th.index(),
                inverse = false;
            th.click(function() {
                installedCompTable.find('td.collapse-tr').filter(function() {
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

    //Search
    $(".installedSearchItem").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#installedComponentsList tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            $('.overview-detail').css('display','none');
        });
    });

    //transaction history tab sorting code
    var transactionHistoryTable = $('#tblTransactionHistory');
    $('#transactionDate, #transactionUser, #transactionType, #transactionFrom, #transactionTo, #transactionQty')
        .wrapInner('<span title="sort this column"/>')
        .each(function() {
            var th = $(this),
                thIndex = th.index(),
                inverse = false;
            th.click(function() {
                transactionHistoryTable.find('td.collapse-tr').filter(function() {
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

    $("form#frmInvenotry #location_id, #serial_no, #inventory_qty").on("keyup change", function(){
        disableEnableInventorySaveBtn();
    });

    disableEnableInventorySaveBtn();

});

function disableEnableInventorySaveBtn(){
    var errors = 0;
    
    if(is_this_item_serialized == '1'){
        $("form#frmInvenotry #location_id, #serial_no, #inventory_qty").map(function(){
            if( !$.trim($(this).val()) ) {
                errors++;
            } 
        });
    }else{
        $("form#frmInvenotry #location_id, #inventory_qty").map(function(){
            if( !$.trim($(this).val()) ) {
                errors++;
            } 
        });
    }
    
    if(errors > 0){
        $(".inventorysavebtn").attr("disabled", "disabled");
    }else{
        $(".inventorysavebtn").removeAttr("disabled");
    }
}

$(document).on('click', ".vendorsavebtn", function (e) {
    var data = $('form#frmAddVendor').serialize();
    $.ajax({
        url: saveInventoryVendorURL, 
        type: 'post',
        data: data,
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                var invvendor = obj.invvendor;
                $('#vendor').append('<option value="'+invvendor.id+'" selected>'+invvendor.name+'</option>');
                $('.selectpicker').selectpicker('refresh');
                
                $("#vendorAddModel").modal('hide');
                $('#frmAddVendor')[0].reset();
            }else{
                alert(obj.message);
            }
        }
    });
});

//error discard
$(document).on('click', '.inventoriesconfirmdiscard', function (e) {
    var qty = $.trim($('#qty').val());

    if (qty === '' || isNaN(qty) || parseFloat(qty) <= 0) {
        alert("Enter a valid quantity greater than zero.");
    } else {
        $('form#frmInventoriesDiscard').submit();
    }

});

//error correct
$(document).on('change', '#error_correct_status', function(e){
    var status = $(this).val();
    $("#errorquantityblock").css('display', 'none');
    $("#errorlocationblock").css('display', 'none');
    $("#error_correct_location_id").val('');
    if(status == '1' || status == '5' || status == '13' || status == '12' || status == '6' || status == '8'){
        $("#errorlocationblock").css('display', 'block');
    }
    if(status == '1' || status == '5' || status == '14' || status == '13' || status == '12' || status == '6' || status == '8'){
        $("#errorquantityblock").css('display', 'block');
    }
    $('.selectpicker').selectpicker('refresh');
    
});

$(document).on("keyup change", "form#frmInventoriesErrorCorrect #error_correct_status, #error_correct_qty, #error_correct_location_id", function(){
    disableEnableSaveErrorCorrectBtn();
});

function disableEnableSaveErrorCorrectBtn(){
    var errors = 0;
    var status = $('#error_correct_status').val();
    if(status == '1' || status == '5' || status == '14' || status == '13' || status == '12' || status == '6' || status == '8'){
        $("form#frmInventoriesErrorCorrect #error_correct_qty").map(function(){
            if( !$(this).val() ) {
                errors++;
            }
        });
    }
    if(status == '1' || status == '5' || status == '13' || status == '12' || status == '6' || status == '8'){
        $("form#frmInventoriesErrorCorrect #error_correct_location_id").map(function(){
            if( !$(this).val() ) {
                errors++;
            }
        });
    }
    
    if(errors > 0){
        $(".inventorieserrorcorrectsave").attr("disabled", "disabled");
    }else{
        $(".inventorieserrorcorrectsave").removeAttr("disabled");
    }
}

$(document).on('click', '.inventorieserrorcorrectsave', function (e) {
    $('form#frmInventoriesErrorCorrect').submit();
});

//adjust
$(document).on("keyup change", "form#frmInventoriesAdjust #adjust_qty, #discard_reason", function(){
    disableEnableSaveAdjustBtn();
});

function disableEnableSaveAdjustBtn(){
    var errors = 0;
    $("form#frmInventoriesAdjust #adjust_qty, #discard_reason").map(function(){
        if( !$(this).val() ) {
            errors++;
        }
    });
    
    if(errors > 0){
        $(".inventoriesadjustsave").attr("disabled", "disabled");
    }else{
        $(".inventoriesadjustsave").removeAttr("disabled");
    }
}

$(document).on('click', '.inventoriesadjustsave', function (e) {
    $('form#frmInventoriesAdjust').submit();
});

//transfer
$(document).on("keyup change", "form#frmInventoriesTransfer #transfer_qty, #transfer_location", function(){
    disableEnableSaveTransferBtn();
});

function disableEnableSaveTransferBtn(){
    var errors = 0;
    $("form#frmInventoriesTransfer #transfer_qty, #transfer_location").map(function(){
        if( !$.trim($(this).val()) ) {
            errors++;
        }
    });
    
    if(errors > 0){
        $(".inventoriestransfersave").attr("disabled", "disabled");
    }else{
        $(".inventoriestransfersave").removeAttr("disabled");
    }
}

$(document).on('click', '.inventoriestransfersave', function (e) {
    $('form#frmInventoriesTransfer').submit();
});

//install
$(document).on("keyup change", "form#frmInventoriesInstall #install_to, #qty", function(){
    disableEnableSaveInstallBtn();
});

function disableEnableSaveInstallBtn(){
    
    var errors = 0;
    $("form#frmInventoriesInstall #install_to, #qty").map(function(){
        if( !$(this).val() ) {
            errors++;
        }
    });
    
    if(errors > 0){
        $(".inventoriesinstallsave").attr("disabled", "disabled");
    }else{
        $(".inventoriesinstallsave").removeAttr("disabled");
    }
}

$(document).on('click', '.inventoriesinstallsave', function (e) {
    $('form#frmInventoriesInstall').submit();
});

//uninstall
$(document).on("keyup change", "form#frmInventoriesUnInstall #location_id, #qty, #status", function(){
    disableEnableSaveUnInstallBtn();
});

function disableEnableSaveUnInstallBtn(){
    var errors = 0;
    $("form#frmInventoriesUnInstall #location_id, #qty, #status").map(function(){
        if( !$(this).val() ) {
            errors++;
        }
    });
    
    if(errors > 0){
        $(".inventoriesuninstallsave").attr("disabled", "disabled");
    }else{
        $(".inventoriesuninstallsave").removeAttr("disabled");
    }
}

$(document).on('click', '.inventoriesuninstallsave', function (e) {
    $('form#frmInventoriesUnInstall').submit();
});

//consume
$(document).on("keyup change", "form#frmInventoriesConsume #consume_qty", function(){
    disableEnableSaveConsumeBtn();
});

function disableEnableSaveConsumeBtn(){
    var errors = 0;
    $("form#frmInventoriesConsume #consume_qty").map(function(){
        if( !$(this).val() ) {
            errors++;
        }
    });
    
    if(errors > 0){
        $(".inventoriesconsumesave").attr("disabled", "disabled");
    }else{
        $(".inventoriesconsumesave").removeAttr("disabled");
    }
}

$(document).on('click', '.inventoriesconsumesave', function (e) {
    $('form#frmInventoriesConsume').submit();
});

$(document).on('click', ".updateinventorystatus", function (e) {
    var status = $(this).attr('data-val');
    if(status != ''){
        var id = window.location.pathname.split('/').pop();

        $("#actionForm").attr("action",updateInventoriesStatusURL);
        $("#actionForm").append("<input type='hidden' name='id' value='"+id+"'/>");
        $("#actionForm").append("<input type='hidden' name='status' value='"+status+"'/>");

        $("#actionForm").submit();
    }
});

$(document).on('change', "#transfer_location_id", function (e) {
    var inventory_item_id = $('#inventory_item_id').val();
    var location_id = $('#transfer_location_id').val();
    if(location_id == ''){
        $('#currently_at_destination').text(0);
    }else{
        $.ajax({
            url: getInventoryLocationQtyCountURL, 
            type: 'post',
            data: {inventory_item_id:inventory_item_id, location_id:location_id},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    var qtycount = obj.qtycount;
                    $('#currently_at_destination').text(qtycount);
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('click', '#tblInstalledComponents tbody td', function (e) {
    if ($(this).index() == 0 ) {
        return;
    }
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".chkBoxCls").val();
    if(values != undefined){
        window.location.href = inventoriesDetPageURL+'/'+values;
    }
});

$(document).on('click', '.addInvHoldingToBox', function(e){
    var ids = [];
    $("input:checkbox[name=childcheckbox]:checked").each(function(){
        ids.push($(this).val());
    });

    if(ids.length > '0'){
        $.ajax({
            url: addQuantitiesToHoldingBoxAjaxURL, 
            type: 'post',
            data: {'ids':ids},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status =='failure'){
                    alert(obj.message);
                }else{
                    window.location.replace(window.location.href);
                }
            }
        });
    }
});