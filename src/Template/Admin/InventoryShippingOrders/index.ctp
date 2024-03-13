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
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'ajaxInventoryShippingOrdersearch']); ?>";
    var pagelimit = <?php echo PAGINATION_LIMIT;?>;
    var inventoryPurchaseOrderDetailsURL = "<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'detail',]); ?>";
    
    var exportListingDataExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'exportShippingOrderListToExcel']); ?>";

    if(window.location.search != ''){
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        exportListingDataExcelURL += '?'+hashes[0]+'&';
    }else{
        exportListingDataExcelURL += '?1=1&';
    }

    var pdfPagTitle = 'SHIPPING ORDERS';
    
</script>

<?php 
echo $this->Html->css('inventory_shipping_order');
echo $this->Html->script('inventory_common'); 
echo $this->Html->script('inventory_purchase_order');
?>

<?php echo $this->Form->create('', ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading">Shipping Orders</h2>
            
            <div class="float-right mb-5">
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $sessionUser['id'] == 1) {
                echo $this->Html->link("Print", 'javascript:void(0);', array('class' => 'btn btn-default', 'id'=>'export-button-pdf', 'escape' => false));
                echo $this->Html->link("Export", 'javascript:void(0);', array('class' => 'btn btn-default exportListingDataExcel ml-5', 'escape' => false));
            }
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1) {
                echo $this->Html->link("Create", array('action' => 'create'), array('class' => 'btn btn-primary', 'escape' => false));
            }
            ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="action-bar dflex">
                <div class="input-group search-control mb-0">
                    <input id="searchItem" name="searchItem" type="text" class="form-control" placeholder="Search Shipping Orders">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div>
                </div>
                
                <div class="inputWrap btn-group sortWrap" class="ml-10">
                    <label class="po-order-sortby dNoneMOb">Sort By</label>
                    <select class="selectpicker" id="FilterBy" name="sortBy">
                        <option value="">Sort By</option>
                        <option value="0">Order Number</option>
                        <option value="1">Reference</option>
                        <option value="2">Requestor</option>
                        <option value="3">Status</option>
                        <option value="4">Shipping Cost</option>
                        <option value="5" selected>Submitted Date</option>
                    </select>
                    <div class="ml-10">
                        <button type="button" class="btn resetfilterbtn">Clear</button>
                        <button type="button" class="btn btn-primary btnOpenFilterPopup ml-5">Filter</button>
                    </div>
                </div>

            </div>

            <div class="tableScroll">
                <table id="datatableListingPage" class="table display dataTable table2excel <?php if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $sessionUser['id'] == 1) { ?>invpotable<?php } ?>" width="100%">
                    <thead>
                        <tr>
                            <th class="valign-t" scope="col"></th>
                            <th class="valign-t" scope="col"><?php echo __('Order'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Reference'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Requestor'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Shipping Cost'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Submitted'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Status'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="popupFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <?php echo $this->element('InventoryFilter/inventory_shipping_order_filter'); ?>
</div>
<?php echo $this->Form->end(); ?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>