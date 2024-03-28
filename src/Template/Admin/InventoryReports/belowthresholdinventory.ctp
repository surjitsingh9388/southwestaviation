<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Parts[]|\Cake\Collection\CollectionInterface $parts
 */
?>
<?php 
$sessionUser = $this->request->session()->read('Auth.User'); 
?>
<script src=
    "//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js">
</script>
<script type="text/javascript">
    var hashes = window.location.search.substring(1);
    
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'ajaxBelowThresholdSearch']); ?>";
    var exportLineItemsExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'exportBelowThresholdInventory']); ?>";
    if(hashes != ''){
        ajaxListPageSearchURL += '?'+hashes;
        exportLineItemsExcelURL += '?'+hashes+'&';
    }else{
        exportLineItemsExcelURL += '?1=1&';
    }

    var inventoryitemdetURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'detail',]); ?>";
    
    var pagelimit = <?php echo PAGINATION_LIMIT;?>;
    var pdfPagTitle = 'INVENTORY BELOW THRESHOLD REPORT';
</script>

<?php
echo $this->Html->css('inventory_report');
echo $this->Html->script('inventory_report');
echo $this->Html->script('inventory_common');
?>

<?php echo $this->Form->create('', ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="content sliding">
    <div class="outerWrapper flex-column-mob">
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading"><?php echo $this->Html->link('Inventory Reports', ['action' => 'index']).' / Inventory Below Threshold Report'; ?></h2>
            
            <div class="float-right">
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1) {
                    echo $this->Html->link("Print", 'javascript:void(0);', array('class' => 'btn btn-default', 'id'=>'export-button-pdf', 'escape' => false));
                    echo $this->Html->link("Export", 'javascript:void(0);', array('class' => 'btn btn-default exportLineItemExcel', 'escape' => false));
                }
                ?>

                <select class="selectpicker" id="reportRedirectUrl">
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completeinventory']); ?>">Complete Inventory</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'expiringinventory']); ?>">Expiring Inventory</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'belowthresholdinventory']); ?>" selected>Inventory Below Threshold</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completethresholdinventory']); ?>">Inventory Complete Threshold</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'transactionhistory']); ?>">Transaction History</option>
                    <!--option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completeinventorymonthly']); ?>">Monthly Complete Inventory</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'inventoryreconciliationreport']); ?>">Inventory Reconciliation Report</option-->
                </select>    
            </div>
        </div>
        
        <div class="page-content mt-35">
            
            <div class="action-bar dflex">
                <div class="input-group search-control mb-5">
                    <input id="searchItem" name="searchItem" type="text" class="form-control" placeholder="Search Below Threshold Inventory" >
                    <div class="input-group-btn valign-t">
                        <button class="btn btn-default" type="button">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div>
                </div>
                
                <div class="inputWrap btn-group sortWrap mb-5">                    
                    <label class="po-order-sortby dNoneMOb">Sort By</label>
                    <select class="selectpicker" id="FilterBy" name="sortBy">
                        <option value="">Sort By</option>
                        <option value="1">Name</option>
                        <option value="2" selected>Part Number</option>
                        <option value="3">Location</option>
                        <option value="4">Quantity Available</option>
                        <option value="5">Safety Stock Quantity</option>
                        <option value="6">On order</option>
                        <option value="7">Cost Per Unit</option>
                    </select>
                    <div class="ml-10">
                        <button type="button" class="btn ml-5 resetfilterbtn mb-5">Clear</button>
                        <button type="button" class="btn btn-primary btnOpenFilterPopup ml-5 mb-5">Filter</button>
                    </div>
                </div>
            </div>

            <div class="tableScroll">
                <table id="datatableListingPage" class="table dataTable table2excel invitemtable" width="100%">
                    <thead>
                        <tr>
                            <th class="check noExl"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                            <th class="valign-t" scope="col"><?php echo __('Item Name'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Part Number'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Location'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('In Stock'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Safety Stock'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('On Order'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Unit Cost'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="popupFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content">
            <?php echo $this->element('InventoryFilter/inventory_belowthreshold_filter'); ?>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>