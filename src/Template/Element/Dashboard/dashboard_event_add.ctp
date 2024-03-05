<div id="dashboardEventAddModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;"> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php if(!empty(@$dashboardevent->id)){ echo 'Edit'; }else{echo 'Add';} ?> Event</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <?php
                    echo $this->Form->create($dashboardevent, array('class' => 'form-horizontal form-label-left', 'id' => 'frmDashboardEventAdd'));
                    ?>
                    <input type="hidden" name="dashboard_event_id" value="<?php echo @$dashboardevent->id; ?>" />
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Event Name</label>
                                <div class="form-input-frame">
                                    <?php
                                    echo $this->Form->control('event_name', array('class' => 'form-control', 'placeholder' => '', 'label' => false));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt10">
                            <div class="form-group">
                                <label class="control-label" for="reference">Event Description</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->input('event_description', array('type' => 'textarea', 'class'=>'form-control col-md-12', 'placeholder' => '', 'label' => false, 'required' => 'required')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Event Start Date</label>
                                <div class="form-input-frame">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('event_start_date', array('class' => 'form-control', 'id' => 'event_start_date', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Event Start Time</label>
                                <div class="form-input-frame">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('event_start_time', array('class' => 'form-control', 'id' => 'event_start_time', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Event End Date</label>
                                <div class="form-input-frame">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('event_end_date', array('class' => 'form-control', 'id' => 'event_end_date', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Event End Time</label>
                                <div class="form-input-frame">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('event_end_time', array('class' => 'form-control', 'id' => 'event_end_time', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <?php 
                $isdisabled = '';
                if((empty($dashboardMenuItems->action_edit) && !empty(@$dashboardevent->id)) && $user_role != '1'){ 
                    $isdisabled = 'disabled';
                }
                ?>
                <button type="button" class="btn btn-primary saveDashboardEvent" <?php echo $isdisabled; ?>>Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
    var sendWOViewMessageURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'sendWOViewMessage']); ?>";
</script>