<?php
echo $this->Form->create($aircraftwoitems, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWorkOrderItems'));

$item_no = isset($aircraftwoitems->item_no) && !empty($aircraftwoitems->item_no) ? $aircraftwoitems->item_no : '1';
$wo_item_position = isset($aircraftwoitems->wo_item_position) && !empty($aircraftwoitems->wo_item_position) ? $aircraftwoitems->wo_item_position : '1';

?>
<div class="row">
    <input type="hidden" name="wo_item_id" id="wo_item_id" value="<?php echo $aircraftwoitems->id; ?>">
    <input type="hidden" name="item_no" id="wo_item_no" value="<?php echo $item_no; ?>" />

    <input type="hidden" name="current_item_position" id="current_item_position" value="<?php echo $current_item_position; ?>" />
    <input type="hidden" id="click_item_index" value="<?php echo $wo_item_position_index; ?>" />
    <input type="hidden" name="last_item_position" id="last_item_position" value="<?php echo $totalitemcount; ?>" />
    
    <div class="col-sm-2">
        <div class="form-group work-order-item-box">
            <p style="margin-bottom:5px !important;">Item No</label>
            <div class="form-input-frame">
                <span class="woitemno"><?php echo $wo_item_position.'/'.$totalitemcount; ?></span>
            </div>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default aircraft-wo-move-item">Move Items</button>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="col-xs-12 pd0">
            <a href="javascript:void(0);" class="woheadingblue wo_item_discrepancy">Discrepancy</a>

            <a href="javascript:void(0);" class="woheadingblue wo_item_discrepancy_history float-right">History</a>
        </div>
        <div class="col-xs-12 pd0 form-group">
            <div class="form-input-frame">
                <?php 
                $wo_discrepancy = !empty($aircraftwoitems->wo_discrepancy) ? $aircraftwoitems->wo_discrepancy : '';
                echo $this->Form->control('wo_discrepancy', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5', 'style'=>'height: 88px; width: 100%;', 'id'=>'wo_item_discrepancy', 'value'=>$wo_discrepancy)); ?>
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="col-xs-12 pd0">
            <a href="javascript:void(0);" class="woheadingblue wo_item_corrective_action">Corrective Action</a>

            <a href="javascript:void(0);" class="woheadingblue wo_item_corrective_action_history float-right">History</a>
        </div>
        <div class="col-xs-12 pd0 form-group"> 
            <div class="form-input-frame">
                <?php 
                $wo_corrective_action = !empty($aircraftwoitems->wo_corrective_action) ? $aircraftwoitems->wo_corrective_action : '';
                echo $this->Form->control('wo_corrective_action', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5', 'style'=>'height: 88px; width: 100%;', 'value'=>$wo_corrective_action)); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7 col-sm-5 form-group">
        <label class="control-label col-md-4 col-sm-4" for="plane_id" style="width:27%;">Created By</label>
        <div class="col-md-8 col-sm-8">
            <?php
            echo $this->Form->control('added_by', array('options' => $userlist, 'empty' => 'Select created by...', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_created_by', 'disabled'=>'disabled')); 
            ?>
        </div>
    </div>
    <div class="col-md-5 col-sm-7 form-group"> 
        <label class="control-label col-md-3" for="plane_id">Item Status</label>
        <div class="col-md-6">
            <?php
            $action_reopen_work_order = !empty($userMenuItems['action_reopen_work_order']) ? $userMenuItems['action_reopen_work_order'] : '';
            $is_item_status_disabled = '';
            if($aircraftwoitems->wo_item_status == '3' && empty($action_reopen_work_order)){
                $is_item_status_disabled = 'disabled';
            }

            echo $this->Form->control('wo_item_current_status', array('type'=>'hidden', 'class' => 'form-control', 'label' => false, 'id' => 'wo_item_current_status', 'value'=>$aircraftwoitems->wo_item_status)); 

            $itemstatus = unserialize(AIRCRAFT_WORKORDER_ITEMSTATUS);
            echo $this->Form->control('wo_item_status', array('options' => $itemstatus, 'empty' => '', 'class' => 'form-control col-md-6 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_item_status', 'disabled'=>$is_item_status_disabled, 'value'=>$aircraftwoitems->wo_item_status)); 
            ?>
        </div>
        <div class="col-md-3">
        <button type="button" class="btn btn-default aircraft-wo-item-signoff" data-val='aircraft_wo_sign_offs'>Sign-offs</button>
        </div>
    </div>
</div>
<?php echo $this->Form->end(); ?>
<div class="row">
    <div id="aircraftTabs" class="aircraftWOItemTabs">
        <div class="container">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#aircraftWOOverviewSection">Overview</a></li>
                <li><a data-toggle="tab" href="#aircraftWOServiceSection">Service</a></li>
                <li><a data-toggle="tab" href="#aircraftWOOSRSection">OSR</a></li>
                <li><a data-toggle="tab" href="#aircraftWOPartsSection">Parts</a></li>
                <li><a data-toggle="tab" href="#aircraftWOToolsSection">Tools</a></li>
                <li>
                    <a data-toggle="tab" href="#aircraftWOPhotosSection">Photos <span class="count_circle count_wo_item_photo"><?php echo count($aircraftwoitemphotoes); ?></span></a>
                </li>
                <li>
                    <a data-toggle="tab" href="#aircraftWOFilesSection">Files <span class="count_circle count_wo_item_file"><?php echo count($aircraftwoitemfiles); ?></span></a>
                </li>
                <li><a data-toggle="tab" href="#aircraftWOSummarySection">Summary</a></li>
                <li><a data-toggle="tab" href="#aircraftWOHistorySection">History</a></li>
            </ul>
            <div class="tab-content">
                <div id="aircraftWOOverviewSection" class="tab-pane fade in active">
                    <div class="page-content mt-35">
                        <div class="formBGCls">
                            <?php echo $this->element('Inventory/customer_otc/customer_aircraft_wo_overview'); ?>
                        </div>
                    </div>
                </div>
                <div id="aircraftWOServiceSection" class="tab-pane fade">
                    <div class="page-content mt-35">
                        <div class="formBGCls workorderservicesblock">
                        <?php echo $this->element('Inventory/customer_otc/customer_aircraft_wo_service'); ?>
                        </div>
                    </div>
                </div>
                <div id="aircraftWOOSRSection" class="tab-pane fade">
                    <div class="page-content mt-35">
                        <div class="formBGCls">
                            <?php echo $this->element('Inventory/customer_otc/customer_aircraft_wo_osr'); ?>
                        </div>
                    </div>
                </div>
                <div id="aircraftWOPartsSection" class="tab-pane fade">
                    <div class="page-content mt-35">
                        <div class="formBGCls">
                            <?php echo $this->element('Inventory/customer_otc/customer_aircraft_wo_parts'); ?>
                        </div>
                    </div>
                </div>
                <div id="aircraftWOToolsSection" class="tab-pane fade">
                    <div class="page-content mt-35">
                        <div class="formBGCls">
                            <?php echo $this->element('Inventory/customer_otc/customer_aircraft_wo_tools'); ?>
                        </div>
                    </div>
                </div>
                <div id="aircraftWOPhotosSection" class="tab-pane fade">
                    <div class="page-content mt-35">
                        <div class="formBGCls">
                            <?php echo $this->element('Inventory/customer_otc/customer_aircraft_wo_photo'); ?>
                        </div>
                    </div>
                </div>
                <div id="aircraftWOFilesSection" class="tab-pane fade">
                    <div class="page-content mt-35">
                        <div class="formBGCls">
                            <?php echo $this->element('Inventory/customer_otc/customer_aircraft_wo_files'); ?>
                        </div>
                    </div>
                </div>
                <div id="aircraftWOSummarySection" class="tab-pane fade">
                    <div class="page-content mt-35">
                        <div class="formBGCls">
                            <?php echo $this->element('Inventory/customer_otc/customer_aircraft_wo_summary'); ?>
                        </div>
                    </div>
                </div>
                <div id="aircraftWOHistorySection" class="tab-pane fade">
                    <div class="page-content mt-35">
                        <div class="formBGCls">
                            <?php echo $this->element('Inventory/customer_otc/aircraft_wo_item_history'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mt10">
        <div class="col-md-6">
            <span class="text-red">Sign-off Info:</span>
            <span>15 sign-offs are incomplete</span>
        </div>
        <div class="col-md-6 text-right">
            <span>Auth:</span>
            <span>Yes</span>
        </div>
    </div>
</div>

<script type="text/javascript">
    var wo_item_positions = <?php echo json_encode($wo_item_positions); ?>;

    var reorganizeWorkOrderItemURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'reorganizeWorkOrderItem']); ?>";
    var checkWOItemSignoffComplURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'checkWOItemSignoffCompl']); ?>";
    var goToCustomerURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'customerinfo']); ?>";
</script>