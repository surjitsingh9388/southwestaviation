<?php $sessionUser = $this->request->getSession()->read('Auth');; ?>
<script type="text/javascript">
function action(e){
    var msg = $(e).attr("data-msg");
    if(confirm(msg)){
        var url = $(e).attr("data-url");
        var airframe_category_id = $(e).attr("data-airframe_category_id");
        $("#actionForm").attr("action",url);
        $("#actionForm").append("<input type='hidden' name='id' value='"+airframe_category_id+"'/>");
         $("#actionForm").submit();
     }
     return false;  
}
$(document).ready(function() {
    var dataTable = $('#datatable').DataTable( {
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[<?php echo PAGINATION_LIMIT;?>, 50, 100, -1], [<?php echo PAGINATION_LIMIT;?>, 50, 100, "All"]],
        "lengthChange": false,
        "order": [[ 1, "asc" ]],
        "aoColumnDefs": [
          {
             bSortable: false,
             aTargets: [ 4 ]
          }
        ],
        "ajax":{
            url:"<?php echo $this->Url->build(['controller'=>'AirframeCategories', 'action'=>'ajaxManageCategoriesSearch']); ?>",
            accepts: 'application/json', 
            type: "post",
            error: function(){
                $(".employees-grid-error").html("");
                $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employees-grid_processing").css("display","none");
            }
        }
    });
});
</script>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Aircraft Categories List</h2>
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1){
                echo $this->Html->link("<i class='fa fa-plus'></i> Add Category", array('action' => 'add'), array('class' => 'btn btn-default', 'escape' => false));
            }
            ?>
        </div>
                
        <div class="page-content mt-35">
            <div class="tableScroll">
                <table id="datatable" class="table dataTable" width="100%">
                    <thead>
                        <tr>
                            <th style="vertical-align: top;" scope="col">#</th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Aircraft'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Category Name'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Serial Number'); ?></th>
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