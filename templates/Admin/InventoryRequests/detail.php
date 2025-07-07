<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;

?>

<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($invenotryrequests, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryRequest', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading">
                <?php 
                echo $this->Html->link('Inventory Requests', ['action' => 'index']).' / '.$invenotryrequests->request_number.'&nbsp;'; 
                
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
                        <!--li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'approval', $invenotryrequests->id]); ?>">Reopen Work Order</a></li>
                        <li><a class="dropdown-item" href="<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'approval', $invenotryrequests->id]); ?>">Final Inspections</a></li-->
                        <?php 
                            }
                        }else if($invenotryrequests->request_status == '1'){ 
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
                <div class="addPartBorder pt10 pl10 pr10">
                    <div class="row">
                        <label class="col-sm-2 col-xs-4" for="plane_id">Title</label>
                        <div class="content-display col-sm-4 col-xs-6"><p class="form-control-static"><?php echo $invenotryrequests->title; ?></p></div>
                        
                        <label class="col-sm-2 col-xs-4" for="plane_id">Description</label>
                        <div class="content-display col-sm-4 col-xs-6"><p class="form-control-static"><?php echo $invenotryrequests->description; ?></p></div>
                        
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-xs-4" for="plane_id">Requested By</label>
                        <div class="content-display col-sm-4 col-xs-6"><p class="form-control-static"><?php echo $invenotryrequests->requested_by; ?></p></div>
                    
                        <label class="col-sm-2 col-xs-4" for="plane_id" >Need By</label>
                        <div class="content-display col-sm-4 col-xs-6"><p class="form-control-static"><?php echo date('d-M-Y',strtotime($invenotryrequests->need_by)); ?></p></div>
                       
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-xs-4">Urgency</label>
                        <div class="content-display col-sm-4 col-xs-6"><?php 
                        $urgency = unserialize(URGENCY);

                        $urgency = !empty($invenotryrequests->urgency) ? $urgency[$invenotryrequests->urgency] : ''; 
                        echo '<p class="form-control-static">'.$urgency.'</p>';
                        ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-xs-4">Comments</label>
                        <div class="col-sm-4 col-xs-6">
                        <p class="form-control-static"><?php echo $invenotryrequests->comment; ?></p>
                        </div>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <!-- Tabs Start -->
                <div id="aircraftTabs" class="tab-pad">
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
                                    
                                    <table class="table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-2">Item</th>
                                                <th class="col-sm-2">Qty Requested</th>
                                                <th class="col-sm-2">Work Order No/Item No</th>
                                                <th class="col-sm-2">Location Needed</th>
                                                <th class="col-sm-2">Action</th>
                                                <th class="col-sm-2">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="invrequeststbl">
                                            <?php
                                            $defaultUOM = unserialize(DEFAULT_UOM);
                                            foreach($inventoryrequestitems as $val){
                                                $work_order_item = !empty($val['work_order']['work_order_no']) ? $val['work_order']['work_order_no'].'-'.$val['wo_item']['wo_item_position'] : '';
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo isset($val['invitms']['name']) ? '<a href="'.$this->Url->build(['controller'=>'InventoryItems', 'action'=>'detail', $val['inventory_item_id']]).'" class="inventory-select-item">'.$val['invitms']['name'].' ('.$val['invitms']['part_number'].')'.'</a>' : $val['noninventory_item']; ?>
                                                </td>
                                                <td><?php echo $val['qty'].' '.$defaultUOM[$val['uom']]; ?></td>
                                                <td><?php echo !empty($work_order_item) ? '<a href="'.$this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'loadWorkOrder', $val['work_order_id'].'-'.$val['wo_item']['wo_item_position']]).'">'.$work_order_item.'</a>' : ''; ?></td>
                                                <td><?php echo isset($val['invloc']['location_name']) ? $val['invloc']['location_name'] : ''; ?></td>
                                                <?php if(!empty($val['work_order_id'])){ ?>
                                                <td>
                                                    <button type="button" class="btn btn-success request_item_approve" data-val="1" request-item-id = "<?php echo $val['id']; ?>">Approve</button>
                                                    <button type="button" class="btn btn-danger request_item_deny" data-val="3" request-item-id = "<?php echo $val['id']; ?>">Deny</button>
                                                </td>
                                                <td>
                                                    <select class="form-select request_item_status" request-item-id = "<?php echo $val['id']; ?>" id="request_item_status_<?php echo $val['id']; ?>">
                                                        <option value="">Select Stats</option>
                                                        <option value="1" <?php if($invenotryrequests->request_status == '1'){ echo 'selected'; } ?>>Approve</option>
                                                        <option value="3" <?php if($invenotryrequests->request_status == '3'){ echo 'selected'; } ?>>Deny</option>
                                                        <option value="6" <?php if($invenotryrequests->request_status == '6'){ echo 'selected'; } ?>>Ordered</option>
                                                        <option value="7" <?php if($invenotryrequests->request_status == '7'){ echo 'selected'; } ?>>Received</option>
                                                        <option value="8" <?php if($invenotryrequests->request_status == '8'){ echo 'selected'; } ?>>On Hold</option>
                                                    </select>
                                                </td>
                                                <?php }else{ ?>
                                                <td></td>
                                                <td></td>
                                                <?php } ?>
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
                                                <th class="col-sm-2 col-xs-4">Date</th>
                                                <th class="col-sm-2 col-xs-4">User</th>
                                                <th class="col-sm-2 col-xs-4">Description</th>
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
                                                    <pre class="history-detail-block hide-block"><?php echo $data;?>
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
var updateRequestItemStatusURL = "<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'updateRequestItemStatus']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>
<?php 
echo $this->Html->css('inventory_request');
echo $this->Html->script('inventory_common');
echo $this->Html->script('inventory_request'); 
?>