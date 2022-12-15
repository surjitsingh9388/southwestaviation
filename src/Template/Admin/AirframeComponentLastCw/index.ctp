<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>
<script type="text/javascript">
    function action(e){
        var msg = $(e).attr("data-msg");
        if(confirm(msg)){
            var url = $(e).attr("data-url");
            var airframe_component_last_cw_id = $(e).attr("data-airframe_component_last_cw_id");
            $("#actionForm").attr("action",url);
            $("#actionForm").append("<input type='hidden' name='id' value='"+airframe_component_last_cw_id+"'/>");
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
                 aTargets: [ 6 ]
              }
            ],
            "ajax":{
                url:"<?php echo $this->Url->build(['controller'=>'AirframeComponentLastCw', 'action'=>'ajaxManageCompLastCWSearch']); ?>",
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
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Airframe Component Last Complied With</h3>
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
                    <h2>Airframe Component Last C/W List</h2>
                    <?php
                    if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1){
                        echo $this->Html->link("<i class='fa fa-plus'></i> Add Component Last C/W", array('action' => 'add'), array('class' => 'btn btn-primary pull-right', 'escape' => false));
                    }
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="datatable" class="table table-striped table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo __('Aircraft'); ?></th>
                                <th><?php echo __('Log Book'); ?></th>
                                <th><?php echo __('Hours'); ?></th>
                                <th><?php echo __('Cycles'); ?></th>
                                <th><?php echo __('Months'); ?></th>
                                <th class="actions"><?php echo __('Actions'); ?></th>
                            </tr>
                        </thead>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>