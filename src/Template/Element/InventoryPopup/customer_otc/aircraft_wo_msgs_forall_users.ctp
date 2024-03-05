<div id="aircarftWOOptoinMsgForAllUsersModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">List of Received Messages 
                    <?php 
                    if(!empty($work_order_no)){
                        $headingmsg = ' for Work Order#'.$work_order_no;
                        
                        if($section_clk == 'all-user'){
                            $headingmsg .= '&nbsp;(All Users)';
                        }
                        echo $headingmsg;
                    }
                    ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <div class="row">
                        <?php
                        echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmMarkMessageReadUnread'));
                        ?>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <p>List of Messages Received</p>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="is_mark_read" id="wo_is_mark_read" value="1" />
                                <button type="button" class="btn btn-default float-right mark_msg_read_unread" style="display:none;">Mark as read</button>
                            </div>
                            
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">From</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Sent On</th>
                                        <th scope="col">
                                            <div class="dropdown">
                                                <input type="checkbox" id="checkall_messages" value="1" />
                                                <span class="dropdown-toggle" type="button" data-toggle="dropdown">
                                                    <span class="caret"></span>
                                                </span>
                                                <ul class="dropdown-menu">
                                                    <li><a class="message_action_dropdown" href="javascript:void(0);" data-val='none'>None</a></li>
                                                    <li><a class="message_action_dropdown" href="javascript:void(0);" data-val='unread'>Unread</a></li>
                                                    <li><a class="message_action_dropdown" href="javascript:void(0);" data-val='read'>Read</a></li>
                                                </ul>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="wo-option-message-list">
                                    <?php echo $this->InventoryAircraftWorkOrder->getWOMessageListHTML($receivedmsglist); ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-default wo-option-refresh-message">Refresh List</button>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default wo-send-new-message" data-val="new-msg">New Message</button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-default wo-option-delete-message">Delete</button>
                                </div>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <script>
        var markMessageReadUnreadURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'markMessageReadUnread']); ?>";
    </script>
</div>