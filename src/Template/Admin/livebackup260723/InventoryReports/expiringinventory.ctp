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
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'ajaxExpiringInventorySearch']); ?>";
    var exportLineItemsExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'exportExpiringInventory']); ?>";
    if(hashes.length > 0){
        ajaxListPageSearchURL += '?'+hashes[0];
        exportLineItemsExcelURL += '?'+hashes[0]+'&';
    }else{
        exportLineItemsExcelURL += '?1=1&';
    }

    var inventoryitemdetURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'detail',]); ?>";
    
    var pagelimit = <?php echo PAGINATION_LIMIT;?>;
    var pdfPagTitle = 'EXPIRING INVENTORY REPORT';
</script>

<?php
echo $this->Html->script('inventory_report');
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

    .dataTable tbody tr{
        cursor:pointer;
    }
</style>

<?php echo $this->Form->create('', ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link('Inventory Reports', ['action' => 'index']).' / Inventory Expiring as of '; ?> 
                <span id="expirationheading">
                    <?php 
                    $hexpirationdate = str_replace('-', '/', $expirationdate);
                    $hexpirationdate = date("d-M-Y", strtotime($hexpirationdate));

                    echo $hexpirationdate;
                    ?>
                    </span>
            </h2>
            
            <div style="float:right;">
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1) {
                    echo $this->Html->link("Print", 'javascript:void(0);', array('class' => 'btn btn-default', 'id'=>'export-button-pdf', 'escape' => false));
                    echo $this->Html->link("Export", 'javascript:void(0);', array('class' => 'btn btn-default exportLineItemExcel', 'escape' => false, 'data-val'=>'inventory_item_reports'));
                }
                ?>

                <select class="selectpicker" id="reportRedirectUrl">
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completeinventory']); ?>">Complete Inventory</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'expiringinventory']); ?>" selected>Expiring Inventory</option>
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
                    <input id="searchItem" name="searchItem" type="text" class="form-control" placeholder="Search Expiring Inventory" >
                    <div class="input-group-btn" style="vertical-align:top;">
                        <button class="btn btn-default" type="button">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div>
                </div>
                
                <div class="inputWrap btn-group sortWrap">
                    <label style="color: white;font-size: 15px;padding-right: 10px;padding-left: 10px;">Expiring as of</label>
                    <div class="input-group datePicker expirationdate">
                        <?php echo $this->Form->Text('search_expiration_date', array('class' => 'form-control col-md-3 col-xs-12 datePicker', 'id' => 'expiration_date', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'), 'value'=>$expirationdate, 'style'=>'width: 175px;border-radius: 5px;')); ?>
                        <span class="input-group-addon" style="display:none;">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    
                    <label style="color: white;font-size: 15px;padding-right: 10px;padding-left: 10px;">Sort By</label>
                    <select class="selectpicker" id="FilterBy" name="sortBy">
                        <option value="">Sort By</option>
                        <option value="1">Item Name</option>
                        <option value="2">Part Number</option>
                        <option value="4">Location</option>
                        <option value="5">Status</option>
                        <option value="6">Quantity Available</option>
                        <option value="7" selected>Expiration Date</option>
                    </select>
                    <div style="margin-left: 10px;">
                        <button type="button" class="btn ml-5 resetfilterbtn">Clear</button>
                        <button type="button" class="btn btn-primary btnOpenFilterPopup" style="margin-left: 5px;">Filter</button>
                    </div>
                </div>
            </div>

            <div class="tableScroll">
                <table id="datatableListingPage" class="table dataTable table2excel invitemtable" width="100%">
                    <thead>
                        <tr>
                            <th class="check noExl"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Item Name'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Part Number'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Serial / Lot'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Location'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Status'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Qty'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Expiration'); ?></th>
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
            <?php echo $this->element('InventoryFilter/inventory_expiring_filter'); ?>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>