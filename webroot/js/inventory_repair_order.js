$(document).ready(function() {
    $("form#frmInventoryRepairOrders #vendor, #ro_number, #ro_date, #ship-via, #requestor, #po_currency, #bill_to_address, #ship_to_address").on("keyup change", function(){
        disableEnableSaveInvROBtn();
    });
    
    setInterval(function() {
        disableEnableSaveInvROBtn();
    }, 1000);

    disableEnableSaveInvRORecBtn();

    $(".changeinvrepairorders").click(function(e){
        
        var msg = '';
        if($(this).attr('ro_status') == '3'){
            var requestnumber = $(this).attr('request-number');
            msg = 'Are you sure you want to cancel inventory request '+requestnumber;
        }else if($(this).attr('status-val') == '2'){
            msg = 'This record will no longer be displayed in the system. Confirm deactivation?';
        }else if($(this).attr('status-val') == '1'){
            msg = 'This record will be reactivated. Confirm activation?';
        }else if($(this).attr('ro_status') == '1'){
            msg = 'By confirming, the Repair Order will be changed to an opened state.\n Keep in mind that if you edit the Purchase Order and it has no open or partially-open line items, the Purchase Order will close again when saved.';
        }

        var status = $(this).attr('status-val') != undefined ? $(this).attr('status-val') : '';
        var ro_status = $(this).attr('ro_status') != undefined ? $(this).attr('ro_status') : '';
        
        var ro_id = window.location.pathname.split('/').pop();
        var url = updateRepairOrderStatusURL;

        $("#actionForm").attr("action",url);
        $("#actionForm").append("<input type='hidden' name='id' value='"+ro_id+"'/>");
        $("#actionForm").append("<input type='hidden' name='status' value='"+status+"'/>");
        $("#actionForm").append("<input type='hidden' name='ro_status' value='"+ro_status+"'/>");

        if($(this).attr('ro_status') == '2'){
            if($(this).attr('data-vendor') == '1'){
                $("#actionForm").submit();
            }else{ 
                alert("Vendor can not be blank");
            }
            
        }else{
            if(confirm(msg)){
                $("#actionForm").submit();
            }
        }
    });

});

function disableEnableSaveInvROBtn(){
    var errors = 0;
    $("form#frmInventoryPurchaseOrders #vendor, #ro_number, #ro_date, #ship-via, #requestor, #po_currency, #bill_to_address, #ship_to_address").map(function(){
        if( !$(this).val() ) {
            errors++;
        } 
    });
    if(errors > 0){
        $(".invROSaveBtn").attr("disabled", "disabled");
    }else{
        $(".invROSaveBtn").removeAttr("disabled");
    }
}

$(document).on("keyup change onblur", "form#frmInventoryROReceive .inventory_item_id, .location_id, .status, .qty, .currency", function(){
    disableEnableSaveInvRORecBtn();
});

function disableEnableSaveInvRORecBtn(){
    var errors = 0;
    var invitemcount = $('form#frmInventoryROReceive .inventory_item_id').length;
    var invlocationcount = $('form#frmInventoryROReceive .location_id').length;
    var invstatuscount = $('form#frmInventoryROReceive .status').length;
    var invcurrencycount = $('form#frmInventoryROReceive .currency').length;

    $('form#frmInventoryROReceive .inventory_item_id').map(function(){
        if (!$(this).val()) {
            errors++;
        }
    });
    errors = errors-(invitemcount/2);
    

    $('form#frmInventoryROReceive .location_id').map(function(){
        if (!$(this).val()) {
            errors++;
        }
    });
    errors = errors-(invlocationcount/2);

    $('form#frmInventoryROReceive .status').map(function(){
        if (!$(this).val()) {
            errors++;
        }
    });
    errors = errors-(invstatuscount/2);

    $('form#frmInventoryROReceive .currency').map(function(){
        if (!$(this).val()) {
            errors++;
        }
    });
    errors = errors-(invcurrencycount/2);

    $('form#frmInventoryROReceive .qty').map(function(){
        if ($(this).val() == '' || $(this).val() == '0') {
            errors++;
        }
    });
    
    if(errors > 0){
        $("#ro-receive-button").attr("disabled", "disabled");
    }else{
        $("#ro-receive-button").removeAttr("disabled");
    }
}


$(document).on("click", ".invROSaveBtn", function(){
    $("form#frmInventoryRepairOrders :disabled").removeAttr('disabled');
    $('form#frmInventoryRepairOrders').submit();
});

$(document).on("click", "#ro-receive-button", function(){
    $('form#frmInventoryROReceive').submit();
});

$(document).on('keyup', '#rec_qty, #cost', function(e){
    var qty = $("#rec_qty").val();
    var cost = $("#cost").val();

    var totalcost = parseInt(qty)*parseFloat(cost);
    $("#total-cost").val(totalcost);
    $("#quantity").val(qty);
});

var tabcount = 1;
$(document).on("click", "#ro-receive-next-button, #ro-receive-right-arrow-button, #ro-receive-previous-button, #ro-receive-left-arrow-button", function() {
    var quantity = $('div[id^="tab-"]').length;
    $('div[id^="tab-"]').css('display', 'none');

    $("#ro-receive-next-button").attr('disabled', 'disabled');
    $("#ro-receive-right-arrow-button").attr('disabled', 'disabled');
    $("#ro-receive-left-arrow-button").attr('disabled', 'disabled');
    $("#ro-receive-previous-button").attr('disabled', 'disabled');

    if($(this).attr('data-val') == 'first'){
        $('div[id^="tab-1"]').css('display', 'block');
        tabcount = 1;
    }else if($(this).attr('data-val') == 'last'){
        $('div[id^="tab-'+quantity+'"]').css('display', 'block');
        tabcount = quantity;
    }else if($(this).attr('data-val') == 'prev'){
        tabcount--;
        $('div[id^="tab-'+tabcount+'"]').css('display', 'block');
    }else if($(this).attr('data-val') == 'next'){
        tabcount++;
        $('div[id^="tab-'+tabcount+'"]').css('display', 'block');
    }

    //console.log("tabcount val--"+tabcount+' qty--'+quantity);
    
    if(tabcount == quantity){
        $("#ro-receive-left-arrow-button").removeAttr('disabled');
        $("#ro-receive-previous-button").removeAttr('disabled');
    }else if(tabcount == '1'){
        $("#ro-receive-next-button").removeAttr('disabled');
        $("#ro-receive-right-arrow-button").removeAttr('disabled');
    }else{
        $("#ro-receive-left-arrow-button").removeAttr('disabled');
        $("#ro-receive-previous-button").removeAttr('disabled');
        $("#ro-receive-next-button").removeAttr('disabled');
        $("#ro-receive-right-arrow-button").removeAttr('disabled');
    }
});

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