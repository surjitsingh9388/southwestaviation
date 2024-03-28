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
    
    #itemGeneral{
        padding:10px;
    }

    .mb-3 {
        margin-bottom: 10px !important;
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
        background-color: #2C3E50;
        color: #ecf0f1;
        padding: 10px 15px;
        margin-top: 0;
        margin-left: 0px;
        height: 40px;
    }


    .upload-area{
        width: 100%;
        min-height: .01%;
        border: 2px solid lightgray;
        border-radius: 3px;
        margin: 0 auto;
        margin-top: 10px;
        text-align: center;
        overflow: auto;
    }

    .document-name {
        white-space: nowrap;
        overflow: hidden;
        -ms-text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis;
    }

    .tab-content th{
        background-color:#2c3e50;
        color:white;
    }

</style>

<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($invenotryrequests, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryRequest', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper">
            <h2 class="heading"><?php echo $this->Html->link('Inventory Requests', ['action' => 'index']).' / '.$this->Html->link($invenotryrequests->request_number, ['action' => 'detail', $invenotryrequests->id]); ?>/ Edit</h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Cancel", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Save', ['type' => 'button', 'class' => 'btn btn-primary ml-10 invrequestitemsave']);
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                
                <div class="addPartBorder" style="padding-top:10px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Request Number&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo $this->Form->control('request_number', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Title&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                                <?php echo $this->Form->control('title', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Description</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo $this->Form->control('description', array('class' => 'form-control col-md-8 col-xs-12', 'label'=> false, 'rows'=>2)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Requested By&nbsp;<span class="required">*</span></label>
                                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                                <?php echo $this->Form->control('requested_by', array('class'=>'form-control col-md-8 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"> 
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="plane_id">Need By<span class="required">*</span>
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('need_by', array('class' => 'form-control col-md-3 col-xs-12', 'id' => 'need_by', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="airframe_component_id">Urgency</label>
                                <div class="col-md-8 col-sm-8 col-xs-12" id="airCompsList">
                                    <?php 
                                    $urgency = unserialize(URGENCY);
                                    echo $this->Form->control('urgency', array('options' => $urgency, 'empty' => 'Select urgency ...', 'class' => 'form-control col-md-8 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'currency')); 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div style="clear: both;"></div>

                <!-- Tabs Start -->
                <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemGeneral">Line Items</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemGeneral" class="tab-pane fade in active"><!-- general-tab-section start -->
                                <div class="g-0 bg-light position-relative">
                                    
                                    <table class="table upload-area" id="uploadfile">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="col-sm-1"></th>
                                                <th class="col-sm-2">Item</th>
                                                <th class="col-sm-2">Qty</th>
                                                <th class="col-sm-2">UOM</th>
                                                <th class="col-sm-1">Location Needed</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            
                                            foreach($inventoryrequestitems as $invreqitem){
                                                echo $this->element("Inventory/inventory_request_item_add", array('invreqitem'=>$invreqitem));
                                            } ?>
                                            <tr id="invrequeststbl">
                                                <td colspan="5">
                                                    <a class="btn btn-primary addinvitem" data-val="invitem"><i class="fa fa-plus-circle"></i> Add Inventory Item</a>
                                                    <a class="btn btn-primary addinvitem" data-val="noninvitem"><i class="fa fa-plus-circle"></i> Add Non-Inventory Item</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- general-tab-section end -->

                        </div>
                    </div>
                </div>
                <!-- Tabs end -->
            </div>
        </div>
    <?php 
    echo $this->Form->end(); 
    ?>
    </div>
</div>

<div id="inventoryItemModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Create New Part</h4>
            </div>
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                <?php echo $this->element('Inventory/create_new_part', array('isinvrequestpage'=>1)); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary invItemSaveBtn" disabled>Create</button>
            </div>
        </div>
    </div>
</div>

<script> 
var getInventoryItemDropdownURL = "<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'inventoryDropDown']); ?>";
var saveInventoryItemsURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'saveInventoryItems']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>
<?php 
echo $this->Html->script('inventory_common');
echo $this->Html->script('inventory_request'); 
?>