<div id="addPTOAccrualRatePopup" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Add PTO Accrual Rate</h4>
                <input type="hidden" name="pto_accrual_rate_id" id="pto_accrual_rate_id" />
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create($ptoaccrualrates, array('class' => 'form-horizontal form-label-left', 'id' => 'frmPTOAccrualRates', 'autocomplete'=>'off'));
                ?>
                <div  class="row">
                    <div  class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">PTO Accrual Rate (In Days)</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('pto_accrual_rate', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'PTO Accrual Rate', 'required'=>'required', 'id'=>'pto_accrual_rate')); ?>
                            </div>
                        </div>
                    </div>

                    <div  class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">PTO Accrual Rate Value</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('pto_accrual_rate_value', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'PTO Accrual Rate Value', 'required'=>'required', 'id'=>'pto_accrual_rate_value')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary save_pto_accrual_rate">Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>