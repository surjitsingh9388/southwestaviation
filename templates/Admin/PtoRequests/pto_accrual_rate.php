<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading">PTO Accrual Rate</h2>
            <div class="float-right mb-5">
                <button type="button" class="btn btn-default add_pto_accrual_rate_btn">Add PTO Accrual Rate</button>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="table-responsive">
                <table class="table mb-0" id="user_departments_table">
                    <thead>
                        <tr>
                            <th>PTO Accrual Rate</th>
                            <th>PTO Accrual Value</th>
                            <th>Added By</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="pto_accrual_rate_list">
                        <?php
                        foreach($ptoaccrualratelist as $ptoaccrualrate){
                        ?>
                        <tr>
                            <td><?php echo $ptoaccrualrate['pto_accrual_rate']; ?></td>
                            <td><?php echo $ptoaccrualrate['pto_accrual_rate_value']; ?></td>
                            <td><?php echo $ptoaccrualrate['users']['full_name']; ?></td>
                            <td><?php echo date('m/d/Y', strtotime($ptoaccrualrate['created_at'])); ?></td>
                            <td>
                                <button type="button" class="btn btn-default pto_accrual_rate_edit" data-val="<?=$ptoaccrualrate['id']?>" title="Edit">Edit</button>
                                <button type="button" class="btn btn-default pto_accrual_rate_delete" data-val="<?=$ptoaccrualrate['id']?>" title="Delete">Delete</button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
echo $this->element('PTORequests/create_pto_accrual_rate');
echo $this->Html->script('user_pto_requests'); 
?>
<script type="text/javascript">
    var savePTOAccrualRatesURL = "<?php echo $this->Url->build(['controller'=>'PtoRequests', 'action'=>'savePTOAccrualRates']); ?>";
    var deletePTOAccrualRatesURL = "<?php echo $this->Url->build(['controller'=>'PtoRequests', 'action'=>'deletePTOAccrualRates',]); ?>";
</script>