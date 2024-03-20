<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
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
            <h2 class="heading"><?php echo $this->Html->link('Item Catalog', ['controller'=>'InventoryItems', 'action' => 'index']).' / '.$this->Html->link($invenotries['_matchingData']['InventoryItems']['name'], ['action' => 'detail', $invenotries->id]).' /  Adjust';?></h2>
            <div class="btnWrap">
                <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));

                echo $this->Form->button('Confirm', ['type' => 'submit', 'class' => 'btn btn-primary ml-10 inventoriesadjustsave', 'disabled'=>'disabled']);
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <?php
                 echo $this->Form->create($invenotries, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoriesAdjust'));
                ?>
                
                <div class="addPartBorder">
                    <div class="invaddPageHeading">Adjustment Details</div>
                    
                    <div class="row">
                        <div class="col-md-12 heading-description">The below item will be adjusted. Quantities can be adjusted to account for missing or extra inventory not obtained or lost through a documented transaction.</div>
                    </div>

                    <div class="row mt10">
                        <div class="col-sm-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="qty">New Quantity&nbsp;<span class="required">*</span></label>
                                <div class="col-md-2 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('qty', array('class'=>'form-control col-md-2 col-xs-12', 'placeholder' => 'Quantity', 'label' => false, 'id'=>'adjust_qty', 'value'=>'', 'required'=>'required')); ?>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="discard_reason">Reason&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('discard_reason', array('class' => 'form-control col-md-10 col-xs-12', 'placeholder'=>'Enter a reason why you are manually adjusting the inventory quantity.', 'label'=> false, 'rows'=>2, 'value'=>'', 'required'=>'required', 'id'=>'discard_reason')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-2 col-sm-3 col-xs-12" for="account_code">Account Code</label>
                                <div class="col-md-2 col-sm-8 col-xs-12">
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Item Name</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries['_matchingData']['InventoryItems']['name']; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="uom">Unit of Measure</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->uom == '1' ? 'Each' : ''; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="revision">Revision</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->revision; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="expiration">Expiration</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo !empty($invenotries->expiration) ? date('d-M-Y', strtotime($invenotries->expiration)) : ''; ?></p>
                                </div>
                            </div>

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

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Quantity</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->qty; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="location_name">Location</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static">
                                    <?php echo isset($invenotries['_matchingData']['InventoryLocations']['location_name']) ? $invenotries['_matchingData']['InventoryLocations']['location_name'] : ''; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="serial_no">Lot/Serial</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->serial_no; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="received">Received</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo !empty($invenotries->received) ? date('d-M-Y', strtotime($invenotries->received)) : ''; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row"></div>
                <?php 
                echo $this->Form->end(); 
                ?>
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