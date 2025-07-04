<?php echo $this->Form->create(null, ['url' =>'', 'id' => 'formPopupSearch', 'autocomplete'=>'off']); ?>
<div class="action-bar dflex">
    <div class="input-group search-control mb-5">
        <input id="poSearchItem" name="searchItem" type="text" class="form-control" placeholder="Search Purchase Orders">
        <div class="input-group-btn">
            <button class="btn btn-default" type="button">
                <?php echo $this->Html->image('/images/icons/zoom.png', array('class'=>'zoom-img')); ?>
            </button>
        </div>
    </div>
    
    <div class="inputWrap btn-group sortWrap ml-10 mb-5">
        <label class="po-order-sortby dNoneMOb">Sort By</label>
        <select class="selectpicker" id="FilterBy" name="sortBy">
            <option value="">Sort By</option>
            <option value="0">Order Number</option>
            <option value="1">Reference Number</option>
            <option value="2">Vendor</option>
            <option value="3">Requestor</option>
            <option value="4">Account Code</option>
            <option value="5">Status</option>
            <option value="6">Cost</option>
            <option value="7" selected>Submitted Date</option>
        </select>
        <div class="ml-10">
            <button type="button" class="btn ml-5 clearapplyPOfilter">Clear</button>
            <button type="button" class="btn btn-primary btnOpenFilterPopup ml-5">Filter</button>
        </div>
    </div>

    <?php
    if(isset($isinvdetpage) && $isinvdetpage == 'invdetpage'){
        if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
        echo "<div>";
        echo $this->Html->link("Print", 'javascript:void(0);', array('class' => 'btn btn-default', 'id'=>'export-button-pdf', 'escape' => false));
        echo $this->Html->link("Export", 'javascript:void(0);', array('class' => 'btn btn-default exportPOListingDataExcel ml-5', 'escape' => false));
        echo $this->Html->link("Export Line Items", 'javascript:void(0);', array('class' => 'btn btn-default btnspace exportPOLineItemExcel', 'escape' => false));
        echo "</div>";
        }
    }
    ?>

</div>

<div class="tableScroll">
    <table id="datatableListingPage" class="table display dataTable table2excel <?php if((!empty($actionItems) && $actionItems['action']['action_view']==1) || $sessionUser['id'] == 1) { ?>invpotable<?php } ?>" width="100%">
        <thead>
            <tr>
                <th class="valign-t" scope="col"></th>
                <th class="valign-t" scope="col"><?php echo __('Order / Type'); ?></th>
                <th class="valign-t" scope="col"><?php echo __('Reference'); ?></th>
                <th class="valign-t" scope="col"><?php echo __('Vendor'); ?></th>
                <th class="valign-t" scope="col"><?php echo __('Requestor'); ?></th>
                <th class="valign-t" scope="col"><?php echo __('Account Code'); ?></th>
                <th class="valign-t" scope="col"><?php echo __('Cost'); ?></th>
                <th class="valign-t" scope="col"><?php echo __('Submitted'); ?></th>
                <th class="valign-t" scope="col"><?php echo __('PO Status'); ?></th>
            </tr>
        </thead>
    </table>
</div>
<?php echo $this->Form->end(); ?>