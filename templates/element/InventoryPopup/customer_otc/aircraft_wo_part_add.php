<div id="aircraftWOAddPartModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 65%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Add Part</h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create($aircraftwoitemparts, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOItemParts'));
                ?>
                <input type="hidden" name="wo_item_id" id="part_wo_item_id" value="<?php echo $wo_item_id; ?>" />
                <input type="hidden" name="wo_item_part_id" id="wo_item_part_id" value="<?php echo @$aircraftwoitemparts->id; ?>" />

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="reference">Part No.</label>
                            <div class="form-input-frame">
                                <?= $this->Form->control('part_number', [
                                    'options' => $invpartnumbers,
                                    'label' => false,
                                    'class' => 'form-control',  // This ensures it works with Select2
                                    'id' => 'wo_item_part_number',  // Ensure the ID is set correctly
                                    'empty' => 'Select Part No.'
                                ]) ?>
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Part Name</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('wo_item_part_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>"wo_item_part_name", 'readonly'=>'readonly')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Part Description</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('part_description', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'style'=>'height: 103px;', 'id'=>'wo_item_part_description')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-default wo-itempart-addpart-btn" data-val="part-add" style="width:100%;">Add Part</button>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-default wo-itempart-addpartclose-btn" data-val="part-add-close" style="width:100%;">Add Part & Close</button>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="width:100%;">Close</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3" style="padding-right:0px;">
                        <div class="col-md-12 pd0">
                            <div class="col-md-6 pd0">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Qty Needed</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('qty_needed', array('type'=>'number','class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>'1')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Qty Used</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('qty_used', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_qty_used', 'placeholder'=>'0')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="col-md-12 pd0">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Qty Stk</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('qty_stock', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'wo_item_part_qty_stock', 'placeholder'=>'0')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 pd0">
                                <div class="form-group">
                                    <label class="control-label" for="reference">Qty Cust.</label>
                                    <div class="form-input-frame">
                                        <?php echo $this->Form->control('qty_cust_owned', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Price Each</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('price_each', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'price_each')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">
                                <?php
                                $give_discountchk = '';
                                if(!empty($aircraftwoitemparts->give_discount_percentage)){
                                    $give_discountchk = 'checked';
                                }
                                ?>
                                <input class="form-check-input" type="checkbox" value="1" id="give_discount" name="give_discount" <?php echo $give_discountchk; ?>>
                                <span class="form-check-label" for="give_discount">Give Discount</span>
                            </label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('give_discount_percentage', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00%', 'id'=>'give_discount_percentage')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Total</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('part_total_prices', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'part_total_prices', 'readonly'=>'readonly')); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-check">
                            <?php
                            $part_taxablechk = '';
                            if(!empty($aircraftwoitemparts->part_taxable)){
                                $part_taxablechk = 'checked';
                            }
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="part_taxable" <?php echo $part_taxablechk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">Taxable</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <?php
                            $not_deduct_from_stockchk = '';
                            if(!empty($aircraftwoitemparts->not_deduct_from_stock)){
                                $not_deduct_from_stockchk = 'checked';
                            }
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="not_deduct_from_stock" <?php echo $not_deduct_from_stockchk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">Do not deduct from stock</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <?php
                            $is_loanerchk = '';
                            if(!empty($aircraftwoitemparts->is_loaner)){
                                $is_loanerchk = 'checked';
                            }
                            ?>
                            <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" name="is_loaner" <?php echo $is_loanerchk; ?>>
                            <span class="form-check-label" for="flexCheckDefault">Is Loaner</span>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Condition</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('part_conditions', array('options' => $conditions, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_part_conditions'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Serial Number</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('serial_number', array('options' => $serialno, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_serial_number'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Date Needed</label>
                            <div class="form-input-frame">
                                <div class="input-group date datePicker">
                                    <?php
                                    $date_needed = '';
                                    if(!empty($aircraftwoitemparts->date_needed)){
                                        $date_needed = date('m-d-Y', strtotime($aircraftwoitemparts->date_needed));
                                    }else{
                                        $date_needed = date('m-d-Y');
                                    }
                                    echo $this->Form->Text('date_needed', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_item_part_date_needed', 'placeholder' => '', 'label' => false, 'value'=>$date_needed)); 
                                    ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Ship In</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('part_ship_in', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'wo_item_part_part_ship_in')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Ship Out</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('part_ship_out', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'wo_item_part_part_ship_out')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="headings">
                    <h5 class="title">Parts Information for MParts</h5>
                    <span class="hr"></span>
                </div>

                <div class="row">
                    <div class="col-md-4" style="padding-right:0px;">
                        <div class="form-group">
                            <label class="control-label" for="reference">General Location</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('general_location', array('options' => $locationlist, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_part_general_location'));
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Qty On Other OTC Quotes</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('qty_on_other_otc_quotes', array('type'=>'number','class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label" for="reference">Hazardous Notes</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('hazardous_notes', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'style'=>'height: 103px;')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Cost In MParts</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('cost_in_mparts', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'wo_item_part_cost')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="reference">Supplier</label>
                                <div class="form-input-frame">
                                    <?php
                                    echo $this->Form->control('supplier', array('options' => $vendorlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_part_vendor'));
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Retail Price in MParts</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('general_retail', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_general_retail')); ?>
                                </div>
                            </div>
                        
                            <div class="form-group">
                                <label class="control-label" for="reference">Discount Code</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('discount_code', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                </div>
                            </div>
                        </div>

                      <div class="col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Notes</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('notes', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'style'=>'height: 103px;')); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Dealer Price</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('dealer_price', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'dealer_price')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Core Charge</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('core_charges', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Unit of Measure</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('unit_measure', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_unit_measure')); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="reference">Weight</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('part_weight', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_item_part_weight')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 10px;">Update Descrip</button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 5px;">Show Qty Info</button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 5px;">Show Alt #s</button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 5px;">Find Part</button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 5px;">Show All S/Ns</button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 5px;">Show Family</button>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default" style="width:131px;margin-top: 5px;">Kit Analyzer</button>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>



<!-- Initialize Select2 -->
<script>
$(document).ready(function () {
    const $partSelect = $('#wo_item_part_number');
    const $partName = $('#wo_item_part_name');

    // Initialize select2
    $partSelect.select2({
        tags: true,
        placeholder: 'Select or enter a part number',
        allowClear: true,
        width: '100%',
        createTag: function (params) {
            let term = $.trim(params.term).toLowerCase();
            let exists = false;

            // Check if already in the options
            $('#wo_item_part_number option').each(function () {
                if ($(this).text().toLowerCase() === term) {
                    exists = true;
                    return false; // break loop
                }
            });

            if (exists || term === '') {
                return null;
            }

            return {
                id: params.term,
                text: params.term,
                newOption: true
            };
        }
    });

    // Detect change/selection
    $partSelect.on('select2:select', function (e) {
        const selectedText = e.params.data.text.toLowerCase();
        let found = false;

        // Check if selected text exists in original options
        $partSelect.find('option').each(function () {
            if ($(this).text().toLowerCase() === selectedText && !$(this).is('[data-select2-tag="true"]')) {
                found = true;
                return false;
            }
        });

        if (found) {
            $partName.prop('readonly', true);
        } else {
            $partName.prop('readonly', false);
        }
    });

    // Optional: clear input resets readonly
    $partSelect.on('select2:clear', function () {
        $partName.prop('readonly', true);
    });
});

</script>
