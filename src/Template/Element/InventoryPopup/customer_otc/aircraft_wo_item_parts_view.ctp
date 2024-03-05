<div id="woItemPartsViewModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 65%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span><?php echo 'W/O: '.$aircraftwoitemparts['work_order']['work_order_no'].' - Parts For Item #'.$aircraftwoitemparts['wo_items']['wo_item_position']; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default wo-item-part-prev-btn" data-val="first"><<</button>
                            <button type="button" class="btn btn-default wo-item-part-prev-btn" data-val="prev"><</button>
                            <button type="button" class="btn btn-default wo-item-part-next-btn" data-val="next">></button>
                            <button type="button" class="btn btn-default wo-item-part-next-btn" data-val="last">>></button>
                            <button type="button" class="btn btn-default wo-item-add-part-btn">New Part</button>
                            <button type="button" class="btn btn-default ">Go to MPart</button>
                            <button type="button" class="btn btn-default ">Deduct from Stock</button>
                            <button type="button" class="btn btn-default wo-item-part-requisition-btn">Requistions</button>
                            <button type="button" class="btn btn-default wo-item-part-notes-btn">Notes</button>
                            <button type="button" class="btn btn-default wo-item-part-delete-btn">Delete Part</button>
                            <button type="button" class="btn btn-default ">Preview</button>
                            <button type="button" class="btn btn-default ">Print</button>
                        </div>
                    </div>
                </div>
                
                <div class="row wo-item-part-view-block">
                    <?php echo $this->element('Inventory/customer_otc/aircraft_wo_item_parts_view_block'); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" disabled>Go to Core</button>
                <button type="button" class="btn btn-default wo-item-parts-to-pull">Parts to Pull</button>
                <button type="button" class="btn btn-default wo-item-make-all-parts-taxable">Make All Parts Taxable</button>
            </div>
        </div>
    </div>
</div>
<script>
    var getWOItemPartsRequisitionURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getWOItemPartsRequisition']); ?>";
    var deleteAircraftWOAllPartsURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteAircraftWOAllParts']); ?>";
    var woItemMarkAllPartsTaxableURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'woItemMarkAllPartsTaxable']); ?>";
    var getAircraftWOItemNextPrevPartURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getAircraftWOItemNextPrevPart']); ?>";
</script>