<div id="aircraftWOAllOSRListModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">List All OSR</h4>
            </div>
            <div class="modal-body" style="height: auto;">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Vendor</th>
                                    <th scope="col">Invoice</th>
                                    <th scope="col">Part Number</th>
                                </tr>
                            </thead>
                            <tbody class="wo-osr-list">
                                <?php
                                foreach($aircraftwoitemosrinfoes as $osrinfo){
                                ?>
                                <tr class="editwoosritem" data-val="<?php echo $osrinfo['osr_infoes']['id']; ?>">
                                    <td><?php echo $osrinfo['wo_items']['item_no']; ?></td>
                                    <td><?php echo $osrinfo['vendors']['vendor_name']; ?></td>
                                    <td><?php echo $osrinfo['osr_infoes']['osr_invoice_no']; ?></td>
                                    <td><?php echo $osrinfo['osr_infoes']['osr_part_number']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>