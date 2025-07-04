<div id="woOSRAddToCurrentPOModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add to Current P/O?</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>Add item to the current P/O(#<span class="osrcurrentponumber"><?php echo $po_no; ?></span>) for this vendor(<?php echo $vendor_name;?>)?</p>
                        <p>Yes = User Current P/O</p>
                        <p>No = Create New P/O</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary osrPOCurrentYesBtn">Yes</button>
                <button type="button" class="btn btn-default osrPOCurrentNoBtn" data-val='1'>No</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>