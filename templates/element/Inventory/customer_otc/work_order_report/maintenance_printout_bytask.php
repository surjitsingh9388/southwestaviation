<style>
    strong{
        font-size: 10px !important;
    }
    span{
        font-size: 10px !important;
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
        <tr>
            <td width="15%">
                <strong>A/C Reg. Number:</strong>
                <span class="d-block"><?php echo $reportdata['aircraft']['aircraft_registration_number']; ?></span>
            </td>
            <td width="15%">
                <strong>A/C SN:</strong>
                <span class="d-block"><?php echo $reportdata['aircraft']['aircraft_serial']; ?></span>
            </td>
            <td width="30%">
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
            <td width="20%">
                <strong>Work Order #:</strong>
                <span class="d-block"><?php echo $reportdata['work_order_no']; ?></span>
            </td>
            <td width="20%">
                <strong>Labour Kit or Grouping:</strong>
                <span class="d-block">None</span>
            </td>
        </tr>
    </table>

    <table class="item-group-table table-border">
        <tr>
            <td width="15%">
                <strong>Other Sigoffs</strong>
            </td>
            <td>
                <strong>Technician</strong>
            </td>
            <td>
                <strong>Inspector</strong>
            </td>
            <td>
                <strong>Other Sigoffs</strong>
            </td>
            <td>
                <strong>Technician</strong>
            </td>
            <td>
                <strong>Inspector</strong>
            </td>
            <td>
                <strong>Other Sigoffs</strong>
            </td>
            <td>
                <strong>Technician</strong>
            </td>
            <td>
                <strong>Inspector</strong>
            </td>
            <td>
                <strong>Other Sigoffs</strong>
            </td>
            <td>
                <strong>Technician</strong>
            </td>
            <td>
                <strong>Inspector</strong>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <table class="item-group-table table-border">
        <tr>
            <td class="border-btm-db">
                <strong>Item</strong>
            </td>
            <td class="border-btm-db" width="65.7%">
                <strong>Discrepancy & Corrective Action</strong>
            </td>
            <td class="border-btm-db">
                <strong>Technician</strong>
            </td>
            <td class="border-btm-db">
                <strong>Inspector</strong>
            </td>
        </tr>
        <?php
        $report_date = date("m/d/Y");
        if(!empty($reportdata['wo_item'])){
        foreach($reportdata['wo_item'] as $keys=>$row){
            $inspector = '';
            $technician = '';
            foreach($row['signoff_details'] as $signoff){
                if($signoff['signoff_category'] == '1'){
                    $inspector = $signoff['users']['user_initials'];
                }
                if($signoff['signoff_category'] == '13'){
                    $technician = $signoff['users']['user_initials'];
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
                <p><?php echo $technician; ?></p>
            </td>
            <td>
                <p><?php echo $inspector; ?></p>
            </td>
        </tr>
        <?php }} ?>
    </table>
    <?php
    $inspector = '';
    $technician = '';
    $rii = '';
    
    ?>
    <table class="item-group-table" style="margin-top: 100px;">
        <tr>
            <td class="ps-0" style="padding-left: 0; width:50%;">
                <table class="item-group-table table-border">
                    <tr>
                        <td width="34%">
                            <p><strong>Technician Signature:</strong>&nbsp;<?php echo $technician; ?></p>
                        </td>
                        <td width="33%">
                            <p><strong>Certification #:</strong></p>
                            <p>S30R818N</p>
                        </td>
                        <td width="33%">
                            <p><strong>Date:</strong></p>
                            <p><?php echo $report_date; ?></p>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="pe-0" style="padding-right: 0; width:50%;">
                <table class="item-group-table table-border">
                    <tr>
                        <td width="34%">
                            <p><strong>Inspector Signature:</strong>&nbsp;<?php echo $inspector; ?></p>
                            <p></p>
                        </td>
                        <td width="33%">
                            <p><strong>Certification #:</strong></p>
                            <p>S30R818N</p>
                        </td>
                        <td width="33%">
                            <p><strong>Date:</strong></p>
                            <p><?php echo $report_date; ?></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="item-group-table table-border">
        <tr>
            <td width="33.2%">
                <p><strong>RII Signature:</strong>&nbsp;<?php echo $rii; ?></p> 
            </td>
            <td width="34.1%">
                <p><strong>Certification #:</strong></p>
                <p>S30R818N</p>
            </td>
            <td>
                <p><strong>Date:</strong></p> 
                <p><?php echo $report_date; ?></p>
            </td>
        </tr>
    </table>
</div>