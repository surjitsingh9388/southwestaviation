<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;

?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper flex-column-mob">
            <h2 class="heading"><?php echo $this->Html->link('Vendors', ['action' => 'index']).' / '.$this->Html->link($inventoryvendors->name, ['action' => 'detail', $inventoryvendors->id]).' / Edit'; ?></h2>
            <div class="btnWrap mb-10">
                <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Save', ['type' => 'button', 'class' => 'btn btn-primary ml-10 vendorsavebtn']);
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="addPartBorder pl10 pr10 pt10">
                    <?php echo $this->element("Inventory/create_vendor"); ?>
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
echo $this->Html->script('inventory_vendors'); 
echo $this->Html->script('inventory_common');
?>