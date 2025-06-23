<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;

?>

<div class="content sliding">
    <div class="outerWrapper">
         
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading"><?php echo $this->Html->link('Manufacturers', ['action' => 'index']).' / ';?> 
                <?php 
                echo $inventorymanufacturers->name; 
                
                if($inventorymanufacturers->status == '1'){
                    $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-approved">Active</div>';
                }else if($inventorymanufacturers->status == '0'){
                    $statushtml = '<div data-html="true" placement="left" class="inventory_request_status">Inactive</div>'; 
                }
                echo $statushtml;
                ?>
                
            </h2>
            <div class="btnWrap mb-5">
             <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_delete'] == 1) || $sessionUser['id'] == 1) {
                    if($inventorymanufacturers->status == '0'){
                        echo $this->Form->button('Activate', ['type' => 'button', 'class' => 'btn btn-primary ml-10 changeinvmanufacturer', 'data-val'=>'1']);
                    }else{
                        echo $this->Form->button('Deactivate', ['type' => 'button', 'class' => 'btn btn-danger ml-10 changeinvmanufacturer', 'data-val'=>'0']);
                    }
                }
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Html->link('Edit', ['action' => 'edit', $inventorymanufacturers->id], ['class'=>'btn btn-primary']);
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="addPartBorder">
                    <?php echo $this->element("Inventory/create_manufacturer", array('reqtype'=>'detail')); ?>
                </div>
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

<script> 
var updateVednorStatusURL = "<?php echo $this->Url->build(['controller'=>'InventoryManufacturers', 'action'=>'updateManufacturerStatus']); ?>";
var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>

<?php 
echo $this->Html->script('inventory_manufacturer'); 
echo $this->Html->script('inventory_common');
?>