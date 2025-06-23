<div class="table-container">
    <table class="cust-invoice-table">
        <tr>
            <td width="65%" class="pb-0 ps-0">
                <table class="cust-invoice-table">
                    <tr>
                        <td width="30%">
                            <?php
                            $path = WWW_ROOT . 'images' . DS . 'logo.png';
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            ?>
                            <img src="<?= $base64 ?>" alt="Logo" />
                        </td>
                        <td width="70%">
                            <p class="title" style="font-size:18px;">Southwest Aviation Specialties, LLC</p>
                            <p style="font-size:16px;">Repair Station No. S30R818N</p>
                            <p style="font-size:16px;">8720 Jack Bates Ave</p>
                            <p style="font-size:16px;">Tusla, OK 74132</p>
                            <p style="font-size:16px;">Phone: (918)298-4044</p>
                            <p style="font-size:16px;">Fax: (918)298-6930</p>
                            <p style="font-size:16px;">Date: <?php echo date('m/d/Y, h:i A'); ?></p>
                        </td>
                    </tr>
                </table>
                <table class="cust-invoice-table">
                    <tr>
                        <td class="text-center td-border">
                            <p class="title" style="text-decoration: underline;"><?php echo ($report_type >= '72' && $report_type <= '74') ? 'Warranty Invoice' : 'Customer Invoice'; ?></p>
                            <p class="title">Work Order: 
                                <?php 
                                $woItemOverviewWarranty = unserialize(WOITEMOVERVIEWWARRANTY);
                                echo ($report_type >= '72' && $report_type <= '74') ? $reportdata['work_order_no'].'-'.$woItemOverviewWarranty[$reportdata['wo_item'][0]['wo_item_overview']['warranty']] : $reportdata['work_order_no']; 
                                ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="35%" class="td-border" style="padding: 0;">
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
                            if(!empty($reportdata['customers']['customer_name'])){
                                $reportHtml .= '<p style="font-size:16px;"><strong>'.$reportdata['customers']['customer_name'].'</strong></p>';
                            }
                            if(!empty($reportdata['customers']['address'])){
                                $reportHtml .= '<p style="font-size:16px;">'.$reportdata['customers']['address'].'</p>';
                            }
                            if(!empty($reportdata['customers']['address2'])){
                                $reportHtml .= '<p style="font-size:16px;">'.$reportdata['customers']['address2'].'</p>';
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
                                $reportHtml .= '<p style="font-size:16px;">'.$citystatepin.'</p>';
                            }
                            if(!empty($reportdata['customers']['cellular_phone'])){
                                $reportHtml .= '<p style="font-size:16px;">'.$reportdata['customers']['cellular_phone'].'</p>';
                            }
                            if(!empty($reportdata['aircraft']['aircraft_registration_number'])){
                                $reportHtml .= '<p style="font-size:16px;">Reg. No: '.$reportdata['aircraft']['aircraft_registration_number'].'</p>';
                            }
                            if(!empty($reportdata['aircraft']['aircraft_serial'])){
                                $reportHtml .= '<p style="font-size:16px;">Aircraft Serial: '.$reportdata['aircraft']['aircraft_serial'].'</p>';
                            }
                            if(!empty($reportdata['logbook_value_overviews']['current_ac_tt'])){
                                $reportHtml .= '<p style="font-size:16px;">Current A/C TT: '.$reportdata['logbook_value_overviews']['current_ac_tt'].'</p>';
                            }
                            if(!empty($reportdata['logbook_value_overviews']['airframe_lndgs'])){
                                $reportHtml .= '<p style="font-size:16px;">A/C Landings: '.$reportdata['logbook_value_overviews']['airframe_lndgs'].'</p>';
                            }
                            
                            echo $reportHtml;

                            $customerterms = unserialize(CUSTOMERTERMS);
                            if(!empty($reportdata['customers']['terms'])){
                            ?>
                            <p style="font-size:16px;">Terms: <?php echo $customerterms[$reportdata['customers']['terms']]; ?></p>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <?php
    $totalbalancedue = 0;
    $total_deposits = 0;
    $addl_charges_sub_total = 0;
    $final_labor_charges = 0;
    $final_parts_charges = 0;
    $final_shipping_charges = 0;
    $final_outside_repair_charges = 0;
    $total_outside_repair_charges = 0;
    $final_additional_charges = 0;
    $final_estimate_before_deposit = 0;
    $final_deposits_credits = 0;
    $final_item_part_charges = 0;

    if(!empty($reportdata['wo_item'])){
    
        $aircraftWOCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);
        $estimated_rate_static = ESTIMATEDRATE;

        foreach($reportdata['wo_item'] as $keys=>$row){
            $hour_worked = 0;
            if(!empty($row['services_details'])){
                foreach($row['services_details'] as $services){
                    if(!empty($services['total_hrs_for_tech'])){
                        $hour_worked += $services['total_hrs_for_tech'];
                    }
                }
            }
            
            if(!empty($reportdata['warranty_infoes']['pay_labor']) || empty($row['wo_item_overview']['item_is_warranty'])){
                if($row['wo_item_overview']['way_of_billing'] == '1'){
                    if(!empty($row['wo_item_overview']['special_hourly_rate_for_item'])){
                        $estimated_rate = !empty($row['wo_item_overview']['special_rate_hr']) ? $row['wo_item_overview']['special_rate_hr'] : '0.00';
                    }else{
                        $estimated_rate = !empty($row['wo_item_overview']['estimated_rate']) && $row['wo_item_overview']['estimated_rate'] != '0.00' ? $row['wo_item_overview']['estimated_rate'] : $estimated_rate_static;
                    }
                    $subtotal = $estimated_rate*$hour_worked;
                }else if($row['wo_item_overview']['way_of_billing'] == '2'){
                    $estimated_rate = !empty($row['wo_item_overview']['flat_rate']) ? ($row['wo_item_overview']['flat_rate']*$row['wo_item_overview']['flat_rate_qty']) : '0.00';
                    $subtotal = $estimated_rate;
                }else{
                    $estimated_rate = '0.00';
                    $subtotal = $estimated_rate*$hour_worked;
                }
            }else{
                $estimated_rate = '0.00';
                $subtotal = $estimated_rate*$hour_worked;
            }
            
            $total_labor_charges = '0.00';
            $total_part_charges = '0.00';
            $total_shipping_charges = '0.00';
            if(!empty($row['wo_osr_info'])){
                foreach($row['wo_osr_info'] as $osrinfo){
                    $labor_charge = !empty($osrinfo['osr_labor_charge']) ? $osrinfo['osr_labor_charge'] : '0.00';
                    $parts_charge = !empty($osrinfo['osr_parts_charge']) ? $osrinfo['osr_parts_charge'] : '0.00';

                    if(!empty($reportdata['warranty_infoes']['pay_labor']) || empty($row['wo_item_overview']['item_is_warranty'])){
                        $total_labor_charges += $labor_charge;
                        $total_outside_repair_charges += $labor_charge;
                    }
                    if(!empty($reportdata['warranty_infoes']['pay_parts']) || empty($row['wo_item_overview']['item_is_warranty'])){
                        $total_part_charges += $parts_charge;
                    }
                    if(!empty($reportdata['warranty_infoes']['pay_shipping']) || empty($row['wo_item_overview']['item_is_warranty'])){
                        $total_shipping_charges += ($osrinfo['osr_shipping_out']+$osrinfo['osr_shipping_in']);
                    }
                    
                }
            }
            if(!empty($reportdata['warranty_infoes']['pay_shipping']) || empty($row['wo_item_overview']['item_is_warranty'])){
                $total_shipping_charges += $row['wo_item_overview']['shipping_in'];
            }

            $final_labor_charges += $subtotal;//($total_labor_charges+$subtotal);
            $final_parts_charges += '0.00';//$total_part_charges;
            $final_shipping_charges += $total_shipping_charges;
            $final_outside_repair_charges += ($total_labor_charges+$total_part_charges);

            if(!empty($row['part_details'])){
                foreach($row['part_details'] as $partdet){
                    $final_item_part_charges += $partdet['part_total_prices'];
                }
            }
        }

        foreach($reportdata['wo_geninfo_deposits'] as $deposits){
            $total_deposits += $deposits['amount_to_add'];
        }
        $pilot_services_amount = !empty($reportdata['wo_misc_charges']['pilot_services_amount']) ? $reportdata['wo_misc_charges']['pilot_services_amount'] : '0.00';
        $amount_per_tire = !empty($reportdata['wo_misc_charges']['amount_per_tire']) ? $reportdata['wo_misc_charges']['amount_per_tire']* $reportdata['wo_misc_charges']['tire'] : '0.00';
        $epa_charge_amount = !empty($reportdata['wo_misc_charges']['epa_charge_amount']) ? $reportdata['wo_misc_charges']['epa_charge_amount']*2 : '0.00';
        $oil_analysis_amount = !empty($reportdata['wo_misc_charges']['oil_analysis_amount']) ? $reportdata['wo_misc_charges']['oil_analysis_amount']*2 : '0.00';
        $shop_supplies_amount = !empty($reportdata['wo_misc_charges']['shop_supplies_amount']) && !empty($reportdata['wo_misc_charges']['shop_supplies_method']) && $reportdata['wo_misc_charges']['shop_supplies_method'] == '1' ? $reportdata['wo_misc_charges']['shop_supplies_amount'] : '0.00';
        $fuel_amount = !empty($reportdata['wo_misc_charges']['totalfuelcharges']) ? $reportdata['wo_misc_charges']['totalfuelcharges'] : '0';
        $mis_charge_amount = !empty($reportdata['wo_misc_charges']['mis_charge_amount']) ? $reportdata['wo_misc_charges']['mis_charge_amount'] : '0.00';

        $addl_charges_sub_total = $pilot_services_amount+$amount_per_tire+$epa_charge_amount+$oil_analysis_amount+$shop_supplies_amount+$fuel_amount+$mis_charge_amount;

        $totalbalancedue = $final_labor_charges+$final_outside_repair_charges+$final_shipping_charges+$addl_charges_sub_total+$final_item_part_charges-$total_deposits;
    }
    if($report_type != '17' && $report_type != '18' && $report_type != '19' && $report_type != '14'){
    ?>
    <table class="cust-invoice-table cost-table-data">
        <tbody>
            <tr>
                <td class="text-center">
                    <span>Total Balance Due &nbsp;&nbsp;&nbsp;</span>     
                    <?php if($report_type != '18' && $report_type != '19'){ ?>
                    <strong class="value-title"><u><?php echo '$'.number_format($totalbalancedue, 2); ?></u></strong>
                    <?php } ?>
                    <span> &nbsp;&nbsp;&nbsp;(See Below For Cost Table)</span>
                </td>
            </tr>
        </tbody>
    </table>
    
    <?php
    }

    if(!empty($reportdata['wo_item'])){
    if($report_type != '13' && $report_type != '14'){
    $aircraftWOCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);
    
    foreach($reportdata['wo_item'] as $keys=>$row){
        $hour_worked = 0;
        if(!empty($row['services_details'])){
            foreach($row['services_details'] as $services){
                if(!empty($services['total_hrs_for_tech'])){
                    $hour_worked += $services['total_hrs_for_tech'];
                }
            }
        }
        
        if(!empty($reportdata['warranty_infoes']['pay_labor']) || empty($row['wo_item_overview']['item_is_warranty'])){
            if($row['wo_item_overview']['way_of_billing'] == '1'){
                if(!empty($row['wo_item_overview']['special_hourly_rate_for_item'])){
                    $estimated_rate = !empty($row['wo_item_overview']['special_rate_hr']) ? $row['wo_item_overview']['special_rate_hr'] : '0.00';
                }else{
                    $estimated_rate = !empty($row['wo_item_overview']['estimated_rate']) && $row['wo_item_overview']['estimated_rate'] != '0.00' ? $row['wo_item_overview']['estimated_rate'] : $estimated_rate_static;
                }
                $subtotal = $estimated_rate*$hour_worked;
            }else if($row['wo_item_overview']['way_of_billing'] == '2'){
                $estimated_rate = !empty($row['wo_item_overview']['flat_rate']) ? ($row['wo_item_overview']['flat_rate']*$row['wo_item_overview']['flat_rate_qty']) : '0.00';
                $subtotal = $estimated_rate;
            }else{
                $estimated_rate = '0.00';
                $subtotal = $estimated_rate*$hour_worked;
            }
        }else{
            $estimated_rate = '0.00';
            $subtotal = $estimated_rate*$hour_worked;
        }
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
                        <td style="padding-left: 0; text-align: left;">
                            <p><strong>Discrepancy</strong></p>
                            <p><?php echo $row['wo_discrepancy']; ?></p>
                            <p>&nbsp;</p>
                            <p><strong>Corrective Action</strong></p>
                            <p><?php echo $row['wo_corrective_action']; ?></p>
                            <?php 
                            if(($report_type == '21' || $report_type == '74') && !empty($row['item_notes'])){ 
                            ?>
                                <p>&nbsp;</p>
                                <p><strong>Notes</strong></p>
                                <p><?php echo $row['item_notes']; ?></p>
                            <?php
                            } 
                            $total_labor_charges = '0.00';
                            $total_part_charges = '0.00';
                            $total_shipping_charges = '0.00';
                            $total_item_part_charges = '0.00';
                            if(!empty($row['wo_osr_info']) && $report_type != '15'){ 
                            ?>
                            <p>&nbsp;</p>
                            <p><strong>Outside Repair:</strong></p>
                            <?php
                            foreach($row['wo_osr_info'] as $osrinfo){
                                $labor_charge = !empty($osrinfo['osr_labor_charge']) ? $osrinfo['osr_labor_charge'] : '0.00';
                                $parts_charge = !empty($osrinfo['osr_parts_charge']) ? $osrinfo['osr_parts_charge'] : '0.00';

                                if($row['wo_item_overview']['way_of_billing'] != '3'){
                                    $total_labor_charges += $labor_charge;
                                }

                                if(!empty($reportdata['warranty_infoes']['pay_labor']) || empty($row['wo_item_overview']['item_is_warranty'])){
                                    $total_labor_charges += $labor_charge;
                                }
                                if(!empty($reportdata['warranty_infoes']['pay_parts']) || empty($row['wo_item_overview']['item_is_warranty'])){
                                    $total_part_charges += $parts_charge;
                                }
                                if(!empty($reportdata['warranty_infoes']['pay_shipping']) || empty($row['wo_item_overview']['item_is_warranty'])){
                                    $total_shipping_charges += ($osrinfo['osr_shipping_out']+$osrinfo['osr_shipping_in']);
                                }
                            ?>
                            <p>
                                <span style="padding: 5px 10px 0 0">Labor: <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($labor_charge, 2);} ?>,</span>
                                <span style="padding: 5px 10px 0 0">Parts: <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($parts_charge, 2);} ?>,</span>
                                <?php if($report_type != '16'){ ?>
                                <span style="padding: 5px 10px 0 0">Parts No: <?php echo $osrinfo['osr_part_number']; ?></span>
                                <?php } ?>
                            </p>
                            <?php 
                            }} 
                            if(!empty($reportdata['warranty_infoes']['pay_shipping']) || empty($row['wo_item_overview']['item_is_warranty'])){
                                $total_shipping_charges += $row['wo_item_overview']['shipping_in'];
                            }
                            
                            if(!empty($row['signoff_details'])){
                                $woItemSignOff = unserialize(WOITEMSIGNOFF);
                            ?>
                            <p>&nbsp;</p>
                            <p><strong>Inspection Information:</strong></p>
                            <?php
                            foreach($row['signoff_details'] as $signoff){
                            ?>
                            <p><?php echo $woItemSignOff[$signoff['signoff_category']].': '.$signoff['users']['full_name'].' on '.date('m/d/Y', strtotime($signoff['inspected_date'])); ?></p>
                            <?php }} ?>
                        </td>
                        <td class="text-end" style="padding-right: 0">
                            <table class="value-table">
                                <tr>
                                    <td width="150px">
                                        <p><strong>Hours</strong></p>
                                        <p><strong>
                                            <?php 
                                            if($row['wo_item_overview']['way_of_billing'] == '2'){
                                                $hour_worked = 'Flat';
                                            }else if($row['wo_item_overview']['way_of_billing'] == '3'){
                                                $hour_worked = 'N/C';
                                            }else{
                                                $hour_worked = number_format($hour_worked, 2);
                                            }
                                            
                                            echo $hour_worked; 
                                            ?>
                                        </strong></p>
                                    </td>
                                    <td width="150px">
                                        <p><strong>Subtotal</strong></p>
                                        <p><strong><?php  if($report_type != '18' && $report_type != '19'){echo '$'.number_format($subtotal, 2);} ?></strong></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php if(!empty($row['part_details'])){ ?>
                    <tr>
                        <td colspan="2">
                            <table class="cust-invoice-table-body border-top-db">
                                <tr>
                                    <th style="width:5%; text-align:left;">Qty</th>
                                    <th style="width:5%; text-align:left;">Unit</th>
                                    <th style="width:15%; text-align:left;">Part Number</th>
                                    <th style="width:30%; text-align:left;">Description</th>
                                    <th style="width:15%; text-align:left;">Serial Number</th>
                                    <th style="width:15%; text-align:left;">Unit Price</th>
                                    <th style="width:15%; text-align:left;">Subtotal</th>
                                </tr>
                                <?php 
                                foreach($row['part_details'] as $partdet){ 
                                $total_item_part_charges += $partdet['part_total_prices'];    
                                ?>
                                <tr>
                                    <td style="text-align:left;"><?php echo $partdet['qty_used']; ?></td>
                                    <td style="text-align:left;"><?php echo 'EA'; ?></td>
                                    <td style="text-align:left;"><?php echo $partdet['part_number']; ?></td>
                                    <td style="text-align:left;"><?php echo $partdet['part_description']; ?></td>
                                    <td style="text-align:left;"><?php echo $partdet['serial_number']; ?></td>
                                    <td style="text-align:left;"><?php echo $partdet['price_each']; ?></td>
                                    <td style="text-align:left;"><?php echo $partdet['part_total_prices']; ?></td>
                                </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>
    </table>
    <table class="cust-invoice-table cust-invoice-table-body bg-gray border-btm">
        <tr>
            <td width="18%" style="text-align:left;">
                <strong>Labor: <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($total_labor_charges, 2);} ?></strong>
            </td>
            <td width="18%" style="text-align:left;">
                <strong>Parts: <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($total_item_part_charges, 2);} ?></strong>
            </td>
            <td width="18%" style="text-align:left;">
                <strong>Shipping: <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($total_shipping_charges, 2);} ?></strong>
            </td>
            <td width="23%" style="text-align:left;">
                <strong>Outside Repair: <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format(($total_labor_charges+$total_part_charges), 2);} ?></strong>
            </td>
            <td style="text-align:left;">
                <strong>Item Subtotal: <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format(($total_labor_charges+$subtotal+$total_shipping_charges+$total_part_charges+$total_item_part_charges), 2);} ?></strong>
            </td>
        </tr>
    </table>
    <?php }} ?>
    <p>&nbsp;</p>
    <p class="text-end" style="padding-right: 0;">Please see the next page for the totals for this invoice</p>
    <pagebreak />
    <table class="cust-invoice-table cust-invoice-table-body td-border">
        <tr>
            <td colspan="7">
                <strong>Additional Charges</strong>
            </td>
        </tr>
    
        <tr>
            <td>
                <p>Pilot Services</p>
                <p><?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($pilot_services_amount, 2);} ?></p>
            </td>
            <td>
                <p>Tire Disposal</p>
                <p><?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($amount_per_tire, 2);} ?></p>
            </td>
            <td>
                <p>EPA Charge</p>
                <p><?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($epa_charge_amount, 2);} ?></p>
            </td>
            <td>
                <p>Oil Analysis</p>
                <p><?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($oil_analysis_amount, 2);} ?></p>
            </td>
            <td>
                <p>Shop Supplies</p>
                <p><?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($shop_supplies_amount, 2);} ?></p>
            </td>
            <td>
                <p>Fuel</p>
                <p><?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($fuel_amount, 2);} ?></p>
            </td>
            <td>
                <p>Misc.</p>
                <p><?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($mis_charge_amount, 2);} ?></p>
            </td>
            <td class="text-end">
                <p><strong>Subtotal<strong></p>
                <p><strong><?php if($report_type != '18' && $report_type != '19'){echo (!empty($addl_charges_sub_total) ? '$'.number_format($addl_charges_sub_total, 2) : '$0.00');} ?></strong></p>
            </td>
        </tr>
    </table>
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
                                            Shop Labor: <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($final_labor_charges, 2);} ?>
                                        </p>
                                    </td>
                                    <td>
                                        <p>
                                            Outside Repair: <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($total_outside_repair_charges, 2); } ?>
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
                                        <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($final_labor_charges, 2);} ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Total Parts:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($final_item_part_charges, 2);} ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Total Shipping:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($final_shipping_charges, 2);} ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Total Outside Repair:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($final_outside_repair_charges, 2);} ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Additional Charges:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($addl_charges_sub_total, 2);} ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Tax:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php if($report_type != '18' && $report_type != '19'){?>$0.00<?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Amount Due:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format(($totalbalancedue+$total_deposits), 2);} ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Deposit(s) and Credit(s):</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($total_deposits, 2);} ?>
                                    </td>
                                </tr>
                                <tr class="td-border">
                                    <td class="text-end">
                                        <strong>Balance Due (USD):</strong>
                                    </td>
                                    <td class="text-end pe-1">
                                        <?php if($report_type != '18' && $report_type != '19'){echo '$'.number_format($totalbalancedue, 2);} ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="cust-invoice-table">
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
    <table class="cust-invoice-table border-top">
        <tr>
            <td style="padding-left: 0">
                <?php echo $reportdata['customers']['customer_name']; ?> or Authorized representative
            </td>
            <td style="padding-right: 0">
                Date: 
            </td>
            
        </tr>
    </table>
    <?php } ?>
</div>