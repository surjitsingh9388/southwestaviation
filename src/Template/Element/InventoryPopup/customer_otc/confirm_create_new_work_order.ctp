<div id="ConfirmCreateNewWOModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
                <h4 class="modal-title"><span class="gpTypeCls"></span>Create Another Open W/O</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>The following work orders are open for this registration number (<?php echo $aircraftregdetail->aircraft_registration_number; ?>):</p>
                        <p><?php echo implode(', ', $workordernolist); ?></p>
                        <p>Continue and create a new work order?</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary fetchCustOTCPopup" data-val='aircraft_create_wo_btn'>Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>