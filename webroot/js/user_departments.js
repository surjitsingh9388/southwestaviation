$(document).on('click', '.add_department_btn', function (e) {
    $('#user_department_id').val('');
    $('#user_department_name').val('');
    $('#addUserDepartmentsPopup').modal('show');
});

$(document).on('click', '.user_department_edit', function (e) {
    var user_department_id = $(this).attr('data-val');
    var department_name = $(this).parent().siblings(":first").text();
    if(user_department_id != '' && user_department_id != undefined && department_name!= '' && department_name!=undefined){
        $('#user_department_id').val(user_department_id);
        $('#user_department_name').val(department_name);
        $('#addUserDepartmentsPopup').modal('show');
    }
});

$(document).on('click', '.save_user_departments', function(e){
    var department_name = $('#user_department_name').val();
    if(department_name != '' && department_name != undefined){
        var user_department_id = $('#user_department_id').val();
        $.ajax({
            url: saveUserDepartmentsURL, 
            type: 'post',
            data: {user_department_id:user_department_id, department_name:department_name},
            async : true,
            success: function (response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success'){
                    alert(obj.message);
                    $('#user_departments_list').html(obj.tblhtml);
                    $('#addUserDepartmentsPopup').modal('hide');
                }else{
                    alert(obj.message);
                }
            }
        });
    }else{
        alert("Please fill Department/Title");
    }
});

$(document).on('click', '.user_department_delete', function(e){
    var user_department_id = $(this).attr('data-val');
    if(user_department_id != '' && user_department_id != undefined){
        if(confirm('Are you sure want to delete this department')){
            $.ajax({
                url: deleteUserDepartmentsURL, 
                type: 'post',
                data: {user_department_id:user_department_id},
                async : true,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success'){
                        alert(obj.message);
                        $('#user_departments_list').html(obj.tblhtml);
                    }else{
                        alert(obj.message);
                    }
                }
            });
        }
    }
});