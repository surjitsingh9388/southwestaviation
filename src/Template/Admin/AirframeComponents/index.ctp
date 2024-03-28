<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>

<style type="text/css">
    .compPgCls select {
        display: block !important;
        float:right;
        margin: 5px;
        width: 250px;
        height: 40px !important;
    }

    #datatable_filter {
        display:none;
    }
</style>

<script type="text/javascript">
function action(e){
    var msg = $(e).attr("data-msg");
    if(confirm(msg)){
        var url = $(e).attr("data-url");
        var airframe_components_id = $(e).attr("data-airframe_components_id");
        $("#actionForm").attr("action",url);
        $("#actionForm").append("<input type='hidden' name='id' value='"+airframe_components_id+"'/>");
         $("#actionForm").submit();
     }
     return false;  
}
$(document).ready(function() {
    var dataTable = $('#datatable').DataTable( {
        //"searching": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[<?php echo PAGINATION_LIMIT;?>, 50, 100, -1], [<?php echo PAGINATION_LIMIT;?>, 50, 100, "All"]],
        "lengthChange": false,
        "order": [[ 1, "asc" ], [ 2, "asc" ]],
        "aoColumnDefs": [
            {
                bSortable: false,
                aTargets: [ 5 ]
            }
        ],
        "ajax":{
            url:"<?php echo $this->Url->build(['controller'=>'AirframeComponents', 'action'=>'ajaxManageComponentsSearch']); ?>",
            accepts: 'application/json', 
            type: "post",
            error: function(){
                $(".employees-grid-error").html("");
                $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employees-grid_processing").css("display","none");
            }
        }
    } );

    $('#airListId').on('change', function () {
        //dataTable.columns(1).search( this.value ).draw();
        var value = $(this).val();
        console.log(value);
        //Searching
        dataTable.search(value).draw();
    } );
} );


</script>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Airframe Components List</h2>
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                echo $this->Html->link("<i class='fa fa-plus'></i> Add Component", array('action' => 'add'), array('class' => 'btn btn-default', 'escape' => false));
            }
            ?>
        </div>

        <div class="page-content mt-35">
            <div class="tableScroll compPgCls">
                <select name="plane_id" class="" data-show-subtext="true" data-live-search="false" id="airListId" style="">
                    <option value="">All</option>
                    <?php
                    foreach ($planes as $key => $value) {
                    ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php
                    }
                    ?>
                </select>
                <table id="datatable" class="table dataTable" width="100%">
                    <thead>
                        <tr>
                            <th style="vertical-align: top;" scope="col">#</th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Aircraft'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Log Book'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Position'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Serial No'); ?></th>
                            <th style="vertical-align: top;" scope="col" class="actions"><?php echo __('Actions'); ?></th>
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

