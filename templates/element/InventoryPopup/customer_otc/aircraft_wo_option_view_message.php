<div id="aircraftWOOptionViewMsgModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php echo $wooptionmessages['sent_from'].': '.$wooptionmessages['message_subject']; ?></h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <?php
                    echo $this->Form->create($messageobj, array('class' => 'form-horizontal form-label-left', 'id' => ''));
                    ?>
                    <input type="hidden" id="replay_message_id" value="<?php echo $wooptionmessages['id']; ?>" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="reference">From: </label>
                                <div class="col-md-10">
                                    <p class="form-control-static"><?php echo $wooptionmessages['sent_from']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="reference">Subject: </label>
                                <div class="col-md-10">
                                    <p class="form-control-static"><?php echo $wooptionmessages['message_subject']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="reference">W/O or R/O: </label>
                                <div class="col-md-10">
                                    <p class="form-control-static">
                                        <?php
                                        $wo_number = !empty($wooptionmessages['work_order_no']) ? $wooptionmessages['work_order_no'].'-'.$wooptionmessages['wo_item_position'] : '';
                                        echo $wo_number; 
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php echo $this->Form->input('message', array('type' => 'textarea', 'class'=>'form-control col-md-12', 'placeholder' => '', 'label' => false, 'disabled' => 'disabled', 'value'=>$wooptionmessages['message'])); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12" style="text-align:right;">
                                <!--a href="#" style="color:blue;">Is user online?</a-->
                                <?php if(!empty($userptorequests) && $userptorequests->pto_requests_status == '1'){ ?>
                                    <button type="button" class="btn btn-primary pto-request-approve-deny-btn" data-val="2" pto-request-id="<?php echo $wooptionmessages['pto_request_id']; ?>">Approve</button>
                                    <button type="button" class="btn btn-primary pto-request-approve-deny-btn" data-val="3" pto-request-id="<?php echo $wooptionmessages['pto_request_id']; ?>">Deny</button>
                                <?php } ?>
                                <button type="button" class="btn btn-primary wo-send-new-message" data-val="reply-msg">Reply</button>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>