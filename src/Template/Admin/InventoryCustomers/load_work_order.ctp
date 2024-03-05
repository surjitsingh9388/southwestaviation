<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Load a Work Order or Work Order Quote</h2>
        </div>
       
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Enter the number you wish to open</legend>

                                    <div class="col-md-10">
                                        <?php echo $this->Form->control('work_order_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'work_order_no')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-default searchAircraftWorkOrder">Load</button>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Listing</legend>

                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default col-md-12 load-open-work-orders" data-val="open_work_order">Open Work Orders</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default col-md-12 load-open-work-orders" data-val="all_work_order">All Work Orders</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default col-md-12">Open W/O Quotes</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default col-md-12">All W/O Quotes</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default col-md-12 load-warranty-claims-work-orders" data-val="open_warranty_claim_work_order">Open Warr. Claims</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-default col-md-12 load-warranty-claims-work-orders" data-val="all_warranty_claim_work_order">All Warr. Claims</button>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">Create a new</legend>
                                    
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-default col-md-12" onclick="$('#woCreateNewWOModal').modal('show');">Work Order</button>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-default col-md-12">Work Order Quotes</button>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="col-md-12">
                                <button type="button" class="btn btn-default col-md-6 import-wo-from-email">Import from Email</button>
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-default col-md-6">Additional Reports</button>
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-default col-md-6 export-work-order-data">Export Data</button>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Find</legend>

                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Registration Number</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('aircraft_registration_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'search_by_aircraft_registration_number')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">&nbsp;</label>
                                            <div class="form-input-frame">
                                                <button type="button" class="btn btn-default searchWOByAircraftRegNo">Find</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Aircraft Serial Number</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('aircraft_serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'search_by_aircraft_serial_number')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">&nbsp;</label>
                                            <div class="form-input-frame">
                                                <button type="button" class="btn btn-default searchWOByAircraftSerialNo">Find</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Customer Name</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('customer_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'search_by_customer_name')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">&nbsp;</label>
                                            <div class="form-input-frame">
                                                <button type="button" class="btn btn-default searchWOByAircraftCustomerName">Find</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Customer P/O</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('customer_po', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'search_by_customer_po')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">&nbsp;</label>
                                            <div class="form-input-frame">
                                                <button type="button" class="btn btn-default searchWOByAircraftCustomerPO">Find</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Discrepancy</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('discrepancy', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'search_by_discrepancy')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">&nbsp;</label>
                                            <div class="form-input-frame">
                                                <button type="button" class="btn btn-default searchWOByItemDiscrepancy">Find</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Corrective Action</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('corrective_action', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'search_by_corrective_action')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">&nbsp;</label>
                                            <div class="form-input-frame">
                                                <button type="button" class="btn btn-default searchWOByItemCorrectiveAction">Find</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Part Number</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'search_by_part_number')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">&nbsp;</label>
                                            <div class="form-input-frame">
                                                <button type="button" class="btn btn-default searchWOByPartNumber">Find</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Warranty Claim Number</label>
                                            <div class="form-input-frame">
                                                <?php echo $this->Form->control('warranty_claim_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'search_by_warranty_claim_no')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">&nbsp;</label>
                                            <div class="form-input-frame">
                                                <button type="button" class="btn btn-default searchWOByWarrantyClaim">Find</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">Warranty For W/O Status</label>
                                            <div class="form-input-frame">
                                                <?php
                                                    $workOrderStatus = unserialize(AIRCRAFT_WORKORDER_STATUS);
                                                    echo $this->Form->control('warranty_wo_status', array('options' => $workOrderStatus, 'empty' => 'Select', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'search_by_warranty_wo_status'));
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label" for="reference">&nbsp;</label>
                                            <div class="form-input-frame">
                                                <button type="button" class="btn btn-default searchWOByWarrantyWOStatus">Find</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="col-md-12">
                                <a href="javascript:void(0);" style="color:blue;" class="woMaintAdvFindOptPopup"><b>Advanced Find Options</b></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->element('InventoryPopup/customer_otc/load_create_new_work_order'); ?>

<div id="customerotcpopup"></div>

<?php echo $this->element('Inventory/customer_otc/aircraft_work_order_ajax_url'); ?>

<script>
var loadAircraftWorkOrderPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'loadAircraftWorkOrderPopup']); ?>";

var fetchCustomerOTCPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'fetchCustomerOTCPopup']); ?>";
</script>

<?php
echo $this->Html->css('inventory_customer_otc');
echo $this->Html->script('inventory_customers');
?>