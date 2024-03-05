<div id="woItemPartsToPullModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 65%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Parts to Pull</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>This is a list of all parts that have already assigned by the parts department.</p>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Pull from Inventory</legend>

                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Part Number</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Item</th>
                                            <th scope="col">Discrepancy</th>
                                            <th scope="col">Location</th>
                                            <th scope="col">Qty.</th>
                                            <th scope="col">Serial Number</th>
                                            <th scope="col">Lot</th>
                                            <th scope="col">Processed</th>
                                        </tr>
                                    </thead>
                                    <tbody id="wo-parts-requisitions-list">
                                        <?php
                                        $tblrow = '';
                                        foreach($aircraftwoitemparts as $row){
                                            $tblrow .= '<tr class="wopartsrequisiterow" data-val="'.$row['id'].'">';
                                            $tblrow .= '<td>'.$row['part_number'].'</td>';
                                            $tblrow .= '<td>'.$row['part_description'].'</td>';
                                            $tblrow .= '<td>'.$row['wo_items']['wo_item_position'].'</td>';
                                            $tblrow .= '<td>'.$row['wo_items']['wo_discrepancy'].'</td>';
                                            $tblrow .= '<td>'.$row['general_location'].'</td>';
                                            $tblrow .= '<td>'.$row['qty_cust_owned'].'</td>';
                                            $tblrow .= '<td>'.$row['serial_number'].'</td>';
                                            $tblrow .= '<td>'.$row['lot'].'</td>';
                                            $tblrow .= '<td><input type="checkbox" name="" value="1" /></td>';
                                            $tblrow .= '</tr>';
                                        }
                                        echo $tblrow;
                                        ?>
                                    </tbody>
                                </table>

                                <button type="button" class="btn btn-default">Mark All Processed</button>
                            </div>
                        </fieldset>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Pull from Purchase Orders</legend>

                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Part Number</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Item</th>
                                            <th scope="col">Discrepancy</th>
                                            <th scope="col">P/O</th>
                                            <th scope="col">Qty.</th>
                                            <th scope="col">Serial Number</th>
                                            <th scope="col">Lot</th>
                                            <th scope="col">Processed</th>
                                        </tr>
                                    </thead>
                                    <tbody id="wo-parts-requisitions-list">
                                        <?php
                                        $tblrow = '';
                                        /*foreach($workorderpartslist as $row){
                                            $tblrow .= '<tr class="wopartsrequisiterow" data-val="'.$row['id'].'">';
                                            $tblrow .= '<td>'.$row['part_number'].'</td>';
                                            $tblrow .= '<td>'.$row['part_description'].'</td>';
                                            $tblrow .= '<td>'.$row['wo_items']['wo_item_position'].'</td>';
                                            $tblrow .= '<td>'.$row['wo_items']['wo_discrepancy'].'</td>';
                                            $tblrow .= '<td>'.$row['purchase_order'].'</td>';
                                            $tblrow .= '<td>'.$row['qty_cust_owned'].'</td>';
                                            $tblrow .= '<td>'.$row['serial_number'].'</td>';
                                            $tblrow .= '<td>'.$row['lot'].'</td>';
                                            $tblrow .= '<td><input type="checkbox" name="" value="1" /></td>';
                                            $tblrow .= '</tr>';
                                        }*/
                                        echo $tblrow;
                                        ?>
                                    </tbody>
                                </table>

                                <button type="button" class="btn btn-default">Mark All Processed</button>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default float-left">Print Pull Ticket</button>
                        <button type="button" class="btn btn-default float-right">Save & Close</button>
                        <button type="button" class="btn btn-default float-right" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>