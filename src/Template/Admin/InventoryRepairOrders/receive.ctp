<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

?>

<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($InventoryRepairOrders, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryROReceive', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading"><?php echo $this->Html->link('Repair Orders', ['action' => 'index']).' / '.$this->Html->link($InventoryRepairOrders->ro_number, ['action' => 'detail', $InventoryRepairOrders->id]).' / Receive'; ?></h2>
            
            <div class="btnWrap">
                <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <button id="ro-receive-left-arrow-button" type="button" class="btn btn-primary" disabled="disabled" data-val="first"><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>
                <button id="ro-receive-previous-button" type="button" class="btn btn-primary" disabled="disabled" data-val="prev">Previous</button>
                <button id="ro-receive-next-button" type="button" class="btn btn-primary" <?php if($invitemnotreccount == 1){ ?>disabled="disabled" <?php } ?> data-val="next">Next</button>
                <button id="ro-receive-right-arrow-button" type="button" class="btn btn-primary" <?php if($invitemnotreccount == 1){ ?>disabled="disabled" <?php } ?> data-val="last"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
                <button id="ro-receive-button" type="button" class="btn btn-primary" disabled="disabled">Receive</button>
            </div>
        </div>
        <div class="page-content mt-35">
            <div class="formBGCls">
                <?php echo $this->element("Inventory/repair_order_receive_add"); ?>               
            </div>
        </div>

    </div>
</div>

<script>
var bulkInvPORecAttachmentUploadURL = "<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'bulkInvRORecAttachmentUpload']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>
<?php 
echo $this->Html->script('inventory_repair_order'); 
echo $this->Html->script('inventory_purchase_order'); 
echo $this->Html->script('inventory_common'); 
?>