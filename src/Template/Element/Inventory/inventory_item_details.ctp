<style>
    .color-threshold-not-met {
        color: red;
    }
</style>
<div class="addPartBorder">
    <div class="invaddPageHeading">General Information</div>
    <div class="row mb-3">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Name:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems->name; ?></p>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Part Number:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems->part_number; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Is this item serialized?
                <i class="fa fa-info-circle" data-toggle="tooltip" title="If items are distinguished from one another by a serial number, select yes."></i>
                :</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo !empty($invenotryitems->is_this_item_serialized) ? 'Yes' : 'No'; ?></p>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Accepts Install? <i class="fa fa-info-circle" data-toggle="tooltip" title="Turning this on allows inventory to be installed to items of this type."></i>:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo !empty($invenotryitems->accept_install) ? 'Yes' : 'No'; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="item_type">Item Type
                    <i class="fa fa-info-circle" data-toggle="tooltip" data-html="true" rel="tooltip"  title="<div class=&quot;text-left item-type-options-tooltip&quot;>
                        Item Type Options: <br><br>
                        Consumable - Once used, cannot be used again.<br><br>
                        Expendable - Discarded at the end of it's lifecycle and cannot be overhauled.<br><br>
                        Rotable - Repaired or restored as new or overhauled until it is no longer repairable. <br><br>
                        Tool - Device used to complete a task.</div>">
                    </i>
                :</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $invItemType = unserialize(INVENTORY_ITEM_TYPE);
                    ?>
                    <p class="form-control-static"><?php echo !empty($invenotryitems->item_type) ? $invItemType[$invenotryitems->item_type] : ''; ?></p>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <!--div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Capital Equipment?<i class="fa fa-info-circle" data-toggle="tooltip" title="A capital item represents inventory in excess of a value to be determined by the operator."></i>:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form-check form-check-inline">
                        <?php echo $this->Form->radio('capital_equipment',  [
                            ['value' => '1', 'text' => 'Yes', 'label' => ['class' => 'form-check-input'], 'disabled'=>'disabled'],
                            ['value' => '0', 'text' => 'No', 'label' => ['class' => 'form-check-input'], 'disabled'=>'disabled'],
                        ]); ?>
                        
                    </div>
                </div>
            </div-->
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Safety Stock Threshold
                <?php if($invenotryitems->item_instock < $invenotryitems->safety_stock_threshold){ ?>    
                &nbsp;<i class="fa fa-exclamation-triangle color-threshold-not-met" title="Global safety stock threshold not met" data-toggle="tooltip"></i>
                <?php } ?>
                :</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems->safety_stock_threshold; ?></p>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Default UOM:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $defaultUOM = unserialize(DEFAULT_UOM);
                    ?>
                    <p class="form-control-static"><?php echo $defaultUOM[$invenotryitems->default_uom]; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Out Right Cost:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems->unit_cost; ?></p>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Currency:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    $currency = unserialize(CURRENCY);
                    ?>
                    <p class="form-control-static"><?php echo !empty($invenotryitems->currency) ? $currency[$invenotryitems->currency] : ''; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Core Charge:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems->exchange_cost; ?></p>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Rev:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems->rev; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Manufacturer:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo isset($invenotryitems['manufacturer']['name']) ? $invenotryitems['manufacturer']['name'] : ''; ?></p>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Weight:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems['weight']; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Exchange Price:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems['exchange_price']; ?></p>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Retail Price:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems['retail_price']; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Company Purchase Price:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems['company_purchase_price']; ?></p>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Overhauled Cost:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems['overhauled_cost']; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Description:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems->description; ?></p>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Notes:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems->notes; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-xs-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Tags
                    <i class="fa fa-info-circle" data-toggle="tooltip" title="This field can only be edited from the inventory item level"></i>
                :</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems->tags; ?></p>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Alternate Part Numbers:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotryitems->alternate_part_number; ?></p>
                </div>
                
            </div>
        </div>
    </div>
</div>