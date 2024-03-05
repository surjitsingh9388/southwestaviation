<section class="top-form-section">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <button type="button" class="btn btn-default" onclick="$('#inventory_tool_photo').trigger('click'); return false;">Add Photo</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt10">
            <input type="file" name="files[]" id="inventory_tool_photo" class="hide-block" multiple  accept=".png, .gif, .jpeg" style="display:none;" />
            <table class="table table-bordered toolphototable">
                <thead>
                    <tr>
                        <th scope="col">File Name</th>
                        <th scope="col">Caption</th>
                    </tr>
                </thead>
                <tbody id="toolphotosattachlist">
                    <?php
                    if(!empty($invtoolphotoes)){
                    foreach($invtoolphotoes as $key=>$photes){
                        $ext = substr(strrchr($photes['file_name'] , '.'), 1);
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

                        $tool_photo_active = '';
                        /*if($key == 0){
                            $tool_photo_active = 'inv-tool-photo-active';
                        }*/
                    ?>
                    <tr class="inventory-tool-photo <?php echo $tool_photo_active; ?>" data-val="<?php echo $photes['id']; ?>">
                        <td class="document-name"><span class="document-management-icon <?php echo $iconcss; ?>"></span>
                        <?php
                        echo $this->Html->link($photes['file_name'], '/inventorytools/' . $photes['file_name'],['download'=>$photes['file_name']]);
                        ?></td>
                        <td><?php echo $photes['caption']; ?></td>
                    </tr>
                    <?php }} ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script>
    var uploadToolPhotosURL = "<?php echo $this->Url->build(['controller'=>'InventoryTools', 'action'=>'uploadToolPhotos']); ?>";
</script>