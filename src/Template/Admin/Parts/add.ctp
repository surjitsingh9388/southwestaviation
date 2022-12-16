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
            <div class="btnWrap">
             <?php
                echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
            </div> 
        </div>
       
        <div class="page-content mt-35">
            
            <div class="formBGCls">
                <div class="addPartBorder">
                   
                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="reference">Part Number:
                                </label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->control('part_number', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="reference">Serial Number:
                                </label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->control('serial_number', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-1 col-sm-1 col-xs-12">Description</label>
                                <div class="col-md-11 col-sm-11 col-xs-12">
                                    <?php echo $this->Form->control('description', array('class' => 'form-control col-md-7 col-xs-12', 'label'=> false, 'rows'=>2, 'style'=>'margin: 0 0 0 43px; width: 90%;')); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="reference">Part Classification:
                                </label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php
                                    $classificationopt = ['Test Classification1'=>'Test Classification1', 'Test Classification2'=>'Test Classification2'];
                                    echo $this->Form->control('part_classification', array('options' => $classificationopt, 'empty' => '-Select-', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="reference">Lot Number:
                                </label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->control('lot_number', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div style="clear: both;"></div>

                <div class="row" style="margin-top:10px;">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="qty">Qty
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php echo $this->Form->control('qty', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="owner_of_part">Owner of Part
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php echo $this->Form->control('owner_of_part', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="location">Location
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php echo $this->Form->control('location', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="conditions">Condition
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php
                                    $conditions = ['Test Conditions1'=>'Test Conditions1', 'Test Conditions2'=>'Test Conditions2'];
                                    echo $this->Form->control('conditions', array('options' => $conditions, 'empty' => '-Select-', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="cost">Cost
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php echo $this->Form->control('cost', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="retail">Retail
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php echo $this->Form->control('retail', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="date_received">Date Received
                            </label>
                            <div class="input-group date datePicker">
                                <?php echo $this->Form->Text('date_received', array('class' => 'form-control col-md-7 col-xs-12', 'id' => 'dateRDatepicker', 'placeholder' => '', 'label' => false, 'value'=>'', 'autocomplete'=>'off')); ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="vendor">Vendor
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php 
                                $vendors = ['Test Vendors1'=>'Test Vendors1', 'Test Vendors2'=>'Test Vendors2'];
                                echo $this->Form->control('vendor', array('options' => $vendors, 'empty' => '-Select-', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="warranty_expires">Warranty Expires
                            </label>
                            <div class="input-group date datePicker">
                                <?php echo $this->Form->Text('warranty_expires', array('class' => 'form-control col-md-7 col-xs-12', 'id' => 'wExpDatepicker', 'placeholder' => '', 'label' => false, 'value'=>'', 'autocomplete'=>'off')); ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="invoice">Invoice#
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php echo $this->Form->control('invoice', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="purchase_order">Purchase Order
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php echo $this->Form->control('purchase_order', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="lot">Lot#
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php echo $this->Form->control('lot', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="shelf_life">Shelf Life#
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php 
                                $shelflife = ['Test Shelf Life1'=>'Test Shelf Life1', 'Test Shelf Life2'=>'Test Shelf Life2'];
                                echo $this->Form->control('shelf_life', array('options' => $shelflife, 'empty' => '-Select-', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-5 col-xs-12" for="approved_by">Approved By
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php
                                echo $this->Form->control('approved_by', array('options' => $allAdmins, 'empty' => '-Select-', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row"  style="text-align: right; margin-top: 20px;">
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="button" onclick="javascript:history.back()" class="btn btn-primary" id="reset">Cancel</button>
                            <button type="submit" class="btn btn-success">Finished</button>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    <?php 
    echo $this->Form->end(); 
    ?>
    </div>
</div>

<?php echo $this->Html->script('parts'); ?>
<script> 

$(document).ready(function () {

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
                digits: true
            },
            retail: {
                required: true,
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