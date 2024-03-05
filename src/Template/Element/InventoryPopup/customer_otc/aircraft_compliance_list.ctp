<div id="aircarftComplianceListModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">List of Compliance Items</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <div id="aircraftTabs" class="customerinfoTab">
                        <div class="container">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#inspectionListSection">Inspections</a></li>
                                <li><a data-toggle="tab" href="#airframeListSection">Airframe</a></li>
                                <li><a data-toggle="tab" href="#engineListSection">Engine</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="inspectionListSection" class="tab-pane fade in active">
                                    <div class="page-content mt-35">
                                        <div class="formBGCls">
                                            <div class="aircraft-compliance-list">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Inspection Items</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach($aircraftComplianceList['inspectionItemList'] as $inspitem){
                                                        ?>
                                                            <tr>
                                                                <td class="compliancelistdata" data-val="inspections-<?php echo $inspitem['id']; ?>"><?php echo $inspitem['inspection_name']; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="airframeListSection" class="tab-pane fade">
                                    <div class="page-content mt-35">
                                        <div class="formBGCls">
                                            <div class="aircraft-compliance-list">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Airframe Items</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach($aircraftComplianceList['airframeItemList'] as $airframeitem){
                                                        ?>
                                                            <tr>
                                                                <td class="compliancelistdata" data-val="airframe-<?php echo $airframeitem['id']; ?>"><?php echo $airframeitem['name']; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="engineListSection" class="tab-pane fade">
                                    <div class="page-content mt-35">
                                        <div class="formBGCls">
                                            <div class="aircraft-compliance-list">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Engine Items</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach($aircraftComplianceList['engineItemList'] as $engineitem){
                                                        ?>
                                                            <tr>
                                                                <td class="compliancelistdata" data-val="engine-<?php echo $engineitem['id']; ?>"><?php echo $engineitem['name']; ?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Save</button-->
            </div>
        </div>
    </div>
</div>