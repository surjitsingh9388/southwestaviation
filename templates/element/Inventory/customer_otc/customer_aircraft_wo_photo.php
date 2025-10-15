<section class="top-form-section">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <button type="button" class="btn btn-default woaddphotofilebtn" data-val="wo_photo_upload" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Add Photo</button>
                <button type="button" class="btn btn-default woremovephotobtn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Remove Photo</button>
                <button type="button" class="btn btn-default womovepicturebtn" <?php if($aircraftwoitems->wo_item_status == '3'){ ?> disabled<?php } ?>>Move Picture</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12 col-sm-12 mt10">
            <table class="table table-bordered aircraftwotable">
                <thead>
                    <tr>
                        <th scope="col">File Name</th>
                        <th scope="col">Caption</th>
                    </tr>
                </thead>
                <tbody id="wophotosattachlist">
                    <?php
                    if(!empty($aircraftwoitemphotoes)){
                    foreach($aircraftwoitemphotoes as $key=>$photes){
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

                        $wo_item_photo_active = '';
                        if($key == 0){
                            $wo_item_photo_active = 'wo-item-photo-active';
                        }
                    ?>
                    <tr class="aircraft-wo-item-photo <?php echo $wo_item_photo_active; ?>" data-val="<?php echo $photes['id']; ?>">
                        <td class="document-name"><span class="document-management-icon <?php echo $iconcss; ?>"></span>
                        <?php
                        echo $this->Html->link($photes['file_name'], '/inventorycustomers/' . $photes['file_name'],['download'=>$photes['file_name'], 'target' => '_blank']);
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
    var uploadWOItemPhotosURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'uploadWOItemPhotos']); ?>";
    var deleteWOItemPhotoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteWOItemPhoto']); ?>";
    var saveWOMoveItemPhotoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOMoveItemPhoto']); ?>";
</script>