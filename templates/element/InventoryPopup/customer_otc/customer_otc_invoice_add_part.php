<div id="customerOTCInvoiceAddPartModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <?php
    echo $this->Form->create($otcinfoinvoiceparts, array('class' => 'form-horizontal form-label-left', 'id' => 'frmCustomerOTCInfoInvoicePart'));
    ?> 
    <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $inventorycustomers->id; ?>" />
    <input type="hidden" name="otc_invoice_id" id="otc_invoice_id" value="<?php echo @$otcinfoinvoiceparts->otc_invoice_id; ?>" />
    <input type="hidden" name="otc_invoice_part_id" id="otc_invoice_part_id" value="<?php echo @$otcinfoinvoiceparts->id; ?>" />

    <div class="modal-dialog" style="width: 65%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span><?php echo empty($otcinfoinvoiceparts->id) ? 'Add Part' : 'Edit Part'; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="reference">Part No.</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('part_number', array('options' => $inventoryitemdropdowndata, 'empty' => 'Select Part No.', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'invoice_part_number'));
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Superseding Part Number</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('superseding_part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'style'=>'width:65%; float:left;', 'readonly'=>'readonly')); ?>

                                <button type="button" class="btn btn-default" style="margin-left:8px;" disabled>Go to S/S</button>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="reference">Part Description</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('part_description', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'style'=>'height: 103px;', 'id'=>'invoice_part_description')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary otc-invoice-addpart-btn" data-val="add-part"><?php echo empty($otcinfoinvoiceparts->id) ? 'Add Part' : 'Save'; ?></button>
                        </div>
                        <?php
                        if(empty($otcinfoinvoiceparts->id)){
                        ?>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary otc-invoice-addpart-btn" data-val='part-add-close'>Add Part & Close</button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 5px;" onclick="$('#customerOTCInvoiceAddPartModel').modal('hide');">Close</button>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6" style="padding-right:0px;">
                        <div class="col-md-12" style="padding:0px;">
                            <div class="col-md-6" style="padding:0px;">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Qty Needed</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('qty_needed', array('type'=>'number','class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>'1')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Qty Stk</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('qty_stock', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'part_qty_stock')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Qty (Cust. Owned)</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('qty_cust_owned', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly')); ?>
                            </div>
                        </div>
                        <div class="form-check">
                            <?php
                            $donot_deduct_from_stockchk = '';
                            if(!empty($otcinfoinvoiceparts->donot_deduct_from_stock)){
                                $donot_deduct_from_stockchk = 'checked';
                            }
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="donot_deduct_from_stock" <?php echo $donot_deduct_from_stockchk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">Do not deduct from stock</span>
                        </div>
                    </div-->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Price Each</label>
                            <div class="form-input-frame">
                                <?php 
                                $price_each = '';
                                if(!empty($otcinfoinvoiceparts->price_each)){
                                    $price_each = '$'.$otcinfoinvoiceparts->price_each;
                                }
                                echo $this->Form->control('price_each', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'otc_invoice_part_price_each', 'value'=>$price_each)); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">
                                <?php
                                $give_discountchk = '';
                                if(!empty($otcinfoinvoiceparts->give_discount_percentage)){
                                    $give_discountchk = 'checked';
                                }
                                ?>
                                <input class="form-check-input" type="checkbox" value="1" id="otc_invoice_give_discount" name="give_discount" <?php echo $give_discountchk; ?>>
                                <span class="form-check-label" for="give_discount">Give Discount</span>
                            </label>
                            <div class="form-input-frame">
                                <?php 
                                $give_discount_percentage = '';
                                if(!empty($otcinfoinvoiceparts->give_discount_percentage)){
                                    $give_discount_percentage = $otcinfoinvoiceparts->give_discount_percentage.'%';
                                }
                                echo $this->Form->control('give_discount_percentage', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%', 'id'=>'otc_invoice_give_discount_percentage', 'value'=>$give_discount_percentage)); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Total</label>
                            <div class="form-input-frame">
                                <?php 
                                $part_total_prices = '';
                                if(!empty($otcinfoinvoiceparts->part_total_prices)){
                                    $part_total_prices = '$'.$otcinfoinvoiceparts->part_total_prices;
                                }
                                echo $this->Form->control('part_total_prices', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'otc_invoice_part_total_prices', 'readonly'=>'readonly', 'value'=>$part_total_prices)); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="reference">Condition</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('part_conditions', array('options' => $conditions, 'empty' => 'Select Condition', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'invoice_part_conditions'));
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Serial Number</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('serial_number', array('options' => $serialno, 'empty' => 'Select Serial Number', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'invoice_serial_number'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">AOG Charge</label>
                            <div class="form-input-frame">
                                <?php 
                                $aoc_charge = '';
                                if(!empty($otcinfoinvoiceparts->aoc_charge)){
                                    $aoc_charge = '$'.$otcinfoinvoiceparts->aoc_charge;
                                }
                                echo $this->Form->control('aoc_charge', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'aoc_charge', 'value'=>$aoc_charge)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Ship In</label>
                            <div class="form-input-frame">
                                <?php 
                                $part_ship_in = '';
                                if(!empty($otcinfoinvoiceparts->part_ship_in)){
                                    $part_ship_in = '$'.$otcinfoinvoiceparts->part_ship_in;
                                }
                                echo $this->Form->control('part_ship_in', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'part_ship_in', 'value'=>$part_ship_in)); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Drop Ship Charge</label>
                            <div class="form-input-frame">
                                <?php 
                                $drop_ship_charges = '';
                                if(!empty($otcinfoinvoiceparts->drop_ship_charges)){
                                    $drop_ship_charges = '$'.$otcinfoinvoiceparts->drop_ship_charges;
                                }
                                echo $this->Form->control('drop_ship_charges', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'drop_ship_charges', 'value'=>$drop_ship_charges)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Ship Out</label>
                            <div class="form-input-frame">
                                <?php 
                                $ship_out = '';
                                if(!empty($otcinfoinvoiceparts->ship_out)){
                                    $ship_out = '$'.$otcinfoinvoiceparts->ship_out;
                                }
                                echo $this->Form->control('ship_out', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'invoice_ship_out', 'value'=>$ship_out)); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Wire Fee</label>
                            <div class="form-input-frame">
                                <?php 
                                $misc_charges = '';
                                if(!empty($otcinfoinvoiceparts->misc_charges)){
                                    $misc_charges = '$'.$otcinfoinvoiceparts->misc_charges;
                                }
                                echo $this->Form->control('misc_charges', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'invoice_misc_charges', 'value'=>$misc_charges)); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Credit Card Fee</label>
                            <div class="form-input-frame">
                                <?php 
                                $hazardous_fee = '';
                                if(!empty($otcinfoinvoiceparts->hazardous_fee)){
                                    $hazardous_fee = '$'.$otcinfoinvoiceparts->hazardous_fee;
                                }
                                echo $this->Form->control('hazardous_fee', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'hazardous_fee', 'value'=>$hazardous_fee)); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group"> 
                            <?php
                            $part_taxablechk = '';
                            if(!empty($otcinfoinvoiceparts->part_taxable)){
                                $part_taxablechk = 'checked';
                            }
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="part_taxable" <?php echo $part_taxablechk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">Taxable</span>
                        </div>

                        <div class="form-group"> 
                            <?php
                            $drop_ship_partchk = '';
                            if(!empty($otcinfoinvoiceparts->drop_ship_part)){
                                $drop_ship_partchk = 'checked';
                            }
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="drop_ship_part" <?php echo $drop_ship_partchk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">Drop Ship Part</span>
                        </div>

                        <div class="form-group"> 
                            <?php
                            $show_net_with_pricechk = '';
                            if(!empty($otcinfoinvoiceparts->show_net_with_price)){
                                $show_net_with_pricechk = 'checked';
                            }
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="show_net_with_price" <?php echo $show_net_with_pricechk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">Show "NET" with Price</span>
                        </div>
                    </div>

                </div>
                
                <!--div class="headings">
                    <h5 class="title">Parts Information for MParts</h5>
                    <span class="hr"></span>
                    <?php
                    if(empty($otcinfoinvoiceparts->id)){
                    ?>
                    <button type="button" class="btn btn-default">Find Part</button>
                    <?php } ?>
                </div>

                <div class="row">
                    <div class="col-md-4" style="padding-right:0px;">
                        <div class="col-md-12" style="padding:0px;">
                            <div class="col-md-6" style="padding:0px;">
                                <div class="form-group">
                                    <label class="control-label" for="reference">General Location</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('general_location', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'invoice_general_location')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Cost In MParts</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('cost_in_mparts', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'readonly'=>'readonly')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" style="padding:0px;">
                            <div class="form-group">
                                <label class="control-label" for="reference">Qty On Other OTC Quotes</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('qty_other_otc_quotes', array('type'=>'number','class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0', 'readonly'=>'readonly')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Hazardous Notes</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('hazardous_notes', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'style'=>'height: 103px;', 'readonly'=>'readonly')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4" style="padding-right:0px;">
                        <div class="col-md-12" style="padding:0px;">
                            <div class="col-md-6" style="padding:0px;">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Retail Price</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('retail_price', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'readonly'=>'readonly', 'id'=>'invoice_retail_price')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="reference">List Price</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('invoice_list_price', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'readonly'=>'readonly', 'id'=>'list_price')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" style="padding:0px;">
                            <div class="col-md-6" style="padding:0px;">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Supplier</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('part_supplier', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Discount Code</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('part_discount_code', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Notes</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('part_notes', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'style'=>'height: 103px;', 'readonly'=>'readonly')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Dealer Price</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('part_dealer_price', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'readonly'=>'readonly')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Core Charge</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('part_core_charge', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'readonly'=>'readonly')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Unit of Measure</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('part_unit_measure', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Weight</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('part_weight', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <?php
                        if(empty($otcinfoinvoiceparts->id)){
                        ?>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 10px;">Show Family</button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 5px;">Show Alt #s</button>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 5px;">Show All S/Ns</button>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 5px;">Show Qty Info</button>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 5px;">Kit Analyzer</button>
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 5px;">Add Last Sale</button>
                        </div>
                        <?php } ?>
                    </div>
                </div-->

                <div class="row">
                    <div class="col-md-10">
                        <div class="headings">
                            <h5 class="title">Purchase History for Customer</h5>
                            <span class="hr"></span>
                        </div>

                        <div style="height:200px; overflow:scroll;margin-top:10px;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">OTC Type</th>
                                        <th scope="col">OTC Number</th>
                                        <th scope="col">Date Created</th>
                                        <th scope="col">Qty</th>
                                        <th scope="col">Price Ea.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>

            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>

    <script>
    var getInventoryDetByConditionURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'getInventoryDetByCondition']); ?>";
</script>

</div>