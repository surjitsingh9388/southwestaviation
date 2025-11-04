<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Customer Info <?php echo $inventorycustomers->customer_name; ?></h2>
        </div>
       
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="row ml0">
                    <div class="col-sm-12 mt10 navHeaderWrap">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default"><<</button>
                            <button type="button" class="btn btn-default"><</button>
                            <button type="button" class="btn btn-default">></button>
                            <button type="button" class="btn btn-default">>></button>
                            <button type="button" class="btn btn-default" onclick="$('#addNewCustomerModal').modal('show');">New</button>
                            <button type="button" class="btn btn-default fetchCustOTCPopup" data-val="customer_list">List View</button>
                            <button type="button" class="btn btn-default customerinfodelbtn">Delete Record</button>
                            <button type="button" class="btn btn-default">Preview</button>
                            <button type="button" class="btn btn-default">Print</button>
                        </div>
                    
                        <div class="goToBlock">
                            <label class="control-label col-md-4" for="reference">Go To</label>
                            <div>
                                <?php
                                echo $this->Form->control('go_to', array('options' => [], 'empty' => '', 'class' => 'form-control col-md-8 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'go_to'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="aircraftTabs" class="customerinfoTab">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a class="customerinfotab" data-toggle="tab" href="#customerInfo">Customer Info</a></li>
                            <li><a class="customerinfotab" data-toggle="tab" href="#aircraftInfo">Aircraft Info</a></li>
                            <li><a class="customerinfotab" data-toggle="tab" href="#customerFiles">Custom Fields</a></li>
                            <li><a class="customerinfotab" data-toggle="tab" href="#OTCInfo">OTC Info</a></li>
                            <li><a class="customerinfotab" data-toggle="tab" href="#repairOrderInfo">Repair Order Info</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="customerInfo" class="tab-pane fade in active">
                                <div class="page-content mt-35">
                                    <div class="formBGCls">
                                        <?php echo $this->element('Inventory/customer_otc/create_new_customer'); ?>
                                    </div>
                                </div>
                            </div>
                            <div id="aircraftInfo" class="tab-pane fade">
                                <div class="page-content mt-35">
                                    <div class="formBGCls" id="customerotcaircraftblock">
                                        <?php echo $this->element('Inventory/customer_otc/customer_aircraft_info_add'); ?>
                                    </div>
                                </div>
                            </div>
                            <div id="customerFiles" class="tab-pane fade">
                                <div class="page-content mt-35">
                                    <div class="formBGCls"></div>
                                </div>
                            </div>
                            <div id="OTCInfo" class="tab-pane fade">
                                <div class="page-content mt-35">
                                    <div class="formBGCls">
                                        <?php echo $this->element('Inventory/customer_otc/customer_otc_info_add'); ?>
                                    </div>
                                </div>
                            </div>
                            <div id="repairOrderInfo" class="tab-pane fade">
                                <?php echo $this->element('Inventory/customer_otc/customer_repair_order_info_add'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!------------------- create otc invoice popup ------------------------>
<?php //echo $this->element('InventoryPopup/customer_otc/create_otc_invoice'); ?> 

<!------------------- create otc invoice popup ------------------------>
<?php //echo $this->element('InventoryPopup/customer_otc/customer_otc_add_part'); ?>

<!------------------- create otc invoice popup ------------------------>
<?php echo $this->element('InventoryPopup/customer_otc/create_new_customer'); ?>

<!------------------- upload media popup ------------------------>
<?php echo $this->element('InventoryPopup/customer_otc/inventory_customer_upload_media'); ?>


<!-- Add Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Add Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div id="customerotcpopup"></div>

<script>
var uploadInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'bulkMediaUpload']); ?>";
var getInventoryItemByIdURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getInvItemsWithInventoriesList']); ?>";
var fetchCustomerOTCPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'fetchCustomerOTCPopup']); ?>";
var getAdditionalShippingAddressURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getAdditionalShippingAddress']); ?>";
var saveCustomerInfoNotesURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveCustomerInfoNotes']); ?>";
var saveAddlShippingAddressURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAdditionalShippingAddress']); ?>";
var saveCustomerInfoMediaURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveCustomerInfoMedia']); ?>";
var deleteInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteCustomerInfoMedia']); ?>";
var customerInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'customerinfo']); ?>";
var deleteAddlShippingAddressURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteAddlShippingAddress']); ?>";
var getCustomerShippingAddressURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getCustomerShippingAddress']); ?>";
var deleteCustomerOTCURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteCustomerOTC']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>

<?php
echo $this->Html->css('inventory_customer_otc');
echo $this->Html->script('inventory_common');
echo $this->Html->script('inventory_customers');
?>