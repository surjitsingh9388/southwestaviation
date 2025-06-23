<div id="ptoRequestsDetailsModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">PTO Requests</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="tbluserpto">
                            <thead>
                                <tr>
                                    <th class="col-sm-3">Employee Name</th>
                                    <th class="col-sm-3">Day of the week</th>
                                    <th class="col-sm-3">Date</th>
                                    <th class="col-sm-2">Time From</th>
                                    <th class="col-sm-2">Time To</th>
                                    <th class="col-sm-1">#PTO to Use</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($ptorequestlist as $ptorequest){
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $ptorequest['users']['full_name']; ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $dayoftheweeks = unserialize(DAYOFWEEKS);
                                            echo $dayoftheweeks[$ptorequest['request_logs']['day_of_week']]; 
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo date('m/d/Y', strtotime($ptorequest['request_logs']['date_of_day'])); ?>
                                    </td>
                                    <td>
                                        <?php echo date('H:i', strtotime($ptorequest['request_logs']['time_from'])); ?>
                                    </td>
                                    <td>
                                        <?php echo date('H:i', strtotime($ptorequest['request_logs']['time_to'])); ?>
                                    </td>
                                    <td>
                                        <?php echo $ptorequest['request_logs']['pto_to_use']; ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
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