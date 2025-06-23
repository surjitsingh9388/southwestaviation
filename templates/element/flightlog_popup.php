<!-- Flight Log -->
<div id="flightLogModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="flightLegFrm" class="form-horizontal">
                <input type="hidden" name="allcrew" class="allCrewCls">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Flight Crew</h4>
                    <span>Add or edit flight crew for this leg.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:30px;">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Crew Member</label>
                            <div class="crewListCls"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Crew Member Type</label>
                            <select name="member_type" class="form-control col-md-6 col-xs-12 landingCls">
                                <option value="pic">PIC</option>
                                <option value="sic">SIC</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input type="checkbox" name="pilot_flying"> Pilot Flying
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Duty Start Date</label>
                            <input type="text" name="duty_start_date" class="form-control datePicker dtStartD">
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Estimated Duty On</label>
                            <input type="text" name="duty_on" class="form-control timePicker" placeholder="00.00">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Estimated Duty Off</label>
                            <input type="text" name="duty_off" class="form-control timePicker" placeholder="00.00">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Required Rest</label>
                            <select name="required_rest" class="form-control col-md-6 col-xs-12 requiredRestCls">
                                <option value="8">8 Hours</option>
                                <option value="9">9 Hours</option>
                                <option value="10" selected>10 Hours</option>
                                <option value="11">11 Hours</option>
                                <option value="12">12 Hours</option>
                                <option value="16">16 Hours</option>
                                <option value="0">00 Hours (Part 91)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Which legs should this apply to</label>
                            <div class="allLegsList">
                                <input type="checkbox" name="legs_apply" checked="checked" disabled><input type="hidden" name="leg_info" class="legInfoCls"> <span class="crewLegInfo"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="crewErrorMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success crewSaveBtn" id="flightLegBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Flight Log -->

<!-- Update Flight Log -->
<div id="flightLogUpdateModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="flUpdateFrm" class="form-horizontal">
                <input type="hidden" name="allcrew" class="allCrewCls">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Flight Crew</h4>
                    <span>Add or edit flight crew for this leg.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:30px;">
                </div>
                <div class="modal-footer">
                    <span id="flUpdateErrorMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="flUpdateBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Update Flight Log -->

<!-- Dispatch Add Schedule -->
<div id="flsCrewModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="flsCrewFrm" class="form-horizontal">
                <input type="hidden" name="allcrew" class="allCrewCls">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Flight Crew</h4>
                    <span>Add or edit flight crew for this leg.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:30px;">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Crew Member</label>
                            <div id="flsCrewListId"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Crew Member Type</label>
                            <select name="member_type" class="form-control col-md-6 col-xs-12 landingCls">
                                <option value="pic">PIC</option>
                                <option value="sic">SIC</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Duty Start Date</label>
                            <input type="text" name="duty_start_date" class="form-control datePicker dtStartD">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Estimated Duty On</label>
                            <input type="text" name="duty_on" class="form-control timePicker" placeholder="00.00">
                        </div>

                        <div class="col-md-6">
                            <label>Estimated Duty Off</label>
                            <input type="text" name="duty_off" class="form-control timePicker" placeholder="00.00">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Required Rest</label>
                            <select name="required_rest" class="form-control col-md-6 col-xs-12 requiredRestCls">
                                <option value="8">8 Hours</option>
                                <option value="9">9 Hours</option>
                                <option value="10" selected>10 Hours</option>
                                <option value="11">11 Hours</option>
                                <option value="12">12 Hours</option>
                                <option value="16">16 Hours</option>
                                <option value="0">00 Hours (Part 91)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Which legs should this apply to</label>
                            <div class="allLegsList">
                                <input type="checkbox" name="legs_apply" checked="checked" disabled><input type="hidden" name="leg_info" class="legInfoCls"> <span class="flsCrewLegInfo"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="flsCrewErrorMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="flsCrewSaveBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Dispatch Add Schedule -->

<!-- Dispatch update Schedule -->
<div id="flsCrewUpdModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="flsCrewUpdFrm" class="form-horizontal">
                <input type="hidden" name="allcrew" class="allCrewCls">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Flight Crew</h4>
                    <span>Add or edit flight crew for this leg.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:30px;">  
                </div>
                <div class="modal-footer">
                    <span class="crewUpdateMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="flsCrewUpdateBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Dispatch update Schedule -->

<!-- Display Trip Files -->
<div id="displayTripFilesModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Trip Files</h4>
                <span>Choose a file to view or edit.</span>
            </div>
            <div class="modal-body" style="max-height: 580px; padding:30px;">
                <table class="table borderless">
                    <tr>
                        <th width="80%">File Name</th>
                        <th width="10%">&nbsp;</th>
                        <th width="10%">&nbsp;</th>
                    </tr>
                    <tbody class="tripFilesList">
                        <tr>
                            <td colspan="3">No Trip Files Uploaded</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-success" id="openTripFlBtn" style="float:left;"><i class="fa fa-upload"></i> Upload New Trip File</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Done</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Display Trip Files -->

<!-- Upload Trip Files -->
<div id="uploadTripFilesModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="upTripFilesFrm" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="trip_id" class="upTripidCls">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload Trip Files</h4>
                    <span>Choose a file to upload and enter a name for the file.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:30px;">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Choose a File</label>
                            <input type="file" name="file_name" class="form-control" />
                            <span>Max 50mb file upload.</span>
                        </div>
                    </div>
                    <div style="clear: both;padding:10px;"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Type a name for the file</label>
                            <input type="text" name="title" class="form-control" maxlength="100">
                            <span>100 characters max.</span>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <span class="tripFileStatusMsg"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="reset" class="btn btn-danger" style="float:left;">Delete File</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-success" id="saveTripFileBtn"><i class="fa fa-upload"></i> Save File</button>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Upload Trip Files -->

<!-- Update/Edit Trip Files -->
<div id="updateTFModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="updateTFFrm" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="id" class="updateTFId">
                <input type="hidden" name="trip_id" class="upTripidCls">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload Trip Files</h4>
                    <span>Choose a file to upload and enter a name for the file.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:30px;"> 
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <span class="tripFileStatusMsg"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="reset" class="btn btn-danger deleteTFBtn" style="float:left;">Delete File</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-success" id="updateTFBtn"><i class="fa fa-upload"></i> Save File</button>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Upload Trip Files -->

<!-- Add Template Name  -->
<div id="addTempNameModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="addTempNameFrm" class="form-horizontal">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Give your new Template a name</h4>
                    <span>Enter a name for the template. Then click Save or Schedule at the bottom of this page.</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:30px;">
                    <div class="row">
                        <label>Type a name for the template</label>
                        <input type="text" name="template_name" class="form-control" placeholder="Template Name" />
                    </div>
                </div>
                <div class="modal-footer">
                    <span id="addTempNameMsg"></span>
                    <button type="button" class="btn btn-success" id="addTempBtn">Done</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Add Template Name  -->

<!-- Display Templates List -->
<div id="showTemplModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="showTemplFrm" class="form-horizontal">
                <input type="hidden" name="trip_id" class="upTripidCls">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Trip Templates</h4>
                    <span>Choose an existing template to load</span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:30px;">
                    <table class="table borderless">
                        <tr>
                            <th width="80%">Template Name</th>
                            <th width="10%">&nbsp;</th>
                            <th width="10%">&nbsp;</th>
                        </tr>
                        <tbody class="templateList">
                            <tr>
                                <td colspan="3">No Templates Saved Yet</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <span id="addTempNameMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Display Templates List -->

<!-- Load Trip Templates Popup -->
<div id="loadTemplModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="loadTemplFrm" class="form-horizontal">
                <input type="hidden" name="new_trip" value="copy">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Load Trip Template</h4>
                    <span>Template Name: <span class="tempNameCls"></span></span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:30px 50px 30px 50px;">
                </div>
                <div class="modal-footer">
                    <span id="addTempNameMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success loadTempCls">Load Template</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Load Trip Templates Popup -->

<!-- Copy Trip Popup -->
<div id="copyTemplModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="copyTemplFrm" class="form-horizontal">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Copy Trip</h4>
                    <span>Trip Id: <span class="copyTripIdCls"></span></span>
                </div>
                <div class="modal-body" style="max-height: 580px; padding:30px 50px 30px 50px;">
                    <input type="hidden" name="new_trip" value="copy">
                    <input type="hidden" class="copyTripId" name="trip_id">
                    <div class="row">
                        <label>Pick a New Starting Date for the Trip</label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar col-md-1" aria-hidden="true"></i>
                            </span>
                            <input type="text" name="leg_date" class="form-control legDate datePicker valid" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="copyTripMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success copyTripBtn">Copy Trip</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Copy Trip Popup -->

<!-- Add manifest -->
<div id="manifestModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="manifestFrm" class="form-horizontal">
                <input type="hidden" name="tabnum" class="tabnum">
                <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Load Manifest</h4>
                    <span>Edit the manifest for this leg.</span>
                </div>
                <div class="modal-body" style="max-height: 450px; padding:30px; overflow-y: scroll;">
                </div>
                <div class="modal-footer">
                    <span class="manifestMsg"></span>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="saveManifestBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Add Menifest -->

<!-- Close Leg Error -->
<div id="closeLegModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <!-- Modal content-->
        <div class="modal-content">
            <input type="hidden" name="tabnum" class="tabnum">
            <div class="modal-header" style="background-color: #e5e5e5; text-align: center;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">We Found a Problem</h4>
                <span>Details Are Below.</span>
            </div>
            <div class="modal-body" style="max-height: 450px; text-align: center; font-size: 16px;">
            </div>
            <div class="modal-footer">
                <span class="manifestMsg"></span>
                <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End Close Leg Error -->