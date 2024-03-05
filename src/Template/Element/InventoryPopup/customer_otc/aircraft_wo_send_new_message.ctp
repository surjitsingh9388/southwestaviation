<div id="aircraftWOSendNewMsgModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Send Message</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <?php
                    echo $this->Form->create($wooptionmessages, array('class' => 'form-horizontal form-label-left', 'id' => 'frmSendWOViewMessage'));

                    $work_order_id = !empty($work_order_id) ? $work_order_id : '';
                    $wo_item_id = !empty($wo_item_id) ? $wo_item_id : '';
                    ?>
                    <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
                    <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="reference">To</label>
                                <div class="col-md-10">
                                    <?php
                                    echo $this->Form->control('message_to', array('options' => $userlist, 'empty' => 'Select', 'class' => 'form-control selectpicker mh', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    ?>
                                </div>
                                <!--div class="col-md-1">
                                    <span>Multiple...</span>
                                </div-->
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="reference">Subject</label>
                                <div class="col-md-10">
                                    <?php echo $this->Form->control('message_subject', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="reference">W/O or R/O</label>
                                <div class="col-md-9">
                                    <?php
                                    echo $this->Form->control('message_wo_ro', array('options' => $worodata, 'empty' => 'Select', 'class' => 'form-control selectpicker mh', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    ?>
                                </div>
                                <div class="col-md-1">
                                    <span>(Optional)</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <?php echo $this->Form->input('message', array('type' => 'textarea', 'class'=>'form-control col-md-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="col-md-9">&nbsp;</div>
                            <div class="col-md-3" style="text-align:right;">
                                <!--a href="#" style="color:blue;">Is user online?</a-->
                                <button type="button" class="btn btn-primary sendWOViewMessage">Send</button>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>