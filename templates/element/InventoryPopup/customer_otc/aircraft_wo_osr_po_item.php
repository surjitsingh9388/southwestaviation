<div id="aircraftWOOSRPOItemModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <?php
                $itemtitle = 'Add Item to Service Purchase Order';
                $isedit = '';
                $poitemdisable = '';
                if(isset($woosrpoitems->id) && !empty($woosrpoitems->id)){
                    $itemtitle = 'Edit Service P/O Info';
                    $isedit = '1';
                    $poitemdisable = 'disabled';
                }
                ?>
                <h4 class="modal-title"><span class="gpTypeCls"></span><?php echo $itemtitle; ?></h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create($woosrpoitems, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOSRPOItems'));
                ?>
                <div class="row">
                    <?php
                    $wo_osr_po_id = !empty($wo_osr_po_id) ? $wo_osr_po_id : $woosrpoitems->osr_po_id;
                    ?>
                    <input type="hidden" name="osr_po_item_id" id="osr_po_item_id" value="<?php echo @$woosrpoitems->id; ?>" />
                    <input type="hidden" name="osr_po_id" id="osr_po_id" value="<?php echo $wo_osr_po_id; ?>" />

                    <div class="col-md-6">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Destination</label>
                                <div class="form-input-frame">
                                    <?php
                                    $destinationArr = ['1'=>'Work Order', '2'=>'Repair Order'];
                                    echo $this->Form->control('destination_id', array('options' => $destinationArr, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'osr_po_item_destination_id'));
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label" for="reference">Destination</label>
                                <div class="form-input-frame">
                                    <?php
                                    echo $this->Form->control('destination', array('options' => $wodropdown, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'osr_po_item_destination'));
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label" for="reference">Item No.</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('item_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"> 
                            <label class="control-label" for="reference">Discrepancy for W/O or R/O</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->input('discrepancy_wo_ro', array('type' => 'textarea', 'class'=>'form-control', 'placeholder' => '', 'label' => false, 'id'=>'discrepancy_wo_ro')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Part Number</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                    <?php
                                    //echo $this->Form->control('destination', array('options' => [], 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'osr_po_item_destination'));
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Old Serial Number</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('old_serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Labor Charge(to Customer)</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $labor_cost = '$0.00';
                                    if(!empty($woosrpoitems->labor_cost)){
                                        $labor_cost = '$'.number_format($woosrpoitems->labor_cost, 2);
                                    }
                                    echo $this->Form->control('labor_cost', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'labor_cost', 'value'=>$labor_cost)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Parts Charge(to Customer)</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $part_cost = '$0.00';
                                    if(!empty($woosrpoitems->part_cost)){
                                        $part_cost = '$'.number_format($woosrpoitems->part_cost, 2);
                                    }
                                    echo $this->Form->control('part_cost', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'part_cost', 'value'=>$part_cost)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <?php
                                    $tax_labor_chk = '';
                                    if(isset($woosrpoitems->tax_labor) && !empty($woosrpoitems->tax_labor)){
                                        $tax_labor_chk = 'checked';
                                    }
                                    ?>
                                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_labor" <?php echo $tax_labor_chk; ?>>
                                    <span class="form-check-label" for="flexCheckDefault">Tax Labor</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                <?php
                                    $tax_part_chk = '';
                                    if(isset($woosrpoitems->tax_part) && !empty($woosrpoitems->tax_part)){
                                        $tax_part_chk = 'checked';
                                    }
                                    ?>
                                    <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_part" <?php echo $tax_part_chk; ?>>
                                    <span class="form-check-label" for="flexCheckDefault">Tax Parts</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"> 
                            <label class="control-label" for="reference">Description of Work Requested</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->input('description_work_requested', array('type' => 'textarea', 'class'=>'form-control', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'vendor_notes', 'style'=>'width: 426px; height: 140px;')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Vendor Labor Charge</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $labor_charge = '$0.00';
                                    if(!empty($woosrpoitems->labor_charge)){
                                        $labor_charge = '$'.number_format($woosrpoitems->labor_charge, 2);
                                    }
                                    echo $this->Form->control('labor_charge', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'labor_charge', 'value'=>$labor_charge)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Vendor Parts Charge</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $parts_charge = '$0.00';
                                    if(!empty($woosrpoitems->parts_charge)){
                                        $parts_charge = '$'.number_format($woosrpoitems->parts_charge, 2);
                                    }
                                    
                                    echo $this->Form->control('parts_charge', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'parts_charge', 'value'=>$parts_charge)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-6">
                            <div class="form-group d-flex">
                                <label class="control-label" for="reference">Estimated Arrival Date</label>
                                <div class="input-group date datePicker">
                                    <?php 
                                    echo $this->Form->Text('estimated_arrival_date', array('class' => 'form-control', 'id' => 'po_item_estimated_arrival_date', 'placeholder' => '', 'label' => false)); ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Shipping Out</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $ship_out = '$0.00';
                                    if(!empty($woosrpoitems->ship_out)){
                                        $ship_out = '$'.number_format($woosrpoitems->ship_out, 2);
                                    }
                                    echo $this->Form->control('ship_out', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'ship_out', 'value'=>$ship_out)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Shipping In</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $ship_in = '$0.00';
                                    if(!empty($woosrpoitems->ship_in)){
                                        $ship_in = '$'.number_format($woosrpoitems->ship_in, 2);
                                    }
                                    echo $this->Form->control('ship_in', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'ship_in', 'value'=>$ship_in)); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Owner(for Stock Destination)</label>
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
                        <div class="col-md-6"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">MParts Cost for Part</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $mparts_cost = '$0.00';
                                    if(!empty($woosrpoitems->mparts_cost)){
                                        $mparts_cost = '$'.number_format($woosrpoitems->mparts_cost, 2);
                                    }
                                    echo $this->Form->control('mparts_cost', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'disabled'=>$poitemdisable, 'id'=>'mparts_cost', 'value'=>$mparts_cost)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">MParts Retail for Part</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $mparts_retail = '$0.00';
                                    if(!empty($woosrpoitems->mparts_retail)){
                                        $mparts_retail = '$'.number_format($woosrpoitems->mparts_retail, 2);
                                    }
                                    echo $this->Form->control('mparts_retail', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'disabled'=>$poitemdisable, 'id'=>'mparts_retail', 'value'=>$mparts_retail)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">MParts Location</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('part_location', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'disabled'=>$poitemdisable)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(!empty($isedit)){ ?>
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="tax_part">
                                <span class="form-check-label" for="flexCheckDefault">This has been received and processed (Do not modify unless absolutely necessary)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                }
                echo $this->Form->end(); 
                ?>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary saveWOOSRPOItemsBtn">Save</button>
            </div>
        </div>
    </div>
</div>