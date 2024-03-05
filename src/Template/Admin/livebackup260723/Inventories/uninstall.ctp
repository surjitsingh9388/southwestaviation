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

    .form-control-static a {
        color: #23527c;
        cursor: pointer;
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
            
            <h2 class="heading"><?php echo $this->Html->link('Item Catalog', ['controller'=>'InventoryItems', 'action' => 'index']).' / '.$this->Html->link($invenotries['_matchingData']['InventoryItems']['name'], ['action' => 'detail', $invenotries->id]).' / Uninstall';?></h2>

            <div class="btnWrap">
                <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));

                echo $this->Form->button('Uninstall', ['type' => 'submit', 'class' => 'btn btn-primary ml-10 inventoriesuninstallsave', 'disabled'=>'disabled']);
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <?php
                 echo $this->Form->create($invenotries, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoriesUnInstall'));
                ?>
                
                <div class="addPartBorder">
                <div class="addPageHeading">Item Details</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="uom">Part Number</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries['_matchingData']['InventoryItems']['part_number']; ?></p>
                                </div>
                            </div>
                        
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="location_name">Current Location</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                    $location = $install_to['_matchingData']['InventoryItems']['name'].' (PN:'.$install_to['_matchingData']['InventoryItems']['part_number'].') (SN:'.$install_to['serial_no'].')';
                                    echo '<p class="form-control-static"><a class="dropdown-item" href="'.$this->Url->build(['controller'=>'Inventories', 'action'=>'detail', $install_to['id']]).'">'.$location.'</a></p>';
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="name">Item Name</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries['_matchingData']['InventoryItems']['name']; ?></p>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Available Qty</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->qty; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                
                    <div class="addPageHeading">Uninstallation</div>
                    
                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="install_to">Location&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                        echo $this->Form->control('location_id', array('options' => $locations, 'empty' => 'Select location...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'location_id', 'value'=>'', 'required'=>'required')); 
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="status">Status&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                        $statusarr = ['1'=>'Active', '4'=>'Discarded', '13'=>'Needs Repair', '12'=>'Quarantine'];
                                        echo $this->Form->control('status', array('options' => $statusarr, 'empty' => 'Select status ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'status', 'required'=>'required')); 
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="quantities">Available</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <p class="form-control-static"><?php echo $invenotries->qty; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="qty">Uninstall Quantity (EA)&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('qty', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => 'Quantity', 'label' => false, 'id'=>'qty', 'required'=>'required', 'value'=>$invenotries->qty)); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-6"></div>
                        
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="account_code">Account Code</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php 
                                        echo $this->Form->control('account_code', array('options' => '', 'empty' => 'Enter an account code ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'account_code')); 
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            
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