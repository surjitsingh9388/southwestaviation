<div class="modal-header" style="background-color: #e5e5e5;">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><span class="gpTypeCls"></span>Transaction History Filter</h4>
</div>
<div class="modal-body" style="max-height: 500px; overflow-y: auto;">
    <?php echo $this->Form->create('', ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Period</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                    <?php 
                        $period = array('1'=>'Past 7 Days', '2'=>'Past 30 Days', '3'=>'Year-to-Date', '4'=>'Custom Range');
                        echo $this->Form->control('transaction_period', array('options' => $period, 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'transaction_period', 'value'=>'2')); 
                        ?>
                    </div>
                </div>
            </div>
        </div>    

        <div class="row mt5" id="period_custom_range" style="display:none;">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                    <div class="col-sm-9 col-xs-12" style="padding-left:0px; padding-right:0px;">
                        <div class="col-xs-4">
                            <select id="period_date" name="period_date" class="form-control">
                                <option value="1" selected="selected">Equals</option>
                                <option value="2">Between</option>
                                <option value="3">Before</option>
                                <option value="4">After</option>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-group date datePicker">
                                <?php echo $this->Form->Text('period_date_start', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'period_date_start', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'), 'value'=>date('m-d-Y'))); ?>
                                <span class="input-group-addon" style="display:none;">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="input-group date datePicker">
                                <?php echo $this->Form->Text('period_date_end', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'period_date_end', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'), 'disabled'=>'disabled')); ?>
                                <span class="input-group-addon" style="display:none;">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Action</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('transaction_action', array('class'=>'form-control col-md-9 col-xs-12', 'placeholder' => '', 'label' => false, 'id'=>'transaction_action')); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Part Type</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php 
                            $parttype = array('2'=>'All', '1'=>'Serialized', '0'=>'Non-Serialized');
                            echo $this->Form->control('is_this_item_serialized', array('options' => $parttype, 'empty' => 'Enter Type ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'is_this_item_serialized')); 
                        ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Capital Equipment</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="capital_equipment" id="f_capital_equipment" value="2" checked>
                            <label class="form-check-label" for="capital_equipment">All</label>&nbsp;&nbsp;

                            <input class="form-check-input" type="radio" name="capital_equipment" id="f_capital_equipment" value="1">
                            <label class="form-check-label" for="inlineRadio2">Yes</label>

                            <input class="form-check-input" type="radio" name="capital_equipment" id="f_capital_equipment" value="0">
                            <label class="form-check-label" for="capital_equipment">No</label>&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Quantity</label>
                    <div class="col-sm-9 col-xs-12" style="padding-left:0px; padding-right:0px;">
                        <div class="col-xs-4">
                            <select id="qty_range" name="qty_range" class="form-control">
                                <option value="1" selected="selected">Equals</option>
                                <option value="2">Between</option>
                                <option value="3">Greater Than</option>
                                <option value="4">Less Than</option>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <?php echo $this->Form->control('minqty', array('class'=>'form-control col-md-12 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <?php echo $this->Form->control('maxqty', array('class'=>'form-control col-md-12 col-xs-12', 'placeholder' => '', 'label' => false, 'disabled' => 'disabled')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Part Number</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('part_number', array('class'=>'form-control col-md-9 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Lot or Serial</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php echo $this->Form->control('serial_no', array('class'=>'form-control col-md-9 col-xs-12', 'placeholder' => '', 'label' => false)); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt5">
            <div class="col-md-12">
                <div class="form-group"> 
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Account Code</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?php 
                            $account_code = [];
                            echo $this->Form->control('account_code', array('options' => $account_code, 'empty' => 'Enter condition ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code')); 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>

    <div class="modal-footer">
        <button type="button" class="btn btn-default clearapplyfilter" onclick='$("#transaction_action").tokenInput("clear");'>Clear</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick='$("#transaction_action").tokenInput("clear");'>Cancel</button>
        <button type="button" class="btn btn-primary applyfilterbtn">Apply Filter</button>
    </div>
</div>