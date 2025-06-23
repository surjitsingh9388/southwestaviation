$(document).ready(function() {
    if ( typeof transactionactionlist !== 'undefined'){
        $("#transaction_action").tokenInput(transactionactionlist, {
            theme: "facebook",
            preventDuplicates: true,
            searchingText: 'Searching action...',
            hintText: 'Search action',
            zindex: '9999'
        });
    }
});

$(document).on('dp.change', ".expirationdate", function(e){
    localStorage.setItem('applyfilter', '0');

    var expiration_date = $("#expiration_date").val();
    
    $("#expiration_date_end").val(expiration_date);
    
    var dateString = moment(new Date(expiration_date)).format('DD-MMM-YYYY');
    $("#expirationheading").text(dateString);

    searchApplyExpFilter();
})

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

$(document).on('click', '.applyExpfilterbtn', function(e){
    $("#expiration_date").val($("#expiration_date_end").val());
    var dateString = moment(new Date($("#expiration_date_end").val())).format('DD-MMM-YYYY');
    $("#expirationheading").text(dateString);
    localStorage.setItem('applyfilter', '1');
    $('.selectpicker').selectpicker('refresh');
    searchApplyExpFilter();
    $('#popupFilterModel').modal('hide');
});

function searchApplyExpFilter(){
    var formdata = $("#formPopupSearch").serialize();
    var dataTable = $('#datatableListingPage').DataTable();
    dataTable.columns(1).search(formdata).draw();
}

$(document).on('change', "#qty_range", function(e){
    $("#maxqty").attr('disabled', 'disabled');
    if($(this).val() == '2'){
        $("#maxqty").removeAttr('disabled');
    }
})

$(document).on('change', '#transaction_period', function(e){
    $("#period_date").val('1');
    $("#period_date_end").attr('disabled', 'disabled');
    $("#period_date").val('1');

    if($(this).val() == '4'){
        $("#period_custom_range").css('display', 'block');
    }else{
        $("#period_custom_range").css('display', 'none');
    }
    $('.selectpicker').selectpicker('refresh');
});

$(document).on('change', "#period_date", function(e){
    $("#period_date_end").attr('disabled', 'disabled');
    $("#period_date_end").val('');
    if($(this).val() == '2'){
        $("#period_date_end").removeAttr('disabled');
    }
});

$(document).on('click', '.invtransactionreporttable tbody td', function (e) {
    if ($(this).index() == 0 ) {
        return;
    }
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".transactionrepid").val();
    if(values != undefined){
        window.location.href = transactionReportDetURL+'/'+values;
    }
});