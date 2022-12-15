<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Airframe Component Part</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Aircraft:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo isset($airCompParts->plane->plane_code) ? h($airCompParts->plane->plane_code) : ''; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Aircraft Component:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo isset($airCompParts->airframe_component->log_book) ? h($airCompParts->airframe_component->log_book) : ''; ?>
                        </div>
                    </div>

                    <?php if(!empty($airCompParts->airframe_category->category_name)) { ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Aircraft Category:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo isset($airCompParts->airframe_category->category_name) ? h($airCompParts->airframe_category->category_name) : ''; ?>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php 
                        $ataType = '';
                        if(!empty($airCompParts->ata_code) || !empty($airCompParts->mfg_code)) { 
                            $ataType = $airCompParts->ata_code.' '.$airCompParts->mfg_code;
                        } elseif (!empty($airCompParts->item_type) || !empty($airCompParts->ad_sb_number) || !empty($airCompParts->amendment)) {
                            $ataType = $airCompParts->item_type.' '.$airCompParts->ad_sb_number.' '.$airCompParts->amendment;
                        }
                    ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">ATA / Type:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($ataType); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Description:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airCompParts->description); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Notes:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airCompParts->notes); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Part Number:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airCompParts->part_number); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Serial Number:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airCompParts->serial_number); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Hardware:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airCompParts->hardware); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Software:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airCompParts->software); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Maintenance Manual Ref:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airCompParts->mm_ref); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Operations Number:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airCompParts->ops_numbers); ?>
                        </div>
                    </div>

                    <?php if(!empty($airCompParts->avg_man_hrs)) { ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Avg Man Hours:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airCompParts->avg_man_hrs); ?>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if(!empty($airCompParts->approx_price)) { ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Approx Price:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airCompParts->approx_price); ?>
                        </div>
                    </div>
                    <?php } ?>

                    <?php if(!empty($airCompParts->approx_price)) { ?>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Manufacturer:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo isset($airCompParts->manufacturer) ? h($airCompParts->manufacturer) : ''; ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div> 
        </div>
    </div>
</div>

