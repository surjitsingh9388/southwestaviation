<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Parts[]|\Cake\Collection\CollectionInterface $parts
 */
?>
<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>
<script src=
    "//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js">
</script>
<script type="text/javascript">
    var ajaxListPageSearchURL = "<?php echo $this->Url->build(['controller'=>'InventoryManufacturers', 'action'=>'ajaxInventoryManufacturersearch']); ?>";
    var pagelimit = <?php echo PAGINATION_LIMIT;?>;
    var inventoryRequestDetailsURL = "<?php echo $this->Url->build(['controller'=>'InventoryManufacturers', 'action'=>'detail',]); ?>";
    var pdfPagTitle = 'MANUFACTURER';
</script>

<?php 
echo $this->Html->css('inventory_manufacturer'); 

echo $this->Html->script('inventory_manufacturer'); 
echo $this->Html->script('inventory_common'); 
?>

<?php echo $this->Form->create('', ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Manufacturer</h2>
            
            <div class="text-right">
            <?php
            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1) {
                echo $this->Html->link("Create", array('action' => 'create'), array('class' => 'btn btn-primary', 'escape' => false));
            }
            ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="action-bar dflex">
                <div class="input-group search-control mb-0">
                    <input id="searchItem" name="searchItem" type="text" class="form-control" placeholder="Search Manufacturer">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div>
                </div>
                
                <div class="inputWrap btn-group sortWrap ml-10">
                    <label class="po-order-sortby">Sort By</label>
                    <select class="selectpicker" id="FilterBy" name="sortBy">
                        <option value="">Sort By</option>
                        <option value="0" selected>Name</option>
                        <option value="1">City</option>
                        <option value="2">State</option>
                        <option value="3">Country</option>
                        <option value="4">Contact Phone Number</option>
                    </select>
                    <div class="ml-10">
                        <button type="button" class="btn ml-5 resetfilterbtn">Clear</button>
                        <button type="button" class="btn btn-primary btnOpenFilterPopup ml-5">Filter</button>
                    </div>
                </div>

            </div>

            <div class="tableScroll">
                <table id="datatableListingPage" class="table display dataTable table2excel <?php if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $sessionUser['id'] == 1) { ?> invmanufacturertbl <?php } ?>" width="100%">
                    <thead>
                        <tr>
                            <th class="valign-t hide-block" scope="col"></th>
                            <th class="valign-t" scope="col"><?php echo __('Name'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('City'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('State/Province'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Country'); ?></th>
                            <th class="valign-t" scope="col"><?php echo __('Phone'); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="popupFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <?php echo $this->element('InventoryFilter/manufacturer_filter'); ?>
</div>
<?php echo $this->Form->end(); ?>

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>