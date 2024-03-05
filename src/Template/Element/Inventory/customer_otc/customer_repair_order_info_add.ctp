<section class="top-form-section">
    <div class="row">
        <div class="col-md-6">
            <?php echo $this->Form->create($repairorderrates, array('class' => 'form-horizontal form-label-left', 'id' => 'frmCustRepairOrderRates')); ?>
            <div class="col-md-12">
                <input type="hidden" name="customer_id" value="<?php echo $inventorycustomers->id; ?>" />
                <input type="hidden" name="repair_order_rates_id" id="repair_order_rates_id" value="<?php echo @$repairorderrates->id; ?>" />
                <div class="form-group">
                    <label class="control-label" for="reference">Repair Order Rate</label>
                    <div class="form-input-frame">
                        <?php 
                        $repairOrderRates = unserialize(REPAIRORDERRATES);
                        echo $this->Form->control('repair_order_rate', array('options' => $repairOrderRates, 'empty' => '', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false, 'id' => 'repair_order_rate')); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pd0">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                        $labor_discountchk = '';
                        if(!empty($repairorderrates->labor_discount)){
                            $labor_discountchk = 'checked';
                        }
                        ?>
                        <input class="form-check-input repair_order_rates_notification" type="checkbox" value="1" id="labor_discount" name="labor_discount" <?php echo $labor_discountchk; ?> />
                        <span class="form-check-label" for="airframe_disabllabor_discounte_time_limit">Labor Discount</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                        $parts_discountchk = '';
                        if(!empty($repairorderrates->parts_discount)){
                            $parts_discountchk = 'checked';
                        }
                        ?>
                        <input class="form-check-input repair_order_rates_notification" type="checkbox" value="1" id="parts_discount" name="parts_discount" <?php echo $parts_discountchk; ?> />
                        <span class="form-check-label" for="parts_discount">Parts Discount</span>
                    </div>
                </div>
            </div>
            <div class="col-md-12 pd0">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('labor_discount_percentage', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'labor_discount_percentage')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('parts_discount_percentage', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'parts_discount_percentage')); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Notes</label>
                    <span class="label-chkbox-right">
                        <?php
                        $part_discount_over_costchk = '';
                        if(!empty($repairorderrates->part_discount_over_cost)){
                            $part_discount_over_costchk = 'checked';
                        }
                        ?>
                        <input class="form-check-input repair_order_rates_notification" type="checkbox" value="1" id="flexCheckDefault" name="part_discount_over_cost" <?php echo $part_discount_over_costchk; ?> />
                        <span class="form-check-label" for="flexCheckDefault">Parts Discount Is % Over Cost</span>
                    </span>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('notes', array('type'=>'textarea', 'class' => 'form-control aircraft-notes', 'label'=> false, 'style'=>'width: 542px; height: 246px;', 'id'=>'repair_order_info_notes')); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <?php
                    $show_notes_on_ro_createchk = '';
                    if(!empty($repairorderrates->show_notes_on_ro_create)){
                        $show_notes_on_ro_createchk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="show_notes_on_ro_create" name="show_notes_on_ro_create" <?php echo $show_notes_on_ro_createchk; ?> />
                    <span class="form-check-label" for="show_notes_on_ro_create">Show Notes on R/O Create</span>
                </div>
            </div>
            <div class="col-md-12">
                <button type="button" class="btn btn-primary float-right saveCustRepairOrderRates">Save</button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
        <div class="col-md-6">
            <label class="control-label" for="reference">Repair Order History</label>
            <div class="repair-order-info-tbl-scroll">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Repair Order</th>
                            <th scope="col">Date Created</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php
                        foreach($repairorderhistory as $repairorder){
                        ?>
                        <tr class="aircraftworkordertr" data-val="<?php echo $repairorder['id']; ?>">
                            <td><?php echo $repairorder['work_order_no']; ?></td>
                            <td><?php echo $repairorder['created_at']; ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<script>
    var saveCustomerRepairOrderRatesURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveCustomerRepairOrderRates']); ?>";
</script>