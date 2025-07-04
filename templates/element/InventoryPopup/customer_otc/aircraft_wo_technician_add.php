<div id="aircraftWOTechnicianAddModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 25%;">
        <?php
        //echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryCustomersNotes'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Technician to Item</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12">Double click on the list to select the proper item.</div>
                <div class="col-md-12">
                    <div class="form-group wo-technican-list">
                        <?php
                        foreach($userlist as $user_id=>$full_name){
                            if(!in_array($user_id, $wotechnicianids)){
                        ?>
                        <div data-val="<?php echo $user_id; ?>"><?php echo $full_name; ?></div>
                        <?php }} ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default float-right" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>