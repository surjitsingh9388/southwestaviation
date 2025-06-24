<div id="timeClockReportPopup" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="gpTypeCls"></span>Time Clock Reports</h4>
            </div>
            <div class="modal-body">
                <?php echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmTimeClockReports')); ?>
                <div  class="row">
                    <div class="col-sm-9">
                        <div class="col-xs-12">
                            <div class="form-group"> 
                                <label class="control-label" for="reference">Select the Printer</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $printlist = ['1'=>'OneNote for Window 10', '2'=>'Microsoft XPS Document Writer', '3'=>'Microsoft Print to PDF', '4'=>'Fax'];
                                    echo $this->Form->control('time_clock_log_printer', array('options' => $printlist, 'empty' => 'Select...', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'time_clock_log_printer', 'value'=>'3')); 
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group"> 
                                <label class="control-label" for="reference">Select a Report</label>
                                <div class="form-input-frame">
                                    <?php 
                                    $report = [
                                                '1'=>'Individual Employee - Time Clock Detail', 
                                                '2'=>'Individual Employee - Technician Comparison', 
                                                '3'=>'All Employees - Date Range Summary', 
                                                '4'=>'All Employees - Time Clock Detail', 
                                                '5'=>'All Employees - Time Clock Summary', 
                                                '6'=>'All Technicians - Productivity'
                                            ];
                                    echo $this->Form->control('time_clock_log_report', array('options' => $report, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'time_clock_log_report')); 
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="form-group"> 
                                <label class="control-label" for="reference">Employee Name</label>
                                <div class="form-input-frame">
                                    <?php 
                                    echo $this->Form->control('user_id', array('options' => $userlist, 'empty' => 'Select...', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'time_clock_log_user_id')); 
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group"> 
                                <label class="control-label" for="reference">Date From</label>
                                <div class="form-input-frame">
                                    <div class="input-group date datePicker">
                                        <?php 
                                        $current_date = date('m/d/Y');
                                        echo $this->Form->Text('date_from', array('class' => 'form-control', 'id' => 'po_date', 'placeholder' => '', 'label' => false, 'id'=>'time_clock_log_date_from', 'value'=>$current_date)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2">&nbsp;</div>
                            </div>
                        </div>

                        <div class="col-xs-6">
                            <div class="form-group"> 
                                <label class="control-label" for="reference">Date To</label>
                                <div class="form-input-frame">
                                    <div class="input-group date datePicker">
                                        <?php 
                                        echo $this->Form->Text('date_to', array('class' => 'form-control', 'id' => 'po_date', 'placeholder' => '', 'label' => false, 'id'=>'time_clock_log_date_to', 'value'=>$current_date)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3  mt-25" >
                        <div class="col-sm-12 col-xs-3">
                            <button type="button" class="btn btn-default col-sm-12 previewTimeClockReports">Preview</button>
                        </div>
                        <div class="col-sm-12 col-xs-3">
                            <button type="button" class="btn btn-default col-sm-12" disabled>Print</button>
                        </div>
                        <div class="col-sm-12 col-xs-3">
                            <button type="button" class="btn btn-default col-sm-12" disabled>Print All</button>
                        </div>
                        <div class="col-sm-12 col-xs-3">
                            <button type="button" class="btn btn-default col-sm-12" disabled>Export</button>
                        </div>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
