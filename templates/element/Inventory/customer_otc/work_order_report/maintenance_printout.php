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
    <?php
    $woItemSignOff = unserialize(WOITEMSIGNOFF);

    if(!empty($reportdata['wo_item'])){
        foreach($reportdata['wo_item'] as $keys=>$row){
            $inspector = '';
            $technician = '';
            $inspection_date = '';

            foreach($row['signoff_details'] as $signoff){
                if($signoff['signoff_category'] == '13'){
                    $technician = $signoff['users']['user_initials'];
                    $inspection_date = date('m/d/Y', strtotime($signoff['inspected_date']));
                }
                if($signoff['signoff_category'] == '1'){
                    $inspector = $signoff['users']['user_initials'];
                    $inspection_date = date('m/d/Y', strtotime($signoff['inspected_date']));
                }
            }
    ?>
    <table class="item-group-table item-group-table-body">
        <tr>
            <td class="p-0">
                <table class="item-group-table table-border">
                    <tr>
                        <td style="width:20%;">
                            <strong>A/C Reg. Number:</strong>
                            <span class="d-block"><?php echo $reportdata['aircraft']['aircraft_registration_number']; ?></span>
                        </td>
                        <td style="width:20%;">
                            <strong>A/C SN:</strong>
                            <span class="d-block"><?php echo $reportdata['aircraft']['aircraft_serial']; ?></span>
                        </td>
                        <td style="width:37%;">
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
                        <td style="width:13%;">
                            <strong>Work Order #:</strong>
                            <span class="d-block"><?php echo $reportdata['work_order_no']; ?></span>
                        </td>
                        <td style="width:10%;">
                            <strong>ACTT:</strong>
                            <span class="d-block">
                                <?php 
                                if($reportdata['aircraft']['aircraft_engine_type'] != '5'){
                                    echo !empty($reportdata['maintenance_overview']['current_ac_tt']) ? $reportdata['maintenance_overview']['current_ac_tt'] : '';
                                }else{
                                    echo !empty($reportdata['maintenance_overview']['ac_tt']) ? $reportdata['maintenance_overview']['ac_tt'] : '';
                                }
                                ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Item:</strong>
                            <span><?php echo $row['wo_item_position']; ?></span>
                        </td>
                        <td>
                            <strong>Date:</strong>
                            <span><?php echo $inspection_date; ?></span>
                        </td>
                        <td colspan="2">
                            <strong>ATA:</strong>
                            <span></span>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php if($report_type == '39'){ ?>
        <tr>
            <td class="ps-0">
                <p><strong>Discrepancy:</strong></p>
                <p><?php echo $row['wo_discrepancy']; ?></p>
            </td>
        </tr>
        <tr>
            <td class="ps-0" style="padding-top: 20px; padding-bottom: 20px;">
                <p><strong>Corrective Action:</strong></p>
                <p><?php echo $row['wo_corrective_action']; ?></p>
            </td>
        </tr>
        <?php } ?>
    </table>
    <?php if($report_type == '40'){ ?>
    <table class="item-group-table item-group-table-body mt-0">
        <tr>
            <td class="p-0">
                <table>
                    <tr>
                        <td class="ps-0">
                            <p><strong>Discrepancy:</strong></p>
                            <p><?php echo $row['wo_discrepancy']; ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-0" style="padding-top: 100px; padding-bottom: 100px;">
                            <p><strong>Corrective Action:</strong></p>
                            <p><?php echo $row['wo_corrective_action']; ?></p>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="border-left p-0" width="35%">
                <table>
                    <tr>
                        <td class="border-btm p-tb-10"><strong>Other Signoffs:</strong></td>
                    </tr>
                    <?php
                    foreach($row['signoff_details'] as $signoff){
                        if(isset($woItemSignOff[$signoff['signoff_category']])){
                    ?>
                    <tr>
                        <td class="p-tb-10">
                            <p><strong><?php echo $woItemSignOff[$signoff['signoff_category']]; ?>:</strong></p>
                            <p><?php echo $signoff['users']['full_name']; ?></p>
                        </td>
                    </tr>
                    <?php }} ?>
                    <!--tr>
                        <td class="p-tb-10">
                            <p><strong>Function Check, as applicable:</strong></p>
                            <p></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-tb-10">
                            <p><strong>Hidden Damage, as applicable:</strong></p>
                            <p></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-tb-10">
                            <p><strong>In Process, as applicable:</strong></p>
                            <p></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-tb-10">
                            <p><strong>Prelin Inspection:</strong></p>
                            <p></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-tb-10">
                            <p><strong>Technician 01:</strong></p>
                            <p></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-tb-10">
                            <p><strong>Technician 02:</strong></p>
                            <p></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-tb-10">
                            <p><strong>Technician 03:</strong></p>
                            <p></p>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-tb-10">
                            <p><strong>Technician 04:</strong></p>
                            <p></p>
                        </td>
                    </tr-->
                </table>
            </td>
        </tr>
    </table>
    <?php 
    } 
    ?>
    <table class="item-group-table item-group-table-body">
        <tr>
            <td class="ps-0" width="50%">
                <table class="item-group-table table-border">
                    <tr>
                        <td>
                            <p><strong>Technician Signature:</strong>&nbsp;<?php echo $technician; ?></p>
                            <p></p>
                        </td>
                        <td>
                            <p><strong>Certification #:</strong></p>
                            <p>S30R818N</p>
                        </td>
                        <td>
                            <p><strong>Date:</strong></p>
                            <p><?php echo $report_date; ?></p>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="pe-0" width="50%">
                <table class="item-group-table table-border">
                    <tr>
                        <td>
                            <p><strong>Inspector Signature:</strong>&nbsp;<?php echo $inspector; ?></p>
                            <p></p>
                        </td>
                        <td>
                            <p><strong>Certification #:</strong></p>
                            <p>S30R818N</p>
                        </td>
                        <td>
                            <p><strong>Date:</strong></p>
                            <p><?php echo $report_date; ?></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <pagebreak />
    <?php }} ?>
</div>