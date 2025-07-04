<section class="top-form-section" id="aircraft_info_add_section">    
    <?php
    echo $this->Form->create($woosrpoitems, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOSRServicePOCheckInLaborInfo'));
    ?> 
    <div class="row">
        <input type="hidden" name="osr_po_item_id" id="osr_po_item_id" value="<?php echo @$woosrpoitems->id; ?>" />
        <div class="col-md-3">
            <div class="form-group">
                <p>Parts Not Checked In</p>
            </div>

            <div class="form-group listitems parts-not-checkedin-list">
                <table>
                    <thead>
                        <tr>
                            <th>Part Number</th>
                            <th>Destination</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $woosrinfodata = [];
                        foreach($allserviceitemspo as $itempo){
                            $classative = '';
                            if($itempo['id'] == $woosrpoitems['id']){
                                $woosrinfodata = $itempo;
                                $classative = 'parts_not_checked_in_active';
                            }
                        ?>
                        <tr class="parts_not_checked_in <?php echo $classative; ?>" data-val="<?php echo $itempo['id']; ?>">
                            <td><?php echo $itempo['part_number']; ?></td>
                            <td><?php echo $itempo['work_order']['work_order_no']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-default">Refresh List</button>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="print_label_when_applicable">
                    <span class="form-check-label" for="flexCheckDefault">Print labels when applicable</span>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <input type="hidden" name="aircraft_id" id="aircraft_id" value="" />
            <div class="row">
                <div class="col-md-12">
                    <b>Individual Service Item Information</b>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="reference">Part Number</label>
                        <div class="form-input-frame">
                            <?php
                            echo $this->Form->control('part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly')); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="reference">Destination</label>
                        <div class="form-input-frame">
                            <?php 
                            $destination = 'W/O No. '.$woosrinfodata['work_order']['work_order_no'].' Item 1';
                            echo $this->Form->control('checking_labor_destination', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>$destination)); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-default osrMarkArrivedBtn">Mark Arrived</button>
                    </div>
                </div>
                    
                <div class="col-md-6">
                    <div class="form-group"> 
                        <label class="control-label" for="reference">Discrepancy</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->input('vendor_notes', array('type' => 'textarea', 'class'=>'form-control', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'vendor_notes')); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">Labor Cost</label>
                        <div class="form-input-frame">
                            <?php 
                            $labor_cost = '$0.00';
                            if(!empty($woosrpoitems->labor_cost)){
                                $labor_cost = '$'.number_format($woosrpoitems->labor_cost, 2);
                            }
                            echo $this->Form->control('labor_cost', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'labor_cost', 'value'=>$labor_cost)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Parts Cost</label>
                        <div class="form-input-frame">
                            <?php 
                            $part_cost = '$0.00';
                            if(!empty($woosrpoitems->part_cost)){
                                $part_cost = '$'.number_format($woosrpoitems->part_cost, 2);
                            }
                            echo $this->Form->control('part_cost', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'part_cost', 'value'=>$part_cost)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Ship Out</label>
                        <div class="form-input-frame">
                            <?php 
                            $ship_out = '$0.00';
                            if(!empty($woosrpoitems->ship_out)){
                                $ship_out = '$'.number_format($woosrpoitems->ship_out, 2);
                            }
                            echo $this->Form->control('ship_out', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'ship_out', 'value'=>$ship_out)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Ship In</label>
                        <div class="form-input-frame">
                            <?php 
                            $ship_in = '$0.00';
                            if(!empty($woosrpoitems->ship_in)){
                                $ship_in = '$'.number_format($woosrpoitems->ship_in, 2);
                            }
                            echo $this->Form->control('ship_in', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'ship_in', 'value'=>$ship_in)); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">Labor Charges</label>
                        <div class="form-input-frame">
                            <?php 
                            $labor_charge = '$0.00';
                            if(!empty($woosrpoitems->labor_charge)){
                                $labor_charge = '$'.number_format($woosrpoitems->labor_charge, 2);
                            }
                            echo $this->Form->control('labor_charge', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'labor_charge', 'value'=>$labor_charge)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Parts Charges</label>
                        <div class="form-input-frame">
                            <?php 
                            $parts_charge = '$0.00';
                            if(!empty($woosrpoitems->parts_charge)){
                                $parts_charge = '$'.number_format($woosrpoitems->parts_charge, 2);
                            }
                            echo $this->Form->control('parts_charge', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'parts_charge', 'value'=>$parts_charge)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">New Part S/N</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('new_part_sn', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Owner (Stock)</label>
                        <div class="form-input-frame">
                            <?php 
                            $owner = '0.00%';
                            if(!empty($woosrpoitems->owner)){
                                $owner = number_format($woosrpoitems->owner, 2).'%';
                            }
                            echo $this->Form->control('owner', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%', 'value'=>$owner)); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">MParts Cost</label>
                        <div class="form-input-frame">
                            <?php 
                            $mparts_cost = '$0.00';
                            if(!empty($woosrpoitems->mparts_cost)){
                                $mparts_cost = '$'.number_format($woosrpoitems->mparts_cost, 2);
                            }
                            echo $this->Form->control('mparts_cost', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'mparts_cost', 'value'=>$mparts_cost)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">MParts Retail</label>
                        <div class="form-input-frame">
                            <?php 
                            $mparts_retail = '$0.00';
                            if(!empty($woosrpoitems->mparts_retail)){
                                $mparts_retail = '$'.number_format($woosrpoitems->mparts_retail, 2);
                            }
                            echo $this->Form->control('mparts_retail', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'mparts_retail', 'value'=>$mparts_retail)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Part Location</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('part_location', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Condition</label>
                        <div class="form-input-frame">
                            <?php
                            $outsideRepairInfoCond = unserialize(OUTSIDE_REPAIR_INFO_CONDITION);
                            echo $this->Form->control('po_condition', array('options' => $outsideRepairInfoCond, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'osr_po_condition'));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">Shelf Life</label>
                        <div class="input-group date datePicker">
                            <?php 
                            echo $this->Form->Text('shelf_life', array('class' => 'form-control', 'id' => 'po_item_shelf_life', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Warranty Expires</label>
                        <div class="input-group date datePicker">
                            <?php 
                            echo $this->Form->Text('warranty_expires', array('class' => 'form-control', 'id' => 'po_warranty_expires', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">EPA Charges</label>
                        <div class="form-input-frame">
                            <?php 
                            $epa_charges = '$0.00';
                            if(!empty($woosrpoitems->epa_charges)){
                                $epa_charges = '$'.number_format($woosrpoitems->epa_charges, 2);
                            }
                            echo $this->Form->control('epa_charges', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'epa_charges', 'value'=>$epa_charges)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    
                </div>
            </div>

            <div class="row">
                <div class="col-md-9">
                    <div class="form-group"> 
                        <label class="control-label" for="reference">Description of Work Requested</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->input('description_work_requested', array('type' => 'textarea', 'class'=>'form-control', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'description_work_requested')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Lot No.</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('lot_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</section>