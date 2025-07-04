<div id="confirmCreateOTCInvoiceModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
                <h4 class="modal-title"><span class="gpTypeCls"></span>Create OTC Invoice?</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>This will create OTC invoice for customer ID "<?php echo $inventorycustomers->customer_name; ?>", Continue?</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary fetchCustOTCPopup" data-val='confirm_create_otc_invoice_btn'>Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>