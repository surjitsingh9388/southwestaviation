<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;

$allPilots = $pilotComp->getPilots();
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Crew Documents</h2>
        </div>

        <div class="page-content mt-35" id="pilotStatusCls">
            <div class="tableScroll">
            
                <div class="form-horizontal form-label-left">
                    <div class=" panel-default">
                        <div class="panel-heading">
                            <div class="selectWrap">
                                <div class="col-md-3 col-sm-4 col-xs-12 p-0">
                                    <?php 
                                    echo $this->Form->control('pilot_id', array('options'=>$allPilots, 'class'=>'form-control selectpicker selDropDCls', 'div'=>false, 'label' => 'Choose Crew Member', 'value'=>$pilotId));
                                    ?>
                                </div>
                                <div class="col-md-9 col-sm-8 p-0">
                                    <button id="openUploadDocs" class="btn btn-success" style="float:right; margin-top:15px;">Upload new document</button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body displayCrewDocs">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="docborder">
                                            <h5>Crew Documents</h5>
                                            <div class="badge docbadge"><span><span class="docCount"></span> Documents(s)</span></div>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="documentsCls"></div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="docborder">
                                            <h5>Drug & Alcohol Testing</h5>
                                            <div class="badge docbadge"><span><span class="drgCount"></span> Documents(s)</span></div>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="drugCls"></div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="docborder">
                                            <h5>Training Records</h5>
                                            <div class="badge docbadge"><span><span class="trnCount"></span> Documents(s)</span></div>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="trainingCls"></div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="docborder">
                                            <h5>PRIA</h5>
                                            <div class="badge docbadge"><span><span class="prnCount"></span> Documents(s)</span></div>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="priaCls"></div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="docborder">
                                            <h5>Checking Currency</h5>
                                            <div class="badge docbadge"><span><span class="chkCount"></span> Documents(s)</span></div>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="checkingCls"></div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->element('pilots_popup'); ?>

<script>
$(document).ready(function() {
    $( window ).on( "load", function() {
        var pilotid = $('#pilot-id').val();
        var startDate = $('.startDate').val();
        var endDate = $('.endDate').val();

        //Assign in data attribute
        $('#openUploadDocs').data('pilot_id', pilotid);

        crewDocuments(pilotid);
    });

    //Select pilot
    $(".selDropDCls").on("change", function() {
        var pilotid = $('#pilot-id').val();
        var startDate = $('.startDate').val();
        var endDate = $('.endDate').val();

        //Assign in data attribute
        $('#openUploadDocs').data('pilot_id', pilotid);

        crewDocuments(pilotid);
    });

    //Get crew reports
    function crewDocuments(pilotid) {
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'Pilots', 'action'=>'getCrewDocuments']); ?>",
            data: {pilotid:pilotid},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {                  
                    setTimeout(function() {
                        $('.loader').hide();
                        $('.documentsCls').html(obj.data.documents);
                        $('.trainingCls').html(obj.data.training);
                        $('.checkingCls').html(obj.data.checking);
                        $('.drugCls').html(obj.data.drug);
                        $('.priaCls').html(obj.data.pria);
                        $('.docCount').html(obj.data.docCount);
                        $('.trnCount').html(obj.data.trnCount);
                        $('.chkCount').html(obj.data.chkCount);
                        $('.drgCount').html(obj.data.drgCount);
                        $('.prnCount').html(obj.data.prnCount);
                    }, 1000);
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('.documentsCls').html(obj.data.documents);
                        $('.trainingCls').html(obj.data.training);
                        $('.checkingCls').html(obj.data.checking);
                        $('.drugCls').html(obj.data.drug);
                        $('.priaCls').html(obj.data.pria);
                        $('.docCount').html(obj.data.docCount);
                        $('.trnCount').html(obj.data.trnCount);
                        $('.chkCount').html(obj.data.chkCount);
                        $('.drgCount').html(obj.data.drgCount);
                        $('.prnCount').html(obj.data.prnCount);
                    }, 1000);
                }
            }                   
        });
    }

    //Change category
    $("#crewDocsCategory").on("change", function() {
        var catVal = $(this).val();
        //console.log(catVal);
        if(catVal == 'documents') {
            $("#crewDocuments").show();
            $('#crewDocuments').prop('disabled', false);

            $("#crewTraining").hide();
            $('#crewTraining').prop('disabled', 'disabled');

            $("#crewChecking").hide();
            $('#crewChecking').prop('disabled', 'disabled');

            $("#crewDrug").hide();
            $('#crewDrug').prop('disabled', 'disabled');

            $("#crewPria").hide();
            $('#crewPria').prop('disabled', 'disabled');

            $("#docNameContainer").hide();
            $('#docNameId').prop('disabled', 'disabled');

            $("#docAircraftContainer").hide();
            $('#aircraftDocuments').prop('disabled', 'disabled');
        } else if(catVal == 'training') {
            $("#crewDocuments").hide();
            $('#crewDocuments').prop('disabled', 'disabled');
            
            $("#crewTraining").show();
            $('#crewTraining').prop('disabled', false);

            $("#crewChecking").hide();
            $('#crewChecking').prop('disabled', 'disabled');

            $("#crewDrug").hide();
            $('#crewDrug').prop('disabled', 'disabled');

            $("#crewPria").hide();
            $('#crewPria').prop('disabled', 'disabled');

            $("#docNameContainer").hide();
            $('#docNameId').prop('disabled', 'disabled');

            $("#docAircraftContainer").hide();
            $('#aircraftDocuments').prop('disabled', 'disabled');
        } else if(catVal == 'checking') {
            $("#crewDocuments").hide();
            $('#crewDocuments').prop('disabled', 'disabled');
            
            $("#crewTraining").hide();
            $('#crewTraining').prop('disabled', 'disabled');

            $("#crewChecking").show();
            $('#crewChecking').prop('disabled', false);

            $("#crewDrug").hide();
            $('#crewDrug').prop('disabled', 'disabled');

            $("#crewPria").hide();
            $('#crewPria').prop('disabled', 'disabled');

            $("#docNameContainer").hide();
            $('#docNameId').prop('disabled', 'disabled');

            $("#docAircraftContainer").hide();
            $('#aircraftDocuments').prop('disabled', 'disabled');
        } else if(catVal == 'drug') {
            $("#crewDocuments").hide();
            $('#crewDocuments').prop('disabled', 'disabled');
            
            $("#crewTraining").hide();
            $('#crewTraining').prop('disabled', 'disabled');

            $("#crewChecking").hide();
            $('#crewChecking').prop('disabled', 'disabled');

            $("#crewDrug").show();
            $('#crewDrug').prop('disabled', false);

            $("#crewPria").hide();
            $('#crewPria').prop('disabled', 'disabled');

            $("#docNameContainer").hide();
            $('#docNameId').prop('disabled', 'disabled');

            $("#docAircraftContainer").hide();
            $('#aircraftDocuments').prop('disabled', 'disabled');
        } else if(catVal == 'pria') {
            $("#crewDocuments").hide();
            $('#crewDocuments').prop('disabled', 'disabled');
            
            $("#crewTraining").hide();
            $('#crewTraining').prop('disabled', 'disabled');

            $("#crewChecking").hide();
            $('#crewChecking').prop('disabled', 'disabled');

            $("#crewDrug").hide();
            $('#crewDrug').prop('disabled', 'disabled');

            $("#crewPria").show();
            $('#crewPria').prop('disabled', false);

            $("#docNameContainer").hide();
            $('#docNameId').prop('disabled', 'disabled');

            $("#docAircraftContainer").hide();
            $('#aircraftDocuments').prop('disabled', 'disabled');
        }
    });

    //Change subcategory
    $(".docNameHide").on("change", function() {
        var subCatVal = $(this).val();
        if(subCatVal == 'Other') {
            $("#docNameContainer").show();
            $('#docNameId').prop('disabled', false);
        } else {
            $("#docNameContainer").hide();
            $('#docNameId').prop('disabled', 'disabled');
        }

        var optArray = ['Initial Flight/SIM Training', 'Recurrent Flight/SIM Training', 'Instructor/Check Airman Training', '293 (a) 2-3 (b) Aircraft Specific', '293 (a) 2-3 (b) / 297 Combo Check'];
        if(jQuery.inArray(subCatVal, optArray) !== -1) {
            $("#docAircraftContainer").show();
            $('#aircraftDocuments').prop('disabled', false);
        } else {
            $("#docAircraftContainer").hide();
            $('#aircraftDocuments').prop('disabled', 'disabled');
        }
    });

    //Dynamically open popup with values(to display all data listing)
    $(document).on("click", "#openUploadDocs", function() {
        var pilotid = $(this).data('pilot_id');
        var curDate = new Date();
        curDate = moment(curDate).format('MM/DD/YYYY'); 
        //console.log(curDate);
        
        $('.loader').show();
        setTimeout(function() {
            $('.loader').hide();
            $('#uploadDocsModel .pilotIdCls').val(pilotid);
            $('#uploadDocsModel .dPCls').val(curDate);
            $('#uploadDocsModel').modal('show');
        }, 500);
    });

    //Save crew documents
    $(document).on('click', '#uploadDocsSaveBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var form = $("#uploadDocsFrm");
        var formData = new FormData(form[0]);

        var file = $('#fileNameId').val();
        if(file !='') {
            $.ajax({
                type: 'post',
                url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'uploadDocs']); ?>",
                data: formData,
                async: true,
                beforeSend: function () {
                    $('.loader').show();
                },
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('#uploadDocsMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        }, 500);
                    } else {
                        setTimeout(function() {
                            $('.loader').hide();
                            $('#uploadDocsMsg').html('<span style="color:red;">'+obj.message+'</span>');
                        }, 500);
                    }                  
                },
                error : function() {
                    alert('Some error occured. Please try again!');
                    setTimeout(function() {
                        $('#uploadDocsModel').modal('hide');
                    }, 2000);
                },
                complete: function () {
                    setTimeout(function() {
                        $('#uploadDocsModel').modal('hide');
                    }, 2000);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        } else {
            alert('Please choose a file to upload.');
        }
    });

    //Open update popup
    $(document).on('click', '.fa-edit', function(e) {
        var id = $(this).data('id');
        var pilotid = $(this).data('pilot_id');
        
        $.ajax({
            type: 'post',
            url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'updateDocsPopup']); ?>",
            data: {id:id, pilotid:pilotid},
            async: true,
            beforeSend: function () {
                //$('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#updateDocsModel .modal-body').html(obj.data);
                    $('#updateDocsModel').modal('show');
                    //setTimeout(function() {
                        //$('.loader').hide();
                        
                    //}, 500);
                }                
            }
        });
    });

    //Initiate after html load
    $(document).on("show.bs.modal",".modal", function () {
        $('.datePicker').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: true
        });

        //Hide/show popup field
        $("#docDiffFile").click(function () {
            if($('input#docDiffFile').is(':checked')) {
                $('#uploadNewFile').show();
                $('input#updateFileNameId').prop('disabled', false);
            } else {
                $('#uploadNewFile').hide();
                $('input#updateFileNameId').prop('disabled', 'disabled');
            }
        });

        //Update change category
        $("#updateCrewDocsCategory").on("change", function() {
            var docType = $(this).data('document_type');
            var catVal = $(this).val();
            console.log(catVal);
            if(catVal == 'documents') {
                $("#updateCrewDocuments").show();
                $('#updateCrewDocuments').prop('disabled', false);

                $("#updateCrewTraining").hide();
                $('#updateCrewTraining').prop('disabled', 'disabled');

                $("#updateCrewChecking").hide();
                $('#updateCrewChecking').prop('disabled', 'disabled');

                $("#updateCrewDrug").hide();
                $('#updateCrewDrug').prop('disabled', 'disabled');

                $("#updateCrewPria").hide();
                $('#updateCrewPria').prop('disabled', 'disabled');

                /*$("#updateDocNameContainer").hide();
                $('#updateDocNameId').prop('disabled', 'disabled');

                $("#updateDocAircraftContainer").hide();
                $('#updateAircraftDocuments').prop('disabled', 'disabled');*/
            } else if(catVal == 'training') {
                $("#updateCrewDocuments").hide();
                $('#updateCrewDocuments').prop('disabled', 'disabled');
                
                $("#updateCrewTraining").show();
                $('#updateCrewTraining').prop('disabled', false);

                $("#updateCrewChecking").hide();
                $('#updateCrewChecking').prop('disabled', 'disabled');

                $("#updateCrewDrug").hide();
                $('#updateCrewDrug').prop('disabled', 'disabled');

                $("#updateCrewPria").hide();
                $('#updateCrewPria').prop('disabled', 'disabled');

                /*$("#updateDocNameContainer").hide();
                $('#updateDocNameId').prop('disabled', 'disabled');

                $("#updateDocAircraftContainer").hide();
                $('#updateAircraftDocuments').prop('disabled', 'disabled');*/
            } else if(catVal == 'checking') {
                $("#updateCrewDocuments").hide();
                $('#updateCrewDocuments').prop('disabled', 'disabled');
                
                $("#updateCrewTraining").hide();
                $('#updateCrewTraining').prop('disabled', 'disabled');

                $("#updateCrewChecking").show();
                $('#updateCrewChecking').prop('disabled', false);

                $("#updateCrewDrug").hide();
                $('#updateCrewDrug').prop('disabled', 'disabled');

                $("#updateCrewPria").hide();
                $('#updateCrewPria').prop('disabled', 'disabled');

                /*$("#updateDocNameContainer").hide();
                $('#updateDocNameId').prop('disabled', 'disabled');

                $("#updateDocAircraftContainer").hide();
                $('#updateAircraftDocuments').prop('disabled', 'disabled');*/
            } else if(catVal == 'drug') {
                $("#updateCrewDocuments").hide();
                $('#updateCrewDocuments').prop('disabled', 'disabled');
                
                $("#updateCrewTraining").hide();
                $('#updateCrewTraining').prop('disabled', 'disabled');

                $("#updateCrewChecking").hide();
                $('#updateCrewChecking').prop('disabled', 'disabled');

                $("#updateCrewDrug").show();
                $('#updateCrewDrug').prop('disabled', false);

                $("#updateCrewPria").hide();
                $('#updateCrewPria').prop('disabled', 'disabled');

                /*$("#updateDocNameContainer").hide();
                $('#updateDocNameId').prop('disabled', 'disabled');

                $("#updateDocAircraftContainer").hide();
                $('#updateAircraftDocuments').prop('disabled', 'disabled');*/
            } else if(catVal == 'pria') {
                $("#updateCrewDocuments").hide();
                $('#updateCrewDocuments').prop('disabled', 'disabled');
                
                $("#updateCrewTraining").hide();
                $('#updateCrewTraining').prop('disabled', 'disabled');

                $("#updateCrewChecking").hide();
                $('#updateCrewChecking').prop('disabled', 'disabled');

                $("#updateCrewDrug").hide();
                $('#updateCrewDrug').prop('disabled', 'disabled');

                $("#updateCrewPria").show();
                $('#updateCrewPria').prop('disabled', false);

                /*$("#updateDocNameContainer").hide();
                $('#updateDocNameId').prop('disabled', 'disabled');

                $("#updateDocAircraftContainer").hide();
                $('#updateAircraftDocuments').prop('disabled', 'disabled');*/
            }

            if(docType == 'Other') {
                $("#updateDocNameContainer").show();
                $('#updateDocNameId').prop('disabled', false);
            } else {
                $("#updateDocNameContainer").hide();
                $('#updateDocNameId').prop('disabled', 'disabled');
            }

            var optArray = ['Initial Flight/SIM Training', 'Recurrent Flight/SIM Training', 'Instructor/Check Airman Training', '293 (a) 2-3 (b) Aircraft Specific', '293 (a) 2-3 (b) / 297 Combo Check'];
            if(jQuery.inArray(docType, optArray) !== -1) {
                $("#updateDocAircraftContainer").show();
                $('#updateAircraftDocuments').prop('disabled', false);
            } else {
                $("#updateDocAircraftContainer").hide();
                $('#updateAircraftDocuments').prop('disabled', 'disabled');
            }

        });

        //Change subcategory
        $(".updateDocNameHide").on("change", function() {
            var subCatVal = $(this).val();
            if(subCatVal == 'Other') {
                $("#updateDocNameContainer").show();
                $('#updateDocNameId').prop('disabled', false);
            } else {
                $("#updateDocNameContainer").hide();
                $('#updateDocNameId').prop('disabled', 'disabled');
            }

            var optArray = ['Initial Flight/SIM Training', 'Recurrent Flight/SIM Training', 'Instructor/Check Airman Training', '293 (a) 2-3 (b) Aircraft Specific', '293 (a) 2-3 (b) / 297 Combo Check'];
            if(jQuery.inArray(subCatVal, optArray) !== -1) {
                $("#updateDocAircraftContainer").show();
                $('#updateAircraftDocuments').prop('disabled', false);
            } else {
                $("#updateDocAircraftContainer").hide();
                $('#updateAircraftDocuments').prop('disabled', 'disabled');
            }
        });

    });

    //Update crew documents or details
    $(document).on('click', '#updateDocsSaveBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var form = $("#updateDocsFrm");
        var formData = new FormData(form[0]);
        $.ajax({
            type: 'post',
            url: "<?php echo $this->Url->build(['controller' => 'Pilots', 'action' => 'updateDocsDetails']); ?>",
            data: formData,
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateDocsMsg').html('<span style="color:green;">'+obj.message+'</span>');
                    }, 500);
                } else {
                    setTimeout(function() {
                        $('.loader').hide();
                        $('#updateDocsMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    }, 500);
                }                  
            },
            error : function() {
                alert('Some error occured. Please try again!');
                setTimeout(function() {
                    $('#updateDocsModel').modal('hide');
                }, 2000);
            },
            complete: function () {
                setTimeout(function() {
                    $('#updateDocsModel').modal('hide');
                }, 2000);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    //Delete documents
    $(document).on("click", ".fa-trash", function() {
        var docMsg = $(this);
        var docId = $(this).data('id');
        var pilotId = $(this).data('pilot_id');
        
        if (confirm('Are you sure you want to delete this?')) {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller'=>'Pilots', 'action'=>'deleteDocuments']); ?>",
                data: {docId:docId, pilotId:pilotId},
                async: true,
                beforeSend: function () {
                    $('.loader').show();
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        setTimeout(function() {
                            $('.loader').hide();
                            docMsg.closest('div[class^="displayDoc"]').html('<span style="color:green;">'+obj.message+'</span>').hide(3000);
                        }, 1000);
                    } else {
                        setTimeout(function() {
                            $('.loader').hide();
                            docMsg.closest('div[class^="displayDoc"]').html('<span style="color:red;">'+obj.message+'</span>').hide(3000);
                        }, 1000);
                    }
                }
            });
        }   
    });
    

});
</script>
