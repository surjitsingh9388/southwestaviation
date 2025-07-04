<div id="updateLogBookValOpenWOModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls">Update Log Book Values in Open W/Os?</h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmUpdateWOLogBookValue'));
                ?> 
                <div class="row">
                    <input type="hidden" name="aircraft_id" value="<?php echo $aircraftregdetail->id; ?>" />
                    <div class="col-md-12">
                        <p>This maintenance record in Customer > Aircraft is always the latest, most current times. However, if there are open W/Os, they may have a different snapshot of this maintenance info (stored as "Log Book Values").</p>
                    </div>
                    
                    <div class="col-md-12">
                        <p>Below is the list of open W/Os for this aircraft. Please select the work orders you wish to update to have this current aircraft maintenance information.</p>
                    </div>
                    <div class="col-md-12">
                        <p style="color:red;">Newerwork orders that do not yet have log book values data will not appear on this list.</p>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="th-sm">Work Order</th>
                                    <th class="th-sm">Status</th>
                                    <?php if($aircraftregdetail->aircraft_engine_type != '5'){ ?>
                                    <th class="th-sm">A/C TT</th>
                                    <th class="th-sm">A/C Tach</th>
                                    <th class="th-sm">A/C Hoobs</th>
                                    <?php }else{ ?>
                                    <th class="th-sm">TT</th>
                                    <th class="th-sm">Hoobs</th>
                                    <?php } ?>
                                    <th class="th-sm">Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($workorderlist as $row){
                                ?>
                                <tr>
                                    <td><?php echo $row['work_order_no']; ?></td>
                                    <td><?php echo $row['wo_status']; ?></td>
                                    <?php if($aircraftregdetail->aircraft_engine_type != '5'){ ?>
                                    <td><?php echo $row['overviews']['current_ac_tt']; ?></td>
                                    <td><?php echo $row['overviews']['current_ac_tach']; ?></td>
                                    <td><?php echo $row['overviews']['hobbs']; ?></td>
                                    <?php }else{ ?>
                                    <td><?php echo $row['overviews']['actt']; ?></td>
                                    <td><?php echo $row['overviews']['hobbs']; ?></td>
                                    <?php } ?>
                                    <td>
                                        <input type="checkbox" name="workorderids[]" checked value="<?php echo $row['id']; ?>" />
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <a href="javascript:void(0);" style="color:blue;" class="selectall_maintenance_woitem">Select All</a>
                            <a href="javascript:void(0);" style="color:blue; padding:10px;" class="deselectall_maintenance_woitem">Clear Selection</a>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default float-right" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-default float-right update_wo_logbook_value">Update</button>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>