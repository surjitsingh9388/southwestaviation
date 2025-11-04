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
    <div class="inventorySection" style="padding: 15px;">
        <div class="row mt10 customRow">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Location:&nbsp;<span class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0">
                        <?php
                        if(!empty($invenotries->id)){
                        echo '<p class="form-control-static loc-det-error-msg">Uninstall, Transfer, or Error Correct to change location</p>'; 
                        }else if(empty($invenotries->id) || !empty($invenotries->location_id)){
                            echo $this->Form->control('location_id', array('options' => $location, 'empty' => 'Enter a location ...', 'class' => 'form-control col-md-10 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'location_id', 'required' => 'required'));
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix">
                    <?php if($invenotryitems->is_this_item_serialized == 1){ ?>
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="airframe_component_id">Serial:&nbsp;<span class="required">*</span></label>
                        <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                        <?php echo $this->Form->control('serial_no', array('class'=>'form-control col-md-9', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'serial_no')); ?>
                        </div>
                    <?php }else{ ?>
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="airframe_component_id">Lot:</label>
                        <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                        <?php echo $this->Form->control('serial_no', array('class'=>'form-control col-md-9', 'placeholder' => '', 'label' => false, 'id'=>'serial_no')); ?>
                        </div>
                    <?php } ?>
                    
                </div>
            </div>
        
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Display Name:</label>
                    
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                        <?php echo $this->Form->control('display_name', array('class'=>'form-control col-md-7', 'placeholder' => '', 'label' => false)); ?>
                    </div>
                </div>
            </div>
                
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Cost:</label>
                    
                    <div class="col-md-7 col-sm-6 p-0" id="airCompsList">
                        <?php 
                        $cost = !empty($invenotries->cost) ? $invenotries->cost : $invenotryitems->unit_cost;
                        $cost = (!empty($cost) && (float)$cost > 0) ? '$' . number_format((float)$cost, 2) : '';

                        echo $this->Form->control('cost', array('type'=>'text', 'class'=>'form-control col-md-7', 'placeholder' => '$0.00', 'label' => false, 'value'=>$cost, 'id'=>'inventory_cost')); ?>
                    </div>
                    <div class="col-md-2 col-sm-3" style="padding-right:0px;">
                        <div class="form-group clearfix"> 
                            <?php 
                            $currency = unserialize(CURRENCY);
                            $currencyval = !empty($invenotries->currency) ? $invenotries->currency : $invenotryitems->currency;
                            echo $this->Form->control('currency', array('options' => $currency, 'class' => 'form-control col-md-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'currency', 'value'=>$currencyval)); 
                            ?>
                        </div>
                    </div>
                </div>
            </div>


            <!-- <div class="col-md-6 col-sm-6 col-xs-12"> -->
                <?php if(!isset($action)){ ?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group clearfix"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Exchange Cost:</label>
                            
                            <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                                <?php 
                                $exchange_cost = !empty($invenotries->exchange_cost) ? $invenotries->exchange_cost : $invenotryitems->exchange_cost;
                                $exchange_cost = (!empty($exchange_cost) && (float)$exchange_cost > 0) ? '$' . number_format((float)$exchange_cost, 2) : '';

                                echo $this->Form->control('exchange_cost', array('type'=>'text', 'class'=>'form-control col-md-6', 'placeholder' => '$0.00', 'label' => false, 'value'=>$exchange_cost, 'id'=>'exchange_cost')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group clearfix"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">UOM:&nbsp;<span class="required">*</span></label>
                            
                            <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                                <?php 
                                    $defaultUOM = unserialize(DEFAULT_UOM);
                                    $default_uom = !empty($invenotries->default_uom) ? $invenotries->default_uom : $invenotryitems->default_uom;

                                    echo $this->Form->control('uom', array('options' => $defaultUOM, 'empty' => 'Enter a amount of measure ...', 'class' => 'form-control col-md-7 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false, 'id' => 'uom', 'value'=>$default_uom)); 
                                ?>
                            </div>
                        </div>
                    </div>
                    
                <?php if($invenotryitems->is_this_item_serialized == 0){ ?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group clearfix"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Quantity:<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                                <?php echo $this->Form->control('qty', array('class'=>'form-control col-md-7', 'placeholder' => '0.00', 'label' => false, 'id'=>'inventory_qty')); ?>
                            </div>
                        </div>
                    </div>
                <?php }else{ ?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group clearfix"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Quantity:&nbsp;<span class="required">*</span></label>
                            <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                                <?php echo $this->Form->control('qty', array('class'=>'form-control col-md-7', 'placeholder' => '0.00', 'label' => false, 'id'=>'inventory_qty')); ?>
                            </div>
                        </div>
                    </div>
                <?php }}else{ ?>
                        <div class="form-group clearfix"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Exchange Cost:</label>
                            <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                                <?php 
                                $exchange_cost = !empty($invenotries->exchange_cost) ? $invenotries->exchange_cost : $invenotryitems->exchange_cost;
                                $exchange_cost = (!empty($exchange_cost) && (float)$exchange_cost > 0) ? '$' . number_format((float)$exchange_cost, 2) : '';
                                echo $this->Form->control('exchange_cost', array('type'=>'text', 'class'=>'form-control col-md-7', 'placeholder' => '$0.00', 'label' => false, 'value'=>$exchange_cost, 'id'=>'exchange_cost')); ?>
                            </div>
                        </div>
                   

                <?php } ?>
            <!-- </div> -->
      
         
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Barcode:</label>
                    
                    <div id="airCompsList" class="col-md-9 col-sm-9 col-xs-12 p-0">
                        <?php echo $this->Form->control('bar_code', array('class'=>'form-control', 'placeholder' => '', 'label' => false)); ?>
                    </div>
                </div>
            </div>
                
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">ATA Chapter:</label>
                    
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                        <?php 
                            echo $this->Form->control('ata_chapter', array('options' => $ataCode, 'empty' => 'Enter a code ...', 'class' => 'form-control col-md-7 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'ata_chapter')); 
                        ?>
                    </div>
                </div>

                <!--label class="control-label col-md-3" for="plane_id">Account Code:</label>
                
                <div class="col-md-3" id="airCompsList">
                    <?php 
                        //echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Enter an account code ...', 'class' => 'form-control col-md-4 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code')); 
                    ?>
                </div-->
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Supplier:</label>
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                        <div class="input-group inputWrap" style="margin-bottom:0px;">
                           <?php 
                            echo $this->Form->control('vendor', array('options' => $vendor, 'empty' => 'Enter a vendor ...', 'class' => 'form-control col-md-6 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'vendor')); 
                             ?>
                            <span class="input-group-btn">
                                <button class="btn btn-primary vendorModelbtn" type="button" style="margin:0px;">
                                <i class="fa fa-plus"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12 label-cost" for="plane_id" >Revision:</label>
                    
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                        <?php echo $this->Form->control('revision', array('class'=>'form-control col-md-9', 'placeholder' => '', 'label' => false)); ?>
                    </div>
                </div>
            </div>
           
        
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Condition:</label>
                    
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                        <?php 
                            $inventorycondition = unserialize(INVENTORY_CONDITION);
                            echo $this->Form->control('conditions', array('options' => $inventorycondition, 'empty' => 'Enter condition ...', 'class' => 'form-control col-md-7 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'conditions')); 
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Warranty Exp.:</label>
                    
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('warranty_expire', array('class' => 'form-control col-md-10', 'id' => 'warranty_expire', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
          
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Expiration:</label>
                    
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('expiration', array('class' => 'form-control col-md-10', 'id' => 'expiration', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Received:</label>
                    
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList" >
                        <?php if(isset($action) && $action == 'edit'){ ?>
                            <p class="form-control-static"><?php echo !empty($invenotries->received) ? date('d-M-Y', strtotime($invenotries->received)) : ''; ?></p>
                        <?php }else{ ?>
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('received', array('class' => 'form-control col-md-7', 'id' => 'received', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
     
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Exchange Price:</label>
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0">
                        <?php 
                        $exchange_price = '';
                        if(!empty($invenotries->exchange_price)){
                            $exchange_price = '$'.$invenotries->exchange_price;
                        }
                        echo $this->Form->control('exchange_price', array('type'=>'text', 'class'=>'form-control col-md-10', 'placeholder' => '$0.00', 'label' => false, 'id'=>'exchange_price', 'value'=>$exchange_price)); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="airframe_component_id">Retail Price:</label>
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                        <?php 
                        $retail_price = '';
                        if(!empty($invenotries->retail_price)){
                            $retail_price = '$'.$invenotries->retail_price;
                        }
                        echo $this->Form->control('retail_price', array('type'=>'text', 'class'=>'form-control col-md-10', 'placeholder' => '$0.00', 'label' => false, 'id'=>'retail_price', 'value'=>$retail_price)); ?>
                    </div>
                </div>
            </div>
   
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Company Purchase Price:</label>
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0">
                        <?php 
                        $company_purchase_price = '';
                        if(!empty($invenotries->company_purchase_price)){
                            $company_purchase_price = '$'.$invenotries->company_purchase_price;
                        }
                        echo $this->Form->control('company_purchase_price', array('type'=>'text', 'class'=>'form-control col-md-10', 'placeholder' => '$0.00', 'label' => false, 'id'=>'company_purchase_price', 'value'=>$company_purchase_price)); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="airframe_component_id">Overhauled Cost:</label>
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                        <?php 
                        $overhauled_cost = '';
                        if(!empty($invenotries->overhauled_cost)){
                            $overhauled_cost = '$'.$invenotries->overhauled_cost;
                        }
                        echo $this->Form->control('overhauled_cost', array('type'=>'text', 'class'=>'form-control col-md-10', 'placeholder' => '$0.00', 'label' => false, 'id'=>'overhauled_cost', 'value'=>$overhauled_cost)); ?>
                    </div>
                </div>
            </div>
    
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Tag <i class="fa fa-info-circle" data-toggle="tooltip" title="This field can only be edited from the inventory item level"></i>:</label>
                    
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0">
                        <?php echo $this->Form->control('tags', array('class'=>'form-control col-md-10 label-width-auto', 'placeholder' => '', 'label' => false, 'style'=>'width:71% !important;')); ?>
                        <span>&nbsp;(Hit enter to add tags)</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Capital Equipment?:<i class="fa fa-info-circle" data-toggle="tooltip" title="A capital item represents inventory in excess of a value to be determined by the operator."></i></label>
                    
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
                        <div class="form-check form-check-inline">
                            <?php echo $this->Form->radio('capital_equipment',  [
                                ['value' => '1', 'text' => 'Yes', 'label' => ['class' => 'form-check-input']],
                                ['value' => '0', 'text' => 'No', 'label' => ['class' => 'form-check-input']],
                            ]); ?>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
     
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="airframe_component_id">Notes:</label>
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0">
                    <?php echo $this->Form->control('notes', array('class' => 'form-control col-md-10', 'label'=> false, 'rows'=>2)); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group clearfix"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Tear Down Assest:</label>
                    
                    <div class="col-md-9 col-sm-9 col-xs-12 p-0" id="airCompsList">
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
    
</div>