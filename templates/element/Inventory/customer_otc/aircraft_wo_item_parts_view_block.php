<?php
echo $this->Form->create($aircraftwoitemparts, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOItemViewParts', 'autocomplete' => 'off'));
?>
<input type="hidden" name="wo_item_part_id" id="wo_item_part_id" value="<?php echo @$aircraftwoitemparts->id; ?>" />
<input type="hidden" name="wo_item_id" id="part_wo_item_id" value="<?php echo $wo_item_id; ?>" />  

<div class="col-md-4">
    <div class="form-group">
        <label class="control-label" for="reference">Item No.</label>
        <div class="form-input-frame">
            <?php echo $this->Form->control('part_item_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'part_item_no', 'value'=>$aircraftwoitemparts['wo_items']['wo_item_position'], 'readonly'=>'readonly')); ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label class="control-label" for="reference">Part Number</label>
        <div class="form-input-frame">
            <?php echo $this->Form->control('part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'wo_view_item_part_number', 'readonly'=>'readonly')); ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <button type="button" class="btn btn-default mt-25" disabled>Expand Part Kit</button>
</div>

<div id="aircraftTabs" class="col-md-12 customerinfoTab">
    <div class="container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#partsOverviewSection">Overview</a></li>
            <li><a data-toggle="tab" href="#partsInspectionInfoSection">Inspection Info</a></li>
        </ul>
        <div class="tab-content">
            <div id="partsOverviewSection" class="tab-pane fade in active">
                <div class="page-content mt-35">
                    <div class="formBGCls">
                        <?php echo $this->element('Inventory/customer_otc/aircraft_wo_item_parts_view_overview'); ?>
                    </div>
                </div>
            </div>
            <div id="partsInspectionInfoSection" class="tab-pane fade">
                <div class="page-content mt-35">
                    <div class="formBGCls">
                        <?php echo $this->element('Inventory/customer_otc/aircraft_wo_item_parts_view_inspinfo'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 col-xs-12 col-sm-12">
    <button type="button" class="btn btn-primary float-right wo-itempart-addpart-btn" data-val="view_part" <?php if($aircraftwoitemparts['wo_items']['wo_item_status'] == '3'){?>disabled<?php } ?>>Save</button>
</div>

<?php echo $this->Form->end(); ?>