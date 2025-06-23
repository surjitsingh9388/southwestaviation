<div id="createOTCInvoiceModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <?php
    echo $this->Form->create($otcinfoinvoices, array('class' => 'form-horizontal form-label-left', 'id' => 'frmCustomerOTCInfoInvoice'));
    ?> 
    <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $inventorycustomers->id; ?>" />
    <input type="hidden" name="customer_otc_invoice_id" id="customer_otc_invoice_id" value="<?php echo @$otcinfoinvoices->id; ?>" />
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>OTC Invoice No. <?php echo $otcinfoinvoices->otc_invoice_no; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mt10">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default fetchCustOTCPopup" data-val='create_otc_invoice_ad_part_btn'>New Part</button>
                            <!--button type="button" class="btn btn-default">Notes</button>
                            <button type="button" class="btn btn-default">Goto Cust.</button>
                            <button type="button" class="btn btn-default">Load Cust.</button>
                            <button type="button" class="btn btn-default">Update Cust. Media</button-->
                            <button type="button" class="btn btn-default">Billing Info</button>
                            <button type="button" class="btn btn-default">Returns</button>
                            <button type="button" class="btn btn-default otcinvoice_report">Preview</button>
                            <button type="button" class="btn btn-default otcinvoice_report">Print</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">OTC Invoice No.</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('otc_invoice_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'otc_invoice_no')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Customer Name</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('invoice_customer_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>$inventorycustomers->customer_name, 'readonly'=>'readonly')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Customer ID</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('invoice_customer_ids', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>$inventorycustomers->customer_name, 'readonly'=>'readonly')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Customer Phone #s</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('invoice_customer_phone', array('options' => $clientphone, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'invoice_customer_phone'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Date Created</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('invoice_date_created', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'invoice_date_created', 'readonly'=>'readonly', 'value'=>date('m/d/Y'))); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Created By</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('added_by', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Ship To</label>
                            <div class="form-input-frame">
                                <?php
                                    $shipToArr = ['1'=>'Billing Address', '2'=>'Shipping Address', '3'=>'Other'];
                                    echo $this->Form->control('invoice_ship_to', array('options' => $shipToArr, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'invoice_ship_to'));
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Terms</label>
                            <div class="form-input-frame">
                                <?php
                                $defaultPaymentMethod = unserialize(DEFAULTPAYMENTMETHOD);
                                echo $this->Form->control('invoice_terms', array('options' => $defaultPaymentMethod, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'shipping_method'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Buyer Contact</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('invoice_buyer_contact', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="reference">Customer P/O No.</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('invoice_customer_po_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <label class="control-label" for="plane_id">Ship Method</label>
                                    <div class="form-input-frame">
                                        <?php
                                        $defaultOTCShippingMethod = unserialize(DEFAULTOTCSHIPPINGMETHOD);
                                        echo $this->Form->control('invoice_shipping_method', array('options' => $defaultOTCShippingMethod, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'shipping_method'));
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="reference">Additional Ship  In Cost</label>
                                    <div class="form-input-frame">
                                        <?php 
                                        $additional_ship_in_cost = '';
                                        if(!empty($otcinfoinvoices->additional_ship_in_cost)){
                                            $additional_ship_in_cost = '$'.$otcinfoinvoices->additional_ship_in_cost;
                                        }
                                        echo $this->Form->control('additional_ship_in_cost', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'additional_ship_in_cost', 'value'=>$additional_ship_in_cost)); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group"> 
                                    <label class="control-label" for="plane_id" style="text-align:left; width:100%;">Other Shipping Address <span class="otcinvoice_ship_to_address">Select Ship-To Address</span></label>
                                    <div class="form-input-frame">
                                        <?php 
                                        $othershipreadonly = '';
                                        if(@$otcinfoinvoices->invoice_ship_to != '3'){
                                            $othershipreadonly = 'readonly';
                                        }
                                        echo $this->Form->control('other_shipping_address', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'style'=>'height: 96px;', 'readonly'=>$othershipreadonly)); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group"> 
                            <label class="control-label" for="plane_id">Status</label>
                            <div class="form-input-frame">
                                <?php
                                $OTCInvoiceStatus = unserialize(OTCINVOICESTATUS);
                                echo $this->Form->control('invoice_status', array('options' => $OTCInvoiceStatus, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'invoice_status'));
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Additional Ship Out Cost</label>
                            <div class="form-input-frame">
                                <?php 
                                $additional_ship_out_cost = '';
                                if(!empty($otcinfoinvoices->additional_ship_out_cost)){
                                    $additional_ship_out_cost = '$'.$otcinfoinvoices->additional_ship_out_cost;
                                }
                                echo $this->Form->control('additional_ship_out_cost', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'additional_ship_out_cost', 'value'=>$additional_ship_out_cost)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Tracking Number</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('invoice_tracking_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary float-right saveCustomerOTCInvoiceBtn">Save</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" style="height:200px; overflow:scroll;">
                        <h6>All Parts on Invoice</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Qty Need</th>
                                    <th scope="col">Qty Ship</th>
                                    <th scope="col">Part Number</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Price Each</th>
                                    <th scope="col">Total Price</th>
                                </tr>
                            </thead>
                            <tbody id="otcinfoinvoicetbl">
                                <?php echo $otcinfotblrow; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <!--button type="button" class="btn btn-default">Options</button-->
                            <button type="button" class="btn btn-default remove_item_otcinvoice">Remove Item from Invoice</button>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="airframe_component_id">Quote No.</label>
                                <div class="col-md-8" id="airCompsList">
                                    <?php echo $this->Form->control('quoteno', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false)); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label col-md-6" for="airframe_component_id">Total Parts Subtotal</label>
                                <div class="col-md-6" id="airCompsList">
                                    <?php echo $this->Form->control('total_parts_subtotal', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'value'=>'$'.$totalpartsubtotal, 'id'=>'otc_invoice_total_parts_subtotal', 'readonly'=>'readonly')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
<script>
var removeCustomerOTCInfoInvoicePartURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'removeCustomerOTCInfoInvoicePart']); ?>";
var customerOTCInfoInvoiceReportURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'customerOTCInfoInvoiceReport']); ?>";
</script>