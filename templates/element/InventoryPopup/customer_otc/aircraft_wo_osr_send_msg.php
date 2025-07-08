<div id="woOSRSendMsgModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 20%;">
        <?php
        echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmSendWOViewMessage'));

        $work_order_id = !empty($work_order_id) ? $work_order_id : '';
        $wo_item_id = !empty($wo_item_id) ? $wo_item_id : '';
        ?>
        <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
        <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
        <input type="hidden" name="message_subject" id="message_subject" value="<?php echo 'OSR Request: '.$work_order_no; ?>" />
        <input type="hidden" name="osr_message" value="<?php echo 'Outside repair items need attention (W/O #'.$work_order_no.', Item #'.$wo_item_no.')'; ?>" />
        
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send OSR Message</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Send Message To</label>
                            <div class="form-input-frame">
                                <?php
                                    echo $this->Form->control('message_to', array('options' => $userlist, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                ?>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Notes</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('message', array('type'=>'textarea', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary sendWOViewMessage" data-dismiss="modal">Continue</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>