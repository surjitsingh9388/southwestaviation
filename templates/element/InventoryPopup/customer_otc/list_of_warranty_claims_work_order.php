<div id="listOfWarrantyClaimsWOModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 70%;">
        <?php
        //echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomersNotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $search_by == 'open_warranty_claim_work_order' ? 'List of Open Warranty Claims for Work Orders' : 'List of All Warranty Claims for Work Orders'; ?></h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <?php echo $search_by == 'open_warranty_claim_work_order' ? 'List of Open Warranty Claims for Work Orders' : 'List of All Warranty Claims for Work Orders'; ?>
                </div>
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="th-sm">Claim Number</th>
                                <th class="th-sm">Work Order</th>
                                <th class="th-sm">Item</th>
                                <th class="th-sm">Discrepancy</th>
                                <th class="th-sm">Reg. Number</th>
                                <th class="th-sm">Status</th>
                                <th class="th-sm">Date Created</th>
                            </tr>
                        </thead>
                        <tbody id="list-of-open-work-order-block">
                            <?php
                            $aircraftWOStatus = unserialize(AIRCRAFT_WORKORDER_STATUS);
                            $listofopenwohtml = '';
                            foreach($aircrafOpenWorkOrders as $workorder){
                                $listofopenwohtml .= '<tr class="loadaircraftworkordertr" data-val="'.$workorder['work_order_no'].'">';
                                $listofopenwohtml .= '<td>'.$workorder['item_overviews']['warranty_claim_no'].'</td>';
                                $listofopenwohtml .= '<td>'.$workorder['work_order_no'].'</td>';
                                $listofopenwohtml .= '<td>'.$workorder['itemcount'].'</td>';
                                $listofopenwohtml .= '<td>'.$workorder['wo_items']['wo_discrepancy'].'</td>';
                                $listofopenwohtml .= '<td>'.$workorder['otc_aircrafts']['aircraft_registration_number'].'</td>';
                                $listofopenwohtml .= '<td>'.$aircraftWOStatus[$workorder['wo_status']].'</td>';
                                $listofopenwohtml .= '<td>'.date('Y-m-d', strtotime($workorder['created_at'])).'</td>';
                                $listofopenwohtml .= '</tr>';
                            }
                            echo $listofopenwohtml;
                            ?>
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