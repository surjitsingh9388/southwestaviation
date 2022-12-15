<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>
<script type="text/javascript">
function action(e){
    var msg = $(e).attr("data-msg");
    if(confirm(msg)){
        var url = $(e).attr("data-url");
        var disposition_id = $(e).attr("data-disposition_id");
        $("#actionForm").attr("action",url);
        $("#actionForm").append("<input type='hidden' name='id' value='"+disposition_id+"'/>");
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
                aTargets: [ 3 ]
            }
        ],
        "ajax":{
            url:"<?php echo $this->Url->build(['controller'=>'Dispositions', 'action'=>'ajaxManageDispositionsSearch']); ?>",
            accepts: 'application/json', 
            type: "post",
            error: function() {
                
            }
        }
    } );
} );
</script>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Disposition List</h2>
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1){
            echo $this->Html->link("<i class='fa fa-plus'></i> Add Disposition", array('action' => 'add'), array('class' => 'btn btn-default', 'escape' => false));
            }
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="tableScroll">
                <table id="datatable" class="table dataTable" width="100%">
                    <thead>
                        <tr>
                            <th style="vertical-align: top;" scope="col">#</th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Disposition'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Status'); ?></th>
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