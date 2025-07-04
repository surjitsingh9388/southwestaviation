<style>
    .table-container table, p{
        width: 100%;
        font-size: 12px;
    }
</style>
<div class="table-container">
    <table class="header-table" style="min-height:100px;">
        <tr>
            <td style="vertical-align: top;">
                <?php 
                $aircraftmaintenance = $reportdata['aircraftmaintenance'];

                $use_today_date = '';
                if(!empty($postData['date_option']) && $postData['date_option'] == '1'){ 
                    $use_today_date = date('m/d/Y'); 
                }else{ 
                    $use_today_date = date('m/d/Y', strtotime($postData['use_today_date'])); 
                }
                
                if(!empty($postData['log_book_category']) && ($postData['log_book_category'] == '1' || $postData['log_book_category'] == '2')){ 
                ?>
                    <p>MAKE: <?php echo !empty($reportdata['aircraft']['aircraft_make_id']) ? $reportdata['aircraft']['aircraft_make_id'] : ''; ?></p>
                    <p>MODEL: <?php echo !empty($reportdata['aircraft']['aircraft_model_id']) ? $reportdata['aircraft']['aircraft_model_id'] : ''; ?></p>
                    <p>S/N: <?php echo !empty($reportdata['aircraft']['aircraft_serial']) ? $reportdata['aircraft']['aircraft_serial'] : ''; ?></p>
                    <p>REG. NO: <?php echo !empty($reportdata['aircraft']['aircraft_registration_number']) ? $reportdata['aircraft']['aircraft_registration_number'] : ''; ?></p>
                    <p>WORK ORDER: <?php echo $reportdata['work_order_no']; ?></p>

                <?php }else if(!empty($postData['log_book_category']) && ($postData['log_book_category'] == '3' || $postData['log_book_category'] == '7' || $postData['log_book_category'] == '4' || $postData['log_book_category'] == '8') && (!empty($reportdata['aircraft']['aircraft_engine_type']) && $reportdata['aircraft']['aircraft_engine_type'] != '5')){ 
                    $engines = '';
                    if($postData['log_book_category'] == '3' || $postData['log_book_category'] == '4'){
                        $engines = '(#1)';
                        if($reportdata['aircraft']['aircraft_engine_type'] == '1' || $reportdata['aircraft']['aircraft_engine_type'] == '2'){
                            $engine_model = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['model_no']) ? $aircraftmaintenance['aircraftmaintengine']['model_no'] : '';
                            $engine_sn = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['serial_no']) ? $aircraftmaintenance['aircraftmaintengine']['serial_no'] : '';
                        }else{
                            $engine_model = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['engine1_model_no']) ? $aircraftmaintenance['aircraftmaintengine']['engine1_model_no'] : '';
                            $engine_sn = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['engine1_serial_no']) ? $aircraftmaintenance['aircraftmaintengine']['engine1_serial_no'] : '';
                        }
                    }else if($postData['log_book_category'] == '7' || $postData['log_book_category'] == '8'){
                        $engines = '(#2)';
                        if($reportdata['aircraft']['aircraft_engine_type'] == '1' || $reportdata['aircraft']['aircraft_engine_type'] == '2'){
                            $engine_model = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['model_no_r']) ? $aircraftmaintenance['aircraftmaintengine']['model_no_r'] : '';
                            $engine_sn = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['serial_no_r']) ? $aircraftmaintenance['aircraftmaintengine']['serial_no_r'] : '';
                        }else{
                            $engine_model = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['engine2_model_no']) ? $aircraftmaintenance['aircraftmaintengine']['engine2_model_no'] : '';
                            $engine_sn = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['engine2_serial_no']) ? $aircraftmaintenance['aircraftmaintengine']['engine2_serial_no'] : '';
                        }
                    }
                    
                    ?>
                    <p>ENGINE MODEL <?php echo $engines.' : '.$engine_model; ?></p>
                    <p>ENGINE S/N <?php echo $engines.' : '.$engine_sn; ?></p>
                    <p>REG. NO: <?php echo !empty($reportdata['aircraft']['aircraft_registration_number']) ? $reportdata['aircraft']['aircraft_registration_number'] : ''; ?></p>
                    <p>WORK ORDER: <?php echo $reportdata['work_order_no']; ?></p>

                <?php }else if(!empty($postData['log_book_category']) && ($postData['log_book_category'] == '5' || $postData['log_book_category'] == '9' || $postData['log_book_category'] == '6' || $postData['log_book_category'] == '10')){ 
                    $prop = '';
                    if($postData['log_book_category'] == '5' || $postData['log_book_category'] == '6'){
                        $prop = '(#1)';
                        $prop_model = !empty($aircraftmaintenance['aircraftmaintprops']) && !empty($aircraftmaintenance['aircraftmaintprops']['p_model_no_l']) ? $aircraftmaintenance['aircraftmaintprops']['p_model_no_l'] : '';
                        $prop_sn = !empty($aircraftmaintenance['aircraftmaintprops']) && !empty($aircraftmaintenance['aircraftmaintprops']['p_serial_no_l']) ? $aircraftmaintenance['aircraftmaintprops']['p_serial_no_l'] : '';
                    }else if($postData['log_book_category'] == '9' || $postData['log_book_category'] == '10'){
                        $prop = '(#2)';
                        $prop_model = !empty($aircraftmaintenance['aircraftmaintprops']) && !empty($aircraftmaintenance['aircraftmaintprops']['p_model_no_r']) ? $aircraftmaintenance['aircraftmaintprops']['p_model_no_r'] : '';
                        $prop_sn = !empty($aircraftmaintenance['aircraftmaintprops']) && !empty($aircraftmaintenance['aircraftmaintprops']['p_serial_no_r']) ? $aircraftmaintenance['aircraftmaintprops']['p_serial_no_r'] : '';
                    }
                ?>
                    <p>PROP MODEL <?php echo $prop.' : '.$prop_model; ?></p>
                    <p>PROP S/N <?php echo $prop.' : '.$prop_sn; ?></p>
                    <p>REG. NO: <?php echo !empty($reportdata['aircraft']['aircraft_registration_number']) ? $reportdata['aircraft']['aircraft_registration_number'] : ''; ?></p>
                    <p>WORK ORDER: <?php echo $reportdata['work_order_no']; ?></p>
                <?php } ?>
            </td>
            <td style="width:100px; vertical-align: top;">
                <p>
                    <?php
                    $path = WWW_ROOT . 'images' . DS . 'logo.png';
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    ?>
                    <img src="<?= $base64 ?>" alt="Swas" class="logo" style="width:100px; height:50px;" /> 
                </p>
            </td>
            <td style="vertical-align: top;">
                <p><strong>Southwest Aviation Specialties, LLC</strong></p>
                <p>8720 Jack Bates Ave</p>
                <p>Tulsa, OK 74132</p>
                <p>Phone: (918)298-4044</p>
            </td>
            <td style="vertical-align: top;">
                <?php if(!empty($postData['log_book_category']) && ($postData['log_book_category'] == '1' || $postData['log_book_category'] == '2')){ ?>
                    <p>DATE: <?php echo !empty($use_today_date) ? $use_today_date : '___/___/____'; ?></p>
                    <p>A/C TSN: <?php echo !empty($aircraftmaintenance['aircraftmaintoverview']['current_ac_tt']) ? $aircraftmaintenance['aircraftmaintoverview']['current_ac_tt'] : ''; ?></p>
                    <?php
                    $use_hobbs = '0';
                    if(!empty($reportdata['aircraft']['aircraft_engine_type']) && $reportdata['aircraft']['aircraft_engine_type'] != '5'){
                        if(!empty($aircraftmaintenance['aircraftmaintoverview']['use_hobbs']) && !empty($aircraftmaintenance['aircraftmaintoverview']['hobbs'])){
                    ?>
                    <p>HOBBS: <?php echo !empty($aircraftmaintenance['aircraftmaintoverview']['hobbs']) ? $aircraftmaintenance['aircraftmaintoverview']['hobbs'] : ''; ?></p>
                    <?php
                        }
                    }else if(!empty($aircraftmaintenance['aircraftmaintoverview']['hobbs'])){
                    ?>
                    <p>HOBBS: <?php echo !empty($aircraftmaintenance['aircraftmaintoverview']['hobbs']) ? $aircraftmaintenance['aircraftmaintoverview']['hobbs'] : ''; ?></p>
                    <?php } ?>
                    <p>Landings: <?php echo !empty($aircraftmaintenance['aircraftmaintoverview']['airframe_lndgs']) ? $aircraftmaintenance['aircraftmaintoverview']['airframe_lndgs'] : ''; ?></p>
                <?php 
                }else{
                    if(!empty($postData['log_book_category']) && ($postData['log_book_category'] == '3' || $postData['log_book_category'] == '7' || $postData['log_book_category'] == '4' || $postData['log_book_category'] == '8') && (!empty($reportdata['aircraft']['aircraft_engine_type']) && $reportdata['aircraft']['aircraft_engine_type'] != '5')){
                        $engines = '';
                        $engtt = '';
                        $engtc = '';
                        $engtso = '';
                        $engtcso = '';
                        $engtsmoh = '';

                        if($postData['log_book_category'] == '3' || $postData['log_book_category'] == '4'){
                            $engines = '(#1)';
                            if($reportdata['aircraft']['aircraft_engine_type'] == '1' || $reportdata['aircraft']['aircraft_engine_type'] == '2'){
                                $engtt = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['ttl']) ? $aircraftmaintenance['aircraftmaintengine']['ttl'] : '';
                                $engtsmoh = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['tsmoh']) ? $aircraftmaintenance['aircraftmaintengine']['tsmoh'] : '';
                            }else{
                                $engtt = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['engine1_tt']) ? $aircraftmaintenance['aircraftmaintengine']['engine1_tt'] : '';
                                $engtc = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['engine1_tc']) ? $aircraftmaintenance['aircraftmaintengine']['engine1_tc'] : '';
                                $engtso = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['engine1_tso']) ? $aircraftmaintenance['aircraftmaintengine']['engine1_tso'] : '';
                                $engtcso = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['engine1_tcso']) ? $aircraftmaintenance['aircraftmaintengine']['engine1_tcso'] : '';
                            }
                        }else if($postData['log_book_category'] == '7' || $postData['log_book_category'] == '8'){
                            $engines = '(#2)';
                            if($reportdata['aircraft']['aircraft_engine_type'] == '1' || $reportdata['aircraft']['aircraft_engine_type'] == '2'){
                                $engtt = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['tt_r']) ? $aircraftmaintenance['aircraftmaintengine']['tt_r'] : '';
                                $engtsmoh = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['tsmoh_r']) ? $aircraftmaintenance['aircraftmaintengine']['tsmoh_r'] : '';
                            }else{
                                $engtt = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['engine2_tt']) ? $aircraftmaintenance['aircraftmaintengine']['engine2_tt'] : '';
                                $engtc = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['engine2_tc']) ? $aircraftmaintenance['aircraftmaintengine']['engine2_tc'] : '';
                                $engtso = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['engine2_tso']) ? $aircraftmaintenance['aircraftmaintengine']['engine2_tso'] : '';
                                $engtcso = !empty($aircraftmaintenance['aircraftmaintengine']) && !empty($aircraftmaintenance['aircraftmaintengine']['engine2_tcso']) ? $aircraftmaintenance['aircraftmaintengine']['engine2_tcso'] : '';
                            }
                        }
                    ?>
                    <p>DATE: <?php echo !empty($use_today_date) ? $use_today_date : '___/___/____'; ?></p>
                    <?php
                    if($reportdata['aircraft']['aircraft_engine_type'] == '1' || $reportdata['aircraft']['aircraft_engine_type'] == '2'){
                    ?>
                    <p>A/C TSN: <?php echo !empty($aircraftmaintenance['aircraftmaintoverview']['current_ac_tt']) ? $aircraftmaintenance['aircraftmaintoverview']['current_ac_tt'] : ''; ?></p>
                    <?php }else{ ?>
                    <p>Airframe TT: <?php echo !empty($aircraftmaintenance['aircraftmaintoverview']['airframe_tt']) ? $aircraftmaintenance['aircraftmaintoverview']['airframe_tt'] : ''; ?></p>
                    <?php } ?>
                    <p>ENG TT <?php echo $engines.': '.$engtt; ?></p>
                    <?php
                    if($reportdata['aircraft']['aircraft_engine_type'] == '1' || $reportdata['aircraft']['aircraft_engine_type'] == '2'){
                    ?>
                    <p>ENG TSMOH <?php echo $engines.': '.$engtsmoh; ?></p>
                    <?php }else{ ?>
                    <p>ENG TC <?php echo $engines.': '.$engtc; ?></p>
                    <p>ENG TSO <?php echo $engines.': '.$engtso; ?></p>
                    <p>ENG TCSO <?php echo $engines.': '.$engtcso; ?></p>
                    <?php
                    }
                    
                    }else if(!empty($postData['log_book_category']) && ($postData['log_book_category'] == '5' || $postData['log_book_category'] == '9' || $postData['log_book_category'] == '6' || $postData['log_book_category'] == '10')){ 
                        $prop = '';
                        $actsn = '';
                        $proptt = '';
                        $tspoh = '';
                        $tach = '';
                        $hobbs = '';

                        if(!empty($reportdata['aircraft']['aircraft_engine_type']) && $reportdata['aircraft']['aircraft_engine_type'] != '5'){
                            if($postData['log_book_category'] == '5' || $postData['log_book_category'] == '6'){
                                $prop = '(#1)';
                                $proptt = !empty($aircraftmaintenance['aircraftmaintprops']) && !empty($aircraftmaintenance['aircraftmaintprops']['p_tt_l']) ? $aircraftmaintenance['aircraftmaintprops']['p_tt_l'] : '';
                                $tspoh = !empty($aircraftmaintenance['aircraftmaintprops']) && !empty($aircraftmaintenance['aircraftmaintprops']['p_tspoh_l']) ? $aircraftmaintenance['aircraftmaintprops']['p_tspoh_l'] : '';
                                
                            }else if($postData['log_book_category'] == '9' || $postData['log_book_category'] == '10'){
                                $prop = '(#2)';
                                $proptt = !empty($aircraftmaintenance['aircraftmaintprops']) && !empty($aircraftmaintenance['aircraftmaintprops']['p_tt_r']) ? $aircraftmaintenance['aircraftmaintprops']['p_tt_r'] : '';
                                $tspoh = !empty($aircraftmaintenance['aircraftmaintprops']) && !empty($aircraftmaintenance['aircraftmaintprops']['p_tspoh_r']) ? $aircraftmaintenance['aircraftmaintprops']['p_tspoh_r'] : '';

                            }
                            $actsn = !empty($aircraftmaintenance['aircraftmaintoverview']['current_ac_tt']) ? $aircraftmaintenance['aircraftmaintoverview']['current_ac_tt'] : '';
                            $tach = !empty($aircraftmaintenance['aircraftmaintoverview']) && !empty($aircraftmaintenance['aircraftmaintoverview']['actc']) ? $aircraftmaintenance['aircraftmaintoverview']['actc'] : '';
                            $hobbs = !empty($aircraftmaintenance['aircraftmaintoverview']) && !empty($aircraftmaintenance['aircraftmaintoverview']['use_hobbs']) ? $aircraftmaintenance['aircraftmaintoverview']['hobbs'] : '';
                        }else{
                            $use_today_date = '';
                        }
                    ?>
                    <p>DATE: <?php echo !empty($use_today_date) ? $use_today_date : '___/___/____'; ?></p>
                    <p>A/C TSN: <?php echo $actsn; ?></p>
                    <p>PROP TT <?php echo $prop. ': '.$proptt; ?></p>
                    <p>TSPOH <?php echo $prop. ': '.$tspoh; ?></p>
                    <?php
                    }
                } 
                ?>
            </td>
        </tr>
    </table>

    <p style="margin-top:20px;">
        <strong>
            <?php
            $aircraftWOLogBookCategory = unserialize(AIRCRAFT_WORKORDER_LOGBOOK_CATEGORY);
            echo $aircraftWOLogBookCategory[$log_book_category].' Entries';
            ?>
        </strong>
    </p>
    <table class="item-group-table">
    <?php 
    if(!empty($reportdata['wo_item'])){
        $srcount = 1;
        foreach($reportdata['wo_item'] as $keys=>$row){
            $wo_corrective_action = trim($row['wo_corrective_action']);
            if(!empty($wo_corrective_action)){
    ?>
    <tr>
        <td style="width:5%;"><?php echo $srcount++; ?></td>
        <td><?php echo $wo_corrective_action; ?></td>
    </tr>
    <?php }}} ?>
    </table> 
    <?php 
    $use_today_date = !empty($use_today_date) ? $use_today_date : date('m/d/Y');
    $statement_name = '';
    if(empty($postData['statement_name']) || empty($statementdata)){ 
        $statement_name = 'Southwest Aviation Specialties, L.L.C. FAA CRS# S30R818N'; 
    }else if(!empty($postData['statement_name'])){
        $statement_name = $statementdata->statement_name.' A&P 245943753'; 
    } 

    if(!empty($statement_name)){
    ?>
    <p class="border-top" style="padding-top:10px; margin-bottom: 10px;"><b>Maintenance Release</b></p>
    <p>
        <?php
            $statement_description = str_replace('[#aircraftregistrationno]', $reportdata['aircraft']['aircraft_registration_number'], $statementdata->statement_description);
            $statement_description = str_replace('[#workorderno]', $reportdata['work_order_no'], $statement_description);
            $statement_description = str_replace('[#date]', $use_today_date, $statement_description);

            echo $statement_description;
        ?>
    </p>
    <?php } ?>
    <table class="border-bottom" style="border-bottom: 1px solid;margin-top: 25px;">
        <tr>
            <td width="33%" style="text-align:left;">DATE: <?php echo $use_today_date; ?></td>
            <td width="33%" class="text-end" style="text-align:left;">SIGNED:</td>
            <td width="33%" class="text-end" style="text-align:right;">Work Order: <?php echo $reportdata['work_order_no']; ?></td>
        </tr>
    </table>
    <?php if(!empty($postData['labels_technician']) || !empty($postData['custom_footer'])){ ?>
    <table>
        <tr>
            <td width="33%" style="text-align:left;">&nbsp;</td>
            <td width="33%" class="text-end" style="text-align:left;"><?php echo ($postData['labels_footer_show'] == '5') ? $postData['custom_footer'] : $postData['technician_name'].' A&P: 245943753'; ?></td>
            <td width="33%" class="text-end" style="text-align:right;">&nbsp;</td>
        </tr>
    </table>
    <?php } ?>
</div>