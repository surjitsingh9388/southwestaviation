<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

echo $this->Html->css('inventory_item');
?>

<div class="content sliding">
    <div class="outerWrapper">
         
        <div class="btnWrapper">
            <h2 class="heading">
                <?php echo $this->Html->link('Item Catalog', ['action' => 'index']).' / '.$invenotryitems->name. '(PN: '.$invenotryitems->part_number.')'; ?>&nbsp;
                <?php if($invenotryitems->status == '1'){ ?>
                <span class="badge heading-success badge-status">Active</span>
                <?php }else{ ?>
                <span class="badge badge-status">Inactive</span>
                <?php } ?>
            </h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if($invenotryitems->status == '1' && (!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                ?>
                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'edit', $invenotryitems->id]); ?>">Edit</a></li>
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'create', $invenotryitems->id]); ?>">Add Inventory</a></li>
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'create', "?" => ["invitemid" => $invenotryitems->id, 'po_type'=>1]]); ?>">Purchase Item</a></li>
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'create', "?" => ["invitemid" => $invenotryitems->id, 'po_type'=>2]]); ?>">Exchange Item</a></li>
                        <li><a class="dropdown-item printBarCode" href="javascript:void(0);">Print Barcode</a></li>
                        <?php } ?>
                        <?php 
                        if($invenotryitems->status == '1' && (!empty($actionItems) && $actionItems['action']['action_delete'] == 1) || $sessionUser['id'] == 1) {
                        if($invenotryitems->status == '0'){ ?>
                            <li><a class="dropdown-item changeinvitmstatus" status-val="1" href="javascript:void(0);">Activate</a></li>
                        <?php }else{ ?>
                            <li><a class="dropdown-item changeinvitmstatus" status-val="0" href="javascript:void(0);">Deactivate</a></li>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <?php
                 echo $this->Form->create($invenotryitems, array('class' => 'form-horizontal form-label-left', 'id' => 'frmItemCatalogDet'));
                ?>
                <?php echo $this->element('Inventory/inventory_item_details'); ?>
                
                <div style="clear: both;"></div>
                <?php 
                echo $this->Form->end(); 
                ?>
                <!-- Tabs Start -->
                <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                    <div class="container">
                        
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemQuantities">Quantities</a></li>
                            <li><a data-toggle="tab" href="#itemsThresholds">Thresholds 
                                <?php 
                                if(!empty($itemthresholds)){
                                if($invenotryitems->item_instock < $totalThreshold){ ?>
                                <span class="badge-threshold" title="" title="Location safety stock threshold(s) not met"><?php echo $thresholdRemaining; ?></span>
                                <?php }else if($invenotryitems->item_instock >= $totalThreshold){ ?>
                                <i class="fa fa-check-circle inventory-item-belowthresholds" title="All location safety stock thresholds met" data-toggle="tooltip" ></i>
                                <?php }} ?>
                                </a>
                            </li>
                            <li><a data-toggle="tab" href="#itemsPurchaseorders">Purchase Orders</a></li>
                            <li><a data-toggle="tab" href="#itemsAttachments">Attachments</a></li>
                            <li><a data-toggle="tab" href="#itemsHistory">History</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemQuantities" class="tab-pane fade in active">
                                <div class="" style="margin-top:10px;">
                                    <div class="item-counts-row">
                                        <div class="col-sm-2">
                                            <div id="item-catalog-quantities-in-stock-tile" class="tile inventory-tile instock-count" data-val="1">
                                                <div id="item-catalog-quantities-in-stock-tile-count" class="tile-body">
                                                    <?php echo $stock; ?>
                                                </div>
                                                <div class="tile-label">
                                                    <div href="">In Stock</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div id="item-catalog-quantities-out-for-repair-tile" class="tile inventory-tile outforrepair-count" data-val="7">
                                                <div id="item-catalog-quantities-out-for-repair-tile-count" class="tile-body">
                                                    <?php echo $outforrepair; ?>
                                                </div>
                                                <div class="tile-label">
                                                    <div href="">Out For Repair</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div id="item-catalog-quantities-installed-tile" class="tile inventory-tile installed-count" data-val="2">
                                                <div id="item-catalog-quantities-installed-tile-count" class="tile-body">
                                                    <?php echo $installed; ?>
                                                </div>
                                                <div class="tile-label">
                                                    <div href="">Installed</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div id="item-catalog-quantities-quarantined-tile" class="tile inventory-tile quarantined-count" data-val="12">
                                                <div id="item-catalog-quantities-quarantined-tile-count" class="tile-body">
                                                    <?php echo $quarantined; ?>
                                                </div>
                                                <div class="tile-label">
                                                    <div>Quarantined</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div id="item-catalog-quantities-allocated" class="tile inventory-tile allocated-count" data-val="11">
                                                <div id="item-catalog-quantities-allocated-count" class="tile-body">
                                                    <?php echo $allocated; ?>
                                                </div>
                                                <div class="tile-label">
                                                    <div href="">Allocated</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div id="item-catalog-quantities-on-order-tile" class="tile inventory-tile onorder-count" data-val="order">
                                                <div id="item-catalog-quantities-on-order-tile-count" class="tile-body">
                                                    <?php echo $onorder; ?>
                                                </div>
                                                <div class="tile-label">
                                                    <div href="">On Order</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmItemInventoryDet'));
                                    ?>
                                    <div class="action-bar">
                                        <div class="col-sm-12">

                                            <div class="col-sm-4">
                                                <input type="text" id="quantitesSearchItem" name="search" class="form-control" placeholder="Search Inventory" style="border-radius: 5px">
                                                <div style="display: inline; position:absolute;right: 20px;top: 6px;color: darkgray">
                                                    <i class="fa fa-search"></i>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <a ng-href="" class="btn btn-primary dispinvdetpagepopup" data-val="invqantitiesfilter">Filter</a>
                                                <a ng-href="" class="btn btn-default resetquantitiesfilterbtn">Clear</a>
                                                <span style="margin-left: 10px;" for="showinactive">
                                                    <input id="showinactiveinv" type="checkbox" name="invinactive" value="1">&nbsp;
                                                    <span>Include Inactive</span>
                                                </span>
                                            </div>
                                            <?php
                                            if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                                            ?>
                                            <div class="btn-group col-sm-4" style="float:right;">
                                                <select class="selectpicker actionSel invquantitiesaction" id="actionSel">
                                                    <option value="">Action on Selected</option>
                                                    <option value="1" disabled>Add to Holding Box</option>
                                                    <option value="2" disabled>Bulk Transfer</option>
                                                    <option value="3" disabled>Bulk Install</option>
                                                    <option value="4" disabled>Bulk Discard</option>
                                                    <option value="5" disabled>Print Barcode</option>
                                                </select>    
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="">

                                        <table id="datatableQuantities" class="table dataTable table2excel" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="check noExl"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Serial or Lot / Display'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Qty.'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Cost'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Location'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Received'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Last Transaction'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Status'); ?></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <?php 
                                    echo $this->Form->end(); 
                                    ?>
                                </div>
                            </div>
                            <div id="itemsThresholds" class="tab-pane fade">
                                <div class="mt5">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <div class="search-control" style="width: 220px; margin-right:10px;display: inline-block;position: relative;">
                                                <input type="text" class="form-control thresholdSearchItem" placeholder="Search thresholds list">
                                                <div style="display: inline; position:absolute; right: 10px; top: 6px; color: darkgray">
                                                    <i class="fa fa-search"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <?php
                                            if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                                            ?>
                                            <div  style="float:right;">
                                                <button type="button" class="btn btn-primary dispinvdetpagepopup" data-val="thresholds">Create Thrashold</button>

                                                <div class="inputWrap btn-group" style="margin-left:10px;margin-bottom: 5px;">
                                                    <select class="selectpicker" id="actionOnSelect">
                                                        <option value="">Action on Selected</option>
                                                        <?php if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $sessionUser['id'] == 1) { ?>
                                                        <option>Delete</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="table-responsive" style="width:100% !important;">
                                        <table id="inventoryThresholdTable" class="table mb-0 invitemThresholdTable">
                                            <thead>
                                                <tr style="cursor:pointer;">
                                                    <th class="check"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                                                    <th id="thresholdLocation">Location <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="thresholdInstock">In Stock<i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="thresholdSafetyStock">Safety Stock <i class="fa fa-fw fa-sort"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody id="inventoryThresholdList">
                                                <?php
                                                if(!empty($itemthresholds)){
                                                foreach($itemthresholds as $key=>$val){
                                                    $key = $key+1;
                                                    $evenOdd = (!empty($key) && ($key % 2) == 0) ? 'even' : 'odd';  
                                                ?>
                                                <tr class="mainTR activeTble <?php echo $evenOdd; ?>" style="cursor:pointer;">
                                                    <td class="collapse-tr"><input type="checkbox" class="chkBoxCls" name="childcheckbox" value="<?php echo $val['id'];?>"></td>
                                                    <td class="collapse-tr"><span class="thresholdloc" style="display:none;"><?php echo $val['location_id'];?></span><?php echo $val['location_name'];?></td>
                                                    <td class="collapse-tr">
                                                        <?php
                                                        echo !empty($val['instock']) ? $val['instock'] : '0';
                                                        if($val['instock'] < $val['safety_stock_threshold']){
                                                            echo '&nbsp;<i class="fa fa-exclamation-triangle color-threshold-not-met" title="This location has not met its safety stock requirement for this item." data-toggle="tooltip"></i>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="collapse-tr"><span class="thresholdstock"><?php echo $val['safety_stock_threshold'];?></span></td>
                                                </tr>
                                                <?php }}else{ ?>
                                                <tr>
                                                    <td colspan="3">No Threshold Found.</td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="itemsPurchaseorders" class="tab-pane fade">
                                <div class="">
                                    <?php echo $this->element('Inventory/inventory_purchase_order_list', ['isinvdetpage'=>'invdetpage', 'sessionUser'=>$sessionUser]); ?>
                                </div>
                            </div>
                            <div id="itemsAttachments" class="tab-pane fade"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative">
                                    <div style="margin-top:10px;">
                                        <div class="search-control" style="width: 220px; margin-right:10px;display: inline-block;position: relative;margin-left: 5px;">
                                            <input type="text" id="attachmentSearch" class="form-control" placeholder="Search Attachments">
                                            <div style="display: inline; position:absolute; right: 10px; top: 6px; color: darkgray">
                                                <i class="fa fa-search"></i>
                                            </div>
                                        </div>

                                        <div class="pull-right">
                                            <button class="btn btn-primary pull-right" type="button" disabled>Upload</button>
                                        </div>
                                    </div>

                                    <table class="table upload-area" id="uploadfile">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-2">File Name</th>
                                                <th class="col-sm-1">Size</th>
                                                <th class="col-sm-2">Uploaded</th>
                                                <th class="col-sm-2">Uploaded By</th>
                                            </tr>
                                        </thead>
                                        <tbody id="filetbody">
                                            <?php if(empty($attachments)){ ?>
                                            <tr>
                                                <td colspan="5">No Attachments.</td>
                                            </tr>
                                            <?php 
                                            }else{ 
                                            foreach($attachments as $attachment){
                                                $ext = substr(strrchr($attachment['file_name'] , '.'), 1);

                                                $iconcss = '';
                                                if($ext == 'pdf'){
                                                    $iconcss = 'icon-pdf';
                                                }else if($ext == 'doc' || $ext == 'docx'){
                                                    $iconcss = 'icon-doc';
                                                }else if($ext == 'xls' || $ext == 'xlsx'){
                                                    $iconcss = 'icon-excel';
                                                }else if($ext == 'txt'){
                                                    $iconcss = 'icon-text';
                                                }else{
                                                    $iconcss = 'icon-generic';
                                                }
                                            ?>
                                            <tr style="text-align:left;">
                                                <td class="document-name"><span class="document-management-icon <?php echo $iconcss; ?>"></span>
                                                <?php
                                                echo $this->Html->link($attachment['file_name'], '/inventoryitems/' . $attachment['file_name'],['download'=>$attachment['file_name']]);
                                                ?></td>
                                                <td><?php echo $attachment['file_size']; ?></td>
                                                <td><?php echo $attachment['created']; ?></td>
                                                <td><?php echo $attachment['uploaded_by']; ?></td>
                                            </tr>
                                            <?php }} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- attachment-tab-section end -->
                            <div id="itemsHistory" class="tab-pane fade">
                                <div class="">
                                    <table class="table invitmhistory">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-2">Date</th>
                                                <th class="col-sm-2">User</th>
                                                <th class="col-sm-2">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach($inventoryitemhistories as $invhistory){
                                                $data = @unserialize($invhistory['description']); 
                                                if ($data === false) {
                                                    $data = $invhistory['description'];
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo date('d-M-Y h:i A', strtotime($invhistory['created'])); ?></td>
                                                <td><?php echo $invhistory['users']['email']; ?></td>
                                                <td>
                                                    <a class="toggleplusminus"><i class="fa fa-plus"></i></a>
                                                    <?php echo $invhistory['title']; ?>
                                                    <pre class="history-detail-block" style="display:none;"><?php echo $data;?>
                                                    </pre>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabs end -->
            </div>
        </div>
       
    </div>
</div>

<?php
if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
?>
<div id="invThresholdAddModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content">
            <?php echo $this->element('Inventory/inventory_threshold_add'); ?>
        </div>
    </div>
</div>
<?php } ?>

<div id="invQuantitiesFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content">
            <?php echo $this->element('InventoryFilter/inventory_item_quantities_filter'); ?>
        </div>
    </div>
</div>

<div id="invQuantitiesActionOnSelectModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content" id="bulkpopupcontent">
            
        </div>
    </div>
</div>

<div id="popupFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <?php echo $this->element('InventoryFilter/inventory_purchase_order_filter'); ?>
</div>

<!--- inventory print barcode popup --->
<?php echo $this->element('InventoryPopup/inventory_printbarcode_popup'); ?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<script>
var qantitiesDetPageURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'detail',]); ?>";
var deleteThresholdsURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'deleteThresholds']); ?>";
var getInventoryItemPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'getInventoryItemPopupData']); ?>";
var purchaseOrderListPageURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'index']); ?>";
var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'ajaxInventoryPurchaseOrdersearch']); ?>";
var pagelimit = <?php echo PAGINATION_LIMIT;?>;
var pdfPagTitle = 'PURCHASE ORDER';
var ajaxOpenActionSelectPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'ajaxOpenActionSelectPopup']); ?>";
var addQuantitiesToHoldingBoxURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'addQuantitiesToHoldingBox']); ?>";
var bulkTransferURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkTransfer']); ?>";
var bulkDiscardURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkDiscard']); ?>";
var bulkInstallURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkInstall']); ?>";
var updateInventoryItemStatusURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'updateInventoryItemStatus']); ?>";
var printBarCodeURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'printInventoryItemsBarCode']); ?>";
var inventoryPurchaseOrderDetailsURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'detail',]); ?>";
var printInventoriesListBarCodeURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'printInventoriesListBarCode']); ?>";

var exportLineItemsExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'exportPurchaseLineItems']); ?>";
var exportListingDataExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'exportPurchaseListToExcel']); ?>";

if(window.location.search != ''){
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    exportLineItemsExcelURL += '?'+hashes[0]+'&';
    exportListingDataExcelURL += '?'+hashes[0]+'&';
}else{
    exportLineItemsExcelURL += '?1=1&';
    exportListingDataExcelURL += '?1=1&';
}

$(document).ready(function () {
    
    var inventory_item_id = window.location.pathname.split("/").pop();
    var dataTable = $('#datatableQuantities').DataTable({
        'columnDefs': [
            { 'orderable': false, 'targets': '_all' },
            { className: "check noExl", "targets": [ 0 ] }
        ],
        'order': [0, 'asc'],
        //"searching": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[<?php echo PAGINATION_LIMIT;?>, 50, 100, -1], [<?php echo PAGINATION_LIMIT;?>, 50, 100, "All"]],
        "lengthChange": false,
        "ajax":{
            url:"<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'ajaxQantitiesSearch']); ?>",
            accepts: 'application/json', 
            type: "post",
            data:{'inventory_item_id':inventory_item_id},
            error: function(){
                $(".employees-grid-error").html("");
                $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employees-grid_processing").css("display","none");
            }
        }
    });

    $('#quantitesSearchItem').bind("keyup", function(){
        dataTable.columns(0).search($("#quantitesSearchItem").val()).draw();
    });

    $(document).on('click', ".resetquantitiesfilterbtn", function (e) {
        $("#quantitesSearchItem").val('');
        $("#showinactiveinv").prop('checked', false);

        dataTable.columns(0).search('').draw();
        dataTable.columns(1).search('').draw();

        $('#frmInventoryQuantitiesFilter')[0].reset();

        var formdata = '';
        searchInventoryQantities(formdata);
        $('.selectpicker').selectpicker('refresh');
    });

    $(document).on('click', '#showinactiveinv', function(e){
        var inactiveinv = 0;
        if($("#showinactiveinv").prop('checked') == true){
            inactiveinv = 1;
        }
        dataTable.columns(1).search(inactiveinv).draw();
    });

});
$(document).on('click', '.applyinvqtyfilterbtn', function(e){
    var formdata = $("#frmInventoryQuantitiesFilter").serialize();
    searchInventoryQantities(formdata);

    $("#invQuantitiesFilterModel").modal('hide');
});
$(document).on('click', '.clearinvitmquantitiesfilter', function(e){
    $('#frmInventoryQuantitiesFilter')[0].reset();
    $('.selectpicker').selectpicker('refresh');
    var formdata = '';
    searchInventoryQantities(formdata);
})
function searchInventoryQantities(formdata){
    var dataTable = $('#datatableQuantities').DataTable();
    dataTable.columns(2).search(formdata).draw();
}

</script>

<?php 
echo $this->Html->script('jquery.sortElements'); 
echo $this->Html->script('inventory_items'); 
echo $this->Html->script('inventory_purchase_order');
echo $this->Html->script('inventory_common');
?>