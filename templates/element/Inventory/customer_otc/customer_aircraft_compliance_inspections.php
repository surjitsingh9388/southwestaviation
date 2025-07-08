<section class="top-form-section aricraftcomplinspectblock">
    <?php
    echo $this->Form->create($aircraftcomplinspections, array('action' => 'saveAircraftComplInspections', 'class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftComplInspections', 'autocomplete' => 'off'));
    ?>
    <div class="row">
        <input type="hidden" name="aircraft_id" value="<?php echo $aircraft_id; ?>" />
        <input type="hidden" name="compliance_inspections_id" id="compliance_inspections_id" value="<?php echo @$aircraftcomplinspections->id; ?>" />
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Inspection Name</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('inspection_name', array('class' => 'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id' => 'inspection_name')); ?>
                    </div>
                    <!-- <div class="col-md-3"></div> -->
                </div>



                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">ATA Code</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('ata_code', array('class' => 'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id' => 'inspections_ata_code')); ?>
                    </div>
                    <!-- <div class="col-md-3"></div> -->
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Interval(Hour)</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php
                        $inspections_interval_hours_readonly = '';
                        if (!empty($aircraftcomplinspections->use_cycles)) {
                            $inspections_interval_hours_readonly = 'readonly';
                        }
                        echo $this->Form->control('interval_hours', array('type' => 'number', 'class' => 'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id' => 'inspections_interval_hours', 'readonly' => $inspections_interval_hours_readonly)); ?>
                    </div>
                    <!-- <div class="col-md-3"></div> -->
                </div>

                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Interval(Months)</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php
                        $inspections_interval_month_readonly = '';
                        if (!empty($aircraftcomplinspections->use_cycles)) {
                            $inspections_interval_month_readonly = 'readonly';
                        }

                        echo $this->Form->control('interval_months', array('type' => 'number', 'class' => 'form-control col-md-5', 'placeholder' => '', 'label' => false, 'id' => 'inspections_interval_months', 'readonly' => $inspections_interval_month_readonly)); ?>
                    </div>
                    <!-- <div class="col-md-3"></div> -->
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Interval(Cycles)</label>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <?php
                        $inspections_interval_cycles_readonly = 'readonly';
                        if (!empty($aircraftcomplinspections->use_cycles)) {
                            $inspections_interval_cycles_readonly = '';
                        }
                        echo $this->Form->control('interval_cycles', array('type' => 'number', 'class' => 'form-control col-md-5', 'placeholder' => '', 'label' => false, 'readonly' => $inspections_interval_cycles_readonly, 'id' => 'inspections_interval_cycles')); ?>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <?php
                        $usercyclechk = '';
                        if (!empty($aircraftcomplinspections->use_cycles)) {
                            $usercyclechk = 'checked';
                        }
                        ?>
                        <input class="form-check-input" type="checkbox" value="1" id="inspections_use_cycles" name="use_cycles" <?php echo $usercyclechk; ?>>
                        <span class="form-check-label" for="inspections_use_cycles">Use Cycles</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Type</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php
                        $aircraftComplianceType = unserialize(AIRCRAFT_COMPLIANCE_TYPE);
                        echo $this->Form->control('type', array('options' => $aircraftComplianceType, 'empty' => '', 'class' => 'form-control col-md-5 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'inspections_type'));
                        ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>


            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Current AC Tach</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('current_ac_tach', array('class' => 'form-control col-md-5', 'placeholder' => '', 'label' => false, 'readonly' => 'readonly', 'id' => 'inspections_current_ac_tach')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Tach Correction</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('tach_correction', array('type' => 'number', 'class' => 'form-control col-md-5', 'placeholder' => '', 'label' => false, 'readonly' => 'readonly', 'id' => 'inspections_tach_correction')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Current AC TT</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('current_ac_tt', array('class' => 'form-control col-md-5', 'placeholder' => '', 'label' => false, 'readonly' => 'readonly', 'id' => 'inspections_current_ac_tt')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Current AC Lndgs</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('current_ac_landings', array('class' => 'form-control col-md-5', 'placeholder' => '', 'label' => false, 'readonly' => 'readonly', 'id' => 'inspections_current_ac_landings')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Due(Hours)</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('due_hours', array('type' => 'number', 'class' => 'form-control col-md-5', 'placeholder' => '', 'label' => false, 'readonly' => 'readonly', 'id' => 'inspections_due_hours')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Due(Date)</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <div class="input-group date datePicker">
                            <?php
                            $due_date = !empty($aircraftcomplinspections->due_date) ? date('m-d-Y', strtotime($aircraftcomplinspections->due_date)) : '';
                            echo $this->Form->Text('due_date', array('class' => 'form-control col-md-5', 'id' => 'inspections_due_date', 'placeholder' => '', 'label' => false, 'value' => $due_date)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3"></div>
                </div>



                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id">Due(Cycles)</label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('due_cycles', array('type' => 'number', 'class' => 'form-control col-md-5', 'placeholder' => '', 'label' => false, 'readonly' => 'readonly', 'id' => 'inspections_due_cycles')); ?>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>



            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <?php
                    $inspections_duenextlandingacctt_h = 'Due Next - AC TT';
                    if (!empty($aircraftcomplinspections->use_cycles)) {
                        $inspections_duenextlandingacctt_h = 'Due Next - Lndgs';
                    }
                    ?>
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 due_next_landings_ac_tt" for="plane_id"><?php echo $inspections_duenextlandingacctt_h; ?></label>
                    <div class="col-md-8 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('due_next_landings_ac_tt', array('class' => 'form-control col-md-5', 'placeholder' => '', 'label' => false, 'readonly' => 'readonly', 'id' => 'due_next_landings_ac_tt')); ?>
                    </div>
                    <!-- <div class="col-md-3"></div> -->
                </div>

                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-3 col-xs-12 " for="plane_id"></label>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <button type="button" class="btn btn-default">Refresh</button>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <?php
                        $statuschk = '';
                        if (empty($aircraftcomplinspections->status)) {
                            $statuschk = 'checked';
                        }
                        ?>
                        <input class="form-check-input" type="checkbox" value="1" id="inspections_status" name="status" <?php echo $statuschk; ?>>
                        <span class="form-check-label" for="inspections_status">Inactive</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Inspections</legend>
                <div class="aircraft-inspection-fieldset">
                    <p>Inspection History</p>
                    <table class="table table-bordered">
                        <?php
                        $inspections_col3_h = 'Hrs';
                        if (!empty($aircraftcomplinspections->use_cycles)) {
                            $inspections_col3_h = 'Lndgs';
                        }
                        ?>
                        <thead>
                            <tr>
                                <th scope="col">User</th>
                                <th scope="col">Date</th>
                                <th scope="col" class="insp_history_col3"><?php echo $inspections_col3_h; ?></th>
                            </tr>
                        </thead>
                        <tbody id="complinsphistory">
                            <?php
                            if (!empty($aircraftcomplinspectionshisties)) {
                                foreach ($aircraftcomplinspectionshisties as $inpshist) {
                            ?>
                                    <tr>
                                        <td><?php echo $inpshist['inspection_code']; ?></td>
                                        <td><?php echo $inpshist['date_override']; ?></td>
                                        <td><?php echo $inpshist['insp_current_ac_tach']; ?></td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Inspection Code</label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <?php echo $this->Form->control('inspection_code', array('class' => 'form-control col-md-5 col-sm-5 col-xs-12', 'placeholder' => '', 'label' => false, 'id' => 'inspection_code')); ?>
                        </div>
                        <!-- <div class="col-md-3"></div> -->
                    </div>

                    <div class="form-group">
                        <?php
                        $inspections_currentactach_h = 'Current AC Tach';
                        if (!empty($aircraftcomplinspections->use_cycles)) {
                            $inspections_currentactach_h = 'AC Landings';
                        }
                        ?>
                        <label class="control-label col-md-4 col-sm-3 col-xs-12 insp_current_ac_tach" for="plane_id"><?php echo $inspections_currentactach_h; ?></label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <?php echo $this->Form->control('insp_current_ac_tach', array('type' => 'number', 'class' => 'form-control col-md-5 col-sm-5 col-xs-12', 'placeholder' => '', 'label' => false, 'id' => 'insp_current_ac_tach')); ?>
                        </div>
                        <!-- <div class="col-md-3"></div> -->
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-3 col-xs-12" for="plane_id">Date Override</label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                            <div class="input-group date datePicker">
                                <?php
                                $date_override = '';
                                echo $this->Form->Text('date_override', array('class' => 'form-control col-md-5', 'id' => 'inspections_date_override', 'placeholder' => '', 'label' => false, 'value' => $date_override)); ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-primary saveCompInspHistoryBtn">Add</button>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label" for="plane_id">Notes</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('notes', array('type' => 'textarea', 'class' => 'form-control aircraft-notes', 'label' => false, 'id' => 'insp_notes')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <?php
                    $allow_negative_hourschk = '';
                    if (!empty($aircraftcomplinspections->allow_negative_hours)) {
                        $allow_negative_hourschk = 'checked';
                    }
                    ?>
                    <input class="form-check-input" type="checkbox" value="1" id="allow_negative_hours" name="allow_negative_hours" <?php echo $allow_negative_hourschk; ?>>
                    <span class="form-check-label" for="allow_negative_hours">Allow Negative Hours(for reporting)</span>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12">
            <button type="button" class="btn btn-primary saveCompInspBtn float-right">Save</button>
        </div>
    </div>
    <?php
    echo $this->Form->end();
    ?>
</section>
<script>
    var saveAircraftComplInspectionsURL = "<?php echo $this->Url->build(['controller' => 'InventoryCustomers', 'action' => 'saveAircraftComplInspections']); ?>";
    var saveAircraftComplInspHistoryURL = "<?php echo $this->Url->build(['controller' => 'InventoryCustomers', 'action' => 'saveAircraftComplInspHistory']); ?>";
    var getAircraftComplianceDetailURL = "<?php echo $this->Url->build(['controller' => 'InventoryCustomers', 'action' => 'getAircraftComplianceDetail']); ?>";
    var removeAircraftComplianceDetailURL = "<?php echo $this->Url->build(['controller' => 'InventoryCustomers', 'action' => 'removeAircraftComplianceDetail']); ?>";
</script>