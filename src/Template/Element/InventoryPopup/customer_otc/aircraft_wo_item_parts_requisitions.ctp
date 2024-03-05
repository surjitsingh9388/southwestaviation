<div id="woItemPartsRequisitionsModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 65%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Parts With Outstanding Quantities</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>Below is a list of all parts that are not on a Purchase Order.</p>
                        <p>Click on an Item from the list to bring up individual item information.</p>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Part Number</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">W/O Need</th>
                                    <th scope="col">P/O Qty.</th>
                                    <th scope="col">Stock Qty.</th>
                                    <th scope="col">Order Qty.</th>
                                    <th scope="col">Avail Qty.</th>
                                    <th scope="col">Avail Order</th>
                                </tr>
                            </thead>
                            <tbody id="wo-parts-requisitions-list">
                                <?php
                                $tblrow = '';
                                foreach($workorderpartslist as $row){
                                    $tblrow .= '<tr class="wopartsrequisiterow" data-val="'.$row['id'].'">';
                                    $tblrow .= '<td>'.$row['wo_items']['wo_item_position'].'</td>';
                                    $tblrow .= '<td>'.$row['part_number'].'</td>';
                                    $tblrow .= '<td>'.$row['part_description'].'</td>';
                                    $tblrow .= '<td></td>';
                                    $tblrow .= '<td></td>';
                                    $tblrow .= '<td></td>';
                                    $tblrow .= '<td></td>';
                                    $tblrow .= '<td></td>';
                                    $tblrow .= '<td></td>';
                                    $tblrow .= '</tr>';
                                }
                                echo $tblrow;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 wo-item-parts-requisitions">
                        <?php echo $this->element('/Inventory/customer_otc/aircraft_wo_part_requisition_block'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var getWOItemPartsRequisitionURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getWOItemPartsRequisition']); ?>";
</script>