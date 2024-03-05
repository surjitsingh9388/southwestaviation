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
echo $this->Html->script('inventory_request'); 
echo $this->Html->script('inventory_common');
?>

<style>
    .dataTables_filter {
        display: none;
    }
    
    #searchItem{
        //border-radius: 5px;
    }

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

    .ro-request-is-urgent {
        color: #ce2127;
    }
</style>

<?php echo $this->Form->create('', ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Inventory Requests</h2>
            
            <div style="float:right;">
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
                
                <div class="inputWrap btn-group sortWrap" style="margin-left:10px;">
                    <label style="color: white; font-size: 20px; padding-right: 10px;">Sort By</label>
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
                    <div style="margin-left: 10px;">
                        <button type="button" class="btn ml-5 resetfilterbtn">Clear</button>
                        <button type="button" class="btn btn-primary btnOpenFilterPopup" style="margin-left: 5px;">Filter</button>
                    </div>
                </div>

            </div>

            <div class="tableScroll">
                <table id="datatableListingPage" class="table display dataTable table2excel <?php if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $sessionUser['id'] == 1) { ?>invreqtable<?php } ?>" width="100%">
                    <thead>
                        <tr>
                            <th style="vertical-align: top;" scope="col" style="display:none;"></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Number'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Title'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Requested By'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Date Requested'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Date Required'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Urgency'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Status'); ?></th>
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