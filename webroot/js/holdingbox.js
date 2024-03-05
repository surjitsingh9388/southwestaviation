$(document).on('click', '.itmCatChkBoxCls', function (e) {
    var totalCheckboxes = $('input.itmCatChkBoxCls:checkbox').length;
    var checkedcount = $('input.itmCatChkBoxCls:checked').length;
    
    if(checkedcount > '0'){
        $("select.itemcatalogaction option").prop('disabled', false);
        $('.selectCount').html("("+checkedcount+")");
    }else{
        $("select.itemcatalogaction option").prop('disabled', true);
        $('.selectCount').html('');
    }

    var statusflag = 0;
    $('input[name="physicalchildcheckbox"]:checked').each(function() {
        if(statusflag == '0' && $(this).attr('data-val') == ''){
            statusflag = 1;
        }
    });

    $('.selectpicker').selectpicker('refresh');

    if(totalCheckboxes == checkedcount) {
        $('#itmCatCheckAll').prop('checked', true);
    } else {
        $('#itmCatCheckAll').prop('checked', false);
    }
});

$(document).on('click', "#itmCatCheckAll", function () {
    $(".itmCatChkBoxCls").prop('checked', $(this).prop('checked'));

    var checkedcount = $('input.itmCatChkBoxCls:checked').length;
    
    if(checkedcount > '0'){
        $("select.itemcatalogaction option").prop('disabled', false);
        $('.selectCount').html("("+checkedcount+")");
    }else{
        $("select.itemcatalogaction option").prop('disabled', true);
        $('.selectCount').html('');
    }
    
    $('.selectpicker').selectpicker('refresh');
});

$(document).on('click', '.invPhyChkBoxCls', function (e) {
    var totalCheckboxes = $('input.invPhyChkBoxCls:checkbox').length;
    var checkedcount = $('input.invPhyChkBoxCls:checked').length;
    
    if(checkedcount > '0'){
        $(".invquantitiesoptiondef").prop('disabled', false);
        $('.selectCount').html("("+checkedcount+")");
    }else{
        $(".invquantitiesoptiondef").prop('disabled', true);
        $('.selectCount').html('');
    }

    if(totalCheckboxes == checkedcount) {
        $('#invPhyCheckAll').prop('checked', true);
    } else {
        $('#invPhyCheckAll').prop('checked', false);
    }

    if(checkedcount == '1'){
        var invstatus = $('input.invPhyChkBoxCls:checked').attr('data-val');
        var optionhtml = physicalInventoryActionOnSelect(invstatus);
        $("#invquantitiesaction").append(optionhtml);
    }else{
        $(".invquantitiesoption").remove();
    }

    $('.selectpicker').selectpicker('refresh');
});

$(document).on('click', "#invPhyCheckAll", function () {
    $(".invPhyChkBoxCls").prop('checked', $(this).prop('checked'));

    var checkedcount = $('input.invPhyChkBoxCls:checked').length;
    
    if(checkedcount > '0'){
        $(".invquantitiesoptiondef").prop('disabled', false);
        $('.selectCount').html("("+checkedcount+")");
    }else{
        $(".invquantitiesoptiondef").prop('disabled', true);
        $('.selectCount').html('');
    }

    if(checkedcount == '1'){
        $(".invquantitiesoption").prop('disabled', false);
    }else{
        $(".invquantitiesoption").prop('disabled', true);
    }
    
    $('.selectpicker').selectpicker('refresh');
});

function physicalInventoryActionOnSelect(invstatus){
    var opthtml = '<option class="invquantitiesoption" value="6">View Catalog Item</option>';
    if(invstatus == '2' || invstatus == '1' || invstatus == '6' || invstatus == '7' || invstatus == '5' || invstatus == '8' || invstatus == '12' || invstatus == '13'){ 
        opthtml += '<option class="invquantitiesoption" value="8">Edit</option>';
    } 
    if(invstatus != '2' && invstatus != '3' && invstatus != '4' && invstatus != '6' && invstatus != '10' && invstatus != '7' && invstatus != '9' && invstatus != '14' && invstatus != '8'){ 
        opthtml += '<option class="invquantitiesoption" value="9">Ship</option>';
        opthtml += '<option class="invquantitiesoption" value="10">Repair</option>';    
        opthtml += '<option class="invquantitiesoption" value="11">Discard</option>';
    if(invstatus != '5' && invstatus != '13'){ 
        opthtml += '<option class="invquantitiesoption" value="12">Consume</option>';
        if(invstatus != '12'){ 
            opthtml += '<option class="invquantitiesoption" value="13" data-val="1">Install</option>';
        }
    } 
    opthtml += '<option class="invquantitiesoption" value="14">Adjust</option>';
    opthtml += '<option class="invquantitiesoption" value="15">Transfer</option>';
    if(invstatus == '12'){ 
        opthtml += '<option class="invquantitiesoption" value="16" data-val="1">Unquarantine</option>';
    }else{ 
        opthtml += '<option class="invquantitiesoption" value="16" data-val="12">Quarantine</option>';
    }} if(invstatus == '2'){ 
        opthtml += '<option class="invquantitiesoption" value="13" data-val="2">Uninstall</option>';
    } 
    opthtml += '<option class="invquantitiesoption" value="17">Error Correct</option>';
    if(invstatus != '2' && invstatus != '3' && invstatus != '4' && invstatus != '10' && invstatus != '7' && invstatus != '9' && invstatus != '14' && invstatus != '6' && invstatus != '8'){ 
        opthtml += '<option class="invquantitiesoption" value="5">Print Barcode</option>';
    }
    return opthtml;
}

$(document).on('click', "#removeallitemcatalogs, #removeallinventories", function () {
    var msgtxt = 'Are you sure you want to remove all '+$(this).attr('data-val')+' from the Holding Box?';
    $(".removeallmsg").html(msgtxt);
    $('.confirmremoveallsubmit').attr('data-val', $(this).attr('data-val'));
    $("#removeAllConfirmModel").modal('show');
});

$(document).on('click', '.confirmremoveallsubmit', function(e){
    $("#actionForm").attr("action",removeAllHoldingBoxURL);
    var section_name = '';
    if($(this).attr('data-val') == 'inventory items'){
        section_name = 'inventory_catalog';
    }else if($(this).attr('data-val') == 'physical inventory items'){
        section_name = 'physical_inventory';
    }
    
    if(section_name != ''){
        $("#actionForm").append("<input type='hidden' name='section_name' value='"+section_name+"'/>");
        $("#actionForm").submit();
    }
});

$(document).on('change', '.itemcatalogaction', function(e){
    if($(this).val() == '1'){
        $("#actionForm").attr("action",removeAllHoldingBoxURL);
        var section_name = 'inventory_catalog';
        $('input[name="catalogchildcheckbox"]:checked').each(function() {
            $("#actionForm").append("<input type='hidden' name='id[]' value='"+$(this).val()+"'/>");
        });
        
        $("#actionForm").append("<input type='hidden' name='section_name' value='"+section_name+"'/>");
        $("#actionForm").submit();
    }else if($(this).val() == '2'){
        var ids = [];
        $("input:checkbox[name=catalogchildcheckbox]:checked").each(function(){
            ids.push($(this).val());
        });
        var formdata = $('#frmItemCatalog,#frmItemCatalogFilter').serialize();
        window.open(exportListingDataExcelURL+'?'+formdata+'&source=holdingbox&ids='+ids);
    }else if($(this).val() == '3'){
        $('#applyTagsModel').modal('show');
    }else if($(this).val() == '4'){
        $('#printCatalogBarcodesModel').modal('show');
    }
    $('.itemcatalogaction').val('');
    $('.selectpicker').selectpicker('refresh');
});

$(document).on('change', '#invquantitiesaction', function(e){
    if($(this).val() == '1'){
        $("#actionForm").attr("action",removeAllHoldingBoxURL);
        var section_name = 'inventory_catalog';
        $('input[name="physicalchildcheckbox"]:checked').each(function() {
            $("#actionForm").append("<input type='hidden' name='id[]' value='"+$(this).val()+"'/>");
        });
        
        $("#actionForm").append("<input type='hidden' name='section_name' value='"+section_name+"'/>");
        $("#actionForm").submit();
    }else if($(this).val() == '7'){
        var ids = [];
        $("input:checkbox[name=physicalchildcheckbox]:checked").each(function(){
            ids.push($(this).val());
        });
        var formdata = $('#frmPhysicalInventory,#frmInventoryQuantitiesFilter').serialize();
        window.open(exportPhysicalInvListingDataExcelURL+'?'+formdata+'&source=holdingbox&ids='+ids);
    }else if($(this).val() == '2' || $(this).val() == '3' || $(this).val() == '4'){
        $.ajax({
            url: ajaxOpenActionSelectPopupURL, 
            type: 'post',
            data: {'bulkpopuptype':$(this).val()},
            dataType: 'text',
            success: function (response) {
                $("#bulkpopupcontent").html(response);
                $("#invQuantitiesActionOnSelectModel").modal('show');
                $('.selectpicker').selectpicker('refresh');
            }
        });
    }else if($(this).val() == '5'){
        $("#printInventoriesListBarcodesModel").modal('show');
    }else if($(this).val() == '6'){
        var id = $("input:checkbox[name=physicalchildcheckbox]:checked").attr('item-id');
        window.location.href = inventoryItemDetURL+'/'+id;
    }else if($(this).val() == '6'){
        var id = $("input:checkbox[name=physicalchildcheckbox]:checked").attr('item-id');
        window.location.href = inventoryItemDetURL+'/'+id;
    }else if($(this).val() == '8'){
        var id = $("input:checkbox[name=physicalchildcheckbox]:checked").val();
        window.location.href = physicalInventoryEditURL+'/'+id;
    }else if($(this).val() == '9'){
        var id = $("input:checkbox[name=physicalchildcheckbox]:checked").val();
        window.location.href = shippingOrderCreateURL+'?invid='+id;
    }else if($(this).val() == '10'){
        var id = $("input:checkbox[name=physicalchildcheckbox]:checked").val();
        window.location.href = repairOrderCreateURL+'?invid='+id;
    }else if($(this).val() == '11'){
        var id = $("input:checkbox[name=physicalchildcheckbox]:checked").val();
        window.location.href = physicalInventoryDiscardURL+'/'+id;
    }else if($(this).val() == '12'){
        var id = $("input:checkbox[name=physicalchildcheckbox]:checked").val();
        window.location.href = physicalInventoryConsumeURL+'/'+id;
    }else if($(this).val() == '13'){
        var id = $("input:checkbox[name=physicalchildcheckbox]:checked").val();
        if($('option:selected', this).attr('data-val') == '1'){
            window.location.href = physicalInventoryInstallURL+'/'+id;
        }else{
            window.location.href = physicalInventoryUninstallURL+'/'+id;
        }
    }else if($(this).val() == '14'){
        var id = $("input:checkbox[name=physicalchildcheckbox]:checked").val();
        window.location.href = physicalInventoryAdjustURL+'/'+id;
    }else if($(this).val() == '15'){
        var id = $("input:checkbox[name=physicalchildcheckbox]:checked").val();
        window.location.href = physicalInventoryTransferURL+'/'+id;
    }else if($(this).val() == '16'){
        var id = $("input:checkbox[name=physicalchildcheckbox]:checked").val();
        var status = $('option:selected', this).attr('data-val');
        
        $("#actionForm").attr("action",updateInventoriesStatusURL);
        $("#actionForm").append("<input type='hidden' name='id' value='"+id+"'/>");
        $("#actionForm").append("<input type='hidden' name='status' value='"+status+"'/>");
        $("#actionForm").submit();

    }else if($(this).val() == '17'){
        var id = $("input:checkbox[name=physicalchildcheckbox]:checked").val();
        window.location.href = physicalInventoryErrorCorrectURL+'/'+id;
    }
    $('#invquantitiesaction').val('');
    $('.selectpicker').selectpicker('refresh');
});

$(document).on('click', '.applyCatalogTags', function(e){
    var activityontags = $("label.tagsbtnactivity.active").attr("data-val");
    var tags = $("#catalogtags").val();
    if($.trim(tags) != ''){
        
        $("#actionForm").attr("action",saveInventoryCatalogTagsURL);
        $('input[name="catalogchildcheckbox"]:checked').each(function() {
            $("#actionForm").append("<input type='hidden' name='inventory_item_id[]' value='"+$(this).val()+"'/>");
        });
        
        $("#actionForm").append("<input type='hidden' name='tags' value='"+tags+"'/>");
        $("#actionForm").append("<input type='hidden' name='activityontags' value='"+activityontags+"'/>");

        $("#actionForm").submit();
    }
})

$(document).on('click', '.printInventoryCatalogBarcodes', function(e){
    var catalogprinttype = $('input[name=catalogprinttype]:checked').val();
    var catalogprintsize = $('input[name=catalogprintsize]:checked').val();

    var inventoryitemids = new Array();
    $('input[name="catalogchildcheckbox"]:checked').each(function() {
        inventoryitemids.push($(this).val());
    });

    var params = {catalogprinttype: catalogprinttype, catalogprintsize:catalogprintsize, inventoryitemids:inventoryitemids};
    downloadPDFAjax(printInventoryCatalogBarCodeURL, params);
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

$(document).on('click', 'input[name=catalogprinttype]', function(e){
    $('#quantities_size1').prop('checked',true);
    if($('input[name=catalogprinttype]:checked').val() == '2'){
        $(".catalogprintsize").css('display', 'block');
    }else{
        $(".catalogprintsize").css('display', 'none');
    }
})

$(document).on('click', '.printInventoriesListBarcodes', function(e){
    var catalogprintsize = $('input[name=catalogprintsize]:checked').val();

    var inventoriesids = new Array();
    $('input[name="physicalchildcheckbox"]:checked').each(function() {
        inventoriesids.push($(this).val());
    });
    
    var params = {catalogprintsize:catalogprintsize, inventoriesids:inventoriesids};
    downloadPDFAjax(printInventoriesListBarCodeURL, params);
});

$(document).on('click', '.applyinvqtydiscardbtn', function (e) {
    var ids = [];
    $("input:checkbox[name=physicalchildcheckbox]:checked").each(function(){
        ids.push($(this).val());
    });
    
    var reason = $("#reason").val();
    
    $("#actionForm").attr("action",bulkDiscardURL);
    $("#actionForm").append("<input type='hidden' name='reason' value='"+reason+"'/>");
    $("#actionForm").append("<input type='hidden' name='ids' value='"+ids+"'/>");

    $("#actionForm").submit();
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
    $("input:checkbox[name=physicalchildcheckbox]:checked").each(function(){
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
    $("input:checkbox[name=physicalchildcheckbox]:checked").each(function(){
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

$(document).on('click', '.tagsbtnactivity', function(e){
    $(".tagsbtnactivity").removeClass('active');
    $(this).addClass('active');
    if($(this).text() == 'Replace'){
        $(".error-text").css('display', 'block');
    }else{
        $(".error-text").css('display', 'none');
    }
})