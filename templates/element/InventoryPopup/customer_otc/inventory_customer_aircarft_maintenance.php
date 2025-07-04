<div id="aircarftMaintenanceModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;">
        <?php
        //echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomersNotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Aircraft Registration Number: <?php echo $aircraftregdetail->aircraft_registration_number; ?></h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <div class="row ml0">
                        This is the latest maintenance information for this aircarft. If the information is changed here after W/O is created, you may need to go to the appropriate W/O > Options menu > Log Book Value and press the "Refresh" button.
                    </div>
                    <div class="row ml0" class="update-time-btn">
                        <button class="btn btn-default fetchCustOTCPopup mt10" type="button" data-val='aircraft_maint_update_times_btn'>Update Times</button>
                    </div>
                        
                    <div id="aircraftTabs" class="customerinfoTab">
                        <div class="container">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#overfiewSection">Overview</a></li>
                                <?php if($engine_type != '5'){ ?>
                                <li><a data-toggle="tab" href="#engineSection">Engine</a></li>
                                <?php if($engine_type != '4'){ ?>
                                <li><a data-toggle="tab" href="#propSection">Prop</a></li>
                                <?php } ?>
                                <li><a data-toggle="tab" href="#customFieldsSection">Custom Fields</a></li>
                                <?php } ?>
                                <li><a data-toggle="tab" href="#applianceSection">Appliance / Equipment</a></li>
                                <li><a data-toggle="tab" href="#adSection">AD</a></li>
                                <li><a data-toggle="tab" href="#notesSection">Notes</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="overfiewSection" class="tab-pane fade in active">
                                    <div class="page-content mt-35">
                                        <div class="formBGCls">
                                            <?php 
                                            $propFileName = '';
                                            if($engine_type == '1'){
                                                $propFileName = 'aircraft_maintenance_single_overview';
                                            }else if($engine_type == '2'){
                                                $propFileName = 'aircraft_maintenance_single_overview';
                                            }else if($engine_type == '3'){
                                                $propFileName = 'aircraft_maintenance_turbine_overview';
                                            }else if($engine_type == '4'){
                                                $propFileName = 'aircraft_maintenance_jet_overview';
                                            }else if($engine_type == '5'){
                                                $propFileName = 'aircraft_maintenance_helicopter_overview';
                                            }
                                            
                                            echo $this->element('Inventory/customer_otc/'.$propFileName); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if($engine_type != '5'){ ?>
                                <div id="engineSection" class="tab-pane fade">
                                    <div class="page-content mt-35">
                                        <div class="formBGCls">
                                            <?php 
                                            $engineFileName = '';
                                            if($engine_type == '1'){
                                                $engineFileName = 'aircraft_maintenance_single_engine';
                                            }else if($engine_type == '2'){
                                                $engineFileName = 'aircraft_maintenance_twin_engine';
                                            }else if($engine_type == '3'){
                                                $engineFileName = 'aircraft_maintenance_turbine_engine';
                                            }else if($engine_type == '4'){
                                                $engineFileName = 'aircraft_maintenance_jet_engine';
                                            }
                                            
                                            echo $this->element('Inventory/customer_otc/'.$engineFileName); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if($engine_type != '4'){ ?>
                                <div id="propSection" class="tab-pane fade">
                                    <div class="page-content mt-35">
                                        <div class="formBGCls">
                                            <?php 
                                            $propFileName = '';
                                            if($engine_type == '1'){
                                                $propFileName = 'aircraft_maintenance_single_prop';
                                            }else if($engine_type == '2'){
                                                $propFileName = 'aircraft_maintenance_twin_prop';
                                            }else if($engine_type == '3'){
                                                $propFileName = 'aircraft_maintenance_turbine_prop';
                                            }else if($engine_type == '5'){
                                                $propFileName = 'aircraft_maintenance_helicopter_prop';
                                            }
                                            
                                            echo $this->element('Inventory/customer_otc/'.$propFileName); 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div id="customFieldsSection" class="tab-pane fade"></div>
                                <?php } ?>
                                <div id="applianceSection" class="tab-pane fade">
                                    <?php echo $this->element('Inventory/customer_otc/customer_aircraft_maintenance_appliances'); ?>
                                </div>
                                <div id="adSection" class="tab-pane fade">
                                    <?php echo $this->element('Inventory/customer_otc/customer_aircraft_maintenance_ad'); ?>
                                </div>
                                <div id="notesSection" class="tab-pane fade">
                                    <div class="row mt10">
                                        <?php
                                        echo $this->Form->create($aircraftmaintnotes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftMaintNotes', 'autocomplete' => 'off'));
                                        ?>
                                        <input type="hidden" name="aircraft_id" value="<?php echo $aircraft_id; ?>" />
                                        <input type="hidden" name="maintenance_note_id" id="maintenance_note_id" value="<?php echo @$aircraftmaintnotes->id; ?>" />
                                        <div class="col-md-12">
                                            <div class="form-group"> 
                                                <div class="form-input-frame">
                                                    <?php echo $this->Form->control('maintenance_notes', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5', 'id'=>'maintenance_notes')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary float-right saveAircraftMaintNotesBtn">Save</button>
                                        </div>
                                        <?php echo $this->Form->end(); ?>
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
    <script>
        var saveAircraftMaintNotesURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftMaintenanceNotes']); ?>";
        var saveAircraftMaintJetEngineURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftMaintJetEngine']); ?>";
        var checkWorkOrderLogBookValueURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'checkWorkOrderLogBookValue']); ?>";
        var updateWOLogBookValueFromMaintURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'updateWOLogBookValueFromMaint']); ?>";
    </script>
</div>