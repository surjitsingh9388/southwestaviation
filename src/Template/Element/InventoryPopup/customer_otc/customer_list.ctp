<div id="addCustomerListsModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Customer List (All Customers)</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="customerinfolistblock">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">City</th>
                                        <th scope="col">Reg. Number</th>
                                        <th scope="col">OTC ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($customerlists)){
                                        foreach($customerlists as $customer){
                                            $tblrow   = '<tr class="customerlsttr" data-val="'.$customer['id'].'">';
                                            $tblrow .= '<td>'.$customer['customer_name'].'</td>';
                                            $tblrow .= '<td>'.$customer['cellular_phone'].'</td>';
                                            $tblrow .= '<td>'.$customer['city'].'</td>';
                                            $tblrow .= '<td>'.$customer['aircraft_reg_no'].'</td>';
                                            $tblrow .= '<td>'.$customer['customer_name'].'</td>';
                                            $tblrow .= '</tr>';

                                            echo $tblrow;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>