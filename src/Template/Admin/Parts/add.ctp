<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

?>

<div class="content sliding">
    <div class="outerWrapper">
    <?php
    echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmParts'));
    ?>  
        <div class="btnWrapper">
            <h2 class="heading">Create Part</h2>
        </div>
       
        <div class="page-content mt-35">
            
            <div class="formBGCls">
                <section class="top-form-section addPartBorder">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group part-number">
                                <label class="control-label" for="reference">Part Number <span class="required">*</span>:
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('part_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Part #')); ?>
                                </div>
                            </div><!--Part Number// -->
                            <div class="form-group part-classification">
                                <label class="control-label" for="reference">Part Classification<span class="required">*</span>:
                                </label>
                                <div class="form-input-frame">
                                    <?php
                                    $classificationopt = unserialize(PARTS_CLASSIFICATION);
                                    echo $this->Form->control('part_classification', array('options' => $classificationopt, 'empty' => '-Select-', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    ?>
                                </div>
                            </div><!-- Parts Classification// -->
                        </div>
                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="reference">Serial Number <span class="required">*</span>:
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('serial_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Searial #')); ?>
                                </div>
                            </div><!-- Serial Number// -->

                            <div class="form-group d-flex">
                                <label class="control-label" for="reference">Lot Number<span class="required">*</span>:
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('lot_number', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Lot #')); ?>
                                </div>
                            </div><!-- Lot Number// -->
                        </div>
                        <div class="col-md-6">
                            <div class="form-group d-flex">
                                <label class="control-label">Description <span class="required">*</span>:</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('description', array('class' => 'form-control', 'label'=> false, 'rows'=>2, 'placeholder'=>'Description')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> <!-- top-form-section// -->

                <section class="bottom-form-section">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="qty">Qty<span class="required">*</span>
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('qty', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter QTY')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="owner_of_part">Owner of Part<span class="required">*</span>
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('owner_of_part', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Owner')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="location">Location<span class="required">*</span>
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('location', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Location')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="conditions">Condition<span class="required">*</span>
                                </label>
                                <div class="form-input-frame">
                                    <?php
                                        $conditions = ['N/A'=>'N/A'];
                                        echo $this->Form->control('conditions', array('options' => $conditions, 'empty' => '-Select-', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="cost">Cost<span class="required">*</span>
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('cost', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.000')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="retail">Retail<span class="required">*</span>
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('retail', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'$0.00')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="date_received">Date Received<span class="required">*</span>
                                </label>
                                <div class="form-input-frame input-group date datePicker">
                                    <?php echo $this->Form->Text('date_received', array('class' => 'form-control', 'id' => 'dateRDatepicker', 'placeholder' => '', 'label' => false, 'value'=>'', 'autocomplete'=>'off')); ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="vendor">Vendor<span class="required">*</span>
                                </label>
                                <div class="form-input-frame">
                                    <?php 
                                    $vendors = ['Test Vendors1'=>'Test Vendors1', 'Test Vendors2'=>'Test Vendors2'];
                                    echo $this->Form->control('vendor', array('options' => $vendors, 'empty' => '-Select-', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="warranty_expires">Warranty Expires<span class="required">*</span>
                                </label>
                                <div class="form-input-frame input-group date datePicker">
                                    <?php echo $this->Form->Text('warranty_expires', array('class' => 'form-control', 'id' => 'wExpDatepicker', 'placeholder' => '', 'label' => false, 'value'=>'', 'autocomplete'=>'off')); ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="invoice">Invoice#<span class="required">*</span>
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('invoice', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter Invoice #')); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="purchase_order">Purchase Order<span class="required">*</span>
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('purchase_order', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter PO #')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="sku">SKU#<span class="required">*</span>
                                </label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('sku', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'Enter SKU #')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="shelf_life">Shelf Life#<span class="required">*</span>
                                </label>
                                <div class="form-input-frame">
                                    <?php 
                                    $shelflife = ['N/A'=>'N/A'];
                                    echo $this->Form->control('shelf_life', array('options' => $shelflife, 'empty' => '-Select-', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group d-flex">
                                <label class="control-label" for="approved_by">Approved By<span class="required">*</span>
                                </label>
                                <div class="form-input-frame">
                                    <?php
                                    echo $this->Form->control('approved_by', array('options' => $allAdmins, 'empty' => '-Select-', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section><!-- bottom-form-section// -->
                <div class="btn-frame">
                    <button type="button" onclick="javascript:history.back()" class="btn btn-default" id="reset">Cancel</button>
                    <button type="submit" class="btn btn-success" disabled>Finished</button>
                </div>
            </div>
        </div>
    <?php 
    echo $this->Form->end(); 
    ?>
    </div>
</div>

<script> 

$(document).ready(function () {
    $("input[type='text'], textarea, select").on("keyup change", function(){
        var errors = 0;
        $("input[type='text'], textarea, select").map(function(){
            if( !$(this).val() ) {
                errors++;
            } 
        });
        if(errors > 0){
            $("button[type='submit']").attr("disabled", "disabled");
        }else{
            $("button[type='submit']").removeAttr("disabled");
        }
    });

    $('#frmParts').validate({ // initialize the plugin
        rules: {
            part_number: {
                required: true,
            },
            serial_number: {
                required: true,
            },
            description: {
                required: true,
            },
            part_classification: {
                required: true,
            },
            lot_number: {
                required: true,
            },
            qty: {
                required: true,
                digits: true
            },
            owner_of_part: {
                required: true,
            },
            location: {
                required: true,
            },
            conditions: {
                required: true,
            },
            cost: {
                required: true,
                number: true
            },
            retail: {
                required: true,
                number: true
            },
            date_received: {
                required: true,
            },
            vendor: {
                required: true,
            },
            warranty_expires: {
                required: true,
            },
            invoice: {
                required: true,
            },
            purchase_order: {
                required: true,
            },
            lot: {
                required: true,
            },
            shelf_life: {
                required: true,
            },
            approved_by: {
                required: true,
            }
        }
    });

});
</script>