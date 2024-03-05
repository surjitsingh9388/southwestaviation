<div id="addHoursToStartTimePopup" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 20%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Add Hours to Start Time</h4>
            </div>
            <div class="modal-body">
                <div  class="row">
                    <div  class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Add Hours to Start Time</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('add_hours_to_start_time', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'required'=>'required', 'id'=>'add_hours_to_start_time')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default changeLogoutTime">Continue</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>