<div class="addPartBorder">
    <input type="hidden" name="inventory_item_id" value="<?php echo $invenotryitems->id; ?>">
    <div class="invaddPageHeading">
        <?php 
        if(isset($isdetailpage)){
            echo 'General Information';
        }else{
            echo 'Primary Information';
        } ?>
    </div>
    <div class="row mt10">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4" for="plane_id">Location:&nbsp;<span class="required">*</span></label>
                <div class="col-md-8">
                    <?php
                    if(!empty($invenotries->id)){
                       echo '<p class="form-control-static loc-det-error-msg">Uninstall, Transfer, or Error Correct to change location</p>'; 
                    }else if(empty($invenotries->id) || !empty($invenotries->location_id)){
                        echo $this->Form->control('location_id', array('options' => $location, 'empty' => 'Enter a location ...', 'class' => 'form-control col-md-8 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'location_id', 'required' => 'required'));
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php if($invenotryitems->is_this_item_serialized == 1){ ?>
                    <label class="control-label col-md-4" for="airframe_component_id">Serial:&nbsp;<span class="required">*</span></label>
                    <div class="col-md-8" id="airCompsList">
                    <?php echo $this->Form->control('serial_no', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'serial_no')); ?>
                    </div>
                <?php }else{ ?>
                    <label class="control-label col-md-4" for="airframe_component_id">Lot:</label>
                    <div class="col-md-8" id="airCompsList">
                    <?php echo $this->Form->control('serial_no', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'id'=>'serial_no')); ?>
                    </div>
                <?php } ?>
                
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-6">
                <div class="form-group"> 
                    <label class="control-label col-md-4" for="plane_id">Display Name:</label>
                    
                    <div class="col-md-8 col-sm-8" id="airCompsList">
                        <?php echo $this->Form->control('display_name', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false)); ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group"> 
                    <label class="control-label col-md-4 label-cost" for="plane_id">Cost:</label>
                    
                    <div class="col-md-8" id="airCompsList">
                        <?php 
                        $cost = !empty($invenotries->cost) ? $invenotries->cost : $invenotryitems->unit_cost;
                        echo $this->Form->control('cost', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'value'=>$cost)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group"> 
                    <?php 
                    $currency = unserialize(CURRENCY);
                    $currencyval = !empty($invenotries->currency) ? $invenotries->currency : $invenotryitems->currency;
                    echo $this->Form->control('currency', array('options' => $currency, 'class' => 'form-control col-md-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'currency', 'value'=>$currencyval)); 
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group"> 
                
                <?php if(!isset($action)){ ?>
                <label class="control-label col-md-4" for="plane_id">Exchange Cost:</label>
                
                <div class="col-md-4" id="airCompsList">
                    <?php 
                    $exchange_cost = !empty($invenotries->exchange_cost) ? $invenotries->exchange_cost : $invenotryitems->exchange_cost;
                    echo $this->Form->control('exchange_cost', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'value'=>$exchange_cost)); ?>
                </div>
                
                <label class="control-label col-md-4 label-cost" for="plane_id">UOM:&nbsp;<span class="required">*</span></label>
                
                <div class="col-md-8" id="airCompsList">
                    <?php 
                        $defaultUOM = unserialize(DEFAULT_UOM);
                        $default_uom = !empty($invenotries->default_uom) ? $invenotries->default_uom : $invenotryitems->default_uom;

                        echo $this->Form->control('uom', array('options' => $defaultUOM, 'empty' => 'Enter a amount of measure ...', 'class' => 'form-control col-md-8 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false, 'id' => 'uom', 'value'=>$default_uom)); 
                    ?>
                </div>
                <?php if($invenotryitems->is_this_item_serialized == 0){ ?>
                    <label class="control-label col-md-4 label-width-auto" for="plane_id">Quantity:&nbsp;<span class="required">*</span></label>
                    <div class="col-md-8" id="airCompsList">
                    <?php echo $this->Form->control('qty', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false)); ?>
                    </div>
                <?php }else{ ?>
                    <label class="control-label col-md-4 label-width-auto" for="plane_id">Quantity 1</label>
                <?php }}else{ ?>
                <label class="control-label col-md-4" for="plane_id">Exchange Cost:</label>
                
                <div class="col-md-8" id="airCompsList">
                    <?php 
                    $exchange_cost = !empty($invenotries->exchange_cost) ? $invenotries->exchange_cost : $invenotryitems->exchange_cost;
                    echo $this->Form->control('exchange_cost', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false, 'value'=>$exchange_cost)); ?>
                </div>

                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-6">
                <div class="form-group"> 
                    <label class="control-label col-md-4" for="plane_id">Barcode:</label>
                    
                    <div class="col-md-8" id="airCompsList">
                        <?php echo $this->Form->control('bar_code', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false)); ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group"> 
                    <label class="control-label col-md-4" for="plane_id">ATA Chapter:</label>
                    
                    <div class="col-md-8" id="airCompsList">
                        <?php 
                            echo $this->Form->control('ata_chapter', array('options' => $ataCode, 'empty' => 'Enter a code ...', 'class' => 'form-control col-md-8 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'ata_chapter')); 
                        ?>
                    </div>
                </div>

                <!--label class="control-label col-md-2" for="plane_id">Account Code:</label>
                
                <div class="col-md-3" id="airCompsList">
                    <?php 
                        //echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Enter an account code ...', 'class' => 'form-control col-md-4 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code')); 
                    ?>
                </div-->
                
            </div>
        </div>

        <div class="col-md-6">
            <div class="col-md-6">
                <div class="form-group"> 
                    <label class="control-label col-md-4" for="plane_id">Supplier:</label>
                    
                    <div class="col-md-6 col-sm-7 supplier-content" id="airCompsList">
                        <?php 
                            echo $this->Form->control('vendor', array('options' => $vendor, 'empty' => 'Enter a vendor ...', 'class' => 'form-control col-md-7 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'vendor')); 
                        ?>
                    </div>
                    <div class="col-md-1 col-sm-1 plus-new-btn vendor-add-btn" style="padding-left:0px !important;float: left;margin-top: 0px;"><button class="btn btn-primary vendorModelbtn" type="button"><i class="fa fa-plus"></i></button></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group"> 
                    <label class="control-label col-md-4 label-width-auto" for="plane_id" >Revision:</label>
                    
                    <div class="col-md-8 revision-content" id="airCompsList">
                        <?php echo $this->Form->control('revision', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-6">
                <div class="form-group"> 
                    <label class="control-label col-md-4" for="plane_id">Condition:</label>
                    
                    <div class="col-md-8" id="airCompsList">
                        <?php 
                            $inventorycondition = unserialize(INVENTORY_CONDITION);
                            echo $this->Form->control('conditions', array('options' => $inventorycondition, 'empty' => 'Enter condition ...', 'class' => 'form-control col-md-8 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'conditions')); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group"> 
                    <label class="control-label col-md-4" for="plane_id">Warranty Exp.:</label>
                    
                    <div class="col-md-8" id="airCompsList">
                        
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('warranty_expire', array('class' => 'form-control col-md-8', 'id' => 'warranty_expire', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="col-md-6">
                <div class="form-group"> 
                    <label class="control-label col-md-4" for="plane_id">Expiration:</label>
                    
                    <div class="col-md-8" id="airCompsList">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('expiration', array('class' => 'form-control col-md-8', 'id' => 'expiration', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="col-md-6">
                <div class="form-group"> 
                    <label class="control-label col-md-4" for="plane_id">Received:</label>
                    
                    <div class="col-md-8" id="airCompsList">
                        <?php if(isset($action) && $action == 'edit'){ ?>
                            <p class="form-control-static"><?php echo !empty($invenotries->received) ? date('d-M-Y', strtotime($invenotries->received)) : ''; ?></p>
                        <?php }else{ ?>
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('received', array('class' => 'form-control col-md-8', 'id' => 'received', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4" for="plane_id">Exchange Price:</label>
                <div class="col-md-8">
                    <?php echo $this->Form->control('exchange_price', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4" for="airframe_component_id">Retail Price:</label>
                <div class="col-md-8" id="airCompsList">
                    <?php echo $this->Form->control('retail_price', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4" for="plane_id">Company Purchase Price:</label>
                <div class="col-md-8">
                    <?php echo $this->Form->control('company_purchase_price', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4" for="airframe_component_id">Overhauled Cost:</label>
                <div class="col-md-8" id="airCompsList">
                    <?php echo $this->Form->control('overhauled_cost', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4" for="plane_id">Tag <i class="fa fa-info-circle" data-toggle="tooltip" title="This field can only be edited from the inventory item level"></i>:</label>
                
                <div class="col-md-8">
                    <?php echo $this->Form->control('tags', array('class'=>'form-control col-md-8 label-width-auto', 'placeholder' => '', 'label' => false)); ?>
                    <span>(Hit enter to add tags)</span>
                </div>
                
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4" for="plane_id">Capital Equipment?:<i class="fa fa-info-circle" data-toggle="tooltip" title="A capital item represents inventory in excess of a value to be determined by the operator."></i></label>
                
                <div class="col-md-8" id="airCompsList">
                    <div class="form-check form-check-inline">
                        <?php echo $this->Form->radio('capital_equipment',  [
                            ['value' => '1', 'text' => 'Yes', 'label' => ['class' => 'form-check-input']],
                            ['value' => '0', 'text' => 'No', 'label' => ['class' => 'form-check-input']],
                        ]); ?>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4" for="airframe_component_id">Notes:</label>
                <div class="col-md-8">
                <?php echo $this->Form->control('notes', array('class' => 'form-control col-md-8', 'label'=> false, 'rows'=>2)); ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4" for="plane_id">Tear Down Assest:</label>
                
                <div class="col-md-8" id="airCompsList">
                    <div class="form-check form-check-inline">
                        <?php echo $this->Form->radio('tear_down_assest',  [
                            ['value' => '1', 'text' => 'Yes', 'label' => ['class' => 'form-check-input']],
                            ['value' => '0', 'text' => 'No', 'label' => ['class' => 'form-check-input']],
                        ]); ?>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
</div>