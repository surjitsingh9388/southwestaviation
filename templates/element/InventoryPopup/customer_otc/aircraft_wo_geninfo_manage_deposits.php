<div id="aircarftWOOptionGenInfoMngDepositsModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Manage Deposits</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            echo $this->Form->create($optiongeninfodeposits, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWOOptionGenInfoDeposit'));
                            ?>
                            <input type="hidden" name="general_info_id" value="<?php echo $general_info_id; ?>" />
                            <input type="hidden" name="general_info_deposit_id" id="general_info_deposit_id" value="<?php echo @$optiongeninfodeposits->id; ?>" />
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Add Deposit</legend>
                                     <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Amount to Add</label>
                                            <div class="form-input-frame">
                                                <?php 
                                                $amount_to_add = !empty($optiongeninfodeposits->amount_to_add) ? '$'.number_format((float)$optiongeninfodeposits->amount_to_add, 2) : '$0.00';

                                                echo $this->Form->control('amount_to_add', array('type'=>'text', 'class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00', 'id'=>'amount_to_add', 'value'=>$amount_to_add)); ?>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Currency</label>
                                            <div class="form-input-frame">
                                                <?php
                                                $currencyList = unserialize(CURRENCYOVERRIDE);
                                                echo $this->Form->control('currency', array('options' => $currencyList, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'deposit_currency', 'value'=>'1'));
                                                ?>   
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Payment Method</label>
                                            <div class="form-input-frame">
                                                <?php
                                                $woPaymentMethod = unserialize(WOPAYMENTMETHOD);
                                                echo $this->Form->control('payment_method', array('options' => $woPaymentMethod, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'payment_method', 'value'=>'1'));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Check Number</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('check_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                            </div>
                                        </div>
                                    </div>
                                   <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Credit Card Type</label>
                                            <div class="form-input-frame">
                                                <?php
                                                $typeOfOTCCreditCard = unserialize(TYPEOFOTCCREDITCARD);
                                                echo $this->Form->control('credit_card_type', array('options' => $typeOfOTCCreditCard, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'credit_card_type'));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Name on Credit Card</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('name_of_credit_card', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-md-12 col-sm-12 col-xs-12">
                                     <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Credit Card(Last 4)</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('credit_card', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                            </div>
                                        </div>
                                    </div>
                                   <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">More Info</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('more_info', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Expires</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('expires', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">CC Authoration Number</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('cc_authoration_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'')); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <div class="form-group d-flex">
                                            <label class="control-label" for="reference">Date</label>
                                            <div class="input-group date datePicker">
                                                <?php 
                                                $deposit_date = isset($optiongeninfodeposits->deposit_date) && !empty($optiongeninfodeposits->deposit_date) ? $optiongeninfodeposits->deposit_date : date('m-d-Y');
                                                echo $this->Form->Text('deposit_date', array('class' => 'form-control', 'id' => 'po_item_shelf_life', 'placeholder' => '', 'label' => false, 'value'=>$deposit_date)); ?>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Paid By</label>
                                            <div class="form-input-frame">
                                                <?php 
                                                $billtocustomer = [$inventorycustomers['id']=>$inventorycustomers['customer_name']];

                                                echo $this->Form->control('paid_by', array('options' => $billtocustomer, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'paid_by', 'value'=>$inventorycustomers['id'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <button type="button" class="btn btn-primary float-right saveWOOptionGenInfoDeposits">Add</button>
                                    </div>
                                </div>
                            </fieldset>
                            <?php echo $this->Form->end(); ?>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">List of Deposits</legend>
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Date</th>
                                                <th scope="col">Paid By</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Check #</th>
                                                <th scope="col">Payment Method</th>
                                            </tr>
                                        </thead>
                                        <tbody class="wo-gen-info-deposit-list">
                                            <?php
                                            $woPaymentMethod = unserialize(WOPAYMENTMETHOD);
                                            $deposittr = '';
                                            $total_amount = 0;
                                            foreach($optiongeninfodepositlist as $deposits){
                                                $deposittr .= '<tr class="wo-gen-info-deposit" data-val="'.$deposits['id'].'">';
                                                $deposittr .= '<td>'.$deposits['deposit_date'].'</td>';
                                                $deposittr .= '<td>'.$deposits['customers']['customer_name'].'</td>';
                                                $deposittr .= '<td>'.(!empty($deposits['amount_to_add']) ? '$'.number_format((float)$deposits['amount_to_add'], 2) : '$0.00').'</td>';
                                                $deposittr .= '<td>'.$deposits['check_number'].'</td>';
                                                $deposittr .= '<td>'.$woPaymentMethod[$deposits['payment_method']].'</td>';
                                                $deposittr .= '</tr>';

                                                $total_amount += $deposits['amount_to_add'];
                                            }
                                            echo $deposittr;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default remove-wo-gen-info-deposit">Delete</button>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4" for="reference">Total Amount</label>
                                            <div class="col-md-8">
                                                <?php 
                                                $total_amount = !empty($total_amount) ? '$'.number_format((float)$total_amount, 2) : '$0.00';
                                                echo $this->Form->control('deposit_total_amount', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>$total_amount, 'id'=>'deposit_total_amount')); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    var saveWOViewOptionGenInfoDepositURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'saveWOViewOptionGenInfoDeposit']); ?>";
    var deleteWOViewOptionGenInfoDepositURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'deleteWOViewOptionGenInfoDeposit']); ?>";
</script>