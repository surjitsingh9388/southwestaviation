<div id="aircarftMaintEngCylDateModel" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 20%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Date: <?php echo $aircraftregdetail->aircraft_registration_number; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Date</label>
                            <div class="form-input-frame">
                                <div class="input-group date datePicker">
                                    <?php echo $this->Form->Text('engine_cyl_date', array('class' => 'form-control', 'id' => 'maint_eng_cyl_date', 'placeholder' => '', 'label' => false)); ?>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 float-right">
                        <button type="button" class="btn btn-primary aircraftMaintEngCylSave">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>