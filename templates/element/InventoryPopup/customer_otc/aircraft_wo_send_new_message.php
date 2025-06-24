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
                        <!-- To Field -->
                        <div class="form-group row mb-15 ">
                            <label class="col-md-2 col-sm-12 col-xs-12 col-form-label" for="message_to">To</label>
                            <div class="col-md-10 col-sm-12 col-xs-12">
                                <?php
                                echo $this->Form->control('message_to', [
                                    'options' => $userlist,
                                    'empty' => 'Select',
                                    'class' => 'form-control selectpicker',
                                    'data-show-subtext' => true,
                                    'data-live-search' => true,
                                    'label' => false
                                ]);
                                ?>
                            </div>
                        </div>

                        <!-- Subject Field -->
                        <div class="form-group row mb-15 ">
                            <label class="col-md-2  col-sm-12 col-xs-12 col-form-label" for="message_subject">Subject</label>
                            <div class="col-md-10 col-sm-12 col-xs-12">
                                <?php
                                echo $this->Form->control('message_subject', [
                                    'class' => 'form-control',
                                    'label' => false,
                                    'autocomplete' => 'off',
                                    'placeholder' => ''
                                ]);
                                ?>
                            </div>
                        </div>

                        <!-- W/O or R/O Field -->
                        <div class="form-group row mb-15  ">
                            <label class="col-md-2  col-sm-12 col-xs-12 col-form-label " for="message_wo_ro">W/O or R/O</label>
                            <div class="col-md-9  col-sm-12 col-xs-12 mb-15">
                                <?php
                                echo $this->Form->control('message_wo_ro', [
                                    'options' => $worodata,
                                    'empty' => 'Select',
                                    'class' => 'form-control selectpicker',
                                    'data-show-subtext' => true,
                                    'data-live-search' => true,
                                    'label' => false
                                ]);
                                ?>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12 d-flex align-items-center">
                                <span>(Optional)</span>
                            </div>
                        </div>

                        <!-- Message Body -->
                        <div class="form-group row  mb-15  col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <?php
                                echo $this->Form->control('message', [
                                    'type' => 'textarea',
                                    'class' => 'form-control',
                                    'placeholder' => '',
                                    'label' => false,
                                    'required' => 'required'
                                ]);
                                ?>
                            </div>
                        </div>

                        <!-- Send Button -->
                        <div class="form-group row col-md-12">
                            <div class="col-md-9"></div>
                            <div class="col-md-3 text-right">
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