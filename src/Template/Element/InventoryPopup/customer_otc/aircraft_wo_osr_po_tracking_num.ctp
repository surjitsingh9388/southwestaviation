<div id="woOSRAddlTrackingNumModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create($woosrinfopo, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOOSRInfoPOTrackingNum'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Tracking Numbers</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">Additional tracking numbers for Purchase Order:</div>
                    <div class="col-md-12">
                        <hr/>
                        <input type="hidden" name="osr_infopoes_id" id="osr_infopoes_id" value="<?php echo @$woosrinfopo->id; ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="reference">Tracking Number1</label>
                            <div class="col-md-8">
                                <?php echo $this->Form->control('tracking_number1', array('class' => 'form-control col-md-8', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'po_tracking_number1')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-10">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="reference">Tracking Number2</label>
                            <div class="col-md-8">
                                <?php echo $this->Form->control('tracking_number2', array('class' => 'form-control col-md-8', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'po_tracking_number2')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-10">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="reference">Tracking Number3</label>
                            <div class="col-md-8">
                                <?php echo $this->Form->control('tracking_number3', array('class' => 'form-control col-md-8', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'po_tracking_number3')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-10">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="reference">Tracking Number4</label>
                            <div class="col-md-8">
                                <?php echo $this->Form->control('tracking_number4', array('class' => 'form-control col-md-8', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'po_tracking_number4')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-10">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="reference">Tracking Number5</label>
                            <div class="col-md-8">
                                <?php echo $this->Form->control('tracking_number5', array('class' => 'form-control col-md-8', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'po_tracking_number5')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-10">
                        <div class="form-group">
                            <label class="control-label col-md-4" for="reference">Additional Tracking Numbers</label>
                            <div class="col-md-8">
                                <?php echo $this->Form->control('additional_tracking_numbers', array('type'=>'textarea', 'class' => 'form-control col-md-8', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saveWOOSRAddlTrackingNum">Save</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>