<style>
    .plusbtn{
        margin-top: -28px;
        padding-left: 6px;
        float:right;
    }
</style>
<?php
echo $this->Form->create($inventorypurchaseorders, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryPurchaseOrders', 'autocomplete'=>'off'));
?> 
<div class="addPartBorder" style="padding-top:10px;">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group"> 
                <?php
                $potypedisabled = '';
                if(!empty($po_type)){
                    $potypedisabled = ' disabled=>disabled';
                }
                ?>
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Type&nbsp;<span class="required">*</span>
                    <i class="fa fa-info-circle" data-toggle="tooltip" data-html="true" title="<div class=&quot;text-left purchase-order-form-tooltip&quot;>Standard - Standard Purchase Order <br><br>Exchange - Core Exchange Order <br> <br><strong>Note:</strong> Purchase order must be in Draft with all line items removed to change the type."></i>
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $invPurchaseOrderType = unserialize(INVENTORY_PURCHASE_TYPE);
                    echo $this->Form->control('po_type', array('options' => $invPurchaseOrderType, 'empty' => 'Select type...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_type', 'required' => 'required', 'value'=>$po_type, $potypedisabled));  ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <?php echo $this->Form->control('request', array('type'=>'hidden','class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'request', 'value'=>$porequestid)); ?>
        </div>

        <div class="col-md-4">
            
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Number&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('po_number', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'po_number')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">PO Date&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="input-group date datePicker">
                        <?php 
                        $po_date = !empty($inventorypurchaseorders->po_date) ? date('m-d-Y', strtotime($inventorypurchaseorders->po_date)) : date('m-d-Y');
                        echo $this->Form->Text('po_date', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'po_date', 'placeholder' => '', 'label' => false, 'required' => 'required', 'value'=>$po_date)); ?>
                        <span class="input-group-addon" style="display:none;">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Currency&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $currencyarr = unserialize(CURRENCY);
                    $currency = !empty($inventorypurchaseorders->currency) ? $inventorypurchaseorders->currency : '1';
                    echo $this->Form->control('currency', array('options' => $currencyarr, 'empty' => 'Enter a currency ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_currency', 'required' => 'required', 'value'=>$currency)); 
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Bill To&nbsp;<span class="required">*</span></label>
                
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <?php
                    echo $this->Form->control('bill_to_address', array('options' => $billingaddress, 'empty' => 'Select Address...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'required' => 'required', 'id' => 'bill_to_address'));
                    ?>
                    <div class="col-md-1 col-sm-1 col-xs-12 plusbtn"><button class="btn btn-primary addnewinvaddresspopup" style="height:34px;" type="button" data-val="billing"><i class="fa fa-plus"></i></button></div>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Ship To</label>
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <?php
                    echo $this->Form->control('ship_to_address', array('options' => $shippingaddress, 'empty' => 'Select Address...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'ship_to_address')); 
                    ?>
                    <div class="col-md-1 col-sm-1 col-xs-12 plusbtn"><button class="btn btn-primary addnewinvaddresspopup" style="height:34px;" type="button" data-val="shipping"><i class="fa fa-plus"></i></button></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Account Code</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Select account code...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code'));  ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Requestor</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('requestor', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>$userData['full_name'])); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Vendor</label>
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <?php
                    echo $this->Form->control('vendor', array('options' => $vendor, 'empty' => 'Select Vendor...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'vendor')); 
                    ?>
                    <div class="col-md-1 col-sm-1 col-xs-12 plusbtn"><button class="btn btn-primary vendorModelbtn" style="height:34px;" type="button"><i class="fa fa-plus"></i></button></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Reference</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('reference', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Sales Person</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('sales_person', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Ship Via</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $shipviaarr = unserialize(SHIP_VIA);

                    echo $this->Form->control('ship_via', array('options' => $shipviaarr, 'empty' => 'Select a shipment method...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'manufacturer')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="plane_id" style="width:125px !important;">Special Instructions</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <?php echo $this->Form->input('special_instructions', array('type' => 'textarea', 'class'=>'form-control col-md-10 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                </div>
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
            <li><a data-toggle="tab" href="#itemAttachment">Attachments</a></li>
        </ul>
        <div class="tab-content">
            <div id="itemGeneral" class="tab-pane fade in active"><!-- general-tab-section start -->
                <div class="g-0 bg-light position-relative">
                    
                    <table class="table opinvitemtable">
                        <thead class="thead-dark">
                            <?php
                            $pocurrency = isset($inventorypurchaseorders['currency']) ? $currencyarr[$inventorypurchaseorders['currency']] : 'USD';
                            ?>
                            <tr class="tblinvpo">
                                <th></th>
                                <th>#</th>
                                <th class="col-sm-2">Item</th>
                                <th class="col-sm-1">Location Needed</th>
                                <th class="col-sm-2">ETA</th>
                                <th class="col-sm-2">Quantity</th>
                                <th class="col-sm-2">UOM</th>
                                <th class="col-sm-2">Cost/Unit (<span class="pocurrency"><?php echo $pocurrency; ?>)</th>
                                <th class="col-sm-2">Total (<span class="pocurrency"><?php echo $pocurrency; ?>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalAmount = '0.00';
                            $subTotalAmount = '0.00';
                            $po_tax_percentage = 0;
                            $po_tax_amount = '0.00';
                            $po_shipping = '0.00';

                            if(!empty($porequestid) || !empty($invitemid)){
                                foreach($inventorypoitems as $invreqitem){
                                    echo $this->element("Inventory/inventory_po_item_add", array('invreqitem'=>$invreqitem));
                                }
                            }

                            $totalAmount = $subTotalAmount+$po_tax_amount+$po_shipping;
                            ?>
                            <tr id="invrequeststbl">
                                <td colspan="9">
                                    <a class="btn btn-primary addinvpoitem" data-val="invitem" <?php if(empty($po_type)){ ?>disabled <?php } ?>><i class="fa fa-plus-circle"></i> Add Inventory Item</a>
                                    <a class="btn btn-primary addinvpoitem" data-val="noninvitem"  <?php if(empty($po_type)){ ?>disabled <?php } ?>><i class="fa fa-plus-circle"></i> Add Non-Inventory Item</a>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="6" class="text-right"><i class="fa fa-info-circle" container="body" data-toggle="tooltip" data-html="true" rel="tooltip" placement="top" title="<div class=&quot;style=&quot;min-width:200px;&quot;>Full precision is used when calculating total value, but rounding is applied when displaying each line item total amount.</div>"></i>&nbsp; Subtotal</td>
                                <td class="text-right"><span class="posubtotal"><?php echo $subTotalAmount; ?></span>&nbsp;<span class="pocurrency"><?php echo $currencyarr[$currency]; ?></span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="4">
                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id" >Tax &nbsp;&nbsp;<input type="radio" name="po_tax" class="potaxcal" value="1" checked="checked"> %</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <?php echo $this->Form->input('po_tax_percentage', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>$po_tax_percentage)); ?>
                                            </div>
                                        </div>    
                                    </div>

                                    <div class="col-md-6" style="padding-right: 0px !important;">
                                        <div class="form-group"> 
                                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id" ><input type="radio" name="po_tax" class="potaxcal" value="0"> $</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12" style="padding-right: 0px !important;">
                                                <?php echo $this->Form->input('po_tax_amount', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'disabled'=>'disabled', 'value'=>$po_tax_amount)); ?>
                                            </div>
                                        </div> 
                                        
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="6" class="text-right">Shipping</td>
                                <td>
                                    <?php echo $this->Form->input('po_shipping', array('type'=>'number', 'class'=>'form-control col-md-6 col-xs-6', 'placeholder' => '', 'label' => false, 'value'=>$po_shipping)); ?>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="7" class="text-right">
                                    <b style="margin-right: 42px;">Total</b>
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
                        <div class="search-control" style="width: 220px; margin-right:10px;display: inline-block;position: relative;">
                            <input type="text" class="form-control" placeholder="Search Attachments">
                            <div style="display: inline; position:absolute; right: 10px; top: 6px; color: darkgray">
                                <i class="fa fa-search"></i>
                            </div>
                        </div>

                        <div class="pull-right">
                            <input type="file" name="files[]" id="inventoryattachment" style="display:none" multiple />
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

        </div>
    </div>
</div>
<?php 
echo $this->Form->end(); 
?>