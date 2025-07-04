<div id="woPrintPreviewLogbookLabelsModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <?php
        echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOPrintPreview'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select a Log Book Category</h4>
                <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
                <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
                <input type="hidden" name="wo_report_type" value="<?php echo $wo_report_type; ?>" />
                <input type="hidden" name="log_book_category" id="log_book_category" value="" />
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>Double click to the list to select the proper item.</p>
                        <p>All Categories of this Work Order</p>
                        <div class="form-group listitems listoflogbooklabels">
                            <?php
                            $aircraftWOLogBookCategory = unserialize(AIRCRAFT_WORKORDER_LOGBOOK_CATEGORY);
                            foreach($logbokCategories as $category){
                                if(isset($category['wo_item_overview']['log_book_category']) && !empty($category['wo_item_overview']['log_book_category']) && (empty($category['wo_item_overview']['donot_use_inlogbook']))){
                            ?>
                            <div class="col-md-12 logbooklabelsbox" data-val="<?php echo $category['wo_item_overview']['log_book_category']; ?>"><?php echo $aircraftWOLogBookCategory[$category['wo_item_overview']['log_book_category']]; ?></div>
                            <?php }} ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" >Cancel</button>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>