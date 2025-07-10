<div id="aircarftCreateWOModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 60%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close closeWorkOrderDetBtn" data-dismiss="modal" data-val="wo_save_close">&times;</button>
                <h4 class="modal-title">Estimate and Service - <?php echo ($aircraftworkorders->order_type == '1') ? 'W/O: '.$aircraftworkorders->work_order_no : 'R/O: '.$aircraftworkorders->work_order_no; ?></h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <div class="row">
                        <div class="col-md-12 mt10">
                            
                            <div class="btn-group">
                                <button type="button" class="btn btn-default work-order-prev-btn" data-val="first" <?php if(!empty($wo_item_positions[0]) &&  $wo_item_positions[0] == $current_item_position){ echo 'disabled'; } ?> ><<</button>
                                <button type="button" class="btn btn-default work-order-prev-btn" data-val="prev" <?php if(!empty($wo_item_positions[0]) &&  $wo_item_positions[0] == $current_item_position){ echo 'disabled'; } ?> ><</button>
                                <button type="button" class="btn btn-default work-order-next-btn" data-val="next" <?php if(!empty($wo_item_positions) && $wo_item_positions[count($wo_item_positions)-1] == $current_item_position){ echo 'disabled'; } ?> >></button>
                                <button type="button" class="btn btn-default work-order-next-btn" data-val="last" <?php if(!empty($wo_item_positions) && $wo_item_positions[count($wo_item_positions)-1] == $current_item_position){ echo 'disabled'; } ?> >>></button>
                                <button type="button" class="btn btn-default work-order-new-item-btn" data-val="new-item">New Item</button>
                                <button type="button" class="btn btn-default work-order-item-notes-btn">Notes</button>
                                <button type="button" class="btn btn-default work-order-item-delete">Delete Item</button>
                                <button type="button" class="btn btn-default go_to_customer_section">Go To Cust.</button>
                                <button type="button" class="btn btn-default work-order-item-list">List</button>
                                <!--button type="button" class="btn btn-default">Options</button-->
                                <div class="split-btn actionMenu sortWrap label-width-auto" style="float:left;">
                                    <button type="button" class="btn-dropdown btn btn-default work-order-item-options">Options<span class="selectCount"></span></button>
                                    <button type="button" class="icon-part dropdown-toggle " data-toggle="dropdown">
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                                        
                                    <div class="dropdown-content dropdown-menu actionLinks">
                                        <a href="javascript:void(0);" class="work-order-item-options">View Options</a>
                                        <a href="javascript:void(0);" class="">Add Continued Items</a>
                                        <a href="javascript:void(0);" class="fetchCustOTCPopup" data-val='aircraft_option_email_work_order'>Email <?php echo $aircraftworkorders->order_type == '1' ? 'Work Order' : 'Repair Order'; ?></a>
                                        <a href="javascript:void(0);" class="">Import ADs</a>
                                        <a href="javascript:void(0);" class="wo-item-option-atacode">Create ATA Code from Item</a>
                                        <a href="javascript:void(0);" class="wo-item-option-labor-kit">Create Labor Kit from Items</a>
                                        <a href="javascript:void(0);" class="wo-go-to-maintenance-info">Go to Maintenance Info</a>
                                        <a href="javascript:void(0);" class="wo-option-log-book-helper">Log Book Helper</a>
                                        <?php if($aircraftworkorders->order_type == '1'){ ?>
                                        <a href="javascript:void(0);" class="wo-option-log-book-values">Log Book Values</a>
                                        <?php } ?>
                                        <a href="javascript:void(0);" class="">Manually Create Core Records</a>
                                        <a href="javascript:void(0);" class="">Set Parts to Max Prices</a>
                                        <?php if($aircraftworkorders->order_type == '2'){ ?>
                                        <a href="javascript:void(0);" class="">Repair Order Info</a>
                                        <a href="javascript:void(0);" class="">Create Sub-R/O</a>
                                        <?php } ?>
                                        <?php if($login_user_id != '1'){ ?>
                                        <a href="javascript:void(0);" class="wo-list-all-message" data-val="login-user" click-source= "work_order">List Message to <?php echo $sessionUser['full_name']; ?></a>
                                        <?php }else{ ?>
                                        <a href="javascript:void(0);" class="wo-list-all-message" data-val="all-user">List Message for All Users</a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-default work-order-mark-items-btn">Mark Items</button>
                                <button type="button" class="btn btn-default wo-preview-btn">Preview</button>
                                <button type="button" class="btn btn-default wo-print-btn">Print</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <?php
                        echo $this->Form->create($aircraftworkorders, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAircraftWorkOrder'));
                        ?>
                        <input type="hidden" name="work_order_id" id="work_order_id" value="<?php echo @$aircraftworkorders->id; ?>" />
                        <input type="hidden" name="aircraft_id" id="wo_aircraft_id" value="<?php echo @$aircraft_id; ?>" />
                        <input type="hidden" name="wo_customer_id" id="wo_customer_id" value="<?php echo @$inventorycustomers->id; ?>" />

                        <div class="col-md-2 col-sm-2 col-sm-2">
                            <div class="form-group">
                                <label class="control-label" for="reference"><?php echo ($aircraftworkorders->order_type == '1') ? 'W' : 'R'; ?>/O No.</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('work_order_no', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>$work_order_number, 'readonly'=>'readonly')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4 col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference">Customer Info</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('wo_customer_info', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'value'=>$wo_customer_name, 'readonly'=>'readonly')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-2 col-sm-2">
                            <div class="form-group">
                                <label class="control-label" for="reference">Phone #</label>
                                <div class="form-input-frame">
                                    <?php
                                    echo $this->Form->control('wo_phone_no', array('options' => $clientphone, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'wo_phone_no'));
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-2 col-sm-2">
                            <div class="form-group">
                                <label class="control-label" for="reference">W/O Status</label>
                                <div class="form-input-frame">
                                    <?php
                                    $aircraftWOStatus = unserialize(AIRCRAFT_WORKORDER_STATUS);
                                    echo $this->Form->control('wo_status', array('options' => $aircraftWOStatus, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'aircraft_work_order_status'));
                                    ?>
                                </div>
                                <input type="hidden" id="old_work_order_status" value="<?php echo $aircraftworkorders->wo_status; ?>" />
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-2 col-sm-2">
                            <div class="form-group">
                                <label class="control-label" for="reference">Go To Item</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('wo_go_to', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'go_to_wo_item')); ?>
                                </div>
                            </div>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>

                    <div id="createwoitemsections">
                        <?php
                            echo $this->element('Inventory/customer_otc/aircraft_create_wo_item_section');
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--button type="button" class="btn btn-default" data-dismiss="modal">Close</button-->
                <?php
                $isdisabled = $aircraftwoitems->wo_item_status == '3' ? 'disabled' : '';
                ?>
                <button type="button" class="btn btn-primary saveAircraftWODetBTN" data-val="wo_save_btn" <?php echo $isdisabled; ?>>Save</button>
            </div>
        </div>
        <?php 
        //echo $this->Form->end(); 
        ?>
    </div>
</div>