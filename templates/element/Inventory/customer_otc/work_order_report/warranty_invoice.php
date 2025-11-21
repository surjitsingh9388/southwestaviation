<div class="table-container">
    <table class="cust-invoice-table">
        <tr>
            <td width="60%" class="pb-0 ps-0">
                <table class="cust-invoice-table">
                    <tr>
                        <td width="40%">
                            <?php
                            $path = WWW_ROOT . 'images' . DS . 'logo.png';
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            ?>
                            <img src="<?= $base64 ?>" alt="Logo" />
                        </td>
                        <td width="60%">
                            <p class="title">Southwest Aviation Specialties, LLC</p>
                            <p>Repair Station No. S30R818N</p>
                            <p>8720 Jack Bates Ave</p>
                            <p>Tusla, OK 74132</p>
                            <p>Phone: (918)298-4044</p>
                            <p>Fax: (918)298-6930</p>
                            <p>Date: <?php echo date('d/m/Y, h:i A'); ?></p>
                        </td>
                    </tr>
                </table>
                <table class="cust-invoice-table">
                    <tr>
                        <td class="text-center td-border">
                            <p class="title" style="text-decoration: underline;">Warranty Invoice</p>
                            <?php if(!empty($reportdata['wo_item'][0]['wo_item_overview']['warranty'])){ ?>
                            <p class="title">Work Order: 
                                <?php 
                                $woItemOverviewWarranty = unserialize(WOITEMOVERVIEWWARRANTY);
                                echo $reportdata['work_order_no'].'-'.$woItemOverviewWarranty[$reportdata['wo_item'][0]['wo_item_overview']['warranty']]; 
                                ?>
                            </p>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="40%" class="td-border" style="padding: 0;">
                <table class="cust-invoice-table">
                    <tr>
                        <td class="bg-gray border-btm title">
                            Customer Information
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            $reportHtml = '';
                            if(!empty($reportdata['wo_item'][0]['wo_item_overview']['warranty'])){
                                $reportHtml .= '<p><strong>'.$woItemOverviewWarranty[$reportdata['wo_item'][0]['wo_item_overview']['warranty']].'</strong></p>';
                            }
                            if(!empty($reportdata['customers']['address'])){
                                $reportHtml .= '<p>'.$reportdata['customers']['address'].'</p>';
                            }
                            if(!empty($reportdata['customers']['address2'])){
                                $reportHtml .= '<p>'.$reportdata['customers']['address2'].'</p>';
                            }
                            $citystatepin = '';
                            if(!empty($reportdata['customers']['city'])){
                                $citystatepin .= $reportdata['customers']['city'];
                            }
                            if(!empty($reportdata['customers']['state'])){
                                $citystatepin .= !empty($citystatepin) ? ', '.$reportdata['customers']['state'].' ' : $reportdata['customers']['state'].' ';
                            }
                            if(!empty($reportdata['customers']['zip'])){
                                $citystatepin .= $reportdata['customers']['zip'];
                            }
                            if(!empty($citystatepin)){
                                $reportHtml .= '<p>'.$citystatepin.'</p>';
                            }
                            if(!empty($reportdata['customers']['cellular_phone'])){
                                $reportHtml .= '<p>'.$reportdata['customers']['cellular_phone'].'</p>';
                            }
                            if(!empty($reportdata['aircraft']['aircraft_registration_number'])){
                                $reportHtml .= '<p>Reg. No: '.$reportdata['aircraft']['aircraft_registration_number'].'</p>';
                            }
                            if(!empty($reportdata['logbook_value_overviews']['current_ac_tt'])){
                                $reportHtml .= '<p>Current A/C TT: '.$reportdata['logbook_value_overviews']['current_ac_tt'].', Tach: </p>';
                            }
                            if(!empty($reportdata['aircraft']['aircraft_serial'])){
                                $reportHtml .= '<p>A/C Serial: '.$reportdata['aircraft']['aircraft_serial'].'</p>';
                            }
                            if(!empty($reportdata['logbook_value_overviews']['warranty_date'])){
                                $reportHtml .= '<p>Warranty Date: '.$reportdata['logbook_value_overviews']['warranty_date'].', Tach: </p>';
                            }
                            if(!empty($reportdata['aircraft']['aircraft_engine_type']) && $reportdata['aircraft']['aircraft_engine_type'] != '5'){
                                if($reportdata['aircraft']['aircraft_engine_type'] == '1' || $reportdata['aircraft']['aircraft_engine_type'] == '2'){
                                    $reportHtml .= '<p>Engine S/N: '.(!empty($reportdata['aircraftlogbookengine']['serial']) ? $reportdata['aircraftlogbookengine']['serial'] : '').'</p>';
                                }else{
                                    if(!empty($reportdata['aircraftlogbookengine']['engine1_serial_no'])){
                                        $reportHtml .= '<p>Engine S/N #1: '.$reportdata['aircraftlogbookengine']['engine1_serial_no'].'</p>';
                                    }
                                    if(!empty($reportdata['aircraftlogbookengine']['engine2_serial_no'])){
                                        $reportHtml .= '<p>Engine S/N #2: '.$reportdata['aircraftlogbookengine']['engine2_serial_no'].'</p>';
                                    }
                                    if(!empty($reportdata['aircraftlogbookengine']['engine3_serial_no'])){
                                        $reportHtml .= '<p>Engine S/N #3: '.$reportdata['aircraftlogbookengine']['engine3_serial_no'].'</p>';
                                    }
                                }
                            }
                            if(!empty($reportdata['customers']['customer_name'])){
                                $reportHtml .= '<p>Customer: '.$reportdata['customers']['customer_name'].'</p>';
                            }
                            
                            echo $reportHtml;

                            $customerterms = unserialize(CUSTOMERTERMS);
                            if(!empty($reportdata['customers']['terms'])){
                            ?>
                            <p>Terms: <?php echo $customerterms[$reportdata['customers']['terms']]; ?></p>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <?php
    $total_outside_repair_charges = '0.00';
    $final_labor_charges = '0.00';
    $final_shipping_charges = '0.00';
    $final_outside_repair_charges = '0.00';
    $addl_charges_sub_total = '0.00';
    $totalbalancedue = '0.00';
    $total_deposits = '0.00';

    if(!empty($reportdata['wo_item'])){
    $aircraftWOCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);
    
    foreach($reportdata['wo_item'] as $keys=>$row){
        $subtotal = 0;
    ?>
    <table class="cust-invoice-table-body border-top-db">
        <tr>
            <td style="padding-left: 0; padding-right: 0">
                <table class="cust-invoice-table">
                    <tr>
                        <td colspan="2" class="text-center">
                            <strong>Item:<?php echo $row['wo_item_position']; ?> - <?php echo $aircraftWOCategory[$row['wo_item_overview']['wo_category']]; ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0">
                            <p><strong>Discrepancy</strong></p>
                            <p><?php echo $row['wo_discrepancy']; ?></p>
                            <p>&nbsp;</p>
                            <p><strong>Corrective Action</strong></p>
                            <p><?php echo $row['wo_corrective_action']; ?></p>
                            <p>&nbsp;</p>
                            <?php if(!empty($row['wo_item_overview']['warranty_claim_no'])){ ?>
                            <p>*Claim Number: <?php echo $row['wo_item_overview']['warranty_claim_no']; ?></p>
                            <?php } ?>
                            <?php if($report_type == '74' && !empty($row['item_notes'])){ ?>
                            <p>&nbsp;</p>
                            <p><strong>Notes</strong></p>
                            <p><?php echo $row['item_notes']; ?></p>
                            <?php
                            } 
                            $total_labor_charges = '0.00';
                            $total_part_charges = '0.00';
                            $total_shipping_charges = '0.00';
                            if(!empty($row['wo_osr_info'])){ 
                            ?>
                            <p>&nbsp;</p>
                            <p><strong>Outside Repair:</strong></p>
                            <?php
                            foreach($row['wo_osr_info'] as $osrinfo){
                                $labor_charge = !empty($osrinfo['osr_labor_charge']) ? $osrinfo['osr_labor_charge'] : '0.00';
                                $parts_charge = !empty($osrinfo['osr_parts_charge']) ? $osrinfo['osr_parts_charge'] : '0.00';
                                
                                if($row['wo_item_overview']['way_of_billing'] != '3'){
                                    if(!empty($reportdata['warranty_infoes']['pay_labor'])){
                                        $total_labor_charges += $labor_charge;
                                        $total_outside_repair_charges += $labor_charge;
                                    }
                                }
                                $total_part_charges += $parts_charge;
                                $total_shipping_charges += ($osrinfo['osr_shipping_out']+$osrinfo['osr_shipping_in']);
                            ?>
                            <p>
                                <span style="padding: 5px 10px 0 0">Labour: <?php echo '$'.number_format((float)$labor_charge, 2); ?>,</span>
                                <span style="padding: 5px 10px 0 0">Parts: <?php echo '$'.number_format((float)$parts_charge, 2); ?>,</span>
                                <span style="padding: 5px 10px 0 0">Parts No: <?php echo $osrinfo['osr_part_number']; ?></span>
                            </p>
                            <?php 
                            }} 
                            $total_shipping_charges += $row['wo_item_overview']['shipping_in'];
                            $final_shipping_charges += $total_shipping_charges;
                            $final_outside_repair_charges += ($total_labor_charges+$total_part_charges);
                            ?>
                            
                        </td>
                        <td class="text-end" style="padding-right: 0">
                            <table class="value-table">
                                <tr>
                                    <td width="150px">
                                        <p><strong>Hours</strong></p>
                                        <p><strong>
                                            <?php 
                                            /*if($row['wo_item_overview']['way_of_billing'] == '2'){
                                                $hour_worked = 'Flat';
                                            }else if($row['wo_item_overview']['way_of_billing'] == '3'){
                                                $hour_worked = 'N/C';
                                            }*/
                                            
                                            echo '0.00'; 
                                            ?>
                                        </strong></p>
                                    </td>
                                    <td width="150px">
                                        <p><strong>Subtotal</strong></p>
                                        <p><strong><?php echo '0.00'; ?></strong></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="cust-invoice-table cust-invoice-table-body bg-gray border-btm">
        <tr>
            <td width="20%">
                <strong>Labour: <?php echo '$0.00'; ?></strong>
            </td>
            <td width="20%">
                <strong>Parts: <?php echo '$0.00'; ?></strong>
            </td>
            <td width="20%">
                <strong>Shipping: <?php if(!empty($reportdata['warranty_infoes']['pay_shipping'])){echo '$'.number_format((float)$total_shipping_charges, 2);} ?></strong>
            </td>
            <td width="20%">
                <strong>Outside Repair: <?php echo '$'.number_format(($total_labor_charges+$total_part_charges), 2); ?></strong>
            </td>
            <td width="20%">
                <strong>Item Subtotal: <?php echo '$'.number_format(($total_labor_charges+$subtotal+$total_shipping_charges+$total_part_charges), 2); ?></strong>
            </td>
        </tr>
    </table>
    <?php 
    }
    $totalbalancedue = $final_labor_charges+$final_outside_repair_charges+$final_shipping_charges;
    ?>
    
    <table class="cust-invoice-table cust-invoice-table-body mt-0">
        <tr>
            <td style="padding-left: 0; padding-right: 0">
                <table class="cust-invoice-table">
                    <tr>
                        <td width="50%" style="padding-left: 0; padding-right: 10%">
                            <table class="bg-gray td-border">
                                <tr>
                                    <td class="pb-0">
                                        <strong>Labor Summary</strong>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>
                                            Shop Labor: <?php echo '$0.00'; ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            Outside Repair: <?php echo '$'.number_format((float)$total_outside_repair_charges, 2); ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="40%" style="padding-right: 0">
                            <table class="cust-invoice-table">
                                <tr>
                                    <td class="text-end">
                                        <strong>Total Labor:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php echo '$0.00';//.$final_labor_charges; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Total Parts:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php echo '$0.00'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Total Shipping:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php echo '$'.number_format((float)$final_shipping_charges, 2); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Total Outside Repair:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php echo '$'.number_format((float)$final_outside_repair_charges, 2); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Additional Charges:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php echo '$0.00'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Tax:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Amount Due:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php echo '$'.number_format((float)$totalbalancedue, 2); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Deposit(s) and Credit(s):</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php echo '$0.00'; ?>
                                    </td>
                                </tr>
                                <tr class="td-border">
                                    <td class="text-end">
                                        <strong>Balance Due:</strong>
                                    </td>
                                    <td class="text-end pe-1">
                                        <?php echo '$'.number_format((float)$totalbalancedue, 2); ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="cust-invoice-table cust-invoice-table-body">
        <tr>
            <td class="text-center impInfo">
                <strong>Important information</strong>
            </td>
        </tr>
        <tr>
            <td>MISC. CHARGES NOTES: </td>
        </tr>
        <tr>
            <td><?php echo $reportdata['wo_misc_charges']['misc_charge_description_for_invoice']; ?></td>
        </tr>
    </table>
    <table class="cust-invoice-table cust-invoice-table-body border-top">
        <tr>
            <td style="padding-left: 0">
                <?php echo $reportdata['customers']['customer_name']; ?> or Authorised representative
            </td>
            <td style="padding-right: 0">
                Date: 
            </td>
            
        </tr>
    </table>
    <?php } ?>
</div>