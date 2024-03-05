
<div class="page-content">
    <?php echo $this->Form->create('', ['url' => ['action' => 'addManufacturer'], 'id' => 'frmManufacturer']); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Name&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                <?php 
                    $invItemType = unserialize(INVENTORY_ITEM_TYPE);
                    echo $this->Form->control('item_type', array('options' => $invItemType, 'empty' => 'Enter Type ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'item_type')); 
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Min Qty</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('street1', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="item_type">Max Qty</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('street2', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Tags</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('city', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Accepts Install</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="accepts_install" id="inlineRadio1" value="1">
                        <label class="form-check-label" for="inlineRadio1">Both</label>&nbsp;&nbsp;
                    
                        <input class="form-check-input" type="radio" name="accepts_install" id="inlineRadio1" value="1">
                        <label class="form-check-label" for="inlineRadio1">Yes</label>&nbsp;&nbsp;

                        <input class="form-check-input" type="radio" name="accepts_install" id="inlineRadio2" value="0" checked>
                        <label class="form-check-label" for="inlineRadio2">No</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt5">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Serialized Only</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="serialized_only" id="inlineRadio1" value="1">
                        <label class="form-check-label" for="inlineRadio1">Yes</label>&nbsp;&nbsp;

                        <input class="form-check-input" type="radio" name="serialized_only" id="inlineRadio2" value="0" checked>
                        <label class="form-check-label" for="inlineRadio2">No</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Capital Only</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="capital_only" id="inlineRadio1" value="1">
                        <label class="form-check-label" for="inlineRadio1">Yes</label>&nbsp;&nbsp;

                        <input class="form-check-input" type="radio" name="capital_only" id="inlineRadio2" value="0" checked>
                        <label class="form-check-label" for="inlineRadio2">No</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Show Inactive</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="show_inactive" id="inlineRadio1" value="1">
                        <label class="form-check-label" for="inlineRadio1">Yes</label>&nbsp;&nbsp;

                        <input class="form-check-input" type="radio" name="show_inactive" id="inlineRadio2" value="0" checked>
                        <label class="form-check-label" for="inlineRadio2">No</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
    echo $this->Form->end(); 
    ?>
</div>