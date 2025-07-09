<?php
echo $this->Form->create($aircraftmaintads, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftMaintAds', 'autocomplete' => 'off'));
?>
<input type="hidden" name="aircraft_id" value="<?php echo $aircraft_id; ?>" />
<input type="hidden" name="maintenance_ad_id" id="maintenance_ad_id" value="<?php echo @$aircraftmaintads->id; ?>" />

<fieldset class="scheduler-border hide-block" id="editadinfo">
    <legend class="scheduler-border">Edit AD Info</legend>
    
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label" for="reference">AD No.</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('ad_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'ad_no')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="reference">Recurring Date</label>
            <div class="form-input-frame">
                <div class="input-group date datePicker">
                    <?php echo $this->Form->Text('ad_recurring_date', array('class' => 'form-control', 'id' => 'ad_recurring_date', 'placeholder' => '', 'label' => false)); ?>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label" for="reference">Recurring Landings</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('ad_recurring_landings', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'ad_recurring_landings')); ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label class="control-label" for="reference">AD Name</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('ad_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'ad_name')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="reference">Recurring Time</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('ad_recurring_time', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'ad_recurring_time')); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label" for="reference">Revision Date</label>
                    <div class="form-input-frame">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('ad_revision_date', array('class' => 'form-control', 'id' => 'ad_revision_date', 'placeholder' => '', 'label' => false)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label" for="reference">Recurring Cycles</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('ad_recurring_cycles', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'ad_recurring_cycles')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-group"> 
                    <label class="control-label label-heading-left" for="plane_id">Notes</label>
                    <span class="label-chkbox-right">
                        <?php
                        $ad_always_recurringchk = '';
                        if(!empty($aircraftmaintads->ad_always_recurring)){
                            $ad_always_recurringchk = 'checked';
                        }
                        ?>
                        <input class="form-check-input" type="checkbox" value="1" id="ad_always_recurring" name="ad_always_recurring" <?php echo $ad_always_recurringchk; ?>>
                        <span class="form-check-label" for="ad_always_recurring">Always Recurring</span>
                    </span>
                    
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('ad_notes', array('type'=>'textarea', 'class' => 'form-control ', 'label'=> false, 'row'=>'5', 'id'=>'ad_notes')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-12 col-xs-12">
        <fieldset class="scheduler-border sign-off-fldset">
            <legend class="scheduler-border">Sign-Offs</legend>
            <div class="form-group">
                <div class="form-input-frame">
                    <?php echo $this->Form->control('ad_signoff', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'ad_signoff')); ?>
                </div>
            </div>
            <div class="sign-off-tbl-scroll">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Inspector</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody id="adsignoffblock">
                        
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</fieldset>

<?php echo $this->Form->end(); ?>