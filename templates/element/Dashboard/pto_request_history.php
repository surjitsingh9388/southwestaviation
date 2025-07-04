<div id="ptoRequestHistoryModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>PTO Request History</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">Employee Name</th>
                                    <th class="text-nowrap">Date</th>
                                    <th class="text-nowrap">Previous Balance</th>
                                    <th class="text-nowrap">Hours Used/Gained</th>
                                    <th class="text-nowrap">New Balance</th>
                                    <th class="text-nowrap">PTO Request Status</th>
                                </tr>
                            </thead>
                            <tbody id="ptoRequestsList">
                                <?php
                                foreach($ptorequestslist as $ptorequest){
                                ?>
                                <tr>
                                    <td ><?php echo $ptorequest['users']['full_name']; ?></td>
                                    <td><?php echo date('m/d/Y', strtotime($ptorequest['created_at'])); ?></td>
                                    <td><?php echo $ptorequest['previous_balance']; ?></td>
                                    <td><?php echo $ptorequest['hours_used_gained']; ?></td>
                                    <td><?php echo $ptorequest['new_balance']; ?></td>
                                    <td>
                                        <?php 
                                        $requetstatus = 'Pending';
                                        if($ptorequest['pto_requests_status'] == '2'){
                                            $requetstatus = 'Approved';
                                        }else if($ptorequest['pto_requests_status'] == '3'){
                                            $requetstatus = 'Denied';
                                        }
                                        echo $requetstatus;
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
