<?php
foreach($inventoryitems as $key=>$invitem){
for($i=0; $i<$quantity; $i++){
?>
<div class="addPartBorder hide-block" id="tab-<?php echo $start; ?>">
    <h2 class="heading po-receive-heading"><?php echo $invitem['invitms']['name'].' (PN: '.$invitem['invitms']['part_number'].')';?></h2>

    <div class="row">
        <div class="col-md-12">
            <div class="invaddPageHeading ml-22">General Information</div>
            <input type="hidden" name="inventory_po_id[]" value="<?php echo $invitem['inventory_po_id']; ?>">
            <input type="hidden" name="inventory_po_item_id[]" value="<?php echo $invitem['id']; ?>">
        </div>
    </div>

    <div class="row mt10">
     <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Part Number&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php
                    echo $this->Form->control('inventory_item_id[]', array('options' => $invitemdropdown, 'empty' => 'Select part number ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker inventory_item_id', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'inventory_item_id', 'required' => 'required', 'value'=>$invitem['inventory_item_id'])); 
                    ?>
                </div>
            </div>
        </div>

<div class="col-md-6 col-sm-6 col-xs-12 ">
            <div class="form-group">
                <label class="control-label col-md-6 col-sm-6 col-xs-12 pt-0" for="plane_id">Capital Equipment?<i class="fa fa-info-circle" data-toggle="tooltip" title="A capital item represents inventory in excess of a value to be determined by the operator."></i></label>
                <div class="col-md-6 col-sm-6 col-xs-12" id="airCompsList">
                    <div class="form-check form-check-inline">
                        <?php echo $this->Form->radio('capital_equipment[]',  [
                            ['value' => '1', 'text' => 'Yes', 'label' => ['class' => 'form-check-input capital_equipment'], 'id'=>"capital_equipment-".$invitem['id']."-".$start],
                            ['value' => '0', 'text' => 'No', 'label' => ['class' => 'form-check-input capital_equipment'], 'checked'=>'checked', 'id'=>"capital_equipment-".$invitem['id']."-".$start],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <?php if($invitem['invitms']['is_this_item_serialized'] == 1){ ?>
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Serial&nbsp;<span class="required">*</span></label>
                    <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php echo $this->Form->control('serial_no[]', array('class'=>'form-control col-md-8 col-xs-12 serial_no', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'serial_no')); ?>
                    </div>
                <?php }else{ ?>
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Lot</label>
                    <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php echo $this->Form->control('serial_no[]', array('class'=>'form-control col-md-8 col-xs-12 serial_no', 'placeholder' => '', 'label' => false, 'id'=>'serial_no')); ?>
                    </div>
                <?php } ?>
                
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12 ">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Condition</label>
                
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php 
                        $inventorycondition = unserialize(INVENTORY_CONDITION);
                        echo $this->Form->control('conditions[]', array('options' => $inventorycondition, 'empty' => 'Enter condition ...', 'class' => 'form-control col-md-4 col-xs-12 selectpicker conditions', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'conditions')); 
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 ">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Location&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php
                    echo $this->Form->control('location_id[]', array('options' => $location, 'empty' => 'Enter a location ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker location_id', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'location_id', 'required' => 'required')); 
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12 ">
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
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 ">
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
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12 ">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Notes</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                <?php echo $this->Form->control('notes[]', array('class' => 'form-control col-md-8 col-xs-12 notes', 'label'=> false, 'rows'=>2)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 ">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Cost</label>
                
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('cost[]', array('class'=>'form-control col-md-8 col-xs-12 cost', 'placeholder' => '', 'label' => false, 'id'=>'cost', 'value'=>$invitem['cost'])); ?>
                </div>
                
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12 ">
            <div class="form-group">&nbsp;</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 ">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Account Code</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php
                    echo $this->Form->control('account_code[]', array('options' => '', 'empty' => 'Enter an account code ...', 'class' => 'form-control col-md-4 col-xs-12 selectpicker account_code', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code')); 
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12 ">
            <div class="form-group">&nbsp;</div>
        </div>
    </div>


    <div class="row po-receive-other">
        <div class="col-md-7 col-sm-7 col-xs-12 no-left-pad">
            <table class="table">
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

        <div class="no-right-pad col-md-5 col-sm-5 col-xs-12 >
            <div class="g-0 bg-light position-relative">
                                
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
                                    <input type="file" name="files-<?php echo $start; ?>[]" class="inventoryattachment hide-block" id="inventoryattachment-<?php echo $start; ?>" multiple />
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
<?php 
$start++;
}}
?>