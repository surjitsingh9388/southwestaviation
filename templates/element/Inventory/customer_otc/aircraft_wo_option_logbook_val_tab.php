<div class="container">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#overfiewSection">Overview</a></li>
        <?php if($engine_type != '5'){ ?>
        <li><a data-toggle="tab" href="#engineSection">Engine</a></li>
        <?php if($engine_type != '4'){ ?>
        <li><a data-toggle="tab" href="#propSection">Prop</a></li>
        <?php }} ?>
    </ul>
    <div class="tab-content">
        <div id="overfiewSection" class="tab-pane fade in active">
            <div class="page-content mt-35">
                <div class="formBGCls">
                    <?php 
                    $propFileName = '';
                    if($engine_type == '1'){
                        $propFileName = 'aircraft_maintenance_single_overview';
                    }else if($engine_type == '2'){
                        $propFileName = 'aircraft_maintenance_single_overview';
                    }else if($engine_type == '3'){
                        $propFileName = 'aircraft_maintenance_turbine_overview';
                    }else if($engine_type == '4'){
                        $propFileName = 'aircraft_maintenance_jet_overview';
                    }else if($engine_type == '5'){
                        $propFileName = 'aircraft_maintenance_helicopter_overview';
                    }
                    
                    echo $this->element('Inventory/customer_otc/'.$propFileName); 
                    ?>
                </div>
            </div>
        </div>
        <?php if($engine_type != '5'){ ?>
        <div id="engineSection" class="tab-pane fade">
            <div class="page-content mt-35">
                <div class="formBGCls">
                    <?php 
                    $engineFileName = '';
                    if($engine_type == '1'){
                        $engineFileName = 'aircraft_maintenance_single_engine';
                    }else if($engine_type == '2'){
                        $engineFileName = 'aircraft_maintenance_twin_engine';
                    }else if($engine_type == '3'){
                        $engineFileName = 'aircraft_maintenance_turbine_engine';
                    }else if($engine_type == '4'){
                        $engineFileName = 'aircraft_maintenance_jet_engine';
                    }
                    
                    echo $this->element('Inventory/customer_otc/'.$engineFileName); 
                    ?>
                </div>
            </div>
        </div>
        <?php if($engine_type != '4'){ ?>
        <div id="propSection" class="tab-pane fade">
            <div class="page-content mt-35">
                <div class="formBGCls">
                    <?php 
                    $propFileName = '';
                    if($engine_type == '1'){
                        $propFileName = 'aircraft_maintenance_single_prop';
                    }else if($engine_type == '2'){
                        $propFileName = 'aircraft_maintenance_twin_prop';
                    }else if($engine_type == '3'){
                        $propFileName = 'aircraft_maintenance_turbine_prop';
                    }else if($engine_type == '5'){
                        $propFileName = 'aircraft_maintenance_helicopter_prop';
                    }
                    
                    echo $this->element('Inventory/customer_otc/'.$propFileName); 
                    ?>
                </div>
            </div>
        </div>
        <?php }} ?>
    </div>
</div>