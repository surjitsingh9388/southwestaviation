<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

if(!empty($part->plane_id) && !empty($subResults)) {
    $pdfCheck = 'style="pointer-events: auto;"';
} else {
    $pdfCheck = 'style="pointer-events: none;"';
}
?>
<style>
   
    .actionWrap .dropdown-toggle {
        width:100px !important;
    }

    #itemGeneral{
        padding:10px;
    }

    .mb-3 {
        margin-bottom: 5px !important;
    }
    h5{
        text-align:center;
        font-weight:bold;
    }
    
    .sinfo{
        margin-top: 15px;
    }

    .addPageHeading {
        font-size: 11pt;
        background-color: #1f2a5e;
        color: #ecf0f1;
        padding: 10px 15px;
        margin-top: 0;
        margin-left: 0px;
        height: 40px;
    }

    .form-control-static {
        padding-top: 4px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .form-control-static {
        border-bottom: 1px solid #dfdfdf;
    }
    .form-control-static {
        min-height: 34px;
        padding-top: 7px;
        padding-bottom: 7px;
        margin-bottom: 0;
    }
    .form-control-static {
        min-height: 34px;
        padding-top: 7px;
        padding-bottom: 7px;
        margin-bottom: 0;
    }

</style>

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
                 echo $this->Form->create($invenotries, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoriesDiscard'));
                ?>
                
                <div class="addPartBorder">
                    <div class="addPageHeading">Discard Information</div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="qty">Units to Discard&nbsp;<span class="required">*</span></label>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <?php echo $this->Form->control('qty', array('class'=>'form-control col-md-1 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>'1')); ?>
                                </div>
                                <div class="col-md-9 col-sm-9 col-xs-12" style="padding-top: 5px;">EA</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="discard_reason">Reason</label>
                                <div class="col-md-10 col-sm-10 col-xs-12">
                                    <?php echo $this->Form->control('discard_reason', array('class' => 'form-control col-md-10 col-xs-12', 'label'=> false, 'rows'=>2, 'placeholder'=>'Optionally, provide a reason for why you discarding this inventory.')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group"> 
                                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="account_code">Account Code</label>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <?php 
                                        echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Enter an account code ...', 'class' => 'form-control col-md-3 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code')); 
                                    ?>
                                </div>
                                <div class="col-md-8 col-sm-8 col-xs-12"></div>
                            </div>
                        </div>
                    </div>
                    <div class="addPageHeading">Item Information</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Item Name</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries['_matchingData']['InventoryItems']['name']; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="uom">Unit of Measure</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->uom == '1' ? 'Each' : ''; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Quantity</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->qty; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="revision">Revision</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->revision; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="expiration">Expiration</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo !empty($invenotries->expiration) ? date('d-M-Y', strtotime($invenotries->expiration)) : ''; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="serial_no">Lot/Serial</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->serial_no; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
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

                        <div class="col-md-6">
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

<script>
var printBarCodeURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'printInventoriesBarCode']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
var is_this_item_serialized ='';
</script>

<?php 
echo $this->Html->script('inventories'); 
?>