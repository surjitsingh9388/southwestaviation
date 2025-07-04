<div id="aircarftWOLogOptionBookValuesModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Aircraft Registration Number: <?php echo $aircraftregdetail->aircraft_registration_number; ?></h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <div class="row ml0">
                        Any information changes here will only be for this W/O. If the maintenance info is incorrect and needs to be updated to the general aircraft, please do so by going to the Customers > Aircraft tab > Maintenance button.
                    </div>
                    <div class="row ml0" class="update-time-btn">
                        <?php $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : ''; ?>
                        <button class="btn btn-default mt10 refresh-val-with-maintenance" type="button" <?php echo $isdisabled; ?>>Refresh Info</button>

                        <span>This information is used on the Log Book Labels and Customer Invoice.</span>
                    </div>
                        
                    <div id="aircraftTabs" class="customerinfoTab wo_option_logbook_val_tab">
                        <?php echo $this->element('Inventory/customer_otc/aircraft_wo_option_logbook_val_tab'); ?>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <script>
        var refreshWOLogBookValWithMaintURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'refreshWOLogBookValWithMaint']); ?>";
        var saveAircraftWOLogBookValEngineURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftWOLogBookValEngine']); ?>";
        var saveAircraftWOLogBookValJetEngineURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftWOLogBookValJetEngine']); ?>";
    </script>
</div>