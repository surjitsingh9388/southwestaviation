<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label class="control-label" for="reference">Vendor</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('vendor_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>@$aircraftwoosrvendors->vendor_name, 'readonly'=>'readonly')); ?>
            </div>
        </div>
    </div>
    <div class="col-md-4"> 
        <div class="form-group">
            <label class="control-label" for="reference">Go To</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('go-to-vendor', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
        <div class="container">
            <ul class="nav nav-tabs">
                <li class="active"><a class="woosrvendortab" data-toggle="tab" href="#woOSRContactInfoSection">Contact Info</a></li>
                <li><a class="woosrvendortab" data-toggle="tab" href="#woOSRCustomFieldSection">Custom Fields</a></li>
                <li><a class="woosrvendortab" data-toggle="tab" href="#woOSRPartHistorySection">Parts History</a></li>
                <li><a class="woosrvendortab" data-toggle="tab" href="#woOSRServiceHistorySection">Service History</a></li>
                <li><a class="woosrvendortab" data-toggle="tab" href="#woOSRNotesSection">Notes</a></li>
            </ul>
            <div class="tab-content">
                <div id="woOSRContactInfoSection" class="tab-pane fade in active">
                    <div class="page-content mt-35">
                        <div class="formBGCls" id="osrvendorcontactinfo">
                            <?php echo $this->element('Inventory/customer_otc/aircraft_wo_osr_contact_info'); ?>
                        </div>
                    </div>
                </div>
                <div id="woOSRCustomFieldSection" class="tab-pane fade">
                    
                </div>
                <div id="woOSRPartHistorySection" class="tab-pane fade">
                    <div class="page-content mt-35">
                        <div class="formBGCls">
                            <?php echo $this->element('Inventory/customer_otc/aircraft_wo_osr_part_history'); ?>
                        </div>
                    </div>
                </div>
                <div id="woOSRServiceHistorySection" class="tab-pane fade">
                    <div class="page-content mt-35">
                        <div class="formBGCls">
                            <?php echo $this->element('Inventory/customer_otc/aircraft_wo_osr_service_history'); ?>
                        </div>
                    </div>
                </div>
                <div id="woOSRNotesSection" class="tab-pane fade">
                    <div class="page-content mt-35">
                        <div class="formBGCls">
                            <?php echo $this->element('Inventory/customer_otc/aircraft_wo_osr_notes'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>