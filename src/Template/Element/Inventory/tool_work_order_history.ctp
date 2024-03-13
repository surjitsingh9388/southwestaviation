<section class="top-form-section">
    <div class="row">
        <div class="col-md-12">
            <p>Work Order History </p>
            <div class="table-responsive" style="overflow-x: auto">
                <table id="inventoryToolsCertHistTbl" class="table mb-0">
                    <thead>
                        <tr>
                            <th id="woHistoryWO">Work Order <i class="fa fa-fw fa-sort"></i></th>
                            <th id="woHistoryItem">Item <i class="fa fa-fw fa-sort"></i></th>
                            <th id="woHistoryCalDate">Cal Date <i class="fa fa-fw fa-sort"></i></th>
                            <th id="woHistoryDueDate">Due Date <i class="fa fa-fw fa-sort"></i></th>
                            <th id="woHistoryDateAdded">Date Added <i class="fa fa-fw fa-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody id="inventoryToolsWOHistList">
                        <?php
                        $wohistoryhtml = $this->InventoryToolHTML->workOrderHistoryTableHTML($wohistories);
                        echo $wohistoryhtml;
                        ?>
                    </thead>
                </table>
            </div>
        </div>
        <div class="col-md-12 mt10">
            <button type="button" class="btn btn-default tool-wo-history-export-list">Export List</button>
        </div>
    </div>
</section>