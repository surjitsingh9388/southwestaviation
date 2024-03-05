<div id="woOSRVendorModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Master Vendor: <span id="osrvendorheading"><?php echo @$aircraftwoosrvendors->vendor_name; ?></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group">
                            <!--button type="button" class="btn btn-default"><<</button>
                            <button type="button" class="btn btn-default"><</button>
                            <button type="button" class="btn btn-default">></button>
                            <button type="button" class="btn btn-default">>></button-->
                            <button type="button" class="btn btn-default fetchCustOTCPopup" data-val="aircraft_create_new_wo_osr_vendor">New</button>
                            <button type="button" class="btn btn-default fetchCustOTCPopup" data-val="aircraft_wo_osr_vendor_list">List View</button>
                            <button type="button" class="btn btn-default deleteWOOSRVendor">Delete Record</button>
                            <button type="button" class="btn btn-default woOSRVendorMediaPopup">Media</button>
                            <button type="button" class="btn btn-default">Preview</button>
                            <button type="button" class="btn btn-default">Print</button>
                        </div>
                    </div>
                </div>
                <div id="newwoosrhtmlblock">
                    <?php echo $this->element('Inventory/customer_otc/aircraft_wo_osr_create_vendor'); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary saveWOOSRVendorInfoBtn">Save</button>
            </div>
        </div>
    </div>
</div>