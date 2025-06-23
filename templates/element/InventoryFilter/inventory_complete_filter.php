<div class="modal-header" style="background-color: #e5e5e5;">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><span class="gpTypeCls"></span>Physical Inventory Filter</h4>
</div>
<div class="modal-body" style="max-height: 500px; overflow-y: auto;">
    <?php echo $this->Form->create(null, ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
    <div class="page-content">
        <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Item Type</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php 
                            $invItemType = unserialize(INVENTORY_ITEM_TYPE);
                            echo $this->Form->control('item_type', array('options' => $invItemType, 'empty' => 'Enter Type ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'f_item_type')); 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Part Type</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_this_item_serialized" id="f_is_this_item_serialized" value="1" checked>
                                <label class="form-check-label" for="is_this_item_serialized">All</label>&nbsp;&nbsp;

                                <input class="form-check-input" type="radio" name="is_this_item_serialized" id="f_serialized" value="2">
                                <label class="form-check-label" for="inlineRadio2">Serialized</label>

                                <input class="form-check-input" type="radio" name="is_this_item_serialized" id="f_nonserialized" value="3">
                                <label class="form-check-label" for="is_this_item_serialized">Non-Serialized</label>&nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Capital Equipment</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="capital_equipment" id="f_capital_equipment" value="1" checked>
                                <label class="form-check-label" for="capital_equipment">All</label>&nbsp;&nbsp;

                                <input class="form-check-input" type="radio" name="capital_equipment" id="f_capital_equipment" value="2">
                                <label class="form-check-label" for="inlineRadio2">Yes</label>

                                <input class="form-check-input" type="radio" name="capital_equipment" id="f_capital_equipment" value="3">
                                <label class="form-check-label" for="capital_equipment">No</label>&nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">System Managed</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="system_managed" id="system_managed" value="1" checked>
                                <label class="form-check-label" for="system_managed">All</label>&nbsp;&nbsp;

                                <input class="form-check-input" type="radio" name="system_managed" id="system_managed" value="2">
                                <label class="form-check-label" for="inlineRadio2">Yes</label>

                                <input class="form-check-input" type="radio" name="system_managed" id="system_managed" value="3">
                                <label class="form-check-label" for="system_managed">No</label>&nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Installed To</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php 
                            echo $this->Form->control('install_to', array('options' => $installto, 'empty' => 'Select Install To ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'f_install_to')); 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Part Number</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <?php echo $this->Form->control('part_number', array('class'=>'form-control col-md-9 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Location</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('location_id', array('options' => $location, 'empty' => 'Select a location...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id'=>'f_location_id'));  ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Status</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php 
                            $inventoryStatus = unserialize(INVENTORY_STATUS);
                            echo $this->Form->control('status', array('options' => $inventoryStatus, 'empty' => 'Enter Type ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'f_status')); 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Active</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="system_managed" id="system_managed" value="1" checked>
                                <label class="form-check-label" for="system_managed">All</label>&nbsp;&nbsp;

                                <input class="form-check-input" type="radio" name="system_managed" id="system_managed" value="2">
                                <label class="form-check-label" for="inlineRadio2">Yes</label>

                                <input class="form-check-input" type="radio" name="system_managed" id="system_managed" value="3">
                                <label class="form-check-label" for="system_managed">No</label>&nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div-->
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Has Expiration</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="has_expiration" id="f_has_expiration" value="1" checked>
                                <label class="form-check-label" for="has_expiration">All</label>&nbsp;&nbsp;

                                <input class="form-check-input" type="radio" name="has_expiration" id="f_has_expiration" value="2">
                                <label class="form-check-label" for="inlineRadio2">Yes</label>

                                <input class="form-check-input" type="radio" name="has_expiration" id="f_has_expiration" value="3">
                                <label class="form-check-label" for="has_expiration">No</label>&nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Expiration Date</label>
                        <div class="col-sm-9 col-xs-12" style="padding-left:0px; padding-right:0px;">
                            <div class="col-xs-4">
                                <select id="f_expiration_date" name="expiration_date" class="form-control">
                                    <option value="1" selected="selected">Equals</option>
                                    <option value="2">Between</option>
                                    <option value="3">Before</option>
                                    <option value="4">After</option>
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <div class="input-group date datePicker">
                                    <?php echo $this->Form->Text('expiration_date_start', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'expiration_date_start', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'))); ?>
                                    <span class="input-group-addon" style="display:none;">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="input-group date datePicker">
                                    <?php echo $this->Form->Text('expiration_date_end', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'expiration_date_end', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'), 'disabled'=>'disabled')); ?>
                                    <span class="input-group-addon" style="display:none;">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cost</label>
                        <div class="col-sm-9 col-xs-12" style="padding-left:0px; padding-right:0px;">
                            <div class="col-xs-4">
                                <select id="f_cost_condition" name="cost_condition" class="form-control">
                                    <option value="1" selected="selected">Equals</option>
                                    <option value="2">Between</option>
                                    <option value="3">Greater Than</option>
                                    <option value="4">Less Than</option>
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <?php echo $this->Form->control('cost_start', array('class'=>'form-control col-md-12 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <?php echo $this->Form->control('cost_end', array('class'=>'form-control col-md-12 col-xs-12', 'placeholder' => '', 'label' => false, 'disabled' => 'disabled')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Condition</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <?php 
                                $inventorycondition = unserialize(INVENTORY_CONDITION);
                                echo $this->Form->control('conditions', array('options' => $inventorycondition, 'empty' => 'Enter condition ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'f_conditions')); 
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Vendor</label>
                        
                        <div class="col-md-9 col-sm-9 col-xs-12" id="airCompsList" style="padding-right: 0px !important;">
                            <?php 
                                echo $this->Form->control('vendor', array('options' => $vendor, 'empty' => 'Enter a vendor ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'f_vendor')); 
                            ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">ATA Code</label>
                        
                        <div class="col-md-9 col-sm-9 col-xs-12" id="airCompsList" style="padding-right: 0px !important;">
                            <?php 
                                echo $this->Form->control('ata_chapter', array('options' => $ataCode, 'empty' => 'Enter a code ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'f_ata_chapter')); 
                            ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="row mt10">
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Tag <i class="fa fa-info-circle" data-toggle="tooltip" title="This field can only be edited from the inventory item level"></i></label>
                        
                        <div class="col-md-9 col-sm-9 col-xs-12" id="airCompsList">
                            <?php echo $this->Form->control('tags', array('class'=>'form-control col-md-3 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php echo $this->Form->end(); ?>

    <div class="modal-footer">
        <button type="button" class="btn btn-default clearapplyfilter">Clear</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary applyfilterbtn">Apply Filter</button>
    </div>
</div>