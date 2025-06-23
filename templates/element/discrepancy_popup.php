<div id="discrepancyModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 45%;">
        <div class="modal-content">
            <form method="post" id="discrepancyFrm" class="form-horizontal">
                <input type="hidden" name="plane_id" class="discPlaneId">
                <input type="hidden" name="disp_id" class="dispId">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close closeCls" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Aircraft Discrepancy</h4>
                    <p>Add or edit an Aircraft Discrepancy.</p>
                </div>
                
                <div class="modal-body" style="max-height: 550px; overflow-y: auto;">
                </div>

                <div class="modal-footer">
                    <span class="discrepMsg"></span>
                    <input type="hidden" name="signature_data" class="signature_data">
                    <button type="button" class="btn btn-default closeCls" data-dismiss="modal">Close</button>
                    <input type="button" value="Save Changes" class="btn btn-primary" id="saveDiscrepBtn">
                </div>
            </form>
        </div>
    </div>
</div>