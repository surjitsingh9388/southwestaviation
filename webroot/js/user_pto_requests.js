$( document ).ready(function() {
    overridePTODate();
});

var cloneCount = 2;
$(document).on('click', '.ptoaddmorebtn', function(e){
    
    var counter = Math.floor(Math.random()*(999-100+1)+100);
    $.ajax({
        url: addNewRowPTORequestAjaxURL, 
        type: 'post',
        data: {counter:counter},
        async : true,
        success: function (response) {
            //response.find("select").val("");
            $('#userptoaddblock').append(response);
            $('.selectpicker').selectpicker('refresh');
            overridePTODate();
        }
    });
    
});

$(document).on('click', '.deleteptobtm', function(e){
    $(this).closest('tr').remove()
});

function overridePTODate(){
    $('.pto_date_of_day').datetimepicker({
        format: 'MM-DD-YYYY'
    }).on('dp.change', function(e){
        if(e.date){
            //alert('Date chosen: ' + e.date.format('MM-DD-YYYY') );
            var d=new Date(e.date.format('MM-DD-YYYY'));
            var daynum = d.getDay();
            daynum = daynum == '0' ? '7' : daynum;
            $(this).closest('tr').find('.day_of_week').val(daynum);
            $('.selectpicker').selectpicker('refresh');
        }
    });

    $('.pto_time_from, .pto_time_to').datetimepicker({
        format: "HH:mm"
    });
}

$(document).on("click", ".userptorequestssave", function(){
    $('form#frmPTORequests').submit();
});

$(document).on('click', '.view_pto_request_det', function(e){
    var pto_request_id = $(this).attr('pto-request-id');
    if(pto_request_id != '' && pto_request_id != undefined){
        $.ajax({
            url: getPTORequestDetURL, 
            type: 'post',
            data: {pto_request_id:pto_request_id},
            async : true,
            success: function (response) {
                $('.porequestpopup').html(response);
                $('#ptoRequestsDetailsModel').modal('show');
            }
        });
    }
});

$(document).on('click', '.pto_request_create_btn', function(e){
    var pto_request_id = $(this).attr('data-val');
    $.ajax({
        url: createPTORequestPopupURL, 
        type: 'post',
        data: {pto_request_id:pto_request_id},
        async : true,
        success: function (response) {
            $('#dashboardpopup').html(response);
            $('.selectpicker').selectpicker('refresh');
            overridePTODate();
            $('#createPTORequestsPopupModel').modal('show');
        }
    });
});

$(document).on('click', '.pto_request_savebtn', function(e){
    var pto_request_id = $('#user_pto_request_id').val();
    $.ajax({
        url: savePTORequestAjaxURL, 
        type: 'post',
        data: $('#frmPTORequests').serialize(),
        async : true,
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success'){
                alert(obj.message);
                $('#createPTORequestsPopupModel').modal('hide');
                if(pto_request_id != ''){
                    window.location.reload();
                }
                
            }else{
                alert(obj.message);
            }
        }
    });
});

$(document).on('click', '.add_pto_accrual_rate_btn', function (e) {
    $('#pto_accrual_rate_id').val('');
    $('#pto_accrual_rate').val('');
    $('#pto_accrual_rate_value').val('');
    $('#addPTOAccrualRatePopup').modal('show');
});

$(document).on('click', '.pto_accrual_rate_edit', function (e) {
    var pto_accrual_rate_id = $(this).attr('data-val');
    var pto_accrual_rate = $(this).parent().siblings(":first").text();
    var pto_accrual_rate_value = $(this).parent().siblings("td:nth-child(2)").text();

    if(pto_accrual_rate_id != '' && pto_accrual_rate_id != undefined && pto_accrual_rate!= '' && pto_accrual_rate!=undefined && pto_accrual_rate_value!= '' && pto_accrual_rate_value!=undefined){
        $('#pto_accrual_rate_id').val(pto_accrual_rate_id);
        $('#pto_accrual_rate').val(pto_accrual_rate);
        $('#pto_accrual_rate_value').val(pto_accrual_rate_value);
        $('#addPTOAccrualRatePopup').modal('show');
    }
});

$(document).on('click', '.save_pto_accrual_rate', function(e){
    var pto_accrual_rate = $('#pto_accrual_rate').val();
    var pto_accrual_rate_value = $('#pto_accrual_rate_value').val();
    if(pto_accrual_rate != '' && pto_accrual_rate != undefined && pto_accrual_rate_value != '' && pto_accrual_rate_value != undefined){
        var pto_accrual_rate_id = $('#pto_accrual_rate_id').val();
        $.ajax({
            url: savePTOAccrualRatesURL, 
            type: 'post',
            data: {pto_accrual_rate_id:pto_accrual_rate_id, pto_accrual_rate:pto_accrual_rate, pto_accrual_rate_value:pto_accrual_rate_value},
            async : true,
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success'){
                    alert(obj.message);
                    $('#pto_accrual_rate_list').html(obj.tblhtml);
                    $('#addPTOAccrualRatePopup').modal('hide');
                }else{
                    alert(obj.message);
                }
            }
        });
    }else{
        alert("Please fill all fields");
    }
});

$(document).on('click', '.pto_accrual_rate_delete', function(e){
    var pto_accrual_rate_id = $(this).attr('data-val');
    if(pto_accrual_rate_id != '' && pto_accrual_rate_id != undefined){
        if(confirm('Are you sure want to delete this pto accrual rate')){
            $.ajax({
                url: deletePTOAccrualRatesURL, 
                type: 'post',
                data: {pto_accrual_rate_id:pto_accrual_rate_id},
                async : true,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success'){
                        alert(obj.message);
                        $('#pto_accrual_rate_list').html(obj.tblhtml);
                    }else{
                        alert(obj.message);
                    }
                }
            });
        }
    }
});

//delete PTO Request
$(document).on('click', '.pto_request_delete_btn', function(e){
    if(confirm('Are you sure want to delete this PTO Request')){
        var url = $(this).attr("data-url");
        var pto_request_id = $(this).attr("data-val");
        $("#actionForm").attr("action",url);
        $("#actionForm").append("<input type='hidden' name='id' value='"+pto_request_id+"'/>");
        $("#actionForm").submit();
    }
});