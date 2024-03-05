<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

if(!empty($part->plane_id) && !empty($subResults)) {
    $pdfCheck = 'style="pointer-events: auto;"';
} else {
    $pdfCheck = 'style="pointer-events: none;"';
}
?>
<style>
    
    .mb-3 {
        margin-bottom: 5px !important;
    }

    h5{
        text-align:center;
        font-weight:bold;
    }
    
    th {
        background-color: #2c3e50;
        color: white;
    }

    .addPageHeading {
        font-size: 11pt;
        background-color: #1f2a5e;
        color: #ecf0f1;
        padding: 10px 15px;
        margin-top: 0;
        margin-left: 0px;
        height: 40px;
        font-weight:400;
    }

    .col-sm-3{
        width: 15%;
    }

    .document-name{
        text-align:left;
    }

    .document-name a{
        color: #337ab7 !important;
    }

    #invrequeststbl a{
        color: #092E6E !important;
        text-decoration:none;
    }

    #invrequeststbl{
        text-align: left;
    }
</style>

<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($InventoryShippingOrders, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryShippingOrderReceive', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link('Shipping Orders', ['action' => 'index']).' / '.$this->Html->link($InventoryShippingOrders->shipping_order_number, ['action' => 'detail', $InventoryShippingOrders->id]).' / Receive'; ?></h2>
            
            <div class="btnWrap">
                <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                <button id="shipping-order-receive-button" type="button" class="btn btn-primary">Receive</button>
            </div>
        </div>
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="" style="padding-top:10px;padding-left: 10px;">
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

                        </div>
                        
                        <div class="col-lg-4">
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label">From Address&nbsp;<span class="required">*</span></label>
                                <div class="col-lg-8">
                                    <div id="from_addressblock" style="display:none;">
                                        <?php
                                        echo $this->Form->control('from_address', array('options' => $from_address, 'empty' => 'Select Address...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'required' => 'required', 'id' => 'from_address'));
                                        ?>
                                    </div>
                                    <div id="from_addressdet">
                                        <p class="form-control-static">
                                            <?php echo $inventorybillingaddress->name; ?>
                                        </p>
                                        <p><?php echo $inventorybillingaddress->street1; ?></p>
                                        <p>
                                            <?php
                                            $address = $inventorybillingaddress->city.', ';
                                            if(!empty($inventorybillingaddress->state)){
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
                                <button type="button" class="btn btn-link changerecaddressbtn" style="position: absolute; right: 15px; top: 0px; cursor: pointer;" data-val="fromaddr">Change</button>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-lg-4 control-label">To Address&nbsp;<span class="required">*</span></label>
                                <div class="col-lg-8">
                                    <div id="to_addressblock" style="display:none;">
                                        <?php
                                        echo $this->Form->control('to_address', array('options' => $to_address, 'empty' => 'Select Address...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'required' => 'required', 'id' => 'to_address'));
                                        ?>
                                    </div>
                                    <div id="to_addressdet">
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
                                <button type="button" class="btn btn-link changerecaddressbtn" style="position: absolute; right: 15px; top: 0px; cursor: pointer;" data-val="toaddr">Change</button>
                            </div>
                        </div>
                    </div>

                </div>

                <div style="clear: both;"></div>

                <!-- Tabs Start -->
                <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemGeneral">Line Items</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemGeneral" class="tab-pane fade in active"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative">
                                    
                                    <table class="table upload-area" id="uploadfile">
                                        <thead class="thead-dark">
                                            <tr class="tblinvpo">
                                                <th class="col-sm-3">Item</th>
                                                <th class="col-sm-2">Shipped</th>
                                                <th class="col-sm-2">In Transit</th>
                                                <th class="col-sm-2" style="width:7%;">Received</th>
                                                <th class="col-sm-2" style="width:12%;">Location</th>
                                                <th class="col-sm-3" style="width:12%;">Account Code</th>
                                            </tr>
                                        </thead>
                                        <tbody id="invrequeststbl">
                                            <?php
                                            $defaultUOM = unserialize(DEFAULT_UOM);
                                            $currencyarr = unserialize(CURRENCY);
                                            foreach($InventoryShippingOrderItems as $key=>$val){
                                            ?>
                                            
                                                <tr>
                                                    <td>
                                                        <?php
                                                        if($val['status'] != '4'){
                                                        ?>
                                                        <input type="hidden" name="itemid[]" value="<?php echo $val['id']; ?>">
                                                        <?php } ?>
                                                        <?php echo isset($val['invitms']['name']) ? '<a href="'.$this->Url->build(['controller'=>'Inventories', 'action'=>'detail', $val['inv']['id']]).'">'.$val['invitms']['name'].' (PN: '.$val['invitms']['part_number'].') (SN: '.$val['inv']['serial_no'].')</a>' : $val['noninventory_item']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $val['qty'].' EA'; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $val['qty']-$val['received'].' EA'; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $disabled = ''; 
                                                        if($val['status'] == '4'){
                                                            $disabled = 'disabled';
                                                        }
                                                        echo $this->Form->Text('received[]', array('class' => 'form-control col-md-3 col-xs-12 received', 'placeholder' => '', 'label' => false, 'style'=>'width: 83%;display: inline-block;', 'id'=>'received', 'disabled'=>$disabled)); ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if(!empty($val['noninventory_item'])){
                                                            $location = [];
                                                        }
                                                        echo $this->Form->control('location_id[]', array('options' => $location, 'empty' => 'Enter a location ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker location_id', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'location_id', 'required' => 'required', 'disabled'=>'disabled')); 
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $this->Form->control('account_code[]', array('options' => '', 'empty' => 'Select account code...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker account_code', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code', 'value'=>'', 'disabled'=>'disabled'));  ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6">
                                                        <?php echo $this->Form->control('notes[]', array('class' => 'form-control col-md-8 col-xs-12 notes', 'label'=> false, 'rows'=>2, 'placeholder'=>'Notes', 'disabled'=>'disabled')); ?>
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


<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<script>
var getInventoryAdressDetailURL = "<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'getInventoryAddressDetails']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>

<?php
echo $this->Html->script('inventory_common'); 
echo $this->Html->script('inventory_shipping_order'); 
echo $this->Html->script('inventory_purchase_order'); 
?>