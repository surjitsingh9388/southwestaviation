<div id="aircraftWOOptionViewMsgModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $wooptionmessages['sent_from'].': '.$wooptionmessages['message_subject']; ?></h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <?php
                    echo $this->Form->create($messageobj, array('class' => '', 'id' => ''));
                    ?>
                    <input type="hidden" id="replay_message_id" value="<?php echo $wooptionmessages['id']; ?>" />
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group clearfix">
                                <label class="control-label col-sm-3" for="reference">From: </label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?php echo $wooptionmessages['sent_from']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group clearfix">
                                <label class="control-label col-sm-3" for="reference">Subject: </label>
                                <div class="col-sm-9">
                                    <p class="form-control-static"><?php echo $wooptionmessages['message_subject']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group clearfix">
                                <label class="control-label col-sm-3" for="reference">W/O or R/O: </label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">
                                        <?php
                                        $wo_number = '';
                                        if(!empty($wooptionmessages['message_wo_ro'])){
                                            $wo_number = $wooptionmessages['msg_woro_no'];
                                        }
                                        
                                        echo $wo_number; 
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-sm-12">
                                <div class="form-group clearfix">
                                    <?php echo $this->Form->input('message', array('type' => 'textarea', 'class'=>'form-control col-md-12', 'placeholder' => '', 'label' => false, 'disabled' => 'disabled', 'value'=>$wooptionmessages['message'])); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-md-12" style="text-align:right;">
                                <!--a href="#" style="color:blue;">Is user online?</a-->
                                <?php if(!empty($userptorequests) && $userptorequests->pto_requests_status == '1'){ ?>
                                    <button type="button" class="btn btn-primary pto-request-approve-deny-btn" data-val="2" pto-request-id="<?php echo $wooptionmessages['pto_request_id']; ?>" style="margin:0px;">Approve</button>
                                    <button type="button" class="btn btn-primary pto-request-approve-deny-btn" data-val="3" pto-request-id="<?php echo $wooptionmessages['pto_request_id']; ?>" style="margin:0px;">Deny</button>
                                <?php } if($msg_source == 'received'){ ?>
                                <button type="button" class="btn btn-primary wo-send-new-message" data-val="reply-msg" style="margin:0px;">Reply</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>