<div id="customerPhoneAddModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 35%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Add Phone Number</h4>
            </div>
            <div class="modal-body" style="overflow-y:none;">
                <div class="page-content">
                    <?php echo $this->Form->create($inventorycustomerphones, ['action'=>'savephonenumber', 'id' => 'frmCustomerAddPhone', 'autocomplete'=>'off']); ?>
                    <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Phone Number&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php 
                                echo $this->Form->control('phone_number', array('class'=>'form-control col-md-8 col-xs-12 mf_name no-special-char', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'customer_phone_number'));
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    echo $this->Form->end(); 
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary customerPhoneSaveBtn">Save</button>
            </div>
        </div>
    </div>
    <script>
        var saveCustomerPhoneNumberURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveCustomerPhoneNumber']); ?>";
    </script>
</div>