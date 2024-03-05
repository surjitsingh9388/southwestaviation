<section class="top-form-section">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="reference">Description</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('part_description', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5', 'style'=>'height: 113px; width: 442px;')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <p>This part exists in Master Parts</p>
                </div>
                <div class="col-md-12">
                    <?php
                    $part_taxable_chk = '';
                    if(!empty(@$aircraftwoitemparts->part_taxable)){
                        $part_taxable_chk = 'checked';
                    }
                    ?>
                    <input type="checkbox" name="part_taxable" value="1" <?php echo $part_taxable_chk; ?> />&nbsp;Part is Taxable
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="reference">Old Serial Number</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('old_serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_old_serial_number')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="reference">New Serial Number</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_serial_number')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="reference">Date Needed</label>
                        <div class="form-input-frame">
                            <div class="input-group date datePicker">
                                <?php echo $this->Form->Text('date_needed', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_item_part_date_needed', 'placeholder' => '', 'label' => false)); ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <input type="checkbox" name="" value="1" />&nbsp;Is Loaner
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">Qty Needed</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('qty_needed', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_qty_needed')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">Qty Used</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('qty_cust_owned', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_qty_cust_owned')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">Qty On Hand</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('qty_stock', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_qty_stock')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">Cost</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('cost', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_cost')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">Retail Price</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('price_each', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'price_each')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <?php
                        $give_discount_percentage_chk = '';
                        if(!empty(@$aircraftwoitemparts->give_discount_percentage)){
                            $give_discount_percentage_chk = 'checked';
                        }
                        ?>
                        <label class="control-label" for="reference"><input type="checkbox" name="give_discount" id="give_discount" value="1" <?php echo $give_discount_percentage_chk; ?> />&nbsp;Give Discount</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('give_discount_percentage', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'give_discount_percentage')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">Shipping In</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('part_ship_in', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_part_ship_in')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">Shipping Out</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('part_ship_out', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_part_ship_out')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">Total Retail</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('part_total_prices', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'part_total_prices')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">Condition</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('part_conditions', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_part_conditions')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">Core Charge</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('core_charges', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_core_charges')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">General Retail</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('general_retail', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_general_retail')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">General Location</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('general_location', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_general_location')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label" for="reference">Superseding No.</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('superseding_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_superseding_no')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-default mt-25">Use Superseding</button>
                </div>
            </div>
            <div class="col-md-12">
                <h5>Outstanding Purchase Order for Part</h5>
                <div class="col-md-12 pd0">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Qty</th>
                                <th scope="col">Purchase Order</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Vendor</th>
                                <th scope="col">Contact</th>
                                <th scope="col">Est. Arrive</th>
                                <th scope="col">Ship Method</th>
                            </tr>
                        </thead>
                        <tbody id="aircraft-woitem-partlist">
                            <?php
                            //$woitempartshtml = $this->InventoryAircraftWorkOrder->getWorkOrderItemPartListHTML($aircraftwoitemparts);
                            //echo $woitempartshtml;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>