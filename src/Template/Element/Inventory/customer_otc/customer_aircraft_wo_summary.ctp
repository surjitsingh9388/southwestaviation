<section class="top-form-section">
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-12"><h7 class="wosummaryh1">Item Tools</h7></div>
        </div>
        <div class="col col-md-6">
            <div class="col-md-12"><h7 class="wosummaryh2">Item Adjustment</h7></div>
        </div>
    </div>
    <div class="row">
        <?php
        echo $this->Form->create($aircraftwoitemservices, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWorkOrderItemSummary'));

        $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '';
        ?>
        <div class="col-md-6">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="reference">Estimated Hours</label>
                    <div class="form-input-frame">
                        <?php 
                        $estimated_hour = @$aircraftwoitemoverviews->estimated_hour;
                        echo $this->Form->control('estimated_hrs_for_item', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00', 'readonly'=>'readonly', 'value'=>$estimated_hour)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="reference">Hours Worked</label>
                    <div class="form-input-frame">
                        <?php 
                        $total_hrs_for_item = @$aircraftwoitemservices->total_hrs_for_item;
                        echo $this->Form->control('total_hrs_for_item', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00', 'readonly'=>'readonly', 'value'=>$total_hrs_for_item)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="reference">Hours Left</label>
                    <div class="form-input-frame">
                        <?php 
                        $hrsleft = $estimated_hour-$total_hrs_for_item;
                        echo $this->Form->control('hour_left', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00', 'readonly'=>'readonly', 'value'=>$hrsleft)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <?php
                $aircraftWOWayofBilling = unserialize(AIRCRAFT_WORKORDER_WAYOF_BILLING);
                $way_of_billing = !empty($aircraftwoitemoverviews->way_of_billing) ? $aircraftWOWayofBilling[$aircraftwoitemoverviews->way_of_billing] : '';
                ?>
                <p>The item billing method is: <?php echo $way_of_billing; ?></p>
            </div>
            <div class="col-md-12">
                <p><b>All Items: Time Totals</b></p>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="reference">Total Estimat Hrs</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('aircraft_wo_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00', 'readonly'=>'readonly', 'value'=>$woitemsummary['totalestimatedhour'], 'disabled'=>$isdisabled)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="reference">Total Hrs Worked</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('aircraft_wo_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00', 'readonly'=>'readonly', 'value'=>$woitemsummary['totaltechhour'], 'disabled'=>$isdisabled)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="reference">Total Hrs Left</label>
                    <div class="form-input-frame">
                        <?php 
                        $totalhrsleft = $woitemsummary['totalestimatedhour'] - $woitemsummary['totaltechhour'];
                        echo $this->Form->control('aircraft_wo_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00', 'readonly'=>'readonly', 'value'=>$totalhrsleft, 'disabled'=>$isdisabled)); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-12">
                <p>Each item can have a time adjustment for the invoice. This requires admin access.</p>
                <p>If subtracting time, insert a negative number.</p>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="reference">Hour Adjustment</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('aircraft_wo_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00', 'disabled'=>$isdisabled)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label" for="reference">Adjust Rate / Hour</label>
                    <div class="form-input-frame">
                        <?php echo $this->Form->control('aircraft_wo_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'0.00', 'value'=>ESTIMATEDRATE, 'disabled'=>$isdisabled)); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</section>