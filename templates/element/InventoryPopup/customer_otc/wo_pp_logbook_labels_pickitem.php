<div id="woPPreviewLogbookLabelsPickItemModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <?php
        echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOPrintPreview'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Pick Items to Show on Log Book</h4>
                <!--input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
                <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
                <input type="hidden" name="wo_report_type" value="<?php echo $wo_report_type; ?>" />
                <input type="hidden" name="log_book_category" id="log_book_category" value="<?php echo $log_book_category; ?>" / -->
                <?php
                foreach($postData as $key=>$val){
                    if($key == 'customer_id' || $key == 'aircraft_id' || $key == 'section'){
                        continue;
                    }
                ?>
                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $val; ?>" />
                <?php } ?>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Corrective Action</th>
                                    <th scope="col">Show</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($correctiveActions as $correctiveAction){
                                    if(!empty($correctiveAction['wo_corrective_action'])){
                                ?>
                                <tr>
                                    <td><?php echo $correctiveAction['wo_item_position']; ?></td>
                                    <td><?php echo $correctiveAction['wo_corrective_action']; ?></td>
                                    <td><input type="checkbox" class="logbooklabel_chkbox" name="label_wo_item_id[]" value="<?php echo $correctiveAction['id']; ?>" checked /></td>
                                </tr>
                                <?php }} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12">
                    <div style="float:left;">
                        <a href="javascript:void(0);" style="color:#337ab7;" class="selectall_print_logbooklbl">Select All</a>&nbsp;&nbsp;
                        <a href="javascript:void(0);" style="color:#337ab7;" class="deselectall_print_logbooklbl">Clear Selection</a>
                    </div>
                    <div style="float:right;">
                        <button type="button" class="btn btn-primary continueWOPrintLogBookLblBtn">Continue</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal" >Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>