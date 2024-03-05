<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Parts[]|\Cake\Collection\CollectionInterface $parts
 */
?>
<?php $sessionUser = $this->request->session()->read('Auth.User'); ?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Tools</h2>
            
            <div class="float-right"><button type="button" class="btn btn-default add-new-tool-popup">Add New Tool</button></div>
        </div>
        
        <div class="page-content mt-35">
            <div class="action-bar dflex">
                <div class="input-group search-control mb-0">
                    <input id="searchItem" name="searchItem" type="text" class="form-control" placeholder="Search Tools">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="inventoryToolsTbl" class="table mb-0">
                    <thead>
                        <tr>
                            <th id="toolName">Tool Name <i class="fa fa-fw fa-sort"></i></th>
                            <th id="toolDescription">Description <i class="fa fa-fw fa-sort"></i></th>
                            <th id="toolModel">Model <i class="fa fa-fw fa-sort"></i></th>
                            <th id="toolSerialNumber">Serial Number <i class="fa fa-fw fa-sort"></i></th>
                            <th id="toolLocation">Location <i class="fa fa-fw fa-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody id="inventoryToolsList">
                        <?php
                        $toolshtml = $this->InventoryToolHTML->toolsTableHTML($toolsdata);
                        echo $toolshtml;
                        ?>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="inventorytoolspopup"></div>

<?php 
echo $this->HTML->script('inventory_tools'); 
echo $this->HTML->css('inventory_tools'); ?>
?>

<script type="text/javascript">
    var openToolAddPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryTools', 'action'=>'openToolAddPopup',]); ?>";
    var saveInventoryToolURL = "<?php echo $this->Url->build(['controller'=>'InventoryTools', 'action'=>'saveInventoryTool',]); ?>";
    var openToolDetailPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryTools', 'action'=>'openToolDetailPopup',]); ?>";
</script>