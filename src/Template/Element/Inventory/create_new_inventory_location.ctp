<div class="row">
    <div class="col-md-12">
        <div class="row" style="margin-top:5px;">
            <div class="form-group">
                <div class="col-md-12 col-md-offset-2 col-sm-12" style="padding-left:12px;">
                    <div class="btn-group">
                        <label class="btn btn-label-default <?php if(empty($location_id)){ ?>active invlocationcreatetab <?php } ?>" btn-radio="true" <?php if(!empty($location_id)){ ?>disabled <?php } ?>>Top-Level Location</label>
                        <label class="btn btn-label-default  <?php if(!empty($location_id)){ ?>active <?php }else{ ?>invlocationcreatetab <?php }?>" btn-radio="false" <?php if(!empty($location_id)){ ?>disabled <?php } ?>>Sub Location</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row parent-location-block" <?php if(empty($location_id)){ ?> style="display:none;" <?php }?>>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="airframe_component_id">Parent Location&nbsp;<span class="required">*</span></label>
                    <div class="col-md-4 col-sm-4 col-xs-12" id="airCompsList">
                        <?php
                        $disabled = '';
                        if(!empty($location_id)){
                            $disabled = 'disabled';
                        }
                        echo $this->Form->control('parent_location_id', array('options' => $parentlocation, 'empty' => 'Select parent location ...', 'class' => 'form-control col-md-4 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'disabled'=>$disabled)); 
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="plane_id">Name&nbsp;<span class="required">*</span></label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                    <?php echo $this->Form->control('location_name', array('class'=>'form-control col-md-4 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="airframe_component_id">Status&nbsp;<span class="required">*</span></label>
                    <div class="col-md-4 col-sm-4 col-xs-12" id="airCompsList">
                        <?php 
                        $locationStatus = unserialize(INVENTORY_LOCATION_STATUS);
                        echo $this->Form->control('location_status', array('options' => $locationStatus, 'empty' => 'Select status ...', 'class' => 'form-control col-md-4 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'required' => 'required', 'value'=>'1')); 
                        ?>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="plane_id">Description</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                    <?php echo $this->Form->control('description', array('class' => 'form-control col-md-4 col-xs-12', 'label'=> false, 'rows'=>2)); ?>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-2 col-sm-2 col-xs-12" for="plane_id">Barcode</label>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                    <?php echo $this->Form->control('bar_code', array('class'=>'form-control col-md-4 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12"></div>
                </div>
            </div>
        </div>
    </div>
</div>