<div class="table-container">
    <table class="cust-estimates-table">
        <tr>
            <td width="60%" class="pb-0 ps-0">
                <table class="cust-estimates-table">
                    <tr>
                        <td width="30%" style="vertical-align: top;">
                            <?php
                            $path = WWW_ROOT . 'images' . DS . 'logo.png';
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            ?>
                            <img src="<?= $base64 ?>" alt="Logo" />
                        </td>
                        <td width="70%" style="vertical-align: top;">
                            <p style="font-size:18px;"><strong>Southwest Aviation Specialties, LLC</strong></p>
                            <p style="font-size:16px;">Repair Station No. S30R818N</p>
                            <p style="font-size:16px;">8720 Jack Bates Ave</p>
                            <p style="font-size:16px;">Tusla, OK 74132</p>
                            <p style="font-size:16px;">Phone: (918)298-4044</p>
                            <p style="font-size:16px;">Fax: (918)298-6930</p>
                            <p style="font-size:16px;">Date: <?php echo date('m/d/Y, h:i A'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <table class="cust-estimates-table">
                    <tr>
                        <td class="text-center td-border">
                            <p class="title" style="text-decoration: underline;"><?php echo $report_type == '71' ? 'Warranty Estimate' : 'Customer Estimate'; ?></p>
                            <p class="title">Work Order: 
                                <?php 
                                $woItemOverviewWarranty = unserialize(WOITEMOVERVIEWWARRANTY);
                                echo $report_type == '71' ? $reportdata['work_order_no'].'-'.$woItemOverviewWarranty[$reportdata['wo_item'][0]['wo_item_overview']['warranty']] : $reportdata['work_order_no']; 
                                ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="40%" class="td-border" style="padding: 0;">
                <table class="cust-estimates-table">
                    <tr>
                        <td class="td-bg title">
                            CUSTOMER INFORMATION
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
                            if(!empty($reportdata['logbook_value_overviews']['current_ac_tt'])){
                                $reportHtml .= '<p style="font-size:16px;">Current A/C TT: '.$reportdata['logbook_value_overviews']['current_ac_tt'].'</p>';
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
    if(!empty($reportdata['wo_item'])){
    
    $aircraftWOCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);
    $estimated_rate_static = ESTIMATEDRATE;
    $final_labor_charges = 0;
    $final_parts_charges = 0;
    $final_shipping_charges = 0;
    $final_additional_charges = 0;
    $final_estimate_before_deposit = 0;
    $final_deposits_credits = 0;

    $total_hour = 0;
    
    foreach($reportdata['wo_item'] as $keys=>$row){
        if(!empty($reportdata['warranty_infoes']['pay_labor']) || empty($row['wo_item_overview']['item_is_warranty'])){
            if($row['wo_item_overview']['way_of_billing'] == '1'){
                if(!empty($row['wo_item_overview']['special_hourly_rate_for_item'])){
                    $estimated_rate = !empty($row['wo_item_overview']['special_rate_hr']) ? $row['wo_item_overview']['special_rate_hr'] : '0.00';
                }else{
                    $estimated_rate = !empty($row['wo_item_overview']['estimated_rate']) && $row['wo_item_overview']['estimated_rate'] != '0.00' ? $row['wo_item_overview']['estimated_rate'] : $estimated_rate_static;
                    
                }
            }else if($row['wo_item_overview']['way_of_billing'] == '2'){
                $estimated_rate = !empty($row['wo_item_overview']['flat_rate']) ? ($row['wo_item_overview']['flat_rate']*$row['wo_item_overview']['flat_rate_qty']) : '0.00';
            }else{
                $estimated_rate = '0.00';
            }
        }else{
            $estimated_rate = '0.00';
        }
        
        $estimated_hour = !empty($row['wo_item_overview']['estimated_hour']) ? $row['wo_item_overview']['estimated_hour'] : '0.00';

        $total_hour += $estimated_hour;

        $subtotal = $estimated_rate*$estimated_hour;
    ?>
    <table class="cust-estimates-table-body">
        <tr>
            <td style="padding-left: 0; padding-right: 0">
                <table class="cust-estimates-table">
                    <tr>
                        <td style="width:60%; padding-left: 0">
                            <table style="width:100%;">
                                <tr>
                                    <td class="td-border" style="text-align:center; width:95px;">
                                        <p>Authorize</p>
                                        <p style="text-align: left;">
                                            <?php
                                            /*$owner_authentication = '';
                                            if($row['wo_item_overview']['owner_authentication'] == '1'){ 
                                                $owner_authentication = 'Open';
                                            }else if($row['wo_item_overview']['owner_authentication'] == '2'){ 
                                                $owner_authentication = 'Yes';
                                            }else{ 
                                                $owner_authentication = 'No';
                                            }*/
                                            ?>
                                            <input type="checkbox" <?php if($row['wo_item_overview']['owner_authentication'] == '2'){ ?>checked="checked"<?php } ?> />&nbsp;Yes&nbsp;&nbsp;
											<input type="checkbox" <?php if($row['wo_item_overview']['owner_authentication'] == '3'){ ?>checked="checked"<?php } ?> />&nbsp;No
                                        </p>
                                    </td>
                                    <td style="text-align:right; font-size:12px;">
                                        <strong>Item:<?php echo $row['wo_item_position']; ?> - <?php echo $aircraftWOCategory[$row['wo_item_overview']['wo_category']]; ?></strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width:40%;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 0; width:60%;">
                            <p style="font-size:12px;"><strong>Discrepancy</strong></p>
                            <p style="font-size:12px;"><?php echo $row['wo_discrepancy']; ?></p>
                            <?php if($report_type == '9' && !empty($row['item_notes'])){ ?>
                            <p style="font-size:12px;"><strong>Notes</strong></p>
                            <p style="font-size:12px;"><?php echo $row['item_notes']; ?></p>
                            <?php
                            } 

                            $total_labor_charges = '0.00';
                            $total_part_charges = '0.00';
                            $total_shipping_charges = '0.00';
                            $total_item_part_charges = '0.00';
                            
                            if(!empty($row['wo_osr_info'])){ 
                                if($report_type != '6'){
                            ?>
                            <p style="font-size:12px;"><strong>Outside repair</strong></p>
                            <?php
                                }
                            foreach($row['wo_osr_info'] as $osrinfo){
                                $labor_charge = !empty($osrinfo['osr_labor_charge']) ? $osrinfo['osr_labor_charge'] : '0.00';
                                $parts_charge = !empty($osrinfo['osr_parts_charge']) ? $osrinfo['osr_parts_charge'] : '0.00';
                                
                                if(!empty($reportdata['warranty_infoes']['pay_labor']) || empty($row['wo_item_overview']['item_is_warranty'])){
                                    $total_labor_charges += $labor_charge;
                                }
                                if(!empty($reportdata['warranty_infoes']['pay_parts']) || empty($row['wo_item_overview']['item_is_warranty'])){
                                    $total_part_charges += $parts_charge;
                                }
                                if(!empty($reportdata['warranty_infoes']['pay_shipping']) || empty($row['wo_item_overview']['item_is_warranty'])){
                                    $total_shipping_charges += ($osrinfo['osr_shipping_out']+$osrinfo['osr_shipping_in']);
                                }
                                if($report_type != '6'){
                            ?>
                            <p style="font-size:12px;">
                                <span style="padding: 5px 10px 0 0">Labor: <?php echo '$'.number_format((float)$labor_charge, 2); ?></span>
                                <span style="padding: 5px 10px 0 0">Parts: <?php echo '$'.number_format((float)$parts_charge, 2); ?></span>
                                <?php if($report_type != '8'){ ?>
                                <span style="padding: 5px 10px 0 0">Parts No: <?php echo $osrinfo['osr_part_number']; ?></span>
                                <?php } ?>
                            </p>
                            <?php 
                                }
                            }} 
                            if(!empty($reportdata['warranty_infoes']['pay_shipping']) || empty($row['wo_item_overview']['item_is_warranty'])){
                                $total_shipping_charges += $row['wo_item_overview']['shipping_in'];
                            }
                            ?>
                        </td>
                        <td class="text-end" style="padding-right: 0; width:40%;">
                            <table class="value-table">
                                <tr>
                                    <td width="150px">
                                        <p style="font-size:12px;"><strong>Hours</strong></p>
                                        <p style="font-size:12px;">
                                            <strong>
                                                <?php 
                                                if($row['wo_item_overview']['way_of_billing'] == '1'){
                                                    echo !empty($row['wo_item_overview']['estimated_hour']) ? number_format((float)$row['wo_item_overview']['estimated_hour'], 2) : '0.00';
                                                }else if($row['wo_item_overview']['way_of_billing'] == '2'){
                                                    echo 'Flat';
                                                }else{
                                                    echo 'N/C';
                                                }
                                                
                                                ?>
                                            </strong>
                                        </p>
                                    </td>
                                    <td width="150px">
                                        <p style="font-size:12px;"><strong>Subtotal</strong></p>
                                        <p style="font-size:12px;"><strong><?php echo '$'.number_format((float)$subtotal, 2); ?></strong></p>
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
                                    <th style="text-align:left;">Qty</th>
                                    <th style="text-align:left;">Unit</th>
                                    <th style="text-align:left;">Part Number</th>
                                    <th style="text-align:left;">Description</th>
                                    <th style="text-align:left;">Serial Number</th>
                                    <th style="text-align:left;">Unit Price</th>
                                    <th style="text-align:left;">Subtotal</th>
                                </tr>
                                <?php 
                                foreach($row['part_details'] as $partdet){ 
                                    $price_each = '0.00';
                                    $part_total_prices = '0.00';
                                    if(!empty($reportdata['warranty_infoes']['pay_parts']) || empty($row['wo_item_overview']['item_is_warranty'])){
                                        $total_part_charges += $partdet['part_total_prices'];  
                                        $price_each = $partdet['price_each'];
                                        $part_total_prices = $partdet['part_total_prices'];
                                    }  
                                ?>
                                <tr>
                                    <td style="text-align:left;"><?php echo $partdet['qty_used']; ?></td>
                                    <td style="text-align:left;"><?php echo 'EA'; ?></td>
                                    <td style="text-align:left;"><?php echo $partdet['part_number']; ?></td>
                                    <td style="text-align:left;"><?php echo $partdet['part_description']; ?></td>
                                    <td style="text-align:left;"><?php echo $partdet['serial_number']; ?></td>
                                    <td style="text-align:left;"><?php echo $price_each; ?></td>
                                    <td style="text-align:left;"><?php echo $part_total_prices; ?></td>
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
    <table class="cust-estimates-table cust-estimates-table-body td-bg">
        <tr>
            <td width="23%" style="text-align:left;">
                <strong>Labor: <?php echo '$'.number_format(($total_labor_charges+$subtotal), 2); ?></strong>
            </td>
            <td width="23%" style="text-align:left;">
                <strong>Parts: <?php echo '$'.number_format((float)$total_part_charges, 2); ?></strong>
            </td>
            <td width="23%" style="text-align:left;">
                <strong>Shipping: <?php echo '$'.number_format((float)$total_shipping_charges, 2); ?></strong>
            </td>
            <td style="text-align:left;">
                <strong>Item Subtotal: <?php echo '$'.number_format(($total_labor_charges+$subtotal+$total_shipping_charges+$total_part_charges), 2); ?></strong>
            </td>
        </tr>
    </table>
    <?php 
    $final_labor_charges += ($total_labor_charges+$subtotal);
    $final_parts_charges += $total_part_charges;
    $final_shipping_charges += $total_shipping_charges;
    
    }
    $pilot_services_amount = !empty($reportdata['wo_misc_charges']['pilot_services_amount']) ? $reportdata['wo_misc_charges']['pilot_services_amount'] : '0.00';
    $amount_per_tire = !empty($reportdata['wo_misc_charges']['amount_per_tire']) ? $reportdata['wo_misc_charges']['amount_per_tire']* $reportdata['wo_misc_charges']['tire'] : '0.00';
    $epa_charge_amount = !empty($reportdata['wo_misc_charges']['epa_charge_amount']) ? $reportdata['wo_misc_charges']['epa_charge_amount']*2 : '0.00';
    $oil_analysis_amount = !empty($reportdata['wo_misc_charges']['oil_analysis_amount']) ? $reportdata['wo_misc_charges']['oil_analysis_amount']*2 : '0.00';
    $shop_supplies_amount = !empty($reportdata['wo_misc_charges']['shop_supplies_amount']) && !empty($reportdata['wo_misc_charges']['shop_supplies_method']) && $reportdata['wo_misc_charges']['shop_supplies_method'] == '1' ? $reportdata['wo_misc_charges']['shop_supplies_amount'] : '0.00';
    $fuel_amount = !empty($reportdata['wo_misc_charges']['totalfuelcharges']) ? $reportdata['wo_misc_charges']['totalfuelcharges'] : '0';
    $mis_charge_amount = !empty($reportdata['wo_misc_charges']['mis_charge_amount']) ? $reportdata['wo_misc_charges']['mis_charge_amount'] : '0.00';

    $addl_charges_sub_total = $pilot_services_amount+$amount_per_tire+$epa_charge_amount+$oil_analysis_amount+$shop_supplies_amount+$fuel_amount+$mis_charge_amount;
    ?>
    <table class="cust-estimates-table cust-estimates-table-body td-border">
        <tr>
            <td colspan="7">
                <strong>Additional Charges</strong>
            </td>
        </tr>
    
        <tr>
            <td>
                <p>Pilot Services</p>
                <p><?php echo '$'.number_format((float)$pilot_services_amount, 2); ?></p>
            </td>
            <td>
                <p>Tire Disposal</p>
                <p><?php echo '$'.number_format((float)$amount_per_tire, 2); ?></p>
            </td>
            <td>
                <p>EPA Charge</p>
                <p><?php echo '$'.number_format((float)$epa_charge_amount, 2); ?></p>
            </td>
            <td>
                <p>Oil Analysis</p>
                <p><?php echo '$'.number_format((float)$oil_analysis_amount, 2); ?></p>
            </td>
            <td>
                <p>Shop Supplies</p>
                <p><?php echo '$'.number_format((float)$shop_supplies_amount, 2); ?></p>
            </td>
            <td>
                <p>Fuel</p>
                <p><?php echo '$'.number_format((float)$fuel_amount, 2); ?></p>
            </td>
            <td>
                <p>Misc.</p>
                <p><?php echo '$'.number_format((float)$mis_charge_amount, 2); ?></p>
            </td>
            <td class="text-end">
                <p><strong>Subtotal<strong></p>
                <p><strong><?php echo (!empty($addl_charges_sub_total) ? '$'.number_format((float)$addl_charges_sub_total, 2) : '$0.00'); ?></strong></p>
            </td>
        </tr>
    </table>
    <table class="cust-estimates-table cust-estimates-table-body">
        <tr>
            <td style="padding-left: 0; padding-right: 0">
                <table class="cust-estimates-table">
                    <tr>
                        <td width="60%" style="padding-left: 0">
                            <strong>It will take an estimated <?php echo number_format((float)$total_hour, 2); ?> hours to complete the necessary work.</strong>
                        </td>
                        <td width="40%" style="padding-right: 0">
                            <table class="cust-estimates-table">
                                <tr>
                                    <td class="text-end">
                                        <strong>Total Labor:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <strong><?php echo '$'.number_format((float)$final_labor_charges, 2); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Total Parts:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <strong><?php echo '$'.number_format((float)$final_parts_charges, 2); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Total Shipping:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <strong><?php echo '$'.number_format((float)$final_shipping_charges, 2); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Additional Charges:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <strong><?php echo '$'.number_format((float)$addl_charges_sub_total, 2); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Tax:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <strong>$0.00</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end">
                                        <strong>Total Estimate Before Deposit:</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <strong>
                                            <?php 
                                            $total_estimated_before_deposit = $final_labor_charges+$addl_charges_sub_total+$final_parts_charges+$final_shipping_charges;
                                            echo '$'.number_format((float)$total_estimated_before_deposit, 2); ?>
                                        </strong>
                                    </td>
                                </tr>
                                <?php
                                $total_deposits = 0;
                                foreach($reportdata['wo_geninfo_deposits'] as $deposits){
                                    $total_deposits += $deposits['amount_to_add'];
                                }
                                ?>
                                <tr>
                                    <td class="text-end">
                                        <strong>Deposit(s) and Credit(s):</strong>
                                    </td>
                                    <td class="text-end" style="padding-right: 0">
                                        <strong><?php echo '$'.number_format((float)$total_deposits, 2); ?></strong>
                                    </td>
                                </tr>
                                <tr class="td-border">
                                    <td class="text-end">
                                        <strong>Total Estimate (USD):</strong>
                                    </td>
                                    <td class="text-end pe-1">
                                        <strong><?php echo '$'.number_format(($total_estimated_before_deposit-$total_deposits), 2); ?></strong>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="cust-estimates-table-body cust-estimates-table">
        <tr>
            <td class="text-center impInfo">
                <strong>Important information</strong>
            </td>
        </tr>
    </table>
    <table class="cust-estimates-table-body cust-estimates-table border-btm-solid">
        <tr>
            <td style="padding-left: 0">
                <?php echo $reportdata['customers']['customer_name']; ?> or Authorized representative
            </td>
            <td style="padding-right: 0">
                Date: 
            </td>
        </tr>
    </table>
    <table class="cust-estimates-table">
        <tr>
            <td>
                <strong>Credit Cards Information:</strong>
                <p>
                    <span style="padding: 5px 10px 0 0">American Express</span>
                    <span style="padding: 5px 10px 0 0">Discover</span>
                    <span style="padding: 5px 10px 0 0">Master Card</span>
                    <span style="padding: 5px 10px 0 0">Visa</span>
                </p>
            </td>
        </tr>
    
        <tr>
            <td class="text-end" style="padding-left: 0; padding-right: 0">
                <div class="total-amount">
                    <strong class="border-top">Amount Authorized:</strong>
                </div>
            </td>
        </tr>
    </table>
    <table class="cust-estimates-table-body cust-estimates-table border-top">
        <tr>
            <td width="33%" style="text-align:left;">Card#:</td>
            <td width="33%" class="text-end" style="text-align:left;">Security Code:</td>
            <td width="33%" class="text-end" style="text-align:left;">Expiration:</td>
        </tr>
    </table>
    <table class="cust-estimates-table cust-estimates-table-body border-top">
        <tr>
            <td width="33%" class="ps-0" style="text-align:left;">Name on Card (Hand Written):</td>
            <td width="33%" style="text-align:left;">Signature:</td>
        </tr>
    </table>
    <?php } ?>
</div>