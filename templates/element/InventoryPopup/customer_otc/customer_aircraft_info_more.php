<div id="aircraftInfoMoreModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create($aircraftregdetail, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftInfoMore'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">More Aircraft Options</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="aircraft_id" value="<?php echo $aircraft_id; ?>" />
                        <div class="form-group"> 
                            <label class="control-label col-md-4" for="plane_id">Default Department</label>
                            <div class="col-md-8">
                                <?php
                                $contractRateDepartment = unserialize(CONTRACT_RATE_DEPARTMENT);
                                echo $this->Form->control('aircraft_default_department', array('options' => $contractRateDepartment, 'empty' => '', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'engine_type'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="plane_id">QB Class Override</label>
                            <div class="col-md-8">
                                <?php echo $this->Form->control('aircraft_qbclass_override', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">If you have created Aircraft Time Profiles in Preferences > W/O & R/O Tab > More Options tab, you can set the default profiles for this aircraft here.</div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="plane_id">Estimate/Invoice</label>
                            <div class="col-md-8">
                                <?php
                                    $aircraftEngineType = [];
                                    echo $this->Form->control('aircraft_estimate', array('options' => $aircraftEngineType, 'empty' => '(Use Default)', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'engine_type'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="plane_id">Log Books</label>
                            <div class="col-md-8">
                                <?php
                                    $aircraftEngineType = [];
                                    echo $this->Form->control('aircraft_log_book', array('options' => $aircraftEngineType, 'empty' => '(Use Default)', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'engine_type'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="plane_id">Other Applicable Reports</label>
                            <div class="col-md-8">
                                <?php
                                    $aircraftEngineType = [];
                                    echo $this->Form->control('aircract_applicable_report', array('options' => $aircraftEngineType, 'empty' => '(Use Default)', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'engine_type'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary saveAircraftInfoMore" data-dismiss="modal">Save</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>