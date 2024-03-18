<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

?>

<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($invenotries, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInvenotry', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link('Item Catalog', ['controller'=>'InventoryItems', 'action' => 'index']).' / '.$this->Html->link($invenotryitems->name. ' (PN: '.$invenotryitems->part_number.')', ['action' => 'detail', $invenotries->id]).' / Edit'; ?></h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Save', ['type' => 'submit', 'class' => 'btn btn-primary ml-10 inventorysavebtn']);
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <?php echo $this->element('Inventory/create_new_inventory', array('action'=>'edit')); ?>
                <div style="clear: both;"></div>

                <!-- Tabs Start -->
                <div id="aircraftTabs" class="tab-pad">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemGUsageTimes">Usage Times</a></li>
                            <li><a data-toggle="tab" href="#itemAttachment">Attachments <span class="count_circle inventory_attachment_count"><?php echo count($attachments); ?></span></a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemGUsageTimes" class="tab-pane fade in active"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative">
                                    <table class="table upload-area" id="uploadfile">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-1"></th>
                                                <th class="col-sm-2">New</th>
                                                <th class="col-sm-2">Overhaul</th>
                                                <th class="col-sm-2">Repair</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($invenotryitems->is_this_item_serialized == 1){ ?>
                                            <tr>
                                                <td>Months</td>
                                                <td><?php echo $this->Form->control('months_new', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?></td>
                                                <td><?php echo $this->Form->control('months_overhaul', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?></td>
                                                <td><?php echo $this->Form->control('months_repair', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Hours</td>
                                                <td><?php echo $this->Form->Text('hours_new', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?></td>
                                                <td><?php echo $this->Form->Text('hours_overhaul', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?></td>
                                                <td><?php echo $this->Form->Text('hours_repair', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Landings</td>
                                                <td><?php echo $this->Form->control('landings_new', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?></td>
                                                <td><?php echo $this->Form->control('landings_overhaul', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?></td>
                                                <td><?php echo $this->Form->control('landings_repair', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Cycles</td>
                                                <td><?php echo $this->Form->control('cycles_new', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?></td>
                                                <td><?php echo $this->Form->control('cycles_overhaul', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?></td>
                                                <td><?php echo $this->Form->control('cycles_repair', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false)); ?></td>
                                            </tr>
                                            <?php }else{ ?>
                                            <tr>
                                                <td colspan="4"><i>Only serialized components track usage times</i></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div id="itemAttachment" class="tab-pane fade"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative">
                                    <div class="">
                                        <div class="search-control attachemtment-search-block">
                                            <input type="text" class="form-control" placeholder="Search Attachments">
                                            <div class="attachemnt-search-icon">
                                                <i class="fa fa-search"></i>
                                            </div>
                                        </div>

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
                                                echo $this->Html->link($attachment['file_name'], '/inventory/' . $attachment['file_name'],['download'=>$attachment['file_name']]);
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

<div id="vendorAddModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Create Vendor</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <?php echo $this->element('Inventory/create_vendor', array('inventoryvendors'=>'')); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary vendorsavebtn" disabled>Save</button>
            </div>
        </div>
    </div>
</div>

<script> 
var is_this_item_serialized = "<?php echo $invenotryitems->is_this_item_serialized; ?>";
var uploadInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkInventoryupload']); ?>";
var deleteInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'deleteInventoryAttachment']); ?>";
var saveInventoryVendorURL = "<?php echo $this->Url->build(['controller'=>'InventoryVendors', 'action'=>'saveInventoryVendor']); ?>";
var getStatesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getStatesList']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>

<?php 
echo $this->Html->css('inventory'); 
echo $this->Html->script('inventories'); 
echo $this->Html->script('inventory_common');
?>