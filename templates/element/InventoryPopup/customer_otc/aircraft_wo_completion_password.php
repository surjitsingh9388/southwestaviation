<div id="aircarftWOStatusComplPwdModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close wo_status_close_btn" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Enter Completion Password</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Completion Password</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('wo_completion_password', array('type'=>'password', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_completion_password')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default wo_status_continue_btn">Continue</button>
                <button type="button" class="btn btn-default wo_status_close_btn" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
    <script>
        var validateWOStatusComplPasswordURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'validateWOStatusComplPassword']); ?>";
    </script>
</div>