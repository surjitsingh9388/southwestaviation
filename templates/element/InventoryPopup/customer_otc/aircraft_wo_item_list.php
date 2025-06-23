<div id="aircraftWOItemListModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">All Labor Items in W/O: <?php echo $work_order_no; ?></h4>
            </div>
            <div class="modal-body" style="height: auto;">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Discrepancy</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Item Status</th>
                                    <th scope="col">Lead Tech</th>
                                </tr>
                            </thead>
                            <tbody class="wo-osr-list">
                                <?php
                                $aircraftWOCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);
                                $itemstatus = unserialize(AIRCRAFT_WORKORDER_ITEMSTATUS);

                                foreach($aircraftwoitemlist as $woitem){
                                ?>
                                <tr class="selectaircraftwoitems" data-val="<?php echo $woitem['wo_item_position']; ?>">
                                    <td><?php echo $woitem['wo_item_position']; ?></td>
                                    <td><?php echo $woitem['wo_discrepancy']; ?></td>
                                    <td><?php echo isset($woitem['wo_item_overview']['wo_category']) ? $aircraftWOCategory[$woitem['wo_item_overview']['wo_category']] : ''; ?></td>
                                    <td><?php echo $itemstatus[$woitem['wo_item_status']]; ?></td>
                                    <td><?php echo !empty($woitem['wo_item_services']['is_lead_tech_on_item']) ? $woitem['wo_item_services']['is_lead_tech_on_item'] : ''; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>