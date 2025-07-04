<section class="top-form-section">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <button type="button" class="btn btn-default wo-item-tool-add-btn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Add Tool</button>
                <button type="button" class="btn btn-default wotoolpreviewbtn">Preview</button>
                <button type="button" class="btn btn-default wotoolprintbtn">Print</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt10">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Tool Name</th>
                        <th scope="col">Model</th>
                        <th scope="col">Serial Number</th>
                        <th scope="col">Description</th>
                    </tr>
                </thead>
                <tbody id="woitem-tool-body">
                    <?php echo $this->InventoryAircraftWorkOrder->getWorkOrderItemToolListHTML($woitemtoollists); ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script>
    var saveWorkOrderItemToolURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWorkOrderItemTool']); ?>";
</script>