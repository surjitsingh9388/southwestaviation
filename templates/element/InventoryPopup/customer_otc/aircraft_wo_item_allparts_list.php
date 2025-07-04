<div id="aircraftWOItemAllPartsListModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 65%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>List All Parts</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6 pd0">
                            <button type="button" class="btn btn-default float-left">Print</button>
                        </div>
                        <div class="col-md-6 pd0">
                            <button type="button" class="btn btn-default float-right" id="refresh-work-order-part-lists">Refresh</button>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Part Number</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Serial Number</th>
                                    <th scope="col">Need</th>
                                    <th scope="col">Used</th>
                                    <th scope="col">Retail</th>
                                    <th scope="col">P/O Num.</th>
                                    <th scope="col">Price Each</th>
                                </tr>
                            </thead>
                            <tbody id="aircraft-work-order-parts-list">
                                <?php
                                $woitempartshtml = $this->InventoryAircraftWorkOrder->getWorkOrderAllPartsListHTML($workorderpartslist);
                                echo $woitempartshtml;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>
<script>
    var refreshAircraftWOAllPartsListURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'refreshAircraftWOAllPartsList']); ?>";
</script>