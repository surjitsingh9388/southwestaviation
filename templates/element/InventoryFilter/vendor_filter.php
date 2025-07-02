<div class="modal-dialog" style="width: 40%; height:auto;">
    <div class="modal-content">
        <div class="modal-header" style="background-color: #e5e5e5;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><span class="gpTypeCls"></span>Filter</h4>
        </div>
        <div class="modal-body" style="max-height: 500px;">

            <div class="page-content">
                <div class="row mt10">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">State</label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <?php
                                echo $this->Form->control('vendor_state', array('options' => $states, 'empty' => 'Select state ...', 'class' => 'form-control col-md-3 col-sm-3 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'vendor_state'));
                                ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12"></div>
                        </div>
                    </div>
                </div>

                <div class="row mt10">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Country</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                <?php
                                echo $this->Form->control('vendor_country', array('options' => $countries, 'empty' => 'Select country ...', 'class' => 'form-control col-md-3 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'vendor_country'));
                                ?>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12"></div>
                        </div>
                    </div>
                </div>

                <div class="row mt10">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="plane_id">Show Inactive</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="2">
                                    <label class="form-check-label" for="inlineRadio1">Yes</label>&nbsp;&nbsp;

                                    <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="1">
                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12"></div>
                        </div>
                    </div>
                </div>

                <div class="row mt10"></div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default clearapplyfilter">Clear</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary applyfilterbtn">Apply Filter</button>
            </div>
        </div>
    </div>
</div>