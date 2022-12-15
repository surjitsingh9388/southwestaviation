<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable( {
            "processing": true,
            "lengthMenu": [[<?php echo PAGINATION_LIMIT;?>, 50, 100, -1], [<?php echo PAGINATION_LIMIT;?>, 50, 100, "All"]],
            "lengthChange": false,
            "order": [],
            "aoColumnDefs": [
              {
                 bSortable: false,
                 aTargets: [ 0,5 ]
              }
            ]
        } );
    } );

</script>
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>Timezones</h3>
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
                    <h2>Timezones List</h2>
                    <?php
                    if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1){
                    echo $this->Html->link("<i class='fa fa-plus'></i> Add Timezone", array('action' => 'add'), array('class' => 'btn btn-primary pull-right', 'escape' => false));
                    }
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table id="datatable" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%"><?php echo __('Timezones'); ?></th>
                                <th width="35%"><?php echo __('Description'); ?></th>
                                <th width="8%"><?php echo __('Code'); ?></th>
                                <th width="12%"><?php echo __('Serial No.'); ?></th>
                                <th width="40%" pull-right><?php echo __('Actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($reports) > 0) {
                                $i =1;
                                foreach ($reports as $report) { 
                            ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo !empty($report['Timezones__timezone'])?h($report['Timezones__timezone']):''; ?></td>
                                        <td><?php echo !empty($report['Timezones__description'])? $report['Timezones__description']:''; ?></td>
                                        <td><?php echo !empty($report['Timezones__code'])?h($report['Timezones__code']):''; ?></td>
                                        <td><?php echo $report['Timezones__serial_no']; ?></td>
                                        <td>
                                            <?php
                                            if (!empty($report['Timezones__id'])) {
                                                //if(!empty($actionItems)){
                                                    if((!empty($actionItems) && $actionItems['action']['action_view'] == 1) || $sessionUser['id'] == 1){
                                                        echo $this->Html->link("<i class='fa fa-folder'></i> View",
                                                        array('controller' => 'Timezones', 'action' => 'view', $report['Timezones__id']), 
                                                        array('class' => 'btn btn-xs btn-primary', 'escape' => false));
                                                    }
                                                    if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1){
                                                        echo $this->Html->link("<i class='fa fa-pencil'></i> Edit",
                                                                array('controller' => 'Timezones', 'action' => 'edit', $report['Timezones__id']), 
                                                                array('class' => 'btn btn-info btn-xs', 'escape' => false));
                                                    }
                                                    if((!empty($actionItems) && $actionItems['action']['action_delete'] == 1) || $sessionUser['id'] == 1){
                                                        echo $this->Form->postLink("<i class='fa fa-trash-o'></i> Delete", ['action' => 'delete', $report['Timezones__id']], ['confirm' => __('Are you sure you want to Delete this Timezone?', $report['Timezones__id']), 'class' => 'btn btn-danger btn-xs', 'escape' => false ]);
                                                    }
                                                //}
                                            }
                                            ?>
                                        </td>
                                    </tr>
                            <?php  $i++; 
                                } 
                            } else { ?>
                                    <tr><td colspan="6">No Records Found.</td></tr>
                            <?php } ?>        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

