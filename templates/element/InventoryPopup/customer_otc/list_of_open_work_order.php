<div id="listOfOpenWorkOrdersModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 70%;">
        <?php
        //echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomersNotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $search_by == 'open_work_order' ? 'List of Open Work Orders' : 'List of All Work Orders'; ?></h4>
            </div>
            <div class="modal-body larger-modal-body">
                <div class="col-md-12 col-sm-12 col-xs-12 pd0">
                    <div class="col-md-6 col-sm-6 col-xs-6"><?php echo $search_by == 'open_work_order' ? 'List of Open Work Orders' : 'List of All Work Orders'; ?></div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <?php
                        if($search_by == 'open_work_order'){
                        ?>
                        <label class="control-label col-md-4 pd0" for="plane_id" style="text-align:right;">Limit to Depart:</label>
                        <div class="col-md-8">
                            <?php
                            $aircraftWOCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);

                            echo $this->Form->control('load_wo_limit_to_department', array('options' => $aircraftWOCategory, 'empty' => '(View All)', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'load_wo_limit_to_department'));
                            ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="th-sm">Work Order</th>
                                <th class="th-sm">Reg. Number</th>
                                <th class="th-sm">A/C Info</th>
                                <th class="th-sm">Primary Customer</th>
                                <th class="th-sm">Lead</th>
                                <th class="th-sm">Sales</th>
                                <th class="th-sm">Date Created</th>
                                <th class="th-sm">Due Date</th>
                                <th class="th-sm">Last Worked</th>
                                <th class="th-sm">Items</th>
                                <th class="th-sm">Status</th>
                            </tr>
                        </thead>
                        <tbody id="list-of-open-work-order-block">
                            <?php echo $this->InventoryAircraftWorkOrder->getListOfOpenWorkOrderHTML($aircrafOpenWorkOrders); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var filterOpenWODepartsURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'filterOpenWODeparts']); ?>";
</script>