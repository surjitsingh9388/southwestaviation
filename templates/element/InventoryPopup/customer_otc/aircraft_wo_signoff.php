<div id="aircraftWOSignoffModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close close_wo_item_signoff" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Signoffs for W/O: <?php echo $wodetails->work_order_no; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">All Items</div>
                    <div class="col-md-12 wosignoffallitm">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Item Num.</th>
                                    <th scope="col">Discrepancy</th>
                                    <th scope="col">Corrective Action</th>
                                    <th scope="col">Item Status</th>
                                </tr>
                            </thead>
                            <tbody id="wo-signoff-tbody">
                                <?php
                                $itemstatus = unserialize(AIRCRAFT_WORKORDER_ITEMSTATUS);
                                $wosignoffitems = [];
                                foreach($aircraftwoitems as $key=>$woitem){
                                    if($woitem['id'] == $wo_item_id){
                                        $wosignoffitems = $woitem;
                                    }
                                ?>
                                <tr class="wo-signoff-tr <?php if($woitem['wo_item_status'] == '1' || $woitem['wo_item_status'] == '2') { ?>wosignoff-color-box2 <?php }else if($woitem['wo_item_status'] == '3') { ?> wosignoff-color-box1<?php } ?>" data-val="<?php echo $woitem['id']; ?>">
                                    <td><?php echo $woitem['wo_item_position']; ?></td>
                                    <td><?php echo $woitem['wo_discrepancy']; ?></td>
                                    <td><?php echo $woitem['wo_corrective_action']; ?></td>
                                    <td><?php echo (!empty($woitem['wo_item_status']) ? $itemstatus[$woitem['wo_item_status']] : ''); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 wo-singoff-category-block">
                        <?php echo $this->element('Inventory/customer_otc/aircraft_wo_signoff_category_block', array('wosignoffitems'=>$wosignoffitems)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var getWOItemSignoffCategoryListURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getWOItemSignoffCategoryList']); ?>";
    var saveWOItemSignoffCategoryURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOItemSignoffCategory']); ?>";
    var getWOItemSignoffCategoryDetURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getWOItemSignoffCategoryDet']); ?>";
    var clearWOItemSignoffCategoryURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'clearWOItemSignoffCategory']); ?>";
</script>