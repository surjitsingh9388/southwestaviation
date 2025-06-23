<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;

if(!empty($part->plane_id) && !empty($subResults)) {
    $pdfCheck = 'style="pointer-events: auto;"';
} else {
    $pdfCheck = 'style="pointer-events: none;"';
}
?>
<style>
    .dataTables_filter {
        display: none;
    }

    #searchItem{
        /*border-radius: 5px;*/
    }

    .actionWrap .dropdown-toggle {
        width:100px !important;
    }


    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.1em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }

    #itemGeneral{
        padding:10px;
    }

    .mt5 {
        margin-top: 10px !important;
    }
    h5{
        text-align:center;
        font-weight:bold;
    }
    
    .sinfo{
        margin-top: 15px;
    }


    .heading-success {
        color: #FFF;
        background-color: #27ae60;
    }

    .item-counts-row {
        padding-right: 12px;
        padding-left: 12px;
    }

    .item-counts-row .inventory-tile {
        color: #FFF;
        text-align: center;
        margin-top: 0px;
        margin-bottom: 10px;
        font-size: 20px;
        cursor: pointer;
        -webkit-transition: background-color 2000ms ease;
        -moz-transition: background-color 2000ms ease;
        -ms-transition: background-color 2000ms ease;
        -o-transition: background-color 2000ms ease;
        transition: background-color 2000ms ease;
    }
    
    .item-counts-row .instock-count {
        background-color: #0096D9;
    }
    .tile {
        min-width: 125px;
    }

    .item-counts-row .outforrepair-count {
        background-color: #00D3B4;
    }

    .item-counts-row .installed-count {
        background-color: #27AE60;
    }

    .item-counts-row .quarantined-count {
        background-color: #F46323;
    }

    .item-counts-row .allocated-count {
        background-color: #FF0000;
    }

    .item-counts-row .onorder-count {
        background-color: #9B4EB5;
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
        width: 150px;
        background-color: #777777;
    }

    .dataTable tbody tr{
        cursor:pointer;
    }

    .badge-threshold {
        display: inline-block;
        min-width: 10px;
        padding: 3px 7px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        background-color: #e74c3c !important;
        border-radius: 10px;
    }

    .form-control-static {
        padding-top: 4px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .form-control-static {
        border-bottom: 1px solid #dfdfdf;
    }
    .form-control-static {
        min-height: 34px;
        padding-top: 7px;
        padding-bottom: 7px;
        margin-bottom: 0;
    }
    .form-control-static {
        min-height: 34px;
        padding-top: 7px;
        padding-bottom: 7px;
        margin-bottom: 0;
    }

    .action-bar {
        background-color: #39478b;
        padding: 6px 10px 6px 10px;
        margin-bottom: 12px;
    }

    .action-bar {
        border-radius: 0;
        background-color: #f5f5f5;
        height: 59px;
    }

    #itemsPurchaseorders .action-bar {
        background-color: #39478b;
        padding: 6px 10px 6px 10px;
        margin-bottom: 12px;
        height:auto !important;
    }

    .dataTables_paginate a {
        color: #333 !important;
    }

    .inventory-item-belowthresholds {
        color: #2ecc71;
        font-size: 17px;
        vertical-align: middle;
    }

    .history-detail-block {
        padding: 9.5px;
        margin: 0 0 10px;
        font-size: 13px;
        line-height: 1.42857143;
        color: #333;
        word-break: break-all;
        word-wrap: break-word;
        background-color: #f5f5f5;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .invitmhistory a {
        color: #23527c;
        text-decoration: underline;
        cursor: pointer;
    }

    label.btn.btn-default.active {
        background-color: #5cca5c;
        color: #fff;
    }

    .error-text {
        color: red;
    }
    .search-control, .actionMenu{
        border: 1px solid #1e040426;
        border-radius: 3px;
    }
    .sortTxt{
        color: #655555 !important;
    }
</style>

<div class="content sliding">
    <div class="outerWrapper">
         
        <div class="btnWrapper">
            <h2 class="heading">Holding Box</h2>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                
                <!-- Tabs Start -->
                <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                    <div class="container">
                        
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemCatalog">Item Catalog (<?php echo $itemcatalogcount; ?>)</a></li>
                            <li><a data-toggle="tab" href="#physicalInventory">Physical Inventory (<?php echo $inventoriescount; ?>)</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemCatalog" class="tab-pane fade in active">
                                <div class="" style="margin-top:10px;">
                                    <?php
                                        echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmItemCatalog'));
                                    ?>
                                    <div class="action-bar">
                                        <div class="col-xs-12 p-0">
                                            <div class="col-md-3 col-sm-5 col-xs-12 mb-5 ps-0">
                                                <input type="text" id="itemCatalogSearchItem" name="searchItem" class="form-control" placeholder="Search Inventory" style="border-radius: 5px">
                                                <div style="display: inline; position:absolute;right: 20px;top: 6px;color: darkgray">
                                                    <i class="fa fa-search"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-sm-7 col-xs-12 mb-5 ps-0">
                                                <select class="selectpicker" id="CatalogFilterBy" name="sortBy">
                                                    <option value="1">Date Added</option>
                                                    <option value="2">Part Number</option>
                                                    <option value="3">Name</option>
                                                    <option value="4">Type</option>
                                                    <option value="5">Serialized</option>
                                                </select>   
                                                
                                                <a class="btn btn-default clearitemcatalogfilter">Clear</a>
                                                <a class="btn btn-primary" onclick="$('#itemCatalogFilterModel').modal('show');">Filter</a>
                                            </div>

                                            <div class="btn-group col-md-4 col-xs-12 p-0" style="float:right;">
                                            <?php
                                            if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                                            ?>
                                            
                                                <button id="removeallitemcatalogs" type="button" class="btn btn-danger mb-5" data-val='inventory items'>Remove All</button>&nbsp;
                                                <select class="selectpicker actionSel itemcatalogaction mb-5" id="actionSel">
                                                    <option value="">Action on Selected</option>
                                                    <option value="1" disabled>Remove</option>
                                                    <option value="2" disabled>Export</option>
                                                    <option value="3" disabled>Apply Tags</option>
                                                    <option value="4" disabled>Print Barcode</option>
                                                </select>    
                                            
                                            <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tableScrollable">
                                        <table id="datatableItemCatalog" class="table dataTable" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="check noExl"><input type="checkbox" name="air_check" id="itmCatCheckAll"></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Date Added'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Part No.'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Name'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Type'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Serialized'); ?></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <?php 
                                    echo $this->Form->end(); 
                                    ?>
                                </div>
                            </div>
                            <div id="physicalInventory" class="tab-pane fade">
                                <div class="" style="margin-top:10px;">
                                    <?php
                                        echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmPhysicalInventory'));
                                    ?>
                                    <div class="mt5">
                                        <div class="col-xs-12">
                                            <div class="col-md-3 col-sm-5 col-xs-12 mb-5 ps-0">
                                                <input type="text" id="quantitesSearchItem" name="searchItem" class="form-control" placeholder="Search Quantities" style="border-radius: 5px">
                                                <div style="display: inline; position:absolute;right: 20px;top: 6px;color: darkgray">
                                                    <i class="fa fa-search"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-sm-7 col-xs-12 mb-5 ps-0">
                                                <select class="selectpicker" id="PhysicalInvFilterBy" name="sortBy">
                                                    <option value="1">Date Added</option>
                                                    <option value="2">Item Name</option>
                                                    <option value="3">Part Number</option>
                                                    <option value="4">Lot/Serial Number</option>
                                                    <option value="5">Location</option>
                                                    <option value="9">Status</option>
                                                    <option value="6">Quantity</option>
                                                    <option value="7">Expiration</option>
                                                    <option value="8">Cost</option>
                                                </select>  

                                                <a class="btn btn-default resetquantitiesfilterbtn">Clear</a>
                                                <a class="btn btn-primary" onclick="$('#physicalInventoryFilterModel').modal('show');">Filter</a>
                                                
                                            </div>
                                            
                                            <?php
                                            if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                                            ?>
                                            <div class="btn-group col-md-4 col-xs-12 mb-5 p-0" style="float:right;">
                                                <button id="removeallinventories" type="button" class="btn btn-danger" data-val='physical inventory items'>Remove All</button>&nbsp;
                                                <select class="selectpicker actionSel" id="invquantitiesaction">
                                                    <option value="">Action on Selected</option>
                                                    <option class="invquantitiesoptiondef" value="1" disabled>Remove</option>
                                                    <option class="invquantitiesoptiondef" value="7" disabled>Export</option>
                                                    <option class="invquantitiesoptiondef" value="2" disabled>Bulk Transfer</option>
                                                    <option class="invquantitiesoptiondef" value="3" disabled>Bulk Install</option>
                                                    <option class="invquantitiesoptiondef" value="4" disabled>Bulk Discard</option>
                                                </select>    
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="tableScrollable">

                                            <table id="datatablePhysicalInventory" class="table dataTable" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th class="check noExl"><input type="checkbox" name="air_check" id="invPhyCheckAll"></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Date Added'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Item Name'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Part Number'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Serial / Lot'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Location'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Qty.'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Expiration'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Cost'); ?></th>
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
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabs end -->
            </div>
        </div>
       
    </div>
</div>

<div id="physicalInventoryFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
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

<div id="itemCatalogFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
            echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmItemCatalogFilter'));
        ?>
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Filter</h4>
            </div>
            <div class="modal-body" style="height: auto;">
                <?php echo $this->element('InventoryFilter/item_catalog_filter'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default clearitemcatalogfilter">Clear</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary applyitemcatalogfilterbtn">Apply Filter</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>

<div id="removeAllConfirmModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="glyphicon glyphicon-check"></span>Confirm Remove All</h4>
            </div>
            <div class="modal-body removeallmsg" style="height: auto;"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default confirmremoveallsubmit">Yes</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<!--- inventory print barcode popup --->
<?php echo $this->element('InventoryPopup/inventory_printbarcode_popup'); ?>

<!----- include apply item catalog tag popup --------->
<?php echo $this->element('InventoryPopup/inventory_catalog_apply_tags'); ?>

<!-------------- include printbar code format popup ---------------------------->
<?php echo $this->element('InventoryPopup/item_catalog_printbarcode'); ?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<script>
var physicalInventoryListingURL = "<?php echo $this->Url->build(['controller'=>'HoldingBoxes', 'action'=>'ajaxPhysicalInventorySearch',]); ?>";
var itemCatalogListingURL = "<?php echo $this->Url->build(['controller'=>'HoldingBoxes', 'action'=>'ajaxInventoryItemsSearch']); ?>";
var removeAllHoldingBoxURL = "<?php echo $this->Url->build(['controller'=>'HoldingBoxes', 'action'=>'removeAllHoldingBox']); ?>";

var saveInventoryCatalogTagsURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'saveInventoryCatalogTags']); ?>";
var exportListingDataExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'exportInvItemListToExcel']); ?>";
var exportPhysicalInvListingDataExcelURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'exportInventoriesToExcel']); ?>";
var saveInventoryCatalogTagsURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'saveInventoryCatalogTags']); ?>";
var printInventoryCatalogBarCodeURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'printItemCatalogBarCode']); ?>";

var getInventoryItemPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'getInventoryItemPopupData']); ?>";

var ajaxOpenActionSelectPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'ajaxOpenActionSelectPopup']); ?>";
var addQuantitiesToHoldingBoxURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'addQuantitiesToHoldingBox']); ?>";
var bulkTransferURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkTransfer']); ?>";
var bulkDiscardURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkDiscard']); ?>";
var bulkInstallURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkInstall']); ?>";

var printBarCodeURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'printInventoryItemsBarCode']); ?>";
var printInventoriesListBarCodeURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'printInventoriesListBarCode']); ?>";
var inventoryItemDetURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'detail']); ?>";
var physicalInventoryDetURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'detail']); ?>";

var physicalInventoryEditURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'edit']); ?>";
var shippingOrderCreateURL = "<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'create']); ?>";
var repairOrderCreateURL = "<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'create']); ?>";
var physicalInventoryDiscardURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'discard']); ?>";
var physicalInventoryConsumeURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'consume']); ?>";
var physicalInventoryInstallURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'install']); ?>";
var physicalInventoryAdjustURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'adjust']); ?>";
var physicalInventoryTransferURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'transfer']); ?>";
var physicalInventoryUninstallURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'uninstall']); ?>";
var physicalInventoryErrorCorrectURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'error_correct']); ?>";
var updateInventoriesStatusURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'updateInventoriesStatus']); ?>";

$(document).ready(function () {
    
    var dataTablePhysicalInventory = $('#datatablePhysicalInventory').DataTable({
        'columnDefs': [
            { 'orderable': false, 'targets': '_all' },
            { className: "check noExl", "targets": [ 1 ] },
            { 'visible': false, 'targets': [1] }
        ],
        'order': [0, 'asc'],
        //"searching": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[<?php echo PAGINATION_LIMIT;?>, 50, 100, -1], [<?php echo PAGINATION_LIMIT;?>, 50, 100, "All"]],
        "lengthChange": false,
        "ajax":{
            url:"<?php echo $this->Url->build(['controller'=>'HoldingBoxes', 'action'=>'ajaxPhysicalInventorySearch']); ?>",
            accepts: 'application/json', 
            type: "post",
            error: function(){
                $(".employees-grid-error").html("");
                $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employees-grid_processing").css("display","none");
            }
        },
        "drawCallback": function (response) {
            var respobj = response.json;
            if(respobj.recordsTotal != undefined){
                $(".physicalinventorycount").html(respobj.recordsTotal);
            }
        },
    });

    $('#quantitesSearchItem').bind("keyup", function(){
        var formdata = $('#frmPhysicalInventory,#frmInventoryQuantitiesFilter').serialize();
        searchInventoryQantities(formdata);
    });

    $(document).on('click', ".resetquantitiesfilterbtn", function (e) {
        $("#quantitesSearchItem").val('');
        $("#showinactiveinv").prop('checked', false);

        dataTablePhysicalInventory.columns(0).search('').draw();
        dataTablePhysicalInventory.columns(1).search('').draw();

        $('#frmInventoryQuantitiesFilter')[0].reset();
        $('#frmPhysicalInventory')[0].reset();

        var formdata = '';
        searchInventoryQantities(formdata);
        $('.selectpicker').selectpicker('refresh');
    });

    //item catalog table data get

    var dataTableItemCatalog = $('#datatableItemCatalog').DataTable({
        'columnDefs': [
            { 'orderable': false, 'targets': '_all' },
            { className: "check noExl", "targets": [ 1 ] },
            { 'visible': false, 'targets': [1] }
        ],
        'order': [0, 'asc'],
        //"searching": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[<?php echo PAGINATION_LIMIT;?>, 50, 100, -1], [<?php echo PAGINATION_LIMIT;?>, 50, 100, "All"]],
        "lengthChange": false,
        "ajax":{
            url:"<?php echo $this->Url->build(['controller'=>'HoldingBoxes', 'action'=>'ajaxItemCatalogSearch']); ?>",
            accepts: 'application/json', 
            type: "post",
            error: function(){
                $(".employees-grid-error").html("");
                $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employees-grid_processing").css("display","none");
            }
        },
        "drawCallback": function (response) {
            var respobj = response.json;
            if(respobj.recordsTotal != undefined){
                $(".itemcatalogcount").html(respobj.recordsTotal);
            }
        },
    });

    $(document).on('click', ".resetquantitiesfilterbtn", function (e) {
        $("#itemCatalogSearchItem").val('');
        $("#showinactiveinv").prop('checked', false);

        dataTableItemCatalog.columns(0).search('').draw();
        dataTableItemCatalog.columns(1).search('').draw();

        $('#frmItemCatalog')[0].reset();
        $('#frmItemCatalogFilter')[0].reset();

        var formdata = '';
        searchItemCatalog(formdata);
        $('.selectpicker').selectpicker('refresh');
    });

    $(document).on('change', '#CatalogFilterBy', function (e) {
        dataTableItemCatalog.order([$(this).val(), 'asc']).draw();
    });

    $(document).on('change', '#PhysicalInvFilterBy', function (e) {
        dataTablePhysicalInventory.order([$(this).val(), 'asc']).draw();
    });

});
$(document).on('click', '.applyinvqtyfilterbtn', function(e){
    var formdata = $('#frmPhysicalInventory,#frmInventoryQuantitiesFilter').serialize();
    searchInventoryQantities(formdata);

    $("#physicalInventoryFilterModel").modal('hide');
});
$(document).on('click', '.clearinvitmquantitiesfilter', function(e){
    $('#frmInventoryQuantitiesFilter')[0].reset();
    $('#frmPhysicalInventory')[0].reset();

    $('.selectpicker').selectpicker('refresh');
    var formdata = '';
    searchInventoryQantities(formdata);
})
function searchInventoryQantities(formdata){
    var dataTablePhysicalInventory = $('#datatablePhysicalInventory').DataTable();
    dataTablePhysicalInventory.columns(2).search(formdata).draw();
}

//item catalog filter
$('#itemCatalogSearchItem').bind("keyup", function(){
    var formdata = $('#frmItemCatalog,#frmItemCatalogFilter').serialize();
    searchItemCatalog(formdata);
});

$(document).on('click', '.applyitemcatalogfilterbtn', function(e){
    var formdata = $('#frmItemCatalog,#frmItemCatalogFilter').serialize();
    searchItemCatalog(formdata);

    $("#itemCatalogFilterModel").modal('hide');
});
$(document).on('click', '.clearitemcatalogfilter', function(e){
    $('#frmItemCatalog')[0].reset();
    $('#frmItemCatalogFilter')[0].reset();

    $('.selectpicker').selectpicker('refresh');
    var formdata = '';
    searchItemCatalog(formdata);
})
function searchItemCatalog(formdata){
    var dataTableItemCatalog = $('#datatableItemCatalog').DataTable();
    dataTableItemCatalog.columns(2).search(formdata).draw();
}

$(document).on('click', '#datatableItemCatalog tbody td', function (e) {
    if ($(this).index() == 0 ) {
        return;
    }
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".itmCatChkBoxCls").val();
    if(values != undefined){
        window.location.href = inventoryItemDetURL+'/'+values;
    }
});

$(document).on('click', '#datatablePhysicalInventory tbody td', function (e) {
    if ($(this).index() == 0 ) {
        return;
    }
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".invPhyChkBoxCls").val();
    if(values != undefined){
        window.location.href = physicalInventoryDetURL+'/'+values;
    }
});

</script>
<?php 
echo $this->Html->script('holdingbox'); 
?>
