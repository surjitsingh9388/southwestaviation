<div id="woPPLogbookLabelsPrintOptModal" class="modal fade page-content" role="dialog" style="background: transparent;">
    <div class="modal-dialog" style="width: 35%;">
        <?php
        echo $this->Form->create(null, array('class' => 'form-horizontal form-label-left', 'id' => 'frmWOPrintPreview'));
        ?> 
        <div class="modal-content">
            <div class="modal-header" style="background-color: #e5e5e5;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <?php
                        $aircraftWOLogBookCategory = unserialize(AIRCRAFT_WORKORDER_LOGBOOK_CATEGORY);
                        echo 'Log Book Printing Option ('.$aircraftWOLogBookCategory[$log_book_category].')';
                    ?>
                </h4>
                <input type="hidden" name="work_order_id" value="<?php echo $work_order_id; ?>" />
                <input type="hidden" name="wo_item_id" value="<?php echo $wo_item_id; ?>" />
                <input type="hidden" name="wo_report_type" value="<?php echo $wo_report_type; ?>" />
                <input type="hidden" name="log_book_category" id="log_book_category" value="<?php echo $log_book_category; ?>" />
                <input type="hidden" id="log_book_category_name" value="<?php echo $aircraftWOLogBookCategory[$log_book_category]; ?>" />
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Select the Printer</label>
                            <div class="form-input-frame">
                                <?php
                                $printerArr = ['1'=>'OneNote for Window 10', '2'=>'Microsoft XPS Document Writer', '3'=>'Microsoft Print to PDF', '4'=>'Fax'];
                                echo $this->Form->control('label_printer', array('options' => $printerArr, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'label_printer', 'value'=>'3'));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Pick a Label</label>
                            <div class="form-input-frame">
                                <?php
                                $labelsArr = ['1'=>'6 × 4 Inches', '2'=>'6 × 4 Inches (Landscape)', '3'=>'6 × 4 Inches (For Full Page Printer)', '4'=>'5 × 3.5 Inches', '5'=>'5 × 3.5 Inches (Landscape)', '6'=>'Avery 5168 Labels (Top Label)', '7'=>'Avery 5168 Labels (Bottom Label)', '8'=>'Full Page', '9'=>'Generic Shipping Label #1', '10'=>'Generic Shipping Label #2'];
                                echo $this->Form->control('pick_a_label', array('options' => $labelsArr, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'pick_a_label', 'value'=>'8'));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label label-heading-left" for="reference">Statement Name</label>
                            <span class="label-chkbox-right">
                                <input class="form-check-input" type="checkbox" value="1" id="otc" name="statement_show" />
                                <span class="form-check-label" for="statement_show">Show Statement</span>
                            </span>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('statement_name', array('options' => $statementdropdown, 'empty' => 'Select Statement', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'statement_name'));
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="reference">Show Aircraft Times from Profile</label>
                            <div class="form-input-frame">
                                <?php
                                echo $this->Form->control('aircraft_times_profile', array('options' => ['1'=>'(Use Default)'], 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'aircraft_times_profile', 'value'=>'1'));
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="col-md-6" style="padding:0px;">
                            <input class="form-check-input" type="checkbox" value="1" id="item_grouping" name="item_grouping" />
                            <span class="form-check-label" for="item_grouping">Sort by Item Grouping</span>
                        </div>    
                        <div class="col-md-6" style="padding:0px;">
                            <a href="javascript:void(0);" class="previewstatement" style="color:#337ab7; float:right;">Preview Statement</a>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <span>Last Date Used on Label:</span>
                        <span style="color:#337ab7;"><?php echo date('m/d/Y'); ?></span>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-6" style="padding:0px;">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border logbook_labels_legends">Date Options</legend>
                                
                                <div class="col-md-12">
                                    <input class="form-check-input date_option" type="radio" value="1" name="date_option" id="date_option1" checked />
                                    <span class="form-check-label" for="date_option">Use Today's Date</span>
                                </div>

                                <div class="col-md-12">
                                    <input class="form-check-input date_option" type="radio" value="2" id="date_option2" name="date_option" />
                                    <span class="form-check-label" for="date_option">Use Specific Date</span>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-input-frame">
                                            <div class="input-group date datePicker">
                                                <?php echo $this->Form->Text('use_today_date', array('class' => 'form-control', 'id' => 'airframe_due_date', 'placeholder' => '', 'label' => false)); ?>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-6" style="0px 0px 0px 5px;">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border logbook_labels_legends">Footer Options</legend>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="reference">Show</label>
                                        <div class="form-input-frame">
                                            <?php
                                            $footerShow = ['1'=>'No Info', '2'=>'Repair Station No.', '3'=>'A&P Number', '4'=>'IA Number', '5'=>'Custom ...'];
                                            echo $this->Form->control('labels_footer_show', array('options' => $footerShow, 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'labels_footer_show'));
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 technician_block" style="display:none;">
                                    <div class="form-group">
                                        <label class="control-label" for="reference">Technician</label>
                                        <div class="form-input-frame">
                                            <?php
                                            echo $this->Form->control('labels_technician', array('options' => $technicianlist, 'empty' => 'Select Technician', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'labels_technician'));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border logbook_labels_legends">Items To Display</legend>

                            <div class="col-md-12">
                                <input class="form-check-input" type="checkbox" value="1" id="labels_mainually_pick_items" name="labels_mainually_pick_items" checked />
                                <span class="form-check-label" for="labels_mainually_pick_items">Manually Pick Items</span>
                            </div>

                            <div class="col-md-12">
                                <input class="form-check-input" type="checkbox" value="1" id="labels_signed_off_items" name="labels_signed_off_items" disabled />
                                <span class="form-check-label" for="labels_signed_off_items">Show Signed Off Items By: (<span class="selected_technician">no tech selected</span>)</span>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">  
                                    <label class="control-label col-md-2" for="plane_id">Sign-off</label>
                                    <div class="col-md-10">
                                        <?php echo $this->Form->control('labels_signoffs', array('options' => ['1'=>'Final Inspection'], 'empty' => '', 'class' => 'form-control selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'id' => 'labels_signoffs', 'disabled'=>'disabled')); ?>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-12 custom_footer_block" style="display:none;">
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border logbook_labels_legends">Custom Footer</legend>
                            
                            <div class="form-group">  
                                <label class="control-label">This text will appear under the signature line</label>
                                <div class="form-input-frame">
                                    <?php 
                                    echo $this->Form->input('custom_footer', array('type'=>'textarea', 'class'=>'form-control', 'placeholder' => '', 'label' => false, 'required' => 'required', 'id'=>'custom_footer'));
                                    ?>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--button type="button" class="btn btn-default" style="float:left;">Setup Margins</butto-->
                <button type="button" class="btn btn-default contLogbooklblprintopt">Continue</button>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<script>
var validateLogbookCatFinalInspectionURL = "<?php echo $this->Url->build(['controller'=>'InventoryCustomers', 'action'=>'validateLogbookCatFinalInspection']); ?>";
</script>