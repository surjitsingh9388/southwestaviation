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
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'ajaxInventoryPurchaseOrdersearch']); ?>";
    var pagelimit = <?php echo PAGINATION_LIMIT;?>;
    var inventoryPurchaseOrderDetailsURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'detail',]); ?>";
    var exportLineItemsExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'exportPurchaseLineItems']); ?>";
    var exportListingDataExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'exportPurchaseListToExcel']); ?>";

    if(window.location.search != ''){
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        
        ajaxListPageSearchURL += '?'+hashes[0];
        exportLineItemsExcelURL += '?'+hashes[0]+'&';
        exportListingDataExcelURL += '?'+hashes[0]+'&';
    }else{
        exportLineItemsExcelURL += '?1=1&';
        exportListingDataExcelURL += '?1=1&';
    }
    var pdfPagTitle = 'PURCHASE ORDER';
</script>

<?php 
echo $this->Html->css('inventory_purchase_order');

echo $this->Html->script('inventory_common'); 
echo $this->Html->script('inventory_purchase_order');
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Purchase Orders</h2>
            
            <div class="text-right">
            <?php
            if((!empty($actionItems) && ($actionItems['action']['action_edit']==1 || $actionItems['action']['action_view']==1)) || $sessionUser['id'] == 1) {
                echo $this->Html->link("Print", 'javascript:void(0);', array('class' => 'btn btn-default', 'id'=>'export-button-pdf', 'escape' => false));
                echo $this->Html->link("Export", 'javascript:void(0);', array('class' => 'btn btn-default exportPOListingDataExcel ml-5', 'escape' => false, 'data-val'=>'inventory_puchase_order_report'));
                echo $this->Html->link("Export Line Items", 'javascript:void(0);', array('class' => 'btn btn-default btnspace exportPOLineItemExcel', 'escape' => false));
            }
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1) {
                echo $this->Html->link("Create", array('action' => 'create'), array('class' => 'btn btn-primary', 'escape' => false));
            }
            ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <?php echo $this->element("Inventory/inventory_purchase_order_list", array('sessionUser'=>$sessionUser)); ?>
        </div>
    </div>
</div>

<div id="popupFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <?php echo $this->element('InventoryFilter/inventory_purchase_order_filter'); ?>
</div>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>