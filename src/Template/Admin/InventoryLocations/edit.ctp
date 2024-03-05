<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

echo $this->Html->css('inventory_location');
?>

<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($invenotrylocations, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInvenotryLocations', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link('Inventory Locations', ['action' => 'index']).' / '.$this->Html->link($invenotrylocations->location_name, ['action' => 'detail', $invenotrylocations->id]);?> / Edit</h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Save', ['type' => 'submit', 'class' => 'btn btn-primary ml-10 locationsavebtn']);
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <?php echo $this->element('Inventory/create_new_inventory_location'); ?>
                <div style="clear: both;"></div>

                <!-- Tabs Start -->
                <div id="aircraftTabs" class="tab-pad">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemAttachment">Attachments</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemAttachment" class="tab-pane fade in active"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative">
                                    <div class="">
                                        <!--div class="search-control" style="width: 220px; margin-right:10px;display: inline-block;position: relative;">
                                            <input type="text" class="form-control" placeholder="Search Attachments">
                                            <div style="display: inline; position:absolute; right: 10px; top: 6px; color: darkgray">
                                                <i class="fa fa-search"></i>
                                            </div>
                                        </div-->

                                        <div class="pull-right">
                                            <input type="file" name="files[]" id="inventoryattachment" class="hide-block" multiple />
                                            <button class="btn btn-primary pull-right" type="button" onclick="$('#inventoryattachment').trigger('click'); return false;">Upload</button>
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
                                        <tbody id="filetbody">
                                            
                                            <tr id="noattachmenttr" <?php if(!empty($attachments)){ ?> class="hide-block" <?php } ?>>
                                                <td colspan="5" id="noattachmentmsg">No Attachments. Click 'Upload...' or drag and drop file to this area.</td>
                                            </tr>
                                            <?php 
                                            if(!empty($attachments)){
                                            foreach($attachments as $attachment){
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
                                                echo $this->Html->link($attachment['file_name'], '/inventorylocation/' . $attachment['file_name'],['download'=>$attachment['file_name']]);
                                                ?></td>
                                                <td><?php echo $attachment['file_size']; ?></td>
                                                <td><?php echo $attachment['created']; ?></td>
                                                <td><?php echo $attachment['uploaded_by']; ?></td>
                                                <td><i class="fa fa-times deleteattachment" title="Remove File" data-val="<?php echo $attachment['id']; ?>"></i></td>
                                            </tr>
                                            <?php }} ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- general-tab-section end -->

                        </div>
                    </div>
                </div>
                <!-- Tabs end -->
            </div>
        </div>
    <?php 
    echo $this->Form->end(); 
    ?>
    </div>
</div>

<script> 
var uploadInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'InventoryLocations', 'action'=>'bulkInventoryLocUpload']); ?>";
var deleteInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'InventoryLocations', 'action'=>'deleteInventoryLocAttachment']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>
<?php 
echo $this->Html->script('jquery.sortElements'); 
echo $this->Html->script('inventory_location'); 
echo $this->Html->script('inventory_common');
?>