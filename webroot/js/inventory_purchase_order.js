$(document).ready(function() {
    var table = $('#invItemSearchTable');
    $('#partNumberId, #partNameId, #partTypeId, #partInStockId')
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
    
    
    var vars = [], hash;
    if(window.location.search != ''){
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        if(hashes.length > '0'){
            for(var i = 0; i < hashes.length; i++)
            {
                hash = hashes[i].split('=');
                vars.push(hash[1]);
                vars[hash[0]] = hash[1];
            }
            //console.log(vars['inventoryitemid']);
            var dataTable = $('#datatableListingPage').DataTable();
            dataTable.columns(3).search(vars['inventoryitemid']).draw();
        }
    }

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

    $('#frmInventoryAddress').validate({ // initialize the plugin
        rules: {
            part_number: {
                required: true,
            },
            serial_number: {
                required: true,
            },
            description: {
                required: true,
            },
            part_classification: {
                required: true,
            },
            lot_number: {
                required: true,
            }
        }
    });

    $("form#frmInventoryPurchaseOrders #po_type, #po_number, #po_date, #po_currency, #bill_to_address").on("keyup change", function(){
        disableEnableSaveInvPOBtn();
    });

    disableEnableSaveInvPOBtn();

    //disableEnableSaveInvPORecBtn();

    $(".invPOSaveBtn").click(function(){
        $("form#frmInventoryPurchaseOrders :disabled").removeAttr('disabled');
        $('form#frmInventoryPurchaseOrders').submit();
    });

    $(".invaddresssave").click(function(){
        $('form#frmInventoryAddress').submit();
    });

    $("#po-receive-receive-button").click(function(){
        $('form#frmInventoryPOReceive').submit();
    });
    
    $(".changeinvpurchaseorders").click(function(e){
        
        var msg = '';
        if($(this).attr('po_status') == '3'){
            var requestnumber = $(this).attr('request-number');
            msg = 'Are you sure you want to cancel inventory request '+requestnumber;
        }else if($(this).attr('status-val') == '0'){
            msg = 'This record will no longer be displayed in the system. Confirm deactivation?';
        }else if($(this).attr('status-val') == '1'){
            msg = 'This record will be reactivated. Confirm activation?';
        }else if($(this).attr('po_status') == '1'){
            msg = 'By confirming, the Purchase Order will be changed to an opened state.\n Keep in mind that if you edit the Purchase Order and it has no open or partially-open line items, the Purchase Order will close again when saved.';
        }

        var status = $(this).attr('status-val') != undefined ? $(this).attr('status-val') : '';
        var po_status = $(this).attr('po_status') != undefined ? $(this).attr('po_status') : '';
        
        var po_id = window.location.pathname.split('/').pop();
        var url = updatePurchaseOrderStatusURL;

        $("#actionForm").attr("action",url);
        $("#actionForm").append("<input type='hidden' name='id' value='"+po_id+"'/>");
        $("#actionForm").append("<input type='hidden' name='status' value='"+status+"'/>");
        $("#actionForm").append("<input type='hidden' name='po_status' value='"+po_status+"'/>");

        if($(this).attr('po_status') == '2'){
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

function disableEnableSaveInvPOBtn(){
    var errors = 0;
    $("form#frmInventoryPurchaseOrders #po_type, #po_number, #po_date, #po_currency, #bill_to_address").map(function(){
        if( !$(this).val() ) {
            errors++;
        } 
    });
    if(errors > 0){
        $(".invPOSaveBtn").attr("disabled", "disabled");
    }else{
        $(".invPOSaveBtn").removeAttr("disabled");
    }
}

$(document).on("keyup change onblur", "form#frmInventoryPOReceive .inventory_item_id, .location_id", function(){
    disableEnableSaveInvPORecBtn();
});

function disableEnableSaveInvPORecBtn(){
    var errors = 0;
    var invitemcount = $('form#frmInventoryPOReceive .inventory_item_id').length;
    var invlocationcount = $('form#frmInventoryPOReceive .location_id').length;

    $('form#frmInventoryPOReceive .inventory_item_id').map(function(){
        if (!$(this).val()) {
            errors++;
        }
    });
    errors = errors-(invitemcount/2);
    

    $('form#frmInventoryPOReceive .location_id').map(function(){
        if (!$(this).val()) {
            errors++;
        }
    });
    errors = errors-(invlocationcount/2);

    /*$('form#frmInventoryPOReceive .serial_no').map(function(){
        if (!$(this).val()) {
            errors++;
        }
    });*/
    
    if(errors > 0){
        $("#po-receive-receive-button").attr("disabled", "disabled");
    }else{
        $("#po-receive-receive-button").removeAttr("disabled");
    }
}

$(document).on('click', '.invpotable tbody tr', function (e) {
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".chkBoxCls").val();
    if(values != undefined){
        window.location.href = inventoryPurchaseOrderDetailsURL+'/'+values;
    }
});

$(".addinvpoitem").click(function(e){
    if($('#po_type').find(":selected").val() != ''){
        var invtype = $(this).attr('data-val');
        var currency = $("#po_currency").val();
        $.ajax({
            url: getInventoryPOItemDropdownURL, 
            type: 'post',
            data: {'invtype':invtype, 'currency':currency},
            dataType: 'text',
            success: function (response) {
                $('#invrequeststbl').before(response);
                $('.selectpicker').selectpicker('refresh');

                $('.etaDatepicker').datetimepicker({
                    format: 'MM-DD-YYYY'
                });

                $(".invpocount").each(function (index, element) {
                    $(this).html(parseInt(index)+1);
                });
            }
        });

        $('#po_type').attr('disabled', 'disabled');
        $('#po_type').selectpicker('refresh');
    }
});

// To show list of cities on change of country dropdown
$(document).on('change', '#addresscountry', function (e) {
    var countryId = $( this ).val();
    $('#addressstate').find('option:not(:first)').remove();
    $("#addressprovince").val('');
    if (countryId == '231') {
        $(".addressprovinceblock").css('display', 'none');
        $(".addressstateblock").css('display', '');
        $.ajax({
            type: "POST",
            url: getStatesList,
            data: {countryId:countryId},
            async : true,
            success: function(response) {
                if (response != '') {
                    $('#addressstate').append(response);
                }
                $('#addressstate').selectpicker('refresh');
            }                   
        });
    } else if(countryId != '231' || countryId == ''){
        $(".addressprovinceblock").css('display', '');
        $(".addressstateblock").css('display', 'none');

        $('#addressstate').selectpicker('refresh');
    }
});

$(document).on('click', ".invaddresssavebtn", function (e) {
    $("form#frmAddAddress :disabled").removeAttr('disabled');
    var data = $('form#frmAddAddress').serialize();
    var pagesource = $(this).attr('data-val');

    $.ajax({
        url: saveInventoryAddressURL, 
        type: 'post',
        data: data,
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                var invaddresses = obj.invaddresses;
                if(pagesource == 'shipping'){
                    $('#to_address').append('<option value="'+invaddresses.id+'" selected>'+invaddresses.name+'</option>');
                    $('#from_address').append('<option value="'+invaddresses.id+'" selected>'+invaddresses.name+'</option>');
                }else{
                    if(invaddresses.is_billing_address == '1'){
                        $('#bill_to_address').append('<option value="'+invaddresses.id+'" selected>'+invaddresses.name+'</option>');
                    }
                    if(invaddresses.is_shipping_address == '1'){
                        $('#ship_to_address').append('<option value="'+invaddresses.id+'" selected>'+invaddresses.name+'</option>');
                    }
                }
                
                $('.selectpicker').selectpicker('refresh');
                
                $("#addressAddModel").modal('hide');
                $('#frmAddAddress')[0].reset();
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.potaxcal', function(e){
    var clkbtn = $(this).val();
    
    $("#po-tax-percentage").removeAttr('disabled');
    $("#po-tax-amount").removeAttr('disabled');

    if(clkbtn == '0'){
        $("#po-tax-percentage").attr('disabled', true);
    }else{
        $("#po-tax-amount").attr('disabled', true);
    }
})

$(document).on('keyup click', '.po_cost, .po_qty', function(e){
    var qty = $(this).parent().parent().find(".po_qty").val();
    var cost = $(this).parent().parent().find(".po_cost").val();
    var totalamount = qty*cost;
    totalamount = parseFloat(totalamount).toFixed(2);
    $(this).parent().parent().find(".porowtotal").text(totalamount);
    
    calculatePOTotalAmount()
})

$(document).on('keyup click', '#po-tax-percentage, #po-tax-amount, #po-shipping', function(e){
    calculatePOTotalAmount();
});

function calculatePOTotalAmount(){
    var totalamount = 0;
    $('.porowtotal').each(function (index, element) {
        totalamount = parseFloat(totalamount) + parseFloat($(element).text());
    });

    if(!isNaN(totalamount)){
        $('.posubtotal').text(totalamount);
    }
    
    var taxcalval = 0;
    
    if($("input[type='radio'].potaxcal:checked").val() == '0'){
        var percentagecal = parseFloat(totalamount)/100;
        percentagecal = parseFloat($("#po-tax-amount").val())/percentagecal;
        percentagecal = percentagecal.toFixed(3);
        $('#po-tax-percentage').val(percentagecal);
    }else if($("input[type='radio'].potaxcal:checked").val() == '1'){
        var percentagecal = totalamount*parseFloat($("#po-tax-percentage").val())/100;
        $('#po-tax-amount').val(percentagecal);
    }

    if($("#po-tax-amount").val() != ''){
        taxcalval = $("#po-tax-amount").val();
        taxcalval = parseFloat(taxcalval).toFixed(3);
    }
    
    if($("#po-shipping").val() != ''){
        taxcalval = parseFloat(taxcalval)+parseFloat($("#po-shipping").val());
    }
    
    var totalpoamount = 0;
    if(!isNaN(totalamount)){
        totalpoamount += parseFloat(totalamount);
    }
    if(!isNaN(taxcalval)){
        totalpoamount += parseFloat(taxcalval);
    }
    totalpoamount = totalpoamount.toFixed(2);
    $('.totalpoamount').text(totalpoamount);
}

$(document).on('change', '#po_currency', function (e) {
    var currencytxt = $('#po_currency').find(":selected").text();
    $(".pocurrency").text(currencytxt);
});

$(document).on('change', '#po_type', function (e) {
    var po_type = $('#po_type').find(":selected").val();
    $(".addinvpoitem").attr('disabled', 'disabled');
    if(po_type != ''){
        $(".addinvpoitem").removeAttr('disabled');
    }
});

$(document).on('click', '.receivedbtnclick', function(e){
    $(this).parent().parent().find(".receivedinput").css('display', 'block');
    $(this).parent().parent().find(".receivedtxt").css('display', 'none');
    $(this).addClass('receivedsavebtn');
    $(this).html('Save');
    $(this).removeClass('receivedbtnclick');
})

$(document).on('click', '.receivedsavebtn', function(e){
    var inventory_po_item_id = $(this).attr('data-val');
    if(inventory_po_item_id != '' && inventory_po_item_id != undefined){
        var received = $(this).parent().parent().find(".received").val();
        $(this).attr('disabled', 'true');
        $(this).html('Close');
        $(this).removeClass('receivedsavebtn');
        var inventory_po_id = window.location.pathname.split('/').pop();
        var event = $(this).parent().parent();
        $.ajax({
            url: saveInventoryReceivedURL, 
            type: 'post',
            data: {'inventory_po_item_id':inventory_po_item_id, 'received':received, 'inventory_po_id':inventory_po_id},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    var invreceived = obj.invreceived;
                    event.find(".receivedinput").remove();
                    event.find(".receivedtxt").css('display', 'block');
                    event.find(".receivedtxt").text(invreceived.received);

                    location.reload();
                }else{
                    alert(obj.message);
                }
            }
        });
    }
})

$(document).on('click', '.detshowbtn', function(e){
    $(".vendordispblock").css('display', 'none');
    $(".addressdispblock").css('display', 'none');

    $(".fa-long-arrow-right").remove();

    $(this).append('<i class="fa fa-long-arrow-right"></i>');

    if($(this).attr('data-val') == 'vendor'){
        var vendors = JSON.parse(vendorobj);
        
        $("#company").text(vendors.name);
        $("#contact").text(vendors.firstname);
        $("#street1").text(vendors.street1);
        $("#street2").text(vendors.street2);
        $("#city_state").text(vendors.city);
        $("#postal").text(vendors.postal);
        $("#phone").text(vendors.primaryphone);
        $("#fax").text(vendors.fax);

        $(".vendordispblock").css('display', 'block');
    }else if($(this).attr('data-val') == 'billing'){
        var billingaddress = JSON.parse(billingaddressobj);
        $("#name").text(billingaddress.name);
        $("#street1").text(billingaddress.street1);
        $("#street2").text(billingaddress.street2);
        $("#city_state").text(billingaddress.city);
        $("#postal").text(billingaddress.postal);

        $(".addressdispblock").css('display', 'block');
    }else if($(this).attr('data-val') == 'shipping'){
        var shippingaddress = JSON.parse(shippingaddressobj);
        $("#name").text(shippingaddress.name);
        $("#street1").text(shippingaddress.street1);
        $("#street2").text(shippingaddress.street2);
        $("#city_state").text(shippingaddress.city);
        $("#postal").text(shippingaddress.postal);

        $(".addressdispblock").css('display', 'block');
    }
})

$(document).on("click", ".collapse-tr", function() {
    $(this).parent().find(".invpoitemrec").toggleClass( "fa-caret-right").toggleClass("fa-caret-down");
    //$(this).find(".invpoitemrec").toggleClass( "fa-caret-down");
    var invitmid = $(this).closest('td').find('.poitemid').val();
    if($('.overview-detail-'+invitmid).css('display') == 'none'){
        $('.overview-detail-'+invitmid).css('display', '');
    }else{
        $('.overview-detail-'+invitmid).css('display', 'none');
    }
    //$(this).closest('tr').find('.overview-detail-'+invitmid).css('display', '');
});

$(document).on('keyup click', '.quantity', function(){
    var totalfields = $('input[id^="quantity-"]').length;
    var quantity = 0;
    $('.quantity').each(function(){
        if($(this).val() > 0){
            quantity ++;
        }
    });
    $("#po-receive-receive-button").attr('disabled', 'disabled');
    if(totalfields == quantity){
        $("#po-receive-continue-button").removeAttr('disabled');
        $("#po-receive-next-button").removeAttr('disabled');
        $("#po-receive-right-arrow-button").removeAttr('disabled');
    }else{
        $("#po-receive-continue-button").attr('disabled', 'disabled');
        $("#po-receive-next-button").attr('disabled', 'disabled');
        $("#po-receive-right-arrow-button").attr('disabled', 'disabled');
    }
})
var counter = 0;
$(document).on("click", "#po-receive-continue-button, #po-receive-next-button, #po-receive-right-arrow-button, #po-receive-previous-button, #po-receive-left-arrow-button", function() {
    var quantity = 0;
    $('.quantity').each(function(){
        quantity += parseFloat($(this).val());
    });

    $('div[id^="tab-"]').css('display', 'none');

    $("#po-receive-continue-button").attr('disabled', 'disabled');
    $("#po-receive-next-button").attr('disabled', 'disabled');
    $("#po-receive-right-arrow-button").attr('disabled', 'disabled');
    $("#po-receive-left-arrow-button").attr('disabled', 'disabled');
    $("#po-receive-previous-button").attr('disabled', 'disabled');
    $("#po-receive-receive-button").attr('disabled', 'disabled');

    var clickbtn = $(this).attr('data-val');
    if($('div[id^="tab-"]').length != parseInt(quantity)+1){
        $('.addPartBorder').not(':first').remove();

        var totalfields = $('input[id^="quantity-"]').length;
        var cnt = 1;
        var start = 1;
        for(var i=0; i < parseInt(totalfields); i++){
            var total_qty = $('#quantity-'+i).val();
            var qtyfldarr = $('#quantity-'+i).attr('data-val');
            qtyfldarr = qtyfldarr.split('-');
            var inventory_po_id = qtyfldarr[0];
            var po_item_id = qtyfldarr[1];
            
            start = i=='0' ? 1 : parseInt(start);

            $.ajax({
                url: inventoryPOReceivedBlockURL, 
                type: 'post',
                data: {'inventory_po_id':inventory_po_id, 'po_item_id':po_item_id, 'quantity':total_qty, 'start':start},
                dataType: 'text',
                success: function (response) {
                    $(".invreceievedblock").append(response);
                    $('.selectpicker').selectpicker('refresh');
                    
                    $('.warranty_expire, .expiration').datetimepicker({
                        format: 'MM-DD-YYYY'
                    });
                    //console.log(cnt+'--'+counter+'--'+clickbtn);
                    if(clickbtn == 'next' || clickbtn == 'countinue'){
                        $('div[id^="tab-1"]').css('display', 'block');
                        counter = 1;
                    }else if(cnt == parseInt(i) && clickbtn == 'last'){
                        $('div[id^="tab-'+quantity+'"]').css('display', 'block');
                        counter = quantity;
                    }
                    
                    cnt++;
                }
            });
            start += parseInt(total_qty);
        }
    }
    
    if($(this).attr('data-val') == 'first'){
        $('div[id^="tab-0"]').css('display', 'block');
        counter = 0;
    }else if($(this).attr('data-val') == 'last'){
        $('div[id^="tab-'+quantity+'"]').css('display', 'block');
        counter = quantity;
    }else if($(this).attr('data-val') == 'prev'){
        counter--;
        $('div[id^="tab-'+counter+'"]').css('display', 'block');
    }else if($(this).attr('data-val') == 'next'){
        counter++;
        $('div[id^="tab-'+counter+'"]').css('display', 'block');
    }else{
        $('div[id^="tab-1"]').css('display', 'block');
        counter = 1;
    }
    
    if(counter > '0' && counter < quantity){
        $("#po-receive-next-button").removeAttr('disabled');
        $("#po-receive-right-arrow-button").removeAttr('disabled');
        $("#po-receive-left-arrow-button").removeAttr('disabled');
        $("#po-receive-previous-button").removeAttr('disabled');
    }else if(counter == quantity){
        $("#po-receive-left-arrow-button").removeAttr('disabled');
        $("#po-receive-previous-button").removeAttr('disabled');
    }else{
        $("#po-receive-next-button").removeAttr('disabled');
        $("#po-receive-right-arrow-button").removeAttr('disabled');
        $("#po-receive-continue-button").removeAttr('disabled');
    }

    
});

$(document).on('change', '.inventoryattachment', function(e){
    // Read selected files
    var fldid = $(this).attr("id");
    var dymcids = fldid.split('inventoryattachment');
    var totalfiles = document.getElementById(fldid).files.length;
    for (var index = 0; index < totalfiles; index++) {
        var form_data = new FormData();
        form_data.append(fldid, document.getElementById(fldid).files[index]);
        form_data.append('ids',fldid);

        $.ajax({
            url: bulkInvPORecAttachmentUploadURL, 
            type: 'post',
            data: form_data,
            contentType: false,
            processData: false,
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $("#noattachmenttr"+dymcids[1]).before(obj.tblrow);
                } else {
                    $('#filetbody'+dymcids[1]).html('<tr><td colspan="2"><span style="color:red;">'+obj.message+'</span></td></tr>');
                }
            }
        });
    }
});

$(document).on('change', '.inventory_item_id', function(e){
    var inventory_item_id = $(this).val();
    if(inventory_item_id != ''){
        var event = $(this).closest('tr');

        updateLineItemData(event, inventory_item_id, 'dropdown');
    }
});

function updateLineItemData(event, inventory_item_id, selectsource){
    $.ajax({
        url: getInventoryItemDetailURL, 
        type: 'post',
        data: {'inventory_item_id':inventory_item_id},
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                var inventoryitems = obj.inventoryitems;
                if(selectsource == ''){
                    event.find(".inventory_item_id").val(inventoryitems.id);
                }
                event.find(".invitem_default_uom").val(inventoryitems.default_uom);
                if(inventoryitems.unit_cost != ''){
                    event.find(".porowtotal").text(inventoryitems.unit_cost);
                    event.find(".po_cost").val(inventoryitems.unit_cost);
                    
                    calculatePOTotalAmount();
                }
                $('.selectpicker').selectpicker('refresh');
            }
        }
    });
}

$(document).on('click', '.adjustInvCost', function(e){
    if($(this).attr('data-val') != undefined && $(this).attr('data-val') != ''){
        $.ajax({
            url: getInvAjustCostDataURL, 
            type: 'post',
            data: {'inventory_po_item_id':$(this).attr('data-val')},
            dataType: 'text',
            success: function (response) {
                $('.invadjustcostdata').html(response);
                $("#adjustInvCostModel").modal('show');
            }
        });
    }
})

$(document).on('keyup', '#invadjustnewcost', function(e){
    calculateInvAdjustCost();
})

$(document).on('click', "#ckbCheckAllPopup", function () {
    $(".chkBoxClsPopup").prop('checked', $(this).prop('checked'));

    calculateInvAdjustCost();
});

$(document).on('click', ".chkBoxClsPopup", function () {
    var totalCheckboxes = $('input.chkBoxClsPopup:checkbox').length;
    var checkedcount = $('input.chkBoxClsPopup:checked').length;
    
    if(totalCheckboxes == checkedcount) {
        $('#ckbCheckAllPopup').prop('checked', true);
    } else {
        $('#ckbCheckAllPopup').prop('checked', false);
    }
    calculateInvAdjustCost();
});

function calculateInvAdjustCost(){
    var newadjustedcost = $("#invadjustnewcost").val();
    if(newadjustedcost != ''){
        newadjustedcost = parseFloat(newadjustedcost).toFixed(2);
        $("input.chkBoxClsPopup:not(:checked)").each(function() {
            var row = $(this).closest("tr");
            var currentcostval = row.find(".invcurrentcost").text();
            currentcostval = parseFloat(currentcostval).toFixed(2);
            row.find(".invadjustcost").text(currentcostval);
            var adjustedcostval = row.find(".invadjustcost").text();
            var diff = parseFloat(adjustedcostval)-parseFloat(currentcostval);
            diff = parseFloat(diff).toFixed(2);
            row.find(".invadjustdiff").text(diff);
        });

        $("input.chkBoxClsPopup:checked").each(function() {
            var row = $(this).closest("tr");
            var currentcostval = row.find(".invcurrentcost").text();
            row.find(".invadjustcost").text(newadjustedcost);
            var adjustedcostval = row.find(".invadjustcost").text();
            var diff = parseFloat(adjustedcostval)-parseFloat(currentcostval);
            diff = parseFloat(diff).toFixed(2);
            row.find(".invadjustdiff").text(diff);
        });

        var checkboxlength = $("input.chkBoxClsPopup:checked").length;
        if(checkboxlength > '0'){
            $(".invAdjustConfirmBtn").removeAttr('disabled');
        }else{
            $(".invAdjustConfirmBtn").attr('disabled', 'disabled');
        }
    }
}

$(document).on('click', '.invAdjustConfirmBtn', function(e){
    $("form#frmInventoryAdjustCost").submit();
});

$(document).on('click', ".downloadLinePOItems", function (e) {
    var url = invExportLineItemsURL;
    //window.open(url);
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

$(document).on('click', '.updateexchangestatusbtn', function(e){
    $("form#frmInventoryPOExchangeStatus").submit();
})

$(document).on('keyup', '#poSearchItem', function(e){
    searchApplyPOFilter();
});

$(document).on('click', '.applyfilterPObtn', function(e){
    localStorage.setItem('applyfilter', '1');
    searchApplyPOFilter();
    $('#popupFilterModel').modal('hide');
});
$(document).on('click', '.clearapplyPOfilter', function(e){
    localStorage.setItem('applyfilter', '0');
    $("#formPopupSearch")[0].reset();
    $("#formPurchaseOrderFilter")[0].reset();
    $('.selectpicker').selectpicker('refresh');
    searchApplyPOFilter();
})
function searchApplyPOFilter(){
    var formdata = $("#formPurchaseOrderFilter, #formPopupSearch").serialize();
    var dataTable = $('#datatableListingPage').DataTable();
    dataTable.columns(1).search(formdata).draw();
}

//export line item in excel format
$(document).on('click', ".exportPOLineItemExcel", function (e) {
    var formdata = $("#formPurchaseOrderFilter, #formPopupSearch").serialize();
    window.open(exportLineItemsExcelURL+formdata+'&applyfilter='+localStorage.getItem('applyfilter'));
});

//export listing data in excel format
$(document).on('click', ".exportPOListingDataExcel", function (e) {
    var formdata = $("#formPurchaseOrderFilter, #formPopupSearch").serialize();
    window.open(exportListingDataExcelURL+formdata+'&applyfilter='+localStorage.getItem('applyfilter'));
});

var eventvar;
$(document).on('click', ".searchInventoryItemPopup", function (e) {
    eventvar = $(this).closest('tr');

    $("#searchInventoryItemModel").modal('show');
});

$(document).on("keyup", "#invItmSearchItem", function() {
    var value = $(this).val().toLowerCase();
    $("#invItemSearchList tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        $('.overview-detail').css('display','none');
    });
});

$(document).on('dblclick', '#invItemSearchList tr', function(e){
    var inventory_item_id = $(this).attr('data-val');
    if(inventory_item_id != ''){
        updateLineItemData(eventvar, inventory_item_id, '');
        $("#searchInventoryItemModel").modal('hide');
    }
});