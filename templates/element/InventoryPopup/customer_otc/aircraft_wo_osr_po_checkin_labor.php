<div id="aircraftWOOSRPOCheckInLaborModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Add Arrived Service Item for Purchase Order # <?php echo @$woosrinfopo->po_no; ?></h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create($woosrinfopo, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOSRServicePOCheckInLabor'));
                ?>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Date Received</label>
                            <div class="input-group date datePicker">
                                <?php 
                                $date_order_placed = date('m/d/Y');
                                /*if(!empty($woosrinfopo->date_order_placed)){
                                    $date_order_placed = $woosrinfopo->date_order_placed;
                                }*/
                                echo $this->Form->Text('date_order_placed', array('class' => 'form-control', 'id' => 'po_date_order_placed', 'placeholder' => '', 'label' => false, 'value'=>$date_order_placed)); ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="reference">Vendor</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('vendor_id', array('options' => $woosrvendordata, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'osr_po_vendor_id', 'disabled'=>'disabled'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Invoice#</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('osr_invoice_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'service_poes_po_no', 'value'=>@$woosrinfo->osr_invoice_no)); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Approved</label>
                            <div class="form-input-frame">
                                <?php
                                $osrPOApproved = ['1'=>'Yes', '2'=>'No'];
                                echo $this->Form->control('is_approved', array('options' => $osrPOApproved, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'osr_is_approved'));
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="control-label" for="reference">Initials</label>
                            <div class="form-input-frame">
                                <?php
                                $aircraftWOOSRPOInitials = unserialize(AIRCRAFT_WO_OSR_PO_INITIALS);
                                echo $this->Form->control('initials', array('options' => $aircraftWOOSRPOInitials, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'osr_initials'));
                                ?>
                            </div>
                        </div>
                    </div>    
                </div>
                <?php echo $this->Form->end(); ?>
                
                <div id="po-checkin-labor-info">
                    <?php echo $this->element('Inventory/customer_otc/aircraft_osr_po_checkin_labor_info'); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary saveWOOSRPOCheckInLabor">Save</button>
            </div>
        </div>
    </div>
</div>