<div id="checkTimeClockStatusPopup" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 20%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Check Time Clock Status</h4>
            </div>
            <div class="modal-body">
                <div  class="row">
                    <div  class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Time Clock Code</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('user_time_clock_status_code', array('type'=>'password', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'user_time_clock_status_code', 'value'=>'')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default float-left checkUserTimeClockStatus">Check</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>