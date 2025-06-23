<?php 
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');
use Cake\Routing\Router;

?>

<div class="content sliding">
    <div class="outerWrapper">
         
        <div class="btnWrapper">
            <?php
            $serialmsg = 'No Lot';
            if(!empty($invenotries->serial_no)){
                $serialmsg = $invenotries->serial_no;
            }
            ?>
            <h2 class="heading"><?php echo $this->Html->link('Item Catalog', ['controller'=>'InventoryItems', 'action' => 'index']).' / '.$this->Html->link($invenotries['_matchingData']['InventoryItems']['name'], ['action' => 'detail', $invenotries->id]).' / Error Correct';?></h2>
            <div class="btnWrap">
                <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));

                echo $this->Form->button('Save', ['type' => 'submit', 'class' => 'btn btn-primary ml-10 inventorieserrorcorrectsave', 'disabled'=>'disabled']);
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <?php
                 echo $this->Form->create($invenotries, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoriesErrorCorrect'));
                ?>
                
                <div class="addPartBorder">
                    <div class="invaddPageHeading">Error Correct Information</div>
                    
                    <div class="row">
                        <div class="col-md-12 heading-description">
                            Error correcting a physical inventory item will allow you to adjust the state of an item and will create a new transaction.
                        </div>
                    </div>

                    <div class="row mt10">
                        <div class="col-sm-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="plane_id">Status&nbsp;<span class="required">*</span></label>
                                <div class="col-md-3 col-sm-5 col-xs-12">
                                <?php 
                                    $inventoryStatus = ['1'=>'Active', '3'=>'Consumed', '5'=>'Damaged', '4'=>'Discarded', '14'=>'Inactive', '13'=>'Needs Repair', '12'=>'Quarantine', '6'=>'Unavailable', '8'=>'Unrepairable'];

                                    echo $this->Form->control('status', array('options' => $inventoryStatus, 'empty' => 'Enter Type ...', 'class' => 'form-control col-md-3 col-xs-12 selectpicker errorstatuschange', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'error_correct_status', 'value'=>'')); 
                                    ?>
                                </div>
                                <div class="col-md-7 col-sm-7 col-xs-12"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row hide-block" id="errorquantityblock">
                        <div class="col-sm-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="qty">Quantity&nbsp;<span class="required">*</span></label>
                                <div class="col-md-2 col-sm-5 col-xs-12">
                                    <?php 
                                    $qty = !empty($invenotries->qty) ? $invenotries->qty : '1';
                                    echo $this->Form->control('qty', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => '', 'label' => false, 'id'=>'error_correct_qty', 'disabled'=>'disabled', 'value'=>$qty)); ?>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row hide-block" id="errorlocationblock">
                        <div class="col-sm-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="plane_id">Location&nbsp;<span class="required">*</span></label>
                                <div class="col-md-3 col-sm-5 col-xs-12">
                                    <?php echo $this->Form->control('location_id', array('options' => $location, 'empty' => 'Select a location...', 'class' => 'form-control col-md-3 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id'=>'error_correct_location_id', 'value'=>''));  ?>
                                    <!--div class="col-md-1 col-sm-1 col-xs-12 plus-new-btn" onclick='$("#locationAddModel").modal("show");'><button class="btn btn-primary" style="height:34px;" type="button"><i class="fa fa-plus"></i></button></div-->
                                </div>
                                <div class="col-md-7 col-sm-7 col-xs-12"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="discard_reason">Reason</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <?php echo $this->Form->control('discard_reason', array('class' => 'form-control col-md-10 col-xs-12', 'label'=> false, 'rows'=>2)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="account_code">Account Code</label>
                                <div class="col-md-2 col-sm-5 col-xs-12">
                                    <?php 
                                        echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Enter an account code ...', 'class' => 'form-control col-md-3 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code')); 
                                    ?>
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-12"></div>
                            </div>
                        </div>
                    </div>
                    <div class="invaddPageHeading">Item Information</div>
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Item Name</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries['_matchingData']['InventoryItems']['name']; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="status">Status</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static">
                                        <?php 
                                        $inventorystatus = unserialize(INVENTORY_STATUS);
                                        echo $inventorystatus[$invenotries->status]; 
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="uom">Unit of Measure</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->uom == '1' ? 'Each' : ''; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Quantity</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->qty; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="revision">Revision</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->revision; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="location_name">Location</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static">
                                    <?php echo isset($invenotries['_matchingData']['InventoryLocations']['location_name']) ? $invenotries['_matchingData']['InventoryLocations']['location_name'] : ''; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="expiration">Expiration</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo !empty($invenotries->expiration) ? date('d-M-Y', strtotime($invenotries->expiration)) : ''; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="serial_no">Lot/Serial</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->serial_no; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="cost">Cost</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                    $currency = unserialize(CURRENCY);
                                    ?>
                                    <p class="form-control-static"><?php echo $invenotries->cost.' '.$currency[$invenotries->currency]; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="received">Received</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo !empty($invenotries->received) ? date('d-M-Y', strtotime($invenotries->received)) : ''; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div style="clear: both;"></div>
                <?php 
                echo $this->Form->end(); 
                ?>
            </div>
        </div>
       
    </div>
</div>

<div id="locationAddModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Create Location</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <?php echo $this->element('Inventory/create_new_inventory_location'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary locationsavebtn" disabled>Save</button>
            </div>
        </div>
    </div>
</div>

<script>
var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
var is_this_item_serialized ='';
</script>

<?php 
echo $this->Html->css('inventory'); 
echo $this->Html->script('inventories'); 
?>