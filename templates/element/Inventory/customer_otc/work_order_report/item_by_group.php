
<div class="table-container">
    <table class="header-table">
        <tr>
            <td class="pb-0 ps-0">
                <table class="item-group-table">
                    <tr>
                        <td>
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
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class="item-group-table">
                                <tr>
                                    <td>
                                        <table class="item-group-table">
                                            <tr>
                                                <td class="title" width="70%" style="font-size:16px;">
                                                    Southwest Aviation Specialties, LLC - Phone: (918) 298-4044, Fax: (918) 298-698-6930 - Repair Station #: S30R818N - 8720 Jac Bates Ave, Tulsa, OK 74132
                                                </td>
                                                <td class="title text-end" width="30%" style="font-size:16px;">
                                                    WO: <?php echo $reportdata['work_order_no']; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-top-db text-center" style="padding-top: 15px;">
                                        <p class="headingTitle">Items by Group - WO: <?php echo $reportdata['work_order_no']; ?> - <?php echo $reportdata['customers']['customer_name'].'('.$reportdata['aircraft']['aircraft_registration_number'].')'; ?></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="item-group-table item-group-table-body">
        <tr>
            <td style="padding-left: 0; padding-right: 0" class="border-top-db">
                <?php
                $hour_worked = 0;
                $finish_item = 0;
                $total_item_count = count($reportdata['wo_item']);
                $estimated_hour = 0;

                if(!empty($reportdata['wo_item'])){
                    foreach($reportdata['wo_item'] as $keys=>$row){
                        if($row['wo_item_status'] == '3'){
                            $finish_item++;
                        }
                        if(!empty($row['wo_item_overview']['estimated_hour'])){
                            $estimated_hour += $row['wo_item_overview']['estimated_hour'];
                        }
                        if(!empty($row['services_details'])){
                            foreach($row['services_details'] as $services){
                                if(!empty($services['total_hrs_for_tech'])){
                                    $hour_worked += $services['total_hrs_for_tech'];
                                }
                            }
                        }
                    }
                }

                $avg_hour_worked = ($hour_worked > 0 && $estimated_hour > 0) ? ($hour_worked*100)/$estimated_hour : 0;
                $avg_hour_worked = round($avg_hour_worked, 2);

                $avg_finish_item = ($finish_item*100)/$total_item_count;
                $avg_finish_item = round($avg_finish_item, 2);
                ?>
                <table class="item-group-table">
                    <tr>
                        <td class="ps-0" width="29%">
                            <p class="headingTitle nowrap" style="font-size:22px;">(No Grouping)</p>
                        </td>
                        <td width="35%">
                            <p class="headingTitle nowrap" style="font-size:22px;">
                                Hrs Worked / Est Hrs: <?php echo number_format((float)$hour_worked, 2).'/'.number_format((float)$estimated_hour, 2).' ('.number_format((float)$avg_hour_worked, 2).'%'.')'; ?>
                            </p>
                        </td>
                        <td class="pe-0" width="35%">
                            <p class="headingTitle nowrap" style="font-size:22px;">Finished/ All Items: <?php echo $finish_item; ?>/<?php echo $total_item_count.' ('.$avg_finish_item.'%'.')'; ?></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php if($report_type != '29'){ ?>
        <tr>
            <td>
                <table>
                    <tr>
                        <th class="title ps-0"></td>
                        <th class="title"></th>
                        <th class="title"></th>
                        <th class="title"></th>
                        <th class="title"></th>
                        <th class="title" style="font-size:22px;">Hours</th>
                        <th class="title"></th>
                    </tr>
                
                    <tr>
                        <td class="title nowrap text-start border-btm-db ps-0" width="5%" style="font-size:22px;">Item</th>
                        <td class="title nowrap text-start border-btm-db" width="25%" style="font-size:22px;">Discrepancy</th>
                        <td class="title nowrap text-start border-btm-db" width="33%" style="font-size:22px;">Corrective Action</th>
                        <td class="title nowrap text-start border-btm-db" width="15%" style="font-size:22px;">Category</th>
                        <td class="title nowrap text-start border-btm-db" width="7%" style="font-size:22px;">East.</th>
                        <td class="title nowrap text-start border-btm-db" width="7%" style="font-size:22px;">Actual</th>
                        <td class="title nowrap text-start border-btm-db" style="padding-right: 0; font-size:22px;" width="7%">Item Status</th>
                    </tr>
                    <?php
                    if(!empty($reportdata['wo_item'])){
                        $aircraftWOCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);
                        $aircraftWOItemStatus = unserialize(AIRCRAFT_WORKORDER_ITEMSTATUS);

                        foreach($reportdata['wo_item'] as $keys=>$row){
                            $hour_worked = 0;
                            if(!empty($row['services_details'])){
                                foreach($row['services_details'] as $services){
                                    if(!empty($services['total_hrs_for_tech'])){
                                        $hour_worked += $services['total_hrs_for_tech'];
                                    }
                                }
                            }
                    ?>
                    <tr>
                        <td class="border-btm ps-0" style="font-size:22px;"><?php echo $row['wo_item_position']; ?></td>
                        <td class="border-btm" style="font-size:22px;"><?php echo $row['wo_discrepancy']; ?></td>
                        <td class="border-btm" style="font-size:22px;"><?php echo $row['wo_corrective_action']; ?></td>
                        <td class="border-btm" style="font-size:22px;"><?php echo $aircraftWOCategory[$row['wo_item_overview']['wo_category']]; ?></td>
                        <td class="border-btm" style="font-size:22px;"><?php echo (!empty($row['wo_item_overview']['estimated_hour']) ? number_format((float)$row['wo_item_overview']['estimated_hour'], 2) : '0.00'); ?></td>
                        <td class="border-btm" style="font-size:22px;"><?php echo number_format((float)$hour_worked, 2); ?></td>
                        <td class="border-btm pe-0" style="font-size:22px;"><?php echo $aircraftWOItemStatus[$row['wo_item_status']]; ?></td>
                    </tr>
                    <?php }} ?>
                </table>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>