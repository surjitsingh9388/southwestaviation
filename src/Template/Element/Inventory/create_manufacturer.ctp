<div class="page-content">
    <?php echo $this->Form->create($inventorymanufacturers, ['id' => 'frmManufacturer', 'autocomplete'=>'off']); ?>
    <div class="row mt10">
        <div class="col-md-6">
            <div class="form-group col-md-12"> 
                <label class="control-label col-md-4" for="plane_id">Name&nbsp;<span class="required">*</span></label>
                <div class="col-md-8">
                <?php 
                if(!isset($reqtype)){
                echo $this->Form->control('name', array('class'=>'form-control col-md-8 mf_name', 'placeholder' => '', 'label' => false, 'required'=>'required')); 
                }else{
                    echo '<p class="form-control-static">'.$inventorymanufacturers->name.'</p>';
                }
                ?>
                </div>
            </div>

            <div class="form-group col-md-12"> 
                <label class="control-label col-md-4" for="plane_id">Street 1</label>
                <div class="col-md-8">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('street1', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false));
                    }else{
                        echo '<p class="form-control-static">'.$inventorymanufacturers->street1.'</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="form-group col-md-12"> 
                <label class="control-label col-md-4" for="item_type">Street 2</label>
                <div class="col-md-8">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('street2', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false));
                    }else{
                        echo '<p class="form-control-static">'.$inventorymanufacturers->street2.'</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="form-group col-md-12"> 
                <label class="control-label col-md-4" for="plane_id">City</label>
                <div class="col-md-8">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('city', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false));
                    }else{
                        echo '<p class="form-control-static">'.$inventorymanufacturers->city.'</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="form-group col-md-12"> 
                <label class="control-label col-md-4" for="plane_id">Postal</label>
                <div class="col-md-8">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('postal', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false));
                    }else{
                        echo '<p class="form-control-static">'.$inventorymanufacturers->postal.'</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="form-group col-md-12"> 
                <label class="control-label col-md-4" for="plane_id">Country</label>
                <div class="col-md-8">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('country', array('options' => $countries, 'empty' => 'Select a country...', 'class' => 'form-control col-md-8 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'country'));
                    }else{
                        $country = !empty($inventorymanufacturers->country) ? $countries[$inventorymanufacturers->country] : '';
                        echo '<p class="form-control-static">'.$country.'</p>';
                    }
                    ?>
                </div>
            </div>

            <?php
            $stateblock = 1;
            if(empty($inventorymanufacturers->country) || $inventorymanufacturers->country != '231'){
                $stateblock = 0;
            }
            ?>
            <div class="form-group col-md-12 provinceblock"  <?php if(!empty($stateblock)){ ?>style="display:none;"<?php } ?>> 
                <label class="control-label col-md-4" for="plane_id">Province</label>
                <div class="col-md-8">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('province', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false));
                    }else{
                        echo '<p class="form-control-static">'.$inventorymanufacturers->province.'</p>';
                    }
                    ?>
                </div>
            </div>
            
            <div class="form-group col-md-12 stateblock" <?php if(empty($stateblock)){ ?>style="display:none;"<?php } ?>> 
                <label class="control-label col-md-4" for="plane_id">State</label>
                <div class="col-md-8">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('state', array('options' => $states, 'empty' => 'Select a state...', 'class' => 'form-control col-md-8 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'state'));
                    }else{
                        $state = !empty($inventorymanufacturers->state) ? $states[$inventorymanufacturers->state] : '';
                        echo '<p class="form-control-static">'.$state.'</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="form-group col-md-12"> 
                <label class="control-label col-md-4" for="plane_id">Account Number</label>
                <div class="col-md-8">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('accountno', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false));
                    }else{
                        echo '<p class="form-control-static">'.$inventorymanufacturers->accountno.'</p>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="invaddPageHeading">Contact Information</div>

            <div class="form-group col-md-12 mt10">
                <label class="control-label col-md-4" for="airframe_component_id">First Name</label>
                <div class="col-md-8">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('firstname', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false));
                    }else{
                        echo '<p class="form-control-static">'.$inventorymanufacturers->firstname.'</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="airframe_component_id">Last Name</label>
                <div class="col-md-8">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('lastname', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false));
                    }else{
                        echo '<p class="form-control-static">'.$inventorymanufacturers->lastname.'</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="airframe_component_id">Primary Email</label>
                <div class="col-md-8" id="airCompsList">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('primaryemail', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false));
                    }else{
                        echo '<p class="form-control-static">'.$inventorymanufacturers->primaryemail.'</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="airframe_component_id">Secondary Email</label>
                <div class="col-md-8" id="airCompsList">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('secondaryemail', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false));
                    }else{
                        echo '<p class="form-control-static">'.$inventorymanufacturers->secondaryemail.'</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="form-group col-md-12">
                <label class="control-label col-md-4" for="airframe_component_id">Primary Phone</label>
                <div class="col-md-8" id="airCompsList">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('primaryphone', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false));
                    }else{
                        echo '<p class="form-control-static">'.$inventorymanufacturers->primaryphone.'</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="form-group col-md-12"> 
                <label class="control-label col-md-4" for="plane_id">Secondary Phone</label>
                <div class="col-md-8">
                    <?php 
                    if(!isset($reqtype)){
                        echo $this->Form->control('secondaryphone', array('class'=>'form-control col-md-8', 'placeholder' => '', 'label' => false));
                    }else{
                        echo '<p class="form-control-static">'.$inventorymanufacturers->secondaryphone.'</p>';
                    }
                    ?>
                </div>
            </div>


        </div>
    </div>
    
    <div class="row mt10"></div>
    <?php 
    echo $this->Form->end(); 
    ?>
</div>