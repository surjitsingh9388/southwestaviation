<div class="addPartBorder">
    <input type="hidden" name="inventory_item_id" value="<?php echo $invenotries['_matchingData']['InventoryItems']['id']; ?>">
    <div class="invaddPageHeading">General Information</div>
    <div style="padding: 0 15px;">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <div class="form-group"> 
                    <label class="control-label col-sm-4 col-xs-12" for="plane_id">Location:</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <?php 
                        if($invenotries->status == 2){
                            if(isset($install_to['_matchingData']['InventoryItems']['name']) && !empty($install_to['_matchingData']['InventoryItems']['name'])){
                                $location = $install_to['_matchingData']['InventoryItems']['name'].' (PN:'.$install_to['_matchingData']['InventoryItems']['part_number'].') (SN:'.$install_to['serial_no'].')';
                                echo '<p class="form-control-static"><a class="dropdown-item" href="'.$this->Url->build(['controller'=>'Inventories', 'action'=>'detail', $install_to['id']]).'">'.$location.'</a></p>';
                            }else{
                                echo '<p class="form-control-static required">[Inactive]</p>';
                            }
                            
                        }else if(empty($invenotries->location_id) && $invenotries->status != 7 && $invenotries->status != 9){ ?>
                        <p class="form-control-static required">[Inactive]</p>
                        <?php }else{ ?>
                        <p class="form-control-static">
                            <?php echo isset($invenotries['_matchingData']['InventoryLocations']['location_name']) ? $invenotries['_matchingData']['InventoryLocations']['location_name'] : ''; ?>
                        </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="form-group">
                    <?php if($invenotries['_matchingData']['InventoryItems']['is_this_item_serialized'] == 1){ ?>
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Serial:</label>
                    <?php }else{ ?>
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Lot:</label>
                    <?php } ?>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <p class="form-control-static"><?php echo $invenotries->serial_no; ?></p>
                    </div>
                </div>
            </div>
        
            <div class="col-sm-6 col-xs-12">
                <div class="form-group"> 
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Display Name:</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <p class="form-control-static"><?php echo $invenotries->display_name; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="form-group"> 
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Cost:</label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <?php 
                        $currency = unserialize(CURRENCY);
                        ?>
                        <p class="form-control-static"><?php echo $invenotries->cost.' '.$currency[$invenotries->currency]; ?></p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xs-12">
                <div class="form-group"> 
                    <label class="control-label col-md-4 col-sm-4 col-xs-5" for="plane_id">Exchange Cost:</label>
                    <div class="col-md-2 col-sm-3 col-xs-7">
                        <p class="form-control-static"><?php echo $invenotries->exchange_cost; ?></p>
                    </div>

                    <label class="control-label col-md-2 col-sm-2 col-xs-5 label-cost" for="plane_id">UOM:</label>
                    <div class="col-md-4 col-sm-3 col-xs-7">
                        <?php 
                        $defaultUOM = unserialize(DEFAULT_UOM);
                        ?>
                        <p class="form-control-static"><?php echo !empty($invenotries->uom) ? $defaultUOM[$invenotries->uom] : ''; ?></p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xs-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-2 col-xs-3" for="plane_id">Qty :</label>
                    <p class="form-control-static col-md-3 col-sm-3 col-xs-9"><?php echo $invenotries->qty; ?></p>

                    <label class="control-label col-md-3 col-sm-3 col-xs-5" for="plane_id">Barcode:</label>
                    <div class="col-md-3 col-sm-4 col-xs-7">
                        <p class="form-control-static"><?php echo $invenotries->bar_code; ?></p>
                    </div>
                    
                   
                    <!--label class="control-label col-md-2 col-sm-4 col-xs-12" for="plane_id">Account Code:</label>
                    
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <p class="form-control-static"><?php //echo $invenotries->account_code; ?></p>
                    </div-->
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="form-group"> 
                    <label class="control-label col-md-2 col-sm-4 col-xs-12" for="plane_id">ATA Chapter:</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <p class="form-control-static">
                            <?php
                                $atacodedet = explode('-', $ataCode);
                                echo $atacodedet[0];
                            ?>
                        </p>
                    </div>

                    <label class="control-label col-md-3 col-sm-3 col-xs-5" for="plane_id">Supplier:</label>
                    <div class="col-md-3 col-sm-4 col-xs-7">
                        <p class="form-control-static"><?php echo isset($invenotries['_matchingData']['InventoryVendors']['name']) ? $invenotries['_matchingData']['InventoryVendors']['name'] : ''; ?></p>
                    </div>
                    
                   
                    <!--label class="control-label col-md-2 col-sm-4 col-xs-12" for="plane_id">Account Code:</label>
                    
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <p class="form-control-static"><?php //echo $invenotries->account_code; ?></p>
                    </div-->
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <label class="control-label col-md-2 col-sm-4 col-xs-12" for="plane_id">Revision:</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <p class="form-control-static">
                        <?php echo $invenotries->revision; ?>
                    </p>
                </div>
                <label class="control-label col-md-2 col-sm-4 col-xs-12" for="plane_id">Condition:</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                        <?php 
                            $inventorycondition = unserialize(INVENTORY_CONDITION);
                        ?>
                    <p class="form-control-static"><?php echo !empty($invenotries->conditions) ? $inventorycondition[$invenotries->conditions] : ''; ?></p>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <label class="control-label col-md-2 col-sm-4 col-xs-12" for="plane_id">Warranty Exp.:</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <p class="form-control-static"><?php echo !empty($invenotries->warranty_expire) ? date('d-M-Y', strtotime($invenotries->warranty_expire)) : ''; ?></p>
                </div>
                <label class="control-label col-md-2 col-sm-4 col-xs-12" for="plane_id">Expiration:</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <p class="form-control-static">
                        <?php echo !empty($invenotries->expiration) ? date('d-M-Y', strtotime($invenotries->expiration)) : ''; ?></p>
                    </p>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <label class="control-label col-md-2 col-sm-4 col-xs-12" for="plane_id">Received:</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <p class="form-control-static"><?php echo !empty($invenotries->received) ? date('d-M-Y', strtotime($invenotries->received)) : ''; ?></p>
                </div>
                <label class="control-label col-md-2 col-sm-4 col-xs-12" for="plane_id">Exchange Price:</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotries['exchange_price']; ?></p>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Retail Price:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotries['retail_price']; ?></p>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Company Purchase Price:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotries['overhauled_cost']; ?></p>
                </div>
            </div>

            <div class="col-sm-6 col-xs-12">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Overhauled Cost:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotries['retail_price']; ?></p>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Tag <i class="fa fa-info-circle" data-toggle="tooltip" title="This field can only be edited from the inventory item level"></i>:</label>
                
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotries->tags; ?></p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xs-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Capital Equipment?:<i class="fa fa-info-circle" data-toggle="tooltip" title="A capital item represents inventory in excess of a value to be determined by the operator."></i></label>
                
                <div class="col-md-8 col-sm-8 col-xs-12" style="padding-top:8px;">
                    <?php echo !empty($invenotries->capital_equipment) ? 'Yes' : 'No'; ?>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xs-12">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Notes:</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <p class="form-control-static"><?php echo $invenotries->notes; ?></p>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xs-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Tear Down Assest:</label>
                
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList" style="padding-top:8px;">
                    <?php echo !empty($invenotries->tear_down_assest) ? 'Yes' : 'No'; ?>
                </div>
            </div>
        </div>
        </div>
        
       
    </div>

    
</div>