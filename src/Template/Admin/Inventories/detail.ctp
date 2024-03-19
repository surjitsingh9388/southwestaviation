<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

?>

<div class="content sliding">
    <div class="outerWrapper">
         
        <div class="btnWrapper flex-column-mob">
            <?php
            $serialmsg = 'No Lot';
            if(!empty($invenotries->serial_no)){
                $serialmsg = $invenotries->serial_no;
            }
            ?>
            <h2 class="heading" style="flex-basis: 70%"><?php echo $this->Html->link('Item Catalog', ['controller'=>'InventoryItems', 'action' => 'index']).' / '.$this->Html->link($invenotries['_matchingData']['InventoryItems']['name']. '(PN: '.$invenotries['_matchingData']['InventoryItems']['part_number'].')', ['controller'=>'InventoryItems', 'action' => 'detail', $invenotries['_matchingData']['InventoryItems']['id']]).' / '.$serialmsg;?>&nbsp;
            <?php echo $statushtml; ?>
            </h2>
            <div class="btnWrap mb-5">
             <?php
                echo $this->Html->link("Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                ?>
                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Action
                    <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'detail', $invenotries->inventory_item_id]); ?>">View Catalog Item</a></li>
                        <?php if($invenotries->status == '2' || $invenotries->status == '1' || $invenotries->status == '6' || $invenotries->status == '7' || $invenotries->status == '5' || $invenotries->status == '8' || $invenotries->status == '12' || $invenotries->status == '13'){ ?>
                            <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'edit', $invenotries->id]); ?>">Edit</a></li>
                        <?php } ?>

                        <?php if($invenotries->status != '2' && $invenotries->status != '3' && $invenotries->status != '4' && $invenotries->status != '6' && $invenotries->status != '10' && $invenotries->status != '7' && $invenotries->status != '9' && $invenotries->status != '14' && $invenotries->status != '8'){ ?>
                        <li>
                            <a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'create', '?'=>['invid'=>$invenotries->id]]); ?>">Ship</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'create', '?'=>['invid'=>$invenotries->id]]); ?>">Repair</a>    
                        </li>
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'discard', $invenotries->id]); ?>">Discard</a></li>
                        <?php if($invenotries->status != '5' && $invenotries->status != '13'){ ?>
                            <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'consume', $invenotries->id]); ?>">Consume</a></li>
                            <?php if($invenotries->status != '12'){ ?>
                            <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'install', $invenotries->id]); ?>">Install</a></li>
                        <?php }} ?>
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'adjust', $invenotries->id]); ?>">Adjust</a></li>
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'transfer', $invenotries->id]); ?>">Transfer</a></li>
                        <?php if($invenotries->status == '12'){ ?>
                            <li><a class="dropdown-item updateinventorystatus" data-val="1" href="javascript:void(0);">Unquarantine</a></li>
                        <?php }else{ ?>
                            <li><a class="dropdown-item updateinventorystatus" data-val="12" href="javascript:void(0);">Quarantine</a></li>
                        <?php }} if($invenotries->status == '2'){ ?>
                            <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'uninstall', $invenotries->id]); ?>">Uninstall</a></li>
                        <?php } ?>
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'error_correct', $invenotries->id]); ?>">Error Correct</a></li>
                        <?php if($invenotries->status != '2' && $invenotries->status != '3' && $invenotries->status != '4' && $invenotries->status != '10' && $invenotries->status != '7' && $invenotries->status != '9' && $invenotries->status != '14' && $invenotries->status != '6' && $invenotries->status != '8'){ ?>
                        <li><a class="dropdown-item" href="javascript:void(0);" onclick="$('#printInventoriesListBarcodesModel').modal('show');">Print Barcode</a></li>
                        <?php } ?>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <?php
                 echo $this->Form->create($invenotries, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryDet'));
                ?>
                <?php echo $this->element('Inventory/inventory_detail', array('isdetailpage'=>true)); ?>
                <div style="clear: both;"></div>
                <?php 
                echo $this->Form->end(); 
                ?>
                <!-- Tabs Start -->
                <div id="aircraftTabs" class="tab-pad">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemUsageTimes">Usage Times</a></li>
                            <li><a data-toggle="tab" href="#itemsInstalledComp">Installed Components</a></li>
                            <li>
                                <a data-toggle="tab" href="#itemsAttachments">Attachments <span class="count_circle inventory_attachment_count"><?php echo count($attachments); ?></span></a>
                            </li>
                            <li><a data-toggle="tab" href="#itemsTransactions">Transactions</a></li>
                            <li><a data-toggle="tab" href="#itemsHistory">History</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemUsageTimes" class="tab-pane fade in active">
                                <div class="partsGpCls">
                                    <table class="table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-1">Metric</th>
                                                <th class="col-sm-2">New</th>
                                                <th class="col-sm-2">Overhaul</th>
                                                <th class="col-sm-2">Repair</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($invenotries['_matchingData']['InventoryItems']['is_this_item_serialized'] == 1){ ?>
                                            <tr>
                                                <td>Months</td>
                                                <td><?php echo !empty($invenotries->months_new) ? $invenotries->months_new : '-'; ?></td>
                                                <td><?php echo !empty($invenotries->months_overhaul) ? $invenotries->months_overhaul : '-'; ?></td>
                                                <td><?php echo !empty($invenotries->months_repair) ? $invenotries->months_repair : '-'; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Hours</td>
                                                <td><?php echo !empty($invenotries->hours_new) ? $invenotries->hours_new : '-'; ?></td>
                                                <td><?php echo !empty($invenotries->hours_overhaul) ? $invenotries->hours_overhaul : '-'; ?></td>
                                                <td><?php echo !empty($invenotries->hours_repair) ? $invenotries->hours_repair : '-'; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Landings</td>
                                                <td><?php echo !empty($invenotries->landings_new) ? $invenotries->landings_new : '-'; ?></td>
                                                <td><?php echo !empty($invenotries->landings_overhaul) ? $invenotries->landings_overhaul : '-'; ?></td>
                                                <td><?php echo !empty($invenotries->landings_repair) ? $invenotries->landings_repair : '-'; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Cycles</td>
                                                <td><?php echo !empty($invenotries->cycles_new) ? $invenotries->cycles_new : '-'; ?></td>
                                                <td><?php echo !empty($invenotries->cycles_overhaul) ? $invenotries->cycles_overhaul : '-'; ?></td>
                                                <td><?php echo !empty($invenotries->cycles_repair) ? $invenotries->cycles_repair : '-'; ?></td>
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
                            <div id="itemsInstalledComp" class="tab-pane fade">
                                <div class="mt10">
                                    <div class="col-sm-12">
                                        <div class="col-sm-6 mb-5">
                                            <div class="search-control attachemtment-search-block">
                                                <input type="text" class="form-control installedSearchItem" placeholder="Search Inventory">
                                                <div class="attachemnt-search-icon">
                                                    <i class="fa fa-search"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-5">
                                            <div class="text-right">
                                                <button type="button" class="btn btn-default addInvHoldingToBox">Add to Holding Box<span class="<?php echo PAGINATION_LIMIT;?>"></span></button>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="table-responsive table-width">
                                        <table id="tblInstalledComponents" class="table mb-0">
                                            <thead>
                                                <tr class="tr-cursor">
                                                    <th class="check"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                                                    <th id="instlCompitemName">Item Name <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="instlComppartNumber">Part Number<i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="instlCompSerialLot">Serial / Lot <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="instlCompdisplayName">Display Name <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="instlCompqty">Qty.<i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="instlComplocation">Location <i class="fa fa-fw fa-sort"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody id="installedComponentsList">
                                                <?php
                                                if(count($installedcomponents) > 0){
                                                foreach($installedcomponents as $key=>$instlcomp){
                                                    $key = $key+1;
                                                    $evenOdd = (!empty($key) && ($key % 2) == 0) ? 'even' : 'odd';  
                                                ?>
                                                <tr class="mainTR activeTble tr-cursor <?php echo $evenOdd; ?>">
                                                    <td class="collapse-tr check">
                                                        <input type="checkbox" class="chkBoxCls" name="childcheckbox" value="<?php echo $instlcomp['id'];?>">
                                                        <?php if($instlcomp['in_holdingbox'] == '1'){ ?>
                                                        <img src="../../../images/icons/holdingbox.png" class="holding-box-icon">
                                                        <?php } ?>
                                                    </td>
                                                    <td class="collapse-tr"><?php echo $instlcomp['name']; ?></td>
                                                    <td class="collapse-tr"><?php echo $instlcomp['part_number']; ?></td>
                                                    <td class="collapse-tr"><?php echo $instlcomp['serial_no']; ?></td>
                                                    <td class="collapse-tr"><?php echo $instlcomp['display_name']; ?></td>
                                                    <td class="collapse-tr"><?php echo $instlcomp['qty']; ?></td>
                                                    <td class="collapse-tr"><?php echo $instlcomp['installtopath']; ?></td>
                                                </tr>
                                                <?php }}else{ ?>
                                                <tr>
                                                    <td colspan="7">No Inventory Found</td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="itemsAttachments" class="tab-pane fade"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative">
                                    <div class="mt10" style="padding-left: 10px;">
                                        <div class="search-control attachemtment-search-block">
                                            <input type="text" id="attachmentSearch" class="form-control" placeholder="Search Attachments">
                                            <div class="attachemnt-search-icon">
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
                                                <td colspan="5" id="noattachmentmsg">No Attachments.</td>
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
                                            <tr class="text-left">
                                                <td class="document-name"><span class="document-management-icon <?php echo $iconcss; ?>"></span>
                                                <?php
                                                echo $this->Html->link($attachment['file_name'], '/inventory/' . $attachment['file_name'],['download'=>$attachment['file_name']]);
                                                ?></td>
                                                <td><?php echo $attachment['file_size']; ?></td>
                                                <td><?php echo $attachment['created']; ?></td>
                                                <td><?php echo $attachment['uploaded_by']; ?></td>
                                            </tr>
                                            <?php }} ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- attachment-tab-section end -->

                            <div id="itemsTransactions" class="tab-pane fade">
                                <div class="mt10">
                                    <div class="col-sm-12">
                                        <div class="text-right">
                                            <?php echo $this->Html->link("Print", 'javascript:void(0);', array('class' => 'btn btn-default downloadInvDetailPagePdf', 'escape' => false)); ?>
                                        </div>
                                    </div>
                                    <div class="table-responsive table-width">
                                        <table id="tblTransactionHistory" class="table mb-0 tr-cursor">
                                            <thead>
                                                <tr>
                                                    <th id="transactionDate">Date <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="transactionUser">User <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="transactionType">Type / Reason <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="transactionFrom">From <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="transactionTo">To <i class="fa fa-fw fa-sort"></i></th>
                                                    <th id="transactionQty">Quantity <i class="fa fa-fw fa-sort"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody id="transactionHistoryList">
                                                <?php
                                                if($transactionhistory->count() == 0){
                                                ?>
                                                <tr>
                                                    <td colspan="6">No Transactions found.</td>
                                                </tr>
                                                <?php 
                                                }else{
                                                    foreach($transactionhistory as $key=>$transaction){
                                                        $key = $key+1;
                                                        $evenOdd = (!empty($key) && ($key % 2) == 0) ? 'even' : 'odd'; 
                                                ?> 
                                                    <tr class="mainTR activeTble <?php echo $evenOdd; ?>">
                                                        <td><?php echo date('d-M-Y', strtotime($transaction['created'])); ?></td>
                                                        <td class="collapse-tr"><?php echo $transaction['users']['email']; ?></td>
                                                        <td class="collapse-tr">
                                                            <?php 
                                                            $transactionActionList = unserialize(TRANSACTION_ACTION_LIST);
                                                            $transactionindx = !empty($transaction->type) ? array_search($transaction->type, array_column($transactionActionList, 'id')) : '';
                                                            $actions = !empty($transactionindx) ? $transactionActionList[$transactionindx]['name'] : '';
                                                            echo $actions; 
                                                            ?>
                                                        </td>
                                                        <td class="collapse-tr"><?php echo $transaction['from_description']; ?></td>
                                                        <td class="collapse-tr"><?php echo $transaction['to_description']; ?></td>
                                                        <td class="collapse-tr"><?php echo $transaction['qty']; ?></td>
                                                    </tr>
                                                <?php }} ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="itemsHistory" class="tab-pane fade">
                                <div class="partsGpCls">
                                    <table class="table invitmhistory">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-2">Date</th>
                                                <th class="col-sm-1">User</th>
                                                <th class="col-sm-2">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            foreach($inventoryhistories as $invhistory){
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
                                                    <pre class="history-detail-block hide-block"><?php echo $data;?>
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
                <!-- Tabs end -->
            </div>
        </div>
       
    </div>
</div>

<!--- inventory print barcode popup --->
<?php echo $this->element('InventoryPopup/inventory_printbarcode_popup'); ?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<script>
var printBarCodeURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'printInventoriesBarCode']); ?>";
var updateInventoriesStatusURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'updateInventoriesStatus']); ?>";
var inventoriesDetPageURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'detail',]); ?>";
var addQuantitiesToHoldingBoxAjaxURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'addQuantitiesToHoldingBoxAjax']); ?>";
var inventoryItemsGenPDFURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'generateTransactionHistoryPdf']); ?>";
var printInventoriesListBarCodeURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'printInventoriesListBarCode']); ?>";

$(document).ready(function () {
    $("#capital-equipment-1, #capital-equipment-0").prop("disabled", true);

    $('[data-toggle="tooltip"]').tooltip({ 'placement': 'right'});


    var dataTable = $('#datatableQuantities').DataTable({
        'columnDefs': [
            { 'orderable': false, 'targets': '_all' },
            { className: "check noExl", "targets": [ 0 ] }
        ],
        'order': [0, 'asc'],
        //"searching": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[<?php echo PAGINATION_LIMIT;?>, 50, 100, -1], [<?php echo PAGINATION_LIMIT;?>, 50, 100, "All"]],
        "lengthChange": false,
        "ajax":{
            url:"<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'ajaxQantitiesSearch']); ?>",
            accepts: 'application/json', 
            type: "post",
            error: function(){
                $(".employees-grid-error").html("");
                $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employees-grid_processing").css("display","none");
            }
        }
    });

    
});

$(document).on('click', '.printInventoriesListBarcodes', function(e){
    var catalogprintsize = $('input[name=catalogprintsize]:checked').val();

    var inventoriesids = new Array();
    var inventory_id = window.location.pathname.split('/').pop();
    inventoriesids.push(inventory_id);
    
    var params = {catalogprintsize:catalogprintsize, inventoriesids:inventoriesids};
    downloadPDFAjax(printInventoriesListBarCodeURL, params);
});

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
var is_this_item_serialized ='';
</script>

<?php 
echo $this->Html->css('inventory');
echo $this->Html->script('jquery.sortElements'); 
echo $this->Html->script('inventories'); 
echo $this->Html->script('inventory_common');
?>