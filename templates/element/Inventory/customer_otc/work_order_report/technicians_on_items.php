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
                                                <td class="title ps-0" style="font-size:16px;">
                                                    Southwest Aviation Specialties, LLC - Phone: (918) 298-4044, Fax: (918) 298-698-6930 - Repair Station #: S30R818N - 8720 Jac Bates Ave, Tulsa, OK 74132
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-top-db p-tb-15 text-center">
                                        <?php 
                                        $itemtypeheading = '';
                                        if($report_type == '60'){ 
                                            $itemtypeheading = '#'.$reportdata['wo_item'][0]['wo_item_position'];
                                        }
                                        ?>
                                        <p class="headingTitle"><?php echo 'Technicians on Items '.$itemtypeheading.'- W/O: '.$reportdata['work_order_no'].' - '.$reportdata['customers']['customer_name'].' ('.$reportdata['aircraft']['aircraft_registration_number'].')'; ?></p>
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
    ?>
    <table class="item-group-table item-group-table-body">
        <tr>
            <td class="ps-0 pe-0">
                <table class="item-group-table">
                    <tr>
                        <th class="title nowrap text-start ps-0" style="padding: 0; width:5%; font-size:12px;">Item No.</th>
                        <th class="title nowrap text-start" style="padding: 0; width:25%; font-size:12px;">Discrepancy</th>
                        <th class="title nowrap text-start" style="padding: 0; width:30%; font-size:12px;">Corrective Action</th>
                        <th class="title nowrap text-end pe-0" style="padding: 0; width:10%; font-size:12px;">Inspector</th>
                    </tr>
                    
                    <?php
                    foreach($reportdata['wo_item'] as $keys=>$row){
                        $signoff_done_by = !empty($row['signoff_details']) ? $row['signoff_details']['users']['full_name'] : '';
                    ?>
                    <tr>
                        <td class="border-top-db ps-0" style="font-size:12px;"><?php echo $row['wo_item_position']; ?></td>
                        <td class="border-top-db" style="font-size:12px;"><?php echo $row['wo_discrepancy']; ?></td>
                        <td class="border-top-db" style="font-size:12px;"><?php echo $row['wo_corrective_action']; ?></td>
                        <td class="text-end pe-0 border-top-db" style="font-size:12px;"><?php echo $signoff_done_by; ?></td>
                    </tr>
                    <?php if(!empty($row['services_details'])){ ?>
                    <tr>
                        <td class="ps-0 pe-0" colspan="4">
                            <table class="item-group-table" style="margin-left:1%;">
                                <tr>
                                    <th class="text-start title" style="font-size:12px;">Technicians:</th>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-0 pe-0" colspan="4">
                            <table class="item-group-table" style="margin-left:1%;">
                                <tr>
                                    <?php
                                    foreach($row['services_details'] as $services){
                                    ?>
                                    <td style="font-size:12px;"><?php echo !empty($services['users']['full_name']) ? $services['users']['full_name'] : ''; ?></td>
                                    <?php } ?>
                                </tr>                    
                            </table>
                        </td>
                    </tr>
                    <?php }} ?> 
                </table>   
            </td>
        </tr>         
    </table>
    <?php } ?>
</div>