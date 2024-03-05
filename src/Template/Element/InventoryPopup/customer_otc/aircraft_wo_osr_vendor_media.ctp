<div id="woOSRVendorMediaModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
        echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOOSRVendorMedia'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Media</h4>
            </div>
            <div class="modal-body" style="height: auto;">
                <div class="">
                    <div class="pull-right">
                        <input type="hidden" name="osr_vendor_id" value="<?php echo $osr_vendor_id; ?>" />
                        <input type="file" name="files[]" id="wo_osr_vendor_media" style="display:none" multiple />
                        <button class="btn btn-primary pull-right" type="button" onclick="$('#wo_osr_vendor_media').trigger('click'); return false;">Upload</button>
                    </div>
                </div>

                <table class="table upload-area" id="uploadfile">
                    <thead class="thead-dark">
                        <tr>
                            <th class="col-sm-2">File Name</th>
                            <th class="col-sm-1">Size</th>
                            <th class="col-sm-2">Uploaded</th>
                            <th class="col-sm-2">Uploaded By</th>
                            <th class="col-sm-1"></th>
                        </tr>
                    </thead>
                    <tbody id="aircraftwoosrmediatbl">
                        <?php if(count($osrVendorInfoMedia) > 0){
                        foreach($osrVendorInfoMedia as $attachment){
                            $ext = substr(strrchr($attachment['file_name'] , '.'), 1);

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
                        ?>
                        <tr>
                            <td class="document-name"><span class="document-management-icon <?php echo $iconcss; ?>"></span>
                            <?php
                            echo $this->Html->link($attachment['file_name'], '/customer_otc_aircraft/' . $attachment['file_name'],['download'=>$attachment['file_name']]);
                            ?></td>
                            <td><?php echo $attachment['file_size']; ?></td>
                            <td><?php echo $attachment['created']; ?></td>
                            <td><?php echo $attachment['full_name']; ?></td>
                            <td><i class="fa fa-times deleteWOOSRVendorMedia" title="Remove File" data-val="<?php echo $attachment['id']; ?>"></i></td>
                        </tr>
                        <?php }} ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saveOSRVendorInfoMedia">Save</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>