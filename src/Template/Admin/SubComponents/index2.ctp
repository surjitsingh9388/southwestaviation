<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>
<script type="text/javascript">
function action(e){
    var msg = $(e).attr("data-msg");
    if(confirm(msg)){
        var url = $(e).attr("data-url");
        var sub_component_id = $(e).attr("data-sub_component_id");
        $("#actionForm").attr("action",url);
        $("#actionForm").append("<input type='hidden' name='id' value='"+sub_component_id+"'/>");
        $("#actionForm").submit();
     }
     return false;  
}
$(document).ready(function() {
    var dataTable = $('#datatable').DataTable({
        
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
            url:"<?php echo $this->Url->build(['controller'=>'SubComponents', 'action'=>'ajaxManageSubComp2Search']); ?>",
            accepts: 'application/json', 
            type: "post",
            error: function() {
                
            }
        }
    });
});
</script>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Sub Component 1-1 List</h2>
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1){
            echo $this->Html->link("<i class='fa fa-plus'></i> Add Sub Component 1-1", array('action' => 'add2'), array('class' => 'btn btn-default', 'escape' => false));
            }
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="tableScroll">
                <table id="datatable" class="table dataTable" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Aircraft</th>
                            <th>Component</th>
                            <th>Sub Component</th>
                            <th>Sub Component 1-1</th>
                            <th class="actions" width="20%">Actions</th>
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