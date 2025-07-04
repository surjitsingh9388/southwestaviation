<div class="table-container">
    <table class="header-table">
        <tr>
            <td class="pb-0 ps-0">
                <table>
                    <tr>
                        <td>
                            <?php
                            $path = WWW_ROOT . 'images' . DS . 'logo.png';
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            ?>
                            <img src="<?= $base64 ?>" alt="Logo" />
                        </td>
                        <td>
                            <table>
                                <tr>
                                    <td class="ps-0">
                                        <table>
                                            <tr>
                                                <td class="title ps-0">
                                                    Southwest Aviation Specialties, LLC - Phone: (918) 298-4044, Fax: (918) 298-698-6930 - Repair Station #: S30R818N - 8720 Jac Bates Ave, Tulsa, OK 74132
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-top-db p-tb-15 text-center">
                                        <p class="headingTitle">Service List - W/O: <?php echo $reportdata['work_order_no'].' - '.$reportdata['customers']['customer_name'].' ('.$reportdata['aircraft']['aircraft_registration_number'].')'; ?></p>
                                        <?php
                                        if($report_type == '49' || $report_type == '56'){
                                            $woItemSignOff = unserialize(WOITEMSIGNOFF);
                                        ?>
                                        <p class="headingTitle">
                                            <?php
                                            $heading = ($report_type == '49') ? 'Signoff: ' : 'Missing Signoff: '; 
                                            echo !empty($signoff_category) ? $heading.$woItemSignOff[$signoff_category] : $heading; ?></p>   
                                        <?php 
                                        }else if($report_type == '52'){ 
                                            $contractRateDepartment = unserialize(CONTRACT_RATE_DEPARTMENT);
                                        ?>
                                        <p class="headingTitle">Department: <?php echo !empty($wo_department) ? $contractRateDepartment[$wo_department] : ''; ?></p>   
                                        <?php }else if($report_type == '55'){ ?>
                                        <p class="headingTitle">Open Items Only</p> 
                                        <?php }else if($report_type == '57'){ ?>
                                            <p class="headingTitle">Items That Require RII Inspection</p> 
                                        <?php } ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <?php 
    $aircraftWOCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);
    $aircraftWOItemStatus = unserialize(AIRCRAFT_WORKORDER_ITEMSTATUS);

    if($report_type == '50'){ 
    if(!empty($reportdata['wo_item'])){
        $last_category_id = '';
        foreach($reportdata['wo_item'] as $keys=>$row){
            $signoff_done_by = '';
            if(!empty($row['signoff_details'])){
                foreach($row['signoff_details'] as $signoff){
                    if($signoff['signoff_category'] == '1'){
                        $signoff_done_by = $signoff['users']['full_name'];
                    }
                }
            }
    ?>

    <table class="item-group-table item-group-table-body">
        <?php
        if($last_category_id != $row['wo_item_overview']){
            $last_category_id = $row['wo_item_overview'];
        ?>
        <tr>
            <td class="ps-0 pe-0">
                <table class="item-group-table item-group-table-body">
                    <tr>
                        <th class="headingTitle nowrap text-start border-top-db ps-0" style="padding-left: 0px;">
                            <?php echo !empty($row['wo_item_overview']['wo_category']) ? $aircraftWOCategory[$row['wo_item_overview']['wo_category']] : ''; ?>
                        </th>
                    </tr>
                </table>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td class="ps-0 pe-0">
                <table class="item-group-table item-group-table-body">
                    <tr>
                        <th class="title nowrap text-start border-btm-db ps-0" style="padding: 0; width:5%;">Item</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; width:25%;">Discrepancy</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; width:30%;">Corrective Action</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; width:15%;">Sign-off</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; width:15%;">Created</th>
                        <th class="title nowrap text-end border-btm-db pe-0" style="padding: 0; width:10%;">Item Status</th>
                    </tr>
                    
                    <tr>
                        <td class="ps-0"><?php echo $row['wo_item_position']; ?></td>
                        <td><?php echo $row['wo_discrepancy']; ?></td>
                        <td><?php echo $row['wo_corrective_action']; ?></td>
                        <td><?php echo $signoff_done_by; ?></td>
                        <td><?php echo $row['users']['full_name']; ?></td>
                        <td class="text-end pe-0"><?php echo $aircraftWOItemStatus[$row['wo_item_status']]; ?></td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
    <?php 
    }}}else if($report_type == '53' || $report_type == '54'){ 
    if(!empty($reportdata['wo_item'])){
        
        foreach($aircraftWOItemStatus as $itemstatusid=>$itemstatusval){
    ?>
        <table class="item-group-table">
        <tr>
            <td class="ps-0 pe-0">
                <table class="item-group-table">
                    <tr>
                        <th class="headingTitle nowrap text-start border-top-db ps-0" style="padding-left: 0px; width:50%;">
                            <?php echo $itemstatusval; ?>
                        </th>
                        <th class="headingTitle nowrap text-end border-top-db ps-0" style="padding-left: 0px;width:50%;">
                            <?php
                            $itemstatuscount = !empty($reportdata['wo_item'][$itemstatusid]) ? count($reportdata['wo_item'][$itemstatusid]) : '0';
                            
                            $avgstatus = ($itemstatuscount*100)/$reportdata['totalitemval'];
                            $avgstatus = round($avgstatus, 2);

                            echo $itemstatuscount.'/'.$reportdata['totalitemval'].' ('.$avgstatus.'%)'; 
                            ?>
                        </th>
                    </tr>
                </table>
            </td>
        </tr>
        <?php if(!empty($reportdata['wo_item'][$itemstatusid]) && $report_type != '54'){ ?>
        <tr>
            <td class="ps-0 pe-0">
                <table class="item-group-table">
                    <tr>
                        <th class="title nowrap text-start border-btm-db ps-0" style="padding: 0; width:5%;">Item</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; width:35%;">Discrepancy</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; width:35%;">Corrective Action</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; width:15%;">Category</th>
                        <th class="title nowrap text-end border-btm-db pe-0" style="padding: 0; width:10%;">Created</th>
                    </tr>
                    <?php
                    foreach($reportdata['wo_item'][$itemstatusid] as $keys=>$row){
                    ?>
                    <tr>
                        <td class="border-btm ps-0"><?php echo $row['wo_item_position']; ?></td>
                        <td class="border-btm"><?php echo $row['wo_discrepancy']; ?></td>
                        <td class="border-btm"><?php echo $row['wo_corrective_action']; ?></td>
                        <td class="border-btm"><?php echo !empty($row['wo_item_overview']['wo_category']) ? $aircraftWOCategory[$row['wo_item_overview']['wo_category']] : ''; ?></td>
                        <td class="border-btm text-end pe-0"><?php echo $row['users']['full_name']; ?></td>
                    </tr>
                    <?php } ?>
                    
                </table>
            </td>
        </tr>
        <?php } ?>
    </table>   
    <?php }if($report_type != '54'){ ?>
    <table class="item-group-table">
        <tr>
            <td class="ps-0 pe-0">
                <table class="item-group-table">
                    <tr>
                        <th class="border-btm-db ps-0 pe-0" style="padding: 0; width:100%;" colspan="4">&nbsp;</th>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <?php }}}else{ ?>
    <table class="item-group-table item-group-table-body">
        <?php if($report_type == '51'){ ?>
        <tr>
            <td class="ps-0 pe-0">
                <table class="item-group-table item-group-table-body">
                    <tr>
                        <th class="headingTitle nowrap text-start border-top-db ps-0" style="padding-left: 0px;">
                            (No Grouping)
                        </th>
                    </tr>
                </table>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td class="ps-0 pe-0">
                <table class="item-group-table">
                    <tr>
                        <th class="title nowrap text-start border-btm-db ps-0" style="padding: 0; width:5%;">Item</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; width:25%;">Discrepancy</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; width:25%;">Corrective Action</th>
                        <?php if($report_type != '51'){ ?>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; width:15%;">Category</th>
                        <?php } ?>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; width:10%;">Sign-off</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; width:10%;">Created</th>
                        <th class="title nowrap text-end border-btm-db pe-0" style="padding: 0; width:10%;">Item Status</th>
                    </tr>
                    <?php
                    if(!empty($reportdata['wo_item'])){
                    foreach($reportdata['wo_item'] as $keys=>$row){
                        $signoff_done_by = '';
                        if(!empty($row['signoff_details'])){
                            foreach($row['signoff_details'] as $signoff){
                                if($signoff['signoff_category'] == '1'){
                                    $signoff_done_by = $signoff['users']['full_name'];
                                }
                            }
                        }
                    ?>
                    <tr>
                        <td class="border-btm ps-0"><?php echo $row['wo_item_position']; ?></td>
                        <td class="border-btm"><?php echo $row['wo_discrepancy']; ?></td>
                        <td class="border-btm"><?php echo $row['wo_corrective_action']; ?></td>
                        <?php if($report_type != '51'){ ?>
                        <td class="border-btm"><?php echo !empty($row['wo_item_overview']['wo_category']) ? $aircraftWOCategory[$row['wo_item_overview']['wo_category']] : ''; ?></td>
                        <?php } ?>
                        <td class="border-btm"><?php echo $signoff_done_by; ?></td>
                        <td class="border-btm"><?php echo $row['users']['full_name']; ?></td>
                        <td class="border-btm text-end pe-0"><?php echo $aircraftWOItemStatus[$row['wo_item_status']]; ?></td>
                    </tr>
                    <?php }} ?>
                </table>
            </td>
        </tr>
    </table>
    <?php } ?>
</div>