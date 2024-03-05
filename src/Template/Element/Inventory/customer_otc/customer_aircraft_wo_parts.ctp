<section class="top-form-section">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <button type="button" class="btn btn-default wo-item-add-part-btn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Add Part</button>
                <button type="button" class="btn btn-default wo-item-part-view-btn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>View Parts for Item</button>
                <button type="button" class="btn btn-default wo-item-part-listall-btn">List All Parts</button>
                <button type="button" class="btn btn-default wo-item-part-requisition-btn">Requisitions</button>
                <button type="button" class="btn btn-default wo-send-new-message" data-val="new-msg">Send Request Msg</button>
                <button type="button" class="btn btn-default wopartpreviewbtn">Preview</button>
                <button type="button" class="btn btn-default wopartprintbtn">Print</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt10">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Part Number</th>
                        <th scope="col">Description</th>
                        <th scope="col">Serial Number</th>
                        <th scope="col">Need</th>
                        <th scope="col">Used</th>
                        <th scope="col">P/O Num.</th>
                        <th scope="col">Price Each</th>
                    </tr>
                </thead>
                <tbody id="aircraft-woitem-partlist">
                    <?php
                    $woitempartshtml = $this->InventoryAircraftWorkOrder->getWorkOrderItemPartListHTML($aircraftwoitemparts);
                    echo $woitempartshtml;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script>
    var getInventoryItemByIdURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getInvItemsWithInvListByItemNumber']); ?>";
    var saveAircraftWOItemPartsURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftWOItemParts']); ?>";
</script>