<!-- Utilization popup to update values -->
<div id="utilResModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <form method="post" id="utilTimeFrm" method="POST">
                <div class="modal-header" style="background-color: #e5e5e5;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Utilization - <span class="utilCompName"></span></h4>
                </div>
                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                </div>
                <div class="modal-footer">
                    <span id="errorMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    
                    <input type="button" value="Save" class="btn btn-primary" id="saveUtilBtn">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Utilization popup to update values -->