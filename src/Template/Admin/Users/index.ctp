<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<?php
$sessionUser = $this->request->session()->read('Auth.User');
?>
<script type="text/javascript">
function action(e){
    var msg = $(e).attr("data-msg");
    if(confirm(msg)) {
        var url = $(e).attr("data-url");
        var user_id = $(e).attr("data-user_id");
        $("#actionForm").attr("action",url);
        $("#actionForm").append("<input type='hidden' name='id' value='"+user_id+"'/>");
        $("#actionForm").submit();
    }
    return false;  
}
$(document).ready(function() {
    var dataTable = $('#datatable').DataTable( {
        // "responsive": true,
        "fixedHeader": true,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[<?php echo PAGINATION_LIMIT;?>, 50, 100, -1], [<?php echo PAGINATION_LIMIT;?>, 50, 100, "All"]],
        "lengthChange": false,
        "order": [[ 0, "desc" ]],
        "aoColumnDefs": [
          {
             bSortable: false,
             aTargets: [ 6 ]
          }
        ],
        "ajax":{
            url:"<?php echo $this->Url->build(['controller'=>'Users', 'action'=>'ajaxManageUsersSearch']); ?>",
            accepts: 'application/json', 
            type: "post",
            error: function() {
                
            }
        }
    } );
    $('[data-toggle="tooltip"]').tooltip();
} );
</script>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Users List</h2>
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1){
            echo $this->Html->link("<i class='fa fa-plus'></i> Add User", array('action' => 'add'), array('class' => 'btn btn-default', 'escape' => false));
            }
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="tableScroll">
                <table id="datatable" class="table dataTable" width="100%">                
                    <thead>
                        <tr>
                            <th><?php echo __('User ID'); ?></th>
                            <th><?php echo __('Full Name'); ?></th>
                            <th><?php echo __('Email'); ?></th>
                            <th><?php echo __('Phone'); ?></th>
                            <th><?php echo __('Role'); ?></th>
                            <th><?php echo __('Status'); ?></th>
                            <th><?php echo __('Actions'); ?></th>
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