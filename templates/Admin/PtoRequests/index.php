<style type="text/css">
    #datatable_filter {
        display: none;
    }

</style>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading">PTO Requests</h2>
            <div class="float-right mb-5">
                <button type="button" class="btn btn-default pto_request_create_btn">Create</button>
            </div>
        </div>

        <div class="page-content mt-35">
            <div class="tableScroll">
                <table id="datatable" class=" dataTable table table-striped table-bordered table-responsive" width="100%">
                    <thead>
                        <tr>
                            <th class="text-nowrap" style="vertical-align: top;" scope="col">#</th>
                            <th class="text-nowrap" style="vertical-align: top;" scope="col"><?php echo __('Employee Name'); ?></th>
                            <th class="text-nowrap" style="vertical-align: top;" scope="col"><?php echo __('Date'); ?></th>
                            <th class="text-nowrap" style="vertical-align: top;" scope="col"><?php echo __('Previous Balance'); ?></th>
                            <th class="text-nowrap" style="vertical-align: top;" scope="col"><?php echo __('Hours Used/Gained'); ?></th>
                            <th class="text-nowrap" style="vertical-align: top;" scope="col"><?php echo __('New Balance'); ?></th>
                            <th class="text-nowrap" style="vertical-align: top;" scope="col"><?php echo __('PTO Request Status'); ?></th>
                            <th class="text-nowrap white-space-nowrap" style="vertical-align: top;" scope="col" class="actions"><?php echo __('Actions'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="porequestpopup"></div>
<div id="dashboardpopup"></div>
<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<?php
echo $this->Html->script('user_pto_requests');
?>
<script type="text/javascript">
    var savePTORequestsURL = "<?php echo $this->Url->build(['controller' => 'PtoRequests', 'action' => 'savePTORequests',]); ?>";
    var getPTORequestDetURL = "<?php echo $this->Url->build(['controller' => 'PtoRequests', 'action' => 'getPTORequestDet',]); ?>";
    var createPTORequestPopupURL = "<?php echo $this->Url->build(['controller' => 'PtoRequests', 'action' => 'createPTORequestPopup']); ?>";

    $(document).ready(function() {
        var dataTable = $('#datatable').DataTable({
            //"searching": false,
            "processing": true,
            "serverSide": true,
            "lengthMenu": [
                [<?php echo PAGINATION_LIMIT; ?>, 50, 100, -1],
                [<?php echo PAGINATION_LIMIT; ?>, 50, 100, "All"]
            ],
            "lengthChange": false,
            "order": [0, "desc"],
            "aoColumnDefs": [{
                bSortable: false,
                aTargets: [7]
            }],
            "ajax": {
                url: "<?php echo $this->Url->build(['controller' => 'PtoRequests', 'action' => 'ajaxPTORequestSearch']); ?>",
                accepts: 'application/json',
                type: "post",
                error: function() {
                    $(".employees-grid-error").html("");
                    $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employees-grid_processing").css("display", "none");
                }
            }
        });
    });
</script>
