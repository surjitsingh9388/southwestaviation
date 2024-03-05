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

    .mt5 {
        margin-bottom: 10px !important;
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

</style>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link($inventorymanufacturers->name, ['action' => 'detail', $inventorymanufacturers->id]).' / Edit'; ?></h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Save', ['type' => 'button', 'class' => 'btn btn-primary ml-10 manufacturersavebtn']);
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="addPartBorder" style="padding-top:10px;">
                    <?php echo $this->element("Inventory/create_manufacturer"); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var getStatesList = "<?php echo $this->Url->build(['controller' => 'addresses', 'action' => 'getStatesList']); ?>";
var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>
<?php 
echo $this->Html->script('inventory_manufacturer'); 
echo $this->Html->script('inventory_common'); 
?>