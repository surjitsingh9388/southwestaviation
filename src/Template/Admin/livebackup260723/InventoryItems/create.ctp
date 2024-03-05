<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

echo $this->Html->css('inventory_item');
?>
<style>
    .icon-generic {
        background: url(<?php echo Router::url('/', true).'images/icons/doc_man_generic.svg';?>);
    }

    .icon-doc {
        background: url(<?php echo Router::url('/', true).'images/icons/doc_man_word.svg';?>);
    }

    .icon-excel {
        background: url(<?php echo Router::url('/', true).'images/icons/doc_man_excel.svg';?>);
    }

    .icon-pdf {
        background: url(<?php echo Router::url('/', true).'images/icons/doc_man_pdf.svg';?>);
    }

    .icon-text {
        background: url(<?php echo Router::url('/', true).'images/icons/doc_man_text.svg';?>);
    }

</style>

<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($invenotryitems, array('class' => 'form-horizontal form-label-left', 'id' => 'frmItemCatalog', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link('Item Catalog', ['action' => 'index']).' / Create'; ?></h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Save', ['type' => 'submit', 'class' => 'btn btn-primary ml-10 invItemSaveBtn']);
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <?php echo $this->element('Inventory/create_new_part'); ?>
                <div style="clear: both;"></div>

                <!-- Tabs Start -->
                <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemGeneral">Attachments</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemGeneral" class="tab-pane fade in active"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative">
                                    <div class="">
                                        <div class="search-control" style="width: 220px; margin-right:10px;display: inline-block;position: relative;">
                                            <input type="text" class="form-control" placeholder="Search Attachments">
                                            <div style="display: inline; position:absolute; right: 10px; top: 6px; color: darkgray">
                                                <i class="fa fa-search"></i>
                                            </div>
                                        </div>

                                        <div class="pull-right">
                                            <input type="file" name="files[]" id="inventoryattachment" style="display:none" multiple />
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
                                            <tr id="noattachmenttr">
                                                <td colspan="5">No Attachments. Click 'Upload...' or drag and drop file to this area.</td>
                                            </tr>
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

<div id="manufacturerModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Manufacturers / Create</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <?php echo $this->element('Inventory/create_manufacturer'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary manufacturersavebtn" disabled>Save</button>
            </div>
        </div>
    </div>
</div>

<script> 
var uploadInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'bulkitemupload']); ?>";
var saveInventoryManufacturerURL = "<?php echo $this->Url->build(['controller'=>'InventoryManufacturers', 'action'=>'saveInventoryManufacturer']); ?>";
var getStatesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getStatesList']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>

<?php 
echo $this->Html->script('inventory_items'); 
echo $this->Html->script('inventory_common');
?>