<?php
echo $this->Form->create($inventorytools, array('class' => 'form-horizontal form-label-left', 'id' => 'frmInventoryToolsDet', 'autocomplete' => 'off'));
?>
<input type="hidden" name="tool_id" id="tool_id" value="<?php echo @$inventorytools->id; ?>" />

<div class="col-md-6">
    <div class="form-group">
        <label class="control-label" for="reference">Tool Name</label>
        <div class="form-input-frame">
            <?php echo $this->Form->control('tool_name', array('class' => 'form-control', 'label' => false, 'autocomplete'=>'off', 'placeholder'=>'', 'id'=>'tool_info_name', 'readonly'=>'readonly')); ?>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="control-label" for="reference">Go To</label>
        <div class="form-input-frame">
            <?php
            echo $this->Form->control('billing_rate_method', array('options' => [], 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id'=>'option_billing_rate_method'));
            ?>
        </div>
    </div>
</div>

<div id="aircraftTabs" class="col-md-12 customerinfoTab">
    <div class="container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#toolInfoSection">Tool Info</a></li>
            <li><a data-toggle="tab" href="#toolCustomFieldsSection">Custom Fields</a></li>
            <li><a data-toggle="tab" href="#toolCertificationHistSection">Certification History</a></li>
            <li><a data-toggle="tab" href="#toolWOHistorySection">W/O History</a></li>
            <li><a data-toggle="tab" href="#toolPhotoSection">Photos</a></li>
            <li><a data-toggle="tab" href="#toolFileSection">Files</a></li>
            <li><a data-toggle="tab" href="#toolNoteSection">Notes</a></li>
        </ul>
        <div class="tab-content">
            <div id="toolInfoSection" class="tab-pane fade in active">
                <div class="page-content mt-35">
                    <div class="formBGCls">
                        <?php echo $this->element('Inventory/tool_info'); ?>
                    </div>
                </div>
            </div>
            <div id="toolCustomFieldsSection" class="tab-pane fade">
                <div class="page-content mt-35">
                    <div class="formBGCls">
                        
                    </div>
                </div>
            </div>
            <div id="toolCertificationHistSection" class="tab-pane fade">
                <div class="page-content mt-35">
                    <div class="formBGCls">
                        <?php echo $this->element('Inventory/tool_certification_history'); ?>
                    </div>
                </div>
            </div>
            <div id="toolWOHistorySection" class="tab-pane fade">
                <div class="page-content mt-35">
                    <div class="formBGCls">
                        <?php echo $this->element('Inventory/tool_work_order_history'); ?>
                    </div>
                </div>
            </div>
            <div id="toolPhotoSection" class="tab-pane fade">
                <div class="page-content mt-35">
                    <div class="formBGCls">
                        <?php echo $this->element('Inventory/tool_photos'); ?>
                    </div>
                </div>
            </div>
            <div id="toolFileSection" class="tab-pane fade">
                <div class="page-content mt-35">
                    <div class="formBGCls">
                        <?php echo $this->element('Inventory/tool_files'); ?>
                    </div>
                </div>
            </div>
            <div id="toolNoteSection" class="tab-pane fade">
                <div class="page-content mt-35">
                    <div class="formBGCls">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php echo $this->Form->control('tool_notes', array('type'=>'textarea','class' => 'form-control col-md-10 col-xs-12', 'label'=> false, 'row mb-3s'=>2, 'id'=>'tool_notes', 'required'=>'required')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>

<script type="text/javascript">
    var openCertificationHistoryPopupURL = "<?php echo $this->Url->build(['controller'=>'InventoryTools', 'action'=>'openCertificationHistoryPopup',]); ?>";
    var saveToolCertifiedHistoryURL = "<?php echo $this->Url->build(['controller'=>'InventoryTools', 'action'=>'saveToolCertifiedHistory',]); ?>";
    var deleteToolCertifiedHistoryURL = "<?php echo $this->Url->build(['controller'=>'InventoryTools', 'action'=>'deleteToolCertifiedHistory',]); ?>";
    var deleteToolDetailURL = "<?php echo $this->Url->build(['controller'=>'InventoryTools', 'action'=>'deleteToolDetail',]); ?>";
</script>