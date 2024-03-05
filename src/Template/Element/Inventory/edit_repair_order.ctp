<?php
echo $this->Form->create($InventoryRepairOrders, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryRepairOrders', 'autocomplete'=>'off'));
?> 
<div class="addPartBorder pt10">
    <div class="row">
        <div class="col-md-4"></div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Vendor&nbsp;<span class="required">*</span></label>
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <?php
                    echo $this->Form->control('vendor', array('options' => $vendor, 'empty' => 'Select Vendor...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'vendor', 'required' => 'required')); 
                    ?>
                    <div class="col-md-1 col-sm-1 col-xs-12 plus-new-btn"><button class="btn btn-primary vendorModelbtn" type="button"><i class="fa fa-plus"></i></button></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">RO Date&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="input-group date datePicker">
                        <?php 
                        $ro_date = !empty($InventoryRepairOrders->ro_date) ? date('m-d-Y', strtotime($InventoryRepairOrders->ro_date)) : date('m-d-Y');
                        echo $this->Form->Text('ro_date', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'ro_date', 'placeholder' => '', 'label' => false, 'required' => 'required', 'value'=>$ro_date)); 
                        ?>
                        <span class="input-group-addon hide-block">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Number&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('ro_number', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'ro_number')); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Contact</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('contact', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Ship Via&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $shipviaarr = unserialize(SHIP_VIA);

                    echo $this->Form->control('ship_via', array('options' => $shipviaarr, 'empty' => 'Select a shipment method...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'required' => 'required')); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Requestor&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('requestor', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>$userData['full_name'], 'required' => 'required')); ?>
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
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Currency&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $currencyarr = unserialize(CURRENCY);
                    $currency = !empty($InventoryRepairOrders->currency) ? $InventoryRepairOrders->currency : '1';
                    echo $this->Form->control('currency', array('options' => $currencyarr, 'empty' => 'Enter a currency ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_currency', 'required' => 'required', 'value'=>$currency)); 
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Bill To&nbsp;<span class="required">*</span></label>
                
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <?php
                    echo $this->Form->control('bill_to_address', array('options' => $billingaddress, 'empty' => 'Select Address...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'required' => 'required', 'id' => 'bill_to_address'));
                    ?>
                    <div class="col-md-1 col-sm-1 col-xs-12 plus-new-btn"><button class="btn btn-primary addnewinvaddresspopup plus-btn-h" type="button" data-val="billing"><i class="fa fa-plus"></i></button></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Ship To&nbsp;<span class="required">*</span></label>
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <?php
                    echo $this->Form->control('ship_to_address', array('options' => $shippingaddress, 'empty' => 'Select Address...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'ship_to_address', 'required' => 'required')); 
                    ?>
                    <div class="col-md-1 col-sm-1 col-xs-12 plus-new-btn"><button class="btn btn-primary addnewinvaddresspopup plus-btn-h" type="button" data-val="shipping"><i class="fa fa-plus"></i></button></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-2 col-sm-2 col-xs-12 special-instruction-label" for="plane_id">Special Instructions</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <?php echo $this->Form->input('special_instructions', array('type' => 'textarea', 'class'=>'form-control col-md-10 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
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
            <li class="active"><a data-toggle="tab" href="#itemGeneral">Items to be Repaired</a></li>
            <li><a data-toggle="tab" href="#itemAttachment">Attachments</a></li>
        </ul>
        <div class="tab-content">
            <div id="itemGeneral" class="tab-pane fade in active"><!-- general-tab-section start -->
                <div class="g-0 bg-light position-relative">
                    
                    <table class="table opinvitemtable">
                        <thead class="thead-dark">
                            <tr class="tblinvro">
                                <th></th>
                                <th>#</th>
                                <th class="col-sm-2">Part Name, Number, Serial</th>
                                <th class="col-sm-1">Location Needed</th>
                                <th class="col-sm-2">ETA</th>
                                <th class="col-sm-2">Qty Requested</th>
                                <th class="col-sm-2">Cost/Unit (<span class="pocurrency"><?php echo $currencyarr[$currency]; ?></span>)</th>
                                <th class="col-sm-2">Repair Cost (<span class="pocurrency"><?php echo $currencyarr[$currency]; ?></span>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalAmount = '0.00';
                            $subTotalAmount = '0.00';
                            $ro_tax_percentage = 0;
                            $ro_tax_amount = '0.00';
                            $ro_shipping = '0.00';

                            foreach($InventoryROItems as $invreqitem){
                                echo $this->element("Inventory/inventory_ro_item_add", array('invreqitem'=>$invreqitem));

                                $totalcost = $invreqitem->qty*$invreqitem->cost;
                                $subTotalAmount += !empty($totalcost) ? $totalcost : '0.00';
                            }
                            $ro_tax_percentage = isset($InventoryRepairOrders->ro_tax_percentage) ? $InventoryRepairOrders->ro_tax_percentage : '0.00';
                            $ro_tax_amount = isset($InventoryRepairOrders->ro_tax_amount) ? $InventoryRepairOrders->ro_tax_amount : '0.00';
                            $ro_shipping = isset($InventoryRepairOrders->ro_shipping) ? $InventoryRepairOrders->ro_shipping : '0.00';

                            $totalAmount = $subTotalAmount+$ro_tax_amount+$ro_shipping;
                            ?>
                            <tr id="invrequeststbl">
                                <td colspan="8">
                                    <a class="btn btn-primary addinvpoitem" data-val="invitem"><i class="fa fa-plus-circle"></i> Add Inventory Item</a>
                                    <a class="btn btn-primary addinvpoitem" data-val="noninvitem"><i class="fa fa-plus-circle"></i> Add Non-Inventory Item</a>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="5" class="text-right"><i class="fa fa-info-circle" container="body" data-toggle="tooltip" data-html="true" rel="tooltip" placement="top" title="<div class=&quot;style=&quot;min-width:200px;&quot;>Full precision is used when calculating total value, but rounding is applied when displaying each line item total amount.</div>"></i>&nbsp; Subtotal</td>
                                <td class="text-right"><span class="posubtotal"><?php echo $subTotalAmount; ?></span>&nbsp;<span class="pocurrency"><?php echo $currencyarr[$currency]; ?></span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="3">
                                    <div class="col-md-7">
                                        <div class="form-group"> 
                                            <label class="control-label col-md-6 col-sm-6 col-xs-12" for="plane_id" >Tax &nbsp;&nbsp;<input type="radio" name="ro_tax" class="potaxcal" value="1" checked="checked"> %</label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <?php echo $this->Form->input('ro_tax_percentage', array('type'=>'number', 'class'=>'form-control col-md-6 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>$ro_tax_percentage, 'id'=>'po-tax-percentage')); ?>
                                            </div>
                                        </div>    
                                    </div>

                                    <div class="col-md-5 pr-0">
                                        <div class="form-group"> 
                                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id" ><input type="radio" name="ro_tax" class="potaxcal" value="0"> $</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12 pr-0">
                                                <?php echo $this->Form->input('ro_tax_amount', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'disabled'=>'disabled', 'value'=>$ro_tax_amount, 'id'=>'po-tax-amount')); ?>
                                            </div>
                                        </div> 
                                        
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="5" class="text-right">Shipping</td>
                                <td>
                                    <?php echo $this->Form->input('ro_shipping', array('type'=>'number', 'class'=>'form-control col-md-6 col-xs-6', 'placeholder' => '', 'label' => false, 'value'=>$ro_shipping, 'id'=>'po-shipping')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="6" class="text-right">
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
                    <div class="mt10">
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
                            <tr>
                                <td class="document-name"><span class="document-management-icon <?php echo $iconcss; ?>"></span>
                                <?php
                                echo $this->Html->link($attachment['file_name'], '/inventoryroitems/' . $attachment['file_name'],['download'=>$attachment['file_name']]);
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

        </div>
    </div>
</div>
<?php 
echo $this->Form->end(); 
?>