$(document).ready(function() {
    
    $(".manufacturersavebtn").click(function(){
        $('form#frmManufacturer').submit();
    });

    $(".changeinvmanufacturer").click(function(e){
        var msg = '';
        if($(this).attr('data-val') == '0'){
            msg = 'This record will no longer be displayed in the system. Confirm deactivate?';
        }else{
            msg = 'This record will be reactivated. Confirm reactivation?';
        }

        if(confirm(msg)){
            var status = $(this).attr('data-val');
            var vendor_id = window.location.pathname.split('/').pop();
            var url = updateVednorStatusURL;
            
            $("#actionForm").attr("action",url);
            $("#actionForm").append("<input type='hidden' name='id' value='"+vendor_id+"'/>");
            $("#actionForm").append("<input type='hidden' name='status' value='"+status+"'/>");
            $("#actionForm").submit();
        }
    });
});

$(document).on('click', '.invmanufacturertbl tbody tr', function (e) {
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".chkBoxCls").val();

    window.location.href = inventoryRequestDetailsURL+'/'+values;
});