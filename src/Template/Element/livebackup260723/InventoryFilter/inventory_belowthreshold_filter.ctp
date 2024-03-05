<div class="modal-header" style="background-color: #e5e5e5;">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><span class="gpTypeCls"></span>Filter Inventory</h4>
</div>
<div class="modal-body" style="max-height: 500px;">
    
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Part Type</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php 
                        $itemSerialized = array('2'=>'All', '1'=>'Serialized', '0'=>'Non-Serialized');
                        echo $this->Form->control('is_this_item_serialized', array('options' => $itemSerialized, 'empty' => 'Enter a part type ...', 'class' => 'form-control col-md-3 col-sm-3 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'is_this_item_serialized')); 
                        ?>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                </div>
            </div>
        </div>
        <div class="row mt5">
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
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Location</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo $this->Form->control('location_id', array('options' => $location, 'empty' => 'Select a location...', 'class' => 'form-control col-md-6 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id'=>'f_location_id'));  ?>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12"></div>
                </div>
            </div>
        </div>
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Min Unit Cost</label>
                    <div class="col-sm-4 col-sm-4 col-xs-12">
                        <?php echo $this->Form->Text('min_unit_cost', array('type'=>'number','class' => 'form-control col-md-4 col-xs-12', 'id' => 'min_unit_cost', 'placeholder' => '', 'label' => false)); ?>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12"></div>
                </div>
            </div>
        </div>
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Max Unit Cost</label>
                    <div class="col-sm-4 col-sm-4 col-xs-12">
                        <?php echo $this->Form->Text('max_unit_cost', array('type'=>'number','class' => 'form-control col-md-4 col-xs-12', 'id' => 'max_unit_cost', 'placeholder' => '', 'label' => false)); ?>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12"></div>
                </div>
            </div>
        </div>
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Tag <i class="fa fa-info-circle" data-toggle="tooltip" title="This field can only be edited from the inventory item level"></i></label>
                    <div class="col-md-4 col-sm-4 col-xs-12" id="airCompsList">
                        <?php echo $this->Form->control('tags', array('class'=>'form-control col-md-4 col-xs-12', 'placeholder' => 'Enter a tag', 'label' => false)); ?>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12"></div>
                </div>
            </div>
        </div>
        <div class="row mt5"></div>

    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-default clearapplyfilter">Clear</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary applyfilterbtn">Apply Filter</button>
    </div>
</div>