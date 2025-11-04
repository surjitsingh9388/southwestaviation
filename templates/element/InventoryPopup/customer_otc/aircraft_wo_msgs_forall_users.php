<div id="aircarftWOOptoinMsgForAllUsersModel" class="modal fade page-content" role="dialog" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-xl" style="max-width: 95%; width: 50%;"> <!-- Full width modal -->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    List of Sent/Received Messages 
                    <?php 
                    if (!empty($work_order_no)) {
                        $headingmsg = ' for Work Order#' . $work_order_no;
                        if ($section_clk == 'all-user') {
                            $headingmsg .= '&nbsp;(All Users)';
                        }
                        echo $headingmsg;
                    }
                    ?>
                </h4>
            </div>

            <div class="modal-body" style="padding: 20px;padding-top: 0px;">
                <div class="page-content">
                    <div class="row">
                        <?php echo $this->Form->create(null, ['class' => 'form-horizontal form-label-left', 'id' => 'frmMarkMessageReadUnread']); ?>

                        <div style="clear: both;"></div>

                        <!-- Tabs Start -->
                        <div id="aircraftTabs" style="padding: 15px 0;">
                            <div class="d-flex justify-content-between align-items-center mb-2" style="display: flex; justify-content: space-between; align-items: center; background:#e5e5e5;">
                                <!-- Tabs -->
                                <ul class="nav nav-tabs mb-0">
                                    <li class="active"><a data-toggle="tab" href="#sentMessageInfo">Sent</a></li>
                                    <li><a data-toggle="tab" href="#receivedMessageInfo">Received</a></li>
                                </ul>

                                <input type="hidden" name="is_mark_read" id="wo_is_mark_read" value="1" />
                                <!-- Button on the right -->
                                <button type="button" class="btn btn-primary mark_msg_read_unread" style="display:none;">
                                    Mark as Read
                                </button>
                            </div>

                            <div class="tab-content" style="margin-top: 15px;">
                                <!-- Sent Tab -->
                                <div id="sentMessageInfo" class="tab-pane fade in active">
                                    <h5><strong>Sent Messages List</strong></h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">To</th>
                                                    <th scope="col">Subject</th>
                                                    <th scope="col">Sent Date</th>
                                                </tr>
                                            </thead>
                                            <tbody class="sent-message-list">
                                                <?php echo $this->InventoryAircraftWorkOrder->getWOSentMessageListHTML($sentmsglist); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Received Tab -->
                                <div id="receivedMessageInfo" class="tab-pane fade">
                                    <h5><strong>Received Messages List</strong></h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th scope="col">From</th>
                                                    <th scope="col">Subject</th>
                                                    <th scope="col">Received Date</th>
                                                    <th scope="col" style="width: 50px; text-align: center;">
                                                        <input type="checkbox" id="checkall_messages" value="1" />
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="received-message-list">
                                                <?php echo $this->InventoryAircraftWorkOrder->getWOReceivedMessageListHTML($receivedmsglist); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tabs End -->

                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <!-- Left side -->
                        <div class="col-md-6 text-left">
                            <button type="button" class="btn btn-success wo-option-refresh-message">
                                Refresh List
                            </button>
                        </div>

                        <!-- Right side -->
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-primary wo-send-new-message" data-val="new-msg">
                                New Message
                            </button>
                            <button type="button" class="btn btn-danger wo-option-delete-message">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        var markMessageReadUnreadURL = "<?php echo $this->Url->build(['controller' => 'InventoryCustomers', 'action' => 'markMessageReadUnread']); ?>";
    </script>
</div>
