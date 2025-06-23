<?php $sessionUser = $this->request->getSession()->read('Auth');; ?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Settings</h2>
        </div>
        
        <div class="page-content mt-35">
            <div class="table-responsive">
                <div class="col-md-12 pd0">
                    <div class="col-md-6 pd0" style="padding-right:5px !important;">
                        <div class="dashboard_heading_bar">
                            <span class="dashboard_heading">Statement</span>
                        </div>
                        <div class="btnWrapper" style="display:flow-root !important;">
                            <div class="float-right">
                                <button type="button" class="btn btn-default statement_create_btn" data-val="">Add New Statement</button>
                            </div>
                        </div>
                        <table class="table mb-0 statement_tbl">
                            <thead>
                                <tr>
                                    <th>Statement Name</th>
                                    <th>Statement Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $statementstatus = ['0'=>'Inactive', '1'=>'Active'];
                                foreach($statementdata as $statements){
                                ?>
                                    <tr>
                                        <td><?php echo $statements['statement_name']; ?></td>
                                        <td><?php echo $statements['statement_description']; ?></td>
                                        <td><?php echo $statementstatus[$statements['status']]; ?></td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-info btn-xs statement_create_btn" data-val="<?php echo $statements['id']; ?>"><i class="fa fa-pencil"></i> Edit</a>
                                            <a href="javascript:void(0);" class="btn btn-danger btn-xs statement_delete_btn" data-val="<?php echo $statements['id']; ?>"><i class="fa fa-trash-o"></i> Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 pd0">
                        <div class="dashboard_heading_bar">
                            <span class="dashboard_heading">Settings</span>
                        </div>
                        <div class="btnWrapper" style="display:flow-root !important;">
                            <div class="float-right">
                                <button type="button" class="btn btn-default upload-new-logo-popup">Add New Setting</button>
                            </div>
                        </div>
                        <table class="table mb-0 settings_tbl">
                            <thead>
                                <tr>
                                    <th id="logoth">Logo </th>
                                    <th id="addressth">Office Address</th>
                                    <th id="statusth">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $settingsstatus = ['0'=>'Inactive', '1'=>'Active'];
                                foreach($settingsdata as $setting){
                                    $filelocation = DS.'settings_logo'.DS.$setting['logo'];
                                ?>
                                    <tr>
                                        <td><?php echo $this->Html->link($setting['logo'], $filelocation, ['target'=>'_blank']); ?></td>
                                        <td><?php echo $setting['office_address']; ?></td>
                                        <td><?php echo $settingsstatus[$setting['status']]; ?></td>
                                        <td><i class="fa fa-times deletesettings" data-val="<?php echo $setting['id']; ?>" title="Remove"></i></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="settingspopup"></div>
<div id="statementInfoModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 20%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Statement Dynamic Text</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><b>1.</b> Use [#aircraftregistrationno] for Aircraft Registration Number.</p>
                        <p><b>2.</b> Use [#workorderno] for Work Order Number.</p>
                        <p><b>3.</b> Use [#date] for Date.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->Html->script('tinymce/tinymce.min');
echo $this->Html->script('settings');
echo $this->Html->css('settings');
?>
<script>
    var fetchSettingPopupURL = "<?php echo $this->Url->build(['controller'=>'Settings', 'action'=>'fetchSettingPopup']); ?>";
    var saveSettingsURL = "<?php echo $this->Url->build(['controller'=>'Settings', 'action'=>'saveSettings']); ?>";
    var deleteSettingsURL = "<?php echo $this->Url->build(['controller'=>'Settings', 'action'=>'deleteSettings']); ?>";
    var uploadSettingLogoURL = "<?php echo $this->Url->build(['controller'=>'Settings', 'action'=>'uploadSettingLogo']); ?>";
    var fetchStatementPopupURL = "<?php echo $this->Url->build(['controller'=>'Settings', 'action'=>'fetchStatementPopup']); ?>";
    var saveStatementsURL = "<?php echo $this->Url->build(['controller'=>'Settings', 'action'=>'saveStatements']); ?>";
    var deleteStatementsURL = "<?php echo $this->Url->build(['controller'=>'Settings', 'action'=>'deleteStatements']); ?>";
</script>