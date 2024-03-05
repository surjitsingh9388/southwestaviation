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

    #filetbody a{
        color:blue !important;
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

    .tab-content td{
        text-align:left;
    }
    #invrequeststbl a{
        color: #092E6E !important;
        text-decoration:none;
    }
    
    .heading div {
        padding: 2px !important;
        margin-left: 10px !important;
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

    #invrequeststbl td{
        text-align:left;
    }

    .addPageHeading {
        font-size: 11pt;
        background-color: #1f2a5e;
        color: #ecf0f1;
        padding: 10px 15px;
        margin-top: 0;
        margin-left: 0px;
        height: 40px;
        width:100%;
    }

</style>

<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($invenotryrequests, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryApproveDenyRequest', 'autocomplete'=>'off'));
        ?>  
        <div class="btnWrapper">
            <h2 class="heading">
                <?php echo $this->Html->link('Inventory Requests', ['action' => 'index']).' / Inventory Request #'.$invenotryrequests->request_number; ?>
            </h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Deny', ['type' => 'button', 'class' => 'btn btn-danger ml-10 invrequestsapprovedeny', 'data-val'=>'3']);
                    echo $this->Form->button('Approve', ['type' => 'button', 'class' => 'btn btn-primary ml-10 invrequestsapprovedeny', 'data-val'=>'1']);
                }
                ?>
            </div>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                <div class="addPageHeading">General Information</div>
                <div style="padding-top:10px;padding-left: 10px;">
                    <input type="hidden" name="request_status" id="request_status">
                    <div class="">
                        <label class="col-lg-2" for="plane_id">Title</label>
                        <div class="content-display col-lg-10 form-control-static"><?php echo $invenotryrequests->title; ?></div>
                    </div>
                    <div class="">
                        <label class="col-lg-2" for="plane_id">Description</label>
                        <div class="content-display col-lg-10 form-control-static"><?php echo $invenotryrequests->description; ?></div>
                        
                    </div>
                    <div class="">
                        <label class="col-lg-2" for="plane_id">Requested By</label>
                        <div class="content-display col-lg-4 form-control-static"><?php echo $invenotryrequests->requested_by; ?></div>
                    
                        <label class="col-lg-2" for="plane_id" >Need By</label>
                        <div class="content-display col-lg-4 form-control-static"><?php echo date('d-M-Y',strtotime($invenotryrequests->need_by)); ?></div>
                       
                    </div>
                    <div class="">
                        <label class="col-lg-2">Urgency</label>
                        <div class="content-display col-lg-4 form-control-static"><?php 
                        $urgency = unserialize(URGENCY);

                        echo !empty($invenotryrequests->urgency) ? $urgency[$invenotryrequests->urgency] : ''; ?>
                        </div>
                    </div>
                    
                </div>

                <div style="clear: both;"></div>
                    <div class="g-0 bg-light position-relative">
                        
                        <table class="table upload-area" id="uploadfile">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="col-sm-2">Item</th>
                                    <th class="col-sm-2">Qty Requested</th>
                                    <th class="col-sm-1">Location Needed</th>
                                </tr>
                            </thead>
                            <tbody id="invrequeststbl">
                                <?php
                                $defaultUOM = unserialize(DEFAULT_UOM);
                                foreach($inventoryrequestitems as $val){
                                ?>
                                <tr>
                                    <td>
                                        <?php echo isset($val['invitms']['name']) ? $val['invitms']['name'] : $val['noninventory_item']; ?></td>
                                    <td><?php echo $val['qty'].' '.$defaultUOM[$val['uom']]; ?></td>
                                    <td><?php echo isset($val['invloc']['location_name']) ? $val['invloc']['location_name'] : ''; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="">
                        <div class="form-group"> 
                            <label class="col-lg-2" style="padding-top:10px;">Comments</label>
                            <div class="col-md-10 col-sm-10 col-xs-12">
                                <?php echo $this->Form->input('comment', array('type' => 'textarea', 'class'=>'form-control col-md-10 col-xs-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                            </div>
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
<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<script> 
var updaterequeststatusURL = "<?php echo $this->Url->build(['controller'=>'InventoryRequests', 'action'=>'updaterequeststatus']); ?>";

var ajaxListPageSearchURL = '';
var pagelimit = '';
var pdfPagTitle = '';
</script>
<?php 
echo $this->Html->script('inventory_common');
echo $this->Html->script('inventory_request'); 
?>