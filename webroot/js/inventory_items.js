$(document).ready(function() {
    //Sorting script
    var thresholdTable = $('#inventoryThresholdTable');
    $('#thresholdLocation, #thresholdInstock, #thresholdSafetyStock')
        .wrapInner('<span title="sort this column"/>')
        .each(function() {
            var th = $(this),
                thIndex = th.index(),
                inverse = false;
            th.click(function() {
                thresholdTable.find('td.collapse-tr').filter(function() {
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
    $(".thresholdSearchItem").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#inventoryThresholdList tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            $('.overview-detail').css('display','none');
        });
    });

    $(document).on('click', '.dispinvdetpagepopup', function(e){
        var popuptoshow = $(this).attr('data-val');
        if(popuptoshow == 'thresholds'){
            $("#location_id").removeAttr('disabled');
            $("#location_id").val('');
            $("#thresholdid").val('');
            $("#safety-stock-threshold").val('');
            
            $('.selectpicker').selectpicker('refresh');

            $("#invThresholdAddModel").modal('show');
        }else if(popuptoshow == 'invqantitiesfilter'){
            $("#invQuantitiesFilterModel").modal('show');
        }
    });

    $("form#frmItemCatalogDet :input").prop("disabled", true);

    $('#actionOnSelect').bind("change", function(){
        var checkboxarr = [];
        $("input:checkbox[name=childcheckbox]:checked").each(function(){
            checkboxarr.push($(this).val());
        });
        if($(this).val() == 'Delete'){
            if(checkboxarr.length > 0){
                if(confirm("Are you sure you want to delete")){
                    var url = deleteThresholdsURL;
                    var ids = checkboxarr.join(',');
                    var inventory_item_id = window.location.pathname.split('/').pop();

                    $("#actionForm").attr("action",url);
                    $("#actionForm").append("<input type='hidden' name='id' value='"+ids+"'/>");
                    $("#actionForm").append("<input type='hidden' name='inventory_item_id' value='"+inventory_item_id+"'/>");
                    $("#actionForm").submit();
                }
                return false; 
            }else{
                $('#actionOnSelect').val('');
                alert("Please select a field which you want to delete");
            }
        }
    });

});

$(document).on("keyup change", "form#frmaddThrashold #location_id, #safety-stock-threshold", function(){
    disableEnableSaveThresholdBtn();
});

function disableEnableSaveThresholdBtn(){
    var errors = 0;
    $("form#frmaddThrashold #location_id, #safety-stock-threshold").map(function(){
        if( !$(this).val() ) {
            errors++;
        } 
    });
    if(errors > 0){
        $(".thrasholdsavebtn").attr("disabled", "disabled");
    }else{
        $(".thrasholdsavebtn").removeAttr("disabled");
    }
}

$(document).on('click', '.thrasholdsavebtn', function (e) {
    $('form#frmaddThrashold').submit();
});

$(document).on('click', '.invitemtable tbody td', function (e) {
    if ($(this).index() == 0 ) {
        return;
    }
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".chkBoxCls").val();
    if(values != undefined){
        window.location.href = inventoryitemdetURL+'/'+values;
    }
});

$(document).on('click', '#datatableQuantities tbody td', function (e) {
    if ($(this).index() == 0 ) {
        return;
    }
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".chkBoxCls").val();
    if(values != undefined){
        window.location.href = qantitiesDetPageURL+'/'+values;
    }
});

$(document).on('click', ".manufacturersavebtn", function (e) {
    var data = $('form#frmManufacturer').serialize();
    $.ajax({
        url: saveInventoryManufacturerURL, 
        type: 'post',
        data: data,
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                var invmanufacturers = obj.invmanufacturers;
                $('#manufacturer').append('<option value="'+invmanufacturers.id+'" selected>'+invmanufacturers.name+'</option>');
                $('.selectpicker').selectpicker('refresh');
                
                $("#manufacturerModel").modal('hide');
                $('#frmManufacturer')[0].reset();
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('change', "#f_cost_condition", function(e){
    if($(this).val() != '2'){
        $("#cost-end").attr('disabled', 'disabled');
    }else{
        $("#cost-end").removeAttr('disabled');
    }
})

$(document).on('change', "#f_expiration_date", function(e){
    if($(this).val() != '2'){
        $("#expiration_date_end").attr('disabled', 'disabled');
    }else{
        $("#expiration_date_end").removeAttr('disabled');
    }
})

$(document).on('click', '.invitmdetaction', function(e){
    $(".chkBoxCls").prop('checked', $(this).prop('checked'));

    var checkedcount = $('input.chkBoxCls:checked').length;
    
    if(checkedcount > '0'){
        if($(this).attr('data-val') == '1'){
            var ids = [];
            $("input:checkbox[name=childcheckbox]:checked").each(function(){
                ids.push($(this).val());
            });
            
            $("#actionForm").attr("action",addToHoldingBoxURL);
            $("#actionForm").append("<input type='hidden' name='ids' value='"+ids+"'/>");

            $("#actionForm").submit();
        }else if($(this).attr('data-val') == '2'){
            $("#applyTagsModel").modal('show');
        }
    }else{
        alert("Please select atleast one item.");
    }
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

$(document).on('click', '.inventory-tile', function(e){
    var dataTable = $('#datatableQuantities').DataTable();
    
    if($(this).attr('data-val') == 'order'){
        var inventory_item_id = window.location.pathname.split("/").pop();
        window.location.href = purchaseOrderListPageURL+'/?inventoryitemid='+inventory_item_id;
    }else{
        var inventory_status = $(this).attr('data-val');
        if(inventory_status != '11'){
            dataTable.column(6).visible(false);
        }else{
            dataTable.column(6).visible(true);
        }
        dataTable.columns(4).search(inventory_status).draw();
    }
});

$(document).on('change', '.invquantitiesaction', function(e){
    var inventory_item_id = window.location.pathname.split("/").pop();
    
    if($(this).val() == '1'){
        var ids = [];
        $("input:checkbox[name=childcheckbox]:checked").each(function(){
            ids.push($(this).val());
        });
        
        $("#actionForm").attr("action",addQuantitiesToHoldingBoxURL);
        $("#actionForm").append("<input type='hidden' name='inventory_item_id' value='"+inventory_item_id+"'/>");
        $("#actionForm").append("<input type='hidden' name='ids' value='"+ids+"'/>");

        $("#actionForm").submit();
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
    }
    
    $(this).val('');
    $('.selectpicker').selectpicker('refresh');
});

$(document).on('click', '.changeinvitmstatus', function (e) {   
    var msg = '';
    if($(this).attr('status-val') == '0'){
        msg = 'This record will no longer be displayed in the system. Confirm deactivation?';
    }else if($(this).attr('status-val') == '1'){
        msg = 'This record will be reactivated. Confirm activation?';
    }

    var status = $(this).attr('status-val') != undefined ? $(this).attr('status-val') : '';
    
    var inventory_item_id = window.location.pathname.split('/').pop();
    var url = updateInventoryItemStatusURL;

    $("#actionForm").attr("action",url);
    $("#actionForm").append("<input type='hidden' name='id' value='"+inventory_item_id+"'/>");
    $("#actionForm").append("<input type='hidden' name='status' value='"+status+"'/>");

    if(confirm(msg)){
        $("#actionForm").submit();
    }
});

$(document).on('click', '.invitemThresholdTable tbody td', function (e) {
    if ($(this).index() == 0 ) {
        return;
    }
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".chkBoxCls").val();
    var location_name = row.find(".thresholdloc").text();
    var safetystock = row.find(".thresholdstock").text();
    if(values != undefined){
        $("#location_id").attr('disabled', 'disabled');
        $("#location_id").val(location_name);
        $("#thresholdid").val(values);

        $("#safety-stock-threshold").val(safetystock);

        $('.selectpicker').selectpicker('refresh');

        $("#invThresholdAddModel").modal('show');
    }
});

$(document).on('click', 'input[name=accept_install], input[name=is_this_item_serialized]', function(e){
    if($('input[name=is_this_item_serialized]:checked').val() == '0' && $('input[name=accept_install]:checked').val() == '1'){
        $('#accept-install-0').prop('checked',true);
        $('#is-this-item-serialized-1').prop('checked',true);
        alert("Inventory Item must be Serialized in order to Accept Installs.");
    }
})

$(document).on('click', 'input[name=catalogprinttype]', function(e){
    $('#quantities_size1').prop('checked',true);
    if($('input[name=catalogprinttype]:checked').val() == '2'){
        $(".catalogprintsize").css('display', 'block');
    }else{
        $(".catalogprintsize").css('display', 'none');
    }
})

$(document).on('click', '.printInventoryCatalogBarcodes', function(e){
    var catalogprinttype = $('input[name=catalogprinttype]:checked').val();
    var catalogprintsize = $('input[name=catalogprintsize]:checked').val();

    var inventoryitemids = new Array();
    $('input[name="childcheckbox"]:checked').each(function() {
        inventoryitemids.push($(this).val());
    });

    var params = {catalogprinttype: catalogprinttype, catalogprintsize:catalogprintsize, inventoryitemids:inventoryitemids};
    downloadPDFAjax(printInventoryCatalogBarCodeURL, params);
});

$(document).on('click', '.applyCatalogTags', function(e){
    var activityontags = $("label.tagsbtnactivity.active").attr("data-val");
    var tags = $("#catalogtags").val();
    if($.trim(tags) != ''){
        
        $("#actionForm").attr("action",saveInventoryCatalogTagsURL);
        $('input[name="childcheckbox"]:checked').each(function() {
            $("#actionForm").append("<input type='hidden' name='inventory_item_id[]' value='"+$(this).val()+"'/>");
        });
        
        $("#actionForm").append("<input type='hidden' name='tags' value='"+tags+"'/>");
        $("#actionForm").append("<input type='hidden' name='activityontags' value='"+activityontags+"'/>");

        $("#actionForm").submit();
    }
})

$(document).on('click', '.printInventoriesListBarcodes', function(e){
    var catalogprintsize = $('input[name=catalogprintsize]:checked').val();

    var inventoriesids = new Array();
    $('input[name="childcheckbox"]:checked').each(function() {
        inventoriesids.push($(this).val());
    });
    
    var params = {catalogprintsize:catalogprintsize, inventoriesids:inventoriesids};
    downloadPDFAjax(printInventoriesListBarCodeURL, params);
});