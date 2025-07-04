<div id="aircraftWOMoveItemModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>More Item Number</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">To move an item number, double click on the desired item number.</div>

                    <div class="col-md-12">
                        <fieldset class="scheduler-border mt10">
                            <legend class="scheduler-border wosignoffleg">List of All Items</legend>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12 wosignoffcategory">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Item</th>
                                                    <th scope="col">Category</th>
                                                    <th scope="col">Grouping</th>
                                                    <th scope="col">Labor Kit</th>
                                                    <th scope="col">Warranty</th>
                                                    <th scope="col">Discrepancy</th>
                                                    <th scope="col">Corrective Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $aircraftWOCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);
                                                $warrantywoarr = unserialize(WOITEMOVERVIEWWARRANTY);

                                                foreach($aircraftwoitems as $woitem){
                                                ?>
                                                <tr>
                                                    <td><?php echo $woitem['wo_item_position']; ?></td>
                                                    <td><?php echo (!empty($woitem['wo_item_overviews']['wo_category']) ? $aircraftWOCategory[$woitem['wo_item_overviews']['wo_category']] : ''); ?></td>
                                                    <td><?php echo $woitem['wo_item_overviews']['wo_grouping']; ?></td>
                                                    <td><?php echo $woitem['wo_item_overviews']['labor_kit_name']; ?></td>
                                                    <td><?php echo (!empty($woitem['wo_item_overviews']['warranty']) ? $warrantywoarr[$woitem['wo_item_overviews']['warranty']] : ''); ?></td>
                                                    <td><?php echo $woitem['wo_discrepancy']; ?></td>
                                                    <td><?php echo $woitem['wo_corrective_action']; ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default float-left wo_item_reorganize_number" data-dismiss="modal">Reorganize Number</button>
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>