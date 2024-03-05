<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

echo $this->Html->css('inventory_purchase_order');
?>

<div class="content sliding">
    <div class="outerWrapper">
         
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link('Purchase Orders', ['action' => 'index']).' / Create'; ?></h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Save', ['type' => 'button', 'class' => 'btn btn-primary ml-10 invPOSaveBtn']);
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <?php echo $this->element('Inventory/create_purchase_order'); ?>
            </div>
        </div>
    </div>
</div>

<div id="inventoryItemModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            <?php
            echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmItemCatalog', 'autocomplete'=>'off'));
            ?>
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Create New Part</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <?php echo $this->element('Inventory/create_new_part', array('isinvrequestpage'=>1)); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary invItemSaveBtn" disabled>Save</button>
            </div>
            <?php 
            echo $this->Form->end(); 
            ?>
        </div>
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

<div id="addressAddModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 35%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Create Address</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <?php echo $this->element('Inventory/add_new_address'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary invaddresssavebtn">Save</button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->element('InventoryPopup/purchase_order_inventory_item_search'); ?>

<script> 
var getInventoryPOItemDropdownURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'inventoryPOItemDropDown']); ?>";
var saveInventoryItemsURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'saveInventoryItems']); ?>";
var uploadInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'bulkInvPOAttachmentUpload']); ?>";
var deleteInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'deleteInventoryAttachment']); ?>";
var saveInventoryVendorURL = "<?php echo $this->Url->build(['controller'=>'InventoryVendors', 'action'=>'saveInventoryVendor']); ?>";
var updatePurchaseOrderStatusURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'updatePurchaseOrderStatus']); ?>";
var getStatesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getStatesList']); ?>";
var saveInventoryAddressURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'saveInventoryAddress']); ?>";
var getInventoryItemDetailURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'getInventoryItem']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>

<?php 
echo $this->Html->script('jquery.sortElements'); 
echo $this->Html->script('inventory_purchase_order');
echo $this->Html->script('inventory_common'); 
?>