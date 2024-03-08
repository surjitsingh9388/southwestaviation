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
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryLocations', 'action'=>'ajaxInventoryLocationsSearch']); ?>";
    var printLocationBarCodeURL = "<?php echo $this->Url->build(['controller'=>'InventoryLocations', 'action'=>'printLocationSubLocationBarCode']); ?>";

    var pagelimit = <?php echo PAGINATION_LIMIT;?>;

    var pdfPagTitle = 'LOCATION';

    var exportListingDataExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryLocations', 'action'=>'exportLocationListToExcel']); ?>";

    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    if(hashes.length > 0){
        exportListingDataExcelURL += '?'+hashes[0]+'&';
    }else{
        exportListingDataExcelURL += '?1=1&';
    }

    $(document).on('click', '.invlocationtable tbody td', function (e) {
        if ($(this).index() == 0 ) {
            return;
        }
        var row = $(this).closest("tr");    // Find the row
        var values = row.find(".chkBoxCls").val();

        window.location.href = "<?php echo $this->Url->build(['controller'=>'InventoryLocations', 'action'=>'detail',]); ?>"+'/'+values;
    });
    
</script>

<?php 
echo $this->Html->css('inventory_location');

echo $this->Html->script('inventory_location'); 
echo $this->Html->script('inventory_common');
?>


<?php echo $this->Form->create('', ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Inventory Locations</h2>
            
            <div class="text-right">
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $sessionUser['id'] == 1) {
                echo $this->Html->link("Print", 'javascript:void(0);', array('class' => 'btn btn-default btnspace', 'id'=>'export-button-pdf', 'escape' => false));
                echo $this->Html->link("Export", 'javascript:void(0);', array('class' => 'btn btn-default btnspace exportListingDataExcel', 'escape' => false));
            }
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1) {
                echo $this->Html->link("Create", array('action' => 'create'), array('class' => 'btn btn-primary btnspace', 'escape' => false));
            }
            ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="action-bar dflex">
                <div class="input-group search-control mb-5">
                    <input id="searchItem" name="searchItem" type="text" class="form-control" placeholder="Search Locations">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div>
                </div>
                
                <div class="inputWrap btn-group sortWrap ml-10">
                    <label class="location-sort-by">Sort By</label>
                    <select class="selectpicker" id="FilterBy" name="sortBy">
                        <option value="">Sort By</option>
                        <option value="1" selected>Name</option>
                        <option value="2">Description</option>
                        <option value="3">Active Status</option>
                    </select>
                    <div class="ml-10">
                        <button type="button" class="btn ml-5 resetfilterbtn">Clear</button>
                        <button type="button" class="btn btn-primary btnOpenFilterPopup ml-5">Filter</button>
                    </div>
                </div>
                <?php if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $sessionUser['id'] == 1) { ?>
                <div class="split-btn pull-right actionMenu sortWrap label-width-auto">
                    <button type="button" class="btn-dropdown btn-default">Action on Selected<span class="selectCount"></span></button>
                    <button type="button" class="icon-part dropdown-toggle actionCls" data-toggle="dropdown">
                        <i class="fa fa-caret-down"></i>
                    </button>
                                           
                    <div class="dropdown-content dropdown-menu actionLinks">
                        <a href="javascript:void(0);" class="actionOnSelected printLocBarCodes action-link" onclick="$('#printBarcodesModel').modal('show');">Print Barcodes</a>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div class="tableScroll">
                <table id="datatableListingPage" class="table dataTable table2excel <?php if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $sessionUser['id'] == 1) { ?>invlocationtable <?php } ?>" width="100%">
                    <thead>
                        <tr>
                            <th class="check noExl valign-t"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                            <th class="valign-t" scope="col"><?php echo __('Name'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Description'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Active'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


<div id="popupFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <?php echo $this->element('InventoryFilter/inventory_location_filter'); ?>
</div>

<?php echo $this->Form->end(); ?>

<div id="printBarcodesModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Print Barcodes</h4>
            </div>
            <div class="modal-body" style="max-height: 500px;">
                <div class="page-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Print Options:</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <div class="form-check form-check-inline col-md-4 col-xs-12">
                                        <input class="form-check-input locationcheckbox" name="locationprintopt" type="checkbox" id="locationCheckbox" value="1">&nbsp;Location
                                    </div>
                                    <div class="form-check form-check-inline col-md-4 col-xs-12">
                                        <input class="form-check-input locationcheckbox" name="locationprintopt" type="checkbox" id="sublocationCheckbox" value="2">&nbsp;Sub Location
                                    </div>
                                    <div class="col-md-4 col-xs-12"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt5"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary printLocationBarcodes" disabled>Print</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>