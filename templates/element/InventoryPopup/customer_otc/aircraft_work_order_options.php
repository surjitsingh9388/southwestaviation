<div id="aircarftWorkOrderOptionModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <div class="row">
                        <div class="col-md-12 mt10">
                            <div id="aircraftTabs">
                                <div class="container">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#woGeneralInfoSection">General Info</a></li>
                                        <li><a data-toggle="tab" href="#woMiscChargeSection">Misc. Charges</a></li>
                                        <li><a data-toggle="tab" href="#woTaxInfoSection">Tax Info</a></li>
                                        <li><a data-toggle="tab" href="#woPricingInfoSection">Pricing Info</a></li>
                                        <li><a data-toggle="tab" href="#woBillingInfoSection">Billing Info</a></li>
                                        <li><a data-toggle="tab" href="#woWarrantyInfoSection">Warranty Info</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="woGeneralInfoSection" class="tab-pane fade in active">
                                            <div class="page-content mt-35">
                                                <div class="formBGCls">
                                                    <?php echo $this->element('Inventory/customer_otc/aircraft_wo_option_generalinfo'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="woMiscChargeSection" class="tab-pane fade">
                                            <div class="page-content mt-35">
                                                <div class="formBGCls">
                                                    <?php echo $this->element('Inventory/customer_otc/aircraft_wo_option_misc_charges'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="woTaxInfoSection" class="tab-pane fade">
                                            <div class="page-content mt-35">
                                                <div class="formBGCls">
                                                    <?php echo $this->element('Inventory/customer_otc/aircraft_wo_option_tax_info'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="woPricingInfoSection" class="tab-pane fade">
                                            <div class="page-content mt-35">
                                                <div class="formBGCls">
                                                    <?php echo $this->element('Inventory/customer_otc/aircraft_wo_option_pricing_info'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="woBillingInfoSection" class="tab-pane fade">
                                            <div class="page-content mt-35">
                                                <div class="formBGCls">
                                                    <?php echo $this->element('Inventory/customer_otc/aircraft_wo_option_billing_info'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="woWarrantyInfoSection" class="tab-pane fade">
                                            <div class="page-content mt-35">
                                                <div class="formBGCls">
                                                    <?php echo $this->element('Inventory/customer_otc/aircraft_wo_option_warranty_info'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>