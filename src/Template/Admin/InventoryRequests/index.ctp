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
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'ajaxInventoryRequestSearch']); ?>";
    var pagelimit = <?php echo PAGINATION_LIMIT;?>;
    var inventoryRequestDetailsURL = "<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'detail',]); ?>";
    var exportLineItemsExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'exportRequestLineItems']); ?>";
    var exportListingDataExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'exportRequestListToExcel']); ?>";

    if(window.location.search != ''){
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        
        ajaxListPageSearchURL += '?'+hashes[0];
        exportLineItemsExcelURL += '?'+hashes[0]+'&';
        exportListingDataExcelURL += '?'+hashes[0]+'&';
    }else{
        exportLineItemsExcelURL += '?1=1&';
        exportListingDataExcelURL += '?1=1&';
    }
    
    var pdfPagTitle = 'INVENTORY REQUESTS';
</script>

<?php 
echo $this->Html->css('inventory_request');
echo $this->Html->script('inventory_request'); 
echo $this->Html->script('inventory_common');
?>

<?php echo $this->Form->create('', ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Inventory Requests</h2>
            
            <div clas="float-right">
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1) {
                echo $this->Html->link("Print", 'javascript:void(0);', array('class' => 'btn btn-default', 'id'=>'export-button-pdf', 'escape' => false));
                echo $this->Html->link("Export", 'javascript:void(0);', array('class' => 'btn btn-default exportListingDataExcel', 'escape' => false));
                echo $this->Html->link("Export Line Items", 'javascript:void(0);', array('class' => 'btn btn-default btnspace exportLineItemExcel', 'escape' => false));
                echo $this->Html->link("Create", array('action' => 'create'), array('class' => 'btn btn-primary', 'escape' => false));
            }
            ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="action-bar dflex">
                <div class="input-group search-control mb-0">
                    <input id="searchItem" name="searchItem" type="text" class="form-control" placeholder="Search Inventory Requests">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div>
                </div>
                
                <div class="inputWrap btn-group sortWrap ml-10">
                    <label class="po-order-sortby">Sort By</label>
                    <select class="selectpicker" id="FilterBy" name="sortBy">
                        <option value="">Sort By</option>
                        <option value="0">Number</option>
                        <option value="1">Title</option>
                        <option value="2">Requested By</option>
                        <option value="3">Date Requested</option>
                        <option value="4">Date Required</option>
                        <option value="5" selected>Urgency</option>
                        <option value="6">Status</option>
                    </select>
                    <div class="ml-10">
                        <button type="button" class="btn ml-5 resetfilterbtn">Clear</button>
                        <button type="button" class="btn btn-primary btnOpenFilterPopup ml-5">Filter</button>
                    </div>
                </div>

            </div>

            <div class="tableScroll">
                <table id="datatableListingPage" class="table display dataTable table2excel <?php if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $sessionUser['id'] == 1) { ?>invreqtable<?php } ?>" width="100%">
                    <thead>
                        <tr>
                            <th class="valign-t" scope="col"></th>
                            <th class="valign-t" scope="col"><?php echo __('Number'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Title'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Requested By'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Date Requested'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Date Required'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Urgency'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Status'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="popupFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <?php echo $this->element('InventoryFilter/inventory_request_filter'); ?>
</div>

<?php echo $this->Form->end(); ?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>