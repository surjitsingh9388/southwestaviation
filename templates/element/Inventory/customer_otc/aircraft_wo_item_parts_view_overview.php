<section class="top-form-section">
    <div class="row">
        <div class="col-md-6 col-xs-6 col-sm-5 p-0">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Description</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('part_description', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 col-sm-12">
                <p>This part exists in Master Parts</p>
            </div>
            <div class="col-md-12 col-xs-12 col-sm-12">
                <?php
                $part_taxable_chk = '';
                if(!empty(@$aircraftwoitemparts->part_taxable)){
                    $part_taxable_chk = 'checked';
                }
                ?>
                <input type="checkbox" name="part_taxable" value="1" <?php echo $part_taxable_chk; ?> />&nbsp;Part is Taxable
            </div>
            <div class="col-md-6 col-xs-6 col-sm-12">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Old Serial Number</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('old_serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_old_serial_number')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-12">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">New Serial Number</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_serial_number')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-12">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Date Needed</label>
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
            <div class="col-md-6 col-xs-6 col-sm-12">
                <input type="checkbox" name="is_loaner" value="1" />&nbsp;Is Loaner
            </div>
        </div>
        <div class="col-md-6 col-xs-6 col-sm-7 p-0">
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Qty Needed</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('qty_needed', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'qty-needed')); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Qty Used</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('qty_used', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_qty_used')); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Qty On Hand</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('qty_stock', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_qty_stock')); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Cost</label>
                    <div class="form-input-frame">
                        <?php
                        $cost_in_mparts = '$0.00'; 
                        if(!empty(@$aircraftwoitemparts->cost_in_mparts)){
                            $cost_in_mparts = '$'.number_format($aircraftwoitemparts->cost_in_mparts, 2);
                        }
                        echo $this->Form->control('cost_in_mparts', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_cost', 'value'=>$cost_in_mparts)); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Retail Price</label>
                    <div class="form-input-frame">
                        <?php 
                        $price_each = '$0.00'; 
                        if(!empty(@$aircraftwoitemparts->price_each)){
                            $price_each = '$'.number_format($aircraftwoitemparts->price_each, 2);
                        }
                        echo $this->Form->control('price_each', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'price_each', 'value'=>$price_each)); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <?php
                    $give_discount_percentage_chk = '';
                    if(!empty(@$aircraftwoitemparts->give_discount_percentage)){
                        $give_discount_percentage_chk = 'checked';
                    }
                    $give_discount_percentage = '0.0%';
                    if(!empty(@$aircraftwoitemparts->give_discount_percentage)){
                        $give_discount_percentage = number_format($aircraftwoitemparts->give_discount_percentage, 2).'%';
                    }
                    ?>
                    <label class="control-label text-left" for="reference"><input type="checkbox" name="give_discount" id="give_discount" value="1" <?php echo $give_discount_percentage_chk; ?> />&nbsp;Give Discount</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('give_discount_percentage', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'give_discount_percentage', 'value'=>$give_discount_percentage)); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Shipping In</label>
                    <div class="form-input-frame">
                        <?php 
                        $part_ship_in = '$0.00'; 
                        if(!empty(@$aircraftwoitemparts->part_ship_in)){
                            $part_ship_in = '$'.number_format($aircraftwoitemparts->part_ship_in, 2);
                        }
                        echo $this->Form->control('part_ship_in', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_part_ship_in', 'value'=>$part_ship_in)); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Shipping Out</label>
                    <div class="form-input-frame">
                        <?php 
                        $part_ship_out = '$0.00'; 
                        if(!empty(@$aircraftwoitemparts->part_ship_out)){
                            $part_ship_out = '$'.number_format($aircraftwoitemparts->part_ship_out, 2);
                        }
                        echo $this->Form->control('part_ship_out', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_part_ship_out', 'value'=>$part_ship_out)); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Total Retail</label>
                    <div class="form-input-frame">
                        <?php 
                        $part_total_prices = '$0.00'; 
                        if(!empty(@$aircraftwoitemparts->part_total_prices)){
                            $part_total_prices = '$'.number_format($aircraftwoitemparts->part_total_prices, 2);
                        }
                        echo $this->Form->control('part_total_prices', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'part_total_prices', 'value'=>$part_total_prices)); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Condition</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('part_conditions', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_conditions')); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Core Charge</label>
                    <div class="form-input-frame">
                        <?php 
                        $core_charges = '$0.00'; 
                        if(!empty(@$aircraftwoitemparts->core_charges)){
                            $core_charges = '$'.number_format($aircraftwoitemparts->core_charges, 2);
                        }
                        echo $this->Form->control('core_charges', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>$core_charges)); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">General Retail</label>
                    <div class="form-input-frame">
                        <?php 
                        $general_retail = '$0.00'; 
                        if(!empty(@$aircraftwoitemparts->general_retail)){
                            $general_retail = '$'.number_format($aircraftwoitemparts->general_retail, 2);
                        }
                        echo $this->Form->control('general_retail', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_general_retail', 'value'=>$general_retail)); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">General Location</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('general_location', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_general_location')); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <div class="form-group">
                    <label class="control-label text-left" for="reference">Superseding No.</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('superseding_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_superseding_no')); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-6">
                <button type="button" class="btn btn-default" style="margin:0px;width: 100%;" disabled>Use Superseding</button>
            </div>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12 p-0">
            <h5 class="col-sm-12">Outstanding Purchase Order for Part</h5>
            <div class="table-responsive col-sm-12">
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
</section>