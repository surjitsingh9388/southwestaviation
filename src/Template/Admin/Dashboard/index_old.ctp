<?php
echo $this->Html->css('inventory_item_list');

$sessionUser = $this->request->session()->read('Auth.User'); 
?>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script type="text/javascript">
    var inventoryitemdetURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'detail',]); ?>";
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'ajaxInventoryItemsSearch']); ?>";
    var pagelimit = 5;
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

<?php echo $this->Form->create('', ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Dashboard</h2>
        </div>
        
        <div class="page-content mt-35">
            <h6>Item Catalog</h6>
            <div class="action-bar dflex">
                <div class="input-group search-control mb-0">
                    <input id="searchItem" name="searchItem" type="text" class="form-control" placeholder="Search Item Catalog">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div>
                </div>
                
                <div class="inputWrap btn-group sortWrap ml-10">
                    <label class="invsearchby">Sort By</label>
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
            </div>

            <div class="tableScroll" style="min-height:300px !important;">
                <table id="datatableListingPage" class="table dataTable table2excel <?php if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $sessionUser['id'] == 1) { ?>invitemtable <?php } ?>" width="100%">
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
        <div class="col-md-12" style="background-color:#fff;">
            <div class="col-md-6 pd0" style="padding-right:5px !important;">
                <div class="dashboard_heading_bar"><span class="dashboard_heading">Load a Work Order or Work Order Quote</span></div>
                <div class="col-md-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Listing</legend>

                        <div class="col-md-6">
                            <button type="button" class="btn btn-default col-md-12 load-open-work-orders" data-val="open_work_order">Open Work Orders</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default col-md-12 load-open-work-orders" data-val="all_work_order">All Work Orders</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default col-md-12">Open W/O Quotes</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default col-md-12">All W/O Quotes</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default col-md-12 load-warranty-claims-work-orders" data-val="open_warranty_claim_work_order">Open Warr. Claims</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default col-md-12 load-warranty-claims-work-orders" data-val="all_warranty_claim_work_order">All Warr. Claims</button>
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-12">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">Create a new</legend>
                        
                        <div class="col-md-12">
                            <button type="button" class="btn btn-default col-md-12" onclick="$('#woCreateNewWOModal').modal('show');">Work Order</button>
                        </div>

                        <div class="col-md-12">
                            <button type="button" class="btn btn-default col-md-12">Work Order Quotes</button>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-6 pd0">
                <div class="dashboard_heading_bar"><span class="dashboard_heading">Active Time Clock Users</span></div>
                <div class="btnWrapper" style="display:flow-root !important;">
                    <div class="float-right">
                        <button type="button" class="btn btn-default fetchUserTimeClockPopup" data-val="time_clock_log">Time Clocks Log</button>
                    </div>
                </div>
    
                <div class="page-content mt-35">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th id="toolName">Employee <i class="fa fa-fw fa-sort"></i></th>
                                    <th id="toolDescription">Hours <i class="fa fa-fw fa-sort"></i></th>
                                    <th id="toolModel">Time Login <i class="fa fa-fw fa-sort"></i></th>
                                </tr>
                            </thead>
                            <tbody id="inventoryToolsList">
                                <?php
                                foreach($usertimeclocklist as $timeclock){
                                ?>
                                <tr>
                                    <td><?php echo $timeclock['full_name']; ?></td>
                                    <td><?php echo $timeclock['totaltime']; ?></td>
                                    <td><?php echo date('h:i A', strtotime($timeclock['in_time'])); ?></td>
                                </tr>
                                <?php } ?>
                            </thead>
                        </table>
                    </div>
                </div>
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
<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<div id="customerotcpopup"></div>

<?php 
echo $this->element('InventoryPopup/customer_otc/load_create_new_work_order');
echo $this->element('Inventory/customer_otc/aircraft_work_order_ajax_url'); 
?>

<script>
var loadAircraftWorkOrderPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'loadAircraftWorkOrderPopup']); ?>";

var fetchCustomerOTCPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'fetchCustomerOTCPopup']); ?>";
</script>

<?php
echo $this->Html->css('inventory_customer_otc');
echo $this->Html->script('inventory_customers');
echo $this->Html->css('user_time_clock'); 
?>
<script type="text/javascript">
    var loadTimeClockForDateURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'loadTimeClockForDate',]); ?>";
    var saveUserTimeClockURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'saveUserTimeClock',]); ?>";
    var getUserTimeClockDetailURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'getUserTimeClockDetail',]); ?>";
    var timeClockLogReportsPdfURL = "<?php echo $this->Url->build(['controller'=>'UserTimeClocks', 'action'=>'timeClockLogReportsPdf',]); ?>";
</script>