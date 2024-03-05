<div id="aircraftWOOSRVendorListModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Vendor List</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12">Double click on the list to select the proper item.</div>
                <div class="col-md-12">
                    <div class="osrvendorinfolistblock">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Vendor</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Class</th>
                                    <th scope="col">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $vendorClassArr = unserialize(WO_OSR_VENDOR_CLASS);
                                foreach($woosrvendorlist as $vendor){
                                    $tblrow   = '<tr class="woosrvendorlsttr" data-val="'.$vendor['id'].'">';
                                    $tblrow .= '<td>'.$vendor['vendor_name'].'</td>';
                                    $tblrow .= '<td>'.$vendor['vendor_contact'].'</td>';
                                    $tblrow .= '<td>'.$vendor['vendor_phone'].'</td>';
                                    $tblrow .= '<td>'.(!empty($vendor['vendor_class']) ? $vendorClassArr[$vendor['vendor_class']] : '').'</td>';
                                    $tblrow .= '<td>'.$vendor['vendor_notes'].'</td>';
                                    $tblrow .= '</tr>';

                                    echo $tblrow;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>