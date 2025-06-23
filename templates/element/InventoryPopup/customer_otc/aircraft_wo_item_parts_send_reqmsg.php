<div id="woPartsSendNeededMsgModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Send Parts Needed Message</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <?php
                    echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOPartsSendNeededMsg'));
                    ?>
                    <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Send Message To</label>
                                <div class="form-input-frame">
                                    <?php
                                    echo $this->Form->control('message_to', array('options' => $userlist, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label" for="reference">Notes</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->input('message_notes', array('type' => 'textarea', 'class'=>'form-control col-md-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary sendWOItemPartSendMessage">Continue</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
    var sendWOViewMessageURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'sendWOViewMessage']); ?>";
</script>