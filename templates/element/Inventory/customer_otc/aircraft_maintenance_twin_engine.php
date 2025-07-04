<section class="top-form-section">
    <div class="row">
        <?php
        $formid = '';
        if($section == 'cust_otc_aircraft_maintenance'){
            $formid = 'frmAircraftMaintEngine';
            $isdisabled = '';
        }else{
            $formid = 'frmAircraftWOLogBookValEngine';
            $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '';
        }

        echo $this->Form->create($aircraftmaintengine, array('class' => 'form-horizontal form-label-left', 'id' => $formid, 'autocomplete' => 'off'));
        

        if($section == 'cust_otc_aircraft_maintenance'){
        ?>
            <input type="hidden" name="aircraft_id" value="<?php echo $aircraft_id; ?>" />
            <input type="hidden" name="maintenance_engine_id" id="maintenance_engine_id" value="<?php echo @$aircraftmaintengine->id; ?>" />
        <?php }else{ ?>
            <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
            <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
            <input type="hidden" name="logbook_value_engine_id" id="logbook_value_engine_id" value="<?php echo @$aircraftwologbookvalengine->id; ?>" />
        <?php } ?>
        <div class="col-md-2">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TSMOH-L</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tsmoh', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_tsmoh')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TTL-L</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('ttl', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_ttl')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TT Vac. Pump-L</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tt_vac_pump', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_tt_vac_pump')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TSMOH-R</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tsmoh_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'maint_eng_tsmoh_r')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TT-R</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tt_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'maint_eng_tt_r')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TT Vac. Pump-R</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tt_vac_pump_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'maint_eng_tt_vac_pump_r')); ?>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="col-md-2">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">O/H Date-L</label>
                    <div class="form-input-frame">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('oh_date', array('class' => 'form-control', 'id' => 'maint_eng_oh_date', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Model No.-L</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('model_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_model_no')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TSN-L</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tsn', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_tsn')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">O/H Date-R</label>
                    <div class="form-input-frame">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('oh_date_r', array('class' => 'form-control', 'id' => 'maint_eng_oh_date_r', 'placeholder' => '', 'label' => false, 'readonly'=>'readonly')); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Model No.-R</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('model_no_r', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'maint_eng_model_no_r')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TSN-R</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tsn_r', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'maint_eng_tsn_r')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Serial No.-L</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('serial_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_serial_no')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">TBO</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('tbo', array('type'=>'number', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_tbo')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Manufacturer</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('manufacturer', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'maint_eng_manufacturer')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="reference">Serial No.-R</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('serial_no_r', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>'readonly', 'id'=>'maint_eng_serial_no_r')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                
            </div>

            <div class="col-md-12"></div>
        </div>
        <div class="col-md-12">
            <?php 
            $btnclass = '';
            if($section == 'cust_otc_aircraft_maintenance'){ 
                $btnclass = 'saveAircraftMainEngine';
            }else{
                $btnclass = 'saveAircraftWOLogBookValEngine';
            }
            ?>
            <button type="button" class="btn btn-primary float-right <?php echo $btnclass; ?>" <?php echo $isdisabled; ?>>Save</button>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
    
    <?php if($section == 'cust_otc_aircraft_maintenance'){ ?>
    <div class="row">
        <?php
        echo $this->Form->create($aircraftmaintenginecylhistory, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftMaintEngineCylHistory', 'autocomplete' => 'off'));
        ?>
        <input type="hidden" name="aircraft_id" id="maint_engine_aircraft_id" value="<?php echo $aircraft_id; ?>" />
        <!--input type="hidden" name="maintenance_eng_history_id" id="maintenance_eng_history_id" value="<?php echo @$aircraftmaintenginecylhistory->id; ?>" /-->
        <input type="hidden" id="aircraft_maint_eng_cyl_date" name="eng_cyl_date" />
        <div class="col-md-12 cyclinderhistblock">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Cylinder History</legend>
                <div class="form-group col-md-12">
                    <label class="control-label col-md-1" for="reference"></label>

                    <label class="control-label col-md-1 text-center" for="reference">Cyl:</label>

                    <label class="control-label col-md-1 text-center" for="reference">1</label>

                    <label class="control-label col-md-1 text-center" for="reference">2</label>

                    <label class="control-label col-md-1 text-center" for="reference">3</label>

                    <label class="control-label col-md-1 text-center" for="reference">4</label>

                    <label class="control-label col-md-1 text-center" for="reference">5</label>

                    <label class="control-label col-md-1 text-center" for="reference">6</label>

                    <label class="col-md-4"></label>
                </div>
                <div class="form-group col-md-12">
                    <label class="control-label col-md-1 air-maint-engine-h" for="reference" style="width: 60px !important;">Engine</label>
                    
                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine1_cylinder_a', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine1_cylinder_1', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine1_cylinder_2', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine1_cylinder_3', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine1_cylinder_4', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine1_cylinder_5', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine1_cylinder_6', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-4"></div>
                </div>

                <div class="form-group col-md-12">
                    <label class="control-label col-md-1  air-maint-engine-h" for="reference" style="width: 60px !important;">Engine</label>
                    
                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine2_cylinder_b', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine2_cylinder_1', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine2_cylinder_2', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine2_cylinder_3', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine2_cylinder_4', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine2_cylinder_5', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-1">
                        <?php echo $this->Form->control('engine2_cylinder_6', array('class' => 'form-control col-md-1', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                    </div>

                    <div class="form-input-frame col-md-4 air-maint-engine-btn">
                        <button type="button" class="btn btn-default add-maint-engine-cylinder" data-val='aircraft-maint-eng-cyl' <?php echo $isdisabled; ?>>Add</button>
                    </div>
                </div>
            </fieldset>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="aircraft-maintenance-tbl-scroll">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Eng A</th>
                            <th scope="col">A1</th>
                            <th scope="col">A2</th>
                            <th scope="col">A3</th>
                            <th scope="col">A4</th>
                            <th scope="col">A5</th>
                            <th scope="col">A6</th>
                            <th scope="col">Eng B</th>
                            <th scope="col">B1</th>
                            <th scope="col">B2</th>
                            <th scope="col">B3</th>
                            <th scope="col">B4</th>
                            <th scope="col">B5</th>
                            <th scope="col">B6</th>
                        </tr>
                    </thead>
                    <tbody id="maintenghistorytbl">
                        <?php
                        $tblrow   = '';
                        foreach($aircraftmaintenginecylhistorylist as $cylhistory){
                            $tblrow   = '<tr>';
                            $tblrow .= '<td>'.$cylhistory->eng_cyl_date.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine1_cylinder_a.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine1_cylinder_1.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine1_cylinder_2.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine1_cylinder_3.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine1_cylinder_4.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine1_cylinder_5.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine1_cylinder_6.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine2_cylinder_b.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine2_cylinder_1.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine2_cylinder_2.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine2_cylinder_3.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine2_cylinder_4.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine2_cylinder_5.'</td>';
                            $tblrow .= '<td>'.$cylhistory->engine2_cylinder_6.'</td>';
                            $tblrow .= '</tr>';

                            echo $tblrow;
                        }
                        
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
</section>
<script>
    var saveAircraftMaintEngineURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftMaintEngine']); ?>";
    var saveAircraftMaintEngHistoryURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftMaintEngHistory']); ?>";
</script>