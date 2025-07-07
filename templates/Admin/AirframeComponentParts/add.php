<?php
$sessionUser = $this->request->getSession()->read('Auth');;
$sessionArray = $this->request->getSession()->read('Auth');

use Cake\Routing\Router;
?>
<div class="content sliding">
    <div class="outerWrapper">
        <?php
        echo $this->Form->create($airCompParts, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAirCompPart'));
        ?>
        <div class="btnWrapper">
            <h2 class="heading">Add Component Part</h2>
            <div class="btnWrap">
                <?php
                echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                if ((!empty($actionItems) && $actionItems['action']['action_add'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Submit', ['type' => 'submit', 'class' => 'btn btn-default']);
                }
                ?>
            </div>
        </div>

        <div class="page-content mt-35">
            <input type="hidden" name="compHours" id="compHours">
            <input type="hidden" name="compCycles" id="compCycles">
            <div class="formBGCls formInfoComponent">
                <div class="addPartBorder">
                    <div class="addPageHeading">Basic Information</div>

                    <div class="row">
                        <!-- <div class="col-md-6">
                            <div class="col-md-2" style="width:115px;">

                            </div>
                            <div class="col-md-5" style="padding-left: 34px;padding-right: 10px;width: 220px;">
                                <button type="button" class="btn btn-default selectaircraftcomponent">Select Aircraft & Component</button>
                                <div class="aircraft_component_list" style="display:none;"></div>
                            </div>
                            <div class="col-md-5">
                                <div class="table-responsive aircraft_component_selected" style="display:none;">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th id="toolName">Aircraft</th>
                                                <th id="toolName">Component</th>
                                            </tr>
                                        </thead>
                                        <tbody id="aircraftSelCompList">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> -->
                        <div class="row">

                            <div class="col-md-5" style="padding-left: 37px; padding-bottom: 12px;">
                                <div class="form-inline">
                                    <label style="margin-right: 23px;">Aircraft & Component</label>
                                    <button type="button" class="btn btn-default selectaircraftcomponent">
                                        Select
                                    </button>
                                </div>
                                <div class="aircraft_component_list" style="display: none; margin-top: 10px;">

                                </div>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-5 table-aircraft">
                                <div class="table-responsive aircraft_component_selected"  style="display: none;">
                                    <table class="table table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Aircraft</th>
                                                <th>Component</th>
                                            </tr>
                                        </thead>
                                        <tbody id="aircraftSelCompList">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--div class="col-md-3">
                            <div class="form-group"> 
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="plane_id">Aircraft&nbsp;<span class="required">*</span></label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                <?php echo $this->Form->control('plane_id', array('options' => $planes, 'empty' => 'Select Aircraft', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false, 'id' => 'planeName')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="airframe_component_id">Component&nbsp;<span class="required">*</span></label>
                                <div class="col-md-7 col-sm-7 col-xs-12" id="airCompsList">
                                <?php echo $this->Form->control('airframe_component_id', array('empty' => 'Select Aircraft Component', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false, 'id' => 'airframe_component_id')); ?>
                                </div>
                            </div>
                        </div-->

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="item_type">Item Type</label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php
                                    $itemTypes = $airCPComp->getItemTypes();
                                    echo $this->Form->control('item_type', array('options' => $itemTypes, 'empty' => 'Enter a type', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="disposition">Disposition
                                </label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->control('disposition', array('options' => $dispArr, 'empty' => 'Enter disposition', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="ata_code">ATA</label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->control('ata_code', array('options' => $ataCode, 'empty' => 'Select ATA', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="mfg_code">Mfg Code</label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->control('mfg_code', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'Mfg Code', 'label' => false)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="ad_sb_number">AD/SB Number</label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->control('ad_sb_number', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="ad_sb_status">AD/SB Class</label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php
                                    echo $this->Form->control('ad_sb_status', array('options' => $adsbArr, 'empty' => 'Enter class', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="reference">Reference
                                </label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->control('reference', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="requirement_type">Requirement Type</label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php
                                    $reqTypes = $airCPComp->getRequirementTypes();
                                    echo $this->Form->control('requirement_type', array('options' => $reqTypes, 'empty' => 'Enter requirement type', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="amendment">Amendment</label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->control('amendment', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false)); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="authority">Authority</label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php
                                    $authority = $airCPComp->getAuthority();
                                    echo $this->Form->control('authority', array('options' => $authority, 'empty' => 'Enter authority', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="description">Item Name</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('description', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'rows' => 2, 'style' => 'margin-left:25px;width:97%;')); ?>
                                </div>
                                <div class="col-md-3" style="padding:0px;">
                                    <input type="file" name="files[]" id="component_part_attachment" style="display:none !important;" multiple>
                                    <button class="btn btn-primary pull-right" type="button" onclick="$('#component_part_attachment').trigger('click'); return false;" id="component_part_upload">Upload Attachment</button>
                                </div>
                            </div>
                        </div>
                    </div-->

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="control-label col-md-1 col-sm-1 col-xs-12" for="description">Item Name</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo $this->Form->control('description', array('class' => 'form-control col-md-11 col-xs-12', 'label' => false, 'rows' => 2, 'style' => 'margin: 0 0 0 25px; width: 97%;')); ?>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label col-md-5 col-sm-4 col-xs-12" for="position">Position</label>
                                    <div class="col-md-7 col-sm-8 col-xs-12">
                                        <?php
                                        echo $this->Form->control('position_id', array('options' => $positionArr, 'empty' => 'Enter Position', 'class' => 'form-control  selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="file" name="files[]" id="component_part_attachment" style="display:none !important;" multiple>
                                    <button class="btn btn-primary pull-right btn-upload" type="button" onclick="$('#component_part_attachment').trigger('click'); return false;" id="component_part_upload">Upload Attachment</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="notes">Notes</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('notes', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'rows' => 2, 'style' => 'margin-left:25px;width:97%;')); ?>
                                </div>
                                <div class="col-md-3">
                                    <table class="table-responsive aircraft_component_file_selected" style="display:none; border:1px;">
                                        <thead>
                                            <tr>
                                                <td>File Name</td>
                                                <td>Action</td>
                                            </tr>
                                        </thead>
                                        <tbody id="component_part_file_list"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label class="control-label col-md-1 col-sm-1 col-xs-12">Work Description</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <?php echo $this->Form->control('work_description', array('class' => 'form-control col-md-7 col-xs-12 mxl-0', 'label' => false, 'rows' => 2, 'style' => 'margin: 0 0 0 25px; width: 97%;')); ?>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label col-md-5 col-sm-4 col-xs-12" for="avg_man_hrs">Man Hours</label>
                                    <div class="col-md-7 col-sm-8 col-xs-12">
                                        <?php echo $this->Form->control('avg_man_hrs', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false)); ?>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-1 col-sm-1 col-xs-12 " for="tags">Tags</label>
                                <div class="col-md-8 col-sm-8 col-xs-12 tags-bootstrap-tagsinput" style='margin-left: 25px;'>
                                    <input type="text" name="tags" value="" data-role="tagsinput" class="form-control" placeholder="Enter a tag"></input>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="addPartBorder">
                    <div class="corrPageHeading">
                        <div class="corrPH text-nowrap">Last Complied With</div>
                        <div class="corrPH text-nowrap">Next Due</div>
                        <div class="corrPH text-nowrap">Remaining</div>
                        <div class="corrPH text-nowrap">Tolerance</div>
                        <div class="corrPH text-nowrap">Alert</div>
                    </div>
                    <div class="itemInfoCls ">
                        <div class="itemCls">
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <a href="javascript:void(0)" style="cursor: pointer; color: #3c8dbc;" id="currentTimeId">Use Current Times</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_cw_date">Date
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <div class="input-group date datePicker">
                                        <?php echo $this->Form->Text('last_cw_date', array('class' => 'form-control col-md-7 col-xs-12', 'id' => 'cwDatepicker', 'placeholder' => '', 'label' => false)); ?>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_cw_hrs">Hours
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('last_cw_hrs', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_cw_afl">Cycles
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('last_cw_afl', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="itemCls">
                            <div class="form-group">
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->checkbox('override', array('label' => '', 'id' => 'overrideNextDue', 'class' => 'overrideEom')); ?> <span style="font-size: 10px;">Override Next Due</span>
                                </div>

                                <div class="col-md-5 col-sm-5 col-xs-12">
                                    <?php echo $this->Form->checkbox('eom', array('label' => '', 'id' => 'eomNextDue', 'class' => 'overrideEom')); ?> <span style="font-size: 10px;">EoM Adj</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="next_due_date">Date
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('next_due_date', array('class' => 'form-control col-md-7 col-xs-12 remDateUpdate', 'placeholder' => '', 'label' => false, 'div' => false, 'autocomplete' => 'off', 'readonly' => 'readonly')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="next_due_hrs">Hours
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('next_due_hrs', array('class' => 'form-control col-md-7 col-xs-12 remUpdate', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off', 'readonly' => 'readonly')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="next_due_afl">Cycles
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('next_due_afl', array('class' => 'form-control col-md-7 col-xs-12 remUpdate', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off', 'readonly' => 'readonly')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="itemCls">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Months
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <span class="form-control" id="remMos">
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Days
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <span class="form-control" id="remDays">
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Hours
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <span class="form-control" id="remHrs">
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Cycles
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <span class="form-control" id="remAfl">
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="itemCls">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tolerance_mos">Months
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('tolerance_mos', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tolerance_days">Days
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('tolerance_days', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tolerance_hrs">Hours
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('tolerance_hrs', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tolerance_afl">Cycles
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('tolerance_afl', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="itemCls">
                            <div class="form-group" style="padding-top: 33px;">
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alert_days">Days
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('alert_days', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete' => 'off', 'value' => 30)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alert_hrs">Hours
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('alert_hrs', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete' => 'off', 'value' => 50)); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alert_afl">Cycles
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('alert_afl', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete' => 'off', 'value' => 25)); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="addPageHeading">
                        <div class="col-md-3 col-sm-3 col-xs-3" style="top: -5px;">
                            Recurring <input type="radio" name="is_recThres" value="recurring" id="recurringId" checked>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3" style="top: -5px;">
                            Threshold <input type="radio" name="is_recThres" value="threshold" id="thresholdId">
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">Interval</div>
                        <div class="col-md-3 col-sm-3 col-xs-3">Adjustment</div>
                    </div>
                    <div style="clear: both;"></div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="recurring_mos">Months
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('recurring_mos', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="recurring_days">Days
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('recurring_days', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="recurring_hrs">Hours
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('recurring_hrs', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="recurring_afl">Cycles
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('recurring_afl', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="threshold_mos">Months
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('threshold_mos', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="threshold_days">Days
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('threshold_days', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="threshold_hrs">Hours
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('threshold_hrs', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="threshold_afl">Cycles
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('threshold_afl', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="required_frequency_mos">Months
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('required_frequency_mos', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="required_frequency_days">Days
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('required_frequency_days', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="required_frequency_hrs">Hours
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('required_frequency_hrs', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="required_frequency_afl">Cycles
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('required_frequency_afl', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adjustment_mos">Months
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('adjustment_mos', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adjustment_days">Days
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('adjustment_days', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adjustment_hrs">Hours
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('adjustment_hrs', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adjustment_afl">Cycles
                                </label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('adjustment_afl', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'autocomplete' => 'off')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="addPartBorder">
                    <div class="addPageHeading">
                        <div class="col-md-4 col-sm-4 col-xs-4 text-nowrap">Part Information</div>
                        <div class="col-md-4 col-sm-4 col-xs-4 ">Times Since (at Installed)</div>
                        <div class="col-md-4 col-sm-4 col-xs-4 text-nowrap">Additional Information</div>
                    </div>
                    <div class="addPartContainer">
                        <div class="addPartInfoCls">
                            <div class="itemCls">
                            </div>

                            <div class="itemCls">
                                Removed
                            </div>

                            <div class="itemCls">
                                Installed
                            </div>

                            <div class="itemCls">
                            </div>

                            <div class="itemCls">
                                New <input type="radio" class="newTypeId" name="part_type" value="new" checked>
                            </div>

                            <div class="itemCls">
                                Overhaul <input type="radio" class="overhaulTypeId" name="part_type" value="overhaul">
                            </div>

                            <div class="itemCls">
                                Repair <input type="radio" class="repairTypeId" name="part_type" value="repair">
                            </div>

                            <div class="itemCls">
                                Work Card
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('work_card', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="addPartInfoCls">
                            <div class="itemCls">
                                Part Number
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('removed_part_number', array('class' => 'form-control col-xs-12', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('part_number', array('class' => 'form-control col-xs-12', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                Months
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('new_months', array('class' => 'form-control col-md-7 col-xs-12 ohkeypress norCalCls', 'placeholder' => 'mm-dd-yyyy', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('overhaul_months', array('class' => 'form-control col-md-7 col-xs-12 ohkeypress norCalCls', 'placeholder' => 'mm-dd-yyyy', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('repair_months', array('class' => 'form-control col-md-7 col-xs-12 ohkeypress norCalCls', 'placeholder' => 'mm-dd-yyyy', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                Position
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('position', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="addPartInfoCls">
                            <div class="itemCls">
                                Serial Number
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('removed_serial_number', array('class' => 'form-control col-xs-12', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('serial_number', array('class' => 'form-control col-xs-12', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                Hours
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('new_hours', array('class' => 'form-control col-md-7 col-xs-12 ohkeypress', 'placeholder' => '', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('overhaul_hours', array('class' => 'form-control col-md-7 col-xs-12 ohkeypress', 'placeholder' => '', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('repair_hours', array('class' => 'form-control col-md-7 col-xs-12 ohkeypress', 'placeholder' => '', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                Version
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('version', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="addPartInfoCls">
                            <div class="itemCls">
                            </div>

                            <div class="itemCls">
                                Removal Reason
                            </div>

                            <div class="itemCls">
                                Installed Status
                            </div>

                            <div class="itemCls">
                                Landings
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('new_landings', array('class' => 'form-control col-md-7 col-xs-12 ohkeypress', 'placeholder' => '', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('overhaul_landings', array('class' => 'form-control col-md-7 col-xs-12 ohkeypress', 'placeholder' => '', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('repair_landings', array('class' => 'form-control col-md-7 col-xs-12 ohkeypress', 'placeholder' => '', 'label' => false)); ?>
                            </div>

                            <div class="itemCls">
                                Last Revised By
                            </div>

                            <div class="itemCls">
                                <?php echo $this->Form->control('last_revised_by', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false)); ?>
                            </div>
                        </div>

                        <div class="addPartInfoCls">
                            <div class="itemCls">
                            </div>

                            <div class="itemCls">
                                <?php
                                $removalReason = $airCPComp->getRemovalReason();
                                echo $this->Form->control('removal_reason', array('options' => $removalReason, 'empty' => 'Enter reason', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                ?>
                            </div>

                            <div class="itemCls">
                                <?php
                                $installStatus = $airCPComp->getInstallStatus();
                                echo $this->Form->control('installed_status', array('options' => $installStatus, 'empty' => 'Enter status', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false));
                                ?>
                            </div>

                            <div class="itemCls">
                            </div>

                            <div class="itemCls">
                            </div>

                            <div class="itemCls">
                            </div>

                            <div class="itemCls">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="addPartBorder">
                    <div class="addPageHeading">Admin Notes</div>

                    <div class="form-group">
                        <label class="control-label col-md-1 col-sm-1 col-xs-12" for="admin_notes" style="text-align: left;">Notes
                        </label>
                        <div class="col-md-11 col-sm-11 col-xs-12">
                            <?php echo $this->Form->control('admin_notes', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'rows' => 3)); ?>
                        </div>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <?php echo $this->Form->control('updated_by', array('type' => 'hidden', 'value' => $sessionArray['id'], 'label' => false)); ?>
            </div>
        </div>
        <?php
        echo $this->Form->end();
        ?>
    </div>
</div>
<div class="airframecomppopup"></div>
<style>
    table,
    th,
    td {
        border: 1px solid;
        padding: 2px;
        width: 100%;
    }

    .aircraft_component_selected,
    .aircraft_component_file_selected {
        max-height: 75px;
        overflow-y: scroll;
    }
</style>
<?php
echo $this->Html->script('parts');
?>
<script>
    var getComponents = "<?php echo Router::url(['controller' => 'AirframeComponents', 'action' => 'getComponents']); ?>";
    var addNewRowAircraftCompntPartAjaxURL = "<?php echo Router::url(['controller' => 'AirframeComponentParts', 'action' => 'addNewRowAircraftCompntPartAjax']); ?>";
    var uploadAirframeCompPartAttachmentURL = "<?php echo Router::url(['controller' => 'AirframeComponentParts', 'action' => 'uploadAirframeCompPartAttachment']); ?>";

    $(document).ready(function() {
        $('.norCalCls').datetimepicker({
            format: 'MM-DD-YYYY',
            useCurrent: false
        });

        /*** If compliance date, hours and cycles are changed ***/
        //Change compliance date
        $("#cwDatepicker").datetimepicker({
            useCurrent: false,
            format: 'MM-DD-YYYY'
        }).on('dp.change', function(e) {
            var a = moment(e.date._d);
            //var lastCwMos = e.date._d.format('m-d-Y');
            var lastCwMos = a.format('M-D-Y');
            var diffVal = 'false';
            var thisVal = $('input[name="is_recThres"]').val();

            if ($('input#overrideNextDue').is(':checked')) {
                addNextRemDuesIfOverride(lastCwMos, diffVal, thisVal);
            } else {
                addNextRemDues(lastCwMos, diffVal, thisVal);
            }
        });
        /*** If compliance date, hours and cycles are changed ***/

        //Change next due, remaining and interval values based on recurring or threshold selected
        $('input[name="is_recThres"]').on('change', function() {
            var lastCwMos = $('#cwDatepicker').val();
            var diffVal = 'false';
            var thisVal = $(this).val();

            if ($('input#overrideNextDue').is(':checked')) {
                addNextRemDuesIfOverride(lastCwMos, diffVal, thisVal);
            } else {
                addNextRemDues(lastCwMos, diffVal, thisVal);
            }
        });

        //Change next due and remaining values based on recurring, threshold or adjustment values change
        //$(".keypress").on('keyup paste change', function () {
        $(".keypress").keyup(function() {
            var lastCwMos = $('#cwDatepicker').val();
            var diffVal = 'false';
            var thisVal = $('input[name=is_recThres]:checked').val();

            if ($('input#overrideNextDue').is(':checked')) {
                addNextRemDuesIfOverride(lastCwMos, diffVal, thisVal);
            } else {
                addNextRemDues(lastCwMos, diffVal, thisVal);
            }
        });

        //When add value in overhaul
        $(".ohkeypress").keyup(function() {
            var lastCwMos = $('#cwDatepicker').val();
            var diffVal = 'true';
            var thisVal = $('input[name=is_recThres]:checked').val();

            if ($('input#overrideNextDue').is(':checked')) {
                addNextRemDuesIfOverride(lastCwMos, diffVal, thisVal);
            } else {
                addNextRemDues(lastCwMos, diffVal, thisVal);
            }
        });

        //When add value in part (New/Overhaul/Repair) and any option selected
        $('input[name="part_type"]').on('change', function() {
            var lastCwMos = $('#cwDatepicker').val();
            var diffVal = 'true';
            var thisVal = $('input[name=is_recThres]:checked').val();

            if ($('input#overrideNextDue').is(':checked')) {
                addNextRemDuesIfOverride(lastCwMos, diffVal, thisVal);
            } else {
                addNextRemDues(lastCwMos, diffVal, thisVal);
            }
        });

        //Override next due
        $('.overrideEom').on('click', function() {
            var planeId = $('#planeName').val();
            var compId = $('#airframe_component_id').val();
            var lastCwMos = $('#cwDatepicker').val();
            var isOverride = $('input#overrideNextDue').is(':checked');
            var isEom = $('input#eomNextDue').is(':checked');
            var diffVal = 'false';
            var thisVal = $('input[name=is_recThres]:checked').val();

            if (thisVal == 'recurring') {
                $('#required-frequency-mos').val($('#recurring-mos').val());
                $('#required-frequency-days').val($('#recurring-days').val());
                $('#required-frequency-hrs').val($('#recurring-hrs').val());
                $('#required-frequency-afl').val($('#recurring-afl').val());
            } else if (thisVal == 'threshold') {
                $('#required-frequency-mos').val($('#threshold-mos').val());
                $('#required-frequency-days').val($('#threshold-days').val());
                $('#required-frequency-hrs').val($('#threshold-hrs').val());
                $('#required-frequency-afl').val($('#threshold-afl').val());
            }

            if ($('input#overrideNextDue').is(':checked')) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo Router::url(['controller' => 'AirframeComponentParts', 'action' => 'addNextdueRem']); ?>",
                    data: {
                        planeId: planeId,
                        compId: compId,
                        isOverride: isOverride,
                        isEom: isEom
                    },
                    async: true,
                    success: function(response) {
                        var obj = JSON.parse(response);
                        if (obj.status == 'success') {
                            if ($('input#eomNextDue').is(':checked') || !$('input#eomNextDue').is(':checked')) {}

                            if ($('input#overrideNextDue').is(':checked')) {
                                if (obj.data.nextMos != '') {
                                    $('#next-due-date').val(obj.data.nextMos).prop('readonly', false);
                                } else {
                                    $('#next-due-date').val('').prop('readonly', false);
                                }

                                if (obj.data.nextMos != '') {
                                    $('#next-due-hrs').val(obj.data.nextHrs).prop('readonly', false);
                                } else {
                                    $('#next-due-hrs').val('').prop('readonly', false);
                                }

                                if (obj.data.nextMos != '') {
                                    $('#next-due-afl').val(obj.data.nextAfl).prop('readonly', false);
                                } else {
                                    $('#next-due-afl').val('').prop('readonly', false);
                                }
                            }

                            if (obj.data.override == 1) {
                                $('#remMos').html(obj.data.remMos);
                                $('#remDays').html(obj.data.remDays);
                                $('#remHrs').html(obj.data.remHrs);
                                $('#remAfl').html(obj.data.remAfl);
                            }

                            //Update component hours and cycles in hidden field for calculations
                            $('#compHours').val(obj.data.hours);
                            $('#compCycles').val(obj.data.cycles);
                        }
                    }
                });
            } else {
                //Add next due and remaining due
                addNextRemDues(lastCwMos, diffVal, thisVal);
            }
        });

        //Common function to update next and remaining dues if Override checked
        function addNextRemDuesIfOverride(lastCwMos, diffVal, thisVal) {
            if (thisVal == 'recurring') {
                $('#required-frequency-mos').val($('#recurring-mos').val());
                $('#required-frequency-days').val($('#recurring-days').val());
                $('#required-frequency-hrs').val($('#recurring-hrs').val());
                $('#required-frequency-afl').val($('#recurring-afl').val());

                //Change Alert value
                if ($('#recurring-mos').val() > 24) {
                    $('#alert-days').val(60);
                } else {
                    $('#alert-days').val(30);
                }

                if ($('#recurring-hrs').val() > 999) {
                    $('#alert-hrs').val(200);
                } else {
                    $('#alert-hrs').val(50);
                }

                if ($('#recurring-afl').val() > 999) {
                    $('#alert-afl').val(200);
                } else {
                    $('#alert-afl').val(25);
                }

            } else if (thisVal == 'threshold') {
                $('#required-frequency-mos').val($('#threshold-mos').val());
                $('#required-frequency-days').val($('#threshold-days').val());
                $('#required-frequency-hrs').val($('#threshold-hrs').val());
                $('#required-frequency-afl').val($('#threshold-afl').val());

                //Change Alert value
                if ($('#threshold-mos').val() > 24) {
                    $('#alert-days').val(60);
                } else {
                    $('#alert-days').val(30);
                }

                if ($('#threshold-hrs').val() > 999) {
                    $('#alert-hrs').val(200);
                } else {
                    $('#alert-hrs').val(50);
                }

                if ($('#threshold-afl').val() > 999) {
                    $('#alert-afl').val(200);
                } else {
                    $('#alert-afl').val(25);
                }
            }

            if (diffVal == 'true') {
                var overhaulType = 'yes';
                var partTypeVal = $('input[name="part_type"]:checked').val();
                var partMos;
                var partHrs;
                var partAfl;
                if (partTypeVal == 'new') {
                    partMos = $('#new-months').val();
                    partHrs = $('#new-hours').val();
                    partAfl = $('#new-landings').val();
                } else if (partTypeVal == 'overhaul') {
                    partMos = $('#overhaul-months').val();
                    partHrs = $('#overhaul-hours').val();
                    partAfl = $('#overhaul-landings').val();
                } else if (partTypeVal == 'repair') {
                    partMos = $('#repair-months').val();
                    partHrs = $('#repair-hours').val();
                    partAfl = $('#repair-landings').val();
                }

                var data = {
                    planeId: $('#planeName').val(),
                    compId: $('#airframe_component_id').val(),
                    lastCwMos: lastCwMos,
                    lastCwHrs: $('#last-cw-hrs').val(),
                    lastCwAfl: $('#last-cw-afl').val(),
                    mos: $('#next-due-date').val(),
                    hrs: $('#next-due-hrs').val(),
                    afl: $('#next-due-afl').val(),
                    intvMos: $('#required-frequency-mos').val(),
                    intvDay: $('#required-frequency-days').val(),
                    intvHrs: $('#required-frequency-hrs').val(),
                    intvAfl: $('#required-frequency-afl').val(),
                    intvAdjMos: $('#adjustment-mos').val(),
                    intvAdjDay: $('#adjustment-days').val(),
                    intvAdjHrs: $('#adjustment-hrs').val(),
                    intvAdjAfl: $('#adjustment-afl').val(),
                    isOverride: $('input#overrideNextDue').is(':checked'),
                    isEom: $('input#eomNextDue').is(':checked'),
                    overhaulType: overhaulType,
                    partTypeVal: partTypeVal,
                    partMos: partMos,
                    partHrs: partHrs,
                    partAfl: partAfl
                };
            } else {
                var data = {
                    planeId: $('#planeName').val(),
                    compId: $('#airframe_component_id').val(),
                    lastCwMos: lastCwMos,
                    lastCwHrs: $('#last-cw-hrs').val(),
                    lastCwAfl: $('#last-cw-afl').val(),
                    mos: $('#next-due-date').val(),
                    hrs: $('#next-due-hrs').val(),
                    afl: $('#next-due-afl').val(),
                    intvMos: $('#required-frequency-mos').val(),
                    intvDay: $('#required-frequency-days').val(),
                    intvHrs: $('#required-frequency-hrs').val(),
                    intvAfl: $('#required-frequency-afl').val(),
                    intvAdjMos: $('#adjustment-mos').val(),
                    intvAdjDay: $('#adjustment-days').val(),
                    intvAdjHrs: $('#adjustment-hrs').val(),
                    intvAdjAfl: $('#adjustment-afl').val(),
                    isOverride: $('input#overrideNextDue').is(':checked'),
                    isEom: $('input#eomNextDue').is(':checked')
                };
            }

            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller' => 'AirframeComponentParts', 'action' => 'addNextdueRem']); ?>",
                data: data,
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status == 'success') {

                        if ($('#next-due-date').val() == '' && $('#next-due-hrs').val() == '' && $('#next-due-afl').val() == '') {
                            $('#next-due-date').prop('readonly', false);
                            $('#next-due-hrs').prop('readonly', false);
                            $('#next-due-afl').prop('readonly', false);
                        } else {
                            $('#next-due-date').val(obj.data.nextMos).prop('readonly', false);
                            $('#next-due-hrs').val(obj.data.nextHrs).prop('readonly', false);
                            $('#next-due-afl').val(obj.data.nextAfl).prop('readonly', false);
                        }

                        $('#remMos').html(obj.data.remMos);
                        $('#remDays').html(obj.data.remDays);
                        $('#remHrs').html(obj.data.remHrs);
                        $('#remAfl').html(obj.data.remAfl);

                        if (data.overhaulType == 'yes') {
                            $('#adjustment-mos').val(obj.data.intvAdjMos);
                            $('#adjustment-days').val(obj.data.intvAdjDay);
                            $('#adjustment-hrs').val(obj.data.intvAdjHrs);
                            $('#adjustment-afl').val(obj.data.intvAdjAfl);
                        }

                        //Update component hours and cycles in hidden field for calculations
                        $('#compHours').val(obj.data.hours);
                        $('#compCycles').val(obj.data.cycles);
                    }
                }
            });
        }

        //Common function to update next and remaining dues
        function addNextRemDues(lastCwMos, diffVal, thisVal) {

            if (thisVal == 'recurring') {
                $('#required-frequency-mos').val($('#recurring-mos').val());
                $('#required-frequency-days').val($('#recurring-days').val());
                $('#required-frequency-hrs').val($('#recurring-hrs').val());
                $('#required-frequency-afl').val($('#recurring-afl').val());

                //Change Alert value
                if ($('#recurring-mos').val() > 24) {
                    $('#alert-days').val(60);
                } else {
                    $('#alert-days').val(30);
                }

                if ($('#recurring-hrs').val() > 999) {
                    $('#alert-hrs').val(200);
                } else {
                    $('#alert-hrs').val(50);
                }

                if ($('#recurring-afl').val() > 999) {
                    $('#alert-afl').val(200);
                } else {
                    $('#alert-afl').val(25);
                }

            } else if (thisVal == 'threshold') {
                $('#required-frequency-mos').val($('#threshold-mos').val());
                $('#required-frequency-days').val($('#threshold-days').val());
                $('#required-frequency-hrs').val($('#threshold-hrs').val());
                $('#required-frequency-afl').val($('#threshold-afl').val());

                //Change Alert value
                if ($('#threshold-mos').val() > 24) {
                    $('#alert-days').val(60);
                } else {
                    $('#alert-days').val(30);
                }

                if ($('#threshold-hrs').val() > 999) {
                    $('#alert-hrs').val(200);
                } else {
                    $('#alert-hrs').val(50);
                }

                if ($('#threshold-afl').val() > 999) {
                    $('#alert-afl').val(200);
                } else {
                    $('#alert-afl').val(25);
                }
            }

            if (diffVal == 'true') {
                var overhaulType = 'yes';
                var partTypeVal = $('input[name="part_type"]:checked').val();
                var partMos;
                var partHrs;
                var partAfl;
                if (partTypeVal == 'new') {
                    partMos = $('#new-months').val();
                    partHrs = $('#new-hours').val();
                    partAfl = $('#new-landings').val();
                } else if (partTypeVal == 'overhaul') {
                    partMos = $('#overhaul-months').val();
                    partHrs = $('#overhaul-hours').val();
                    partAfl = $('#overhaul-landings').val();
                } else if (partTypeVal == 'repair') {
                    partMos = $('#repair-months').val();
                    partHrs = $('#repair-hours').val();
                    partAfl = $('#repair-landings').val();
                }

                var data = {
                    planeId: $('#planeName').val(),
                    compId: $('#airframe_component_id').val(),
                    lastCwMos: lastCwMos,
                    lastCwHrs: $('#last-cw-hrs').val(),
                    lastCwAfl: $('#last-cw-afl').val(),
                    intvMos: $('#required-frequency-mos').val(),
                    intvDay: $('#required-frequency-days').val(),
                    intvHrs: $('#required-frequency-hrs').val(),
                    intvAfl: $('#required-frequency-afl').val(),
                    intvAdjMos: $('#adjustment-mos').val(),
                    intvAdjDay: $('#adjustment-days').val(),
                    intvAdjHrs: $('#adjustment-hrs').val(),
                    intvAdjAfl: $('#adjustment-afl').val(),
                    isOverride: $('input#overrideNextDue').is(':checked'),
                    isEom: $('input#eomNextDue').is(':checked'),
                    overhaulType: overhaulType,
                    partTypeVal: partTypeVal,
                    partMos: partMos,
                    partHrs: partHrs,
                    partAfl: partAfl
                };
            } else {
                var data = {
                    planeId: $('#planeName').val(),
                    compId: $('#airframe_component_id').val(),
                    lastCwMos: lastCwMos,
                    lastCwHrs: $('#last-cw-hrs').val(),
                    lastCwAfl: $('#last-cw-afl').val(),
                    intvMos: $('#required-frequency-mos').val(),
                    intvDay: $('#required-frequency-days').val(),
                    intvHrs: $('#required-frequency-hrs').val(),
                    intvAfl: $('#required-frequency-afl').val(),
                    intvAdjMos: $('#adjustment-mos').val(),
                    intvAdjDay: $('#adjustment-days').val(),
                    intvAdjHrs: $('#adjustment-hrs').val(),
                    intvAdjAfl: $('#adjustment-afl').val(),
                    isOverride: $('input#overrideNextDue').is(':checked'),
                    isEom: $('input#eomNextDue').is(':checked')
                };
            }

            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller' => 'AirframeComponentParts', 'action' => 'addNextdueRem']); ?>",
                data: data,
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status == 'success') {
                        $('#next-due-date').val(obj.data.nextMos).prop('readonly', true);
                        $('#next-due-hrs').val(obj.data.nextHrs).prop('readonly', true);
                        $('#next-due-afl').val(obj.data.nextAfl).prop('readonly', true);
                        $('#remMos').html(obj.data.remMos);
                        $('#remDays').html(obj.data.remDays);
                        $('#remHrs').html(obj.data.remHrs);
                        $('#remAfl').html(obj.data.remAfl);

                        if (data.overhaulType == 'yes') {
                            $('#adjustment-mos').val(obj.data.intvAdjMos);
                            $('#adjustment-days').val(obj.data.intvAdjDay);
                            $('#adjustment-hrs').val(obj.data.intvAdjHrs);
                            $('#adjustment-afl').val(obj.data.intvAdjAfl);
                        }

                        //Update component hours and cycles in hidden field for calculations
                        $('#compHours').val(obj.data.hours);
                        $('#compCycles').val(obj.data.cycles);
                    }
                }
            });
        }

        //Remaining update if custom nextdue change
        $('.remUpdate').keyup(function() {
            var planeId = $('#planeName').val();
            var compId = $('#airframe_component_id').val();
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller' => 'AirframeComponentParts', 'action' => 'getCompTimes']); ?>",
                data: {
                    planeId: planeId,
                    compId: compId
                },
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status == 'success') {
                        $('#compHours').val(obj.data.hours);
                        $('#compCycles').val(obj.data.cycles);
                    }
                }
            });

            if ($('#next-due-hrs').val() != '') {
                var hrs = $('#next-due-hrs').val() - $('#compHours').val();
                $('#remHrs').html(hrs.toFixed(2));
            } else {
                $('#remHrs').html('');
            }

            if ($('#next-due-afl').val() != '') {
                var afl = $('#next-due-afl').val() - $('#compCycles').val();
                $('#remAfl').html(afl.toFixed(2));
            } else {
                $('#remAfl').html('');
            }
        });

        //Remaining update if custom nextdue change
        $("#next-due-date").datetimepicker({
            useCurrent: false,
            format: 'MM-DD-YYYY'
        }).on('dp.change', function(e) {
            var a = moment(e.date._d);
            //var date = e.date._d.format('m-d-Y');
            var date = a.format('M-D-Y');
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller' => 'AirframeComponentParts', 'action' => 'updateRemaining']); ?>",
                data: {
                    nextMos: date
                },
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status == 'success') {
                        $('#remMos').html(obj.data.remMos);
                        $('#remDays').html(obj.data.remDays);
                    }
                }
            });
        });

        //Set current time based on selected aircraft and component
        $('#currentTimeId').css('pointer-events', 'none');
        $(document).on('change', '#airframe_component_id', function() {
            var compId = $(this).val();
            var airId = $('#planeName').val();
            if (compId) {
                $('#currentTimeId').data('comp_id', compId);
                $('#currentTimeId').data('air_id', airId);
                $('#currentTimeId').css('pointer-events', 'auto');
            } else {
                $('#currentTimeId').data('comp_id', '');
                $('#currentTimeId').data('air_id', '');
                $('#currentTimeId').css('pointer-events', 'none');
            }
        });

        //Update current component time
        $(document).on('click', '#currentTimeId', function() {
            var air_id = $(this).data('air_id');
            var comp_id = $(this).data('comp_id');
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1;
            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            var today = yyyy + '-' + mm + '-' + dd;
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller' => 'Reports', 'action' => 'updateCurrentTime']); ?>",
                data: {
                    plane_id: air_id,
                    comp_id: comp_id
                },
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status == 'success') {
                        $('#cwDatepicker').val(today);
                        $('#last-cw-hrs').val(obj.data.hours);
                        $('#last-cw-afl').val(obj.data.cycles);
                    } else {
                        $('#cwDatepicker').val('');
                        $('#last-cw-hrs').val('');
                        $('#last-cw-afl').val('');
                    }
                }
            });
        });
        //End set current time based on selected aircraft and component

    });

    $(document).on('change', '.aircraft_dropdown', function(e) {
        var planeId = $(this).val();
        if (planeId != '') {
            var events = $(this);
            $.ajax({
                type: "POST",
                url: getComponents,
                data: {
                    planeId: planeId,
                    'source': 'airframe_comp_add'
                },
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    var components = events.parents().siblings("td:eq(1)");
                    //var components = $('#airframe_component_id_1');
                    var component_id = events.closest('tr').find('td:eq(1) select').attr("id");
                    var options = '<option value="">Select Aircraft Component</option>';
                    $.each(obj.data, function(index, value) {
                        options += '<option value="' + index + '">' + value + '</option>';
                    });
                    $('#' + component_id).html(options);
                    $('.selectpicker').selectpicker('refresh');
                }
            });
        }
    });

    $(document).on('click', '.aircraft_comp_addmorebtn', function(e) {
        /*var clonedRow = $("#aircraft_component_block tbody tr:last").clone();
        //clonedRow.find("input").val("");
        //clonedRow.find("select").val("");
        
        clonedRow.find('.bootstrap-select').replaceWith(function() { return $('select', this); });
        clonedRow.find('.selectpicker').selectpicker('render');
        clonedRow.children().find('select').attr('id', function( i, val ){ 
            var newid = val + cloneCount;
            clonedRow.find("#"+val).attr("id", newid);
            
        });
        cloneCount = parseFloat(cloneCount)+1;
        $("#aircraft_component_block tbody").append(clonedRow);
        $('.selectpicker').selectpicker('refresh');*/
        var counter = Math.floor(Math.random() * (999 - 100 + 1) + 100);

        $.ajax({
            url: addNewRowAircraftCompntPartAjaxURL,
            type: 'post',
            data: {
                counter: counter
            },
            async: true,
            success: function(response) {
                //response.find("select").val("");
                $('#aircraft_comp_select_block').append(response);
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });

    $(document).on('click', '.delete_aircraft_comp_btn', function(e) {
        var rowCount = $('#aircraft_component_block tr').length;
        if (rowCount > '2') {
            $(this).closest('tr').remove();
        }
    });

    $(document).on('click', '.aircraft_component_selectbtn', function(e) {
        var html = '';
        var isempty = '0';
        var tablehtml = '';
        $('#aircraft_component_block > tbody > tr').each(function() {
            var counter = 1;
            $(this).children('td').each(function() {
                if (counter <= '2') {
                    var val = $(this).find('select').val();
                    var texts = $(this).find('select option:selected').text();
                    if (val != 'undefined' && val != '') {
                        if (counter == '1') {
                            html += '<input type="hidden" name="plane_id[]" value="' + val + '" />';
                            tablehtml += '<tr><td>' + texts + '</td>';
                        } else if (counter == '2') {
                            html += '<input type="hidden" name="airframe_component_id[]" value="' + val + '" />';
                            tablehtml += '<td>' + texts + '</td></tr>';
                        }
                    } else {
                        isempty = '1';
                    }
                }
                counter++;
            });
        });

        if (isempty == '0') {
            $('.aircraft_component_list').html(html);
            $('.aircraft_component_selected').css('display', 'block');
            $('#aircraftSelCompList').html(tablehtml);
            $('#aircraftComponentDetModel').modal('hide');
        } else {
            alert("Please fill all fields");
        }
    });

    $(document).on('click', '.selectaircraftcomponent', function(e) {
        var aircraftarr = [];
        var componentarr = [];

        $(".aircraft_component_list").find('input').each(function(index) {
            var counter = parseInt(index) + 1;
            if (counter % 2 != '0') {
                aircraftarr.push($(this).val());
            } else {
                componentarr.push($(this).val());
            }
        });
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller' => 'AirframeComponentParts', 'action' => 'fetchAircraftComponentPopupHTML']); ?>",
            data: {
                'aircraft': aircraftarr,
                'component': componentarr
            },
            async: true,
            success: function(response) {
                $('.airframecomppopup').html(response);
                $('.selectpicker').selectpicker('refresh');
                $('#aircraftComponentDetModel').modal('show');
            }
        });
    });

    $(document).on("change", "#component_part_attachment", function() {
        // Read selected files
        var fldid = 'component_part_attachment';
        var tableid = 'component_part_file_list';
        uploadFileToServer(fldid, tableid, uploadAirframeCompPartAttachmentURL);
    });

    function uploadFileToServer(fldid, tableid, url) {
        var totalfiles = document.getElementById(fldid).files.length;
        for (var index = 0; index < totalfiles; index++) {
            var form_data = new FormData();
            form_data.append("file_name", document.getElementById(fldid).files[index]);
            form_data.append("source", 'add');

            $.ajax({
                url: url,
                type: 'post',
                data: form_data,
                contentType: false,
                processData: false,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj.status == 'success') {
                        $("#" + tableid).append(obj.tblrow);
                        $('.aircraft_component_file_selected').css('display', 'block');
                    } else {
                        //$('#'+tableid).html('<tr><td colspan="5"><span style="color:red;">'+obj.message+'</span></td></tr>');
                        alert(obj.message);
                        $("#" + tableid).html('');
                        $('.aircraft_component_file_selected').css('display', 'none');
                    }
                }
            });
        }
    }

    $(document).on('click', '.delete_comp_part_attachment', function(e) {
        if (confirm('Are you sure want to delete this attachment?')) {
            $(this).parent().parent().remove();
            if ($('.aircraft_component_file_selected >tbody >tr').length == '0') {
                $('.aircraft_component_file_selected').css('display', 'none');
            }
        }
    });
</script>