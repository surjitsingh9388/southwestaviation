<div id="generateReportTypeModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 20%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Report Type</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Select Report Type</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('generate_report_type', [
                                    'type' => 'select',
                                    'options' => [
                                        'pdf' => 'PDF',
                                        'excel' => 'Excel',
                                    ],
                                    'class' => 'form-control',
                                    'label' => false,
                                    'id' => 'generate_report_type',
                                    'value' => 'pdf'
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="projErrorMsg"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="button" value="Generate Report" class="btn btn-primary" id="generateReportBtn">
            </div>
        </div>
    </div>
</div>