$(document).ready(function() {
	$('.datePicker').datetimepicker({
        format: 'MM/DD/YYYY',
        useCurrent: true
    });

    $('.timePicker').datetimepicker({
        format: 'HH:mm'
    });

    $('body').on('focus',".datePicker", function(){
        $(this).datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: true
        });
    });

    $('body').on('focus',".timePicker", function(){
        $(this).datetimepicker({
            format: 'HH:mm',
            useCurrent: true
        });
    });

    //Generate trip id
    function tripID() {
        var encKey = "";
        var possible = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        for (var i = 0; i < 11; i++) {
            encKey += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return encKey;
    }

    //Save flightlog data(all the tabs data at once)
    $(document).on("click", ".saveLaterCls", function(e) {
        e.stopPropagation();
        e.preventDefault();

        //Get tripid
        var tripid = $('#tripId').val();
        if(tripid == '') {
            tripid = tripID();
        }

        //Change form_type value for flightlog save later
        var flstatus = $(this).data('flstatus');
        if(flstatus != '' || flstatus != 'undefined') {
        	$('#tabs .formTypeCls').val(flstatus);
        }
        
        $('form.frmFlightLegs').each(function() {
            var form = $(this);
            if(form.valid()) {
                var formData = 'trip_id=' + tripid +'&'+ form.serialize();
                
                $.ajax({
                    type: "POST",
                    url: saveFLSData,
                    data: formData,
                    success: function(response) {
                        var obj = JSON.parse(response);
                        if(obj.status == 'success') {
                        	if(obj.formtype == 'fldetail') {
                                if(obj.datatype == 'initial') {
                                    window.location = "flightlogSuccess/"+obj.tripid;
                                } else {
                                    window.location = "../flightlogSuccess/"+obj.tripid;
                                }
                        	} else if(obj.formtype == 'flrelease') { 
                        		window.location = "../releaseSuccess/"+obj.tripid;
                        	} else if(obj.formtype == 'flpreplanning') { 
                                window.location = "../preplanningSuccess/"+obj.tripid;
                            } else {
                        		if(obj.datatype == 'initial') {
                                    window.location = "dispatchSuccess/"+obj.tripid;
                                } else if(obj.datatype == 'copytrip') { 
                                    window.location = "../../dispatchSuccess/"+obj.tripid;
                                } else {
                                    window.location = "../dispatchSuccess/"+obj.tripid;
                                }
                        	}
                        } else if(obj.status == 'failure') {
                            $('.flsErrMsg').html('<span style="color:red;">'+obj.message+'</span>');
                        }
                    }
                });
            } else {
                return false;
            }
        });
    });

    //Save flightlog data(all the tabs data at once)
    /*$(document).on("click", ".saveLaterCls", function(e) {
        e.stopPropagation();
        e.preventDefault();

        //Get tripid
        var tripid = $('#tripId').val();
        if(tripid == '') {
            tripid = tripID();
        }

        //Change form_type value for flightlog save later
        var flstatus = $(this).data('flstatus');
        if(flstatus != '' || flstatus != 'undefined') {
            $('#tabs .formTypeCls').val(flstatus);
        }
        
        $('form.frmFlightLegs').each(function() {
            var form = $(this);
            if(form.valid()) {
                var formData = 'trip_id=' + tripid +'&'+ form.serialize();
                
                $.ajax({
                    type: "POST",
                    url: saveFLSData,
                    data: formData,
                    success: function(response) {
                        var obj = JSON.parse(response);
                        if(obj.status == 'success') {
                            if(obj.formtype == 'fldetail') {
                                if(obj.datatype == 'initial') {
                                    window.location = "flightlogSuccess/"+obj.tripid;
                                } else {
                                    window.location = "../flightlogSuccess/"+obj.tripid;
                                }
                            } else if(obj.formtype == 'flrelease') { 
                                window.location = "../releaseSuccess/"+obj.tripid;
                            } else if(obj.formtype == 'flpreplanning') { 
                                window.location = "../preplanningSuccess/"+obj.tripid;
                            } else {
                                if(obj.datatype == 'initial') {
                                    window.location = "dispatchSuccess/"+obj.tripid;
                                } else if(obj.datatype == 'copytrip') { 
                                    window.location = "../../dispatchSuccess/"+obj.tripid;
                                } else {
                                    window.location = "../dispatchSuccess/"+obj.tripid;
                                }
                            }
                        } else if(obj.status == 'failure') {
                            $('.flsErrMsg').html('<span style="color:red;">'+obj.message+'</span>');
                        }
                    }
                });
            }
        });
    });*/

    //Delete initially added crew members
    $(document).on("click", ".crewDelCls", function() {
        var tabnum = $('#tabs li.ui-tabs-active').data('tabnum');
        var crew_member = $(this).data('crew_member');
        if (confirm('Delete this crew member from this flight leg?')) {
            $('#tab'+tabnum+' .crewRecord'+crew_member).remove();
        }
    });

    //Delete leg tab
    $(document).on("click", ".deleteLeg", function() {
        var tabnum = $('#tabs li.ui-tabs-active').data('tabnum');
        console.log(tabnum);
        var tabCnt = $("div#tabs ul li").length;
        if (confirm('Delete this leg?')) {
            if(tabCnt != 1) {
                $('#tabs li.tab'+tabnum).remove();
                $('div#tab'+tabnum).remove();
                $("div#tabs").tabs("refresh");
            }
        }
    });

    //Release and Preplanning - Open trip files listing popup
    $(document).on('click', '#viewTripFiles', function(e) {
        var tripid = $(this).data('tripid');
        
        $.ajax({
            type: 'post',
            url: tripFilesList,
            data: {tripid:tripid},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#uploadTripPopup .filesCountCls').html(obj.tfcount);
                    $('#displayTripFilesModel #openTripFlBtn').remove();
                    $('#displayTripFilesModel .tripFilesList').html(obj.data);
                    $('#displayTripFilesModel .editTFCls').prop('disabled', true);
                    $('#displayTripFilesModel').modal('show');
                    setTimeout(function() {
                        $('.loader').hide();
                    }, 500);
                }                
            }
        });
    });

    //Dispatch success and flightlog page - Open trip files listing popup
    $(document).on('click', '#uploadTripPopup', function(e) {
        var tripid = $(this).data('tripid');
        
        $.ajax({
            type: 'post',
            url: tripFilesList,
            data: {tripid:tripid},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#uploadTripPopup .filesCountCls').html(obj.tfcount);
                        $('#displayTripFilesModel #openTripFlBtn').data("tripid", tripid);
                        $('#displayTripFilesModel .tripFilesList').html(obj.data);
                        $('#displayTripFilesModel').modal('show');
                    }, 500);
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#displayTripFilesModel').modal('show');
                    }, 500);
                }               
            }
        });
    });

    //Trip files upload popup (Dispatch success and flightlog page)
    $(document).on("click", "#openTripFlBtn", function() {
        var tripid = $(this).data('tripid');
        $('#uploadTripFilesModel .upTripidCls').val(tripid);
        $('#uploadTripFilesModel').modal('show');
    });

    //Upload Trip File popup validation
    $("#upTripFilesFrm").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'file_name': {
                required: true
            },   
            'title': {
                required: true
            }  
        },
        message: {
            'file_name': {
                required: "Please choose a file to upload."
            },   
            'title': {
                required: "Please enter title."
            }  
        }
    });

    //Update/Edit Trip File popup validation
    $("#updateTFFrm").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'file_name': {
                required: true
            },   
            'title': {
                required: true
            }  
        },
        message: {
            'file_name': {
                required: "Please choose a file to upload."
            },   
            'title': {
                required: "Please enter title."
            }  
        }
    });

    //Save uploaded trip files files ((Dispatch success and flightlog page))
    $(document).on('click', '#saveTripFileBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        if($('#upTripFilesFrm').valid()) {
            var form = $("#upTripFilesFrm");
            var formData = new FormData(form[0]);

            $.ajax({
                type: 'post',
                url: saveTripFiles,
                data: formData,
                async: true,
                beforeSend: function () {
                    $('.loader').show();
                },
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('.filesCountCls').html(obj.tfcount);
                        $('#uploadTripPopup .filesCountCls').html(obj.tfcount);
                        $('#displayTripFilesModel .tripFilesList').html(obj.data);
                        setTimeout(function() {
                            $('.loader').hide();
                            $('#uploadTripFilesModel .tripFileStatusMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        }, 500);
                    } else {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('#uploadTripFilesModel .tripFileStatusMsg').html('<span style="color:red;">'+obj.message+'</span>');
                        }, 500);
                    }                  
                },
                error : function() {
                    alert('Some error occured. Please try again!');
                    setTimeout(function() {
                        $('#uploadTripFilesModel').modal('hide');
                    }, 2000);
                },
                complete: function () {
                    setTimeout(function() {
                        $('#uploadTripFilesModel').modal('hide');
                    }, 2000);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    });

    //Open Update/Edit trip files popup
    $(document).on('click', '.editTFCls', function(e) {
        var tfid = $(this).data('id');
        var tripid = $(this).data('tripid');
        
        $.ajax({
            type: 'post',
            url: editTFPopup,
            data: {tfid:tfid, tripid:tripid},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#updateTFModel .updateTFId').val(obj.tfid);
                    $('#updateTFModel .deleteTFBtn').data("tfid", obj.tfid);
                    $('#updateTFModel .upTripidCls').val(obj.tripid);
                    $('#updateTFModel .deleteTFBtn').data("tripid", obj.tripid);
                    $('#updateTFModel .modal-body').html(obj.data);
                    $('#updateTFModel').modal('show');
                    setTimeout(function() {
                        $('.loader').hide();
                    }, 500);
                }                
            }
        });
    });

    //Save Update/Edit trip file model
    $(document).on('click', '#updateTFBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        if($('#updateTFFrm').valid()) {
            var form = $("#updateTFFrm");
            var formData = new FormData(form[0]);

            $.ajax({
                type: 'post',
                url: saveTripFiles,
                data: formData,
                async: true,
                beforeSend: function () {
                    $('.loader').show();
                },
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('.filesCountCls').html(obj.tfcount);
                        $('#uploadTripPopup .filesCountCls').html(obj.tfcount);
                        $('#displayTripFilesModel .tripFilesList').html(obj.data);
                        setTimeout(function() {
                            $('.loader').hide();
                            $('#updateTFModel .tripFileStatusMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        }, 500);
                    } else {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('#updateTFModel .tripFileStatusMsg').html('<span style="color:red;">'+obj.message+'</span>');
                        }, 500);
                    }                  
                },
                error : function() {
                    alert('Some error occured. Please try again!');
                    setTimeout(function() {
                        $('#updateTFModel').modal('hide');
                    }, 2000);
                },
                complete: function () {
                    setTimeout(function() {
                        $('#updateTFModel').modal('hide');
                    }, 2000);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    });

    //Delete trip files
    $(document).on('click', '.deleteTFBtn', function(e) {
        var tfid = $(this).data('tfid');
        var tripid = $(this).data('tripid');
        
        $.ajax({
            type: 'post',
            url: deleteTripFile,
            data: {tfid:tfid, tripid:tripid},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#updateTFModel .tripFileStatusMsg').html('<span style="color:green;">'+obj.message+'</span>');
                    setTimeout(function() {
                        $('.loader').hide();
                        $('.filesCountCls').html(obj.tfcount);
                        $('#uploadTripPopup .filesCountCls').html(obj.tfcount);
                        $('#displayTripFilesModel .tripFilesList').html(obj.data);
                        $('#updateTFModel').modal('hide');
                    }, 500);
                }  else {
                    $('#updateTFModel .tripFileStatusMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateTFModel').modal('hide');
                    }, 500);
                }               
            }
        });
    });
    
});