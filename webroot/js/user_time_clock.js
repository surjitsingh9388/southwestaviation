$(document).on('click', '.fetchUserTimeClockPopup', function(e){
    var section = $(this).attr('data-val');
    if(section != '' && section != undefined){
        var url = fetchUserTimeClockPopupURL;
        var dataval = {section:section};
        fetchCustomOTCPopupDataFromServer(url, dataval, section);
    }
});

$(document).on('click', '.wo-list-all-message', function(e){
    var section = 'customer_otc_message_list';
    if(section != '' && section != undefined){
        var click_source = $(this).attr('click-source');
        if(click_source == 'top_message'){
            $('#customerotcpopup').html('');
        }

        var work_order_id = $('#work_order_id').val();
        var customer_id = $('#wo_customer_id').val();
        var aircraft_id = $('#wo_aircraft_id').val();
        var section_clk = $(this).attr('data-val');
        var wo_item_id = $('#wo_item_id').val();

        var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, work_order_id:work_order_id, wo_item_id:wo_item_id, section_clk:section_clk};

        var url = fetchMessageListPopupURL;
        
        fetchCustomOTCPopupDataFromServer(url, dataval, section);
    }
});

function fetchCustomOTCPopupDataFromServer(url, dataval, section){
    $.ajax({
        url: url, 
        type: 'post',
        data: dataval,
        success: function (response) {
            appendUserTimeClockPopupData(section, response);
        }
    });
}

$(document).on('click', '.wo-send-new-message', function(e){
    
    var section = 'aircraft_wo_send_new_message';
    
    var work_order_id = $('#work_order_id').val();
    var customer_id = $('#wo_customer_id').val();
    var aircraft_id = $('#wo_aircraft_id').val();
    var section_clk = $(this).attr('data-val');
    var wo_item_id = $('#wo_item_id').val();
    var replay_message_id = '';
    if(section_clk == 'reply-msg'){
        replay_message_id = $('#replay_message_id').val();
    }

    var dataval = {section:section, customer_id:customer_id, aircraft_id:aircraft_id, work_order_id:work_order_id, wo_item_id:wo_item_id, section_clk:section_clk, replay_message_id:replay_message_id};

    var url = fetchMessageSendPopupURL;
    
    fetchCustomOTCPopupDataFromServer(url, dataval, section);
    
});

function appendUserTimeClockPopupData(section, response){
    var sectionId = '';
                
    if(section == 'time_clock'){
        sectionId = 'userTimeClockPopup';
    }else if(section == 'check_time_clock_status'){
        sectionId = 'checkTimeClockStatusPopup';
    }else if(section == 'time_clock_adjustment'){
        sectionId = 'userTimeClockAdjustmentPopup';
    }else if(section == 'add_new_time_clock_record'){
        sectionId = 'addNewTimeClockRecordPopup';
        $('#updateTimeClockRecordPopup').remove();
    }else if(section == 'add_hours_to_start_time'){
        sectionId = 'addHoursToStartTimePopup';
    }else if(section == 'time_clock_log'){
        sectionId = 'timeClockReportPopup';
    }else if(section == 'edit_time_clock'){
        sectionId = 'updateTimeClockRecordPopup';
        $('#addNewTimeClockRecordPopup').remove();
    }else if(section == 'customer_otc_message_list'){
        sectionId = 'aircarftWOOptoinMsgForAllUsersModel';
    }else if(section == 'aircraft_wo_send_new_message'){
        sectionId = 'aircraftWOSendNewMsgModel';
    }else if(section == 'aircraft_wo_option_view_message'){
        sectionId = 'aircraftWOOptionViewMsgModel';
    }

    $('#'+sectionId).remove();
    $("#usertimeclockpopup").append(response);
    $('.selectpicker').selectpicker('refresh');
    $('#'+sectionId).modal('show');

    if(section == 'time_clock_adjustment' || section == 'time_clock_log'){
        overrideTimeClockDate();
    }
    if(section == 'add_new_time_clock_record' || section == 'edit_time_clock'){
        overrideTimeClockDateTime();
        if(section == 'edit_time_clock'){
            calculateTimeClockRecordHour();
        }
    }
}

function overrideTimeClockDate(){
    $('#time_clock_adjustment_date, #time_clock_log_date_from, #time_clock_log_date_to').datetimepicker({
        format: 'MM/DD/YYYY'
    });
}

function overrideTimeClockDateTime(){
    $('#login_time_clock_date, #logout_time_clock_date').datetimepicker({
        format: 'MM/DD/YYYY hh:mm:ss A',
        useCurrent: false,
    }).on('dp.change', function(e) {
        calculateTimeClockRecordHour();
        $(this).datetimepicker('hide');
    });
}

function calculateTimeClockRecordHour(){
    var logindate = $('#login_time_clock_date').val();
    var logoutdate = $('#logout_time_clock_date').val();
    if(logindate != '' && logindate != undefined && logoutdate != '' && logoutdate != undefined){
        var milliseconds = Math.abs(new Date(logoutdate) - new Date(logindate));
        const secs = Math.floor(Math.abs(milliseconds) / 1000);
        const mins = Math.floor(secs / 60);
        const hours = Math.floor(mins / 60);
        var remainmins = Math.floor(mins % 60);
        remainmins = remainmins < '10' ? '0'+remainmins : remainmins;
        var calhour = hours+'.'+remainmins;
        calhour = parseFloat(calhour).toFixed(2);
        
        $('#user_time_clock_cal_hour').val(calhour);
    }
}

$(document).on('keyup', '#login_time_clock_date, #logout_time_clock_date', function(e){
    calculateTimeClockRecordHour();
});

$(document).on('click', '.input-group-addon', function(e){
    var ids = $(this).siblings("input").attr('id');
    $('#'+ids).datetimepicker('show');
});

$(document).on('click', '.checkUserTimeClockStatus', function(e){
    var user_time_clock_code = $.trim($('#user_time_clock_status_code').val());
    if(user_time_clock_code != '' && user_time_clock_code != undefined){
        $.ajax({
            url: checkUserTimeClockStatusURL,
            type: 'post',
            data: {user_time_clock_code:user_time_clock_code},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                $('#checkTimeClockStatusPopup').modal('hide');
                alert(obj.message);
            }
        });
    }
});

$(document).on('click', '.markUserTimeClockBtn', function(e){
    var user_time_clock_code = $.trim($('#user_time_clock_code').val());
    if(user_time_clock_code != '' && user_time_clock_code != undefined){
        $.ajax({
            url: markUserTimeClockURL,
            type: 'post',
            data: {user_time_clock_code:user_time_clock_code},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                $('#user_time_clock_code').val('');
                alert(obj.message);
                if(obj.status == 'success'){
                    $('#userTimeClockPopup').modal('hide');
                }
            }
        });
    }else{
        alert('Please enter a Time Clock code and try again.');
    }
});

$(document).on('click', '.load_time_clock_record', function(e){
    var user_id = $.trim($('#time_clock_adjustment_user_id').val());
    var time_clock_date = $.trim($('#time_clock_adjustment_date').val());

    if(user_id != '' && user_id != undefined && time_clock_date != '' && time_clock_date!= undefined){
        $.ajax({
            url: loadTimeClockForDateURL,
            type: 'post',
            data: {user_id:user_id, time_clock_date:time_clock_date},
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success'){
                    $('#time_clock_adjustment_tbody').html(obj.timeclockhtml);
                }else{
                    alert(obj.message);
                }
            }
        });
    }else{
        alert('Please fill Employee Name or Date');
    }
});

$(document).on('click', '.saveAddNewTimeClockRecord', function(e){
    var user_id = $('#adjustment_user_id').val();
    var logindate = $.trim($('#login_time_clock_date').val());
    var logoutdate = $.trim($('#logout_time_clock_date').val());
    //console.log(user_id+'--'+logindate+'--'+logoutdate);
    if(user_id != '' && logindate != '' && logoutdate != ''){
        $.ajax({
            url: saveUserTimeClockURL,
            type: 'post',
            data: $('#frmAddNewTimeClockRecord').serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                alert(obj.message);
                if(obj.status == 'success'){
                    $('#addNewTimeClockRecordPopup').modal('hide');
                }
            }
        });
    }else{
        alert('Please Select Employee Name, Log In and Log Out and try again.');
    }
});

/*$(document).on('keypress', '#add_hours_to_start_time', function(event) {
    if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
}).on('paste', function(event) {
    event.preventDefault();
});*/

$(document).on('click', '.changeLogoutTime', function(e){
    var logindate = $('#login_time_clock_date').val();
    var logoutdate = $('#logout_time_clock_date').val();
    if(logindate != '' && logoutdate != ''){
        var calhours = $('#add_hours_to_start_time').val();
        calhours = $.trim(calhours);
        if(calhours != ''){
            var calhoursarr = calhours.split('.');
            var flag = 0;
            var hours = '00';
            var minutes = '00';
            if(calhoursarr.length>'1'){
                if(calhoursarr['0'] >= 12 && calhoursarr['1'] > '00'){
                    alert('Please enter a number greate than 0 and less than 12.');
                    flag = '1';
                }else if(calhoursarr['0'] > 12 || calhoursarr['1'] > 59){
                    alert('Please enter a number greate than 0 and less than 12.');
                    flag = '1';
                }else{
                    hours = calhoursarr['0'];
                    minutes = calhoursarr['1'];
                }
            }else{
                if(calhoursarr['0'] > 12){
                    alert('Please enter a number greate than 0 and less than 12.');
                    flag = '1';
                }else{
                    hours = calhoursarr['0'];
                }
            }
            if(flag == '0'){
                var startDateTime = new Date(logindate);
                var startDateTimeInMilliseconds = startDateTime.getTime();
                var hoursInMilliseconds = parseInt(hours)*60*60*1000;
                var minutesInMilliseconds = parseInt(minutes)*60*1000;
                var futureInMilliseconds = startDateTimeInMilliseconds + hoursInMilliseconds + minutesInMilliseconds;

                var futureDateTime = new Date(futureInMilliseconds);
                logoutdate = moment(futureDateTime).format('MM-DD-YYYY hh:mm:ss A');
                $('#logout_time_clock_date').val(logoutdate);

                $('#addHoursToStartTimePopup').modal('hide');

                calculateTimeClockRecordHour();
            }
        }
    }else{
        alert("Log In and Log Out can not be blank.");
    }
});

$(document).on('dblclick', '.time_clock_adjustment_tr', function(e){
    var time_clock_id = $(this).attr('data-val');
    if(time_clock_id != '' && time_clock_id != undefined){
        var section = 'edit_time_clock';
        var dataval = {section:section, time_clock_id:time_clock_id};
        fetchUserTimeClockPopup(section, dataval)
    }
});

function fetchUserTimeClockPopup(section, dataval){
    $.ajax({
        url: fetchUserTimeClockPopupURL, 
        type: 'post',
        data: dataval,
        async : true,
        success: function (response) {
            appendUserTimeClockPopupData(section, response);
        }
    });
}

$(document).on('click', '.time_clock_adjustment_tr', function(e){
    $('.time_clock_adjustment_tr').removeClass('time_clock_adjustment_tr_active');
    $(this).addClass('time_clock_adjustment_tr_active');
});

$(document).on('click', '.saveUpdateTimeClock', function(e){
    var logindate = $.trim($('#login_time_clock_date').val());
    var logoutdate = $.trim($('#logout_time_clock_date').val());
    
    if(logindate != '' && logoutdate != ''){
        $.ajax({
            url: saveUserTimeClockURL,
            type: 'post',
            data: $('#frmUpdateTimeClockRecord').serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                alert(obj.message);
            }
        });
    }else{
        alert('Please Select Log In and Log Out and try again.');
    }
});

$(document).on('change', '#time_clock_log_report', function(e){
    $('#time_clock_log_user_id').val('');
    if($(this).val() != '1' && $(this).val() != '2'){
        $('#time_clock_log_user_id').attr('disabled', true);
    }else{
        $('#time_clock_log_user_id').attr('disabled', false);
    }
    $('.selectpicker').selectpicker('refresh');
});

$(document).on('click', ".previewTimeClockReports", function (e) {
    var startDate = new Date($('#time_clock_log_date_from').val());
    var endDate = new Date($('#time_clock_log_date_to').val());

    var time_clock_log_report = $('#time_clock_log_report').val();
    var time_clock_log_user_id = $('#time_clock_log_user_id').val();

    var flag = 0;
    if (startDate > endDate){
        var flag = 1;
        alert("Date From should less from Date To");
    }else if((time_clock_log_report == '1'  || time_clock_log_report == '2') && (time_clock_log_user_id == '' || time_clock_log_user_id ==  undefined)){
        var flag = 1;
        alert("Select Employee Name");
    }

    if(flag == '0'){
        var params = $("#frmTimeClockReports").serialize();

        downloadPDFAjax(timeClockLogReportsPdfURL, params);
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

$(document).on('click', '.sendWOViewMessage', function(e){
    var message_to = $('#message-to').val();
    var message_subject = $('#message-subject').val();
    var message = $('#message').val();

    if(message_to != '' && message_subject != '' && message != ''){
        $.ajax({
            url: sendWOViewMessageURL,
            type: 'post',
            data: $("#frmSendWOViewMessage").serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#aircraftWOSendNewMsgModel').modal('hide');
                    $('.wo-option-message-list').html(obj.msgtr);
                    alert('Message sent successfully.');
                }else{
                    alert(obj.message);
                }
            }
        });
    }else{
        alert('Please fill to, subject and message');
    }
});

$(document).on('click', '.wo-option-message-tr', function(e){
    var message_id = $(this).attr('data-val');
    $('.wo-option-message-tr').removeClass('wo_new_message');
    $('.wo-option-message-tr').each(function(index,item){
        if($(this).attr('is-read') == '0' && message_id != $(this).attr('data-val')){
            $(this).addClass('wo_new_message');
        }
    });
    
    $('.wo-option-message-tr').removeClass('wo-option-message-active');
    $(this).addClass('wo-option-message-active');
});

$(document).on('dblclick', '.wo-option-message-tr', function(e){
    var message_id = $(this).attr('data-val');
    
    if(message_id != '' && message_id != undefined){
        var section = 'aircraft_wo_option_view_message';
        if(section != '' && section != undefined){
            var dataval = {section:section, message_id:message_id};
            var url = fetchMessageViewPopupURL;
            fetchCustomOTCPopupDataFromServer(url, dataval, section);
        }
    }
});

$(document).on('click', '.wo-option-refresh-message', function(e){
    var work_order_id = $('#work_order_id').val();
    
    $.ajax({
        url: refreshWOViewMessageListURL,
        type: 'post',
        data: {work_order_id:work_order_id},
        dataType: 'text',
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj.status == 'success') {
                $('.wo-option-message-list').html(obj.msgtr);
            }else{
                alert(obj.message);
            }
        }
    });
    
});

$(document).on('click', '.wo-option-delete-message', function(e){
    var message_id = $('.wo-option-message-active').attr('data-val');
    
    if(message_id != '' && message_id != undefined){
        if(confirm('Are you sure you want to remove this message?')){
            $.ajax({
                url: deleteWOViewMessageURL, 
                type: 'post',
                data: {message_id:message_id},
                dataType: "text",
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('.wo-option-message-list').html(obj.msgtr);
                    }else{
                        alert(obj.message);
                    }
                }
            });
        }
    }
});

setInterval(function() {
    checkNewMessageCount();
}, 1000 * 60 * 1);

function checkNewMessageCount(){
    $.ajax({
        url: checkNewMessageURL,
        type: 'post',
        data: {},
        dataType: 'text',
        success: function (response) {
            var messagecount = $.trim(response);
            $('.message-counter').css('display', 'none');
            if(messagecount > '0'){
                $('.message-counter').css('display', 'block');
            }
            $('.message-counter').html(messagecount);
        }
    });
}

$(document).on("change", "#checkall_messages", function () {
    $(".check_messages").prop('checked', $(this).prop("checked"));
    if($(".check_messages:checked").length == '0'){
        mark_msg_read_unread = 'Mark as read';
        is_mark_read = '1';

        $('.mark_msg_read_unread').css('display', 'none');
    }else{
        $('.mark_msg_read_unread').css('display', 'block');
    }

    woMessageReadUnreadBtn();
});

$(document).on("change", ".check_messages", function () {
    if($(".check_messages").length==$(".check_messages:checked").length){
        $("#checkall_messages").prop('checked', true);
    }else{
        $("#checkall_messages").prop('checked', false);
    }

    woMessageReadUnreadBtn();
});

function woMessageReadUnreadBtn(){
    var mark_msg_read_unread = 'Mark as unread';
    var is_mark_read = '0';
    $(".check_messages:checked").each(function(index,item){
        if($(this).parent().parent().attr('is-read') == '0'){
            mark_msg_read_unread = 'Mark as read';
            is_mark_read = '1';
        }
    });
    if($(".check_messages:checked").length == '0'){
        mark_msg_read_unread = 'Mark as read';
        is_mark_read = '1';

        $('.mark_msg_read_unread').css('display', 'none');
    }else{
        $('.mark_msg_read_unread').css('display', 'block');
    }
    $('.mark_msg_read_unread').html(mark_msg_read_unread);
    $('#wo_is_mark_read').val(is_mark_read);
}

$(document).on('click', '.message_action_dropdown', function(e){
    var message_action = $(this).attr('data-val');
    $("#checkall_messages").prop('checked', false);
    $(".check_messages").prop('checked', false);
    var mark_msg_read_unread = 'Mark as read';
    var is_mark_read = '1';

    if(message_action == 'none'){
        $("#checkall_messages").prop('checked', true);
        $(".check_messages").prop('checked', true);
    }else if(message_action == 'unread'){
        $('.wo-option-message-tr').each(function(index,item){
            if($(this).attr('is-read') == '0'){
                $(this).find('td:last .check_messages').prop('checked', true);
            }
        });
    }else if(message_action == 'read'){
        $('.wo-option-message-tr').each(function(index,item){
            if($(this).attr('is-read') == '1'){
                $(this).find('td:last .check_messages').prop('checked', true);
            }
        });
        mark_msg_read_unread = 'Mark as unread';
        is_mark_read = '0';
    }
    $('.mark_msg_read_unread').html(mark_msg_read_unread);
    $('#wo_is_mark_read').val(is_mark_read);
    if($(".check_messages").length==$(".check_messages:checked").length){
        $("#checkall_messages").prop('checked', true);
    }else{
        $("#checkall_messages").prop('checked', false);
    }

    if($(".check_messages:checked").length > '0'){
        $('.mark_msg_read_unread').css('display', 'block');
    }
});

$(document).on('click', '.mark_msg_read_unread', function(e){
    var msgsel = $(".check_messages:checked").length;
    if(msgsel > '0'){
        $.ajax({
            url: markMessageReadUnreadURL,
            type: 'post',
            data: $('#frmMarkMessageReadUnread').serialize(),
            dataType: 'text',
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'failure'){
                    alert(obj.message);
                }else{
                    $('.wo-option-message-list').html(obj.msgtr);
                    $("#checkall_messages").prop('checked', false);
                    $('.mark_msg_read_unread').html('Mark as read');
                    $('#wo_is_mark_read').val('1');
                    $('.mark_msg_read_unread').css('display', 'none');
                }
            }
        });
    }else{
        alert("Please select atleast one message.");
    }
});

$(document).on('click', '#delete-user-time-clock-btn', function(e){
    var time_clock_id = $('.time_clock_adjustment_tr_active').attr('data-val');
    
    var user_id = $.trim($('#time_clock_adjustment_user_id').val());
    var time_clock_date = $.trim($('#time_clock_adjustment_date').val());

    if(user_id != '' && user_id != undefined && time_clock_date != '' && time_clock_date!= undefined && time_clock_id != '' && time_clock_id!= undefined){
        if(confirm('Are you sure you want to remove this time clock?')){
            $.ajax({
                url: deleteUserTimeClockURL,
                type: 'post',
                data: {user_id:user_id, time_clock_date:time_clock_date, time_clock_id:time_clock_id},
                dataType: 'text',
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success'){
                        $('#time_clock_adjustment_tbody').html(obj.timeclockhtml);
                    }else{
                        alert(obj.message);
                    }
                }
            });
        }
    }
});

$(document).on('click', '.pto-request-approve-deny-btn', function(e){
    var pto_requests_status = $(this).attr('data-val');
    var pto_request_id = $(this).attr('pto-request-id');
    var source = $(this).attr('source');
    
    var msg = pto_requests_status == '2' ? 'Approve' : 'Deny';
    if(pto_request_id != '' && pto_request_id != undefined && pto_requests_status != '' && pto_requests_status != undefined){
        if(confirm('Are you sure you want to '+msg+' this PTO Request?')){
            $.ajax({
                url: approveDenyPTORequestsURL,
                type: 'post',
                data: {pto_request_id:pto_request_id, pto_requests_status:pto_requests_status},
                dataType: 'text',
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success'){
                        if(source == '' || source == undefined){
                            alert(obj.message);
                            $('#aircraftWOOptionViewMsgModel').hide();
                            $('#aircraftWOOptionViewMsgModel').remove();
                        }else{
                            window.location.reload();
                        }
                    }else{
                        alert(obj.message);
                    }
                }
            });
        }
    }
});