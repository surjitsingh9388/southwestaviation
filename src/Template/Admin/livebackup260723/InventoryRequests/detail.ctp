<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

if(!empty($part->plane_id) && !empty($subResults)) {
    $pdfCheck = 'style="pointer-events: auto;"';
} else {
    $pdfCheck = 'style="pointer-events: none;"';
}
?>
<style>
    
    #itemGeneral{
        padding:10px;
    }
    
    .mb-3 {
        margin-bottom: 5px !important;
    }
    h5{
        text-align:center;
        font-weight:bold;
    }
    
    .sinfo{
        margin-top: 15px;
    }

    .mt5{
        margin-top: 10px;
    }

    .addPageHeading {
        font-size: 11pt;
        background-color: #2C3E50;
        color: #ecf0f1;
        padding: 10px 15px;
        margin-top: 0;
        margin-left: 0px;
        height: 40px;
    }

    .upload-area{
        width: 100%;
        min-height: .01%;
        border: 2px solid lightgray;
        border-radius: 3px;
        margin: 0 auto;
        margin-top: 10px;
        text-align: center;
        overflow: auto;
    }

    #filetbody a{
        color:blue !important;
    }

    .document-name {
        white-space: nowrap;
        overflow: hidden;
        -ms-text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis;
    }

    .tab-content th{
        background-color:#2c3e50;
        color:white;
    }

    .tab-content td{
        text-align:left;
    }
    #invrequeststbl a{
        color: #092E6E !important;
        text-decoration:none;
    }
    
    .heading div {
        padding: 2px !important;
        margin-left: 10px !important;
    }

    #invRequestHistoryTable a {
        color: #23527c;
        text-decoration: underline;
        cursor: pointer;
    }

    #invPOHistoryTable a {
        color: #23527c;
        text-decoration: underline;
        cursor: pointer;
    }

</style>

<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($invenotryrequests, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryRequest', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper">
            <h2 class="heading">
                <?php 
                echo $this->Html->link('Inventory Requests', ['action' => 'index']).' / '.$invenotryrequests->request_number.'&nbsp;'; 
                
                if($invenotryrequests->request_status == '1'){
                    $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-approved">Approved</span>';
                }else if($invenotryrequests->request_status == '0'){
                    $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-pending">Pending Review</span>';
                }else if($invenotryrequests->request_status == '2'){
                    $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-canceled">Canceled</span>';
                }else if($invenotryrequests->request_status == '5'){
                    $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-created">PO Created</span>';
                }else if($invenotryrequests->request_status == '3'){
                    $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-denied">Denied</span>';
                }else if($invenotryrequests->request_status == '4'){
                    $statushtml = '<span data-html="true" placement="left" class="inventory_request_status inventory-requests-closed">Close</span>';
                }
                
                if($invenotryrequests->status == '0'){
                    $statushtml .= '<span data-html="true" placement="left" class="inventory_request_status">Inactive</span>'; 
                }
                echo $statushtml;
                ?>
                
            </h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_delete'] == 1) || $sessionUser['id'] == 1) {
                    if($invenotryrequests->status == '0'){
                        echo $this->Form->button('Activate', ['type' => 'button', 'class' => 'btn btn-default ml-10 changeinvrequests', 'data-val'=>'1']);
                    }else{
                        echo $this->Form->button('Deactivate', ['type' => 'button', 'class' => 'btn btn-danger ml-10 changeinvrequests', 'data-val'=>'6']);
                    }
                }
                ?>

                <div class="btn-group">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                    <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                        <?php 
                        if($invenotryrequests->status == '1'){ 
                            if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                        ?>
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'edit', $invenotryrequests->id]); ?>">Edit</a></li>
                        <li><a class="dropdown-item changeinvrequests" href="javascript:void(0);" data-val="2">Cancel Request</a></li>
                        <li><a class="dropdown-item changeinvrequests" href="javascript:void(0);" data-val="4">Close Request</a></li>
                        <?php } ?>
                        <?php 
                        if($invenotryrequests->request_status == '0'){ 
                            if((!empty($actionItems) && $actionItems['action']['action_approve_deny'] == 1) || $sessionUser['id'] == 1) {
                        ?>
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'approval', $invenotryrequests->id]); ?>">Approve/Deny</a></li>
                        <?php 
                            }
                        }else{ 
                            if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                        ?>
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'create', "?" => ["porequestid" => $invenotryrequests->id]]); ?>">Generate PO</a></li>
                        <?php 
                            }    
                        }
                        }
                        if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                        ?>
                        <li>
                            <a class="dropdown-item linktoanotherorder" href="javascript:void(0);">Link to Another Order</a>
                        </li>
                        <li><a class="dropdown-item downloadInvDetailPagePdf" href="javascript:void(0);">Print</a></li>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                
                <div class="addPartBorder" style="padding-top:10px;padding-left: 10px;">
                    <div class="row">
                        <label class="col-lg-2" for="plane_id">Title</label>
                        <div class="content-display col-lg-4"><?php echo $invenotryrequests->title; ?></div>
                        
                        <label class="col-lg-2" for="plane_id">Description</label>
                        <div class="content-display col-lg-4"><?php echo $invenotryrequests->description; ?></div>
                        
                    </div>
                    <div class="row">
                        <label class="col-lg-2" for="plane_id">Requested By</label>
                        <div class="content-display col-lg-4"><?php echo $invenotryrequests->requested_by; ?></div>
                    
                        <label class="col-lg-2" for="plane_id" >Need By</label>
                        <div class="content-display col-lg-4"><?php echo date('d-M-Y',strtotime($invenotryrequests->need_by)); ?></div>
                       
                    </div>
                    <div class="row">
                        <label class="col-lg-2">Urgency</label>
                        <div class="content-display col-lg-4"><?php 
                        $urgency = unserialize(URGENCY);

                        echo !empty($invenotryrequests->urgency) ? $urgency[$invenotryrequests->urgency] : ''; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-lg-2">Comments</label>
                        <div class="col-lg-4">
                            <?php echo $invenotryrequests->comment; ?>
                        </div>
                    </div>
                    

                </div>

                <div style="clear: both;"></div>

                <!-- Tabs Start -->
                <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemGeneral">Line Items</a></li>
                            <li>
                                <a data-toggle="tab" href="#itemsLinkedOrder">Linked Order 
                                    <span class="linkordercountblock"><?php echo ($linkorderdata['linkedpurchaseorderscount']+$linkorderdata['linkedrepairorderscount']+$linkorderdata['linkedshippingorderscount']+$linkorderdata['linkedrequestscount']); ?></span>
                                </a>
                            </li>
                            <li><a data-toggle="tab" href="#itemHistory">History</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemGeneral" class="tab-pane fade in active"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative">
                                    
                                    <table class="table upload-area" id="uploadfile">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-2">Item</th>
                                                <th class="col-sm-2">Qty Requested</th>
                                                <th class="col-sm-1">Location Needed</th>
                                            </tr>
                                        </thead>
                                        <tbody id="invrequeststbl">
                                            <?php
                                            $defaultUOM = unserialize(DEFAULT_UOM);
                                            foreach($inventoryrequestitems as $val){
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo isset($val['invitms']['name']) ? '<a href="'.$this->Url->build(['controller'=>'InventoryItems', 'action'=>'detail', $val['inventory_item_id']]).'">'.$val['invitms']['name'].' ('.$val['invitms']['part_number'].')'.'</a>' : $val['noninventory_item']; ?></td>
                                                <td><?php echo $val['qty'].' '.$defaultUOM[$val['uom']]; ?></td>
                                                <td><?php echo isset($val['invloc']['location_name']) ? $val['invloc']['location_name'] : ''; ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- general-tab-section end -->

                            <div id="itemsLinkedOrder" class="tab-pane fade">
                            <?php echo $this->element('Inventory/linked_order_list', array('sessionUser'=>$sessionUser)); ?>
                            </div>

                            <div id="itemHistory" class="tab-pane fade">
                                <div class="g-0 bg-light position-relative">
                                    
                                    <table class="table" id="invRequestHistoryTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-2">Date</th>
                                                <th class="col-sm-2">User</th>
                                                <th class="col-sm-2">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach($inventoryrequesthistories as $invhistory){
                                                $data = @unserialize($invhistory['description']);
                                                if ($data === false) {
                                                    $data = $invhistory['description'];
                                                }else{
                                                    $data = json_encode($data, JSON_PRETTY_PRINT);
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

<!---- include linked order popup ------>
<?php
echo $this->element('InventoryPopup/linked_other_order_popup');
?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<script> 
var updaterequeststatusURL = "<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'updaterequeststatus']); ?>";
var inventoryItemsGenPDFURL = "<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'generateInvRODetPdf']); ?>";
var createPurchaseOrderURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'create']); ?>";
var createRepairOrderURL = "<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'create']); ?>";
var createShippingOrderURL = "<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'create']); ?>";
var createRequestURL = "<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'create']); ?>";
var inventoryExistOrderDropDownURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'inventoryExistOrderDropDown']); ?>";
var inventoryExistOrderLinkSaveURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'existingOrderLinkSave']); ?>";
var inventoryUnlinkExistOrderURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'unlinkExistingOrder']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>
<?php 
echo $this->Html->script('inventory_common');
echo $this->Html->script('inventory_request'); 
?>