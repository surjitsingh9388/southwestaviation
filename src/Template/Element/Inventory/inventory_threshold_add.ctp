<div class="modal-header" style="background-color: #e5e5e5;">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><span class="gpTypeCls"></span>Location Threshold</h4>
</div>
<div class="modal-body" style="max-height: 500px; overflow-y: auto;">
    <div class="page-content">
        <?php echo $this->Form->create($invenotryitems, ['url' => ['action' => 'addThrashold'], 'id' => 'frmaddThrashold', 'autocomplete'=>'off']); ?>
        <div>
            <h5>Item: <?php echo $invenotryitems->name.' (PN: '.$invenotryitems->part_number.')'; ?></h5>
            <input type="hidden" name="id" id="thresholdid" value="">
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Location&nbsp;<span class="required">*</span></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php echo $this->Form->control('location_id', array('options' => $location, 'empty' => 'Select a location...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'required' => 'required', 'id'=>'location_id'));  ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Safety Stock Threshold&nbsp;<span class="required">*</span></label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <?php echo $this->Form->control('safety_stock_threshold', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary thrasholdsavebtn" disabled>Submit</button>
    </div>
</div>