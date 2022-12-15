<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Plane[]|\Cake\Collection\CollectionInterface $planes
 */
?>
<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>
<script type="text/javascript">
    function action(e){
        var msg = $(e).attr("data-msg");
        if(confirm(msg)){
            var url = $(e).attr("data-url");
            var plane_id = $(e).attr("data-plane_id");
            $("#actionForm").attr("action",url);
            $("#actionForm").append("<input type='hidden' name='id' value='"+plane_id+"'/>");
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
                 aTargets: [ 0,6 ]
              }
            ],
            "ajax":{
                url:"<?php echo $this->Url->build(['controller'=>'Planes', 'action'=>'ajaxManagePlanesSearch']); ?>",
                accepts: 'application/json', 
                type: "post",
                error: function(){
                    $(".employees-grid-error").html("");
                    $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employees-grid_processing").css("display","none");
                }
            }
        } );
    } );
</script>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Aircraft List</h2>
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1) {
                echo $this->Html->link("<i class='fa fa-plus'></i> Add Aircraft", array('action' => 'add'), array('class' => 'btn btn-default', 'escape' => false));
            }
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="tableScroll">
                <table id="datatable" class="table dataTable" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo __('Aircraft'); ?></th>
                            <th><?php echo __('Model'); ?></th>
                            <th><?php echo __('Serial'); ?></th>
                            <th><?php echo __('Airworthiness Date'); ?></th>
                            <th><?php echo __('Operator Name'); ?></th>
                            <th class="actions"><?php echo __('Actions'); ?></th>
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