<section class="top-form-section">
    <div class="row">
        <div class="col-md-12">
            <p>Certification History For Tool</p>
            <div>
                <table id="inventoryToolsCertHistTbl" class="table mb-0">
                    <thead>
                        <tr>
                            <th id="certDateSent">Date Sent <i class="fa fa-fw fa-sort"></i></th>
                            <th id="certDateReceived">Date Received <i class="fa fa-fw fa-sort"></i></th>
                            <th id="certCalib">Calib.</th>
                            <th id="certAdjustNotes">Adjust Notes <i class="fa fa-fw fa-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody id="inventoryToolsCertHistList">
                        <?php
                        $certifiedhistoryhtml = $this->InventoryToolHTML->certifielHistoryTableHTML($certificationhistories);
                        echo $certifiedhistoryhtml;
                        ?>
                    </thead>
                </table>
            </div>
        </div>
        <div class="col-md-12 mt10">
            <button type="button" class="btn btn-default tool-add-history-item">Add History Item</button>
            <button type="button" class="btn btn-default tool-delete-history-item">Delete History Item</button>
        </div>
    </div>
</section>