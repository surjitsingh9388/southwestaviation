$(document).ready(function() {

    disableEnableSaveInvSOBtn();
    
    $(".changeinvshippingorders").click(function(e){
        
        var msg = '';
        if($(this).attr('shipping_order_status') == '3'){
            var requestnumber = $(this).attr('request-number');
            msg = 'Are you sure you want to cancel inventory request '+requestnumber;
        }else if($(this).attr('status-val') == '2'){
            msg = 'This record will no longer be displayed in the system. Confirm deactivation?';
        }else if($(this).attr('status-val') == '1'){
            msg = 'This record will be reactivated. Confirm activation?';
        }else if($(this).attr('shipping_order_status') == '1'){
            msg = 'By confirming, the Shipping Order will be changed to an opened state.\n Keep in mind that if you edit the Purchase Order and it has no open or partially-open line items, the Purchase Order will close again when saved.';
        }

        var status = $(this).attr('status-val') != undefined ? $(this).attr('status-val') : '';
        var shipping_order_status = $(this).attr('shipping_order_status') != undefined ? $(this).attr('shipping_order_status') : '';
        
        var ro_id = window.location.pathname.split('/').pop();
        var url = updateShippingOrderStatusURL;

        $("#actionForm").attr("action",url);
        $("#actionForm").append("<input type='hidden' name='id' value='"+ro_id+"'/>");
        $("#actionForm").append("<input type='hidden' name='status' value='"+status+"'/>");
        $("#actionForm").append("<input type='hidden' name='shipping_order_status' value='"+shipping_order_status+"'/>");

        if($(this).attr('shipping_order_status') == '2'){
            $("#actionForm").submit();
        }else{
            if(confirm(msg)){
                $("#actionForm").submit();
            }
        }
    });

});

$(document).on("keyup change", "form#frmInventoryShippingOrders #shipping_order_number, #destination, #shipper, #ship-via, #requestor, #po_currency, #from_address, #to_address, #shipping_order_date, #attention, #vendor, #thirdpartydescription, #thirdpartyaddressstreet1, #thirdpartyaddresscity, #thirdpartyaddressprovince, #thirdpartyaddressstate, #thirdpartyaddresspostal, #thirdpartyaddresscountry", function(){
    disableEnableSaveInvSOBtn();
});

function disableEnableSaveInvSOBtn(){
    var errors = 0;
    $("form#frmInventoryShippingOrders #shipping_order_number, #destination, #shipper, #ship-via, #requestor, #po_currency, #from_address, #shipping_order_date, #attention").map(function(){
        if( !$(this).val() ) {
            errors++;
        } 
    });
    if($('#destination').val() == '1'){
        $("form#frmInventoryShippingOrders #to_address").map(function(){
            if( !$(this).val() ) {
                errors++;
            } 
        });
    }else if($('#destination').val() == '2'){
        $("form#frmInventoryShippingOrders #vendor").map(function(){
            if( !$(this).val() ) {
                errors++;
            } 
        });
    }else if($('#destination').val() == '3'){
        if($('#thirdpartyaddresscountry').val() == '231'){
            $("form#frmInventoryShippingOrders #thirdpartydescription, #thirdpartyaddressstreet1, #thirdpartyaddresscity, #thirdpartyaddressstate, #thirdpartyaddresspostal, #thirdpartyaddresscountry").map(function(){
                if( !$(this).val() ) {
                    errors++;
                }
            });
        }else if($('#thirdpartyaddresscountry').val() != '231' && $('#thirdpartyaddresscountry').val() != ''){
            $("form#frmInventoryShippingOrders #thirdpartydescription, #thirdpartyaddressstreet1, #thirdpartyaddresscity, #thirdpartyaddressprovince, #thirdpartyaddresspostal, #thirdpartyaddresscountry").map(function(){
                if( !$(this).val() ) {
                    errors++;
                }
            });
        }
    }
    if(errors > 0){
        $(".invShippingSaveBtn").attr("disabled", "disabled");
    }else{
        $(".invShippingSaveBtn").removeAttr("disabled");
    }
}

$(document).on("click", ".invShippingSaveBtn", function(){
    $("form#frmInventoryShippingOrders :disabled").removeAttr('disabled');
    $('form#frmInventoryShippingOrders').submit();
});

$(document).on("click", "#shipping-order-receive-button", function(){
    $(".location_id").removeAttr('disabled');
    $(".account_code").removeAttr('disabled');
    $(".notes").removeAttr('disabled');
    
    $('form#frmInventoryShippingOrderReceive').submit();
});

$(document).on('keyup', '.received', function(e){
    if($(this).val() > '0'){
        $(this).closest("tr").find(".location_id").removeAttr('disabled');
        $(this).closest("tr").find(".account_code").removeAttr('disabled');
        $(this).closest("tr").next('tr').find(".notes").removeAttr('disabled');
    }else{
        $(this).closest("tr").find(".location_id").attr('disabled', 'disabled');
        $(this).closest("tr").find(".account_code").attr('disabled', 'disabled');
        $(this).closest("tr").next('tr').find(".notes").attr('disabled', 'disabled');
    }
    $('.location_id, .account_code').selectpicker('refresh');
});

$(document).on("click", ".changerecaddressbtn", function() {
    $(this).css("display", 'none');
    if($(this).attr('data-val') == 'fromaddr'){
        $("#from_addressblock").css("display", 'block');
    }else{
        $("#to_addressblock").css("display", 'block');
    }
});

$(document).on("change", "#from_address, #to_address", function(e){
    if($(this).attr("data-val") != ''){
        var currentid = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: getInventoryAdressDetailURL,
            data: {'address_id':$(this).val()},
            async : true,
            success: function(response) {
                var resp = JSON.parse(response);
                if (resp.status == 'success') {
                    $('#'+currentid+'det').html(resp.addrdata);
                }                
            }                   
        });
    }
})

$(document).on('change', '#destination', function(e){
    $(".vendorblock").css('display', 'none');
    $(".toaddressblock").css('display', 'none');
    $(".thirdpartyblock").css('display', 'none');

    if($(this).val() == '1'){
        $(".toaddressblock").css('display', 'block');
    }else if($(this).val() == '2'){
        $(".vendorblock").css('display', 'block');
    }else if($(this).val() == '3'){
        $(".thirdpartyblock").css('display', 'block');
    }
});

// To show list of cities on change of country dropdown
$(document).on('change', '#thirdpartyaddresscountry', function (e) {
    var countryId = $( this ).val();
    $('#thirdpartyaddressstate').find('option:not(:first)').remove();
    $("#thirdpartyaddressprovince").val('');
    if (countryId == '231') {
        $(".thirdpartyaddressprovinceblock").css('display', 'none');
        $(".thirdpartyaddressstateblock").css('display', '');
        $.ajax({
            type: "POST",
            url: getStatesList,
            data: {countryId:countryId},
            async : true,
            success: function(response) {
                if (response != '') {
                    $('#thirdpartyaddressstate').append(response);
                }
                $('#thirdpartyaddressstate').selectpicker('refresh');
            }                   
        });
    } else if(countryId != '231' || countryId == ''){
        $(".thirdpartyaddressprovinceblock").css('display', '');
        $(".thirdpartyaddressstateblock").css('display', 'none');

        $('#thirdpartyaddressstate').selectpicker('refresh');
    }
});