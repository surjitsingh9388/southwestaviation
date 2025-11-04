<?php
echo $this->Html->css('inventory_item_list');

$sessionUser = $this->request->getSession()->read('Auth');; 
?>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

<script type="text/javascript">
    var inventoryitemdetURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'detail',]); ?>";
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'ajaxInventoryItemsSearch']); ?>";
    var printInventoryCatalogBarCodeURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'printItemCatalogBarCode']); ?>";
    var pagelimit = <?php echo PAGINATION_LIMIT;?>;
    var addToHoldingBoxURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'addToHoldingBox']); ?>";
    var saveInventoryCatalogTagsURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'saveInventoryCatalogTags']); ?>";
    var exportListingDataExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'exportInvItemListToExcel']); ?>";

    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    if(hashes.length > 0){
        exportListingDataExcelURL += '?'+hashes[0]+'&';
    }else{
        exportListingDataExcelURL += '?1=1&';
    }

    var pdfPagTitle = 'INVENTORY ITEM LIST REPORT';

</script>

<?php 
echo $this->Html->script('inventory_items'); 
echo $this->Html->script('inventory_common');
?>

<?php echo $this->Form->create(null, ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading">Item Catalog</h2>
            
            <div class="text-right">
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1) {
                echo $this->Html->link("Print", 'javascript:void(0);', array('class' => 'btn btn-default', 'id'=>'export-button-pdf', 'escape' => false));
                echo $this->Html->link("Export", 'javascript:void(0);', array('class' => 'btn btn-default exportListingDataExcel', 'escape' => false, 'data-val'=>'inventory_item_reports'));
                echo $this->Html->link("Import CSV", 'javascript:void(0);', array('class' => 'btn btn-default', 'escape' => false, 'data-val'=>'inventory_item_reports', 'onclick'=>"$('#importItemCatalogCSVModel').modal('show');"));
                echo $this->Html->link("Create", array('action' => 'create'), array('class' => 'btn btn-primary', 'escape' => false));
            }
            ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="action-bar dflex">
                <div class="input-group search-control mb-5">
                    <input id="searchItem" name="searchItem" type="text" class="form-control" placeholder="Search Item Catalog">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div>
                </div>
                
                <div class="inputWrap btn-group sortWrap ml-10">
                    <label class="invsearchby dNoneMOb">Sort By</label>
                    <select class="selectpicker" id="FilterBy" name="sortBy">
                        <option value="">Sort By</option>
                        <option value="1">Part Number</option>
                        <option value="2" selected>Name</option>
                        <option value="3">In Stock</option>
                        <option value="4">Out Right Cost</option>
                        <option value="5">Type</option>
                        <option value="6">Serialized</option>
                        <option value="7">Ordered</option>
                    </select>
                    <div class="ml-10">
                        <button type="button" class="btn ml-5 resetfilterbtn">Clear</button>
                        <button type="button" class="btn btn-primary btnOpenFilterPopup ml-5">Filter</button>
                    </div>
                </div>
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $sessionUser['id'] == 1) {
                ?>
                <div class="split-btn pull-right actionMenu sortWrap label-width-auto">
                    <button type="button" class="btn-dropdown btn-default">Action on Selected<span class="selectCount"></span></button>
                    <button type="button" class="icon-part dropdown-toggle actionCls" data-toggle="dropdown">
                        <i class="fa fa-caret-down"></i>
                    </button>
                                           
                    <div class="dropdown-content dropdown-menu actionLinks">
                        <a href="javascript:void(0);" class="actionOnSelected invitmdetaction" data-val="1">Add to Holding Box</a>
                        <a href="javascript:void(0);" class="actionOnSelected invitmdetaction" data-val="2">Apply Tags</a>
                        <a href="javascript:void(0);" class="actionOnSelected" onclick="$('#printCatalogBarcodesModel').modal('show');">Print Barcode</a>
                    </div>
                </div>
                <?php } ?>

                <!--div class="inputWrap btn-group sortWrap actionWrap" style="margin-left:10px;">
                    <select class="selectpicker invitmdetaction" id="actionSel">
                        <option value="">Action on Selected</option>
                        <option value="1" disabled>Add to Holding Box</option>
                        <option value="2" disabled>Apply Tags</option>
                    </select>
                </div-->
            </div>

            <div class="tableScroll">
                <table id="datatableListingPage" class="table dataTable table-striped table-bordered table-responsive table2excel <?php if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $sessionUser['id'] == 1) { ?>invitemtable <?php } ?>" width="100%">
                    <thead>
                        <tr>
                            <th class="check noExl"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                            <th class="valign-t" scope="col"><?php echo __('Part No.'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Name'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Out Right Cost'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Serialized'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Type'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Tags'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('In Stock'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Ordered'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="popupFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Filter</h4>
            </div>
            <div class="modal-body" style="height: auto;">
                <?php echo $this->element('InventoryFilter/item_catalog_filter'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default clearapplyfilter">Clear</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary applyfilterbtn">Apply Filter</button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>

<!----- include apply item catalog tag popup --------->
<?php echo $this->element('InventoryPopup/inventory_catalog_apply_tags'); ?>

<!-------------- include printbar code format popup ---------------------------->
<?php echo $this->element('InventoryPopup/item_catalog_printbarcode'); ?>

<div id="importItemCatalogCSVModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Import Item Catalog CSV</h4>
            </div>
            <div class="modal-body">
                <div  class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Select a CSV File
                            </label>
                            <div class="col-sm-9 form-label-input-wrapper">
                                <?php echo $this->Form->control('itemcatalogcsv', array('type'=>'file', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt5">
                     <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Download sample csv file</label>
                            <div class="col-sm-9 form-label-input-wrapper">
                                <a href="javascript:void(0);" onclick="" style="color:#092E6E; font-weight:bold;">Click here to Download sample csv file</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
                <button type="button" class="btn btn-primary uploadItemCatalogCSV">Upload</button>
            </div>
        </div>
    </div>
</div>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>