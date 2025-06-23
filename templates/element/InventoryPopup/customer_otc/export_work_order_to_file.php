<div id="exportWOROExportDataModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 90%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">W/O & R/O - Export Data</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="col-md-12"><b>General Search Filters</b></div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Primary Customer</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_customer_name', array('options' => $inventorycustomers, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_customer_name'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Reg. Number</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_registration_number', array('options' => [], 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_registration_number'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Make</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_make', array('options' => $aircraftmakes, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_make'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Model</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_model', array('options' => $aircraftmodels, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_model'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">A/C Serial</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_ac_serial', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_ac_serial')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">W/O or R/O</label>
                                <div class="col-md-8">
                                    <?php
                                    $woroarr = ['1'=>'Both', '2'=>'Work Order', '3'=>'Repair Order'];
                                    echo $this->Form->control('wo_ro', array('options' => $woroarr, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Status</label>
                                <div class="col-md-8">
                                    <?php
                                    $workorderstatusArr = unserialize(AIRCRAFT_WORKORDER_STATUS);

                                    echo $this->Form->control('wo_ro_status', array('options' => $workorderstatusArr, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_status'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Status Type</label>
                                <div class="col-md-8">
                                    <?php
                                    $statusTypeArr = ['1'=>'All', '2'=>'All Open', '3'=>'All Completed', '4'=>'All Void'];

                                    echo $this->Form->control('wo_ro_status_type', array('options' => $statusTypeArr, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_status_type'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Lead Tech</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_lead_tech', array('options' => [], 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_lead_tech'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Sales Person</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_sales_person', array('options' => [], 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_sales_person'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Misc. Charge Notes</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_misc_charges_notes', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_misc_charges_notes')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">
                                    <span>Created</span>
                                    <span style="float:right;">From</span>
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('wo_ro_created_from', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_ro_created_from', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">
                                    <span style="float:right;">To</span>
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('wo_ro_created_to', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_ro_created_to', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">
                                    <span>Completed</span>
                                    <span style="float:right;">From</span>
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('wo_ro_completed_from', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_ro_completed_from', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">
                                    <span style="float:right;">To</span>
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('wo_ro_completed_to', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_ro_completed_to', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12 mt10"><b>Item Filters</b></div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">ATA Code</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_ata_code', array('options' => $atacodes, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_ata_code'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Category</label>
                                <div class="col-md-8">
                                    <?php
                                    $woCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);
                                    echo $this->Form->control('wo_ro_category', array('options' => $woCategory, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_category'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Department</label>
                                <div class="col-md-8">
                                    <?php
                                    $contractRateDepartment = unserialize(CONTRACT_RATE_DEPARTMENT);

                                    echo $this->Form->control('wo_ro_department', array('options' => $contractRateDepartment, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_department'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Grouping</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_grouping', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_grouping')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Labor Kit</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_labor_kit', array('options' => $laborkits, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_labor_kit'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Warranty</label>
                                <div class="col-md-8">
                                    <?php
                                    $woItemOverviewWarranty = unserialize(WOITEMOVERVIEWWARRANTY);

                                    echo $this->Form->control('wo_ro_warranty', array('options' => $woItemOverviewWarranty, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_warranty'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Warranty Claim No.</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_warranty_claim_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_warranty_claim_no')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Discrepancy</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_discrepancy', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_discrepancy')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Corrective Action</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_corrective_action', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_corrective_action')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Item Notes</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_item_notes', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_item_notes')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Item Status</label>
                                <div class="col-md-8">
                                    <?php
                                    $aircraftWOItemStatus = unserialize(AIRCRAFT_WORKORDER_ITEMSTATUS);

                                    echo $this->Form->control('wo_ro_item_status', array('options' => $aircraftWOItemStatus, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_item_status'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">&nbsp;</label>
                                <div class="col-md-8">
                                    <input type="checkbox" name="wo_ro_include_signoffs" value="1" />&nbsp;Include Signoffs
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Signoff</label>
                                <div class="col-md-8">
                                    <?php
                                    $woItemSignOff = unserialize(WOITEMSIGNOFF);

                                    echo $this->Form->control('wo_ro_signoff', array('options' => $woItemSignOff, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_signoff'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Signoff By</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_signoff_by', array('options' => $userlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_signoff_by'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">
                                    <span>Signoff</span>
                                    <span style="float:right;">From</span>
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('wo_ro_signoff_from', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_ro_signoff_from', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">
                                    <span style="float:right;">To</span>
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('wo_ro_signoff_to', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_ro_signoff_to', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12 mt10"><b>Service Info</b></div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">&nbsp;</label>
                                <div class="col-md-8">
                                    <input type="checkbox" name="wo_ro_service_info" value="1" />&nbsp;Include Service Info
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Service Technician</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_service_technician', array('options' => $userlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_service_technician'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">
                                    <span>Logged In</span>
                                    <span style="float:right;">From</span>
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('wo_ro_loggedin_from', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_ro_loggedin_from', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">
                                    <span style="float:right;">To</span>
                                </label>
                                <div class="col-md-8">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('wo_ro_loggedin_to', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'wo_ro_loggedin_to', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10"><b>Tools</b></div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">&nbsp;</label>
                                <div class="col-md-8">
                                    <input type="checkbox" name="wo_ro_tools" value="1" />&nbsp;Include Tools Info
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Tool Name</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_tool_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_tool_name')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Tool Serial No.</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_tool_serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_tool_serial_no')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10"><b>OSR Info</b></div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">&nbsp;</label>
                                <div class="col-md-8">
                                    <input type="checkbox" name="wo_ro_include_outside_repair" value="1" />&nbsp;Include Outside Repair
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Part Number</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_osr_part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_osr_part_number')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Description</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_osrdescription', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_osrdescription')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Old Serial No.</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_osr_old_serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_osr_old_serial_no')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">New Serial No.</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_osr_new_serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_osr_new_serial_no')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Vendor</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_osr_vendor', array('options' => $osrvendorlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_osr_vendor'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="col-md-12 mt10"><b>Parts Info</b></div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">&nbsp;</label>
                                <div class="col-md-8">
                                    <input type="checkbox" name="wo_ro_include_parts" value="1" />&nbsp;Include Parts
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Part Number</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_part_number')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Description</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_part_description', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_part_description')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">PO Number</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_po_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_po_number')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12 mt10">&nbsp;</div>
                        <div class="col-md-12 mt10">&nbsp;</div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Old Serial No.</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_old_serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_old_serial_no')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">New Serial No.</label>
                                <div class="col-md-8">
                                    <?php echo $this->Form->control('wo_ro_new_serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_ro_new_serial_no')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12 mt10">&nbsp;</div>
                        <div class="col-md-12 mt10">&nbsp;</div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">Supplier</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_supplier', array('options' => $supplierlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_supplier'));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="reference">PO Vendor</label>
                                <div class="col-md-8">
                                    <?php
                                    echo $this->Form->control('wo_ro_po_vendor', array('options' => $supplierlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_ro_po_vendor'));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="button" class="btn btn-default float-left">Clear Filters</button>
                        <button type="button" class="btn btn-default float-right">Export</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>