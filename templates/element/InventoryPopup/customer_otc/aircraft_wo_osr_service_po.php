<div id="aircraftWOOSRServicePOModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Service P/O # <?php echo @$woosrinfopo->po_no; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mt10">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default wo-osr-po-items">New Item</button>
                            <button type="button" class="btn btn-default wo-osr-service-po-notes">Notes</button>
                            <button type="button" class="btn btn-default wo-vendor-list-sec" style="color:black;">Goto Vendor</button>
                            <button type="button" class="btn btn-default wo-osr-po-checkin-labor">Check-in Labor</button>
                            <button type="button" class="btn btn-default removeosrpoitemsbtn">Delete</button>
                            <button type="button" class="btn btn-default wo-osr-po-media-btn">Media</button>
                            <button type="button" class="btn btn-default wo-osr-po-reminder-btn">Reminders</button>
                            <button type="button" class="btn btn-default">Preview</button>
                            <button type="button" class="btn btn-default">Print</button>
                        </div>
                    </div>
                </div>

                <?php
                echo $this->Form->create($woosrinfopo, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOSRServicePO'));
                ?> 
                <input type="hidden" name="osr_infopoes_id" id="osr_infopoes_id" value="<?php echo @$woosrinfopo->id; ?>" />

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">P/O No.</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('po_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'service_poes_po_no')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="reference">Vendor</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('vendor_id', array('options' => $woosrvendordata, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'osr_po_vendor_id'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Vendor Phone</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('vendor_phone', array('options' => $woosrvendorphones, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'osr_po_vendor_phone'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Date Order Placed</label>
                            <div class="input-group date datePicker">
                                <?php 
                                $date_order_placed = date('m/d/Y');
                                if(!empty($woosrinfopo->date_order_placed)){
                                    $date_order_placed = $woosrinfopo->date_order_placed;
                                }
                                echo $this->Form->Text('date_order_placed', array('class' => 'form-control', 'id' => 'po_date_order_placed', 'placeholder' => '', 'label' => false, 'value'=>$date_order_placed)); ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">General Est. Arrival Date</label>
                            <div class="input-group date datePicker">
                                <?php echo $this->Form->Text('general_est_arrival_date', array('class' => 'form-control', 'id' => 'po_general_est_arrival_date', 'placeholder' => '', 'label' => false)); ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Terms</label>
                            <div class="form-input-frame">
                                <?php
                                $defaultPaymentMethod = unserialize(DEFAULTPAYMENTMETHOD);
                                echo $this->Form->control('term', array('options' => $defaultPaymentMethod, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_term'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">P/O Status</label>
                            <div class="form-input-frame">
                                <?php
                                $aircraftWOOSRPOStatus = unserialize(AIRCRAFT_WO_OSR_PO_STATUS);
                                echo $this->Form->control('po_status', array('options' => $aircraftWOOSRPOStatus, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'osr_po_status'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Created By</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('added_by', array('options' => $userlist, 'empty' => 'Select created by...', 'class' => 'form-control col-md-8 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_created_by')); 
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Vendor Contact</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('vendor_contact', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'vendor_contact')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Total Shipping Cost</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('total_shipping_cost', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'po_total_shipping_cost')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Currency</label>
                            <div class="form-input-frame">
                                <?php
                                $currencyArr = unserialize(CURRENCYOVERRIDE);
                                echo $this->Form->control('currency', array('options' => $currencyArr, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_currency'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Ship To</label>
                            <div class="form-input-frame">
                                <?php
                                $defaultShipTo = unserialize(DEFAULT_SHIP_TO);
                                echo $this->Form->control('ship_to', array('options' => $defaultShipTo, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_ship_to'));
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">RMA Number</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('rma_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'po_rma_number')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label label-heading-left" for="reference">Enter Full Address Info Here:</label>
                            <span class="label-chkbox-right wo-osr_customer_address">
                                <span class="form-check-label" for="flexCheckDefault">Select Customer Address</span>
                            </span>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('full_address_info', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'style'=>'height: 96px;', 'id'=>'po_full_address_info')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Ship Method</label>
                            <div class="form-input-frame">
                                <?php
                                $defaultOTCShippingMethod = unserialize(DEFAULTOTCSHIPPINGMETHOD);
                                echo $this->Form->control('ship_method', array('options' => $defaultOTCShippingMethod, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_ship_method'));
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label label-heading-left" for="reference" style="width:65% !important;">Tracking Number</label>
                            <span class="label-chkbox-right wo-osr_customer_address">
                                <span class="form-check-label osr-more-tracking-number" for="flexCheckDefault">More...</span>
                            </span>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('tracking_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'po_tracking_number')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary float-right saveWOOSRServicePOBtn">Save</button>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>

                <div class="row">
                    <div class="col-md-12" style="height:200px; overflow:scroll;">
                        <h6>All Service Items on Purchase Order</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Destination</th>
                                    <th scope="col">Dest. Num</th>
                                    <th scope="col">Item No.</th>
                                    <th scope="col">Part Number</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Labor</th>
                                    <th scope="col">Parts</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody id="otcosrpoitemstbl">
                                <?php
                                $total_purchase_order = 0;
                                foreach($allserviceitemspo as $key=>$serviceitems){
                                    $total = ($serviceitems['labor_cost']+$serviceitems['part_cost']+$serviceitems['ship_out']+$serviceitems['ship_in']);
                                ?>
                                <tr class="editwoosrpoitem <?php if($key == '0'){ ?>osr-po_item-active<?php } ?>" data-val="<?php echo $serviceitems['id']; ?>">
                                    <td><?php echo $serviceitems['work_order']['order_type'] == '1' ? 'Work Order' : 'Repair Order'; ?></td>
                                    <td><?php echo $serviceitems['work_order']['work_order_no']; ?></td>
                                    <td><?php echo $serviceitems['wo_item']['wo_item_position']; ?></td>
                                    <td><?php echo $serviceitems['part_number']; ?></td>
                                    <td><?php echo $serviceitems['osr_description_of_work']; ?></td>
                                    <td><?php echo $serviceitems['labor_cost']; ?></td>
                                    <td><?php echo $serviceitems['part_cost']; ?></td>
                                    <td><?php echo $total; ?></td>
                                </tr>
                                <?php $total_purchase_order += $total;} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-2">
                            <button type="button" class="btn btn-default">Options</button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-default">Status Notes</button>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="checkbox" name="osr_tax_parts" value="1">&nbsp;User Vendor Ship-To-Address
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="airframe_component_id">Total Amount</label>
                                <div class="col-md-8" id="airCompsList">
                                    <?php echo $this->Form->control('tot_purchase_order_amt', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'value'=>$total_purchase_order, 'readonly'=>'readonly')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>