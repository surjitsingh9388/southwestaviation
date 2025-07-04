<div class="page-content">
    <?php echo $this->Form->create($inventoryaddresses, ['action' => 'saveaddress', 'id' => 'frmAddAddress', 'autocomplete' => 'off']); ?>

   
            <div class="form-group">
                <label class="control-label " for="plane_id">Name&nbsp;<span class="required">*</span></label>
                <div class="">
                    <?php
                    echo $this->Form->control('name', array('class' => 'form-control  mf_name', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id' => 'addressname'));
                    ?>
                </div>
            </div>

    <div >
        <div >
            <div class="form-group">
                <label class="control-label " for="plane_id">Street 1&nbsp;<span class="required">*</span></label>
                <div >
                    <?php
                    echo $this->Form->control('street1', array('class' => 'form-control  mf_name', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id' => 'addressstreet1'));
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div >
        <div >
            <div class="form-group">
                <label class="control-label " for="plane_id">Street 2</label>
                <div >
                    <?php
                    echo $this->Form->control('street2', array('class' => 'form-control  mf_name', 'placeholder' => '', 'label' => false));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div >
        <div >
            <div class="form-group">
                <label class="control-label " for="plane_id">Street 3</label>
                <div >
                    <?php
                    echo $this->Form->control('street3', array('class' => 'form-control ', 'placeholder' => '', 'label' => false));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div >
        <div >
            <div class="form-group">
                <label class="control-label " for="plane_id">City&nbsp;<span class="required">*</span></label>
                <div >
                    <?php
                    echo $this->Form->control('city', array('class' => 'form-control ', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id' => 'addresscity'));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div >
        <div >
            <div class="form-group">
                <label class="control-label " for="plane_id">Country&nbsp;<span class="required">*</span></label>
                <div >
                    <?php
                    echo $this->Form->control('country', array('options' => $countries, 'empty' => 'Select a country...', 'class' => 'form-control  selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'addresscountry', 'required' => 'required'));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div >
        <div >
            <?php
            $stateblock = 1;
            if (empty($inventoryaddresses->country) || $inventoryaddresses->country != '231') {
                $stateblock = 0;
            }
            ?>
            <div class="form-group addressprovinceblock" <?php if (!empty($stateblock)) { ?>style="display:none;" <?php } ?>>
                <label class="control-label " for="plane_id">Province&nbsp;<span class="required">*</span></label>
                <div >
                    <?php
                    echo $this->Form->control('province', array('class' => 'form-control ', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id' => 'addressprovince'));
                    ?>
                </div>
            </div>

            <div class="form-group addressstateblock" <?php if (empty($stateblock)) { ?>style="display:none;" <?php } ?>>
                <label class="control-label " for="plane_id">State&nbsp;<span class="required">*</span></label>
                <div >
                    <?php
                    echo $this->Form->control('state', array('options' => $states, 'empty' => 'Select a state...', 'class' => 'form-control  selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'addressstate'));
                    ?>
                </div>
            </div>

        </div>
    </div>


    <div >
        <div >
            <div class="form-group">
                <label class="control-label " for="plane_id">Postal&nbsp;<span class="required">*</span></label>
                <div >
                    <?php
                    echo $this->Form->input('postal', array('type' => 'number', 'class' => 'form-control ', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id' => 'addresspostal'));
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div >
        <div >
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php
                    echo $this->Form->text('is_billing_address', array('type' => 'checkbox', 'label' => 'Label', 'id' => 'is_billing_address'));
                    ?>
                    <span>This address supports being billed to.</span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php
                    echo $this->Form->text('is_shipping_address', array('type' => 'checkbox', 'label' => 'Label', 'id' => 'is_shipping_address'));
                    ?>
                    <span>This address supports being shipped to.</span>
                </div>
            </div>
        </div>
    </div>

    <?php
    echo $this->Form->end();
    ?>
</div>