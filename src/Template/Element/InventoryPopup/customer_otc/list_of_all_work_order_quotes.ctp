<div id="listOfAllWorkOrdersQuotesModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 70%;">
        <?php
        //echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomersNotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $search_page_heading; ?></h4>
            </div>
            <div class="modal-body">
                <?php
                if($search_by != 'other_find_option' && $search_by != 'advanced_find_option' && $search_by != 'advanced_parts_search'){
                ?>
                <div class="col-md-12"><?php echo $search_page_heading; ?></div>
                <?php 
                }
                if($search_by == 'other_find_option' || $search_by == 'advanced_find_option' || $search_by == 'advanced_parts_search'){
                ?>
                <div class="col-md-12">
                    <button type="button" class="btn btn-default float-left">Print</button>
                </div>
                <?php } ?>
                <div class="col-md-12 mt5">
                    <?php echo $this->InventoryAircraftWorkOrder->getListOfAllWorkOrderQuotesHTML($aircrafAllWorkOrders, $search_by); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var filterOpenWODepartsURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'filterOpenWODeparts']); ?>";
</script>