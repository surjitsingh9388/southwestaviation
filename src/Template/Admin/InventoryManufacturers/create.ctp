<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link('Manufacturers', ['action' => 'index']).' / Create'; ?></h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Save', ['type' => 'button', 'class' => 'btn btn-primary ml-10 manufacturersavebtn']);
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="addPartBorder">
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