<div class="modal-dialog" style="width: 40%; height:auto;">
    <div class="modal-content">
        <div class="modal-header" style="background-color: #e5e5e5;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><span class="gpTypeCls"></span>Location Filter</h4>
        </div>
        <div class="modal-body" style="max-height: 500px;">
            
            <div class="page-content">
                <div class="row">
                    <div class="row mt10">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Status</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                <?php 
                                    $locationStatus = unserialize(INVENTORY_LOCATION_STATUS);
                                    echo $this->Form->control('location_status', array('options' => $locationStatus, 'empty' => 'Enter Status ...', 'class' => 'form-control col-md-9 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'f_location_status')); 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt10">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Show Inactive</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="show_inactive" id="inlineRadio1" value="1">&nbsp;Yes</label>&nbsp;&nbsp;

                                        <input class="form-check-input" type="radio" name="show_inactive" id="inlineRadio2" value="2">&nbsp;No
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default clearapplyfilter">Clear</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary applyfilterbtn">Apply Filter</button>
            </div>
        </div>
    </div>
</div>