<!-- Report Time popup -->
<div id="reportTimeModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            <form method="post" id="reportTimeFrm" method="POST">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Component New Times - <span id="planeCode"></span></h4>
            </div>
            <div class="modal-body" style="max-height: 580px; overflow-y: auto;">
            </div>
            <div class="modal-footer">
                <span id="errorMsg"></span>
                <button type="button" class="btn btn-default" id="historyTime">Historical Times</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="button" value="Save" class="btn btn-primary" id="saveRTimeBtn">
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Projected Time popup -->
<div id="projectedTimeModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <form method="post" id="projectedTimeFrm" method="POST">
                <div class="modal-header" style="background-color: #e5e5e5;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Projected Time - <span id="projectedAirCode"></span></h4>
                </div>
                <div class="modal-body" style="min-height: 330px; max-height: 500px; overflow-y: auto;">
                </div>
                <div class="modal-footer">
                    <span id="projErrorMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" value="Generate Projected Report" class="btn btn-primary" id="saveProjTimeBtn">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Parent/Child popup -->
<div id="parentChildModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 90%;">
        <div class="modal-content"> 
        </div>
    </div>
</div>

<!-- Create Group Popup -->
<div id="groupModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <form method="post" id="groupFrm" method="POST">
                <div class="modal-header" style="background-color: #e5e5e5;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="gpTypeCls"></span> Maintenance Item Group</h4>
                </div>
                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                </div>
                <div class="modal-footer">
                    <span id="errorMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" value="Save" class="btn btn-primary" id="saveGpBtn">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Create Group Popup -->

<!-- Parts List Popup with search functionality -->
<div id="partsListModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 90%;">
        <div class="modal-content">
            <form method="post" id="partsListFrm" method="POST">
                <input type="hidden" name="childpartids" id="selPartIds" data-childpartids="">
                <div class="modal-header" style="background-color: #e5e5e5;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Maintenance Items</h4>
                </div>
                <div class="modal-body" style="max-height: 580px; overflow-y: auto;">
                </div>
                <div class="modal-footer">
                    <span id="addToPEMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" value="Add" class="btn btn-primary addToPBtn" id="addToPBtn">
                    <input type="button" value="Add and Close" class="btn btn-primary addToPBtn" id="addToPCBtn">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Parts List Popup with search functionality -->

<!-- Add Compliance popup -->
<div id="addComplianceModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <form method="post" id="addCompFrm" method="POST">
                <input type="hidden" name="pid" class="pidCls">
                <input type="hidden" name="partids" class="partsCls">
                <div class="modal-header" style="background-color: #e5e5e5;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Compliance</h4>
                </div>
                <div class="modal-body" style="max-height: 580px; overflow-y: auto;">
                </div>
                <div class="modal-footer">
                    <span class="addCompMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="button" value="Save" class="btn btn-primary" id="addCompBtn">
                </div>
            </form>
        </div>
    </div>
</div>
