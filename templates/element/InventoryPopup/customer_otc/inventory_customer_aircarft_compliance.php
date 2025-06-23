<div id="aircarftComplianceModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;">
        <?php
        //echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomersNotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Compliance Items - Reg Number:  <?php echo $aircraftregdetail->aircraft_registration_number; ?></h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <div class="row">
                        <div class="col-md-8 mt10">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default"><<</button>
                                <button type="button" class="btn btn-default"><</button>
                                <button type="button" class="btn btn-default">></button>
                                <button type="button" class="btn btn-default">>></button>
                                <button type="button" class="btn btn-default complianceinspectionadd">New</button>
                                <button type="button" class="btn btn-default complianceinspectionremove">Remove</button>
                                <button type="button" class="btn btn-default fetchCustOTCPopup" data-val="aircraft_compliance_list">List</button>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Go To</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('go_to', array('options' => [], 'empty' => '', 'class' => 'form-control col-md-8 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'go_to'));
                                    ?>
                                </div>
                            </div>
                        </div>  
                    </div>
                    <div id="aircraftTabs" class="customerinfoTab">
                        <div class="container">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" class="complnavtab" href="#inspectionSection">Inspections</a></li>
                                <li><a data-toggle="tab" class="complnavtab" href="#airframeSection">Airframe</a></li>
                                <li><a data-toggle="tab" class="complnavtab" href="#compengineSection">Engine</a></li>
                            </ul>
                            <div class="tab-content">
                                <input type="hidden" id="comp_tab_click" value='Inspections' />
                                <div id="inspectionSection" class="tab-pane fade in active">
                                    <div class="page-content mt-35">
                                        <div class="formBGCls aircraftComplInspectionBlock">
                                            <?php echo $this->element('Inventory/customer_otc/customer_aircraft_compliance_inspections'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div id="airframeSection" class="tab-pane fade">
                                    <div class="page-content mt-35">
                                        <div class="formBGCls aircraftComplAirframeBlock">
                                            <?php echo $this->element('Inventory/customer_otc/customer_aircraft_compliance_airframe'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div id="compengineSection" class="tab-pane fade">
                                    <div class="page-content mt-35">
                                        <div class="formBGCls aircraftComplEngineBlock">
                                            <?php echo $this->element('Inventory/customer_otc/customer_aircraft_compliance_engine'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Save</button-->
            </div>
        </div>
        <?php 
        //echo $this->Form->end(); 
        ?>
    </div>
</div>