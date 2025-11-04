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
            <h2 class="heading"><?php echo $this->Html->link('Item Catalog', ['controller'=>'InventoryItems', 'action' => 'index']).' / '.$this->Html->link($invenotries['_matchingData']['InventoryItems']['name'], ['action' => 'detail', $invenotries->id]).' / Discard';?></h2>
            <div class="btnWrap">
                <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                echo $this->Form->button('Confirm', ['type' => 'submit', 'class' => 'btn btn-primary ml-10 inventoriesconfirmdiscard']);
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <?php
                 echo $this->Form->create($invenotries, array('class' => '', 'id' => 'frmInventoriesDiscard'));
                ?>
                
                <div class="addPartBorder">
                    <div class="invaddPageHeading">Discard Information</div>
                    <div style="padding:15px;">
                        <div class="row mt10 customRow">
                            <div class="col-md-12 col-sm-12 col-xs-12 p-0">
                                <div class="form-group clearfix"> 
                                    <label class="control-label col-md-2 col-sm-3 col-xs-12" for="qty">Units to Discard&nbsp;<span class="required">*</span></label>
                                    <div class="col-md-1 col-sm-3 col-xs-3">
                                        <?php echo $this->Form->control('qty', array('class'=>'form-control col-md-1 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>'1')); ?>
                                    </div>
                                    <div class="col-md-9 col-sm-2 col-xs-2 pt5">EA</div>
                                </div>
                            </div>
                   
                            <div class="col-md-12 col-sm-12 col-xs-12 p-0">
                                <div class="form-group clearfix"> 
                                    <label class="control-label col-md-2 col-sm-3 col-xs-12" for="discard_reason">Reason</label>
                                    <div class="col-md-4 col-sm-5 col-xs-12">
                                        <?php echo $this->Form->control('discard_reason', array('class' => 'form-control col-md-10 col-xs-12', 'label'=> false, 'rows'=>2, 'placeholder'=>'Optionally, provide a reason for why you discarding this inventory.')); ?>
                                    </div>
                                </div>
                            </div>
         
                            <div class="col-md-12 col-sm-12 col-xs-12 p-0">
                                <div class="form-group clearfix"> 
                                    <label class="control-label col-md-2 col-sm-3 col-xs-12" for="account_code">Account Code</label>
                                    <div class="col-md-4 col-sm-5 col-xs-12">
                                        <?php 
                                            echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Enter an account code ...', 'class' => 'form-control col-md-3 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code')); 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="invaddPageHeading">Item Information</div>
                    <div style="padding:15px;">
                        <div class="row mt10 customRow">
                            <div class="col-md-6 col-sm-6 col-xs-12 p-0">
                                <div class="form-group clearfix">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Item Name</label>
                                    <div class="col-md-9 col-sm-9  col-xs-12">
                                        <p class="form-control-static"><?php echo $invenotries['_matchingData']['InventoryItems']['name']; ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12 p-0">
                                <div class="form-group clearfix">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status</label>
                                    <div class="col-md-9 col-sm-9  col-xs-12">
                                        <p class="form-control-static">
                                            <?php 
                                            $inventorystatus = unserialize(INVENTORY_STATUS);
                                            echo $inventorystatus[$invenotries->status]; 
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 p-0">
                                    <div class="form-group clearfix">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="uom">Unit of Measure</label>
                                    <div class="col-md-9 col-sm-9  col-xs-12">
                                            <p class="form-control-static"><?php echo $invenotries->uom == '1' ? 'Each' : ''; ?></p>
                                        </div>
                                    </div>
                                </div>

                            <div class="col-md-6 col-sm-6 col-xs-12 p-0">
                                    <div class="form-group clearfix">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="quantities">Quantity</label>
                                    <div class="col-md-9 col-sm-9  col-xs-12">
                                            <p class="form-control-static"><?php echo $invenotries->qty; ?></p>
                                        </div>
                                    </div>
                                </div>
                    
                            <div class="col-md-6 col-sm-6 col-xs-12 p-0">
                                    <div class="form-group clearfix">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="revision">Revision</label>
                                    <div class="col-md-9 col-sm-9  col-xs-12">
                                            <p class="form-control-static"><?php echo $invenotries->revision; ?></p>
                                        </div>
                                    </div>
                                </div>

                            <div class="col-md-6 col-sm-6 col-xs-12 p-0">
                                    <div class="form-group clearfix">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="location_name">Location</label>
                                    <div class="col-md-9 col-sm-9  col-xs-12">
                                            <p class="form-control-static">
                                            <?php echo isset($invenotries['_matchingData']['InventoryLocations']['location_name']) ? $invenotries['_matchingData']['InventoryLocations']['location_name'] : ''; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                        
                            <div class="col-md-6 col-sm-6 col-xs-12 p-0">
                                    <div class="form-group clearfix">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="expiration">Expiration</label>
                                    <div class="col-md-9 col-sm-9  col-xs-12">
                                            <p class="form-control-static"><?php echo !empty($invenotries->expiration) ? date('d-M-Y', strtotime($invenotries->expiration)) : ''; ?></p>
                                        </div>
                                    </div>
                                </div>

                            <div class="col-md-6 col-sm-6 col-xs-12 p-0">
                                    <div class="form-group clearfix">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="serial_no">Lot/Serial</label>
                                    <div class="col-md-9 col-sm-9  col-xs-12">
                                            <p class="form-control-static"><?php echo $invenotries->serial_no; ?></p>
                                        </div>
                                    </div>
                                </div>
                        
                            <div class="col-md-6 col-sm-6 col-xs-12 p-0">
                                    <div class="form-group clearfix">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="cost">Cost</label>
                                    <div class="col-md-9 col-sm-9  col-xs-12">
                                            <?php 
                                            $currency = unserialize(CURRENCY);
                                            ?>
                                            <p class="form-control-static"><?php echo $invenotries->cost.' '.$currency[$invenotries->currency]; ?></p>
                                        </div>
                                    </div>
                                </div>

                            <div class="col-md-6 col-sm-6 col-xs-12 p-0">
                                    <div class="form-group clearfix">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="received">Received</label>
                                    <div class="col-md-9 col-sm-9  col-xs-12">
                                            <p class="form-control-static"><?php echo !empty($invenotries->received) ? date('d-M-Y', strtotime($invenotries->received)) : ''; ?></p>
                                        </div>
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

<script>
var printBarCodeURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'printInventoriesBarCode']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
var is_this_item_serialized ='';
</script>

<?php 
echo $this->Html->css('inventory');
echo $this->Html->script('inventories'); 
?>