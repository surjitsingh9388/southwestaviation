<div class="modal-dialog" style="width: 40%; height:auto;">
    <div class="modal-content">
        <div class="modal-header" style="background-color: #e5e5e5;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><span class="gpTypeCls"></span>Filter</h4>
        </div>
        <div class="modal-body" style="max-height: 500px;">
            <div class="page-content">
                <div class="row mt10">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                            <?php 
                                $invRequestStatus = unserialize(INVENTORY_REQUEST_STATUS);
                                echo $this->Form->control('request_status', array('options' => $invRequestStatus, 'empty' => 'Enter a status ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'request_status')); 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Request Date</label>
                            <div class="col-sm-9 col-xs-12" style="padding-left:0px; padding-right:0px;">
                                <div class="col-xs-4">
                                    <select id="request_date" name="request_date" class="form-control">
                                        <option value="1" selected="selected">Equals</option>
                                        <option value="2">Between</option>
                                        <option value="3">Before</option>
                                        <option value="4">After</option>
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('request_date_start', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'request_date_start', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'))); ?>
                                        <span class="input-group-addon" style="display:none;">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('request_date_end', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'request_date_end', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'), 'disabled'=>'disabled')); ?>
                                        <span class="input-group-addon" style="display:none;">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Required Date</label>
                            <div class="col-sm-9 col-xs-12" style="padding-left:0px; padding-right:0px;">
                                <div class="col-xs-4">
                                    <select id="required_date" name="required_date" class="form-control">
                                        <option value="1" selected="selected">Equals</option>
                                        <option value="2">Between</option>
                                        <option value="3">Before</option>
                                        <option value="4">After</option>
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('required_date_start', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'required_date_start', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'))); ?>
                                        <span class="input-group-addon" style="display:none;">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('required_date_end', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'required_date_end', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'), 'disabled'=>'disabled')); ?>
                                        <span class="input-group-addon" style="display:none;">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Urgent Item?</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="urgent_item" id="inlineRadio1" value="1">
                                    <label class="form-check-label" for="inlineRadio1">Yes</label>&nbsp;&nbsp;

                                    <input class="form-check-input" type="radio" name="urgent_item" id="inlineRadio2" value="0">
                                    <label class="form-check-label" for="inlineRadio2">No</label>

                                    <input class="form-check-input" type="radio" name="urgent_item" id="inlineRadio3" value="2" checked>
                                    <label class="form-check-label" for="inlineRadio3">Both</label>&nbsp;&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-md-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Urgency</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <?php
                                $urgency = unserialize(URGENCY);
                                echo $this->Form->control('urgency', array('options' => $urgency, 'empty' => 'Enter an uragency ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'urgency')); 
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt10">
                    <div class="col-md-12">
                        <div class="form-group"> 
                        <div class="checkbox"><label for="show_inactive"><input id="show_inactive" name="show_inactive" type="checkbox" value="1">Include Inactive</label></div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default clearapplyfilter">Clear</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary applyfilterbtn">Apply Filter</button>
            </div>
        </div>
    </div>
</div>