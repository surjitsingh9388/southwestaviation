<?php 
$sessionUser = $this->request->session()->read('Auth.User');
$sessionArray = $this->Session->read('Auth.User');
use Cake\Routing\Router;

if(!empty($airCompParts->plane_id) && !empty($subResults)) {
    $pdfCheck = 'style="pointer-events: auto;"';
} else {
    $pdfCheck = 'style="pointer-events: none;"';
}
?>

<div class="content sliding">
    <div class="outerWrapper">
    <?php
    echo $this->Form->create($airCompParts, array('class' => 'form-horizontal form-label-left', 'id' => 'frmAirCompPart'));
    ?>  
        <div class="btnWrapper">
            <h2 class="heading">Update Part Details</h2>
            <div class="btnWrap">
             <?php
                echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
                ?>
                <div class="split-btn ml-10">
                    <button class="btn-dropdown btn-default">Action</button>

                    <button class="icon-part dropdown-toggle actionCls" data-toggle="dropdown">
                        <i class="fa fa-caret-down"></i>
                    </button>
                                                  
                    <?php
                    if((!empty($actionItems) && $actionItems['action']['action_view']==1 && $actionItems['action']['action_add']!=1 && $actionItems['action']['action_edit']!=1 && $actionItems['action']['action_delete']!=1) && $sessionUser['id'] != 1) {
                        echo "";
                    } else {
                    ?>
                    <div class="dropdown-content dropdown-menu">
                        <?php
                        if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $sessionUser['id'] == 1){
                        ?>
                        <a href="javascript:void(0);" class="reviseCls">Enable Revise</a>
                        <a href="javascript:void(0);" data-part_id="<?php echo $airCompParts['id']; ?>" data-plane_id="<?php echo $airCompParts['plane_id']; ?>" class="addCompliance">Add Compliance</a>
                        <?php
                        }
                        ?>

                        <?php
                        if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $sessionUser['id'] == 1){
                        ?>
                        <a href="javascript:void(0);" class="removePartCls" data-pids="<?php echo $airCompParts['plane_id']; ?>" data-partids="<?php echo $airCompParts['id']; ?>" data-aircounts="">Remove</a>
                        <?php
                        }
                        ?>

                        <?php
                        if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $sessionUser['id'] == 1){
                        ?>
                        <a href="javascript:void(0);" data-part_id="<?php echo $airCompParts['id']; ?>" data-plane_id="<?php echo $airCompParts['plane_id']; ?>" class="errorCorrect">Error Correct</a>
                        <?php
                        }
                        ?>
                    </div>
                    <?php } ?>
                </div>
                <?php
                if((!empty($actionItems) && $actionItems['action']['action_edit'] == 1) || $sessionUser['id'] == 1) {
                    echo $this->Form->button('Save', ['type' => 'submit', 'class' => 'btn btn-default ml-10']);
                }
                ?>
                <a class="btn btn-default partDetailPrint" href="javascript:void(0);" data-plane_id="<?php echo $airCompParts['plane_id']; ?>" data-part_id="<?php echo $airCompParts['id']; ?>">Print</a>
            </div>
        </div>
       
        <div class="page-content mt-35">
            <?php
            $readonly = true;
            if(!empty($airCompParts['airframe_component_last_cw'][0]['override'])) {
                $readonly = false;
            }

            $isResThres = !empty($airCompParts['airframe_component_last_cw'][0]['is_recThres']) ? $airCompParts['airframe_component_last_cw'][0]['is_recThres'] : '';

            //Change alert box value
            if(!empty($isResThres) && $isResThres == 'recurring') {
                $airCompParts['airframe_component_last_cw'][0]['alert_days'] = ($airCompParts['airframe_component_last_cw'][0]['recurring_mos'] > 24) ? 60 : 30;
                $airCompParts['airframe_component_last_cw'][0]['alert_hrs'] = ($airCompParts['airframe_component_last_cw'][0]['recurring_hrs'] > 999) ? 200 : 50;
                $airCompParts['airframe_component_last_cw'][0]['alert_afl'] = ($airCompParts['airframe_component_last_cw'][0]['recurring_afl'] > 999) ? 200 : 25;
            } elseif(!empty($isResThres) && $isResThres == 'threshold') {
                $airCompParts['airframe_component_last_cw'][0]['alert_days'] = ($airCompParts['airframe_component_last_cw'][0]['threshold_mos'] > 24) ? 60 : 30;
                $airCompParts['airframe_component_last_cw'][0]['alert_hrs'] = ($airCompParts['airframe_component_last_cw'][0]['threshold_hrs'] > 999) ? 200 : 50;
                $airCompParts['airframe_component_last_cw'][0]['alert_afl'] = ($airCompParts['airframe_component_last_cw'][0]['threshold_afl'] > 999) ? 200 : 25;
            }

            //Get data correction and nextdue calculation
            $mos = $hrs = $afl = $msc = '';
            if(!empty($airCompParts['airframe_component_last_cw'][0])) {
                $getRes = $airCPComp->postDataProcess($airCompParts['airframe_component_last_cw'][0]);
                $mos = $getRes['mos'];
                $hrs = $getRes['hrs'];
                $afl = $getRes['afl'];
                $msc = $getRes['msc'];
            }

            //Start Remaining    
            $remMonths = '';
            $remDays = '';
            if(!empty($mos)) {
                $mos = $airCPComp->changeFormat($mos);
                $date1 = new \DateTime($mos);
                //Do not change date format
                $date2 = new \DateTime(date('d-m-Y'));
                $interval = date_diff($date1, $date2);
                $year = $interval->format('%y');
                $remMonths = $interval->format('%m') + $year * 12;
                $remDays = $interval->format('%d');
                
                if($date1 < $date2) {
                    $remMonths = !empty($remMonths) ? -$remMonths : 0;
                    $remDays = !empty($remDays) ? -$remDays : 0;
                }
            }

            //Component Times
            $compHours  = !empty($airCompParts['airframe_component']['airframe_component_times'][0]['hours']) ? $airCompParts['airframe_component']['airframe_component_times'][0]['hours'] : 0;
            $compCycles = !empty($airCompParts['airframe_component']['airframe_component_times'][0]['cycles']) ? $airCompParts['airframe_component']['airframe_component_times'][0]['cycles'] : 0;

            $remHours  = !empty($hrs) ? $hrs - $compHours : '';
            $remCycles = !empty($afl) ? $afl - $compCycles : '';
            //End Remaining

            $override = !empty($airCompParts['airframe_component_last_cw'][0]['override']) ? $airCompParts['airframe_component_last_cw'][0]['override'] : '';
            $eom = !empty($airCompParts['airframe_component_last_cw'][0]['eom']) ? $airCompParts['airframe_component_last_cw'][0]['eom'] : '';
            
            $lastCwDate = !empty($airCompParts['airframe_component_last_cw'][0]['last_cw_date']) ? date('m-d-Y', strtotime($airCompParts['airframe_component_last_cw'][0]['last_cw_date'])) : '';
            $lastCwHour = !empty($airCompParts['airframe_component_last_cw'][0]['last_cw_hrs']) ? $airCompParts['airframe_component_last_cw'][0]['last_cw_hrs'] : '';
            $lastCwCycle = !empty($airCompParts['airframe_component_last_cw'][0]['last_cw_afl']) ? $airCompParts['airframe_component_last_cw'][0]['last_cw_afl'] : '';

            $style = '';
            if ((!empty($mos) && strtotime($mos) <= strtotime(date('m-d-Y'))) || (empty($mos) && !empty($remHours) && $remHours < 0)) {
                $style = 'pastDue';
            }

            //Change next due date format
            $mos = !empty($mos) ? $airCPComp->dateFormat($mos) : '';
            ?>
            <input type="hidden" name="part_id" id="part_id" value="<?php echo $airCompParts['id']; ?>">
            <input type="hidden" name="compHours" id="compHours" value="<?php echo $compHours; ?>">
            <input type="hidden" name="compCycles" id="compCycles" value="<?php echo $compCycles; ?>">
            
            <div class="formBGCls">
                <div class="addPartBorder">
                    <div class="addPageHeading">Basic Information</div>

                    <div class="row">
                        <div class="col-md-3">
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
                                <?php echo $this->Form->control('airframe_component_id', array('options' => $airComps, 'empty' => 'Select Aircraft Component', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'required' => 'required', 'label' => false, 'id'=>'airframe_component_id')); ?>
                                </div>
                            </div>
                        </div>

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
                                    <?php echo $this->Form->control('mfg_code', array('class'=>'form-control col-md-7 col-xs-12', 'placeholder' => 'Mfg Code', 'label' => false, 'autocomplete'=>'off')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="ad_sb_number">AD/SB Number</label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->control('ad_sb_number', array('class'=>'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="ad_sb_status">AD/SB Class</label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php
                                    echo $this->Form->control('ad_sb_status', array('options' => $adsbArr, 'empty' => 'Enter class', 'class' => 'form-control col-md-7 col-xs-12 selectpicker', 'data-show-subtext' => true, 'data-live-search' => true, 'label' => false, 'autocomplete'=>'off'));
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
                                    <?php echo $this->Form->control('reference', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
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
                                    <?php echo $this->Form->control('amendment', array('class'=>'form-control col-md-7 col-xs-12', 'label' => false, 'autocomplete'=>'off')); ?>
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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="description">Item Name</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('description', array('class' => 'form-control col-md-7 col-xs-12', 'label'=> false, 'rows'=>2, 'style'=>'margin-left:18px;width:97%;')); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="notes">Notes</label>
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                    <?php echo $this->Form->control('notes', array('class' => 'form-control col-md-7 col-xs-12', 'label'=> false, 'rows'=>2, 'style'=>'margin-left:18px;width:97%;')); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label col-md-1 col-sm-1 col-xs-12">Work Description</label>
                                <div class="col-md-11 col-sm-11 col-xs-12">
                                    <?php echo $this->Form->control('work_description', array('class' => 'form-control col-md-7 col-xs-12', 'label'=> false, 'rows'=>2, 'style'=>'margin: 0 0 0 43px; width: 95%;')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-5 col-xs-12" for="avg_man_hrs">Man Hours</label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <?php echo $this->Form->control('avg_man_hrs', array('class' => 'form-control col-md-7 col-xs-12', 'label'=>false, 'autocomplete'=>'off')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-1 col-sm-1 col-xs-12" for="tags">Tags</label>
                                <div class="col-md-8 col-sm-8 col-xs-12" style='margin-left: 18px; width: 65%;'>
                                    <input type="text" name="tags" value="<?php echo $airCompParts['tags']; ?>" data-role="tagsinput" class="form-control" placeholder="Enter a tag"></input>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <!-- Tabs Start -->
                <div id="aircraftTabs" style="padding: 15px 0 15px 0;">
                    <div class="container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#itemInfo">Item Information</a></li>
                            <li><a data-toggle="tab" href="#subItems">Sub Items</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="itemInfo" class="tab-pane fade in active">
                                <div style="">
                                    <div class="corrPageHeading">
                                        <div class="corrPH">Last Complied With</div>
                                        <div class="corrPH" style="background: #518aed;">Next Due</div>
                                        <div class="corrPH" style="background: #518aed;">Remaining</div>
                                        <div class="corrPH">Tolerance</div>
                                        <div class="corrPH">Alert</div>
                                    </div>
                                    <div class="itemInfoCls">
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
                                                        <?php echo $this->Form->Text('last_cw_date', array('class' => 'form-control col-md-7 col-xs-12', 'id' => 'cwDatepicker', 'placeholder' => '', 'label' => false, 'value'=>$lastCwDate, 'autocomplete'=>'off')); ?>
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
                                                    <?php echo $this->Form->control('last_cw_hrs', array('class'=>'form-control col-md-7 col-xs-12 keypress', 'placeholder'=>'', 'label'=>false, 'value'=>$lastCwHour, 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_cw_afl">Cycles
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('last_cw_afl', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>$lastCwCycle, 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>                               
                                        </div>

                                        <div class="itemCls">
                                            <div class="form-group">
                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                    <?php echo $this->Form->checkbox('override', array('label'=>'', 'id'=>'overrideNextDue', 'class'=>'overrideEom', 'checked'=>$override)); ?> <span style="font-size: 10px;">Override Next Due</span>
                                                </div>

                                                <div class="col-md-5 col-sm-5 col-xs-12">
                                                    <?php echo $this->Form->checkbox('eom', array('label' => '', 'id'=>'eomNextDue', 'class'=>'overrideEom', 'checked'=>$eom)); ?> <span style="font-size: 10px;">EoM Adj</span>
                                                </div>
                                            </div>

                                            <div class="form-group"> 
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="next_due_date">Date 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('next_due_date', array('class' => 'form-control col-md-7 col-xs-12 remDateUpdate '.$style, 'placeholder' => '', 'label' => false, 'div'=>false, 'value'=>$mos, 'readonly' => $readonly)); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="next_due_hrs">Hours
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('next_due_hrs', array('class' => 'form-control col-md-7 col-xs-12 remUpdate '.$style, 'placeholder'=>'', 'label'=> false, 'value'=>$hrs, 'autocomplete'=>'off', 'readonly' => $readonly)); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="next_due_afl">Cycles
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('next_due_afl', array('class'=>'form-control col-md-7 col-xs-12 remUpdate '.$style, 'placeholder'=>'', 'label'=> false, 'value'=>$afl, 'autocomplete'=>'off', 'readonly' => $readonly)); ?>
                                                </div>
                                            </div>                            
                                        </div>

                                        <div class="itemCls">
                                            <div class="form-group"> 
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Months 
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <span class="form-control <?php echo $style; ?>" id="remMos">
                                                        <?php echo $remMonths; ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_cw_hrs">Days
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <span class="form-control <?php echo $style; ?>" id="remDays">
                                                        <?php echo $remDays; ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_cw_afl">Hours
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <span class="form-control <?php echo $style; ?>" id="remHrs">
                                                        <?php echo round($remHours,1); ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_cw_msc">Cycles
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <span class="form-control <?php echo $style; ?>" id="remAfl">
                                                        <?php echo $remCycles; ?>
                                                    </span>
                                                </div>
                                            </div>                                
                                        </div>

                                        <div class="itemCls">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tolerance_mos">Months
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('tolerance_mos', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['tolerance_mos']) ? $airCompParts['airframe_component_last_cw'][0]['tolerance_mos'] : '', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tolerance_days">Days
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('tolerance_days', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['tolerance_days'])?$airCompParts['airframe_component_last_cw'][0]['tolerance_days']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tolerance_hrs">Hours
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('tolerance_hrs', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['tolerance_hrs'])?$airCompParts['airframe_component_last_cw'][0]['tolerance_hrs']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tolerance_afl">Cycles
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('tolerance_afl', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['tolerance_afl'])?$airCompParts['airframe_component_last_cw'][0]['tolerance_afl']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="itemCls">
                                            <div class="form-group" style="padding-top: 33px;"></div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alert_days">Days
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php 
                                                    
                                                    echo $this->Form->control('alert_days', array('class' => 'form-control col-md-7 col-xs-12', 'label'=>false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['alert_days']) ? $airCompParts['airframe_component_last_cw'][0]['alert_days'] : 30, 'autocomplete'=>'off')); 
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alert_hrs">Hours
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('alert_hrs', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['alert_hrs']) ? $airCompParts['airframe_component_last_cw'][0]['alert_hrs'] : 50, 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alert_afl">Cycles
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('alert_afl', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['alert_afl']) ? $airCompParts['airframe_component_last_cw'][0]['alert_afl'] : 25, 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="addPageHeading">
                                        <div class="col-md-3 col-sm-3 col-xs-12" style="top: -5px;">
                                            Recurring <input type="radio" name="is_recThres" value="recurring" id="recurringId" <?php if(!empty($isResThres) && $isResThres == 'recurring') echo "checked"; ?>>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12" style="top: -5px;">
                                            Threshold <input type="radio" name="is_recThres" value="threshold" id="thresholdId" <?php if(!empty($isResThres) && $isResThres == 'threshold') echo "checked"; ?>>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">Interval</div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">Adjustment</div>
                                    </div>
                                    <div style="clear: both;"></div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="recurring_mos">Months
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('recurring_mos', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['recurring_mos'])?$airCompParts['airframe_component_last_cw'][0]['recurring_mos']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="recurring_days">Days
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('recurring_days', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['recurring_days'])?$airCompParts['airframe_component_last_cw'][0]['recurring_days']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="recurring_hrs">Hours
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('recurring_hrs', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['recurring_hrs'])?$airCompParts['airframe_component_last_cw'][0]['recurring_hrs']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="recurring_afl">Cycles
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('recurring_afl', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['recurring_afl'])?$airCompParts['airframe_component_last_cw'][0]['recurring_afl']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="threshold_mos">Months
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('threshold_mos', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['threshold_mos'])?$airCompParts['airframe_component_last_cw'][0]['threshold_mos']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="threshold_days">Days
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('threshold_days', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['threshold_days'])?$airCompParts['airframe_component_last_cw'][0]['threshold_days']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="threshold_hrs">Hours
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('threshold_hrs', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['threshold_hrs'])?$airCompParts['airframe_component_last_cw'][0]['threshold_hrs']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="threshold_afl">Cycles
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('threshold_afl', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['threshold_afl'])?$airCompParts['airframe_component_last_cw'][0]['threshold_afl']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="required_frequency_mos">Months
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('required_frequency_mos', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['required_frequency_mos'])?$airCompParts['airframe_component_last_cw'][0]['required_frequency_mos']:'', 'autocomplete'=>'off', 'readonly' => 'readonly')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="required_frequency_days">Days
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('required_frequency_days', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['required_frequency_days'])?$airCompParts['airframe_component_last_cw'][0]['required_frequency_days']:'', 'autocomplete'=>'off', 'readonly' => 'readonly')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="required_frequency_hrs">Hours
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('required_frequency_hrs', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['required_frequency_hrs'])?$airCompParts['airframe_component_last_cw'][0]['required_frequency_hrs']:'', 'autocomplete'=>'off', 'readonly' => 'readonly')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="required_frequency_afl">Cycles
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('required_frequency_afl', array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['required_frequency_afl'])?$airCompParts['airframe_component_last_cw'][0]['required_frequency_afl']:'', 'autocomplete'=>'off', 'readonly' => 'readonly')); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adjustment_mos">Months
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('adjustment_mos', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['adjustment_mos'])?$airCompParts['airframe_component_last_cw'][0]['adjustment_mos']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adjustment_days">Days
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('adjustment_days', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['adjustment_days'])?$airCompParts['airframe_component_last_cw'][0]['adjustment_days']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adjustment_hrs">Hours
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('adjustment_hrs', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['adjustment_hrs'])?$airCompParts['airframe_component_last_cw'][0]['adjustment_hrs']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="adjustment_afl">Cycles
                                                </label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <?php echo $this->Form->control('adjustment_afl', array('class' => 'form-control col-md-7 col-xs-12 keypress', 'placeholder' => '', 'label' => false, 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['adjustment_afl'])?$airCompParts['airframe_component_last_cw'][0]['adjustment_afl']:'', 'autocomplete'=>'off')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div style="clear: both;"></div>

                                <div>
                                    <div class="addPageHeading">
                                        <div class="col-md-3 col-sm-3 col-xs-12">Part Information</div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">Times Since (at Installed)</div>
                                        <div class="col-md-3 col-sm-3 col-xs-12">Additional Information</div>
                                    </div>
                                    <div class="editPartInfoCls">
                                        <div class="itemCls">
                                            Part Number
                                        </div>

                                        <div class="itemCls">
                                            <?php echo !empty($airCompParts['part_number']) ? $airCompParts['part_number'] : ''; ?>
                                        </div>

                                        <div class="itemCls">
                                        </div>

                                        <div class="itemCls">
                                            New
                                        </div>

                                        <div class="itemCls">
                                            Overhaul
                                        </div>

                                        <div class="itemCls">
                                            Repair
                                        </div>

                                        <div class="itemCls">
                                        Work Card
                                        </div>

                                        <div class="itemCls">
                                        <?php echo $this->Form->control('work_card', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false)); ?>
                                        </div>
                                    </div>

                                    <div class="editPartInfoCls">
                                        <div class="itemCls">
                                            Serial Number
                                        </div>

                                        <div class="itemCls">
                                            <?php echo !empty($airCompParts['serial_number']) ? $airCompParts['serial_number'] : ''; ?>
                                        </div>

                                        <div class="itemCls">
                                            Months
                                        </div>

                                        <div class="itemCls">
                                            <?php echo (!empty($airCompParts['part_installed_times'][0]['new_months']) || (isset($airCompParts['part_installed_times'][0]['new_months']) && $airCompParts['part_installed_times'][0]['new_months'] == 0)) ? $airCompParts['part_installed_times'][0]['new_months'] : ''; ?>
                                        </div>

                                        <div class="itemCls">
                                           <?php echo (!empty($airCompParts['part_installed_times'][0]['overhaul_months']) || (isset($airCompParts['part_installed_times'][0]['overhaul_months']) && $airCompParts['part_installed_times'][0]['overhaul_months'] == 0)) ? $airCompParts['part_installed_times'][0]['overhaul_months'] : ''; ?>
                                        </div>

                                        <div class="itemCls">
                                            <?php echo (!empty($airCompParts['part_installed_times'][0]['repair_months']) || (isset($airCompParts['part_installed_times'][0]['repair_months']) && $airCompParts['part_installed_times'][0]['repair_months'] == 0)) ? $airCompParts['part_installed_times'][0]['repair_months'] : ''; ?>
                                        </div>

                                        <div class="itemCls">
                                        Position
                                        </div>

                                        <div class="itemCls">
                                        <?php echo $this->Form->control('position', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false)); ?>
                                        </div>
                                    </div>

                                    <div class="editPartInfoCls">
                                        <div class="itemCls">
                                        </div>

                                        <div class="itemCls">
                                        </div>

                                        <div class="itemCls">
                                            Hours
                                        </div>

                                        <div class="itemCls">
                                            <?php echo (!empty($airCompParts['part_installed_times'][0]['new_hours']) || (isset($airCompParts['part_installed_times'][0]['new_hours']) && $airCompParts['part_installed_times'][0]['new_hours'] == 0)) ? $airCompParts['part_installed_times'][0]['new_hours'] : ''; ?>
                                        </div>

                                        <div class="itemCls">
                                           <?php echo (!empty($airCompParts['part_installed_times'][0]['overhaul_hours']) || (isset($airCompParts['part_installed_times'][0]['overhaul_hours']) && $airCompParts['part_installed_times'][0]['overhaul_hours'] == 0)) ? $airCompParts['part_installed_times'][0]['overhaul_hours'] : ''; ?>
                                        </div>

                                        <div class="itemCls">
                                            <?php echo (!empty($airCompParts['part_installed_times'][0]['repair_hours']) || (isset($airCompParts['part_installed_times'][0]['repair_hours']) && $airCompParts['part_installed_times'][0]['repair_hours'] == 0)) ? $airCompParts['part_installed_times'][0]['repair_hours'] : ''; ?>
                                        </div>

                                        <div class="itemCls">
                                            Version
                                        </div>

                                        <div class="itemCls">
                                        <?php echo $this->Form->control('version', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false)); ?>
                                        </div>
                                    </div>

                                    <div class="editPartInfoCls">
                                        <div class="itemCls">
                                        </div>

                                        <div class="itemCls">
                                        </div>

                                        <div class="itemCls">
                                            Landings
                                        </div>

                                        <div class="itemCls">
                                            <?php echo (!empty($airCompParts['part_installed_times'][0]['new_landings']) || (isset($airCompParts['part_installed_times'][0]['new_landings']) && $airCompParts['part_installed_times'][0]['new_landings'] == 0)) ? $airCompParts['part_installed_times'][0]['new_landings'] : ''; ?>
                                        </div>

                                        <div class="itemCls">
                                           <?php echo (!empty($airCompParts['part_installed_times'][0]['overhaul_landings']) || (isset($airCompParts['part_installed_times'][0]['overhaul_landings']) && $airCompParts['part_installed_times'][0]['overhaul_landings'] == 0)) ? $airCompParts['part_installed_times'][0]['overhaul_landings'] : ''; ?>
                                        </div>

                                        <div class="itemCls">
                                            <?php echo (!empty($airCompParts['part_installed_times'][0]['repair_landings']) || (isset($airCompParts['part_installed_times'][0]['repair_landings']) && $airCompParts['part_installed_times'][0]['repair_landings'] == 0)) ? $airCompParts['part_installed_times'][0]['repair_landings'] : ''; ?>
                                        </div>

                                        <div class="itemCls">
                                        Last Revised By
                                        </div>

                                        <div class="itemCls">
                                        <?php
                                        $lastRevisedBy = !empty($airCompParts['airframe_component_last_cw'][0]['last_revised_by']) ? trim($airCompParts['airframe_component_last_cw'][0]['last_revised_by']) : '';
                                        $revisedBy = $airCPComp->getUserName($lastRevisedBy);
                                        if(!empty($revisedBy)) {
                                            $lastRevisedBy = $revisedBy;
                                        }

                                        echo $this->Form->control('last_revised_by', array('class' => 'form-control col-md-7 col-xs-12', 'label' => false, 'value'=>$lastRevisedBy, 'readonly'=>'readonly')); 
                                        ?>
                                        </div>
                                    </div>

                                    <div class="editPartInfoCls">
                                        <div class="itemCls">
                                        </div>

                                        <div class="itemCls">
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

                                <div style="clear: both;"></div>

                                <div>
                                    <div style="background: #99a1a6; font-weight: bold; padding: 2px; margin: 5px;">Admin Notes</div>
                                    <div class="form-group">
                                        <label class="col-md-1 col-sm-1 col-xs-12" for="admin_notes">Notes
                                        </label>
                                        <div class="col-md-11 col-sm-11 col-xs-12">
                                            <?php echo $this->Form->control('admin_notes', array('class' => 'form-control', 'label' => false, 'rows'=>2)); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="ln_solid"></div>
                                <?php echo $this->Form->control('updated_by', array('type' => 'hidden', 'value' => $sessionArray['id'], 'label'=> false)); ?>

                                <?php echo $this->Form->control('airframe_component_last_cw_id', array('type' => 'hidden', 'value'=>!empty($airCompParts['airframe_component_last_cw'][0]['id'])?$airCompParts['airframe_component_last_cw'][0]['id']:'', 'label'=> false)); ?>
                            </div>

                            <div id="subItems" class="tab-pane fade">
                                <div class="partsGpCls">
                                    <?php
                                    if(!empty($airCompParts['group'])) {
                                    ?>
                                        <div class="gpExistCls">
                                            <div class="form-group">
                                                <div class="col-md-2 col-sm-2 col-xs-12" style="padding-left: 20px;">Group Name</div>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    <?php echo ucfirst($airCompParts['group']['group_name']); ?>
                                                </div>

                                                <div class="col-md-3 col-sm-3 col-xs-12 displayGroup"></div>
                                                
                                                <?php
                                                if((!empty($actionItems) && $actionItems['action']['action_edit']==1) || $sessionUser['id'] == 1){
                                                ?>
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <?php if(!empty($subResults)) { ?>
                                                    <input type="button" id="downloadPdfId" value="Generate Report" class="btn btn-primary pull-right" data-pid="<?php echo $airCompParts['plane_id']; ?>" data-partid="<?php echo $airCompParts['id']; ?>" data-typ="<?php echo $typ; ?>" data-act="<?php echo $act; ?>" style="margin-right: 16px;">
                                                    <?php } ?>

                                                    <input type="button" value="Edit Group" class="btn btn-primary pull-right gpBtnCls" data-planeid="<?php echo $airCompParts['plane_id']; ?>" data-compid="<?php echo $airCompParts['airframe_component_id']; ?>" data-partid="<?php echo $airCompParts['id']; ?>" data-groupid="<?php echo $airCompParts['group']['id']; ?>" style="margin-right: 16px;">
                                                </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="gpNotExistCls">
                                            <div class="form-group">
                                                <div class="col-md-2 col-sm-2 col-xs-12" style="padding-left: 20px;">No Group Assigned</div>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                </div>

                                                <div class="col-md-3 col-sm-3 col-xs-12 displayGroup"></div>

                                                <?php
                                                if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1){
                                                ?>
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <input type="button" value="Create Group" class="btn btn-primary pull-right gpBtnCls" data-planeid="<?php echo $airCompParts['plane_id']; ?>" data-compid="<?php echo $airCompParts['airframe_component_id']; ?>" data-partid="<?php echo $airCompParts['id']; ?>" data-groupid="<?php echo $airCompParts['group']['id']; ?>" style="margin-right: 16px;">
                                                </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div style="clear: both;"></div>
                                <div class="action-bar">
                                    <div class="actionbar-lft">
                                        <div class="lftWrap">
                                            <?php echo $this->element('search_form', array('title'=>'edit')); ?>
                                            <?php echo $this->element('sort_by', array('title'=>'edit')); ?>
                                        </div>

                                        <div class="split-btn pull-right actionMenu sortWrap">
                                        <?php
                                        if(!empty($airCompParts['group'])) {
                                        ?>
                                            <button class="btn-dropdown btn-default">Action<span class="selectCount"></span></button>
                                            <button class="icon-part dropdown-toggle actionCls" data-toggle="dropdown">
                                                <i class="fa fa-caret-down"></i>
                                            </button>
                                            <?php
                                            if((!empty($actionItems) && $actionItems['action']['action_delete']==1) || $sessionUser['id'] == 1){
                                            ?>                               
                                            <div class="dropdown-content dropdown-menu actionLinks" style="pointer-events: none;">
                                                <a href="javascript:void(0);" data-parentid="<?php echo $airCompParts['id']; ?>" class="resetPartCls">Reset</a>
                                            </div>
                                            <?php
                                            }
                                            ?>
                                            
                                            <?php
                                            if((!empty($actionItems) && $actionItems['action']['action_add']==1) || $sessionUser['id'] == 1){
                                            ?>
                                            <a href="javascript:void(0);" class="btn btn-default ml-10" data-planeid="<?php echo $airCompParts['plane_id']; ?>" data-partid="<?php echo $airCompParts['id']; ?>" data-groupid="<?php echo $airCompParts['group']['id']; ?>" id="allPartList">Add</a>
                                            <?php
                                            }
                                            ?>
                                        <?php
                                        }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                                <div style="clear: both;"></div>
                                <div class="table-responsive">
                                    <table id="customReport" class="table mb-0" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="4%" class="check"><input type="checkbox" name="air_check" id="ckbCheckAll"></th>
                                                <th width="4%">Aircraft</th>
                                                <th width="4%">ATA</th>
                                                <th width="14%">Reference & Component & Item Type</th>
                                                <th width="20%">Description</th>
                                                <th width="11%">Current Hr/Cy</th>
                                                <th width="10%">Last C/W</th>
                                                <th width="8%">Intervals</th>
                                                <th width="10%">Next Due</th>
                                                <th width="8%">Remaining</th>
                                                <th width="7%">Status</th>
                                                <th width="0%"></th>
                                                <th width="0%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="aircraftPartsList">
                                            <?php
                                            $subPartIds = array();
                                            foreach ($subResults as $row) {
                                                $subPartIds[] = $row['partDet']['id'];                       
                                                echo $this->element('partslist', array('row'=>$row, 'typ'=>$typ, 'act'=>$act));
                                            } 
                                            ?>
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="subPartIds" id="subPartIds" data-subpartids="<?= h(implode(",", $subPartIds)); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabs end -->
            </div>
        </div>
    <?php 
    echo $this->Form->end(); 
    ?>
    </div>
</div>

<!-- Report time popup -->
<?php echo $this->element('report_time_popup'); ?>
<!-- Report time popup -->

<?php echo $this->Html->script('parts'); ?>
<script> 
var getComponents = "<?php echo Router::url(['controller'=>'AirframeComponents', 'action'=>'getComponents']); ?>";   

$(document).ready(function() {
    //Enable/disable form and few buttons
    $("#frmAirCompPart :input").prop("disabled", true);
    $('#frmAirCompPart .actionCls').prop("disabled", false);
    $('#frmAirCompPart .gpBtnCls').prop("disabled", false);
    $(document).on("click", ".reviseCls", function() {
        $("#frmAirCompPart :input").prop("disabled", false);
        $('.selectpicker').selectpicker('refresh');
        $(this).text('Disable Revise');
        $(this).addClass('reviseDCls').removeClass('reviseCls');
    });

    $(document).on("click", ".reviseDCls", function() {
        $("#frmAirCompPart :input").prop("disabled", true);
        $('#frmAirCompPart .actionCls').prop("disabled", false);
        $('#frmAirCompPart .actionCls button.selectpicker').prop("disabled", false);
        $('#frmAirCompPart .gpBtnCls').prop("disabled", false);
        $(this).text('Enable Revise');
        $(this).addClass('reviseCls').removeClass('reviseDCls');
    });
    //Enable/disable form

    /*** If compliance date, hours and cycles are changed ***/
    //Change compliance date
    $("#cwDatepicker").datetimepicker({
        useCurrent: false,
        format: 'MM-DD-YYYY'
    }).on('dp.change', function(e) {
        var a = moment(e.date._d);
        var lastCwMos = a.format('M-D-Y');
        var thisVal = $('input[name="is_recThres"]').val();

        if ($('input#overrideNextDue').is(':checked')) {
            updateNextRemDuesIfOverride(lastCwMos, thisVal);
        } else {
            updateNextRemDues(lastCwMos, thisVal);
        }
    });
    /*** If compliance date, hours and cycles are changed ***/

    //Change next due, remaining and interval values based on recurring or threshold selected
    $('input[name="is_recThres"]').on('change', function() {
        var lastCwMos = $('#cwDatepicker').val();
        var thisVal = $(this).val();
        
        if ($('input#overrideNextDue').is(':checked')) {
            updateNextRemDuesIfOverride(lastCwMos, thisVal);
        } else {
            updateNextRemDues(lastCwMos, thisVal);
        }
    });

    //Change next due and remaining values based on recurring, threshold or adjustment values change
    $(".keypress").keyup(function() {
        var lastCwMos = $('#cwDatepicker').val();
        var thisVal = $('input[name=is_recThres]:checked').val();

        if ($('input#overrideNextDue').is(':checked')) {
            updateNextRemDuesIfOverride(lastCwMos, thisVal);
        } else {
            updateNextRemDues(lastCwMos, thisVal);
        }
    });

    //Override next due
    $('.overrideEom').on('click', function() {
        var partId = $('#part_id').val(); 
        var isOverride = $('input#overrideNextDue').is(':checked');
        var isEom = $('input#eomNextDue').is(':checked');
        var lastCwMos = $('#cwDatepicker').val();
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
            //Change next due & remaining
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(['controller'=>'AirframeComponentParts', 'action'=>'changeNextdueRem']); ?>",
                data: {
                    partId: partId, 
                    isOverride: isOverride, 
                    isEom: isEom 
                },
                async: true,
                success: function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        if(obj.data.nextMos != '') {
                            $('#next-due-date').val(obj.data.nextMos).prop('readonly', false);
                        } else if($('input#eomNextDue').is(':checked')) {
                            $('#next-due-date').prop('readonly', false);
                        } else { 
                            $('#next-due-date').val('').prop('readonly', false);
                        }

                        if(obj.data.nextMos != '') {
                            $('#next-due-hrs').val(obj.data.nextHrs).prop('readonly', false);
                        } else if($('input#eomNextDue').is(':checked')) {
                            $('#next-due-hrs').prop('readonly', false);
                        } else {
                            $('#next-due-hrs').val('').prop('readonly', false);
                        }

                        if(obj.data.nextMos != '') {
                            $('#next-due-afl').val(obj.data.nextAfl).prop('readonly', false);
                        } else if($('input#eomNextDue').is(':checked')) {
                            $('#next-due-afl').prop('readonly', false);
                        } else {
                            $('#next-due-afl').val('').prop('readonly', false);
                        }
                        
                        if(obj.data.override == 1) {
                            $('#remMos').html(obj.data.remMos);
                            $('#remDays').html(obj.data.remDays);
                            $('#remHrs').html(obj.data.remHrs);
                            $('#remAfl').html(obj.data.remAfl);
                        }
                    }
                }                   
            });
        } else {
            //Change next due & remaining
            updateNextRemDues(lastCwMos, thisVal);
            $('#next-due-date').prop('readonly', true);
            $('#next-due-hrs').prop('readonly', true);
            $('#next-due-afl').prop('readonly', true);
        }
    });

    //Common function to update next and remaining dues if Override checked
    function updateNextRemDuesIfOverride(lastCwMos, thisVal) {
        if (thisVal == 'recurring') {
            $('#required-frequency-mos').val($('#recurring-mos').val());
            $('#required-frequency-days').val($('#recurring-days').val());
            $('#required-frequency-hrs').val($('#recurring-hrs').val());
            $('#required-frequency-afl').val($('#recurring-afl').val());

            //Change Alert value
            if($('#recurring-mos').val() > 24) {
                $('#alert-days').val(60);
            } else {
                $('#alert-days').val(30);
            }

            if($('#recurring-hrs').val() > 999) {
                $('#alert-hrs').val(200);
            } else {
                $('#alert-hrs').val(50);
            }

            if($('#recurring-afl').val() > 999) {
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
            if($('#threshold-mos').val() > 24) {
                $('#alert-days').val(60);
            } else {
                $('#alert-days').val(30);
            }

            if($('#threshold-hrs').val() > 999) {
                $('#alert-hrs').val(200);
            } else {
                $('#alert-hrs').val(50);
            }

            if($('#threshold-afl').val() > 999) {
                $('#alert-afl').val(200);
            } else {
                $('#alert-afl').val(25);
            }              
        }

        var data = {                    
                    partId: $('#part_id').val(),
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
                    isEom:      $('input#eomNextDue').is(':checked'),
                    lastCwMos:  lastCwMos,
                    lastCwHrs:  $('#last-cw-hrs').val(),
                    lastCwAfl:  $('#last-cw-afl').val()
                };

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'AirframeComponentParts', 'action'=>'changeNextdueRem']); ?>",
            data: data,
            async: true,
            success: function(response) {
                console.log(response);
                var obj = JSON.parse(response);
                if(obj.status == 'success') {

                    if($('#next-due-date').val() == '' && $('#next-due-hrs').val() == '' && $('#next-due-afl').val() == '') {
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
                }
            }                   
        });
    }

    //Common function to update next and remaining dues
    function updateNextRemDues(lastCwMos, thisVal) {
        if (thisVal == 'recurring') {
            $('#required-frequency-mos').val($('#recurring-mos').val());
            $('#required-frequency-days').val($('#recurring-days').val());
            $('#required-frequency-hrs').val($('#recurring-hrs').val());
            $('#required-frequency-afl').val($('#recurring-afl').val());

            //Change Alert value
            if($('#recurring-mos').val() > 24) {
                $('#alert-days').val(60);
            } else {
                $('#alert-days').val(30);
            }

            if($('#recurring-hrs').val() > 999) {
                $('#alert-hrs').val(200);
            } else{
                $('#alert-hrs').val(50);
            }

            if($('#recurring-afl').val() > 999) {
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
            if($('#threshold-mos').val() > 24) {
                $('#alert-days').val(60);
            } else {
                $('#alert-days').val(30);
            }

            if($('#threshold-hrs').val() > 999) {
                $('#alert-hrs').val(200);
            } else {
                $('#alert-hrs').val(50);
            }

            if($('#threshold-afl').val() > 999) {
                $('#alert-afl').val(200);
            } else {
                $('#alert-afl').val(25);
            }              
        }

        var data = {
                    partId:     $('#part_id').val(),
                    intvMos:    $('#required-frequency-mos').val(),
                    intvDay:    $('#required-frequency-days').val(),
                    intvHrs:    $('#required-frequency-hrs').val(),
                    intvAfl:    $('#required-frequency-afl').val(),
                    intvAdjMos: $('#adjustment-mos').val(),
                    intvAdjDay: $('#adjustment-days').val(),
                    intvAdjHrs: $('#adjustment-hrs').val(),
                    intvAdjAfl: $('#adjustment-afl').val(),
                    isOverride: $('input#overrideNextDue').is(':checked'),
                    isEom:      $('input#eomNextDue').is(':checked'),
                    lastCwMos:  lastCwMos,
                    lastCwHrs:  $('#last-cw-hrs').val(),
                    lastCwAfl:  $('#last-cw-afl').val()
                };

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'AirframeComponentParts', 'action'=>'changeNextdueRem']); ?>",
            data: data,
            async: true,
            success: function(response) {
                //console.log(response);
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#next-due-date').val(obj.data.nextMos);
                    $('#next-due-hrs').val(obj.data.nextHrs);
                    $('#next-due-afl').val(obj.data.nextAfl);
                    $('#remMos').html(obj.data.remMos);
                    $('#remDays').html(obj.data.remDays);
                    $('#remHrs').html(obj.data.remHrs);
                    $('#remAfl').html(obj.data.remAfl);
                }
            }                   
        });
    }

    //Remaining update if custom nextdue change
    $('.remUpdate').keyup(function() {
        if($('#next-due-hrs').val() != '') {
            var hrs = $('#next-due-hrs').val() - $('#compHours').val();
            $('#remHrs').html(hrs.toFixed(2));
        } else {
            $('#remHrs').html('');
        }

        if($('#next-due-afl').val() != '') {
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
        var partId = $('#part_id').val();
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(['controller'=>'AirframeComponentParts', 'action'=>'updateRemaining']); ?>",
            data: {nextMos: date},
            async: true,
            success: function(response) {
                console.log(response);
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#remMos').html(obj.data.remMos);
                    $('#remDays').html(obj.data.remDays);
                }
            }                   
        });
    });

    /*********************** Sub Items Script *****************/
    //Checkbox script to count past due, tolerance and coming due
    $("#ckbCheckAll").click(function () {
        $(".chkBoxCls").prop('checked', $(this).prop('checked'));

        var vals = [];
        $("input.chkBoxCls:visible:checked").each(function() {
            vals.push($(this).val());
        });

        //Add part ids to generate pdf
        $('#downloadPdfId').data('partids',vals);

        //Add search value to generate pdf
        $('#downloadPdfId').data('searchval', $('#searchItem').val().toLowerCase());

        //Part ids to delete parts
        var checkedcount = $('input.chkBoxCls:visible:checked').length;
        $('.removePartCls').data('partids', vals);
        $('.resetPartCls').data('partids', vals);
        if(checkedcount >= 1) {
            $('.removePartCls').data('aircounts', '');
            $('.removePartCls').css('pointer-events', 'auto');
            $('.resetPartCls').css('pointer-events', 'auto');
            $('.selectCount').html("("+checkedcount+")");
        } else {
            $('.removePartCls').data('aircounts', '');
            $('.removePartCls').css('pointer-events', 'none');
            $('.resetPartCls').css('pointer-events', 'none');
            $('.selectCount').html('');
        }
    });

    $(".chkBoxCls").click(function () {
        var totalCheckboxes = $('input.chkBoxCls:checkbox').length;
        var checkedcount = $('input.chkBoxCls:checked').length;
        
        if(totalCheckboxes == checkedcount) {
            $('#ckbCheckAll').prop('checked', true);
        } else {
            $('#ckbCheckAll').prop('checked', false);
        }
                
        var vals = [];
        $("input.chkBoxCls:checked").each(function() {
            vals.push($(this).val());
        });

        //Add part ids to generate pdf
        $('#downloadPdfId').data('partids',vals);

        //Part ids to delete parts
        $('.removePartCls').data('partids', vals);
        $('.resetPartCls').data('partids', vals);
        if(checkedcount >= 1) {
            $('.removePartCls').data('aircounts', '');
            $('.removePartCls').css('pointer-events', 'auto');
            $('.resetPartCls').css('pointer-events', 'auto');
            $('.selectCount').html("("+checkedcount+")");
        } else {
            $('.removePartCls').data('aircounts', '');
            $('.removePartCls').css('pointer-events', 'none');
            $('.resetPartCls').css('pointer-events', 'none');
            $('.selectCount').html('');
        }
    });
    //Checkbox script to count past due, tolerance and coming due

    //Change sorting dynamically
    var oTable = $('#customReport').DataTable({
        "scrollY": $(window).height()/1.70,
        "scrollCollapse": true,
        "searching": false,
        "paging": false,
        "info": false,
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": 0 },
            { "orderData": [ 11 ], "targets": [ 10 ] },
            { "orderData": [ 12 ], "targets": [ 9 ] }, 
            { "visible": false, "targets": [ 11,12 ] }
        ],
        "order": []
    });

    new $.fn.dataTable.FixedHeader( oTable );

    $("select#sortById").change(function() {
        var val = $(this).prop('selectedIndex') + 1;
        oTable.order( [ val, 'asc' ] )
        .draw();
    });
    
    //jQuery custom search
    $("#searchItem").on("keyup", function() {
        var value = $(this).val().toLowerCase();

        /**************** 10/12/2020 ******************/
        //Add search value to generate pdf
        $('#downloadPdfId').data('searchval',value);
        var vals = [];
        $("input.chkBoxCls:visible").each(function() {
            vals.push($(this).val());
        });

        //Add part ids to generate pdf
        $('#downloadPdfId').data('partids',vals);
        /**********************************/
            
        $("#aircraftPartsList tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    //Download pdf
    $('#downloadPdfId').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var typ = $(this).data('typ');
        var act = $(this).data('act');
        var pids = $(this).data('pid') || [];
        var partids = $(this).data('partids');
        //console.log(partids);
        if(partids == '' || typeof partids === 'undefined') {
            //console.log('here');
            partids = $('#subPartIds').data('subpartids') || [];
        }

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'generateMultiAircraftPdf']); ?>",
            data: {pids:pids, partids:partids, type:typ, action:act },
            beforeSend: function () {
                $('.loader').show();
            },
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('.loader').hide();
                    window.open(obj.data);
                } else {
                    $('.loader').hide();
                    alert('Some error occured. Please try again!');
                }
            },
            error : function() {
                $('.loader').hide();
                alert('Some error occured. Please try again!');
            },
            complete: function () {
                $('.loader').hide();
            }
        });
    });

    //Parent/Child details popup
    $(document).on("click", ".prChildCls", function() {
        var airid = $(this).data('airid');
        var pid   = $(this).data('pid');
        var type  = $(this).data('type');
        var cid   = $(this).data('cid');
        var typ   = $(this).data('typ');
        var act   = $(this).data('act');
        
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'parentChildInfo']); ?>",
            data: {airid: airid, pid: pid, type: type, cid:cid, typ:typ, act:act},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                $('.loader').hide();
                $('#parentChildModel .modal-content').html(response);
                $('#parentChildModel').modal('show');
            },
            error : function() {
                $('.loader').hide();
                $('#parentChildModel .modal-content').html('');
                $('#parentChildModel').modal('hide');
            }
        });
    });
    /*********************** End Sub Items Script *****************/

    $('#currentTimeId').css('pointer-events', 'none'); 

    //addCompliance form
    $(document).on("click", ".addCompliance", function() {
        var partId  = $(this).data('part_id');
        var planeId  = $(this).data('plane_id');
        
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Compliance', 'action'=>'addCompliance']);?>">');
        form.append('<input type="hidden" name="aircraftId" value="'+planeId+'">');
        form.append('<input type="hidden" name="partId" value="'+partId+'">');
        form.append('<input type="hidden" name="ftype" value="initial">');
        $('body').append(form);
        form.submit();
    });

    //Error-Correct form
    $(document).on("click", ".errorCorrect", function() {
        var partId  = $(this).data('part_id');
        var planeId  = $(this).data('plane_id');
        
        var form = $('<form method="GET" action="<?php echo $this->Url->build(['controller'=>'Compliance', 'action'=>'errorCorrection']);?>">');
        form.append('<input type="hidden" name="aircraftId" value="'+planeId+'">');
        form.append('<input type="hidden" name="partId" value="'+partId+'">');
        $('body').append(form);
        form.submit();
    });

    //Group popup
    $(document).on('click', '.gpBtnCls', function() {
        var planeId = $(this).data('planeid');
        var compId = $(this).data('compid');
        var groupId = $(this).data('groupid');
        var partId = $(this).data('partid');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'AirframeComponentParts', 'action'=>'getGroupRecord']); ?>",
            data: {planeId: planeId, compId: compId, partId: partId, groupId: groupId},
            async: true,
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#groupModel .modal-body').html(obj.data);
                    $('#groupModel .gpTypeCls').html(obj.type);
                    $('#groupModel').modal('show');
                } else {
                    alert('No data exists.');
                }
            }
        });
    });

    //Save group details
    $(document).on('click', '#saveGpBtn', function(e) {
        e.stopPropagation();
        e.preventDefault();
        
        if($('#groupFrm').valid()) {
            var data = $('#groupFrm').serialize();
            $.ajax({
                url : "<?php echo $this->Url->build(['controller'=>'AirframeComponentParts', 'action'=>'addGroup']); ?>",
                type : 'post',
                data : data,
                async: true,
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('.displayGroup').html(obj.data);
                        $('#errorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        location.reload(true);
                    } else {
                        $('#errorMsg').html('<span style="color:red;">'+obj.message+'</span>');
                    }                   
                },
                error : function() {
                    alert('Some error occured. Please try again!');
                    $('#groupModel').modal('hide');
                },
                complete: function () {
                    $('#groupModel').modal('hide');
                }
            });
        }
    });

    //Validation on group form
    $("#groupFrm").validate({
        ignore: "input[type='text']:hidden",
        validateHiddenInputs: false,
        rules: {
            'group_name': {
                required: true
            }
        },
        messages: {
            'group_name': {
                required: "Please enter group name."
            }
        }
    });

    /*** Parts list popup ***/
    //Parts List Popup
    $(document).on('click', '#allPartList', function() {
        var planeId = $(this).data('planeid');
        var partId = $(this).data('partid');
        var groupId = $(this).data('groupid');

        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'AirframeComponentParts', 'action'=>'getPartsRecord']); ?>",
            data: {planeId: planeId, partId: partId, groupId:groupId},
            async: true,
            beforeSend: function () {
                $('.loader').show();
            },
            success: function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#partsListModel .modal-body').html(obj.data);
                    $('#partsListModel .gpTypeCls').html(obj.type);
                    $('#partsListModel').modal('show');
                    $('.loader').hide();
                } else {
                    $('.loader').hide();
                    alert('No data exists.');
                }
            }
        });
    });

    //Initiate popup model after html load
    $(document).on('show.bs.modal','.modal', function () {
        //Dropdown select
        $("#sortByIdPopup").selectpicker();

        //Change sorting dynamically
        var oTable = $('#customReportPopup').DataTable({
            "searching": false,
            "paging": false,
            "info": false,
            "columnDefs": [
                { "orderable": false, "targets": 0 }
            ],
            "order": []
        });

        //Initialised 2nd time
        //new $.fn.dataTable.FixedHeader( oTable );

        $("select#sortByIdPopup").change(function() {
            var val = $(this).prop('selectedIndex') + 1;
            oTable.order( [ val, 'asc' ] )
            .draw();
        });
        
        //jQuery custom search
        $("#searchItemPopup").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#aircraftPartsListPopup tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        //Checkbox script to select/unselect all records
        $("#ckbCheckAllPopup").click(function () {
            $(".chkBoxClsPopup").prop('checked', $(this).prop('checked'));

            var vals = [];
            $("input.chkBoxClsPopup:checked").each(function() {
                vals.push($(this).val());
            });
            $('#selPartIds').data('childpartids',vals);
        });

        $(".chkBoxClsPopup").click(function () {
            var totalCheckboxes = $('input.chkBoxClsPopup:checkbox').length;
            var checkedcount = $('input.chkBoxClsPopup:checked').length;
            
            if(totalCheckboxes == checkedcount) {
                $('#ckbCheckAllPopup').prop('checked', true);
            } else {
                $('#ckbCheckAllPopup').prop('checked', false);
            }
                    
            var vals = [];
            $("input.chkBoxClsPopup:checked").each(function() {
                vals.push($(this).val());
            });
            $('#selPartIds').data('childpartids',vals);
        });
        //Checkbox script

        //Add child items to parent
        $(document).on('click', '.addToPBtn', function(e) {
            e.stopPropagation();
            e.preventDefault();

            var btnVal = $(this).val();
            var planeId = $('#selPlaneId').data('planeid');
            var parentId = $('#selParentId').data('parentid');
            var groupId = $('#selGroupId').data('groupid');
            var partIds = $('#selPartIds').data('childpartids');
            
            $.ajax({
                url : "<?php echo $this->Url->build(['controller'=>'AirframeComponentParts', 'action'=>'addToParent']); ?>",
                type : 'post',
                data : {planeId:planeId, parentId: parentId, groupId:groupId, partIds: partIds},
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#addToPEMsg').html('<span style="color:green;">'+obj.message+'</span>').hide(5000);
                        if(btnVal != 'Add') {
                            location.reload(true);
                        }
                    } else {
                        $('#addToPEMsg').html('<span style="color:red;">'+obj.message+'</span>').hide(5000);
                    }                    
                },
                error : function() {
                    alert('Some error occured. Please try again!');
                    $('#partsListModel').modal('hide');
                }
            });
        });
    });
    /*** Parts list popup ***/

    //Delete parts
    $('.removePartCls').on('click', function() {
        var pids = $(this).data('pids') || [];
        var partids = $(this).data('partids');
        var aircounts = $(this).data('aircounts');

        if (confirm('Are you sure you want to delete this?')) {
            $.ajax({
                url : "<?php echo $this->Url->build(['controller'=>'AirframeComponentParts', 'action'=>'deleteMultiParts']); ?>",
                type : 'post',
                data : {pids: pids, partids: partids, aircounts: aircounts},
                success:function(response) {
                    var obj = JSON.parse(response);
                    if(obj.status == 'success') {
                        $('#delErrorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                        //location.reload(true);
                        setTimeout(function() {
                            window.location.href = "javascript:history.back()";
                        }, 1000);
                    } else {
                        $('#delErrorMsg').html('<span style="color:red;">'+obj.message+'</span>').hide(5000);
                    }                  
                }
            });
        }
    });

    //Reset parts status
    $('.resetPartCls').on('click', function() {
        var partIds = $(this).data('partids');
        var parentId = $(this).data('parentid');

        $.ajax({
            url : "<?php echo $this->Url->build(['controller'=>'AirframeComponentParts', 'action'=>'resetParentChild']); ?>",
            type : 'post',
            data : {parentId:parentId, partIds:partIds},
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('#delErrorMsg').html('<span style="color:green;">'+obj.message+'</span>');
                    setTimeout(function() {
                        location.reload(true);
                    }, 1000);
                } else {
                    $('#delErrorMsg').html('<span style="color:red;">'+obj.message+'</span>').hide(5000);
                }                    
            }
        });
    });

    //Download print of part details
    $('.partDetailPrint').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();

        var partid = $(this).data('part_id');
        var planeid = $(this).data('plane_id');
        
        $.ajax({
            type: "POST",
            url: "<?php echo $this->Url->build(['controller'=>'Reports', 'action'=>'printPartDetailPdf']); ?>",
            data: {planeid:planeid, partid:partid},
            /*beforeSend: function () {
                $('.loader').show();
            },*/
            success:function(response) {
                var obj = JSON.parse(response);
                if(obj.status == 'success') {
                    $('.loader').hide();
                    window.open(obj.data);
                } else {
                    $('.loader').hide();
                    alert('Some error occured. Please try again!');
                }
            },
            error : function() {
                $('.loader').hide();
                alert('Some error occured. Please try again!');
            },
            complete: function () {
                $('.loader').hide();
            }
        });
    });
    
});    
</script>