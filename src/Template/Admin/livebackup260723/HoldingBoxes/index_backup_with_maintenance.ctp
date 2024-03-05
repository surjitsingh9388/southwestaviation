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
    .dataTables_filter {
        display: none;
    }

    #searchItem{
        /*border-radius: 5px;*/
    }

    .actionWrap .dropdown-toggle {
        width:100px !important;
    }


    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        font-size: 1.1em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }

    #itemGeneral{
        padding:10px;
    }

    .mt5 {
        margin-top: 10px !important;
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

    .heading-success {
        color: #FFF;
        background-color: #27ae60;
    }

    .item-counts-row {
        padding-right: 12px;
        padding-left: 12px;
    }

    .item-counts-row .inventory-tile {
        color: #FFF;
        text-align: center;
        margin-top: 0px;
        margin-bottom: 10px;
        font-size: 20px;
        cursor: pointer;
        -webkit-transition: background-color 2000ms ease;
        -moz-transition: background-color 2000ms ease;
        -ms-transition: background-color 2000ms ease;
        -o-transition: background-color 2000ms ease;
        transition: background-color 2000ms ease;
    }
    
    .item-counts-row .instock-count {
        background-color: #0096D9;
    }
    .tile {
        min-width: 125px;
    }

    .item-counts-row .outforrepair-count {
        background-color: #00D3B4;
    }

    .item-counts-row .installed-count {
        background-color: #27AE60;
    }

    .item-counts-row .quarantined-count {
        background-color: #F46323;
    }

    .item-counts-row .allocated-count {
        background-color: #FF0000;
    }

    .item-counts-row .onorder-count {
        background-color: #9B4EB5;
    }

    .status.pi-status-active {
        background-color: #0096D9;
    }

    .status.pi-status-outforrepair {
        background-color: #00D3B4;
    }

    .status.pi-status-installed {
        background-color: #27AE60;
    }

    .status.pi-status-quarantined {
        background-color: #F46323;
    }

    .status.pi-status-allocated {
        background-color: #FF0000;
    }

    .status.pi-status-onorder {
        background-color: #9B4EB5;
    }

    .status.pi-status-shipped {
        background-color: #2C3E50;
    }

    .status.pi-status-damaged {
        background-color: #E74C3C;
    }

    .status.pi-status-discarded {
        background-color: #2C3E50;
    }

    .status.pi-status-unavailable {
        background-color: #3498DB;
    }

    .status.pi-status-needs-repair {
        background-color: #E74C3C;
    }

    .status.pi-status-consumed {
        background-color: #2C3E50;
    }

    .status.pi-status-unrepairable {
        background-color: #E74C3C;
    }

    .po-inactive {
        background-color: #CE0000;
    }

    .status {
        display: inline-block;
        padding: 0.4em 0.6em 0.4em;
        font-size: 12px;
        font-weight: bold;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: text-bottom;
        width: 150px;
        background-color: #777777;
    }

    .dataTable tbody tr{
        cursor:pointer;
    }

    .badge-threshold {
        display: inline-block;
        min-width: 10px;
        padding: 3px 7px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        background-color: #e74c3c !important;
        border-radius: 10px;
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

    .action-bar {
        background-color: #39478b;
        padding: 6px 10px 6px 10px;
        margin-bottom: 12px;
    }

    .action-bar {
        border-radius: 0;
        background-color: #f5f5f5;
        height: 59px;
    }

    #itemsPurchaseorders .action-bar {
        background-color: #39478b;
        padding: 6px 10px 6px 10px;
        margin-bottom: 12px;
        height:auto !important;
    }

    .dataTables_paginate a {
        color: #333 !important;
    }

    .dt-buttons{
        display:none;
    }

    .inventory-item-belowthresholds {
        color: #2ecc71;
        font-size: 17px;
        vertical-align: middle;
    }

    .history-detail-block {
        padding: 9.5px;
        margin: 0 0 10px;
        font-size: 13px;
        line-height: 1.42857143;
        color: #333;
        word-break: break-all;
        word-wrap: break-word;
        background-color: #f5f5f5;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .invitmhistory a {
        color: #23527c;
        text-decoration: underline;
        cursor: pointer;
    }

    label.btn.btn-default.active {
        background-color: #5cca5c;
        color: #fff;
    }

    .error-text {
        color: red;
    }
    .search-control, .actionMenu{
        border: 1px solid #1e040426;
        border-radius: 3px;
    }
    .sortTxt{
        color: #655555 !important;
    }
</style>

<div class="content sliding">
    <div class="outerWrapper">
         
        <div class="btnWrapper">
            <h2 class="heading">Holding Box</h2>
        </div>
        
        <div class="page-content mt-35">
            <div class="formBGCls">
                
                <!-- Tabs Start -->
                <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                    <div class="container">
                        
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#maintenanceItems">Maintenance Items (<?php echo count($results); ?>)</a></li>
                            <li><a data-toggle="tab" href="#itemCatalog">Item Catalog (<?php echo $itemcatalogcount; ?>)</a></li>
                            <li><a data-toggle="tab" href="#physicalInventory">Physical Inventory (<?php echo $inventoriescount; ?>)</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="maintenanceItems" class="tab-pane fade in active">
                                <div class="maintenanceWrap">
                                    <?php
                                    $urlp = $_GET;
                                    $airIds = [];
                                    if(!empty($aircraftIds)) {
                                        $airIds = explode(',', $aircraftIds);
                                    }

                                    if(!empty($airIds)) {
                                        $airChkCnt = count($airIds);
                                        $pdfCheck = 'style="pointer-events: auto;"';
                                    } else {
                                        $airChkCnt = 0;
                                        $pdfCheck = 'style="pointer-events: none; background: #c6c6c6;"';
                                    }

                                    if(!empty($airChkCnt) && $airChkCnt == 1) {
                                        $selectVal = $airChkCnt." Selected";
                                    } elseif(!empty($airChkCnt) && $airChkCnt > 1) {
                                        $selectVal = $airChkCnt." Selected";
                                    } else {
                                        $selectVal = "No Selection";
                                    }

                                    $action = '';
                                    if(!empty($params['action'])) {
                                        $action = $params['action'];
                                    }
                                    ?>
                                    <div class="action-bar ">
                                        <div class="actionbar-lft">
                                            <div class="lftWrap">
                                                <div class="inputWrap btn-group">
                                                    <button id="aircraftSelectorBtn" class="btn btn-default">
                                                    <?php echo $selectVal; ?> <i class="fa fa-caret-down"></i>
                                                    </button>
                                                    <button id="airCompDetailBtn" data-pids="<?= h($aircraftIds); ?>" class="btn btn-default" <?php echo $pdfCheck;?>><i class="fa fa-plane" aria-hidden="true"></i></button>
                                                </div>
                                                
                                                <div id="aircraftDetailsPopup" style="display: none;">
                                                    <div class="modal-content">
                                                        <form id="aircraftDetailForm" action="holding_boxes/index">
                                                            <div style="max-height: 340px; overflow-y: auto;">
                                                                <table id="aircraftUtilization" class="table table-hover table-header-dark">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="10%"><input type="checkbox" name="aircraft_info" id="airCheckAll" <?php if(count($allAircraft) == $airChkCnt) { echo "checked"; } ?>></th>
                                                                            <th width="20%">Registration</th>
                                                                            <th width="10%">Div.</th>
                                                                            <th width="30%">Make & Model</th>
                                                                            <th width="20%">Serial Number</th>
                                                                            <th width="10%">&nbsp;</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                    $i=1;
                                                                    foreach ($allAircraft as $key => $value) {
                                                                        if(in_array($value['id'], $airIds)) {
                                                                            $airSelected = 'checked';
                                                                        } else {
                                                                            $airSelected = '';
                                                                        }
                                                                    ?>
                                                                        <tr>
                                                                            <td><input type="checkbox" class="airChkBoxCls" name="AircraftIds[]" value="<?php echo $value['id']; ?>" <?php echo $airSelected; ?>></td>
                                                                            <td><?php echo $value['plane_code']; ?></td>
                                                                            <td> - </td>
                                                                            <td><?php echo $value['plane_type']; ?></td>
                                                                            <td><?php echo $value['plane_serial_number']; ?></td>
                                                                            <td>
                                                                                <?php
                                                                                if((!empty($reportTime) && ($reportTime['action']['action_add']==1 || $reportTime['action']['action_edit']==1)) || $sessionUser['id'] == 1){
                                                                                ?>
                                                                                <a href="javascript:void(0);" class="reportTimeCls" data-plane_id="<?php echo $value['id']; ?>"><i class="fa fa-clock-o" aria-hidden="true"></i></a>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                        </tr>            
                                                                    <?php
                                                                    $i++;    
                                                                    }
                                                                    ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <input type="hidden" name="type" value="<?php echo $type; ?>" id="statusType">
                                                            <input type="hidden" name="action" value="<?php echo $action; ?>">
                                                            <div class="modal-footer">
                                                                <span style="float: left; font-size: 17px;" id="airCountId"><?php echo $airChkCnt.'/'.count($allAircraft); ?></span>
                                                                <button type="button" class="btn btn-default" id="closeAircraftInfo">Close</button>
                                                                <input type="submit" value="Apply" class="btn btn-primary" />
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div id="airCompDetailsPopup" style="display: none;">
                                                    <div class="modal-content">
                                                        <div class="container">  
                                                            <div id="myCarousel" class="carousel" data-interval="false" data-ride="carousel">
                                                                <div class="carousel-inner">
                                                                <?php
                                                                $modelDes = '';
                                                                $i = 0;
                                                                foreach ($allAircraft as $key => $value) {
                                                                    if(in_array($value['id'], $airIds)) {                               
                                                                        $modelDes = !empty($value['plane_type']) ? $value['plane_type'] : '';
                                                                        foreach ($value['airframe_components'] as $key2 => $value2) {
                                                                            if($value2['log_book'] == 'Airframe') {
                                                                                $modelDes = $value2['description'];
                                                                            }
                                                                        }

                                                                        $active = '';
                                                                        if($i <= 0) {
                                                                            $active = 'active';
                                                                        }
                                                                ?>
                                                                    <div class="item <?php echo $active; ?>">
                                                                        <div class="airDetailPopupHeader">
                                                                            <strong><?php echo $value['plane_code']; ?></strong><?php echo " ".$modelDes; ?>
                                                                            <button type="button" class="btn btn-default closeAirInfoBtn">Close</button>
                                                                            
                                                                            <?php
                                                                            if((!empty($reportAction) && $reportAction['action']['action_add']==1) || $sessionUser['id'] == 1) {
                                                                            ?>
                                                                            <button type="button" class="btn btn-default downloadPdfCls" data-pids="<?= h($value['id']); ?>">Print Times</button>
                                                                            <?php } ?>

                                                                            <?php
                                                                            if((!empty($reportTime) && ($reportTime['action']['action_add']==1 || $reportTime['action']['action_edit']==1)) || $sessionUser['id'] == 1){
                                                                            ?>
                                                                            <button type="button" class="btn btn-default reportTimeCls" data-plane_id="<?php echo $value['id']; ?>">Update Times</button>
                                                                            <?php } ?>
                                                                        </div>
                                                                        <div class="" style="height: 400px; overflow-y: auto; width: 100%;">
                                                                            <div class="col-md-6" style="margin: 3px 0 3px 0;">
                                                                                <div class="air-detail-title">Aircraft Information</div>

                                                                                <div class="form-group"> 
                                                                                    <div class="col-md-5 col-sm-5 col-xs-12">Serial Number</div>
                                                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                                                        <?php echo $value['plane_serial_number']; ?>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group"> 
                                                                                    <div class="col-md-5 col-sm-5 col-xs-12">Airworthiness Date</div>
                                                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                                                        <?php echo !empty($value['airworthiness_date']) ? date('d-M-Y', strtotime($value['airworthiness_date'])) : '&nbsp;'; ?>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group"> 
                                                                                    <div class="col-md-5 col-sm-5 col-xs-12">Schedule Rev Level</div>
                                                                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                                                                        <?php echo !empty($value['federal_aviation_regulation']) ? $value['federal_aviation_regulation'] : '&nbsp;'; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6" style="margin: 3px 0 3px 0;">
                                                                                <div class="air-detail-title">Operator Information</div>
                                                                                <div class="form-group"> 
                                                                                    <div class="col-md-4 col-sm-4 col-xs-12">Owner</div>
                                                                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                                                                        <?php echo !empty($value['plane_name']) ? $value['plane_name'] : '&nbsp;'; ?>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="form-group"> 
                                                                                    <div class="col-md-4 col-sm-4 col-xs-12">Address</div>
                                                                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                                                                        <?php echo !empty($value['address']) ? $value['address'] : '&nbsp;'; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <table class="table table-hover table-header-dark table-condensed">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th width="18%">Equipment</th>
                                                                                        <th width="30%">Model</th>
                                                                                        <th width="10%">Serial</th>
                                                                                        <th width="13%">Report Date</th>
                                                                                        <th width="13%">Reported By</th>
                                                                                        <th width="8%">Hours</th>
                                                                                        <th width="8%">Cycles</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php
                                                                                foreach ($value['airframe_components'] as $key2 => $value2) {

                                                                                    if($value2['log_book'] == 'Airframe') {
                                                                                        $logBook = $value2['log_book'];
                                                                                    } else {
                                                                                        $logBook = $value2['log_book'].' '.$value2['position'];
                                                                                    }

                                                                                    $date = !empty($value2['airframe_component_times'][0]['log_date']) ? date('m-d-Y', strtotime($value2['airframe_component_times'][0]['log_date'])) : '';
                                                                                    $compHours  = !empty($value2['airframe_component_times'][0]['hours']) ? $value2['airframe_component_times'][0]['hours'] : ''; 
                                                                                    $compCycles = !empty($value2['airframe_component_times'][0]['cycles']) ? $value2['airframe_component_times'][0]['cycles'] : '';
                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?php echo $logBook; ?></td>
                                                                                        <td><?php echo $value2['description']; ?></td>
                                                                                        <td><?php echo $value2['serial_no']; ?></td>
                                                                                        <td><?php echo $date; ?></td>
                                                                                        <td> - </td>
                                                                                        <td><?php echo $compHours; ?></td>
                                                                                        <td><?php echo $compCycles; ?></td>
                                                                                    </tr>            
                                                                                <?php    
                                                                                }
                                                                                ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                    $i++;
                                                                    }
                                                                }
                                                                ?>
                                                                </div>

                                                                <?php
                                                                if($airChkCnt > 1) {
                                                                ?>    
                                                                <a class="mvleft carousel-control" href="#myCarousel" data-slide="prev">
                                                                    <span class="glyphicon glyphicon-chevron-left"></span>
                                                                    <span class="sr-only">Previous</span>
                                                                </a>
                                                                <a class="mvright carousel-control" href="#myCarousel" data-slide="next">
                                                                    <span class="glyphicon glyphicon-chevron-right"></span>
                                                                    <span class="sr-only">Next</span>
                                                                </a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php echo $this->element('search_form', array('title'=>'maintenance')); ?>
                                                <?php echo $this->element('search_by', array('title'=>'maintenance')); ?>
                                                <?php echo $this->element('sort_by', array('title'=>'maintenance')); ?>
                                            </div>

                                            <div class="split-btn pull-right actionMenu sortWrap ">
                                                <button class="btn-dropdown btn-default">Action<span class="selectCount"></span></button>
                                                <button class="icon-part dropdown-toggle actionCls" data-toggle="dropdown">
                                                    <i class="fa fa-caret-down"></i>
                                                </button>
                                                <?php
                                                if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $sessionUser['id'] == 1){
                                                ?>                              
                                                <div class="dropdown-content dropdown-menu actionLinks" style="pointer-events: none;">
                                                    <a href="javascript:void(0);" class="removePartCls" data-pids="<?= h($aircraftIds); ?>">Remove</a>
                                                    <a href="javascript:void(0);" class="addComplianceCls" data-pids="<?= h($aircraftIds); ?>">Add Compliance</a>
                                                </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>

                                        <div class="delErrorMsg"></div>
                                    </div>

                                    <div id="activeHistId">
                                    <?php
                                    $actions = ['active'=>'Active', 'historical'=>'Historical'];
                                    if(!empty($action)) {
                                        echo $this->Form->control('display_action', array('options'=>$actions, 'class'=>'actionAHCls selectpicker', 'data-show-subtext'=>true, 'data-live-search'=>false, 'div'=>false, 'label'=>false, 'value'=>$action));
                                    } 
                                    ?>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="customReport" class="table mb-0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th width="4%" class="check"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                                                    <th width="4%">Aircraft</th>
                                                    <th width="4%">ATA</th>
                                                    <th width="14%" class="order" data-datasort="1" data-titlen="mfg_code">Reference & Component & Item Type</th>
                                                    <th width="20%" class="order" data-datasort="1" data-titlen="description">Description</th>
                                                    <th width="11%">Current Hr/Cy</th>
                                                    <th width="10%">Last C/W</th>
                                                    <th width="8%">Intervals</th>
                                                    <th width="10%">Next Due</th>
                                                    <th width="8%">Remaining</th>
                                                    <th width="7%">Status</th>
                                                    <th width="0%"></th>
                                                    <th width="0%"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="aircraftPartsList">
                                            <?php
                                            if(!empty($results)) {
                                                foreach ($results as $row) {
                                                    if($type == 'maintenanceItems' || $type == 'adsbstatus') {

                                                        echo $this->element('partslist', array('row'=>$row, 'typ'=>$urlp['type'], 'act'=>!empty($urlp['action'])?$urlp['action']:''));
                                                    
                                                    } elseif((!empty($row['nextDue']['mos']) || !empty($row['nextDue']['hrs']) || !empty($row['nextDue']['afl'])) && $type != 'maintenanceItems') {
                                                        
                                                        echo $this->element('partslist', array('row'=>$row, 'typ'=>$urlp['type'], 'act'=>!empty($urlp['action'])?$urlp['action']:'')); 
                                                    }
                                                }
                                            } elseif(empty($aircraftIds)) {
                                                echo "<tr class='activeTble'>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>No Aircraft Selected.</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        </tr>";
                                            } 
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="itemCatalog" class="tab-pane fade">
                                <div class="" style="margin-top:10px;">
                                    <?php
                                        echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmItemCatalog'));
                                    ?>
                                    <div class="action-bar">
                                        <div class="col-sm-12">
                                            <div class="col-sm-3">
                                                <input type="text" id="itemCatalogSearchItem" name="searchItem" class="form-control" placeholder="Search Inventory" style="border-radius: 5px">
                                                <div style="display: inline; position:absolute;right: 20px;top: 6px;color: darkgray">
                                                    <i class="fa fa-search"></i>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <select class="selectpicker" id="CatalogFilterBy" name="sortBy">
                                                    <option value="1">Date Added</option>
                                                    <option value="2">Part Number</option>
                                                    <option value="3">Name</option>
                                                    <option value="4">Type</option>
                                                    <option value="5">Serialized</option>
                                                </select>   
                                                
                                                <a class="btn btn-default clearitemcatalogfilter">Clear</a>
                                                <a class="btn btn-primary" onclick="$('#itemCatalogFilterModel').modal('show');">Filter</a>
                                            </div>

                                            <div class="btn-group col-sm-4" style="float:right;">
                                            <?php
                                            if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                                            ?>
                                            
                                                <button id="removeallitemcatalogs" type="button" class="btn btn-danger" data-val='inventory items'>Remove All</button>&nbsp;
                                                <select class="selectpicker actionSel itemcatalogaction" id="actionSel">
                                                    <option value="">Action on Selected</option>
                                                    <option value="1" disabled>Remove</option>
                                                    <option value="2" disabled>Export</option>
                                                    <option value="3" disabled>Apply Tags</option>
                                                    <option value="4" disabled>Print Barcode</option>
                                                </select>    
                                            
                                            <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="">
                                        <table id="datatableItemCatalog" class="table dataTable" width="100%">
                                            <thead>
                                                <tr>
                                                    <th class="check noExl"><input type="checkbox" name="air_check" id="itmCatCheckAll"></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Date Added'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Part No.'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Name'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Type'); ?></th>
                                                    <th style="vertical-align: top;" scope="col"><?php echo __('Serialized'); ?></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <?php 
                                    echo $this->Form->end(); 
                                    ?>
                                </div>
                            </div>
                            <div id="physicalInventory" class="tab-pane fade">
                                <div class="" style="margin-top:10px;">
                                    <?php
                                        echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmPhysicalInventory'));
                                    ?>
                                    <div class="mt5">
                                        <div class="col-sm-12">
                                            <div class="col-sm-3">
                                                <input type="text" id="quantitesSearchItem" name="searchItem" class="form-control" placeholder="Search Quantities" style="border-radius: 5px">
                                                <div style="display: inline; position:absolute;right: 20px;top: 6px;color: darkgray">
                                                    <i class="fa fa-search"></i>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <select class="selectpicker" id="PhysicalInvFilterBy" name="sortBy">
                                                    <option value="1">Date Added</option>
                                                    <option value="2">Item Name</option>
                                                    <option value="3">Part Number</option>
                                                    <option value="4">Lot/Serial Number</option>
                                                    <option value="5">Location</option>
                                                    <option value="9">Status</option>
                                                    <option value="6">Quantity</option>
                                                    <option value="7">Expiration</option>
                                                    <option value="8">Cost</option>
                                                </select>  

                                                <a class="btn btn-default resetquantitiesfilterbtn">Clear</a>
                                                <a class="btn btn-primary" onclick="$('#physicalInventoryFilterModel').modal('show');">Filter</a>
                                                
                                            </div>
                                            
                                            <?php
                                            if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                                            ?>
                                            <div class="btn-group col-sm-4" style="float:right;">
                                                <button id="removeallinventories" type="button" class="btn btn-danger" data-val='physical inventory items'>Remove All</button>&nbsp;
                                                <select class="selectpicker actionSel" id="invquantitiesaction">
                                                    <option value="">Action on Selected</option>
                                                    <option class="invquantitiesoptiondef" value="1" disabled>Remove</option>
                                                    <option class="invquantitiesoptiondef" value="7" disabled>Export</option>
                                                    <option class="invquantitiesoptiondef" value="2" disabled>Bulk Transfer</option>
                                                    <option class="invquantitiesoptiondef" value="3" disabled>Bulk Install</option>
                                                    <option class="invquantitiesoptiondef" value="4" disabled>Bulk Discard</option>
                                                </select>    
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="">

                                            <table id="datatablePhysicalInventory" class="table dataTable table2excel" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th class="check noExl"><input type="checkbox" name="air_check" id="invPhyCheckAll"></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Date Added'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Item Name'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Part Number'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Serial / Lot'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Location'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Qty.'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Expiration'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Cost'); ?></th>
                                                        <th style="vertical-align: top;" scope="col"><?php echo __('Status'); ?></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <?php 
                                        echo $this->Form->end(); 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabs end -->
            </div>
        </div>
       
    </div>
</div>

<div id="physicalInventoryFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content">
            <?php echo $this->element('InventoryFilter/inventory_item_quantities_filter'); ?>
        </div>
    </div>
</div>

<div id="invQuantitiesActionOnSelectModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%; height:auto;">
        <div class="modal-content" id="bulkpopupcontent">
            
        </div>
    </div>
</div>

<div id="itemCatalogFilterModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <?php
            echo $this->Form->create('', array('class' => 'form-horizontal form-label-left', 'id' => 'frmItemCatalogFilter'));
        ?>
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Filter</h4>
            </div>
            <div class="modal-body" style="height: auto;">
                <?php echo $this->element('InventoryFilter/item_catalog_filter'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default clearitemcatalogfilter">Clear</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary applyitemcatalogfilterbtn">Apply Filter</button>
            </div>
        </div>
        <?php 
        echo $this->Form->end(); 
        ?>
    </div>
</div>

<div id="removeAllConfirmModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="glyphicon glyphicon-check"></span>Confirm Remove All</h4>
            </div>
            <div class="modal-body removeallmsg" style="height: auto;"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default confirmremoveallsubmit">Yes</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<!--- inventory print barcode popup --->
<?php echo $this->element('InventoryPopup/inventory_printbarcode_popup'); ?>

<!----- include apply item catalog tag popup --------->
<?php echo $this->element('InventoryPopup/inventory_catalog_apply_tags'); ?>

<!-------------- include printbar code format popup ---------------------------->
<?php echo $this->element('InventoryPopup/item_catalog_printbarcode'); ?>

<!-- Report time popup -->
<?php echo $this->element('report_time_popup'); ?>
<!-- Report time popup -->

<div class="form-hidden">
    <form id="actionForm" method="post"></form>
</div>

<?php echo $this->Html->script('jquery.tokeninput'); ?>
<?php echo $this->Html->css('token-input-facebook.css'); ?>

<script>
var physicalInventoryListingURL = "<?php echo $this->Url->build(['controller'=>'HoldingBoxes', 'action'=>'ajaxPhysicalInventorySearch',]); ?>";
var itemCatalogListingURL = "<?php echo $this->Url->build(['controller'=>'HoldingBoxes', 'action'=>'ajaxInventoryItemsSearch']); ?>";
var removeAllHoldingBoxURL = "<?php echo $this->Url->build(['controller'=>'HoldingBoxes', 'action'=>'removeAllHoldingBox']); ?>";

var saveInventoryCatalogTagsURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'saveInventoryCatalogTags']); ?>";
var exportListingDataExcelURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'exportInvItemListToExcel']); ?>";
var exportPhysicalInvListingDataExcelURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'exportInventoriesToExcel']); ?>";
var saveInventoryCatalogTagsURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'saveInventoryCatalogTags']); ?>";
var printInventoryCatalogBarCodeURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'printItemCatalogBarCode']); ?>";

var getInventoryItemPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'getInventoryItemPopupData']); ?>";

var ajaxOpenActionSelectPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'ajaxOpenActionSelectPopup']); ?>";
var addQuantitiesToHoldingBoxURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'addQuantitiesToHoldingBox']); ?>";
var bulkTransferURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkTransfer']); ?>";
var bulkDiscardURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'bulkDiscard']); ?>";

var printBarCodeURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'printInventoryItemsBarCode']); ?>";
var printInventoriesListBarCodeURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'printInventoriesListBarCode']); ?>";
var inventoryItemDetURL = "<?php echo $this->Url->build(['controller'=>'InventoryItems', 'action'=>'detail']); ?>";
var physicalInventoryDetURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'detail']); ?>";

var physicalInventoryEditURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'edit']); ?>";
var shippingOrderCreateURL = "<?php echo $this->Url->build(['controller'=>'InventoryShippingOrders', 'action'=>'create']); ?>";
var repairOrderCreateURL = "<?php echo $this->Url->build(['controller'=>'InventoryRepairOrders', 'action'=>'create']); ?>";
var physicalInventoryDiscardURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'discard']); ?>";
var physicalInventoryConsumeURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'consume']); ?>";
var physicalInventoryInstallURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'install']); ?>";
var physicalInventoryAdjustURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'adjust']); ?>";
var physicalInventoryTransferURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'transfer']); ?>";
var physicalInventoryUninstallURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'uninstall']); ?>";
var physicalInventoryErrorCorrectURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'error_correct']); ?>";
var updateInventoriesStatusURL = "<?php echo $this->Url->build(['controller'=>'Inventories', 'action'=>'updateInventoriesStatus']); ?>";

$(document).ready(function () {
    
    var dataTablePhysicalInventory = $('#datatablePhysicalInventory').DataTable({
        'columnDefs': [
            { 'orderable': false, 'targets': '_all' },
            { className: "check noExl", "targets": [ 1 ] },
            { 'visible': false, 'targets': [1] }
        ],
        'order': [0, 'asc'],
        //"searching": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[<?php echo PAGINATION_LIMIT;?>, 50, 100, -1], [<?php echo PAGINATION_LIMIT;?>, 50, 100, "All"]],
        "lengthChange": false,
        "ajax":{
            url:"<?php echo $this->Url->build(['controller'=>'HoldingBoxes', 'action'=>'ajaxPhysicalInventorySearch']); ?>",
            accepts: 'application/json', 
            type: "post",
            error: function(){
                $(".employees-grid-error").html("");
                $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employees-grid_processing").css("display","none");
            }
        },
        "drawCallback": function (response) {
            var respobj = response.json;
            if(respobj.recordsTotal != undefined){
                $(".physicalinventorycount").html(respobj.recordsTotal);
            }
        },
    });

    $('#quantitesSearchItem').bind("keyup", function(){
        var formdata = $('#frmPhysicalInventory,#frmInventoryQuantitiesFilter').serialize();
        searchInventoryQantities(formdata);
    });

    $(document).on('click', ".resetquantitiesfilterbtn", function (e) {
        $("#quantitesSearchItem").val('');
        $("#showinactiveinv").prop('checked', false);

        dataTablePhysicalInventory.columns(0).search('').draw();
        dataTablePhysicalInventory.columns(1).search('').draw();

        $('#frmInventoryQuantitiesFilter')[0].reset();
        $('#frmPhysicalInventory')[0].reset();

        var formdata = '';
        searchInventoryQantities(formdata);
        $('.selectpicker').selectpicker('refresh');
    });

    //item catalog table data get

    var dataTableItemCatalog = $('#datatableItemCatalog').DataTable({
        'columnDefs': [
            { 'orderable': false, 'targets': '_all' },
            { className: "check noExl", "targets": [ 1 ] },
            { 'visible': false, 'targets': [1] }
        ],
        'order': [0, 'asc'],
        //"searching": false,
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[<?php echo PAGINATION_LIMIT;?>, 50, 100, -1], [<?php echo PAGINATION_LIMIT;?>, 50, 100, "All"]],
        "lengthChange": false,
        "ajax":{
            url:"<?php echo $this->Url->build(['controller'=>'HoldingBoxes', 'action'=>'ajaxItemCatalogSearch']); ?>",
            accepts: 'application/json', 
            type: "post",
            error: function(){
                $(".employees-grid-error").html("");
                $("#employees-grid").append('<tbody class="employees-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employees-grid_processing").css("display","none");
            }
        },
        "drawCallback": function (response) {
            var respobj = response.json;
            if(respobj.recordsTotal != undefined){
                $(".itemcatalogcount").html(respobj.recordsTotal);
            }
        },
    });

    $(document).on('click', ".resetquantitiesfilterbtn", function (e) {
        $("#itemCatalogSearchItem").val('');
        $("#showinactiveinv").prop('checked', false);

        dataTableItemCatalog.columns(0).search('').draw();
        dataTableItemCatalog.columns(1).search('').draw();

        $('#frmItemCatalog')[0].reset();
        $('#frmItemCatalogFilter')[0].reset();

        var formdata = '';
        searchItemCatalog(formdata);
        $('.selectpicker').selectpicker('refresh');
    });

    $(document).on('change', '#CatalogFilterBy', function (e) {
        dataTableItemCatalog.order([$(this).val(), 'asc']).draw();
    });

    $(document).on('change', '#PhysicalInvFilterBy', function (e) {
        dataTablePhysicalInventory.order([$(this).val(), 'asc']).draw();
    });

});
$(document).on('click', '.applyinvqtyfilterbtn', function(e){
    var formdata = $('#frmPhysicalInventory,#frmInventoryQuantitiesFilter').serialize();
    searchInventoryQantities(formdata);

    $("#physicalInventoryFilterModel").modal('hide');
});
$(document).on('click', '.clearinvitmquantitiesfilter', function(e){
    $('#frmInventoryQuantitiesFilter')[0].reset();
    $('#frmPhysicalInventory')[0].reset();

    $('.selectpicker').selectpicker('refresh');
    var formdata = '';
    searchInventoryQantities(formdata);
})
function searchInventoryQantities(formdata){
    var dataTablePhysicalInventory = $('#datatablePhysicalInventory').DataTable();
    dataTablePhysicalInventory.columns(2).search(formdata).draw();
}

//item catalog filter
$('#itemCatalogSearchItem').bind("keyup", function(){
    var formdata = $('#frmItemCatalog,#frmItemCatalogFilter').serialize();
    searchItemCatalog(formdata);
});

$(document).on('click', '.applyitemcatalogfilterbtn', function(e){
    var formdata = $('#frmItemCatalog,#frmItemCatalogFilter').serialize();
    searchItemCatalog(formdata);

    $("#itemCatalogFilterModel").modal('hide');
});
$(document).on('click', '.clearitemcatalogfilter', function(e){
    $('#frmItemCatalog')[0].reset();
    $('#frmItemCatalogFilter')[0].reset();

    $('.selectpicker').selectpicker('refresh');
    var formdata = '';
    searchItemCatalog(formdata);
})
function searchItemCatalog(formdata){
    var dataTableItemCatalog = $('#datatableItemCatalog').DataTable();
    dataTableItemCatalog.columns(2).search(formdata).draw();
}

$(document).on('click', '#datatableItemCatalog tbody td', function (e) {
    if ($(this).index() == 0 ) {
        return;
    }
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".itmCatChkBoxCls").val();
    if(values != undefined){
        window.location.href = inventoryItemDetURL+'/'+values;
    }
});

$(document).on('click', '#datatablePhysicalInventory tbody td', function (e) {
    if ($(this).index() == 0 ) {
        return;
    }
    var row = $(this).closest("tr");    // Find the row
    var values = row.find(".invPhyChkBoxCls").val();
    if(values != undefined){
        window.location.href = physicalInventoryDetURL+'/'+values;
    }
});

$(function() {
    var newToken;
    $('#catalogtags').tokenInput([], {
        theme: "facebook",
        hintText: "Enter a tag...",
        preventDuplicates: true,
        zindex: '9999',
        onAdd: function(item){
            newToken = null;
        },
        onReady: function(){          
            $("#token-input-catalogtags").keyup(function(event) {
                
                if (event.keyCode === 13 || event.keyCode === 9 || event.keyCode === 188) // return, tab, or comma
                {
                if (newToken) $('#catalogtags').tokenInput("add", {id: newToken, name: newToken});
                }
                
                newToken = $("tester").text();
            });
        }
    }); 
}); 

$(document).ready(function() {
    //Active/Historical dropdown
    $(document).on("change", "#display-action", function() {
        var pids = $("#downloadPdfId").data('pids') || [];
        //console.log(pids);
        var type = $("#statusType").val();
        var action = $(this).val();

        //Set action attribute with downloadpdf element
        //$('#downloadPdfId').data('action',action);

        var form = $('<form action="maintenance">');
        if(Math.floor(pids) == pids && $.isNumeric(pids)) {
            form.append('<input type="hidden" name="AircraftIds[]" value="'+pids+'">');
        } else {
            pids = pids.split(',');
            $.each(pids, function( index, value ) {
                form.append('<input type="hidden" name="AircraftIds[]" value="'+value+'">');
            });
        }
        form.append('<input type="hidden" name="type" value="'+type+'">');
        form.append('<input type="hidden" name="action" value="'+action+'">');
        $('body').append(form);
        form.submit();
    });

    //Checkbox script to count past due, tolerance and coming due
    $("#ckbCheckAll").click(function () {
        $(".chkBoxCls").prop('checked', $(this).prop('checked'));

        var vals = [];
        $("input.chkBoxCls:visible:checked").each(function() {
            vals.push($(this).val());
        });

        //Add part ids to generate pdf
        $('#downloadPdfId').data('partids',vals);
        $('#projectedPdfId').data('partids',vals);
        
        //Add search value to generate pdf
        $('#downloadPdfId').data('searchval', $('#searchItem').val().toLowerCase());
        $('#projectedPdfId').data('searchval', $('#searchItem').val().toLowerCase());

        //Part ids to delete parts
        var checkedcount = $('input.chkBoxCls:visible:checked').length;
        //console.log(checkedcount, vals);
        $('.removePartCls').data('partids', vals);
        $('.addComplianceCls').data('partids', vals);
        if(checkedcount >= 1) {
            $('.removePartCls').data('aircounts', 'all');
            $('.removePartCls').css('pointer-events', 'auto');
            $('.addComplianceCls').css('pointer-events', 'auto');
            $('.selectCount').html("("+checkedcount+")");
        } else {
            $('.removePartCls').data('aircounts', '');
            $('.removePartCls').css('pointer-events', 'none');
            $('.addComplianceCls').css('pointer-events', 'none');
            $('.selectCount').html('');
        }
    });

    $(".chkBoxCls").click(function () {
        var totalCheckboxes = $('input.chkBoxCls:checkbox').length;
        var checkedcount = $('input.chkBoxCls:checked').length;
        
        if(totalCheckboxes == checkedcount) {
            $('#ckbCheckAll').prop('checked', true);
        } else {
            $('#ckbCheckAll').prop('checked', false);
        }
                
        var vals = [];
        $("input.chkBoxCls:checked").each(function() {
            vals.push($(this).val());
        });

        //Add part ids to generate pdf
        $('#downloadPdfId').data('partids',vals);
        $('#projectedPdfId').data('partids',vals);

        //Add search value to generate pdf
        $('#downloadPdfId').data('searchval', $('#searchItem').val().toLowerCase());
        $('#projectedPdfId').data('searchval', $('#searchItem').val().toLowerCase());

        //Part ids to delete parts
        $('.removePartCls').data('partids', vals);
        $('.addComplianceCls').data('partids', vals);
        if(checkedcount >= 1) {
            $('.removePartCls').data('aircounts', '');
            $('.removePartCls').css('pointer-events', 'auto');
            $('.addComplianceCls').css('pointer-events', 'auto');
            $('.selectCount').html("("+checkedcount+")");
        } else {
            $('.removePartCls').data('aircounts', '');
            $('.removePartCls').css('pointer-events', 'none');
            $('.addComplianceCls').css('pointer-events', 'none');
            $('.selectCount').html('');
        }
    });
    //Checkbox script to count past due, tolerance and coming due

    //Change sorting dynamically
    var oTable = $('#customReport').DataTable({
        "scrollY": $(window).height()/1.70,
        "scrollCollapse": true,
        "searching": false,
        "paging": false,
        "info": false,
        "responsive": true, 
        "columnDefs": [
            { "orderable": false, "targets": 0 },
            { "orderData": [ 11 ], "targets": [ 10 ] },
            { "orderData": [ 12 ], "targets": [ 9 ] }, 
            { "visible": false, "targets": [ 11,12 ] },
            { "searchable": false, "targets": [ 0,5,6,7,8,9,10,11,12 ] }
        ],
        "order": []
    });

    new $.fn.dataTable.FixedHeader( oTable );

    $("select#sortById").change(function() {
        var val = $(this).prop('selectedIndex') + 1;
        oTable.order( [ val, 'asc' ] )
        .draw();
    });

    //Get custom sort values added in columns
    var temp = '';
    $("#customReport").find("thead").on('click', 'th.order', function(event) {
        event.preventDefault();
        //var col_idx = oTable.column(this).index();
        var datasort = $(this).data('datasort');
        var titlen = $(this).data('titlen');

        if(temp != titlen) {
            $(this).prevAll().data('datasort', 1);
            $(this).nextAll().data('datasort', 1);
        } 

        if($(this).data('datasort') == 0) {
            $('#downloadPdfId').data('datasort', 'DESC');
            $('#projectedPdfId').data('datasort', 'DESC');
        } else {
            $('#downloadPdfId').data('datasort', 'ASC');
            $('#projectedPdfId').data('datasort', 'ASC');
        }

        $('#downloadPdfId').data('titlen', titlen);
        $('#projectedPdfId').data('titlen', titlen);
        $(this).data('datasort', !datasort);
        var temp = titlen;
    });
    
    //jQuery custom search
    $("#searchItem").on("keyup", function() {
        var value = $(this).val().toLowerCase();

        $('.loader').show();
        setTimeout(function() {
            $('.loader').hide();
            
            //Searching
            oTable.search(value).draw();

            /**************** 10/12/2020 ******************/
            //Add search value to generate pdf
            $('#downloadPdfId').data('searchval',value);
            $('#projectedPdfId').data('searchval',value);
            var vals = [];
            $("input.chkBoxCls:visible").each(function() {
                vals.push($(this).val());
            });

            //Add part ids to generate pdf
            $('#downloadPdfId').data('partids',vals);
            $('#projectedPdfId').data('partids',vals);
            /**********************************/

            //Remove checked part ids if search used to generate pdf
            //$('#downloadPdfId').data('partids', '');
            $('.removePartCls').data('partids', '');
            $('.addComplianceCls').data('partids', '');
            $(".chkBoxCls").prop('checked', false);
            $("#ckbCheckAll").prop('checked', false);

            //Disable checkbox when search
            //$("#ckbCheckAll").css('pointer-events', 'none');
            $('.selectCount').html('');
        }, 100);

        $("#aircraftPartsList tr").filter(function() {
            //console.log("aaa: ", $(this).text().toLowerCase().indexOf(value));
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $(document).on("change", "#searchByVal", function() {
        $('#downloadPdfId').data('searchby',$(this).val());
        $('#projectedPdfId').data('searchby',$(this).val());
    });

    //Download pdf
    $('#downloadPdfId').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var pids = $(this).data('pids') || [];
        var partids = $(this).data('partids');
        var type = $("#statusType").val();
        var searchval = $(this).data('searchval');
        var searchby = $(this).data('searchby');
        var titlen = $(this).data('titlen');
        var datasort = $(this).data('datasort');
        var action = $(this).data('action');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'generateMultiAircraftPdf']); ?>",
            data: {pids: pids, partids: partids, type: type, searchval:searchval, searchby:searchby, titlen: titlen, datasort: datasort, action: action},
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('.loader').hide();
                    //Open pdf in new link
                    window.open(obj.data);
                } else if(obj.status == 'failure') {
                    $('.loader').hide();
                    alert('Some error occured. Please try again!');
                } else {
                    $('.loader').hide();
                }
            },
            error : function() {
                $('.loader').hide();
                alert('Some error occured. Please try again!');
            },
            complete: function () {
                $('.loader').hide();
            }
        });
    });

    //Get all aircraft details
    $('#aircraftSelectorBtn').on('click', function(e) {
        $('#aircraftDetailsPopup').toggle();
    });

    //Close div
    $('#closeAircraftInfo').on('click', function() {
       $('#aircraftDetailsPopup').hide();
    });

    $('#airCompDetailBtn').on('click', function(e) {
        $('#airCompDetailsPopup').toggle();
    });

    $('.closeAirInfoBtn').on('click', function() {
        $('#airCompDetailsPopup').hide(); 
    });

    //Generate Aircraft Times PDF from popup
    $('.downloadPdfCls').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var pids = $(this).data('pids');
        
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'generateAircraftTimesPdf']); ?>",
            data: {pids: pids},
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('.loader').hide();
                    //Open pdf in new link
                    window.open(obj.data);
                } else if(obj.status == 'failure') {
                    $('.loader').hide();
                    alert('Some error occured. Please try again!');
                } else {
                    $('.loader').hide();
                }
            },
            error : function() {
                $('.loader').hide();
                alert('Some error occured. Please try again!');
            },
            complete: function () {
                $('.loader').hide();
            }
        });
    });

    //Aircraft Checkbox script to count
    $("#airCheckAll").click(function () {
        $(".airChkBoxCls").prop('checked', $(this).prop('checked'));
        var totalCheckboxes = $('input.airChkBoxCls:checkbox').length;
        var checkedcount = $('input.airChkBoxCls:checked').length;
        if(totalCheckboxes == checkedcount) {
            $('#airCountId').html(totalCheckboxes+'/'+totalCheckboxes);
        } else {
            $('#airCountId').html(checkedcount+'/'+totalCheckboxes);
        }
    });

    $(".airChkBoxCls").click(function () {
        var totalCheckboxes = $('input.airChkBoxCls:checkbox').length;
        var checkedcount = $('input.airChkBoxCls:checked').length;
        if(totalCheckboxes == checkedcount) {
            $('#airCheckAll').prop('checked', true);
        } else {
            $('#airCheckAll').prop('checked', false);
        }    
        $('#airCountId').html(checkedcount+'/'+totalCheckboxes);
    });
    
    //Report time model
    $(document).on("click", ".reportTimeCls", function() {
        var plane_id  = $(this).data('plane_id');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'compNewTime']); ?>",
            data: {plane_id: plane_id},
            async : true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#planeCode').html(obj.planeCode);
                    $('#reportTimeModel .modal-body').html(obj.data);
                    $('#reportTimeModel').modal('show');
                    $('#historyTime').data('plane_id', plane_id);
                    $('#errorMsg').html('');
                } else {
                    alert('No data exists.');
                }
            }
        });
    });

    //Projected report time model
    $(document).on("click", "#projectedPdfId", function() {
        var pid = $(this).data('pid');
        var partids = $(this).data('partids');
        var type = $("#statusType").val();
        var reporttype = 'projected';
        var searchval = $(this).data('searchval');
        var searchby = $(this).data('searchby');
        var titlen = $(this).data('titlen');
        var datasort = $(this).data('datasort');
        var action = $(this).data('action');
        //var pidArr = pid.split(',');
        if(Number.isInteger(pid) == true) {
            $.ajax({
                type: "POST",
                url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'compProjectedTime']); ?>",
                data: {plane_id: pid, partids: partids, type: type, reporttype:reporttype, searchval:searchval, searchby:searchby, titlen: titlen, datasort: datasort, action: action},
                async : true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    //console.log(obj.data);
                    if(obj.status == 'success') {
                        $('#projectedAirCode').html(obj.planeCode);
                        $('#projectedTimeModel .modal-body').html(obj.data);
                        $('#projectedTimeModel').modal('show');
                        $('#projErrorMsg').html('');
                    } else {
                        alert('No data exists.');
                    }
                }
            });
        } else {
            alert('Please select single aircraft to generate projected report.');
        }
    });

    //Generate projected report
    $(document).on('click', '#saveProjTimeBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        var data = $('#projectedTimeFrm').serialize();
        $.ajax({
            url : "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'generateMultiAircraftPdf']); ?>",
            type : 'post',
            data : data,
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('.loader').hide();
                    //Open pdf in new link
                    window.open(obj.data);
                } else if(obj.status == 'failure') {
                    $('.loader').hide();
                    alert('Some error occured. Please try again!');
                } else {
                    $('.loader').hide();
                }
            },
            error : function() {
                $('.loader').hide();
                alert('Some error occured. Please try again!');
            },
            complete: function () {
                $('.loader').hide();
            }
        });
        
    });

    //Initiate after html load
    $(document).on('show.bs.modal','.modal', function () {
        $('.logDatepicker').datetimepicker({
            format: 'MM-DD-YYYY',
            useCurrent: false,
            maxDate: new Date()
        });
        
        $('.projDatepicker').datetimepicker({
            format: 'MM/DD/YYYY',
            useCurrent: false
        });
    });

    //Validation on report time form
    //https://stackoverflow.com/questions/24670447/how-to-validate-array-of-inputs-using-validate-plugin-jquery
    $("#reportTimeFrm").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'hours_accrued[0]': {
                required: true
            },
            'cycles_accrued[0]': {
                required: true
            }
        },
        messages: {
            'hours_accrued[0]': {
                required: "Please enter hours."
            },
            'cycles_accrued[0]': {
                required: "Please enter cycles."
            }
        }
    });
   
    //Save Report Time details
    $(document).on('click', '#saveRTimeBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        if($('#reportTimeFrm').valid()) {
            var data = $('#reportTimeFrm').serialize();
            $.ajax({
                url : "<?php echo $this->Url->build(['controller' => 'Reports', 'action' => 'addTime']); ?>",
                type : 'post',
                data : data,
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#errorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                    } else {
                        $('#errorMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    }                    
                },
                error : function(){
                    alert('Some error occured. Please try again!');
                    $('#reportTimeModel').modal('hide');
                },
                complete: function () {
                    $('#reportTimeModel').modal('hide');
                }
            });
        }
    });

    //Change readonly status
    $(document).on('click', '.chkClass', function () {
        var key = $(this).data('key');
        var val = $(this).data('val');
        if ($(this).is(':checked')) {
            $('.'+val+'_'+key).prop('readonly', false);
        } else {
            $('.'+val+'_'+key).prop('readonly', true);
        }
    });

    //Historical times
    $(document).on("click", "#historyTime", function() {
        var plane_id  = $(this).data('plane_id');
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'aircraftHistoricalTime']);?>">');
        form.append('<input type="hidden" name="plane_id" value="'+plane_id+'">');
        $('body').append(form);
        form.submit();
    });

    //Parent/Child details popup
    $(document).on("click", ".prChildCls", function() {
        var airid = $(this).data('airid');
        var pid   = $(this).data('pid');
        var type  = $(this).data('type');
        var cid   = $(this).data('cid');
        var typ   = $(this).data('typ');
        var act   = $(this).data('act');
        
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'parentChildInfo']); ?>",
            data: {airid: airid, pid: pid, type: type, cid:cid, typ:typ, act:act},
            async : true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                $('.loader').hide();
                $('#parentChildModel .modal-content').html(response);
                $('#parentChildModel').modal('show');
            },
            error : function() {
                $('.loader').hide();
                $('#parentChildModel .modal-content').html('');
                $('#parentChildModel').modal('hide');
            }
        });
    });

    //Delete parts
    $('.removePartCls').on('click', function() {
        var pids = $(this).data('pids') || [];
        var partids = $(this).data('partids');
        var aircounts = $(this).data('aircounts');

        if (confirm('Are you sure you want to delete this?')) {
            $.ajax({
                url : "<?php echo $this->Url->build(['controller'=>'AirframeComponentParts', 'action'=>'deleteMultiParts']); ?>",
                type : 'post',
                data : {pids: pids, partids: partids, aircounts: aircounts},
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#delErrorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        
                        setTimeout(function() {
                            location.reload(true);
                        }, 1000);
                    } else {
                        $('#delErrorMsg').html('<span style="color:red;">'+obj.message+'</span>').hide(5000);
                    }                  
                }
            });
        }
    });

    //Add compliance to inspections without create a group
    /*$('.addComplianceCls').on('click', function() {
        var pids = $(this).data('pids') || [];
        var partids = $(this).data('partids');

        $('#addComplianceModel .pidCls').val(pids);
        $('#addComplianceModel .partsCls').val(partids);
        $('#addComplianceModel').modal('show');
    });*/

    //Add custom compliance to inspections without create a group
    $(document).on("click", ".addComplianceCls", function() {
        var pids = $(this).data('pids') || [];
        var partids = $(this).data('partids');
        
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Compliance', 'action'=>'addCustomCompliance']);?>">');
        form.append('<input type="hidden" name="aircraftId" value="'+pids+'">');
        form.append('<input type="hidden" name="partids" value="'+partids+'">');
        form.append('<input type="hidden" name="ftype" value="initial">');
        $('body').append(form);
        form.submit();
    });

});

</script>
<?php 
echo $this->Html->script('holdingbox'); 
?>