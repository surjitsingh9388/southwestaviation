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
<?php echo $this->Html->script('jquery.tokeninput'); ?>
<?php echo $this->Html->css('token-input-facebook.css'); ?>

<script type="text/javascript">
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'ajaxTransctionHistorySearch']); ?>";
    var exportLineItemsExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'exportInventoryTransactionHistory']); ?>";
    if(window.location.search != ''){
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        ajaxListPageSearchURL += '?'+hashes[0];
        exportLineItemsExcelURL += '?'+hashes[0]+'&';
    }else{
        exportLineItemsExcelURL += '?1=1&';
    }
    var transactionReportDetURL = "<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'transactiondetail',]); ?>";

    var pagelimit = <?php echo PAGINATION_LIMIT;?>;
    var pdfPagTitle = 'TRANSACTION HISTORY REPORT';
    var transactionactionlist = <?php echo json_encode(unserialize(TRANSACTION_ACTION_LIST)); ?>;
    
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
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading"><?php echo $this->Html->link('Inventory Reports', ['action' => 'index']).' / Transaction History Report'; ?></h2>
            
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
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'belowthresholdinventory']); ?>">Inventory Below Threshold</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completethresholdinventory']); ?>">Inventory Complete Threshold</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'transactionhistory']); ?>" selected>Transaction History</option>
                    <!--option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'completeinventorymonthly']); ?>">Monthly Complete Inventory</option>
                    <option value="<?php echo $this->Url->build(['controller'=>'InventoryReports', 'action'=>'inventoryreconciliationreport']); ?>">Inventory Reconciliation Report</option-->
                </select>    
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="action-bar dflex">
                <div class="input-group search-control mb-0">
                    <input id="searchItem" name="searchItem" type="text" class="form-control" placeholder="Search Transaction History">
                    <div class="input-group-btn valign-t">
                        <button class="btn btn-default" type="button">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div>
                </div>
                
                <div class="inputWrap btn-group sortWrap">
                    <select class="selectpicker" id="SearchBy" name="SearchBy">
                        <option value="">Select filter</option>
                        <option value="1">Expensed Inventory</option>
                        <option value="2">Recently Received</option>
                        <option value="3">Recently Uninstall</option>
                    </select>    

                    <label class="expiring-block">Sort By</label>
                    <select class="selectpicker" id="FilterBy" name="sortBy">
                        <option value="">Sort By</option>
                        <option value="0" selected>Create Date</option>
                        <option value="1">From Description</option>
                        <option value="2">To Description</option>
                        <option value="3">Part Name</option>
                        <option value="4">Part Number</option>
                        <option value="5">Lot/Serial Number</option>
                        <option value="6">Qunatity</option>
                        <option value="7">Unit Cost</option>
                        <option value="8">Cost</option>
                        <option value="9">Type</option>
                    </select>
                    <div class="ml-10">
                        <button type="button" class="btn ml-5 resetfilterbtn" onclick='$("#transaction_action").tokenInput("clear");'>Clear</button>
                        <button type="button" class="btn btn-primary btnOpenFilterPopup ml-5">Filter</button>
                    </div>
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
                <table id="datatableListingPage" class="table dataTable table2excel invtransactionreporttable" width="100%">
                    <thead>
                        <tr>
                            <th class="valign-t" scope="col"><?php echo __('Date'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('From Description'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('To Description'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Part Name'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Part No'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Serial or Lot / Display'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Qty'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Unit Cost'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Cost'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Type'); ?></th>
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
            <?php echo $this->element('InventoryFilter/inventory_transaction_filter'); ?>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>