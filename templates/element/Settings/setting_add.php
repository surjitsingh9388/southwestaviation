<div id="settingsAddModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php if (!empty(@$settings->id)) {
                        echo 'Edit';
                    } else {
                        echo 'Add';
                    } ?> Setting</h4>
            </div>
            <div class="modal-body">
                <div class="page-content">
                    <?php
                    echo $this->Form->create($settings, array('class' => 'form-horizontal form-label-left', 'id' => 'frmSettingsAdd'));
                    ?>
                    <input type="hidden" name="setting_id" value="<?php echo @$settings->id; ?>" />

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-1" for="reference">Logo</label>
                                <div class="col-md-11">
                                    <div class="form-input-frame">
                                        <input type="file" name="files[]" id="upload_site_logo" style="display:none" accept="image/png, image/gif, image/jpeg">
                                        <button class="btn btn-primary" type="button" onclick="$('#upload_site_logo').trigger('click'); return false;">Select Logo</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered dataTable" id="uploadfile" width="100%">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>File Name</th>
                                            <th>Size</th>
                                            <th>Uploaded</th>
                                            <th>Uploaded By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filetbody">
                                        <!-- Dynamic file rows go here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label" for="reference">Office Address</label>
                                <div class="form-input-frame">
                                    <?php echo $this->Form->input('office_address', array('type' => 'textarea', 'class' => 'form-control col-md-12', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id' => 'office_address')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group"> 
                                <label class="control-label" for="reference">Status</label>
                                <div class="form-input-frame">
                                    <?php
                                    $settingsstatus = ['0' => 'Inactive', '1' => 'Active'];
                                    echo $this->Form->control('status', array('options' => $settingsstatus, 'empty' => 'Select status...', 'class' => 'form-control col-md-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'status'));
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!--div class="form-group row align-items-center">
                            <label class="control-label col-md-7 mb-0" for="status">Status</label>
                            <div class="col-md-5">
                                <div class="form-input-frame">
                                    <?php
                                    $settingsstatus = ['0' => 'Inactive', '1' => 'Active'];
                                    echo $this->Form->control('status', [
                                        'options' => $settingsstatus,
                                        'empty' => 'Select status...',
                                        'class' => 'form-control selectpicker',
                                        'data-show-subtext' => true,
                                        'data-live-search' => true,
                                        'label' => false,
                                        'id' => 'status'
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div-->

                    </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <?php
                $isdisabled = '';
                if ((empty($dashboardMenuItems->action_edit) && !empty(@$settings->id)) && $user_role != '1') {
                    $isdisabled = 'disabled';
                }
                ?>
                <button type="button" class="btn btn-primary saveSettings" <?php echo $isdisabled; ?>>Save</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>