<div id="woItemCorrectiveActionHistoryModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Corrective Action</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p>Corrective Action History</p>
                    </div>
                    <div class="col-md-12 discrepancy_history_table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Corrective Action</th>
                                </tr>
                            </thead>
                            <tbody class="wo-osr-list">
                                <?php
                                foreach($correctiveactionhistorylist as $correctiveactionhistory){
                                ?>
                                <tr class="correctiveactionhistorylist">
                                    <td><?php echo date('Y-m-d H:i:s', strtotime($correctiveactionhistory['created_at'])); ?></td>
                                    <td><?php echo $correctiveactionhistory['users']['full_name']; ?></td>
                                    <td><?php echo $correctiveactionhistory['corrective_action']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-md-12 mt10 item_with_line">
                        <span>Individual Item</span>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Corrective Action</label>
                            <div class="form-input-frame">
                                <?php echo $this->Form->control('wo_item_corrective_action', array('type'=>'textarea', 'class' => 'form-control', 'label'=> false, 'row'=>'5', 'style'=>'height: 200px;', 'id'=>'wo_item_corrective_action_history', 'disabled'=>'disabled')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 pd0">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Date Modified</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('corrective_action_date_modified', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'corrective_action_date_modified', 'disabled'=>'disabled')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reference">Username</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->control('corrective_action_username', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'corrective_action_username', 'disabled'=>'disabled')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
            </div>
        </div>
    </div>
</div>