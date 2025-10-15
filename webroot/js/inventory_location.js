$(document).ready(function() {
    //Sorting script
    
    var table = $('#quantitiesTable');
    $('#partNumberId, #serialNoId, #qtyId, #costId, #locationId, #receivedId, #lastTransactionId, #statusId')
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

    $('.changelocstatus').click(function(e){
        var confirmmsg = 'This record will no longer be displayed in the system. Confirm deactivate?';
        var status = $(this).attr('data-val');
        if(status == '0'){
            confirmmsg = 'This record will no longer be displayed in the system. Confirm deactivate?';
        }else{
            confirmmsg = 'This record will be reactivated. Confirm reactivation?';
        }
        if(confirm(confirmmsg)){
            var location_id = window.location.pathname.split('/').pop();
            var url = updateLocationStatusURL;
            
            $("#actionForm").attr("action",url);
            $("#actionForm").append("<input type='hidden' name='id' value='"+location_id+"'/>");
            $("#actionForm").append("<input type='hidden' name='status' value='"+status+"'/>");
            $("#actionForm").submit();
        }
    });

    $('.showinactiveloc').change(function(e){
        var location_id = window.location.pathname.split('/').pop();
        var status = 1;
        if($(this).is(":checked")){
            status = 0;
        }
        
        $.ajax({
            url: childLocationSearchAjaxURL, 
            type: 'post',
            data: {'status':status, 'location_id':location_id},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $("#childLocationList").html(obj.tblrow);
                } else {
                    $('#childLocationList').html('<tr><td colspan="2"><i>'+obj.message+'</i></td></tr>');
                }
            }
        });
    });

    $(document).on('click', '#quantitiesTable tbody td', function (e) {
        if ($(this).index() == 0 ) {
            return;
        }
        var row = $(this).closest("tr");    // Find the row
        var values = row.find(".chkBoxCls").val();
        if(values != undefined){
            window.location.href = inventoriesDetailPageURL+'/'+values;
        }
    });

    $("form#frmInvenotryLocations #location-name, #parent-location-id, #location-status").on("keyup change", function(){
        disableEnableLocationSaveBtn();
    });

    disableEnableLocationSaveBtn();

    $(".btn-label-default").click(function(){
        $(".btn-label-default").removeClass('active');

        $(this).addClass('active');
        if($(this).text() == 'Top-Level Location'){
            $('.parent-location-block').css('display', 'none');
            $('#parent-location-id').val('');
            $('#parent-location-id').prop('required',false);
            $('.selectpicker').selectpicker('refresh');
        }else{
            $('.parent-location-block').css('display', '');
            $('#parent-location-id').prop('required',true);
            $('.selectpicker').selectpicker('refresh');
        }
    })

});

//Search
$(document).on("keyup", "#quantitesSearchItem", function() {
    var value = $(this).val().toLowerCase();
    $("#quantitiesList tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        $('.overview-detail').css('display','none');
    });
});


function disableEnableLocationSaveBtn(){
    var errors = 0;
    if($('label.active').text() == 'Top-Level Location'){
        $("form#frmInvenotryLocations #location-name, #location-status").map(function(){
            if( !$.trim($(this).val()) ) {
                errors++;
            } 
        });
    }else{
        $("form#frmInvenotryLocations #location-name, #parent-location-id, #location-status").map(function(){
            if( !$.trim($(this).val()) ) {
                errors++;
            } 
        });
    }
    
    if(errors > 0){
        $(".locationsavebtn").attr("disabled", "disabled");
    }else{
        $(".locationsavebtn").removeAttr("disabled");
    }
}

$(document).on('change', '.invquantitiesaction', function(e){
    if($(this).val() == '2' || $(this).val() == '3' || $(this).val() == '4'){
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
    }

    $(this).val('');
    $('.selectpicker').selectpicker('refresh');
});

$(document).on('click', '#showinactiveinv', function(e){
    var inactiveinv = 0;
    if($("#showinactiveinv").prop('checked') == true){
        inactiveinv = 1;
    }
    getQuantitiesData(inactiveinv);
});

function getQuantitiesData(inactiveinv){
    var location_id = window.location.pathname.split('/').pop();
    var table = $('#quantitiesTable');
    $.ajax({
        url: locationQantitiesSearchAjaxURL, 
        type: 'post',
        data: {'location_id':location_id, 'inactiveinv':inactiveinv},
        dataType: 'text',
        success: function (response) {
            $("#quantitiesList").html(response);
        }
    });
}

$(document).on('click', ".printQuantitiesBarCodeBtn", function (e) {
    var id = window.location.pathname.split('/').pop();
    var pagesizeval = $("input[type='radio'][name='quantities_size']:checked").val();
    $.ajax({
        type: "POST",
        url: printQantitiesBarCodeURL,
        data: {id: id, pagesizeval:pagesizeval},
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
});

$(document).on('click', '.locationcheckbox', function(e){
    var locationdata = $('input[name="locationprintopt"]:checked').serialize();
    
    if(locationdata != undefined && locationdata != ''){
        $(".printLocationBarcodes").removeAttr('disabled');
    }else{
        $(".printLocationBarcodes").attr('disabled', 'disabled');
    }
});

$(document).on('click', '.printLocationBarcodes', function(e){
    var locationdata = new Array();
    $('input[name="locationprintopt"]:checked').each(function() {
        locationdata.push($(this).val());
    });
    var checkboxdata = new Array();
    $('input[name="childcheckbox"]:checked').each(function() {
        checkboxdata.push($(this).val());
    });
    
    var params = {locationdata: locationdata, checkboxdata:checkboxdata};
    downloadPDFAjax(printLocationBarCodeURL, params);
})