<div class="page-content">
    <?php echo $this->Form->create($inventorymanufacturers, ['id' => 'frmManufacturer', 'autocomplete'=>'off']); ?>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Name&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                <?php 
                if(!isset($reqtype)){
                echo $this->Form->control('name', array('class'=>'form-control col-md-8 col-xs-12 mf_name', 'placeholder' => '', 'label' => false, 'required'=>'required')); 
                }else{
                    echo $inventorymanufacturers->name;
                }
                ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="addPageHeading">Contact Information</div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Street 1</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('street1', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventorymanufacturers->street1;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">First Name</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('firstname', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventorymanufacturers->firstname;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="item_type">Street 2</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('street2', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventorymanufacturers->street2;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Last Name</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('lastname', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventorymanufacturers->lastname;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">City</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('city', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventorymanufacturers->city;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Primary Email</label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('primaryemail', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventorymanufacturers->primaryemail;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Postal</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('postal', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventorymanufacturers->postal;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Secondary Email</label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('secondaryemail', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventorymanufacturers->secondaryemail;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Country</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('country', array('options' => $countries, 'empty' => 'Select a country...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'country'));
                    }else{
                        echo !empty($inventorymanufacturers->country) ? $countries[$inventorymanufacturers->country] : '';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Primary Phone</label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('primaryphone', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventorymanufacturers->primaryphone;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <?php
            $stateblock = 1;
            if(empty($inventorymanufacturers->country) || $inventorymanufacturers->country != '231'){
                $stateblock = 0;
            }
            ?>
            <div class="form-group provinceblock"  <?php if(!empty($stateblock)){ ?>style="display:none;"<?php } ?>> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Province</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('province', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventorymanufacturers->province;
                    }
                    ?>
                </div>
            </div>
            
            <div class="form-group stateblock" <?php if(empty($stateblock)){ ?>style="display:none;"<?php } ?>> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">State</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('state', array('options' => $states, 'empty' => 'Select a state...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'state'));
                    }else{
                        echo !empty($inventorymanufacturers->state) ? $states[$inventorymanufacturers->state] : '';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Secondary Phone</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('secondaryphone', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventorymanufacturers->secondaryphone;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Account Number</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('accountno', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventorymanufacturers->accountno;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php 
    echo $this->Form->end(); 
    ?>
</div>