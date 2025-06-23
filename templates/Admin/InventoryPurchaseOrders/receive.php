<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;

echo $this->Html->css('inventory_purchase_order');
?>

<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($inventorypurchaseorders, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryPOReceive', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link('Purchase Orders', ['action' => 'index']).' / '.$this->Html->link($inventorypurchaseorders->po_number, ['action' => 'detail', $inventorypurchaseorders->id]).' / Receive'; ?></h2>
            
            <div class="btnWrap">
                <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                <button id="po-receive-left-arrow-button" type="button" class="btn btn-primary" disabled="disabled" data-val="first"><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>
                <button id="po-receive-previous-button" type="button" class="btn btn-primary" disabled="disabled" data-val="prev">Previous</button>
                <button id="po-receive-next-button" type="button" class="btn btn-primary" disabled="disabled" data-val="next">Next</button>
                <button id="po-receive-right-arrow-button" type="button" class="btn btn-primary" disabled="disabled" data-val="last"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
                <button id="po-receive-receive-button" type="button" class="btn btn-primary" disabled="disabled">Receive</button>
            </div>
        </div>
        <div class="page-content mt-35">
            <div class="formBGCls invreceievedblock">
                
                <div class="addPartBorder" id="tab-0">
                    <div class="invaddPageHeading">Quantity of Each Item to Receive</div>

                    <div class="row mt10">
                        <?php
                        $count = 0;
                        foreach($inventoryitems as $key=>$invitem){
                        if(empty($invitem['noninventory_item'])){
                        ?>
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id" data-val="<?php echo $invitem['id']; ?>"><?php echo $invitem['invitms']['name'].' (PN: '.$invitem['invitms']['part_number'].')';?>&nbsp;<span class="required">*</span></label>
                                <div class="col-sm-3">
                                    <?php 
                                    if(!isset($reqtype)){
                                        echo $this->Form->control('quantity-'.$count, array('type'=>'number','class'=>'form-control col-sm-12 quantity', 'placeholder' => '', 'label' => false, 'required' => 'required', 'value'=>0, 'data-val'=>$invitem['inventory_po_id'].'-'.$invitem['id']));
                                    }else{
                                        echo $inventoryvendors->name;
                                    }
                                    ?>
                                </div>
                                <div class="col-xs-4 no-left-pad"><label class="control-label" style="font-weight:100;"> of <?php echo $invitem['qty']; ?> ordered and <?php echo isset($inventorypoitemsrec[$invitem['id']]) ? $inventorypoitemsrec[$invitem['id']] : '0'; ?> already received.</label></div>
                            </div>
                        </div>
                        <?php $count++;}} ?>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group" style="float: right; padding-right: 50px;">
                                    <button id="po-receive-continue-button" type="button" class="btn btn-primary" data-val="countinue" disabled>Continue</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script> 
var updaterequeststatusURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'updaterequeststatus']); ?>";
var inventoryPOReceivedBlockURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'inventoryPOReceivedBlock']); ?>";
var bulkInvPORecAttachmentUploadURL = "<?php echo $this->Url->build(['controller'=>'InventoryPurchaseOrders', 'action'=>'bulkInvPORecAttachmentUpload']); ?>";
</script>
<?php echo $this->Html->script('inventory_purchase_order'); ?>