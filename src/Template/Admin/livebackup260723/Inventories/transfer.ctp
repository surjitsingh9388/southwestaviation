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

    .plusbtn{
        margin-top: -28px;
        padding-left: 16px;
        float:right;
    }

    .btn-label-default {
        color: #002e6d;
        border: solid 1px #bfbfbf;
        background: #FFFFFF;
    }

    label.btn.btn-label-default.active {
        background-color: #5cca5c;
        color: #fff;
    }

    .tranfer-block {
        min-height: 20px;
        padding: 19px;
        margin-bottom: 20px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 5%);
        box-shadow: inset 0 1px 1px rgb(0 0 0 / 5%);
        margin-left:0px;
        margin-right:0px;
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
            
            <h2 class="heading"><?php echo $this->Html->link('Item Catalog', ['controller'=>'InventoryItems', 'action' => 'index']).' / '.$this->Html->link($invenotries['_matchingData']['InventoryItems']['name'], ['action' => 'detail', $invenotries->id]).' / Transfer';?></h2>
            
            <div class="btnWrap">
                <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));

                echo $this->Form->button('Confirm', ['type' => 'submit', 'class' => 'btn btn-primary ml-10 inventoriestransfersave', 'disabled'=>'disabled']);
                ?>
                <input type="hidden" id="inventory_item_id" value="<?php echo $invenotries->inventory_item_id; ?>">
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <?php
                 echo $this->Form->create($invenotries, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoriesTransfer'));
                ?>
                
                <div class="addPartBorder">
                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="uom">Lot/Serial</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->serial_no; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Item Name</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries['_matchingData']['InventoryItems']['name']; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="account_code">Account Code</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                        echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Enter an account code ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code')); 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="row tranfer-block">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Current Location</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static">
                                        <?php echo isset($invenotries['_matchingData']['InventoryLocations']['location_name']) ? $invenotries['_matchingData']['InventoryLocations']['location_name'] : ''; ?>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Available Qty</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->qty; ?></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Available to Transfer</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->qty; ?></p>
                                </div>
                            </div>
                            
                        </div>

                        <div class="col-md-2">
                            <div class="text-center" style="margin-top: 30px;font-size: 25px;">
                                <i class="fa fa-long-arrow-right fa-2x"></i>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Destination Location&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('location_id', array('options' => $location, 'empty' => 'Select a location...', 'class' => 'form-control col-md-3 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id'=>'transfer_location_id', 'value'=>''));  ?>
                                </div>
                            </div>

                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="qty">Qty. To Transfer&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('qty', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'id'=>'transfer_qty', 'value'=>'')); ?>
                                </div>
                            </div>

                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="qty">Currently at Destination</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static" id="currently_at_destination">-</p>
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
var getInventoryLocationQtyCountURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'getInventoryLocationQtyCount']); ?>";
    
var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
var is_this_item_serialized ='';
</script>

<?php 
echo $this->Html->script('inventories'); 
?>