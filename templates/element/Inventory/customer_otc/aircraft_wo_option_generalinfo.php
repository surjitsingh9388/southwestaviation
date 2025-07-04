<section class="top-form-section">
    <?php
    echo $this->Form->create($wooptiongeninfoes, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOptionGenInfo'));
    ?>
    <div class="row">
        <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
        <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
        <input type="hidden" name="general_info_id" id="general_info_id" value="<?php echo @$wooptiongeninfoes->id; ?>" />
        <?php $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : ''; ?>

        <div class="col-md-12">
            <div class="col-md-8">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="reference">Customer P/O #</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('customer_po', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="reference">Service Quote #</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->control('service_quote', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="reference">Terms</label>
                        <div class="form-input-frame">
                            <?php
                            $customerterms = unserialize(CUSTOMERTERMS);
                            echo $this->Form->control('terms', array('options' => $customerterms, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'geninfo_terms'));
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label" for="reference">Disclaimer for R/O</label>
                        <div class="form-input-frame">
                            <?php 
                            $disclforRo = '';
                            if($aircraftworkorders->order_type == '1'){
                                $disclforRo = 'readonly';
                            }
                            echo $this->Form->control('disclaimer_for_ro', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'readonly'=>$disclforRo)); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Date</legend>
                    <div class="col-md-6">
                        <div class="form-group d-flex">
                            <label class="control-label" for="reference">Created</label>
                            <div class="form-input-frame">
                                <?php
                                $wo_created = date('d/m/Y', strtotime($aircraftworkorders->created_at)); 
                                echo $this->Form->Text('wo_created', array('class' => 'form-control', 'id' => 'wo_created', 'placeholder' => '', 'label' => false, 'value'=>$wo_created, 'readonly'=>'readonly')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group d-flex">
                            <label class="control-label" for="reference">Completed</label>
                            <div class="form-input-frame">
                                <?php 
                                echo $this->Form->Text('completed', array('class' => 'form-control', 'id' => 'option_gen_info_completed', 'placeholder' => '', 'label' => false, 'readonly'=>'readonly')); ?>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="reference">Min. Hours Worked Per Item</label>
                    <div class="form-input-frame">
                        <?php 
                        $min_hour_worked_per_item = !empty($wooptiongeninfoes->min_hour_worked_per_item) ? number_format($wooptiongeninfoes->min_hour_worked_per_item, 2) : '0.00';

                        echo $this->Form->control('min_hour_worked_per_item', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00', 'value'=>$min_hour_worked_per_item)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label" for="reference">Overtime Hrs.</label>
                    <div class="form-input-frame">
                        <?php 
                        $overtime_hrs = !empty($wooptiongeninfoes->overtime_hrs) ? number_format($wooptiongeninfoes->overtime_hrs, 2) : '0.00';

                        echo $this->Form->control('overtime_hrs', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>$overtime_hrs)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label" for="reference">Add Hrs. Inspection</label>
                    <div class="form-input-frame">
                        <?php 
                        $add_hrs_inspection = !empty($wooptiongeninfoes->add_hrs_inspection) ? number_format($wooptiongeninfoes->add_hrs_inspection, 2) : '0.00';

                        echo $this->Form->control('add_hrs_inspection', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>$add_hrs_inspection)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group d-flex">
                    <label class="control-label" for="reference">Date Due</label>
                    <div class="input-group date datePicker">
                        <?php 
                        echo $this->Form->Text('date_due', array('class' => 'form-control', 'id' => 'option_gen_info_due_date', 'placeholder' => '', 'label' => false)); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="reference">Lead Technician</label>
                    <div class="form-input-frame">
                        <?php
                        echo $this->Form->control('lead_technician', array('options' => $userlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label" for="reference">Sales Rep.</label>
                    <div class="form-input-frame">
                        <?php
                        echo $this->Form->control('sales_reply', array('options' => $userlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group"> 
                    <label class="control-label" for="reference">Status Notes</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->input('status_notes', array('type' => 'textarea', 'class'=>'form-control', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-8">
                <div class="form-group">
                    <label class="control-label" for="reference">Est/Invoice - Show A/C Times Profile</label>
                    <div class="form-input-frame">
                        <?php
                        echo $this->Form->control('est_invoice', array('options' => [], 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'est_invoice'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div class="col-md-12">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Deposits</legend>
                <div class="col-md-12">
                    <div class="form-group"> 
                        <label class="control-label" for="reference">Notes</label>
                        <div class="form-input-frame">
                            <?php echo $this->Form->input('deposit_notes', array('type' => 'textarea', 'class'=>'form-control', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'deposit_notes')); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label" for="reference">Total Deposit Amount</label>
                        <div class="form-input-frame">
                            <?php 
                            $total_deposit_amount = !empty($wooptiongeninfoes->total_deposit_amount) ? '$'.number_format($wooptiongeninfoes->total_deposit_amount, 2) : '$0.00';

                            echo $this->Form->control('total_deposit_amount', array('type' => 'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'readonly'=>'readonly', 'value'=>$total_deposit_amount)); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-default aircraft-wo-geninfo-manage-deposit" style="margin-top: 27px;">Add/Edit Deposits</button>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-3"></div>
            </fieldset>
        </div>
        <div class="col-md-12">
            <div class="col-md-3">
                <button type="button" class="btn btn-default wo-option-log-book-values">Log Book Values</button>
            </div>
            <div class="col-md-5" style="text-align:right;">
                <label class="control-label" for="reference">Accouting Invoice#</label>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('accounting_invoice', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button type="button" class="btn btn-primary float-right saveWOOptionGenInfo" <?php echo $isdisabled; ?>>Save</button>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>
</section>
<script>
    var saveWOViewOptionGenInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOViewOptionGenInfo']); ?>";
</script>