
<div id="cost-adjust-info-alert" class="alert alert-info">
    Cost adjustments will only be applied to the selected received inventory for the purchase order line item. No updates will be made to the purchase order or the purchase order line item. Please edit the purchase order if you would like to update line item cost.
</div>
<?php
echo $this->Form->create(null, array('url'=>['controller' => 'Inventories', 'action'=>'updateInventoryCost'], 'class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryAdjustCost', 'autocomplete'=>'off'));
?> 
<div class="costs row" style="margin-bottom: 10px;">
    <div class="col-sm-12">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-sm-4 control-label">
                    Unit Price
                </label>
                <div class="col-sm-8 form-label-input-wrapper">
                    <p class="form-control-static"><?php echo $invitmcost; ?></p>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-sm-4 control-label">New Cost&nbsp;<span class="required">*</span></label>
                <div class="col-sm-8 form-label-input-wrapper">
                    <input type="number" autocomplete="off" class="form-control" value="<?php echo $invitmcost; ?>" id="invadjustnewcost" name="invadjustnewcost">
                </div>
            </div>
        </div>
    </div>
</div>

<div scrodal="" class="physical-inventory-selection-container" style="height: 186.9px;overflow-y: auto;">
    
    <div class="table-responsive">
        <table class="table data-table">
            <thead>
                <tr>
                    <th class="check"><input type="checkbox" name="air_check" id="ckbCheckAllPopup"></th>
                    <th>Part</th>
                    <th>Lot/Serial</th>
                    <th class="text-right">Qty.</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Currency</th>
                    <th class="text-right cost-column">
                        <div class="cost-column">
                            Current Cost
                        </div>
                    </th>
                    <th class="text-right cost-column">
                        <div class="cost-column">
                            Adj. Cost
                        </div>
                    </th>
                    <th class="text-right cost-column">
                        <div class="cost-column">
                            Diff.
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($invpodata as $invporec){
                ?>
                <tr style="cursor: pointer;">
                    <td class="check"><input type="checkbox" class="chkBoxClsPopup" name="ids[]" value="<?php echo $invporec['inv']['id']; ?>"></td>
                    <td><?php echo $invporec['invitms']['part_number']; ?></td>
                    <td><?php echo $invporec['inv']['serial_no']; ?></td>
                    <td class="text-right"><?php echo $invporec['inv']['qty']; ?></td>
                    <td><?php echo $invporec['invloc']['location_name']; ?></td>
                    <td>Active</td>
                    <td><?php echo $invporec['inv']['currency']; ?></td>
                    <td class="text-right">
                        <div class="cost-column invcurrentcost">
                            <?php echo number_format((float)$invporec['inv']['cost'], 2, '.', ''); ?>
                        </div>
                    </td>
                    <td class="text-right">
                        <div class="cost-column invadjustcost">
                            <?php echo number_format((float)$invporec['inv']['cost'], 2, '.', ''); ?>
                        </div>
                    </td>
                    <td class="text-right increase">
                        <div class="cost-column invadjustdiff">0.00</div>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php 
echo $this->Form->end(); 
?>