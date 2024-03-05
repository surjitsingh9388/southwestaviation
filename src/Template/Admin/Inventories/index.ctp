<?php 
$sessionUser = $this->request->session()->read('Auth.User'); 
use Cake\Routing\Router;
?>

<div class="content sliding">
    <div class="outerWrapper">
        <h2 class="heading border-btm">Inventory Overview</h2>
        <div class="boxWrapper">
            <div class="invbox invbox1 past-due-widget">
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'expiringinventory', "?" => ['from'=>'exprd']]); ?>">
                    <div id="pastDueId" class="title invtilebody"><?php echo $expiredItems; ?></div>
                    <div class="invlbltxt tile-label past-due-label">Expired Items</div>
                </a>
            </div>

            <div class="invbox invbox2 tolerance-widget" >
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'expiringinventory', "?" => ['from'=>'expr']]); ?>">
                    <div id="toleranceDueId" class="title invtilebody"><?php echo $expiringItems; ?></div>
                    <div class="invlbltxt tile-label tolerance-label">Expiring Items</div>
                </a>
            </div>

            <div class="invbox invbox3 coming-due-widget">
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'belowthresholdinventory']); ?>">
                    <div id="comingDueId" class="title invtilebody"><?php echo $lowStockItems; ?></div>
                    <div class="invlbltxt tile-label coming-due-label">Low Stock Items</div>
                </a>
            </div>

            <div class="invbox invbox3 alert-due-widget">
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completeinventory', "?" => ['out_for_repair'=>'7']]); ?>">
                    <div id="alertDueId" class="title invtilebody"><?php echo $itemOutForRepaires; ?></div>
                    <div class="invlbltxt tile-label alert-due-label">Items Out For Repair</div>
                </a>
            </div>

            <div class="invbox invbox2 coming-due-widget">
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completeinventory', "?" => ['out_for_repair'=>'12']]); ?>">
                    <div id="comingDueId" class="title invtilebody"><?php echo $quarantinedItems; ?></div>
                    <div class="invlbltxt tile-label coming-due-label">Quarantined Items</div>
                </a>
            </div>

            <div class="invbox invbox4 alert-due-widget">
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'index', "?" => ['open_po'=>'1']]); ?>">
                <div id="alertDueId" class="title invtilebody"><?php echo $openPurchaseOrders; ?></div>
                <div class="invlbltxt tile-label alert-due-label">Open Purchase Orders</div>
                </a>
            </div>

            <div class="invbox invbox4 coming-due-widget">
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'index', "?" => ['open_exchange'=>'2']]); ?>">
                <div id="comingDueId" class="title invtilebody"><?php echo $openExchangeOrders; ?></div>
                <div class="invlbltxt tile-label coming-due-label">Open Exchange Orders</div>
                </a>
            </div>

            <div class="invbox invbox5 alert-due-widget">
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'index', "?" => ['open_ro'=>'1']]); ?>">
                <div id="alertDueId" class="title invtilebody"><?php echo $openRepairOrders; ?></div>
                <div class="invlbltxt tile-label alert-due-label">Open Repair Orders</div>
                </a>
            </div>

            <div class="invbox invbox5 alert-due-widget">
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'index', "?" => ['open_request'=>'1']]); ?>">
                <div id="alertDueId" class="title invtilebody"><?php echo $openRequests; ?></div>
                <div class="invlbltxt tile-label alert-due-label">Open Requests</div>
                </a>
            </div>

            <div class="invbox invbox1 alert-due-widget">
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'index', "?" => ['past_due'=>'1']]); ?>">
                <div id="alertDueId" class="title invtilebody"><?php echo $pastDueUrgntRequests; ?></div>
                <div class="invlbltxt tile-label alert-due-label">Past Due & Urgent Requests</div>
                </a>
            </div>
        </div>

        <div class="page-content mt-35">
            <h2 class="heading">Shipment Tracking</h2>
            <div>
                <div class="table-responsive">
                    <table class="table mb-0" id="shipmenttracking">
                        <thead>
                            <tr>
                                <th width="15%">PO Number</th>
                                <th width="13%">Vendor</th>
                                <th width="13%">Part Number</th>
                                <th width="5%">Qty.</th>
                                <th width="10%">Ship Via</th>
                                <th width="13%">Tracking Number</th>
                                <th width="2%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($inventorypoitems->count() > 0){
                                $shipviaarr = unserialize(SHIP_VIA);
                                $shipViaTrackURL = unserialize(SHIP_VIA_TRACKING_URL);
                            foreach($inventorypoitems as $invpo){
                            ?>
                            <tr>
                                <td class="trcollapse" data-val="<?php echo $invpo['id']; ?>"><?php echo $invpo['invpo']['po_number']; ?></td>
                                <td class="trcollapse" data-val="<?php echo $invpo['id']; ?>"><?php echo isset($invpo['vendor']['name']) ? $invpo['vendor']['name'] : '-'; ?></td>
                                <td class="trcollapse" data-val="<?php echo $invpo['id']; ?>"><?php echo $invpo['invitms']['part_number']; ?></td>
                                <td class="trcollapse" data-val="<?php echo $invpo['id']; ?>"><?php echo $invpo['qty']; ?></td>
                                <td class="trcollapse" data-val="<?php echo $invpo['id']; ?>"><?php echo !empty($invpo['invpo']['ship_via']) ? $shipviaarr[$invpo['invpo']['ship_via']] : '-'; ?></td>
                                <td class="trcollapse" data-val="<?php echo $invpo['id']; ?>"><?php echo $invpo['tracking_number']; ?></td>
                                <td>
                                    <?php
                                    if(!empty($invpo['invpo']['ship_via']) && !empty($invpo['tracking_number'])){
                                    ?>
                                    <a class="btn btn-primary btn-xs pull-right" href="<?php echo $shipViaTrackURL[$invpo['invpo']['ship_via']].$invpo['tracking_number']; ?>" target="_blank">Track</a>
                                    <?php
                                    } 
                                    ?>
                                </td>
                            </tr>
                            <?php }}else{ ?>
                            <tr>
                                <td colspan="7">No purchase order line items with tracking numbers found.</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
echo $this->Html->css('inventory'); 
?>

<script>
    var purchseOrderDetURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'detail']); ?>";
    $(document).on('click', '.trcollapse', function(e){
        window.location.href = purchseOrderDetURL+'/'+$(this).attr('data-val');
    })
</script>