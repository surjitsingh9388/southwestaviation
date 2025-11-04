$(document).ready(function() {
    var currentUrl = window.location.href;

    // Define URLs where you want to hide the first column
    var hideColumnUrls = [
        'inventory_requests',
        'inventory_repair_orders',
        'inventory_purchase_orders',
        'inventory_shipping_orders'
    ];

    // Check if current URL matches any of those
    var shouldHideColumn = hideColumnUrls.some(function(url) {
        return currentUrl.includes(url);
    });

    var dataTable = $('#datatableListingPage').DataTable({
        'order': [$("#FilterBy").val(), 'asc'],
        columnDefs: [{
            "targets": [0],
            visible: !shouldHideColumn,
            orderable: false,
            className: "check noExl"
        }],
        //"searching": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[pagelimit, 50, 100, -1], [pagelimit, 50, 100, "All"]],
        "pageLength": pagelimit,
        "lengthChange": false,
        "dom": 'Bfrtip',
        "buttons": [
            {
                "extend": 'pdfHtml5',
                "text": 'Export Table',
                "message": '',
                "title": pdfPagTitle,
                "download": 'open',
                "exportOptions": {
                    "columns": ':visible',
                    modifier: {
                        page: 'current'
                    }
                },
                customize: function(doc) {
                    doc.content.forEach(function(item) {
                    /* if (item.table) {
                        item.table.widths = [40, '*','*'] 
                    } */
                    })
                }
            }
        ],
        "ajax":{
            url:ajaxListPageSearchURL,
            accepts: 'application/json', 
            type: "post",
            error: function(){
                $(".employees-grid-error").html("");
                $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employees-grid_processing").css("display","none");
            }
        },
        "drawCallback": function (response) {
            var respobj = response.json;
            if(respobj.totalcost != undefined){
                $("#totalCost").html('$'+respobj.totalcost);
            }
        },
    });
    
    $('#searchItem').bind("keyup", function(){
        searchApplyFilter();
    });

    $('#FilterBy').on('change', function(){
        dataTable.order([$(this).val(), 'asc']).draw();
    });

    $(document).on('click', ".resetfilterbtn", function (e) {
        localStorage.setItem('applyfilter', '0');
        $("#formPopupSearch")[0].reset();
        $('.selectpicker').selectpicker('refresh');

        searchApplyFilter();
    });

    $('[data-toggle="tooltip"]').tooltip({ 'placement': 'right'});

    $('.exporttoexcel').click(function(){
        var filenames = $(this).attr('data-val');
        $("#datatableListingPage").table2excel({
            name: "Backup file for HTML content",
            filename: filenames+".xls",
            exclude: ".noExl", 
            preserveColors: false 
        });
    });

    //manufacturer form validation

    $('#frmManufacturer').validate({ // initialize the plugin
        rules: {
            name: {
                required: true,
            }
        }
    });

    $("form#frmManufacturer #name").on("keyup change", function(){
        disableEnableManufacturerSaveBtn();
    });

    disableEnableManufacturerSaveBtn();

    //vendor form validation

    $('#frmAddVendor').validate({ // initialize the plugin
        rules: {
            name: {
                required: true,
            },
            street1: {
                required: true,
            },
            city: {
                required: true,
            }
            ,
            postal: {
                required: true,
            }
            ,
            country: {
                required: true,
            }
            ,
            province: {
                required: true,
            }
        }
    });

    $("form#frmAddVendor #vendor_name, #street1, #city, #postal, #country, #province, #state").on("keyup change", function(){
        disableEnableVendorSaveBtn();
    });

    disableEnableVendorSaveBtn();

    //inventory item form validation

    $('#frmItemCatalog').validate({ // initialize the plugin
        rules: {
            part_number: {
                required: true,
            },
            name: {
                required: true,
            },
            safety_stock_threshold: {
                required: true,
            },
            default_uom: {
                required: true,
            }
        }
    });

    $("form#frmItemCatalog #name, #part-number, #safety-stock-threshold, #default_uom, #currency").on("keyup change", function(){
        disableEnableInvItemSaveBtn();
    });

    disableEnableInvItemSaveBtn();

    $("form#frmAddAddress #addressname, #addressstreet1, #addresscity, #addresspostal, #addresscountry, #addressprovince, #addressstate").on("keyup change", function(){
        disableEnableSaveInvAddressBtn();
    });

    disableEnableSaveInvAddressBtn();

    //Search
    $(".linkedOrderSearchItem").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#linkedPurchaseOrderList tr, #linkedRequestList tr, #linkedShippingOrderList tr, #linkedRepairOrderList tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            $('.overview-detail').css('display','none');
        });
    });
    
});

$(document).on('change', "#inventoryattachment", function(){
    // Read selected files
    var totalfiles = document.getElementById('inventoryattachment').files.length;
    for (var index = 0; index < totalfiles; index++) {
        var form_data = new FormData();
        form_data.append("file_name", document.getElementById('inventoryattachment').files[index]);
        /*var filename = document.getElementById('inventoryattachment').files[index].name;
        var fileext = filename.split('.').pop();
        fileext = fileext.toLowerCase();
        var iconcss = '';
        if(fileext == 'pdf'){
            iconcss = 'icon-pdf';
        }else if(fileext == 'doc' || fileext == 'docx'){
            iconcss = 'icon-doc';
        }else if(fileext == 'xls' || fileext == 'xlsx'){
            iconcss = 'icon-excel';
        }else if(fileext == 'txt'){
            iconcss = 'icon-text';
        }else{
            iconcss = 'icon-generic';
        }*/
        
        uploadData(form_data);
    }
});

$(document).on('drop', '.upload-area', function (e) {
    e.stopPropagation();
    e.preventDefault();
    
    var file = e.originalEvent.dataTransfer.files;
    var fd = new FormData();
    fd.append('file_name', file[0]);
    
    uploadData(fd);
});

$(document).on('dragenter', '.upload-area', function (e) {
    e.stopPropagation();
    e.preventDefault();
});

// Drag over
$(document).on('dragover', '.upload-area', function (e) {
    e.stopPropagation();
    e.preventDefault();
});

$(document).on('click', '.btnOpenFilterPopup', function(e){
    $('#popupFilterModel').modal('show');
});

$(document).on('click', '.applyfilterbtn', function(e){
    localStorage.setItem('applyfilter', '1');
    searchApplyFilter();
    $('#popupFilterModel').modal('hide');
});
$(document).on('click', '.clearapplyfilter', function(e){
    localStorage.setItem('applyfilter', '0');

    $("#formPopupSearch")[0].reset();
    $('.selectpicker').selectpicker('refresh');
    searchApplyFilter();
})
function searchApplyFilter(){
    var formdata = $("#formPopupSearch").serialize();
    var dataTable = $('#datatableListingPage').DataTable();
    dataTable.columns(1).search(formdata).draw();
}

$(document).on('change', '#SearchBy', function(e){
    var url = $(location).attr('href'),
    parts = url.split("/"),
    last_part = parts[parts.length-1];
    if(last_part == 'transactionhistory'){
        $("#transaction_action").tokenInput("clear");
        if($(this).val() == '1'){
            $("#transaction_action").tokenInput("add", {'id':'6', 'name':'Consumed'});
            $("#transaction_action").tokenInput("add", {'id':'18', 'name':'Install'});
        }else if($(this).val() == '2'){
            $("#transaction_action").tokenInput("add", {'id':'25', 'name':'Receive'});
        }else if($(this).val() == '3'){
            $("#transaction_action").tokenInput("add", {'id':'28', 'name':'Uninstall'});
        }
    }
    searchApplyFilter();
});

// To show list of cities on change of country dropdown
$(document).on('change', '#country', function (e) {
    var countryId = $( this ).val();
    $('#state').find('option:not(:first)').remove();
    $("#province").val('');
    if (countryId == '231') {
        $(".provinceblock").css('display', 'none');
        $(".stateblock").css('display', '');
        $.ajax({
            type: "POST",
            url: getStatesList,
            data: {countryId:countryId},
            async : true,
            success: function(response) {
                if (response != '') {
                    $('#state').append(response);
                }
                $('#state').selectpicker('refresh');
            }                   
        });
    } else if(countryId != '231' || countryId == ''){
        $(".provinceblock").css('display', '');
        $(".stateblock").css('display', 'none');

        $('#state').selectpicker('refresh');
    }
});

$(document).on('click', '.deleteattachment', function (e) {
    if(confirm('Are you sure want to delete this attachment')){
        if($(this).attr('data-val') != undefined){
            $(this).parent().parent().remove();
            $.ajax({
                url: deleteInventoriesAttURL, 
                type: 'POST',
                data: {'id':$(this).attr('data-val')},
                dataType: "text",
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        var rowCount = $('#filetbody tr').length;
                        if(rowCount == '1'){
                            $('#noattachmenttr').css('display', '');
                        }
                        rowCount = rowCount >= '1' ? rowCount-1 : '0';
                        $('.inventory_attachment_count').html(rowCount);
                    } else {
                        $('#filetbody').html('<tr><td colspan="5"><span style="color:red;">'+obj.message+'</span></td></tr>');
                    }
                }
            });
        }else{
            $(this).parent().parent().remove();
            var rowCount = $('#filetbody tr').length;
            if(rowCount == '1'){
                $('#noattachmenttr').css('display', '');
            }
            rowCount = rowCount >= '1' ? rowCount-1 : '0';
            $('.inventory_attachment_count').html(rowCount);
        }
    }
    
});

function uploadData(form_data){
    $.ajax({
        url: uploadInventoriesAttURL, 
        type: 'post',
        data: form_data,
        contentType: false,
        processData: false,
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                var rowCount = $('#filetbody tr').length;
                if(rowCount == '1'){
                    $('#noattachmenttr').css('display', 'none');
                }
                $("#filetbody").append(obj.tblrow);

                rowCount = $('#filetbody tr').length;
                rowCount = rowCount >= '1' ? rowCount-1 : '0';
                $('.inventory_attachment_count').html(rowCount);
            } else {
                $('#filetbody').html('<tr><td colspan="5"><span style="color:red;">'+obj.message+'</span></td></tr>');
            }
        }
    });
}

$(document).on('click', '.chkBoxCls', function (e) {
    var totalCheckboxes = $('input.chkBoxCls:checkbox').length;
    var checkedcount = $('input.chkBoxCls:checked').length;
    
    if(checkedcount > '0'){
        $("select#actionSel option").prop('disabled', false);
        $(".actionOnSelected").css('pointer-events', '');
        $('.selectCount').html("("+checkedcount+")");
    }else{
        $("select#actionSel option").prop('disabled', true);
        $(".actionOnSelected").css('pointer-events', 'none');
        $('.selectCount').html('');
    }

    $('.selectpicker').selectpicker('refresh');

    if(totalCheckboxes == checkedcount) {
        $('#ckbCheckAll').prop('checked', true);
    } else {
        $('#ckbCheckAll').prop('checked', false);
    }

});

$(document).on('click', "#ckbCheckAll", function () {
    $(".chkBoxCls").prop('checked', $(this).prop('checked'));

    var checkedcount = $('input.chkBoxCls:checked').length;
    
    if(checkedcount > '0'){
        $("select#actionSel option").prop('disabled', false);
        $(".actionOnSelected").css('pointer-events', '');
        $('.selectCount').html("("+checkedcount+")");
    }else{
        $("select#actionSel option").prop('disabled', true);
        $(".actionOnSelected").css('pointer-events', 'none');
        $('.selectCount').html('');
    }
    
    $('.selectpicker').selectpicker('refresh');
});

//Download pdf
$(document).on('click', ".downloadInvItemsPdf", function (e) {
    var searchItem = $("#searchItem").val();

    var params = {searchItem: searchItem, type:'reportpdf'};
    downloadPDFAjax(inventoryItemsGenPDFURL, params);
});

//Down detail page pdf

$(document).on('click', ".downloadInvDetailPagePdf", function (e) {
    var id = window.location.pathname.split('/').pop();
    
    var params = {id: id};
    downloadPDFAjax(inventoryItemsGenPDFURL, params);
});

//export line item in excel format
$(document).on('click', ".exportLineItemExcel", function (e) {
    var formdata = $("#formPopupSearch").serialize();
    window.open(exportLineItemsExcelURL+formdata+'&applyfilter='+localStorage.getItem('applyfilter'));
});

//export listing data in excel format
$(document).on('click', ".exportListingDataExcel", function (e) {
    var formdata = $("#formPopupSearch").serialize();
    window.open(exportListingDataExcelURL+formdata+'&applyfilter='+localStorage.getItem('applyfilter'));
});

$(document).on('click', '.vendorModelbtn', function(e) {
    e.preventDefault();
    
    // Reset all form fields inside the modal
    $('#frmAddVendor')[0].reset();

    // Optionally clear select2 fields or custom input fields (if any)
    $('#frmAddVendor select').val('').trigger('change');
    $('#frmAddVendor input[type="hidden"]').val('');

    // Show the modal
    $('#vendorAddModel').modal('show');
});

$(document).on('click', '.manufacturerbtn', function (e) {
    e.preventDefault();
    
    // Reset all form fields inside the modal
    $('#frmManufacturer')[0].reset();

    // Optionally clear select2 fields or custom input fields (if any)
    $('#frmManufacturer select').val('').trigger('change');
    $('#frmManufacturer input[type="hidden"]').val('');

    // Show the modal
    $("#manufacturerModel").modal('show');
});

$(document).on('click', ".addnewinvitempopup", function (e) {
    e.preventDefault();
    
    // Reset all form fields inside the modal
    $('#frmItemCatalog')[0].reset();

    // Optionally clear select2 fields or custom input fields (if any)
    $('#frmItemCatalog select').val('').trigger('change');
    $('#frmItemCatalog input[type="hidden"]').val('');

    // Show the modal
    $("#inventoryItemModel").modal('show');
});

$(document).on("click", ".remove_inventory_item", function() {
    $(this).closest('tr').remove();
    var rowCount = $('.opinvitemtable tr').length;
    if(rowCount == '6'){
        $('#po_type').removeAttr('disabled');
        $('#po_type').selectpicker('refresh');
    }
});

$(document).on('click', ".addnewinvaddresspopup", function (e) {
    e.preventDefault();
    
    // Reset all form fields inside the modal
    $('#frmAddAddress')[0].reset();

    // Optionally clear select2 fields or custom input fields (if any)
    $('#frmAddAddress select').val('').trigger('change');
    $('#frmAddAddress input[type="hidden"]').val('');

    var clkbtn = $(this).attr('data-val');
    $("#is_billing_address").prop('checked', true);
    $("#is_shipping_address").prop('checked', true);

    $("#is_shipping_address").removeAttr('disabled');
    $("#is_billing_address").removeAttr('disabled');

    if(clkbtn == 'shipping'){
        $("#is_billing_address").prop('checked', false);
        $("#is_shipping_address").attr('disabled', true);
    }else{
        $("#is_shipping_address").prop('checked', false);
        $("#is_billing_address").attr('disabled', true);
    }
    $("#addressAddModel").modal('show');
});

function disableEnableManufacturerSaveBtn(){
    var errors = 0;
    $("form#frmManufacturer #name").map(function(){
        if( !$.trim($(this).val()) ) {
            errors++;
        } 
    });
    
    if(errors > 0){
        $(".manufacturersavebtn").attr("disabled", "disabled");
    }else{
        $(".manufacturersavebtn").removeAttr("disabled");
    }
}

function disableEnableVendorSaveBtn() {
    let errors = 0;
    const country = $('#country').val();
    const requiredFields = (country === '231')
        ? ['#vendor_name', '#street1', '#city', '#postal', '#country', '#state']
        : ['#vendor_name', '#street1', '#city', '#postal', '#country', '#province'];

    // Loop through required fields
    requiredFields.forEach(function(selector) {
        if (!$.trim($(selector).val())) {
            errors++;
            console.log(selector);
        }
    });

    // Enable or disable save button
    if (errors > 0) {
        $(".vendorsavebtn").prop("disabled", true);
    } else {
        $(".vendorsavebtn").prop("disabled", false);
    }
}


function disableEnableSaveInvAddressBtn(){
    var errors = 0;
    
    if($('#addresscountry').val() == '231'){
        $("form#frmAddAddress #addressname, #addressstreet1, #addresscity, #addresspostal, #addresscountry, #addressstate").map(function(){
            if( !$.trim($(this).val()) ) {
                errors++;
            } 
        });
    }else{
        $("form#frmAddAddress #addressname, #addressstreet1, #addresscity, #addresspostal, #addresscountry, #addressprovince").map(function(){
            if( !$.trim($(this).val()) ) {
                errors++;
            } 
        });
    }
    
    if(errors > 0){
        $(".invaddresssavebtn").attr("disabled", "disabled");
    }else{
        $(".invaddresssavebtn").removeAttr("disabled");
    }
}

function disableEnableInvItemSaveBtn(){
    var errors = 0;
    $("form#frmItemCatalog #name, #part-number, #safety-stock-threshold, #default_uom, #currency").map(function(){
        if( !$.trim($(this).val()) ) {
            errors++;
        } 
    });
    if(errors > 0){
        $(".invItemSaveBtn").attr("disabled", "disabled");
    }else{
        $(".invItemSaveBtn").removeAttr("disabled");
    }
}

$(document).on('click', '#export-button-pdf', function(e){
    var dataTable = $('#datatableListingPage').DataTable();
    dataTable.button('.buttons-pdf').trigger();
});

$(document).on('click', ".printBarCode", function (e) {
    var id = window.location.pathname.split('/').pop();
    
    var params = {id: id};
    downloadPDFAjax(printBarCodeURL, params);
});

function downloadPDFAjax(url, params){
    $.ajax({
        type: "POST",
        url: url,
        data: params,
        beforeSend: function () {
            $('.loader').show();
        },
        success:function(response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('.loader').hide();
                window.open(obj.data);
            } else if(obj.status == 'failure') {
                $('.loader').hide();
                alert('Some error occured. Please try again!');
            } else {
                $('.loader').hide();
            }
        },
        error : function() {
            $('.loader').hide();
            alert('Some error occured. Please try again!');
        },
        complete: function () {
            $('.loader').hide();
        }
    });
}

$(document).on("change", "#reportRedirectUrl", function(e){
    window.location.href = $(this).val();
})

$(document).on("click", ".toggleplusminus", function(e){
    if($(this).children('i.fa-plus').length > 0){
        $(this).children('i.fa').removeClass('fa-plus');
        $(this).children('i.fa').addClass('fa-minus');

        $(this).parent("td").find('pre.history-detail-block').css('display', 'block');
    }else{
        $(this).children('i.fa').removeClass('fa-minus');
        $(this).children('i.fa').addClass('fa-plus');

        $(this).parent("td").find('pre.history-detail-block').css('display', 'none');
    }
});

$(document).on("keyup change", "form#frmInventoryQuantitiesBulkTransfer #transfer_location_id", function(){
    disableEnableSaveBulkTransferBtn();
});

function disableEnableSaveBulkTransferBtn(){
    var errors = 0;
    $("form#frmInventoryQuantitiesBulkTransfer #transfer_location_id").map(function(){
        if( !$(this).val() ) {
            errors++;
        } 
    });
    if(errors > 0){
        $(".applyinvqtytransferbtn").attr("disabled", "disabled");
    }else{
        $(".applyinvqtytransferbtn").removeAttr("disabled");
    }
}

$(document).on('click', '.applyinvqtytransferbtn', function (e) {
    var ids = [];
    $("input:checkbox[name=childcheckbox]:checked").each(function(){
        ids.push($(this).val());
    });

    var location_id = '';
    if($("#transfer_location_id").val() != ''){
        location_id = $("#transfer_location_id").val();
    }
    
    $("#actionForm").attr("action",bulkTransferURL);
    $("#actionForm").append("<input type='hidden' name='location_id' value='"+location_id+"'/>");
    $("#actionForm").append("<input type='hidden' name='ids' value='"+ids+"'/>");

    $("#actionForm").submit();
});

$(document).on('click', '.applyinvqtydiscardbtn', function (e) {
    var ids = [];
    $("input:checkbox[name=childcheckbox]:checked").each(function(){
        ids.push($(this).val());
    });
    
    var reason = $("#reason").val();
    
    $("#actionForm").attr("action",bulkDiscardURL);
    $("#actionForm").append("<input type='hidden' name='reason' value='"+reason+"'/>");
    $("#actionForm").append("<input type='hidden' name='ids' value='"+ids+"'/>");

    $("#actionForm").submit();
});

$(document).on("keyup change", "form#frmInventoryQuantitiesBulkInstall #install_to", function(){
    disableEnableSaveBulkInstallBtn();
});

function disableEnableSaveBulkInstallBtn(){
    var errors = 0;
    $("form#frmInventoryQuantitiesBulkInstall #install_to").map(function(){
        if( !$(this).val() ) {
            errors++;
        } 
    });
    if(errors > 0){
        $(".applyinvqtyinstallbtn").attr("disabled", "disabled");
    }else{
        $(".applyinvqtyinstallbtn").removeAttr("disabled");
    }
}

$(document).on('click', '.applyinvqtyinstallbtn', function (e) {
    var ids = [];
    $("input:checkbox[name=childcheckbox]:checked").each(function(){
        ids.push($(this).val());
    });

    var install_to = '';
    if($("#install_to").val() != ''){
        install_to = $("#install_to").val();
    }
    
    $("#actionForm").attr("action",bulkInstallURL);
    $("#actionForm").append("<input type='hidden' name='install_to' value='"+install_to+"'/>");
    $("#actionForm").append("<input type='hidden' name='ids' value='"+ids+"'/>");

    $("#actionForm").submit();
});

//Search
$(document).on("keyup", "#attachmentSearch", function() {
    var value = $(this).val().toLowerCase();
    $("#filetbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        $('.overview-detail').css('display','none');
    });
});

//linked orders
$(document).on('click', '.linktoanotherorder', function(e){
    $("#linkWithAnotherOrderModel").modal('show');
})

$(document).on('click', '#linked_order_type', function(e){
    getInvExistingOrderData();
});

$(document).on('click', '.link-to-new-order', function(e){
    getInvExistingOrderData();
});

function getInvExistingOrderData(){
    var ordertype = $('input[name="ordertype"]:checked').val();
    var linked_order_type = $("#linked_order_type").val();
    
    if(ordertype == 'new-order'){
        $(".invlinkorderbtn").removeAttr('disabled');
        $('.ordertypedropdown').html('');
    }else if(linked_order_type != ''){
        var inventory_po_id = window.location.pathname.split('/').pop();
        $.ajax({
            url: inventoryExistOrderDropDownURL, 
            type: 'post',
            data: {'inventory_po_id':inventory_po_id, 'order_type':linked_order_type},
            dataType: 'text',
            success: function (response) {
                $('.ordertypedropdown').html(response);
                $('.selectpicker').selectpicker('refresh');
            }
        });
    }
}

$(document).on('click', '.invlinkorderbtn', function(e){
    var ordertype = $('input[name="ordertype"]:checked').val();
    var linked_order_type = $("#linked_order_type").val();
    var currenturl = window.location.pathname.split('/');
    var parentLinkedType = '';
    if(currenturl['2'] == 'inventory_purchase_orders'){
        parentLinkedType = '1';
    }else if(currenturl['2'] == 'inventory_requests'){
        parentLinkedType = '4';
    }else if(currenturl['2'] == 'inventory_shipping_orders'){
        parentLinkedType = '3';
    }else if(currenturl['2'] == 'inventory_repair_orders'){
        parentLinkedType = '2';
    }
    var inventory_po_id = window.location.pathname.split('/').pop();
    if(ordertype == 'new-order'){
        var url = '';
        if(linked_order_type == 'purchase'){
            url = createPurchaseOrderURL+'/?linkedOrderType=1';
        }else if(linked_order_type == 'repair'){
            url = createRepairOrderURL+'/?linkedOrderType=2';
        }else if(linked_order_type == 'request'){
            url = createRequestURL+'/?linkedOrderType=4';
        }else{
            url = createShippingOrderURL+'/?linkedOrderType=3';
        }
        window.location.href = url+'&linkedOrderId='+inventory_po_id+'&parentLinkedType='+parentLinkedType;
    }else{
        var inventory_po_id = window.location.pathname.split('/').pop();
        var linked_order_id = $("#link_order_id").val();
        
        $("#actionForm").attr("action",inventoryExistOrderLinkSaveURL);
        $("#actionForm").append("<input type='hidden' name='inventory_po_id' value='"+inventory_po_id+"'/>");
        $("#actionForm").append("<input type='hidden' name='order_type' value='"+linked_order_type+"'/>");
        $("#actionForm").append("<input type='hidden' name='parent_link_type' value='"+parentLinkedType+"'/>");
        $("#actionForm").append("<input type='hidden' name='linked_order_id' value='"+linked_order_id+"'/>");
        
        $("#actionForm").submit();
    }
});

$(document).on('click', '.chkBoxPO', function (e) {
    var totalCheckboxes = $('input.chkBoxPO:checkbox').length;
    var checkedcount = $('input.chkBoxPO:checked').length;
    
    linkedOrderAction();

    if(totalCheckboxes == checkedcount) {
        $('#ckbPOCheckAll').prop('checked', true);
    } else {
        $('#ckbPOCheckAll').prop('checked', false);
    }

});

$(document).on('click', "#ckbPOCheckAll", function () {
    $(".chkBoxPO").prop('checked', $(this).prop('checked'));

    linkedOrderAction();
});

$(document).on('click', '.chkBoxRO', function (e) {
    var totalCheckboxes = $('input.chkBoxRO:checkbox').length;
    var checkedcount = $('input.chkBoxRO:checked').length;
    
    linkedOrderAction();

    if(totalCheckboxes == checkedcount) {
        $('#ckbROCheckAll').prop('checked', true);
    } else {
        $('#ckbROCheckAll').prop('checked', false);
    }

});

$(document).on('click', "#ckbROCheckAll", function () {
    $(".chkBoxRO").prop('checked', $(this).prop('checked'));

    linkedOrderAction();
});

$(document).on('click', '.chkBoxSO', function (e) {
    var totalCheckboxes = $('input.chkBoxSO:checkbox').length;
    var checkedcount = $('input.chkBoxSO:checked').length;
    
    linkedOrderAction();

    if(totalCheckboxes == checkedcount) {
        $('#ckbSOCheckAll').prop('checked', true);
    } else {
        $('#ckbSOCheckAll').prop('checked', false);
    }

});

$(document).on('click', "#ckbSOCheckAll", function () {
    $(".chkBoxSO").prop('checked', $(this).prop('checked'));

    linkedOrderAction();
});

$(document).on('click', '.chkBoxReq', function (e) {
    var totalCheckboxes = $('input.chkBoxReq:checkbox').length;
    var checkedcount = $('input.chkBoxReq:checked').length;
    
    linkedOrderAction();

    if(totalCheckboxes == checkedcount) {
        $('#ckbReqCheckAll').prop('checked', true);
    } else {
        $('#ckbReqCheckAll').prop('checked', false);
    }

});

$(document).on('click', "#ckbReqCheckAll", function () {
    $(".chkBoxReq").prop('checked', $(this).prop('checked'));

    linkedOrderAction();
});

function linkedOrderAction(){
    var checkedcount = $('input.linkedOrderChkbox:checked').length;
    
    if(checkedcount > '0'){
        $("select#actionSel option").prop('disabled', false);
        $(".actionOnSelected").css('pointer-events', '');
        $('.selectCount').html("("+checkedcount+")");
    }else{
        $("select#actionSel option").prop('disabled', true);
        $(".actionOnSelected").css('pointer-events', 'none');
        $('.selectCount').html('');
    }
    
    $('.selectpicker').selectpicker('refresh');
}

$(document).on('click', '#unlinkOrder', function(e){
    var checkedcount = $('input.linkedOrderChkbox:checked').length;
    
    if(checkedcount > '0'){
        $('.chkBoxPO:checked').each(function (i) {
            $("#actionForm").append("<input type='hidden' name='purchaseorderids[]' value='"+$(this).val()+"'/>");
        }); 

        $('.chkBoxReq:checked').each(function (i) {
            $("#actionForm").append("<input type='hidden' name='requestids[]' value='"+$(this).val()+"'/>");
        }); 

        $('.chkBoxSO:checked').each(function (i) {
            $("#actionForm").append("<input type='hidden' name='shippingorderids[]' value='"+$(this).val()+"'/>");
        });

        $('.chkBoxRO:checked').each(function (i) {
            $("#actionForm").append("<input type='hidden' name='repairorderids[]' value='"+$(this).val()+"'/>");
        });
        
        var parent_purchase_ordre_id = window.location.pathname.split('/').pop();
        var currenturl = window.location.pathname.split('/');
        var parentLinkedType = '';
        if(currenturl['2'] == 'inventory_purchase_orders'){
            parentLinkedType = '1';
        }else if(currenturl['2'] == 'inventory_requests'){
            parentLinkedType = '4';
        }else if(currenturl['2'] == 'inventory_shipping_orders'){
            parentLinkedType = '3';
        }else if(currenturl['2'] == 'inventory_repair_orders'){
            parentLinkedType = '2';
        }
        
        $("#actionForm").attr("action",inventoryUnlinkExistOrderURL);
        $("#actionForm").append("<input type='hidden' name='parent_purchase_ordre_id' value='"+parent_purchase_ordre_id+"'/>");
        $("#actionForm").append("<input type='hidden' name='parent_link_type' value='"+parentLinkedType+"'/>");
        
        $("#actionForm").submit();
    }
});

$(document).on('blur', '#unit-cost, #exchange-cost, #exchange-price, #company-purchase-price, #retail-price, #overhauled-cost, #inventory_cost, #exchange_cost, #exchange_price, #retail_price, #company_purchase_price', function(e){
    let value = $(this).val().replace(/\$/g, '');
    if($.trim(value) != ''){
        value = parseFloat(value).toFixed(2);
        $(this).val('$'+value);
    }
});

$(document).on('blur', '#safety-stock-threshold, #weight, #inventory_qty', function(e){
    let value = $(this).val().replace(/\$/g, '');
    if($.trim(value) != ''){
        value = parseFloat(value).toFixed(2);
        $(this).val(value);
    }
});

$(document).on('blur', '#give_discount_percentage', function(e){
    let value = $(this).val().replace(/\%/g, '');
    if($.trim(value) != ''){
        value = parseFloat(value).toFixed(2);
        $(this).val(value+'%');
    }
});