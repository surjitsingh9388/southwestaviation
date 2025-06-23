<section class="top-form-section">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <button type="button" class="btn btn-default" onclick="$('#inventory_tool_files').trigger('click'); return false;">Add File</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt10">
            <input type="file" name="files[]" id="inventory_tool_files" class="hide-block" multiple  accept=".xlsx, .xls, .doc, .docx,.ppt, .pptx, .pdf" style="display:none;" />
            <table class="table table-bordered aircraftwotable" style="table-layout: fixed;">
                <thead>
                    <tr>
                        <th scope="col">File Name</th>
                        <th scope="col">Caption</th>
                    </tr>
                </thead>
                <tbody id="toolfileattachlist">
                    <?php
                    if(!empty($invtoolfiles)){
                    foreach($invtoolfiles as $key=>$files){
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
                        /*if($key == 0){
                            $wo_item_file_active = 'wo-item-file-active';
                        }*/
                    ?>
                    <tr class="inventory-tool-file <?php echo $wo_item_file_active; ?>" data-val="<?php echo $files['id']; ?>">
                        <td class="document-name"><span class="document-management-icon <?php echo $iconcss; ?>"></span>
                        <?php
                        echo $this->Html->link($files['file_name'], '/inventorytools/' . $files['file_name'],['download'=>$files['file_name']]);
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
    var uploadToolFilesURL = "<?php echo $this->Url->build(['controller'=>'InventoryTools', 'action'=>'uploadToolFiles']); ?>";
    //var deleteWOItemFileURL = "<?php echo $this->Url->build(['controller'=>'InventoryTools', 'action'=>'deleteWOItemFile']); ?>";
</script>