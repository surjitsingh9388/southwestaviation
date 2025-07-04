<div class="modal-dialog" style="width: 30%; height:auto;">
    <div class="modal-content">
        <div class="modal-header" style="background-color: #e5e5e5;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><span class="gpTypeCls"></span>Filter</h4>
        </div>
        <div class="modal-body" style="max-height: 500px;">
            <div class="page-content">
                <div class="row mt10">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                            <?php 
                                $invRepairStatus = unserialize(REPAIR_ORDER_STATUS);
                                echo $this->Form->control('ro_status', array('options' => $invRepairStatus, 'empty' => 'Enter a status ...', 'class' => 'form-control col-md-3 col-sm-3 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'ro_status')); 
                                ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Start Date</label>
                            <div class="col-sm-9 col-sm-9 col-xs-12">
                                <div class="input-group date datePicker" style="width: 100%;">
                                    <?php echo $this->Form->Text('ro_date_start', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'ro_date_start', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'))); ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">End Date</label>
                              <div class="col-sm-9 col-sm-9 col-xs-12">
                                <div class="input-group date datePicker" style="width: 100%;">
                                    <?php echo $this->Form->Text('ro_date_end', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'ro_date_end', 'placeholder' => '', 'label' => false, 'placeholder'=>'e.g. '.date('m-d-Y'))); ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Min Cost</label>
                             <div class="col-sm-9 col-sm-9 col-xs-12">
                                <?php echo $this->Form->control('ro_min_amount', array('type'=>'number', 'class'=>'form-control col-md-3 col-xs-12', 'placeholder' => '', 'label' => false, 'id'=>'ro_min_amount')); ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="item_type">Max Cost</label>
                              <div class="col-sm-9 col-sm-9 col-xs-12">
                                <?php echo $this->Form->control('ro_max_amount', array('type'=>'number', 'class'=>'form-control col-md-3 col-xs-12', 'placeholder' => '', 'label' => false, 'id'=>'ro_max_amount')); ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group"> 
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Account Code</label>
                                <div class="col-sm-9 col-sm-9 col-xs-12">
                                <?php
                                echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Enter Type ...', 'class' => 'form-control col-md-3 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'po_account_code')); 
                                ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt10"></div>

            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default clearapplyfilter">Clear</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary applyfilterbtn">Apply Filter</button>
            </div>
        </div>
    </div>
</div>