<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

?>
<style>
    .form-group a, .list-group-item a {
        color: #092E6E !important;
        text-decoration: none;
        cursor:pointer;
        background-color: transparent;
    } {
        color: #092E6E;
        text-decoration: none;
        cursor:pointer;
        background-color: transparent;
    }
</style>
<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($InventoryRepairOrders, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryRepairOrders', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading">
                <?php 
                echo $this->Html->link('Repair Orders', ['action' => 'index']).' / '.$InventoryRepairOrders->ro_number.'&nbsp;'; 
                
                echo $statushtml;
                ?>
                
            </h2>
            <div class="btnWrap mb-5">
                <?php
                echo $this->Html->link("Back", 'javascript:history.go(-1);', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    if($InventoryRepairOrders->status == '2'){
                        //echo $this->Form->button('Activate', ['type' => 'button', 'class' => 'btn btn-default ml-10 changeinvrepairorders', 'status-val'=>'1']);
                    }else{
                        //echo $this->Form->button('Deactivate', ['type' => 'button', 'class' => 'btn btn-danger ml-10 changeinvrepairorders', 'status-val'=>'2']);
                    }
                    echo $this->Html->link('Edit', ['action' => 'edit', $InventoryRepairOrders->id], ['class'=>'btn btn-primary']);
                }
                ?>

                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                    <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php 
                        if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                        if($InventoryRepairOrders->ro_status == '0' || $InventoryRepairOrders->ro_status == '2'){ 
                        ?>
                        <li>
                            <a class="dropdown-item changeinvrepairorders" href="javascript:void(0);" ro_status="3" request-number="<?php echo $InventoryRepairOrders->ro_number; ?>">Cancel Order</a>
                        </li>
                        <?php }if($InventoryRepairOrders->ro_status == '4' || $InventoryRepairOrders->ro_status == '3'){ ?>
                        <li>
                            <a class="dropdown-item changeinvrepairorders" ro_status="1" href="javascript:void(0);">Reopen</a>
                        </li>
                        <?php }if($InventoryRepairOrders->ro_status == '0'){ ?>
                        <li>
                            <a class="dropdown-item changeinvrepairorders" href="javascript:void(0);" ro_status="2" data-vendor="<?php echo !empty($vendors) ? 1 : 0; ?>">Send To Vendor</a>
                        </li>
                        <?php } ?>
                        <?php if(empty($isallitemreceived) && ($InventoryRepairOrders->ro_status == '1' || $InventoryRepairOrders->ro_status == '2') && !empty($is_inventory_ro_exist)){ ?>
                        <li>
                            <a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'receive', $InventoryRepairOrders->id]); ?>">Receive</a>
                        </li>
                        <?php } ?>
                        
                        <li>
                            <a class="dropdown-item invROSaveBtn" href="javascript:void(0);">Save</a>
                        </li>
                        <li>
                            <a class="dropdown-item downloadInvDetailPagePdf" href="javascript:void(0);">Print</a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="pt10 pl10">
                    <div class="">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-lg-4 control-label">RO Date</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static text-uppercase"><?php echo date("d-M-Y", strtotime($InventoryRepairOrders->ro_date)); ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Requestor</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static"><?php echo $InventoryRepairOrders->requestor; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Reference</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static reference-white-space"><? echo $InventoryRepairOrders->reference; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Contact</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static"><? echo $InventoryRepairOrders->contact; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Ship Via</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <?php
                                            if(!empty($InventoryRepairOrders->ship_via)){
                                                $shipviaarr = unserialize(SHIP_VIA);
                                                echo $shipviaarr[$InventoryRepairOrders->ship_via];
                                            } 
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <div id="po-detail-account-code-section" class="form-group">
                                <label class="col-lg-4 control-label">Account Code</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static"> &nbsp;</p>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div id="po-detail-vendor-section" class="form-group">
                                <label class="col-lg-4 control-label">Vendor</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static detshowbtn" data-val="vendor">
                                        <a><?php echo isset($vendors->name) ? $vendors->name : ''; ?></a>&nbsp;
                                        <?php
                                        if(!empty($InventoryRepairOrders->vendor)){
                                        ?>
                                        <i class="fa fa-long-arrow-right"></i>
                                        <?php } ?>
                                    </p>
                                </div>
                            </div>

                            <div id="po-detail-bill-to-section" class="form-group">
                                <label class="col-lg-4 control-label">Bill To</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static detshowbtn" data-val="billing">
                                        <a><?php echo $inventorybillingaddress->name; ?></a>
                                    </p>
                                </div>
                            </div>

                            <div id="po-detail-ship-to-section" class="form-group">
                                <label class="col-lg-4 control-label">Ship To</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static detshowbtn" data-val="shipping">
                                        <a><?php echo isset($inventoryshippingaddress->name) ? $inventoryshippingaddress->name : ''; ?></a>
                                    </p>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-4 vendor-address-box">
                            <div class="vendordispblock">
                                <label>Company</label>&nbsp;<span id="company"><?php echo isset($vendors->name) ? $vendors->name : ''; ?></span><br>
                                <label>Contact</label> &nbsp;<span><span id="contact"><?php echo isset($vendors->firstname) ? $vendors->firstname : ''; ?></span>
                            </div>
                            <div class="addressdispblock hide-block">
                                <label>Name</label>&nbsp;<span id="name"><?php echo isset($vendors->name) ? $vendors->name : ''; ?></span> <br>
                            </div>

                            <label>Address 1</label>&nbsp; <span id="street1"><?php echo isset($vendors->street1) ? $vendors->street1 : ''; ?></span><br>
                            <label>Address 2</label>&nbsp; <span id="street2"><?php echo isset($vendors->street2) ? $vendors->street2 : ''; ?></span><br>
                            <label>City &amp; State</label>&nbsp; <span id="city_state"><?php echo isset($vendors->city) ? $vendors->city : ''; ?></span> <br>
                            <label>Postal</label>&nbsp; <span id="postal"><?php echo isset($vendors->postal) ? $vendors->postal : ''; ?></span><br>

                            <div class="vendordispblock">
                                <label>Phone</label>&nbsp; <span id="phone"><?php echo isset($vendors->primaryphone) ? $vendors->primaryphone : ''; ?></span><br>
                                <label>Fax</label>&nbsp; <span id="fax"><?php echo isset($vendors->fax) ? $vendors->fax : ''; ?></span>
                            </div>
                        </div>
                    </div>

                </div>

                <div style="clear: both;"></div>

                <!-- Tabs Start -->
                <div id="aircraftTabs" class="tab-pad">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemGeneral">Inventory for Repair</a></li>
                            <li>
                                <a data-toggle="tab" href="#itemsLinkedOrder">Linked Order 
                                    <span class="linkordercountblock"><?php echo ($linkorderdata['linkedpurchaseorderscount']+$linkorderdata['linkedrepairorderscount']+$linkorderdata['linkedshippingorderscount']+$linkorderdata['linkedrequestscount']); ?></span>
                                </a>
                            </li>
                            <li><a data-toggle="tab" href="#itemsAttachments">Attachments</a></li>
                            <li><a data-toggle="tab" href="#itemHistory">History</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemGeneral" class="tab-pane fade in active"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative tableScroll">
                                    <table class="table upload-area" id="uploadfile">
                                        <thead class="thead-dark">
                                            <tr class="tblinvpo">
                                                <th></th>
                                                <th>#</th>
                                                <th class="col-sm-2">Status</th>
                                                <th class="col-sm-2">Tracking Number</th>
                                                <th class="col-sm-2">Serial, Name, Part No.</th>
                                                <th class="col-sm-2">ETA</th>
                                                <th class="col-sm-1">Quantity</th>
                                                <th class="col-sm-1">Received</th>
                                                <th class="col-sm-2 th-cost">Cost/Unit (USD)</th>
                                                <th class="col-sm-2">Total (USD)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="invrequeststbl">
                                            <?php
                                            $defaultUOM = unserialize(DEFAULT_UOM);
                                            $currencyarr = unserialize(CURRENCY);
                                            $subTotalAmount = 0;
                                            $totalAmount = 0;
                                            foreach($InventoryROItems as $key=>$val){
                                                $subTotalAmount += $val['cost']*$val['qty'];
                                                $statusmsg = '';

                                                if(isset($invitmreceived[$val['id']]) && !empty($val['noninventory_item'])){
                                                    $statusmsg = 'Non-Inventory-Closed';
                                                }else if(!isset($invitmreceived[$val['id']]) && !empty($val['noninventory_item'])){
                                                    $statusmsg = 'Non-Inventory';
                                                }else if(isset($invitmreceived[$val['id']]) && !empty($val['inventory_id'])){
                                                    $statusmsg = 'Received';
                                                }else if(!isset($invitmreceived[$val['id']]) && !empty($val['inventory_id'])){
                                                    if($InventoryRepairOrders->ro_status != '0'){
                                                        $statusmsg = 'Shipped to Vendor';
                                                    }else{
                                                        $statusmsg = 'Pending Shipment';
                                                    }
                                                }
                                                        
                                            ?>
                                            
                                                <tr>
                                                    <td>
                                                        <?php
                                                        if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {

                                                        if(($InventoryRepairOrders->ro_status == '1' || $InventoryRepairOrders->ro_status == '2') && $statusmsg == 'Shipped to Vendor'){       
                                                        ?>
                                                            <a class="btn btn-default" href="<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'receive', $val['inventory_ro_id'], $val['id']]); ?>">Receive</a>
                                                        <?php }else if($statusmsg == 'Received'){ ?>
                                                            <a class="btn btn-default" href="javascript:void(0);" disabled="disabled">Receive</a>
                                                        <?php } ?>
                                                        
                                                        <?php
                                                        if($statusmsg == 'Non-Inventory-Closed'){       
                                                        ?>
                                                            <a class="btn btn-default" href="javascript:void(0);" disabled="disabled">Close</a>
                                                        <?php }else if(($InventoryRepairOrders->ro_status == '1' || $InventoryRepairOrders->ro_status == '2') && $statusmsg == 'Non-Inventory'){ ?>
                                                            <a class="btn btn-default receivedbtnclick" href="javascript:void(0);" data-val="<?php echo $val['id']; ?>">Close</a>
                                                        <?php }} ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $key+1; ?>
                                                        <input type="hidden" name="itemid[]" value="<?php echo $val['id']; ?>">
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo $statusmsg; 
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $this->Form->Text('tracking_number[]', array('class' => 'form-control col-md-3 col-xs-12 etaDatepicker po-shipping-input', 'id' => 'tracking_number', 'placeholder' => '', 'label' => false, 'value'=>$val['tracking_number'])); ?>
                                                        <span class="input-group-addon hide-block">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($val['invitms']['name']) ? '<a href="'.$this->Url->build(['controller'=>'Inventories', 'action'=>'detail', $val['inv']['id']]).'" class="inventory-select-item">'.$val['invitms']['name'].' ('.$val['invitms']['part_number'].') ('.$val['inv']['serial_no'].')</a>' : $val['noninventory_item']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($val['eta']) ? date("d-M-Y", strtotime($val['eta'])) : ''; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $val['qty'].' EA'; ?>
                                                    </td>
                                                    <td>
                                                        <span class="receivedtxt">
                                                            <?php
                                                            $received = isset($invitmreceived[$val['id']]) ? $invitmreceived[$val['id']] : 0;
                                                            echo $received.' EA'; 
                                                            ?>
                                                        </span>
                                                        <?php             
                                                            if(!empty($val['noninventory_item']) && empty($invitmreceived[$val['id']])){
                                                            ?>
                                                            <span class="receivedinput hide-block">
                                                                <?php echo $this->Form->Text('received', array('type'=>'number', 'class' => 'form-control col-md-3 col-xs-12 etaDatepicker received', 'id' => 'received', 'placeholder' => '', 'label' => false, 'value'=>$val['received'])); ?>
                                                                <span class="input-group-addon hide-block">
                                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                                </span>
                                                            </span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $val['cost'].' '.$currencyarr[$InventoryRepairOrders->currency]; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $val['cost']*$val['qty'].' '.$currencyarr[$InventoryRepairOrders->currency]; ?>
                                                    </td>
                                                </tr>
                                            
                                            <?php } ?>

                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-right"><i class="fa fa-info-circle" container="body" data-toggle="tooltip" data-html="true" rel="tooltip" placement="top" title="<div class=&quot;style=&quot;min-width:200px;&quot;>Full precision is used when calculating total value, but rounding is applied when displaying each line item total amount.</div>"></i>&nbsp; Subtotal</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right"><span><?php echo $subTotalAmount; ?></span>&nbsp;<span><?php echo $currencyarr[$InventoryRepairOrders->currency]; ?></span></td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-right">Tax</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">
                                                    <span><?php echo $InventoryRepairOrders->ro_tax_amount; ?></span>&nbsp;
                                                    <span><?php echo $currencyarr[$InventoryRepairOrders->currency]; ?></span>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-right">Tax %</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">
                                                    <span><?php echo $InventoryRepairOrders->ro_tax_percentage; ?></span>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-right">Total</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right"><span>
                                                    <?php 
                                                    $totalAmount = $subTotalAmount;
                                                    echo $totalAmount+$InventoryRepairOrders->ro_tax_amount+$InventoryRepairOrders->ro_shipping; ?></span>&nbsp;
                                                    <span><?php echo $currencyarr[$InventoryRepairOrders->currency]; ?></span>
                                                </td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- general-tab-section end -->
                            <div id="itemsLinkedOrder" class="tab-pane fade">
                                <?php echo $this->element('Inventory/linked_order_list', array('sessionUser'=>$sessionUser)); ?>
                            </div><!-- general-tab-section end -->
                            <div id="itemsAttachments" class="tab-pane fade"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative">
                                    <div class="mt10">
                                        <div class="search-control attachemtment-search-block">
                                            <input type="text" id="attachmentSearch" class="form-control" placeholder="Search Attachments">
                                            <div class="attachemnt-search-icon">
                                                <i class="fa fa-search"></i>
                                            </div>
                                        </div>

                                        <div class="pull-right">
                                            <button class="btn btn-primary pull-right" type="button" disabled="disabled">Upload</button>
                                        </div>
                                    </div>

                                    <table class="table upload-area tblinvpo" id="uploadfile">
                                        <thead class="thead-dark">
                                            <tr class="tblinvpo">
                                                <th class="col-sm-2">File Name</th>
                                                <th class="col-sm-1">Size</th>
                                                <th class="col-sm-2">Uploaded</th>
                                                <th class="col-sm-2">Uploaded By</th>
                                            </tr>
                                        </thead>
                                        <tbody id="filetbody">
                                            <?php if(empty($attachments)){ ?>
                                                <tr id="noattachmenttr">
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
                                            <tr class="text-left">
                                                <td class="document-name"><span class="document-management-icon <?php echo $iconcss; ?>"></span>
                                                <?php
                                                echo $this->Html->link($attachment['file_name'], '/inventoryroitems/' . $attachment['file_name'],['download'=>$attachment['file_name']]);
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
                            <div id="itemHistory" class="tab-pane fade">
                                <div class="g-0 bg-light position-relative tableScroll">
                                    
                                    <table class="table" id="invROHistoryTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-2">Date</th>
                                                <th class="col-sm-2">User</th>
                                                <th class="col-sm-2">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach($inventoryrohistories as $invhistory){
                                                $data = @unserialize($invhistory['description']);
                                                if ($data === false) {
                                                    $data = $invhistory['description'];
                                                }else{
                                                    $data = json_encode($data, JSON_PRETTY_PRINT);
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo date('d-M-Y h:i A', strtotime($invhistory['created'])); ?></td>
                                                <td><?php echo $invhistory['users']['email']; ?></td>
                                                <td>
                                                    <a class="toggleplusminus"><i class="fa fa-plus"></i></a>
                                                    <?php echo $invhistory['title']; ?>
                                                    <pre class="history-detail-block hide-block"><?php echo $data;?>
                                                    </pre>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- general-tab-section end -->
                        </div>
                    </div>
                </div>
                <!-- Tabs end -->
            </div>
        </div>
    <?php 
    echo $this->Form->end(); 
    ?>
    </div>
</div>

<div id="adjustInvCostModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Inventory Adjustment Confirmation</h4>
            </div>
            <div class="modal-body invadjustcostdata" style="max-height: 500px; overflow-y: auto;">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary invAdjustConfirmBtn" disabled>Confirm</button>
            </div>
        </div>
    </div>
</div>

<!---- include linked order popup ------>
<?php
echo $this->element('InventoryPopup/linked_other_order_popup');
?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<script> 
var updateRepairOrderStatusURL = "<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'updateRepairOrderStatus']); ?>";
var saveInventoryReceivedURL = "<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'saveInventoryReceived']); ?>";
var uploadInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'bulkInvROAttachmentUpload']); ?>";
var deleteInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'deleteAttachment']); ?>";

var billingaddressobj = '<?php echo json_encode($inventorybillingaddress); ?>';
var shippingaddressobj = '<?php echo !empty($inventoryshippingaddress) ? json_encode($inventoryshippingaddress) : $inventoryshippingaddress; ?>';
var vendorobj = '<?php echo !empty($vendors) ? json_encode($vendors) : $vendors; ?>';
var inventoryItemsGenPDFURL = "<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'generateInvRODetPdf']); ?>";
var getInvAjustCostDataURL = "<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'getInvAjustCostData']); ?>";

var createPurchaseOrderURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'create']); ?>";
var createRepairOrderURL = "<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'create']); ?>";
var createShippingOrderURL = "<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'create']); ?>";
var createRequestURL = "<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'create']); ?>";
var inventoryExistOrderDropDownURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'inventoryExistOrderDropDown']); ?>";
var inventoryExistOrderLinkSaveURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'existingOrderLinkSave']); ?>";
var inventoryUnlinkExistOrderURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'unlinkExistingOrder']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>

<?php
echo $this->Html->css('inventory_repair_order');
echo $this->Html->script('inventory_common'); 
echo $this->Html->script('inventory_repair_order'); 
echo $this->Html->script('inventory_purchase_order'); 
?>