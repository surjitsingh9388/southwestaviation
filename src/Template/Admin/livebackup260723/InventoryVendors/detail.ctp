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

</style>

<div class="content sliding">
    <div class="outerWrapper">
         
        <div class="btnWrapper">
            <h2 class="heading">
                <?php 
                echo $this->Html->link('Vendors', ['action' => 'index']).' / '.$inventoryvendors->name; 
                
                if($inventoryvendors->status == '1'){
                    $statushtml = '<div data-html="true" placement="left" class="inventory_request_status inventory-requests-approved">Active</div>';
                }else if($inventoryvendors->status == '0'){
                    $statushtml = '<div data-html="true" placement="left" class="inventory_request_status">Inactive</div>'; 
                }
                echo $statushtml;
                ?>
                
            </h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_delete'] == 1) || $sessionUser['id'] == 1) {
                    if($inventoryvendors->status == '0'){
                        echo $this->Form->button('Activate', ['type' => 'button', 'class' => 'btn btn-primary ml-10 changeinvvendors', 'data-val'=>'1']);
                    }else{
                        echo $this->Form->button('Deactivate', ['type' => 'button', 'class' => 'btn btn-danger ml-10 changeinvvendors', 'data-val'=>'0']);
                    }
                }
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Html->link('Edit', ['action' => 'edit', $inventoryvendors->id], ['class'=>'btn btn-primary']);
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                
                <div class="addPartBorder" style="padding-top:10px;">
                    <?php echo $this->element("Inventory/create_vendor", array('reqtype'=>'detail')); ?>
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
var updateVednorStatusURL = "<?php echo $this->Url->build(['controller'=>'InventoryVendors', 'action'=>'updatevendorstatus']); ?>";
</script>
<?php echo $this->Html->script('inventory_vendors'); ?>