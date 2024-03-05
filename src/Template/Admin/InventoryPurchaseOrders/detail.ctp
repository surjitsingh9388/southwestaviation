<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

echo $this->Html->css('inventory_purchase_order');
?>
<style>
    .exchange-status-indicator{
        float:right;
    }
</style>
<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($inventorypurchaseorders, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryPurchaseOrders', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper">
            <h2 class="heading">
                <?php 
                echo $this->Html->link('Purchase Orders', ['action' => 'index']).' / '.$inventorypurchaseorders->po_number.'&nbsp;'; 
                
                echo $statushtml;
                ?>
                
            </h2>
            <div class="btnWrap">
                <?php
                echo $this->Html->link("Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_delete'] == 1) || $sessionUser['id'] == 1) {
                    if($inventorypurchaseorders->status == '0'){
                        echo $this->Form->button('Activate', ['type' => 'button', 'class' => 'btn btn-default ml-10 changeinvpurchaseorders', 'status-val'=>'1']);
                    }else{
                        echo $this->Form->button('Deactivate', ['type' => 'button', 'class' => 'btn btn-danger ml-10 changeinvpurchaseorders', 'status-val'=>'0']);
                    }
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
                        if($inventorypurchaseorders->status == '1'){ ?>
                        <li>
                            <a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'edit', $inventorypurchaseorders->id]); ?>">Edit</a>
                        </li>
                        <?php if($inventorypurchaseorders->po_status == '0' || $inventorypurchaseorders->po_status == '2'){ ?>
                        <li>
                            <a class="dropdown-item changeinvpurchaseorders" href="javascript:void(0);" po_status="3" request-number="<?php echo $inventorypurchaseorders->po_number; ?>">Cancel Order</a>
                        </li>
                        <?php }if($inventorypurchaseorders->po_status == '0'){ ?>
                        <li>
                            <a class="dropdown-item changeinvpurchaseorders" href="javascript:void(0);" po_status="2" data-vendor="<?php echo !empty($vendors) ? 1 : 0; ?>">Send To Vendor</a>
                        </li>
                        <?php } ?>
                        <?php if($inventorypurchaseorders->po_status == '0' || $inventorypurchaseorders->po_status == '1' || $inventorypurchaseorders->po_status == '2'){ ?>
                        <li>
                            <a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'receive', $inventorypurchaseorders->id]); ?>">Receive</a>
                        </li>
                        <?php }else{ ?>
                        <li>
                            <a class="dropdown-item changeinvpurchaseorders" po_status="1" href="javascript:void(0);">Reopen</a>
                        </li>
                        <?php } if($inventorypurchaseorders->po_type == '2' && $inventorypurchaseorders->po_status == '2'){ ?>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" onclick="$('#updateCoreExchangeStatusModel').modal('show');">Update Exchange Status</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'create', '?'=>['po_id'=>$inventorypurchaseorders->id]]); ?>">Ship Core to Vendor</a>
                        </li>
                        <?php } ?>
                        <li>
                            <a class="dropdown-item linktoanotherorder" href="javascript:void(0);">Link to Another Order</a>
                        </li>
                        <?php } ?>
                        <li>
                            <a class="dropdown-item downloadInvDetailPagePdf" href="javascript:void(0);">Print</a>
                        </li>
                        <?php } ?>
                    </ul>
                    
                    <?php
                    if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    if($inventorypurchaseorders->status == '1'){
                        echo $this->Form->button('Save', ['type' => 'button', 'class' => 'btn btn-primary ml-10 invPOSaveBtn']);
                    }
                    }
                    ?>
                </div>
                
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="po-detail-block">
                    <div class="">
                        <div class="col-lg-4">

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Type</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <span title="Purchase order type">
                                            <?php 
                                            $invPurchaseOrderType = unserialize(INVENTORY_PURCHASE_TYPE);
                                            echo $invPurchaseOrderType[$inventorypurchaseorders->po_type]; 
                                            ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
			
                            <div class="form-group">
                                <label class="col-lg-4 control-label">PO Date</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static text-uppercase"><?php echo date("d-M-Y", strtotime($inventorypurchaseorders->po_date)); ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Reference</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static reference-white-space"><? echo $inventorypurchaseorders->reference; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Sales Person</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static"><? echo $inventorypurchaseorders->sales_person; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-4 control-label">Ship Via</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <?php
                                            if(!empty($inventorypurchaseorders->ship_via)){
                                                $shipviaarr = unserialize(SHIP_VIA);
                                                echo $shipviaarr[$inventorypurchaseorders->ship_via];
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
                                    <p class="form-control-static"><?php echo $inventorypurchaseorders->requestor; ?></p>
                                </div>
                            </div>

                            <div id="po-detail-vendor-section" class="form-group">
                                <label class="col-lg-4 control-label">Vendor</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static detshowbtn" data-val="vendor">
                                        <a><?php echo isset($vendors->name) ? $vendors->name : ''; ?></a>&nbsp;
                                        <?php
                                        if(!empty($inventorypurchaseorders->vendor)){
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
                            
                            <?php if(!empty($inventorypurchaseorders->request)){ ?>
                            <div id="po-detail-ship-to-section" class="form-group">
                                <label class="col-lg-4 control-label">Request</label>
                                <div class="col-lg-8">
                                    <p class="form-control-static">
                                        <a href="<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'detail', $inventorypurchaseorders->request]); ?>"><?php echo $request->request_number; ?></a>
                                    </p>
                                </div>
                            </div>
                            <?php } ?>
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
                            <li class="active"><a data-toggle="tab" href="#itemGeneral">Line Items</a></li>
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
                                <div class="g-0 bg-light position-relative">
                                    
                                    <table class="table upload-area" id="uploadfile">
                                        <thead class="thead-dark">
                                            <tr class="tblinvpo">
                                                <th></th>
                                                <th>#</th>
                                                <th class="col-sm-2">Status</th>
                                                <th class="col-sm-2">Tracking Number</th>
                                                <th class="col-sm-2">Item</th>
                                                <th class="col-sm-1">Location Needed</th>
                                                <th class="col-sm-2">ETA</th>
                                                <th class="col-sm-2">Quantity</th>
                                                <th class="col-sm-2">Received</th>
                                                <th class="col-sm-2">Cost/Unit (USD)</th>
                                                <th class="col-sm-2">Total (USD)</th>
                                                <th class="col-sm-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="invrequeststbl">
                                            <?php
                                            $defaultUOM = unserialize(DEFAULT_UOM);
                                            $currencyarr = unserialize(CURRENCY);
                                            $subTotalAmount = 0;
                                            $totalAmount = 0;
                                            foreach($inventorypoitems as $key=>$val){
                                                $subTotalAmount += $val['cost']*$val['qty'];
                                            ?>
                                            <tr>
                                                <td class="collapse-tr">
                                                    <div class="logbook-entry-line-item-children-toggle-column poitemtoggle">
                                                    <i id="po-detail-line-item-0-expansion-arrow" class="fa fa-caret-right invpoitemrec po-toggle-button"></i>
                                                    </div>
                                                    <input type="hidden" name="itemid[]" class="poitemid" value="<?php echo $val['id']; ?>">
                                                </td>
                                                <td>
                                                    <?php echo $key+1; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $statusmsg = '';
                                                    if(isset($invitmreceived[$val['id']]) && !empty($val['noninventory_item'])){
                                                        $statusmsg = 'Non-Inventory-Closed';
                                                    }else if(!isset($invitmreceived[$val['id']]) && !empty($val['noninventory_item'])){
                                                        $statusmsg = 'Non-Inventory';
                                                    }else if(isset($invitmreceived[$val['id']]) && !empty($val['inventory_item_id'])){
                                                        $statusmsg = 'Received';
                                                    }else if(!isset($invitmreceived[$val['id']]) && !empty($val['inventory_item_id'])){
                                                        $statusmsg = 'Pending Shipment';
                                                    }
                                                    echo $statusmsg; ?>
                                                    
                                                </td>
                                                <td>
                                                    <?php echo !empty($val['noninventory_item']) ? '' : $this->Form->Text('tracking_number[]', array('class' => 'form-control col-md-3 col-xs-12 etaDatepicker po-shipping-input', 'id' => 'tracking_number', 'placeholder' => '', 'label' => false, 'value'=>$val['tracking_number'])).'<div class="po-shipping"><a target="_new"> <i class="fa fa-truck fa-flip-horizontal"></i></a></div>'; ?>
                                                    <span class="input-group-addon hide-block">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php echo isset($val['invitms']['name']) ? '<a href="'.$this->Url->build(['controller'=>'InventoryItems', 'action'=>'detail', $val['inventory_item_id']]).'">'.$val['invitms']['name'].' ('.$val['invitms']['part_number'].')'.'</a>' : $val['noninventory_item']; ?>
                                                </td>
                                                <td>
                                                    <?php echo isset($val['invloc']['location_name']) ? $val['invloc']['location_name'] : ''; ?>
                                                </td>
                                                <td>
                                                    <?php echo isset($val['eta']) ? date("d-M-Y", strtotime($val['eta'])) : ''; ?>
                                                </td>
                                                <td>
                                                    <?php echo $val['qty'].' '.$defaultUOM[$val['uom']]; ?>
                                                </td>
                                                <td class="collapse-tr">
                                                    <span class="receivedtxt">
                                                        <a href="javascript:void(0);">
                                                            <?php
                                                            $received = isset($invitmreceived[$val['id']]) ? $invitmreceived[$val['id']] : 0;
                                                            echo $received.' '.$defaultUOM[$val['uom']]; 
                                                            ?>
                                                        </a>
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
                                                    <?php echo $val['cost'].' '.$currencyarr[$inventorypurchaseorders->currency]; ?>
                                                </td>
                                                <td>
                                                    <?php echo $val['cost']*$val['qty'].' '.$currencyarr[$inventorypurchaseorders->currency]; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {

                                                    if(!empty($val['noninventory_item'])){
                                                    ?>
                                                    <button type="button" class="btn btn-primary btn-sm <?php if(empty($val['received'])){ ?> receivedbtnclick <?php } ?>" id="po-detail-line-item-close-button-1" style="cursor: pointer;" <?php if(!empty($invitmreceived[$val['id']])){ ?> disabled <?php } ?> data-val="<?php echo $val['id']; ?>">Close</button>
                                                    <?php }else{ ?>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle" aria-expanded="false">Actions <span class="caret"></span></button>
                                                        <ul role="menu" class="dropdown-menu action-dropdown-menu">
                                                            <?php
                                                            if($statusmsg != 'Received'){       
                                                            ?>
                                                                <li><a class="dropdown-item received-txt" href="<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'receive', $val['inventory_po_id'], $val['id']]); ?>">Receive</a></li>
                                                            <?php }else{ ?>
                                                                <li class="disabled"><a class="dropdown-item received-txt" href="javascript:void(0);">Receive</a></li>
                                                            <?php } ?>
                                                            <?php
                                                            if($statusmsg == 'Received'){       
                                                            ?>
                                                            <li>
                                                                <a id="po-detail-line-item-adjust-cost-button-0 " class="adjustInvCost adjust-cost-txt" data-val="<?php echo $val['id']?>">
                                                                    Adjust Inventory Cost
                                                                </a>
                                                            </li>
                                                            <?php }else{ ?>
                                                            <li class="disabled">
                                                                <a id="po-detail-line-item-adjust-cost-button-0" class="adjust-cost-txt">
                                                                    Adjust Inventory Cost
                                                                </a>
                                                            </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                    <?php }} ?>
                                                </td>
                                            </tr>
                                                        
                                            <?php 
                                            if(isset($inventoryporeceivedarr[$val['id']]) && empty($val['noninventory_item'])){
                                            foreach($inventoryporeceivedarr[$val['id']] as $invrec){ ?>
                                                <tr class="overview-detail-<?php echo $val['id']; ?>" style="display:none;">
                                                    <td colspan="5"></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <label class="sub-header">Part Number</label>&nbsp;
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 serial-part-info-list sub-header-text">
                                                                <?php echo '<a href="'.$this->Url->build(['controller'=>'InventoryItems', 'action'=>'detail', $invrec['invitms']['id']]).'">'.$invrec['invitms']['part_number'].'</a>'; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <label class="sub-header">Serial</label>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 serial-part-info-list sub-header-text">
                                                                <?php echo '<a href="'.$this->Url->build(['controller'=>'Inventories', 'action'=>'detail', $invrec['inventory_id']]).'">'.$invrec['inv']['serial_no'].'</a>'; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td colspan="4">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <label class="sub-header">Location</label>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12 serial-part-info-list sub-header-text">
                                                            <?php echo '<a href="'.$this->Url->build(['controller'=>'InventoryLocations', 'action'=>'detail', $invrec['invloc']['id']]).'">'.$invrec['invloc']['location_name'].'</a>'; ?> <i class="fa fa-info-circle" title="Shelf 1"></i>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    
                                                </tr>
                                            <?php }}else if(empty($invitmreceived[$val['id']]) || !empty($val['noninventory_item'])){ ?>
                                                <tr class="overview-detail-<?php echo $val['id']; ?>" style="display:none;">
                                                    <td colspan="5"></td>
                                                    <td colspan="5"><em>No inventory has been received.</em></td>
                                                </tr>
                                            <?php } ?>

                                            <?php } ?>

                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="text-right"><i class="fa fa-info-circle" container="body" data-toggle="tooltip" data-html="true" rel="tooltip" placement="top" title="<div class=&quot;style=&quot;min-width:200px;&quot;>Full precision is used when calculating total value, but rounding is applied when displaying each line item total amount.</div>"></i>&nbsp; Subtotal</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right"><span><?php echo $subTotalAmount; ?></span>&nbsp;<span><?php echo $currencyarr[$inventorypurchaseorders->currency]; ?></span></td>
                                            </tr>
                                            <?php if(!empty($inventorypurchaseorders->po_tax_amount)){ ?>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="text-right">Tax</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">
                                                    <span><?php echo $inventorypurchaseorders->po_tax_amount; ?></span>&nbsp;
                                                    <span><?php echo $currencyarr[$inventorypurchaseorders->currency]; ?></span>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <?php if(!empty($inventorypurchaseorders->po_tax_percentage)){ ?>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="text-right">Tax %</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right"><span><?php echo $inventorypurchaseorders->po_tax_percentage; ?></span></td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="8"></td>
                                                <td class="text-right">Total</td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right"><span>
                                                    <?php 
                                                    $totalAmount = $subTotalAmount;
                                                    echo $totalAmount+$inventorypurchaseorders->po_tax_amount+$inventorypurchaseorders->po_shipping; ?></span>&nbsp;
                                                    <span><?php echo $currencyarr[$inventorypurchaseorders->currency]; ?></span></td>
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
                                                <th class="col-sm-1"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="filetbody">
                                            <?php if(empty($attachments)){ ?>
                                                <tr id="noattachmenttr">
                                                    <td colspan="5">No Attachments. Click 'Upload...' or drag and drop file to this area.</td>
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
                                                echo $this->Html->link($attachment['file_name'], '/inventoryitems/' . $attachment['file_name'],['download'=>$attachment['file_name']]);
                                                ?></td>
                                                <td><?php echo $attachment['file_size']; ?></td>
                                                <td><?php echo $attachment['created']; ?></td>
                                                <td><?php echo $attachment['uploaded_by']; ?></td>
                                                <td><i class="fa fa-times deleteattachment" title="Remove File" data-val="<?php echo $attachment['id']; ?>"></i></td>
                                            </tr>
                                            <?php }} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- attachment-tab-section end -->
                            <div id="itemHistory" class="tab-pane fade">
                                <div class="g-0 bg-light position-relative">
                                    
                                    <table class="table" id="invPOHistoryTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-2">Date</th>
                                                <th class="col-sm-2">User</th>
                                                <th class="col-sm-2">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach($inventorypohistories as $invhistory){
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

<div id="updateCoreExchangeStatusModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Update Core Exchange Status</h4>
            </div>

            <div class="modal-body" style="max-height: auto; overflow-y: auto;">
                <?php echo $this->Form->create('', ['url' =>['controller'=>'InventoryPurchaseOrders', 'action'=>'updatePOExchangeStatus'], 'id' => 'frmInventoryPOExchangeStatus', 'autocomplete'=>'off']); ?>
                <input type="hidden" name="inventory_po_id" value="<?php echo $inventorypurchaseorders->id; ?>">
                <div class="page-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Exchange Status&nbsp;<span class="required">*</span></label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                <?php 
                                    $invPOExchangeStatus = unserialize(INVENTORY_PURCHASE_EXCHANGE_STATUS);
                                    echo $this->Form->control('exchange_status', array('options' => $invPOExchangeStatus, 'empty' => '', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_exchange_status')); 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt5">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Notes</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <?php echo $this->Form->input('exchange_note', array('type' => 'textarea', 'class'=>'form-control col-md-10 col-xs-12', 'placeholder' => 'Optionally enter a note', 'label' => false, 'required' => 'required')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt5"></div>
                </div>
                <?php echo $this->Form->end(); ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary updateexchangestatusbtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<script> 
var updatePurchaseOrderStatusURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'updatePurchaseOrderStatus']); ?>";
var saveInventoryReceivedURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'saveInventoryReceived']); ?>";
var uploadInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'bulkInvPOAttachmentUpload']); ?>";
var deleteInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'deleteAttachment']); ?>";

var billingaddressobj = '<?php echo json_encode($inventorybillingaddress); ?>';
var shippingaddressobj = '<?php echo !empty($inventoryshippingaddress) ? json_encode($inventoryshippingaddress) : $inventoryshippingaddress; ?>';
var vendorobj = '<?php echo !empty($vendors) ? json_encode($vendors) : $vendors; ?>';
var inventoryItemsGenPDFURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'generateInvPODetPdf']); ?>";
var getInvAjustCostDataURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'getInvAjustCostData']); ?>";

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
echo $this->Html->script('inventory_purchase_order'); 
?>