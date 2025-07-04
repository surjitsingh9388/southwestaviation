<style>
    strong, span{
        font-size: 10px;
    }
</style>
<div class="table-container">
    <table class="item-group-table header-table">
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
                            <table>
                                <tr>
                                    <td class="title" style="font-size:15px;">
                                        <?php echo 'Southwest Aviation Specialties, LLC - Phone: (918) 298-4044, Fax: (918) 298-698-6930 - Repair Station #: S30R818N - 8720 Jac Bates Ave, Tulsa, OK 74132';//echo !empty($settings) ? $settings['office_address'] : 'Southwest Aviation Specialties, LLC - Phone: (918) 298-4044, Fax: (918) 298-698-6930 - Repair Station #: S30R818N - 8720 Jac Bates Ave, Tulsa, OK 74132'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-top-db text-center" style="padding-top: 15px;">
                                        <p class="headingTitle">Maintenance Work Order</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="item-group-table table-border">
        <tr style="padding:0px;margin:0px;">
            <td style="padding:0px;margin:0px; width:80%;">
                <table class="item-group-table">
                    <tr>
                        <td style="width:20%;">
                            <strong>A/C Reg. Number:</strong>
                            <span class="d-block"><?php echo $reportdata['aircraft']['aircraft_registration_number']; ?></span>
                        </td>
                        <td style="width:20%;">
                            <strong>A/C SN:</strong>
                            <span class="d-block"><?php echo $reportdata['aircraft']['aircraft_serial']; ?></span>
                        </td>
                        <td style="width:40%;">
                            <table class="item-group-table" style="border:hidden;">
                                <tr>
                                    <td style="border:hidden;font-weight:bold; font-size:10px;">Year</td>
                                    <td style="border:hidden;font-weight:bold; font-size:10px;">Make</td>
                                    <td style="border:hidden;font-weight:bold; font-size:10px;">Model</td>
                                </tr>
                                <tr>
                                    <td style="border:hidden; font-size:10px;"><?php echo $reportdata['aircraft']['aircraft_year']; ?></td>
                                    <td style="border:hidden; font-size:10px;"><?php echo $reportdata['aircraft']['aircraft_make_id']; ?></td>
                                    <td style="border:hidden; font-size:10px;"><?php echo $reportdata['aircraft']['aircraft_model_id']; ?></td>
                                </tr>
                            </table>
                        </td>
                        <td style="width:20%;">
                            <strong>Work Order #:</strong>
                            <span class="d-block"><?php echo $reportdata['work_order_no']; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <strong>Labor Kit of Grouping:</strong>
                            <p>(None)</p>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="padding: 0; width:20%;">
                <table class="item-group-table">
                    <tr>
                        <td style="border:hidden;">
                            <p><strong>A/C Info:</strong></p>
                            <p>AC TT: <?php echo !empty($reportdata['maintenance_overview']['current_ac_tt']) ? $reportdata['maintenance_overview']['current_ac_tt'] : ''; ?></p>
                            <p>AC HOBBS:  <?php echo !empty($reportdata['maintenance_overview']['hobbs']) ? $reportdata['maintenance_overview']['hobbs'] : ''; ?></p>
                            <p>AC TC:  <?php echo !empty($reportdata['maintenance_overview']['actc']) ? $reportdata['maintenance_overview']['actc'] : ''; ?></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
    <table class="item-group-table table-border">
        <tr>
            <td class="border-btm-db" width="5%">
                <strong>Item</strong>
            </td>
            <td class="border-btm-db" width="65%">
                <strong>Discrepancy & Corrective Action</strong>
            </td>
            <td class="border-btm-db" width="10%">
                <strong>Technician</strong>
            </td>
            <td class="border-btm-db" width="10%">
                <strong>Inspector</strong>
            </td>
            <td class="border-btm-db" width="10%">
                <strong>RII</strong>
            </td>
        </tr>
        <?php
        if(!empty($reportdata['wo_item'])){
        foreach($reportdata['wo_item'] as $keys=>$row){
            $inspector = '';
            $technician = '';
            $rii = '';
            foreach($row['signoff_details'] as $signoff){
                if($signoff['signoff_category'] == '1'){
                    $inspector = $signoff['users']['user_initials'];
                }
                if($signoff['signoff_category'] == '13'){
                    $technician = $signoff['users']['user_initials'];
                }
                if($signoff['signoff_category'] == '11'){
                    $rii = $signoff['users']['user_initials'];
                }
            }
        ?>
        <tr>
            <td>
                <?php echo $row['wo_item_position']; ?>
            </td>
            <td>
                <p><?php echo $row['wo_discrepancy']; ?></p>
                <p>&nbsp;</p>
                <p><?php echo $row['wo_corrective_action']; ?></p>
            </td>
            <td>
                <p><?php echo $technician;?></p>
            </td>
            <td>
                <p><?php echo $inspector;?></p>
            </td>
            <td>
                <p><?php echo $rii;?></p>
            </td>
        </tr>
        <?php }} ?>
    </table>
    
    <table class="item-group-table table-border">
        <tr style="padding:0px;margin:0px;">
            <td style="padding:0px;margin:0px; width:80%;">
                <table class="item-group-table">
                    <tr>
                        <td style="width:20%;">
                            <strong>A/C Reg. Number:</strong>
                            <span class="d-block"><?php echo $reportdata['aircraft']['aircraft_registration_number']; ?></span>
                        </td>
                        <td style="width:20%;">
                            <strong>A/C SN:</strong>
                            <span class="d-block"><?php echo $reportdata['aircraft']['aircraft_serial']; ?></span>
                        </td>
                        <td style="width:40%;">
                            <table class="item-group-table" style="border:hidden;">
                                <tr>
                                    <td style="border:hidden;font-weight:bold;">Year</td>
                                    <td style="border:hidden;font-weight:bold;">Make</td>
                                    <td style="border:hidden;font-weight:bold;">Model</td>
                                </tr>
                                <tr>
                                    <td style="border:hidden;"><?php echo $reportdata['aircraft']['aircraft_year']; ?></td>
                                    <td style="border:hidden;"><?php echo $reportdata['aircraft']['aircraft_make_id']; ?></td>
                                    <td style="border:hidden;"><?php echo $reportdata['aircraft']['aircraft_model_id']; ?></td>
                                </tr>
                            </table>
                        </td>
                        <td style="width:20%;">
                            <strong>Work Order #:</strong>
                            <span class="d-block"><?php echo $reportdata['work_order_no']; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                </table>
            </td>
            <td style="padding: 0; width:20%;">
                <table class="item-group-table">
                    <tr>
                        <td style="border:hidden;">
                            <p><strong>A/C Info:</strong></p>
                            <p>AC TT: <?php echo !empty($reportdata['maintenance_overview']['current_ac_tt']) ? $reportdata['maintenance_overview']['current_ac_tt'] : ''; ?></p>
                            <p>AC HOBBS:  <?php echo !empty($reportdata['maintenance_overview']['hobbs']) ? $reportdata['maintenance_overview']['hobbs'] : ''; ?></p>
                            <p>AC TC:  <?php echo !empty($reportdata['maintenance_overview']['actc']) ? $reportdata['maintenance_overview']['actc'] : ''; ?></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="table-body">
        <tr>
            <td class="title text-center">
                Return to Service
            </td>
        </tr>
        <tr>
            <td class="ps-0">
                <strong>Maintenance Release</strong>
                <p>&nbsp;</p>
                <p>
                    The aircraft and/or component(s) on [<?php echo $reportdata['aircraft']['aircraft_registration_number']; ?>] was repaired and inspected in accordance with current FARs rules of the Federal Aviation Regulations and was found Airworthy for return to service with regards to the work performed above. Pertinent details of the repair are on file at this repair station under [*Work Order Words*] No. [<?php echo $reportdata['work_order_no']; ?>] Dated [*Date*]. Southewest Aviation Specialties, L.L.C. FAA CSR# S30R818N.
                </p>
            </td>
        </tr>
        <tr>
            <td class="ps-0 pe-0" style="padding-top: 20px">
                <table>
                    <tr>
                        <td class="ps-0 border-btm-db">
                            <strong>DATE:</strong>
                        </td>
                        <td class="border-btm-db" width="70%">
                            <strong>SIGNED:</strong>
                        </td>
                        <td class="border-btm-db text-end pe-0">
                            <strong><?php echo $reportdata['work_order_no']; ?></strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>