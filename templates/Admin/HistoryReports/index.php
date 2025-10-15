<?php

use Cake\Routing\Router;
?>
<style>
    .dataTables_filter {
        display: none;
    }

    td .history-detail-block {
        max-height: 100px;
    }

    @media (min-width: 768px) {
        .form-horizontal .control-label {

            text-align: left !important;
        }
    }
</style>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">History Reports</h2>
        </div>

        <div class="page-content mt-35">
            <div class="action-bar dflex" style="display:block;">
                <?php
                echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmHistoryReports'));
                ?>
                <div class="row inputWrap btn-group" style="margin-bottom: 15px;">

                    <div class="col-md-3">
                        <div class="form-group row">
                            <label class="col-sm-12 col-xs-12 col-md-12  col-lg-3 col-xs-12 control-label" for="FilterBySection" style="color: white; font-size: 15px;">Section</label>
                            <div class="col-sm-12 col-md-12    col-lg-9 col-xs-10">
                                <?php
                                $historyReports = unserialize(HISTORYREPORTS);
                                echo $this->Form->control('filterBySection', [
                                    'options' => $historyReports,
                                    'empty' => 'Select Section',
                                    'class' => 'form-control selectpicker',
                                    'data-show-subtext' => true,
                                    'data-live-search' => true,
                                    'label' => false,
                                    'id' => 'FilterBySection',
                                    'value' => 'user_histories'
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-7">
                        <div class="form-group row">
                            <label class="col-md-2 col-xs-12 control-label text-nowrap" style="color: white; font-size: 15px;">Date Range</label>
                            <div class="col-md-5  col-xs-12 mb-5">
                                <div class="input-group btn-group date datePicker" style="width: 100%;" >
                                    <?php
                                    echo $this->Form->text('date_from', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Date From',
                                        'label' => false,
                                        'id' => 'histiroy_date_from'
                                    ]);
                                    ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>


                            <div class="col-md-5 col-xs-12">
                                <div class="input-group  btn-group date datePicker" style="width: 100%;">
                                    <?php
                                    echo $this->Form->text('date_to', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Date To',
                                        'label' => false,
                                        'id' => 'histiroy_date_to'
                                    ]);
                                    ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-2 pull-right ">
                        <div class="form-group ">
                            <button type="button" class="btn btn-primary applyfilterbtn">Submit</button>
                            <button type="button" class="btn clearapplyfilter">Clear</button>
                        </div>
                    </div>
                </div>

                <?php echo $this->Form->end(); ?>
            </div>

            <div class="tableScroll table-responsive">
                <table id="datatable" class=" dataTable table table-striped table-bordered " width="100%">
                    <thead>
                        <tr>
                            <th width="1%"><?php echo __('Id'); ?></th>
                            <th width="30%"><?php echo __('Title'); ?></th>
                            <th width="15%"><?php echo __('User'); ?></th>
                            <th width="20%"><?php echo __('Created'); ?></th>
                            <th><?php echo __('Description'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<script>
    $(document).ready(function() {
        var dataTable = $('#datatable').DataTable({
            'columnDefs': [{
                    'orderable': true,
                    'targets': '_all'
                },
                {
                    "targets": [0]
                },
                {
                    'visible': false,
                    'targets': [0]
                }
            ],
            'order': [0, 'desc'],
            "searching": true,
            "processing": true,
            "serverSide": true,
            "lengthMenu": [
                [<?php echo PAGINATION_LIMIT; ?>, 50, 100, -1],
                [<?php echo PAGINATION_LIMIT; ?>, 50, 100, "All"]
            ],
            "lengthChange": false,
            "ajax": {
                url: "<?php echo $this->Url->build(['controller' => 'HistoryReports', 'action' => 'ajaxHistoryReportSearch']); ?>",
                accepts: 'application/json',
                type: "post",
                error: function() {
                    $(".employees-grid-error").html("");
                    $("#datatable").append('<tbody class="employees-grid-error"><tr><th colspan="4">No data found in the server</th></tr></tbody>');
                    $("#employees-grid_processing").css("display", "none");
                }
            }
        });
    });

    $(document).on('click', '.applyfilterbtn', function(e) {
        searchApplyFilter();
    });
    $(document).on('click', '.clearapplyfilter', function(e) {
        $("#frmHistoryReports")[0].reset();
        $('.selectpicker').selectpicker('refresh');
        searchApplyFilter();
    })

    function searchApplyFilter() {
        var formdata = $("#frmHistoryReports").serialize();
        var dataTable = $('#datatable').DataTable();
        dataTable.columns(1).search(formdata).draw();
    }

    $(document).on("click", ".toggleplusminus", function(e) {
        /*$('.toggleplusminus').children('i.fa').removeClass('fa-minus');
        $('.toggleplusminus').children('i.fa').addClass('fa-plus');
        $('.toggleplusminus').parent("td").find('pre.history-detail-block').css('display', 'none');*/

        if ($(this).children('i.fa-plus').length > 0) {
            $(this).children('i.fa').removeClass('fa-plus');
            $(this).children('i.fa').addClass('fa-minus');

            $(this).parent("td").find('pre.history-detail-block').css('display', 'block');
        } else {
            $(this).children('i.fa').removeClass('fa-minus');
            $(this).children('i.fa').addClass('fa-plus');

            $(this).parent("td").find('pre.history-detail-block').css('display', 'none');
        }
    });
</script>
