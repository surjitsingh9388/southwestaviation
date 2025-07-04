<section class="top-form-section">
    <div class="row">
        <div class="col-md-12">
            <fieldset class="scheduler-border">
                <legend class="scheduler-border">List of Appliances</legend>
                <div class="appliance-tbl-scroll">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Appliance</th>
                                <th scope="col">Manufacturer</th>
                                <th scope="col">Model</th>
                                <th scope="col">Part No</th>
                                <th scope="col">Serial No</th>
                            </tr>
                        </thead>
                        <tbody id="tblmaintappliancelist">
                            <?php
                            foreach($aircraftmaintapplianceslist as $appliancedata){
                            ?>
                            <tr class="airmaintapplrow" data-val="<?php echo $appliancedata->id; ?>">
                                <td><?php echo $appliancedata->a_appliance; ?></td>
                                <td><?php echo $appliancedata->a_manufacturer; ?></td>
                                <td><?php echo $appliancedata->a_model_no; ?></td>
                                <td><?php echo $appliancedata->a_part_no; ?></td>
                                <td><?php echo $appliancedata->a_serial_no; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </fieldset>
        </div>
        
        <div class="col-md-12" id="maintenance_appliances_block">
            <?php 
            $propFileName = '';
            if($engine_type == '1'){
                $propFileName = 'aircraft_maintenance_single_appliances';
            }else if($engine_type == '2'){
                $propFileName = 'aircraft_maintenance_single_appliances';
            }else if($engine_type == '3' || $engine_type == '4'){
                $propFileName = 'aircraft_maintenance_turbine_appliances';
            }else if($engine_type == '5'){
                $propFileName = 'aircraft_maintenance_turbine_appliances';
            }
            
            echo $this->element('Inventory/customer_otc/'.$propFileName); 
            ?>
        </div>

        <div class="col-md-12">
            <div class="col-md-1">
                <button type="button" class="btn btn-default addNewMaintApplianceBtn">New</button>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-default removeMaintApplianceBtn">Delete</button>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-primary saveAircraftMaintApplInfo">Save</button>
            </div>
            <div class="col-md-5">
                <div class="form-group"> 
                    <div class="col-md-12">
                        <?php
                        $listAllAppianceArr = ['1'=>'Date Report', '2'=>'Time Report'];
                        echo $this->Form->control('manufacturer_id', array('options' => $listAllAppianceArr, 'empty' => 'List All Appliances', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'manufacturer'));
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
    var saveAircraftMaintApplianceInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveAircraftMaintApplianceInfo']); ?>";
    var fetchAircraftMaintApplianceInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'fetchAircraftMaintApplianceInfo']); ?>";
    var removeAircraftMaintApplianceDetailURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'removeAircraftMaintApplianceInfoDet']); ?>";
</script>