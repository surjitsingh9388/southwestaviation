<div id="userTimeClockPopup" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Time Clock</h4>
            </div>
            <div class="modal-body">
                <div  class="row">
                    <div  class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Enter your time clock code</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('user_time_clock_code', array('type'=>'password', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'user_time_clock_code', 'value'=>'')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default float-left fetchUserTimeClockPopup" data-val='check_time_clock_status'>Check Status</button>
                <button type="button" class="btn btn-default markUserTimeClockBtn">Process</button>
            </div>
        </div>
    </div>
</div>