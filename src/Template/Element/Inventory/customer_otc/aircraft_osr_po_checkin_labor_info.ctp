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
                            <?php echo $this->Form->control('labor_cost', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Parts Cost</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('part_cost', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Ship Out</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('ship_out', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Ship In</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('ship_in', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">Labor Charges</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('labor_charge', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Parts Charges</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('parts_charge', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
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
                            <?php echo $this->Form->control('owner', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group d-flex">
                        <label class="control-label" for="reference">MParts Cost</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('mparts_cost', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">MParts Retail</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('mparts_retail', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Part Location</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('part_location', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
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
                            <?php echo $this->Form->control('epa_charges', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
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