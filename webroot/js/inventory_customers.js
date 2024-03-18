$(document).ready(function(e){
    var aircraft_id = $('#aircraft_id').val();
    getAircraftDetById(aircraft_id);
});

$(document).on('click', '#datatableListingPage tbody td', function (e) {
    /*if ($(this).index() == 0 ) {
        return;
    }*/
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".chkBoxCls").val();
    if(values != undefined){
        window.location.href = inventoryRequestCreateInfoURL+'/'+values;
    }
});

$(document).on("click", ".toggleplusminus_aircraft", function(e){
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

$(document).on('change', '#invoice_part_number', function(e){
    if($(this).val() != ''){
        $.ajax({
            url:getInventoryItemByIdURL,
            data:{'inventory_item_id':$(this).val()},
            dataType: "text",
            type:'post',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {console.log(obj.conditions);
                    $('#invoice_part_description').val(obj.inventoryitems.description);
                    $('#part_qty_stock').val(obj.inventoryitems.qty);
                    $('#part-unit-measure').val(obj.inventoryitems.default_uom);
                    $('#part-weight').val(obj.inventoryitems.weight);
                    $('#otc_invoice_part_price_each').val(obj.inventoryitems.unit_cost);
                    $('#otc_invoice_part_total_prices').val(obj.inventoryitems.unit_cost);

                    var condoption = '<option value="">Select Conditions</option>';
                    var conditionlist = obj.conditions;
                    
                    $.each(conditionlist, function (key, val) {
                        condoption += '<option value="'+key+'">'+val+'</option>';
                    });
                    $('#invoice_part_conditions').html(condoption);

                    var serialoption = '<option value="">Select Serial Number</option>';
                    /*var seriallist = obj.serialno;
                    
                    $.each(seriallist, function (key, val) {
                        serialoption += '<option value="'+key+'">'+val+'</option>';
                    });*/
                    $('#invoice_serial_number').html(serialoption);

                    $('.selectpicker').selectpicker('refresh');
                } else {
                    alert("Something went wrong, please try again later");
                }
            }
        });
    }
});

$(document).on('change keyup', '#otc_invoice_give_discount, #otc_invoice_part_price_each, #otc_invoice_give_discount_percentage', function(e){
    var price_each = $('#otc_invoice_part_price_each').val();
    var give_discount_percentage = $('#otc_invoice_give_discount_percentage').val();

    var calpercentage = 0;
    if(give_discount_percentage > '0' && $('#otc_invoice_give_discount').is(':checked')){
        calpercentage = (price_each*give_discount_percentage)/100;
        calpercentage = calpercentage.toFixed(2);
    }
    var caltotalprice = price_each-calpercentage;
    caltotalprice = caltotalprice.toFixed(2);

    $('#otc_invoice_part_total_prices').val(caltotalprice);
});

$(document).on('change', '#invoice_part_conditions', function(e){
    if($(this).val() != ''){
        var inventory_item_id = $('#invoice_part_number').val();
        $.ajax({
            url:getInventoryDetByConditionURL,
            data:{'conditions':$(this).val(), 'inventory_item_id':inventory_item_id},
            dataType: "text",
            type:'post',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    var serialoption = '<option value="">Select Serial Number</option>';
                    var seriallist = obj.serialno;
                    
                    $.each(seriallist, function (key, val) {
                        serialoption += '<option value="'+key+'">'+val+'</option>';
                    });
                    $('#invoice_serial_number').html(serialoption);

                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    }
});

$(document).ready(function(e){
    $("#aircraft_info_add_section :input").prop("disabled", true);
    $('.addaircrafttolist').prop("disabled", false);
});

$(document).on('click', '#maint_use_hobbs_chkbox', function(e){
    $("#maint_hobbs").prop('readonly', true);
    $("#maint_current_ac_tach").prop('readonly', true);

    if($(this).prop('checked') == true){
        $("#maint_hobbs").prop('readonly', false);
    }else{
        $("#maint_current_ac_tach").prop('readonly', false);
    }
});

$(document).on('click', '.saveCustomerInfoBtn', function(e){
    if($("#customer_name").val() != ''){
        $('#frmAddNewCustomer')[0].submit();
    }else{
        alert("Customer name cann't be blank.");
    }
});

$(document).on('click', '.saveCustomerInfoDetBtn', function(e){
    $('#customer_ship_to_country').prop('disabled',false);
    $('form#frmInventoryCustomerInfo').submit();
});

$(document).on('click', '.saveCustomerInfoNotesbtn', function(e){
    var customer_id = window.location.pathname.split('/').pop();
    var notes = $('#customer_info_notes').val();
    if(notes != ''){
        $.ajax({
            url: saveCustomerInfoNotesURL, 
            type: 'post',
            data: {'notes':notes, 'customer_id':customer_id},
            dataType: 'text',
            success: function (response) {
                if(response.status == 'failure'){
                    alert(response.message);
                }else{
                    alert('Customer Info notes save successfully');
                }
            }
        });
    }
});

$(document).on('click', '.saveCustomerInfoMedia', function(e){
    $.ajax({
        url: saveCustomerInfoMediaURL, 
        type: 'post',
        data: $('#frmInventoryCustomersMedia').serialize(),
        dataType: 'text',
        success: function (response) {
            if(response.status == 'failure'){
                alert(response.message);
            }
            $('#customerMediaUploadModel').modal('hide');
        }
    });
});

$(document).on('click', '.saveAircraftInfoMedia', function(e){
    $.ajax({
        url: saveAircraftInfoMediaURL, 
        type: 'post',
        data: $('#frmAircraftUploadMedia').serialize(),
        dataType: 'text',
        success: function (response) {
            if(response.status == 'failure'){
                alert(response.message);
            }
            $('#aircraftMediaUploadModel').modal('hide');
        }
    });
});

$(document).on('click', '.deleteAircraftAttachment', function (e) {
    
    if($(this).attr('data-val') != undefined){
        $(this).parent().parent().remove();
        $.ajax({
            url: deleteAircraftMediaAttURL, 
            type: 'POST',
            data: {'id':$(this).attr('data-val')},
            dataType: "text",
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status != 'success') {
                    alert(obj.message);
                }
            }
        });
    }else{
        $(this).parent().parent().remove();
    }
    
});

$(document).on('click', '.saveAircraftRegNumber', function(e){
    var aircraft_registration_number = $('#aircraft-registration-number').val();
    if(aircraft_registration_number != ''){
        var customer_id = window.location.pathname.split('/').pop();

        $.ajax({
            url: saveCustomerOTCAircraftURL, 
            type: 'post',
            data: {'aircraft_registration_number':aircraft_registration_number, 'customer_id':customer_id},
            dataType: 'text',
            success: function (response) {
                if(response == 'Something went wrong, please try again' || response == 'Aircraft registration number already exist.'){
                    alert(response);
                }else{
                    $('#customerotcaircraftblock').html(response);
                    $("#aircraft_info_add_section :input").prop("disabled", false);
                    $(".selectpicker").selectpicker("refresh");
                    $('.contractpricesbtn').prop('disabled', true);
                    $('.fueldiscoutbtn').prop('disabled', true);
                }
            }
        });
    }
});

$(document).on('click', '.updateAircraftInfoBtn', function(e){
    $.ajax({
        url: saveCustomerOTCAircraftURL, 
        type: 'post',
        data: $('#frmUpdateCustomerOTCAircrafts').serialize(),
        dataType: 'text',
        async: true,
        success: function (response) {
            if(response == 'Something went wrong, please try again' || response == 'Aircraft registration number already exist.'){
                alert(response);
            }else{
                alert("Aircraft Detail added successfully");

                $('#customerotcaircraftblock').html(response);
                $("#aircraft_info_add_section :input").prop("disabled", false);
                $(".selectpicker").selectpicker("refresh");
                //$('.contractpricesbtn').prop('disabled', true);
                $('.fueldiscoutbtn').prop('disabled', true);
            }
        }
    });
    
});

$(document).on('click', '.saveAircraftInfoMore', function(e){
    $.ajax({
        url: saveCustomerOTCAircraftURL, 
        type: 'post',
        data: $('#frmAircraftInfoMore').serialize(),
        dataType: 'text',
        async: true,
        success: function (response) {
            if(response == 'Something went wrong, please try again' || response == 'Aircraft registration number already exist.'){
                alert(response);
            }else{
                alert("Aircraft Detail updated successfully");

                $('#customerotcaircraftblock').html(response);
                $("#aircraft_info_add_section :input").prop("disabled", false);
                $(".selectpicker").selectpicker("refresh");
                $('.fueldiscoutbtn').prop('disabled', true);
            }
        }
    });
    
});

$(document).on('click', '.deleteAircraftInfoBtn', function(e){
    var aircraft_id = $('.aircraftregbox-active').attr('data-val');
    var customer_id = window.location.pathname.split('/').pop();
    if(aircraft_id != '' && aircraft_id != undefined && customer_id != '' && customer_id != undefined){
        $.ajax({
            url: deleteCustomerOTCAircraftURL, 
            type: 'post',
            data: {'id':aircraft_id, 'customer_id':customer_id},
            async: true,
            success: function (response) {
                if(response == 'Something went wrong, please try again'){
                    alert(response);
                }else{
                    alert("Aircraft deleted successfully");
    
                    $('#customerotcaircraftblock').html(response);
                    $("#aircraft_info_add_section :input").prop("disabled", false);
                    $(".selectpicker").selectpicker("refresh");
                    //$('.contractpricesbtn').prop('disabled', true);
                    $('.fueldiscoutbtn').prop('disabled', true);
                }
            }
        });
    }else{
        alert('Please select shipping address.');
    }
});

$(document).on('click', '#user_contract_pricing', function(e){
    if($(this).prop('checked') == true){
        $('.contractpricesbtn').prop('disabled', false);
    }else{
        $('.contractpricesbtn').prop('disabled', true);
    }
});

$(document).on('click', '.complianceinspectionadd', function(e){
    if($('#comp_tab_click').val() == 'Inspections'){
        $("#frmAircraftComplInspections")[0].reset();
    }else if($('#comp_tab_click').val() == 'Airframe'){
        $("#frmAircraftComplAirframe")[0].reset();
    }else if($('#comp_tab_click').val() == 'Engine'){
        $("#frmAircraftComplEngines")[0].reset();
    }

    getNewAircraftComplainceItemBlock();
    
});

function getNewAircraftComplainceItemBlock(){
    var section = $('#comp_tab_click').val();
    section = section.toLowerCase();
    $.ajax({
        url: getAircraftComplianceDetailURL, 
        type: 'post',
        data: {'section':section, 'id':'', 'aircraft_id':$('#aircraft_id').val()},
        success: function (response) {
            if(response == 'Something went wrong, please try again'){
                alert(response);
            }else{
                if(section == 'inspections'){
                    $('.aircraftComplInspectionBlock').html(response);
                }else if(section == 'airframe'){
                    $('.aircraftComplAirframeBlock').html(response);
                }else if(section == 'engine'){
                    $('.aircraftComplEngineBlock').html(response);
                }
                $(".selectpicker").selectpicker("refresh");
                overrideComplianceBlockDate();
            }
        }
    });
}

$(document).on('click', '#inspections_use_cycles', function(e){
    if($(this).is(":checked")){
        $('#inspections_interval_hours').prop('readonly', true);
        $('#inspections_interval_months').prop('readonly', true);
        $('#inspections_interval_cycles').prop('readonly', false);

        $('.due_next_landings_ac_tt').text('Due Next - Lndgs');
        $('.insp_current_ac_tach').text('AC Landings');
        $('.insp_history_col3').text('Lndgs');
    }else{
        $('#inspections_interval_hours').prop('readonly', false);
        $('#inspections_interval_months').prop('readonly', false);
        $('#inspections_interval_cycles').prop('readonly', true);

        $('.due_next_landings_ac_tt').text('Due Next - AC TT');
        $('.insp_current_ac_tach').text('Current AC Tach');
        $('.insp_history_col3').text('Hrs');
    }
});

$(document).on('click', '#airframe_use_cycles', function(e){
    if($(this).is(":checked")){
        $('#airframe_time_limit_of_part').prop('readonly', true);
        $('#airframe_time_at_install').prop('readonly', true);
        $('#airframe_ac_tt_at_install').prop('readonly', true);

        $('#airframe_cycles_limit_of_part').prop('readonly', false);
        $('#airframe_cycles_at_install').prop('readonly', false);
        $('#airframe_landing_at_install').prop('readonly', false);
    }else{
        $('#airframe_cycles_limit_of_part').prop('readonly', true);
        $('#airframe_cycles_at_install').prop('readonly', true);
        $('#airframe_landing_at_install').prop('readonly', true);

        $('#airframe_time_limit_of_part').prop('readonly', false);
        $('#airframe_time_at_install').prop('readonly', false);
        $('#airframe_ac_tt_at_install').prop('readonly', false);
    }
});

$(document).on("change", "#aircraft_wo_files", function(){
    // Read selected files
    var fldid = 'aircraft_wo_files';
    var tableid = 'wofileattachlist';
    uploadFileToServer(fldid, tableid, uploadWOItemFilesURL);
});

$(document).on("change", "#aircraft_wo_photo", function(){
    // Read selected files
    var fldid = 'aircraft_wo_photo';
    var tableid = 'wophotosattachlist';
    uploadFileToServer(fldid, tableid, uploadWOItemPhotosURL);
});

$(document).on("change", "#aircraft_upload_media", function(){
    // Read selected files
    var fldid = 'aircraft_upload_media';
    var tableid = 'aircraftuploadmediatbl';
    uploadFileToServer(fldid, tableid, uploadAircraftMediaURL);
});

$(document).on("change", "#wo_osr_vendor_media", function(){
    // Read selected files
    var fldid = 'wo_osr_vendor_media';
    var tableid = 'aircraftwoosrmediatbl';
    uploadFileToServer(fldid, tableid, uploadWOOSRVendorMediaURL);
});

$(document).on("change", "#wo_osr_purchase_order_media", function(){
    // Read selected files
    var fldid = 'wo_osr_purchase_order_media';
    var tableid = 'aircraftwoosrpomediatbl';
    uploadFileToServer(fldid, tableid, uploadWOOSRPurchaseOrderMediaURL);
});

function uploadFileToServer(fldid, tableid, url){
    var totalfiles = document.getElementById(fldid).files.length;
    for (var index = 0; index < totalfiles; index++) {
        var form_data = new FormData();
        form_data.append("file_name", document.getElementById(fldid).files[index]);
        if(fldid == 'aircraft_wo_files' || fldid == 'aircraft_wo_photo'){
            var wo_item_id = $('#wo_item_id').val();
            form_data.append("wo_item_id", wo_item_id);
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
                    if(fldid == 'aircraft_wo_photo'){
                        var photocount = parseInt($('.count_wo_item_photo').html())+1;
                        $('.count_wo_item_photo').html(photocount);
                    }
                    if(fldid == 'aircraft_wo_files'){
                        var filecount = parseInt($('.count_wo_item_file').html())+1;
                        $('.count_wo_item_file').html(filecount);
                    }
                } else {
                    //$('#'+tableid).html('<tr><td colspan="5"><span style="color:red;">'+obj.message+'</span></td></tr>');
                    alert(obj.message);
                }
            }
        });
    }
}

$(document).on('click', '.fetchCustOTCPopup', function(e){
    var section = $(this).attr('data-val');
    if(section != '' && section != undefined){
        if(section == 'confirm_create_otc_invoice_btn'){
            var tax_exempt_expire = $('#tax_exempt_expire').val();
            var tax_exempt_expire = new Date(tax_exempt_expire).getTime();
            var currenttime = new Date();
            currenttime = currenttime.getTime()
        
            if(currenttime > tax_exempt_expire){
                alert('The tax exemption for this customer has expired.');
            }
        }
        var customer_id = $('#wo_customer_id').val();
        if(customer_id == '' || customer_id == undefined){
            customer_id = window.location.pathname.split('/').pop();
        }
        var aircraft_id = $('#aircraft_id').val();
        if(aircraft_id == '' || aircraft_id == undefined){
            aircraft_id = $('#wo_aircraft_id').val();
        }
        $.ajax({
            url: fetchCustomerOTCPopupURL, 
            type: 'post',
            data: {section:section, customer_id:customer_id, aircraft_id:aircraft_id},
            async : true,
            success: function (response) {
                appendCustomerOTCPopupData(section, response);
            }
        });
    }
});

function appendCustomerOTCPopupData(section, response){
    var sectionId = '';
                
    if(section == 'upload_new_cust_media'){
        sectionId = 'customerMediaUploadModel';
    }else if(section == 'new_cust_note'){
        sectionId = 'customerNotesModel';
    }else if(section == 'cust_otc_aircraft_maintenance'){
        sectionId = 'aircarftMaintenanceModel';
        $("#customerotcpopup").html('');
    }else if(section == 'cust_otc_aircraft_compliance'){
        sectionId = 'aircarftComplianceModel';
    }else if(section == 'aircraft_create_wo_btn'){
        $("#customerotcpopup").html('');
        $('.modal-backdrop').remove();
        sectionId = 'aircarftCreateWOModel';
    }else if(section == 'add_new_aircraft_btn'){
        sectionId = 'addAircraftModal';
    }else if(section == 'cust_addl_ship_addr_btn'){
        sectionId = 'customerAddlShippingAddressModal';
    }else if(section == 'cust_add_ship_addr_btn'){
        sectionId = 'customerShippingAddressModal';
    }else if(section == 'aircraft_maint_update_times_btn'){
        sectionId = 'aircraftMaintenanceUpdtTimeModel';
    }else if(section == 'aircraft_view_contract_price_btn'){
        sectionId = 'aircraftContractPricingModal';
    }else if(section == 'contract_price_dept_add_btn'){
        sectionId = 'contractPricingDeptAddModal';
    }else if(section == 'customer_aircraft_info_more_btn'){
        sectionId = 'aircraftInfoMoreModal';
    }else if(section == 'aircraft_wo_move_items'){
        sectionId = 'aircraftWOMoveItemModel';
    }else if(section == 'aircraft_wo_sign_offs'){
        sectionId = 'aircraftWOSignoffModel';
    }else if(section == 'add_wo_technician_btn'){
        sectionId = 'aircraftWOTechnicianAddModal';
    }else if(section == 'aircarft_wo_add_outside_repair'){
        sectionId = 'woOutstandingRepairEditModal';
    }else if(section == 'aircraft_wo_part_add_btn'){
        sectionId = 'aircraftWOAddPartModel';
        $('#customerOTCInvoiceAddPartModel').remove();
    }else if(section == 'wo_osr_send_msg_btn'){
        sectionId = 'woOSRSendMsgModal';
    }else if(section == 'aircraft_wo_tool_add_btn'){
        sectionId = 'aircraftWOAddToolModel';
    }else if(section == 'create_otc_invoice_btn'){
        sectionId = 'confirmCreateOTCInvoiceModel';
    }else if(section == 'aircraft_upload_media_btn'){
        sectionId = 'aircraftMediaUploadModel';
        $("#customerotcpopup").html('');
    }else if(section == 'aircraft_compliance_list'){
        sectionId = 'aircarftComplianceListModel';
    }else if(section == 'aircraft-maint-eng-cyl'){
        sectionId = 'aircarftMaintEngCylDateModel';
    }else if(section == 'customer_list'){
        sectionId = 'addCustomerListsModal';
    }else if(section == 'confirm_create_otc_invoice_btn'){
        sectionId = 'createOTCInvoiceModel';
        $("#confirmCreateOTCInvoiceModel").modal("hide");
    }else if(section == 'create_otc_invoice_ad_part_btn'){
        sectionId = 'customerOTCInvoiceAddPartModel';
    }else if(section == 'add_wo_services_note_btn'){
        sectionId = 'aircraftWOServicesNotesModel';
    }else if(section == 'aircraft_wo_osr_vendor'){
        sectionId = 'woOSRVendorModal';
    }else if(section == 'aircraft_create_new_wo_osr_vendor'){
        sectionId = 'addNewWOOSRVendorModal';
    }else if(section == 'aircraft_wo_osr_vendor_list'){
        sectionId = 'aircraftWOOSRVendorListModal';
    }else if(section == 'aircraft_wo_osr_vendor_media'){
        sectionId = 'woOSRVendorMediaModel';
    }else if(section == 'aircraft_wo_osr_service_po'){
        sectionId = 'aircraftWOOSRServicePOModel';
    }else if(section == 'aircraft_wo_osr_po_tracking_num'){
        sectionId = 'woOSRAddlTrackingNumModal';
    }else if(section == 'aircraft_wo_osr_service_po_notes'){
        sectionId = 'aircraftWOOSRServicePONotesModel';
    }else if(section == 'aircraft_wo_osr_po_checkin_labor'){
        sectionId = 'aircraftWOOSRPOCheckInLaborModel';
    }else if(section == 'aircraft_wo_osr_po_media'){
        sectionId = 'woOSRServicePOMediaModel';
    }else if(section == 'aircraft_wo_osr_po_reminder'){
        sectionId = 'aircraftWOOSRPONotifyModel';
    }else if(section == 'aircraft_wo_osr_po_item'){
        sectionId = 'aircraftWOOSRPOItemModel';
    }else if(section == 'aircraft_wo_all_osr_list'){
        sectionId = 'aircraftWOAllOSRListModel';
    }else if(section == 'aircraft_wo_item_note'){
        sectionId = 'aircraftWorkOrderItemNoteModel';
    }else if(section == 'aircraft_wo_item_list'){
        sectionId = 'aircraftWOItemListModel';
    }else if(section == 'aircraft_work_order_options'){
        sectionId = 'aircarftWorkOrderOptionModel';
    }else if(section == 'aircraft_option_email_work_order'){
        sectionId = 'aircarftOptionEmailWOModel';
    }else if(section == 'aircraft_wo_option_create_atacode'){
        sectionId = 'aircarftOptionWOATACodeModel'; 
    }else if(section == 'aircraft_wo_option_create_laborkit'){
        sectionId = 'aircarftOptionWOLaborKitModel';
    }else if(section == 'aircraft_wo_option_logbook_helper'){
        sectionId = 'aircarftOptionWOLogBookHelperModel';
    }else if(section == 'aircraft_wo_option_logbook_values'){
        sectionId = 'aircarftWOLogOptionBookValuesModel';
        $('#aircarftMaintenanceModel').remove();
    }else if(section == 'aircraft_wo_msgs_forall_users'){
        sectionId = 'aircarftWOOptoinMsgForAllUsersModel';
    }else if(section == 'aircraft_wo_geninfo_manage_deposits'){
        sectionId = 'aircarftWOOptionGenInfoMngDepositsModel';
    }else if(section == 'aircraft_wo_option_taxinfo_extra_taxes'){
        sectionId = 'aircarftOptionWOExtraTaxesModel';
    }else if(section == 'aircraft_wo_option_new_extra_tax'){
        sectionId = 'aircraftWOOptNewExtTaxModal';
    }else if(section == 'wo_set_all_items_specific_dept'){
        sectionId = 'woSetAllItemsSpecificDeptModal';
    }else if(section == 'list_of_open_work_order'){
        sectionId = 'listOfOpenWorkOrdersModal';
    }else if(section == 'list_of_warranty_claims_work_order'){
        sectionId = 'listOfWarrantyClaimsWOModal';
    }else if(section == 'list_of_all_work_order_quotes'){
        sectionId = 'listOfAllWorkOrdersQuotesModal';
    }else if(section == 'advanced_wo_find_options'){
        sectionId = 'advancedWOFindOptionsModal';
    }else if(section == 'aircraft_wo_item_allparts_list'){
        sectionId = 'aircraftWOItemAllPartsListModel';
    }else if(section == 'aircraft_wo_item_parts_requisitions'){
        sectionId = 'woItemPartsRequisitionsModel';
    }else if(section == 'aircraft_wo_item_parts_send_reqmsg'){
        sectionId = 'woPartsSendNeededMsgModel';
    }else if(section == 'aircraft_wo_item_parts_view'){
        sectionId = 'woItemPartsViewModel';

        $('#aircraftWOAddPartModel').remove();
    }else if(section == 'aircraft_wo_item_parts_to_pull'){
        sectionId = 'woItemPartsToPullModel';
    }else if(section == 'aircraft_wo_item_part_notes'){
        sectionId = 'aircraftWorkOrderItemPartNotesModel';
    }else if(section == 'aircraft_work_order_mark_items'){
        sectionId = 'aircarftWOMarkItemsModel';
    }else if(section == 'import_work_order_from_email'){
        sectionId = 'importWOROFromEmailModel';
    }else if(section == 'export_work_order_to_file'){
        sectionId = 'exportWOROExportDataModel';

        exportWORODataToExcelBlockDate();
    }else if(section == 'aircraft_wo_completion_password'){
        sectionId = 'aircarftWOStatusComplPwdModel';
    }else if(section == 'aircraft_wo_item_tool_edit'){
        sectionId = 'aircraftWOItemToolEditModel';
    }else if(section == 'inventory_customer_add_address'){
        sectionId = 'customerAddressAddModel';
    }else if(section == 'update_logbook_value_open_wo'){
        sectionId = 'updateLogBookValOpenWOModel';
    }else if(section == 'confirm_create_new_work_order'){
        sectionId = 'ConfirmCreateNewWOModel';
    }else if(section == 'wo_item_discrepancy'){
        sectionId = 'woItemDiscrepancyModal';
    }else if(section == 'wo_item_discrepancy_history'){
        sectionId = 'woItemDiscrepancyHistoryModal';
    }else if(section == 'wo_item_corrective_action'){
        sectionId = 'woItemCorrectiveActionModal';
    }else if(section == 'wo_item_corrective_action_history'){
        sectionId = 'woItemCorrectiveActionHistoryModal';
    }

    $('#'+sectionId).remove();
    $("#customerotcpopup").append(response);
    $('.selectpicker').selectpicker('refresh');
    $('#'+sectionId).modal('show');

    if(section == 'aircraft_wo_osr_service_po'){
        setOSRPOFieldsEnableDisable();
    }

    if(section == 'aircraft_wo_option_taxinfo_extra_taxes'){
        $('#frmAircraftWOOptExtraTaxes :input').prop("disabled", true);
        $('.wo-option-new-extra-taxes').prop("disabled", false);
    }

    if(section == 'export_work_order_to_file'){
        exportWORODataToExcelBlockDate();
    }

    if(section == 'aircraft_wo_item_tool_edit'){
        woItemToolBlockDate();
    }

    overrideComplianceBlockDate();
    overrideMaintenanceBlockDate();
    overrideWorkOrderOSRBlockDate();
    overrideWorkOrderPartsBlockDate();
}

function overrideComplianceBlockDate(){
    $('#engine_due_date, #engine_date_installed, #airframe_due_date, #airframe_date_installed, #inspections_due_date, #inspections_date_override, #date_labeled').datetimepicker({
        format: 'MM-DD-YYYY'
    });

    $('#airframe_time_at_install, #engine_time_at_install').datetimepicker({
        format: "HH:mm"
    });
}

function woItemToolBlockDate(){
    $('#date_labeled').datetimepicker({
        format: 'MM-DD-YYYY'
    });
}

function overrideWorkOrderPartsBlockDate(){
    $('#wo_item_part_date_needed, #wo_item_part_date_received, #wo_item_part_warranty_expires').datetimepicker({
        format: 'MM-DD-YYYY'
    });
}

function exportWORODataToExcelBlockDate(){
    $('#wo_ro_created_from, #wo_ro_created_to, #wo_ro_completed_from, #wo_ro_completed_to, #wo_ro_signoff_from, #wo_ro_signoff_to, #wo_ro_loggedin_from, #wo_ro_loggedin_to').datetimepicker({
        format: 'MM-DD-YYYY'
    });
}

$(document).on('change', '#aircraft_make_id', function(e){
    var aircraft_make_id = $(this).val();
    if(aircraft_make_id != ''){
        $.ajax({
            url: fetchAircraftModelURL, 
            type: 'post',
            data: {aircraft_make_id:aircraft_make_id},
            async : true,
            success: function (response) {
                $('#aircraft_model_id').html(response);
                $('.selectpicker').selectpicker('refresh');
            }
        });
    }
});

$(document).on('click', '.aircraftregbox', function(e){
    var aircraft_id = $(this).attr('data-val');
    $('.aircraftregbox').removeClass('aircraftregbox-active');
    $(this).addClass('aircraftregbox-active');

    getAircraftDetById(aircraft_id);
});

function getAircraftDetById(aircraft_id){
    if(aircraft_id != '' && aircraft_id !=undefined){
        var customer_id = window.location.pathname.split('/').pop();
        $.ajax({
            url: fetchAircraftInfoHtmlURL, 
            type: 'post',
            data: {'aircraft_id':aircraft_id, 'customer_id':customer_id},
            async : true,
            success: function (response) {
                $('#customerotcaircraftblock').html(response);
                $("#aircraft_info_add_section :input").prop("disabled", false);
                $(".selectpicker").selectpicker("refresh");
                $('.fueldiscoutbtn').prop('disabled', true);
            }
        });
    }
}

$(document).on('click', '.saveAircraftContractRate', function(e){
    var contract_rate_department = $('#contract_rate_department_new').val();
    var contract_rate_hour = $('#contract_rate_hour_new').val();
    var aircraft_id = $('#aircraft_id').val();
    var contract_rate_id = $('#contract_rate_id').val();
    if(contract_rate_department != '' && contract_rate_hour != '' && aircraft_id != ''){
        
        $.ajax({
            url: saveAircraftContractRatesURL, 
            type: 'post',
            data: {'contract_rate_department':contract_rate_department, 'rate_an_hour':contract_rate_hour, 'aircraft_id': aircraft_id, 'contract_rate_id':contract_rate_id},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    var aircraftContractRates = obj.aircraftContractRates;
                    var tableelem = '';
                    for(var i=0; i<aircraftContractRates.length; i++){
                        tableelem += '<tr data-val="'+aircraftContractRates[i]['id']+'"><td>'+aircraftContractRates[i]['department']+'</td><td>'+aircraftContractRates[i]['rate_an_hour']+'</td></tr>';
                    }
                    $('#contractratetbl').html(tableelem);
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('dblclick', '#contractratetbl tr', function(e){
    var contract_rate_id = $(this).attr('data-val');
    var section = 'contract_price_dept_add_btn';
    var customer_id = window.location.pathname.split('/').pop();
    var aircraft_id = $('#aircraft_id').val();
    if(contract_rate_id != ''){
        $.ajax({
            url: fetchCustomerOTCPopupURL, 
            type: 'post',
            data: {'contract_rate_id':contract_rate_id, 'section':section, customer_id:customer_id, aircraft_id:aircraft_id},
            async : true,
            success: function (response) {
                appendCustomerOTCPopupData(section, response);
            }
        });
    }
});

$(document).on('click', '#contractratetbl tr', function(e){
    $('#contractratetbl tr').removeClass('active');
    $(this).addClass('active');
});

$(document).on('click', '.contractpriceratedelete', function(e){
    var contractratedata = $('#contractratetbl .active').attr('data-val');
    contractratedata = contractratedata.split('-');
    //console.log(contractratedata);
    var contract_rate_id = contractratedata[0];
    var aircraft_id = contractratedata[1];
    if(contract_rate_id != '' && contract_rate_id != undefined && aircraft_id != '' && aircraft_id != undefined){
        $.ajax({
            url: deleteAircraftContractRatesURL, 
            type: 'post',
            data: {'id':contract_rate_id, 'aircraft_id':aircraft_id},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    alert("Contract rate deleted successfully.");
                    
                    var aircraftContractRates = obj.aircraftContractRates;
                    var tableelem = '';
                    for(var i=0; i<aircraftContractRates.length; i++){
                        tableelem += '<tr data-val="'+aircraftContractRates[i]['id']+'"><td>'+aircraftContractRates[i]['department']+'</td><td>'+aircraftContractRates[i]['rate_an_hour']+'</td></tr>';
                    }
                    $('#contractratetbl').html(tableelem);
                }else{
                    alert(obj.message);
                }
            }
        });
    }else{
        alert('Please select contract rate.');
    }
});

$(document).on('click', '.saveAddlShippingAddrBtn', function(e){
    var customer_id = window.location.pathname.split('/').pop();
    var additional_shipping_address_description = $('#additional-shipping-address-description').val();
    var additional_shipping_full_address = $('#additional-shipping-full-address').val();
    var additional_shipping_address_id = $('#additional_shipping_address_id').val();
    if(customer_id != '' && additional_shipping_address_description != '' && additional_shipping_full_address != ''){
        $.ajax({
            url: saveAddlShippingAddressURL, 
            type: 'post',
            data: {'customer_id':customer_id, 'additional_shipping_address_description':additional_shipping_address_description, 'additional_shipping_full_address': additional_shipping_full_address, 'additional_shipping_address_id':additional_shipping_address_id},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    var customerAddlShipAddrList = obj.customerAddlShipAddrList;
                    var shippingelem = '';
                    for(var i=0; i<customerAddlShipAddrList.length; i++){
                        shippingelem += '<div class="addlshipaddrbox" data-val="'+customerAddlShipAddrList[i]['id']+'">'+customerAddlShipAddrList[i]['additional_shipping_address_description']+'</div>';
                    }
                    $('.listofadlshipaddr').html(shippingelem);

                    $('#frmAddlShippingAddress')[0].reset();
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('click', '.addlshipaddrbox', function(e){
    var additional_shipping_address_id = $(this).attr('data-val');
    $('.addlshipaddrbox').removeClass('addlshipaddrbox-active');
    $(this).addClass('addlshipaddrbox-active');

    getAddlShippingAddress(additional_shipping_address_id)
});

$(document).on('dblclick', '.addlshipaddrbox', function(e){
    var additional_shipping_address_id = $(this).attr('data-val');
    var shippingaddr = $('#invoice_customer_name').val()+"\n";
    shippingaddr += $('#additional-shipping-address-description').val();

    $('#other-shipping-address').val(shippingaddr);
    $('#customerAddlShippingAddressModal').modal('hide');

    getAddlShippingAddress(additional_shipping_address_id)
});

function getAddlShippingAddress(additional_shipping_address_id){
    if(additional_shipping_address_id != '' && additional_shipping_address_id !=undefined){
        var customer_id = window.location.pathname.split('/').pop();
        $.ajax({
            url: getAdditionalShippingAddressURL, 
            type: 'post',
            data: {'additional_shipping_address_id':additional_shipping_address_id, 'customer_id':customer_id},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    var customerAddlShipAddrList = obj.customerAddlShipAddrList;
                    $('#additional_shipping_address_id').val(customerAddlShipAddrList.id);
                    $('#additional-shipping-address-description').val(customerAddlShipAddrList.additional_shipping_address_description);
                    $('#additional-shipping-full-address').val(customerAddlShipAddrList.additional_shipping_full_address);
                }else{
                    alert(obj.message);
                }
            }
        });
    }
}

$(document).on('change', '#invoice_ship_to', function(e){
    if($(this).val() == '3'){
        $('#other-shipping-address').prop('readonly', false);
    }else{
        $('#other-shipping-address').val('');
        $('#other-shipping-address').prop('readonly', true);
    }
});

$(document).on('click', '.deleteAdditionalShipAddr', function(e){
    var additional_shipping_id = $('.addlshipaddrbox-active').attr('data-val');
    var customer_id = window.location.pathname.split('/').pop();
    if(additional_shipping_id != '' && additional_shipping_id != undefined && customer_id != ''){
        $.ajax({
            url: deleteAddlShippingAddressURL, 
            type: 'post',
            data: {'id':additional_shipping_id, 'customer_id':customer_id},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    alert("Selected shipping address deleted successfully.");
                    var customerAddlShipAddrList = obj.customerAddlShipAddrList;
                    var shippingelem = '';
                    for(var i=0; i<customerAddlShipAddrList.length; i++){
                        shippingelem += '<div class="addlshipaddrbox" data-val="'+customerAddlShipAddrList[i]['id']+'">'+customerAddlShipAddrList[i]['additional_shipping_address_description']+'</div>';
                    }
                    $('.listofadlshipaddr').html(shippingelem);
                }else{
                    alert(obj.message);
                }
            }
        });
    }else{
        alert('Please select shipping address.');
    }
});

$(document).on('click', '.saveCompInspBtn', function(e){
    if($('#inspection_name').val() != '' && $('#inspection_name').val() !=undefined){
        $.ajax({
            url: saveAircraftComplInspectionsURL, 
            type: 'post',
            data: $("#frmAircraftComplInspections").serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    getNewAircraftComplainceItemBlock();

                    alert('Inspection detail added successfully.')
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('click', '.saveCompInspHistoryBtn', function(e){
    if($('#inspection_code').val() != '' && $('#inspection_code').val() !=undefined && $('#compliance_inspections_id').val() != ''){
        $.ajax({
            url: saveAircraftComplInspHistoryURL, 
            type: 'post',
            data: {'aircraft_inspection_id':$('#compliance_inspections_id').val(), 'inspection_code':$('#inspection_code').val(), 'insp_current_ac_tach':$('#insp_current_ac_tach').val(), 'date_override':$('#inspections_date_override').val()},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#complinsphistory').html(obj.tblrow);
                    $('#inspection_code').val('');
                    $('#insp_current_ac_tach').val('');
                    $('#inspections_date_override').val('');
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('click', '.complnavtab', function(e){
    $('#comp_tab_click').val($(this).text());
});

$(document).on('click', '.saveCompAirframeBtn', function(e){
    if($('#airframe_name').val() != '' && $('#airframe_name').val() !=undefined){
        $.ajax({
            url: saveAircraftComplAirframeURL, 
            type: 'post',
            data: $("#frmAircraftComplAirframe").serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    getNewAircraftComplainceItemBlock();

                    alert('Airframe detail added successfully.');
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('click', '.saveCompEngineBtn', function(e){
    if($('#engine_name').val() != '' && $('#engine_name').val() !=undefined){
        $.ajax({
            url: saveAircraftComplEnginesURL, 
            type: 'post',
            data: $("#frmAircraftComplEngines").serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    getNewAircraftComplainceItemBlock();

                    alert('Engine detail added successfully.');
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('dblclick', '.compliancelistdata', function(e){
    var compllistval = $(this).attr('data-val');
    compllistval = compllistval.split('-');
    if(compllistval.length>1){
        var section = compllistval[0];
        $.ajax({
            url: getAircraftComplianceDetailURL, 
            type: 'post',
            data: {'section':section, 'id':compllistval[1]},
            success: function (response) {
                if(response == 'Something went wrong, please try again'){
                    alert(response);
                }else{
                    if(section == 'inspections'){
                        $('.aircraftComplInspectionBlock').html(response);
                    }else if(section == 'airframe'){
                        $('.aircraftComplAirframeBlock').html(response);
                    }else if(section == 'engine'){
                        $('.aircraftComplEngineBlock').html(response);
                    }
                    $(".selectpicker").selectpicker("refresh");
                    overrideComplianceBlockDate();
                    $('#aircarftComplianceListModel').modal('hide');
                }
            }
        });
    }
});

$(document).on('click', '.complianceinspectionremove', function(e){
    var section = $('#comp_tab_click').val();
    section = section.toLowerCase();
    var id = '';
    if(section == 'inspections'){
        id = $('#compliance_inspections_id').val();
    }else if(section == 'airframe'){
        id = $('#compliance_airframe_id').val();
    }else if(section == 'engine'){
        id = $('#compliance_engine_id').val();
    }
    if(id != '' && id != undefined){
        $.ajax({
            url: removeAircraftComplianceDetailURL, 
            type: 'post',
            data: {'section':section, 'id':id, 'aircraft_id':$('#aircraft_id').val()},
            success: function (response) {
                if(response == 'Something went wrong, please try again'){
                    alert(response);
                }else{
                    if(section == 'inspections'){
                        $('.aircraftComplInspectionBlock').html(response);
                    }else if(section == 'airframe'){
                        $('.aircraftComplAirframeBlock').html(response);
                    }else if(section == 'engine'){
                        $('.aircraftComplEngineBlock').html(response);
                    }
                    $(".selectpicker").selectpicker("refresh");
                    overrideComplianceBlockDate();
                }
            }
        });
    }
});

$(document).on('click', '#maint_tach_is_flight_time', function(e){
    var labelheading = '';
    if($(this).is(':checked')){
        labelheading = 'A/C FlightTime';
    }else{
        labelheading = 'Current A/C Tach';
    }

    $('.maint_current_ac_tach').text(labelheading);
});

function overrideMaintenanceBlockDate(){
    $('#maint_next_annual, #maint_next_elt_date, #maint_next_corrosion, #maint_next_o2_bottle, #maint_next_far_91_411, #maint_reg_expires, #maint_next_far_91_413, #maint_warranty_date, #maint_ac_battery_date, #maint_last_oil_change_date, #maint_eng_oh_date, #maint_eng_cyl_date, #p_oh_date_l, #p_oh_date_r, #appliance_last_update, #appliance_due_date, #ad_revision_date, #ad_recurring_date').datetimepicker({
        format: 'MM-DD-YYYY'
    });

    $('#maint_last_oil_change_time, #appliance_time_due, #ad_recurring_time').datetimepicker({
        format: "HH:mm"
    });
}

function overrideWorkOrderOSRBlockDate(){
    $('#outside_date_due, #vendor_approval_expires, #po_date_order_placed, #po_general_est_arrival_date, #po_warranty_expires, #po_item_shelf_life, #po_item_estimated_arrival_date').datetimepicker({
        format: 'MM-DD-YYYY'
    });

    $('.osr_po_notify_datetime').datetimepicker({
        format:'MM-DD-YYYY HH:mm'
    });
}

$(document).on('click', '.input-group-addon', function(e){
    var ids = $(this).siblings("input").attr('id');
    $('#'+ids).datetimepicker('show');
});

$(document).on('click', '.saveAircraftMaintOverviewBtn', function(e){
    $.ajax({
        url: saveAircraftMaintOverviewURL,
        type: 'post',
        data: $("#frmAircraftMaintOverview").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('#maintenance_overview_id').val(obj.id);

                //alert('Overview detail added successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.saveAircraftMainEngine', function(e){
    $.ajax({
        url: saveAircraftMaintEngineURL,
        type: 'post',
        data: $("#frmAircraftMaintEngine").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('#maintenance_engine_id').val(obj.id);

                //alert('Engine detail saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.aircraftMaintEngCylSave', function(e){
    $('#aircraft_maint_eng_cyl_date').val($('#maint_eng_cyl_date').val());
    $('#aircarftMaintEngCylDateModel').modal('hide');

    $.ajax({
        url: saveAircraftMaintEngHistoryURL,
        type: 'post',
        data: $("#frmAircraftMaintEngineCylHistory").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                var tblrow = obj.tblrow;
                
                $('#maintenghistorytbl').append(tblrow);
                $("#frmAircraftMaintEngineCylHistory")[0].reset();
                alert('Cyclinder history saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.saveAircraftMaintProp', function(e){
    $.ajax({
        url: saveAircraftMaintPropURL,
        type: 'post',
        data: $("#frmAircraftMaintProp").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('#maintenance_prop_id').val(obj.id);

                //alert('Prop detail saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.saveAircraftMaintApplInfo', function(e){
    if($('#a_appliance').val() != '' && $('#a_appliance').val() != undefined){
        $.ajax({
            url: saveAircraftMaintApplianceInfoURL,
            type: 'post',
            data: $("#frmAircraftMaintAppliance").serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#maintenance_appliance_id').val(obj.id);
                    
                    $('#tblmaintappliancelist').html(obj.tblrow);

                    alert('Appliance detail saved successfully.');
                }else{
                    alert(obj.message);
                }
            }
        });
    }else{
        alert("Appliance can't be blank.");
        $('#a_appliance').focus();
    }
});

$(document).on('click', '.airmaintapplrow', function(e){
    if($(this).attr('data-val') != ''){
        $('.airmaintapplrow').removeClass('airmaintapplrow_active');
        $(this).addClass('airmaintapplrow_active');

        $.ajax({
            url: fetchAircraftMaintApplianceInfoURL,
            type: 'post',
            data: {'maintenance_appliance_id': $(this).attr('data-val')},
            async : true,
            success: function (response) {
                if(response != 'failure') {
                    $('#maintenance_appliances_block').html(response);
                    $('#editapplianceinfo').css('display', 'block');
                    overrideMaintenanceBlockDate();
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('click', '.addNewMaintApplianceBtn', function(e){
    $('.airmaintapplrow').removeClass('airmaintapplrow_active');
    $("#frmAircraftMaintAppliance .form-control").val('');
    $('#maintenance_appliance_id').val('');
    $('#editapplianceinfo').css('display', 'block');
});

$(document).on('click', '.removeMaintApplianceBtn', function(e){
    var id = $('#maintenance_appliance_id').val();
    
    if(id != '' && id != undefined){
        $.ajax({
            url: removeAircraftMaintApplianceDetailURL, 
            type: 'post',
            data: {'id':id},
            success: function (response) {
                if(response.status == 'failure'){
                    alert(response);
                }else{
                    $('.airmaintapplrow').each(function(index,item){
                        if($(this).attr('data-val') == id){
                            $(this).remove();
                        }
                    });

                    $("#frmAircraftMaintAppliance .form-control").val('');
                    $('#maintenance_appliance_id').val('');
                }
            }
        });
    }
});

$(document).on('click', '.saveAircraftMaintAdsInfo', function(e){
    if($('#ad_no').val() != '' && $('#ad_no').val() != undefined){
        $.ajax({
            url: saveAircraftMaintAdsInfoURL,
            type: 'post',
            data: $("#frmAircraftMaintAds").serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#maintenance_ad_id').val(obj.id);
                    
                    $('#tblmaintadslist').html(obj.tblrow);

                    alert('AD detail saved successfully.');
                }else{
                    alert(obj.message);
                }
            }
        });
    }else{
        alert("Ad No. can't be blank.");
        $('#ad_no').focus();
    }
});

$(document).on('click', '.airmaintadsrow', function(e){
    if($(this).attr('data-val') != ''){
        $('.airmaintadsrow').removeClass('airmaintadsrow_active');
        $(this).addClass('airmaintadsrow_active');

        $.ajax({
            url: fetchAircraftMaintAdsInfoURL,
            type: 'post',
            data: {'maintenance_ad_id': $(this).attr('data-val')},
            dataType: 'text',
            success: function (response) {
                if(response != 'failure') {
                    $('#maintenance_ad_block').html(response);

                    $('#editadinfo').css('display', 'block');
                    overrideMaintenanceBlockDate();
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('click', '.addNewMaintAdsBtn', function(e){
    $('.airmaintadsrow').removeClass('active');
    $("#frmAircraftMaintAds .form-control").val('');
    $('#maintenance_ad_id').val('');
    $('#editadinfo').css('display', 'block');
});

$(document).on('click', '.removeMaintAdsBtn', function(e){
    var id = $('#maintenance_ad_id').val();
    
    if(id != '' && id != undefined){
        $.ajax({
            url: removeAircraftMaintAdsDetailURL, 
            type: 'post',
            data: {'id':id},
            success: function (response) {
                if(response.status == 'failure'){
                    alert(response);
                }else{
                    $('.airmaintadsrow').each(function(index,item){
                        if($(this).attr('data-val') == id){
                            $(this).remove();
                        }
                    });

                    $("#frmAircraftMaintAds .form-control").val('');
                    $('#maintenance_ad_id').val('');
                }
            }
        });
    }
});

$(document).on('click', '.saveAircraftMaintNotesBtn', function(e){
    var maintenance_notes = $('#maintenance_notes').val();
    
    if(maintenance_notes != '' && maintenance_notes != undefined){
        $.ajax({
            url: saveAircraftMaintNotesURL, 
            type: 'post',
            data: $("#frmAircraftMaintNotes").serialize(),
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    alert("Notes saved successfully.");
                    $('#maintenance_note_id').val(obj.id);
                }
            }
        });
    }
});

$(document).on('click', '.updatemaintoverviewtimebtn', function(e){
    var aircraft_engine_type = $('#aircraft_engine_type').val();
    if(aircraft_engine_type == '3' || aircraft_engine_type == '4'){
        var engine_checked = 0;
        if($('#maint_engine1_use_engine').is(":checked") || $('#maint_engine2_use_engine').is(":checked")){
            engine_checked = 1;
        }
        if(engine_checked == '0'){
            if(confirm('You do not have `Use Engine` checked for the aircraft in the Maintenance > Engine Tab.\n\nContinue?')){
                checkWOLogBookValue();
            }
        }else{
            checkWOLogBookValue();
        }
    }else{
        checkWOLogBookValue();
    }
});

function checkWOLogBookValue(){
    var aircraft_id = $('#aircraft_id').val();
    var engine_type = $('#aircraft_engine_type').val();

    $.ajax({
        url: checkWorkOrderLogBookValueURL, 
        type: 'post',
        data: {aircraft_id:aircraft_id, engine_type:engine_type},
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'failure'){
                alert(obj.message);
            }else{
                if(obj.islogbookvalue > 0){
                    openUpdateLogbookValueOpenWOPopup();
                }
                updateAircraftMaintenanceTime();
            }
        }
    });
}

function updateAircraftMaintenanceTime(){
    var updatetimeradio = $('input[name="update_aircraft_maint_overview_time"]:checked').val();
    var aircraft_engine_type = $('#aircraft_engine_type').val();
    var maint_current_ac_tt = parseFloat($('#maint_current_ac_tt').val());
    var maint_hobbs = parseFloat($('#maint_hobbs').val());
    var maint_airswitch = parseFloat($('#maint_airswitch').val());
    var maint_current_ac_tach = $.trim(parseFloat($('#maint_current_ac_tach').val()));
    var aircraft_maint_overview_techtime = parseFloat($('#aircraft_maint_overview_techtime').val());

    if(aircraft_engine_type == '1' || aircraft_engine_type == '2'){
        if(maint_current_ac_tach == '' || maint_current_ac_tach == 'NaN'){
            alert("Your maintenance information is not set for this aircraft, so this must be set manually.");
            return false;
        }
    }

    if(maint_current_ac_tach != '' && updatetimeradio != undefined){
        var valcalfrom = maint_current_ac_tach;
        if($('#maint_use_hobbs_chkbox').is(":checked")){
            valcalfrom = maint_hobbs;
        }

        if(updatetimeradio == '1'){
            var hobtackval = 0;
            if($('#maint_use_hobbs_chkbox').is(":checked")){
                hobtackval = maint_hobbs;
                $('#maint_hobbs').val(aircraft_maint_overview_techtime);
            }else{
                hobtackval = maint_current_ac_tach;
                $('#maint_current_ac_tach').val(aircraft_maint_overview_techtime);
            }
            var curracttcalval = maint_current_ac_tt < maint_hobbs ? (parseFloat(aircraft_maint_overview_techtime)+parseFloat(maint_current_ac_tt)) - parseFloat(maint_hobbs) : parseFloat(aircraft_maint_overview_techtime)+1;

            var airswitchcal = maint_airswitch < maint_hobbs ? (parseFloat(aircraft_maint_overview_techtime)+parseFloat(maint_airswitch)) - parseFloat(maint_hobbs) : parseFloat(aircraft_maint_overview_techtime)+1;

            var enginetabcal = parseFloat(aircraft_maint_overview_techtime)-parseFloat(hobtackval);

            setEngineAndPopValue(enginetabcal);

        }else if(updatetimeradio == '2'){
            var curracttcalval = parseFloat(maint_current_ac_tt)+parseFloat(aircraft_maint_overview_techtime);
            var airswitchcal = parseFloat(maint_airswitch)+parseFloat(aircraft_maint_overview_techtime);
            
            if($('#maint_use_hobbs_chkbox').is(":checked")){
                var maint_current_ac_tachcal = parseFloat(maint_hobbs)+parseFloat(aircraft_maint_overview_techtime);
                $('#maint_hobbs').val(maint_current_ac_tachcal);
            }else{
                var maint_current_ac_tachcal = parseFloat(maint_current_ac_tach)+parseFloat(aircraft_maint_overview_techtime);
                $('#maint_current_ac_tach').val(maint_current_ac_tachcal);
            }

            setEngineAndPopValue(aircraft_maint_overview_techtime);
            
        }else if(updatetimeradio == '3' || updatetimeradio == '4'){
            if($('#maint_engine1_use_engine').is(":checked")){
                if($('#is_tc2').is(":checked")){
                    var maint_engine1_tc2 = $.trim($('#maint_engine1_tc2').val());
                    var calvaleng1 = parseFloat(maint_engine1_tc2)+parseFloat(aircraft_maint_overview_techtime);
                    $('#maint_engine1_tc2').val(calvaleng1);
                }else{
                    var maint_engine1_tc = $.trim($('#maint_engine1_tc').val());
                    var calvaleng1 = parseFloat(maint_engine1_tc)+parseFloat(aircraft_maint_overview_techtime);
                    $('#maint_engine1_tc').val(calvaleng1);
                }
            }
            if($('#maint_engine2_use_engine').is(":checked")){
                if($('#is_tc2').is(":checked")){
                    var maint_engine2_tc2 = $.trim($('#maint_engine2_tc2').val());
                    var calvaleng2 = parseFloat(maint_engine2_tc2)+parseFloat(aircraft_maint_overview_techtime);
                    $('#maint_engine2_tc2').val(calvaleng2);
                }else{
                    var maint_engine2_tc = $.trim($('#maint_engine2_tc').val());
                    var calvaleng2 = parseFloat(maint_engine2_tc)+parseFloat(aircraft_maint_overview_techtime);
                    $('#maint_engine2_tc').val(calvaleng2);
                }
            }
            if($('#maint_engine3_use_engine').is(":checked")){
                if($('#is_tc2').is(":checked")){
                    var maint_engine3_tc2 = $.trim($('#maint_engine2_tc3').val());
                    var calvaleng3 = parseFloat(maint_engine3_tc2)+parseFloat(aircraft_maint_overview_techtime);
                    $('#maint_engine2_tc2').val(calvaleng3);
                }else{
                    var maint_engine3_tc = $.trim($('#maint_engine3_tc').val());
                    var calvaleng3 = parseFloat(maint_engine3_tc)+parseFloat(aircraft_maint_overview_techtime);
                    $('#maint_engine3_tc').val(calvaleng3);
                }
            }
            if(updatetimeradio == '4'){
                var maint_airframe_lndgs = $.trim($('#maint_airframe_lndgs').val());
                var calvallandgs = maint_airframe_lndgs != '' && maint_airframe_lndgs != '0' ? parseFloat(maint_airframe_lndgs)+parseFloat(aircraft_maint_overview_techtime) : aircraft_maint_overview_techtime;
                $('#maint_airframe_lndgs').val(calvallandgs);
            }
        }

        $('#aircraftMaintenanceUpdtTimeModel').modal('hide');

        if(updatetimeradio == '1' || updatetimeradio == '2'){
            $('#maint_current_ac_tt').val(curracttcalval);
            $('#maint_airswitch').val(airswitchcal);

            $(".saveAircraftMaintOverviewBtn").click();
            $('.saveAircraftMainEngine').click();
            $('.saveAircraftMaintProp').click();
        }else if(updatetimeradio == '3'){
            $(".saveAircraftMaintOverviewBtn").click();
            $('.saveAircraftMainJetEngine').click();
            $('.saveAircraftMaintProp').click();
        }else if(updatetimeradio == '4'){
            $(".saveAircraftMaintOverviewBtn").click();
            $('.saveAircraftMainJetEngine').click();
        }
    }
}

function setEngineAndPopValue(enginetabcal){
    enginetabcal = parseFloat(enginetabcal);
    var maint_eng_tsmoh = parseFloat($('#maint_eng_tsmoh').val());
    maint_eng_tsmoh += enginetabcal;
    $('#maint_eng_tsmoh').val(maint_eng_tsmoh);

    var maint_eng_ttl = parseFloat($('#maint_eng_ttl').val());
    maint_eng_ttl += enginetabcal;
    maint_eng_ttl = maint_eng_ttl<0 ? 0 : maint_eng_ttl;
    $('#maint_eng_ttl').val(maint_eng_ttl);

    var maint_eng_tt_vac_pump = parseFloat($('#maint_eng_tt_vac_pump').val());
    maint_eng_tt_vac_pump += enginetabcal;
    maint_eng_tt_vac_pump = maint_eng_tt_vac_pump<0 ? 0 : maint_eng_tt_vac_pump;
    $('#maint_eng_tt_vac_pump').val(maint_eng_tt_vac_pump);

    var maint_eng_tsn = parseFloat($('#maint_eng_tsn').val());
    maint_eng_tsn += enginetabcal;
    maint_eng_tsn = maint_eng_tsn<0 ? 0 : maint_eng_tsn;
    $('#maint_eng_tsn').val(maint_eng_tsn);

    var maint_eng_tbo = parseFloat($('#maint_eng_tbo').val());
    maint_eng_tbo += enginetabcal;
    maint_eng_tbo = maint_eng_tbo<0 ? 0 : maint_eng_tbo;
    $('#maint_eng_tbo').val(maint_eng_tbo);

    var p_tspoh_l = parseFloat($('#p-tspoh-l').val());
    p_tspoh_l += enginetabcal;
    p_tspoh_l = p_tspoh_l<0 ? 0 : p_tspoh_l;
    $('#p-tspoh-l').val(p_tspoh_l);

    var p_tt_l = parseFloat($('#p-tt-l').val());
    p_tt_l += enginetabcal;
    p_tt_l = p_tt_l<0 ? 0 : p_tt_l;
    $('#p-tt-l').val(p_tt_l);

    var p_last_prop_balance_l = parseFloat($('#p-last-prop-balance-l').val());
    p_last_prop_balance_l += enginetabcal;
    p_last_prop_balance_l = p_last_prop_balance_l<0 ? 0 : p_last_prop_balance_l;
    $('#p-last-prop-balance-l').val(p_last_prop_balance_l);

    var p_tsn_l = parseFloat($('#p-tsn-l').val());
    p_tsn_l += enginetabcal;
    p_tsn_l = p_tsn_l<0 ? 0 : p_tsn_l;
    $('#p-tsn-l').val(p_tsn_l);

    var p_tbo = parseFloat($('#p-tbo').val());
    p_tbo += enginetabcal;
    p_tbo = p_tbo<0 ? 0 : p_tbo;
    $('#p-tbo').val(p_tbo);
}

$(document).on('click', '.selectall_maintenance_woitem', function(e){
    $("input[name='workorderids[]']").prop('checked', true);
});

$(document).on('click', '.deselectall_maintenance_woitem', function(e){
    $("input[name='workorderids[]']").prop('checked', false);
});

$(document).on('dblclick', '.customerlsttr', function(e){
    if($(this).attr('data-val') != '' && $(this).attr('data-val') != undefined){
        window.location.href= customerInfoURL+'/'+$(this).attr('data-val');
    }
});

$(document).on('click', '.saveOTCInfoBtn', function(e){
    var otcinfo_customer_name = $('#otcinfo_customer_name').val();
    
    if(otcinfo_customer_name != '' && otcinfo_customer_name != undefined){
        $.ajax({
            url: saveCustomerOTCInfoURL, 
            type: 'post',
            data: $("#frmCustomerOTCInfo").serialize(),
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    $('.createotcinvoicebtn').prop('disabled', false);
                    alert("OTC Info saved successfully.");
                }
            }
        });
    }
});

$(document).on('change keyup', '#give_discount, #price_each, #give_discount_percentage', function(e){
    var price_each = $('#price_each').val();
    var give_discount_percentage = $('#give_discount_percentage').val();

    var calpercentage = 0;
    if(give_discount_percentage > '0' && $('#give_discount').is(':checked')){
        calpercentage = (give_discount_percentage*price_each)/100;
        calpercentage = calpercentage.toFixed(2);
    }
    var caltotalprice = price_each-calpercentage;

    $('#part_total_prices').val(caltotalprice);
});

$(document).on('click', '.saveCustomerOTCInvoiceBtn', function(e){
    var otc_invoice_no = $('#otc_invoice_no').val();
    
    if(otc_invoice_no != '' && otc_invoice_no != undefined){
        $.ajax({
            url: saveCustomerOTCInfoInvoiceURL, 
            type: 'post',
            data: $("#frmCustomerOTCInfoInvoice").serialize(),
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    alert("OTC Info Invoice saved successfully.");
                }
            }
        });
    }
});

$(document).on('click', '.otc-invoice-addpart-btn', function(e){
    $('#otc_invoice_id').val($('#customer_otc_invoice_id').val());
    var invoice_part_number = $('#invoice_part_number').val();
    var otc_invoice_id = $('#otc_invoice_id').val();
    var addbtnevent = $(this).attr('data-val');

    if(invoice_part_number != '' && invoice_part_number != undefined && otc_invoice_id != ''){
        $.ajax({
            url: saveCustomerOTCInfoInvoicePartURL, 
            type: 'post',
            data: $("#frmCustomerOTCInfoInvoicePart").serialize(),
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    $("#frmCustomerOTCInfoInvoicePart")[0].reset()
                    
                    $('#otcinfoinvoicetbl').html(obj.otcinfotblrow);
                    $('#otcinfoinvoicehisttbl').html(obj.invoiceparthisttblrow);
                    $('#otc_invoice_total_parts_subtotal').val(obj.totalpartsubtotal);

                    alert("Invoice Part saved successfully.");
                    if(addbtnevent == 'part-add-close'){
                        $('#customerOTCInvoiceAddPartModel').modal('hide');
                    }
                }
            }
        });
    }
});

$(document).on('click', '.otcinvoicetblrow', function(e){
    $('.otcinvoicetblrow').removeClass('otcinvoicetblrow_active');
    $(this).addClass('otcinvoicetblrow_active');
});

$(document).on('click', '.remove_item_otcinvoice', function(e){
    var otc_invoice_part_id = $('.otcinvoicetblrow_active').attr('data-val');
    if(otc_invoice_part_id != '' && otc_invoice_part_id != undefined){
        $.ajax({
            url: removeCustomerOTCInfoInvoicePartURL, 
            type: 'post',
            data: {otc_invoice_part_id:otc_invoice_part_id},
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    alert("Invoice Part deleted successfully.");

                    $('#otcinfoinvoicetbl').html(obj.otcinfotblrow);
                    $('#otcinfoinvoicehisttbl').html(obj.invoiceparthisttblrow);
                    $('#otc_invoice_total_parts_subtotal').val(obj.totalpartsubtotal);

                }
            }
        });
    }
});

$(document).on('click', '.customerinfotab', function(e){
    if($(this).attr('href') != '#customerInfo'){
        $('.customerinfodelbtn').prop('disabled', true);
    }else{
        $('.customerinfodelbtn').prop('disabled', false);
    }
});

$(document).on('dblclick', '.otcinfotblrow', function(e){
    if($(this).attr('data-val') != ''){
        $('.otcinfotblrow').removeClass('active');
        $(this).addClass('active');

        var section = 'confirm_create_otc_invoice_btn';
        if(section != '' && section != undefined){
            var customer_id = window.location.pathname.split('/').pop();
            var otc_invoice_id = $(this).attr('data-val');
            var dataval = {section:section, customer_id:customer_id, otc_invoice_id:otc_invoice_id, 'aircraft_id':''};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('dblclick', '.otcinvoicetblrow', function(e){
    if($(this).attr('data-val') != ''){
        $('.otcinvoicetblrow').removeClass('active');
        $(this).addClass('active');

        var section = 'create_otc_invoice_ad_part_btn';
        if(section != '' && section != undefined){
            var customer_id = window.location.pathname.split('/').pop();
            var otc_invoice_part_id = $(this).attr('data-val');
            var dataval = {section:section, customer_id:customer_id, otc_invoice_part_id:otc_invoice_part_id, 'aircraft_id':''};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('dblclick', '.aircraftworkordertr', function(e){
    var work_order_id = $(this).attr('data-val');
    if(work_order_id != '' && work_order_id != undefined){
        $('.aircraftworkordertr').removeClass('aircraftworkordertr_active');
        $(this).addClass('aircraftworkordertr_active');

        var section = 'aircraft_create_wo_btn';
        if(section != '' && section != undefined){
            var customer_id = window.location.pathname.split('/').pop();
            var aircraft_id = $('#aircraft_id').val();
            var dataval = {section:section, customer_id:customer_id, work_order_id:work_order_id, aircraft_id:aircraft_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.saveAircraftWODetBTN', function(e){
    var btnclickattr = $(this).attr('data-val');
    
    submitWOFormData(btnclickattr, '', '');
});

$(document).on('dblclick', '.wo-technican-list div', function(e){
    var repair_technician = $(this).attr('data-val');
    if(repair_technician != '' && repair_technician != undefined){
        var wo_item_id = $('#wo_item_id').val();
        $.ajax({
            url: saveAircraftWOServicesTechnicianURL, 
            type: 'post',
            data: {'repair_technician':repair_technician, 'wo_item_id':wo_item_id},
            async : true,
            success: function (response) {
                if(response == 'Failure'){
                    alert('Something went wrong, please try again.');
                }else{
                    $('#aircraftWOTechnicianAddModal').modal('hide');
                    $('.workorderservicesblock').html(response);
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    }
});

function submitWOFormData(btnclickattr, dataval, seltabid){
    var wo_customer_info = $('#wo-customer-info').val();
    
    if(wo_customer_info != '' && wo_customer_info != undefined){
        $.ajax({
            url: saveAircraftWorkOrderDetURL, 
            type: 'post',
            data: $("#frmAircraftWorkOrder, #frmAircraftWorkOrderItems, #frmAircraftWorkOrderItemOverviews, #frmAircraftWorkOrderItemServices").serialize(),
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    //$('#wo_item_id').val(obj.wo_item_id);
                    $('#wo_overviews_id').val(obj.wo_overviews_id);
                    $('#wo_services_id').val(obj.wo_services_id);
                    if(btnclickattr == 'wo_save_btn'){
                        alert("Work Order detail saved successfully.");
                    }else{
                        saveAndGetAircraftWODet(dataval, seltabid);
                    }
                }
            }
        });
    }
}

$(document).on('click', '.wo_status_continue_btn', function(e){
    var completionpassword = $.trim($('#wo_completion_password').val());
    var work_order_id = $('#work_order_id').val();
    if(completionpassword != '' && work_order_id!= ''){
        var wo_status = $('#aircraft_work_order_status').val();
        var old_wo_status = $('#old_work_order_status').val();

        $.ajax({
            url: validateWOStatusComplPasswordURL, 
            type: 'post',
            data: {'completionpassword':completionpassword, 'work_order_id':work_order_id, wo_status:wo_status},
            async : true,
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    $('#aircraft_work_order_status').val(old_wo_status);
                    $('.selectpicker').selectpicker('refresh');
                    
                    alert(obj.message);
                }else{
                    $('#aircarftWOStatusComplPwdModel').modal('hide');
                }
            }
        });
    }
});

$(document).on('click', '.wo_status_close_btn', function(e){
    var old_wo_status = $('#old_work_order_status').val();
    $('#aircraft_work_order_status').val(old_wo_status);
    $('.selectpicker').selectpicker('refresh');
});

$(document).on('click', '.wo-service-technician-list', function(e){
    var repair_technician = $(this).attr('data-val');
    var wo_item_id = $('#wo_item_id').val();
    var wo_services_id = $('#wo_services_id').val();

    $('.wo-service-technician-list').removeClass('wo-service-technician-list-active');
    $(this).addClass('wo-service-technician-list-active');

    $.ajax({
        url: fetchWorkOrderServicesHtmlURL, 
        type: 'post',
        data: {'repair_technician':repair_technician, 'wo_item_id':wo_item_id, 'wo_services_id':wo_services_id},
        async : true,
        success: function (response) {
            $('.workorderservicesblock').html(response);
            $('.selectpicker').selectpicker('refresh');
        }
    });
});

$(document).on('keypress', '#service-add-time', function (e) {
    if (e.which == 13) {
        saveAircraftWOServicesTechnician();
    }
});

function saveAircraftWOServicesTechnician(){
    $.ajax({
        url: saveAircraftWOServicesTechnicianURL, 
        type: 'post',
        data: $('#frmAircraftWorkOrderItemServices').serialize(),
        async : true,
        success: function (response) {
            if(response == 'Failure'){
                alert('Something went wrong, please try again.');
            }else{
                $('.workorderservicesblock').html(response);
                $('.selectpicker').selectpicker('refresh');
            }
        }
    });
}

$(document).on('click', '.woservicenotebtn', function(e){
    var wo_services_id = $('#wo_services_id').val();
    if(wo_services_id != undefined){
        var section = 'add_wo_services_note_btn';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var aircraft_id = $('#wo_aircraft_id').val();
            var dataval = {section:section, customer_id:customer_id, wo_services_id:wo_services_id, aircraft_id:aircraft_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.saveWOServicesNotesbtn', function(e){
    var service_notes = $('#service_notes').val();
    var wo_services_id = $('#wo_services_id').val();
    if(service_notes != '' && service_notes != undefined && wo_services_id != undefined){
        $.ajax({
            url: saveAircraftWOServicesNoteURL, 
            type: 'post',
            data: {wo_services_id:wo_services_id, service_notes:service_notes},
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    alert("Work Order note saved successfully.");
                    $('#aircraftWOServicesNotesModel').modal('hide');
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    }
});

$(document).on('click', '.services_add_technician_btn', function(e){
    var wo_item_id = $('#wo_item_id').val();
    if(wo_item_id != undefined){
        var section = 'add_wo_technician_btn';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var aircraft_id = $('#wo_aircraft_id').val();
            var dataval = {section:section, customer_id:customer_id, wo_item_id:wo_item_id, aircraft_id:aircraft_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.deleteWOServicesBtn', function(e){
    var repair_technician = '';
    var wo_item_id = $('#wo_item_id').val();
    var wo_services_id = $('#wo_services_id').val();

    if(wo_item_id != '' && wo_item_id != undefined && wo_services_id != undefined){
        $.ajax({
            url: deleteAircraftWOServicesURL, 
            type: 'post',
            data: {wo_services_id:wo_services_id, wo_item_id:wo_item_id, repair_technician:repair_technician},
            success: function (response) {
                if(response == 'failure'){
                    alert('Something went wrong, please try again.');
                }else{
                    alert("Work Order service deleted successfully.");
                    $('.workorderservicesblock').html(response);
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    }
});

$(document).on('click', '.saveAircraftWOOSRBtn', function(e){
    var osr_repair_done_by = $('#osr_repair_done_by').val();
    
    if(osr_repair_done_by != '' && osr_repair_done_by != undefined){
        $.ajax({
            url: saveAircraftWOItemOSRInfoURL, 
            type: 'post',
            data: $("#frmAircraftWOOSR").serialize(),
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    $('#wo_osrinfo_id').val(obj.wo_osrinfo_id);
                    $('.wo-osr-list').html(obj.osrlist);
                    $('.selectpicker').selectpicker('refresh');
                    
                    alert("Outside Repair Information saved successfully.");
                }
            }
        });
    }
});

$(document).on('dblclick click', '.editwoosritem', function(e){
    if(e.type == 'click'){
        $('.editwoosritem').removeClass('wo-osr-list-active');
        $(this).addClass('wo-osr-list-active');

        return false;
    }
    var wo_osr_id = $(this).attr('data-val');
    var wo_item_id = $('#wo_item_id').val();
    var work_order_id = $('#work_order_id').val();
    if(wo_osr_id != '' && wo_osr_id != undefined){
        $('.editwoosritem').removeClass('wo-osr-list-active');
        $(this).addClass('wo-osr-list-active');
        
        var section = 'aircarft_wo_add_outside_repair';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var aircraft_id = $('#wo_aircraft_id').val();
            var dataval = {section:section, customer_id:customer_id, wo_osr_id:wo_osr_id, aircraft_id:aircraft_id, wo_item_id:wo_item_id, work_order_id:work_order_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.addoutsiderepair', function(e){
    var wo_item_id = $('#wo_item_id').val();
    var work_order_id = $('#work_order_id').val();
    var section = 'aircarft_wo_add_outside_repair';
    if(section != '' && section != undefined){
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, wo_item_id:wo_item_id, work_order_id:work_order_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.newwosorrecordbtn', function(e){
    var wo_item_id = $('#osrinfo_item_id').val();
    var work_order_id = $('#osrinfo_wo_id').val();
    $.ajax({
        url: fetchWOOSRCreateHtmlURL, 
        type: 'post',
        data: {'wo_item_id':wo_item_id, 'work_order_id':work_order_id},
        async : true,
        success: function (response) {
            $('#newwoosrhtmlblock').html(response);
            $('.selectpicker').selectpicker('refresh');
            overrideWorkOrderOSRBlockDate();
        }
    });
});

$(document).on('click', '.osrPOCurrentYesBtn', function(e){
    var po_no = $('.osrcurrentponumber').html();
    $('#is_add_to_po').val('1');
    $('#osr_purchase_order_no').val(po_no);

    $('#woOSRAddToCurrentPOModal').modal('hide');
    $('#woOSRAddToCurrentPOModal').remove();
    
    $('.saveAircraftWOOSRBtn').click();
});

$(document).on('click', '.woosraddtoporobtn', function(e){
    var addtoporo = $(this).attr('data-val');
    if(addtoporo != '' && addtoporo != undefined){
        var osr_purchase_order_no = $('#osr_purchase_order_no').val();
        var osr_invoice_no = $('#osr_invoice_no').val();
        var vendor_id = $('#osr_repair_done_by').val();

        if(vendor_id == ''){
            alert('Please select Repair Done By.');
            return false;
        }else if($('#osr-part-number').val() == '' && addtoporo == '1'){
            alert('Please enter a part number.');
            return false;
        }else if(osr_purchase_order_no != '' && addtoporo == '1'){
            return false;
        }else if(osr_invoice_no != '' && addtoporo == '2'){
            return false;
        }

        if(addtoporo == '2'){
            setOSRPOROData(addtoporo);
        }else{
            var vendor_name = $("#osr_repair_done_by option:selected").text();
            if(vendor_id == '' || vendor_id == undefined){
                alert('Please select repair done by.');
                return false;
            }else{
                $.ajax({
                    url: getExistingPONumByVendorIdURL, 
                    type: 'post',
                    data: {vendor_id:vendor_id, vendor_name:vendor_name},
                    success: function (response) {
                        if($.trim(response) == 'Not Exist'){
                            setOSRPOROData('1');
                        }else if(response == 'Failed'){
                            alert('Something went wrong, please try again.');
                        }else{
                            $('#woOSRAddToCurrentPOModal').remove();
                            $("#customerotcpopup").append(response);
                            $('.selectpicker').selectpicker('refresh');
                            $('#woOSRAddToCurrentPOModal').modal('show');
                        }
                    }
                });
            }
        }
    }
});

$(document).on('click', '.osrPOCurrentNoBtn', function(e){
    var addtoporo = $(this).attr('data-val');
    $('#woOSRAddToCurrentPOModal').modal('hide');
    $('#woOSRAddToCurrentPOModal').remove();
    setOSRPOROData(addtoporo);
});

function setOSRPOROData(addtoporo){
    if(addtoporo != '' && addtoporo != undefined){
        var osr_invoice_no = $('#osr_invoice_no').val();
        var osr_purchase_order_no = $('#osr_purchase_order_no').val();
        if($('#osr-part-number').val() == '' && addtoporo == '1'){
            alert('Please enter a part number.');
            return false;
        }
        
        $.ajax({
            url: setOSRPORODataURL, 
            type: 'post',
            data: {'addtoporo':addtoporo, 'osr_invoice_no':osr_invoice_no, 'osr_purchase_order_no':osr_purchase_order_no},
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    if(addtoporo == '1'){
                        $('#is_add_to_po').val('1');
                        $('#osr_purchase_order_no').val(obj.osr_purchase_order_no);
                    }else{
                        $('.woosr-ro-list-sec').css('display', 'block');
                        $('#is_create_new_ro').val('1');
                        $('#osr_invoice_no').val(obj.osr_invoice_no);
                    }

                    $('.saveAircraftWOOSRBtn').click();
                }
            }
        });
    }
}

$(document).on('click', '.wo-vendor-list-sec', function(e){
    var vendor_id = $('#osr_repair_done_by').val();
    
    var section = 'aircraft_wo_osr_vendor';
    if(section != '' && section != undefined){
        var customer_id = $('#wo_customer_id').val();
        var dataval = {section:section, customer_id:customer_id, vendor_id:vendor_id, 'aircraft_id':''};

        fetchOTCCustomPopupDataFromServer(section, dataval);
        
    }

    if(vendor_id == '' || vendor_id == undefined){
        var section = 'aircraft_create_new_wo_osr_vendor';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var dataval = {section:section, customer_id:customer_id, 'aircraft_id':''};

            fetchOTCCustomPopupDataFromServer(section, dataval);
            
        } 
    }
    
});

$(document).on('click', '.saveWOOSRVendorBtn', function(e){
    var vendor_name = $('#vendor_name').val();
    if(vendor_name != '' && vendor_name != undefined){
        var dataval = {'vendor_name':vendor_name, 'source':'new_vendor'};

        saveWOOSRVendorDetail(dataval, 'new_vendor');
    }
});

$(document).on('click', '.saveWOOSRVendorInfoBtn', function(e){
    var dataval = $('#frmAircraftWOOSRVendorInfo, #frmAircraftWOOSRVendorNotes').serialize();
    saveWOOSRVendorDetail(dataval, '');
});

function saveWOOSRVendorDetail(dataval, source){
    $.ajax({
        url: saveWOOSRVendorDetailURL, 
        type: 'post',
        data: dataval,
        async : true,
        success: function (response) {
            if(response == 'failure'){
                alert('Something went wrong, please try again');
            }else if(response == 'duplicate'){
                var vendor_name = dataval.vendor_name;
                alert('Vendor `'+vendor_name+'` already exist.');
            }else{
                if(source == 'new_vendor'){
                    $('#osrvendorcontactinfo').html(response);
                    var vendor_id = $('#osr_vendor_id').val();
                    var vendor_name = dataval.vendor_name;
                    $('#vendor-name').val(vendor_name);
                    $('#osrvendorheading').html(vendor_name);
                    $("#osr_repair_done_by").append('<option value="'+vendor_id+'" selected>'+vendor_name+'</option>');
                    $("#osr_repair_done_by").selectpicker("refresh");

                    $('#addNewWOOSRVendorModal').modal('hide');
                }else{
                    alert("Vendor saved successfully.");
                }
            }
        }
    });
}

$(document).on('dblclick', '.woosrvendorlsttr', function(e){
    var vendor_id = $(this).attr('data-val');
    var vendor_name = $(this).closest("tr").children("td:first").text();
    if(vendor_id != '' && vendor_id != undefined){
        $.ajax({
            url: fetchWOOSRCreateVendorHtmlURL, 
            type: 'post',
            data: {vendor_id:vendor_id},
            async : true,
            success: function (response) {  
                $('#aircraftWOOSRVendorListModal').modal('hide');
                $('#osrvendorcontactinfo').html(response);
                $('#vendor-name').val(vendor_name);
                $('#osrvendorheading').html(vendor_name);
                $('.selectpicker').selectpicker('refresh');
            }
        });
    }
});

$(document).on('click', '.woosrvendortab', function(e){
    if($(this).html() == 'Contact Info' || $(this).html() == 'Notes'){
        $('.saveWOOSRVendorInfoBtn').css('display', '');
    }else{
        $('.saveWOOSRVendorInfoBtn').css('display', 'none');
    }
});

$(document).on('click', '.woOSRVendorMediaPopup', function(e){
    var osr_vendor_id = $('#osr_vendor_id').val();
    if(osr_vendor_id != ''){
        var section = 'aircraft_wo_osr_vendor_media';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var dataval = {section:section, customer_id:customer_id, osr_vendor_id:osr_vendor_id, 'aircraft_id':''};

            fetchOTCCustomPopupDataFromServer(section, dataval);
            
        }
    }
});

$(document).on('click', '.saveOSRVendorInfoMedia', function(e){
    $.ajax({
        url: saveWOOSRVendorMediaURL, 
        type: 'post',
        data: $('#frmWOOSRVendorMedia').serialize(),
        dataType: 'text',
        success: function (response) {
            if(response.status == 'failure'){
                alert(response.message);
            }
            $('#woOSRVendorMediaModel').modal('hide');
        }
    });
});

$(document).on('click', '.deleteWOOSRVendorMedia', function (e) {
    if($(this).attr('data-val') != undefined){
        $(this).parent().parent().remove();
        $.ajax({
            url: deleteWOOSRVendorMediaURL, 
            type: 'POST',
            data: {'id':$(this).attr('data-val')},
            dataType: "text",
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status != 'success') {
                    alert(obj.message);
                }
            }
        });
    }else{
        $(this).parent().parent().remove();
    }
    
});

$(document).on('click', '.deleteWOOSRVendor', function (e) {
    var osr_vendor_id = $('#osr_vendor_id').val();
    if(osr_vendor_id != '' && osr_vendor_id != undefined){
        $.ajax({
            url: deleteWOOSRVendorURL, 
            type: 'POST',
            data: {'osr_vendor_id':osr_vendor_id},
            success: function (response) {
                var obj = JSON.parse(response);
                alert(obj.message);
                if(obj.status == 'success') {
                    $('#woOSRVendorModal').modal('hide');
                    $("#osr_repair_done_by option[value='"+osr_vendor_id+"']").remove();
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    }    
});

$(document).on('click', '.woosr-po-link', function(e){
    if($('#osr_purchase_order_no').val() == '' || $('#osr_purchase_order_no').val() == '0'){
        alert('Pleaes enter a P/O number and try again.');
        return false;
    }else if($('#is_add_to_po').val() != '1'){
        alert("This P/O number specified does not exist.");
        return false;
    }
    var osr_purchase_order_no = $('#osr_purchase_order_no').val();
    if(osr_purchase_order_no != ''){
        var section = 'aircraft_wo_osr_service_po';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var dataval = {section:section, customer_id:customer_id, osr_purchase_order_no:osr_purchase_order_no, 'aircraft_id':''};

            fetchOTCCustomPopupDataFromServer(section, dataval);
            
        }
    }
});

$(document).on('change', '#osr_po_vendor_id', function(e){
    var vendor_id = $(this).val();
    if(vendor_id != ''){
        $.ajax({
            url: fetchWOOSRVendorPhonesURL, 
            type: 'post',
            data: {vendor_id:vendor_id},
            async : true,
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success'){
                    var phoneoptions = '';
                    var woosrvendorphones = obj.woosrvendorphones;
                    $.each(woosrvendorphones, function (key, val) {
                        phoneoptions += '<option value="'+key+'">'+val+'</option>';
                    });
                    
                    $('#osr_po_vendor_phone').html(phoneoptions);
                    $('.selectpicker').selectpicker('refresh');
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('click', '.saveWOOSRServicePOBtn', function(e){
    var dataval = $('#frmWOSRServicePO').serialize();
    saveWOSRServicePOData(dataval);
});

$(document).on('click', '.saveWOOSRPOCheckInLabor', function(e){
    var dataval = $('#frmWOSRServicePOCheckInLabor, #frmWOSRServicePOCheckInLaborInfo').serialize();
    var sectionName = 'checkin_labor';
    saveOSRPOItemsDetail(dataval, sectionName);
});

$(document).on('click', '.osrMarkArrivedBtn', function(e){
    var dataval = {'osr_po_item_id':$('#osr_po_item_id').val(), 'mark_arrived':'1'};
    var sectionName = 'mark_arrived';
    saveOSRPOItemsDetail(dataval, sectionName);
});

$(document).on('click', '.saveWOOSRPOItemsBtn', function(e){
    var dataval = $('#frmWOSRPOItems').serialize();
    var sectionName = 'po_item';
    saveOSRPOItemsDetail(dataval, sectionName);
});

function saveOSRPOItemsDetail(dataval, sectionName){
    $.ajax({
        url: saveWOOSRPOItemsURL, 
        type: 'post',
        data: dataval,
        success: function (response) {
            var obj = JSON.parse(response);
            
            $('#otcosrpoitemstbl').html(obj.serviceitem_po_tr);
            $('#tot-purchase-order-amt').val(obj.total_purchase_order);
            if(obj.status != 'success') {
                alert(obj.message);
            }else{
                if(sectionName == 'po_item'){
                    $('#aircraftWOOSRPOItemModel').modal('hide');
                }
                alert("Record saved successfully");
            }
        }
    });
}

$(document).on('click', '.saveWOOSRAddlTrackingNum', function(e){
    var dataval = $('#frmWOOSRInfoPOTrackingNum').serialize();
    saveWOSRServicePOData(dataval);
});

$(document).on('click', '.saveWOOSRServicePONotesbtn', function(e){
    var dataval = $('#frmAircraftWOOSRServicePONotes').serialize();
    saveWOSRServicePOData(dataval);
});

function saveWOSRServicePOData(dataval){
    $.ajax({
        url: saveWOOSRServicePOURL, 
        type: 'post',
        data: dataval,
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status != 'success') {
                alert(obj.message);
            }else{
                $('#osr_infopoes_id').val(obj.osr_infopoes_id);
                alert("Service PO detail saved successfully");
            }
        }
    });
}

$(document).on('change', '#osr_po_status', function(e){
    setOSRPOFieldsEnableDisable();
});

function setOSRPOFieldsEnableDisable(){
    if($('#osr_po_status').val() != '1'){
        $('#osr_po_vendor_id').prop('disabled', true);
        $('#osr_po_vendor_phone').prop('disabled', true);
        $('#po_date_order_placed').prop('disabled', true);
        $('#po_general_est_arrival_date').prop('disabled', true);
        $('#po_term').prop('disabled', true);
        $('#po_created_by').prop('disabled', true);
        $('#vendor_contact').prop('disabled', true);
        $('#po_total_shipping_cost').prop('disabled', true);
        $('#po_currency').prop('disabled', true);
        $('#po_ship_to').prop('disabled', true);
        $('#po_full_address_info').prop('disabled', true);
        $('#po_ship_method').prop('disabled', true);
        $('#po_rma_number').prop('disabled', true);
        $('#po_tracking_number').prop('disabled', true);
    }else{
        $('#osr_po_vendor_id').prop('disabled', false);
        $('#osr_po_vendor_phone').prop('disabled', false);
        $('#po_date_order_placed').prop('disabled', false);
        $('#po_general_est_arrival_date').prop('disabled', false);
        $('#po_term').prop('disabled', false);
        $('#po_created_by').prop('disabled', false);
        $('#vendor_contact').prop('disabled', false);
        $('#po_total_shipping_cost').prop('disabled', false);
        $('#po_currency').prop('disabled', false);
        $('#po_ship_to').prop('disabled', false);
        $('#po_full_address_info').prop('disabled', false);
        $('#po_ship_method').prop('disabled', false);
        $('#po_rma_number').prop('disabled', false);
        $('#po_tracking_number').prop('disabled', false);
    }
    $('.selectpicker').selectpicker('refresh');
}

$(document).on('click', '.osr-more-tracking-number', function(e){
    var osr_infopoes_id = $('#osr_infopoes_id').val();
    if(osr_infopoes_id != ''){
        var section = 'aircraft_wo_osr_po_tracking_num';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            
            var dataval = {section:section, customer_id:customer_id, osr_infopoes_id:osr_infopoes_id, 'aircraft_id':''};
            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

function fetchOTCCustomPopupDataFromServer(section, dataval){
    $.ajax({
        url: fetchCustomerOTCPopupURL, 
        type: 'post',
        data: dataval,
        async : true,
        success: function (response) {
            appendCustomerOTCPopupData(section, response);
        }
    });
}

$(document).on('click', '.wo-osr-service-po-notes', function(e){
    var osr_infopoes_id = $('#osr_infopoes_id').val();
    if(osr_infopoes_id != ''){
        var section = 'aircraft_wo_osr_service_po_notes';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var dataval = {section:section, customer_id:customer_id, osr_infopoes_id:osr_infopoes_id, 'aircraft_id':''};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.wo-osr-po-checkin-labor', function(e){
    var osr_po_status = $('#osr_po_status').val();
    if(osr_po_status != '4'){
        alert('The status of the Purchase Order must me `Ordered`');
        return false;
    }else{
        var osr_infopoes_id = $('#osr_infopoes_id').val();
        if(osr_infopoes_id != ''){
            var section = 'aircraft_wo_osr_po_checkin_labor';
            if(section != '' && section != undefined){
                var customer_id = $('#wo_customer_id').val();
                var dataval = {section:section, customer_id:customer_id, osr_infopoes_id:osr_infopoes_id, 'aircraft_id':''};

                fetchOTCCustomPopupDataFromServer(section, dataval);
            }
        }
    }
});

$(document).on('click', '.wo-osr-po-media-btn', function(e){
    var osr_info_po_id = $('#osr_infopoes_id').val();
    if(osr_info_po_id != ''){
        var section = 'aircraft_wo_osr_po_media';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var dataval = {section:section, customer_id:customer_id, osr_info_po_id:osr_info_po_id, 'aircraft_id':''};

            fetchOTCCustomPopupDataFromServer(section, dataval);
            
        }
    }
});

$(document).on('click', '.saveOSRPurchaseOrderMedia', function(e){
    $.ajax({
        url: saveWOOSRPurchaseOrderMediaURL, 
        type: 'post',
        data: $('#frmWOOSRServicePOMedia').serialize(),
        dataType: 'text',
        success: function (response) {
            if(response.status == 'failure'){
                alert(response.message);
            }else{
                alert("Upload file saved successfully");
                $('#woOSRServicePOMediaModel').modal('hide');
            }
        }
    });
});

$(document).on('click', '.deleteWOOSRPurchaseOrderMedia', function (e) {
    if($(this).attr('data-val') != undefined){
        $(this).parent().parent().remove();
        $.ajax({
            url: deleteWOOSRPurchaseOrderMediaURL, 
            type: 'POST',
            data: {'id':$(this).attr('data-val')},
            dataType: "text",
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status != 'success') {
                    alert(obj.message);
                }
            }
        });
    }else{
        $(this).parent().parent().remove();
    }
    
});

$(document).on('click', '.wo-osr-po-reminder-btn', function(e){
    var osr_info_po_id = $('#osr_infopoes_id').val();
    if(osr_info_po_id != ''){
        var section = 'aircraft_wo_osr_po_reminder';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var dataval = {section:section, customer_id:customer_id, osr_info_po_id:osr_info_po_id, 'aircraft_id':''};

            fetchOTCCustomPopupDataFromServer(section, dataval);
            
        }
    }
});

$(document).on('click', '.saveWOOSRPOReminder', function(e){
    var wo_osr_po_id = $('#wo_osr_po_id').val();
    if(wo_osr_po_id != ''){
        $.ajax({
            url: saveWOOSRPurchaseOrderReminderURL, 
            type: 'post',
            data: $('#frmWOSRPOReminder').serialize(),
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    var reminder_idarr = obj.reminder_id;
                    if(reminder_idarr.length > 0){
                        for(var i=0; i<reminder_idarr.length; i++){
                            $('#reminder_id'+i).val(reminder_idarr[i]);
                        }
                    }

                    alert("Purchase Order Reminder saved successfully");
                }
            }
        });
    }
});

$(document).on('click', '.parts_not_checked_in', function(e){
    var osr_po_item_id = $(this).attr('data-val');
    $('.parts_not_checked_in').removeClass('parts_not_checked_in_active');
    $(this).addClass('parts_not_checked_in_active');
    if(osr_po_item_id != '' && osr_po_item_id != undefined){
        $.ajax({
            url: fetchWOOSRCheckInLaborHTMLURL, 
            type: 'post',
            data: {osr_po_item_id:osr_po_item_id},
            async : true,
            success: function (response) {  
                $('#po-checkin-labor-info').html(response);
                $('.selectpicker').selectpicker('refresh');
            }
        });
    }
});

$(document).on('dblclick click', '.editwoosrpoitem', function(e){
    if(e.type == 'click'){
        $('.editwoosrpoitem').removeClass('osr-po_item-active');
        $(this).addClass('osr-po_item-active');

        return false;
    }
    var osr_po_item_id = $(this).attr('data-val');
    
    if(osr_po_item_id != '' && osr_po_item_id != undefined){
        $('.editwoosrpoitem').removeClass('osr-po_item-active');
        $(this).addClass('osr-po_item-active');
        
        var section = 'aircraft_wo_osr_po_item';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var aircraft_id = $('#wo_aircraft_id').val();
            var dataval = {section:section, customer_id:customer_id, osr_po_item_id:osr_po_item_id, aircraft_id:aircraft_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.wo-osr-po-items', function(e){
    var wo_osr_po_id = $('#osr_infopoes_id').val();

    if(wo_osr_po_id != '' && wo_osr_po_id != undefined){
        var section = 'aircraft_wo_osr_po_item';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var aircraft_id = $('#wo_aircraft_id').val();
            var dataval = {section:section, customer_id:customer_id, wo_osr_po_id:wo_osr_po_id, aircraft_id:aircraft_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.woosr-ro-list-sec', function(e){
    var work_order_no = $('#osr_invoice_no').val();

    if(work_order_no != '' && work_order_no != undefined){
        var section = 'aircraft_create_wo_btn';
        if(section != '' && section != undefined){
            $('#customerotcpopup').html('');
            $('.modal-backdrop').remove();

            var customer_id = $('#wo_customer_id').val();
            var aircraft_id = $('#wo_aircraft_id').val();
            var dataval = {section:section, customer_id:customer_id, work_order_no:work_order_no, aircraft_id:aircraft_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.wolistallosrbtn', function(e){
    var work_order_id = $('#work_order_id').val();

    if(work_order_id != '' && work_order_id != undefined){
        var section = 'aircraft_wo_all_osr_list';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var aircraft_id = $('#wo_aircraft_id').val();
            var dataval = {section:section, customer_id:customer_id, work_order_id:work_order_id, aircraft_id:aircraft_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.removewoosrbtn', function(e){
    var wo_osrinfo_id = $('#wo_osrinfo_id').val();
    var repair_done_by = '';
    repair_done_by = $('.wo-osr-list-active').closest('tr').children('td:first').text();
    wo_osrinfo_id = $('.wo-osr-list-active').attr('data-val');
    
    if(wo_osrinfo_id != '' && wo_osrinfo_id != undefined){
        if(confirm('Remove outside labor record for `'+repair_done_by+'`')){
            $.ajax({
                url: deleteWOOSRRecordURL, 
                type: 'post',
                data: {wo_osrinfo_id:wo_osrinfo_id},
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        $('.wo-osr-list').html(obj.osrlist);
                    }
                }
            });
        }
    }
});

$(document).on('click', '.removeosrpoitemsbtn', function(e){
    var wo_osr_part_number = $('.osr-po_item-active').closest('tr').children('td:nth-child(4)').text();
    var wo_osr_po_item_id = $('.osr-po_item-active').attr('data-val');
    
    if(wo_osr_po_item_id != '' && wo_osr_po_item_id != undefined){
        if(confirm('Are you sure you want this service item with part number `'+wo_osr_part_number+'` from this P/O?')){
            $.ajax({
                url: deleteWOOSRPOItemsRecordURL, 
                type: 'post',
                data: {wo_osr_po_item_id:wo_osr_po_item_id},
                dataType: "text",
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        $('#otcosrpoitemstbl').html(obj.poitemlist);
                    }
                }
            });
        }
    }
});

$(document).on('click', '.work-order-prev-btn, .work-order-next-btn, .work-order-new-item-btn', function(e){
    var clickbtn = $(this).attr('data-val');
    var current_item_position = $('#current_item_position').val();
    var click_item_index = parseInt($('#click_item_index').val());
    var last_item_position = parseInt($('#last_item_position').val())-1;
    
    $('.work-order-prev-btn').prop('disabled', true);
    $('.work-order-next-btn').prop('disabled', true);
    
    if(clickbtn == 'first'){
        click_item_index = '0';
        $('.work-order-next-btn').prop('disabled', false);
    }else if(clickbtn == 'prev'){
        click_item_index -= 1;
        $('.work-order-next-btn').prop('disabled', false);
        if(click_item_index > '0'){
            $('.work-order-prev-btn').prop('disabled', false);
        }
    }else if(clickbtn == 'next'){
        click_item_index = parseInt(click_item_index)+1;
        
        $('.work-order-prev-btn').prop('disabled', false);
        if(click_item_index < last_item_position){
            $('.work-order-next-btn').prop('disabled', false);
        }
    }else if(clickbtn == 'last' || clickbtn == 'new-item'){
        click_item_index = last_item_position;
        $('.work-order-prev-btn').prop('disabled', false);
        if(click_item_index < last_item_position){
            $('.work-order-next-btn').prop('disabled', false);
        }
    }
    
    var is_new_item = '0';
    if(clickbtn == 'new-item'){
        is_new_item = '1';
        click_item_index = last_item_position;
    }
    
    current_item_position = wo_item_positions[click_item_index];
    
    $('#current_item_position').val(current_item_position);
    
    var work_order_id = $('#work_order_id').val();
    var item_no = $('#wo_item_no').val();
    item_no = parseInt(item_no)+1;
    if(work_order_id != '' && work_order_id != undefined){
        //var tabselected = $('.aircraftWOItemTabs').find('ul.nav').children('li.active').text();
        var seltabid = $('.aircraftWOItemTabs').find('ul.nav').children('li.active').children('a').attr('href');
        if(is_new_item == '1'){
            seltabid = '#aircraftWOOverviewSection';
        }

        var btnclickattr = '';

        var customer_id = $('#wo_customer_id').val();
        
        var dataval = {work_order_id:work_order_id, item_no:item_no, current_item_position:current_item_position, is_new_item:is_new_item, customer_id:customer_id};

        submitWOFormData(btnclickattr, dataval, seltabid);

    }
});

$(document).on('click', '.work-order-item-notes-btn', function(e){
    var wo_item_id = $('#wo_item_id').val();

    if(wo_item_id != '' && wo_item_id != undefined){
        var section = 'aircraft_wo_item_note';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var aircraft_id = $('#wo_aircraft_id').val();
            var dataval = {section:section, customer_id:customer_id, wo_item_id:wo_item_id, aircraft_id:aircraft_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.saveWorkOrderItemNotebtn', function(e){
    var wo_item_id = $('#note_wo_item_id').val();
    if(wo_item_id != ''){
        $.ajax({
            url: saveWorkOrderItemNoteURL, 
            type: 'post',
            data: $('#frmAircraftWorkOrderNote').serialize(),
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    alert("Item note saved successfully");
                }
            }
        });
    }
});

$(document).on('click', '.work-order-item-delete', function(e){
    var work_order_id = $('#work_order_id').val();
    var last_item_position = $('#last_item_position').val();
    var item_no = $('#wo_item_no').val();
    item_no = parseInt(item_no)+1;
    var is_delete_item = '1';
    var wo_item_id = $('#wo_item_id').val();

    if(work_order_id != '' && work_order_id != undefined){
        if(confirm('Delete this item number')){
            var customer_id = $('#wo_customer_id').val();

            var dataval = {work_order_id:work_order_id, item_no:item_no, is_delete_item:is_delete_item, last_item_position:last_item_position, wo_item_id:wo_item_id, customer_id:customer_id};
            saveAndGetAircraftWODet(dataval, '#aircraftWOOverviewSection');
        }
    }
});

function saveAndGetAircraftWODet(dataval, seltabid){
    $.ajax({
        url: saveAndGetAircraftWODetURL, 
        type: 'post',
        data: dataval,
        async : true,
        success: function (response) {
            if(response == 'failure'){
                alert(obj.message);
            }else{
                $('#createwoitemsections').html(response);
                $('.selectpicker').selectpicker('refresh');

                $('.aircraftWOItemTabs').find('ul.nav').children('li').removeClass('active');
                $('#aircraftWOOverviewSection').removeClass('in');
                $('#aircraftWOOverviewSection').removeClass('active');

                $('.aircraftWOItemTabs ul.nav li').each(function (index, element) {
                    if($(this).find('a').attr('href') == seltabid){
                        $(this).addClass('active');
                        $(seltabid).addClass('in');
                        $(seltabid).addClass('active');
                    }
                });
            }
        }
    });
}

$(document).on('click', '.work-order-item-list', function(e){
    var work_order_id = $('#work_order_id').val();
    var work_order_no = $('#work-order-no').val();

    if(work_order_id != '' && work_order_id != undefined){
        var section = 'aircraft_wo_item_list';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var aircraft_id = $('#wo_aircraft_id').val();
            var dataval = {section:section, customer_id:customer_id, work_order_id:work_order_id, work_order_no:work_order_no, aircraft_id:aircraft_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('dblclick', '.selectaircraftwoitems', function(e){
    var work_order_id = $('#work_order_id').val();
    var current_item_position = $(this).attr('data-val');
    var customer_id = $('#wo_customer_id').val();

    var dataval = {work_order_id:work_order_id, current_item_position:current_item_position, customer_id:customer_id};

    saveAndGetAircraftWODet(dataval);
    $('#aircraftWOItemListModel').modal('hide');
});

$(document).on('click', '.work-order-item-options', function(e){
    var work_order_id = $('#work_order_id').val();

    if(work_order_id != '' && work_order_id != undefined){
        var section = 'aircraft_work_order_options';
        if(section != '' && section != undefined){
            var customer_id = $('#wo_customer_id').val();
            var aircraft_id = $('#wo_aircraft_id').val();
            var wo_item_id = $('#wo_item_id').val();

            var dataval = {section:section, customer_id:customer_id, work_order_id:work_order_id, aircraft_id:aircraft_id, wo_item_id:wo_item_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.wo-go-to-maintenance-info', function(e){
    $('#aircarftCreateWOModel').modal('hide');

    var section = 'cust_otc_aircraft_maintenance';
    if(section != '' && section != undefined){
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.wo-item-option-atacode', function(e){
    var overview_ata_code = $('#overview_ata_code').val();
    if(overview_ata_code != '' && overview_ata_code != undefined){
        var dataval = {'ata_code':overview_ata_code};
        
        saveAircraftWOATACode(dataval);
    }else{
        var section = 'aircraft_wo_option_create_atacode';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id};
        
        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.wo-item-option-labor-kit', function(e){
    var overview_labor_kit = $('#overview_labor_kit').val();
    if(overview_labor_kit != '' && overview_labor_kit != undefined){
        var dataval = {'labor_kit':overview_labor_kit};
        
        saveAircraftWOLaborKit(dataval);
    }else{
        var section = 'aircraft_wo_option_create_laborkit';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id};
        
        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.wo-option-log-book-helper', function(e){
    var work_order_id = $('#work_order_id').val();

    if(work_order_id != '' && work_order_id != undefined){
        var section = 'aircraft_wo_option_logbook_helper';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, work_order_id:work_order_id};
        
        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.saveAircraftWOATACodebtn', function(e){
    var ata_code = $('#item_ata_code').val();
    var wo_ata_code_id = $('#wo_ata_code_id').val();
    if(ata_code != '' && ata_code != undefined){
        var dataval = {ata_code:ata_code, wo_ata_code_id:wo_ata_code_id};
        
        saveAircraftWOATACode(dataval);
    }
});

function saveAircraftWOATACode(dataval){
    $.ajax({
        url: saveAircraftWOATACodeURL, 
        type: 'post',
        data: dataval,
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'failure'){
                alert(obj.message);
            }else{
                alert('The ATA code was created successfully.');
                $('#overview_ata_code').val(dataval.ata_code);
                $('#overview_ata_code_id').val(obj.wo_ata_code_id);

                $('.selectpicker').selectpicker('refresh');
            }
        }
    });
}

$(document).on('click', '.saveAircraftWOLaborKitbtn', function(e){
    var labor_kit = $('#item_labor_kit').val();
    var wo_labor_kit_id = $('#wo_labor_kit_id').val();
    
    if(labor_kit != '' && labor_kit != undefined){
        var dataval = {labor_kit:labor_kit, wo_labor_kit_id:wo_labor_kit_id, 'labor_kit':labor_kit};
        
        saveAircraftWOLaborKit(dataval);
    }
});

function saveAircraftWOLaborKit(dataval){
    $.ajax({
        url: saveAircraftWOLaborKitURL, 
        type: 'post',
        data: dataval,
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'failure'){
                alert(obj.message);
            }else{
                alert('Labor kit saved successfully');
                $('#overview_labor_kit').html(dataval.ata_code);
                $('#overview_labor_kit_name').val(obj.wo_ata_code_id);

                $('.selectpicker').selectpicker('refresh');
            }
        }
    });
}

$(document).on('click', '.wo-option-log-book-values', function(e){
    var work_order_id = $('#work_order_id').val();

    if(work_order_id != '' && work_order_id != undefined){
        var section = 'aircraft_wo_option_logbook_values';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var wo_item_id = $('#wo_item_id').val();

        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, work_order_id:work_order_id, wo_item_id:wo_item_id};
        
        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.aircraft-wo-geninfo-manage-deposit', function(e){
    var work_order_id = $('#work_order_id').val();

    if(work_order_id != '' && work_order_id != undefined){
        var section = 'aircraft_wo_geninfo_manage_deposits';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var general_info_id = $('#general_info_id').val();

        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, work_order_id:work_order_id, general_info_id:general_info_id};
        
        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.wo-option-taxinfo-extra-taxes', function(e){
    var option_tax_info_id = $('#option_tax_info_id').val();

    if(option_tax_info_id != '' && option_tax_info_id != undefined){
        var section = 'aircraft_wo_option_taxinfo_extra_taxes';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();

        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, option_tax_info_id:option_tax_info_id};
        
        fetchOTCCustomPopupDataFromServer(section, dataval);
    }else{
        alert("Save the taxes info and then try again");
    }
});

$(document).on('click', '.wo-option-new-extra-taxes', function(e){
    var work_order_id = $('#work_order_id').val();

    if(work_order_id != '' && work_order_id != undefined){
        var section = 'aircraft_wo_option_new_extra_tax';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var option_tax_info_id = $('#option_tax_info_id').val();

        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, work_order_id:work_order_id, option_tax_info_id:option_tax_info_id};
        
        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.mark-all-shop-labor-taxable', function(e){
    if(confirm('Do you want to mark all labor items in this Work Order taxable?')){

    }
});

$(document).on('click', '.mark-all-osr-labor-taxable', function(e){
    if(confirm('Do you want to mark all OSR labor items in this Work Order taxable?')){

    }
});

$(document).on('click', '.mark-all-shop-parts-taxable', function(e){
    if(confirm('Do you want to mark all parts in this Work Order taxable?')){

    }
});

$(document).on('click', '.mark-all-osr-parts-taxable', function(e){
    if(confirm('Do you want to mark all OSR parts in this Work Order taxable?')){

    }
});

$(document).on('click', '.reset-estimated-rate-to-current-rate', function(e){
    if(confirm('This will reset the estimated rate to the current rate for the W/O. Continue?')){

    }
});

$(document).on('click', '.set-all-items-specific-dept', function(e){
    var work_order_id = $('#work_order_id').val();

    if(work_order_id != '' && work_order_id != undefined){
        var section = 'wo_set_all_items_specific_dept';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var option_billing_info_id = $('#option_billing_info_id').val();

        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, work_order_id:work_order_id, option_billing_info_id:option_billing_info_id};
        
        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.wo-option-billing-info-reset', function(e){
    if(confirm('This will reset the `Bill-to` on all items to the default bill-to customer. Continue?')){

    }
});

$(document).on('click', '.wo-option-billing-clear-integ-log', function(e){
    if(confirm('This will remove any integration logs for this item. This will then cause EBIs to mark this Work Order for integration when it is marked with a `Completed` status. Continue?')){

    }
});

$(document).on('click', '.saveAircraftWOLogBookValOverviewBtn', function(e){
    $.ajax({
        url: saveAircraftWOLogBookValOverviewURL,
        type: 'post',
        data: $("#frmAircraftWOLogBookValOverview").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('logbook_value_overview_id').val(obj.id);

                alert('Overview detail added successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.saveAircraftWOLogBookValEngine', function(e){
    $.ajax({
        url: saveAircraftWOLogBookValEngineURL,
        type: 'post',
        data: $("#frmAircraftWOLogBookValEngine").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('#logbook_value_engine_id').val(obj.id);

                alert('Engine detail saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.saveAircraftWOLogBookValProp', function(e){
    $.ajax({
        url: saveAircraftWOLogBookValPropURL,
        type: 'post',
        data: $("#frmAircraftWOLogBookValProp").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('#logbook_value_prop_id').val(obj.id);

                alert('Prop detail saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.refresh-val-with-maintenance', function(e){
    var aircraft_id = $('#wo_aircraft_id').val();
    if(aircraft_id != '' && aircraft_id != undefined){
        var work_order_id = $('#work_order_id').val();
        var wo_item_id = $('#wo_item_id').val();
        
        $.ajax({
            url: refreshWOLogBookValWithMaintURL, 
            type: 'post',
            data: {aircraft_id:aircraft_id, work_order_id:work_order_id, wo_item_id:wo_item_id},
            async : true,
            success: function (response) {
                if(response == 'failure'){
                    alert(obj.message);
                }else{
                    $('.wo_option_logbook_val_tab').html(response);
                    overrideMaintenanceBlockDate();
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    }
});

$(document).on('click', '.saveWOOptionGenInfo', function(e){
    $.ajax({
        url: saveWOViewOptionGenInfoURL,
        type: 'post',
        data: $("#frmAircraftWOOptionGenInfo").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('#general_info_id').val(obj.general_info_id);

                alert('General Info detail saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.saveWOOptionGenInfoDeposits', function(e){
    $.ajax({
        url: saveWOViewOptionGenInfoDepositURL,
        type: 'post',
        data: $("#frmAircraftWOOptionGenInfoDeposit").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                //$('#general_info_deposit_id').val(obj.gen_info_deposit_id);
                $('.wo-gen-info-deposit-list').html(obj.deposittr);
                $('#deposit_total_amount').val(obj.total_amount);
                $("#frmAircraftWOOptionGenInfoDeposit")[0].reset();
                $('.selectpicker').selectpicker('refresh');

                alert('Deposits detail saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.wo-gen-info-deposit', function(e){
    $('.wo-gen-info-deposit').removeClass('wo-gen-info-deposit-active');
    $(this).addClass('wo-gen-info-deposit-active');
});

$(document).on('click', '.remove-wo-gen-info-deposit', function(e){
    var deposit_amount = $('.wo-gen-info-deposit-active').closest('tr').children('td:nth-child(3)').text();
    var general_info_deposit_id = $('.wo-gen-info-deposit-active').attr('data-val');
    
    if(general_info_deposit_id != '' && general_info_deposit_id != undefined){
        if(confirm('Are you sure you want to remove this deposit ($'+deposit_amount+')?')){
            $.ajax({
                url: deleteWOViewOptionGenInfoDepositURL, 
                type: 'post',
                data: {general_info_deposit_id:general_info_deposit_id},
                dataType: "text",
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        $('.wo-gen-info-deposit-list').html(obj.deposittr);
                        $('#deposit_total_amount').val(obj.total_amount);
                    }
                }
            });
        }
    }
});

$(document).on('click', '.saveWOOptionMiscCharges', function(e){
    $.ajax({
        url: saveWOViewOptionMiscChargesURL,
        type: 'post',
        data: $("#frmAircraftWOOptMiscCharges").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('#misc_charges_id').val(obj.misc_charges_id);

                alert('Misc. Charges detail saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('change', '#shop-supplies-method', function(e){
    if($(this).val() == '2'){
        $('#percentage_of_labor').prop('disabled', false);
    }else{
        $('#percentage_of_labor').prop('disabled', true);
    }
    $('.selectpicker').selectpicker('refresh');
});

$(document).on('click', '.aircraft-wo-item-photo', function(e){
    $('.aircraft-wo-item-photo').removeClass('wo-item-photo-active');
    $(this).addClass('wo-item-photo-active');
});

$(document).on('click', '.aircraft-wo-item-file', function(e){
    $('.aircraft-wo-item-file').removeClass('wo-item-file-active');
    $(this).addClass('wo-item-file-active');
});

$(document).on('click', '.woremovephotobtn', function(e){
    var wo_item_photo_id = $('.wo-item-photo-active').attr('data-val');
    
    if(wo_item_photo_id != '' && wo_item_photo_id != undefined){
        if(confirm('Are you sure you want to remove this photo?')){
            var wo_item_id = $('#wo_item_id').val();
            $.ajax({
                url: deleteWOItemPhotoURL, 
                type: 'post',
                data: {wo_item_photo_id:wo_item_photo_id, wo_item_id:wo_item_id},
                dataType: "text",
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        $('.count_wo_item_photo').html(obj.woitemphotocount);
                        $('#wophotosattachlist').html(obj.woitemphototr);
                    }
                }
            });
        }
    }
});

$(document).on('click', '.woremovefilebtn', function(e){
    var wo_item_file_id = $('.wo-item-file-active').attr('data-val');
    
    if(wo_item_file_id != '' && wo_item_file_id != undefined){
        if(confirm('Are you sure you want to remove this file?')){
            var wo_item_id = $('#wo_item_id').val();
            $.ajax({
                url: deleteWOItemFileURL, 
                type: 'post',
                data: {wo_item_file_id:wo_item_file_id, wo_item_id:wo_item_id},
                dataType: "text",
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        $('.count_wo_item_file').html(obj.woitemfilecount);
                        $('#wofileattachlist').html(obj.woitemfiletr);
                    }
                }
            });
        }
    }
});

$(document).on('click', '.aircraft-wo-item-photo', function(e){
    $('.aircraft-wo-item-photo').removeClass('wo-item-photo-active');
    $(this).addClass('wo-item-photo-active');
});

$(document).on('click', '.aircraft-wo-item-file', function(e){
    $('.aircraft-wo-item-file').removeClass('wo-item-file-active');
    $(this).addClass('wo-item-file-active');
});

$(document).on('click', '.saveWOOptionPricingInfo', function(e){
    $.ajax({
        url: saveWOViewOptionPricingInfoURL,
        type: 'post',
        data: $("#frmAircraftWOOptPricingInfo").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('#pricing_info_id').val(obj.pricing_info_id);

                alert('Pricing Info detail saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.saveWOOptionWarrantyInfo', function(e){
    $.ajax({
        url: saveWOViewOptionWarrantyInfoURL,
        type: 'post',
        data: $("#frmAircraftWOOptWarrantyInfo").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                alert('Warranty Info detail saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.saveWOOptionTaxInfo', function(e){
    var work_order_id = $('#tax_info_work_order_id').val();
    if(work_order_id!= '' && work_order_id != undefined){
        $.ajax({
            url: saveWOViewOptionTaxInfoURL,
            type: 'post',
            data: $("#frmAircraftWOOptTaxInfo").serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#option_tax_info_id').val(obj.option_tax_info_id);
                    alert('Tax Info detail saved successfully.');
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('click', '.saveWOOptionNewExtraTaxes', function(e){
    var option_tax_info_id = $('#option_tax_info_id').val();
    var btnclickevent = 'new';
    var formdata = $("#frmAircraftWOOptNewExtraTaxes").serialize();
    
    if(option_tax_info_id!= '' && option_tax_info_id != undefined){
        saveWOViewOptionExtraTaxes(formdata, btnclickevent)
    }
});

$(document).on('click', '.saveWOOptionExtraTaxes', function(e){
    var option_tax_info_id = $('#option_tax_info_id').val();
    var btnclickevent = '';
    var formdata = $("#frmAircraftWOOptExtraTaxes").serialize();
    var extra_tax_name = $('#extra-tax-name').val();
    
    if(option_tax_info_id!= '' && option_tax_info_id != undefined && extra_tax_name != '' && extra_tax_name != undefined){
        saveWOViewOptionExtraTaxes(formdata, btnclickevent)
    }
});

function saveWOViewOptionExtraTaxes(formdata, btnclickevent){
    $.ajax({
        url: saveWOViewOptionExtraTaxesURL,
        type: 'post',
        data: formdata,
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                if(btnclickevent == 'new'){
                    $('#frmAircraftWOOptExtraTaxes :input').prop("disabled", false);

                    $('#aircraftWOOptNewExtTaxModal').modal('hide');

                    $('.wo-option-extra-taxes-list').removeClass('wo-option-extra-taxes-list-active');
                    $('.list-of-extra-taxes').prepend(obj.newaddedtax);
                }
                $('#extra_taxes_id').val(obj.extra_taxes_id);
                alert('Extra Tax detail saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
}

$(document).on('click', '.wo-option-extra-taxes-list', function(e){
    var extra_taxes_id = $(this).attr('data-val');
    $('.wo-option-extra-taxes-list').removeClass('wo-option-extra-taxes-list-active');
    $(this).addClass('wo-option-extra-taxes-list-active');

    if(extra_taxes_id != '' && extra_taxes_id != undefined){
        $.ajax({
            url: getWOViewOptionExtraTaxesDataURL,
            type: 'post',
            data: {extra_taxes_id:extra_taxes_id},
            dataType: 'text',
            success: function (response) {
                if(response == 'Failed') {
                    alert('Something went wrong, please try again');
                }else{
                    $('#frmAircraftWOOptExtraTaxes :input').prop("disabled", false);
                    
                    $('.extra-taxes-setup-block').html(response);
                    $('.wo-option-delete-extra-taxes').prop('disabled', false);
                }
            }
        });
    }
});

$(document).on('click', '.wo-option-delete-extra-taxes', function(e){
    var extra_taxes_id = $('.wo-option-extra-taxes-list-active').attr('data-val');
    
    if(extra_taxes_id != '' && extra_taxes_id != undefined){
        if(confirm('Are you sure you want to remove this message?')){
            $.ajax({
                url: deleteWOViewOptionExtraTaxesURL, 
                type: 'post',
                data: {extra_taxes_id:extra_taxes_id},
                dataType: "text",
                success: function (response) {
                    if(response == 'Failed') {
                        alert('Something went wrong, please try again');
                    }else{
                        $('.wo-option-extra-taxes-list-active').remove();
                        
                        alert('Extra Tax detail deleted successfully.');
                        $('#frmAircraftWOOptExtraTaxes :input').prop("disabled", true);
                        
                        $('.extra-taxes-setup-block').html(response);
                    }
                }
            });
        }
    }
});

$(document).on('change', '#option_billing_rate_method', function(e){
    var billing_rate_method = $(this).val();alert(billing_rate_method);
    if(billing_rate_method == '1'){
        $('.technician_rate_block').css('display', '');
        $('.aircraft_rate_block').css('display', 'none');
    }else{
        $('.aircraft_rate_block').css('display', '');
        $('.technician_rate_block').css('display', 'none');
    }
});

$(document).on('click', '.saveWOOptionBillingInfo', function(e){
    var work_order_id = $('#billing_info_work_order_id').val();
    if(work_order_id!= '' && work_order_id != undefined){
        $.ajax({
            url: saveWOViewOptionBillingInfoURL,
            type: 'post',
            data: $("#frmAircraftWOOptBillingInfo").serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#option_billing_info_id').val(obj.option_billing_info_id);
                    alert('Billing Info detail saved successfully.');
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('click', '.aircraft-wo-move-item', function(e){
    var work_order_id = $('#work_order_id').val();
    if(work_order_id != ''){
        var section = 'aircraft_wo_move_items';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();

        var dataval = {section:section, customer_id:customer_id, work_order_id:work_order_id, aircraft_id:aircraft_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.wo_item_reorganize_number', function(e){
    var work_order_id = $('#work_order_id').val();
    if(work_order_id != '' && work_order_id != undefined){
        if(confirm('This will reorganize the item numbers, removing any blanks between item numbers. Continue?')){
            $.ajax({
                url: reorganizeWorkOrderItemURL, 
                type: 'post',
                data: {work_order_id:work_order_id},
                dataType: "text",
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        alert('The item numbers has been reorganized successfully.');
                    }else{
                        alert(obj.message);
                    }
                }
            });
        }
    }
});

$(document).on('click', '.aircraft-wo-item-signoff', function(e){
    var work_order_id = $('#work_order_id').val();
    if(work_order_id != ''){
        var section = 'aircraft_wo_sign_offs';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var wo_item_id = $('#wo_item_id').val();

        var dataval = {section:section, customer_id:customer_id, work_order_id:work_order_id, aircraft_id:aircraft_id, wo_item_id:wo_item_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.continueToCreateWO', function(e){
    var filter_aircraft_id = $('#filter_aircraft_registration_number').val();

    if(filter_aircraft_id != '' && filter_aircraft_id != undefined){
        var section = 'aircraft_create_wo_btn';
        
        var dataval = {section:section, filter_aircraft_id:filter_aircraft_id, 'search_by':'create_new'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        $('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.searchAircraftWorkOrder', function(e){
    var work_order_no = $('#work_order_no').val();
    
    if(work_order_no != '' && work_order_no != undefined){
        var section = 'aircraft_create_wo_btn';
        
        var dataval = {section:section, work_order_no:work_order_no, 'search_by':'aircraft_wo_no'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        $('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('dblclick', '.loadaircraftworkordertr', function(e){
    var work_order_no = $(this).attr('data-val');
    
    if(work_order_no != '' && work_order_no != undefined){
        var section = 'aircraft_create_wo_btn';
        
        var dataval = {section:section, work_order_no:work_order_no, 'search_by':'aircraft_wo_no'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        $('#woCreateNewWOModal').modal('hide');
    }
});

function loadAircraftWOPopupDataFromServer(section, dataval){
    $.ajax({
        url: loadAircraftWorkOrderPopupURL, 
        type: 'post',
        data: dataval,
        async : true,
        success: function (response) {
            appendCustomerOTCPopupData(section, response);
        }
    });
}

$(document).on('click', '.load-open-work-orders', function(e){
    var section = 'list_of_open_work_order';
    var search_by = $(this).attr('data-val');
        
    var dataval = {section:section, search_by:search_by};

    loadAircraftWOPopupDataFromServer(section, dataval);
});

$(document).on('click', '.load-warranty-claims-work-orders', function(e){
    var section = 'list_of_warranty_claims_work_order';
    var search_by = $(this).attr('data-val');

    var dataval = {section:section, search_by:search_by};

    loadAircraftWOPopupDataFromServer(section, dataval);
});

$(document).on('change', '#load_wo_limit_to_department', function(e){
    var wo_category = $(this).val();

    if(wo_category != '' && wo_category != undefined){
        $.ajax({
            url: filterOpenWODepartsURL,
            type: 'post',
            data: {wo_category:wo_category},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#list-of-open-work-order-block').html(obj.openwohtml);
                }else{
                    alert(obj.message);
                }
            }
        });
    }
});

$(document).on('click', '.searchWOByAircraftRegNo', function(e){
    var aircraft_registration_number = $('#search_by_aircraft_registration_number').val();
    
    if(aircraft_registration_number != '' && aircraft_registration_number != undefined){
        var section = 'list_of_all_work_order_quotes';
        
        var dataval = {section:section, aircraft_registration_number:aircraft_registration_number, 'search_by':'aircraft_registration_number'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        //$('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.searchWOByAircraftSerialNo', function(e){
    var aircraft_serial_number = $('#search_by_aircraft_serial_number').val();
    
    if(aircraft_serial_number != '' && aircraft_serial_number != undefined){
        var section = 'list_of_all_work_order_quotes';
        
        var dataval = {section:section, 'aircraft_serial_number':aircraft_serial_number, 'search_by':'aircraft_serial_number'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        //$('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.searchWOByAircraftCustomerName', function(e){
    var customer_name = $('#search_by_customer_name').val();
    
    if(customer_name != '' && customer_name != undefined){
        var section = 'list_of_all_work_order_quotes';
        
        var dataval = {section:section, customer_name:customer_name, 'search_by':'customer_name'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        //$('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.searchWOByAircraftCustomerPO', function(e){
    var customer_po = $('#search_by_customer_po').val();
    
    if(customer_po != '' && customer_po != undefined){
        var section = 'list_of_all_work_order_quotes';
        
        var dataval = {section:section, customer_po:customer_po, 'search_by':'customer_po'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        //$('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.searchWOByItemDiscrepancy', function(e){
    var discrepancy = $('#search_by_discrepancy').val();
    
    if(discrepancy != '' && discrepancy != undefined){
        var section = 'list_of_all_work_order_quotes';
        
        var dataval = {section:section, discrepancy:discrepancy, 'search_by':'discrepancy'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        //$('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.searchWOByItemCorrectiveAction', function(e){
    var corrective_action = $('#search_by_corrective_action').val();
    
    if(corrective_action != '' && corrective_action != undefined){
        var section = 'list_of_all_work_order_quotes';
        
        var dataval = {section:section, corrective_action:corrective_action, 'search_by':'corrective_action'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        //$('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.searchWOByWarrantyClaim', function(e){
    var warranty_claim_no = $('#search_by_warranty_claim_no').val();
    
    if(warranty_claim_no != '' && warranty_claim_no != undefined){
        var section = 'list_of_all_work_order_quotes';
        
        var dataval = {section:section, warranty_claim_no:warranty_claim_no, 'search_by':'warranty_claim_no'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        //$('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.searchWOByWarrantyWOStatus', function(e){
    var warranty_wo_status = $('#search_by_warranty_wo_status').val();
    
    if(warranty_wo_status != '' && warranty_wo_status != undefined){
        var section = 'list_of_all_work_order_quotes';
        
        var dataval = {section:section, warranty_wo_status:warranty_wo_status, 'search_by':'warranty_wo_status'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        //$('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.searchWOByPartNumber', function(e){
    var part_number = $('#search_by_part_number').val();
    
    if(part_number != '' && part_number != undefined){
        var section = 'list_of_all_work_order_quotes';
        
        var dataval = {section:section, part_number:part_number, 'search_by':'part_number'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        //$('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.woMaintAdvFindOptPopup', function(e){
    var section = 'advanced_wo_find_options';
    
    var dataval = {section:section};

    loadAircraftWOPopupDataFromServer(section, dataval);
});

$(document).on('click', '.searchAdvFindOptInWO', function(e){
    var adv_registration_number = $('#adv_filter_wo_reg_number').val();
    var adv_discrpancy = $('#adv_filter_wo_discrpancy').val();
    var adv_corrective_action = $('#adv_filter_wo_corrective_action').val();
    
    if((adv_registration_number != '' && adv_registration_number != undefined) || (adv_discrpancy != '' && adv_discrpancy != undefined) || (adv_corrective_action != '' && adv_corrective_action != undefined)){
        var section = 'list_of_all_work_order_quotes';
        
        var dataval = {section:section, adv_registration_number:adv_registration_number, adv_discrpancy:adv_discrpancy, adv_corrective_action:adv_corrective_action, 'search_by':'advanced_find_option'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        //$('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.searchOtherFindOptInWO', function(e){
    var wo_misc_charges_notes = $('#adv_filter_wo_misc_charges_notes').val();
    
    if(wo_misc_charges_notes != '' && wo_misc_charges_notes != undefined){
        var section = 'list_of_all_work_order_quotes';
        
        var dataval = {section:section, wo_misc_charges_notes:wo_misc_charges_notes, 'search_by':'other_find_option'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        //$('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.searchPartSearchInWO', function(e){
    var wo_part_number = $('#adv_filter_wo_part_number').val();
    var wo_description = $('#adv_filter_wo_description').val();
    var wo_old_serial_no = $('#adv_filter_wo_old_serial_no').val();
    var wo_new_serial_no = $('#adv_filter_wo_new_serial_no').val();
    var wo_supplier = $('#adv_filter_wo_supplier').val();
    
    if((wo_part_number != '' && wo_part_number != undefined) || (wo_description != '' && wo_description != undefined) || (wo_old_serial_no != '' && wo_old_serial_no != undefined) || (wo_new_serial_no != '' && wo_new_serial_no != undefined) || (wo_supplier != '' && wo_supplier != undefined)){
        var section = 'list_of_all_work_order_quotes';
        
        var dataval = {section:section, wo_part_number:wo_part_number, wo_description:wo_description, wo_old_serial_no:wo_old_serial_no, wo_new_serial_no:wo_new_serial_no, wo_supplier:wo_supplier, 'search_by':'advanced_parts_search'};

        loadAircraftWOPopupDataFromServer(section, dataval);
        //$('#woCreateNewWOModal').modal('hide');
    }
});

$(document).on('click', '.wo-item-add-part-btn', function(e){
    var wo_item_id = $('#wo_item_id').val();
    if(wo_item_id != ''){
        var section = 'aircraft_wo_part_add_btn';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        
        var dataval = {section:section, customer_id:customer_id, wo_item_id:wo_item_id, aircraft_id:aircraft_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('change', '#wo_item_part_number', function(e){
    if($(this).val() != ''){
        $.ajax({
            url:getInventoryItemByIdURL,
            data:{'inventory_item_id':$(this).val()},
            dataType: "text",
            type:'post',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {//console.log(obj.conditions);
                    $('#wo_item_part_name').val(obj.inventoryitems.name);
                    $('#wo_item_part_description').val(obj.inventoryitems.description);
                    $('#wo_item_part_qty_stock').val(obj.inventoryitems.qty);
                    $('#wo_item_part_unit_measure').val(obj.inventoryitems.default_uom);
                    $('#wo_item_part_weight').val(obj.inventoryitems.weight);
                    $('#price_each').val(obj.inventoryitems.unit_cost);
                    $('#part_total_prices').val(obj.inventoryitems.unit_cost);

                    var condoption = '<option value="">Select Conditions</option>';
                    var conditionlist = obj.conditions;
                    
                    $.each(conditionlist, function (key, val) {
                        condoption += '<option value="'+key+'">'+val+'</option>';
                    });
                    $('#wo_item_part_conditions').html(condoption);

                    var serialoption = '<option value="">Select Serial Number</option>';
                    var seriallist = obj.serialno;
                    
                    $.each(seriallist, function (key, val) {
                        serialoption += '<option value="'+key+'">'+val+'</option>';
                    });
                    $('#wo_item_serial_number').html(serialoption);

                    var locationoption = '';
                    var locationlist = obj.locationlist;
                    
                    $.each(locationlist, function (key, val) {
                        locationoption += '<option value="'+key+'">'+val+'</option>';
                    });
                    $('#wo_item_part_general_location').html(locationoption);

                    var vendoroption = '';
                    var vendorlist = obj.vendorlist;
                    
                    $.each(vendorlist, function (key, val) {
                        vendoroption += '<option value="'+key+'">'+val+'</option>';
                    });
                    $('#wo_item_part_vendor').html(vendoroption);

                    $('.selectpicker').selectpicker('refresh');
                } else {
                    alert("Something went wrong, please try again later");
                }
            }
        });
    }else{
        resetWOItemPartForm();
    }
});

function resetWOItemPartForm(){
    $("#frmAircraftWOItemParts")[0].reset();
    var condoption = '';
    
    $('#wo_item_part_conditions').html(condoption);

    var serialoption = '';
    
    $('#wo_item_serial_number').html(serialoption);

    var locationoption = '';
    
    $('#wo_item_part_general_location').html(locationoption);

    var vendoroption = '';
    
    $('#wo_item_part_vendor').html(vendoroption);

    $('.selectpicker').selectpicker('refresh');
}

$(document).on('click', '.wo-itempart-addpart-btn, .wo-itempart-addpartclose-btn', function(e){
    var wo_item_part_number = $('#wo_item_part_number').val();
    if(wo_item_part_number == '' || wo_item_part_number == undefined){
        wo_item_part_number = $('#wo_view_item_part_number').val();
    }
    var part_wo_item_id = $('#part_wo_item_id').val();
    var addbtnevent = $(this).attr('data-val');

    var dataval = $("#frmAircraftWOItemParts").serialize();
    if(addbtnevent == 'view_part'){
        dataval = $("#frmAircraftWOItemViewParts").serialize();
    }else if(addbtnevent == 'part_notes'){
        dataval = $("#frmAircraftWOItemPartNotes").serialize();
    }

    if(wo_item_part_number != '' && wo_item_part_number != undefined && part_wo_item_id != ''){
        $.ajax({
            url: saveAircraftWOItemPartsURL, 
            type: 'post',
            data: dataval,
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    if(addbtnevent != 'view_part' && addbtnevent != 'part_notes'){
                        resetWOItemPartForm();
                    }
                    
                    $('#aircraft-woitem-partlist').html(obj.woitempartshtml);
                    
                    alert("Part saved successfully.");
                    if(addbtnevent == 'part-add-close'){
                        $('#aircraftWOAddPartModel').modal('hide');
                    }
                }
            }
        });
    }else{
        alert("Select part number");
    }
});

$(document).on('click', '.wo-item-part-listall-btn', function(e){
    var work_order_id = $('#work_order_id').val();
    if(work_order_id != '' && work_order_id != undefined){
        var section = 'aircraft_wo_item_allparts_list';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        
        var dataval = {section:section, customer_id:customer_id, work_order_id:work_order_id, aircraft_id:aircraft_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '#refresh-work-order-part-lists', function(e){
    var work_order_id = $('#work_order_id').val();
    if(work_order_id != '' && work_order_id != undefined){
        $.ajax({
            url: refreshAircraftWOAllPartsListURL, 
            type: 'post',
            data: {work_order_id:work_order_id},
            async : true,
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    $('#aircraft-work-order-parts-list').html(obj.woitempartshtml);
                }
            }
        });
    }
});

$(document).on('click', '.wo-item-part-requisition-btn', function(e){
    var wo_item_id = $('#wo_item_id').val();
    if(wo_item_id != '' && wo_item_id != undefined){
        var section = 'aircraft_wo_item_parts_requisitions';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var work_order_id = $('#work_order_id').val();
        
        var dataval = {section:section, customer_id:customer_id, wo_item_id:wo_item_id, aircraft_id:aircraft_id, work_order_id:work_order_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.wopartsrequisiterow', function(e){
    var wo_item_part_id = $(this).attr('data-val');
    $('.wopartsrequisiterow').removeClass('wopartsrequisiterow_active');
    $(this).addClass('wopartsrequisiterow_active');
    if(wo_item_part_id != '' && wo_item_part_id != undefined){
        var wo_item_id = $('#wo_item_id').val();

        $.ajax({
            url: getWOItemPartsRequisitionURL, 
            type: 'post',
            data: {wo_item_part_id:wo_item_part_id, wo_item_id:wo_item_id},
            async : true,
            success: function (response) {
                if(response == 'Failed'){
                    alert('Something went wrong, please try again.');
                }else{
                    $('.wo-item-parts-requisitions').html(response);
                    $('.selectpicker').selectpicker('refresh');

                    overrideWorkOrderPartsBlockDate();
                }
            }
        });
    }
});

$(document).on('click', '.wo-item-part-view-btn', function(e){
    openWOItemPartViewPopup();
});

$(document).on('dblclick', '.woitempartstblrow', function(e){
    openWOItemPartViewPopup();
});

function openWOItemPartViewPopup(){
    var work_order_id = $('#work_order_id').val();
    var wo_item_part_id = $('.wo-item-part-list-active').attr('data-val');
    if(work_order_id != '' && wo_item_part_id!= '' && wo_item_part_id != undefined){
        var section = 'aircraft_wo_item_parts_view';
        var wo_item_id = $('#wo_item_id').val();
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();

        var dataval = {section:section, customer_id:customer_id, work_order_id:work_order_id, aircraft_id:aircraft_id, wo_item_part_id:wo_item_part_id, wo_item_id:wo_item_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
}

$(document).on('click', '.woitempartslisttblrow', function(e){
    $('.woitempartslisttblrow').removeClass('wo-item-part-list-active');
    $(this).addClass('wo-item-part-list-active');
});

$(document).on('click', '.woitempartsalllisttblrow', function(e){
    $('.woitempartsalllisttblrow').removeClass('wo-item-part-list-active');
    $(this).addClass('wo-item-part-list-active');
});

$(document).on('click', '.wo-item-parts-to-pull', function(e){
    var wo_item_part_id = $('#wo_item_part_id').val();
    if(wo_item_part_id != '' && wo_item_part_id != undefined){
        var section = 'aircraft_wo_item_parts_to_pull';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        //var work_order_id = $('#work_order_id').val();
        
        var dataval = {section:section, customer_id:customer_id, wo_item_part_id:wo_item_part_id, aircraft_id:aircraft_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.wo-item-make-all-parts-taxable', function(e){
    var wo_item_id = $('#wo_item_id').val();
    if(wo_item_id != '' && wo_item_id != undefined){
        if(confirm('Do you want to mark all parts in this Work Order taxable?')){
            $.ajax({
                url: woItemMarkAllPartsTaxableURL, 
                type: 'post',
                data: {wo_item_id:wo_item_id},
                dataType: "text",
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }
                }
            });
        }
    }
});

$(document).on('click', '.wo-item-part-notes-btn', function(e){
    var wo_item_part_id = $('#wo_item_part_id').val();
    if(wo_item_part_id != '' && wo_item_part_id != undefined){
        var section = 'aircraft_wo_item_part_notes';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        //var work_order_id = $('#work_order_id').val();
        
        var dataval = {section:section, customer_id:customer_id, wo_item_part_id:wo_item_part_id, aircraft_id:aircraft_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.wo-item-part-delete-btn', function(e){
    var wo_item_part_id = $('#wo_item_part_id').val();
    var part_number = $('#wo_view_item_part_number').val();
    
    if(wo_item_part_id != '' && wo_item_part_id != undefined){
        if(confirm('Delete part number('+part_number+') from this item? This cannot be undone.')){
            var wo_item_id = $('#wo_item_id').val();
            $.ajax({
                url: deleteAircraftWOAllPartsURL, 
                type: 'post',
                data: {wo_item_part_id:wo_item_part_id, wo_item_id:wo_item_id},
                dataType: "text",
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        alert("Parts deleted successfully.");
                        $('#woItemPartsViewModel').modal('hide');
                        $('#aircraft-woitem-partlist').html(obj.woitempartshtml);
                    }
                }
            });
        }
    }
});

$(document).on('click', '.wo-item-part-prev-btn, .wo-item-part-next-btn', function(e){
    var clickbtn = $(this).attr('data-val');
    
    var wo_item_part_id = $('#wo_item_part_id').val();
    var work_order_id = $('#work_order_id').val();
    var wo_item_id = $('#wo_item_id').val();

    if(wo_item_part_id != '' && wo_item_part_id != undefined){
        $.ajax({
            url: getAircraftWOItemNextPrevPartURL, 
            type: 'post',
            data: {clickbtn:clickbtn, wo_item_part_id:wo_item_part_id, work_order_id:work_order_id, wo_item_id:wo_item_id},
            dataType: "text",
            success: function (response) {
                if(response == 'Failed'){
                    alert('Something went wrong, please try again.');
                }else if(response != 'Empty'){
                    $('.wo-item-part-view-block').html(response);
                    overrideWorkOrderPartsBlockDate();
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    }
});

$(document).on('click', '.work-order-mark-items-btn', function(e){
    var work_order_id = $('#work_order_id').val();
    if(work_order_id != '' && work_order_id != undefined){
        var section = 'aircraft_work_order_mark_items';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var wo_item_id = $('#wo_item_id').val();
        
        var dataval = {section:section, customer_id:customer_id, work_order_id:work_order_id, aircraft_id:aircraft_id, wo_item_id:wo_item_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.wo-mark-all-item-yes', function(e){
    var wo_item_ids = $('#wo_item_ids').val();
    if(wo_item_ids != '' && wo_item_ids != undefined){
        if(confirm('Do you want to Mark All Items "Yes"?')){
            $.ajax({
                url: woMarkAllItemsYesURL, 
                type: 'post',
                data: {wo_item_ids:wo_item_ids},
                dataType: "text",
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }
                }
            });
        }
    }
});

$(document).on('click', '.import-wo-from-email', function(e){
    
    var section = 'import_work_order_from_email';
    
    var dataval = {section:section, 'search_by':'import_work_order_from_email'};

    loadAircraftWOPopupDataFromServer(section, dataval);
    //$('#woCreateNewWOModal').modal('hide');
    
});

$(document).on('click', '.export-work-order-data', function(e){
    
    var section = 'export_work_order_to_file';
    
    var dataval = {section:section, 'search_by':'export_work_order_to_file'};

    loadAircraftWOPopupDataFromServer(section, dataval);
    //$('#woCreateNewWOModal').modal('hide');
    
});

$(document).on('change', '#aircraft_work_order_status', function(e){
    $("#frmAircraftWorkOrderItems :input, #frmAircraftWorkOrderItemOverviews :input, #frmAircraftWorkOrderItemServices :input, .saveAircraftWODetBTN").prop("disabled", false);

    var wo_status = $(this).val();

    if(wo_status == '3' || wo_status == '7'){
        $("#frmAircraftWorkOrderItems :input, #frmAircraftWorkOrderItemOverviews :input, #frmAircraftWorkOrderItemServices :input, .saveAircraftWODetBTN").prop("disabled", true);
    }else if(wo_status == '4' || wo_status == '6'){
        var work_order_id = $('#work_order_id').val();
        if(work_order_id != '' && work_order_id != undefined){
            var section = 'aircraft_wo_completion_password';
            
            var customer_id = $('#wo_customer_id').val();
            var aircraft_id = $('#wo_aircraft_id').val();
            
            var dataval = {section:section, customer_id:customer_id, work_order_id:work_order_id, aircraft_id:aircraft_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
    $('.selectpicker').selectpicker('refresh');
});

$(document).on('change', '#wo_item_status', function(e){
    var wo_item_status = $(this).val();
    if(wo_item_status == '3'){
        var wo_item_id = $('#wo_item_id').val();
        var signoff_category = '1';

        $.ajax({
            url: checkWOItemSignoffComplURL, 
            type: 'post',
            data: {wo_item_id:wo_item_id, signoff_category:signoff_category},
            dataType: "text",
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    if(obj.is_singoff_done == '0'){
                        alert('The primary sign-off must have a sign-off before this item can be marked `Finished`.');
                        $('#wo_item_status').val($('#wo_item_current_status').val());
                        $('.selectpicker').selectpicker('refresh');
                    }
                }
            }
        });
    }
});

$(document).on('click', '.signoff-categories-tr', function(e){
    var categories = $(this).closest("tr").children("td:first").text();
    
    $('.signoff-categories-tr').removeClass('signoff-categories-tr-active');
    $(this).addClass('signoff-categories-tr-active');

    var wo_item_id = $(this).attr('wo-item-id');
    var signoff_category = $(this).attr('signoff-category');
    if(wo_item_id != '' && wo_item_id != undefined && signoff_category != '' && signoff_category != undefined){
        $.ajax({
            url: getWOItemSignoffCategoryDetURL, 
            type: 'post',
            data: {wo_item_id:wo_item_id, signoff_category:signoff_category},
            dataType: "text",
            success: function (response) {
                if(response == 'Failed'){
                    alert('Something went wrong, please try again.');
                }else if(response != 'Empty'){
                    $('.signoff-category-insp-info').html(response);
                    $('.selectpicker').selectpicker('refresh');
                    $('#wo_item_signoff_category_name').val(categories);
                }
            }
        });
    }
});

$(document).on('click', '.wo-signoff-tr', function(e){
    var wo_item_id = $(this).attr('data-val');
    if(wo_item_id != '' && wo_item_id != undefined){
        $('.wo-signoff-tr').removeClass('wo-signoff-tr-active');
        $(this).addClass('wo-signoff-tr-active');

        $.ajax({
            url: getWOItemSignoffCategoryListURL, 
            type: 'post',
            data: {wo_item_id:wo_item_id},
            dataType: "text",
            success: function (response) {
                if(response == 'Failed'){
                    alert('Something went wrong, please try again.');
                }else if(response != 'Empty'){
                    $('.wo-singoff-category-block').html(response);
                    $('.selectpicker').selectpicker('refresh');
                }
            }
        });
    }
});

$(document).on('click', '.saveWOItemSignoffCategory', function(e){
    var inspection_code = $.trim($('#wo_item_signoff_inspection_code').val());
    if(inspection_code != '' && inspection_code != undefined){
        $.ajax({
            url: saveWOItemSignoffCategoryURL, 
            type: 'post',
            data: $('#frmWOItemSignoffCategory').serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    var signoff_info = obj.signoff_info;
                    $('#wo_item_signoff_inspection_code').val('');
                    $('#wo_item_signoff_info').val(signoff_info);
                }
            }
        });
    }else{
        alert("Please fill inspection code.");
    }
});

$(document).on('click', '.loadaircraftworkordertr', function(e){
    $('.loadaircraftworkordertr').removeClass('loadaircraftworkordertr-active');
    $(this).addClass('loadaircraftworkordertr-active');
});

$(document).on('click', '.wo-item-tool-add-btn', function(e){
    var wo_item_id = $('#wo_item_id').val();
    if(wo_item_id != '' && wo_item_id != undefined){
        var section = 'aircraft_wo_tool_add_btn';
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        
        var dataval = {section:section, customer_id:customer_id, wo_item_id:wo_item_id, aircraft_id:aircraft_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.saveWorkOrderItemTools', function(e){
    if($('#wo_item_tool_id').val() != '' && $('#wo_item_tool_id').val() != undefined){
        var clkbtn = 'add';
        var dataval = $('#frmWorkOrderItemTools').serialize();
        saveWOItemToolDetails(dataval, clkbtn);
    }
});

$(document).on('click', '.saveWorkOrderItemEditTools', function(e){
    if($('#wo_item_edit_tool_id').val() != '' && $('#wo_item_edit_tool_id').val() != undefined){
        var clkbtn = 'edit';
        var dataval = $('#frmWorkOrderItemEditTools').serialize();
        saveWOItemToolDetails(dataval, clkbtn);
    }
});

function saveWOItemToolDetails(dataval, clkbtn){
    $.ajax({
        url: saveWorkOrderItemToolURL, 
        type: 'post',
        data: dataval,
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'failure'){
                alert(obj.message);
            }else{
                if(clkbtn == 'add'){
                    $('#aircraftWOAddToolModel').modal('hide');
                    $('#aircraftWOItemToolEditModel').modal('hide');
                }else{
                    alert("Tool detail updated successfully.");
                }
                $('#woitem-tool-body').html(obj.woitemtoolhtml);
            }
        }
    });
}

$(document).on('click', '.woitem-tools-tblrow', function(e){
    $('.woitem-tools-tblrow').removeClass('woitem-tools-tblrow-active');
    $(this).addClass('woitem-tools-tblrow-active');
});

$(document).on('dblclick', '.woitem-tools-tblrow', function(e){
    var wo_item_tool_id = $(this).attr('data-val');
    if(wo_item_tool_id != '' && wo_item_tool_id != undefined){
        var section = 'aircraft_wo_item_tool_edit';
        var wo_item_id = $('#wo_item_id').val();
        
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        
        var dataval = {section:section, customer_id:customer_id, wo_item_tool_id:wo_item_tool_id, aircraft_id:aircraft_id, wo_item_id:wo_item_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
}); 

$(document).on('click', '.wo-item-tool-prev-btn, .wo-item-tool-next-btn', function(e){
    var clickbtn = $(this).attr('data-val');
    
    var wo_item_tool_id = $('#wo_item_edit_tool_id').val();

    if(wo_item_tool_id != '' && wo_item_tool_id != undefined){
        var wo_item_id = $('#wo_item_id').val();
        $.ajax({
            url: getWOItemToolNextPrevURL, 
            type: 'post',
            data: {clickbtn:clickbtn, wo_item_tool_id:wo_item_tool_id, wo_item_id:wo_item_id},
            dataType: "text",
            success: function (response) {
                if(response == 'Failed'){
                    alert('Something went wrong, please try again.');
                }else if(response != 'Empty'){
                    $('.wo-item-tool-edit-block').html(response);
                    $('.selectpicker').selectpicker('refresh');
                    woItemToolBlockDate();
                }
            }
        });
    }
});

$(document).on('click', '.inventory-tool-delete-btn', function(e){
    var wo_item_tool_id = $('#wo_item_edit_tool_id').val();
    if(wo_item_tool_id != '' && wo_item_tool_id != undefined){
        if(confirm('Are you sure want to delete this tool?')){
            var wo_item_id = $('#wo_item_id').val();
            $.ajax({
                url: deleteWOItemToolURL, 
                type: 'post',
                data: {wo_item_tool_id:wo_item_tool_id, wo_item_id:wo_item_id},
                dataType: 'text',
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure'){
                        alert(obj.message);
                    }else{
                        alert('Tool detail deleted successfully.');
                        $('#aircraftWOItemToolEditModel').modal('hide');
                        $('#woitem-tool-body').html(obj.woitemtoolhtml);
                    }
                }
            });
        }
    }
});

$(document).on('click', '.woitem-start-timer-btn', function(e){
    //var current_logged_in_time = new Date().toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
    if($(this).hasClass('woitem-start-service-timer')){
        /*var woitem_end_time = new Date();
        var woitem_start_time = $('#woitem-timerstarttime').val();

        var milliseconds = woitem_end_time.getTime() - new Date(woitem_start_time).getTime();
        var diff_time = milliseconds / (60 * 60 * 1000);

        var technician_hrs_worked = $('#woitem_technician_hrs_worked').val();

        technician_hrs_worked = parseFloat(diff_time)+parseFloat(technician_hrs_worked);
        var total_hrs_for_tech = parseFloat($('#woitem_total_hrs_for_tech').val());
        var total_hrs_for_item = parseFloat($('#woitem_total_hrs_for_item').val());

        technician_hrs_worked = parseFloat(technician_hrs_worked).toFixed(4);
        total_hrs_for_tech = parseFloat(total_hrs_for_tech)+parseFloat(technician_hrs_worked);
        total_hrs_for_item = parseFloat(total_hrs_for_item)+parseFloat(technician_hrs_worked);

        total_hrs_for_item = parseFloat(total_hrs_for_item).toFixed(2);
        total_hrs_for_tech = parseFloat(total_hrs_for_tech).toFixed(2);

        $('#woitem_technician_hrs_worked').val(technician_hrs_worked);
        $('#woitem_total_hrs_for_tech').val(total_hrs_for_tech);
        $('#woitem_total_hrs_for_item').val(total_hrs_for_item);

        $('.starttimestatus').html('NOT ACTIVE');
        $('.starttimestatus').removeClass('starttimer-status-active');
        $(this).text('Start Timer');
        $(this).removeClass('woitem-start-service-timer');*/

        //current_logged_in_time = '';
        $('#is_timer_start').val('2');
    }else{
        /*var current_logged_in_time = new Date().toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
        //var woitem_start_time = new Date();

        //$('#woitem-timerstarttime').val(woitem_start_time);
        //current_logged_in_time = 'Logged in at '+current_logged_in_time;
        $('.starttimestatus').html('ACTIVE');
        $('.starttimestatus').addClass('starttimer-status-active');
        $(this).addClass('woitem-start-service-timer');
        $(this).text('Stop Timer');*/

        $('#is_timer_start').val('1');
    }
    //$('#woitem-loggedin-msg').html(current_logged_in_time);

    saveAircraftWOServicesTechnician();
});

$(document).on('click', '#woitem_services_currently_on_overtime', function(e){
    if($(this).is(':checked')){
        if(confirm('Are you sure want to select `Overtime`?')){

        }
    }
});

$(document).on('click', ".otcinvoice_report", function (e) {
    var customer_otc_invoice_id = $('#customer_otc_invoice_id').val();
    if (customer_otc_invoice_id != '' && customer_otc_invoice_id != undefined){
        var params = {customer_otc_invoice_id:customer_otc_invoice_id};

        downloadPDFAjax(customerOTCInfoInvoiceReportURL, params);
    }
});

function downloadPDFAjax(url, params){
    $.ajax({
        type: "POST",
        url: url,
        data: params,
        success:function(response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                window.open(obj.data);
            } else if(obj.status == 'failure') {
                alert(obj.message);
            }
        },
        error : function() {
            alert('Some error occured. Please try again!');
        }
    });
}

$(document).on('click', '.otcinvoice_ship_to_address', function(e){
    var customer_id = $('#customer_id').val();
    if(customer_id != '' && customer_id != undefined){
        var section = 'cust_addl_ship_addr_btn';
        
        var aircraft_id = '';
        
        var dataval = {section:section, customer_id:customer_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.add_customer_shipto_address', function(e){
    var customer_id = window.location.pathname.split('/').pop();
    if(customer_id != '' && customer_id != undefined){
        var section = 'inventory_customer_add_address';
        if(section != '' && section != undefined){
            var dataval = {section:section, customer_id:customer_id};

            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.customerAddlAddressSaveBtn', function(e){
    var name = $.trim($('#customer_addr_name').val());
    var address = $.trim($('#customer_addr_address').val());
    var city = $.trim($('#customer_addr_city').val());
    var country = $.trim($('#customer_addr_country').val());
    var province = $.trim($('#customer_addr_province').val());
    var state = $.trim($('#customer_addr_state').val());
    var zip = $.trim($('#customer_addr_zip').val());

    var flag = 1;
    if(name == '' || name == undefined){
        flag = 0;
        alert("Please fill name");
    }else if(address == '' || address == undefined){
        flag = 0;
        alert("Please fill address");
    }else if(city == '' || city == undefined){
        flag = 0;
        alert("Please fill city");
    }else if(country == '' || country == undefined){
        flag = 0;
        alert("Please select country");
    }else if((province == '' || province == undefined) && (state == '' || state == undefined)){
        flag = 0;
        alert("Please fill state or province");
    }else if(zip == '' || zip == undefined){
        flag = 0;
        alert("Please fill zip");
    }
    
    if(flag == '1'){
        $.ajax({
            url: saveCustomerAddlAddressURL, 
            type: 'post',
            data: $('#frmCustomerAddAddress').serialize(),
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success'){
                    var customeraddressarr = obj.customeraddressarr;

                    $('#customer_shipping_address_id').html(obj.customeraddrdropdown);
                    $('#customerAddressAddModel').modal('hide');
                    fillCustomerInfoShipAddress(customeraddressarr);

                    $('.selectpicker').selectpicker('refresh');
                }
                alert(obj.message);
            }
        });
    }
});

// To show list of cities on change of country dropdown
$(document).on('change', '#customer_addr_country', function (e) {
    var countryId = $( this ).val();
    $('#customer_addrstate').find('option:not(:first)').remove();
    $("#customer_addr_province").val('');
    if (countryId == '231') {
        $(".customer_addr_provinceblock").css('display', 'none');
        $(".customer_addr_stateblock").css('display', '');
        $.ajax({
            type: "POST",
            url: getStatesList,
            data: {countryId:countryId},
            async : true,
            success: function(response) {
                if (response != '') {
                    $('#customer_addr_state').append(response);
                }
                $('#customer_addr_state').selectpicker('refresh');
            }                   
        });
    } else if(countryId != '231' || countryId == ''){
        $(".customer_addr_provinceblock").css('display', '');
        $(".customer_addr_stateblock").css('display', 'none');

        $('#customer_addr_state').selectpicker('refresh');
    }
});

$(document).on('change', '#customer_shipping_address_id', function(e){
    var shipping_address_id = $(this).val();
    if(shipping_address_id != '' && shipping_address_id != undefined){
        $.ajax({
            type: "POST",
            url: getCustomerShippingAddressURL,
            data: {shipping_address_id:shipping_address_id},
            async : true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success'){
                    var addressarr = obj.customeraddressarr;
                    fillCustomerInfoShipAddress(addressarr);
                }else{
                    alert(obj.message);
                }
            }                   
        });
    }
});

function fillCustomerInfoShipAddress(addressarr){
    $('#customer_shipping_ship_to_address').val(addressarr.address);
    $('#customer_shipping_ship_to_address2').val(addressarr.address2);
    $('#customer_ship_to_city').val(addressarr.city);
    var state = '';
    if(addressarr.state != '' && addressarr.state != null){
        state = addressarr.state;
    }else{
        state = addressarr.province;
    }
    $('#customer_ship_to_state').val(state);
    $('#customer_ship_to_zip').val(addressarr.zip);
    $('#customer_ship_to_country').val(addressarr.country);
    $('.selectpicker').selectpicker('refresh');
}

$(document).on('click', '.customer_aircraft_maintenance', function(e){
    var customer_id = window.location.pathname.split('/').pop();
    if(customer_id != '' && customer_id != undefined){
        var section = 'cust_otc_aircraft_maintenance';
        if(section != '' && section != undefined){
            var aircraft_id = $('#aircraft_id').val();
            var engine_type = $('#aircraft_engine_type').val();

            var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, engine_type:engine_type};
            
            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
});

$(document).on('click', '.saveMaintHelicopterOverviewBtn', function(e){
    $.ajax({
        url: saveAircraftMaintHelicopterOverviewURL,
        type: 'post',
        data: $("#frmAircraftMaintHelicopterOverview").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('#maintenance_helicopter_overview_id').val(obj.id);

                //alert('Overview detail added successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.saveAircraftWOLogBookValHelicopterOverviewBtn', function(e){
    $.ajax({
        url: saveAircraftWOLogBookValHelicopterOverviewURL,
        type: 'post',
        data: $("#frmAircraftWOLogBookValHelicopterOverview").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('#logbook_value_helicopter_overview_id').val(obj.id);

                alert('Overview detail added successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.saveAircraftMainJetEngine', function(e){
    $.ajax({
        url: saveAircraftMaintJetEngineURL,
        type: 'post',
        data: $("#frmAircraftMaintJetEngine").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('#maintenance_jet_engine_id').val(obj.id);

                //alert('Engine detail saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.saveAircraftWOLogBookValJetEngine', function(e){
    $.ajax({
        url: saveAircraftWOLogBookValJetEngineURL,
        type: 'post',
        data: $("#frmAircraftWOLogBookValJetEngine").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('#logbook_value_jet_engine_id').val(obj.id);

                alert('Engine detail saved successfully.');
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.update_maint_overview_time', function(e){
    var inputheading = '';
    var checkboxval = $(this).val();
    $('#is_tc2_block').css('display', 'none');

    if(checkboxval == '1'){
        inputheading = 'Enter the current tach time on the aircraft';
    }else if(checkboxval == '2'){
        inputheading = 'Enter amount of time you want to add';
    }else if(checkboxval == '3' || checkboxval == '4'){
        inputheading = 'Enter the amount of cycles you want to add';
        $('#is_tc2_block').css('display', 'block');
    }

    $('#maint_update_time_heading').html(inputheading);
});

function openUpdateLogbookValueOpenWOPopup(){
    var customer_id = window.location.pathname.split('/').pop();
    if(customer_id != '' && customer_id != undefined){
        var section = 'update_logbook_value_open_wo';
        if(section != '' && section != undefined){
            var aircraft_id = $('#aircraft_id').val();
            var engine_type = $('#aircraft_engine_type').val();

            var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, engine_type:engine_type};
            
            fetchOTCCustomPopupDataFromServer(section, dataval);
        }
    }
}

$(document).on('change', '#aircraft_update_type', function(e){
    var update_type = $(this).val();
    $('.update_current_hobbs').css('display', 'none');
    $('.update_specific_amount_time').css('display', 'none');
    $('.update_current_time').css('display', 'none');

    if(update_type == '1'){
        $('.update_current_time').css('display', 'block');
    }else if(update_type == '2'){
        $('.update_current_hobbs').css('display', 'block');
    }else if(update_type == '3'){
        $('.update_specific_amount_time').css('display', 'block');
    }
});

$(document).on('click', '.updatemainthelicoptertimebtn', function(e){
    var aircraft_update_type = $('#aircraft_update_type').val();
    if(aircraft_update_type == '1'){
        var maint_actt = parseFloat($.trim($('#maint_actt').val()));
        var maint_actc = parseFloat($.trim($('#maint_actc').val()));
        var update_mint_current_tt = parseFloat($('#update_mint_current_tt').val());
        var update_mint_current_tc = parseFloat($('#update_mint_current_tc').val());

        if(maint_actt == '' || maint_actt == '0' || maint_actc == '' || maint_actc == '0'){
            alert("Please set value of ACTT and ACTC");
            return false;
        }else{
            if(confirm("Please verify value entered on this screen:\n\n Update Type: "+$("#aircraft_update_type option:selected").text()+"\nCurrent TT: "+maint_actt+"\nCurrent TC: "+maint_actc+"\n\nIf they are correct, please press Yes to continue.")){
                if(maint_actt > update_mint_current_tt || maint_actc > update_mint_current_tc){
                    alert("A negative amount was entered. If you wish to enter a negative, please add a specific amount of time and try again.");
                    return false;
                }else{
                    $('#maint_actt').val(update_mint_current_tt);
                    $('#maint_actc').val(update_mint_current_tc);

                    $('#aircraftMaintenanceUpdtTimeModel').modal('hide');
                    checkWOLogBookValue();

                    $(".saveMaintHelicopterOverviewBtn").click();
                }
            }
        }
    }else if(aircraft_update_type == '2'){
        var maint_hobbs = parseFloat($.trim($('#maint_hobbs').val()));
        var update_mint_current_hobbs = parseFloat($('#update_mint_current_hobbs').val());

        if(maint_hobbs == '' || maint_hobbs == '0'){
            alert("Please set value of Hobbs");
            return false;
        }else{
            if(confirm("Please verify value entered on this screen:\n\n Update Type: "+$("#aircraft_update_type option:selected").text()+"\nCurrent Hobbs Reading: "+maint_actt+"\n\nIf they are correct, please press Yes to continue.")){
                if(maint_hobbs > update_mint_current_hobbs){
                    alert("A negative amount was entered. If you wish to enter a negative, please add a specific amount of time and try again.");
                    return false;
                }else{
                    $('#maint_hobbs').val(update_mint_current_hobbs);

                    $('#aircraftMaintenanceUpdtTimeModel').modal('hide');
                    checkWOLogBookValue();
                    $(".saveMaintHelicopterOverviewBtn").click();
                }
            }
        }
    }else if(aircraft_update_type == '3'){
        var maint_actt = parseFloat($.trim($('#maint_actt').val()));
        var maint_actc = parseFloat($.trim($('#maint_actc').val()));
        var update_mint_add_time = parseFloat($('#update_mint_add_time').val());
        var update_mint_add_cycles = parseFloat($('#update_mint_add_cycles').val());

        if(maint_actt == '' || maint_actt == '0' || maint_actc == '' || maint_actc == '0'){
            alert("Please set value of ACTT and ACTC");
            return false;
        }else{
            if(confirm("Please verify value entered on this screen:\n\n Update Type: "+$("#aircraft_update_type option:selected").text()+"\nAdd Time: "+maint_actt+"\nAdd Cycles: "+maint_actc+"\n\nIf they are correct, please press Yes to continue.")){
                if(maint_actt > update_mint_add_time || maint_actc > update_mint_add_cycles){
                    alert("A negative amount was entered. If you wish to enter a negative, please add a specific amount of time and try again.");
                    return false;
                }else{
                    var add_time = parseFloat(maint_actt)+parseFloat(update_mint_add_time);
                    var add_cycles = parseFloat(maint_actc)+parseFloat(update_mint_add_cycles);

                    $('#maint_actt').val(add_time);
                    $('#maint_actc').val(add_cycles);

                    $('#aircraftMaintenanceUpdtTimeModel').modal('hide');
                    checkWOLogBookValue();
                    $(".saveMaintHelicopterOverviewBtn").click();
                }
            }
        }
    }
});

$(document).on('click', '.update_wo_logbook_value', function(e){
    if($('#frmUpdateWOLogBookValue input[type=checkbox]:checked').length) {
        if(confirm("Update the selected work orders with the current maintenance information?")){
            $.ajax({
                url: updateWOLogBookValueFromMaintURL,
                type: 'post',
                data: $("#frmUpdateWOLogBookValue").serialize(),
                dataType: 'text',
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'failure') {
                        alert(obj.message);
                    }else{
                        $('#updateLogBookValOpenWOModel').modal('hide');
                    }
                }
            });
        }
    }else{
        alert("Please select work order for update.");
    }
});

$(document).on('click', '.aircraft_create_new_wo_btn', function(e){
    var customer_id = window.location.pathname.split('/').pop();
    if(customer_id != '' && customer_id != undefined){
        var section = 'confirm_create_new_work_order';
        if(section != '' && section != undefined){
            var aircraft_id = $('#aircraft_id').val();
            
            var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id};
            
            $.ajax({
                url: fetchCustomerOTCPopupURL, 
                type: 'post',
                data: dataval,
                async : true,
                success: function (response) {
                    if(response == 'no-data'){
                        section = 'aircraft_create_wo_btn';
                        dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id};
                        fetchOTCCustomPopupDataFromServer(section, dataval);
                    }else{
                        appendCustomerOTCPopupData(section, response);
                    }
                }
            });
        }
    }
});

$(document).on('click', '.wo_item_discrepancy', function(e){
    var wo_item_id = $('#wo_item_id').val();
    var work_order_id = $('#work_order_id').val();
    var section = 'wo_item_discrepancy';
    if(section != '' && section != undefined){
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, wo_item_id:wo_item_id, work_order_id:work_order_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.saveWOItemDiscrepancybtn', function(e){
    if($.trim($('#wo-discrepancy').val()) != ''){
        saveWOItemDiscrepancyData();
    }else if($.trim($('#wo_item_discrepancy').val()) != '' && $.trim($('#wo-discrepancy').val()) == ''){
        if(confirm('Are you sure want to clear this discrepancy?')){
            saveWOItemDiscrepancyData();
        }else{
            $('#wo-discrepancy').val($('#wo_item_discrepancy').val());
        }
    }else if($.trim($('#wo-discrepancy').val()) == ''){
        alert("Please fill discrepancy.");
    }
});

function saveWOItemDiscrepancyData(){
    $.ajax({
        url: saveWOItemDiscrepancyURL,
        type: 'post',
        data: $("#frmWOItemDiscrepancy").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'failure') {
                alert(obj.message);
            }else{
                $('#wo_item_discrepancy').val(obj.discrepancy);
                $('#woItemDiscrepancyModal').modal('hide');
            }
        }
    });
}

$(document).on('click', '.wo_item_discrepancy_history', function(e){
    var wo_item_id = $('#wo_item_id').val();
    var work_order_id = $('#work_order_id').val();
    var section = 'wo_item_discrepancy_history';
    if(section != '' && section != undefined){
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, wo_item_id:wo_item_id, work_order_id:work_order_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.discrepancyhistorylist', function(e){
    $('.discrepancyhistorylist').removeClass('discrepancyhistorylist_active');
    $(this).addClass('discrepancyhistorylist_active');

    var date_modified = '';
    var username = '';
    var discrepancy = '';

    $(this).find('td').each (function(index) {
        if(index == '0'){
            date_modified = $(this).text();
        }else if(index == '1'){
            username = $(this).text();
        }else if(index == '2'){
            discrepancy = $(this).text();
        }
    });
    
    $('#wo_item_discrepancy_history').val(discrepancy);
    $('#discrepancy_username').val(username);
    $('#discrepancy_date_modified').val(date_modified);
});

$(document).on('click', '.wo_item_corrective_action', function(e){
    var wo_item_id = $('#wo_item_id').val();
    var work_order_id = $('#work_order_id').val();
    var section = 'wo_item_corrective_action';
    if(section != '' && section != undefined){
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, wo_item_id:wo_item_id, work_order_id:work_order_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.saveWOItemCorrectiveActionbtn', function(e){
    if($.trim($('#wo_item_corrective_action').val()) != ''){
        saveWOItemCorrectiveActionData();
    }else if($.trim($('#wo-corrective-action').val()) != '' && $.trim($('#wo_item_corrective_action').val()) == ''){
        if(confirm('Are you sure want to clear this corrective action?')){
            saveWOItemCorrectiveActionData();
        }else{
            $('#wo_item_corrective_action').val($('#wo-corrective-action').val());
        }
    }else if($.trim($('#wo_item_corrective_action').val()) == ''){
        alert("Please fill corrective action.");
    }
});

function saveWOItemCorrectiveActionData(){
    $.ajax({
        url: saveWOItemCorrectiveActionURL,
        type: 'post',
        data: $("#frmWOItemCorrectiveAction").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'failure') {
                alert(obj.message);
            }else{
                $('#wo-corrective-action').val(obj.corrective_action);
                $('#woItemCorrectiveActionModal').modal('hide');
            }
        }
    });
}

$(document).on('click', '.wo_item_corrective_action_history', function(e){
    var wo_item_id = $('#wo_item_id').val();
    var work_order_id = $('#work_order_id').val();
    var section = 'wo_item_corrective_action_history';
    if(section != '' && section != undefined){
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, wo_item_id:wo_item_id, work_order_id:work_order_id};

        fetchOTCCustomPopupDataFromServer(section, dataval);
    }
});

$(document).on('click', '.correctiveactionhistorylist', function(e){
    $('.correctiveactionhistorylist').removeClass('correctiveactionhistorylist_active');
    $(this).addClass('correctiveactionhistorylist_active');

    var date_modified = '';
    var username = '';
    var corrective_action = '';

    $(this).find('td').each (function(index) {
        if(index == '0'){
            date_modified = $(this).text();
        }else if(index == '1'){
            username = $(this).text();
        }else if(index == '2'){
            corrective_action = $(this).text();
        }
    });
    
    $('#wo_item_corrective_action_history').val(corrective_action);
    $('#corrective_action_username').val(username);
    $('#corrective_action_date_modified').val(date_modified);
});

$(document).on('change', '#owner_authentication', function(e){
    if(confirm('Are you sure want to change the owner authentication to `No`')){

    }
});

$(document).on('click', '.saveCustRepairOrderRates', function(e){
    $.ajax({
        url: saveCustomerRepairOrderRatesURL,
        type: 'post',
        data: $("#frmCustRepairOrderRates").serialize(),
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'failure') {
                alert(obj.message);
            }else{
                $('#repair_order_rates_id').val(obj.repair_order_rates_id);
                alert("Repair order rates saved successfully.");
            }
        }
    });
});

$(document).on('click', '.repair_order_rates_notification', function(e){
    var alertmsg = 'This change will not affect any repair orders that have already been created.\n\nIf needed, modify the repair orders that have this customer manually.';
    alert(alertmsg);
});

$(document).on('keyup', '#fuel-gallons, #fuel-price', function(e){
    var fuel_gallons = $('#fuel-gallons').val();
    fuel_gallons = fuel_gallons != '' ? fuel_gallons : '0';
    var fuel_price = $('#fuel-price').val();
    fuel_price = fuel_price != '' ? fuel_price : '0';

    var total = parseFloat(fuel_gallons)*parseFloat(fuel_price);
    $('#epa_total_charges').val(total);
});

$(document).on('keyup', '#go_to_wo_item', function(e){
    var id = e.which;
    var last_item_position = $('#last_item_position').val();
    var current_item_position = $(this).val();
    if (id == '13' && current_item_position <= last_item_position) {
        
        var work_order_id = $('#work_order_id').val();
        var item_no = $('#wo_item_no').val();
        item_no = parseInt(item_no)+1;
        if(work_order_id != '' && work_order_id != undefined){
            $('.work-order-prev-btn').prop('disabled', false);
            $('.work-order-next-btn').prop('disabled', false);

            $('#current_item_position').val(current_item_position);
            var seltabid = $('.aircraftWOItemTabs').find('ul.nav').children('li.active').children('a').attr('href');
            
            var is_new_item = 0;
            if(is_new_item == '1'){
                seltabid = '#aircraftWOOverviewSection';
            }

            var btnclickattr = '';

            var customer_id = $('#wo_customer_id').val();
            
            var dataval = {work_order_id:work_order_id, item_no:item_no, current_item_position:current_item_position, is_new_item:is_new_item, customer_id:customer_id};

            submitWOFormData(btnclickattr, dataval, seltabid);
        }
    }
});

$(document).on('click', '.go_to_customer_section', function(e){
    var customer_id = $('#wo_customer_id').val();
    window.location.href = goToCustomerURL+'/'+customer_id;
});