<?php
echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOItemSignoffCategory'));

$signoff_category = !empty(@$signoffinspectioninfo['signoff_category']) ? @$signoffinspectioninfo['signoff_category'] : (!empty($signoff_category) ? $signoff_category : '');
$inspected_by = !empty(@$signoffinspectioninfo['users']['full_name']) ? @$signoffinspectioninfo['users']['full_name'] : '';
$inspected_date = !empty(@$signoffinspectioninfo['inspected_date']) ? @$signoffinspectioninfo['inspected_date'] : '';
$signoff_info = '';
if(!empty($inspected_by) && !empty($inspected_date)){
    $signoff_info = $inspected_by.' ('.date('m/d/Y', strtotime($inspected_date)).')';
}

?> 
<input type="hidden" name="wo_item_id" id="signoff_wo_item_id" value="<?php echo $wo_item_id; ?>" />
<div class="col-md-12"><h7 class="woinspinfo">Inspection Info</h7></div>
<div class="col-md-12">
    <div class="form-group">
        <label class="control-label" for="reference">Sign-off Category</label>
        <div class="form-input-frame">
            <?php 
            echo $this->Form->control('signoff_category', array('type'=>'hidden', 'class' => 'form-control', 'label'=> false, 'id'=>'wo_item_signoff_category', 'value'=>$signoff_category));
            echo $this->Form->control('signoff_category_name', array('class' => 'form-control', 'label'=> false, 'id'=>'wo_item_signoff_category_name', 'readonly'=>'readonly')); 
            ?>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label class="control-label" for="reference">Sign-off Info</label>
        <div class="form-input-frame">
            <?php echo $this->Form->control('signoff_info', array('class' => 'form-control', 'label'=> false, 'id'=>'wo_item_signoff_info', 'readonly'=>'readonly', 'value'=>$signoff_info)); ?>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label class="control-label text-left" for="reference">Enter Inspection Code</label>
        <div class="form-input-frame">
            <?php echo $this->Form->control('signoff_inspection_code', array('type'=>'password', 'class' => 'form-control', 'label'=> false, 'id'=>'wo_item_signoff_inspection_code', 'maxlength'=>'4')); ?>
        </div>
    </div>
</div>

<div class="col-md-12">
    <?php 
    $issavebtn = '0';
    if($signoff_category == '1'){ 
        $issavebtn = (!empty($userMenuItems['action_final_inspections']) && empty($signoff_info)) ? '1' : '0';
    }else if(empty($is_signoff_done) && empty($signoff_info) && !empty($signoff_category)){
        $issavebtn = '1';
    }
    
    if(!empty($issavebtn)){
    ?>
    <button type="button" class="btn btn-primary float-right saveWOItemSignoffCategory">Save</button>
    <?php } ?>
</div>
<?php echo $this->Form->end(); ?>