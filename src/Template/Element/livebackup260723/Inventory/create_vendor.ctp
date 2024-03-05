<div class="page-content">
    <?php echo $this->Form->create($inventoryvendors, ['id' => 'frmAddVendor', 'autocomplete'=>'off']); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="addPageHeading">General Information</div>
        </div>

        <div class="col-md-6">
            <div class="addPageHeading">Contact Information</div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Name&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                <?php 
                if(!isset($reqtype)){
                    echo $this->Form->control('name', array('class'=>'form-control col-md-8 col-xs-12 mf_name', 'placeholder' => '', 'label' => false, 'required' => 'required'));
                }else{
                    echo $inventoryvendors->name;
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
                        echo $inventoryvendors->firstname;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Website</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('website', array('class'=>'form-control col-md-8 col-xs-12 mf_name', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventoryvendors->website;
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
                        echo $inventoryvendors->lastname;
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
                        echo $this->Form->control('account_number', array('class'=>'form-control col-md-8 col-xs-12 mf_name', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventoryvendors->account_number;
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
                        echo $inventoryvendors->primaryemail;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="addPageHeading">Address Information</div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Secondary Email</label>
                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('secondaryemail', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventoryvendors->secondaryemail;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Street 1&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('street1', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required'));
                    }else{
                        echo $inventoryvendors->street1;
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
                        echo $inventoryvendors->primaryphone;
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
                        echo $inventoryvendors->street2;
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
                        echo $inventoryvendors->secondaryphone;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">City&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('city', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required'));
                    }else{
                        echo $inventoryvendors->city;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Fax</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('fax', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false));
                    }else{
                        echo $inventoryvendors->fax;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Postal&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->input('postal', array('type'=>'number', 'class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required'));
                    }else{
                        echo $inventoryvendors->postal;
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-group"> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Country&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('country', array('options' => $countries, 'empty' => 'Select a country...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'country', 'required' => 'required')); 
                    }else{
                        echo $countries[$inventoryvendors->country];
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <?php
            $stateblock = 1;
            if(empty($inventoryvendors->country) || $inventoryvendors->country != '231'){
                $stateblock = 0;
            }
            ?>
            <div class="form-group provinceblock" <?php if(!empty($stateblock)){ ?>style="display:none;"<?php } ?>> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Province&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('province', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required'));
                    }else{
                        echo $inventoryvendors->province;
                    }
                    ?>
                </div>
            </div>
            
            <div class="form-group stateblock" <?php if(empty($stateblock)){ ?>style="display:none;"<?php } ?>> 
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">State&nbsp;<span class="required">*</span></label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('state', array('options' => $states, 'empty' => 'Select a state...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'state'));
                    }else{
                        echo !empty($inventoryvendors->state) ? $states[$inventoryvendors->state] : '';
                    }
                    ?>
                </div>
            </div>
            
        </div>

        <div class="col-md-6">
            
        </div>
    </div>
    <?php 
    echo $this->Form->end(); 
    ?>
</div>