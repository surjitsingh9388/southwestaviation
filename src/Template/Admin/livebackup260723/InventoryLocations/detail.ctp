<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

echo $this->Html->css('inventory_location');

echo $this->Html->script(array('jquery-qrcode-master/src/jquery.qrcode', 'jquery-qrcode-master/src/qrcode'));
?>


<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($invenotrylocations, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInvenotryLocations', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link('Inventory Locations', ['action' => 'index']).' / '.$invenotrylocations->location_name;?>&nbsp;
            <?php if($invenotrylocations->status == '1'){ ?>
            <span class="badge heading-success badge-status">Active</span>
            <?php }else{ ?>
            <span class="badge badge-status">Inactive</span>
            <?php } ?></h2>
            <div class="btnWrap">
                <?php
                echo $this->Html->link("Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));

                echo $this->Html->link("Edit", ['action'=>'edit', $invenotrylocations->id], array('class' => 'btn btn-primary', 'escape' => false));
                ?>
                
                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                    <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                    <?php
                    if((!empty($actionItems) && $actionItems['action']['action_delete'] == 1) || $sessionUser['id'] == 1) {
                    ?>
                        <?php if($invenotrylocations->status == '0'){ ?>
                        <li><a class="dropdown-item changelocstatus" href="javascript:void(0);" data-val="1">Activate</a></li>
                        <?php }else{ ?>
                        <li><a class="dropdown-item changelocstatus" href="javascript:void(0);" data-val="0">Deactivate</a></li>
                        <?php }?>
                    <?php } ?>
                    <?php
                    if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    ?>
                        <li><a class="dropdown-item printBarCode" href="javascript:void(0);">Print Barcode</a></li>
                    </div>
                    <?php } ?>
                </div>
               
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Name</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                            <?php echo $invenotrylocations->location_name; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Status</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                            <?php 
                            $locationStatus = unserialize(INVENTORY_LOCATION_STATUS);
                            echo $locationStatus[$invenotrylocations->location_status]; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if(isset($locationdata['location_name'])){ ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Parent Location</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                            <a class="parent_location_name" href="<?php echo $this->Url->build(['action'=>'detail', $locationdata['id']]); ?>"><?php echo $locationdata['location_name']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Description</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                            <?php echo $invenotrylocations->description; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"> 
                            <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Barcode</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php
                                $bar_code = !empty($invenotrylocations->bar_code) ? $invenotrylocations->bar_code : Router::url(['controller' => 'InventoryLocations', 'action' => 'detail', $invenotrylocations->id]); 
                                ?>
                                <img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=<?php echo $bar_code; ?>&choe=UTF-8" />
                            </div>
                        </div>
                    </div>
                </div>


                <div style="clear: both;"></div>

                <!-- Tabs Start -->
                <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemQuantities">Quantities</a></li>
                            <li><a data-toggle="tab" href="#itemSubLocations">Sub Locations</a></li>
                            <li><a data-toggle="tab" href="#itemAttachment">Attachments</a></li>
                            <li><a data-toggle="tab" href="#itemHistory">History</a></li>
                        </ul>
                        <div class="tab-content">

                            <div id="itemQuantities" class="tab-pane fade in active"><!-- general-tab-section start -->
                            
                                <div class="mt5">
                                    <div class="col-sm-12" style="margin-bottom: 10px;">
                                        <div class="col-sm-4">
                                            <input type="text" id="quantitesSearchItem" name="search" class="form-control" placeholder="Search Inventory" style="border-radius: 5px">
                                            <div style="display: inline; position:absolute;right: 20px;top: 6px;color: darkgray">
                                                <i class="fa fa-search"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-4" style="padding-top: 5px;">
                                            <span style="margin-left: 10px;" for="showinactive">
                                                <input id="showinactiveinv" type="checkbox" name="invinactive" value="1">&nbsp;
                                                <span>Include Inactive</span>
                                            </span>
                                        </div>
                                        <?php
                                        if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                                        ?>
                                        <div class="btn-group col-sm-4" style="float:right;">
                                            <div class="col-sm-8">
                                                <select class="selectpicker actionSel invquantitiesaction col-sm-12" id="actionSel">
                                                    <option value="">Action on Selected</option>
                                                    <option value="2" disabled>Bulk Transfer</option>
                                                    <option value="3" disabled>Bulk Install</option>
                                                    <option value="4" disabled>Bulk Discard</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <button type="button" class="btn btn-primary dispinvdetpagepopup" data-val="thresholds" onclick="$('#printQuantitiesBarCodeModel').modal('show');">Print Barcodes</button>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>

                                    <div class="col-sm-12 table-responsive">
                                        <table id="quantitiesTable" class="table mb-0">
                                            <thead>
                                                <tr style="cursor:pointer;">
                                                    <th class="check" style="text-align: left;"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                                                    <th id="partNumberId">Part Number / Name <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="serialNoId">Serial or Lot / Display <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="qtyId">Qty. <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="costId">Cost <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="locationId">Location <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="receivedId">Received<i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="lastTransactionId">Last Transaction <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="statusId">Status <i class="fa fa-fw fa-sort"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody id="quantitiesList">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div id="itemSubLocations" class="tab-pane fade"><!-- general-tab-section start -->
                                <div class="">
                                    <div class="table-responsive">
                                        <div class="addsublocbtn">
                                            <a href="<?php echo $this->Url->build(['action'=>'create', $invenotrylocations->id]); ?>" class="btn btn-primary btn-sm">Add Sub Location
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </a>
                                            <span>
                                                <input type="checkbox" name="status" class="showinactiveloc" value="0"> Include Inactive
                                            </span>
                                        </div>
                                        <table id="customReport" class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th id="aircraftId">Name <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="reportedDateId">Description <i class="fa fa-fw fa-sort"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody id="childLocationList">
                                                <?php 
                                                if($childlocations->count() > 0){
                                                foreach($childlocations as $val){
                                                ?>
                                                <tr>
                                                    <td><a href="<?php echo $this->Url->build(['action'=>'detail', $val['id']]); ?>"><?php echo $val['location_path']; ?></a></td>
                                                    <td><?php echo $val['description']; ?></td>
                                                </tr>
                                                <?php }}else{ ?>
                                                <tr>
                                                    <td colspan="2" align="center"><i>No sub locations found</i></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div id="itemAttachment" class="tab-pane fade"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative">
                                    <div style="margin-top:10px;">
                                        <div class="search-control" style="width: 220px; margin-right:10px;display: inline-block;position: relative;margin-left: 5px;">
                                            <input type="text" id="attachmentSearch" class="form-control" placeholder="Search Attachments">
                                            <div style="display: inline; position:absolute; right: 10px; top: 6px; color: darkgray">
                                                <i class="fa fa-search"></i>
                                            </div>
                                        </div>

                                        <div class="pull-right">
                                            <button class="btn btn-primary pull-right" type="button" disabled>Upload</button>
                                        </div>
                                    </div>

                                    <table class="table upload-area" id="uploadfile">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-2">File Name</th>
                                                <th class="col-sm-1">Size</th>
                                                <th class="col-sm-2">Uploaded</th>
                                                <th class="col-sm-2">Uploaded By</th>
                                            </tr>
                                        </thead>
                                        <tbody id="filetbody">
                                            
                                        <?php if(empty($attachments)){ ?>
                                            <tr>
                                                <td colspan="5">No Attachments.</td>
                                            </tr>
                                            <?php 
                                            }else{ 
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
                                            <tr style="text-align:left;">
                                                <td class="document-name"><span class="document-management-icon <?php echo $iconcss; ?>"></span>
                                                <?php
                                                echo $this->Html->link($attachment['file_name'], '/inventorylocation/' . $attachment['file_name'],['download'=>$attachment['file_name']]);
                                                ?></td>
                                                <td><?php echo $attachment['file_size']; ?></td>
                                                <td><?php echo $attachment['created']; ?></td>
                                                <td><?php echo $attachment['uploaded_by']; ?></td>
                                            </tr>
                                            <?php }} ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- general-tab-section end -->
                            
                            <div id="itemHistory" class="tab-pane fade"><!-- general-tab-section start -->
                                <div class="partsGpCls">
                                    <div class="table-responsive">
                                        <table id="inventoryLocationHistoryTable" class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th id="aircraftId">Date <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="reportedDateId">User <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="reportedDateId">Description <i class="fa fa-fw fa-sort"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach($inventorylocationhistories as $invhistory){
                                                    $data = @unserialize($invhistory['description']); 
                                                    if ($data === false) {
                                                        $data = $invhistory['description'];
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?php echo date('d-M-Y h:i A', strtotime($invhistory['created'])); ?></td>
                                                    <td><?php echo $invhistory['users']['email']; ?></td>
                                                    <td>
                                                        <a class="toggleplusminus"><i class="fa fa-plus"></i></a>
                                                        <?php echo $invhistory['title']; ?>
                                                        <pre class="history-detail-block" style="display:none;"><?php echo $data;?>
                                                        </pre>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<div id="invQuantitiesActionOnSelectModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content" id="bulkpopupcontent">
            
        </div>
    </div>
</div>

<div id="printQuantitiesBarCodeModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Print Barcode</h4>
            </div>
            <div class="modal-body" style="max-height: 500px;">
                <?php echo $this->Form->create('', ['url' =>'', 'id' => 'formprintQuantitiesBarCode', 'autocomplete'=>'off']); ?>
                <div class="page-content">
                    <div class="row mt5">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Print Size</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="quantities_size" id="f_serialized" value="1" checked>
                                        <label class="form-check-label" for="inlineRadio2">2 inch x 1 inch</label>

                                        <input class="form-check-input" type="radio" name="quantities_size" id="f_nonserialized" value="2">
                                        <label class="form-check-label" for="is_this_item_serialized">4 inch x 2 inch</label>&nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt5"></div>

                </div>
                <?php echo $this->Form->end(); ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary printQuantitiesBarCodeBtn">Print</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var childLocationSearchAjaxURL = "<?php echo $this->Url->build(['controller'=>'InventoryLocations', 'action'=>'childLocationSearchAjax']); ?>";
    var locationQantitiesSearchAjaxURL = "<?php echo $this->Url->build(['controller'=>'InventoryLocations', 'action'=>'ajaxQantitiesSearch']); ?>";
    var inventoriesDetailPageURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'detail',]); ?>";
    var updateLocationStatusURL = "<?php echo $this->Url->build(['controller'=>'InventoryLocations', 'action'=>'updatelocationstatus']); ?>";
    var printBarCodeURL = "<?php echo $this->Url->build(['controller'=>'InventoryLocations', 'action'=>'printInventoryLocationBarCode']); ?>";
    var pagelimit = <?php echo PAGINATION_LIMIT;?>;
    var pdfPagTitle = 'PURCHASE ORDER';
    var ajaxOpenActionSelectPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'ajaxOpenActionSelectPopup']); ?>";
    var bulkTransferURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkTransfer']); ?>";
    var bulkDiscardURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkDiscard']); ?>";
    var printQantitiesBarCodeURL = "<?php echo $this->Url->build(['controller'=>'InventoryLocations', 'action'=>'printQantitiesBarCode']); ?>";

    var ajaxListPageSearchURL = "";


    $(document).ready(function() {
        getQuantitiesData('0');
    });
</script>
<?php 
echo $this->Html->script('jquery.sortElements'); 
echo $this->Html->script('inventory_common');
echo $this->Html->script('inventory_location');
?>
