<?php $sessionUser = $this->request->getSession()->read('Auth');; ?>
<script type="text/javascript">
    function action(e){
        var msg = $(e).attr("data-msg");
        if(confirm(msg)){
            var url = $(e).attr("data-url");
            var state_id = $(e).attr("data-state_id");
            $("#actionForm").attr("action",url);
            $("#actionForm").append("<input type='hidden' name='id' value='"+state_id+"'/>");
             $("#actionForm").submit();
         }
         return false;  
    }
    $(document).ready(function() {
        var dataTable = $('#datatable').DataTable( {
            /*"fixedHeader": {
                "header": true,
                "headerOffset": 50,
                "footer": true
            },*/
            "language": {
                //"search": "_INPUT_",
                "searchPlaceholder": "United States"
            },
            "processing": true,
            "serverSide": true,
            "lengthMenu": [[<?php echo PAGINATION_LIMIT;?>, 50, 100, -1], [<?php echo PAGINATION_LIMIT;?>, 50, 100, "All"]],
            "lengthChange": false,
            "order": [[ 1, "asc" ]],
            "aoColumnDefs": [
              {
                 bSortable: false,
                 aTargets: [0, 3]
              }
            ],
            "ajax":{
                //url :base_path+"employees/ajax_manage_users_search",
                url:"<?php echo $this->Url->build(['controller'=>'States', 'action'=>'ajaxManageStateSearch']); ?>",
                accepts: 'application/json', 
                type: "post",
                error: function(){
                    $(".employees-grid-error").html("");
                    $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#employees-grid_processing").css("display","none");
                }
            }
        } );
        
        //console.log(dataTable);
    } );

</script>
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>States</h3>
        </div>
        <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <!--serch box-->
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>States List</h2>
                    <?php
                    if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1){
                    echo $this->Html->link("<i class='fa fa-plus'></i> Add State", array('action' => 'add'), array('class' => 'btn btn-primary pull-right', 'escape' => false));
                    }
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="datatable" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%"><?php echo __('State Name'); ?></th>
                                <th width="20%"><?php echo __('Country Name'); ?></th>
                                <th width="16%" pull-right><?php echo __('Actions'); ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="row">
        <div class="paginator">
            <ul class="pagination">
                <?php echo $this->Paginator->first('<< ' . __('first')); ?>
                <?php echo $this->Paginator->prev('< ' . __('previous')); ?>
                <?php echo $this->Paginator->numbers(); ?>
                <?php echo $this->Paginator->next(__('next') . ' >'); ?>
                <?php echo $this->Paginator->last(__('last') . ' >>'); ?>
            </ul>
            <p><?php echo $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]); ?></p>
        </div>
    </div> -->
</div>
<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>