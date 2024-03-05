<div id="woPartsSendMsgModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 20%;">
        <?php
        //echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomersNotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Parts Needed Message</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Send Message To</label>
                            <div class="form-input-frame">
                                <?php
                                    echo $this->Form->control('parts_message_to', array('options' => $userlist, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'repair_done_by'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Notes</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('wo_osr_message_note', array('type'=>'textarea', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary sendWOPartsMsg" data-dismiss="modal">Continue</button>
            </div>
        </div>
        <?php 
        //echo $this->Form->end(); 
        ?>
    </div>
</div>