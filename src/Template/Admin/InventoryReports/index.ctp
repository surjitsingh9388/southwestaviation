<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Parts[]|\Cake\Collection\CollectionInterface $parts
 */
?>
<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>
<script src=
    "//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js">
</script>
<script type="text/javascript">
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryVendors', 'action'=>'ajaxInventoryVendorsearch']); ?>";
    var pagelimit = <?php echo PAGINATION_LIMIT;?>;
    var inventoryRequestDetailsURL = "<?php echo $this->Url->build(['controller'=>'InventoryVendors', 'action'=>'detail',]); ?>";
    
    var pdfPagTitle = 'VENDOR';
    
</script>

<?php 
echo $this->Html->script('inventory_vendors'); 
echo $this->Html->script('inventory_common'); 
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Inventory Reports</h2>
        </div>
        
        <div class="page-content mt-35">
            <div class="list-group">
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completeinventory']); ?>" class="list-group-item">
                    <h4 class="list-group-item-heading">Complete Inventory</h4>
                    <p class="list-group-item-text">Displays the full list of inventory within the system.</p>
                </a>
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'expiringinventory']); ?>" class="list-group-item">
                    <h4 class="list-group-item-heading">Expiring Inventory</h4>
                    <p class="list-group-item-text">Displays a list of inventory which has expired or is expiring within the next 30 days by default.</p>
                </a>
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'belowthresholdinventory']); ?>" class="list-group-item">
                    <h4 class="list-group-item-heading">Below Threshold</h4>
                    <p class="list-group-item-text">Displays a list of inventory which is below the specified threshold/safety stock for that item.</p>
                </a>
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completethresholdinventory']); ?>" class="list-group-item">
                    <h4 class="list-group-item-heading">Complete Threshold</h4>
                    <p class="list-group-item-text">Displays a list of all global and location based thresholds.</p>
                </a>
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'transactionhistory']); ?>" class="list-group-item">
                    <h4 class="list-group-item-heading">Transaction History</h4>
                    <p class="list-group-item-text">Displays a list of all transactions for a given filter criteria</p>
                </a>
                <!--a href="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completeinventorymonthly']); ?>" class="list-group-item">
                    <h4 class="list-group-item-heading">Monthly Complete Inventory</h4>
                    <p class="list-group-item-text">Displays the full list of inventory within the system for the specified month.</p>
                </a>
                <a href="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'inventoryreconciliationreport']); ?>" class="list-group-item">
                    <h4 class="list-group-item-heading">Inventory Reconciliation Report</h4>
                    <p class="list-group-item-text">Displays the begining inventory and transaction breakout to arrive at ending inventory for specified month.</p>
                </a-->

            </div>
        </div>
    </div>
</div>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>