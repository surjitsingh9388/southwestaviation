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
echo $this->Html->script('inventory_report'); 
echo $this->Html->script('inventory_items');
echo $this->Html->script('inventory_common');
?>

<style>
    .dataTables_filter {
        display: none;
    }
    
    #searchItem{
        //border-radius: 5px;
    }

    /*table.dataTable>thead .sorting::before,
    table.dataTable>thead .sorting_asc::before,
    table.dataTable>thead .sorting_desc::before,
    table.dataTable>thead .sorting_asc_disabled::before,
    table.dataTable>thead .sorting_desc_disabled::before {
        right: 0;
        content: "";
    }
 
    table.dataTable>thead .sorting::after,
    table.dataTable>thead .sorting_asc::after,
    table.dataTable>thead .sorting_desc::after,
    table.dataTable>thead .sorting_asc_disabled::after,
    table.dataTable>thead .sorting_desc_disabled::after {
        right: 0;
        content: "";
    }
 
    table.dataTable>thead>tr>th:not(.sorting_disabled),
    table.dataTable>thead>tr>td:not(.sorting_disabled) {
        padding-right: 4px;
        padding-left: 4px;
    }
    
    table.dataTable>thead>tr>th,
    table.dataTable>thead>tr>td {
        padding-right: 4px;
        padding-left: 4px;
    }*/

    .btnspace{
        margin-right: 5px !important;
    }
    
    .mt5{
        margin-top:10px;
    }

    .dataTable tbody tr{
        cursor:pointer;
    }

    .btnWrapper .btn {
        margin: 5px;
        height: 36px;
    }

    .dt-buttons{
        display:none;
    }

    label.btn.btn-default.active {
        background-color: #5cca5c;
        color: #fff;
    }

    .error-text {
        color: red;
    }

    .status.pi-status-active {
        background-color: #0096D9;
    }

    .status.pi-status-outforrepair {
        background-color: #00D3B4;
    }

    .status.pi-status-installed {
        background-color: #27AE60;
    }

    .status.pi-status-quarantined {
        background-color: #F46323;
    }

    .status.pi-status-allocated {
        background-color: #FF0000;
    }

    .status.pi-status-onorder {
        background-color: #9B4EB5;
    }

    .status.pi-status-shipped {
        background-color: #2C3E50;
    }

    .status.pi-status-damaged {
        background-color: #E74C3C;
    }

    .status.pi-status-discarded {
        background-color: #2C3E50;
    }

    .status.pi-status-unavailable {
        background-color: #3498DB;
    }

    .status.pi-status-needs-repair {
        background-color: #E74C3C;
    }

    .status.pi-status-consumed {
        background-color: #2C3E50;
    }

    .status.pi-status-unrepairable {
        background-color: #E74C3C;
    }

    .po-inactive {
        background-color: #CE0000;
    }

    .status {
        display: inline-block;
        padding: 0.4em 0.6em 0.4em;
        font-size: 12px;
        font-weight: bold;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: text-bottom;
        width: 100px;
        background-color: #777777;
    }

    .dataTable tbody tr{
        cursor:pointer;
    }
</style>

<?php echo $this->Form->create('', ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link('Inventory Reports', ['action' => 'index']).' / Complete Inventory Report'; ?></h2>
            
            <div style="float:right;">
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
                    <input id="searchItem" name="searchItem" type="text" class="form-control" placeholder="Search Complete Inventory" style="width:170px !important;">
                    <div class="input-group-btn" style="vertical-align:top;">
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

                    <label style="color: white;font-size: 15px;padding-right: 10px;padding-left: 10px;">Sort By</label>
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
                    <div style="margin-left: 10px;">
                        <button type="button" class="btn ml-5 resetfilterbtn">Clear</button>
                        <button type="button" class="btn btn-primary btnOpenFilterPopup" style="margin-left: 5px;">Filter</button>
                    </div>
                </div>

                <div class="inputWrap btn-group sortWrap actionWrap" style="margin-left:10px;">
                    <select class="selectpicker invquantitiesaction" id="actionSel">
                        <option value="">Action on Selected</option>
                        <option value="2" disabled>Bulk Transfer</option>
                        <option value="3" disabled>Bulk Install</option>
                        <option value="4" disabled>Bulk Discard</option>
                    </select>
                </div>

                <div class="total-cost" style="display: inline; color:#fff;">
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
                            <th style="vertical-align: top;" scope="col"><?php echo __('Part Number/Name'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Serial or Lot / Display'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Quantity'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Cost'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Location'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Status'); ?></th>
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