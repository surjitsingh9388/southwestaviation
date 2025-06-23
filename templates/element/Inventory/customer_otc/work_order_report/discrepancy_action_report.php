<table class="main-table" >
    <tr class="main-tr">
        <td class="main-td rm-border">
            <table>
                <tr>
                    <td valign="top" width="30%">
                        <table>
                            <tr>
                                <td>
                                    <?php
                                    $path = WWW_ROOT . 'images' . DS . 'logo.png';
                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $data = file_get_contents($path);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                    ?>    
                                    <img src="<?= $base64 ?>" alt="" style="width: 180px">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top">
                        <table>
                            <tr>
                                <td class="top-header" width="80%">
                                    <p>Southwest Aviation Specialties, LLC - Phone: (918) 298-4044, Fax: (918) 298-6930 - <?php echo date('d/m/Y'); ?>
                                    <br/>
                                    Repair Station #: S30R818N - 8720 Jack Bates Ave, Tulsa, OK 74132
                                    </p>
                                </td>
                                <td width="20%">
                                    <p><b><?php echo 'W/O: '.$reportdata['work_order_no']; ?></b></p>
                                </td>
                            </tr>
                            <tr>
                                <td class="mid-header">
                                    <?php
                                    if($report_type=="24" || $report_type=="25"){
                                        $heading = '<p class="title" style="font-size:16px;">Discrepancy Action Report - W/O: '.$reportdata['work_order_no'].' - '.$reportdata['customers']['customer_name'].' ('.$reportdata['aircraft']['aircraft_registration_number'].')</p>';
                                        if(!empty($reportdata['logbook_value_overviews']['current_ac_tt'])){
                                            $heading .= '<p class="title" style="font-size:16px;">Current A/C TT: '.$reportdata['logbook_value_overviews']['current_ac_tt'].'</p>';
                                        }
                                        if(!empty($reportdata['logbook_value_overviews']['actc'])){
                                            $heading .= '<p class="title" style="font-size:16px;">Tach: '.$reportdata['logbook_value_overviews']['actc'].'</p>';
                                        }
                                        if(!empty($reportdata['aircraft']['aircraft_registration_number'])){
                                            $heading .= '<p class="title" style="font-size:16px;">S/N: '.$reportdata['aircraft']['aircraft_registration_number'].'</p>';
                                        }
                                        echo $heading;
                                    }else if($report_type=="26" || $report_type=="27"){
                                        $heading = '<p class="title" style="font-size:16px;">Discrepancy List - W/O: '.$reportdata['work_order_no'].' - ('.$reportdata['aircraft']['aircraft_registration_number'].')</p>';
                                        if(!empty($reportdata['logbook_value_overviews']['current_ac_tt'])){
                                            $heading .= '<p class="title" style="font-size:16px;">Current A/C TT: '.$reportdata['logbook_value_overviews']['current_ac_tt'].'</p>';
                                        }
                                        if(!empty($reportdata['logbook_value_overviews']['actc'])){
                                            $heading .= '<p class="title" style="font-size:16px;">Tach: '.$reportdata['logbook_value_overviews']['actc'].'</p>';
                                        }
                                        echo $heading;
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    
    <?php
    $aircraftWOCategory = unserialize(AIRCRAFT_WORKORDER_CATEGORY);
    //$woItemSignOff = unserialize(WOITEMSIGNOFF);

    foreach($reportdata['wo_item'] as $woitemdata){
    ?>
    <tr>
        <td>
            <table class="date-group-table">
                <tr>
                    <td><span class="title" style="font-size:16px;"><?php echo 'Item #'.$woitemdata['wo_item_position'].' - '.$aircraftWOCategory[$woitemdata['wo_item_overview']['wo_category']]; ?></span></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table class="rs-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="rs_td"><?php echo $woitemdata['wo_discrepancy']; ?></td>
                </tr>
                
                <?php if(!empty($woitemdata['item_notes'])){ ?>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                <td>Notes:</td>
                </tr>
                <tr>
                    <td><?php echo $woitemdata['item_notes']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </td>
    </tr>

    <?php
    if($report_type!="24" && $report_type!="25"){
        $signoffarr = [];

        foreach($woitemdata['signoff_details'] as $signoff){
            if($signoff['signoff_category'] == '1'){
                $signoffarr['inspector'] = $signoff;
            }else if($signoff['signoff_category'] == '12'){
                $signoffarr['technician'] = $signoff;
            }
        }
    ?>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>
            <table class="rs-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="rs_td">Technician:  <?php echo !empty($signoffarr['technician']['users']['full_name']) ? $signoffarr['technician']['users']['full_name'] : ''; ?></td>
                    <td class="rs_td" align="right">Date: <?php echo !empty($signoffarr['technician']['inspected_date']) ? date('m/d/Y', strtotime($signoffarr['technician']['inspected_date'])) : ''; ?></td>
                </tr>
                <tr>
                    <td class="rs_td">Inspector:  <?php echo !empty($signoffarr['inspector']['users']['full_name']) ? $signoffarr['inspector']['users']['full_name'] : ''; ?></td>
                    <td class="rs_td" align="right">Date: <?php echo !empty($signoffarr['inspector']['inspected_date']) ? date('m/d/Y', strtotime($signoffarr['inspector']['inspected_date'])) : ''; ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
    </tr>
    <?php }} ?>
</table>