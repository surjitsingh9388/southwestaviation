<?php
echo $this->Form->create($userptorequests, array('class' => 'form-horizontal form-label-left', 'id' => 'frmPTORequests', 'autocomplete'=>'off'));
?>
<div class="col-md-12">
    <input type="hidden" name="pto_request_id" id="user_pto_request_id" value="<?php echo @$userptorequests->id; ?>" />
    <div class="col-md-6" style="padding:1px;">
        <div class="form-group">
            <label class="control-label" for="reference">Employee Name</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('pto_employee_name', array('class' => 'form-control', 'label'=> false, 'readonly'=>'readonly', 'value'=>$sessionUser['full_name'])); ?>
            </div>
        </div>
    </div>
    <div class="col-md-6" style="padding:1px;">
        <div class="form-group">
            <label class="control-label" for="reference">Department/Title</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('pto_department', array('class' => 'form-control', 'label'=> false, 'readonly'=>'readonly', 'value'=>$sessionUser['role'])); ?>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    I request the following days and/or times off.(use a new line for each day you will be using)
</div>
<div class="col-md-12">
    <table class="table table-bordered" id="tbluserpto">
        <thead>
            <tr>
                <th class="col-sm-2">Day of the week</th>
                <th class="col-sm-3">Date</th>
                <th class="col-sm-2">Time From</th>
                <th class="col-sm-2">Time To</th>
                <th class="col-sm-2">#PTO Hrs. to Use</th>
                <th class="col-sm-1"></th>
            </tr>
        </thead>
        <tbody id="userptoaddblock">
            <?php 
            if(!empty($ptorequestlogs)){
                foreach($ptorequestlogs as $keys=>$requestlog){
                    echo $this->element('PTORequests/create_pto_request_row', ['counter'=>$keys+1, 'requestlog'=>$requestlog]); 
                }
            }else{
                echo $this->element('PTORequests/create_pto_request_row', ['counter'=>'1']); 
            }
            ?>
        </tbody>
    </table>
</div>
<div class="col-md-12">
    <button type="button" class="btn btn-default ptoaddmorebtn">Add More</button>
</div>
<?php 
echo $this->Form->end(); 
?>
