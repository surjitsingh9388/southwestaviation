<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
             <h3 id="myModalLabel3">Confirmation</h3>

        </div>
        <div class="modal-body">
            <p><?php echo env('SHOW_ON_CONFIRM_MESSAGE'); ?></p>
        </div>
        <div class="modal-footer">
            <button class="closeConfirmation btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
            <button class="btn-primary btn" id="submitForm">Submit</button>
        </div>
    </div>
</div>  
