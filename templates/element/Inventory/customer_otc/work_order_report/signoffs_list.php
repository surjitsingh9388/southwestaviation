<div class="table-container">
    <table class="header-table">
        <tr>
            <td class="pb-0 ps-0">
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
                        <td>
                            <table>
                                <tr>
                                    <td class="ps-0">
                                        <table>
                                            <tr>
                                                <td class="title ps-0">
                                                    Southwest Aviation Specialties, LLC - Phone: (918) 298-4044, Fax: (918) 298-698-6930 - Repair Station #: S30R818N - 8720 Jac Bates Ave, Tulsa, OK 74132
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-top-db p-tb-15 text-center">
                                        <p class="headingTitle">Signoffs List - W/O: <?php echo $reportdata['work_order_no'].' - '.$reportdata['aircraft']['aircraft_registration_number']; ?></p>
                                        
                                        <p class="headingTitle">
                                            <?php
                                            $heading = 'Current A/C TT: ';
                                            $heading .= !empty($reportdata['logbook_value_overviews']['current_ac_tt']) ? $reportdata['logbook_value_overviews']['current_ac_tt'] : ''; 
                                            $heading .= ', Hobbs: ';
                                            $heading .= !empty($reportdata['logbook_value_overviews']['hobbs']) ? $reportdata['logbook_value_overviews']['hobbs'] : ''; 
                                            echo $heading;
                                            ?>
                                        </p>
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
    $aircraftWOItemStatus = unserialize(AIRCRAFT_WORKORDER_ITEMSTATUS);
    $woItemSignOff = unserialize(WOITEMSIGNOFF);

    if(!empty($reportdata['wo_item'])){
        foreach($reportdata['wo_item'] as $keys=>$row){
    ?>

    <table class="item-group-table">
        <tr>
            <td class="ps-0 pe-0">
                <table class="item-group-table">
                    <tr>
                        <td class="nowrap text-start border-top-db ps-0" style="padding-left: 0px; width:50%;">
                            <?php echo '<strong>Item # </strong>'.$row['wo_item_position']; ?>
                        </td>
                        <td class="nowrap text-end border-top-db ps-0" style="padding-left: 0px;width:50%;">
                            <?php
                            echo '<strong>Item Status: </strong>'.$aircraftWOItemStatus[$row['wo_item_status']]; 
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="ps-0 pe-0 border-btm">
                <?php echo $row['wo_discrepancy']; ?>
            </td>
        </tr>
        <tr>
            <td class="ps-0 pe-0">
                <?php echo $row['wo_corrective_action']; ?>
            </td>
        </tr>
        
        <tr>
            <td>
                <table class="item-group-table" style="margin-left:5%;">
                    <?php
                    if(!empty($row['signoff_details'])){
                        foreach($row['signoff_details'] as $signoff){
                            if(isset($woItemSignOff[$signoff['signoff_category']])){
                    ?>
                    <tr>
                        <td class="border-top border-btm"><strong><?php echo $woItemSignOff[$signoff['signoff_category']]; ?></strong></td>
                        <td class="border-top border-btm"><?php echo $signoff['users']['full_name']; ?></td>
                        <td class="border-top border-btm text-end"><?php echo date('m/d/Y', strtotime($signoff['inspected_date'])); ?></td>
                    </tr>
                    <?php }}} ?>                    
                </table>
            </td>
        </tr>
    </table> 
    <?php }} ?>
</div>