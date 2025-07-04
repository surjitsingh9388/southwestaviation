<div id="createPTORequestsPopupModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php if(empty($pto_request_id)){ echo 'Create'; }else{echo 'Edit';} ?> PTO Requests</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php 
                    echo $this->element('PTORequests/create_pto_requests', ['counter'=>'1']); 
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary pto_request_savebtn">Save</button>
            </div>
        </div>
    </div>
</div>
<script>
    var addNewRowPTORequestAjaxURL = "<?php echo $this->Url->build(['controller'=>'PtoRequests', 'action'=>'addNewRowPTORequestAjax']); ?>";
    var savePTORequestAjaxURL = "<?php echo $this->Url->build(['controller'=>'PtoRequests', 'action'=>'savePTORequestAjax']); ?>";
</script>