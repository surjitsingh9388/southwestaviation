<?php
$sessionUser = $this->request->getSession()->read('Auth');;

use Cake\Routing\Router;

if (!empty($planeId)) {
    $style = 'style="pointer-events:auto;"';
    $disabled = '';
} else {
    $style = 'style="pointer-events:none; background-color:#ccc;"';
    $disabled = 'disabled';
}
?>

<?php echo $this->Html->css('signature/jquery.signaturepad'); ?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading">Aircraft Discrepancies</h2>
            <div class="btnWrap">
                <div class="checkbox-alignment">
                    <?php
                    echo $this->Form->checkbox('show_closed', [
                        'id' => 'showClosedDicp',
                        'label' => false,
                        'data-plane_id' => $planeId,
                        'disabled' => $disabled,
                        'style' => 'margin: 0;'

                    ]);
                    ?>
                    <label for="showClosedDicp" style="margin-left: 5px; margin-bottom: 0;">Show Closed</label>
                </div>

                <div class="split-btn" style="margin-right: 15px; margin-bottom: 12px;">
                    <?php
                    echo $this->Form->control('plane_id', [
                        'options' => $planes,
                        'empty' => 'Select Aircraft',
                        'class' => 'btn selectpicker',
                        'data-show-subtext' => true,
                        'data-live-search' => true,
                        'required' => 'required',
                        'label' => false,
                        'div' => false,
                        'id' => 'aircraftId',
                        'value' => $planeId
                    ]);
                    ?>
                </div>

                <?php
                if ((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                    echo "<a href='javascript:void(0);' class='btn btn-default' data-plane_id='" . $planeId . "' id='addDiscrepancy' $style style='margin-bottom:12px;'> 
            <i class='fa fa-plus'></i> Create Discrepancy</a>";
                }
                ?>
            </div>

        </div>
        <img src="" id="hello">

        <div class="page-content mt-35">
            <div class="tableScroll">
                <table class="table" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Date</th>
                            <th width="50%">Discrepancy</th>
                            <th width="10%">Time</th>
                            <th width="10%">Discovered By</th>
                            <th width="5%">MEL</th>
                            <th width="10%">Repair By</th>
                            <th width="5%">--</th>
                        </tr>
                    </thead>
                    <tbody id="closedDiscreancyId">
                        <?php
                        if (!empty($discrepResult)) {
                            foreach ($discrepResult as $key => $value) {
                                if (!empty($value["discrepancy_mel"])) {
                                    $dispMel = "yes";
                                    $melRepairBy = date('m/d/Y', strtotime($value["mel_repair_by"]));
                                } else {
                                    $dispMel = "no";
                                    $melRepairBy = '';
                                }
                        ?>
                                <tr>
                                    <td data-id="<?php echo $value['id']; ?>" data-plane_id="<?php echo $planeId; ?>" class="updateDiscrepancy" style="cursor: pointer; color: #5395cf;"><?php echo date('m/d/Y', strtotime($value["discrepancy_date"])); ?></td>
                                    <td><?php echo $value["discrepancy"]; ?></td>
                                    <td><?php echo date('H:i', strtotime($value["discrepancy_time"])); ?></td>
                                    <td><?php echo $value["discovered_by"]; ?></td>
                                    <td><?php echo $dispMel; ?></td>
                                    <td><?php echo $melRepairBy; ?></td>
                                    <td>
                                        <?php
                                        if (empty($value["discrepancy_corrected"])) {
                                            echo '<button type="button" class="close deleteBtnCls" data-id="' . $value['id'] . '" data-plane_id="' . $planeId . '">&times;</button>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center;'>No discrepancies found for the selected aircraft</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Discrepancy popup -->
<?php echo $this->element('discrepancy_popup'); ?>
<!-- Discrepancy popup -->

<?php echo $this->Html->script('signature/jquery.signaturepad.js'); ?>
<?php echo $this->Html->script('html2canvas.min'); ?>
<?php echo $this->Html->script('signature/json2.min'); ?>
<script>
    $(document).ready(function() {

        //Get discrepancy dropdown on select aircraft
        $('#aircraftId').on('change', function() {
            var planeId = $(this).val();
            var form = $('<form method="GET" action="<?php echo Router::url(['controller' => 'AircraftDiscrepancies', 'action' => 'index', '']); ?>/' + planeId + '">');
            //form.append('<input type="hidden" name="AircraftId" value="'+planeId+'">');
            $('body').append(form);
            form.submit();
        });

        $(document).on("click", "#addDiscrepancy", function() {
            var planeId = $(this).data('plane_id');

            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller' => 'AircraftDiscrepancies', 'action' => 'addDiscrepancy']); ?>",
                data: {
                    plane_id: planeId
                },
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    //console.log(obj.data);
                    if (obj.status == 'success') {
                        $('.discPlaneId').val(planeId);
                        $('.dispId').val('');
                        $('#discrepancyModel .modal-body').html(obj.data);
                        $('#discrepancyModel').modal('show');
                        $("#discrepancyFrm :input").prop("disabled", false);
                    } else {
                        alert('No data exists.');
                    }
                }
            });
        });

        //Save Discrepancy in DB
        //https://stackoverflow.com/questions/64716679/js-error-element-is-not-attached-to-a-document
        $(document).on('click', '#saveDiscrepBtn', function(e) {
            e.stopPropagation();
            e.preventDefault();

            if ($('#discrepancyFrm').valid()) {
                if ($('input#discrepCorrectId').is(':checked')) {

                    $('#discrepancyModel .signature_data').val("");
                    //console.log('aaa');
                    html2canvas($("#sign-pad")[0]).then((canvas) => {
                        //console.log('bbb');
                        var img_data = canvas.toDataURL('image/png');
                        var img_data = img_data.replace(/^data:image\/(png|jpg);base64,/, "");
                        $('#discrepancyModel .signature_data').val(img_data);

                        var form = document.getElementById('discrepancyFrm');
                        var formData = new FormData(form);
                        saveDataInDB(formData);
                    });
                } else {
                    var form = document.getElementById('discrepancyFrm');
                    var formData = new FormData(form);
                    saveDataInDB(formData);
                }
            }
        });

        function saveDataInDB(formData) {
            $.ajax({
                url: "<?php echo $this->Url->build(['controller' => 'AircraftDiscrepancies', 'action' => 'add']); ?>",
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var obj = JSON.parse(response);

                    if (obj.status == 'success') {
                        $('#discrepancyModel .discrepMsg').html('<span style="color:green;">' + obj.message + '</span>');
                    } else {
                        $('#discrepancyModel .discrepMsg').html('<span style="color:red;">' + obj.message + '</span>');
                    }

                    setTimeout(function() {
                        reloadPage();
                    }, 1000);
                },
                error: function() {
                    setTimeout(function() {
                        reloadPage();
                    }, 1000);
                },
                complete: function() {
                    setTimeout(function() {
                        reloadPage();
                    }, 1000);
                }
            });
        }

        //Validation on group form
        $("#discrepancyFrm").validate({
            ignore: "input[type='text']:hidden",
            validateHiddenInputs: false,
            rules: {
                'discrepancy_date': {
                    required: true
                },
                'discrepancy': {
                    required: true
                },
                'discovered_by': {
                    required: true
                },
                'discovered_cert': {
                    required: true
                }
            },
            messages: {
                'discrepancy_date': {
                    required: "Please select discrepancy date."
                },
                'discrepancy': {
                    required: "Please enter discrepancy."
                },
                'discovered_by': {
                    required: "Please enter discovered by name."
                },
                'discovered_cert': {
                    required: "Please enter cert number."
                }
            }
        });

        //Update discrepancy
        $(document).on("click", ".updateDiscrepancy", function() {
            var planeId = $(this).data('plane_id');
            var dispId = $(this).data('id');

            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller' => 'AircraftDiscrepancies', 'action' => 'updateDiscrepancy']); ?>",
                data: {
                    plane_id: planeId,
                    disp_id: dispId
                },
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status == 'success') {
                        $('.discPlaneId').val(planeId);
                        $('.dispId').val(dispId);
                        $('#discrepancyModel .modal-body').html(obj.data);
                        $('#discrepancyModel').modal('show');
                        $('#discrepancyModel .discrepMsg').html('');

                        if (obj.correction == 1) {
                            $("#discrepancyFrm :input").prop("disabled", true);
                            $("#discrepancyFrm .closeCls").prop("disabled", false);
                        }

                        //Display selected value
                        $(".melCategory").selectpicker("val", obj.category);
                        $(".dispHourCls").selectpicker("val", obj.hour);
                        $(".dispMinutCls").selectpicker("val", obj.minut);
                    } else {
                        alert('No data exists.');
                    }
                }
            });
        });

        //Initiate select picker
        $(document).on('show.bs.modal', '.modal', function() {
            //Creating select picker
            $(".melCategory").selectpicker();
            $(".dispHourCls").selectpicker();
            $(".dispMinutCls").selectpicker();
            $(".crewListCls").selectpicker();

            /*
            jQuery autocomplete
            $( ".crewListCls" ).autocomplete({
              source: availableTags
            });*/

            $('#discrepancy-date').datetimepicker({
                format: 'MM/DD/YYYY',
                useCurrent: false
            });

            $('#mel-repair-by').datetimepicker({
                format: 'MM/DD/YYYY',
                useCurrent: false
            });

            $('#corrected-date').datetimepicker({
                format: 'MM/DD/YYYY',
                useCurrent: false,
                widgetPositioning: {
                    horizontal: 'right',
                    vertical: 'top'
                }
            });

            //Hide/Show popup fields
            $("#discrepMelId").click(function() {
                if ($('input#discrepMelId').is(':checked')) {
                    $('.discrepancyMelUpCls').show();
                } else {
                    $('.discrepancyMelUpCls').hide();
                }
            });

            //Hide/Show popup fields
            $("#discrepCorrectId").click(function() {
                if ($('input#discrepCorrectId').is(':checked')) {
                    $('.discrepancyUpdateCls').show();
                } else {
                    $('.discrepancyUpdateCls').hide();
                }
            });

            $('#signArea').signaturePad({
                drawOnly: true,
                lineTop: 90
            });
        });

        $('#showClosedDicp').click(function() {
            var planeId = $(this).data('plane_id');
            if ($('input#showClosedDicp').is(':checked')) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->Url->build(['controller' => 'AircraftDiscrepancies', 'action' => 'allDiscrepancy']); ?>",
                    data: {
                        plane_id: planeId
                    },
                    async: true,
                    beforeSend: function() {
                        $('.loader').show();
                    },
                    success: function(response) {
                        var obj = JSON.parse(response);
                        //console.log(obj.data);
                        if (obj.status == 'success') {
                            setTimeout(function() {
                                $('.loader').hide();
                                $('#closedDiscreancyId').html(obj.data);
                            }, 1000);

                        } else {

                            setTimeout(function() {
                                $('.loader').hide();
                                $('#closedDiscreancyId').html(obj.data);
                            }, 1000);
                        }
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->Url->build(['controller' => 'AircraftDiscrepancies', 'action' => 'openDiscrepancy']); ?>",
                    data: {
                        plane_id: planeId
                    },
                    async: true,
                    beforeSend: function() {
                        $('.loader').show();
                    },
                    success: function(response) {
                        var obj = JSON.parse(response);
                        //console.log(obj.data);
                        if (obj.status == 'success') {
                            setTimeout(function() {
                                $('.loader').hide();
                                $('#closedDiscreancyId').html(obj.data);
                            }, 1000);

                        } else {
                            setTimeout(function() {
                                $('.loader').hide();
                                $('#closedDiscreancyId').html(obj.data);
                            }, 1000);
                        }
                    }
                });
            }
        });

        //Delete discrepancy
        $(document).on('click', '.deleteBtnCls', function() {
            var planeId = $(this).data('plane_id');
            var dispId = $(this).data('id');

            if (confirm('Are you sure you want to delete this?')) {
                $.ajax({
                    url: "<?php echo $this->Url->build(['controller' => 'AircraftDiscrepancies', 'action' => 'deleteDiscrepancy']); ?>",
                    type: 'post',
                    data: {
                        plane_id: planeId,
                        disp_id: dispId
                    },
                    success: function(response) {
                        var obj = JSON.parse(response);
                        if (obj.status == 'success') {

                            setTimeout(function() {
                                location.reload(true);
                            }, 1000);
                        }
                    }
                });
            }
        });

        //Old method not used
        function createSignatureImg() {
            html2canvas([document.getElementById('sign-pad')], {
                onrendered: function(canvas) {
                    var canvas_img_data = canvas.toDataURL('image/png');
                    var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
                    //ajax call to save image inside folder
                    $.ajax({
                        url: 'save_sign.php',
                        data: {
                            img_data: img_data
                        },
                        type: 'post',
                        dataType: 'json',
                        success: function(response) {
                            //window.location.reload();
                            console.log(response);
                        }
                    });
                }
            });
        }

        function reloadPage() {
            setTimeout(function() {
                $('#discrepancyModel').modal('hide');
                location.reload(true);
            }, 1000);
        }
    });
</script>

