<?php
echo $this->Form->create($userptorequests, array('class' => '', 'id' => 'frmPTORequests', 'autocomplete'=>'off'));
?>
<div class="row" style="margin:0px;display: flex;
    align-items: flex-end;
    flex-wrap: wrap;">
    <input type="hidden" name="pto_request_id" id="user_pto_request_id" value="<?php echo @$userptorequests->id; ?>" />
    <div class="col-sm-5 col-xs-12">
        <div class="form-group">
            <label class="control-label" for="reference">Employee Name</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('pto_employee_name', array('class' => 'form-control', 'label'=> false, 'readonly'=>'readonly', 'value'=>$sessionUser['full_name'])); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-5 col-xs-12">
        <div class="form-group">
            <label class="control-label" for="reference">Department/Title</label>
            <div class="form-input-frame">
                <?php echo $this->Form->control('pto_department', array('class' => 'form-control', 'label'=> false, 'readonly'=>'readonly', 'value'=>$sessionUser['role'])); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-2 form-group">
        <button type="button" class="btn btn-default ptoaddmorebtn" style="margin:0px;">Add More</button>
    </div>
</div>
<div class="col-md-12 col-xs-12">
    I request the following days and/or times off.(use a new line for each day you will be using)
</div>
<div class="col-md-12 col-xs-12">
    <div  class="table-responsive" style="min-height:500px"> 
        <table class="table table-striped table-bordered" id="tbluserpto" width="100%">
            <thead>
                <tr>
                    <th class="text-nowrap">Day of the week</th>
                    <th class="text-nowrap" style="min-width: 150px;">Date</th>
                    <th class="text-nowrap">Time From</th>
                    <th class="text-nowrap" style="min-width: 100px;">Time To</th>
                    <th class="text-nowrap">#PTO Hrs. to Use</th>
                    <th class="text-nowrap"></th>
                </tr>
            </thead>
            <tbody id="userptoaddblock">
                <?php 
                if (!empty($ptorequestlogs)) {
                    foreach ($ptorequestlogs as $keys => $requestlog) {
                        echo $this->element('PTORequests/create_pto_request_row', [
                            'counter' => $keys + 1,
                            'requestlog' => $requestlog
                        ]); 
                    }
                } else {
                    echo $this->element('PTORequests/create_pto_request_row', ['counter' => '1']); 
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<?php 
echo $this->Form->end(); 
?>
