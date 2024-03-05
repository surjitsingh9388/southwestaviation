<section class="top-form-section">
    
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <button type="button" class="btn btn-default" onclick="$('#aircraft_wo_files').trigger('click'); return false;" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Add File</button>
                <button type="button" class="btn btn-default woremovefilebtn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Remove File</button>
                <button type="button" class="btn btn-default womovefilebtn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Move File</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt10">
            <input type="file" name="files[]" id="aircraft_wo_files" class="hide-block uploadcustomerotcattch" multiple  accept=".xlsx, .xls, .doc, .docx,.ppt, .pptx, .pdf" style="display:none;" />
            <table class="table table-bordered aircraftwotable">
                <thead>
                    <tr>
                        <th scope="col">File Name</th>
                        <th scope="col">Caption</th>
                    </tr>
                </thead>
                <tbody id="wofileattachlist">
                    <?php
                    if(!empty($aircraftwoitemfiles)){
                    foreach($aircraftwoitemfiles as $key=>$files){
                        $ext = substr(strrchr($files['file_name'] , '.'), 1);
                        $iconcss = '';
                        if($ext == 'pdf'){
                            $iconcss = 'icon-pdf';
                        }else if($ext == 'doc' || $ext == 'docx'){
                            $iconcss = 'icon-doc';
                        }else if($ext == 'xls' || $ext == 'xlsx'){
                            $iconcss = 'icon-excel';
                        }else if($ext == 'txt'){
                            $iconcss = 'icon-text';
                        }else{
                            $iconcss = 'icon-generic';
                        }

                        $wo_item_file_active = '';
                        if($key == 0){
                            $wo_item_file_active = 'wo-item-file-active';
                        }
                    ?>
                    <tr class="aircraft-wo-item-file <?php echo $wo_item_file_active; ?>" data-val="<?php echo $files['id']; ?>">
                        <td class="document-name"><span class="document-management-icon <?php echo $iconcss; ?>"></span>
                        <?php
                        echo $this->Html->link($files['file_name'], '/customer_otc_aircraft/' . $files['file_name'],['download'=>$files['file_name']]);
                        ?></td>
                        <td><?php echo $files['caption']; ?></td>
                    </tr>
                    <?php }} ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script>
    var uploadWOItemFilesURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'uploadWOItemFiles']); ?>";
    var deleteWOItemFileURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteWOItemFile']); ?>";
</script>