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

    .form-control-static a{
        color:#092E6E;
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
            <h2 class="heading"><?php echo $transactionhistory['invitms']['name'].' (PN: '.$transactionhistory['invitms']['part_number'].') (SN: '.$transactionhistory['inv']['serial_no'].')';?></h2>
            <div class="btnWrap">
                <?php
                echo $this->Html->link("Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));

                $invConditions = unserialize(INVENTORY_CONDITION);
                $invStatus = unserialize(INVENTORY_STATUS);
                $invItemType = unserialize(INVENTORY_ITEM_TYPE);
                $transactionActionList = unserialize(TRANSACTION_ACTION_LIST);
                $defaultUOM = unserialize(DEFAULT_UOM);
                $currency = unserialize(CURRENCY);
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="addPartBorder">
                    <div class="row">
                        <div class="col-md-12" style="font-size: 14px;padding-left: 30px;">
                            <div class="col-md-6">
                                <div class="addPageHeading">From</div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Description</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo $transactionhistory->from_description; ?></p>
                                    </div>
                                </div>
                                <?php
                                if(!empty($transactionhistory->from_location_id)){
                                ?>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Location</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static">
                                            <?php 
                                            echo $this->Html->link(
                                                $transactionhistory['fromlocation']['location_name'],
                                                ['controller' => 'InventoryLocations', 'action' => 'detail', $transactionhistory->from_location_id]
                                            );
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities"><i class="fa fa-link" aria-hidden="true"></i></label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static">
                                            <?php 
                                            echo $this->Html->link(
                                                'Physical Inventory Item',
                                                ['controller' => 'Inventories', 'action' => 'detail', $transactionhistory->inventory_id]
                                            );
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <?php if(!empty($transactionhistory->from_status)){ ?>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Status</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo $invStatus[$transactionhistory->from_status]; ?></p>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Condition</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo $transactionhistory->from_conditions; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Item Type</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo !empty($transactionhistory->from_item_type) ? $invItemType[$transactionhistory->from_item_type] : ''; ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="addPageHeading">To</div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Description</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo $transactionhistory->to_description; ?></p>
                                    </div>
                                </div>
                                <?php
                                if(!empty($transactionhistory->to_location_id)){
                                ?>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Location</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static">
                                            <?php 
                                            echo $this->Html->link(
                                                $transactionhistory['tolocation']['location_name'],
                                                ['controller' => 'InventoryLocations', 'action' => 'detail', $transactionhistory->to_location_id]
                                            );
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities"><i class="fa fa-link" aria-hidden="true"></i></label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static">
                                            <?php 
                                            echo $this->Html->link(
                                                'Physical Inventory Item',
                                                ['controller' => 'Inventories', 'action' => 'detail', $transactionhistory->inventory_id]
                                            );
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <?php if(!empty($transactionhistory->to_status)){ ?>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Status</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo $invStatus[$transactionhistory->to_status]; ?></p>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Condition</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo $transactionhistory->to_conditions; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Item Type</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo !empty($transactionhistory->to_item_type) ? $invItemType[$transactionhistory->to_item_type] : ''; ?></p>
                                    </div>
                                </div>
                                
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" style="font-size: 14px;padding-left: 30px;">
                            <div class="col-md-6">
                                <div class="addPageHeading">Transaction Details</div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Type</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static">
                                            <?php
                                            $transactionActionList = unserialize(TRANSACTION_ACTION_LIST);
                                            $key = !empty($transactionhistory->type) ? array_search($transactionhistory->type, array_column($transactionActionList, 'id')) : '';
                                            $actions = !empty($key) ? $transactionActionList[$key]['name'] : '';
                                            echo $actions; 
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Quantity</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static">
                                            <?php 
                                            echo $transactionhistory->qty;
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">UOM</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static">
                                            <?php 
                                            echo !empty($transactionhistory->uom) ? $defaultUOM[$transactionhistory->uom] : '';
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Unit Cost</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo $transactionhistory->unit_cost.' '.$currency[$transactionhistory['invitms']['currency']]; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Reason</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo $transactionhistory->reason; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Tags</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo $transactionhistory->tags; ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="addPageHeading">Other</div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Created On</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo date("m-d-Y", strtotime($transactionhistory->created)); ?></p>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Username</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static">
                                            <?php 
                                            echo $transactionhistory['users']['email'];
                                            ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Full Name</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static">
                                            <?php 
                                            echo $transactionhistory['users']['full_name'];
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Vendor</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo $transactionhistory['vendor']['name']; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Account Code</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo $transactionhistory->account_code; ?></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">ATA Chapter</label>
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <p class="form-control-static"><?php echo $transactionhistory['atacode']['ata_code']; ?></p>
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
var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
var is_this_item_serialized ='';
</script>

<?php 
echo $this->Html->script('inventories'); 
?>