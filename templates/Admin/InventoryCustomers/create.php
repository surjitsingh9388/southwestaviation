<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;

echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomers', 'autocomplete' => 'off'));
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Create Customer/OTC</h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Save', ['type' => 'submit', 'class' => 'btn btn-primary ml-10 invCustomerSaveBtn']);
                }
                ?>
            </div>
        </div>
       
        <div class="page-content mt-35">
            <div class="formBGCls">
                
            </div>
        </div>
    </div>
</div>

<!------------------- upload media popup ------------------------>
<?php echo $this->element('InventoryPopup/inventory_customer_upload_media'); ?>

<!------------------- add notes popup ------------------------>
<?php echo $this->element('InventoryPopup/inventory_customer_add_notes'); ?>

<?php 
echo $this->Form->end(); 
?>

<script>
var uploadInventoriesAttURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'bulkMediaUpload']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>

<?php
echo $this->Html->css('inventory_customer_otc');
echo $this->Html->script('inventory_common');
echo $this->Html->script('inventory_customers');
?>