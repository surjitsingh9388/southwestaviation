<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;

?>

<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($InventoryShippingOrders, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryShippingOrders', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading">
                <?php 
                echo $this->Html->link('Shipping Orders', ['action' => 'index']).' / '.$InventoryShippingOrders->shipping_order_number.'&nbsp;'; 
                
                echo $statushtml;
                ?>
                
            </h2>
            <div class="btnWrap mb-5">
                <?php
                echo $this->Html->link("Back", 'javascript:history.go(-1);', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Html->link('Edit', ['action' => 'edit', $InventoryShippingOrders->id], ['class'=>'btn btn-primary']);
                }
                ?>

                <?php
                if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $sessionUser['id'] == 1) {
                ?>
                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                    <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php if($InventoryShippingOrders->shipping_order_status == '0'){ ?>
                        <li>
                            <a class="dropdown-item changeinvshippingorders" href="javascript:void(0);" shipping_order_status="3" request-number="<?php echo $InventoryShippingOrders->shipping_order_number; ?>">Cancel Order</a>
                        </li>
                        <?php }if($InventoryShippingOrders->shipping_order_status == '4'){ ?>
                        <li>
                            <a class="dropdown-item changeinvshippingorders" shipping_order_status="1" href="javascript:void(0);">Reopen</a>
                        </li>
                        <?php }
                        if($InventoryShippingOrders->shipping_order_status == '0'){ ?>
                        <li>
                            <a class="dropdown-item changeinvshippingorders" href="javascript:void(0);" shipping_order_status="2" data-vendor="<?php echo !empty($vendors) ? 1 : 0; ?>">Ship</a>
                        </li>
                        <?php } ?>
                        <?php 
                        $allitemreceived = 0;
                        $itemcount = 0;
                        foreach($InventoryShippingOrderItems as $itemreceived){
                            if($itemreceived->status == '4'){
                                $allitemreceived++;
                            }
                            $itemcount++;
                        }
                        if(($InventoryShippingOrders->shipping_order_status == '3' || $InventoryShippingOrders->shipping_order_status == '1' || $InventoryShippingOrders->shipping_order_status == '2') && $allitemreceived != $itemcount){ ?>
                        <li>
                            <a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'receive', $InventoryShippingOrders->id]); ?>">Receive</a>
                        </li>
                        <?php } ?>
                        
                        <li>
                            <a class="dropdown-item invShippingSaveBtn" href="javascript:void(0);">Save</a>
                        </li>
                        <li>
                            <a class="dropdown-item downloadInvDetailPagePdf" href="javascript:void(0);">Print</a>
                        </li>
                    </ul>
                </div>
                <?php } ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="pt10 pl10">
                    <div class="">
                        <div class="col-lg-4">
			
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Reference</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static text-uppercase"><?php echo $InventoryShippingOrders->reference; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Order Date</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static"><?php echo date("d-M-Y", strtotime($InventoryShippingOrders->shipping_order_date)); ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Shipping Cost</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static reference-white-space"><?php echo $InventoryShippingOrders->shipping_order_cost_total; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Currency</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <?php
                                            $currencyarr = unserialize(CURRENCY);
                                            if(!empty($InventoryShippingOrders->currency)){
                                                echo $currencyarr[$InventoryShippingOrders->currency];
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
			
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Requestor</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <?php echo $InventoryShippingOrders->requestor; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Shipper</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <?php echo $InventoryShippingOrders->shipper; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Ship Via</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <?php
                                            if(!empty($InventoryShippingOrders->ship_via)){
                                                $shipviaarr = unserialize(SHIP_VIA);
                                                echo $shipviaarr[$InventoryShippingOrders->ship_via];
                                            }
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">From Address</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <?php echo $inventorybillingaddress->name; ?>
                                    </p>
                                    <p><?php echo $inventorybillingaddress->street1; ?></p>
                                    <p>
                                        <?php
                                        $address = $inventorybillingaddress->city.', ';
                                        if(!empty($states[$inventorybillingaddress->state])){
                                            $address .= $states[$inventorybillingaddress->state];
                                        }else{
                                            $address .= $inventorybillingaddress->province;
                                        }
                                        echo $address.' '.$inventorybillingaddress->postal_code; 
                                        ?>
                                    </p>
                                    <p><?php echo $countries[$inventorybillingaddress->country]; ?></p>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Destination</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <?php 
                                        $shippingOrderDestination = unserialize(SHIPPING_ORDER_DESTINATION);
                                        echo $shippingOrderDestination[$InventoryShippingOrders->destination]; 
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Attention</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <?php
                                        echo $InventoryShippingOrders->attention; 
                                        ?>
                                    </p>
                                </div>
                            </div>
                            
                            <?php if(!empty($InventoryShippingOrders->vendor)){ ?>
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Vendor</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <?php
                                        echo $vendors->name; 
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">To Address</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <?php echo $InventoryShippingOrders->description; ?>
                                    </p>
                                    <p><?php echo $InventoryShippingOrders->street1; ?></p>
                                    <p>
                                        <?php
                                        $address = $InventoryShippingOrders->city.', ';
                                        if(!empty($InventoryShippingOrders->state)){
                                            $address .= $states[$InventoryShippingOrders->state];
                                        }else{
                                            $address .= $InventoryShippingOrders->province;
                                        }
                                        echo $address.' '.$InventoryShippingOrders->postal_code; 
                                        ?>
                                    </p>
                                    <p><?php echo $countries[$InventoryShippingOrders->country]; ?></p>
                                </div>
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
                            <li><a data-toggle="tab" href="#itemsAttachments">Attachments <span class="count_circle inventory_attachment_count"><?php echo count($attachments); ?></span></a></li>
                            <li><a data-toggle="tab" href="#itemHistory">History</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemGeneral" class="tab-pane fade in active"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative tableScroll">
                                    
                                    <table class="table">
                                        <thead class="thead-dark">
                                            <tr class="tblinvpo">
                                                <th>#</th>
                                                <th class="col-sm-2">Status</th>
                                                <th class="col-sm-2">Tracking Number</th>
                                                <th class="col-sm-3">Item</th>
                                                <th class="col-sm-2 quantity-th">Quantity</th>
                                                <th class="col-sm-2 received-th">Received</th>
                                                <th class="col-sm-2 cost-th">Cost/Unit (USD)</th>
                                                <th class="col-sm-3">Total (USD)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="invrequeststbl">
                                            <?php
                                            $defaultUOM = unserialize(DEFAULT_UOM);
                                            $currencyarr = unserialize(CURRENCY);
                                            $subTotalAmount = 0;
                                            $totalAmount = 0;
                                            foreach($InventoryShippingOrderItems as $key=>$val){
                                                $subTotalAmount += $val['cost']*$val['qty'];
                                                
                                            ?>
                                            
                                                <tr>
                                                    <td>
                                                        <?php echo $key+1; ?>
                                                        <input type="hidden" name="itemid[]" value="<?php echo $val['id']; ?>">
                                                    </td>
                                                    <td>
                                                        <?php
                                                        
                                                        if($val['status'] == '1'){
                                                            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status so-partial">Partial Received</div>';
                                                        }else if($val['status'] == '0'){
                                                            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status so-pending">Pending</div>';
                                                        }else if($val['status'] == '3'){
                                                            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status so-cancelled">Canceled</div>';
                                                        }else if($val['status'] == '2'){
                                                            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status so-shipped">Shipped</div>';
                                                        }else if($val['status'] == '4'){
                                                            $statushtml = '<div data-html="true" placement="left" class="inventory_request_status so-received">Received</div>';
                                                        }
                                                        echo $statushtml;
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $this->Form->Text('tracking_number[]', array('class' => 'form-control col-md-3 col-xs-12 etaDatepicker po-shipping-input', 'id' => 'tracking_number', 'placeholder' => '', 'label' => false, 'value'=>$val['tracking_number'])); ?>
                                                        <span class="input-group-addon hide-block">
                                                            <span class="glyphicon glyphicon-calendar"></span>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php echo isset($val['invitms']['name']) ? '<a href="'.$this->Url->build(['controller'=>'Inventories', 'action'=>'detail', $val['inv']['id']]).'" class="shipping_order_item_link">'.$val['invitms']['name'].' ('.$val['invitms']['part_number'].') ('.$val['inv']['serial_no'].')</a>' : $val['noninventory_item']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $val['qty'].' '.(!empty($val['inv']['uom']) ? $defaultUOM[$val['inv']['uom']] : 'Each'); ?>
                                                    </td>
                                                    <td>
                                                        <span class="receivedtxt">
                                                            <?php
                                                            echo $val['received'].' '.(!empty($val['inv']['uom']) ? $defaultUOM[$val['inv']['uom']] : 'Each'); 
                                                            ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php echo $val['cost'].' '.$currencyarr[$InventoryShippingOrders->currency]; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $val['cost']*$val['qty'].' '.$currencyarr[$InventoryShippingOrders->currency]; ?>
                                                    </td>
                                                </tr>
                                            
                                            <?php } ?>

                                            <tr>
                                                <td colspan="4"></td>
                                                <td class="text-right"><i class="fa fa-info-circle" container="body" data-toggle="tooltip" data-html="true" rel="tooltip" placement="top" title="<div class=&quot;style=&quot;min-width:200px;&quot;>Full precision is used when calculating total value, but rounding is applied when displaying each line item total amount.</div>"></i>&nbsp; Subtotal</td>
                                                <td></td>
                                                <td></td>
                                                <td class=""><span><?php echo $subTotalAmount; ?></span>&nbsp;<span><?php echo $currencyarr[$InventoryShippingOrders->currency]; ?></span></td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="4"></td>
                                                <td class="text-right">Tax</td>
                                                <td></td>
                                                <td></td>
                                                <td class="">
                                                    <span><?php echo $InventoryShippingOrders->shipping_order_tax_amount; ?></span>&nbsp;
                                                    <span><?php echo $currencyarr[$InventoryShippingOrders->currency]; ?></span>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="4"></td>
                                                <td class="text-right">Tax %</td>
                                                <td></td>
                                                <td></td>
                                                <td class="">
                                                    <span><?php echo $InventoryShippingOrders->shipping_order_tax_percentage; ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"></td>
                                                <td class="text-right">Additional Fees</td>
                                                <td></td>
                                                <td></td>
                                                <td class="">
                                                    <span><?php echo $InventoryShippingOrders->shipping_order_additional_fees; ?></span>&nbsp;
                                                    <span><?php echo $currencyarr[$InventoryShippingOrders->currency]; ?></span>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td colspan="4"></td>
                                                <td class="text-right">Total</td>
                                                <td></td>
                                                <td></td>
                                                <td class=""><span>
                                                    <?php 
                                                    $totalAmount = $subTotalAmount;
                                                    echo $totalAmount+$InventoryShippingOrders->shipping_order_tax_amount+$InventoryShippingOrders->shipping_order_additional_fees; ?></span>&nbsp;
                                                    <span><?php echo $currencyarr[$InventoryShippingOrders->currency]; ?></span>
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
                                                echo $this->Html->link($attachment['file_name'], '/inventoryshippingorderitems/' . $attachment['file_name'],['download'=>$attachment['file_name']]);
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
                                    
                                    <table class="table" id="invSOHistoryTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-2">Date</th>
                                                <th class="col-sm-2">User</th>
                                                <th class="col-sm-2">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach($inventorysohistories as $invhistory){
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

<!---- include linked order popup ------>
<?php
echo $this->element('InventoryPopup/linked_other_order_popup');
?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<script> 
var updateShippingOrderStatusURL = "<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'updateShippingOrderStatus']); ?>";
var saveInventoryReceivedURL = "<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'saveInventoryReceived']); ?>";

var inventoryItemsGenPDFURL = "<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'generateInvShippingDetPdf']); ?>";

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
echo $this->Html->script('inventory_common'); 
echo $this->Html->script('inventory_shipping_order'); 
echo $this->Html->script('inventory_purchase_order'); 
?>