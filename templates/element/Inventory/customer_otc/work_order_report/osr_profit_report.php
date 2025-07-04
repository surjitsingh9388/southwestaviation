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
                                        <img src="<?= $base64 ?>" alt="Logo" class="logo" />
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class="item-group-table">
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td class="title ps-0" style="font-size:15px;">
                                                    Southwest Aviation Specialties, LLC - Phone: (918) 298-4044, Fax: (918) 298-698-6930 - Repair Station #: S30R818N - 8720 Jac Bates Ave, Tulsa, OK 74132
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-top-db p-tb-15 text-center">
                                        <p class="headingTitle">Outside Repair Profit Report - W/O: <?php echo $reportdata['work_order_no']; ?></p>
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
            <td class="ps-0 pe-0">
                <table class="item-group-table">
                    <tr>
                        <th class="title nowrap text-start border-btm-db ps-0" style="padding: 0; font-size:12px;">Item No.</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; font-size:12px;">Repair Done By</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; font-size:12px;">Invoice#</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; font-size:12px;">Labor Cost</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; font-size:12px;">Labor Charge</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; font-size:12px;">Parts Cost</th>
                        <th class="title nowrap text-start border-btm-db" style="padding: 0; font-size:12px;">Parts Charges</th>
                        <th class="title nowrap text-end border-btm-db pe-0" style="padding: 0; font-size:12px;">Total Profit</th>
                    </tr>
                    <?php
                    if(!empty($reportdata['wo_item'])){
                    
                    $total_labor_cost = '0.00';
                    $total_labor_charge = '0.00';
                    $total_parts_cost = '0.00';
                    $total_parts_charge = '0.00';

                    foreach($reportdata['wo_item'] as $keys=>$row){
                        $labor_cost = !empty($row['osr_infoes']['osr_vendor_labor_charges']) ? $row['osr_infoes']['osr_vendor_labor_charges'] : '0.00';
                        $labor_charge = !empty($row['osr_infoes']['osr_labor_charge']) ? $row['osr_infoes']['osr_labor_charge'] : '0.00';
                        $parts_cost = !empty($row['osr_infoes']['osr_vendor_part_charges']) ? $row['osr_infoes']['osr_vendor_part_charges'] : '0.00';
                        $parts_charges = !empty($row['osr_infoes']['osr_parts_charge']) ? $row['osr_infoes']['osr_parts_charge'] : '0.00';
                        
                        $total_profit = ($labor_charge+$parts_charges)-($labor_cost+$parts_cost);

                        $total_labor_cost += $labor_cost;
                        $total_labor_charge += $labor_charge;
                        $total_parts_cost += $parts_cost;
                        $total_parts_charge += $parts_charges;
                    ?>
                    <tr>
                        <td class="border-btm ps-0" style="font-size:12px;"><?php echo $row['wo_item_position']; ?></td>
                        <td class="border-btm" style="font-size:12px;"><?php echo !empty($row['vendors']['vendor_name']) ? $row['vendors']['vendor_name'] : ''; ?></td>
                        <td class="border-btm" style="font-size:12px;"><?php echo !empty($row['osr_infoes']['osr_invoice_no']) ? $row['osr_infoes']['osr_invoice_no'] : ''; ?></td>
                        <td class="border-btm" style="font-size:12px;"><?php echo number_format($labor_cost, 2); ?></td>
                        <td class="border-btm" style="font-size:12px;"><?php echo number_format($labor_charge, 2); ?></td>
                        <td class="border-btm" style="font-size:12px;"><?php echo number_format($parts_cost, 2); ?></td>
                        <td class="border-btm" style="font-size:12px;"><?php echo number_format($parts_charges, 2); ?></td>
                        <td class="border-btm text-end pe-0" style="font-size:12px;"><?php echo number_format($total_profit, 2); ?></td>
                    </tr>
                    <?php }} ?>
                </table>
            </td>
        </tr>
    </table>
    <?php
    if(!empty($reportdata['wo_item'])){
    ?>
    <table>
        <tr style="margin:0px; padding:0px;">
            <td style="width:70%;">&nbsp;</td>
            <td width="30%" style="padding-right: 0">
                <table>
                    <tr style="margin:0px; padding:0px;">
                        <td colspan="2" class="text-end" style=" font-size:12px;" width="70%">
                            <strong>Total Labor (Cost):</strong>
                        </td>
                        <td class="text-end" style="padding-right: 0; font-size:12px;" width="30%">
                            <?php echo '$'.number_format($total_labor_cost, 2); ?>
                        </td>
                    </tr>
                    <tr style="margin:0px; padding:0px;">
                        <td colspan="2" class="text-end" style="margin:0px; padding:0px; width:70%; font-size:12px;">
                            <strong>Total Labor (Charge):</strong>
                        </td>
                        <td class="text-end" style="margin:0px; padding:0px; font-size:12px;">
                            <?php echo '$'.number_format($total_labor_charge, 2); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end" style="font-size:12px;">
                            <strong>Total Labor Profit:</strong>
                        </td>
                        <td class="text-end pe-0" style="padding-right: 0; font-size:12px;">
                            <?php
                            $total_labor_profit = '0.00';
                            if(!empty($total_labor_cost) || !empty($total_labor_charge)){
                                $total_labor_profit = $total_labor_charge - $total_labor_cost;
                            } 
                            echo '$'.number_format($total_labor_profit, 2); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end" style="padding-top: 15px; font-size:12px;">
                            <strong>Total Parts (Cost):</strong>
                        </td>
                        <td class="text-end" style="padding-top: 15px; padding-right: 0; font-size:12px;">
                            <?php echo '$'.number_format($total_parts_cost, 2); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end" style="font-size:12px;">
                            <strong>Total Parts (Charge):</strong>
                        </td>
                        <td class="text-end pe-0" style="padding-right: 0; font-size:12px;">
                            <?php echo '$'.number_format($total_parts_charge, 2); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-end  pe-0" style="font-size:12px;">
                            <strong>Total Parts Profit:</strong>
                        </td>
                        <td class="text-end" style="padding-right: 0; font-size:12px;">
                            <?php
                            $total_parts_profit = '0.00';
                            if(!empty($total_parts_cost) || !empty($total_parts_charge)){
                                $total_parts_profit = $total_parts_charge - $total_parts_cost;
                            } 
                            echo '$'.number_format($total_parts_profit, 2); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="title border-top text-end" style="font-size:12px;">
                            <strong>Total Profit:</strong>
                        </td>
                        <td class="border-top text-end pe-0" style="font-size:12px;">
                            <?php echo '$'.number_format(($total_labor_profit+$total_parts_profit), 2); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <?php } ?>
</div>