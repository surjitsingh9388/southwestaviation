
<?php
echo $this->Form->create($InventoryShippingOrders, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryShippingOrders', 'autocomplete'=>'off'));
?> 
<div class="addPartBorder pt10">
    <div class="row">
         <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Number&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('shipping_order_number', array('class'=>'form-control ', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'shipping_order_number', 'value'=>$so_number)); ?>
                </div>
            </div>

            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Reference</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('reference', array('class'=>'form-control ', 'placeholder' => '', 'label' => false, 'id'=>'reference')); ?>
                </div>
            </div>

            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Order Date&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="input-group date datePicker" style="width:100%">
                        <?php 
                        $shipping_order_date = date('m-d-Y');
                        echo $this->Form->Text('shipping_order_date', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'shipping_order_date', 'placeholder' => '', 'label' => false, 'required' => 'required', 'value'=>$shipping_order_date)); 
                        ?>
                        <span class="input-group-addon hide-block">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Currency&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $currencyarr = unserialize(CURRENCY);
                    $currency = !empty($InventoryShippingOrders->currency) ? $InventoryShippingOrders->currency : '1';
                    echo $this->Form->control('currency', array('options' => $currencyarr, 'empty' => 'Enter a currency ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_currency', 'required' => 'required', 'value'=>$currency)); 
                    ?>
                </div>
            </div>

            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Account Code</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Select account code...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code', 'value'=>''));  ?>
                </div>
            </div>
        </div>

       <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Requestor</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('requestor', array('class'=>'form-control ', 'placeholder' => '', 'label' => false, 'value'=>$userData['full_name'], 'required' => 'required')); ?>
                </div>
            </div>

            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Shipper&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('shipper', array('class'=>'form-control ', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>

            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Ship Via&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $shipviaarr = unserialize(SHIP_VIA);

                    echo $this->Form->control('ship_via', array('options' => $shipviaarr, 'empty' => 'Select a shipment method...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'required' => 'required')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="from_address">
                    Ship From&nbsp;<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-10" style="padding-right:0px;">
                    <?= $this->Form->control('from_address', [
                        'options' => $from_address,
                        'empty' => 'Select Address...',
                        'class' => 'form-control selectpicker',
                        'data-show-subtext' => true,
                        'data-live-search' => true,
                        'label' => false,
                        'required' => 'required',
                        'id' => 'from_address'
                    ]); ?>
                </div>

                <div class="col-md-2 col-sm-2 col-xs-2 plus-new-btn" style="margin-top:0px; padding-left: 0px;">
                    <button type="button" class="btn btn-primary addnewinvaddresspopup" data-val="shipping">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>

            
        </div>

          <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Destination&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $shippingOrderDestination = unserialize(SHIPPING_ORDER_DESTINATION);

                    echo $this->Form->control('destination', array('options' => $shippingOrderDestination, 'empty' => 'Select destination', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'required' => 'required')); ?>
                </div>
            </div>
            
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Attention&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('attention', array('class'=>'form-control ', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>

            <div class="form-group vendorblock">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="vendor">
                    Vendor&nbsp;<span class="required">*</span>
                </label>

                <div class="col-md-6 col-sm-6 col-xs-10" style="padding-right:0px;">
                    <?= $this->Form->control('vendor', [
                        'options' => $vendor,
                        'empty' => 'Select Vendor...',
                        'class' => 'form-control selectpicker',
                        'data-show-subtext' => true,
                        'data-live-search' => true,
                        'label' => false,
                        'id' => 'vendor',
                        'required' => 'required'
                    ]); ?>
                </div>

                <div class="col-md-2 col-sm-2 col-xs-2 plus-new-btn" style="margin-top:0px; padding-left: 0px;">
                    <button type="button" class="btn btn-primary vendorModelbtn">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>


            <div class="form-group toaddressblock hide-block"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">To Address&nbsp;<span class="required">*</span></label>
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <?php
                    echo $this->Form->control('to_address', array('options' => $to_address, 'empty' => 'Select Address...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'to_address')); 
                    ?>
                    <div class="col-md-1 col-sm-1 col-xs-12 plus-new-btn"><button class="btn btn-primary addnewinvaddresspopup" type="button" data-val="shipping"><i class="fa fa-plus"></i></button></div>
                </div>
            </div>

            <div class="form-group thirdpartyblock hide-block">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Description&nbsp;<span class="required">*</span></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                            <?php 
                            echo $this->Form->control('description', array('type'=>'textbox', 'class'=>'form-control col-md-8 col-xs-12 mf_name', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'thirdpartydescription'));
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Street 1&nbsp;<span class="required">*</span></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                            <?php 
                            echo $this->Form->control('street1', array('class'=>'form-control col-md-8 col-xs-12 mf_name', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'thirdpartyaddressstreet1'));
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Street 2</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                            <?php 
                            echo $this->Form->control('street2', array('class'=>'form-control col-md-8 col-xs-12 mf_name', 'placeholder' => '', 'label' => false));
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Street 3</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php 
                                echo $this->Form->control('street3', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">City&nbsp;<span class="required">*</span></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php 
                                echo $this->Form->control('city', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'thirdpartyaddresscity'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <?php
                        $stateblock = 1;
                        if(empty($inventoryaddresses->country) || $inventoryaddresses->country != '231'){
                            $stateblock = 0;
                        }
                        ?>
                        <div class="form-group thirdpartyaddressprovinceblock" <?php if(!empty($stateblock)){ ?>style="display:none;"<?php } ?>> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Province&nbsp;<span class="required">*</span></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php 
                                echo $this->Form->control('province', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'thirdpartyaddressprovince'));
                                ?>
                            </div>
                        </div>
                        
                        <div class="form-group thirdpartyaddressstateblock" <?php if(empty($stateblock)){ ?>style="display:none;"<?php } ?>> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">State&nbsp;<span class="required">*</span></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php 
                                echo $this->Form->control('state', array('options' => $states, 'empty' => 'Select a state...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'thirdpartyaddressstate'));
                                ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Postal&nbsp;<span class="required">*</span></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php 
                                echo $this->Form->input('postal', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'thirdpartyaddresspostal'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Country&nbsp;<span class="required">*</span></label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php 
                                echo $this->Form->control('country', array('options' => $countries, 'empty' => 'Select a country...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'thirdpartyaddresscountry', 'required' => 'required')); 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
       <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group"> 
                <label class="control-label  col-md-3 col-sm-3 col-xs-12 special-instruction-label" for="plane_id">Special Instructions</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <?php echo $this->Form->input('special_instructions', array('type' => 'textarea', 'class'=>'form-control ', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
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
            <li class="active"><a data-toggle="tab" href="#itemGeneral">Line Items</a></li>
            <li><a data-toggle="tab" href="#itemAttachment">Attachments <span class="count_circle inventory_attachment_count">0</span></a></li>
            <li><a data-toggle="tab" href="#itemHistory">History</a></li>
        </ul>
        <div class="tab-content">
            <div id="itemGeneral" class="tab-pane fade in active"><!-- general-tab-section start -->
                <div class="g-0 bg-light position-relative tableScroll">
                    
                    <table class="table opinvitemtable">
                        <thead class="thead-dark">
                            <tr class="tblinvro">
                                <th></th>
                                <th class="col-sm-1">#</th>
                                <th class="col-sm-3">Part (Name, Part #, Serial)</th>
                                <th class="col-sm-3">Qty Requested</th>
                                <th class="col-sm-3">Shipping Cost/Unit (<span class="pocurrency"><?php echo $currencyarr[$currency]; ?></span>)</th>
                                <th class="col-sm-3">Shipping Cost (<span class="pocurrency"><?php echo $currencyarr[$currency]; ?></span>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalAmount = '0.00';
                            $subTotalAmount = '0.00';
                            $shipping_order_tax_percentage = 0;
                            $shipping_order_tax_amount = '0.00';
                            $shipping_order_additional_fees = '0.00';

                            $totalAmount = $subTotalAmount+$shipping_order_tax_amount+$shipping_order_additional_fees;
                            
                            foreach($InventoryShippingOrderItems as $key=>$invreqitem){
                                echo $this->element("Inventory/inventory_shipping_order_item_add", array('invreqitem'=>$invreqitem, 'srcount'=>$key+1));
                            }

                            ?>
                            <tr id="invrequeststbl">
                                <td colspan="6">
                                    <a class="btn btn-primary addinvpoitem" data-val="invitem"><i class="fa fa-plus-circle"></i> Add Inventory Item</a>
                                    <a class="btn btn-primary addinvpoitem" data-val="noninvitem"><i class="fa fa-plus-circle"></i> Add Non-Inventory Item</a>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="3" class="text-right"><i class="fa fa-info-circle" container="body" data-toggle="tooltip" data-html="true" rel="tooltip" placement="top" title="<div class=&quot;style=&quot;min-width:200px;&quot;>Full precision is used when calculating total value, but rounding is applied when displaying each line item total amount.</div>"></i>&nbsp; Subtotal</td>
                                <td class="text-right"><span class="posubtotal"><?php echo $subTotalAmount; ?></span>&nbsp;<span class="pocurrency"><?php echo $currencyarr[$currency]; ?></span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="3">
                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id" >Tax &nbsp;&nbsp;<input type="radio" name="shipping_order_tax" class="potaxcal" value="1" checked="checked"> %</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $this->Form->input('shipping_order_tax_percentage', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>$shipping_order_tax_percentage, 'id'=>'po-tax-percentage')); ?>
                                            </div>
                                        </div>    
                                    </div>

                                    <div class="col-md-6 pr-0">
                                        <div class="form-group"> 
                                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id" ><input type="radio" name="shipping_order_tax" class="potaxcal" value="0"> $</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12 pr-0">
                                                <?php echo $this->Form->input('shipping_order_tax_amount', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'disabled'=>'disabled', 'value'=>$shipping_order_tax_amount, 'id'=>'po-tax-amount')); ?>
                                            </div>
                                        </div> 
                                        
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="3" class="text-right">Additional Fees</td>
                                <td>
                                    <?php echo $this->Form->input('shipping_order_additional_fees', array('type'=>'number', 'class'=>'form-control col-md-6 col-xs-6', 'placeholder' => '', 'label' => false, 'value'=>$shipping_order_additional_fees, 'id'=>'po-shipping')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="4" class="text-right">
                                    <b class="prorder-total">Total</b>
                                    <b class="totalpoamount"><?php echo $totalAmount; ?></b>&nbsp;<span class="pocurrency"><?php echo $currencyarr[$currency]; ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><!-- general-tab-section end -->

            <div id="itemAttachment" class="tab-pane fade"><!-- attachment-tab-section start -->
                <div class="g-0 bg-light position-relative">
                    <div class="">
                        <div class="search-control attachemtment-search-block">
                            <input type="text" class="form-control" placeholder="Search Attachments">
                            <div class="attachemnt-search-icon">
                                <i class="fa fa-search"></i>
                            </div>
                        </div>

                        <div class="pull-right">
                            <input type="file" name="files[]" id="inventoryattachment" class="hide-block" multiple />
                            <button class="btn btn-primary pull-right" type="button" onclick="$('#inventoryattachment').trigger('click'); return false;">Upload</button>
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
                            <tr id="noattachmenttr">
                                <td colspan="5">No Attachments. Click 'Upload...' or drag and drop file to this area.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><!-- attachment-tab-section end -->
            
            <div id="itemHistory" class="tab-pane fade"><!--history-tab-section start -->
                <div class="g-0 bg-light position-relative">
                    <table class="table upload-area tblinvpo">
                        <thead class="thead-dark">
                            <tr class="tblinvpo">
                                <th class="col-sm-3">Date</th>
                                <th class="col-sm-3">User</th>
                                <th class="col-sm-3">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5">No history found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><!-- attachment-tab-section end -->
        </div>
    </div>
</div>
<?php 
echo $this->Form->end(); 
?>