<?php
$start = 1;
foreach($invitemnotrec as $key=>$invitem){
?>
<div class="addPartBorder" id="tab-<?php echo $start; ?>" <?php if($start > 1){ ?>style="display:none;" <?php } ?>>
    <h2 class="heading" style="color:black; padding-left: 15px;"><?php echo $invitem['invitms']['name'].' (PN: '.$invitem['invitms']['part_number'].') (SN:'.$invitem['inv']['serial_no'].')';?></h2>
    
    <div class="row">
        <div class="col-md-6">
            <div class="invaddPageHeading">General Information</div>
            <input type="hidden" name="inventory_ro_id[]" value="<?php echo $invitem['inventory_ro_id']; ?>">
            <input type="hidden" name="inventory_ro_item_id[]" value="<?php echo $invitem['id']; ?>">
            <input type="hidden" name="inventory_id[]" value="<?php echo $invitem['inv']['id']; ?>">
        
            <div class="form-group  mt10"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Quantity&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('qty[]', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12 qty', 'placeholder' => '', 'label' => false, 'value'=>'0','id'=>'rec_qty')); ?>
                </div>
            </div>
            
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Status&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php 
                    $repairReceivedStatus = unserialize(REPAIR_RECEIVED_STATUS);
                    echo $this->Form->control('status[]', array('options' => $repairReceivedStatus, 'empty' => 'Select status', 'class' => 'form-control col-md-4 col-xs-12 selectpicker status', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'value'=>1));  
                    ?>
                </div>
            </div>

            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Part Number&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php
                    echo $this->Form->control('inventory_item_id[]', array('options' => $invitemdropdown, 'empty' => 'Select part number ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker inventory_item_id', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'inventory_item_id', 'required' => 'required', 'value'=>$invitem['invitms']['id'])); 
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Serial</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('serial[]', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'disabled'=>'disabled')); ?>
                </div>
            </div>

            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Location&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php
                    echo $this->Form->control('location_id[]', array('options' => $location, 'empty' => 'Enter a location ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker location_id', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'location_id', 'required' => 'required')); 
                    ?>
                </div>
            </div>

            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Expiration</label>
                
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('expiration[]', array('class' => 'form-control col-md-8 col-xs-12 expiration', 'id' => 'expiration', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Account Code</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php
                    echo $this->Form->control('account_code[]', array('options' => '', 'empty' => 'Enter an account code ...', 'class' => 'form-control col-md-4 col-xs-12 selectpicker account_code', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code', 'value'=>'')); 
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Cost</label>
                
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('detail_cost[]', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'id'=>'detail_cost', 'value'=>$invitem['cost'])); ?>
                </div>
            </div>

            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Condition</label>
                
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php 
                        $inventorycondition = unserialize(INVENTORY_CONDITION);
                        echo $this->Form->control('conditions[]', array('options' => $inventorycondition, 'empty' => 'Enter condition ...', 'class' => 'form-control col-md-4 col-xs-12 selectpicker conditions', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'conditions')); 
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Warranty Expiration</label>
                
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    
                    <div class="input-group date datePicker">
                        <?php echo $this->Form->Text('warranty_expire[]', array('class' => 'form-control col-md-8 col-xs-12 warranty_expire', 'id' => 'warranty_expire', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                
            </div>

            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Notes</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                <?php echo $this->Form->control('notes[]', array('class' => 'form-control col-md-8 col-xs-12 notes', 'label'=> false, 'rows'=>2)); ?>
                </div>
            </div>

        </div>

        <div class="col-md-6">
            <div class="invaddPageHeading">Physical Inventory</div>
        
            <div class="form-group mt10"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Unit Cost</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('cost[]', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12 serial_no', 'placeholder' => '', 'label' => false, 'value'=>$invitem['inv']['cost'])); ?>
                </div>
            </div>

            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Quantity</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('quantity[]', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12 serial_no', 'placeholder' => '', 'label' => false, 'disabled'=>'disabled', 'value'=>'0')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Total Cost</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('total_cost[]', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'disabled'=>'disabled', 'value'=>'0')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Currency&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php 
                    $currency = unserialize(CURRENCY);
                    echo $this->Form->control('currency[]', array('options' => $currency, 'empty' => 'Enter a currency ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'currency')); 
                    ?>
                </div>
            </div>

            <div class="form-group">
                <div class="g-0 bg-light position-relative" style="width:99%;">
                    <table class="table upload-area" id="uploadfile">
                        <thead class="thead-dark">
                            <tr>
                                <th class="col-sm-2" colspan="2">Attachments</th>
                            </tr>
                        </thead>
                        <tbody id="filetbody-<?php echo $start; ?>">
                            
                            <tr id="noattachmenttr-<?php echo $start; ?>">
                                <td colspan="2">
                                    <div class="pull-right">
                                        <input type="file" name="files-<?php echo $start; ?>[]" class="inventoryattachment" id="inventoryattachment-<?php echo $start; ?>" style="display:none" multiple />
                                        <button class="btn btn-primary pull-right" type="button" onclick="$('#inventoryattachment-<?php echo $start; ?>').trigger('click'); return false;">Upload</button>
                                    </div>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row" style="margin-left:0px; margin-right:0px;">
        <div class="col-md-12 no-left-pad">
            <table class="table upload-area" id="uploadfile">
                <thead class="thead-dark">
                    <tr>
                        <th class="col-sm-1">Usage Times</th>
                        <th class="col-sm-2">New</th>
                        <th class="col-sm-2">Overhaul</th>
                        <th class="col-sm-2">Repair</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($invitem['invitms']['is_this_item_serialized'] == 1){ ?>
                    <tr>
                        <td>Months</td>
                        <td><?php echo $this->Form->control('months_new[]', array('class'=>'form-control col-md-2 col-xs-12 months_new', 'placeholder' => '', 'label' => false)); ?></td>
                        <td><?php echo $this->Form->control('months_overhaul[]', array('class'=>'form-control col-md-2 col-xs-12 months_overhaul', 'placeholder' => '', 'label' => false)); ?></td>
                        <td><?php echo $this->Form->control('months_repair[]', array('class'=>'form-control col-md-2 col-xs-12 months_repair', 'placeholder' => '', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Hours</td>
                        <td><?php echo $this->Form->Text('hours_new[]', array('class'=>'form-control col-md-2 col-xs-12 hours_new', 'placeholder' => '', 'label' => false)); ?></td>
                        <td><?php echo $this->Form->Text('hours_overhaul[]', array('class'=>'form-control col-md-2 col-xs-12 hours_overhaul', 'placeholder' => '', 'label' => false)); ?></td>
                        <td><?php echo $this->Form->Text('hours_repair[]', array('class'=>'form-control col-md-2 col-xs-12 hours_repair', 'placeholder' => '', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Landings</td>
                        <td><?php echo $this->Form->control('landings_new[]', array('class'=>'form-control col-md-2 col-xs-12 landings_new', 'placeholder' => '', 'label' => false)); ?></td>
                        <td><?php echo $this->Form->control('landings_overhaul[]', array('class'=>'form-control col-md-2 col-xs-12 landings_overhaul', 'placeholder' => '', 'label' => false)); ?></td>
                        <td><?php echo $this->Form->control('landings_repair[]', array('class'=>'form-control col-md-2 col-xs-12 landings_repair', 'placeholder' => '', 'label' => false)); ?></td>
                    </tr>
                    <tr>
                        <td>Cycles</td>
                        <td><?php echo $this->Form->control('cycles_new[]', array('class'=>'form-control col-md-2 col-xs-12 cycles_new', 'placeholder' => '', 'label' => false)); ?></td>
                        <td><?php echo $this->Form->control('cycles_overhaul[]', array('class'=>'form-control col-md-2 col-xs-12 cycles_overhaul', 'placeholder' => '', 'label' => false)); ?></td>
                        <td><?php echo $this->Form->control('cycles_repair[]', array('class'=>'form-control col-md-2 col-xs-12 cycles_repair', 'placeholder' => '', 'label' => false)); ?></td>
                    </tr>
                    <?php }else{ ?>
                    <tr>
                        <td colspan="4"><i>Only serialized components track usage times</i></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<?php 
$start++;
}
?>