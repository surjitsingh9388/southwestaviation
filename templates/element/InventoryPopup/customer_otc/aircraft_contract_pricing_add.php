<div id="aircraftContractPricingModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Setup Contract Rate(Reg. Number: <?php echo $aircraftregdetail->aircraft_registration_number; ?>)</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="contract-rate-tbl-scroll">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Department</th>
                                        <th scope="col">Rate</th>
                                    </tr>
                                </thead>
                                <tbody id="contractratetbl">
                                    <?php
                                    foreach($aircraftContractRates as $contractrate){
                                    ?>
                                    <tr data-val="<?php echo $contractrate['id'].'-'.$contractrate['aircraft_id']; ?>">
                                        <td><?php echo $contractrate['department']; ?></td>
                                        <td><?php echo $contractrate['rate_an_hour']; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mt10">
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-default contpricedepartmentaddbtn fetchCustOTCPopup" data-val='contract_price_dept_add_btn'>Add Department</button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-default contractpriceratedelete">Delete Rate</button>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>