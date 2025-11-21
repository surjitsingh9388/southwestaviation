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
                                        <p class="headingTitle">
                                            <?php 
                                            $aircraftWOItemStatus = unserialize(AIRCRAFT_WORKORDER_ITEMSTATUS);
                                            
                                            $heading = 'Time Report(All) - ';
                                            if($report_type == '62'){
                                                $heading = 'Technician Cost Report - ';
                                            }else if($report_type == '64'){
                                                $heading = 'Time Report (By Item, All Tech Logs) - ';
                                            }else if($report_type == '66'){
                                                $heading = 'Time Report (By Item, All Tech Logs, With Notes) - ';
                                            }else if($report_type == '67'){
                                                $heading = 'Time Report (Item #'.$reportdata['wo_item'][0]['wo_item_position'].', All Tech Logs) - ';
                                            }else if($report_type == '68'){
                                                $heading = 'Time Overrun Report - ';
                                            }else if($report_type == '69'){
                                                $heading = 'Technician Time Report - ';
                                            }else if($report_type == '70'){
                                                $heading = 'Technician Day/Time Report - ';
                                            }
                                            $heading .= 'W/O: '.$reportdata['work_order_no'].' - '.$reportdata['customers']['customer_name'].' ('.$reportdata['aircraft']['aircraft_registration_number'].')';
                                            echo $heading; 
                                            ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <?php if($report_type == '63'){
    if(!empty($reportdata['wo_item'])){
        foreach($reportdata['wo_item'] as $keys=>$row){
    ?>

    <table class="item-group-table">
        <tr>
            <td class="ps-0 pe-0">
                <table class="item-group-table">
                    <tr>
                        <td class="nowrap text-start border-top-db ps-0" style="padding-left: 0px; width:50%;">
                            <?php echo '<strong>Item # </strong>'.$row['wo_item_position']; ?>
                        </td>
                        <td class="nowrap text-end border-top-db ps-0" style="padding-left: 0px;width:50%;">
                            <?php
                            echo '<strong>Item Status: </strong>'.$aircraftWOItemStatus[$row['wo_item_status']]; 
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="ps-0 pe-0 border-btm">
                <?php echo $row['wo_discrepancy']; ?>
            </td>
        </tr>
        <tr>
            <td class="ps-0 pe-0">
                <?php echo $row['wo_corrective_action']; ?>
            </td>
        </tr>
        
        <tr>
            <td>
                <table class="item-group-table" style="margin-left:5%;">
                    <?php
                    if(!empty($row['signoff_details'])){
                        foreach($row['signoff_details'] as $signoff){
                    ?>
                    <tr>
                        <td class="border-top border-btm"><strong><?php echo $woItemSignOff[$signoff['signoff_category']]; ?></strong></td>
                        <td class="border-top border-btm"><?php echo $signoff['users']['full_name']; ?></td>
                        <td class="border-top border-btm text-end"><?php echo date('m/d/Y', strtotime($signoff['inspected_date'])); ?></td>
                    </tr>
                    <?php }} ?>                    
                </table>
            </td>
        </tr>
    </table> 
    <?php 
    }}
    }else{ 
        $total_hrs_worked = '0.00';
        $total_overtime_hrs = '0.00';
        $total_estimated_hrs = '0.00';
    ?>
    <table class="item-group-table item-group-table-body">
        <tr>
            <td class="ps-0 pe-0">
                <?php if($report_type == '62'){ ?>
                    <table class="item-group-table">
                        <tr>
                            <th class="title nowrap text-start border-btm-db ps-0" style="padding: 0;" width="30%">Technician</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="10%">Hrs</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="10%">Cost/Hr</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="10%">Cost</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="10%">OT Hrs</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="10%">OT Cost/Hr</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="10%">OT Cost</th>
                            <th class="title nowrap text-end border-btm-db pe-0" style="padding: 0;" width="10%">Total Cost</th>
                        </tr>
                        <?php
                        if(!empty($reportdata['services_details'])){
                        foreach($reportdata['services_details'] as $keys=>$row){
                            $hrs_worked = !empty($row['total_hrs_worked']) ? $row['total_hrs_worked'] : '0.00';
                            $overtime_hrs = !empty($row['total_overtime_hrs']) ? $row['total_overtime_hrs'] : '0.00';
                            
                            $total_hrs_worked += $hrs_worked;
                            $total_overtime_hrs += $overtime_hrs;
                            
                        ?>
                        <tr>
                            <td class="border-btm ps-0"><?php echo !empty($row['full_name']) ? $row['full_name'] : ''; ?></td>
                            <td class="border-btm"><?php echo number_format((float)$hrs_worked, 2); ?></td>
                            <td class="border-btm">0.00</td>
                            <td class="border-btm">0.00</td>
                            <td class="border-btm"><?php echo number_format((float)$overtime_hrs, 2); ?></td>
                            <td class="border-btm">0.00</td>
                            <td class="border-btm">0.00</td>
                            <td class="border-btm text-end pe-0">0.00</td>
                        </tr>
                        <?php }} ?>
                    </table>
                <?php 
                }else if($report_type == '64' || $report_type == '66' || $report_type == '67'){ 
                        
                    if(!empty($reportdata['wo_item'])){
                    foreach($reportdata['wo_item'] as $keys=>$row){
                        $total_hrs_for_items = !empty($row['services_details']['total_hrs_for_items']) ? $row['services_details']['total_hrs_for_items'] : '0.00';
                        $total_hrs_for_items = !empty($total_hrs_for_items) ? number_format((float)$total_hrs_for_items, 2) : '0.00';
                        $estimated_hrs = $row['wo_item_overview']['estimated_hour'];
                        $estimated_hrs = !empty($estimated_hrs) ? number_format((float)$estimated_hrs, 2) : '0.00';
                    ?>
                    <table class="item-group-table">
                        <tr>
                            <th class="title nowrap text-start border-top-db ps-0" style="padding: 0;" width="10%">Item No.</th>
                            <th class="title nowrap text-start border-top-db" style="padding: 0;" width="35%">Discrepancy</th>
                            <th class="title nowrap text-start border-top-db" style="padding: 0;" width="35%">Corrective Action</th>
                            <th class="title nowrap text-start border-top-db" style="padding: 0;" width="10%">Est. Hours</th>
                            <th class="title nowrap text-end border-top-db pe-0" style="padding: 0;" width="10%">Total Hours</th>
                        </tr>
                        
                        <tr>
                            <td class="border-top ps-0"><?php echo $row['wo_item_position']; ?></td>
                            <td class="border-top"><?php echo $row['wo_discrepancy']; ?></td>
                            <td class="border-top"><?php echo $row['wo_corrective_action']; ?></td>
                            <td class="border-top"><?php echo number_format((float)$estimated_hrs, 2); ?></td>
                            <td class="border-top text-end pe-0"><?php echo number_format((float)$total_hrs_for_items, 2); ?></td>
                        </tr>
                        <?php if($report_type == '66' && !empty($row['item_notes'])){ ?>
                        <tr>
                            <td class="ps-0 pe-0" colspan="5">
                                <table class="item-group-table" style="margin-left:3%;">
                                    <tr>
                                        <td><b>Notes </b><?php echo $row['item_notes']; ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php 
                        }
                        if(!empty($row['services_logs_details'])){     
                        ?>
                        <tr>
                            <td class="ps-0 pe-0" colspan="5">
                                <table class="item-group-table" style="margin-left:10%;">
                                    <tr>
                                        <th class="border-top nowrap text-start" width="30%">Technician</th>
                                        <th class="border-top nowrap text-start" width="20%">Log In</th>
                                        <th class="border-top nowrap text-start" width="20%">Log Out</th>
                                        <th class="border-top nowrap text-start" width="10%">Is OT</th>
                                        <th class="border-top nowrap text-start" width="10%">Is Override</th>
                                        <th class="border-top nowrap text-end pe-0" width="10%">Hours Worked</th>
                                    </tr>
                                    <?php
                                    foreach($row['services_logs_details'] as $servicelogs){
                                        $login_time = !empty($servicelogs['services_logs']['login_time']) ? date('H:i', strtotime($servicelogs['services_logs']['login_time'])) : '';
                                        $logout_time = !empty($servicelogs['services_logs']['logout_time']) ? date('H:i', strtotime($servicelogs['services_logs']['logout_time'])) : '';

                                        $currently_on_overtime = 'No';
                                        $is_override = 'No';
                                        if(!empty($servicelogs['services_logs']['currently_on_overtime'])){
                                            $currently_on_overtime = 'Yes';
                                        }else if(!empty($login_time) && !empty($logout_time) && $login_time == $logout_time){
                                            $is_override = 'Yes';
                                        }
                                    ?>
                                    <tr>
                                        <td class="border-top ps-0"><?php echo $servicelogs['users']['full_name']; ?></td>
                                        <td class="border-top"><?php echo $login_time; ?></td>
                                        <td class="border-top"><?php echo $logout_time; ?></td>
                                        <td class="border-top"><?php echo $currently_on_overtime; ?></td>
                                        <td class="border-top"><?php echo $is_override; ?></td>
                                        <td class="border-top text-end pe-0"><?php echo $servicelogs['services_logs']['hours_worked']; ?></td>
                                    </tr>
                                    <?php } ?>
                                </table>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                <?php }}}else if($report_type == '68'){ ?>
                    <table class="item-group-table">                        
                        <tr>
                            <th class="title nowrap text-start border-btm-db ps-0" style="padding: 0; font-size:20px;" width="10%">Item No.</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="35%">Discrepancy</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="10%">Est. Hours</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="10%">Hours Worked</th>
                            <th class="title nowrap text-end border-btm-db pe-0" style="padding: 0;" width="10%">Hours Over Estimate</th>
                        </tr>
                        <?php
                        $total_hours_over_estimates = 0;
                        if(!empty($reportdata['wo_item'])){
                        foreach($reportdata['wo_item'] as $keys=>$row){
                            $total_estimated_hrs = !empty($row['services_details']['total_estimated_hrs']) ? $row['services_details']['total_estimated_hrs'] : '0.00';
                            $total_hrs_worked = !empty($row['services_details']['total_hrs_worked']) ? $row['services_details']['total_hrs_worked'] : '0.00';

                            $hours_over_estimate = $total_hrs_worked - $total_estimated_hrs;
                            if($hours_over_estimate > 0){
                                $total_hours_over_estimates += $hours_over_estimate;
                        ?>
                        <tr>
                            <td class="border-btm ps-0"><?php echo $row['wo_item_position']; ?></td>
                            <td class="border-btm"><?php echo $row['wo_discrepancy']; ?></td>
                            <td class="border-btm"><?php echo number_format((float)$total_estimated_hrs, 2); ?></td>
                            <td class="border-btm"><?php echo number_format((float)$total_hrs_worked, 2); ?></td>
                            <td class="border-btm text-end pe-0"><?php echo number_format((float)$hours_over_estimate, 2); ?></td>
                        </tr>
                        <?php }}} ?>
                    </table>
                <?php }else if($report_type == '69'){ ?>
                    <table class="item-group-table">                        
                        <tr>
                            <th class="title nowrap text-start border-top-db ps-0" style="padding: 0;" width="10%">Item No.</th>
                            <th class="title nowrap text-start border-top-db" style="padding: 0;" width="30%">Discrepancy</th>
                            <th class="title nowrap text-start border-top-db" style="padding: 0;" width="30%">Corrective Action</th>
                            <th class="title nowrap text-start border-top-db" style="padding: 0;" width="10%">Inspector</th>
                            <th class="title nowrap text-start border-top-db" style="padding: 0;" width="10%">Est. Hours</th>
                            <th class="title nowrap text-end border-top-db pe-0" style="padding: 0;" width="10%">Total Hours</th>
                        </tr>
                        <?php
                        
                        if(!empty($reportdata['wo_item'])){
                        foreach($reportdata['wo_item'] as $keys=>$row){
                            //if(!empty($row['services_details'])){
                                $inspected_by = !empty($row['signoff_details']) ? $row['signoff_details']['users']['full_name'] : '';
                        ?>
                        <tr>
                            <td class="border-top ps-0"><?php echo $row['wo_item_position']; ?></td>
                            <td class="border-top"><?php echo $row['wo_discrepancy']; ?></td>
                            <td class="border-top"><?php echo $row['wo_corrective_action']; ?></td>
                            <td class="border-top"><?php echo $inspected_by; ?></td>
                            <td class="border-top"><?php echo !empty($row['services_details'][0]['estimated_hrs_for_item']) ? number_format((float)$row['services_details'][0]['estimated_hrs_for_item'], 2) : ''; ?></td>
                            <td class="border-top text-end pe-0"><?php echo !empty($row['services_details'][0]['total_hrs_for_item']) ? number_format((float)$row['services_details'][0]['total_hrs_for_item'], 2) : '0.00'; ?></td>
                        </tr>
                        <?php if(!empty($row['services_details'])){ ?>
                        <tr>
                            <td class="ps-0 pe-0" colspan="6">
                                <table class="item-group-table" style="margin-left:10%;">
                                    <tr>
                                        <th class="border-top nowrap text-start" width="30%">Technician</th>
                                        <th class="border-top nowrap text-start" width="20%">Clocked Hours</th>
                                        <th class="border-top nowrap text-start" width="20%">Override Hours</th>
                                        <th class="border-top nowrap text-start" width="15%">Total Hours</th>
                                        <th class="border-top nowrap text-end pe-0" width="15%">Percent of Item</th>
                                    </tr>
                                    <?php
                                    foreach($row['services_details'] as $services){
                                    ?>
                                    <tr>
                                        <td class="ps-0"><?php echo $services['users']['full_name']; ?></td>
                                        <td ><?php echo number_format((float)$services['hrs_worked'], 2); ?></td>
                                        <td><?php echo number_format((float)$services['service_override_hrs'], 2); ?></td>
                                        <td><?php echo number_format((float)$services['total_hrs_for_tech'], 2); ?></td>
                                        <td class="text-end pe-0"><?php //echo $servicelogs['services_logs']['hours_worked']; ?></td>
                                    </tr>
                                    <?php } ?>
                                </table>
                            </td>
                        </tr>
                        <?php }}} ?>
                    </table>
                <?php }else if($report_type == '70'){ ?>
                    <table class="item-group-table">                        
                        <?php
                        if(!empty($reportdata['services_details'])){
                            $previous_date = '';
                            $technician = '';
                        foreach($reportdata['services_details'] as $keys=>$row){
                        ?>
                        <tr>
                            <th class="title nowrap text-start border-top-db ps-0" style="padding: 0;" width="10%"><?php echo date('m/d/Y', strtotime($keys)); ?></th>
                        </tr>
                        <?php 
                        foreach($row as $technician=>$services){
                        ?>
                        <tr>
                            <td class="ps-0 pe-0">
                                <table class="item-group-table" style="margin-left:10%;">
                                    <tr>
                                        <th class="border-top nowrap text-start"><?php echo $technician; ?></th>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                            <table class="item-group-table" style="margin-left:10%;">
                                                <tr>
                                                    <th class="border-btm nowrap text-start" width="30%">Item No.</th>
                                                    <th class="border-btm nowrap text-start" width="55%">Discrepancy</th>
                                                    <th class="border-btm nowrap text-end pe-0" width="15%">Hrs. Worked</th>
                                                </tr>
                                                <?php
                                                foreach($services as $itemsdet){
                                                ?>
                                                <tr>
                                                    <td class="border-btm ps-0"><?php echo $itemsdet['wo_item_position']; ?></td>
                                                    <td class="border-btm"><?php echo $itemsdet['wo_discrepancy']; ?></td>
                                                    <td class="border-btm text-end pe-0"><?php echo number_format((float)$itemsdet['total_hrs_worked'], 2); ?></td>
                                                </tr>
                                                <?php } ?>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php }}} ?>
                    </table>
                <?php }else{ ?>
                    <table class="item-group-table">
                        <tr>
                            <th class="title nowrap text-start border-btm-db ps-0" style="padding: 0;" width="10%">Item No.</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="35%">Discrepancy</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="10%">Est. Hours</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="10%">Reg. Hours</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="10%">OT Hours</th>
                            <th class="title nowrap text-start border-btm-db" style="padding: 0;" width="15%">Inspected</th>
                            <th class="title nowrap text-end border-btm-db pe-0" style="padding: 0;" width="10%">Item Status</th>
                        </tr>
                        <?php
                        if(!empty($reportdata['wo_item'])){
                        foreach($reportdata['wo_item'] as $keys=>$row){
                            $hrs_worked = !empty($row['services_details']['total_hrs_worked']) ? $row['services_details']['total_hrs_worked'] : '0.00';
                            $overtime_hrs = !empty($row['services_details']['total_overtime_hrs']) ? $row['services_details']['total_overtime_hrs'] : '0.00';
                            $estimated_hrs = $row['wo_item_overview']['estimated_hour'];

                            $total_hrs_worked += $hrs_worked;
                            $total_overtime_hrs += $overtime_hrs;
                            $total_estimated_hrs += $estimated_hrs;
                        ?>
                        <tr>
                            <td class="border-btm ps-0"><?php echo $row['wo_item_position']; ?></td>
                            <td class="border-btm"><?php echo $row['wo_discrepancy']; ?></td>
                            <td class="border-btm"><?php echo number_format((float)$estimated_hrs, 2); ?></td>
                            <td class="border-btm"><?php echo number_format((float)$hrs_worked, 2); ?></td>
                            <td class="border-btm"><?php echo number_format((float)$overtime_hrs, 2); ?></td>
                            <td class="border-btm"><?php echo !empty($row['signoff_details']) ? 'Yes' : 'No'; ?></td>
                            <td class="border-btm text-end pe-0"><?php echo $aircraftWOItemStatus[$row['wo_item_status']]; ?></td>
                        </tr>
                    <?php }} ?>
                    </table>
                <?php } ?>
            </td>
        </tr>
    </table>
    
    <table class="time-table item-group-table-body">
        <tr>
            <td></td>
            <td width="30%">
                <table class="time-table">
                    <?php if($report_type == '61' && !empty($reportdata['wo_item'])){ ?>
                    <tr>
                        <td class="text-end">
                            <strong>Total Estimated Hours:</strong>
                        </td>
                        <td class="text-end" style="padding-right: 0">
                            <?php echo number_format((float)$total_estimated_hrs, 2); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-end">
                            <strong>Total Regular Hours:</strong>
                        </td>
                        <td class="text-end" style="margin:0px; padding:0px;">
                            <?php echo number_format((float)$total_hrs_worked, 2); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-end">
                            <strong>Total Overtime Hours:</strong>
                        </td>
                        <td class="text-end" style="padding-top: 15px; padding-right: 0">
                            <?php echo number_format((float)$total_overtime_hrs, 2); ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="title border-top text-end">
                            <strong>Total Hours:</strong>
                        </td>
                        <td class="border-top text-end pe-0" style="padding-right: 0">
                            <?php echo number_format(($total_hrs_worked+$total_overtime_hrs+$total_estimated_hrs), 2); ?>
                        </td>
                    </tr>
                    <?php }else if($report_type == '62' && !empty($reportdata['services_details'])){ ?>
                    <tr style="margin:0px; padding:0px;">
                        <td class="text-end">
                            <strong>Total Regular Hrs</strong>
                        </td>
                        <td class="text-end" style="padding-right: 0;">
                            <?php echo number_format((float)$total_hrs_worked, 2); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-end">
                            <strong>Total Overtime Hours</strong>
                        </td>
                        <td class="text-end" style="padding-right: 0;;">
                            <?php echo number_format((float)$total_overtime_hrs, 2); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-end">
                            <strong>Total Cost/Hrs</strong>
                        </td>
                        <td class="text-end" style="padding-right: 0;">$0.00</td>
                    </tr>
                    <tr>
                        <td class="text-end">
                            <strong>Total OT Cost/Hrs</strong>
                        </td>
                        <td class="text-end" style="padding-right: 0;">$0.00</td>
                    </tr>
                    
                    <tr>
                        <td class="title border-top text-end">
                            <strong>Total Cost</strong>
                        </td>
                        <td class="border-top text-end pe-0" style="padding-right: 0">$0.00</td>
                    </tr>
                    <?php }else if($report_type == '68' && !empty($total_hours_over_estimates)){ ?>
                    <tr>
                        <td class="text-end">
                            <strong>Total Hours Over Estimate: </strong>
                        </td>
                        <td class="text-end" style="padding-right: 0;"><?php echo number_format((float)$total_hours_over_estimates, 2); ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>
    </table>
    <?php } ?>
</div>