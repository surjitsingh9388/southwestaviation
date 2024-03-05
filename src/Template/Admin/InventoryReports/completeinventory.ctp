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
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'ajaxCompleteInventorySearch']); ?>";
    var exportLineItemsExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'exportCompleteInventory']); ?>";
    if(window.location.search != ''){
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        ajaxListPageSearchURL += '?'+hashes[0];
        exportLineItemsExcelURL += '?'+hashes[0]+'&';
    }else{
        exportLineItemsExcelURL += '?1=1&';
    }
    var inventoryitemdetURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'detail',]); ?>";
    var bulkTransferURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkTransfer']); ?>";
    var bulkDiscardURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkDiscard']); ?>";
    var ajaxOpenActionSelectPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'ajaxOpenActionSelectPopup']); ?>";

    var pagelimit = <?php echo PAGINATION_LIMIT;?>;
    var pdfPagTitle = 'COMPLETE INVENTORY REPORT';
</script>

<?php
echo $this->Html->css('inventory_report'); 

echo $this->Html->script('inventory_report'); 
echo $this->Html->script('inventory_items');
echo $this->Html->script('inventory_common');
?>

<?php echo $this->Form->create('', ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link('Inventory Reports', ['action' => 'index']).' / Complete Inventory Report'; ?></h2>
            
            <div class="float-right">
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1) {
                    echo $this->Html->link("Print", 'javascript:void(0);', array('class' => 'btn btn-default', 'id'=>'export-button-pdf', 'escape' => false));
                    echo $this->Html->link("Export", 'javascript:void(0);', array('class' => 'btn btn-default exportLineItemExcel', 'escape' => false, 'data-val'=>'inventory_item_reports'));
                }
                ?>

                <select class="selectpicker" id="reportRedirectUrl">
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completeinventory']); ?>">Complete Inventory</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'expiringinventory']); ?>">Expiring Inventory</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'belowthresholdinventory']); ?>">Inventory Below Threshold</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completethresholdinventory']); ?>">Inventory Complete Threshold</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'transactionhistory']); ?>">Transaction History</option>
                    <!--option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completeinventorymonthly']); ?>">Monthly Complete Inventory</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'inventoryreconciliationreport']); ?>">Inventory Reconciliation Report</option-->
                </select>    
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="action-bar dflex">
                <div class="input-group search-control mb-0">
                    <input id="searchItem" name="searchItem" type="text" class="form-control complete-search-box" placeholder="Search Complete Inventory">
                    <div class="input-group-btn valign-t">
                        <button class="btn btn-default" type="button">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div>
                </div>
                
                <div class="inputWrap btn-group sortWrap">
                    <select class="selectpicker" id="SearchBy" name="SearchBy">
                        <option value="">Select filter</option>
                        <option value="1">Active Inventory</option>
                        <option value="11">Allocated</option>
                        <option value="2">Installed Inventory</option>
                        <option value="7">Out for Repair</option>
                    </select>    

                    <label class="po-order-sortby">Sort By</label>
                    <select class="selectpicker" id="FilterBy" name="sortBy">
                        <option value="">Sort By</option>
                        <option value="1">Part Number</option>
                        <!--option value="2">Part Name</option-->
                        <option value="2" selected>Lot/Serial Number</option>
                        <!--option value="4">Display Name</option-->
                        <option value="3">Quantity</option>
                        <option value="4">Cost</option>
                        <option value="5">Location</option>
                        <option value="6">Status</option>
                    </select>
                    <div class="ml-10">
                        <button type="button" class="btn ml-5 resetfilterbtn">Clear</button>
                        <button type="button" class="btn btn-primary btnOpenFilterPopup ml-5">Filter</button>
                    </div>
                </div>

                <div class="inputWrap btn-group sortWrap actionWrap ml-10">
                    <select class="selectpicker invquantitiesaction" id="actionSel">
                        <option value="">Action on Selected</option>
                        <option value="2" disabled>Bulk Transfer</option>
                        <option value="3" disabled>Bulk Install</option>
                        <option value="4" disabled>Bulk Discard</option>
                    </select>
                </div>

                <div class="total-cost total-cost-block">
                    <label class="sub-header">Total Cost: </label>
                    <div class="hover-container">
                        <span id="totalCost"></span>
                        <i class="fa fa-info-circle"></i>
                    </div>
                </div>
            </div>

            <div class="tableScroll">
                <table id="datatableListingPage" class="table dataTable table2excel invitemtable" width="100%">
                    <thead>
                        <tr>
                            <th class="check noExl"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                            <th class="valign-t" scope="col"><?php echo __('Part Number/Name'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Serial or Lot / Display'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Quantity'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Cost'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Location'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Status'); ?></th>
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
            <?php echo $this->element('InventoryFilter/inventory_complete_filter'); ?>
        </div>
    </div>
</div>

<div id="invQuantitiesActionOnSelectModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content" id="bulkpopupcontent">
            
        </div>
    </div>
</div>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>