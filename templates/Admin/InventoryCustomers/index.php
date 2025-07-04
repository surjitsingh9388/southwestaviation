<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Parts[]|\Cake\Collection\CollectionInterface $parts
 */
?>
<?php $sessionUser = $this->request->getSession()->read('Auth');; ?>

<script type="text/javascript">
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'ajaxInventoryCustomerSearch']); ?>";
    var pagelimit = <?php echo PAGINATION_LIMIT;?>;
    var inventoryRequestCreateInfoURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'customerinfo',]); ?>";
    
    var pdfPagTitle = 'CUSTOMEROTC';
    
</script>

<?php 
echo $this->Html->script('inventory_customers'); 
echo $this->Html->script('inventory_common'); 
?>

<?php echo $this->Form->create(null, ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Customerd/OTC</h2>
            
            <div style="float:right;">
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1) {
                ?>
                <button type="button" class="btn btn-primary" onclick="$('#addNewCustomerModal').modal('show');">Create</button>
                <?php } ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="action-bar dflex">
                <div class="input-group search-control mb-0">
                    <input id="searchItem" name="searchItem" type="text" class="form-control" placeholder="Search Customer/OTC">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div>
                </div>
                
                <div class="inputWrap btn-group sortWrap" style="margin-left:10px;">
                    <label style="color: white; font-size: 20px; padding-right: 10px;">Sort By</label>
                    <select class="selectpicker" id="FilterBy" name="sortBy">
                        <option value="">Sort By</option>
                        <option value="1" selected>Customer Name</option>
                        <option value="2">Contact Name</option>
                        <option value="3">City</option>
                        <option value="4">State</option>
                        <option value="5">Country</option>
                        <option value="6">Contact Phone Number</option>
                    </select>
                    <div style="margin-left: 10px;">
                        <button type="button" class="btn ml-5 resetfilterbtn">Clear</button>
                        <!--button type="button" class="btn btn-primary btnOpenFilterPopup" style="margin-left: 5px;">Filter</button-->
                    </div>
                </div>
            </div>

            <div class="tableScroll">
                <table id="datatableListingPage" class="table display dataTable <?php if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $sessionUser['id'] == 1) { ?> invcustomerstbl <?php } ?>" width="100%">
                    <thead>
                        <tr>
                            <th style="vertical-align: top; width:200px;" scope="col"><?php echo __('Customer Name'); ?></th>
                            <th style="vertical-align: top; width:200px;" scope="col"><?php echo __('Contact Name'); ?></th>
                            <th style="vertical-align: top; width:150px;" scope="col"><?php echo __('City'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('State/Province'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Country'); ?></th>
                            <th style="vertical-align: top;" scope="col"><?php echo __('Phone'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<?php
echo $this->Html->css('inventory_customer_otc');
?>
<!------------------- create otc invoice popup ------------------------>
<?php echo $this->element('InventoryPopup/customer_otc/create_new_customer'); ?>