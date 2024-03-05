
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Status</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                <?php 
                    $invRequestStatus = unserialize(INVENTORY_REQUEST_STATUS);
                    echo $this->Form->control('name', array('options' => $invRequestStatus, 'empty' => 'Enter Type ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'item_type')); 
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Request Date</label>
                <div class="col-sm-9 col-xs-12" style="padding-left:0px; padding-right:0px;">
                    <div class="col-xs-4">
                        <select id="request_date" class="form-control">
                            <option value="1" selected="selected">Equals</option>
                            <option value="2">Between</option>
                            <option value="3">Before</option>
                            <option value="4">After</option>
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('request_date_start', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'request_date_start', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'))); ?>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('request_date_end', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'request_date_end', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'), 'disabled'=>'disabled')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Required Date</label>
                <div class="col-sm-9 col-xs-12" style="padding-left:0px; padding-right:0px;">
                    <div class="col-xs-4">
                        <select id="required_date" class="form-control">
                            <option value="1" selected="selected">Equals</option>
                            <option value="2">Between</option>
                            <option value="3">Before</option>
                            <option value="4">After</option>
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('required_date_start', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'required_date_start', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'))); ?>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="input-group date datePicker">
                            <?php echo $this->Form->Text('required_date_end', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'required_date_end', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'), 'disabled'=>'disabled')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Urgent Item?</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="urgent_item" id="inlineRadio1" value="1">
                        <label class="form-check-label" for="inlineRadio1">Yes</label>&nbsp;&nbsp;

                        <input class="form-check-input" type="radio" name="urgent_item" id="inlineRadio2" value="0">
                        <label class="form-check-label" for="inlineRadio2">No</label>

                        <input class="form-check-input" type="radio" name="urgent_item" id="inlineRadio1" value="2" checked>
                        <label class="form-check-label" for="inlineRadio1">Both</label>&nbsp;&nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt5">
        <div class="col-md-12">
            <div class="form-group"> 
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Urgency</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <?php
                    $urgency = unserialize(URGENCY);
                    echo $this->Form->control('urgency', array('options' => $urgency, 'empty' => 'Enter Type ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'item_type')); 
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>