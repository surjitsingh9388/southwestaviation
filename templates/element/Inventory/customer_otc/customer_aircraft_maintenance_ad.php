<section class="top-form-section">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">List of ADs</legend>
                <div class="ads-table-scroll">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">AD Number</th>
                                <th scope="col">AD Name</th>
                                <th scope="col">Revision Date</th>
                                <th scope="col">Notes</th>
                            </tr>
                        </thead>
                        <tbody id="tblmaintadslist">
                            <?php
                            foreach($aircraftmaintadslist as $adsdata){
                            ?>
                            <tr class="airmaintadsrow" data-val="<?php echo $adsdata->id; ?>">
                                <td><?php echo $adsdata->ad_no; ?></td>
                                <td><?php echo $adsdata->ad_name; ?></td>
                                <td><?php echo $adsdata->ad_revision_date; ?></td>
                                <td><?php echo $adsdata->ad_notes; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </fieldset>
        </div>
        
        <div class="col-md-12 col-sm-12 col-xs-12" id="maintenance_ad_block">
            <?php 
            $propFileName = '';
            if($engine_type == '1'){
                $propFileName = 'aircraft_maintenance_single_ad';
            }else if($engine_type == '2'){
                $propFileName = 'aircraft_maintenance_single_ad';
            }else if($engine_type == '3' || $engine_type == '4'){
                $propFileName = 'aircraft_maintenance_turbine_ad';
            }else if($engine_type == '5'){
                $propFileName = 'aircraft_maintenance_turbine_ad';
            }
            
            echo $this->element('Inventory/customer_otc/'.$propFileName); 
            ?>
        </div>
        
        <div class="col-md-12">
            <div class="col-md-1">
                <button type="button" class="btn btn-default addNewMaintAdsBtn">New</button>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-default removeMaintAdsBtn">Delete</button>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-primary saveAircraftMaintAdsInfo">Save</button>
            </div>

            <div class="col-md-5">
                <div class="form-group"> 
                    <div class="col-md-12">
                        <?php
                        $listAllAdsArr = ['1'=>'List All Recurring ADs', '2'=>'List Recurring - Date', '3'=>'List Recurring - Time'];
                        echo $this->Form->control('manufacturer_id', array('options' => $listAllAdsArr, 'empty' => 'List All ADs', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'manufacturer'));
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-default">Preview</button>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-default">Print</button>
            </div>
        </div>
        
    </div>
</section>
<script>
    var saveAircraftMaintAdsInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftMaintAdsInfo']); ?>";
    var fetchAircraftMaintAdsInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'fetchAircraftMaintAdsInfo']); ?>";
    var removeAircraftMaintAdsDetailURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'removeAircraftMaintAdsInfoDet']); ?>";
</script>