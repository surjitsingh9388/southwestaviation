<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="btn-group mb-10 ml-8 mt10">
            <label class="btn btn-label-default <?php if(empty($location_id)) echo 'active invlocationcreatetab'; ?>" <?php if(!empty($location_id)) echo 'disabled'; ?>>
                Top-Level Location
            </label>
            <label class="btn btn-label-default <?php echo !empty($location_id) ? 'active' : 'invlocationcreatetab'; ?>" <?php if(!empty($location_id)) echo 'disabled'; ?>>
                Sub Location
            </label>
        </div>

        <?php 
        if(!empty($parentlocation)){ ?>
        <div class="form-group row">
            <label class="col-md-2 col-sm-2 col-xs-12 col-form-label required">Parent Location</label>
            <div class="col-md-10 col-sm-10 col-xs-12">
                <?php
                echo $this->Form->control('parent_location_id', [
                    'type'=>'hidden',
                    'class' => 'form-control',
                    'placeholder' => '',
                    'label' => false,
                    'required' => true,
                    'readonly' => true
                ]);
                
                echo $this->Form->control('parent_location_name', [
                    'class' => 'form-control',
                    'placeholder' => '',
                    'label' => false,
                    'required' => true,
                    'readonly' => true,
                    'value' => $parentlocation['location_name']
                ]); 
                ?>
            </div>
        </div>
        <?php } ?>

        <div class="form-group row">
            <label class="col-md-2 col-sm-2 col-xs-12 col-form-label">Name <span class="required">*</span></label>
            <div class="col-md-10 col-sm-10 col-xs-12">
                <?php echo $this->Form->control('location_name', [
                    'class' => 'form-control',
                    'placeholder' => '',
                    'label' => false,
                    'required' => true
                ]); ?>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-2 col-sm-2 col-xs-12 col-form-label ">Status <span class="required">*</span></label>
            <div class="col-md-10 col-sm-10 col-xs-12">
                <?php
                $locationStatus = unserialize(INVENTORY_LOCATION_STATUS);
                echo $this->Form->control('location_status', [
                    'options' => $locationStatus,
                    'empty' => 'Select status ...',
                    'class' => 'form-control selectpicker',
                    'data-show-subtext' => true,
                    'data-live-search' => true,
                    'label' => false,
                    'required' => true
                ]);
                ?>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-2 col-sm-2 col-xs-12 col-form-label">Description</label>
            <div class="col-md-10 col-sm-10 col-xs-12">
                <?php echo $this->Form->control('description', [
                    'class' => 'form-control',
                    'label' => false,
                    'rows' => 2
                ]); ?>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-2 col-sm-2 col-xs-12 col-form-label">Barcode</label>
            <div class="col-md-10 col-sm-10 col-xs-12">
                <?php echo $this->Form->control('bar_code', [
                    'class' => 'form-control',
                    'placeholder' => '',
                    'label' => false
                ]); ?>
            </div>
        </div>
    </div>
</div>
